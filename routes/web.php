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

/* FULL ROUTE LIST OF A RESOURCE CONTROLLER
 *
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

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /compilations                     index    compilations.index
 * GET         /compilations/create              create   compilations.create
 * POST        /compilations                     store    compilations.store
 * GET         /compilations/{compilation}       show     compilations.show
 */
Route::resource(
    'compilations',
    'CompilationsController',
    ['except' => ['edit', 'update', 'destroy']]
)->middleware('auth');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /locations                        index    locations.index
 * POST        /locations                        store    locations.store
 * GET         /locations/{location}/edit        edit     locations.edit
 * PUT/PATCH   /locations/{location}             update   locations.update
 * DELETE      /locations/{location}             destroy  locations.destroy
 */
Route::resource(
    'locations',
    'LocationsController',
    ['except' => ['create', 'show']]
)->middleware('auth');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /wards                            index    wards.index
 * POST        /wards                            store    wards.store
 * GET         /wards/{ward}/edit                edit     wards.edit
 * PUT/PATCH   /wards/{ward}                     update   wards.update
 * DELETE      /wards/{ward}                     destroy  wards.destroy
 */
Route::resource(
    'wards',
    'WardsController',
    ['except' => ['create', 'show']]
)->middleware('auth');

/*
 * Verb        URI                               Action   Route Name
 * ---------------------------------------------------------------------------
 * GET         /users                            index    users.index
 * GET         /users/{user}                     show     users.show
 * GET         /users/{user}/edit                edit     users.edit
 * PUT/PATCH   /users/{user}                     update   users.update
 * DELETE      /users/{user}                     destroy  users.destroy
 */
Route::resource(
    'users',
    'UsersController',
    ['except' => ['create', 'store']]
)->middleware('auth');

if (App::environment() !== 'production') {
    Route::get('/test', function () {
        return 'test';
    });
}
