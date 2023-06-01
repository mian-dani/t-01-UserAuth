<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudapiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/allemployee', [CrudapiController::class, 'index']);
Route::get('/employee/{id}', [CrudapiController::class, 'show']);
Route::post('/addemployee', [CrudapiController::class, 'store']);
Route::put('/updateemployee/{id}', [CrudapiController::class, 'update']);
Route::delete('/deleteemployee/{id}', [CrudapiController::class, 'destroy']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
