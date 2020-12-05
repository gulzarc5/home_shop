<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'],function(){
    Route::get('/admin/login','LoginController@index')->name('admin.login_form');
    Route::post('login', 'LoginController@adminLogin');


    Route::group(['middleware'=>'auth:admin','prefix'=>'admin'],function(){
        Route::get('/dashboard', 'DashboardController@dashboardView')->name('admin.deshboard');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
        Route::get('/change/password/form', 'DashboardController@changePasswordForm')->name('admin.change_password_form');
        Route::post('/change/password', 'DashboardController@changePassword')->name('admin.change_password');

        Route::group(['prefix'=>'category'],function(){
            Route::get('list','CategoryController@categoryList')->name('admin.category_list');
            Route::get('add/form', 'CategoryController@categoryAddForm')->name('admin.category_add_form');
            Route::post('insert/form', 'CategoryController@categoryInsertForm')->name('admin.category_insert_form');
            Route::get('status/{id}/{status}', 'CategoryController@categoryStatus')->name('admin.category_status');
            Route::get('edit/{id}', 'CategoryController@categoryEdit')->name('admin.category_edit');
            Route::put('update/{id}', 'CategoryController@categoryUpdate')->name('admin.category_update');
        });

        Route::group(['prefix'=>'sub/category'],function(){
            Route::get('list','CategoryController@subCategoryList')->name('admin.sub_category_list');
            Route::get('add/form', 'CategoryController@subCategoryAddForm')->name('admin.sub_category_add_form');
            Route::post('insert/form', 'CategoryController@subCategoryInsertForm')->name('admin.sub_category_insert_form');
            Route::get('edit/{id}', 'CategoryController@subCategoryEdit')->name('admin.sub_category_edit');
            Route::put('update/{id}', 'CategoryController@subCategoryUpdate')->name('admin.sub_category_update');
            Route::get('list/with/category/{category_id}', 'CategoryController@subCategoryListWithCategory')->name('admin.sub_category_list_with_category');
            Route::get('status/{id}/{status}', 'CategoryController@subCategoryStatus')->name('admin.sub_category_status');
        });

        Route::group(['prefix'=>'product'],function(){
            Route::get('add/form','ProductController@AddForm')->name('admin.product_add_form');
            Route::post('insert','ProductController@insertProduct')->name('admin.product_insert');


            Route::get('list','ProductController@productList')->name('admin.product_list');
            Route::get('list/ajax','ProductController@productListAjax')->name('admin.product_list_ajax');
            Route::get('view/{id}','ProductController@productView')->name('admin.product_view');

            Route::get('edit/{id}','ProductController@productEdit')->name('admin.product_edit');
            Route::put('update/{id}','ProductController@productUpdate')->name('admin.product_update');
            Route::get('status/update/{id}/{status}','ProductController@productStatusUpdate')->name('admin.product_status_update');

            Route::get('edit/images/{product_id}','ProductController@editImages')->name('admin.product_edit_images');
            Route::post('add/new/images/','ProductController@addNewImages')->name('admin.product_add_new_images');
            Route::get('make/cover/image/{product_id}/{image_id}','ProductController@makeCoverImage')->name('admin.product_make_cover_image');
            Route::get('delete/image/{image_id}','ProductController@deleteImage')->name('admin.product_delete_image');

            Route::get('related/product/list/{product_id}','ProductController@relatedProductlist')->name('admin.related_products_list');
            Route::get('remove/related/product/{id}','ProductController@removeRelatedProduct')->name('admin.remove_related_product');
            Route::get('add/related/product/form/{product_id}','ProductController@addRelatedProductForm')->name('admin.add_related_product_form');
            Route::post('add/related/product/{product_id}','ProductController@addRelatedProduct')->name('admin.add_related_product');

            Route::get('make/product/popular/{product_id}/{status}','ProductController@makeProductPopular')->name('admin.make_product_popular');
            Route::get('popular/list','ProductController@popularProductlist')->name('admin.popular_product_list');
            Route::get('remove/popular/{product_id}','ProductController@removePopularProduct')->name('admin.remove_popular_product');
           

            Route::group(['prefix' => 'meat'], function () {
                Route::get('add/form','ProductController@AddMeatForm')->name('admin.meat_add_form');
                Route::get('edit/{product_id}','ProductController@editMeatForm')->name('admin.meat_edit_form');
                Route::put('update/{product_id}','ProductController@updateMeatItem')->name('admin.update_meat');
                Route::post('insert','ProductController@insertMeatProduct')->name('admin.meat_insert');
                Route::get('list','ProductController@productMeatList')->name('admin.product_meat_list');
                Route::get('list/ajax','ProductController@productListAjaxMeat')->name('admin.product_list_ajax_meat');
            });

            Route::get('edit/sizes/{product_id}','ProductController@editSizes')->name('admin.product_edit_sizes');
            Route::put('add/new/sizes/{product_id}','ProductController@addNewSize')->name('admin.product_add_new_sizes');
            Route::put('update/sizes/{product_id}','ProductController@updateSize')->name('admin.product_update_sizes');



        });


        Route::group(['prefix'=>'appSetting'],function(){
            Route::get('slider/list','AppSettingController@slider_list')->name('admin.slider_list');
            Route::get('slider/add/form','AppSettingController@sliderAddForm')->name('admin.sliderAddForm');
            Route::post('slider/add','AppSettingController@sliderAdd')->name('admin.sliderAdd');
            Route::get('slider/delete/{slider_id}','AppSettingController@sliderDelete')->name('admin.slider_delete');
            Route::get('slider/status/{id}/{status}','AppSettingController@sliderStatus')->name('admin.sliderStatus');
            Route::get('retailer/list','AppSettingController@retailerList')->name('admin.retailer_list');

            Route::get('list','AppSettingController@chargesList')->name('admin.charges_list');
            Route::get('edit/{id}', 'AppSettingController@chargesEdit')->name('admin.charges_edit');
            Route::put('update/{id}', 'AppSettingController@chargesUpdate')->name('admin.charges_update');
            Route::get('status/{id}/{status}', 'AppSettingController@chargesStatus')->name('admin.charges_status');
        });

        Route::group(['prefix'=>'user'],function(){
            Route::get('list','UserController@userList')->name('admin.user_list');
            Route::get('list/ajax','UserController@userListAjax')->name('admin.user_list_ajax');
            Route::get('edit/{id}','UserController@userEdit')->name('admin.user_edit');
            Route::put('update/{id}','UserController@userUpdate')->name('admin.user_update');
        });

        Route::group(['prefix'=>'deliveryBoy'],function(){
            Route::get('list','UserController@deliveryBoyList')->name('admin.delivery_boy_list');
            Route::get('list/ajax','UserController@deliveryBoyListAjax')->name('admin.delivery_boy_list_ajax');
            Route::get('add/form','UserController@deliveryBoyAddForm')->name('admin.delivery_boy_add_form');
            Route::post('add','UserController@deliveryBoyAdd')->name('admin.delivery_boy_add');
            Route::get('edit/{id}','UserController@deliveryBoyEdit')->name('admin.delivery_boy_edit');
            Route::put('update/{id}','UserController@deliveryBoyUpdate')->name('admin.delivery_boy_update');
        });

        Route::group(['prefix'=>'order'],function(){
            Route::get('list', 'OrderController@orderList')->name('admin.order_list');
            Route::get('assigned/list', 'OrderController@assignedOrderList')->name('admin.assigned_order_list');

            Route::get('refund/info/form/{order_id}', 'OrderController@refundInfoForm')->name('admin.order_refund_info_form');
            Route::put('refund/info/insert/{order_id}', 'OrderController@refundInfoInsert')->name('admin.order_refund_info_insert');
            Route::get('refund/info/view/{order_id}', 'OrderController@refundInfoView')->name('admin.order_refund_info_view');

            Route::get('refund/list', 'OrderController@refundList')->name('admin.refund_order_list');
            Route::get('refund/status/{order_id}', 'OrderController@refundUpdate')->name('admin.refund_update');

            Route::get('status/update/{order_id}/{status}','OrderController@statusUpdate')->name('admin.order_status_update');
            Route::get('/details/{order_id}','OrderController@orderDetails')->name('admin.order_details');
            Route::get('/delivery/boy/assign/{order_id?}/{boy_id?}','OrderController@orderDeliveryBoyAssign')->name('admin.delivery_boy_assign');
        });

        Route::group(['prefix'=>'setting'],function(){
            Route::get('invoice', 'AppSettingController@invoiceForm')->name('admin.invoice_form');
            Route::post('invoice/update/', 'AppSettingController@invoiceUpdate')->name('admin.invoiceUpdate');
        });

    });
});
