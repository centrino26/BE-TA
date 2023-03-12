<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\kotaController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewPropertyController;

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

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/list-property/detail/{id}',[PropertyController::class, 'show']);
    Route::get('/list-property/review/{id}',[ReviewPropertyController::class, 'review']);
    Route::post('/list-property/create',[PropertyController::class, 'store']);
    Route::post('/list-property/destroy/{id}',[PropertyController::class, 'destroy']);
    Route::post('/list-property/update/{id}',[PropertyController::class, 'edit']);
    Route::post('/list-property/order-create',[OrderController::class, 'CreateOrder']);
    Route::post('/list-property/order-update/{id}',[OrderController::class, 'UpdateOrder']);
    Route::post('/list-property/order-destroy/{id}',[OrderController::class, 'DestroyOrder']);
    Route::get('/list-property/order-user/{id}',[OrderController::class, 'UserOrder']);

    Route::prefix('type_property')->group(function () {
        
        Route::get('/list/',[PropertyController::class, 'type_property']);
        Route::get('{id_type_property}/filter/property', [PropertyController::class, 'filter']);
    });

    // favorite
    Route::post('/favorite/',[FavoriteController::class, 'index']);
    Route::get('/favorite/user',[FavoriteController::class, 'getFavorite']);
});

Route::post('/login',[LoginController::class, 'login'])->name("login");
Route::post('/register',[LoginController::class, 'register']);
Route::get('/list/kota/provinsi/{id}',[kotaController::class, 'list']);
Route::get('/list/provinsi',[kotaController::class, 'list_provinsi']);
// list property
Route::get('/list-property',[PropertyController::class, 'index']);