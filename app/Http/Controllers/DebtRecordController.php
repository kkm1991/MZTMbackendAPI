<?php

namespace App\Http\Controllers;

use App\Models\Staffs;
use App\Models\debtRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DebtRecordController extends Controller
{
   public function records(Request $request){
        $records=debtRecord::where('staff_id',$request->staff_id)->get();
        if($records->count()>0){
            return response()->json($records, 200);
        }
        return response()->json(['message'=>"Record Not found"]);
   }

   public function addnew(Request $request){

        try{
            $validator=Validator::make($request->all(),[
                'type'=>'required',
                'amount'=>'required',
                'description'=>'required'
            ]);
            if($validator->fails()) {
                return response()->json(['message'=>"Required data"] );
            }
            $addloan=new debtRecord();
            $addloan->staff_id=$request->staff_id;
            $addloan->type=$request->type;
            $addloan->amount=$request->amount;
            $addloan->description=$request->description;
            $addloan->save();

            $staffloanupdate=Staffs::find($request->staff_id);
            $olddebt=$staffloanupdate->debt;

            if($request->type==="loan"){
                $staffloanupdate->debt=$olddebt+$request->amount;
            }
            else{
                $staffloanupdate->debt=$olddebt-$request->amount;
            }


            $staffloanupdate->save();

        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
   }




public function loanupdate(Request $request){

    Log::info("message",['request'=>$request->all()]);
    try{
        $validator=Validator::make($request->all(),[

            'amount'=>'required',
            'description'=>'required'
        ]);
        if($validator->fails()) {
            return response()->json(['message'=>"Required data"], );
        }
        // id နဲ့ record ကိုအရင်ရှာတယ်
      $loanupdate=debtRecord::find($request->id);

      if($loanupdate){

        // တွေ့ရင် old record amount ကို varaiable တစ်ခုထဲ့မှာအရင်ထဲ့ပြီးသိမ်းထားတယ်
        $oldrecord=$loanupdate->amount;


        //ဝန်ထမ်းကိုရှာတယ်
        $staffDebtUpdate=Staffs::find($request->staff_id);

        if($staffDebtUpdate){

            //အကဲ၍ type မတူရင်
            if($request->type==="loan"){
                //ရှိရင် ဝန်ထမ်းရဲ့ လက်ရှိ debt ထဲက ပထမကထဲ့ထားတဲ့ old loan ကို ပြန်နှုတ်ထားလိုက်တယ်
                $oldStaffLoan=$staffDebtUpdate->debt-$oldrecord;
                //ပြီးတော့မှ debt ထဲကို old loan ပြန်နှုတ်ထားပြီးသား debt ထဲကို update လုပ်မယ့် new loan ပေါင်းပြီးပြန်ထဲ့ပေးလိုက်တယ်
                $staffDebtUpdate->debt=$oldStaffLoan+$request->amount;
            }
            else{
                //ရှိရင် ဝန်ထမ်းရဲ့ လက်ရှိ debt ထဲက ပထမကထဲ့ထားတဲ့ old loan ကို ပြန်ပေါင်းထားလိုက်တယ်
                $oldStaffLoan=$staffDebtUpdate->debt+$oldrecord;
                //ပြီးတော့မှ debt ထဲကို old loan ပြန်နှုတ်ထားပြီးသား debt ထဲကို update လုပ်မယ့် new loan နှုတ်ပြီးပြန်ထဲ့ပေးလိုက်တယ်
                $staffDebtUpdate->debt=$oldStaffLoan-$request->amount;
            }

            $staffDebtUpdate->save();
        }
        else{
           return response()->json(['message'=>"There is no Staff with this id"]);
        }

        $loanupdate->amount=$request->amount;
        $loanupdate->created_at=$request->created_at;
        $loanupdate->description=$request->description;
        $loanupdate->save();

      }
      else{
        return response()->json(['message'=>"there is no record with this id"]);
       }

    }
    catch(Exception $e){
        return response()->json(['message'=>$e->getMessage()]);
    }
}


   public function loandelete(Request $request){

    try{
        $deleteloan=debtRecord::find($request->id);
        if($deleteloan){

            $staffloanupdate=Staffs::find($deleteloan->staff_id);
            $olddebt=$staffloanupdate->debt;
            if($deleteloan->type==="loan"){
                // staff profile ထဲက အကြွေးကို သွားနူတ်တာ
                $staffloanupdate->debt=$olddebt-$deleteloan->amount;
            }
            else{
                // staff profile ထဲက အကြွေးကို သွားပေါင်းတာ
                $staffloanupdate->debt=$olddebt+$deleteloan->amount;
            }

            $staffloanupdate->save();

            $deleteloan->delete();
        }
        else{
            return response()->json(["message"=>"There is no record with this record"]);
        }
    }
    catch(Exception $e){
        return response()->json(['error'=>$e]);
    }
   }
}
