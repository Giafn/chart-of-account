<?php

use Illuminate\Support\Facades\Route;
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
// Route::get('/', function () { return view('home'); })->name('home');
// Route::get('/transaksi', function () { return view('transaksi'); })->name('transaksi');
// Route::get('/master/category', function () { return view('master.coa'); })->name('master.category');
// Route::get('/master/coa', function () { return view('master.coa'); })->name('master.COA');

Route::get('/', [TransaksiController::class, 'index'])->name('home');
Route::get('/master/coa', [CoaController::class, 'index'])->name('master.COA');
Route::get('/master/category', [CategoryController::class, 'index'])->name('master.category');
Route::Post('/add-category', [CategoryController::class, 'store']);
Route::get('/category/{category_id}', [CategoryController::class, 'show']);
Route::put('/categoryupdate/{category_id}', [CategoryController::class, 'update']);
Route::get('/transaksi', function () {
    return view('transaksi');
})->name('transaksi');

