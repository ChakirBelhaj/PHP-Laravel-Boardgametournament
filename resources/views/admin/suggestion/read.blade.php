@extends('layouts.app')

@section('content')

    <div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <center><h1>Spel voorstellen</h1></center>
                <legend></legend>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Naam spel</th>
                            <th>Beschrijving</th>
                            <th>Opties</th>
                        </tr>

                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $item->boardgame_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <form action="{{ route('suggestionDecline', ['id' => $item->id]) }}"
                                          method="post">
                                        <a href="{{ route('suggestionAccept', ['id' => $item->id]) }}"
                                           class="btn btn-success">Accepteren</a>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-danger">Afwijzen</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection