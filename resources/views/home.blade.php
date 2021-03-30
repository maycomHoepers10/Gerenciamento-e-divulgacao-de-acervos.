@extends('layouts.app', ["menu" => "museum"])

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Dados do Museu</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                      <form class="needs-validation" action="/museu" method="POST" novalidate>
                        @csrf
                        <div class="form-row">
                          <div class="col-md-8 mb-3">
                            <label for="validationTooltip01">Nome</label>
                          <input type="text" name="name" class="form-control" id="validationTooltip01" value="{{$museum->nmmuseum ?? null}}" required>
                          </div>
                          <div class="col-md-4 mb-3">
                            <label for="validationTooltip02">Data de fundação</label>
                            <input type="text" name="date" class="form-control" id="validationTooltip02" placeholder="Formato: dd/mm/aaaa" value="{{$museum->dtfundation ?? null}}" required>
                          </div>
                          <div class="form-group col-md-10 mb-3">
                            <label for="inputAddress">Logradouro</label>
                            <input type="text" name="address" class="form-control" id="inputAddress" placeholder="1234 Main St" value="{{$museum->nmaddress ?? null}}">
                          </div>
                          <div class="form-group col-md-2 mb-3">
                            <label for="inputAddressNumber">Número</label>
                            <input type="text" name="addressNumber" class="form-control" id="inputAddressNumber" placeholder="1010" value="{{$museum->ninumberaddress ?? null}}">
                          </div>
                          <div class="form-group col-md-6 mb-3">
                            <label for="inputNeighborhood">Bairro</label>
                            <input type="text" name="neighborhood" class="form-control" id="inputNeighborhood" placeholder="1234 Main St" value="{{$museum->nmneighborhood ?? null}}">
                          </div>
                          <div class="form-group col-md-6 mb-3">
                            <label for="inputComplement">Complemento</label>
                            <input type="text" name="complement" class="form-control" id="inputComplement" placeholder="1234 Main St" value="">
                          </div>
                          <div class="form-group col-md-8 mb-3">
                            <label for="inputCity">Cidade</label>
                            <input type="text" name="city" class="form-control" id="inputCity" placeholder="1234 Main St" value="{{$museum->nmcity ?? null}}">
                          </div>
                          <div class="form-group col-md-4 mb-3">
                            <label for="inputState">Estado</label>
                            <input type="text" name="state" class="form-control" id="inputState" placeholder="1234 Main St"  value="{{$museum->nmstate ?? null}}">
                          </div>
                          <div class="form-group col-md-6 mb-3">
                            <label for="inputPhone">Telefone</label>
                            <input type="text" name="phone" class="form-control" id="inputPhone" placeholder="1234 Main St" value="{{$museum->nmphone ?? null}}">
                          </div>
                          <div class="form-group col-md-6 mb-3">
                            <label for="inputEmail">E-mail</label>
                            <input type="text" name="email" class="form-control" id="inputEmail" placeholder="1234 Main St" value="{{$museum->nmemail ?? null}}">
                          </div>
                          <div class="form-group col-md-12 mb-3">
                            <label for="inputAboutMuseum">Sobre o Museu</label>
                          <textarea class="form-control" name="aboutMuseum" id="inputAboutMuseum" rows="3" >{{$museum->dsmuseum ?? null}}</textarea>
                          </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Salvar</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
  <script type="text/javascript">
    
  </script>
@endsection