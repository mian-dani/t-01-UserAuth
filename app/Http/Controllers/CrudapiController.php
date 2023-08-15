<?php

namespace App\Http\Controllers;
use App\Models\Employee;

use Illuminate\Http\Request;

class CrudapiController extends Controller
{


    public function index(Request $request){
        $employee = Employee::all();
        return response()->json($employee);
    }




    public function show(Request $request, $id){
        $emp = Employee::findOrFail($id);
        return response()->json($emp);
    }



    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'email' => 'required|unique:employee,email',
            'job'=>'required',
            'age'=>'required',
        ]);
        $registeredUser = Employee::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'job'=>$request->job,
            'age'=>$request->age,
        ]);
        
        return response()->json("User Created Successfully", 201);
    }










    public function update(Request $request, $id){
            $emp = Employee::findOrFail($id);

            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:employee,email,',
                'job' => 'required',
                'age' => 'required',
            ]);

            $emp->name = $request->input('name');
            $emp->email = $request->input('email');
            $emp->job = $request->input('job');
            $emp->age = $request->input('age');
            $emp->save();

            return response()->json("User updated successfully", $emp);
    }


    
    public function destroy(Request $request, $id){
        $product = Employee::findOrFail($id);
        $product->delete();
        return response()->json("User Deleted Successfully", 204);
    }
}
