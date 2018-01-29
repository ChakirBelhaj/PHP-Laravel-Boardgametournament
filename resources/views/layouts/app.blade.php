<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  {{--//datatables css--}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">

    @include('layouts._message')
    @include('layouts._header')

    <div class="headerimg" style="background-image: url('{{ URL::asset('images/dice2.jpg') }}'); min-width: 100%;">
        <div class="big" style="height: 5em;"> </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 rounded maincontainer">

                @yield('content')

            </div>
            <div class="col-md-1">
            </div>
        </div>

        @include('layouts._footer')

    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
      $(".alert").fadeTo(7000, 500).slideUp(500, function () {
        $(".alert").alert('close');
      });
    </script>
    {{--datatables script--}}
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
@yield('script')
</body>
</html>
