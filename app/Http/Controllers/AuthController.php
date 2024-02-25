<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup(){
        $data=request()->validate([
            'username'=>['required','string','unique:users,username'],
            'phone'=>['required','string','min:7'],
            'password'=>['required','min:5']
        ]);
        $user=User::create($data);
        $token=$user->createToken(request()->ip(),[])
        ->plainTextToken;
        return [
            'message'=>"Success!.",
            'token'=>$token,
            'user'=>$user
        ];
    }

    public function login(){
        $data=request()->validate([
            'username'=>['required','string','min:3','exists:users,username'],
            'password'=>['required','string','min:5']
        ]);
        if(auth()->attempt($data)){
            $user=request()->user();
            $token=request()->user()
            ->createToken(request()->ip(),[])
            ->plainTextToken;
            $data=[
                'message'=>'Logged in successfully!.',
                'token'=>$token,
                'user'=>$user
            ];
            return $data;
        }else {
            return response([
                'message'=>'Wrong Credentials!.'
            ],403);
        }
    }

    public function logout(){
        request()->user()->currentAccessToken()->delete();
        return [
            'message'=>"logged out successfully!."
        ];
    }

}
