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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /compilations                     index    compilations.index
 * GET         /compilations/create              create   compilations.create
 * POST        /compilations                     store    compilations.store
 * GET         /compilations/{compilation}       show     compilations.show
 * GET         /compilations/{compilation}/edit  edit     compilations.edit
 * PUT/PATCH   /compilations/{compilation}       update   compilations.update
 * DELETE      /compilations/{compilation}       destroy  compilations.destroy
 *
 * @see https://laravel.com/docs/5.5/controllers#resource-controllers
 */
Route::resource('compilations', 'CompilationsController')->middleware('auth');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /locations                  index    locations.index
 * GET         /locations/create           create   locations.create
 * POST        /locations                  store    locations.store
 * GET         /locations/{location}       show     locations.show
 * GET         /locations/{location}/edit  edit     locations.edit
 * PUT/PATCH   /locations/{location}       update   locations.update
 * DELETE      /locations/{location}       destroy  locations.destroy
 *
 * @see https://laravel.com/docs/5.5/controllers#resource-controllers
 */
Route::resource('locations', 'LocationsController')->middleware('auth');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /wards              index    wards.index
 * GET         /wards/create       create   wards.create
 * POST        /wards              store    wards.store
 * GET         /wards/{ward}       show     wards.show
 * GET         /wards/{ward}/edit  edit     wards.edit
 * PUT/PATCH   /wards/{ward}       update   wards.update
 * DELETE      /wards/{ward}       destroy  wards.destroy
 *
 * @see https://laravel.com/docs/5.5/controllers#resource-controllers
 */
Route::resource('wards', 'WardsController')->middleware('auth');

if (App::environment() !== 'production') {
    Route::get('/test', function () {
        return 'test';
    });
}
