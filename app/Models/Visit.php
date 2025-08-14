<?php

namespace App\Models;

use App\Utillity;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'visits';

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
    protected $fillable = [
        'date', 'patient_id', 'know_from', 'send_from', 'other_from',
        'reason_sti', 'reason_sti_other', 'risk_behavior', 'other_risk_behavior',
        'reason_vct', 'reason_vct_other', 'sti_hostory', 'contraceptive_method',
        'LMP', 'symptom', 'body_check', 'treatment', 'consultation',
        'hiv_sti_test', 'hiv_sti_test_date', 'hiv_sti_test_resule',
        'touch_tracing', 'touch_tracing_fail', 'provide_condom_site',
        'provide_condom_quantity', 'provide_lubricant_quantity', 'appointment',
        'appointment_reason','contraception', 'clinic', 'status','last_hiv_syphilis',
        'trush_tracks', 'body_check_marks'
    ];

    protected $casts = [
        'body_check_marks' => 'array',
    ];
    public static $knowFromOption = [0 => '',1 => 'จนท.ในสถานศึกษา,สถานบันเทิง,ที่ทำงานแนะนำ', 2 => 'คนที่ที่ไม่ใช่เจ้าหน้าที่แนะนำ', 3 => 'สื่อต่าง ๆ', 4 => 'เคยมา', 5 => 'ส่งต่อมาจาก', 10 => 'อื่น ๆ'];

    public static $referOption = ['M Plus', 'Caremat', 'PHPT', 'กาชาด', 'อื่น ๆ'];

    public static $QueueOption = ['เรียกคิวแล้ว', 'SimpPrEP', 'Caremat', 'Line ', 'ส่งมาจากห้อง...', 'เปิดสิทธิ Anti-HCV + HBsAg', 'เปิดสิทธิ Anti-HCV อย่างเดียว', 'ไม่ได้รับสิทธิตรวจ Hepatitis เกิดหลัง 2535', 'ตรวจ Hepatitis แล้ว','N/A','ทำบัตรประจำตัว/สมุดใหม่','M-Plus','กาชาด'];
    

    public static $otherOption = [0 => '', 1 => 'ส่งต่อเพื่อรักษาโรคซิฟิลิส', 2 => 'ส่งต่อเพื่อรักษาหนองใน', 3 => 'ส่งต่อเพื่อรักษาหูด'];


    public static $reasonStiOption = [ 1 => 'ต้องการตรวจสุขภาพ', 2 => 'มีอาการผิดปรกติ', 3 => 'คู่สัมผัสมีอาการ', 4 => 'ติดตามผลการรักษา', 5 => 'ฟังผล LAB', 
    6 => 'ข้อบังคับสถานบริการ', 
    7 => 'ผลตรวจห้องปฏิบัติการผิดปกติ',
    10 => 'อื่น ๆ'];

    public static $reasonVctOption = [
         1 => 'ต้องการตรวจสุขภาพ', 2 => 'มีพฤติกรรมเสี่ยง เช่น ไม่ใช้ถุงยาง/แตก/รั่ว', 3 => 'เคยได้รับการถ่ายเลือด/ปลูกถ่ายอวัยวะ',
        4 => 'มีประวัติใช้เข็มฉีดยาร่วมกัน', 5 => 'มีประวัติสักยันต์,เจาะร่างกายโดยใช้ของร่วมกัน', 6 => 'กังวลว่าจะมีการติดเชื้อจากแม่สู่ลูก', 10 => 'อื่น ๆ'
    ];


    public static $riskBehaviorOption = [
        1 => 'มีเพศสัมพันธ์กับหญิงบริการโดยไม่ใช้ถุงยาง', 2 => 'คู่นอนมากกว่า 1 คน', 3 => 'เป็นคู่นอนใหม่', 4 => 'คู่นอนเป็นโรค STI', 5 => 'ไม่ใช้ถุงยาง/แตก/รั่ว',
        6 => 'ใช้ยาเสพติด/ดื่มสุรา ก่อนมีเพศสัมพันธ์', 7 => 'มีเพศสัมพันธ์แบบหมู่', 8 => 'มีเพศสัมพันธ์ขณะเป็นโรค STI',8 => 'อื่น ๆ'
    ];

    public static $symptomOption = [
        'Case ซิฟิลิส รักษาครบเมื่อ...วันนี้มาติดตามผล RPR หลังการรักษา รอบ...เดือน/มาฟังผลเลือด RPR รอบ...เดือน (ผล= : ) ไม่มีผื่น, ไม่มีแผล, ไม่มีผมร่วง, ไม่มีอาการผิดปกติ/มาฉีดยาตามนัด เข็มที่...ไม่มีอาการผิดปกติหลังฉีดยาครั้งก่อน',
        'Case....รักษาเมื่อ....วันนี้มาติดตามผลการรักษา/ขอฟังผลเลือดหลังการรักษา/ไม่มีอาการผิดปกติ',
        'Case....วันนี้มาติดตามอาการหลังการรักษา ไม่มีหนอง/น้ำ ปัสสาวะปกติ',
        'Case wart วันนี้มาป้ายยาและดูอาการ หลังการรักษาครั้งก่อน วันนี้อาการดีขึ้น ติ่งเนื้อลดลง ไม่มีแผลจากการป้ายยา',
        'Case....วันนี้มาติดตามอาการหลังการรักษา ไม่มีตกขาว ไม่คัน ไม่มีกลิ่น ปัสสาวะปกติ',
        '....ก่อนมา มีหนองไหล สีใส/เหลืองขุ่น มีปัสสาวะแสบขัด ไม่มีก้อน ยังไม่เคยซื้อยา/รักษามาก่อน', 
        '....ก่อนมา มีติ่งเนื้อ/ตุ่มน้ำใส มีแผลบริเวณ.... ตุ่มแผลไม่ปวด',
        '....ก่อนมา มีตกขาว มีอาการคันบริเวณ....มีกลิ่น ปัสสาวะปกติ',
        'ต้องการตรวจสุขภาพ อาการปกติ',
        'ตรวจคัดกรองการติดเชื้อไวรัสตับอักเสบบีและซีตามสิทธิ์'
    ];

    public static $consultationOption = [1 => 'ได้ทำ', 2 => 'ไม่ได้ทำ'];

    public static $termSyphilisOption = [1 => '1', 2 => '2', 3 => 'Early Latent', 4 => 'Late Latent', 5 => 'Unknown Duration'];
    public static $diseaseStateOption = [1 => 'ผู้ป่วยใหม่', 2 => 'เกิดโรคซ้ำ', 3 => 'อยู่ระหว่างรักษา', 4 => 'มาติดตามผลการรักษา', 5 => 'ส่งต่อ', 5 => 'ไม่ได้รับการรักษา'];
    public static $hivtestresultOption = [0 => '',1 => 'Negative',2 => 'Inconclusive',3 => 'Positive' ];

    public static $diseaseStateOption1 = [1 => 'ผู้ป่วยใหม่(new case)', 2 => 'เกิดโรคซ้ำ(recurrent,reinfection)', 3 => 'การรักษาเดิมไม่ได้ผล(treatment  failure)', 4 => 'มาติดตามดูอาการ/รับการรักษาต่อเนื่อง(follow up)', 
    5 => 'มาฉีดยาpenicillin เข็มที่1', 5 => 'มาฉีดยาpenicillin เข็มที่2', 6 => 'มาฉีดยาpenicillin เข็มที่3', 7 => 'มาฟังผลทางห้องปฏิบัติการ(Lab test)', 8 => 'มาฟังผลเลือด RPR', 
    9 => 'มาฟังผลเลือด RPR หลังรักษา 3เดือน', 10 => 'มาฟังผลเลือด RPR หลังรักษา 6เดือน', 11 => 'มาฟังผลเลือด RPR หลังรักษา 9เดือน', 12 => '2มาฟังผลเลือด RPR หลังรักษา 12เดือน', 
    13 => 'มาฟังผลเลือด RPR หลังรักษา 24เดือน', 14 => 'สิ้นสุดการรักษา(completed)'];

    public static $hivStiTestResuleOption = [1 => 'รายเดี่ยว', 2 => 'รายกลุ่ม', 3 => 'พร้อมคู่', 4 => 'ไม่เคยตรวจ', 5 => 'เคยตรวจ'];

    public static $touchTracingOption = [0=>'', 1 => 'ฝากยา', 2 => 'มาตรวจพร้อมกันครั้งนี้', 3 => 'ออกใบติดตาม', 4 => 'ผู้ป่วยจะแจ้งเอง', 5 => 'จนท.ตามให้', 5 => 'ไม่สามารถติดตามได้', 6 => 'คู่รักษาแล้ว' ];

    public static $ClinicOption = [1 => 'STI Clinic', 2 => 'TB Clinic', 3 => 'TMC clinic'];
    public static $statusOption = [1 => 'ลงทะเบียน', 2 => 'ห้องตรวจ 2', 7 => 'ห้องตรวจ 8', 3 => 'ห้องเจาะเลือด/ฉีดยา', 4 => 'ห้องยา', 5 => 'กลับบ้าน'];


    public static $treatmentOption = [1 => 'แนะนำให้ใช้ถุงยางอนามัยทุกครั้งในทุกช่องทางเมื่อมีเพศสัมพันธ์และสอนการใส่ถุงยางอย่างถูกต้อง',
    2 => 'ให้คำปรึกษาก่อน (pre-test) และหลังการตรวจเลือดหาการติดเชื้อเอชไอวีและซิฟิลิส (post-test)',
    3 => 'แนะนำตรวจเลือดหาเชื้อเอชไอวีและซิฟิลิสหลังพ้นระยะฟักตัว แนะนำการรับยา PrEP',
    4 => 'สังเกตอาการแพ้ยาหลังฉีดยา 30 นาที และแนะนำการสังเกตอาการแพ้ยาและอาการข้างเคียงเมื่อกลับบ้าน',
    5 => 'ให้ข้อมูลการถ่ายทอดเชื้อไปสู่คู่เพศสัมพันธ์ และการติดตามผู้สัมผัสเพื่อเข้ารับการรักษาร่วมด้วย',
    6 => 'ให้ข้อมูลเกี่ยวกับโรคที่กำลังป่วยอยู่และการปฏิบัติตัวเพื่อไม่ให้รับเชื้อเพิ่มหรือป้องกันไม่ให้ติดเชื้อซ้ำ ความจำเป็นของการรักษาอย่างครบถ้วนและมารับการติดตามอาการอย่างต่อเนื่อง',
    7 => 'ให้คำแนะนำ เรื่อง งดมีเพศสัมพันธ์และสำเร็จความใคร่ด้วยตนเองระหว่างการรักษา หากงดไม่ได้ควรใช้ถุงยางอนามัยทุกครั้ง มีคู่เพศสัมพันธ์คนเดียว งดเว้นการเปลี่ยนคู่เพศสัมพันธ์ งดการสวนล้างช่องคลอด ควรเปลี่ยนผ้าอนามัยบ่อยไม่ให้อับชื้น สังเกตอาการผิดปกติ หากมีอาการมากขึ้นหรือไม่ดีขึ้นให้มาตรวจใหม่หรือไปโรงพยาบาล ฯลฯ'];


    public static $reasonAppointmentOption = ['ติดตามผลการรักษา','รักษาต่อเนื่อง','รับยาตามนัด','ฟังผลเลือด','ฉีดยา penicillinเข็ม1','ฉีดยา penicillin เข็ม2',
    'ฉีดยา penicillin เข็ม3','เจาะ RPR หลังรักษา','เจาะ RPR หลังรักษา 3เดือน','เจาะ RPR หลังรักษา 6เดือน','เจาะ RPR หลังรักษา 9เดือน','เจาะ RPR หลังรักษา 12เดือน',
    'เจาะ RPR หลังรักษา 24เดือน','(แจ้งเตือนทางไลน์แล้ว)','ป้ายยา'];

    public static $ContraceptionOption = ['DMPA','OCP','IUD','ไม่ได้คุม','Condom'];



    public  function contactPerson()
    {
        return ContactPerson::where('visit_id', $this->id)->get();
    }

    public  function lab()
    {
        return Lab::where('visit_id', $this->id)->first();
    }
    public  function labItem()
    {
        $lab = $this->lab();
        if ($lab) {
            return LabItem::where('lab_id', $lab->id)->get();
        }
        return [];
    }

    public function labSub()
    {
        $lab = $this->lab();
        if ($lab) {
            return LabSub::where('lab_id', $lab->id)->get();
        }
        return [];
    }
    public function latestLabMethod()
{
    return $this->hasOneThrough(LabSub::class, Lab::class, 'visit_id', 'lab_id', 'id', 'id')
        ->whereIn('lab_subs.method', [11, 12])
        ->latest('lab_subs.created_at');
}

    public  function patient()
    {
        $patient = Patient::find($this->patient_id);
        return $patient;
        
    }
    // App\Models\Visit.php
    // สร้างฟังก์ชัน Relationship ใหม่
    public function patient2()
    {
        return $this->belongsTo(Patient::class, 'patient_id'); // ใช้ตารางเดิมได้ แต่ตั้งชื่อฟังก์ชันใหม่
    }
    public static function format2DB($requestData)
    {
        if (!array_key_exists('date', $requestData)) {
            $requestData['date'] =  Utillity::AD2BE(date('Y-m-d'));
        } else {
            $requestData['date'] = Utillity::th2dbDate($requestData['date']);
        }

        if (array_key_exists('appointment', $requestData)) {

            $requestData['appointment'] = Utillity::th2dbDate($requestData['appointment']);
        }

        if (!array_key_exists('reason_sti', $requestData)) {
            $requestData['reason_sti'] =null;
        }
        // $requestData['reason_sti'] = implode(",",$requestData['reason_sti']);
        
        return $requestData;
    }

    public  function formatDBBack()
    {
        $this->date = Utillity::db2thDate($this->date);
        $this->appointment = Utillity::db2thDate($this->appointment);
        $this->reason_sti = json_decode($this->reason_sti,true);
        $this->reason_vct = json_decode($this->reason_vct,true);
        $this->risk_behavior = json_decode($this->risk_behavior,true);
        
    }
    public static function getPatient($visit = null)
    {
        if ($visit) {
            return $visit->patient();
        } elseif ($_GET['patient']) {
            $patient = Patient::find($_GET['patient']);
            return $patient;
        }
        return null;
    }

    public function visitMedicine()
    {
        return VisitMedicine::where('visit_id', $this->id)->get();
    }

    public function diagnosi()
    {
        return Diagnosi::where('visit_id', $this->id)->get();
    }

    public function roomLink()
{
    switch ($this->status) {
        case 2:
            return route("visit.edit", ["visit" => $this->id, "page" => 2]);
            break;
        case 3:
            return route("visit.edit", ["visit" => $this->id, "page" => 3]);
            break;
        case 8:
            return route("visit.edit", ["visit" => $this->id, "page" => 8]);
            break;
        case 5:
            return route("visit.edit", ["visit" => $this->id, "page" => 5]);
            break;
    }
    return "";
}

    public static function count($status)
    {
        return Visit::where('status', $status)->count();
    }


    public static function next($page, $id)
    {
        switch ($page) {
            case 1:
                return redirect(route('patient.edit', ['patient' => $id, 'page' => 1]));
                break;
            case 2:
                return redirect(route('visit.edit', ['visit' => $id, 'page' => 2]));
                break;

            case 3:
                return redirect(route('visit.edit', ['visit' => $id, 'page' => 3]));
                break;

            case 4:
                return redirect(route('lab.edit', ['lab' => $id, 'page' => 4]));
                break;

            case 5:
                return redirect(route('visit.lab', ['visit' => $id, 'page' => 5]));
                break;

        }
    }



    public function diagnosiAllString()
    {
        $result = [];
        $diagnosis =  Diagnosi::where('visit_id', $this->id)->get();
        foreach($diagnosis as $diagnosi){
            $result[] = $diagnosi->disease();
        }
        return implode(",", $result);
    }
    public function diagnosiStatusAllString()
    {
        $result = [];
        $diagnosis =  Diagnosi::where('visit_id', $this->id)->get();
        foreach($diagnosis as $diagnosi){
            $result[] = $diagnosi->diseaseState();
        }
        return implode(",", $result);
    }
    public function reasonString()
    {
        $result = [];
        if ($this->reason_sti) {
            $reason_stis = json_decode($this->reason_sti,true);
            foreach($reason_stis as $reason_sti){
                $result[] = self::$reasonStiOption[$reason_sti];
            }
            
        }
        if ($this->reason_vct) {

            $reason_vcts = json_decode($this->reason_vct,true);
            foreach($reason_vcts as $reason_vct){
                $result[] = self::$reasonVctOption[$reason_vct];
            }
            // $result[] = self::$reasonVctOption[$this->reason_vct];
        }
        return implode(",", $result);
    }

    public function diagnosiString()
    {
        $diagnosis =  Diagnosi::where('visit_id', $this->id)->get();
        $result = [];
        foreach($diagnosis as $diagnosi){
            if($diagnosi->disease){
                $result[] = Diagnosi::$diseaseOption[$diagnosi->disease];
            }else{
                $result[] = $diagnosi->other_disease;
            }
        }
        return  implode(", ",$result);
    }

    public function diseaseStateString()
    {
        $diagnosis =  Diagnosi::where('visit_id', $this->id)->get();
        $result = [];
        foreach($diagnosis as $diagnosi){
            if($diagnosi->disease){
                $result[] = Diagnosi::$diseaseStateOption[$diagnosi->disease_state];
            }else{
                $result[] = $diagnosi->disease_state_other;
            }
        }
        return  implode(", ",$result);
    }
    public function RPR()
    {
        $lab = Lab::where('visit_id', $this->id)->first();
        $labSubs = LabSub::where('lab_id', $lab->id)->where('method', 8 /*RPR*/)->get();

        $result = [];
        foreach($labSubs as $labSub){
            if($labSub->result){
                $result[] = $labSub->result;
            }
        }
        return  implode(", ",$result);
    }
    public function trush_tracks()
    {
            if( $this->trush_tracks){
            $result = json_decode($this->trush_tracks);
            return $result;
        }
        return [];
    }
    public function diagnoses()
{
    return $this->hasMany(Diagnosi::class, 'visit_id');
}
    public function followups()
{
    return $this->hasMany(VisitFollowup::class);
}


}
