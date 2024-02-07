<?php

namespace App\Models;

use App\Models\deps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staffs extends Model
{
    use HasFactory;

    protected $fillable=['name','dob','image','start_working_date','educationID','depID','positionID','basic_salary','debt','address','active_status','nrc','father_name'];

    public function dep(){

        return $this->belongsTo(deps::class, 'depID' );
    }
    public function debts(){
        return $this->hasMany(debtRecord::class,'staff_id');
    }
}
