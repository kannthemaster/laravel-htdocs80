<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Exports\LabExport;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use App\Models\Lab;
use App\Models\LabItem;
use App\Models\LabSub;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Schema;
class LabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

public function index(Request $request)
{
    $requestData = $request->all();
    $keyword = $request->get('search');
    $status = $request->get('status');
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    $perPage = 25;

    // เริ่มต้น query
    $query = Lab::with('labSub2');

    // ถ้าค้นหาด้วย keyword
    if (!empty($keyword)) {
        $query->where(function ($q) use ($keyword) {
            $q->where('LN', 'LIKE', "%$keyword%")
              ->orWhereHas('patient', function ($q) use ($keyword) {
                  $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('surname', 'LIKE', "%$keyword%")
                    ->orWhere('code', 'LIKE', "%$keyword%");
              });
        });
    }

if ($status === "all") {
    // ถ้าเลือก "ทั้งหมด" แสดงทุกสถานะ (รวม 2)
    // ไม่ต้องกรองอะไรเลย
} elseif ($status) {
    // ถ้ามีการเลือกสถานะที่เจาะจง กรองตามค่านั้น
    $query->where('status', $status);
} else {
    // ถ้าไม่เลือกสถานะเลย ให้ซ่อน status = 2
    $query->where('status', '!=', 2);
}


    // กรองตามวันที่
    if ($startDate && $endDate) {
        $query->whereBetween('collected_date', [$startDate, $endDate]);
    }

    // ดึงข้อมูลที่กรองแล้ว
    $lab = $query->latest()->paginate($perPage);

    return view('lab.index', compact('lab'));
}






    public function room(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $lab = Lab::where('patient_id', 'LIKE', "%$keyword%")
                ->orWhere('visit_id', 'LIKE', "%$keyword%")
                ->orWhere('report_by', 'LIKE', "%$keyword%")
                ->orWhere('approve_by', 'LIKE', "%$keyword%")
                ->orWhere('collected_date', 'LIKE', "%$keyword%")
                ->orWhere('report_date', 'LIKE', "%$keyword%")
                ->orWhere('LN', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $lab = Lab::latest()->paginate($perPage);
        }

        return view('lab.index', compact('lab'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('lab.create');
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
    $requestData = $request->all();                 // รับค่าจากฟอร์มทั้งหมด
    $requestData = Lab::format2DB($requestData);    // แปลง format วันที่/เวลา จาก d/m/Y → Y-m-d (ค.ศ.)
    Lab::create($requestData);                      // บันทึกลง DB

    return redirect(route('visit.edit', $requestData['visit_id']))
           ->with('flash_message', 'Lab added!');
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $lab = Lab::findOrFail($id);

        return view('lab.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
public function edit($id, Request $request)
{
    $lab = Lab::findOrFail($id);
    $lab->formatDBBack();

    // ดึงข้อมูล patient ผ่าน visit ของ lab
    $patient = $lab->visit ? $lab->visit->patient() : null;

    // สร้าง URL สำหรับปุ่ม Back
    $page = $request->input('page', 1); // ค่า default ของ page คือ 1
    $backUrl = $lab->visit ? url("/visit/{$lab->visit->id}/edit?page={$page}") : null;

    return view('lab.edit', compact('lab', 'patient', 'backUrl'));
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
        // dd($requestData);
        $lab = Lab::findOrFail($id);
        $requestData = Lab::format2DB($requestData);
        $lab->update($requestData);
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
    public function destroy($id)
    {

        $lab = Lab::findOrFail($id);
        $visit_id = $lab->visit_id;

        DB::beginTransaction();
        try {
            LabItem::where('lab_id', $id)->delete();
            LabSub::where('lab_id', $id)->delete();
            Lab::destroy($id);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
        // return redirect('backend/lab')->with('flash_message', 'Lab deleted!');
        return redirect(route('visit.edit', $visit_id))->with('flash_message', 'Lab deleted!');
    }
    public function printLabs(Request $request)
{
    $labIds = $request->input('labIds');
    $labs = LabSub::whereIn('id', $labIds)->get();

    return view('lab-sub.print', compact('labs'));
}

    
    public function updateAllSub(Request $request, $id)
    {
        $requestData = $request->all();

        foreach($requestData['result'] as $sub_id => $result){
            $labSub = LabSub::findOrFail($sub_id);
            $labSub->result = $result;
            $labSub->save();
            
        }
        flash('Lab updated!', 'success'); // Flash message (ตัวเลือกเสริม)
        Alert::success('Lab updated!');  // Alert message (ตัวเลือกเสริม)

        return back()->with('flash_message', 'Lab updated!');
    }
    public function export(Request $request)
{
    $query = Lab::with(['labSub2', 'patient']);  // ดึงข้อมูลที่ต้องการ

    // กรองเงื่อนไขต่างๆ ตามที่มีใน URL query string
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('LN', 'LIKE', "%{$request->search}%")
              ->orWhereHas('patient', function ($q) use ($request) {
                  $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('surname', 'LIKE', "%{$request->search}%")
                    ->orWhere('code', 'LIKE', "%{$request->search}%");
              });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('collected_date', [$request->start_date, $request->end_date]);
    }

    $labs = $query->get();  // ดึงข้อมูลทั้งหมดที่กรองแล้ว

    return Excel::download(new LabExport($labs), 'lab_data.xlsx');  // ส่งออกเป็นไฟล์ Excel
}


    

}
