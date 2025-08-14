<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSub extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lab_subs';

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
    protected $fillable = ['lab_id', 'method', 'method_other', 'specimen_from', 'specimen_from_other', 'result'];

    public static $methodOption=[""=>'',1=>'Gram Stain', 2=>'Wet Preparation', 3=>'KOH', 4=>'Culture'
    , 5=>'PCR for CT/NG', 6=>'Anti-HIV', 7=>'TPHA', 8=>'RPR', 11=>'HBs Ag', 12=>'Anti-HCV', 13 =>'Pap Smear', 10=>'Other'];

    public static $specimenFromOption=[""=>'',3=>'Cervix', 1=>'Urethra', 2=>'Vagina', 4=>'Anus', 5=>'Urine', 6=>'Oral swab', 7=>'Rectal swab', 8=>'Cervix + Urethra', 10=>'Other'];

    public static $specimenFromPCROption=[""=>'',1=>'Urethra', 2=>'Anus', 3=>'Pharynx', 4=>'Vagina', 5=>'Urine', 6=>'Oral swab', 7=>'Rectal swab', 10 =>'Other'];

    public static $cultureResultOption=[""=>'',2=>'No growth', 3=>'Contaminated', 1=>'Growth', 4 =>'GC Growth', 5 =>'Fungus Growth', 6 =>'Neisseria spp.', 7 =>'Neisseria meningitidis' ];
    public static $wetResultOption=["-"=>'Not Found',1=>'Clue cell', 2=>'TV', 3=>'Budding yeast' ]; 
    public static $kohResultOption=["-"=>'-',1=>'Budding yeast', 2=>'Pseudohyphae', 3=>'Budding yeast with Pseudohyphae' ];
    public static $papResultOption=["-"=>'-',1=>'Negative for intraepithelial lesion or malignancy (NILM)', 2=>'Epithelial cell abnormality (see interpretation)', 3=>'Others (see interpretation)' ];  
    public static $pcrResultOption= [""=>'',2=>'Not detected',  3=>'Inconclusive' , 4=>'CT Detected', 5=>'NG Detected', 6=>'CT/NG Detected' ];  
    public static $hivResultOption= [""=>'',2=>'Negative' , 3=>'Inconclusive' , 1=>'Positive'];  
    public static $tphrResultOption= [""=>'',2=>'Negative',  3=>'Inconclusive' , 1=>'Positive'];
    public static $HBsAgResultOption= [""=>'',2=>'Negative', 1=>'Positive'];
    public static $AntiHCVResultOption= [""=>'',2=>'Negative' , 1=>'Positive'];  
    public static $rprResultOption= [""=>'',1=>'Non-reactive', 2=>'1:1', 3=>'1:2', 4=>'1:4', 5=>'1:8', 6=>'1:16', 7=>'1:32',
    8=>'1:64', 9=>'1:128', 10=>'1:256', 11=>'1:512', 12=>'1:1024', 13=>'1:2048'];  
    public static $gramResultOption1=['I: ','E: ','PMN: ','Other: '];
    public static $gramResultOptionCervix = ['I: ', 'E: ', 'PMN: ', 'Other: '];
    public static $gramResultOptionUrethra = ['I: ', 'E: ', 'PMN: ', 'Other: '];
    public static $gramResultOptionVagina = ['I: ', 'E: ', 'PMN: ', 'Fungus: ', 'Other: '];
    public static $gramResultOptionAnus = ['I: ', 'E: ', 'PMN: ', 'Other: '];
    public static $gramResultOptionOther = ['I: ', 'E: ', 'PMN: ', 'Other: '];

public static function getGramOptions($specimen)
{
    switch ($specimen) {
        case 'Cervix':
            return self::$gramResultOptionCervix;
        case 'Urethra':
            return self::$gramResultOptionUrethra;
        case 'Vagina':
            return self::$gramResultOptionVagina;
        case 'Anus':
            return self::$gramResultOptionAnus;
        default:
            return self::$gramResultOptionOther;
    }
}

    public function patient()
{
    return $this->visit()->patient();
    
}

    public function visit(){
        $lab = Lab::find($this->lab_id);
        $visit  = Visit::find($lab->visit_id);
        return $visit ;
    }
    public function method(){
        if($this->method == 10){
            return LabSub::$methodOption[$this->method].':'.$this->method_other;
        }else {
            return LabSub::$methodOption[$this->method];
        }
        // return $this->method
    }
    public function specimenFrom(){
        if($this->method < 14 ){
            if($this->specimen_from ==10){
                return LabSub::$specimenFromOption[$this->specimen_from].":".$this->specimen_from_other; 
            }
            return LabSub::$specimenFromOption[$this->specimen_from];
        }else if($this->method == 5){
            if($this->specimen_from ==10){
                return LabSub::$specimenFromPCROption[$this->specimen_from].":".$this->specimen_from_other; 
            }
            return LabSub::$specimenFromPCROption[$this->specimen_from];
        }
        if($this->specimen_from_other){
            return $this->specimen_from_other;  
        }
    }

    
}
