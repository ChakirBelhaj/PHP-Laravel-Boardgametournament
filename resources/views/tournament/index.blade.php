@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <center><h1>Toernooien</h1></center>
            <legend>
                <center><div class="panel-heading"> @auth<a href="{{ route('createTournament') }}" class="btn btn-default btn-sm">Voeg toe</a>@endauth</div></center>
            </legend>

            <div>
                <form method="get" action="{{ route('tournamentList') }}">
                    <input type="radio" name="filter" value="all"
                           id="all" {{ (isset($_GET['filter']) && $_GET['filter'] == 'all' ? 'checked' : '') }}>
                    <label for="all">All</label>
                    <input type="radio" name="filter" value="open"
                           id="open" {{ (isset($_GET['filter']) && $_GET['filter'] == 'open' ? 'checked' : '') }}>
                    <label for="open">Open</label>
                    <input type="radio" name="filter" value="in_progress"
                           id="in_progress" {{ (isset($_GET['filter']) && $_GET['filter'] == 'in_progress' ? 'checked' : '') }}>
                    <label for="in_progress">In progress</label>
                    <input type="radio" name="filter" value="closed"
                           id="closed" {{ (isset($_GET['filter']) && $_GET['filter'] == 'closed' ? 'checked' : '') }}>
                    <label for="closed">Closed</label>
                </form>
            </div>
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
                        <th>Status</th>
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
                                <td>{{ date('d-m-Y', strtotime($tournament->startdate)) }}</td>
                                <td>{{ $tournament->city }}</td>
                                <td><a href="{{ route('boardgame',['boardgame' => $tournament->boardgame['id']]) }}">{{ $tournament->boardgame['name'] }}</a></td>
                                <td>{{ $tournament->status->name }}</td>
                                <td>
                                    @auth
                                    @if(
                                        $tournament->user_id == Auth::user()->id /* If the user is the owner of the tournament */ ||
                                        in_array($tournament->status->id, current(\App\Status::whereIn('name', ['Public Open', 'Public In progress', 'Public Closed'])->where('allowed','App\Tournament')->pluck('id'))) ||
                                        in_array($tournament->status->id, current(\App\Status::whereIn('name', ['Private Open', 'Private In progress', 'Private Closed'])->where('allowed','App\Tournament')->pluck('id'))) &&
                                        count($tournament->registrations->where('user_id', Auth::user()->id)->whereIn('accepted', [0,2])) == 1
                                    )
                                        <a href="{{ route('tournament', ['tournament'=>$tournament->id]) }}">info</a>
                                    @endif
                                    @if(
                                        $tournament->status->id == current(current(\App\Status::where('name', 'Public Open')->where('allowed','App\Tournament')->pluck('id'))) &&
                                        count($tournament->registrations->where('user_id', Auth::user()->id)) == 0
                                    )
                                        <a href="{{ route('tournamentRegister', ['tournament'=>$tournament->id]) }}">inschrijven</a>
                                    @endif
                                    @if($tournament->user_id == Auth::user()->id)
                                        <a href="{{ route('TournamentAdminEdit', ['tournament'=>$tournament->id]) }}">beheer</a>
                                    @endif
                                    @endauth
                                    @guest
                                    @if(in_array($tournament->status->id, current(\App\Status::whereIn('name', ['Public Open', 'Public In progress', 'Public Closed'])->where('allowed','App\Tournament')->pluck('id'))))
                                        <a href="{{ route('tournament', ['tournament'=>$tournament->id]) }}">info</a>
                                    @endif
                                    @endguest
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <center>{{ $tournaments->links() /* For laravel pagination links */ }}</center>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('input[name="filter"]').on('change', function () {
                $(this).parent().submit();
            });
        });
    </script>
@stop