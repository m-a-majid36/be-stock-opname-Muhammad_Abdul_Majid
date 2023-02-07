<?php

use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Produk
Route::get('/product', [ProductController::class, 'index'])->name('pdIndex');
Route::get('/product/create', [ProductController::class, 'create'])->name('pdCreate');
Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('pdEdit');
Route::post('/product/store', [ProductController::class, 'store'])->name('pdStore');
Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('pdUpdate');
Route::delete('/product/delete', [ProductController::class, 'delete'])->name('pdDelete');
Route::post('/product/import', [ProductController::class, 'import'])->name('pdImport');
Route::post('/product/delete_all', [ProductController::class, 'deleteAll'])->name('pdDeleteAll');
Route::get('/product/export', [ProductController::class, 'export'])->name('pdExport');

//Gudang
Route::get('/warehouse', [WarehouseController::class, 'index'])->name('whIndex');
Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('whCreate');
Route::get('/warehouse/edit/{id}', [WarehouseController::class, 'edit'])->name('whEdit');
Route::post('/warehouse/store', [WarehouseController::class, 'store'])->name('whStore');
Route::put('/warehouse/update/{id}', [WarehouseController::class, 'update'])->name('whUpdate');
Route::delete('/warehouse/delete', [WarehouseController::class, 'delete'])->name('whDelete');

//Periode
Route::get('/periode',[PeriodeController::class, 'index'])->name('periodeIndex');
Route::get('/periode/create',[PeriodeController::class, 'create'])->name('periodeCreate');
Route::get('/periode/edit/{id}',[PeriodeController::class, 'edit'])->name('periodeEdit');
Route::post('/periode/store',[PeriodeController::class, 'store'])->name('periodeStore');
Route::put('/periode/update/{id}',[PeriodeController::class, 'update'])->name('periodeUpdate');
Route::delete('/periode/delete',[PeriodeController::class, 'delete'])->name('periodeDelete');

//Transaksi
Route::get('/transaction', [TransactionController::class, 'index'])->name('trIndex');
Route::get('/transaction/create', [TransactionController::class, 'create'])->name('trCreate');
Route::post('/transaction/store', [TransactionController::class, 'store'])->name('trStore');
Route::delete('/transaction/delete', [TransactionController::class, 'delete'])->name('trDelete');

//Stok Opname
Route::get('/stockopname', [StockController::class, 'index'])->name('soIndex');
Route::get('/stockopname/create', [StockController::class, 'create'])->name('soCreate');
Route::get('/stockopname/edit/{id}', [StockController::class, 'edit'])->name('soEdit');
Route::post('/stockopname/store', [StockController::class, 'store'])->name('soStore');
Route::put('/stockopname/update/{id}', [StockController::class, 'update'])->name('soUpdate');
Route::delete('/stockopname/delete', [StockController::class, 'delete'])->name('soDelete');

//Laporan Stok
Route::get('/stockreport', [WarehouseController::class, 'index'])->name('wsIndex');
Route::get('/stockreport/{id}', [WarehouseController::class, 'sortByWarehouse'])->name('wsSortByWh');
Route::get('/stockreport/{id}/export', [WarehouseController::class, 'export'])->name('wsExport');