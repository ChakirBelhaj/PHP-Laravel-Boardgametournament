@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <center><h1>Registreer</h1></center>
                <legend></legend>
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    @include('auth._form-block', ['type'=> ['name'=> 'Voornaam',            'field'=>'firstname',              'type'=> 'text', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Tussenvoegsels',      'field'=>'insertion',              'type'=> 'text', 'req'=>0]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Achternaam',          'field'=>'lastname',               'type'=> 'text', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Woonplaats',          'field'=>'city',                   'type'=> 'text', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Gebruikersnaam',      'field'=>'username',               'type'=> 'text', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Email',               'field'=>'email',                  'type'=> 'email', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Wachtwoord',          'field'=>'password',               'type'=> 'password', 'req'=>1]])
                    @include('auth._form-block', ['type'=> ['name'=> 'Wachtwoord Herhalen', 'field'=>'password_confirmation',  'type'=> 'password', 'req'=>1]])

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Registreer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
