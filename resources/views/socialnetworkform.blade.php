@extends('layouts.app', ["menu" => "publication"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>
                    <h3>Configuração das Redes Sociais</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                      <form class="needs-validation" action="/redesocial/configuracoes" method="POST" novalidate>
                        @csrf
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Facebook</a>
                            </li>
                            <li class="nav-item" role="presentation">
                              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#twitter_content" role="tab" aria-controls="twitter_content" aria-selected="false">Twitter</a>
                            </li>
                          </ul>
                          <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab" style="padding-top: 10px;">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Id do aplicativo</label>
                                    <input type="text" name="appid_facebook" class="form-control" id="appid_facebook" value="{{$facebookData->nmtoken1 ?? null}}" >
                                    </div>
                                    <div class="form-group col-md-12 mb-3">
                                        <label>Segredo do aplicativo</label>
                                    <input class="form-control" name="appsecret_facebook" id="appsecret_facebook" rows="3" value="{{$facebookData->nmtoken2 ?? null}}" >
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Id da página</label>
                                    <input type="text" name="pageid_facebook" class="form-control" id="pageid_facebook" value="{{$facebookData->nmtoken3 ?? null}}">
                                    </div>
                                    <div class="form-group col-md-12 mb-3">
                                        <label>Token de acesso</label>
                                    <input class="form-control" name="accesstoken_facebook" id="accesstoken_facebook" rows="3" value="{{$facebookData->nmtoken4 ?? null}}">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twitter_content" role="tabpanel" aria-labelledby="profile-tab" style="padding-top: 10px;">
                                <div class="col-md-12 mb-3">
                                    <label>Chave API</label>
                                <input type="text" name="apikey_twitter" class="form-control" id="apikey_twitter" value="{{$twitterData->nmtoken1 ?? null}}" >
                                </div>
                                <div class="form-group col-md-12 mb-3">
                                    <label>Segredo da chave API</label>
                                <input class="form-control" name="apikeysecret_twitter" id="apikeysecret_twitter" rows="3" value="{{$twitterData->nmtoken2 ?? null}}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Token de acesso</label>
                                <input type="text" name="accesstoken_twitter" class="form-control" id="accesstoken_twitter" value="{{$twitterData->nmtoken3 ?? null}}">
                                </div>
                                <div class="form-group col-md-12 mb-3">
                                    <label>Segredo do token de acesso</label>
                                <input class="form-control" name="accesstokensecret_twitter" id="accesstokensecret_twitter" rows="3" value="{{$twitterData->nmtoken4 ?? null}}">
                                </div>
                            </div>
                          </div>
                        <button class="btn btn-primary" type="submit">Salvar</button>
                        <a href="/publicacoes"><button class="btn btn-danger" type="button">Voltar</button></a>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('javascript')
  <script type="text/javascript">
    
  </script>
@endsection --}}