@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <center><h1>{{ $game->name }}</h1></center>
                <legend></legend>


            <div class="col-md-6">
              <img style="width: 480px; height: 400px;" src="{{ (!empty($game->image) ? $game->image : 'http://www.geekyhobbies.com/wp-content/uploads/2017/06/Bugs-in-the-Kitchen-Question-Mark.jpg') }}" class="img-responsive" alt="Responsive image"><br>
            </div>
            <div class="col-md-6">
              @auth
                <legend>Beoordeling:

                <a style="margin-bottom: 5px;" href="{{ route('boardgameVote', ['id'=>$game->id,'value'=>1]) }}"
                   class="btn btn-default btn-sm @if(current(current($userVote))['vote'] == 1) btn-green @endif">
                  <span
                          class="glyphicon glyphicon-thumbs-up"></span> @if($rating[0]['voters'] + $rating[1]['voters'] >= 5){{ (!empty($rating[1]['voters']) ? $rating[1]['voters'] : '') }}@endif
                </a>
                <a style="margin-bottom: 5px;" href="{{ route('boardgameVote', ['id'=>$game->id,'value'=>0]) }}"
                   class="btn btn-default btn-sm @if(count($userVote) > 0 && current(current($userVote))['vote'] == 0) btn-red @endif">
                  <span
                          class="glyphicon glyphicon-thumbs-down"></span> @if($rating[0]['voters'] + $rating[1]['voters'] >= 5){{ (!empty($rating[0]['voters']) ? $rating[0]['voters'] : '') }}@endif
                </a>
              @endauth
              @guest
                @if($rating[0]['voters'] + $rating[1]['voters'] >= 5)
                  <br>
                  <span class="glyphicon glyphicon-thumbs-up"></span>{{ (!empty($rating[1]['voters']) ? $rating[1]['voters'] : '') }}
                  <span class="glyphicon glyphicon-thumbs-down"></span>{{ (!empty($rating[0]['voters']) ? $rating[0]['voters'] : '') }}
                @endif
              @endguest
              @auth
                {{--checks if the user has this game as favorite.
                 Depending on the outcome will the button direct the user to different controllers and show differently.
                 One deletes one adds it to the users favorites.--}}
                <a style="margin-bottom: 5px; @if(count($favorite)!= 0) background: orange;color: white @endif" href=" @if (count($favorite)!= 0) {{ route('boardgameDelFavorite', ['id'=>$game->id]) }} @else {{ route('boardgameAddFavorite', ['id'=>$game->id]) }} @endif"
                   class="btn btn-default btn-sm">
                  <span class="glyphicon glyphicon-star"></span>
                </a>
              @endauth<br>
                </legend>

                <legend>Spelers: <b>{{ $game->minplayers . '-' . $game->maxplayers }}</b><br></legend>
                <legend>Speeltijd: <b>{{ $game->playtime }} min</b><br></legend>
                <legend>Uitbreiding: <b>{{ ($game->isexpansion > 0 ? 'ja' : 'nee') }}</b><br></legend>
                <legend>Uitgebracht op: <b>{{ $game->yearpublished }}</b><br></legend>
                <legend>Beschijving<br><h5>{{ $game->description }}</h5></legend>
            </div>
      </div>

    </div>
@endsection