<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request){
         return view('login');
    }

    public function login_user(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //login code
        if(\Auth::attempt($request->only('email','password'))){
            return redirect('table');
        }
        return redirect('login')->withError('Login not successful');
    }

    public function register(){
        return view('register');
    }

    public function register_user(Request $request){
        //validation
        $request->validate([
            'name'=>'required',
            'email' => 'required|unique:users,email',
            'password'=>'required',
            'phone'=>'required',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> \Hash::make($request->password),
            'phone'=>$request->phone,
            'country'=>$request->country,
        ]);

        //if user successfully registered then also login
        if(\Auth::attempt($request->only('email','password'))){
            return redirect('table');
        }
        
        return redirect('register');
    }


    public function table(){
        return view('table');
    }

    public function logout(){
        \Session::flush();
        \Auth::logout();
        return redirect('register');
    }

}
