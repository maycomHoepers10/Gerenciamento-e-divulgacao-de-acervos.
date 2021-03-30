<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require __DIR__.'/../../../vendor/autoload.php'; // change path as needed


use App\Publication;
use App\Collection;
use App\Item;
use Abraham\TwitterOAuth\TwitterOAuth;

use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\FacebookSDKException;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use App\Http\Controllers\SocialnetworkController;
use Facebook\Facebook;
use stdClass;
use Illuminate\Support\Facades\Storage;
use \Gumlet\ImageResize;

class PublicationController extends Controller
{
    private $fb;
    private $images;
    private $cdImages;
    private $accessToken;

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

        $publications = Publication::all();
        foreach ($publications as $publication) {
            $publication['nmitem'] = (Item::find($publication->cditem))->nmitem;
            $publication['dtpublication'] = \Carbon\Carbon::parse($publication->created_at)->format('d/m/Y');
            $publication['tmpublication'] = \Carbon\Carbon::createFromTimestamp(strtotime($publication->created_at)) 
            ->timezone('America/Sao_Paulo') ->rawFormat('H:i');
            
        }
        // echo var_dump( $publications);
        return view('publicationgrid', ['records' => $publications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->isAuth();
        return view('publicationform', ['collections' => $this->getCollections()]);
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

        $this->setImages($request);
        $publication = new Publication();

        if ($request->input('twitter') !== null) {
            $twitterId = $this->publishOnTwitter($request);
            $publication->setwitter = 1;
            $publication->idtwitterpost = $twitterId;
        }

        if ($request->input('facebook') !== null) {
            $facebookId = $this->publishOnFacebook($request);
            $publication->sefacebook = 1;
            $publication->idfacebookpost = $facebookId;
        }
    
        $publication->cdcollection = $request->input('cdcollection');
        $publication->cditem = $request->input('cditem');
        $publication->nmimages = $this->cdImages;
        $publication->dspublication = $request->input('dspublication');
       
        $publication->save();
        $request->session()->flash('status', 'Salvo com sucesso!');
        
       return redirect()->action('PublicationController@index');
    }

    private function setFacebookConfiguration() 
    {
        $socialnetworkController = new SocialnetworkController();
        $facebookConfiguration = $socialnetworkController->getFacebookConfiguration();
        
        $this->appIdFacebook =  $facebookConfiguration->nmtoken1;
        $this->appSecretFacebook =  $facebookConfiguration->nmtoken2;
        $this->pageIdFacebook =  $facebookConfiguration->nmtoken3;
        $this->accessTokenFacebook =  $facebookConfiguration->nmtoken4;
    }

    private function setTwitterConfiguration() 
    {
        $socialnetworkController = new SocialnetworkController();
        $twitterConfiguration = $socialnetworkController->getTwitterConfiguration();

        $this->apiKeyTwitter = $twitterConfiguration->nmtoken1;
        $this->apiKeySecretTwitter = $twitterConfiguration->nmtoken2;
        $this->accessTokenTwitter = $twitterConfiguration->nmtoken3;
        $this->accessTokenSecretTwitter = $twitterConfiguration->nmtoken4;
    }


    private function publishOnTwitter(Request $request)
    {
        $this->setTwitterConfiguration();
        
        $connection = new TwitterOAuth($this->apiKeyTwitter, $this->apiKeySecretTwitter, $this->accessTokenTwitter, $this->accessTokenSecretTwitter);
        $user = $connection->get("account/verify_credentials");
        $midiaIds = [];

        foreach ($this->images as $image) {
            $nmurl = str_replace('/','\\', "/".$image->nmurl);
            $imageName = str_replace('/','\\', $image->nmurl);
            
           
            $contents = storage_path('app\public'.$nmurl);
            $image = new ImageResize($contents);
            $image->resizeToBestFit(1000, 800);
            $image->save($imageName);
            $image_path = public_path($imageName);
            $media = $connection->upload('media/upload', ['media' => $image_path]);
            
            (new Filesystem())->delete($image_path);
            $midiaIds[] = $media->media_id_string;
        }

        $parameters = [
            'status' => $request->input('dspublication'),
            'media_ids' => implode(',', $midiaIds)
        ];
        
        $result = $connection->post('statuses/update', $parameters);
        

        return $result->id_str;
    }

    private function publishOnFacebook(Request $request)
    {
        $this->setFacebookConfiguration();

        $fb = new Facebook([
            'app_id' => $this->appIdFacebook,
            'app_secret' => $this->appSecretFacebook,
            'default_graph_version' => 'v2.5'
        ]);
        
        $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($this->accessTokenFacebook);
        
        $fb->setDefaultAccessToken($longLivedToken);
        
        $response = $fb->sendRequest('GET', $this->pageIdFacebook, ['fields' => 'access_token'])
            ->getDecodedBody();
        
        $foreverPageAccessToken = $response['access_token'];

        $fb = new \Facebook\Facebook([
            'app_id' => $this->appIdFacebook,
            'app_secret' => $this->appSecretFacebook,
            'default_graph_version' => 'v2.5'
        ]);
        
        $fb->setDefaultAccessToken($foreverPageAccessToken);

        $midiaIds = [];
        foreach ($this->images as $image) {
            $nmurl = str_replace('/','\\', "/".$image->nmurl);
            $media = $fb->sendRequest('POST', $this->pageIdFacebook."/photos", [
                "source" => $fb->fileToUpload('C:\Users\Fernanda\Desktop\meu_tcc_mpv\museu\storage\app\public'.$nmurl),
                "published" => false
            ]);
            $mediaId = $media->getDecodedBody()['id'];
            $midiaIds[] = '{media_fbid:"'.$mediaId.'"}';
        }
    
        $result = $fb->sendRequest('POST', $this->pageIdFacebook."/feed", [
            'message' => $request->input('dspublication'),
            'attached_media' => $midiaIds
        ]);

       return $result->getDecodedBody()['id'];
    }

    public function setImages(Request $request)
    {
        $cdsList = explode(',', $request->input('imagesHidden'));
        array_shift($cdsList);
        $cdsString = implode(',', $cdsList);
        $this->cdImages = $cdsString;
        $this->images = DB::table('photographies')->whereRaw("cdphotography IN(".$cdsString.")")->get();
    }
    

    private function deleteTwitterPost($twitterPostId)
    {
        $this->setTwitterConfiguration();

        $connection = new TwitterOAuth($this->apiKeyTwitter, $this->apiKeySecretTwitter, $this->accessTokenTwitter, $this->accessTokenSecretTwitter);
        $user = $connection->get("account/verify_credentials");
        
        $parameters = [
            'id' => $twitterPostId
        ];
        
        $connection->post('statuses/destroy', $parameters);
    }

    private function deleteFacebookPost($idfacebookpost)
    {
        $this->setFacebookConfiguration();

        $fb = new Facebook([
            'app_id' => $this->appIdFacebook,
            'app_secret' => $this->appSecretFacebook,
            'default_graph_version' => 'v2.5'
        ]);
        
        $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($this->accessTokenFacebook);
        
        $fb->setDefaultAccessToken($longLivedToken);
        
        $response = $fb->sendRequest('GET', $this->pageIdFacebook, ['fields' => 'access_token'])
            ->getDecodedBody();
        
        $foreverPageAccessToken = $response['access_token'];

        $fb = new \Facebook\Facebook([
            'app_id' => $this->appIdFacebook,
            'app_secret' => $this->appSecretFacebook,
            'default_graph_version' => 'v2.5'
        ]);
        
        $fb->setDefaultAccessToken($foreverPageAccessToken);

        $fb->sendRequest('DELETE', $idfacebookpost);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cdpublication)
    {
        $this->isAuth();

        $publication = Publication::find($cdpublication);
        $collection = Collection::find($publication->cdcollection);
        $item = Item::find($publication->cditem);

        $data = new stdClass();
        $data->nmcollection = $collection->nmcollection;
        $data->nmitem = $item->nmitem;
        $data->sefacebook = $publication->sefacebook;
        $data->setwitter = $publication->setwitter;

        $images = DB::table('photographies')->whereRaw("cdphotography IN(".$publication->nmimages.")")->get();
        $imagesUrl = [];
        foreach ($images as $image) {
            $imagesUrl[] = "/storage/".$image->nmurl;
        }
        $data->images = $imagesUrl;
        $data->dspublication = $publication->dspublication;

        return view('publicationview', ['collections' => $this->getCollections(), 'data' => $data]);
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
    public function destroy($cdpublication)
    {
        
        $publication = Publication::find($cdpublication);

        if ($publication->sefacebook) {
            $this->deleteFacebookPost($publication->idfacebookpost);
        }

        if($publication->setwitter) {
            $this->deleteTwitterPost($publication->idtwitterpost);
        }

        if (!empty($publication)) {
            $publication->delete();
            return response('OK', 200);
        }
        return response('Item não encontrado', 404);
    }

    private function getCollections() 
    {
        return Collection::all();
    }
}
