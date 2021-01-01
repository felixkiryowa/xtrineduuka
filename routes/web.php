<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Expense\ExpenseController;

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

Route::get('/all/expenses', [ExpenseController::class, 'getAllExpenses'])->name('get.expenses');
Route::post('/create/expense', [ExpenseController::class, 'createExpense'])->name('create.expense');
Route::post('/update/expense', [ExpenseController::class, 'updateExpense'])->name('update.expense');
Route::post('/generate/expenses/report', [ExpenseController::class, 'generateExpensesReport'])
->name('generate.expense.report');


Route::get('/all/transactions', [TransactionController::class, 'getTransactions'])->name('get.transactions');
Route::post('/create/transaction', [TransactionController::class, 'createTransaction'])->name('create.transaction');
Route::post('/generate/transactions/report', [TransactionController::class, 'generateTransactionsInExcel'])
->name('generate.transactions.report');



Route::get('/get/products', [ProductController::class, 'getAllProducts'])->name('get.products');
Route::get('{path}',[HomeController::class, 'index'])->where( 'path', '([A-z\/_.\d-]+)?' );
