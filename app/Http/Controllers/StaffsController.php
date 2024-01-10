<?php

namespace App\Http\Controllers;

use App\Models\Staffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],442);
        }
        else{

                $updatestaff=Staffs::find($request->id);
                if($updatestaff){
                    $updatestaff->name=$request->name;
                    $updatestaff->nrc=$request->nrc;
                    $updatestaff->father_name=$request->father_name;
                    $updatestaff->start_working_date=$request->start_working_date;
                    $updatestaff->dob=$request->dob;
                    $updatestaff->educationID=$request->educationID;
                    $updatestaff->depID=$request->depID;
                    $updatestaff->positionID=$request->positionID;
                    $updatestaff->basic_salary=$request->basic_salary;
                   if($request->hasfile("image")){
                    $oldimage=$updatestaff->image;
                    if(File::exists(public_path().'/storage/uploads/'.$oldimage)){
                       File::delete(public_path().'/storage/uploads/'.$oldimage);

                    }
                    $image=$request->file('image');
                    $imagename=time().'.'.$image->getClientOriginalExtension();
                    $image->storeAs('uploads',$imagename,'public');
                    $updatestaff->image=$imagename;
                    }
                    $updatestaff->update();
                }
                else{


                     $image=$request->file('image');
                     $imagename=time().'.'.$image->getClientOriginalExtension();
                     $image->storeAs('uploads',$imagename,'public');


                     Staffs::create([
                        'name'=>$request->name,
                        'nrc'=>$request->nrc,
                        'father_name'=>$request->father_name,
                        'start_working_date'=>$request->start_working_date,
                        'dob'=>$request->dob,
                        'educationID'=>$request->educationID,
                        'depID'=>$request->depID,
                        'positionID'=>$request->positionID,
                        'basic_salary'=>$request->basic_salary,
                        'image'=>$imagename,


                    ]);


            //     $newstaff=new Staffs();
            //     $newstaff->name=$request->name;
            //     $newstaff->nrc=$request->nrc;
            //     $newstaff->father_name=$request->father_name;
            //     $newstaff->start_working_date=$request->start_working_date;
            //     $newstaff->dob=$request->dob;
            //     $newstaff->educationID=$request->educationID;
            //     $newstaff->depID=$request->depID;
            //     $newstaff->positionID=$request->positionID;
            //     $newstaff->basic_salary=$request->basic_salary;
            //
            //     $newstaff->save();
                }












            $staff=Staffs::all();
            return response()->json($staff,200);

        }
    }
    //ဝန်ထမ်းအသစ်ထဲ့ အဆုံး

    //ဝန်ထမ်းဖျက် အစ
    public function delete(Request $request){
       $deletestaff= Staffs::find($request->id);
        if(File::exists(public_path().'/storage/uploads/'.$deletestaff->image)){
            File::delete(public_path().'/storage/uploads/'.$deletestaff->image);
         }
         $deletestaff->delete();
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
