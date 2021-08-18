<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportProductsController;
use App\Http\Controllers\PriceImportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleImportProductsController;

Route::get('/', [ImportProductsController::class, 'dailyImport'])->middleware('auth')->name('dailyImport');

Route::get('/home', [ImportProductsController::class, 'index'])->middleware('auth')->name('home');

Route::get('/import', [ImportProductsController::class, 'index'])->middleware('auth')->name('import');

Route::get('/dailyImport', [ImportProductsController::class, 'dailyImport'])->middleware('auth')->name('dailyImport');

Route::get('/importView', [ImportProductsController::class, 'importView'])->middleware('auth')->name('importView');

Route::get('/priceImport', [PriceImportController::class, 'priceImport'])->middleware('auth')->name('priceImport');

Route::get('/importViewForUser', [ImportProductsController::class, 'importViewForUser'])->middleware('auth')->name('importViewForUser');

Route::get('/importDetail', [ImportProductsController::class, 'importDetail'])->middleware('auth')->name('importDetail');

Route::get('/importDetailForUser', [ImportProductsController::class, 'importDetailForUser'])->middleware('auth')->name('importDetailForUser');

Route::get('/importProductTrack', [ImportProductsController::class, 'importProductTrack'])->middleware('auth')->name('importProductTrack');

Route::get('/importProductTrackForUser', [ImportProductsController::class, 'importProductTrackForUser'])->middleware('auth')->name('importProductTrackForUser');

Route::get('/importpdf/{id}', [ImportProductsController::class, 'report'])->middleware('auth')->name('importreport');

Route::post('/deleteImportItem', [ImportProductsController::class, 'deleteImportItem'])->middleware('auth')->name('deleteImportItem');

Route::post('/changeImportWeight', [ImportProductsController::class, 'changeImportWeight'])->middleware('auth')->name('changeImportWeight');

Route::post('/changeImportItemWeight', [ImportProductsController::class, 'changeImportItemWeight'])->middleware('auth')->name('changeImportItemWeight');

Route::post('/importProductForUser', [ImportProductsController::class, 'insertImportForUser'])->middleware('auth')->name('importProductForUser');

Route::post('/insertSaleImportForRider', [ImportProductsController::class, 'insertSaleImportForRider'])->middleware('auth')->name('insertSaleImportForRider');

Route::post('/receiveImportProduct', [ImportProductsController::class, 'updateImport'])->middleware('auth')->name('receiveImportProduct');

Route::post('/getImportProduct', [ImportProductsController::class, 'getImportProduct'])->middleware('auth')->name('getImportProduct');

Route::get('/deleteLot', [ImportProductsController::class, 'deleteLot'])->middleware('auth')->name('deleteLot');

Route::get('/paidLot', [ImportProductsController::class, 'paidLot'])->middleware('auth')->name('paidLot');

Route::get('/addChinaProduct', [ImportProductsController::class, 'addChinaProduct'])->middleware('auth')->name('addChinaProduct');

Route::post('/checkImportProduct', [ImportProductsController::class, 'checkImportProduct'])->middleware('auth')->name('checkImportProduct');

Route::post('/insertChinaProduct', [ImportProductsController::class, 'insertChinaProduct'])->middleware('auth')->name('insertChinaProduct');

Route::post('/importProduct', [ImportProductsController::class, 'insertImport'])->middleware('auth')->name('importProduct');

Route::get('/receiveImport', [ImportProductsController::class, 'receiveImport'])->middleware('auth')->name('receiveImport');

Route::get('/serviceChargeDetail', [ImportProductsController::class, 'serviceChargeDetail'])->middleware('auth')->name('serviceChargeDetail');

Route::get('/editServiceCharge', [ImportProductsController::class, 'editServiceCharge'])->middleware('auth')->name('editServiceCharge');

Route::get('/saleView', [SaleImportProductsController::class, 'saleView'])->middleware('auth')->name('saleView');

Route::get('/saleImport', [SaleImportProductsController::class, 'saleImport'])->middleware('auth')->name('saleImport');

Route::get('/salepdf/{id}', [SaleImportProductsController::class, 'salereport'])->middleware('auth')->name('salepdf');

Route::get('/saleDetail', [SaleImportProductsController::class, 'saleDetail'])->middleware('auth')->name('saleDetail');

Route::post('/insertSaleImport', [SaleImportProductsController::class, 'insertSaleImport'])->middleware('auth')->name('insertSaleImport');

Route::post('/addPriceImport', [PriceImportController::class, 'insertPriceImport'])->middleware('auth')->name('addPriceImport');

Route::post('/addSalePriceImport​', [PriceImportController::class, 'insertSalePriceImport'])->middleware('auth')->name('addSalePriceImport​');

Route::post('/editSalePrice', [PriceImportController::class, 'editSalePrice'])->middleware('auth')->name('editSalePrice');

Route::get('/saleImportPrice', [PriceImportController::class, 'saleImportPrice'])->middleware('auth')->name('saleImportPrice');

Route::get('/money_ch', [ImportProductsController::class, 'money_ch'])->middleware('auth')->name('money_ch');

Route::get('/withdraw_ch', [ImportProductsController::class, 'withdraw_ch'])->middleware('auth')->name('withdraw_ch');

Route::get('/withdraw_detail_ch/{id}', [ImportProductsController::class, 'withdraw_detail_ch'])->middleware('auth')->name('withdraw_detail_ch');

Route::post('/addWithDrawCh', [ImportProductsController::class, 'addWithDrawCh'])->middleware('auth')->name('addWithDrawCh');

Route::get('/tracking',[HomeController::class,'tracking'])->name('tracking');
