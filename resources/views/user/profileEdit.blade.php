@extends('layouts.app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    <h2>Welkom op de profiel pagina van: {{$user->name()}}</h2>
                    <a href="{{route('profileView', ['user'=>$user->id])}}" class="btn btn-info">terug naar
                        profielen</a><br><br>
                    <form action="{{ route('profileSave') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Profiel foto kiezen <input type="file" name="image" style="display: none;" multiple>
                    </span>
                        </label>

                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <label>voornaam</label><br>
                        <input class="form-control" value="{{$user->firstname}}" type="text" name="firstname"
                               required><br>
                        <label>tussenvoegsel</label><br>
                        <input class="form-control" value="{{$user->insertion}}" type="text" name="insertion"><br>
                        <label>achternaam</label><br>
                        <input class="form-control" value="{{$user->lastname}}" type="text" name="lastname"
                               required><br>
                        <label>Woonplaats</label><br>
                        <input class="form-control" value="{{$user->city}}" type="text" name="city"><br>
                        <label>Gebruikersnaam</label><br>
                        <input class="form-control" value="{{$user->username}}" type="text" name="username"
                               required><br>
                        <label>E-mail</label><br>
                        <input class="form-control" value="{{$user->email}}" type="email" name="email" required><br><br>
                        <input type="submit" value="Opslaan" class="btn btn-info">
                    </form>

                </div>
            </div>
        </div>


@stop