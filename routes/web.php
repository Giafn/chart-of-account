<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/transaksi', function () {
    return view('transaksi');
})->name('transaksi');
Route::get('/master/category', function () {
    return view('master.category');
})->name('master.category');
Route::get('/master/coa', function () {
    return view('master.COA');
})->name('master.coa');
