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
    public function resumen_ventas(){
    	return view('main.resumen_ventas');
    }
    public function estado(){
    	return view('main.estado');
    }
    public function codigosDeBarras(){
        return view('main.codigos-de-barras');
    }
}
