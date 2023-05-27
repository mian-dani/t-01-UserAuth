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

// Route::group(['middleware'=>'guest'],function(){
Route::get('/login',[UserController::class, 'login'])->name('login');
Route::post('/loginuser',[UserController::class, 'loginuser'])->name('loginuser');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/registeruser', [UserController::class, 'registeruser'])->name('registeruser');
Route::get('/allusers', [UserController::class, 'allusers'])->name('allusers');

// });



// Route::group(['middleware'=>'auth'],function(){
    Route::get('/table',[UserController::class, 'table'])->name('table');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/userdetails', [UserController::class, 'userdetails'])->name('userdetails');
    Route::get('/userdetailsyajra', [UserController::class, 'userdetailsyajra'])->name('userdetailsyajra');
    Route::get('/countrygraph', [UserController::class, 'countrygraph'])->name('countrygraph');
    Route::get('/chartuserdetail', [UserController::class, 'chartuserdetail'])->name('chartuserdetail');
    Route::get('/dailyuserregistration', [UserController::class, 'dailyuserregistration'])->name('dailyuserregistration');
// });

// 



Route::get('/crudfunctions', [UserController::class, 'crudfunctions'])->name('crudfunctions');
Route::get('/createuser', [UserController::class, 'createuser'])->name('createuser');
Route::post('/useradded', [UserController::class, 'useradded'])->name('useradded');
Route::get('/crudedit/{id}', [UserController::class, 'crudedit'])->name('crudedit');
Route::get('/crudshow/{id}', [UserController::class, 'crudedit'])->name('crudshow'); // wese hi bnaya ha abhi
Route::put('/crudupdate/{id}', [UserController::class, 'crudupdate'])->name('crudupdate');
Route::delete('/cruddelete/{id}', [UserController::class, 'cruddelete'])->name('cruddelete');


Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{id}', [UserController::class, 'edit'])->name('users.edit');


Route::get('/crudngraphs', [UserController::class, 'crudngraphs'])->name('crudngraphs');
Route::post('/users', [UserController::class, 'create'])->name('users.create');
Route::delete('/deletecrud/{id}', [UserController::class, 'delete'])->name('deletecrud');