<?php

namespace App\Models;

use App\Models\Staffs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class monthlyReservation extends Model
{
    use HasFactory;

    public $fillable=[
    'rareCost',
    'bonus',
    'attendedBonus',
    'busFee',
    'mealDeduct',
    'absence',
    'ssbFee',
    'fine',
    'redeem',
    'advance_salary',
    'otherDeductLable',
    'otherDeduct',
    'staff_id' ];

    public function staff(){
        return $this->belongsTo(Staffs::class);
    }

}

