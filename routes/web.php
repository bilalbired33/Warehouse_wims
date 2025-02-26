<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware('auth:sanctum')->group(function () {
    Route::redirect('/dashboard', '/');
    Route::get('/language/{language}', [Controllers\AjaxController::class, 'language']);
    Route::get('/', [Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity', [Controllers\DashboardController::class, 'activity'])->name('activity');

    Route::get('/alerts', [Controllers\AjaxController::class, 'alerts'])->name('alerts');
    Route::get('/alerts/{warehouse}', [Controllers\AjaxController::class, 'warehouse'])->name('alerts.list');

    Route::get('/settings', [Controllers\SettingController::class, 'index'])->name('settings');
    Route::post('settings', [Controllers\SettingController::class, 'store'])->name('settings.store');

    Route::post('search/items', [Controllers\AjaxController::class, 'items'])->name('items.search');
    Route::get('items/{item}/trail', [Controllers\ItemController::class, 'trail'])->name('items.trail');
    Route::post('search/contacts', [Controllers\AjaxController::class, 'contacts'])->name('contacts.search');
    Route::delete('media/{media}/delete', [Controllers\AjaxController::class, 'delete'])->name('media.delete');
    Route::get('media/{media}/download', [Controllers\AjaxController::class, 'download'])->name('media.download');
    Route::delete('users/{user}/disable_2fa', [Controllers\UserController::class, 'disable2FA'])->name('users.disable.2fa');
    Route::delete('items/{item}/photo', [Controllers\ItemController::class, 'destroyPhoto'])->name('items.deletePhoto');

    Route::extendedResources([
        'items'       => Controllers\ItemController::class,
        'roles'       => Controllers\RoleController::class,
        'units'       => Controllers\UnitController::class,
        'users'       => Controllers\UserController::class,
        'contacts'    => Controllers\ContactController::class,
        'checkins'    => Controllers\CheckinController::class,
        'checkouts'   => Controllers\CheckoutController::class,
        'categories'  => Controllers\CategoryController::class,
        'transfers'   => Controllers\TransferController::class,
        'warehouses'  => Controllers\WarehouseController::class,
        'adjustments' => Controllers\AdjustmentController::class,
    ]);

    Route::portResources([
        'items'      => Controllers\ItemPortController::class,
        'units'      => Controllers\UnitPortController::class,
        'contacts'   => Controllers\ContactPortController::class,
        'checkins'   => Controllers\CheckinPortController::class,
        'checkouts'  => Controllers\CheckoutPortController::class,
        'categories' => Controllers\CategoryPortController::class,
        'warehouses' => Controllers\WarehousePortController::class,
    ]);

    // Notifications
    Route::get('notifications/checkin/{checkin}', [Controllers\NotificationController::class, 'checkin'])->name('notifications.checkin');
    Route::get('notifications/checkout/{checkout}', [Controllers\NotificationController::class, 'checkout'])->name('notifications.checkout');
    Route::get('notifications/transfer/{transfer}', [Controllers\NotificationController::class, 'transfer'])->name('notifications.transfer');
    Route::get('notifications/adjustment/{adjustment}', [Controllers\NotificationController::class, 'adjustment'])->name('notifications.adjustment');
    // Notifications Preview
    Route::get('preview/low_stock', [Controllers\NotificationController::class, 'stock'])->name('notifications.stock.preview');
    Route::get('preview/checkin/{checkin}', [Controllers\NotificationController::class, 'checkin'])->name('notifications.checkin.preview');
    Route::get('preview/checkout/{checkout}', [Controllers\NotificationController::class, 'checkout'])->name('notifications.checkout.preview');
    Route::get('preview/transfer/{transfer}', [Controllers\NotificationController::class, 'transfer'])->name('notifications.transfer.preview');
    Route::get('preview/adjustment/{adjustment}', [Controllers\NotificationController::class, 'adjustment'])->name('notifications.adjustment.preview');

    // Reports
    Route::get('reports', [Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::match(['GET', 'POST'], 'reports/checkin', [Controllers\ReportController::class, 'checkin'])->name('reports.checkin');
    Route::match(['GET', 'POST'], 'reports/checkout', [Controllers\ReportController::class, 'checkout'])->name('reports.checkout');
    Route::match(['GET', 'POST'], 'reports/transfer', [Controllers\ReportController::class, 'transfer'])->name('reports.transfer');
    Route::match(['GET', 'POST'], 'reports/adjustment', [Controllers\ReportController::class, 'adjustment'])->name('reports.adjustment');

    // Role Permissions
    Route::post('roles/{role}/permissions', [Controllers\RoleController::class, 'permissions'])->name('roles.permissions');
});

// Routes to run storage & migration commands
Route::view('/notification', 'notification')->name('notification');
Route::prefix('commands')->middleware(['throttle:6,10', 'purchased'])->group(function () {
    Route::get('storage_link', function () {
        Artisan::call('storage:link');

        return redirect('notification')->with('message', Artisan::output());
    });
    Route::get('update_database', function () {
        Artisan::call('migrate --force');

        return redirect('notification')->with('message', Artisan::output());
    });
});
