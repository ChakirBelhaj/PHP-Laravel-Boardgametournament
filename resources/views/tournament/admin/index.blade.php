@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <center><h1>Mijn toernooien</h1></center>
                <legend></legend>
                <div class="table-responsive">
                    <table class="table table-striped searchTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Rondes</th>
                            <th>Spelers</th>
                            <th>Start datum</th>
                            <th>Plaats</th>
                            <th>Spel</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($tournaments) == 0)
                            <tr>
                                <td colspan="4" class="text-center">Er zijn geen toernooien gevonden</td>
                            </tr>
                        @else
                            @foreach($tournaments as $tournament)
                                <tr>
                                    <td>{{ $tournament->name }}</td>
                                    <td>{{ $tournament->rounds }}</td>
                                    <td>{{ ($tournament->minplayers == $tournament->maxplayers ? $tournament->minplayers : $tournament->minplayers . '-' . $tournament->maxplayers) }}</td>
                                    <td>{{ $tournament->startdate->format('d-m-Y') }}</td>
                                    <td>{{ $tournament->city }}</td>
                                    <td>{{ $tournament->boardgame['name'] }}</td>
                                    <td>
                                        <a href="{{ route('TournamentAdminEdit', ['tournament'=>$tournament->id]) }}"
                                           class="btn btn-info">Beheer</a>
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