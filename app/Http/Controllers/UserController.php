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
        
        if(\Auth::attempt($request->only('email','password'))){
            $user = Auth::user();
             $user = User::find(1); 

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
        $countries = Country::query()->get();
        return view('register', compact('countries'));
    }




    public function registeruser(Request $request){
        $request->validate([
            'name'=>'required',
            'email' => 'required|unique:users,email',
            'password'=>'required',
            'phone'=>'required',
            'country'=> 'required',
        ]);
        
        $registeredUser = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> \Hash::make($request->password),
            'phone'=>$request->phone,
            'country_id'=>$request->country,
            'ip'=>$request->ip(),
            'image'=>$request->input('image_url'),
            
        ]);
        $user = Auth::user();
        if(\Auth::attempt($request->only('email','password'))){
             return redirect()->back()->with('success', 'User created successfully');
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
             $usersByCountry = User::select('country_id', DB::raw('count(*) as total'))
                 ->groupBy('country_id')
                 ->get();
             $data = [];
             foreach ($usersByCountry as $user) {
                $c = Country::select(['name'])->where('id', $user->country_id)->first();
                 $data[] = [
                    'label' => $c->name,
                     'y' => $user->total,
                 ];
             }
             return view('countrygraph', compact('data'));
         }
         





    public function chartuserdetail(Request $request){
        $country = $request->query('country');
        $cid = Country::select(['id'])->where('name', $country)->first();
        
        $users = User::select(['name', 'email', 'phone', 'country_id'])->where('country_id', $cid->id)->get();
        $cIdToReplaceIdWithName;
        foreach ($users as $user) {
            $cIdToReplaceIdWithName = $user->country_id;
        }
        
        $c = Country::select(['name'])->where('id', $cIdToReplaceIdWithName)->first();
        $data = [
            'users' => $users,
        ];
        if ($request->expectsJson()) {
            return response()->json($data);
        }
        return view('chartuserdetail', compact('data', 'c'));
    }
    





    public function allusers(Request $request){
        if ($request->ajax()) {
            $query = User::query();
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
        $startDate = Carbon::now()->subDays(30);
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





    public function crudfunctionsview(Request $request){
        $countries = Country::query()->get();
        return view('crudfunctions', compact('countries'));
    }








    public function crudfunctions(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();
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

        }
    
        return view('crudfunctions');
    }





    // is ka kam ab register method kr rha ha 
    public function create(Request $request){
       
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name'=>'required',
            'email' => 'required|unique:users,email',
            'phone'=>'required',
            'country'=> 'required',
        ]);

       
            $registeredUser = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=> \Hash::make($request->password),
                'phone'=>$request->phone,
                'country_id'=>$request->country,
                'ip'=>$request->ip(),
                'image'=>$request->input('image_url'),
                
            ]);
            
        $users = User::select(['id', 'name', 'email', 'phone', 'country_id'])
        ->get();

        return DataTables::of($users)->addIndexColumn()
                ->addColumn('action', function ($user) {
                     return '
                        <button onclick="viewClicked(' . $user->id . ')" id="viewUserBtn" class="btn btn-view">View</button>
                        <button onclick="editClicked(' . $user->id . ')" id="editUserBtn" class="btn btn-edit">Edit</button>
                        <button onclick="deleteClicked(' . $user->id . ')"  id="deleteCrud" type="button" class="btn btn-delete">Delete</button>
                    ';
                })->rawColumns(['action'])
                ->make(true);
    }

    



    // this method is deleting user from Yajra table
    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        $users = User::select(['id', 'name', 'email', 'phone', 'country_id'])
        ->get();

        return DataTables::of($users)->addIndexColumn()
                ->addColumn('action', function ($user) {
                     return '
                        <button onclick="viewClicked(' . $user->id . ')" id="viewUserBtn" class="btn btn-view">View</button>
                        <button onclick="editClicked(' . $user->id . ')" id="editUserBtn" class="btn btn-edit">Edit</button>
                        <button onclick="deleteClicked(' . $user->id . ')"  id="deleteCrud" type="button" class="btn btn-delete">Delete</button>
                    ';
                })->rawColumns(['action'])
                ->make(true);
    }




    public function fetchuserdata($id){
        
                $user = User::find($id);

                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'country' => $user->country_id,
                ];
                
                return response()->json($data);
    }







    public function updateuser(Request $request, $id)
        {
            $newName = $request->input('name');
            $newEmail = $request->input('email');
            $newPhone = $request->input('phone');
            $newCountry = $request->input('country');
            
            $user = User::find($id);

            $user->name = $newName;
            $user->email = $newEmail;
            $user->phone = $newPhone;
            $user->country_id = $newCountry;
            $user->ip = $request->ip();
            $user->save();

            $users = User::select(['id', 'name', 'email', 'phone', 'country_id'])
            ->get();
    
            return DataTables::of($users)->addIndexColumn()
                    ->addColumn('action', function ($user) {
                         return '
                            <button onclick="viewClicked(' . $user->id . ')" id="viewUserBtn" class="btn btn-view">View</button>
                            <button onclick="editClicked(' . $user->id . ')" id="editUserBtn" class="btn btn-edit">Edit</button>
                            <button onclick="deleteClicked(' . $user->id . ')"  id="deleteCrud" type="button" class="btn btn-delete">Delete</button>
                        ';
                    })->rawColumns(['action'])
                    ->make(true);
        }










    public function show($id)
    {
        $user = Crud::query($id)->where('id', $id)->select(['name', 'description'])->first();
        return view('crudview', compact('user'));
    }







    public function crudupdate(Request $request, $id){
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $user = Crud::findOrFail($id);

        // Update the user data
        $user->name = $validatedData['name'];
        $user->description = $validatedData['description'];
        $user->save();
        return ("Data Updated successfully");
  
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
            $usersByCountry = User::select('country_id', DB::raw('count(*) as total'))
                ->groupBy('country_id')
                ->get();
        
            // Prepare the data in the required format for the graph
            $data = [];
            foreach ($usersByCountry as $user) {
            $c = Country::select(['name'])->where('id', $user->country_id)->first();
                $data[] = [
                //  'label' => $user->country_id,
                'label' => $c->name,
                    'y' => $user->total,
                ];
            }
             
           

             // crud functions view 
             $users = Crud::query()->get();
             $countries = Country::query()->get();

            
        return view('crudngraphs', compact('userRegistrations', 'data', 'users', 'countries'));
        
    }








    public function email(){
        return view('email');
    }









    public function getcountries(Request $request){

        $url = 'https://restcountries.com/v3.1/all';

        $response = file_get_contents($url);
        if ($response === false) {
        } else {
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
