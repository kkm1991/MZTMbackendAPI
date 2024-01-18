<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepsController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\DefaultReservationController;
use App\Http\Controllers\MonthlyReservationController;

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

 Route::post('login',[AuthController::class,'login']);
 // Register မလုပ်ခင် info ဖြည့်တဲ့နေရာမှာခေါ်သုံးဖို.
 Route::prefix('list')->group(function(){
    Route::get('deps',[DepsController::class,'list']);
    Route::get('positions',[PositionController::class,'list']);
    Route::get('educations',[EducationController::class,'list']);
 });

 Route::middleware(['auth:sanctum'])->prefix('staffs')->group(function () {
     Route::post('register',[AuthController::class,'register']);
     Route::get('list',[StaffsController::class,'list']);
     Route::post('create',[StaffsController::class,'createprofile']);
     Route::get('delete',[StaffsController::class,'delete']);
     Route::post('status',[StaffsController::class,'changestatus']);
     Route::get('payment',[StaffsController::class,'paymentlist']);
 });

Route::middleware(['auth:sanctum'])->prefix('reservation')->group(function(){
    Route::post('add/monthly',[MonthlyReservationController::class,'add']); // create နဲ့ update ကိုပေါင်းရေးထားတယ်
    Route::post('add/default',[DefaultReservationController::class,'add']);
    Route::get('load/monthly',[MonthlyReservationController::class,'loadReservation']);
    Route::get('load/default',[DefaultReservationController::class,'loadReservation']);
});
