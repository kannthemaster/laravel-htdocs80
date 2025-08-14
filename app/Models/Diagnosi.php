<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosi extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'diagnoses';

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
    protected $fillable = ['visit_id', 'disease', 'other_disease', 'term_syphilis', 'disease_state', 'disease_state_other', 'egasp_info','gf'];

    public static $diseaseOption=[1=>'Syphilis', 2=>'Gonorrhea', 3=>'Non-Gonococcal urethritis', 4=>'LGV', 5=>'Chancroid', 
    6=>'Bacterial vaginosis', 7=>'Vaginal candidiasis', 8=>'Trichomoniasis', 9=>'Molluseum contagiosum', 10=>'Anogenital warts', 11=>'Other',
    12=>'Herpes simplex', 13=>'Pre-test', 14=>'Post-test', 15=>'ตรวจไม่พบโรค STI',16=>'GF',17=>'Egasp 1',18=>'Egasp 2' ];

    public static $termSyphilisOption=[0=>'', 1=>'1', 2=>'2', 3=>'Early Latent', 4=>'Late Latent', 5=>'Unknown Duration'];
    // public static $diseaseStateOption=[1=>'ผู้ป่วยใหม่', 2=>'เกิดโรคซ้ำ', 3=>'อยู่ระหว่างรักษา', 4=>'มาติดตามผลการรักษา', 5=>'ส่งต่อ', 5=>'ไม่ได้รับการรักษา', 10=>'อื่่น ๆ'];

    public static $diseaseStateOption = [20 => 'ปรึกษา/ขอคำแนะนำ',1 => 'ผู้ป่วยใหม่(new case)', 2 => 'เกิดโรคซ้ำ(recurrent,reinfection)', 3 => 'การรักษาเดิมไม่ได้ผล(treatment  failure)', 4 => 'มาติดตามดูอาการ/รับการรักษาต่อเนื่อง(follow up)', 14 => 'สิ้นสุดการรักษา(completed)',  
    5 => 'มาฉีดยาpenicillin เข็มที่1', 23 => 'มาฉีดยาpenicillin เข็มที่2', 6 => 'มาฉีดยาpenicillin เข็มที่3',7 => 'มาฟังผลทางห้องปฏิบัติการ(Lab test)', 8 => 'มาฟังผลเลือด HIV', 
    24 => 'มาฟังผล RPR ก่อนรักษา',9 => 'มาฟังผลเลือด RPR หลังรักษา 3 เดือน', 10 => 'มาฟังผลเลือด RPR หลังรักษา 6 เดือน', 11 => 'มาฟังผลเลือด RPR หลังรักษา 9 เดือน', 12 => 'มาฟังผลเลือด RPR หลังรักษา 12 เดือน', 
    13 => 'มาฟังผลเลือด RPR หลังรักษา 24เดือน',
    21 => 'ตรวจเลือด HIV', 15 => 'ตรวจเลือด RPR หลังรักษา 3 เดือน', 16 => 'ตรวจเลือด RPR หลังรักษา 6 เดือน', 17 => 'ตรวจเลือด RPR หลังรักษา 9 เดือน', 18 => 'ตรวจเลือด RPR หลังรักษา 12 เดือน', 19 => 'ตรวจเลือด RPR หลังรักษา 24 เดือน', 22 => 'Refer/ส่งต่อไปตรวจรักษา รพ.อื่น'

    ];

public function disease()
{
    if ($this->disease == 1) {
        return Diagnosi::$diseaseOption[$this->disease] . ":" . Diagnosi::$termSyphilisOption[$this->term_syphilis];
    } elseif ($this->disease == 11) {
        return Diagnosi::$diseaseOption[$this->disease] . ":" . $this->other_disease;
    } elseif (in_array($this->disease, [17, 18])) {
        return Diagnosi::$diseaseOption[$this->disease] . ":" . $this->egasp_info;
    } elseif ($this->disease == 16) { // เพิ่มเงื่อนไขสำหรับ GF
        return Diagnosi::$diseaseOption[$this->disease] . ":" . $this->gf;
    }
    return Diagnosi::$diseaseOption[$this->disease];
}


    public  function diseaseState(){
        if($this->disease ==10){
            return Diagnosi::$diseaseStateOption[$this->disease_state].":". $this->disease_state_other;
        }
        return Diagnosi::$diseaseStateOption[$this->disease_state];
    }
    public function visit()
{
    return $this->belongsTo(Visit::class, 'visit_id');
}

}
