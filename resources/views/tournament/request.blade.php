@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <center><h1>Uitnodigingen</h1></center>
                <legend></legend>
                <div class="table-responsive">
                    <table class="table table-striped searchTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Rondes</th>
                            <th>Start datum</th>
                            <th>Plaats</th>
                            <th>Spel</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($requests) == 0)
                            <tr>
                                <td colspan="6" class="text-center">Je hebt geen uitnodegingen voor tournamenten</td>
                            </tr>
                        @else
                            @foreach($requests as $request)
                                @php
                                    $tournament = $request->tournament;
                                @endphp
                                <tr>
                                    <td>{{ $tournament->name }}</td>
                                    <td>{{ $tournament->rounds }}</td>
                                    <td>{{ date('d-m-Y', strtotime($tournament->startdate)) }}</td>
                                    <td>{{ $tournament->city }}</td>
                                    <td>{{ $tournament->boardgame['name'] }}</td>
                                    <td>
                                        @if($request->accepted == 0 || $request->accepted == 2)
                                            <a href="{{ route('tournament', ['tournament'=>$tournament->id]) }}">info</a>
                                        @endif
                                        @switch($request->accepted)
                                        @case(0)
                                        <a href="{{ route('requestState', ['tournament_request'=>$request->id, 'state'=>2]) }}">Accepteren</a>
                                        <a href="{{ route('requestState', ['tournament_request'=>$request->id, 'state'=>1]) }}">Afwijzen</a>
                                        @break

                                        @case(1)
                                            Afgewezen
                                        @break

                                        @case(2)
                                            Geaccepteerd
                                        @break

                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection