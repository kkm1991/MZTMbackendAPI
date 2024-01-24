<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\salary;
use Illuminate\Http\Request;
use App\Models\defaultReservation;
use App\Models\monthlyReservation;

class SalaryController extends Controller
{

    public function loadSalary(){
        $currentDate=Carbon::now();
        $Month=$currentDate->format('m');
        $Year=$currentDate->format('Y');

        // staff ဆိုတာ salary model ထဲမှာရေးထားတဲ့ relationship staff.dep ဆိုတာက staff model ထဲမှာ ရေးထားတဲ့ dep relationship
        $salarylist=salary::with('staff','staff.dep')->whereMonth('created_at',$Month)->whereYear('created_at',$Year) ->get();

        return response()->json($salarylist, 200 );



    }
    public function searchsalary(){
        if(request()->has('selectDate')){
            $selectedDate=request()->selectDate; //ဟိုဘက်ကပို.လိုက်တဲ့ params ကိုလက်ခံတယ်
            $date=Carbon::parse($selectedDate); //backend ကလက်ခံတဲ့ format ပြောင်းတယ်
            $Month=$date->format('m'); //လကိုခွဲထုတ်တယ်
            $Year=$date->format('Y');//နှစ်ကိုခွဲထုတ်တယ်
            $salarylist=salary::with('staff')->whereMonth('created_at',$Month)->whereYear('created_at',$Year)->get();

            return response()->json($salarylist, 200 );
        }

    }
        public function updatesalary(Request $request){
            Log::info('Request data:', $request->all());
            $this->updatemonthlyReservation($request);

            $salaryupdate=salary::find($request->id);
            Log::info($salaryupdate);
            $salaryupdate->basicSalary=$request->basicSalary;
            $salaryupdate->rareCost=$request->rareCost;
            $salaryupdate->bonus=$request->bonus;
            $salaryupdate->attendedBonus=$request->attendedBonus;
            $salaryupdate->busFee=$request->busFee;
            $salaryupdate->FirstTotal=$request->FirstTotal;
            $salaryupdate->mealDeduct=$request->mealDeduct;
            $salaryupdate->absence=$request->absence;
            $salaryupdate->ssbFee=$request->ssbFee;
            $salaryupdate->fine=$request->fine;
            $salaryupdate->redeem=$request->redeem;
            $salaryupdate->advance_salary=$request->advance_salary;
            $salaryupdate->otherDeductLable=$request->otherDeductLable;
            $salaryupdate->otherDeduct=$request->otherDeduct;
            $salaryupdate->finalTotal=$request->finalTotal;
            $salaryupdate->save();

            return response()->json(['message'=>"Salary updated"], 200 );
        }


        public function updatemonthlyReservation(Request $request){
            $monthlyreservation=monthlyReservation::find($request->reservation_id);

            $monthlyreservation->rareCost=$request->rareCost;
            $monthlyreservation->bonus=$request->bonus;
            $monthlyreservation->attendedBonus=$request->attendedBonus;
            $monthlyreservation->busFee=$request->busFee;
            $monthlyreservation->mealDeduct=$request->mealDeduct;
            $monthlyreservation->absence=$request->absence;
            $monthlyreservation->ssbFee=$request->ssbFee;
            $monthlyreservation->fine=$request->fine;
            $monthlyreservation->redeem=$request->redeem;
            $monthlyreservation->advance_salary=$request->advance_salary;
            $monthlyreservation->otherDeductLable=$request->otherDeductLable;
            $monthlyreservation->otherDeduct=$request->otherDeduct;
            $monthlyreservation->save();
        }




