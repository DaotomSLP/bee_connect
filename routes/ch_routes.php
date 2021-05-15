<?php

use App\Http\Controllers\ImportProductsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceController;

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

Route::get('/importpdf/{id}', [ImportProductsController::class, 'report'])->middleware('auth')->name('importreport');

Route::get('/salepdf/{id}', [ImportProductsController::class, 'salereport'])->middleware('auth')->name('salepdf');

Route::post('/deleteImportItem', [ImportProductsController::class, 'deleteImportItem'])->middleware('auth')->name('deleteImportItem');

Route::post('/changeImportWeight', [ImportProductsController::class, 'changeImportWeight'])->middleware('auth')->name('changeImportWeight');

Route::post('/changeImportItemWeight', [ImportProductsController::class, 'changeImportItemWeight'])->middleware('auth')->name('changeImportItemWeight');

Route::post('/importProductForUser', [ImportProductsController::class, 'insertImportForUser'])->middleware('auth')->name('importProductForUser');

Route::post('/insertSaleImport', [ImportProductsController::class, 'insertSaleImport'])->middleware('auth')->name('insertSaleImport');

Route::post('/insertSaleImportForRider', [ImportProductsController::class, 'insertSaleImportForRider'])->middleware('auth')->name('insertSaleImportForRider');

Route::post('/addPriceImport', [PriceController::class, 'insertPriceImport'])->middleware('auth')->name('addPriceImport');

Route::post('/addSalePriceImport​', [PriceController::class, 'insertSalePriceImport'])->middleware('auth')->name('addSalePriceImport​');

Route::post('/editSalePrice', [PriceController::class, 'editSalePrice'])->middleware('auth')->name('editSalePrice');

Route::post('/receiveImportProduct', [ProductController::class, 'updateImport'])->middleware('auth')->name('receiveImportProduct');

Route::post('/successImportProduct', [ProductController::class, 'successImport'])->middleware('auth')->name('successImportProduct');

Route::post('/getImportProduct', [ImportProductsController::class, 'getImportProduct'])->middleware('auth')->name('getImportProduct');

Route::get('/deleteLot', [ImportProductsController::class, 'deleteLot'])->middleware('auth')->name('deleteLot');

Route::get('/paidLot', [ImportProductsController::class, 'paidLot'])->middleware('auth')->name('paidLot');
Route::get('/addChinaProduct', [ImportProductsController::class, 'addChinaProduct'])->middleware('auth')->name('addChinaProduct');

Route::post('/checkImportProduct', [ImportProductsController::class, 'checkImportProduct'])->middleware('auth')->name('checkImportProduct');

Route::post('/insertChinaProduct', [ImportProductsController::class, 'insertChinaProduct'])->middleware('auth')->name('insertChinaProduct');

Route::post('/importProduct', [ImportProductsController::class, 'importProduct'])->middleware('auth')->name('importProduct');

Route::get('/successImport', [SuccessController::class, 'successImport'])->middleware('auth')->name('successImport');