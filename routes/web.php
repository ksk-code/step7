<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'redirectToProducts'])->name('home');
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.detail');

Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');