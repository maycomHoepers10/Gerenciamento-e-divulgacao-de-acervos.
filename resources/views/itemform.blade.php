@extends('layouts.app', ["menu" => "item"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>
                    <h3>Dados do Item</h3>
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

                      <form enctype="multipart/form-data" class="needs-validation" action="/itens{{isset($data) ? "/".$data->cditem : ""}}" method="POST" novalidate>
                        @csrf
                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                                <label for="validationTooltip01">Nome</label>
                          <input type="text" name="nmitem" class="form-control" id="nmitem" value="{{$data->nmitem ?? old('nmitem')}}" required>
                          </div>
                            <div class="col-md-12 mb-3" style="heigth: 30px;">
                                <input
                                    accept="image/*"
                                    style="display: none"
                                    id="text-button-file"
                                    type="file"
                                />
                                <label for="text-button-file" type="button" class="btn btn-primary btn-sm">Adicionar foto</label>
                                <div id="images-up" class="col-md-12 mb-3" style="display: flex;" >
                                </div>
                            </div>
                          {{-- <input
                          accept="image/*"
                          className={classes.uploadButton}
                          id="text-button-file"
                          multiple
                          type="file"
                          onChange={ event => setPhoto(event.target.files)}
                      />
                      <label htmlFor="text-button-file">
                          <Button variant="contained" component="span" className={classes.button}>
                              Adicione fotos
                          </Button>
                      </label> --}}
                          <div class="form-group col-md-12 mb-3">
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
                          <div class="form-group col-md-12 mb-3">
                            <label for="inputAboutMuseum">Descrição</label>
                          <textarea class="form-control" name="dsitem" id="dsitem" rows="3" >{{$data->dsitem ?? old('dsitem')}}</textarea>
                          </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Salvar</button>
                        <a href="/itens"><button class="btn btn-danger" type="button">Cancelar</button></a>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php 

    if (isset($images)) {
        echo  '<script> var images = '. json_encode($images).';</script>';
    } else {
        echo  '<script> var images = [];</script>';
    }

?>
@section('javascript')
  <script type="text/javascript">
        var nfile = 0;

        window.addEventListener('load', function(){
            images.forEach(function(image) { 
                createImage(image);
            });
        });
     
        document.getElementById('text-button-file').addEventListener('change', function(e) {
            var urlImage = URL.createObjectURL(e.target.files[0]);
            createImage(urlImage);
        });
        

        function createImage(urlImage)
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
            image.style.width = "100px";
            image.style.height = "100px";
            

            image.onmouseover = function() {
                var div = document.createElement("div");
                div.id = "opacity-img-" + random;
                div.setAttribute("src", urlBlob);
                div.style.position = "absolute";
                div.style.width = "100px";
                div.style.height = "100px";
                div.style.top = 0;
                div.style.background = "rgba(0,0,0, 0.7)";

                var imgDel = document.createElement("span");
                imgDel.innerText = 'X';
                imgDel.style.fontWeight = "bold";
                imgDel.style.width = "25px";
                imgDel.style.position = "relative";
                imgDel.style.color = "#FFFFFF";
                imgDel.style.marginLeft = "5px";
                imgDel.style.cursor = "pointer";
                imgDel.title = "Remover";
                
                imgDel.onclick = function() {
                    document.getElementById(random).remove();
                };

                div.appendChild(imgDel);

                div.ondblclick = function(element) {
                    if (document.getElementById("imageMaxScreen")) {
                        document.getElementById("imageMaxScreen").remove();
                    }

                    var imageSrc = element.target.getAttribute('src');
                    var divContentImage = document.createElement("div");
                    divContentImage.id = "imageMaxScreen";
                    divContentImage.style.position = "fixed";
                    divContentImage.style.width = "100%";
                    divContentImage.style.height = "100vh";
                    divContentImage.style.background = 'rgba(0,0,0, 0.80)';
                    divContentImage.style.top = 0;
                    divContentImage.style.zIndex = 10;
                    divContentImage.style.display = 'flex';
                    divContentImage.style.justifyContent = 'center';
                    divContentImage.style.alignItems = 'center';

                    var img = document.createElement("img");
        
                    img.src = imageSrc;
                    img.style.maxWidth = "800px";
                    img.style.maxHeight = "450px";
                    img.style.border = "6px solid #FFF";

                    var divClose = document.createElement("div");
                    divClose.innerHTML = "X";
                    divClose.style.textAlign = "center";
                    divClose.style.fontWeight = "bold";
                    divClose.style.lineHeight = "45px";
                    divClose.style.width = '45px';
                    divClose.style.height = '45px';
                    divClose.style.borderRadius = '45px';
                    divClose.style.background = 'rgba(255,255,255, 0.6)';
                    divClose.style.position = 'absolute';
                    divClose.style.top = '5vh';
                    divClose.style.cursor = 'pointer';
                    divClose.title = "Fechar";

                    divClose.onclick = function() {
                        document.getElementById("imageMaxScreen").remove();
                    };

                    divContentImage.appendChild(img);
                    divContentImage.appendChild(divClose);
                    document.body.appendChild(divContentImage);
                };

                document.getElementById(random).appendChild(div);
            }

            image.onmouseleave = function() {
                var id = "opacity-img-" + random;
                document.getElementById(id).addEventListener('mouseout', function(){
                    document.getElementById(id).remove();
                });
            }
            // image.setAttribute("alt", "The Pulpit Rock");
            divImage.appendChild(image);
            document.getElementById('images-up').appendChild(divImage);
        }
  </script>
@endsection