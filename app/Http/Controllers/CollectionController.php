<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Collection;
use App\Item;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{

    public function isAuth()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->isAuth();
        //$collections = Collection::all();
        $collections = DB::table('collections') 
        ->select('*',
        (DB::raw("(SELECT COUNT(1) FROM items WHERE items.cdcollection = collections.cdcollection) AS qtd")))
        ->get();
        

        return view('collection', ['records' => $collections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->isAuth();
        return view('collectionform');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->isAuth();

        $collection = new Collection();
        $collection->nmcollection = $request->input('name');
        $collection->dscollection = $request->input('description');
        $collection->save();
        $request->session()->flash('status', 'Salvo com sucesso!');
        
        return redirect()->action('CollectionController@index');
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
    public function edit($cdcollection)
    {
        $this->isAuth();
        $collection = Collection::find($cdcollection);
        if (!empty($collection)) {
                       
            return view('collectionform', ['data' => $collection]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cdcollection)
    {
        $this->isAuth();

        $collection = Collection::find($cdcollection);
        if(!empty($collection)) {
            $collection->nmcollection = $request->input('name');
            $collection->dscollection = $request->input('description');
            $collection->save();
            $request->session()->flash('status', 'Salvo com sucesso!');
        }
        
        return redirect()->action('CollectionController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cdcollection)
    {
        
        $collection = Collection::find($cdcollection);
        if (!empty($collection)) {
            if (!empty(Item::find($cdcollection))) {
            
                $collection->delete();
                
                return response('OK', 200);
            } else {
                return response('Existem dados relacionados', 404); 
            }
        }
        return response('Acervo não encontrado', 404);
    }
}
