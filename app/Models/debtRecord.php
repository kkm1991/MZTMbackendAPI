<?php

namespace App\Models;

use App\Models\Staffs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class debtRecord extends Model
{
    use HasFactory;
protected $fillable=['staff_id','type','amount','description'];
    public function staff(){
       return $this->belongsTo(Staffs::class,'id','staff_id');
    }
}
