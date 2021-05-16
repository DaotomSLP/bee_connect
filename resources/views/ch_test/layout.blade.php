@extends('layouts.app')

@section('content')
    <div class="wrapper ">
        @include('leftnav')
        @include('navbar')
        @yield('body')
    </div>
@endsection