    public function addSalary(Request $request){
        $currentDate=Carbon::now();
        $currentMonth=$currentDate->format('m');
        $currentYear=$currentDate->format('Y');


        $checkSalary=salary::where('staff_id',$request->staff_id)
        ->whereMonth('created_at',$currentMonth)
        ->whereYear('created_at',$currentYear)
        ->get();

        //salary table ထဲမှာ frontend ကပို.လိုက်တဲ့ staff_id နဲ့ ယခုလ အတွက်စာရင်းရှိမရှိအရင်စစ်လိုက်တယ်
        if($checkSalary->count()>0){
            //စာရင်းရှိရင်ထပ်မသွင်းဘူး ရှိပြီးကြောင်းပြတယ်
            return response()->json([
                'message'=>'ယခုဝန်ထမ်းသည် လစာ စာရင်းထဲတွင်ရှည်ပြီးသားဖြစ်ပါသည်'
            ], 200, $headers);
        }
        else{
            //စာရင်းသွင်းဖို.အတွက် လစဉ်ကြိုတင်ငွေစာရင်းထဲမှာ staff_id နဲ့သွင်းထားလာမသွင်းထားလားကြည့်တယ်
            $checkMonthlyReservation=monthlyReservation::with('staff')->where('staff_id',$request->staff_id)
            ->whereMonth('created_at',$currentMonth)
            ->whereYear('created_at',$currentYear)
            ->get();

            if($checkMonthlyReservation->count()>0){
                //အကယ်၍ monthly reservation ထဲမှာ current date format နဲ့ စာရင်းရှိရင် monthly reservation နဲ့လစာပေးမယ်
              $this->createSalary($checkMonthlyReservation);
            }
            else{
                //monthly reservation မှာစာရင်းမရှိရင် default reservation ကို monthly reservation စာရင်းထဲ့မှာအရင်သွင်းမယ်
                $defaultReservation=defaultReservation::where('staff_id',$request->staff_id)->first();
                $createMonthlyReservation=monthlyReservation::create([
                    'rareCost'=>$defaultReservation->rareCost,
                    'bonus'=>$defaultReservation->bonus,
                    'attendedBonus'=>$defaultReservation->attendedBonus,
                    'busFee'=>$defaultReservation->busFee,
                    'mealDeduct'=>$defaultReservation->mealDeduct,
                    'absence'=>$defaultReservation->absence,
                    'ssbFee'=>$defaultReservation->ssbFee,
                    'fine'=>$defaultReservation->fine,
                    'redeem'=>$defaultReservation->redeem,
                    'advance_salary'=>$defaultReservation->advance_salary,
                    'otherDeductLable'=>$defaultReservation->otherDeductLable,
                    'otherDeduct'=>$defaultReservation->otherDeduct,
                    'staff_id'=>$defaultReservation->staff_id
                ]);

                // $checkMonthlyReservation=monthlyReservation::with('staff')->where('staff_id',$request->staff_id)
                // ->whereMonth('created_at',$currentMonth)
                // ->whereYear('created_at',$currentYear)
                // ->get();

                //အပေါ်မှာအသစ်လုပ်လိုက်တဲ့ monthly reservation အသစ်ကိုထဲ့ပေးလိုက်တယ်
                $this->createSalary($createMonthlyReservation);

            }
        }



    }
    public function createSalary($reservation){

          $addsalary=new salary();

          foreach($reservation as $monthlyReservation){
              //ပထမ အပိုင်း (အတိုး)
              $addsalary->basicSalary=$monthlyReservation->staff->basic_salary;
              $addsalary->rareCost=$monthlyReservation->rareCost;
              $addsalary->bonus=$monthlyReservation->bonus;
              $addsalary->attendedBonus=$monthlyReservation->attendedBonus;
              $addsalary->busFee=$monthlyReservation->busFee;
              $firsttotal=$monthlyReservation->busFee+$monthlyReservation->attendedBonus+$monthlyReservation->bonus+$monthlyReservation->rareCost+$monthlyReservation->staff->basic_salary;
              $addsalary->FirstTotal=$firsttotal;

              //ဒုတိယ အပိုင်း(အနူတ်)
              $addsalary->mealDeduct=$monthlyReservation->mealDeduct;
              $addsalary->absence=$monthlyReservation->absence;
              $addsalary->ssbFee=$monthlyReservation->ssbFee;
              $addsalary->fine=$monthlyReservation->fine;
              $addsalary->redeem=$monthlyReservation->redeem;
              $addsalary->advance_salary=$monthlyReservation->advance_salary;
              $addsalary->otherDeductLable=$monthlyReservation->otherDeductLable;
              $addsalary->otherDeduct=$monthlyReservation->otherDeduct;
              $addsalary->staff_id=$monthlyReservation->staff_id;
              $addsalary->reservation_id=$monthlyReservation->id;
              $totaldeduct=$monthlyReservation->mealDeduct+
              $monthlyReservation->absence+
              $monthlyReservation->ssbFee+
              $monthlyReservation->fine+
              $monthlyReservation->redeem+
              $monthlyReservation->advance_salary+
              $monthlyReservation->otherDeduct;
              $addsalary->finalTotal= $firsttotal -$totaldeduct;
              $addsalary->save();

          }

    }
}
