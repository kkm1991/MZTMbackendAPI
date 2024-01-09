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
        })
        ->select('staffs.*','deps.title as deptitle','education.title as educationtitle','positions.title as positiontitle')
        ->leftJoin('deps','staffs.depID','=','deps.id')
        ->leftJoin('education','staffs.educationID','=','education.id')
        ->leftJoin('positions','staffs.positionID','=','positions.id')
        ->orderby('id','desc')
        ->get();

        return response()->json($stafflist, 200);
    }
    //ဝန်ထမ်းအသစ်ထဲ့ အစ
    public function createprofile(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'educationID'=>'required',
            'start_working_date'=>'required',
            'depID'=>'required',
            'positionID'=>'required',
            'basic_salary'=>'required',
            'image'=>'mimes:jpg,png,jpeg'
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],442);
        }
        else{
            $imagepath=null;
            if($request->File("image")){
                $image=$request->image;
                $imagename=time().'.'.$image->getClientOriginalExtension();
                $image->storeAs('uploads',$imagename,'public');
                $imagepath=$imagename;
            }

            Staffs::create([
                'name'=>$request->name,
                'father_name'=>$request->father_name,
                'start_working_date'=>$request->start_working_date,
                'dob'=>$request->dob,
                'educationID'=>$request->educationID,
                'depID'=>$request->depID,
                'positionID'=>$request->positionID,
                'basic_salary'=>$request->basic_salary,
                'image'=>$imagepath,


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
    //ဝန်ထမ်းဖျက်အဆုံး

    public function changestatus(Request $request){
        Staffs::where("id",$request->staff_id)->update([
            'active_status'=>$request->userstatus
        ]);
       return $this->responsesStaff($request->key);

    }


    public function responsesStaff($key){
        $stafflist=Staffs::when($key,function($query) use($key){
            $query->where('depID',$key);
        })
        ->select('staffs.*','deps.title as deptitle','education.title as educationtitle','positions.title as positiontitle')
        ->leftJoin('deps','staffs.depID','=','deps.id')
        ->leftJoin('education','staffs.educationID','=','education.id')
        ->leftJoin('positions','staffs.positionID','=','positions.id')
        ->orderby('id','desc')
        ->get();

        return response()->json($stafflist, 200);
    }
}
