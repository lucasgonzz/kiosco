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

use Zend\Barcode\Barcode;

Route::get('/', function () {
    return redirect()->route('venta');
});

Route::get('venta', 'MainController@venta')->name('venta');
Route::get('ingresar', 'MainController@ingresar')->name('ingresar');
Route::get('lista-precios', 'MainController@lista_precios')->name('lista-precios');
Route::get('resumen-ventas', 'MainController@resumen_ventas')->name('resumen-ventas');
Route::get('estado', 'MainController@estado')->name('estado');
Route::get('codigos-de-barras', 'MainController@codigosDeBarras')->name('codigos-de-barras');
Route::get('cargar-codigos-barras', 'ArticleController@cargarCodigosBarras');

Route::get('articles/ultimosActualizados', 'ArticleController@ultimosActualizados');

Route::post('articles/index', 'ArticleController@listar');
// Route::get('articles/estado', 'ArticleController@estado');
Route::get('articles/buscar/{filtro}', 'ArticleController@buscar');
Route::post('articles/listar-estados', 'ArticleController@listarEstado');
Route::resource('articles', 'ArticleController');

Route::get('mayoristas', 'MayoristaController@index');
Route::post('mayoristas', 'MayoristaController@store');

Route::get('prueba', 'ArticleController@listar');

Route::get('searches', 'SearchController@index');
Route::post('sales', 'SaleController@store');
Route::get('sales/today', 'SaleController@salesToday');
Route::delete('sales/{id}', 'SaleController@destroy');
Route::post('sales/salesFromDate', 'SaleController@salesFromDate');

Route::get('generar-codigo/{codigo}', function($codigo){

	// Only the text to draw is required
	$barcodeOptions = ['text' => $codigo];

	// No required options
	$rendererOptions = [];

	// Draw the barcode, // send the headers, and emit the image:
	return Barcode::factory(
	    'code128',
	    'image',
	    $barcodeOptions,
	    $rendererOptions
	)->render();

});
Route::get('/a', function(){
	$article = App\Article::where('codigo_barras', 3107237)->first();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
