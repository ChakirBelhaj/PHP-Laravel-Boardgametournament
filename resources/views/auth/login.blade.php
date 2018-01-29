@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <center><h1>Login</h1></center>
            <legend></legend>
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                @include('auth._form-block', ['type'=>['name' => 'gebruikersnaam', 'field'=> 'username', 'type'=>'text', 'req'=>1]])
                @include('auth._form-block', ['type'=>['name' => 'Wachtwoord', 'field'=> 'password', 'type'=>'password', 'req'=>1]])

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Onthoud
                                mijn
                                gegevens
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Inloggen
                        </button>

                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Wachtwoord vergeten
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
