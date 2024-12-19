<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DeviceManagementController;
use App\Http\Controllers\EventReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/hello', function () {
    return "hello web";
});

// Admin routes
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login']);
Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Protected admin routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management Routes
    Route::resource('permissions', PermissionController::class);
    Route::get('/users/manage', [UserManagementController::class, 'index'])->name('users.manage');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Device Management Routes
    Route::get('/devices/manage', [DeviceManagementController::class, 'index'])->name('devices.manage');
    Route::get('/devices/{id}', [DeviceManagementController::class, 'show'])->name('devices.show');
    Route::post('/devices', [DeviceManagementController::class, 'store'])->name('devices.store');
    Route::put('/devices/{id}', [DeviceManagementController::class, 'update'])->name('devices.update');
    Route::delete('/devices/{id}', [DeviceManagementController::class, 'destroy'])->name('devices.destroy');

    // Event Reports Routes
    Route::get('/events/manage', [EventReportController::class, 'index'])->name('events.manage');
    Route::get('/events/{id}', [EventReportController::class, 'show'])->name('events.show');
    Route::get('/events/export', [EventReportController::class, 'export'])->name('events.export');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
   
});
