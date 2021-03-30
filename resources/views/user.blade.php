@extends('layouts.app', ["menu" => "user"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Usuários</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                      <a href="/usuarios/novo"><button type="button" class="btn btn-primary btn-sm">Novo usuário</button></a>
                  <br>
                  <br>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">Identificador</th>
                          <th scope="col">Nome</th>
                          <th scope="col">E-mail</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($records as $user)  
                        <tr>
                            <th scope="row">{{$user->id}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                              <div style="display:flex; justify-content: space-evenly;">
                              <a href="/usuarios/editar/{{$user->id}}">
                                    <img 
                                    data-toggle="tooltip" data-placement="top" title="Editar"
                                    style="cursor: pointer;"
                                    width="20px" height="20px" 
                                    src="{{asset('images/editar.svg')}}
                                    "/>
                                </a>
                                <a href="">
                                    <img 
                                    onclick="userDelete({{$user->id}})"
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

        function userDelete(cduser) {
            if (confirm("Tem certeza que deseja excluir?")) {
                var reloadScreen = function() {
                    window.location.reload();
                };

                $.ajax({
                    type: "DELETE",
                    url: "/api/usuarios/" + cduser,
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
