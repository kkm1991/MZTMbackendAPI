<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    //login
    public function login(Request $request){
        //frontend က data ပါလာလားစစ်တာ
      $validator=  Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ]);

        //data မပါလာရင် error response ပြန်
        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],422);
        }
        else{
            $user=User::where('email',$request->email)->first(); //frontend ကပို.လိုက်တဲ့ email နဲ့တူတဲ့ user ကိုခေါ်တယ်

            //email နဲ့ကိုက်တဲ့ user ရှိရင်
            if($user){
                // frontend ကထဲ့လိုက်တဲ့ password နဲ့ database ထဲမှာရှိတဲ့ password နဲ့တိုက်စစ်တယ်
                if(Hash::check($request->password, $user->password)){
                    return response()->json([
                        'user'=>$user, //user info
                        'token'=>$user->createToken(time())->plainTextToken //token
                    ], 200 );
                }
                else{
                    return response()->json(['message'=>'Wrong passsword']);
                }
            }
            else{
                return response()->json(['message'=>'Not registered account']);
            }
        }
    }

    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'confirm_password'=>'required|same:password',
            'dep'=>'required',

        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],442);
        }
        else{
            $emailcheck=User::where('email',$request->email)->first();
                if($emailcheck){
                    return response()->json(['message'=>'This email account already registered']);
                }
                User::create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=>$request->password,
                    'role'=>$request->role,
                    'dep'=>$request->dep
                ]);
                $user=User::where('email',$request->email)->first();
                return response()->json([
                    'user'=>$user,

                ], 200 );
            }
    }
}

