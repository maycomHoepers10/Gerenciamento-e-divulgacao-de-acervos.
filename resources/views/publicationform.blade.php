@extends('layouts.app', ["menu" => "publication"])

@section('content')

<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>
                    <h3>Dados da Publicação</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            {{$errors->first()}}
                        </div>
                    @endif

                      <form class="needs-validation" action="/publicacoes" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="cditem" id="cditem"/>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="inputCollection">Acervo</label>
                                <select name="cdcollection" id="inputCollection" class="form-control">
                                    <option value="">Escolha um acervo...</option>
                                    @foreach ($collections as $collection)
                                        <option 
                                            value="{{$collection->cdcollection}}" 
                                            {{(isset($data) && $data->cdcollection == $collection->cdcollection) 
                                                || old('cdcollection') == $collection->cdcollection? 'selected': ''
                                            }}
                                        >
                                            {{$collection->nmcollection}}
                                        </option>
                                    @endforeach                                
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="inputItem">Item</label>
                                <div id="inputItem">
                                    <label class="sr-only" for="inlineFormInputGroup">Username</label>
                                    <div class="input-group mb-3">
                                      <input type="text" class="form-control" id="item-name" placeholder="Pesquise um item" disabled>
                                      <div class="input-group-prepend" style="cursor: pointer;" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
                                        <div class="input-group-text">
                                            <img title="Pesquisar" width="14px" src="{{asset('images/pesquisar.svg')}}" />
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Consulta de item</h5>
                                          <button id="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          {{-- <form> --}}
                                            <label for="recipient-search" class="col-form-label">Pesquisa</label>
                                            <div class="input-group mb-3">
                                               
     
                                                <input type="text" class="form-control" id="search-item" placeholder="Informe o nome de um item">
                                                <div class="input-group-prepend" style="cursor: pointer;">
                                                    <button id="btn-search-item" type="button" class="btn btn-primary">Pesquisar</button>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <ul id="grid-item" class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-md-12 mb-3" style="heigth: 30px;">
                                <label>Fotos</label>   
                                <input type="hidden" id="imagesHidden" name="imagesHidden">
                                <input
                                    accept="image/*"
                                    style="display: none"
                                    id="text-button-file"
                                    type="file"
                                />
                                {{-- <label for="text-button-file" type="button" class="btn btn-primary btn-sm">Adicionar foto</label> --}}
                                <div id="images-up" class="col-md-12 mb-3" style="display: flex;" >
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="inputAboutMuseum">Descrição</label>
                            <textarea class="form-control" name="dspublication" id="dspublication" rows="3" >{{$data->dspublication ?? old('dspublication')}}</textarea>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="twitter">
                                    <label class="custom-control-label" for="customSwitch1" name="socialType">Twitter</label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"  id="customSwitch2" name="facebook">
                                    <label class="custom-control-label" for="customSwitch2">Facebook</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Salvar</button>
                        <a href="/publicacoes"><button class="btn btn-danger" type="button">Cancelar</button></a>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
        window.fbAsyncInit = function() {
        FB.init({
            appId            : '398006994918935',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v8.0'
        });
        };
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <script type="text/javascript">
        var nfile = 0;
        var listRecords = []; 
        var imagesList = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        
        document.getElementById('btn-search-item').addEventListener('click', function(e) {
            var nameItem = document.getElementById("search-item").value;
            var cdcollection = document.getElementById('inputCollection').value;

            removeItems();
            $.ajax({
                    type: "GET",
                    url: "/api/itens/"+ cdcollection +"/" + nameItem,
                    context: this,
                    success: function(results) {
                        mountGrid(results);
                    },
                error: function(error) {
                    registerNotFound();
                }
            });
        });

        function mountGrid(results)
        {

            if (results.length > 0) {
                results.forEach(function(item) {
                    var li = document.createElement('li');
                    li.innerText = item.nmitem;
                    li.setAttribute('cditem', item.cditem);
                    li.setAttribute('class', 'list-group-item');
                    li.onclick = function(e) {
                        document.getElementById('cditem').value = e.target.getAttribute('cditem');
                        document.getElementById("item-name").value = e.target.innerText;
                        mountPanelImages(e.target.getAttribute('cditem'));
                        document.getElementById('closeModal').click();
                    }
                    listRecords.push(li);
                    document.getElementById('grid-item').appendChild(li);
                });
            } else {
                registerNotFound();
            }
           
        }

        function registerNotFound() 
        {
            var li = document.createElement('li');
            li.innerText = 'Nenhum registro encontrado!!!';
            li.setAttribute('class', 'list-group-item');
            li.style.textAlign = 'center';
            listRecords.push(li);
            document.getElementById('grid-item').appendChild(li);
        }

        function removeItems()
        {
            listRecords.forEach(function(item) {
                item.remove();
            });
        }

        function mountPanelImages(cditem)
        {
            $.ajax({
                    type: "GET",
                    url: "/api/imagens/item/" + cditem,
                    context: this,
                    success: function(images) {
                        console.log(images);
                        JSON.parse(images).forEach(function(image) { 
                            createImage(image.src, image.cdphotography);
                        });
                    },
                error: function(error) {
                    registerNotFound();
                }
            });
        }
        
        function createImage(urlImage, cdphotography)
        {
            var current_date = (new Date()).valueOf().toString();
            var random = Math.random().toString();
            
            var divImage = document.createElement("div");
            divImage.id = random;
            divImage.style.width = "100px";
            divImage.style.height = "100px";
            divImage.style.position = "relative";
            divImage.style.marginLeft = "7px";
            divImage.style.marginBottom = "5px"

            nfile = nfile + 1;
            var fileInput = document.getElementById('text-button-file').cloneNode();
            fileInput.style.display = 'none';
            fileInput.id = "file"+ nfile;
            fileInput.name = "file"+ nfile;

            divImage.appendChild(fileInput); 

            var urlBlob = urlImage;
            var image = document.createElement("IMG");
            image.setAttribute("src", urlBlob);
            image.setAttribute('cdphotography', cdphotography);
            image.style.width = "100px";
            image.style.height = "100px";
            
            image.onclick = function(element) {
                if (element.target.getAttribute('selected') == null) {
                    element.target.style.border = "4px solid #65c368"
                    element.target.setAttribute('selected', true);
                    document.getElementById('imagesHidden').value = document.getElementById('imagesHidden').value + ','+ element.target.getAttribute('cdphotography');
                } else {
                    element.target.style.border = "none"
                    element.target.removeAttribute('selected');
                }
            };

            // image.onmouseover = function() {
            //     var div = document.createElement("div");
            //     div.id = "opacity-img-" + random;
            //     div.style.position = "absolute";
            //     div.style.width = "100px";
            //     div.style.height = "100px";
            //     div.style.top = 0;
            //     div.style.border = "4px solid #1f6fb2"
            //     //div.style.background = "rgba(0,0,0, 0.7)";

            //     // var imgDel = document.createElement("span");
            //     // imgDel.innerText = 'X';
            //     // imgDel.style.fontWeight = "bold";
            //     // imgDel.style.width = "25px";
            //     // imgDel.style.position = "relative";
            //     // imgDel.style.color = "#FFFFFF";
            //     // imgDel.style.marginLeft = "5px";
            //     // imgDel.style.cursor = "pointer";
            //     // imgDel.title = "Remover";
                
            //     // imgDel.onclick = function() {
            //     //     document.getElementById(random).remove();
            //     // };

            //     // div.appendChild(imgDel);
            //     document.getElementById(random).appendChild(div);
            // }

            // image.onmouseleave = function() {
            //     var id = "opacity-img-" + random;
            //     document.getElementById(id).addEventListener('mouseout', function(){
            //         document.getElementById(id).remove();
            //     });
            // }
            // image.setAttribute("alt", "The Pulpit Rock");
            divImage.appendChild(image);
            document.getElementById('images-up').appendChild(divImage);
        }

  </script>
@endsection