<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('get_banner', 'API\ContentController@getBanner');
Route::post('get_banner_slider', 'API\ContentController@getBannerSlider');
Route::post('get_home_cat', 'API\ContentController@getHomecat');
Route::post('get_home_cat_list', 'API\ContentController@getHomecatList');
Route::post('producthomedetail', 'API\ContentController@getHomecatDetail');
Route::post('productlist', 'API\ProductController@productlist');
Route::post('productdetail', 'API\ProductController@productDetail');
Route::post('get_content_list', 'API\ContentController@getContentList');
Route::post('get_content_detail', 'API\ContentController@getContentDetail');

//Master
Route::post('get_insurer_list', 'API\MasterController@getInsurerList');
Route::post('get_make_value_list', 'API\MasterController@getMakeValueList');
Route::post('get_model_value_list', 'API\MasterController@getModelValueList');
Route::post('get_model_year_list', 'API\MasterController@getModelYearList');
Route::post('get_claim_type', 'API\MasterController@getClaimType');
Route::post('get_sperate_pay_list ', 'API\MasterController@getSperatePayList');


Route::post('get_min_max_premium', 'API\PremiumController@getMinMaxPremium');
Route::post('get_min_max_suminsured', 'API\PremiumController@getMinMaxSumInsured');
Route::post('get_min_max_tppd', 'API\PremiumController@getMinMaxTPPD');
Route::post('send_droplead', 'API\PremiumController@sendDroplead');


