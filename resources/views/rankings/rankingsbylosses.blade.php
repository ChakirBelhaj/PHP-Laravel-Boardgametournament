@extends('layouts.app')
@section('content')
    <div>
        <div class="row">
            @if (isset($error))
                @foreach($error as $errors)
                    <?php echo "<div id=\"message\" class=\"alert alert-danger col-md-12\">" . $errors . "</div>"  ?>
                @endforeach
            @endif

            @if (isset($succes))
                @foreach($succes as $success)
                    <?php echo "<div id=\"message\" class=\"alert alert-success col-md-12\">" . $success . "</div>"; ?>
                @endforeach
            @endif


            <div class="col-md-10 col-md-offset-1">
                <center><h1>Scorebord</h1></center>
                <center>
                    <legend>Top 10</legend>
                </center>
                <form method="post" action="{{ route('searchBoardgameRankings') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <div class="input-group">
                                <select required class="js-example-basic-single" style="width: 100%" name="game"
                                        id="games">
                                    @if(is_string(old('game')) && old('game') !== '')
                                        <option value="{{ old('game') }}"
                                                selected>{{ \App\Boardgame::where('id', old('game'))->pluck('name')[0] }}</option>
                                    @endif
                                </select>

                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form method="post" action="{{ route('searchUsersRanking') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <div class="input-group">
                                <select required class="js-example-basic-multiple" style="width: 100%" name="users[]"
                                        multiple="multiple" id="users">
                                    @if(is_array(old('users')))
                                        @foreach(old('users') as $users)
                                            <option value="{{ $users }}"
                                                    selected>{{ \App\User::where('id', $users)->pluck('username')[0] }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table style="width:100%" class="table table-striped" id="myTable">

                        <thead>
                        <tr>
                            <th>Globale rang</th>
                            <th>Gebruikersnaam</th>
                            <th><a href="{{ route('rankings') }}">Gewonnen</a></th>
                            <th><a href="{{ route('rankingsbylosses') }}">Verloren</a></th>
                            <th>Winrate</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($rankingsbylosses as $ranking)
                            <tr>
                                <td>{{ $ranking->getRanking() }}</td>
                                <th>
                                    <a href="{{ route('profileView', ['id' => $ranking->id]) }}">{{ $ranking->username }}</a>
                                </th>
                                <td>{{ $ranking->wins }}</td>
                                <td>{{ $ranking->losses }}</td>
                                @if($ranking->wins >= 0 && $ranking->losses >= 1)
                                    @php
                                        $ratio = $ranking->wins + $ranking->losses;
                                        $winrate = 100 / $ratio * $ranking->wins;
                                    @endphp
                                    <td>
                                        {{round($winrate, 0) }}
                                        {{'%'}}
                                    </td>
                                @else
                                    @if($ranking->wins >=1 &&$ranking->losses == 0)
                                        <td>
                                            100%
                                        </td>
                                    @else
                                        <td>
                                            0%
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <center>{{ $rankingsbylosses->links() }}
                </center>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        $(document).ready(function () {
            setTimeout(function () {
                $('#message').addClass('hide');
            }, 4000);
        });

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
                placeholder: "Zoek speler(s)",
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
@endsection