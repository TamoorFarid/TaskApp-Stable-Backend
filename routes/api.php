<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/createActivity',[ActivityController::class,'createActivity']);
Route::get('/getAllActivities',[ActivityController::class,'getAllActivities']);
Route::post('/createTask/{id}',[TaskController::class,'createTask']);
Route::get('/getTasksList/{id}',[TaskController::class,'getTasksList']);
Route::delete('/deleteTask/{id}',[TaskController::class,'deleteTask']);
Route::delete('/deleteActivity/{id}',[ActivityController::class,'deleteActivity']);
Route::put('/updateTask/{id}',[TaskController::class,'updateTask']);
Route::put('/editActivity/{id}',[ActivityController::class,'editActivity']);
Route::put('/updateTasksStatus/{id}',[TaskController::class,'updateTasksStatus']);
Route::get('/getCompletedTasksList',[TaskController::class,'getCompletedTasksList']);


Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);
Route::group([
    "middleware" => ["auth:api"],
], function () {
    Route::get("profile", [UserController::class, 'profile']);
    Route::get("logout", [UserController::class, 'logout']);
});