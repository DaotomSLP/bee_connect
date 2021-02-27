<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ExpenditureController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportProductsController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SuccessController;
use App\Models\Import_products;
use App\Models\Product;
use App\Models\User;

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


Route::get('/', [HomeController::class, 'dailyImport'])->middleware('auth')->name('dailyImport');

Auth::routes();

Route::get('/send', [SendController::class, 'index'])->middleware('auth')->name('send');

Route::get('/allProducts', [ProductController::class, 'allProducts'])->middleware('auth')->name('allProducts');

Route::get('/import', [ImportProductsController::class, 'index'])->middleware('auth')->name('import');

Route::get('/importView', [ImportProductsController::class, 'importView'])->middleware('auth')->name('importView');

Route::get('/saleView', [ImportProductsController::class, 'saleView'])->middleware('auth')->name('saleView');

Route::get('/saleImport', [ImportProductsController::class, 'saleImport'])->middleware('auth')->name('saleImport');

Route::get('/importViewForUser', [ImportProductsController::class, 'importViewForUser'])->middleware('auth')->name('importViewForUser');

Route::get('/importDetail', [ImportProductsController::class, 'importDetail'])->middleware('auth')->name('importDetail');

Route::get('/saleDetail', [ImportProductsController::class, 'saleDetail'])->middleware('auth')->name('saleDetail');

Route::get('/importDetailForUser', [ImportProductsController::class, 'importDetailForUser'])->middleware('auth')->name('importDetailForUser');

Route::get('/importProductTrack', [ImportProductsController::class, 'importProductTrack'])->middleware('auth')->name('importProductTrack');

Route::get('/importProductTrackForUser', [ImportProductsController::class, 'importProductTrackForUser'])->middleware('auth')->name('importProductTrackForUser');

Route::get('/pdf/{id}', [ProductController::class, 'report'])->middleware('auth')->name('report');

Route::get('/importpdf/{id}', [ImportProductsController::class, 'report'])->middleware('auth')->name('importreport');

Route::get('/salepdf/{id}', [ImportProductsController::class, 'salereport'])->middleware('auth')->name('salepdf');

Route::get('/paidProduct', [ProductController::class, 'paidProduct'])->middleware('auth')->name('paidProduct');

Route::get('/paidProductForSecondBranch', [ProductController::class, 'paidProductForSecondBranch'])->middleware('auth')->name('paidProductForSecondBranch');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/dailyImport', [HomeController::class, 'dailyImport'])->middleware('auth')->name('dailyImport');

Route::get('/receive', [ReceiveController::class, 'index'])->middleware('auth')->name('receive');

Route::get('/receiveImport', [ReceiveController::class, 'receiveImport'])->middleware('auth')->name('receiveImport');

Route::get('/success', [SuccessController::class, 'index'])->middleware('auth')->name('success');

Route::get('/successImport', [SuccessController::class, 'successImport'])->middleware('auth')->name('successImport');

Route::get('/price', [PriceController::class, 'index'])->middleware('auth')->name('price');

Route::get('/priceImport', [PriceController::class, 'priceImport'])->middleware('auth')->name('priceImport');

Route::get('/saleImportPrice', [PriceController::class, 'saleImportPrice'])->middleware('auth')->name('saleImportPrice');

Route::get('/users', [UsersController::class, 'index'])->middleware('auth')->name('users');

Route::get('/branchs', [BranchController::class, 'index'])->middleware('auth')->name('branchs');

Route::get('/branchs/{offset}', [BranchController::class, 'pagination'])->middleware('auth')->name('branchs');

Route::post('/addProduct', [ProductController::class, 'insert'])->middleware('auth')->name('addProduct');

Route::post('/importProduct', [ImportProductsController::class, 'insertImport'])->middleware('auth')->name('importProduct');

Route::get('/deleteLot', [ImportProductsController::class, 'deleteLot'])->middleware('auth')->name('deleteLot');

