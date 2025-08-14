<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Diagnosi;
use App\Models\Visit;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DiagnosisController extends Controller
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
            $diagnosis = Diagnosi::where('visit_id', 'LIKE', "%$keyword%")
                ->orWhere('disease', 'LIKE', "%$keyword%")
                ->orWhere('other_disease', 'LIKE', "%$keyword%")
                ->orWhere('term_syphilis', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $diagnosis = Diagnosi::latest()->paginate($perPage);
        }

        return view('diagnosis.index', compact('diagnosis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('diagnosis.create');
    }

    // public function add()
    // {
    //     return view('diagnosis.add');
    // }

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
    
    // บันทึกข้อมูลในฐานข้อมูล
    Diagnosi::create($requestData);

    // ดึงค่า visit_id และ page จาก Request
    $visitId = $request->input('visit_id');
    $page = $request->input('page', 1); // ถ้าไม่มี page จะใช้ค่า default เป็น 1

    // แสดงข้อความแจ้งเตือน
    Alert::success('Diagnosis added successfully!');

    // Redirect ไปที่หน้า Visit
    return redirect()->route('visit.edit', ['visit' => $visitId, 'page' => $page])
                     ->with('success', 'Diagnosis added successfully.');
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
        $diagnosi = Diagnosi::findOrFail($id);

        return view('diagnosis.show', compact('diagnosi'));
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
        $diagnosi = Diagnosi::findOrFail($id);

        return view('diagnosis.edit', compact('diagnosi'));
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
    
    // ค้นหาข้อมูลที่ต้องการอัปเดต
    $diagnosi = Diagnosi::findOrFail($id);
    $diagnosi->update($requestData);

    // ดึงค่า visit_id และ page จาก Request
    $visitId = $request->input('visit_id');
    $page = $request->input('page', 1); // ถ้าไม่มี page จะใช้ค่า default เป็น 1

    // แสดงข้อความแจ้งเตือน
    Alert::success('Diagnosis updated successfully!');

    // Redirect กลับไปที่หน้า Visit
    return redirect()->route('visit.edit', ['visit' => $visitId, 'page' => $page])
                     ->with('success', 'Diagnosis updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request,$id)
    {
        $diagnosi = Diagnosi::findOrFail($id);
        $visit_id = $diagnosi->visit_id;
        Diagnosi::destroy($id);
        $requestData = $request->all();
        // return Visit::next($requestData['page'], $visit_id);
        return redirect()->route('visit.edit', ['visit' => $visit_id, 'page' => $requestData['page'] + 1])->with('success', 'Diagnosis deleted successfully.');

    }
}
