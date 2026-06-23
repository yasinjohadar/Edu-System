<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Parent\DashboardController;

Route::middleware('auth')->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role_or_permission:parent|parent-dashboard-view')
        ->name('dashboard');
});
