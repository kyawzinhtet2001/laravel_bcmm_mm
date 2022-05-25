<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(["CheckAdmin","CheckSingle"])->prefix('/party')->group(function(){
    Route::get("/create",function(){
        return response()->json(["status"=>"OK","message"=>"party is created"]);
    })->middleware("CheckAdminSingle")->withoutMiddleware("CheckSingle");
    Route::get("/register",function(){
        return response()->json(["status"=>"OK","message"=>"You are registered to party"]);
    })->withoutMiddleware("CheckAdmin");
    Route::get("/attend",function(){
        return response()->json(["status"=>"OK","message"=>"Attending to Party"]);
    })->withoutMiddleware("CheckAdmin");
    Route::get("/unregister",function(){
        return response()->json(["status"=>"OK","message"=>"Unregister to Party"]);
    })->withoutMiddleware("CheckAdmin");
    Route::get("/check/{id?}",function(){
        return response()->json(["status"=>"OK","message"=>"Check someone for ticket"]);
    })->withoutMiddleware("CheckSingle");
    Route::get("/get_number",function(){
        return response()->json(["status"=>"OK","message"=>"You can ask more to party"]);
    })->withoutMiddleware(["CheckAdmin","CheckSingle"]);
});



Route::post('/product/create',[ProductController::class,'create']);
Route::get('/product/',[ProductController::class,'index']);
// Route::post('/product/create',"Api\ProductController@create");
