<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\defaultReservation;

class DefaultReservationController extends Controller
{

    public function loadReservation(Request $request){
        $defaultlist=defaultReservation::select('default_reservations.*','staffs.name')->leftJoin('staffs','staffs.id','default_reservations.staff_id')->get();
       return response()->json($defaultlist, 200 );
    }
}
