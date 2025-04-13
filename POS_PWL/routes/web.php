<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::pattern('id','[0-9]+'); 

// ======================
// PUBLIC ROUTES
// ======================
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);

// Di luar middleware auth
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

// ======================
// AUTHENTICATED ROUTES
// ======================
Route::middleware(['auth'])->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [WelcomeController::class, 'index']);

    // ======================
    // ADMINISTRATOR (ADM) - FULL ACCESS
    // ======================
    Route::middleware(['authorize:ADM'])->group(function() {
        // User Management - Admin only
        Route::prefix('user')->group(function() {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        // Level Management - Admin only
        Route::prefix('level')->group(function() {
            Route::get('/', [LevelController::class, 'index']);
            Route::post('/list', [LevelController::class, 'list']);
            Route::get('/create', [LevelController::class, 'create']);
            Route::post('/', [LevelController::class, 'store']);
            Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
            Route::post('/ajax', [LevelController::class, 'store_ajax']);
            Route::get('/{id}', [LevelController::class, 'show']);
            Route::get('/{id}/edit', [LevelController::class, 'edit']);
            Route::put('/{id}', [LevelController::class, 'update']);
            Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });
    });

    // ======================
    // MANAGEMENT ROUTES (ADMIN + MANAGER)
    // ======================
    Route::middleware(['authorize:ADM,MMG'])->group(function() {
        // Supplier Management
        Route::prefix('supplier')->group(function() {
            Route::get('/', [SupplierController::class, 'index']);
            Route::post('/list', [SupplierController::class, 'list']);
            Route::get('/create', [SupplierController::class, 'create']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);
            Route::post('/ajax', [SupplierController::class, 'store_ajax']);
            Route::get('/{id}', [SupplierController::class, 'show']);
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);
            Route::put('/{id}', [SupplierController::class, 'update']);
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);
            Route::delete('/{id}', [SupplierController::class, 'destroy']);
        });

        Route::middleware(['authorize:ADM'])->group(function() {
        // Barang Management - Read only for manager
        Route::prefix('barang')->group(function() {
            Route::get('/', [BarangController::class, 'index']);
            Route::post('/list', [BarangController::class, 'list']);
            Route::get('/{id}', [BarangController::class, 'show']);
            
            // Restrict create/edit/delete to admin only
            
                Route::get('/create', [BarangController::class, 'create']);
                Route::post('/', [BarangController::class, 'store']);
                Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
                Route::post('/ajax', [BarangController::class, 'store_ajax']);
                Route::get('/{id}/edit', [BarangController::class, 'edit']);
                Route::put('/{id}', [BarangController::class, 'update']);
                Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
                Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
                Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
                Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
                Route::get('/import',[BarangController::class, 'import']); // ajax form upload excel
                Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // ajax upload excel
                Route::delete('/{id}', [BarangController::class, 'destroy']);
            });
        });
    });

    // ======================
    // STAFF (STF) ROUTES
    // ======================
    Route::middleware(['authorize:STF'])->group(function() {
        // Kategori Management - Read only for staff
        Route::prefix('kategori')->group(function() {
            Route::get('/', [KategoriController::class, 'index']);
            Route::post('/list', [KategoriController::class, 'list']);
            Route::get('/{id}', [KategoriController::class, 'show']);
        });
    });

    // ======================
    // ADMIN + STAFF ROUTES
    // ======================
    Route::middleware(['authorize:ADM,STF'])->group(function() {
        // Kategori Management - Full access for admin, limited for staff
        Route::prefix('kategori')->group(function() {
            // Staff can only access these if also authorized
            Route::middleware(['authorize:ADM'])->group(function() {
                Route::get('/create', [KategoriController::class, 'create']);
                Route::post('/', [KategoriController::class, 'store']);
                Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
                Route::post('/ajax', [KategoriController::class, 'store_ajax']);
                Route::get('/{id}/edit', [KategoriController::class, 'edit']);
                Route::put('/{id}', [KategoriController::class, 'update']);
                Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
                Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
                Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
                Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
                Route::delete('/{id}', [KategoriController::class, 'destroy']);
            });
        });
    });
});