<nav class="navbar navbar-expand-md bg-primary text-white shadow-sm p-2">
    <a class="navbar-brand pl-4" href="{{ url('/') }}">
        <img class=" waves-effect" style="width: 70px" src="{{asset('/images/vfleet.png')}}">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('felhasznalok.index') }}">
                        {{__('Felhasználók')}} <span class="caret"></span>
                    </a>
                </li>
            @endif
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('autok.index') }}">
                        {{__('Járművek')}} <span class="caret"></span>
                    </a>
                </li>
            @endif
            <li class="nav-item pl-2 pr-2">
                <a class="nav-link   text-white" href="{{ route('munkalapok.index') }}">
                    {{__('Munkalapok')}} <span class="caret"></span>
                </a>
            </li>
            <li class="nav-item pl-2 pr-2">
                <a class="nav-link   text-white" href="{{ route('hibak.index') }}">
                    {{__('Hibák')}} <span class="caret"></span>
                </a>
            </li>
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('ertesitesek.index') }}">
                        {{__('Értesítések')}} <span class="caret"></span>
                    </a>
                </li>
            @endif
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('dokumentumok.index') }}">
                        {{__('Dokumentumok')}} <span class="caret"></span>
                    </a>
                </li>
            @endif
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('tankolas.index') }}">
                        {{__('Tankolások')}} <span class="caret"></span>
                    </a>
                </li>
            @endif
            @if(!empty(session('role')) && session('role') == 3)
                <li class="nav-item pl-2 pr-2">
                    <a class="nav-link   text-white" href="{{ route('tartozek.index') }}">
                        {{__('Autó tartozékok')}} <span class="caret"></span>
                    </a>
                </li>
            @endif

        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item waves-effect">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item ">
                    <a id="navbarDropdown" class="nav-link  text-white waves-effect" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link  text-white waves-effect" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endguest
        </ul>
    </div>
</nav>
