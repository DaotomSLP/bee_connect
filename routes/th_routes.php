<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportProductsThController;
use App\Http\Controllers\PriceImportControllerTh;

Route::get('/addImportTh', [ImportProductsThController::class, 'addImportTh'])->middleware('auth')->name('addImportTh');

Route::get('/importTh', [ImportProductsThController::class, 'index'])->middleware('auth')->name('import');

Route::post('/checkImportProductTh', [ImportProductsThController::class, 'checkImportProductTh'])->middleware('auth')->name('checkImportProductTh');

Route::get('/importViewTh', [ImportProductsThController::class, 'importViewTh'])->middleware('auth')->name('importViewTh');

Route::get('/importDetailTh', [ImportProductsThController::class, 'importDetailTh'])->middleware('auth')->name('importDetailTh');

Route::get('/importDetailForUserTh', [ImportProductsThController::class, 'importDetailForUser'])->middleware('auth')->name('importDetailForUserTh');

Route::get('/importProductTrackTh', [ImportProductsThController::class, 'importProductTrackTh'])->middleware('auth')->name('importProductTrackTh');

Route::get('/dailyImportTh', [ImportProductsThController::class, 'dailyImportTh'])->middleware('auth')->name('dailyImportTh');

Route::post('/importProductTh', [ImportProductsThController::class, 'importProductTh'])->middleware('auth')->name('importProductTh');

Route::post('/changeImportWeightTh', [ImportProductsThController::class, 'changeImportWeightTh'])->middleware('auth')->name('changeImportWeightTh');

Route::get('/deleteLotTh', [ImportProductsThController::class, 'deleteLotTh'])->middleware('auth')->name('deleteLotTh');

Route::get('/paidLotTh', [ImportProductsThController::class, 'paidLotTh'])->middleware('auth')->name('paidLotTh');

Route::get('/importpdfTh/{id}', [ImportProductsThController::class, 'reportTh'])->middleware('auth')->name('importreportTh');

Route::get('/addImportThPdf/{id}', [ImportProductsThController::class, 'addImportThPdf'])->middleware('auth')->name('addImportThPdf');

Route::post('/deleteImportItemTh', [ImportProductsThController::class, 'deleteImportItemTh'])->middleware('auth')->name('deleteImportItemTh');

Route::get('/deleteImportItemThForWaiting', [ImportProductsThController::class, 'deleteImportItemThForWaiting'])->middleware('auth')->name('deleteImportItemThForWaiting');

Route::post('/changeImportItemWeightTh', [ImportProductsThController::class, 'changeImportItemWeightTh'])->middleware('auth')->name('changeImportItemWeightTh');

Route::post('/importProductForUserTh', [ImportProductsThController::class, 'insertImportForUserTh'])->middleware('auth')->name('importProductForUserTh');

Route::post('/getImportProductTh', [ImportProductsThController::class, 'getImportProductTh'])->middleware('auth')->name('getImportProductTh');

Route::get('/importViewForUserTh', [ImportProductsThController::class, 'importViewForUserTh'])->middleware('auth')->name('importViewForUserTh');

Route::get('/importProductTrackForUserTh', [ImportProductsThController::class, 'importProductTrackForUserTh'])->middleware('auth')->name('importProductTrackForUserTh');

Route::get('/saleImportTh', [ImportProductsThController::class, 'saleImportTh'])->middleware('auth')->name('saleImportTh');

Route::post('/insertSaleImportTh', [ImportProductsThController::class, 'insertSaleImportTh'])->middleware('auth')->name('insertSaleImportTh');

Route::post('/insertSaleImportForRiderTh', [ImportProductsThController::class, 'insertSaleImportForRiderTh'])->middleware('auth')->name('insertSaleImportForRiderTh');

Route::get('/saleViewTh', [ImportProductsThController::class, 'saleViewTh'])->middleware('auth')->name('saleViewTh');

Route::get('/salepdfTh/{id}', [ImportProductsThController::class, 'salereportTh'])->middleware('auth')->name('salepdfTh');

Route::get('/saleDetailTh', [ImportProductsThController::class, 'saleDetailTh'])->middleware('auth')->name('saleDetailTh');

Route::post('/addImportProductTh', [ImportProductsThController::class, 'addImportProductTh'])->middleware('auth')->name('addImportProductTh');

Route::post('/editSalePriceTh', [PriceImportControllerTh::class, 'editSalePriceTh'])->middleware('auth')->name('editSalePriceTh');

Route::get('/saleImportPriceTh', [PriceImportControllerTh::class, 'saleImportPriceTh'])->middleware('auth')->name('saleImportPriceTh');

Route::post('/addSalePriceImportTh​a', [PriceImportControllerTh::class, 'insertSalePriceImportTh'])->middleware('auth')->name('addSalePriceImportTh​a');

Route::get('/priceImportTh', [PriceImportControllerTh::class, 'priceImportTh'])->middleware('auth')->name('priceImportTh');

Route::post('/addPriceImportTh', [PriceImportControllerTh::class, 'insertPriceImportTh'])->middleware('auth')->name('addPriceImportTh');

Route::get('/base_price_th', [ImportProductsThController::class, 'base_price_th'])->middleware('auth')->name('base_price_th');

Route::post('/addBasePriceTh', [ImportProductsThController::class, 'addBasePriceTh'])->middleware('auth')->name('addBasePriceTh');

Route::get('/money_th', [ImportProductsThController::class, 'money_th'])->middleware('auth')->name('money_th');

Route::get('/withdraw_th', [ImportProductsThController::class, 'withdraw_th'])->middleware('auth')->name('withdraw_th');

Route::get('/withdraw_detail_th/{id}', [ImportProductsThController::class, 'withdraw_detail_th'])->middleware('auth')->name('withdraw_detail_th');

Route::post('/addWithDrawTh', [ImportProductsThController::class, 'addWithDrawTh'])->middleware('auth')->name('addWithDrawTh');

Route::get('/trackingTh', [HomeController::class, 'trackingTh'])->name('trackingTh');

Route::post('/searchTrackingTh', [HomeController::class, 'searchTrackingTh'])->name('searchTrackingTh');





