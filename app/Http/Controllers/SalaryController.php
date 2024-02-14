<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use App\Models\deps;
use App\Models\salary;
use App\Models\Staffs;
use App\Models\debtRecord;
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
        $salarylist=salary::with('staff')->whereMonth('created_at',$Month)->whereYear('created_at',$Year)->get();

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

    //delete salary function မှာ reservation ကိုပါဖျက်တဲ့စနစ်လုပ်ထားတယ်
    public function deletesalary(Request $request){
        try {
            $deletereservation=monthlyReservation::where('id',$request->reservation_id);
        $deletereservation->delete();

        $deletesalary=salary::find($request->id);
        if($deletesalary->redeem > 0){
            $debtupdate=Staffs::find($deletesalary->staff_id);
            if($debtupdate){
                $olddebt=$debtupdate->debt;
            $debtupdate->debt=$olddebt+$deletesalary->redeem;
            $debtupdate->save();
            }
          }
        $deletesalary->delete();
        return response()->json(['message'=>"Salary and Reservation List deleted"], 200 );
        } catch (Exception $th) {
           return response()->json($deletesalary);
        }
    }

    //sarlary ကို update လုပ် ရင် reservation ကိုပါ update မယ်
        public function updatesalary(Request $request){

                try{

            //monthly reservation update function
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
            //လက်ရှိအကြွေးကိုယူတယ်
            $staff=Staffs::find($request->staff_id);
           if($staff){
            $olddebt=$staff->debt;

            $olderedeem=$salaryupdate->redeem; //update မလုပ်ခင်ကဆပ်လိုက်တဲ့ ကြွေးဆပ်ကိုယူတယ်
             // လက်ရှိအကြွေးကို update မလုပ်ခင်က ကြွေးဆပ်ပေါင်းထဲ့လိုက်တယ် အဲ့ဒါမှ ယခင်လ ကြွေးကျန်မူရင်းကိုရမယ် ပြီးမှ update redeem ကို ပြန်နှုတ်ပေးလိုက်တယ်
            $staff->debt=($olddebt+$olderedeem)-$request->redeem;
            $staff->save();
           }

           $redeemupdate=debtRecord::find($request->redeem_record);
           if($redeemupdate){
                $redeemupdate->amount=$request->redeem;
                $redeemupdate->save();
           }

            $salaryupdate->redeem=$request->redeem;
            $salaryupdate->advance_salary=$request->advance_salary;
            $salaryupdate->otherDeductLable=$request->otherDeductLable;
            $salaryupdate->otherDeduct=$request->otherDeduct;
            $salaryupdate->finalTotal=$request->finalTotal;
            $salaryupdate->save();

            return response()->json(['message'=>"Salary updated"], 200 );
            }
            catch(Exception $e){
                return response()->json(['Error message'=>$e]);
            }
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
              $this->createSalary($checkMonthlyReservation,$request->dep);
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
                $this->createSalary($createMonthlyReservation,$request->dep);

            }
        }


    }
    public function createSalary($reservation,$dep){

        try {

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
              $addsalary->dep=$dep;
              $addsalary->reservation_id=$monthlyReservation->id;
              $totaldeduct=$monthlyReservation->mealDeduct+
              $monthlyReservation->absence+
              $monthlyReservation->ssbFee+
              $monthlyReservation->fine+
              $monthlyReservation->redeem+
              $monthlyReservation->advance_salary+
              $monthlyReservation->otherDeduct;
              $addsalary->finalTotal= $firsttotal -$totaldeduct;


              if($monthlyReservation->redeem >0){
               $debtupdate=Staffs::find($monthlyReservation->staff_id);
               $olddebt=$debtupdate->debt;
               $debtupdate->debt=$olddebt-$monthlyReservation->redeem;
               $debtupdate->save();

               $addrecord=new debtRecord();
               $addrecord->staff_id=$monthlyReservation->staff_id;
               $addrecord->amount=$monthlyReservation->redeem;
               $addrecord->type="payment";
               $addrecord->description="salary";
               $addrecord->save();
               $addsalary->redeem_record=$addrecord->id;

             }
             else{
                return response()->json([ 'message'=>'ကြွေးမရှိဘူး'.$monthlyReservation->redeem]);
             }


              $addsalary->save();

          }

        } catch (\Throwable $th) {
           return response()->json($th, 500 );
        }


    }

    public function salaryReport(Request $request){
        $selectedDate=$request->monthPicker;
        Log::info('Request data:', $request->all());
        if($selectedDate){

            $timestamp=strtotime($selectedDate);
            $startDateTime=date('Y-m-01 00:00:00',$timestamp);
            $endDateTime=date('Y-m-t 23:59:59',$timestamp);

            $summarybyDeps=new deps();
            $summaryListByDeps=$summarybyDeps->getSummaryByDate($startDateTime,$endDateTime);


        }
        else{
            $summarybyDeps=["message"=>"There is no Salaries in this month"];
        }
        return response()->json($summaryListByDeps, 200 );
    }

    public function slip(Request $request){

        // $slipdata=salary::with('staff')->where('id',$request->salaryId)->first();

        $slipinfo=salary::select('salaries.*' ,'staffs.name as staff_name','deps.title as dep_title' )
        ->leftJoin('staffs','staff_id','=','staffs.id')
        ->leftJoin('deps','dep','=','deps.id')
        ->where('salaries.id',$request->salaryId)
        ->first();
          return response()->json($slipinfo, 200);
    }
}
