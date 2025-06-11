<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// Add new controllers for Daily/Annual
use App\Http\Controllers\Daily\ResidentConsultantController;
use App\Http\Controllers\Daily\VisitingConsultantController;
use App\Http\Controllers\Annual\OldWingController;
use App\Http\Controllers\Annual\NewWingController;
use App\Http\Controllers\Annual\CtgController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Root route - redirects to login for guests and dashboard for authenticated users
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Staff Routes
    Route::middleware('role:staff')->group(function () {
        Route::get('/consultant', [ConsultantController::class, 'index'])->name('consultant');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

        // Daily Routes
        Route::prefix('daily')->name('daily.')->group(function () {
            // Resident Consultant Routes
            Route::controller(ResidentConsultantController::class)->group(function () {
                Route::get('/resident-consultant/monthly', 'monthly')->name('resident-consultant.monthly');
                Route::get('/resident-consultant/create', 'create')->name('resident-consultant.create');
                Route::get('/resident-consultant', 'index')->name('resident-consultant.index');
                Route::post('/resident-consultant', 'store')->name('resident-consultant.store');
                Route::get('/resident-consultant/export', 'export')->name('resident-consultant.export');
                Route::get('/resident-consultant/{entry}', 'show')->name('resident-consultant.show');
                Route::get('/resident-consultant/{entry}/edit', 'edit')->name('resident-consultant.edit');
                Route::put('/resident-consultant/{entry}', 'update')->name('resident-consultant.update');
            });

            // Visiting Consultant Routes
            Route::controller(VisitingConsultantController::class)->group(function () {
                Route::get('/visiting-consultant/monthly', 'monthly')->name('visiting-consultant.monthly');
                Route::get('/visiting-consultant/create', 'create')->name('visiting-consultant.create');
                Route::get('/visiting-consultant', 'index')->name('visiting-consultant.index');
                Route::post('/visiting-consultant', 'store')->name('visiting-consultant.store');
                Route::get('/visiting-consultant/export', 'export')->name('visiting-consultant.export');
                Route::get('/visiting-consultant/{entry}', 'show')->name('visiting-consultant.show');
                Route::get('/visiting-consultant/{entry}/edit', 'edit')->name('visiting-consultant.edit');
                Route::put('/visiting-consultant/{entry}', 'update')->name('visiting-consultant.update');
            });
        });

        // Annual Routes
        Route::prefix('annual')->name('annual.')->group(function () {
            Route::controller(OldWingController::class)->group(function () {
                Route::get('/old-wing', 'index')->name('old-wing.index');
                Route::get('/old-wing/daily', 'daily')->name('old-wing.daily');
                Route::get('/old-wing/monthly', 'monthly')->name('old-wing.monthly');
                Route::post('/old-wing', 'store')->name('old-wing.store');
                Route::get('/old-wing/export', 'export')->name('old-wing.export');
                Route::get('/old-wing/{date}/edit', 'edit')->name('old-wing.edit');
                Route::put('/old-wing/{date}', 'update')->name('old-wing.update');
            });
            Route::controller(NewWingController::class)->group(function () {
                Route::get('/new-wing', 'index')->name('new-wing.index');
                Route::get('/new-wing/daily', 'daily')->name('new-wing.daily');
                Route::get('/new-wing/monthly', 'monthly')->name('new-wing.monthly');
                Route::post('/new-wing', 'store')->name('new-wing.store');
                Route::get('/new-wing/export', 'export')->name('new-wing.export');
                Route::get('/new-wing/{date}/edit', 'edit')->name('new-wing.edit');
                Route::put('/new-wing/{date}', 'update')->name('new-wing.update');
            });
            Route::prefix('ctg')->name('ctg.')->group(function () {
                Route::get('/', [CtgController::class, 'index'])->name('index');
                Route::post('/', [CtgController::class, 'store'])->name('store');
                Route::get('/daily', [CtgController::class, 'daily'])->name('daily');
                Route::get('/monthly', [CtgController::class, 'monthly'])->name('monthly');
                Route::get('/export', [CtgController::class, 'export'])->name('export');
                Route::get('/{record}/edit', [CtgController::class, 'edit'])->name('edit');
                Route::put('/{record}', [CtgController::class, 'update'])->name('update');
                Route::get('/check-date/{date}', [CtgController::class, 'checkDate'])->name('check-date');
            });
        });
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/log', [LogController::class, 'index'])->name('log');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

        // User Management Routes
        Route::prefix('user-management')->name('user-management.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('reset-password');
        });
    });

    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
