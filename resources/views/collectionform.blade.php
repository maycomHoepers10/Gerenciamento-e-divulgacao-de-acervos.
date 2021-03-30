@extends('layouts.app', ["menu" => "collection"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>
                    <h3>Dados do Acervo</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                      <form class="needs-validation" action="/acervos{{isset($data->cdcollection) ? "/".$data->cdcollection : ""}}" method="POST" novalidate>
                        @csrf
                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                            <label for="validationTooltip01">Nome</label>
                          <input type="text" name="name" class="form-control" id="nmcollection" value="{{$data->nmcollection ?? null}}" required>
                          </div>
                          <div class="form-group col-md-12 mb-3">
                            <label for="inputAboutMuseum">Descrição</label>
                          <textarea class="form-control" name="description" id="dscollection" rows="3" >{{$data->dscollection ?? null}}</textarea>
                          </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Salvar</button>
                        <a href="/acervos"><button class="btn btn-danger" type="button">Cancelar</button></a>
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