<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cookie&family=Great+Vibes&family=Niconne&family=Noto+Serif+JP:wght@400;500;600&family=Satisfy&family=Tangerine&family=Varela+Round&display=swap');
    </style>
</head>
<body 

    @if(Auth::check())
        style="overflow-x: hidden;"
    @else
        style="overflow: hidden;"
    @endif
>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <h2 style="font-family: 'Alex Brush', cursive;
                    font-family: 'Cookie', cursive;
                    font-family: 'Great Vibes', cursive;
                    font-family: 'Niconne', cursive;
                    font-family: 'Noto Serif JP', serif;
                    font-family: 'Satisfy', cursive;
                    font-family: 'Tangerine', cursive;
                    font-family: 'Varela Round', sans-serif;
                    font-size: 30px;">Easy Museum</h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul id="menu-top" class="navbar-nav mr-auto">
                        <?php 
                        $menu = $menu ?? null; 
                        $isHomeActive = request()->routeIs('/') || request()->routeIs('museu') || $menu == "museum" ? 'active': '';
                        $isCollectionActive = $menu == "collection" ? 'active': '';
                        $isItemsActive = $menu == "item" ? 'active' : '';
                        $isPublicationActive = $menu == "publication" ? 'active': '';
                        $isUser = $menu == "user" ? 'active' : '';
                 
                    ?>
                    <a class="nav-link {{$isHomeActive}}" id="v-pills-home-tab"  href="{{route('museu')}}" role="tab" aria-controls="v-pills-home">Museu</a>
                    <a class="nav-link {{$isCollectionActive}}" id="v-pills-profile-tab" href="{{route('collection')}}" role="tab" aria-controls="v-pills-profile">Acervos</a>
                    <a class="nav-link {{$isItemsActive}}" id="v-pills-item-tab"  href="{{route('item')}}" role="tab" aria-controls="v-pills-profile">Itens</a>
                    <a class="nav-link {{$isPublicationActive}}"  href="{{route('publicacoes')}}" role="tab" aria-controls="v-pills-messages">Publicações</a>
                    <a class="nav-link {{$isUser}}" id="v-pills-settings-tab" href="{{route('user')}}" role="tab" aria-controls="v-pills-settings">Usuários</a>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                            </li> --}}
                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrar') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
            <div class="row" 
            @if(!Auth::check())
                style="
                background-image: url('{{asset('images/capa2.jpg')}}');
                background-image: url('http://127.0.0.1:8000/images/capa3.jpg');
                background-size: contain;
                background-color: rgba(43, 43, 43);
                background-repeat: no-repeat;
                height: calc(100vh - 60px);
                background-position: right;
            "
            @else
                style="
                    min-height: 89.3vh;
                "
            @endif
            

            >
                @if(Auth::check())
                    <div id="menu-left" class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical"
                            @if(Auth::check())
                                style="
                                    background-color: rgba(43, 43, 43);
                                    min-height: 100%;
                                    max-height: 100vh;
                                "
                            @endif
                        >
                            <?php 
                                $menu = $menu ?? null; 
                                $isHomeActive = request()->routeIs('museu') ? 'active-link': '';
                                $isCollectionActive = $menu == "collection" ? 'active-link': '';
                                $isItemsActive = $menu == "item" ? 'active-link' : '';
                                $isPublicationActive = $menu == "publication" ? 'active-link': '';
                                $isUser = $menu == "user" ? 'active-link' : '';
                         
                            ?>
                            <a class="nav-link color-link {{$isHomeActive}}"  href="{{route('museu')}}" >Museu</a>
                            <a class="nav-link color-link {{$isCollectionActive}}" href="{{route('collection')}}" >Acervos</a>
                            <a class="nav-link color-link {{$isItemsActive}}" href="{{route('item')}}">Itens</a>
                            <a class="nav-link color-link {{$isPublicationActive}}"  href="{{route('publicacoes')}}">Publicações</a>
                            <a class="nav-link color-link {{$isUser}}" href="{{route('user')}}"s>Usuários</a>
                        </div>
                    </div>
                @endif
                <div class="{{Auth::check()? 'col-md-9 col-sm-12 ': 'col-7'}}">
                <div class="tab-content" id="v-pills-tabContent">

                    <main class="py-4">
                        @yield('content')
                    </main>

                </div>
                </div>
          </div>
   
    </div>

    <script src="{{ asset('js/app.js')}}" type="text/javascript"></script>

    @hasSection('javascript')
        @yield('javascript')
    @endif
</body>
</html>
