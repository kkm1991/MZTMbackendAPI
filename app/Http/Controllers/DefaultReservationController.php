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
    public function updateReservation(Request $request){
        defaultReservation::find($request->id)->update([
            'rareCost'=>$request->rareCost,
            'bonus'=>$request->bonus,
            'attendedBonus'=>$request->attendedBonus,
            'busFee'=>$request->busFee,
            'mealDeduct'=>$request->mealDeduct,
            'absence'=>$request->absence,
            'ssbFee'=>$request->ssbFee,
            'fine'=>$request->fine,
            'redeem'=>$request->redeem,
            'advance_salary'=>$request->advance_salary,
            'otherDeductLable'=>$request->otherDeductLable,
            'otherDeduct'=>$request->otherDeduct,

        ]);
    }
}
