@extends('layouts.app')

@section('content')

    <div class="row little">
        <div class="col-md-12">
            <form method="get" class="form-inline" action="{{ route('search') }}">
                <div class="input-group-btn row">
                        <span class="col-xs-6 col-md-2">
                        </span>
                    <!-- search bar -->
                    <input type="text" class="form-control col-xs-3 col-md-6"
                           style="min-width: 55%; max-width: 100%; border-radius: 5px 0px 0px 5px; height: 2.5em;"
                           name="search_string"
                           value="{{ (isset($_GET['search_string']) ? $_GET['search_string'] : '') }}">
                    <select name="search_type" class="form-control col-md-2 col-xs-3 dropd"
                            style="border-radius: 0px; height: 2.5em;">
                        <option value="name" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'name' ? 'selected' : '') }}>
                            Naam
                        </option>
                        <option value="minp" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'minp' ? 'selected' : '') }}>
                            Min spelers
                        </option>
                        <option value="maxp" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'maxp' ? 'selected' : '') }}>
                            Max spelers
                        </option>
                        <option value="time" {{ (isset($_GET['search_type']) && $_GET['search_type'] == 'time' ? 'selected' : '') }}>
                            Speeltijd
                        </option>
                    </select>
                    <button class="btn btn-default col-xs-3 col-md-1" id="searchButton"
                            style="border-radius: 0px 5px 5px 0px; height: 2.5em;">Zoek
                    </button>
                    <span class="col-md-1"></span>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <center><h1>Bordspellen</h1></center>
        <legend></legend>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped searchTable">
                    <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Spelers</th>
                        <th>Speeltijd</th>
                        <th>Likes</th>
                        <th>Uitbreiding</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($games) == 0)
                        <tr>
                            <td colspan="4" class="text-center">Er zijn geen spellen gevonden</td>
                        </tr>
                    @else
                        @foreach($games as $game)
                            <tr>
                                <td><a href="{{ route('boardgame',['boardgame' => $game->id]) }}">{{ $game->name }}</a></td>
                                <td>{{ $game->minplayers . '-' . $game->maxplayers }}</td>
                                <td>{{ $game->playtime }}
                                    min
                                </td>
                                <td>
                                    @if($game['rating'] != false)
                                        <span class="glyphicon glyphicon-thumbs-up"></span> {{ $game['rating'][1]['voters'] }}
                                        <span class="glyphicon glyphicon-thumbs-down"></span> {{ $game['rating'][0]['voters'] }}
                                    @endif
                                </td>
                                <td>{{ ($game->isexpansion > 0 ? 'ja' : 'nee') }}</td>
                                <td>
                                    <a href="{{ route('boardgame', ['boardgame' => $game->id]) }}">
                                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <center>{{ $games->links() /* For laravel pagination links */ }}</center>
        </div>
    </div>
@endsection
