<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Search;
use Carbon\Carbon;
use App\Helpers\DateFormat;
use App\Helpers\ResultFormat;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listar(Request $request)
    {
        $fechaMinima = Carbon::now()->subMonths(6);
        $articles = new Article();

        if(!is_null($request->min) || !is_null($request->max)){
            if(!is_null($request->min) && !is_null($request->max)){
                $articles = $articles->whereBetween('price', [$request->min, $request->max]);
            }else if(!is_null($request->min)){
                $articles = $articles->where('price', '>', $request->min);
            }else if(!is_null($request->max)){
                $articles = $articles->where('price', '<', $request->max);
            }
        }

        if($request->filtrar == 'si'){
            if($request->filtro==0){
                $articles = $articles->where('stock', 1);
            }else if($request->filtro==1){
                $articles = $articles->where('stock', 0);
            }else if($request->filtro==2){
                $articles = $articles->whereDate('updated_at', '<', $fechaMinima);
            }
        }

        if(count($request->mayoristas)>0){
            if(!in_array("0", $request->mayoristas)){
                $articles = $articles->whereIn('mayorista', $request->mayoristas);
            }
        }

        if($request->orden == 0){
            $articles = $articles->orderBy('created_at', 'DESC');
        }else if($request->orden == 1){
            $articles = $articles->orderBy('created_at', 'ASC');
        }else if($request->orden == 2){
            $articles = $articles->orderBy('price', 'ASC');
        }else if($request->orden == 3){
            $articles = $articles->orderBy('price', 'DESC');
        }else if($request->orden == 4){
            $articles = $articles->orderBy('mayorista', 'ASC');
        }  
        $articles = $articles->with('sales');

        if($request->perPage != 0){
            $articles = $articles->paginate($request->perPage);
            $articles = ResultFormat::format($articles);
            $articles = DateFormat::format($articles, 'd/m/Y');
            foreach ($articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at' , 'l']]);
            }
            return [
                'pagination' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastPage(),
                ],
                'articles' => $articles 
            ];
        }else{
            $articles = $articles->get();
            $articles = ResultFormat::format($articles);
            $articles = DateFormat::format($articles, 'd/m/Y');
            foreach ($articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at' , 'l']]);
            }
            return $articles;
        }

    }

    public function ultimosActualizados(){
        $articles = Article::orderBy('updated_at', 'DESC')->take(7)->get();
        return $articles;
    }

    public function buscar($filtro){

        $articles = new Article();

        $articles = Article::where('codigo_barras', $filtro)->with('sales')->get();
        if(count($articles)==0){

            $articles = Article::where('name', 'LIKE', "%$filtro%")->with('sales')->get(); 
            foreach ($articles as $article) {
            $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at' , 'l']]);
            }
        }

        $articles = ResultFormat::format($articles);

        return $articles;




        if(isset($request->code)){
            $articles = $articles->where('codigo_barras', $request->code);
            $article = $articles->where('codigo_barras', $request->code)->first();
            $search = Search::where('article_id', $article->id)->first();
            if(empty($search)){
                $search = new Search();
                $search->article_id = $article->id;
                $search->save();

                $searches_eliminar = Search::whereDate('created_at', '<', Carbon::today())->get()->toArray();
                Search::destroy($searches_eliminar);
            }
        }else if(isset($request->name)){
            $articles = $articles->where('name', 'LIKE', "%$request->name%");
        }
        if(isset($request->mayoristas)){
            if(isset($request->mayoristas[0]) &&  $request->mayoristas[0] != '0'){
                $articles = $articles->whereIn('mayorista', $request->mayoristas);
            }
        }
        $articles = $articles->with('sales');
        $articles = $articles->get();
        $articles = DateFormat::format($articles, 'd/m/Y');
        foreach ($articles as $article) {
            $article->sales = DateFormat::format($article->sales, 'd/m/Y', [['hora', 'created_at', 'H:m'], ['dia', 'created_at' , 'l']]);
        }

        // Se le da color a los articulos
        $articles = ResultFormat::format($articles);
        return $articles;

    }

    public function cargarCodigosBarras(){
        $codigos = Article::all()->pluck('codigo_barras');
        return $codigos;
    }
    
    public function estado(Request $request){

        $fechaMinima = Carbon::now()->subMonths(6);

        $articles = new Article();

        if(!is_null($request->min) || !is_null($request->max)){
            if(!is_null($request->min) && !is_null($request->max)){
                $articles = $articles->whereBetween('price', [$request->min, $request->max]);
            }else if(!is_null($request->min)){
                $articles = $articles->where('price', '>', $request->min);
            }else if(!is_null($request->max)){
                $articles = $articles->where('price', '<', $request->max);
            }
        }

        if($request->filtro==0){
            $articles = $articles->where('stock', 1);
        }else if($request->filtro==1){
            $articles = $articles->where('stock', 0);
        }else if($request->filtro==2){
            $articles = $articles->whereDate('updated_at', '<', $fechaMinima);
        }

        if($request->orden == 0){
            $articles = $articles->orderBy('created_at', 'ASC');
        }else if($request->orden == 1){
            $articles = $articles->orderBy('created_at', 'DESC');
        }else if($request->orden == 2){
            $articles = $articles->orderBy('price', 'ASC');
        }else if($request->orden == 3){
            $articles = $articles->orderBy('price', 'DESC');
        }else if($request->orden == 4){
            $articles = $articles->orderBy('mayorista', 'ASC');
        }  
        $articles = $articles->with('sales');

        if($request->perPage != 0){
            $articles = $articles->paginate($request->perPage);
            DateFormat::format($articles, 'd/m/Y');
            foreach ($articles as $article) {
                $article->sales = DateFormat::format($article->sales, 'd/m/Y', ['hora', 'created_at', 'H:m']);
            }
            return [
                'pagination' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastPage(),
                ],
                'articles' => $articles 
            ];
        }

        DateFormat::format($articles, 'd/m/Y');
        foreach ($articles as $article) {
            $article->sales = DateFormat::format($article->sales, 'd/m/Y', ['hora', 'created_at', 'H:m']);
        }
        $articles = $articles->get();
        return $articles;
    }

    public function listarEstado(){
        $articulos = Article::orderBy('id', 'DESC')->get();
        $result = [];
        $fechaMinima = Carbon::now()->subMonths(6);
        foreach ($articulos as $articulo) {
            if($articulo->updated_at < $fechaMinima){
                $articulo->cost = number_format($articulo->cost, 0, "", ".");
                $articulo->price = number_format($articulo->price, 0, "", ".");
                $articulo->previus_price = number_format($articulo->previus_price, 0, "", ".");
                $articulo->style = "naranja";
                $result[] = $articulo;
            }
            if($articulo->stock == 1 ){
                $articulo->cost = number_format($articulo->cost, 0, "", ".");
                $articulo->price = number_format($articulo->price, 0, "", ".");
                $articulo->previus_price = number_format($articulo->previus_price, 0, "", ".");
                $articulo->style = "amarillo";
                $result[] = $articulo;
            }
            if($articulo->stock == 0 ){
                $articulo->cost = number_format($articulo->cost, 0, "", ".");
                $articulo->price = number_format($articulo->price, 0, "", ".");
                $articulo->previus_price = number_format($articulo->previus_price, 0, "", ".");
                $articulo->style = "rojo";
                $result[] = $articulo;
            }
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cost' => 'integer',
            'price' => 'required|integer',
            'name' => 'string',
            'stock' => 'integer',
        ]);

        $article = new Article();
        $article->codigo_barras=$request->codigo_barras;
        $article->name= ucwords($request->name);
        $article->mayorista=$request->mayorista;
        // $article->cost = number_format($request->cost, 2, ".", ".");
        // $article->price= number_format($request->price, 2, ".", ".");
        if(!is_null($request->cost)) {
            $article->cost = $request->cost;  
        }else{
            $article->cost = 0;
        }
        
        $article->price= $request->price;
        $article->stock=$request->stock;
        if($request->created_at!=date('Y-m-d')){
            $article->created_at = $request->created_at . " " . date("H:i:s");
            $article->updated_at = $request->created_at . " " . date("H:i:s");
        }
        if($article->save()){
            // return redirect()->route('ingresar')->with('status', 'Articulo ingresado con exito')
            //                                     ->with('mayorista', $request->mayorista);
            return "exito";
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($codigo_barras)
    {   
        $article = Article::where('codigo_barras', $codigo_barras)->first();
        $article = DateFormat::formatObject($article, 'd/m/Y');
        return $article;
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
        // Article::find($id)->update($request->all());
        $article = Article::find($id);

        // Se guarda el precio anterior antes de actualizarlo si es que es diferente
        if($request->price!=$article->price){
            $article->previus_price = $article->price;
        }

        $article->name = $request->name;
        $article->cost = str_replace('.', '', $request->cost);
        $article->price = str_replace('.', '', $request->price);
        $article->mayorista = $request->mayorista;
        $article->stock = $request->stock;
        if(!$request->act_fecha){
            $article->timestamps = false;
        }
        $article->save();
        return ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::findOrFail($id)->delete();
        return;
    }
}
