
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Fridients API Routes
Route::group(['prefix' => 'v1'], function () {
    Route::resource('suppliers', 'API\SuppliersController');
    Route::resource('ingredients', 'API\IngredientsController');
    Route::get('ingredients-orders', 'API\IngredientsController@weeklyOrder');
    Route::resource('recipes', 'API\RecipesController');
    Route::resource('boxes', 'API\BoxesController');
});
