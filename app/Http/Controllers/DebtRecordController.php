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
            $staffloanupdate->debt=$olddebt+$request->amount;
            $staffloanupdate->save();

            $records=debtRecord::where('staff_id',$request->staff_id)->get();
            if($records->count()>0){
                return response()->json($records, 200);
            }
            return response()->json(['message'=>"Record Not found"]);
        }
        catch(Exception $e){
            return response()->json($e);
        }
   }
}
