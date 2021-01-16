@extends('layouts.app')

@section('content')
<div class="wrapper ">
    @include('leftnav')
    <div class="main-panel">
        @include('navbar')
        @yield('body')
    </div>
</div>
@endsection