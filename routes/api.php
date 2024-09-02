<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceProductController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductCartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ProductOfferController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductSliderController;
use App\Http\Controllers\ProductWishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SslCommerzeController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\UserAuthentication;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/users')->group(function(){
    Route::post('signup', [UserController::class, 'store']);
    Route::post('signup-otp-verify', [UserController::class, 'verifySignupOTP']);
    Route::post('signin', [UserController::class, 'login']);


    // route with user authentication 
    Route::middleware([UserAuthentication::class])->group(function(){
        
        // profile start 
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile/add', [ProfileController::class, 'store']);
        Route::post('/profile/edit', [ProfileController::class, 'store']);
        Route::delete('/profile/delete', [ProfileController::class, 'destroy']);
        // profile end

        // product cart start 
        Route::get('/product-cart', [ProductCartController::class, 'show']);
        Route::post('/product-cart/add', [ProductCartController::class, 'store']);
        Route::post('/product-cart/edit', [ProductCartController::class, 'store']);
        Route::delete('/product-cart/delete/{id?}', [ProductCartController::class, 'destroy']);
        // product cart end

        // product wishlist start 
        Route::get('/product-wishlist', [ProductWishController::class, 'show']);
        Route::post('/product-wishlist/add', [ProductWishController::class, 'store']);
        Route::post('/product-wishlist/edit', [ProductWishController::class, 'store']);
        Route::delete('/product-wishlist/delete/{id?}', [ProductWishController::class, 'destroy']);
        // product wishlist end


        // billing start 
        Route::get('/billing', [BillingController::class, 'show']);
        Route::post('/billing/add', [BillingController::class, 'store']);
        Route::post('/billing/edit', [BillingController::class, 'store']);
        Route::delete('/billing/delete/{id?}', [BillingController::class, 'destroy']);
        // billing end        


        // shipping start 
        Route::get('/shipping', [ShippingController::class, 'show']);
        Route::post('/shipping/add', [ShippingController::class, 'store']);
        Route::post('/shipping/edit', [ShippingController::class, 'store']);
        Route::delete('/shipping/delete/{id?}', [ShippingController::class, 'destroy']);
        // shipping end        

        // product invoice start 
        Route::get('/invoice/{id?}', [InvoiceController::class, 'show']);
        Route::post('/invoice/add', [InvoiceController::class, 'store']);
        Route::delete('/invoice/delete/{id?}', [InvoiceController::class, 'destroy']);
        // product invoice end

        // invoice product start 
        Route::get('/invoice-product/{id?}', [InvoiceProductController::class, 'show']);
        Route::delete('/invoice-product/delete/{id?}', [InvoiceProductController::class, 'destroy']);
        // invoice product end

        // product review start 
        Route::get('/product-review/{id?}', [ProductReviewController::class, 'show']);
        Route::post('/product-review/add', [ProductReviewController::class, 'store']);
        Route::post('/product-review/edit', [ProductReviewController::class, 'store']);
        Route::delete('/product-review/delete/{id?}', [ProductReviewController::class, 'destroy']);
        // product review end

    });

    Route::prefix('password')->group(function(){
        Route::post('send-otp', [PasswordController::class, 'sendOTP']);
        Route::post('verify-otp', [PasswordController::class, 'verifyOTP']);
        Route::post('new', [PasswordController::class, 'passwordReset']);
    });
});

Route::prefix('v1/admin')->group(function(){
    Route::post('signup', [AdminController::class, 'store']);
    Route::post('signup-otp-verify', [AdminController::class, 'verifySignupOTP']);
    Route::post('signin', [AdminController::class, 'login']);
    Route::post('signout', [AdminController::class, 'logout']);


    Route::middleware([AdminAuth::class])->group(function(){

        // slider controls start
        Route::get('/slider/{id?}', [ProductSliderController::class, 'show']);
        Route::post('/slider/add', [ProductSliderController::class, 'store']);
        Route::post('/slider/edit', [ProductSliderController::class, 'store']);
        Route::delete('/slider/delete/{id?}', [ProductSliderController::class, 'destroy']);
        // slider controls end

        // brand controls start
        Route::get('/brand/{id?}', [BrandController::class, 'show']);
        Route::post('/brand/add', [BrandController::class, 'create']);
        Route::post('/brand/edit', [BrandController::class, 'edit']);
        Route::delete('/brand/delete/{id?}', [BrandController::class, 'delete']);
        // brand controls end

        // category controls start
        Route::get('/category/{id?}/{order?}', [CategoryController::class, 'show']);
        Route::post('/category/add', [CategoryController::class, 'create']);
        Route::post('/category/edit', [CategoryController::class, 'edit']);
        Route::delete('/category/delete/{id?}', [CategoryController::class, 'delete']);
        // category controls end
    
        // sub-category controls start
        Route::get('/sub-category/{id?}', [SubCategoryController::class, 'show']);
        Route::post('/sub-category/add', [SubCategoryController::class, 'create']);
        Route::post('/sub-category/edit', [SubCategoryController::class, 'edit']);
        Route::get('/sub-category/delete/{id?}', [SubCategoryController::class, 'delete']);
        // sub category controls end
    
        // product controls start
        Route::get('/product/{id?}', [ProductController::class, 'show']);
        Route::post('/product/add', [ProductController::class, 'create']);
        Route::post('/product/edit', [ProductController::class, 'edit']);
        Route::delete('/product/delete/{id?}', [ProductController::class, 'delete']);
        // product controls end
    
        // product details controls start
        Route::get('/product-details/{id?}', [ProductDetailController::class, 'show']);
        Route::post('/product-details/add', [ProductDetailController::class, 'create']);
        Route::post('/product-details/edit', [ProductDetailController::class, 'edit']);
        Route::delete('/product-details/delete/{id?}', [ProductDetailController::class, 'delete']);
        // product details controls end
    
        // product offer controls start
        Route::get('/product-offer/{id?}', [ProductOfferController::class, 'show']);
        Route::post('/product-offer/add', [ProductOfferController::class, 'create']);
        Route::post('/product-offer/edit', [ProductOfferController::class, 'edit']);
        Route::delete('/product-offer/delete/{id?}', [ProductOfferController::class, 'destroy']);
        // product offer controls end

        // coupon controls start
        Route::get('/coupon/{id?}', [CouponController::class, 'show']);
        Route::post('/coupon/add', [CouponController::class, 'store']);
        Route::post('/coupon/edit', [CouponController::class, 'store']);
        Route::delete('/coupon/delete/{id?}', [CouponController::class, 'destroy']);
        // coupon controls end
    
        // payment controls start
        Route::get('/payment/ssl-commerze', [SslCommerzeController::class, 'show']);
        Route::post('/payment/ssl-commerze/add', [SslCommerzeController::class, 'store']);
        Route::post('/payment/ssl-commerze/edit', [SslCommerzeController::class, 'edit']);
        Route::delete('/payment/ssl-commerze/delete/{id?}', [SslCommerzeController::class, 'destroy']);
        // payment controls end
    
    });

});
