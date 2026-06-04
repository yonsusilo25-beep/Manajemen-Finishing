<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DepartmentInventoryController;
use App\Http\Controllers\FinishedProductInspectionController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StockMovementController;

Route::get('/',function(){
    if(Auth::check()){
        return redirect()->route('dashboard');
    }
    return redirect()->route('home.main');
});

require __DIR__.'/auth.php';

// ============================================
// GUEST ROUTES (belum login)
// ============================================
Route::middleware('guest')->group(function () {

    // GET - Show Forms (Custom Views)
    Route::get('login', [AuthViewController::class, 'showLogin'])
        ->name('login');

    Route::get('register', [AuthViewController::class, 'showRegister'])
        ->name('register');

    Route::get('forgot-password', [AuthViewController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::get('reset-password/{token}', [AuthViewController::class, 'showResetPassword'])
        ->name('password.reset');

    // POST - Handle Logic (Breeze Controllers)
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::prefix('home')->group(function(){

        Route::get('/',function(){
            return view('home.main');
        })->name('home.main');

        Route::get('/products',function(){
            return view('home.products');
        })->name('home.products');

        Route::get('/divisions',function(){
            return view('home.divisions');
        })->name('home.divisions');

        Route::get('/facilities',function(){
            return view('home.facilities');
        })->name('home.facilities');

        Route::get('/gallery',function(){
            return view('home.galleries');
        })->name('home.gallery');

    });
});

// ============================================
// Auth ROUTES (sudah login)
// ============================================


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/finished-product-inspection', [FinishedProductInspectionController::class, 'index'])
        ->name('finished-product-inspection.index');

    // Materials
    Route::resource('materials', MaterialController::class);

    Route::get('/jobs/{job}/all',[JobController::class,'requestView'])->name('jobs.requests');
    Route::get('/jobs/{job}/requests',[JobController::class,'getRequests'])->name('jobs.requests');
    Route::resource('jobs',JobController::class);

    Route::resource('departments',DepartmentController::class);

    // Requests
    Route::resource('requests', RequestController::class);
    Route::get('requests/{request}/print', [RequestController::class, 'print'])->name('requests.print');
    Route::patch('requests/{request}/submit', [RequestController::class, 'submit'])->name('requests.submit');
    Route::patch('requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::patch('requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    Route::patch('requests/{request}/complete', [RequestController::class, 'complete'])->name('requests.complete');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Department Inventories
    Route::resource('department-inventories', DepartmentInventoryController::class)->except(['create', 'store', 'destroy']);
    Route::patch('department-inventories/{departmentInventory}/update-stock', [DepartmentInventoryController::class, 'updateStock'])->name('department-inventories.update-stock');

    Route::resource('stock-movements',StockMovementController::class)->except(['edit','create','destroy']);
    Route::get('stock-movements/export', [StockMovementController::class, 'export'])->name('stock-movements.export');
});
