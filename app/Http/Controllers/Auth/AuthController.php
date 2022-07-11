<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Constants\ResponseCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //For validation
       $validator = Validator::make($request->all(), [
        'name'=>'required|min:3|max:20',
        'password'=>'required',
        'email'=>'required|unique:users|email'
       ]);

    if ($validator->fails()) {
       $error= $validator->errors();
       return response()->json(['error'=>$error],ResponseCode::VALIDATION_ERROR);
    }
       try{
        //Save New User
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->role_id=User::NORMAL_USER;
        $user->save();
        return response()->json(['data'=>$user,'message'=>'User have been saved'],ResponseCode::CREATED);
       }catch(Exception $e){
        return response()->json(['error'=>$e->getMessage()],ResponseCode::SERVER_ERROR);
       }
    }
    public function login(Request $request)
    {
        //For User Validation
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required'
           ]);
    if ($validator->fails()) {
        $error= $validator->errors();
        return response()->json(['error'=>$error],ResponseCode::VALIDATION_ERROR);
    }
    try{
        //login User
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['data'=>'','message'=>'User Name and Password incorrect'],ResponseCode::LOGIN_FAILURE);    
        }
        return response()->json(['data'=>Auth::user(),'message'=>'Login Sucessfull'],ResponseCode::SUCESS);
       }catch(Exception $e){
        return response()->json(['error'=>$e->getMessage()],ResponseCode::SERVER_ERROR);
       }
    }




}
