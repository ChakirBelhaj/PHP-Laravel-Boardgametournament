@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12 page-header">
                <h1>{{ $tournament->name }}</h1>
                @if($tournament->tournementUser->max('round') < $tournament->rounds)
                    <a href="{{ route('TournamentAdminStart', ['tournament'=>$tournament->id]) }}"
                       class="btn btn-primary pull-right btn-tournament">Start {{ ($tournament->tournementUser->max('round') == null ? 'Tournament' : 'round '.($tournament->tournementUser->max('round')+1).'/'.$tournament->rounds) }}</a>
                @elseif($tournament->status_id < 5)
                    <a href="{{ route('TournamentAdminComplete', ['tournament'=>$tournament->id]) }}"
                       class="btn btn-primary pull-right btn-tournament">FINISH!!!</a>
                @else
                    <a href="{{ route('TournamentAdmin') }}"
                       class="btn btn-default pull-right btn-tournament">Back</a>
                @endif
            </div>
            <div class="col-md-6">
                <form action="{{ route('TournamentAdminStore', ['tournament'=>$tournament->id]) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group input-group">
                        <label class="input-group-addon" for="rounds">Rondes</label>
                        <input type="number" class="form-control" id="rounds" name="rounds"
                               value="{{ $tournament->rounds }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="spelers">Spelers</label>
                        <input type="text" pattern="([0-9]{1,3}\s?-\s?[0-9]{1,3})" class="form-control" id="spelers"
                               name="spelers"
                               value="{{ $tournament->minplayers .' - '. $tournament->maxplayers }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="startdate">Start datum</label>
                        <input type="date" class="form-control" id="startdate" name="startdate"
                               value="{{ $tournament->startdate->format('Y-m-d') }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="enddate">Eind datum</label>
                        <input type="date" class="form-control" id="enddate" name="enddate"
                               value="{{ (!empty($tournament->enddate) ? $tournament->enddate->format('Y-m-d') : '') }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="streetname">Straatnaam</label>
                        <input type="text" class="form-control" id="streetname" name="streetname"
                               value="{{ $tournament->streetname }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="housenumber">Huisnummer</label>
                        <input type="number" class="form-control" id="housenumber" name="housenumber"
                               value="{{ $tournament->housenumber }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="zipcode">Postcode</label>
                        <input type="text" class="form-control" id="zipcode" name="zipcode"
                               value="{{ $tournament->zipcode }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="city">Plaats</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $tournament->city }}"/>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="description">Beschrijving</label>
                        <textarea class="form-control" name="description" id="description" rows="5" style="resize: none;">{{ $tournament->description }}</textarea>
                    </div>

                    <div class="form-group input-group">
                        <label class="input-group-addon" for="boardgame">Boardgame</label>
                        <select class="js-example-basic-single" style="width: 100%" name="game" id="games">
                            <option value="{{ $tournament->boardgame->id }}"
                                    selected>{{ $tournament->boardgame->name }}</option>
                        </select>
                    </div>

                    <div class="form-group input-group">
                        <input type="submit" value="Opslaan" class="btn btn-primary form-control">
                    </div>
                </form>
            </div>
            <div class="col-md-6">

                <h2>Spelers:</h2>
                <h4>Geaccepteerd:</h4>
                <ul>
                    @foreach($tournament->registrations()->where('accepted', 2)->get() as $registration)
                        <li>{{ $registration->user->name() }}</li>
                    @endforeach
                </ul>
                <h4>Niet geaccepteerd:</h4>
                <ul>
                    @foreach($tournament->registrations()->where('accepted', 1)->get() as $registration)
                        <li>{{ $registration->user->name() }}</li>
                    @endforeach
                </ul>
                <h4>Onbekend:</h4>
                <ul>
                    @foreach($tournament->registrations()->where('accepted', 0)->get() as $registration)
                        <li>{{ $registration->user->name() }}</li>
                    @endforeach
                </ul>
                <h3>Spelers uitnodigen</h3>
                <form method="post" action="{{ route('TournamentAdminInvite', ['tournament'=>$tournament->id]) }}">
                    {{ csrf_field() }}
                    <select class="js-example-basic-multiple" style="width: 100%" name="users[]" multiple="multiple"
                            id="users">
                    </select>
                    <input type="submit" value="uitnodigen" class="btn btn-primary form-control">
                </form>
                @if($tournament->tournementUser->max('round') != null)
                    <h3>Voer score in</h3>
                    <form method="get" action="{{ route('TournamentAdminScore', ['tournament'=>$tournament->id]) }}">
                        <select name="round" class="form-control">
                            @for($i=$tournament->tournementUser->max('round');$i>=1;$i--)
                                <option value="{{ $i }}">Ronde {{ $i }}</option>
                            @endfor
                        </select>
                        <input type="submit" value="Invoeren" class="btn btn-primary form-control">
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
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
                    url: '{{ route('usersJson') }}?tournament={{ $tournament->id }}',
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