@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center><h1>Bordspel</h1></center>
                <legend></legend>
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('AdminBoardgameStore') }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group">
                                <div class="text-center">
                                    <input type="hidden" id="hiddenid" name="hiddenid" value="{{$editgame['id']}}">
                                    <label for="name">Naam bordspel</label>
                                    <input readonly type="text" class="form-control" id="name" name="name"
                                           value="{{$editgame['name']}}"><br>
                                    <label for="img">Afbeelding</label>
                                    <input readonly type="text" class="form-control" id="img"
                                           name="img" value="{{$editgame['image']}}"><br>
                                    <label for="minplayers">Minimum spelers</label>
                                    <input readonly type="number" class="form-control" id="minplayers" name="minplayers"
                                           value="{{$editgame['minplayers']}}"><br>
                                    <label for="maxplayers">Maximum spelers spelers</label>
                                    <input readonly type="number" class="form-control" id="maxplayers" name="maxplayers"
                                           value="{{$editgame['maxplayers']}}"><br>
                                    <label for="playtime">Gemiddelde speeltijd (min)</label>
                                    <input readonly type="number" class="form-control" id="playtime" name="playtime"
                                           value="{{$editgame['playtime']}}"><br>
                                    <label for="yearpublished">Jaar uitgaven</label>
                                    <input readonly type="number" class="form-control" id="yearpublished"
                                           name="yearpublished"
                                           value="{{$editgame['yearpublished']}}"><br>
                                    <label for="isexpansion">Is uitbreiding</label><br>
                                    <input <?php if ($editgame['isexpansion'] == 1) {
                                        echo "checked";
                                    }?> type="checkbox" id="isexpansion" onclick="return false;" class="text-center"
                                           value="1" name="isexpansion"
                                           value="{{$editgame['isexpansion']}}"><br><br>
                                </div>
                                <button id="savechanges" type="submit" class="form-control hidden">Opslaan</button>
                                <button id="edit" type="button" class="form-control" onclick="editAttributes();">
                                    Wijzigen
                                </button>
                                <center><a href="{{route('AdminBoardgame')}}" style="color:green"><span id="back"
                                                                                                        class="form-control">Terug naar bordspellen</span></a>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


<script>

    function editAttributes() {
        console.log('ddd');
        document.getElementById('name').removeAttribute('readonly');
        document.getElementById('img').removeAttribute('readonly');
        document.getElementById('minplayers').removeAttribute('readonly');
        document.getElementById('maxplayers').removeAttribute('readonly');
        document.getElementById('playtime').removeAttribute('readonly');
        document.getElementById('yearpublished').removeAttribute('readonly');

        $("#isexpansion").attr('onclick', 'null');
        $('#image').removeClass('hidden');
        $('#savechanges').removeClass('hidden');
        $('#edit').addClass('hidden');
        $('#back').addClass('hidden');
    }

    $(document).ready(function () {
    });
</script>
