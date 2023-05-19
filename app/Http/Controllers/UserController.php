<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;


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

    public function userdetails(){
         
        $id=  Auth::user()->id;
        $email= Auth::user()->email;
        
        $users = User::where('id', $id)->get();
        // foreach($u as $us){
            
        //     $name= $us->name;
        //     dd($name);
        // }
        //  dd($users->name);
        // return view('userdetails', compact('users'));
        return view('userdetails')->with('users', $users);
         
    }

    public function userdetailsyajra(Request $request){

        $id=  Auth::user()->id;
        

             if ($request->ajax()) {
                $data = User::query()->where('id', $id)->select(['name', 'email', 'phone', 'country']);
                
                 return DataTables::of($data)->addIndexColumn()->make(true);
                
             }
              return view('userdetailsyajra');
        
    }

    public function countrygraph(){
         
             // Fetch the data needed for the graph
             $usersByCountry = User::select('country', DB::raw('count(*) as total'))
                 ->groupBy('country')
                 ->get();
         
             // Prepare the data in the required format for the graph
             $data = [];
             foreach ($usersByCountry as $user) {
                 $data[] = [
                     'label' => $user->country,
                     'y' => $user->total,
                 ];
             }
         
             // Pass the data to the view
             return view('countrygraph', compact('data'));
         }
         
        
    

}