Route::get('/paidLot', [ImportProductsController::class, 'paidLot'])->middleware('auth')->name('paidLot');

Route::post('/deleteImportItem', [ImportProductsController::class, 'deleteImportItem'])->middleware('auth')->name('deleteImportItem');

Route::post('/changeImportWeight', [ImportProductsController::class, 'changeImportWeight'])->middleware('auth')->name('changeImportWeight');

Route::post('/changeImportItemWeight', [ImportProductsController::class, 'changeImportItemWeight'])->middleware('auth')->name('changeImportItemWeight');

Route::post('/importProductForUser', [ImportProductsController::class, 'insertImportForUser'])->middleware('auth')->name('importProductForUser');

Route::post('/insertSaleImport', [ImportProductsController::class, 'insertSaleImport'])->middleware('auth')->name('insertSaleImport');

Route::post('/addPrice', [PriceController::class, 'insert'])->middleware('auth')->name('addPrice');

Route::post('/addPriceImport', [PriceController::class, 'insertPriceImport'])->middleware('auth')->name('addPriceImport');

Route::post('/addSalePriceImport​', [PriceController::class, 'insertSalePriceImport'])->middleware('auth')->name('addSalePriceImport​');

Route::post('/editSalePrice', [PriceController::class, 'editSalePrice'])->middleware('auth')->name('editSalePrice');

Route::post('/receiveProduct', [ProductController::class, 'update'])->middleware('auth')->name('receiveProduct');

Route::post('/receiveImportProduct', [ProductController::class, 'updateImport'])->middleware('auth')->name('receiveImportProduct');

Route::post('/successProduct', [ProductController::class, 'success'])->middleware('auth')->name('successProduct');

Route::post('/successImportProduct', [ProductController::class, 'successImport'])->middleware('auth')->name('successImportProduct');

Route::post('/addBranch', [BranchController::class, 'insert'])->middleware('auth')->name('addBranch');

Route::get('/editBranch/{id}', [BranchController::class, 'edit'])->middleware('auth')->name('editBranch');

Route::post('/updateBranch', [BranchController::class, 'update'])->middleware('auth')->name('updateBranch');

Route::get('/deleteBranch/{id}', [BranchController::class, 'delete'])->middleware('auth')->name('deleteBranch');

Route::post('/addUser', [UsersController::class, 'insert'])->middleware('auth')->name('addUser');

Route::get('/editUser/{id}', [UsersController::class, 'edit'])->middleware('auth')->name('editUser');

Route::post('/updateUser', [UsersController::class, 'update'])->middleware('auth')->name('updateUser');

Route::get('/deleteUser/{id}', [UsersController::class, 'delete'])->middleware('auth')->name('deleteUser');

Route::post('/getImportProduct', [ImportProductsController::class, 'getImportProduct'])->middleware('auth')->name('getImportProduct');

Route::get('/expenditure', [ExpenditureController::class, 'index'])->middleware('auth')->name('expenditure');

Route::post('/addExpenditure', [ExpenditureController::class, 'insert'])->middleware('auth')->name('addExpenditure');

Route::get('/partner', [UsersController::class, 'partner'])->middleware('auth')->name('partner');

Route::post('/insertPartner', [UsersController::class, 'insertPartner'])->middleware('auth')->name('insertPartner');

Route::get('/editPartner/{id}', [UsersController::class, 'editPartner'])->middleware('auth')->name('editPartner');

Route::post('/updatePartner', [UsersController::class, 'updatePartner'])->middleware('auth')->name('updatePartner');

Route::get('/admin', [UsersController::class, 'admin'])->middleware('auth')->name('admin');

Route::post('/insertAdmin', [UsersController::class, 'insertAdmin'])->middleware('auth')->name('insertAdmin');

Route::get('/editAdmin/{id}', [UsersController::class, 'editAdmin'])->middleware('auth')->name('editAdmin');

Route::post('/updateAdmin', [UsersController::class, 'updateAdmin'])->middleware('auth')->name('updateAdmin');
