<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {

    // route for users
    Route::get('/users/current', [UserController::class, 'get']);
    Route::patch('/users/current', [UserController::class, 'update']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    // route for contacts
    Route::post('/contacts', [ContactController::class, 'create']);
    Route::get('/contacts/{id}', [ContactController::class, 'get']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactController::class, "delete"]);
    Route::get('/contacts', [ContactController::class, 'search']);

    // route for address
    Route::post('/contacts/{contactId}/addresses', [AddressController::class, 'create']);
    Route::get('/contacts/{contactId}/addresses/{addressId}', [AddressController::class, 'get']);
    Route::put('/contacts/{contactId}/addresses/{addressId}', [AddressController::class, 'update']);
    Route::delete('/contacts/{contactId}/addresses/{addressId}', [AddressController::class, 'delete']);
    Route::get('/contacts/{contactId}/addresses', [AddressController::class, 'list']);
});
