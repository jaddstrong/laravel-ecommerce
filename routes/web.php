<?php

use Illuminate\Support\Facades\Route;
use App\Mail\SendMail;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){

    //PRODUCT ROUTES
    Route::get('/products', ['uses'=>'ProductsController@index', 'as'=>'products.index']);
    Route::post('product', 'ProductsController@store')->middleware('permission:create-products');
    Route::post('/product/update', 'ProductsController@update')->middleware('permission:edit-products');
    Route::post('/product/drop', 'ProductsController@drop')->middleware('permission:delete-products');

    //USER ROUTES
    Route::get('/user', 'UsersController@index');
    Route::get('/user/role', 'UsersController@role');
    Route::post('/user/store', 'UsersController@store');
    Route::get('/user/show', 'UsersController@show');
    Route::post('/user/update', 'UsersController@update');
    Route::post('/user/delete','UsersController@delete');

    //ROLE ROUTES
    Route::get('/roles', 'RolesController@index');
    Route::post('/roles/store', 'RolesController@store');
    Route::get('/roles/permission', 'RolesController@permission');
    Route::get('/roles/show', 'RolesController@show');
    Route::post('/roles/update', 'RolesController@update');
    Route::post('roles/delete', 'RolesController@delete');
});

Route::group(['middleware' => 'App\Http\Middleware\UserMiddleware'], function(){

    //CLIENT ROUTES
    Route::get('/display', 'ProductsController@display');
    Route::get('/logs', 'LogsController@logs');
    Route::post('/addToCart', 'CartController@addToCart');
    Route::get('/cart', 'CartController@cart');
    Route::get('/purchase', 'CartController@purchase');
    Route::get('/remove', 'CartController@remove');
    Route::get('/purchase/list', 'LogsController@purchaseList');

    //BOTH
    Route::get('/product/show', 'ProductsController@show');
});