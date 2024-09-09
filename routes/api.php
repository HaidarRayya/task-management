<?php

use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Manager\TaskController as ManagerTaskController;
use App\Http\Controllers\Manager\UserController as ManagerUserController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
Route::prefix('admin')->middleware(['is_admin', 'auth:api'])->group(function () {
    Route::apiResource('users', AdminUserController::class);
    Route::get('/deletedUsers', [AdminUserController::class, 'allDeletedUsers']);
    Route::post('/restoreUser/{user}', [AdminUserController::class, 'restoreUser']);
    Route::get('/allAvailableEmployees', [AdminUserController::class, 'allAvailableEmployees']);
    Route::get('/allManager', [AdminUserController::class, 'allManager']);

    Route::apiResource('tasks', AdminTaskController::class);
    Route::get('/deletedTasks', [AdminTaskController::class, 'allDeletedTasks']);
    Route::post('/restoreTask/{task}', [AdminTaskController::class, 'restoreTask']);
    Route::post('/changeManageTask/{task}', [AdminTaskController::class, 'changeManageTask']);
});

Route::prefix('manager')->middleware(['is_manager', 'auth:api'])->group(function () {
    Route::apiResource('users.tasks', ManagerTaskController::class);
    Route::get('users/{user}/deletedTasks', [ManagerTaskController::class, 'allDeletedTasks']);
    Route::post('users/{user}/restoreTask/{task}', [ManagerTaskController::class, 'restoreTask']);
    Route::apiResource('users', ManagerUserController::class)->only(['index', 'show']);
    Route::get('/allAvailableEmployees', [ManagerUserController::class, 'allAvailableEmployees']);
});

Route::prefix('employee')->middleware(['is_employee', 'auth:api'])->group(function () {
    Route::apiResource('users.tasks', EmployeeTaskController::class)->only(['index', 'show', 'update']);
    Route::post('users/{user}/tasks/{task}/startTask', [EmployeeTaskController::class, 'startTask']);
    Route::post('users/{user}/tasks/{task}/endTask', [EmployeeTaskController::class, 'endTask']);
});
