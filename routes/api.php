<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertyForController;
use App\Http\Controllers\PropertyFeatureController;
use App\Http\Controllers\Api\PropertyTypeController;
use App\Http\Controllers\Api\PropertyNearLocationController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/me', [AuthController::class, 'me']);    
    });

    /*
    Route::get('teams', [GeneralController::class, 'teamAgent']);
    Route::get('property-types', [GeneralController::class, 'propertyTypes']);
    Route::get('property-for', [GeneralController::class, 'propertyFor']);
    */

    Route::get('/property_for', [PropertyForController::class, 'index']);
    Route::post('/property_for', [PropertyForController::class, 'store']);
    Route::get('/property_for/{id}', [PropertyForController::class, 'show']);
    Route::put('/property_for/{id}', [PropertyForController::class, 'update']);
    Route::delete('/property_for/{id}', [PropertyForController::class, 'destroy']);

    Route::get('/property_types', [PropertyTypeController::class, 'index']);
    Route::post('/property_types', [PropertyTypeController::class, 'store']);
    Route::get('/property_types/{id}', [PropertyTypeController::class, 'show']);
    Route::put('/property_types/{id}', [PropertyTypeController::class, 'update']);
    Route::delete('/property_types/{id}', [PropertyTypeController::class, 'destroy']);

    Route::get('/property_features', [PropertyFeatureController::class, 'index']);
    Route::post('/property_features', [PropertyFeatureController::class, 'store']);
    Route::get('/property_features/{id}', [PropertyFeatureController::class, 'show']);
    Route::put('/property_features/{id}', [PropertyFeatureController::class, 'update']);
    Route::delete('/property_features/{id}', [PropertyFeatureController::class, 'destroy']);

    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::get('/teams/{id}', [TeamController::class, 'show']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::get('/properties/{id}', [PropertyController::class, 'show']);
    Route::put('/properties/{id}', [PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
    Route::post('/properties/image-upload', [PropertyController::class, 'uploadImage']);

    Route::get('/property-near-locations', [PropertyNearLocationController::class, 'index']);
    Route::post('/property-near-locations', [PropertyNearLocationController::class, 'store']);
    Route::get('/property-near-locations/{id}', [PropertyNearLocationController::class, 'show']);
    Route::put('/property-near-locations/{id}', [PropertyNearLocationController::class, 'update']);
    Route::delete('/property-near-locations/{id}', [PropertyNearLocationController::class, 'destroy']);

});