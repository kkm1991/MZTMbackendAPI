<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\defaultReservation;
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
        else{  //လစဉ်ကြိုတင်စာရင်းထဲမှာမရှိရင် ပုံသေကြိုတင်စာရင်းကိုပို.တယ်
            $checkDefaultReservation=defaultReservation::where('staff_id',$request->staff_id)->first();
            if($checkDefaultReservation){
                 $responseData=[
                    'rareCost'=> $checkDefaultReservation->rareCost,
                    'bonus'=> $checkDefaultReservation->bonus,
                    'attendedBonus'=> $checkDefaultReservation->attendedBonus,
                    'busFee'=> $checkDefaultReservation->busFee,
                    'mealDeduct'=> $checkDefaultReservation->mealDeduct,
                    'absence'=> $checkDefaultReservation->absence,
                    'ssbFee'=> $checkDefaultReservation->ssbFee,
                    'fine'=> $checkDefaultReservation->fine,
                    'redeem'=> $checkDefaultReservation->redeem,
                    'advance_salary'=> $checkDefaultReservation->advance_salary,
                    'otherDeductLable'=> $checkDefaultReservation->otherDeductLable,
                    'otherDeduct'=> $checkDefaultReservation->otherDeduct,
                    'staff_id'=> $checkDefaultReservation->staff_id,
                 ];
                return response()->json($responseData, 200);
            }
            else{
                return response()->json(['message'=>"There is not Defult Reservation with this Staff"]);
            }

        }
    }
}
