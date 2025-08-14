<?php

namespace App\Models;

use App\Utillity;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class Patient extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patients';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'prefix', 'name', 'surname', 'sex', 'first_visit', 'id_card_number', 'birth_date', 'address_id','tel'
    ,'nationality','education','marital_status','status','occupation','other_occupation','organization' ,'congenitaldisease','drug_allergy','photo','phone_changed'];

    public static $prefixOption=[""=>'',1=>'นาย', 2=>'นาง', 3=>'นางสาว', 4=>'MR.', 5=>'MRS.', 6=>'MISS', 7=>'MS.'];
    public static $sexOption=[""=>'',1=>'ช', 2=>'ญ'];
    public static $cpsexOption=[""=>'',1=>'ชาย', 2=>'หญิง'];
    public static $educationOption=[""=>'',1=>'ต่ำกว่าประถมศึกษา', 2=>'ประถมศึกษา', 3=>'ม.ต้น', 4=>'ม.ปลาย', 8=>'ปวช', 9=>'ปวส' , 5=>'ป.ตรี', 6=>'ป.โท', 7=>'ป.เอก'];

    
    public static $maritalStatusOption=[""=>'',1=>'โสด', 2=>'หม้าย', 3=>'หย่า', 4=>'สมรส'];
    public static $statusOption=[""=>'',1=>'MSM/MSW/TG', 2=>'พนักงานบริการ', 3=>'แรงงานข้ามชาติ', 4=>'ผู้ป่วยเรือนจำ', 5=>'เยาวชน', 6=>'ประชาชนทั่วไป', 7=>'ผู้ใช้สารเสพติด'];
    public static $occupationOption=[""=>'',1=>'เกษตรกรรม', 2=>'รับราชการ/รัฐวิสาหกิจ', 3=>'รับจ้าง/กรรมกร', 4=>'ค้าขาย/ธุรกิจ', 5=>'งานบ้าน', 6=>'นักเรียน/นักศึกษา',
    7=>'ทหาร/ตำรวจ', 9=>'บุคลากรสาธารณสุข', 10=>'อาชีพพิเศษ', 11=>'อื่น ๆ'
    ];
    
    public  function address(){
        return Address::where('patient_id',$this->id)->first();
    }
    public function address2()
{
    return $this->hasOne(Address::class, 'patient_id');
}

    public  function visits(){
        return Visit::where('patient_id',$this->id)->get();
    }
    // ใน App\Models\Patient.php
public function visits2()
{
    return $this->hasMany(Visit::class, 'patient_id', 'id'); // ความสัมพันธ์กับ Visit
}



public static function calNH() {
    $yearFull = date('Y') + 543;
    
    if (date('m') > 9) {   
        $yearFull = $yearFull + 1;
    }

    $year2 = substr($yearFull, 2, 2); // ได้ค่าเช่น "68", "69"

    // ค้นหารหัสล่าสุดที่เป็น 9 หลัก
    $lastPatient = Patient::whereRaw("LENGTH(code) = 9") // เฉพาะรหัสใหม่
        ->where('code', 'like', $year2 . "%")
        ->orderBy('code', 'DESC')
        ->first();

    $lastCodeCount = 0;

    if ($lastPatient) {
        $lastCodeCount = (int) substr($lastPatient->code, 2);
    }

    // เพิ่มเลขลำดับ +1 และกำหนดให้เป็น 9 หลัก
    $result = $year2 . sprintf("%07d", $lastCodeCount + 1);

    return $result;
}

    public static function getByNH($code){
        $patient =  Patient::where('code',$code)->first();
        return $patient;
    }

    public static function format2DB($requestData){
        $requestData['birth_date'] = Utillity::th2dbDate($requestData['birth_date']) ;
        $requestData['first_visit'] = Utillity::th2dbDate($requestData['first_visit']) ;
        return $requestData;
    }

    public  function formatDBBack(){
        $this->birth_date = Utillity::db2thDate($this->birth_date) ;
        $this->first_visit = Utillity::db2thDate($this->first_visit) ;
        // return $requestData;
    }
    public  function age(){

        $today = date("Y-m-d");
        $diff = date_diff(date_create(Utillity::BE2AD($this->birth_date) ), date_create($today));
        return ($diff->y ). "y" ;
        // echo 'Your age is '.$diff->format('%y');  . $diff->m. "M- " . $diff->d. "D "

        // return $requestData;
    }

    public function calculateAge($birthDate) {
    // แปลงวันที่จาก พ.ศ. เป็น ค.ศ.
    $birthYear = $birthDate->year + 543;

    // หาความแตกต่างของปี
    $ageYears = date('Y') - $birthYear;

    // หาความแตกต่างของเดือน
    $birthMonth = $birthDate->month;
    $currentMonth = date('m');
    if ($currentMonth < $birthMonth) {
        $ageYears--;
    }

    // หาความแตกต่างของวัน
    $birthDateDay = $birthDate->day;
    $currentDay = date('d');
    if ($currentDay < $birthDateDay && $currentMonth == $birthMonth) {
        $ageYears--;
    }

    return $ageYears;
    }

    public  function lastVisitDate(){
        $visit = Visit::where('patient_id',$this->id)->orderBy('id', 'DESC')->first();
        if($visit){
            return $visit->date;
        }
    }

}
