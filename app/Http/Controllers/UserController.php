<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Crud;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UserController extends Controller
{
    public function login(Request $request){
         return view('login');
    }

    public function loginuser(Request $request){
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

    public function registeruser(Request $request){
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
         
    public function chartuserdetail(Request $request){
        $country = $request->query('country');
        $users = User::where('country', $country)->get();
        $data = [
            'users' => $users
        ];
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        return view('chartuserdetail', compact('data'));
    }
    

    public function allusers(Request $request){
        
        
        if ($request->ajax()) {
            $query = User::query();
            
            
            // Apply filters
            if ($request->has('country')) {
                $query->where('country', $request->country)->select(['name', 'email', 'phone', 'country']);
            }
    
            $users = $query->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->make(true);
        }
        
        return view('allusers');
    }

   

    public function dailyuserregistration(){
        $startDate = Carbon::now()->subDays(30); // Retrieve data for the last 7 days
        $endDate = Carbon::now();

        $userRegistrations = User::select('created_at')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($item) {
                return $item->count();
            });
            
        return view('dailyuserregistration', compact('userRegistrations'));
    }

    public function crudfunctions()
    {
        $users = Crud::query()->get();
        return view('crudfunctions', compact('users'));
    }

    public function createuser(Request $request)
    {
        return view('createuser');
        // $user = User::create($request->all());
        // return response()->json(['data' => $user], 201);
    }

    public function useradded(Request $request){
       
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'description' => 'required|string',
        ]);

        // Create a new user instance
        $user = new Crud();
        $user->name = $validatedData['name'];
        $user->description = $validatedData['description'];
        $ipAddress = $request->ip();
        $user->ip = $ipAddress;

        // Save the user to the database
        $user->save();
        return redirect()->back()->with('success', 'User created successfully');
        
    }

    public function cruddelete($id){
        $user = Crud::findOrFail($id);
        $user->delete();
        return ("User deleted successfully Go back and Refresh");
        // return redirect()->route('')->with('success', 'User deleted successfully.')
    }

    public function show($id)
    {
        // $user = Crud::findOrFail($id);
        // return response()->json(['data' => $user]);
        $user = Crud::query($id)->where('id', $id)->select(['name', 'description'])->first();

        return view('crudview', compact('user'));
    }

    public function crudupdate(Request $request, $id){
  
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        // Find the user by ID
        $user = Crud::findOrFail($id);

        // Update the user data
        $user->name = $validatedData['name'];
        $user->description = $validatedData['description'];
        $user->save();
        return ("Data Updated successfully");
        // Redirect to the view page or wherever you want
        // return redirect()->route('', $user->id)->with('success', 'User updated successfully');
  
    }

    public function crudedit(Request $request, $id){
        $user = Crud::query($id)->where('id', $id)->select(['id', 'name', 'description'])->first();

        return view('crudedit', compact('user'));
    }


    

}
