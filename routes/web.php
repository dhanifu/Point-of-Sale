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


Auth::routes();

Route::get('', 'HomeController@home')->name('home');

// MANAGER
Route::prefix('manager')->name('manager.')->middleware('role:manager')->group(function(){
    Route::get('', 'HomeController@home')->name('home');
    Route::get('home', 'HomeController@home')->name('home');

    Route::prefix('product-report')->name('product-report.')->group(function(){
        Route::get('/', 'ReportController@indexProduct')->name('index');
    });

    Route::prefix('transaction-report')->name('transaction-report.')->group(function(){
        Route::get('/', 'ReportController@indexTransaction')->name('index');
        Route::get('/pdf', 'ReportController@pdfTransaction')->name('pdf');
    });
});


// ADMIN
Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function(){
    Route::get('', 'HomeController@home')->name('home');
    Route::get('home', 'HomeController@home')->name('home');

    Route::prefix('brands')->name('brand.')->group(function(){
        Route::get('/', 'BrandController@index')->name('index');
        Route::post('/', 'BrandController@store')->name('store');
        Route::put('/update', 'BrandController@update')->name('update');
        Route::delete('/delete/{brand}', 'BrandController@destroy')->name('destroy');
    });
    Route::prefix('distributor')->name('distributor.')->group(function(){
        Route::get('/', 'DistributorController@index')->name('index');
        Route::post('/', 'DistributorController@store')->name('store');
        Route::put('/update', 'DistributorController@update')->name('update');
        Route::delete('/delete/{distributor}', 'DistributorController@destroy')->name('destroy');
    });
    Route::prefix('products')->name('product.')->group(function(){
        Route::get('/', 'ProductController@index')->name('index');
        Route::get('/create', 'ProductController@create')->name('create');
        Route::post('/create', 'ProductController@store')->name('store');
        Route::get('/edit/{product}', 'ProductController@edit')->name('edit');
        Route::put('/edit/{product}/update', 'ProductController@update')->name('update');
        Route::delete('/delete/{product}', 'ProductController@destroy')->name('destroy');
        // MUNGKIN NANTI NAMBAH BUAT UPLOAD EXCEL
    });
});


// KASIR
Route::prefix('kasir')->name('kasir.')->middleware('role:kasir')->group(function(){
    Route::get('', 'HomeController@home')->name('home');
    Route::get('home', 'HomeController@home')->name('home');

    Route::prefix('transactions')->name('transaction.')->group(function(){
        Route::get('/', 'TransactionController@index')->name('index');
        Route::get('/{kode_transaksi}/add', 'TransactionController@addTransaction')->name('add');
        Route::post('/', 'TransactionController@store')->name('store');
        Route::post('/store/{kode_transaksi}', 'TransactionController@storeMore')->name('store-more');
        Route::delete('/delete/{transaction}', 'TransactionController@destroy')->name('destroy');
    });
});
