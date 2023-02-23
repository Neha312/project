<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\AuthController;
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


Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::controller(UserController::class)->prefix('user')->group(function () {
            Route::post('list',  'list')->middleware('checkpermission:user,view_access');
            Route::post('create', 'create')->middleware('checkpermission:user,add_access');
            Route::get('view/{id}',  'view')->middleware('checkpermission:user,view_access');
            Route::post('update/{id}', 'update')->middleware('checkpermission:user,edit_access');
            Route::post('delete/{id}', 'delete')->middleware('checkpermission:user,delete_access');
            Route::get('restoreData/{id}', 'restoreData');
        });
        Route::controller(ModuleController::class)->group(function () {
            Route::post('list',  'list')->middleware('checkpermission:user,view_access');
            Route::post('create', 'create')->middleware('checkpermission:user,add_access');
            Route::get('view/{id}',  'view')->middleware('checkpermission:user,view_access');
            Route::post('update/{id}', 'update')->middleware('checkpermission:user,edit_access');
            Route::post('delete/{id}', 'delete')->middleware('checkpermission:user,delete_access');
            Route::get('restoreData/{id}', 'restoreData');
        });
        Route::controller(PermissionController::class)->prefix('permission')->group(function () {
            Route::post('list',  'list')->middleware('checkpermission:user,view_access');
            Route::post('create', 'create')->middleware('checkpermission:user,add_access');
            Route::get('view/{id}',  'view')->middleware('checkpermission:user,view_access');
            Route::post('update/{id}', 'update')->middleware('checkpermission:user,edit_access');
            Route::post('delete/{id}', 'delete')->middleware('checkpermission:user,delete_access');
            Route::get('restoreData/{id}', 'restoreData');
        });
        Route::controller(RoleController::class)->prefix('role')->group(function () {
            Route::post('list',  'list')->middleware('checkpermission:user,view_access');
            Route::post('create', 'create')->middleware('checkpermission:user,add_access');
            Route::get('view/{id}',  'view')->middleware('checkpermission:user,view_access');
            Route::post('update/{id}', 'update')->middleware('checkpermission:user,edit_access');
            Route::post('delete/{id}', 'delete')->middleware('checkpermission:user,delete_access');
            Route::get('restoreData/{id}', 'restoreData');
        });
    });
});
