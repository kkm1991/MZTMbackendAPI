<?php

namespace App\Http\Controllers;

use App\Models\salary;
use App\Models\Staffs;
use Illuminate\Http\Request;
use App\Models\defaultReservation;
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
    //ဝန်ထမ်း create လုပ်တာနဲ့ update လုပ်တာကို တစ်ခုတည်းပေါင်းရေးထားတယ်
    // frontend က ပို.လိုက်တဲ့ request မှာ id ထဲ့ပေးမပေးပေါ်မူတည်ပြီး id ပါလာတယ်ဆို update (ပြင်မယ်) id မပါရင် ဝန်ထမ်းအသစ်ထဲ့
    public function createprofile(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'educationID'=>'required',
            'start_working_date'=>'required',
            'depID'=>'required',
            'positionID'=>'required',
            'basic_salary'=>'required',
        ]);
        // အပေါ်က validator စစ်တာ fail ရင် error message response ပြန်မယ်
        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],442);
        }
        // validator စစ်တာ အဆင်ပြေရင်အောက်ကအလုပ်လုပ်မယ်
        else{
                // အရင်ဆုံး request ထဲက id ကိုကြည့်လိုက်တယ် table ထဲမှာရှိတာနဲ့ရှာလိုက်တယ်
                $updatestaff=Staffs::find($request->id);

                //ရှိရင်အောက်ကအလုပ်လုပ်မယ်(update)
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
                    //request ထဲမှာ image file ပါလာလားစစ်တယ်
                   if($request->hasfile("image")){
                    $oldimage=$updatestaff->image;
                    //ဓာတ်ပုံပါလာရင် အရင်ကပုံအဟောင်းကိုဖျက်တယ်
                    if(File::exists(public_path().'/storage/uploads/'.$oldimage)){
                       File::delete(public_path().'/storage/uploads/'.$oldimage);
                    }
                    //ပုံအသစ်ကိုသိမ်းတယ်
                    $image=$request->file('image');
                    $imagename=time().'.'.$image->getClientOriginalExtension();
                    $image->storeAs('uploads',$imagename,'public');
                    //database ထဲကိုလည်းပုံအသစ်သိမ်းတယ်
                    $updatestaff->image=$imagename;
                    }
                    $updatestaff->update();
                }
                // မရှိရင် အောက်ကအလုပ်လုပ်မယ် create
                else{

                    // ဓါတ်ပုံသိမ်းတယ် တစ်ခါတည်း ဝန်ထမ်းအသစ် create လုပ်တယ်
                    $imagestore=null;
                    if($request->hasFile('image')){
                        $image=$request->file('image');
                        $imagename=time().'.'.$image->getClientOriginalExtension();
                        $image->storeAs('uploads',$imagename,'public');
                        $imagestore=$imagename;
                    }



                     $createdstaff=Staffs::create([
                        'name'=>$request->name,
                        'nrc'=>$request->nrc,
                        'father_name'=>$request->father_name,
                        'start_working_date'=>$request->start_working_date,
                        'dob'=>$request->dob,
                        'educationID'=>$request->educationID,
                        'depID'=>$request->depID,
                        'positionID'=>$request->positionID,
                        'basic_salary'=>$request->basic_salary,
                        'image'=>$imagestore,


                    ]);

                    //default reservation ကို staff create လုပ်ကတည်းက ထဲ့ထားတယ်နောက်မှပြန်မထဲ့ချင်လို.
                    $this->createDefaultReservation($createdstaff->id);
                }
                return $this->responsesStaff($request->key);


        }
    }
    //ဝန်ထမ်းအသစ်ထဲ့ အဆုံး


    public function createDefaultReservation($staffid){
        defaultReservation::create([
            'rareCost'=>0,
            'bonus'=>0,
            'attendedBonus'=>0,
            'busFee'=>0,
            'mealDeduct'=>0,
            'absence'=>0,
            'ssbFee'=>0,
            'fine'=>0,
            'redeem'=>0,
            'advance_salary'=>0,

            'otherDeduct'=>0,
            'staff_id'=>$staffid
        ]);
    }

    public function deleteDefaultReservation($staffid){
        $deletedefaultreservation=defaultReservation::where('staff_id',$staffid);
        $deletedefaultreservation->delete();
    }

    //ဝန်ထမ်းဖျက် အစ

    public function delete(Request $request){
       $deletestaff= Staffs::find($request->id);
       if($deletestaff){
        if(File::exists(public_path().'/storage/uploads/'.$deletestaff->image)){
            File::delete(public_path().'/storage/uploads/'.$deletestaff->image);
         }
         $deletestaff->delete();

         $this->deleteDefaultReservation($request->id);
         return $this->responsesStaff($request->key);
       }
       else{
        return response()->json(['Message'=>'Staff not found']);
       }
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

    public function paymentlist(Request $request){
    //ယခုလတွက်လစာပေးပြီးသား သူတွေရဲ့ id တွေကိုပဲဆွဲထုတ်လိုက်ပြီး $staffIds ထဲထဲ့လိုက်တယ်
    $currentMonth = date('m');
    $currentYear = date('Y');
        $staffInSalary=salary::whereMonth('created_at',$currentMonth)
        ->whereYear('created_at',$currentYear)
        ->pluck('staff_id') //id တစ်column ပဲဆွဲထုတ်တာ
        ->toArray();


        $stafflist=Staffs::when($request->key,function($query) use($request){
            $query->where('active_status',$request->key);

        })
        ->select('staffs.*','deps.title as deptitle','education.title as educationtitle','positions.title as positiontitle')
        ->leftJoin('deps','staffs.depID','=','deps.id')
        ->leftJoin('education','staffs.educationID','=','education.id')
        ->leftJoin('positions','staffs.positionID','=','positions.id')
        ->orderby('id','desc')
        ->whereNotIn('staffs.id',$staffInSalary)//အပေါ်က ယခုလအတွက်လစာပေးစာရင်းထဲမှာပါတဲ့ ဝန်ထမ်းတွေမပါအောင်လုပ်တာ
        ->get();

        return response()->json($stafflist, 200);
    }


}
