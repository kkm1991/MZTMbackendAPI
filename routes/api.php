<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepsController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\DebtRecordController;
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
    Route::patch('edit/position',[PositionController::class,'edit']);
    Route::patch('edit/deps',[DepsController::class,'edit']);
    Route::patch('edit/education',[EducationController::class,'edit']);
    Route::post('add/position',[PositionController::class,'add']);
    Route::post('add/deps',[DepsController::class,'add']);
    Route::post('add/education',[EducationController::class,'add']);
    Route::delete('delete/position',[PositionController::class,'delete']);
    Route::delete('delete/deps',[DepsController::class,'delete']);
    Route::delete('delete/education',[EducationController::class,'delete']);
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
    Route::post('update/monthly',[MonthlyReservationController::class,'updateReservation']);
    Route::post('update/default',[DefaultReservationController::class,'updateReservation']);
    Route::get('list/monthly',[MonthlyReservationController::class,'getMonthlyList']);
    Route::get('search/monthly',[MonthlyReservationController::class,'searchMonthlyList']);

});

Route::middleware(['auth:sanctum'])->prefix('salary')->group(function(){
    Route::get('add',[SalaryController::class,'addSalary']);
    Route::get('list',[SalaryController::class,'loadSalary']);
    Route::get('search',[SalaryController::class,'searchsalary']);
    Route::patch('update',[SalaryController::class,'updatesalary']);
    Route::get('delete',[SalaryController::class,'deletesalary']);
    Route::get('report',[SalaryController::class,'salaryReport']);
    Route::get('slip',[SalaryController::class,'slip']);
});

Route::middleware(['auth:sanctum'])->prefix('debt')->group(function(){
    Route::get('records',[DebtRecordController::class,'records']);
    Route::post('add/loan',[DebtRecordController::class,'addnew']);
    Route::delete('delete/loan',[DebtRecordController::class,'loandelete']);
    Route::patch('update/loan',[DebtRecordController::class,'loanupdate']);
});
