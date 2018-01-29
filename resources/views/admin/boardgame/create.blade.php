@extends('layouts.app')

@section('content')

    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center><h1>Bordspel</h1></center>
                <legend></legend>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>
                @endif
                <form action="{{ route('AdminBoardgameSave') }}" method="post">
                    {{ csrf_field() }}
                    @if (!empty($suggestion))
                        <input type="hidden" name="suggestion_id" value="{{$suggestion->id}}">
                    @endif
                    <div class="form-group">

                        <label for="name">Naam bordspel</label>
                        <input type="text" class="form-control" id="name"
                               name="name" {!! (!empty($suggestion)) ? "value=\"{$suggestion->boardgame_name}\"" : null !!}><br>
                        <label for="img">Afbeelding</label>
                        <input type="text" class="form-control" id="img"
                               name="img"><br>
                        <label for="minplayers">Minimum spelers</label>
                        <input type="number" class="form-control" id="minplayers" name="minplayers"><br>
                        <label for="maxplayers">Maximum spelers spelers</label>
                        <input type="number" class="form-control" id="maxplayers" name="maxplayers"><br>
                        <label for="">Gemiddelde speeltijd (min)</label>
                        <input type="number" class="form-control" id="playtime" name="playtime"><br>
                        <label for="yearpublished">Jaar uitgaven</label>
                        <input type="number" class="form-control" id="yearpublished"
                               name="yearpublished"><br>
                        <label for="isexpansion">Is uitbreiding</label><br>
                        <input type="checkbox" class="text-center" value="1" id="isexpansion"
                               name="isexpansion"><br><br>
                    </div>
                    <input type="submit" class="btn btn-info">

                </form>
            </div>
        </div>
    </div>
@endsection