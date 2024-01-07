<?php

namespace App\Http\Controllers;

use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffsController extends Controller
{
    // ဝန်ထမ်းစာရင်း ပြ dep key ပါရင် dep အလိုက်ပြမယ် မပါရင် ဝန်ထမ်းအားလုံးပြမယ်
    public function list(Request $request){
        $stafflist=Staffs::when($request->key,function($query) use($request){
            $query->where('depID',$request->key);
        })->orderby('id','desc')->get();

        return response()->json($stafflist, 200);
    }
    //ဝန်ထမ်းအသစ်ထဲ့ အစ
    public function createprofile(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'educationID'=>'required',
            'depID'=>'required',
            'positionID'=>'required',
            'basic_salary'=>'required',
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],442);
        }
        else{
            Staffs::create([
                'name'=>$request->name,

                'educationID'=>$request->educationID,
                'depID'=>$request->depID,
                'positionID'=>$request->positionID,
                'basic_salary'=>$request->basic_salary
            ]);
            $staff=Staffs::all();
            return response()->json($staff,200);

        }
    }
    //ဝန်ထမ်းအသစ်ထဲ့ အဆုံး

    //ဝန်ထမ်းဖျက် အစ
    public function delete(Request $request){
        Staffs::destroy($request->id);
        $staff=Staffs::all();
        return response()->json($staff, 200);
    }
}
