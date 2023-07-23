<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\ApiTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('auth')->controller(ApiAuthController::class)->group(function () {
    // Зарегистрироваться
    Route::post('register', 'register')->name('auth.register');
    // Залогиниться
    Route::post('login', 'login')->name('auth.login');
    // Получить новый токен (по уже истёкшему)
    Route::post('refresh', 'refresh')->name('auth.refresh');


    Route::middleware(['auth:api'])->group(function () {
        Route::get('user', 'user')->name('auth.user');
        // Выйти
        Route::post('logout', 'logout')->name('auth.logout');
    });
});

Route::prefix('lists')->controller(ApiTaskController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        // Создать задачу
        Route::post('/{list}/tasks', 'store');
        // Изменить задачу
        Route::put('/tasks/{task}', 'update');
        // Удалить задачу
        Route::delete('/tasks/{task}', 'destroy');
    });
});

Route::prefix('lists')->controller(ApiTaskListController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        // Получить свои списки
        Route::get('/', [ApiTaskListController::class, 'index']);
        // Добавить список
        Route::post('/', [ApiTaskListController::class, 'store']);
        // Посмотреть задачи списка
        Route::get('/{list}', [ApiTaskListController::class, 'show']);
        // Изменить список
        Route::put('/{list}', [ApiTaskListController::class, 'update']);
        // Удалить список
        Route::delete('/{list}', [ApiTaskListController::class, 'destroy']);
    });
});
