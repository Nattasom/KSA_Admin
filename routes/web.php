<?php

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
Route::get('/', 'LoginController@index');
Route::post('/', 'LoginController@login');
Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::any('/logout', 'LoginController@logout');
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});//package:discover

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

//Clear package discover:
Route::get('/package-discover', function() {
    $exitCode = Artisan::call('package:discover');
    return '<h1>package discover</h1>';
});

//middle ware session check group  --> after login page
Route::group(['middleware' => 'usersession'], function () {
    Route::any('/dashboard', 'DashboardController@index');
    Route::any('/premium', 'PremiumController@index');
    Route::any('/datatable/premium', 'PremiumController@premiumDatatable');
    Route::any('/datatable/premium-processing', 'PremiumController@premiumProcessingDatatable');
    Route::any('/premium-import', 'PremiumController@import');
    Route::post('/ajax/premium-upload', 'PremiumController@uploadFile');
    Route::any('/premium-import-task', 'PremiumController@importFile');
    Route::any('/data/getpremium', 'PremiumController@getDataPremium');

    Route::any('/promotion', 'PromotionController@index');
    Route::any('/promotion-add', 'PromotionController@add');
    Route::any('/promotion-edit/{id}', 'PromotionController@edit');
    Route::any('/action/promotion-add', 'PromotionController@actionAdd');
    Route::any('/action/promotion-edit', 'PromotionController@actionEdit');
    Route::any('/action/promotion-status', 'PromotionController@actionStatus');
    Route::any('/datatable/promotion', 'PromotionController@promotionDatatable');

    Route::any('/droplead', 'DropleadController@index');
    Route::any('/datatable/droplead', 'DropleadController@dropleadDatatable');
    Route::any('/data/getdroplead', 'DropleadController@getDataDroplead');

    Route::get('/droplead/export','DropleadController@export');

    Route::any('/homecat', 'ContentController@homecat');
    Route::any('/homecat-add', 'ContentController@homecatAdd');
    Route::any('/homecat-edit/{id}', 'ContentController@homecatEdit');
    Route::any('/homecat-product/{id}', 'ContentController@homecatProduct');
    Route::any('/homecat-product-list/{id}', 'ContentController@homecatProductList');
    Route::any('/homecat-product-edit/{id}', 'ContentController@homecatProductEdit');
    Route::post('/action/homecat-add', 'ContentController@actionHomecatAdd');
    Route::post('/action/homecat-edit', 'ContentController@actionHomecatEdit');
    Route::post('/action/homecat-product', 'ContentController@actionHomecatProduct');
    Route::post('/action/homecat-product-edit', 'ContentController@actionHomecatProductEdit');
    Route::any('/action/homecat-status', 'ContentController@homecatSetStatus');
    Route::any('/datatable/homecat', 'ContentController@homecatDatatable');
    Route::any('/datatable/homecat-product-list', 'ContentController@homecatProductListDatatable');

    Route::any('/banner', 'ContentController@banner');
    Route::post('/action/banner-edit', 'ContentController@actionBannerEdit');
    Route::any('/banner-slider', 'ContentController@bannerSlider');
    Route::any('/datatable/banner-slider', 'ContentController@bannerSliderDatatable');
    Route::any('/banner-slider/add', 'ContentController@bannerSliderAdd');
    Route::any('/action/banner-slider-add', 'ContentController@actionBannerSliderAdd');
    Route::any('/action/banner-slider-edit', 'ContentController@actionBannerSliderEdit');
    Route::any('/action/banner-slider-status', 'ContentController@actionBannerSliderStatus');
    Route::any('/banner-slider/edit/{id}', 'ContentController@bannerSliderEdit');

    Route::any('/content', 'ContentController@content');
    Route::any('/content/add', 'ContentController@contentAdd');
    Route::any('/action/content-add', 'ContentController@actionContentAdd');
    Route::any('/content/edit/{id}', 'ContentController@contentEdit');
    Route::any('/action/content-edit', 'ContentController@actionContentEdit');
    Route::any('/datatable/content', 'ContentController@contentDatatable');
    Route::any('/action/content-status', 'ContentController@actionContentStatus');

    Route::any('/product', 'ProductController@index');
    Route::any('/product-add', 'ProductController@add');
    Route::any('/product-edit/{id}', 'ProductController@edit');
    Route::any('/datatable/product', 'ProductController@productDatatable');
    Route::post('/action/product-add', 'ProductController@actionAdd');
    Route::post('/action/product-edit', 'ProductController@actionEdit');
    Route::post('/action/product-status', 'ProductController@actionSetStatus');

    //Master Data
    Route::any('/data/loadmodelvalue', 'MasterController@loadModelValue');
    Route::any('/master/insurer', 'MasterController@insurerlist');
    Route::any('/master/insurer-add', 'MasterController@insureradd');
    Route::post('/action/insurer-add', 'MasterController@actionInsurerAdd');
    Route::post('/action/insurer-edit', 'MasterController@actionInsurerEdit');
    Route::post('/action/insurer-status', 'MasterController@actionSetStatusInsurer');
    Route::any('/master/insurer-edit/{id}', 'MasterController@insureredit');
    Route::any('/datatable/insurer', 'MasterController@insurerDatatable');
});
