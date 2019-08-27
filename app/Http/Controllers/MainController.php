<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mayorista;
use App\Article;
use Jenssegers\Agent\Agent;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function venta(){
    	return view('main.venta');
    }
    public function ingresar(){
        $mayoristas = Mayorista::all();
    	return view('main.ingresar', ["mayoristas" => $mayoristas]);
    }
    public function lista_precios(){
        $agent = new Agent();
    	return view('main.lista_precios', compact('agent'));
    }
    public function resumenVentasHoy(){
        return view('main.resumen-ventas-hoy');
    }
    public function resumenVentasDesdeUnaFecha(){
        return view('main.resumen-ventas-desde-una-fecha');
    }
    public function resumenVentasDiasMasVendidos(){
        return view('main.resumen-ventas-dias-mas-vendidos');
    }
    public function estado(){
    	return view('main.estado');
    }
    public function codigosDeBarras(){
        return view('main.codigos-de-barras');
    }
}
