@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12 page-header">
                <h1>Tournament: {{ $tournament->name }}</h1>
            </div>
            <form action="{{ route('TournamentAdminStartSave', ['tournament'=>$tournament->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="rounds" value="{{ $tournament->rounds }}">
                @php
                    $i=$round;
                @endphp
                <div class="col-md-12">
                    <h2>Ronde {{ $i }}</h2>
                    @for($j=0;$j<$tournament->maxplayers;$j++)
                        <div class="form-group input-group users">
                            <label class="input-group-addon" for="speler{{$j}}">Speler {{$j+1}}</label>
                            <select name="round{{$i}}[speler{{$j}}]" id="speler{{$j}}" class="form-control">
                                <option value="0">--------------------------</option>
                                @foreach($tournament->registrations->where('accepted', 2) as $registration)
                                    <option value="{{ $registration->user->id }}">{{ $registration->user->name() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group input-group user_group">
                            <label class="input-group-addon" for="speler_group{{$j}}">Groep</label>
                            <select name="round{{$i}}[group{{$j}}]" id="speler_group{{$j}}" class="form-control">
                                <option value="0">--------------------------</option>
                                @for($a = 1; $a<=ceil($tournament->maxplayers/$tournament->boardgame->minplayers); $a++)
                                    <option value="{{ $a }}">Groep {{ $a }}</option>
                                @endfor
                            </select>
                        </div>
                    @endfor
                </div>
                <div class="form-group input-group">
                    <input type="submit" value="Opslaan" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
@endsection