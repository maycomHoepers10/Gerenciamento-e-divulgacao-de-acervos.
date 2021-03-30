<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Museum;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
        $museum = Museum::find(1);

        if (!empty($museum->cdmuseum)) {
            $museum->dtfundation = \Carbon\Carbon::parse($museum->dtfundation)->format('d/m/Y');
            return view('home', compact('museum'));
        } else {
            return view('home');
        }
    }


    public function store(Request $request)
    {
        $museum = Museum::find(1);

        if (empty($museum)) {
            $museum = new Museum();
            $museum->cdmuseum = 1;
        }

        $museum->nmmuseum = $request->input('name');
        $museum->dsmuseum = $request->input('aboutMuseum');
        $museum->nmphone = $request->input('phone');
        $museum->nmemail = $request->input('email');
        $museum->dtfundation = $request->input('date');
        $museum->nmstate = $request->input('state');
        $museum->nmcity = $request->input('city');
        $museum->nmneighborhood = $request->input('neighborhood');
        $museum->nmaddress = $request->input('address');
        $museum->ninumberaddress = $request->input('addressNumber');
        $museum->save();
        $request->session()->flash('status', 'Salvo com sucesso!');
        return redirect()->action('HomeController@index');
    }
}
