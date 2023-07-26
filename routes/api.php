<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiListRightsController;
use App\Http\Controllers\Api\ApiTaskController;
use App\Http\Controllers\Api\ApiTaskListController;
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

Route::prefix('tasks')->controller(ApiTaskController::class)->group(function () {
    Route::middleware(['auth:api'])->group(function () {
        // Найти задачи с тэгом(-ами)
        Route::get('/search', 'searchByTags');
        // Создать задачу
        Route::post('/{list}', 'store');
        // Изменить задачу
        Route::patch('/{task}', 'update');
        // Удалить изображение задачи 
        Route::delete('/image/{task}', 'removeImage');
        // Удалить задачу 
        Route::delete('/{task}', 'destroy');
    });
});

Route::prefix('lists')->middleware(['auth:api'])->controller(ApiTaskListController::class)->group(function () {
    Route::controller(ApiTaskListController::class)->group(function () {
        // Получить свои списки
        Route::get('/', 'index');
        // Получить списки которые можно просмотреть
        Route::get('/viewable', 'viewable');
        // Получить списки которые можно редактировать
        Route::get('/editable', 'editable');

        // Посмотреть задачи списка
        Route::get('/{list}', 'show');

        // Добавить список
        Route::post('/', 'store');
        // Изменить список
        Route::put('/{list}', 'update');
        // Удалить список
        Route::delete('/{list}', 'destroy');
    });

    Route::controller(ApiListRightsController::class)->group(function () {
        // Добавить редактора в список
        Route::post('/{list}/add-editor', 'addEditor');
        // Удалить редактора из списка
        Route::delete('/{list}/remove-editor/{editor}', 'removeEditor');
        // Добавить просмотрщика в список
        Route::post('/{list}/add-viewer', 'addViewer');
        // Удалить просмотрщика из списка
        Route::delete('/{list}/remove-viewer/{viewer}', 'removeViewer');
    });
});
