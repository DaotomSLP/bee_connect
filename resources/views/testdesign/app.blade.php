<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- <!--     Fonts and icons     --> --}}
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

    <link href="{{ URL::asset('/css/app.css') }}" rel="stylesheet" />

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="{{ URL::asset('/bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">

    {{-- navbar --}}
    <link rel="stylesheet" href="{{ URL::asset('/navbar/css/style.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('/css/design.css') }}">

    <meta name="csrf-token" content="{{ Session::token() }}">

</head>

<body class="bg-gray">
    @yield('content')
</body>



</html>
