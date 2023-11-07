<?php

use App\Http\Controllers\API\V1\Admin\AdminController;
use App\Http\Controllers\API\V1\AttendanceController;
use App\Http\Controllers\API\V1\EmployeeController;
use App\Http\Controllers\API\V1\OvertimeController;
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

//EMPLOYEE-SIDE
Route::post('register', [EmployeeController::class, 'register']);
Route::post('login', [EmployeeController::class, 'login']);

Route::group([
    'middleware' => ['auth:employee']
], function () {
    Route::post('logout', [EmployeeController::class, 'logout']);
    Route::post('refresh', [EmployeeController::class, 'refresh']);
    Route::get('profile', [EmployeeController::class, 'profile']);

    //ATTENDANCE
    Route::post('clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('clock-out', [AttendanceController::class, 'clockOut']);

    //FILE-OVERTIME
    Route::get('overtime', [OvertimeController::class, 'index']);
    Route::post('overtime', [OvertimeController::class, 'store']);

    //STATUS
    Route::post('overtime/{overtime}/approve', [OvertimeController::class,'approve']);
    Route::post('overtime/{overtime}/decline', [OvertimeController::class,'decline']);
});

//ADMIN-SIDE
Route::prefix('admin')->group(function () {
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);

    Route::group(['middleware' => ['auth:admin']], function () {
      Route::post('logout', [AdminController::class, 'logout']);
      Route::post('refresh', [AdminController::class, 'refresh']);
      Route::get('profile', [AdminController::class, 'profile']);
    });
});
