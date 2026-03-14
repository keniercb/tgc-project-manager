<?php

use App\Http\Controllers\Api\ArtifactController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [UserController::class, 'authenticate']);

    Route::get('/user/me', [UserController::class, 'info']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::post('/', [UserController::class, 'create']);
        });

        Route::prefix('projects')->group(function () {
            Route::get('/', [ProjectController::class, 'index']);
            Route::post('/', [ProjectController::class, 'create']);
            Route::put('/{id}', [ProjectController::class, 'update']);
            Route::get('/show/{id}', [ProjectController::class, 'show']);
            Route::delete('/archive/{id}', [ProjectController::class, 'archive']);
        });

        Route::prefix('artifact')->group(function () {
            Route::get('/list', [ArtifactController::class, 'index']);
            Route::post('/create', [ArtifactController::class, 'create']);
            Route::put('/update/{id}', [ArtifactController::class, 'update']);
            Route::delete('/{id}', [ArtifactController::class, 'delete']);
        });

        Route::prefix('modules')->group(function () {
            Route::get('/', [ModuleController::class, 'list']);
            Route::post('/', [ModuleController::class, 'create']);
            Route::post('/validate/{id}', [ModuleController::class, 'validate']);
            Route::post('/deploy/{id}', [ModuleController::class, 'deploy']);
            Route::put('/{id}', [ModuleController::class, 'update']);
        });

        Route::post('/logout', [UserController::class, 'logout']);
    });

});
