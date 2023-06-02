<?php

namespace App\Http\Controllers;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Crud;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Jobs\WelcomeEmail;
use App\Models\Country;



use Illuminate\Support\Facades\Queue;

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
            $user = Auth::user();
             // Inside your UserController or other relevant code
             $user = User::find(1); // Get the user instance

             Queue::push(new WelcomeEmail($user));
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $response = [
                'success'=> true,
                'data' => $success,
                'message' => "user registered successfully"
            ];
            

           

            return response()->json($response, 200);
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
        $registeredUser = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> \Hash::make($request->password),
            'phone'=>$request->phone,
            'country'=>$request->country,
        ]);
        
        
        // $user = User::find(1); // Get the user instance

        // // Send the email
        // Mail::to($user->email)->send(new WelcomeMail($user));


        $user = Auth::user();
        // $success['token'] = $user->createToken('MyApp')->plainTextToken;
        // $success['name'] = $user->name;

        // $response = [
        //     'success'=> true,
        //     'data' => $success,
        //     'message' => "user registered successfully"
        // ];
        Mail::to($request->input('email'))->send(new WelcomeMail($request->input('name')));
        // return response()->json($response, 200);

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
            if ($request->filled('country')) {
                $query->where('country', $request->country)->select(['name', 'email', 'phone', 'country']);
            }

            if ($request->filled('startdate') && $request->filled('enddate')) {
                $start_date= $request->startdate;
                $end_date= $request->enddate;
                $query->whereBetween('created_at', [$start_date, $end_date])->select(['name', 'email', 'phone', 'country']);
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

    public function crudfunctions(Request $request)
    {
    
        if ($request->ajax()) {
            $query = Crud::query();
            $users = $query->get();

            return DataTables::of($users)->addIndexColumn()
                ->addColumn('action', function ($user) {
                     return '
                        <button onclick="viewClicked(' . $user->id . ')" id="viewUserBtn" class="btn btn-view">View</button>
                        <button onclick="editClicked(' . $user->id . ')" id="editUserBtn" class="btn btn-edit">Edit</button>
                        <button onclick="deleteClicked(' . $user->id . ')"  id="deleteCrud" type="button" class="btn btn-delete">Delete</button>
                    ';
                })->rawColumns(['action'])
                ->make(true);


                // ->addIndexColumn()
                // ->make(true);
        }
    
        return view('crudfunctions') ;
    }

    // public function createuser(Request $request)
    // {
    //     return view('createuser');
    //     // $user = User::create($request->all());
    //     // return response()->json(['data' => $user], 201);
    // }

    public function create(Request $request){
       
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

    

    public function delete($id){
      
        $user = Crud::findOrFail($id);
        
        $user->delete();
        return redirect()->back()->with('success', 'User deleted');

    }

    public function fetchuserdata($id){
        
                $user = Crud::find($id);

                $data = [
                    'name' => $user->name,
                    'description' => $user->description,
                ];
                
                return response()->json($data);
            
    }

    public function updateuser(Request $request, $id)
        {
            // $userId = $request->input('id');
            $newName = $request->input('name');
            $newDescription = $request->input('description');

            // Find the user by ID
            $user = Crud::find($id);

            // if (!$user) {
            //     // User not found, handle the error
            //     return response()->json(['error' => 'User not found'], 404);
            // }

            // Update the user data
            $user->name = $newName;
            $user->description = $newDescription;
            $user->save();

            // Return a success response
            return redirect()->back()->with('success', 'User deleted');
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

    public function crudngraphs(){

        //Daily user registration
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

            //country graph data 
             
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

             // crud functions view 
             $users = Crud::query()->get();
            
        return view('crudngraphs', compact('userRegistrations', 'data', 'users'));

        
    }

    public function email(){
        return view('email');
    }

    public function getcountries(Request $request){

                $url = 'https://restcountries.com/v3.1/all';

        $response = file_get_contents($url);
        
        if ($response === false) {
            // Handle error
            // Error handling logic goes here
        } else {
            // Process the response
            $countries = json_decode($response, true);

            foreach ($countries as $country){
                $name = $country['name']['common'];

                if(isset($country['currencies'])){
                    $changingCurrency = key($country['currencies']);
                    $currency = $country['currencies'][$changingCurrency]['name'];
                    if(isset($country['currencies'][$changingCurrency]['symbol'])){
                        $symbol = $country['currencies'][$changingCurrency]['symbol'];
                    }
                    
                }else{
                    continue;
                }
                
                $flagUrl= $country['flags']['png'];

                $countryModel = new Country();
                $countryModel->name = $name;
                $countryModel->currency = $currency;
                $countryModel->symbol = $symbol;
                $countryModel->flagurl = $flagUrl;
                $countryModel->save();
            
            }
            

            
        }

    }
        
        
    
    
    public function getcountriesview(){
        return view('getcountries');
    }

    

}
