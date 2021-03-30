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

                        <input type="hidden" name="cditem" id="cditem"/>
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="inputCollection">Acervo</label>
                                <input type="text" class="form-control" id="inputCollection" value="{{$data->nmcollection ?? null}}" disabled>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="inputItem">Item</label>
                                <input type="text" class="form-control" id="inputItem" value="{{$data->nmitem ?? null}}" disabled>
                            </div>
                            <div class="col-md-12 mb-3" style="heigth: 30px;">
                                <label>Fotos</label>
                                <div id="images-up" class="col-md-12 mb-3" style="display: flex;" >

                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="inputDescription">Descrição</label>
                                <textarea class="form-control" id="inputDescription" rows="3" disabled>{{$data->dspublication ?? null}}</textarea>
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="twitter" {{$data->setwitter == 1 ? 'checked': ''}} disabled>
                                    <label class="custom-control-label" for="customSwitch1" name="socialType">Twitter</label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"  id="customSwitch2" name="facebook" {{$data->sefacebook == 1 ? 'checked': ''}} disabled>
                                    <label class="custom-control-label" for="customSwitch2">Facebook</label>
                                </div>
                            </div>
                        </div>
                        <a href="/publicacoes"><button class="btn btn-secondary btn-sm" type="button">Voltar</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php 

    if (!empty($data->images)) {
        echo  '<script> var images = '. json_encode($data->images).';</script>';
    } else {
        echo  '<script> var images = [];</script>';
    }

?>
@section('javascript')
    <script type="text/javascript">
        var nfile = 0;
        var listRecords = []; 
        var imagesList = [];

    
        window.addEventListener('load', function(){
            images.forEach(function(imageUrl) { 
                createImage(imageUrl);
            });
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

            // nfile = nfile + 1;
            // var fileInput = document.getElementById('text-button-file').cloneNode();
            // fileInput.style.display = 'none';
            // fileInput.id = "file"+ nfile;
            // fileInput.name = "file"+ nfile;

            // divImage.appendChild(fileInput); 

            var urlBlob = urlImage;
            var image = document.createElement("IMG");
            image.setAttribute("src", urlBlob);
            image.style.width = "100px";
            image.style.height = "100px";
            
            image.ondblclick = function(element) {
                
                if (document.getElementById("imageMaxScreen")) {
                    document.getElementById("imageMaxScreen").remove();
                }

                var imageSrc = element.target.src;
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

            divImage.appendChild(image);
            document.getElementById('images-up').appendChild(divImage);
        }

  </script>
@endsection