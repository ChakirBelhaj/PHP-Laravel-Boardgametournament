@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-8  col-md-offset-2">
                <center><h1>Toernooien</h1></center>
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
                <form method="post" action="{{ route('createTournamentPost') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Naam</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                        <label>Rondes</label>
                        <input type="number" class="form-control" name="rounds" value="{{ old('rounds') }}">

                        <label>Min spelers</label>
                        <input type="number" class="form-control" name="minplayers" value="{{ old('minplayers') }}">

                        <label>Max spelers</label>
                        <input type="number" class="form-control" name="maxplayers" value="{{ old('maxplayers') }}">

                        <label>Start datum</label>
                        <input type="date" class="form-control" name="startdate" value="{{ old('startdate') }}">

                        <label>Straatnaam</label>
                        <input type="text" class="form-control" name="streetname" value="{{ old('streetname') }}">

                        <label>Huisnummer</label>
                        <input type="text" class="form-control" name="housenumber" value="{{ old('housenumber') }}">

                        <label>Postcode</label>
                        <input type="text" class="form-control" name="zipcode" value="{{ old('zipcode') }}">

                        <label>Plaats</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city') }}">

                        <label>Beschrijving</label>
                        <textarea class="form-control" name="description" rows="5" style="resize: none;"></textarea>

                        <label>Spel</label>
                        <select class="js-example-basic-single" style="width: 100%" name="game" id="games">
                            @if(is_string(old('game')) && old('game') !== '')
                                <option value="{{ old('game') }}" selected>{{ \App\Boardgame::where('id', old('game'))->pluck('name')[0] }}</option>
                            @endif
                        </select>

                        <label>Spelers</label>
                        <select class="js-example-basic-multiple" style="width: 100%" name="users[]" multiple="multiple" id="users">
                            @if(is_array(old('users')))
                                @foreach(old('users') as $users)
                                    <option value="{{ $users }}" selected>{{ \App\User::where('id', $users)->pluck('username')[0] }}</option>
                                @endforeach
                            @endif
                        </select>
                        <br><br>
                        <label for="private" class="form-check-label">
                            <input type="checkbox" name="private" value="1" class="form-check-input" id="private">
                            Priv&#233;
                        </label>
                        <br><br>
                        <input type="submit" class="btn btn-primary" value="Maak">
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $(document).ready(function(){
            $('#games').select2({
                placeholder: "Kies een bordspel",
                ajax: {
                    url: '{{ route('boardgamesJson') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return data;
                    },
                    cache: true,
                    delay: 250
                }
            });
            $('#users').select2({
                placeholder: "Kies spelers",
                ajax: {
                    url: '{{ route('usersJson') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term),
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        data.page = data.page || 1;
                        return data;
                    },
                    cache: true,
                    delay: 250
                }
            });
        });
    </script>
@stop