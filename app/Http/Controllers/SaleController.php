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
        $sales = Sale::all();
        foreach ($sales as $sale) {
            $sale->article->sales = DateFormat::format($sale->article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at', 'l']]);
        }
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'H:m']);
        return $sales;
    }

    public function salesFromDate(Request $request){
        $sales = Sale::whereBetween('created_at', [$request->desde, $request->hasta])->orderBy('id', 'DESC')->with('article')->get();
        $sales = DateFormat::format($sales, 'd/m/Y', ['hora', 'created_at', 'H:m']);
        foreach ($sales as $sale) {
            $sale->article->sales = DateFormat::format($sale->article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at', 'l']]);
        }
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
    public function store(Request $request)
    {
        $article = Article::where('codigo_barras', $request->codigo_barras)->firstOrFail();
        $article->timestamps = false;
        $article->stock = $article->stock - 1;
        $article->save();
        $result=[];
        $result['name'] = $article->name;
        $result['price'] = $article->price;
        $result['stock'] = $article->stock;
        $result['update_at'] = $article->update_at;

        $fechaMinima = Carbon::now()->subMonths(6);
        if($article->updated_at < $fechaMinima){
            $result['old'] = true;
        }

        $sale = new Sale();
        $sale->article_id = $article->id;
        if($sale->save()){
            $result['sale_id'] = $sale->id;
            return $result;
        }else{
            return "Error";
        }
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
        $sale = Sale::findOrFail($id);
        $article = $sale->article;
        $article->timestamps = false;
        $article->stock = $article->stock + 1;
        $article->save();
        $sale->delete();
    }
}
