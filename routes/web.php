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

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.detail');

Route::post('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
