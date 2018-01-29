@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <center><h1>Bordspellen</h1><a href="{{ route('AdminBoardgameCreate') }}"
                                               class="btn btn-default btn-sm">Voeg toe</a>
                    <legend>
                        <br></legend>
                </center>
                <form method="post" action="{{ route('searchBoardgameRankingsAdmin') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4 col-md-offset-8">
                            <div class="input-group">
                                <select required class="js-example-basic-multiple" style="width: 100%" name="game[]"
                                        multiple="multiple" id="games">
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
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped searchTable">
                        <thead>
                        <th>Naam</th>
                        <th>Minimum spelers</th>
                        <th>Maximum spelers</th>
                        <th>Speeltijd</th>
                        <th>Is uitbreiding</th>
                        <th>Jaar uitgegeven</th>
                        <th>Bewerken</th>
                        </thead>
                        <tbody>
                        @foreach($boardgames as $game)
                            <tr>
                                <td>{{$game['name']}}</td>
                                <td>{{$game['minplayers']}}</td>
                                <td>{{$game['maxplayers']}}</td>
                                <td>{{$game['playtime']}}</td>
                                @if(isset($game['isexpansion']) && $game['isexpansion'] != null)
                                    <td>ja</td>
                                @else
                                    <td>nee</td>
                                @endif
                                <td>{{$game['yearpublished']}}</td>
                                <td>
                                    <a href="{{ route('AdminBoardgameEdit', ['boardgame'=>$game->id]) }}">
                                        <i class="fa fa-pencil text-primary"></i>
                                    </a>
                                    <form id="deleteform{{$game['id']}}"
                                          action="{{ route('AdminBoardgameDelete') }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" value="{{$game['id']}}" id="hiddenid"
                                               name="hiddenid">
                                        <i class="fa fa-times text-danger" id="confirm"
                                           onclick='deleteitem({{$game['id']}},"{{$game['name']}}" );'
                                           aria-hidden="true"></i>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <center>{{ $boardgames->links() }}</center>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        function deleteitem(id, name) {
            if (confirm("Weet u zeker dat u " + name + " wilt verwijderen?")) {
                document.getElementById('deleteform' + id).submit();
            }
            else {
                e.preventDefault();
            }
        }

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
        });
    </script>
@endsection