<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomAuthenticatedSessionController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\UserAttendanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ルートパターンを設定（数値のみ許可）
Route::pattern('user_id', '[0-9]+');
Route::pattern('attendance_id', '[0-9]+');

//Fortify カスタマイズログイン
Route::get('/login', [CustomAuthenticatedSessionController::class, 'showUserLogin'])->middleware('guest')->name('login');
Route::post('/login', [CustomAuthenticatedSessionController::class, 'storeUser']);
Route::get('/admin/login', [CustomAuthenticatedSessionController::class, 'showAdminLogin'])->middleware('guest')->name('admin.login');
Route::post('/admin/login', [CustomAuthenticatedSessionController::class, 'storeAdmin']);
Route::get('/verify-login', [CustomAuthenticatedSessionController::class, 'verifyLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/logout', [CustomAuthenticatedSessionController::class, 'logout'])->name('logout');

    // 管理者専用ルート
    Route::middleware('role:1')->group(function () {
        Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
        Route::get('/attendance/{attendance_id}', [AdminAttendanceController::class, 'showAttendance'])->name('attendance');
        Route::get('/admin/staff/list', [AdminAttendanceController::class, 'showStaffIndex'])->name('admin.staff.list');
        Route::get('/admin/attemdamce/staff/{user_id}', [AdminAttendanceController::class, 'showStaffAttendance'])->name('admin.attendance.staff');
        Route::get('/stamp_correction_request/list', [AdminAttendanceController::class, 'showRequests'])->name('stamp_correction_request.list');
        Route::get('/stamp_correction_request/approve/{attendance_id}', [AdminAttendanceController::class, 'approve'])->name('stamp_correction_request.approve');
    });

    // 一般ユーザー専用ルート
    Route::middleware('role:2')->group(function () {
        Route::get('/attendance', [UserAttendanceController::class, 'create'])->name('attendance');
        Route::get('/attendance/list', [UserAttendanceController::class, 'index'])->name('attendance.list');
        Route::get('/attendance/{attendance_id}', [UserAttendanceController::class, 'show'])->name('attendance.show');
        Route::get('/stamp_correction_request/list', [UserAttendanceController::class, 'showRequests'])->name('stamp_correction_request.list');
    });

    // 処理の異なる共通ルート
    Route::middleware(['route.role:'  . AdminAttendanceController::class . ',' . UserAttendanceController::class . ',showAttendance,showAttendance'])
        ->get('/attendance/{attendance_id}', function () {})->name('attendance.show');

    Route::middleware(['route.role:' . AdminAttendanceController::class . ',' . UserAttendanceController::class . ',showRequests,showRequests'])
        ->get('/stamp_correction_request/list', function () {})->name('stamp_correction_request.list');
});
