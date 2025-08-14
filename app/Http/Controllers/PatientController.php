<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Country;
use App\Models\Lab;
use App\Models\Patient;
use App\Models\Visit;
use App\Utillity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Address;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
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
        $query = Patient::query();   

        if (!empty($keyword)) {
            $patient = $query->where('code', 'LIKE', "%$keyword%")
                ->orWhere('id', 'LIKE', "%$keyword%")
                ->orWhere('prefix', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->orWhere('surname', 'LIKE', "%$keyword%")
                ->orWhere('first_visit', 'LIKE', "%$keyword%")
                ->orWhere('id_card_number', 'LIKE', "%$keyword%")
                ->orWhere('birth_date', 'LIKE', "%$keyword%");
        }
        $sex = $request->get('sex');
        if (!empty($sex)) {
            $query = $query->where('sex', $sex);
        }
        $start_age = $request->get('start_age');
        if (!empty($start_age)) {
            $year = Utillity::age2BirthBE($start_age);
            $query = $query->where('birth_date', '<=',$year."-01-01");
        }
        $end_age = $request->get('end_age');
        if (!empty($end_age)) {
            $year = Utillity::age2BirthBE($end_age);
            $query = $query->where('birth_date', '>=',$year."-01-01");
        }
        

        // $start = $request->get('start');
        // if(!empty($start) || !empty($end)){
        //     $queryVisit  = Visit::query(); 
        //     if (!empty($start)) {
        //         $start = Utillity::th2dbDate($start);
        //         $queryVisit = $queryVisit->where('date', '<=',$start);
        //     }
        //     $end = $request->get('end');
        //     if (!empty($end)) {
        //         $end = Utillity::th2dbDate($end);
        //         $queryVisit = $queryVisit->where('date', '>=',$end);
        //     }
        //     $patient_id = $queryVisit->pluck('patient_id');
        //     $query = $query->whereIn('id',$patient_id);
        // }

      
        

        $patient = $query->latest()->paginate($perPage);

        return view('patient.index', compact('patient'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    // public function create()
    // {
    //     return view('patient.create');
    // }
       public function create()
{
    $countries = Country::select('country_id', 'nation_name_en', 'nation_name_th')->get();
    return view('patient.create', compact('countries'));
}


    public function createVisit(Request $request)
    {

        $requestData = $request->all();
        $requestData = Visit::format2DB($requestData);
        $visit= Visit::create($requestData);
        $visit= Lab::create(['patient_id'=>$visit->patient_id, 'visit_id'=>$visit->id]);
        $patientId = $visit->patient_id;
        $patient = Patient::find($patientId);
        flash('Visit added!','success');
        Alert::success('Visit added!');
        return redirect(route('patient.index', 'search=' .$patient->code));

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
    DB::beginTransaction();

    try {
        // ตรวจสอบเลขบัตรประชาชนซ้ำ
        if (Patient::where('id_card_number', $request->id_card_number)->exists()) {
            return redirect()->back()->withErrors(['id_card_number' => 'เลขบัตรประชาชนนี้มีในระบบแล้ว'])->withInput();
        }

        // แปลงข้อมูลก่อนบันทึก
        $requestData = $request->all();
        $requestData = Patient::format2DB($requestData);

        // ถ้ามี HN ซ้ำ ให้สร้างใหม่
        $existingPatient = Patient::getByNH($requestData['code']);
        if ($existingPatient) {
            $requestData['code'] = Patient::calNH();
        }

        // แปลงค่า checkbox phone_changed
        $requestData['phone_changed'] = $request->has('phone_changed') ? 1 : 0;

        // อัปโหลดรูปภาพ ถ้ามี
        if ($request->hasFile('photo')) {
    $file = $request->file('photo');
    $contents = file_get_contents($file->getRealPath());

    // เข้ารหัสเนื้อหาไฟล์
    $encrypted = Crypt::encrypt($contents);

    // สร้างชื่อไฟล์สุ่ม
    $filename = uniqid('photo_') . '.enc';

    // บันทึกไฟล์ที่เข้ารหัสในโฟลเดอร์ private
    Storage::disk('local')->put("private/photos/$filename", $encrypted);

    // บันทึกชื่อไฟล์ที่เข้ารหัสลงฐานข้อมูล
    $requestData['photo'] = $filename;
}

        // บันทึกผู้ป่วย
        $createdPatient = Patient::create($requestData);

        // บันทึกที่อยู่
        $addressData = $request->only([
            'house_no', 'address', 'province', 'district', 'sub_district', 'zipcode',
        ]);
        $addressData['patient_id'] = $createdPatient->id;

        Address::create($addressData);

        DB::commit();
        flash('เพิ่มข้อมูลผู้ป่วยเรียบร้อยแล้ว', 'success');
        return redirect('patient');
    } catch (\Exception $e) {
        DB::rollBack();
        flash('เกิดข้อผิดพลาด: ' . $e->getMessage(), 'danger');
        return redirect()->back()->withInput();
    }
}

public function deletePhoto($id)
{
    $patient = Patient::findOrFail($id);

    // ไฟล์ถูกเก็บใน local disk (storage/app/private/photos)
    $photoPath = "private/photos/{$patient->photo}";

    if ($patient->photo && \Storage::disk('local')->exists($photoPath)) {
        \Storage::disk('local')->delete($photoPath);
    }

    $patient->photo = null;
    $patient->save();

    flash('ลบรูปภาพผู้ป่วยเรียบร้อยแล้ว', 'success');
    return back();
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
        $patient = Patient::findOrFail($id);

        return view('patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    // public function edit($id)
    // {
    //     $patient = Patient::findOrFail($id);
    //     $patient->formatDBBack();
    //     return view('patient.edit', compact('patient'));
    // }

    public function edit($id)
{
    $patient = Patient::findOrFail($id);
    $patient->formatDBBack();
    $countries = Country::select('country_id', 'nation_name_en')->get();

    // ดึงวันที่ล่าสุดของ HBs Ag (method = 11)
    $latestHBsAgDate = \DB::table('lab_subs')
        ->join('labs', 'lab_subs.lab_id', '=', 'labs.id')
        ->where('lab_subs.method', 11) // ตรวจสอบ HBs Ag
        ->whereIn('labs.visit_id', $patient->visits()->pluck('id')) // ใช้ visit_id ของผู้ป่วย
        ->orderByDesc('lab_subs.created_at') // เรียงวันที่จากล่าสุด
        ->value('lab_subs.created_at'); // ดึงแค่วันที่ล่าสุด

    // ดึงวันที่ล่าสุดของ Anti-HCV (method = 12)
    $latestAntiHCVDate = \DB::table('lab_subs')
        ->join('labs', 'lab_subs.lab_id', '=', 'labs.id')
        ->where('lab_subs.method', 12) // ตรวจสอบ Anti-HCV
        ->whereIn('labs.visit_id', $patient->visits()->pluck('id')) // ใช้ visit_id ของผู้ป่วย
        ->orderByDesc('lab_subs.created_at') // เรียงวันที่จากล่าสุด
        ->value('lab_subs.created_at'); // ดึงแค่วันที่ล่าสุด

    // ใช้ Carbon เพื่อแปลงวันที่เป็นแค่วันที่ (ไม่มีเวลา)
    $latestHBsAgDate = $latestHBsAgDate ? \Carbon\Carbon::parse($latestHBsAgDate)->format('d-m-Y') : null;
    $latestAntiHCVDate = $latestAntiHCVDate ? \Carbon\Carbon::parse($latestAntiHCVDate)->format('d-m-Y') : null;

    return view('patient.edit', compact('patient', 'countries', 'latestHBsAgDate', 'latestAntiHCVDate'));
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
    $patient = Patient::findOrFail($id);

    $requestData = $request->all();
    $requestData = Patient::format2DB($requestData);

    // จัดการรูปภาพ ถ้ามีไฟล์ใหม่อัปโหลด
    if ($request->hasFile('photo')) {
    $file = $request->file('photo');
    $contents = file_get_contents($file->getRealPath());

    // เข้ารหัสเนื้อหาไฟล์
    $encrypted = Crypt::encrypt($contents);

    // สร้างชื่อไฟล์สุ่ม
    $filename = uniqid('photo_') . '.enc';

    // บันทึกไฟล์ที่เข้ารหัสในโฟลเดอร์ private
    Storage::disk('local')->put("private/photos/$filename", $encrypted);

    // บันทึกชื่อไฟล์ที่เข้ารหัสลงฐานข้อมูล
    $requestData['photo'] = $filename;
}

    $patient->update($requestData);

    flash('Patient updated!', 'success');
    Alert::success('Patient updated!');

    return back();
}

public function showPhoto($id)
{
    $patient = Patient::findOrFail($id);

    if (!$patient->photo || !Storage::disk('local')->exists("private/photos/{$patient->photo}")) {
        abort(404);
    }

    $encrypted = Storage::disk('local')->get("private/photos/{$patient->photo}");
    $decrypted = Crypt::decrypt($encrypted);

    return response($decrypted)->header('Content-Type', 'image/jpeg');
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
        Patient::destroy($id);
        flash('Patient deleted!','success');
        Alert::success('Patient deleted!');
        return redirect('patient');
    }
    public function searchCountries(Request $request)
{
    $query = $request->get('query');
    
    // ดึง country_id และ nation_name_th อย่างชัดเจน
     $countries = Country::select('country_id', 'nation_name_en', 'nation_name_th')->get(); // เพิ่ม 'nation_name_th'
    return view('patient.create', compact('countries'));}

    public function updatePhoneChanged(Request $request, $id)
{
    $patient = Patient::findOrFail($id);
    $patient->phone_changed = $request->input('phone_changed');
    $patient->save();

    return redirect()->route('patient.edit', $id)->with('success', 'ข้อมูลได้อัปเดตแล้ว');
}
    public function getPatientDetails($id)
{
    $patient = Patient::findOrFail($id);

    // ดึงข้อมูลที่อยู่
    $address = $patient->address ? $patient->address->fullAddress() : '-';
    
    // ส่งข้อมูลผู้ป่วยกลับในรูปแบบ JSON
    return response()->json([
        'patient_code' => $patient->code,
        'name' => $patient->name . ' ' . $patient->surname,
        'age' => $patient->age(),
        'sex' => $patient->sex,
        'date_of_birth' => $patient->birth_date,
        'address' => $address,
        'phone' => $patient->tel,
        'email' => $patient->email ?? '-',
        'nationality' => $patient->nationality,
        'education' => Patient::$educationOption[$patient->education],
        'marital_status' => Patient::$maritalStatusOption[$patient->marital_status],
        'occupation' => Patient::$occupationOption[$patient->occupation],
        'underlying_disease' => $patient->congenitaldisease,
        'allergy' => $patient->drug_allergy,
        'organization' => $patient->organization,
    ]);
}
public function checkIdCard(Request $request)
{
    $idCard = $request->input('id_card_number');
    $exists = Patient::where('id_card_number', $idCard)->exists();

    return response()->json(['exists' => $exists]);
}



}
