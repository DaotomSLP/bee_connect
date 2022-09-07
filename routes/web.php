<?php

use App\Http\Controllers\Api\ImportProductApi;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ExpenditureController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\TestDesignController;

require('th_routes.php');
require('ch_routes.php');
require('ch_routes_test.php');

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

Route::get('/access_denied', [UsersController::class, 'access_denied'])->middleware('auth')->name('access_denied');

Route::get('/send', [ProductController::class, 'send'])->middleware('auth')->name('send');

Route::get('/allProducts', [ProductController::class, 'allProducts'])->middleware('auth')->name('allProducts');

Route::get('/pdf/{id}', [ProductController::class, 'report'])->middleware('auth')->name('report');

Route::get('/paidProduct', [ProductController::class, 'paidProduct'])->middleware('auth')->name('paidProduct');

Route::get('/paidProductForSecondBranch', [ProductController::class, 'paidProductForSecondBranch'])->middleware('auth')->name('paidProductForSecondBranch');

Route::get('/receive', [ProductController::class, 'receive'])->middleware('auth')->name('receive');

Route::get('/success', [ProductController::class, 'success'])->middleware('auth')->name('success');

Route::get('/price', [PriceController::class, 'index'])->middleware('auth')->name('price');

Route::get('/users', [UsersController::class, 'index'])->middleware('auth')->name('users');

Route::get('/superAdmin', [UsersController::class, 'superAdmin'])->middleware('auth')->name('superAdmin');

Route::get('/branchs', [BranchController::class, 'index'])->middleware('auth')->name('branchs');

Route::get('/branchs/{offset}', [BranchController::class, 'pagination'])->middleware('auth')->name('branchs');

Route::post('/addProduct', [ProductController::class, 'insert'])->middleware('auth')->name('addProduct');

Route::post('/addPrice', [PriceController::class, 'insert'])->middleware('auth')->name('addPrice');

Route::post('/receiveProduct', [ProductController::class, 'update'])->middleware('auth')->name('receiveProduct');

Route::post('/successProduct', [ProductController::class, 'successProduct'])->middleware('auth')->name('successProduct');

Route::post('/addBranch', [BranchController::class, 'insert'])->middleware('auth')->name('addBranch');

Route::get('/editBranch/{id}', [BranchController::class, 'edit'])->middleware('auth')->name('editBranch');

Route::post('/updateBranch', [BranchController::class, 'update'])->middleware('auth')->name('updateBranch');

Route::get('/deleteBranch/{id}', [BranchController::class, 'delete'])->middleware('auth')->name('deleteBranch');

Route::post('/addUser', [UsersController::class, 'insert'])->middleware('auth')->name('addUser');

Route::get('/editUser/{id}', [UsersController::class, 'edit'])->middleware('auth')->name('editUser');

Route::post('/updateUser', [UsersController::class, 'update'])->middleware('auth')->name('updateUser');

Route::get('/deleteUser/{id}', [UsersController::class, 'delete'])->middleware('auth')->name('deleteUser');

Route::get('/expenditure', [ExpenditureController::class, 'index'])->middleware('auth')->name('expenditure');

Route::post('/addExpenditure', [ExpenditureController::class, 'insert'])->middleware('auth')->name('addExpenditure');

Route::get('/partner', [UsersController::class, 'partner'])->middleware('auth')->name('partner');

Route::post('/insertPartner', [UsersController::class, 'insertPartner'])->middleware('auth')->name('insertPartner');

Route::get('/editPartner/{id}', [UsersController::class, 'editPartner'])->middleware('auth')->name('editPartner');

Route::post('/updatePartner', [UsersController::class, 'updatePartner'])->middleware('auth')->name('updatePartner');

Route::get('/admin', [UsersController::class, 'admin'])->middleware('auth')->name('admin');

Route::post('/admin', [UsersController::class, 'insertAdmin'])->middleware('auth')->name('insertAdmin');

Route::get('/editAdmin/{id}', [UsersController::class, 'editAdmin'])->middleware('auth')->name('editAdmin');

Route::post('/updateAdmin', [UsersController::class, 'updateAdmin'])->middleware('auth')->name('updateAdmin');

Route::get('/api/import_products/{id}', [ImportProductApi::class, 'import_products'])->name('import_products_api');



//test design
Route::get('/testdesign/home', [TestDesignController::class, 'index'])->name('index');

Route::get('/testdesign/detail', [TestDesignController::class, 'detail'])->name('detail');

