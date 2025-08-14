<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\ContactPerson;
use App\Models\Diagnosi;
use App\Models\Patient;
use App\Models\Visit;
use App\Utillity;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\VisitExport;
use Maatwebsite\Excel\Facades\Excel;


class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index0(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;


        if (!empty($keyword)) {
            $visit = Visit::where('patient_id', 'LIKE', "%$keyword%")
                ->orWhere('know_from', 'LIKE', "%$keyword%")
                ->orWhere('send_from', 'LIKE', "%$keyword%")
                ->orWhere('other_from', 'LIKE', "%$keyword%")
                ->orWhere('reason_sti', 'LIKE', "%$keyword%")
                ->orWhere('reason_sti_other', 'LIKE', "%$keyword%")
                ->orWhere('reason_vct', 'LIKE', "%$keyword%")
                ->orWhere('reason_vct_other', 'LIKE', "%$keyword%")
                ->orWhere('sti_hostory', 'LIKE', "%$keyword%")
                ->orWhere('contraceptive_method', 'LIKE', "%$keyword%")
                ->orWhere('LMP', 'LIKE', "%$keyword%")
                ->orWhere('symptom', 'LIKE', "%$keyword%")
                ->orWhere('diagnosis', 'LIKE', "%$keyword%")
                ->orWhere('term_syphilis', 'LIKE', "%$keyword%")
                ->orWhere('disease_state', 'LIKE', "%$keyword%")
                ->orWhere('treatment', 'LIKE', "%$keyword%")
                ->orWhere('disease_state_other', 'LIKE', "%$keyword%")
                ->orWhere('consultation', 'LIKE', "%$keyword%")
                ->orWhere('hiv_sti_test', 'LIKE', "%$keyword%")
                ->orWhere('hiv_sti_test_date', 'LIKE', "%$keyword%")
                ->orWhere('hiv_sti_test_resule', 'LIKE', "%$keyword%")
                ->orWhere('touch_tracing', 'LIKE', "%$keyword%")
                ->orWhere('touch_tracing_fail', 'LIKE', "%$keyword%")
                ->orWhere('provide_condom_site', 'LIKE', "%$keyword%")
                ->orWhere('provide_condom_quantity', 'LIKE', "%$keyword%")
                ->orWhere('provide_lubricant_quantity', 'LIKE', "%$keyword%")
                ->orWhere('appointment', 'LIKE', "%$keyword%")
                ->orWhere('appointment_reason', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $visit = Visit::latest()->paginate($perPage);
        }

        return view('visit.index', compact('visit'));
    }

    public function index(Request $request)
{
    $keyword = $request->get('search');
    $perPage = 500;
    $query = Visit::with('patient2'); // โหลดข้อมูลความสัมพันธ์

    if (!empty($keyword)) {
        // กำลังกรองข้อมูล (ถูกคอมเมนต์ไว้)
    }

    $sex = $request->get('sex');
    if (!empty($sex)) {
        $patient_ids = Patient::where('sex', $sex)->pluck('id');
        $query = $query->whereIn('patient_id', $patient_ids);
    }

    $start_age = $request->get('start_age');
    $end_age = $request->get('end_age');
    if (!empty($start_age) || !empty($end_age)) {
        $queryPatient  = Patient::query();
        if (!empty($start_age)) {
            $year = Utillity::age2BirthBE($start_age);
            $queryPatient = $queryPatient->where('birth_date', '<=', $year . "-01-01");
        }
        if (!empty($end_age)) {
            $year = Utillity::age2BirthBE($end_age);
            $queryPatient = $queryPatient->where('birth_date', '>=', $year . "-01-01");
        }
        $patient_ids = $queryPatient->pluck('id');
        $query = $query->whereIn('patient_id', $patient_ids);
    }

    $start = $request->get('start');
    if (!empty($start)) {
        $start = Utillity::th2dbDate($start);
        $query = $query->where('date', '>=', $start);
    }
    $end = $request->get('end');
    if (!empty($end)) {
        $end = Utillity::th2dbDate($end);
        $query = $query->where('date', '<=', $end);
    }

    $status = $request->get('status');
    if (!empty($status)) {
        $patient_ids = Patient::where('status', $status)->pluck('id');
        $query = $query->whereIn('patient_id', $patient_ids);
    }

    $appointment = $request->get('appointment');
    if (!empty($appointment)) {
        $appointment = Utillity::th2dbDate($appointment);
        $query = $query->where('appointment', '=', $appointment);
    }

    $disease = $request->get('disease');
    if (!empty($disease)) {
        $visit_ids = Diagnosi::where('disease', $disease)->pluck('visit_id');
        $query = $query->whereIn('id', $visit_ids);
    }

    $diseaseState = $request->get('diseaseState');
    if (!empty($diseaseState)) {
        $visit_ids = Diagnosi::where('disease_state', $diseaseState)->pluck('visit_id');
        $query = $query->whereIn('id', $visit_ids);
    }

    $visits = $query->paginate($perPage);

    return view('visit.index', compact('visits')); // เปลี่ยนจาก 'visit' เป็น 'visits'
}


    public function room(Request $request, $status)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        $visit = Visit::where('status', $status)->paginate($perPage);
        return view('visit.room', compact('visit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $requestData = $request->all();
        $visit = Visit::create($requestData);
        return redirect(route('visit.edit', $visit->id))->with('flash_message', 'Visit added!');
        return view('visit.create');
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
        $requestData = Visit::format2DB($requestData);
        $requestData2 = $request->all();
        $requestData2 = Patient::format2DB($requestData2);
        $visit = Visit::create($requestData);
        flash('Visit added!', 'success');
        return redirect(route('visit.edit', $visit->id))->with('flash_message', 'Visit added!');
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
        $visit = Visit::findOrFail($id);

        return view('visit.show', compact('visit'));
    }


    public function medicine($id)
    {
        $visit = Visit::findOrFail($id);

        return view('visit.medicine', compact('visit'));
    }

    public function lab($id)
    {
        $visit = Visit::findOrFail($id);

        return view('visit.lab', compact('visit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
public function edit(Request $request, $id)
{
    $visit = Visit::findOrFail($id);
    $visit->formatDBBack();

    $hasMethod = $visit->latestLabMethod()->exists();

    $page = $request->input('page', 1); // ✅ รองรับ page

    return view('visit.edit', compact('visit', 'hasMethod', 'page'));
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
    $visit = Visit::findOrFail($id);
    $requestData = $request->all();

    // แปลง body_check_marks (ถ้ามี)
    if (!empty($requestData['body_check_marks'])) {
        $marks = json_decode($requestData['body_check_marks'], true);
        $validMarks = collect($marks)->filter(function ($mark) {
            if (!isset($mark['type'])) return false;
            if ($mark['type'] === 'point') return isset($mark['x'], $mark['y']);
            if ($mark['type'] === 'path') return isset($mark['points']) && is_array($mark['points']) && count($mark['points']) > 1;
            return false;
        })->values()->all();

        $requestData['body_check_marks'] = $validMarks;
    }

    $requestData = Visit::format2DB($requestData);
    $visit->update($requestData);

    // อัปเดต patient2 ฟิลด์ที่แก้ได้
    $patient = $visit->patient2;

if (
    $patient->congenitaldisease !== $requestData['congenitaldisease'] ||
    $patient->drug_allergy !== $requestData['drug_allergy'] ||
    $patient->status !== $requestData['patient_status'] ||
    $patient->tel !== $requestData['tel'] ||
    $patient->occupation !== $requestData['occupation'] ||
    $patient->other_occupation !== $requestData['other_occupation'] ||
    $patient->organization !== $requestData['organization'] 
) {
    $patient->congenitaldisease = $requestData['congenitaldisease'];
    $patient->drug_allergy = $requestData['drug_allergy'];
    $patient->status = $requestData['patient_status'];
    $patient->tel = $requestData['tel'];
    $patient->occupation = $requestData['occupation'];
    $patient->other_occupation = $requestData['other_occupation'];
    $patient->organization = $requestData['organization'];
    $patient->save();
}


    flash('Visit updated!', 'success');
    Alert::success('Visit updated!');
    return back();
}


    public function changeStatus(Request $request, $room)
    {

        $requestData = $request->all();
        $visit = Visit::findOrFail($requestData['id']);
        $visit->status = $requestData['status'];
        $visit->save();
        flash('Visit updated!', 'success');

        return redirect(route('visit.room', $room));
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
        $requestData = $request->all();
        $visit = Visit::find($id);
        Visit::destroy($id);

        // return redirect('visit')->with('flash_message', 'Visit deleted!');
        return Visit::next($requestData['page'], $visit->patient_id);
    }

    public function addContactPerson(Request $request, $id)
    {

        $requestData = $request->all();

        // $visit = Visit::findOrFail($id);
        // $visit->update($requestData);
        ContactPerson::create($requestData);
        return redirect(route('visit.edit', $id))->with('flash_message', 'Visit updated!');
        
    }


    public function updateStatus(Request $request, $id)
    {

        $requestData = $request->all();
        $visit = Visit::findOrFail($id);
        $requestData = Visit::format2DB($requestData);
        $visit->update($requestData);
        flash('Visit updated!', 'success');
        return redirect(route('visit.edit', $visit->id))->with('flash_message', 'Visit updated!');
    }

    public function appointment(Request $request)
{
    $start_date = $request->get('start'); // รับวันที่เริ่มต้น
    $end_date = $request->get('end'); // รับวันที่สิ้นสุด
    $hn = $request->get('hn'); // รับ HN
    $reason = $request->get('reason'); // รับ appointment_reason
    $perPage = 25;

    // $query = Visit::query()->with('patient2'); // ใช้ with() สำหรับ Join ข้อมูลจากตาราง patients
    $query = Visit::query()->with(['patient2', 'diagnoses', 'followups']); // เพิ่ม 'followups' เข้ามา

    // ค้นหาด้วย HN
    if (!empty($hn)) {
        $query->whereHas('patient2', function ($q) use ($hn) {
        $q->where('code', 'LIKE', "%$hn%");
        });

    }

    // ค้นหาด้วย appointment_reason
    if (!empty($reason)) {
        $query->where('appointment_reason', 'LIKE', "%$reason%");
    }

    // ค้นหาด้วยวันที่
    if ($start_date && $end_date) {
        $start_date = Utillity::th2dbDate($start_date);
        $end_date = Utillity::th2dbDate($end_date);
        $query->whereBetween('appointment', [$start_date, $end_date]);
    } elseif ($start_date) {
        $start_date = Utillity::th2dbDate($start_date);
        $query->where('appointment', '>=', $start_date);
    } else {
        $date = date('Y-m-d');
        $query->where('appointment', '>=', $date);
    }

    $query->orderBy('appointment', 'asc'); // เรียงลำดับการนัดหมาย
    $totalVisits = $query->count();  // นับจำนวนทั้งหมด
    $visit = $query->paginate($perPage);
     // นับจำนวนผู้ป่วยที่ตรงกับเงื่อนไขการค้นหา
    
    // เพิ่มวันที่มาถัดไป
    $visit->getCollection()->transform(function ($item) {
    $nextVisit = Visit::where('patient_id', $item->patient_id)
                      ->where('date', '>', $item->date)
                      ->orderBy('date', 'desc')
                      ->first();

    // คำนวณวันที่มาถัดไป
    $item->next_visit = $nextVisit ? $nextVisit->date : null;

    // คำนวณจำนวนการติดตามที่สำเร็จ
    $followUpCount = $item->followups->count();
    $item->follow_up_count = $followUpCount;
    
    // หากติดตามครบตามเป้าหมาย (กำหนดเองในกรณีนี้ว่าคือ 3 ครั้ง) ให้แสดงสถานะว่า "สำเร็จ"
    $item->follow_up_status = $followUpCount >= 3 ? 'สำเร็จ' : 'ยังไม่ครบ';

    return $item;
});


    return view('visit.appointment', compact('visit', 'totalVisits'));
}



    // Controller
    public function patient2()
    {
    $visits = Visit::with('patient2')
        ->where('patient_id', $visit->patient_id)
        ->where('id', '<>', $visit->id)
        ->orderBy('id', 'asc')
        ->get();

    return view('lab-sub.table', compact('visits'));

    
}

public function export(Request $request)
{
    // การค้นหาตามเงื่อนไข (ใช้คำสั่งเดียวกับใน method `appointment`)
    $query = Visit::query();

    if ($request->has('hn') && $request->hn != '') {
        $query->whereHas('patient', function($q) use ($request) {
            $q->where('code', 'like', '%' . $request->hn . '%');
        });
    }

    if ($request->has('reason') && $request->reason != '') {
        $query->where('appointment_reason', $request->reason);
    }

    if ($request->has('start') && $request->start != '') {
        $query->where('appointment', '>=', \Carbon\Carbon::parse($request->start)->startOfDay());
    }

    if ($request->has('end') && $request->end != '') {
        $query->where('appointment', '<=', \Carbon\Carbon::parse($request->end)->endOfDay());
    }

    // Export ข้อมูลทั้งหมด
    return Excel::download(new VisitExport, 'visits.xlsx');
}

}
