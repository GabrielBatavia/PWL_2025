<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']);
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
// Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);


// Route::get('/', [WelcomeController::class, 'index']);


Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);            // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);        // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);     // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);           // menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);         // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);    // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);       // menyimpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']);   // menghapus data user
});

Route::prefix('level')->group(function() {
    Route::get('/',          [LevelController::class, 'index']);   // GET /level
    Route::get('/create',    [LevelController::class, 'create']);  // GET /level/create
    Route::post('/',         [LevelController::class, 'store']);   // POST /level
    Route::get('/{id}',      [LevelController::class, 'show']);    // GET /level/{id}
    Route::get('/{id}/edit', [LevelController::class, 'edit']);    // GET /level/{id}/edit
    Route::put('/{id}',      [LevelController::class, 'update']);  // PUT /level/{id}
    Route::delete('/{id}',   [LevelController::class, 'destroy']); // DELETE /level/{id}
});

Route::prefix('kategori')->group(function() {
    Route::get('/',           [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/list',      [KategoriController::class, 'list'])->name('kategori.list');
    Route::get('/create',     [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/',          [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/{id}',       [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/{id}/edit',  [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/{id}',       [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/{id}',    [KategoriController::class, 'destroy'])->name('kategori.destroy');
});


// Resource route
Route::resource('supplier', SupplierController::class);

// Route khusus DataTables:
Route::post('supplier/list', [SupplierController::class, 'list'])->name('supplier.list');


Route::resource('barang', BarangController::class);
Route::post('barang/list', [BarangController::class, 'list'])->name('barang.list');