<?php

namespace App\Models;

use App\Models\deps;
use App\Models\Staffs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class salary extends Model
{
    use HasFactory;

    public function staff(){
        return $this->belongsTo(Staffs::class,'staff_id');
    }
    // public function dep(){
    //     // Check if $this->staff is not null before accessing dep relationship
    //     if ($this->staff) {
    //         // Use optional() to prevent "Call to a member function dep() on null"
    //         return optional($this->staff->dep())->depName; // Change depName to the actual attribute you want to retrieve
    //     } else {
    //         return null;
    //     }
    // }

}
