@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12 page-header">
                <h1>Tournament: {{ $tournament->name }}</h1>
            </div>
            <form action="{{ route('TournamentAdminScoreSave', ['tournament'=>$tournament->id]) }}" method="post">
                {{ csrf_field() }}
                @php
                    $i = request('round');
                @endphp
                <input type="hidden" name="round" value="{{ $i }}">
                <div class="col-md-12">
                    <h2>Ronde {{ $i }}</h2>
                    @foreach($rounds as $j => $round)
                        <div class="form-group input-group users">
                            <label for="speler{{ $round->id }}">Speler {{$j+1}}</label>
                            <div>
                                {{ $round->user->username }}
                            </div>
                        </div>
                        <div class="form-group input-group user_group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="user{{$round->id}}[win]" value="1" {{ ($round->win ? ' checked' : '') }}>Win</label>
                            </div>
                        </div>
                        <div class="form-group input-group users">
                            <label for="speler_group{{$j}}">Groep</label>
                            <div>
                                {{ $round->round_group }}
                            </div>
                        </div>
                        <div class="form-group input-group user_group">
                            <label for="score_{{$j}}">Score</label>
                            <select name="user{{ $round->id }}[score]" class="form-control" id="score_{{$j}}">
                                @php $items = [0,1,3,4,5,6,7,9,10,11,13]; @endphp
                                @foreach($items as $item)
                                    <option value="{{ $item }}" @if($round->score == $item) selected @endif>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
                <div class="form-group input-group">
                    <input type="submit" value="Opslaan" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
@endsection