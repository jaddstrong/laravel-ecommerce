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

Route::get('/mail', function () {
    return new Sendmail();
});

Route::get('/asd', 'ProductsController@logs');

Route::get('/test', 'UsersController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){
    //ADMIN ROUTES
    Route::get('/products', ['uses'=>'ProductsController@index', 'as'=>'products.index']);
    Route::post('product', 'ProductsController@store');
    Route::post('/product/update', 'ProductsController@update');
    Route::post('/product/drop', 'ProductsController@drop');
});

Route::group(['middleware' => 'App\Http\Middleware\UserMiddleware'], function(){
    //USER ROUTES
    Route::get('/user', 'ProductsController@display');
    Route::get('/cart', 'ProductsController@cart');
    Route::post('/addToCart', 'ProductsController@addToCart');
    Route::get('/purchase', 'ProductsController@purchase');
    Route::get('/logs', 'ProductsController@logs');
    //BOTH
    Route::get('/product/show', 'ProductsController@show');
});