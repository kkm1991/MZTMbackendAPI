<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\defaultReservation;
use App\Models\monthlyReservation;
use Illuminate\Support\Facades\Log;

class MonthlyReservationController extends Controller
{

    //frontend ကနေ reservation id ပါလာရင် update လုပ်မယ် မပါလာရင် အသစ်ထဲ့မယ် ဆိုတဲ့အတွက် update ကိုမရေးတာ
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
    public function getMonthlyList(){
       try{

        $currentMonth=date('m');
        $currentYear=date('Y');
        $MonthlyList=monthlyReservation::with('staff')->whereMonth('created_at',$currentMonth)->whereYear('created_at',$currentYear)->get();
        return response()->json($MonthlyList, 200);
       }
       catch(\Exeption $e){
         return response()->json(['error'=>$e->getMessage()], 500 );
       }

    }

    public function searchMonthlyList(){
        try{
            $selectedDate=request()->selectDate; //ဟိုဘက်ကပို.လိုက်တဲ့ params ကိုလက်ခံတယ်
            $date=Carbon::parse($selectedDate); //backend ကလက်ခံတဲ့ format ပြောင်းတယ်
            $selectMonth=$date->format('m'); //လကိုခွဲထုတ်တယ်
            $selectYear=$date->format('Y');//နှစ်ကိုခွဲထုတ်တယ်

            $MonthlyList=monthlyReservation::with('staff') //staff ဆိုတာက monthlyReservation model ထဲမှာရေးထားတဲ့ relationship method
                        ->whereMonth('created_at',$selectMonth)
                        ->whereYear('created_at',$selectYear)->get();
            if($MonthlyList->count()>0){
                return response()->json($MonthlyList, 200);
            }
            return response()->json(['message'=>'There is not data'],204);
           }
           catch(\Exeption $e){
             return response()->json(['error'=>$e->getMessage()], 500 );
           }
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
            //ဒီမှာ default reservation ကို ပြန်ပေးတဲ့အခါမှာ id ကိုမထဲ့ပေးလိုက်ဘူးဘာကြောင့်လည်းဆိုတော့ frontend ဘက်မှာ
            // monthly reservation အသစ်ထဲ့ရင် reservation id ရှိမရှိနဲ့ create ,update ကိုဖမ်းထားတာကြောင့်
            // ဒီက default reservation id ကိုထဲ့ပေးလိုက်ရင် ဟိုဘက်မှာ id ရှိတယ်ဆိုပြီးရောသွားမှာစိုးလို.
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
