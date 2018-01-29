<div data-spy="affix" data-offset-top="150" style="background-color: darkred; font-size: 95%;">

    <nav class="navbar navbar-default navbar-static-top">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse"
                    aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div id="mobile-header"><a href="{{ route('root') }}" style="color: white">Boardgame Tournament 1</a></div>
        </div>

        <div class="nav navbar-nav navbar-left big" style="max-height: 0;">
            <a href="{{ route('root') }}">
            <img class="logo" src="{{ asset('images/WindesheimW.png') }}" width="75" style="position: relative; top: -4.4em; left: 5em;">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Center part of Navbar -->
            <ul class="nav navbar-nav navbar-center">
                <li><a href="{{ route('search') }}"><span class="desctop">Bordspellen</span><span class="little">Zoek</span></a></li>
                <li><a href="{{ route('tournamentList') }}">Toernooien</a></li>
                @auth
                    <li><a href="{{ route('suggestion') }}">Spel voorstellen</a></li>
                @endauth
                <li><a href="{{ route('rankings') }}">Scorebord</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @role('admin')
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle menudrop" data-toggle="dropdown" role="button"
                       aria-expanded="false"
                       aria-haspopup="true">Admin <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('adminAchievement') }}">Achievements</a></li>
                        <li><a href="{{ route('suggestionHandle') }}">Spel voorstellen</a></li>
                        <li><a href="{{ route('AdminBoardgame') }}">Bordspellen</a></li>
                    </ul>
                </li>
                @endrole
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Registreren</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"
                           aria-haspopup="true">
                            {{ Auth::user()->name() }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profileView', ['user' => Auth::user()->id]) }}">Profiel</a></li>
                            <li><a href="{{ route('TournamentAdmin') }}">Mijn Toernooien</a></li>
                            <li><a href="{{ route('requests') }}">Uitnodigingen</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                                    Uitloggen
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</div>
