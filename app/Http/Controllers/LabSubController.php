<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Lab;
use App\Models\LabSub;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Patient;

class LabSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $labsub = LabSub::where('lab_id', 'LIKE', "%$keyword%")
                ->orWhere('method', 'LIKE', "%$keyword%")
                ->orWhere('specimen_from', 'LIKE', "%$keyword%")
                ->orWhere('result', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $labsub = LabSub::latest()->paginate($perPage);
        }

        return view('lab-sub.index', compact('labsub'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('lab-sub.create');
    }

    /**
     * Show the form for add a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function add()
    {
        return view('lab-sub.add');
    }
    // public function config()
    // {
    //     return view('lab-sub.config.main');
    // }
    public function config(Request $request)
{
    $visitId = $request->input('visit');
    $page = $request->input('page', 1); // กำหนดค่า default ของ page เป็น 1
    $lab = Lab::where('visit_id', $visitId)->first(); // ดึง lab จาก visit_id

    // ดึงข้อมูล visit และ patient พร้อมกับ sex
    $visit = Visit::where('id', $visitId)->first();
    $sex = $visit->patient2->sex ?? null; // ดึงค่า sex จาก patient

    // ดึงข้อมูลอื่นๆ ที่เกี่ยวข้อง
    $labSubConfig = $lab ? $lab->labSubConfig() : [];

    // สร้าง URL สำหรับปุ่ม Back
    $backUrl = url("/visit/{$visitId}/edit?page={$page}");

    return view('lab-sub.config.main', compact('lab', 'labSubConfig', 'backUrl', 'sex'));
}




    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        if (array_key_exists('specimen_from', $requestData) && is_array($requestData['specimen_from'])) {
            foreach ($requestData['specimen_from'] as $specimen_from) {
                $labSub = LabSub::create([
                    'lab_id' => $requestData['lab_id'], 
                    'method' => $requestData['method'], 
                    'method_other' => $requestData['method_other'], 
                    'specimen_from' => $specimen_from, 
                    'specimen_from_other' => $requestData['specimen_from_other']
                ]);

        $lab = Lab::findOrFail($id);
        $lab->formatDBBack();
        
    
            }
        } else {
            $labSub = LabSub::create($requestData);
        }

        flash('LabSub saved!', 'success');
        Alert::success('LabSub updated!');
        echo '<script type="text/javascript">'
               , 'history.go(-2);'
               , '</script>';
        // return Visit::next($requestData['page'], $visit_id);
        

        // $visit_id = Lab::find($requestData['lab_id'])->visit()->id;
        // switch ($requestData['page']) {
        //     case 2:
        //         return redirect(route('visit.edit', $visit_id))->with('flash_message', 'LabSub updated!');
        //         break;
        //     case 4:
        //         return redirect(route('visit.lab', $visit_id))->with('flash_message', 'LabSub updated!');
        //         break;
        // }


        // return redirect(route('visit.edit', $visit_id))->with('flash_message', 'LabSub added!');

        // return redirect(route('visit.edit',$labSub->visit()->id ))->with('flash_message', 'LabSub added!');
    }

    public function saveConfig(Request $request)
{
    $requestData = $request->all();

    DB::beginTransaction();
    try {
        // ลบข้อมูลเก่าของ LabSub ที่เกี่ยวข้องกับ lab_id
        LabSub::where('lab_id', $requestData['lab_id'])->delete();

        // จัดการข้อมูลของ LabSub
        foreach ($requestData['item'] as $methodId => $itemMethod) {
            $method_other = "";
            foreach ($itemMethod as $specimenFromId => $value) {
                $specimen_from_other = "";
                if ($specimenFromId == 10) {
                    $specimen_from_other = $value['key'];
                    if ($specimen_from_other) {
                        LabSub::create([
                            'lab_id' => $requestData['lab_id'],
                            'method' => $methodId,
                            'method_other' => $method_other,
                            'specimen_from' => $specimenFromId,
                            'specimen_from_other' => $specimen_from_other,
                            'result' => $value['value']
                        ]);
                    }
                } else {
                    LabSub::create([
                        'lab_id' => $requestData['lab_id'],
                        'method' => $methodId,
                        'method_other' => $method_other,
                        'specimen_from' => $specimenFromId,
                        'specimen_from_other' => $specimen_from_other,
                        'result' => ($value != -1) ? $value : null,
                    ]);
                }
            }
        }

        // จัดการข้อมูล PCR
        if (array_key_exists('pcr', $requestData)) {
            foreach ($requestData['pcr'] as $specimenFromId => $value) {
                LabSub::create([
                    'lab_id' => $requestData['lab_id'],
                    'method' => 5,
                    'specimen_from' => $specimenFromId,
                ]);
            }
        }

        // จัดการข้อมูล Blood Test
        if (array_key_exists('blood', $requestData)) {
            foreach ($requestData['blood'] as $specimenFromId => $value) {
                LabSub::create([
                    'lab_id' => $requestData['lab_id'],
                    'method' => $specimenFromId,
                    'result' => ($value != -1) ? $value : null
                ]);
            }
        }

        // จัดการข้อมูล Other
        if ($requestData['other']) {
            LabSub::create([
                'lab_id' => $requestData['lab_id'],
                'method' => 10,
                'method_other' => $requestData['other'],
                'result' => $requestData['othervalue'],
            ]);
        }

        // // อัปเดตค่า Status ของ Lab
        // if (array_key_exists('status', $requestData)) {
        //     $lab = Lab::find($requestData['lab_id']);
        //     if ($lab) {
        //         $lab->status = $requestData['status'];
        //         $lab->save();
        //     }
        // }
        if (array_key_exists('status', $requestData)) {
    $lab = Lab::find($requestData['lab_id']);
    if ($lab) {
        $lab->status = $requestData['status'];
        
        // อัปเดตค่า collected_date ถ้ามีการส่งมา
        if (array_key_exists('collected_date', $requestData)) {
            $lab->collected_date = $requestData['collected_date']; // เพิ่มบรรทัดนี้
        }

        $lab->save();
    }
}

        DB::commit();
        flash('LabSub and Lab status saved!', 'success');
    } catch (\Exception $e) {
        DB::rollback();
        flash($e->getMessage(), 'danger');
        throw $e;
    }

    $visit_id = Lab::find($requestData['lab_id'])->visit_id;
    Alert::success('Lab updated!');

    return back()->with('flash_message', 'Lab updated!');
}

    public function show($id)
    {
        $labsub = LabSub::findOrFail($id);

        return view('lab-sub.show', compact('labsub'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $labsub = LabSub::findOrFail($id);

        return view('lab-sub.edit', compact('labsub'));
    }

    public function result($id)
    {
        $labsub = LabSub::findOrFail($id);

        return view('lab-sub.result', compact('labsub'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $requestData = $request->all();

        $labsub = LabSub::findOrFail($id);
        $labsub->update($requestData);
        //$lab = Lab::findOrFail($requestData['lab_id']);
        //$lab->update($requestData);

        // if (array_key_exists('submit', $requestData) && $requestData['submit'] == 'result') {
        //     return redirect(route('lab.edit', $labsub->lab_id))->with('flash_message', 'LabSub updated!');
        // }
        $visit_id = $labsub->visit()->id;

        // $visit_id = Lab::find($requestData['lab_id'])->visit()->id;
        // return redirect('backend/lab')->with('flash_message', 'Lab updated!');
        flash('Lab updated!', 'success'); // Flash message (ตัวเลือกเสริม)
    Alert::success('Lab updated!');  // Alert message (ตัวเลือกเสริม)

    return back()->with('flash_message', 'Lab updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, $id)
    {

        $labItem = LabSub::findOrFail($id);
        $visit = $labItem->visit();
        LabSub::destroy($id);

        $requestData = $request->all();
        $visit_id = $visit->id;
        flash('LabSub deleted!', 'success');
        return Visit::next($requestData['page'], $visit_id);

        // switch ($requestData['page']) {
        //     case 2:
        //         return redirect(route('visit.edit', $visit_id))->with('flash_message', 'LabSub deleted!');
        //         break;
        //     case 4:
        //         return redirect(route('visit.lab', $visit_id))->with('flash_message', 'LabSub deleted!');
        //         break;
        // }

        // return redirect(route('visit.edit',$visit->id ))->with('flash_message', 'LabSub deleted!');
    }
    public function print($id)
    {
        // ดึงข้อมูลเฉพาะที่เลือก
        $lab = LabSub::findOrFail($id); // ดึงข้อมูลจาก ID
        return view('lab-sub.print', compact('lab'));
    }
    public function showVisit($visitId)
{
    $visit = Visit::find($visitId);
    $patient = $visit->patient ?? Patient::find(request()->get('patient'));

    return view('lab-sub.table', compact('patient'));
}

public function showTable(Request $request)
{
    if (isset($request->visit)) {
        $patient = $request->visit->patient();
    } else {
        $patient = App\Models\Patient::find($request->input('patient'));
    }

    return view('lab-sub.table', compact('patient'));
}

}
