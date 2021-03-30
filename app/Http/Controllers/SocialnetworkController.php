<?php

namespace App\Http\Controllers;

use App\Socialnetwork;
use Illuminate\Http\Request;
use App\Collection;
use Illuminate\Support\Facades\DB;

class SocialnetworkController extends Controller
{

    public function isAuth()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->isAuth();

        $facebookData = $this->getFacebookConfiguration();
        $twitterData = $this->getTwitterConfiguration();

        return view('socialnetworkform', ['facebookData' => $facebookData, 'twitterData' => $twitterData]);
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

        /*        Salva as configurações do Facebook              */
        if ($request->input('appid_facebook') !== null) {
            $socialNetworkDel1 = Socialnetwork::find(1);
            if (!empty($socialNetworkDel1)) {
                $socialNetworkDel1->delete();
            }
            $socialNetwork1 = new Socialnetwork();
            $socialNetwork1->cdsocialnetwork = 1;
            $socialNetwork1->nmtoken1 = $request->input('appid_facebook');
            $socialNetwork1->nmtoken2 = $request->input('appsecret_facebook');
            $socialNetwork1->nmtoken3 = $request->input('pageid_facebook');
            $socialNetwork1->nmtoken4 = $request->input('accesstoken_facebook');
            $socialNetwork1->setype = 1;
            $socialNetwork1->save();
        }

        /*        Salva as configurações do Twitter              */
        if ($request->input('apikey_twitter') !== null) {
            $socialNetworkDel2 = Socialnetwork::find(2);
            if (!empty($socialNetworkDel2)) {
                $socialNetworkDel2->delete();
            }
            $socialNetwork2 = new Socialnetwork();
            $socialNetwork2->cdsocialnetwork = 2;
            $socialNetwork2->nmtoken1 = $request->input('apikey_twitter');
            $socialNetwork2->nmtoken2 = $request->input('apikeysecret_twitter');
            $socialNetwork2->nmtoken3 = $request->input('accesstoken_twitter');
            $socialNetwork2->nmtoken4 = $request->input('accesstokensecret_twitter');
            $socialNetwork2->setype = 2;
            $socialNetwork2->save();
        }

        $request->session()->flash('status', 'Salvo com sucesso!');
        
       return redirect()->action('PublicationController@index');
    }

    public function getFacebookConfiguration()
    {
        return DB::table('socialnetworks')->whereRaw("cdsocialnetwork = 1")->first();
    }

    public function getTwitterConfiguration()
    {
        return DB::table('socialnetworks')->whereRaw("cdsocialnetwork = 2")->first();
    }
}
