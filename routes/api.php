<?php

use App\Http\Controllers\api\auth\AuthUserController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get("user", function (Request $request){
    return Auth::user();
})->middleware("auth:sanctum");



Route::post("auth/register", [AuthUserController::class,"register" ]);
Route::post("auth/login", [AuthUserController::class,"login" ]);
Route::delete("auth/logout", [AuthUserController::class,"logout" ])
    ->middleware('auth:sanctum');

Route::apiResource("products", ProductController::class)
    ->only("index");

Route::apiResource("products", ProductController::class)
    ->except("index")->middleware("auth:sanctum");
