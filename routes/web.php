<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DesignQueueController;
use App\Http\Controllers\PrintingQueueController;
use App\Http\Controllers\CoverPrintingQueueController;
use App\Http\Controllers\BindingQueueController;
use App\Http\Controllers\QcQueueController;
use App\Http\Controllers\PackagingQueueController;
use App\Http\Controllers\ShipmentQueueController;
use App\Http\Controllers\OrderedBookController;
use App\Http\Middleware\CheckEmployeeSession;
use App\Http\Middleware\RedirectIfEmployeeLoggedIn;

Route::get('/', function () {
    return view('login');
})
->middleware([RedirectIfEmployeeLoggedIn::class])
->name('login');
Route::post('login', [EmployeeAuthController::class, 'login'])->name('employee.login.submit');



Route::middleware([CheckEmployeeSession::class])->group(function () {
        Route::prefix('users')->group(function () {
        Route::get('all', [EmployeeAuthController::class, 'showUsers'])->name('employee.all');
        Route::get('register', [EmployeeAuthController::class, 'showRegister'])->name('employee.register');
        Route::post('register', [EmployeeAuthController::class, 'register'])->name('employee.register.submit');



        Route::get('view/{id}', [EmployeeAuthController::class, 'view'])->name('employee.view');
        Route::get('edit/{id}', [EmployeeAuthController::class, 'edit'])->name('employee.edit');
        Route::put('update/{id}', [EmployeeAuthController::class, 'update'])->name('employee.update');
        Route::delete('delete/{id}', [EmployeeAuthController::class, 'destroy'])->name('employee.delete');

        Route::get('profile/edit', [EmployeeAuthController::class, 'editSelf'])->name('employee.profile.edit');
        Route::put('profile/update', [EmployeeAuthController::class, 'updateSelf'])->name('employee.profile.update');

        Route::post('logout', [EmployeeAuthController::class, 'logout'])->name('employee.logout');

        Route::middleware('auth:employee')->group(function () {
            Route::get('dashboard', [EmployeeAuthController::class, 'dashboard'])->name('employee.dashboard');

        });
});



    // Orders
    Route::resource('orders', OrderController::class);

    Route::prefix('order')->group(function () {
        // Ordered Books (optional if you want individual book management)
        Route::resource('ordered-books', OrderedBookController::class);

        // Queue routes
        Route::resource('design-queues', DesignQueueController::class);
        Route::resource('printing-queues', PrintingQueueController::class);
        Route::resource('cover-printing-queues', CoverPrintingQueueController::class);
        Route::resource('binding-queues', BindingQueueController::class);
        Route::resource('qc-queues', QcQueueController::class);
        Route::resource('packaging-queues', PackagingQueueController::class);
        Route::resource('shipment-queues', ShipmentQueueController::class);
    });

});


