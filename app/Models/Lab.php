<?php

namespace App\Models;

use App\Utillity;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'labs';

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
    'patient_id',
    'visit_id',
    'report_by',
    'approve_by',
    'approve_date',
    'approve_at',
    'collected_date',
    'collected_at',
    'report_date',
    'report_at',
    'LN',
    'remark', // 👈 เพิ่มตรงนี้
    'status',
];




    public static $statusOption = [1 => 'ส่ง Lab', 3 => 'รอลงผล RPR', 6 => 'รอลงผล Culture', 7 => 'รอลงผล PCR', 8 => 'รอลงผล Pap Smear', 4 => 'รอลงผล Culture/PCR', 5 => 'รอลงผล RPR+Culture/PCR', 2 => 'เสร็จ'];


    public function labItem(){
        return LabItem::where('lab_id',$this->id)->get();
    }
    public function labSub(){
        return LabSub::where('lab_id',$this->id)->get();
    }
public function labSub2()
{
    return $this->hasMany(LabSub::class, 'lab_id', 'id');
}

    public function labSubConfig(){
        $labsubs= LabSub::where('lab_id',$this->id)->orderBy('method', 'ASC')->get();
        $result = [];
        foreach($labsubs as $labsub){
            if($labsub->method == 10){
                $result[$labsub->method]['key']= $labsub->method_other;
                $result[$labsub->method]['value']= $labsub->result;
            }else{
                if(!array_key_exists($labsub->method,$result)){
                    $result[$labsub->method] = [];
                }
                if($labsub->specimen_from){
                    if(!array_key_exists($labsub->specimen_from,$result[$labsub->method])){
                        
                        if($labsub->specimen_from==10){
                            $result[$labsub->method][$labsub->specimen_from] = []; 
                            $result[$labsub->method][$labsub->specimen_from]['key'] = $labsub->specimen_from_other; 
                            $result[$labsub->method][$labsub->specimen_from]['value'] = $labsub->result; 
                        }else{
                            $result[$labsub->method][$labsub->specimen_from] = $labsub->result;
                            
                        }
                    }
                }else{
                    $result[$labsub->method]= $labsub->result;
                }
            }

        }
        return $result;
    }
    public function visit()
{
    return $this->belongsTo(Visit::class);
}
    public function labBlood(){
        $labBlood = LabBlood::where('visit_id',$this->visit_id)->first();
        // dd($labBlood);
        return $labBlood;
    }

    public static function format2DB($requestData){
        $requestData['collected_date'] = Utillity::th2dbDate($requestData['collected_date']) ;
        $requestData['report_date'] = Utillity::th2dbDate($requestData['report_date']) ;
        $requestData['approve_date'] = Utillity::th2dbDate($requestData['approve_date']) ;
        return $requestData;
    }

    public  function formatDBBack(){
        $this->collected_date = Utillity::db2thDate($this->collected_date) ;
        $this->report_date = Utillity::db2thDate($this->report_date) ;
        $this->approve_date = Utillity::db2thDate($this->approve_date) ;
        
        // return $requestData;
    }
    
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public  function status(){
        if($this->status){
            return $this->status.":".self::$statusOption[$this->status];
        }
       
    }

    public static function count($status)
    {
        return self::where('status', $status)->count();
    }

    
}
