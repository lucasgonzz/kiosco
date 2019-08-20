<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mayorista;

class MayoristaController extends Controller
{
	
	public function index(){
		$mayoristas = Mayorista::all();
		return $mayoristas;
	}
    public function store(Request $request){
    	$mayorista = new Mayorista();
    	$mayorista->name=$request->name;
    	$mayorista->save();
    }
}
