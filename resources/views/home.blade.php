@extends('layouts.app')

@section('content')
    @php use \App\BoardgameRating; @endphp
    <div>
        <div class="col-lg-3" style="text-align:center"><br><br><br>
            <div class="text-center">
                @if(!empty($boardgame))
                    <center>
                        <legend>Favorieten</legend>
                    </center>
                    <table align="center">

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
            </div>
            <br>
            <div>
                <table class="table table-striped searchTable">
                    <center>
                        <legend>Meest gewaardeerde bordspellen</legend>
                    </center>
                    <thead>
                    <tr>
                        <th>Bordspel</th>
                        <th>Likes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(empty($likes))
                        <tr>
                            <td colspan="4" class="text-center">Er zijn geen likes voor bordspellen gevonden</td>
                        </tr>
                    @else

                        @foreach($likes as $like)
                            @php $data = BoardgameRating::getData($like->boardgame_id);@endphp

                            <tr>
                                <td><a href="{{route('boardgame', $like->boardgame_id) }}">{{$data[0]}}</a></td>
                                <td>{{$like->result}}</td>
                            </tr>
                        @endforeach

                    @endif
                    </tbody>

                </table>
            </div>
            <br>
            <div>
                <center>
                    <legend>Grootste toernamenten</legend>
                </center>
                <div class="table-responsive">
                    <table class="table table-striped searchTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Spelers</th>
                            <th>Start datum</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($tournaments) == 0)
                            <tr>
                                <td colspan="4" class="text-center">Er zijn geen Tournaments gevonden</td>
                            </tr>
                        @else
                            @foreach($tournaments as $tournament)
                                <tr>
                                    <td><a href="{{route('tournament', $tournament->id) }}">{{ $tournament->name }}</a>
                                    </td>
                                    <td>{{ ($tournament->minplayers == $tournament->maxplayers ? $tournament->minplayers : $tournament->minplayers . '-' . $tournament->maxplayers) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($tournament->startdate)) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {{ $tournaments->links() /* For laravel pagination links */ }}
                </div>
            </div>
        </div>

        <div class="col-lg-0"></div>
        <div class="col-lg-5">

            <center>
                <legend><h2>Welkom op Board Game tournament</h2></legend>
            </center>
            Deze website is gemaakt als school project door 6 Enthasiaste studenten van het Windesheim in Flevoland.
            <br><br>
            Het doel van de website is om mensen met het zelfde interesse samen te brengen door middel van het
            organiseren van toernooien.<br><br>
            Probeer zoveel mogelijk achievements te behalen door het winnen van rondes van toernooien.<br><br>
            Ondek nieuwe bordspellen en geef de bordspellen die jij leuk vindt een like of voeg ze toe aan jouw
            favorieten.<br><br>


        </div>
        <div class="col-lg-0"></div>
        <div class="col-lg-4"><br><br><br>
            <center>
                <legend>Top 10 beste spelers</legend>
            </center>
            <div class="table-responsive">
                <table style="width:40%" align="center" class="table table-striped" id="myTable">
                    <thead>
                    <tr>
                        <th>Gebruikersnaam</th>
                        <th>Gewonnen</th>
                        <th>Verloren</th>
                        <th>Win rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ranking as $key => $rankings)
                        <tr>
                            <th>
                                <a href="{{ route('profileView', ['id' => $rankings->id]) }}">{{ $rankings->username }}</a>
                            </th>
                            <td>
                                <center>{{ $rankings->wins }}</center>
                            </td>
                            <td>
                                <center>{{ $rankings->losses }}</center>
                            </td>
                            @if($rankings->wins >= 0 && $rankings->losses >= 1)
                                @php  $ratio = $rankings->wins + $rankings->losses;
                             $winrate = 100 / $ratio * $rankings->wins; @endphp
                                <td>
                                    <center>{{round($winrate, 0)}}{{'%'}}</center>
                                </td>
                            @else
                                @if($rankings->wins >=1 && $rankings->losses == 0)
                                    <td>
                                        <center>100%</center>
                                    </td>
                                @else
                                    <td>
                                        <center>0%</center>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
