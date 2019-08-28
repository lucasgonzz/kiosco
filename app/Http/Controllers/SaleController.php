<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Article;
use Carbon\Carbon;
use App\Helpers\DateFormat;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $sales = Sale::with('article')->get();
        $fecha = Carbon::today();
        $sales = Sale::whereDate('created_at', $fecha)->orderBy('id', 'DESC')->with('article')->get();
        return $sales;
    }

    public function salesToday(){
        $fecha = date('Y-m-d');
        $sales = Sale::whereDate('created_at', $fecha)->orderBy('id', 'DESC')->with('articles')->get();
        foreach ($sales as $sale) {
            $sale->articles = DateFormat::format($sale->articles, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            foreach ($sale->articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            }
        }
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'G:i']);
        return $sales;
    }

    public function salesTodayMorning(){
        $fecha = date('Y-m-d');
        $sales = Sale::whereBetween('created_at', [$fecha . ' 08:00:00', $fecha . ' 15:00:00'])->orderBy('id', 'DESC')->with('articles')->get();
        foreach ($sales as $sale) {
            $sale->articles = DateFormat::format($sale->articles, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            foreach ($sale->articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            }
        }
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'G:i']);
        return $sales;
    }

    public function salesTodayAfternoon(){
        $fecha = date('Y-m-d');
        $sales = Sale::whereBetween('created_at', [$fecha . ' 15:00:00', $fecha . ' 23:59:00'])->orderBy('id', 'DESC')->with('articles')->get();
        foreach ($sales as $sale) {
            $sale->articles = DateFormat::format($sale->articles, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            foreach ($sale->articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            }
        }
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'G:i']);
        return $sales;
    }

    public function salesFromDate(Request $request){
        $sales = Sale::whereBetween('created_at', [$request->desde, $request->hasta])->orderBy('id', 'DESC')->with('articles')->get();
        foreach ($sales as $sale) {
            $sale->articles = DateFormat::format($sale->articles, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            foreach ($sale->articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'G'], ['dia', 'created_at', 'l']]);
            }
        }
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'G:i']);
        return $sales;   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addItem($barCode) {
        $article = Article::where('codigo_barras', $barCode)->firstOrFail();
        $article->stock --;
        $article->timestamps = false;
        $article->save();
        return $article;
    }

    public function store(Request $request)
    {
        $ventas = $request->ventas;

        $sale = new Sale();
        $sale->save();
        $sale->articles()->attach($ventas);
        $sale->save();
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->timestamps = false;
        $article->stock = $article->stock + 1;
        $article->save();
    }
}
