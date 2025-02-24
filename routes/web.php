<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\StorehouseUserController;
use App\Http\Controllers\WorkshopAndBoxTypeController;
use App\Http\Controllers\BoxUnderManufacturingController;
use App\Http\Controllers\ManufacturedBoxController;
use App\Http\Controllers\InventoryBoxController;

// الصفحة الرئيسية تحول إلى صفحة تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// مسارات المصادقة
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});

Route::post('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// المسارات المحمية
Route::middleware(['auth:admin'])->group(function () {
    // توجيه الصفحة الرئيسية بعد تسجيل الدخول
    Route::get('/', function () {
        return redirect()->route('boxes-under-manufacturing.index');
    });

    // Boxes Under Manufacturing Routes
    Route::resource('boxes-under-manufacturing', BoxUnderManufacturingController::class);
    Route::post('boxes-under-manufacturing/{id}/update-status', [BoxUnderManufacturingController::class, 'updateStatus'])
        ->name('boxes-under-manufacturing.update-status');
    Route::post('boxes-under-manufacturing/{id}/update-payment', [BoxUnderManufacturingController::class, 'updatePayment'])
        ->name('boxes-under-manufacturing.update-payment');
    Route::post('/boxes-under-manufacturing/{id}/update-quantity', [BoxUnderManufacturingController::class, 'updateQuantity'])
        ->name('boxes-under-manufacturing.update-quantity');
    Route::post('/boxes-under-manufacturing/{id}/mark-completed', [BoxUnderManufacturingController::class, 'markAsCompleted'])
        ->name('boxes-under-manufacturing.mark-completed');
    Route::put('/boxes-under-manufacturing/{id}/update-received-quantity', [BoxUnderManufacturingController::class, 'updateReceivedQuantity'])
        ->name('boxes-under-manufacturing.update-received-quantity');

    // باقي المسارات المحمية
    Route::resource('inventory-boxes', InventoryBoxController::class);
    Route::resource('manufactured-boxes', ManufacturedBoxController::class);
    Route::resource('storehouse-users', StorehouseUserController::class);
    
    // Workshops and Box Types Routes
    Route::get('/workshops-and-box-types', [WorkshopAndBoxTypeController::class, 'index'])->name('workshops-and-box-types.index');
    Route::post('/workshops-and-box-types', [WorkshopAndBoxTypeController::class, 'store'])->name('workshops-and-box-types.store');
    Route::put('/workshops-and-box-types/{type}/{id}', [WorkshopAndBoxTypeController::class, 'update'])->name('workshops-and-box-types.update');
    Route::delete('/workshops-and-box-types/{type}/{id}', [WorkshopAndBoxTypeController::class, 'destroy'])->name('workshops-and-box-types.destroy');
    Route::get('/workshops/{workshop}/stats', [WorkshopAndBoxTypeController::class, 'getWorkshopStats'])->name('workshops.stats');
    Route::get('/workshops-and-box-types/archive', [WorkshopAndBoxTypeController::class, 'archive'])->name('workshops-and-box-types.archive');
    Route::post('/workshops-and-box-types/workshop/{id}/archive', [WorkshopAndBoxTypeController::class, 'archiveWorkshop'])->name('workshops-and-box-types.archive-workshop');
    Route::post('/manufacturing-boxes/{id}/restore', [WorkshopAndBoxTypeController::class, 'restoreBox'])->name('manufacturing-boxes.restore');

    // Box Types Routes
    Route::get('/box-types/{id}', [WorkshopAndBoxTypeController::class, 'showBoxType'])->name('box-types.show');
    Route::post('/box-types', [WorkshopAndBoxTypeController::class, 'storeBoxType'])->name('box-types.store');
    Route::put('/box-types/{id}', [WorkshopAndBoxTypeController::class, 'updateBoxType'])->name('box-types.update');
    Route::delete('/box-types/{id}', [WorkshopAndBoxTypeController::class, 'destroyBoxType'])->name('box-types.destroy');
});



