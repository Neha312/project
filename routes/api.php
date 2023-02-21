<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\RoleController;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\ModuleController;
use App\Http\Controllers\v1\PermissionController;

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

Route::prefix('v1')->group(function () {
    Route::controller(ModuleController::class)->group(function () {
        Route::post('list',  'list');
        Route::post('create', 'create');
        Route::get('view/{id}',  'view');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::get('restoreData/{id}', 'restoreData');
    });
    Route::controller(PermissionController::class)->prefix('permission')->group(function () {
        Route::post('list',  'list');
        Route::post('create', 'create');
        Route::get('view/{id}',  'view');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::get('restoreData/{id}', 'restoreData');
    });
    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::post('list',  'list');
        Route::post('create', 'create');
        Route::get('view/{id}',  'view');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::get('restoreData/{id}', 'restoreData');
    });
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('list',  'list');
        Route::post('create', 'create');
        Route::get('view/{id}',  'view');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::get('restoreData/{id}', 'restoreData');
    });
});
