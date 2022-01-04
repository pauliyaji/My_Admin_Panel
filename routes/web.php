<?php

use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

    /**
     * Home Routes
     */
    //Route::get('/', 'HomeController@index')->name('home.index');
    Route::get('/', function () {
        return view('auth/login');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    require __DIR__.'/auth.php';

//   Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
       // Route::get('/register', 'RegisterController@show')->name('register.show');
      //  Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
      //  Route::get('/login', 'LoginController@show')->name('login.show');
       // Route::post('/login', 'LoginController@login')->name('login.perform');

   // });

    Route::group(['middleware' => ['auth', 'permission']], function() {
        /**
         * Logout Routes
         */
        //Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        /**
         * User Routes
         */
        Route::group(['prefix' => 'users'], function() {

            Route::get('/', 'App\Http\Controllers\UsersController@index')->name('users.index');
            Route::get('/create', 'App\Http\Controllers\UsersController@create')->name('users.create');
            Route::post('/create', 'App\Http\Controllers\UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'App\Http\Controllers\UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'App\Http\Controllers\UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'App\Http\Controllers\UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'App\Http\Controllers\UsersController@destroy')->name('users.destroy');
        });
        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);
    });


