<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class defaultReservation extends Model
{
    use HasFactory;
    public $fillable=['rareCost',
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
}
