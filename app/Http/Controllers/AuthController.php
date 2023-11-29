<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //register user
    public function register(Request $request){
        //Validate fields
        $attrs = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);

        //Create user
        $user = User::create([
            'name'=> $attrs['name'],
            'email'=> $attrs['email'],
            'password'=> $attrs['password'],
        ]);

        //return user and token in response
        return response([
            'user'=>$user,
            'token'=>$user->createToken('secret')->plainTextToken
        ]);
    }

    //Login User
    public function login(Request $request){
        //Validate fields
        $attrs = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Invalid credentials.',
            ], 403);
        }
        
        
        //return user and token in response
        return response([
            'user'=>auth()->user(),
            'token'=>auth()->user()->createToken('secret')->plainTextToken
        ],200);
    }

    //Logout User
    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'Logout success',
        ],200);
    }

    //get user details
    public function user(){
        return response([
            'user' => auth()->user(),
        ]);
    }

    //Update user
    public function update(Request $request, $id){
        $attrs=$request->validate([
            'name'=>'required|string'
        ]);

        $image = $this->saveImage($request->image, 'profile');

        auth()->user()->update([
            'name'=>$attrs['name'],
            'image'=>$image
        ]);

        return response([
            'message'=>'user updated',
            'user'=>auth()->user(),
        ],200);
    }
    
}
