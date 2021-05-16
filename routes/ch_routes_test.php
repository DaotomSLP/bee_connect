<?php

use App\Http\Controllers\ImportProductsControllerCh;
use App\Http\Controllers\PriceImportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleImportProductsControllerCh;
use App\Models\Price_imports;

Route::get('/importCh', [ImportProductsControllerCh::class, 'index'])->middleware('auth')->name('importCh');

Route::get('/dailyImportCh', [ImportProductsControllerCh::class, 'dailyImport'])->middleware('auth')->name('dailyImportCh');

Route::get('/importViewCh', [ImportProductsControllerCh::class, 'importView'])->middleware('auth')->name('importViewCh');

Route::get('/priceImportCh', [PriceImportController::class, 'priceImport'])->middleware('auth')->name('priceImportCh');

Route::get('/importViewForUserCh', [ImportProductsControllerCh::class, 'importViewForUser'])->middleware('auth')->name('importViewForUserCh');

Route::get('/importDetailCh', [ImportProductsControllerCh::class, 'importDetail'])->middleware('auth')->name('importDetailCh');

Route::get('/importDetailForUserCh', [ImportProductsControllerCh::class, 'importDetailForUser'])->middleware('auth')->name('importDetailForUserCh');

Route::get('/importProductTrackCh', [ImportProductsControllerCh::class, 'importProductTrack'])->middleware('auth')->name('importProductTrackCh');

Route::get('/importProductTrackForUserCh', [ImportProductsControllerCh::class, 'importProductTrackForUser'])->middleware('auth')->name('importProductTrackForUserCh');

Route::get('/importpdfCh/{id}', [ImportProductsControllerCh::class, 'report'])->middleware('auth')->name('importreportCh');

Route::post('/deleteImportItemCh', [ImportProductsControllerCh::class, 'deleteImportItem'])->middleware('auth')->name('deleteImportItemCh');

Route::post('/changeImportWeightCh', [ImportProductsControllerCh::class, 'changeImportWeight'])->middleware('auth')->name('changeImportWeightCh');

Route::post('/changeImportItemWeightCh', [ImportProductsControllerCh::class, 'changeImportItemWeight'])->middleware('auth')->name('changeImportItemWeightCh');

Route::post('/importProductForUserCh', [ImportProductsControllerCh::class, 'insertImportForUser'])->middleware('auth')->name('importProductForUserCh');

Route::post('/insertSaleImportForRiderCh', [ImportProductsControllerCh::class, 'insertSaleImportForRider'])->middleware('auth')->name('insertSaleImportForRiderCh');

Route::post('/receiveImportProductCh', [ImportProductsControllerCh::class, 'updateImport'])->middleware('auth')->name('receiveImportProductCh');

Route::post('/getImportProductCh', [ImportProductsControllerCh::class, 'getImportProduct'])->middleware('auth')->name('getImportProductCh');

Route::get('/deleteLotCh', [ImportProductsControllerCh::class, 'deleteLot'])->middleware('auth')->name('deleteLotCh');

Route::get('/paidLotCh', [ImportProductsControllerCh::class, 'paidLot'])->middleware('auth')->name('paidLotCh');
Route::get('/addChinaProductCh', [ImportProductsControllerCh::class, 'addChinaProduct'])->middleware('auth')->name('addChinaProductCh');

Route::post('/checkImportProductCh', [ImportProductsControllerCh::class, 'checkImportProduct'])->middleware('auth')->name('checkImportProductCh');

Route::post('/insertChinaProductCh', [ImportProductsControllerCh::class, 'insertChinaProduct'])->middleware('auth')->name('insertChinaProductCh');

Route::post('/importProductCh', [ImportProductsControllerCh::class, 'importProduct'])->middleware('auth')->name('importProductCh');

Route::get('/receiveImportCh', [ImportProductsControllerCh::class, 'receiveImport'])->middleware('auth')->name('receiveImportCh');

Route::get('/saleViewCh', [SaleImportProductsControllerCh::class, 'saleView'])->middleware('auth')->name('saleViewCh');

Route::get('/saleImportCh', [SaleImportProductsControllerCh::class, 'saleImport'])->middleware('auth')->name('saleImportCh');

Route::get('/salepdfCh/{id}', [SaleImportProductsControllerCh::class, 'salereport'])->middleware('auth')->name('salepdfCh');

Route::get('/saleDetailCh', [SaleImportProductsControllerCh::class, 'saleDetail'])->middleware('auth')->name('saleDetailCh');

Route::post('/insertSaleImportCh', [SaleImportProductsControllerCh::class, 'insertSaleImport'])->middleware('auth')->name('insertSaleImportCh');

Route::post('/addPriceImportCh', [PriceImportController::class, 'insertPriceImport'])->middleware('auth')->name('addPriceImportCh');

Route::post('/addSalePriceImport​Ch', [PriceImportController::class, 'insertSalePriceImport'])->middleware('auth')->name('addSalePriceImport​Ch');

Route::post('/editSalePriceCh', [PriceImportController::class, 'editSalePrice'])->middleware('auth')->name('editSalePriceCh');

Route::get('/saleImportPriceCh', [PriceImportController::class, 'saleImportPrice'])->middleware('auth')->name('saleImportPriceCh');
