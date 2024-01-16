<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\monthlyReservation;
use Illuminate\Support\Facades\Log;

class MonthlyReservationController extends Controller
{
    public function add(Request $request){
        $createdata=[
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
            'staff_id'=>$request->staff_id,
        ];




        if($request->id==null){
            monthlyReservation::create($createdata);

            return response()->json(['message'=>"New Reservation created"]);
        }
        else{
            $check=monthlyReservation::find($request->id);
            $check->update($createdata);
            return response()->json(['message'=>"reservation update"]);
        }



        //  monthlyReservation::create($filteredData);
        //  return response()->json(['message'=>"created"]);
        // //reservation id ပါလာရင် update လုပ်မယ်
        // if($request->id!=""){



        //     return response()->json(['message'=>"updated"]);
        // }
        // else{


        // }


    }

    public function loadReservation(Request $request){
        $currentMonth = date('m');
        $currentYear = date('Y');
        $checkReservation=monthlyReservation::where('staff_id',$request->staff_id)->whereMonth('created_at',$currentMonth)->whereYear('created_at',$currentYear)->first();
        // အခုလထဲ မှာ request ကပို.လိုက်တဲ့ staff id နဲ့စာရင်းရှိမရှိစစ်
        if($checkReservation){
            //ရှိရင် အဲ့ဒီဝန်ထမ်းရဲ့ကြိုတင်စာရင်းကိုပြန်ပို.တယ်
            return response()->json($checkReservation, 200 );
        }
        else{
            //မရှိရင် ပုံသေကြိုတင်စာရင်းကိုပြန်မယ်

        }
    }
}
