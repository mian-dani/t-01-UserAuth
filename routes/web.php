<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('userform');
});

Route::group(['middleware'=>'guest'],function(){
Route::get('/login',[UserController::class, 'login'])->name('login');
Route::post('/login',[UserController::class, 'login_user'])->name('login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'register_user'])->name('register');
});



Route::group(['middleware'=>'auth'],function(){
    Route::get('/table',[UserController::class, 'table'])->name('table');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/userdetails', [UserController::class, 'userdetails'])->name('userdetails');
    Route::get('/userdetailsyajra', [UserController::class, 'userdetailsyajra'])->name('userdetailsyajra');
});

// 