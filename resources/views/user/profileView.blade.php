@extends('layouts.app')

@section('content')
    <div>
        <div class="row">

            <div class="col-md-8 col-md-offset-2">
                <form method="get" action="{{ route('profileView', $user) }}">
                    <div align="right" class="form-group">
                        <select class="js-example-basic-multiple; form-control col-md-2 col-xs-3" style="width: 20%;"
                                name="search" id="users"></select>
                        <button type="submit" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-search"></span> Zoek
                        </button>
                    </div>
                </form>
                <div class="panel-body">
                    <div>
                        <center><h2>Profiel van: {{$user->username}}</h2></center>
                        <table style="width:40%">
                            {{--shows users data and checks if certain data is not empty otherwise it will not show anything. --}}
                            @if(!empty($user->image))
                                <tr>
                                    <th><img alt="Embedded Image" src="data:image/png;base64,{{$user->image}}"
                                             width="200" height="200" class="img-thumbnail"/></th>
                                </tr>
                            @endif<br>
                            <tr>
                                <th>Naam:</th>
                                <th>{{$user->firstname}}</th>

                            </tr>
                            {{--checks if the insertion is empty --}}
                            @if(!empty($user->insertion))
                                <tr>
                                    <th>Tussen voegsel:</th>
                                    <th>{{$user->insertion}}</th>
                                </tr>
                            @endif
                            <tr>
                                <th>Achternaam:</th>
                                <th>{{$user->lastname}}</th>
                            </tr>
                            @if(!empty($user->city))
                                <tr>
                                    <th>Woonplaats:</th>
                                    <th>{{$user->city}}</th>
                                </tr>
                            @endif
                            <tr>
                                <th>Gebruikersnaam:</th>
                                <th>{{$user->username}}</th>
                            </tr>
                            <tr>
                                <th>E-mail:</th>
                                <th>{{$user->email}}</th>
                            </tr>
                        </table>
                        @auth
                        @if($user->id == Auth::id())
                            <a href="{{route('profileEdit', ['user'=>$user->id])}}" class="btn btn-info">Wijzig mijn
                                profiel</a>
                        @endif
                        @endauth
                    </div>
                    <div>
                        <h3>Favorieten</h3>
                        @if(!empty($boardgame))
                            <table style="width: 400%">
                                @foreach($boardgame as $key)
                                    @foreach($key as $value)
                                        <tr>
                                            <th>
                                                <a href="{{route('boardgame', $value->id) }}">{{$value->name}}
                                                </a>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>

                        @endif
                        @if($user->id == Auth::id() && empty($boardgame))
                            U heeft nog geen favorieten bordspellen.
                            <br>
                            Ga naar de pagina van een <a href="{{route('search')}}">leuk bordspel</a> en klik op het
                            sterretje om een bordspel toe te voegen.
                        @elseif(empty($boardgame))
                            Deze gebruiker heeft geen favorieten :(
                        @endif
                    </div>
                    <div>
                        <h3>Achievements</h3>
                        @if($user->id == Auth::id() && empty($achievementUser))
                            U heeft nog geen achievements.
                        @elseif(empty($achievementUser))
                            Deze gebruiker heeft nog geen achievemnets behaald
                        @endif
                        @if(!empty($achievementUser))
                            <div>
                                <div class="row">
                                    @for($i=0; $i < count($achievementUser); $i++)
                                        <div class="col-md-4">
                                            <p>{{$achievementUser[$i][0]->name}}</p>
                                            <img alt="Embedded Image"
                                                 src="data:image/png;base64,{{$achievementUser[$i][0]->image}}"
                                                 width="50"
                                                 height="50"/>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>


        @stop

        @section('script')
            <script>
                $(document).ready(function () {
                    $('#users').select2({
                        placeholder: "Zoek gebruiker",
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