<?php

use Illuminate\Http\Request;

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

Route::delete('/acervos/{cdcollection}', 'CollectionController@destroy');

Route::delete('/itens/{cditem}', 'ItemController@destroy');

Route::delete('/usuarios/{id}', 'UserController@destroy');

Route::delete('/publicacoes/{cdpublication}', 'PublicationController@destroy');

Route::get('/itens/{collection}/{nmitem}', 'ItemController@searchitems');

Route::get('/imagens/item/{cditem}', 'ItemController@images');