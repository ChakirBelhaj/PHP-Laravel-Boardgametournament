@extends('layouts.app')

@section('content')

    <div>
        <div class="row">


            <div class="col-md-8 col-md-offset-2">
            <center><h1>Spel voorstel</h1></center><legend></legend>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('suggestionSave') }}" method="post">
                {{ csrf_field() }}
                Welk spel wil je graag toegevoegd zien?
                <input class="form-control" type="text" name="boardgame_name"
                       placeholder="Naam van het spel" required>
                <br>
                Waarom wil je nou precies dit spel gaan spelen?
                <textarea class="form-control" name="description" placeholder="Beschrijving"
                          required></textarea>
                <br>
                <button class="btn btn-primary" type="submit">Verzenden</button>
            </form>
        </div>
    </div>
        
    </div>


@endsection