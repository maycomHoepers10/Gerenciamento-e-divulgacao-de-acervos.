@extends('layouts.app', ["menu" => "publication"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Publicações</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                      <a href="/publicacoes/publicar"><button type="button" class="btn btn-primary btn-sm">Publicar</button></a>
                      <a href="/redesocial/configuracoes"><button type="button" class="btn btn-secondary btn-sm">Configurações</button></a>
                  <br>
                  <br>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Identificador</th>
                          <th scope="col">Item</th>
                          <th scope="col">Data de publicação</th>
                          <th scope="col">Hora de publicação</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($records as $publication)  
                        <tr>
                            <th scope="row">{{$publication->cdpublication}}</th>
                            <td>{{$publication->nmitem}}</td>
                            <td>{{$publication->dtpublication}}</td>
                            <td>{{$publication->tmpublication}}</td>
                            <td>
                              <div style="display:flex; justify-content: space-between;">
                              <a href="/publicacoes/visualizar/{{$publication->cdpublication}}">
                                    <img 
                                    data-toggle="tooltip" data-placement="top" title="Visualizar" alt="Visualizar"
                                    style="cursor: pointer;"
                                    width="20px" height="20px" 
                                    src="{{asset('images/visualizador.svg')}}
                                    "/>
                                </a>
                                <a href="">
                                    <img 
                                    onclick="publicationDelete({{$publication->cdpublication}})"
                                    data-toggle="tooltip" data-placement="top" title="Excluir"
                                    style="cursor: pointer;" 
                                    width="20px" height="20px" 
                                    src="{{asset('images/delete.svg')}}"
                                    />
                                </a>
                              </div>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                    </table>
                    @if (count($records) < 1)
                      <div style="text-align: center;">Nenhum registro encontrado!</div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
  <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        function publicationDelete(cdpublication) {
            if (confirm("Tem certeza que deseja excluir?")) {
                var reloadScreen = function() {
                    window.location.reload();
                };

                $.ajax({
                    type: "DELETE",
                    url: "/api/publicacoes/" + cdpublication,
                    context: this,
                    success: function(resp) {
                        reloadScreen();
                    },
                error: function(error) {
                    console.log(error);
                    alert("ATENÇÃO: Não foi possível excluir o registro!\n* Verifique se o registro existe.\n* Verifique se existem dados relacionados.");
                }
            });
          }
        }
  </script>
@endsection
