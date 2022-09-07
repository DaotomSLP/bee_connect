@extends('testdesign.app')

@section('content')
    <div>
        @include('testdesign.navbar')
        @yield('body')
    </div>

    <script src="{{ URL::asset('/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
@endsection
