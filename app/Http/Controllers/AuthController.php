<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
      $validator=  Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()],422);
        }
        else{
            $user=User::where('email',$request->email)->first();

            if($user){
                if(Hash::check($request->password, $user->password)){
                    return response()->json([
                        'user'=>$user,
                        'token'=>$user->createToken(time())->plainTextToken
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
            'role'=>'required'
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
                    'role'=>$request->role
                ]);
                $user=User::where('email',$request->email)->first();
                return response()->json([
                    'user'=>$user,
                    'token'=>$user->createToken(time())->plainTextToken
                ], 200 );
            }
    }
}

