<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitMedicine extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'visit_medicines';

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
    protected $fillable = ['visit_id', 'medicine_id', 'dose', 'route', 'amount'];

    public function visit(){
        return Visit::find($this->visit_id)->first();
    }

    
}
