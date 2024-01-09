<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    use HasFactory;

    protected $fillable=['name','dob','image','start_working_date','educationID','depID','positionID','basic_salary','debt','address','active_status'];
}
