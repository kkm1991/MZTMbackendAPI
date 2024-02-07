<?php

namespace App\Http\Controllers;

use App\Models\debtRecord;
use Illuminate\Http\Request;

class DebtRecordController extends Controller
{
   public function records(Request $request){
        $records=debtRecord::where('staff_id',$request->staff_id)->get();
        if($records->count()>0){
            return response()->json($records, 200);
        }
        return response()->json(['message'=>"Record Not found"]);
   }
}
