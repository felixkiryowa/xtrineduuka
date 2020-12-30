<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\HomeController;


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
    return view('auth.login');
});

Auth::routes();

Route::get('/products', [HomeController::class, 'index'])->name('home');
Route::post('/create/product', [ProductController::class, 'createProduct'])->name('create.product');
Route::post('/update/product', [ProductController::class, 'updateProduct'])->name('update.product');
Route::get('/search/product', [ProductController::class, 'searchingProduct'])->name('search.product');

Route::get('/get/products', [ProductController::class, 'getAllProducts'])->name('get.products');
Route::get('{path}',[HomeController::class, 'index'])->where( 'path', '([A-z\/_.\d-]+)?' );
