<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function uploadImage(Request $request)
    {
        $file = $request->file('image');

        $serviceAccount = [
            'type' => 'service_account',
            'project_id' => env('FIREBASE_PROJECT_ID'),
            'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
            'private_key' => env('FIREBASE_PRIVATE_KEY'),
            'client_email' => env('FIREBASE_CLIENT_EMAIL'),
            'client_id' => env('FIREBASE_CLIENT_ID'),
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://accounts.google.com/o/oauth2/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => env('FIREBASE_CLIENT_CERT_URL'),
        ];

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();

        $storage = $firebase->getStorage();
        $bucket = $storage->getBucket();

        $imagePath = 'images/' . $file->getClientOriginalName();

        $bucket->upload(
            file_get_contents($file),
            [
                'name' => $imagePath
            ]
        );

        $expiration = new \DateTime('now');
        $expiration->modify('+1 hour');
        $imageUrl = $bucket->object($imagePath)->signedUrl($expiration);

        // Return the URL of the uploaded image
        return $imageUrl;
    }

    public function uploadfirebase(){
        return view('uploadimage');
    }
}
