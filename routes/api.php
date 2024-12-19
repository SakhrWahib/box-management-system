<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SubuserController;
use App\Http\Controllers\API\DeviceController;
use App\Http\Controllers\API\CodeListController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\LockfingerController;
use App\Http\Controllers\indexlistcontroller;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\NotificationSettingController;
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
Route::middleware('cors')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('userscount/{id}', [UserController::class,'getusersCountByUserId']);
    Route::apiResource('subusers', SubuserController::class);
    Route::get('subusers/{id}',[SubuserController::class,'show']);
    Route::delete('subusers/{id}',[SubuserController::class,'show']);
    Route::apiResource('devices', DeviceController::class);
    Route::delete('devices/{id}', [DeviceController::class, 'destroy']);
    Route::get('user/{userId}/devices', [DeviceController::class, 'show']);
    Route::put('/devices/{id}', [DeviceController::class, 'update']);
    Route::get('/device-count/{userId}', [DeviceController::class, 'getDeviceCountByUserId']);
    Route::apiResource('codelists', CodeListController::class);
    Route::post('generate', [GroupController::class, 'generateRandomNumbers']);
    Route::post('/groups/device', [GroupController::class, 'getByDeviceNumber']);
    Route::post('/groups/delete', [GroupController::class, 'deleteByDeviceNumber']);
    Route::post('/groups/deleteuserid', [GroupController::class, 'deleteByDeviceNumberUserId']);
    Route::post('/subuser/deletedsubuser', [SubuserController::class, 'deleteByDeviceNumberdeviceId']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('/verify-temp-code', [UserController::class, 'verifyTempCode']);
    Route::post('password-reset/send-code', [UserController::class, 'sendResetCode']);
    Route::post('password-reset/verify-code', [UserController::class, 'verifyResetCode']);
    Route::put('passwordupdatd', [UserController::class, 'storeNewPassword']);
    //   event api
    // Event API
    Route::post('/events', [EventController::class, 'store']);
    Route::post('/events/{id}', [EventController::class, 'show']);
    Route::post('/update-token', [TokenController::class, 'updateOrCreateToken']);
    Route::get('/update-token/{user_id}', [TokenController::class, 'getTokenByUserId']);
    Route::post('/update-lock', [LockfingerController::class, 'updateorcreatestatelock']);
    Route::get('/update-lock/{device_id}', [LockfingerController::class, 'getlockstate']);
    Route::post('/updateorcreateindex', [indexlistcontroller::class, 'updateorcreateindex']);
    Route::get('/updateorcreateindex/{device_id}', [indexlistcontroller::class, 'getindex']);
    Route::post('/send-phone-otp', [UserController::class, 'sendOTP']);
    Route::post('/verify-phone-otp', [UserController::class, 'verifyPhoneOTP']);
    Route::get('/notifications/{user_id}', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    Route::get('/notification-settings/{user_id}', [NotificationSettingController::class, 'show']);
    Route::put('/notification-settings/{user_id}', [NotificationSettingController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/delete-account', [UserController::class, 'deleteAccount']);
});
// Route::apiResource('users', UserController::class);
//     Route::apiResource('subusers', SubuserController::class);
//     Route::get('subusers/{id}',[SubuserController::class,'show']);
//     Route::delete('subusers/{id}',[SubuserController::class,'show']);
//     Route::apiResource('devices', DeviceController::class);
//     Route::delete('devices/{id}', [DeviceController::class, 'destroy']);
//     Route::get('user/{userId}/devices', [DeviceController::class, 'show']);
//     Route::put('/devices/{id}', [DeviceController::class, 'update']);
//     Route::apiResource('codelists', CodeListController::class);
//     Route::post('generate', [GroupController::class, 'generateRandomNumbers']);
//     Route::post('/groups/device', [GroupController::class, 'getByDeviceNumber']);
//     Route::post('/groups/delete', [GroupController::class, 'deleteByDeviceNumber']);
//     Route::post('/groups/deleteuserid', [GroupController::class, 'deleteByDeviceNumberUserId']);
//     Route::post('/subuser/deletedsubuser', [SubuserController::class, 'deleteByDeviceNumberdeviceId']);
//     Route::post('login', [LoginController::class, 'login']);
//     Route::post('/verify-temp-code', [UserController::class, 'verifyTempCode']);
//     Route::post('password-reset/send-code', [UserController::class, 'sendResetCode']);
//     Route::post('password-reset/verify-code', [UserController::class, 'verifyResetCode']);
//     Route::put('passwordupdatd', [UserController::class, 'storeNewPassword']);
//     //   event api
//     // Event API
//     Route::post('events', [EventController::class, 'store']);
//     Route::post('/events/{id}', [EventController::class, 'show']);
//     Route::post('/update-token', [TokenController::class, 'updateOrCreateToken']);
//     Route::post('/update-lock', [LockfingerController::class, 'updateorcreatestatelock']);