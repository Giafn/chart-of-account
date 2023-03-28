<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\CategoryController;

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


Auth::routes();

// dahsboard
Route::get('/', [HomeController::class, 'index'])->name('home');

//report
Route::any('/report', [ReportController::class, 'index'])->name('report');


// routing master Chart Of Account
Route::get('/master/coa', [CoaController::class, 'index'])->name('master.COA');
Route::post('/add-coa', [CoaController::class, 'store']);
Route::get('/coa/{coa_id}', [CoaController::class, 'show']);
Route::put('/coaupdate/{coa_id}', [CoaController::class, 'update']);
Route::delete('/coadelete/{coa_id}', [CoaController::class, 'destroy']);

// routing master category
Route::get('/master/category', [CategoryController::class, 'index'])->name('master.category');
Route::post('/add-category', [CategoryController::class, 'store']);
Route::get('/category/{category_id}', [CategoryController::class, 'show']);
Route::put('/categoryupdate/{category_id}', [CategoryController::class, 'update']);
Route::delete('/categorydelete/{category_id}', [CategoryController::class, 'destroy']);

// routing transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::post('/add-transaksi', [TransaksiController::class, 'store']);
Route::get('/transaksi/{transaksi_id}', [TransaksiController::class, 'show']);
Route::put('/transaksiupdate/{transaksi_id}', [TransaksiController::class, 'update']);
Route::delete('/transaksidelete/{category_id}', [TransaksiController::class, 'destroy']);
Route::post('/transaksifilter', [TransaksiController::class, 'index']);


