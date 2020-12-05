<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api'], function(){

    Route::get('app/load/','CategoryController@AppLoad');
    Route::get('sub/category/list/{category_id}','CategoryController@subCategoryList');

    Route::get('charges/list','CartController@chargesList');

    Route::group(['prefix'=>'product'],function(){
        Route::get('list/{category_id}/{type}/{page}','ProductController@productList');
        Route::get('product/single/view/{product_id}','ProductController@singleProductView');
        
    });


    // Route::get('product/search/{search_key}','ProductController@productSearch');

    Route::post('user/registration','UserController@userRegistration');
    Route::post('user/login','UserController@userLogin');


    Route::get('send/otp/{mobile}','UserController@sendOtp');
    // Route::get('verify/otp/{mobile}/{otp}','UserController@varifyOtp');
    Route::post('user/forgot/change/password/','UserController@forgotChangePass');

    Route::group(['middleware'=>'auth:api','prefix'=>'user'],function(){
        Route::get('profile/{user_id}','UserController@userProfile');
        Route::post('profile/update','UserController@userProfileUpdate');
        Route::post('change/password','UserController@userChangePassword');
        // Route::get('logout/{user_id}','UserController@userLogout');

        Route::group(['prefix'=>'shipping'],function(){
            Route::post('add','UserController@userShippingAdd');
            Route::get('list/{user_id}','UserController@userShippingList');
            Route::get('single/{user_id}/{address_id}','UserController@userShippingSingleView');
            Route::post('update','UserController@userShippingUpdate');
            Route::get('delete/{address_id}','UserController@userShippingDelete');
        });


        Route::group(['prefix'=>'cart'],function(){
            Route::post('add','CartController@addToCart');
            Route::get('fetch/product/{user_id}','CartController@cartProduct');
            Route::get('update/quantity/{cart_id}/{quantity}','CartController@cartUpdate');
            Route::get('remove/item/{cart_id}','CartController@cartRemove');
        });

        Route::group(['prefix'=>'wish/list',],function(){
            Route::get('add/{product_id}/{user_id}','CartController@addToWishList');
            Route::get('items/{user_id}','CartController@wishListProducts');
            // Route::get('wish/to/cart/{user_id}/{wish_list_id}','CartController@wishListToCart');
            Route::get('item/remove/{wish_list_id}','CartController@wishListItemRemove');
        });


        Route::post('place/order','OrderController@placeOrder');
        Route::post('order/payment/verify','OrderController@paymentVerify');

        Route::get('order/history/{user_id}/{page}','OrderController@orderHistory');

        Route::post('order/refund/info/insert/','OrderController@refundInfoInsert');
        Route::get('order/cancel/{order_id}','OrderController@orderCancel');
    });

    Route::group(['prefix' => 'delivery'], function () {
        Route::post('/login','DeliveryBoyController@userLogin');
        Route::post('forgot/change/password/','DeliveryBoyController@forgotChangePass');

        Route::group(['middleware' => 'auth:dBoy'], function () {
            Route::get('profile/{user_id}','DeliveryBoyController@userProfile');
            Route::post('profile/update','DeliveryBoyController@userProfileUpdate');
            Route::post('change/password','DeliveryBoyController@userChangePassword');
            Route::post('order/list','DeliveryBoyController@orderList');
            Route::get('order/update/{order_id}','DeliveryBoyController@orderUpdate');
        });

    });
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
