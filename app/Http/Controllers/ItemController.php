<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Collection;
use App\Photographie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
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

        //$items = Item::all();
        $items = DB::table('items') 
        ->select('*',
        (DB::raw("(SELECT coll.nmcollection FROM collections coll WHERE coll.cdcollection = items.cdcollection) AS nmcollection")))
        ->get();
        
        return view('item', ['records' => $items]);
    }


    public function indexSearchFast(Request $request)
    {
        $this->isAuth();

        $searchField = strtoupper($request->input('searchField'));
        $items = DB::table('items') 
        ->select('*',
        (DB::raw("(SELECT coll.nmcollection FROM collections coll WHERE coll.cdcollection = items.cdcollection) AS nmcollection")))
        ->whereRaw(
            "UPPER(nmitem) LIKE '%". strtoupper($request->input('searchField'))."%' ")
        ->get();
        
        return view('item', ['records' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->isAuth();
        return view('itemform', ['collections' => $this->getCollections()]);
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

        $validateForm = $this->validateForm($request);
        
        if ($validateForm !== false) {
            return $validateForm;
        }

        $item = new Item();
        $item->nmitem = $request->input('nmitem');
        $item->cdcollection = $request->input('cdcollection');
        $item->dsitem = $request->input('dsitem');
        $item->save();

        $files = $request->allFiles();
        $this->saveImages($files, $item->cditem);
        
        $request->session()->flash('status', 'Salvo com sucesso!');
        
       return redirect()->action('ItemController@index');
    }

    public function saveImages($allFiles, $cditem)
    {
        foreach ($allFiles as $key => $file) {
            $path = $file->store("images", 'public');
            $photographie = new Photographie();
            $photographie->cdmuseum = null;
            $photographie->cdsocialnetwork = null;
            $photographie->cditem = $cditem;
            $photographie->nmurl = $path;
            $photographie->save();
        }  
    }

    public function images($cditem) 
    {
        $photographiesResult = DB::table('photographies')->where('cditem', $cditem)->get();
        $images = [];
        foreach ($photographiesResult as $key => $image) {
            $images[$key]['src'] = "/storage/".$image->nmurl;
            $images[$key]['cdphotography'] = $image->cdphotography;
        }

        return json_encode($images);
    }

    /**
     *  Type : 1 - caminho publico
     *         2 - caminho interno
     */
    public function getImages($cditem, $type = 1)
    {
        $photographiesResult = DB::table('photographies')->where('cditem', $cditem)->get();
        $images = [];
        foreach ($photographiesResult as $key => $image) {
            if ($type == 1) {
                $images[$key] = "/storage/".$image->nmurl;
            } elseif($type == 2) {
                $images[$key] = $image->nmurl;
            }
        }

        return $images;
    }

    public function deleteImages($cditem)
    {
        $images = $this->getImages($cditem);

        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }

        DB::table('photographies')->where('cditem', '=', $cditem)->delete();
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
    public function edit($cditem)
    {
        $this->isAuth();
        $item = Item::find($cditem);
       
        if (!empty($item)) {
            return view('itemform', [
                'data' => $item, 
                'collections' => $this->getCollections(), 
                'images' => $this->getImages($cditem)
            ]);
        }
    }

    public function searchitems($cdcollection, $nmitem)
    {
        $this->isAuth();

        $items = DB::table('items')
                ->whereRaw("UPPER(nmitem) LIKE '%". strtoupper($nmitem)."%' AND CDCOLLECTION = ".$cdcollection)
                ->get();

        return response($items, 200);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cditem)
    {
        $this->isAuth();

        $validateForm = $this->validateForm($request);
        
        if ($validateForm !== false) {
            return $validateForm;
        }

        $item = item::find($cditem);
        if(!empty($item)) {
            $item->nmitem = $request->input('nmitem');
            $item->cdcollection = $request->input('cdcollection');
            $item->dsitem = $request->input('dsitem');
            $item->save();
            
            $files = $request->allFiles();
            echo var_dump($files);
            $this->deleteImages($cditem);
            $this->saveImages($files, $cditem);

            $request->session()->flash('status', 'Salvo com sucesso!');
        }
        
      //  return redirect()->action('ItemController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cditem)
    {
        $item = Item::find($cditem);
        if (!empty($item)) {
            $item->delete();
            $this->deleteImages($cditem);
            return response('OK', 200);
        }
        return response('Item não encontrado', 404);
    }

    private function getCollections() 
    {
        return Collection::all();
    }

    private function validateCollection($cdcollection) 
    {
        if (empty($cdcollection) || !is_numeric($cdcollection)) {
            return true;
        }

        if (empty(Collection::find($cdcollection))) {
            return true;
        }
        
    }

    private function validateForm(Request $request)
    {
        if ($request->input('nmitem') == null) {
            return back()->withInput()->withErrors(['Preencha o campo Nome!']);
        }

        if ($this->validateCollection($request->input('cdcollection'))) {
            return back()->withInput()->withErrors(['Selecione um acervo!']);
        }

        if (empty($request->input('dsitem'))) {
            return back()->withInput()->withErrors(['Preencha o campo Descrição!']);
        }

        return false;
    }
}
