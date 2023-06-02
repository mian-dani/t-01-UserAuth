<?php

namespace App\Http\Controllers;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Storage;
use Illuminate\Http\Request;
use App\Models\Url;


class FirebaseController extends Controller
{
    
    public function saveUrl(Request $request)
    {
        
        {   
            $url =$request->url;
            $urlOfImage= Url::create([
                'url'=> $url
            ]);
            $urlOfImage->save();
            return response()->json(['message' => 'Image URL saved successfully']);
        }
        
    }
    
    public function uploadfirebase(){
        return view('uploadimage');
    }
}
