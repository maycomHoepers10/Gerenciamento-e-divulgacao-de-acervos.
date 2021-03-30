@extends('layouts.app', ["menu" => "item"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Itens</h3></div>
              
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                      <a href="/itens/novo"><button type="button" class="btn btn-primary btn-sm">Novo item</button></a>
                      <div style="width: 100%; height:1px; background: rgba(0,0,0, 0.20); margin-top:10px;"></div>
                      <div id="search-fast-content">
                            <form action="/itens/pesquisa" method="POST" novalidate>
                            @csrf
                                <label for="recipient-search" class="col-form-label">Pesquisa rápida</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="searchField" class="form-control" id="search-fast-field" placeholder="Informe o nome de um item">
                                    <div class="input-group-prepend" style="cursor: pointer;">
                                    <button id="btn-search-item" type="submit" class="btn btn-primary">Buscar</button>
                                    </div>
                                </div>
                            </form>
                      </div>
          
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Identificador</th>
                          <th scope="col">Nome </th>
                          <th scope="col">Acervo</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($records as $item)  
                        <tr>
                            <th scope="row">{{$item->cditem}}</th>
                            <td>{{$item->nmitem}}</td>
                            <td>{{$item->nmcollection}}</td>
                            <td>
                              <div style="display:flex; justify-content: space-evenly;">
                              <a href="/itens/editar/{{$item->cditem}}">
                                    <img 
                                    data-toggle="tooltip" data-placement="top" title="Editar"
                                    style="cursor: pointer;"
                                    width="20px" height="20px" 
                                    src="{{asset('images/editar.svg')}}
                                    "/>
                                </a>
                                <a href="">
                                    <img 
                                    onclick="itemDelete({{$item->cditem}})"
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

        function itemDelete(cditem) {
            if (confirm("Tem certeza que deseja excluir?")) {
                var reloadScreen = function() {
                    window.location.reload();
                };

                $.ajax({
                    type: "DELETE",
                    url: "/api/itens/" + cditem,
                    context: this,
                    success: function() {
                        reloadScreen();
                    },
                error: function(error) {
                    console.log(error);
                }
            });
          }
        }

        function searchFast() {
            var text = document.getElementById('search-fast-field').value;

            if (text == "") {
                document.location.href = '/itens';
            } else {
                document.location.href = '/itens/pesquisa/'+ text;
            }
        }
  </script>
@endsection
