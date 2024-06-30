<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrancheController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PhoneTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserTypeController;


Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::group(['middleware'=>["auth:sanctum"]],function(){
    //BrancheRoute
    Route::post('/branche',[BrancheController::class, 'store']);
    Route::put('/branche/{branche}',[BrancheController::class, 'update']);
    Route::delete('/branche/{branche}',[BrancheController::class, 'destroy']);
    Route::get('/branche/{branche}',[BrancheController::class, 'show']);
    Route::get('/branches',[BrancheController::class, 'index']);

    //UserTypeRoute
    Route::post('/userType',[UserTypeController::class, 'store']);
    Route::put('/userType/{userType}',[UserTypeController::class, 'update']);
    Route::delete('/userType/{userType}',[UserTypeController::class, 'destroy']);
    Route::get('/userTypes',[UserTypeController::class, 'index']);

    //TransactionRoute
   
    Route::get('/transaction/{transaction}',[TransactionController::class, 'show']);
    Route::get('/transactions',[TransactionController::class, 'index']);
    Route::get('/transactions/currency/{currencyCode}',[TransactionController::class, 'transactionByCurrency']);
    Route::post('/transactionDelete/{transaction}',[TransactionController::class, 'destroy']);
    Route::post('/transactions',[TransactionController::class, 'store']);
    Route::post('/transaction/{transaction}',[TransactionController::class, 'update']);


    //CurrencyRoute
    Route::post('/currency',[CurrencyController::class, 'store']);
    Route::put('/currency/{currency}',[CurrencyController::class, 'update']);
    Route::delete('/currency/{currency}',[CurrencyController::class, 'destroy']);
    Route::get('/currencies',[CurrencyController::class, 'index']);

    //UserRoute
    Route::get('user/allUsers',[AuthController::class, 'allUsers']);

     //PhoneTypeRoute
    Route::post('phoneType/create',[PhoneTypeController::class, 'store']);
    Route::get('/phoneTypes',[PhoneTypeController::class, 'index']);

    //AuthRoute
    Route::get('/logout',[AuthController::class, 'logout']);
    Route::get('/profile',[AuthController::class, 'profile']);
   
});




// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');