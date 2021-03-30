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

use Illuminate\Support\Facades\View;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    if(Auth::check()) { 
        return (new HomeController)->index();
    } else {
        return View::make('Auth.login');
    }
});

Auth::routes();


/**  
 *  ==========   Museu  ==========   
*/

Route::get('/museu', 'HomeController@index')->name('museu');

Route::post('/museu', 'HomeController@store');


/**  
 * 
 *  ==========   Acervos  ========== 
 * 
*/
Route::get('/acervos', 'CollectionController@index')->name('collection');

Route::get('/acervos/novo', 'CollectionController@create');

Route::get('/acervos/editar/{id}', 'CollectionController@edit');

Route::post('/acervos', 'CollectionController@store');

Route::post('/acervos/{id}', 'CollectionController@update');


/**  
 * 
 *  ==========   Item  ========== 
 * 
*/
Route::get('/itens', 'ItemController@index')->name('item');

Route::post('/itens/pesquisa', 'ItemController@indexSearchFast');

Route::get('/itens/novo', 'ItemController@create');

Route::get('/itens/editar/{id}', 'ItemController@edit');

Route::post('/itens', 'ItemController@store');

Route::post('/itens/{id}', 'ItemController@update');


/**
 * 
 * ==========   Publicação  ==========
 * 
 */

Route::get('/publicacoes', 'PublicationController@index')->name('publicacoes');

Route::get('/publicacoes/publicar', 'PublicationController@create');

Route::get('/publicacoes/visualizar/{id}', 'PublicationController@show');

Route::post('/publicacoes', 'PublicationController@store');

// Route::post('/itens/{id}', 'ItemController@update');

/**
 * 
 * ==========   Configuração das redes sociais  ==========
 * 
 */
Route::get('/redesocial/configuracoes', 'SocialnetworkController@create');
Route::post('/redesocial/configuracoes', 'SocialnetworkController@store');

/**
 * 
 * ==========   Usuários  ==========
 * 
 */

Route::get('/usuarios', 'UserController@index')->name('user');

Route::get('/usuarios/novo', 'UserController@create');

Route::get('/usuarios/editar/{id}', 'UserController@edit');
