@extends('layouts.app')

@section('content')
    <div class="row">
        <legend style="display: inline;">
            <div class="row">
                <div class="col-md-5">
                    <center><h3>{{ $tournament->name }}</h3></center>
                </div>
                <div class="col-md-7">
                    <center><h3>Spelers</h3></center>
                </div>
            </div>
        </legend>
        <div class="col-md-1"></div>
        <div class="col-md-5">
            Rondes:  Het toernooi heeft {{ $tournament->rounds }} rondes<br>
            Spelers: Er kunnen {{ $tournament->minplayers .'-'. $tournament->maxplayers }} spelers tegelijk spelen per bordspel<br>
            <legend></legend>
            Start datum: {{ $tournament->startdate->format('Y-m-d') }}<br>
            Eind datum: {{ (!empty($tournament->enddate) ? $tournament->enddate->format('Y-m-d') : '') }}<br>
            <legend></legend>
            Straat naam: {{ $tournament->streetname }}<br>
            Huis nummer: {{ $tournament->housenumber }}<br>
            Postcode: {{ $tournament->zipcode }}<br>
            Plaats: {{ $tournament->city }}<br>
            <legend></legend>
            Spel: {{ $tournament->boardgame->name }}<br>
            Beheerder: {{ $tournament->Owner->username }}<br>
            Status: {{ $tournament->status->name }}<br>
            Beschrijving:<br>
            {{ $tournament->description }}<br>
            <legend></legend>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-5">
            <legend>Spelers:</legend>
            <legend>Geaccepteerd:</legend>
            <ul>
                @foreach($tournament->registrations()->where('accepted', 2)->get() as $registration)
                    <li>{{ $registration->user->name() }}</li>
                @endforeach
            </ul>
            <legend>Niet geaccepteerd:</legend>
            <ul>
                @foreach($tournament->registrations()->where('accepted', 1)->get() as $registration)
                    <li>{{ $registration->user->name() }}</li>
                @endforeach
            </ul>
            <legend>Onbekend:</legend>
            <ul>
                @foreach($tournament->registrations()->where('accepted', 0)->get() as $registration)
                    <li>{{ $registration->user->name() }}</li>
                @endforeach
            </ul>

            @if(in_array($tournament->status->id, current(\App\Status::whereIn('name', ['Public In progress', 'Public Closed', 'Private In progress', 'Private Closed'])->where('allowed','App\Tournament')->pluck('id'))))
                <legend><h3>Rondes</h3></legend>
                @for($i=1;$i<=$tournament->rounds;$i++)
                    <h4>Ronde {{$i}}</h4>
                    <ul>
                        @foreach($tournament->tournementUser()->where('round', $i)->get() as $tu)
                            <li>{{ $tu->user->name() }}, Groep {{ $tu->round_group }}</li>
                        @endforeach
                    </ul>
                @endfor

                <h3>Scores</h3>
                <div class="table-responsive">
                    <table class="table table-striped searchTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Groep</th>
                            <th>Ronde</th>
                            <th>Score</th>
                            <th>Win</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tournament->tournementUser()->get() as $user)
                            <tr>
                                <td>{{ $user->user->name() }}</td>
                                <td>{{ $user->round_group }}</td>
                                <td>{{ $user->round }}</td>
                                <td>{{ $user->score }}</td>
                                <td>{{ ($user->win ? 'Ja' : 'Nee') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection