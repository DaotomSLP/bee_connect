@extends('testdesign.app')

@section('content')
    <div>
        @include('testdesign.navbar')
        @yield('body')
    </div>
    
    <script src="{{ URL::asset('/navbar/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('/navbar/js/popper.js') }}"></script>
    <script src="{{ URL::asset('/navbar/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('/navbar/js/main.js') }}"></script>
    
    <!-- /top navigation -->
    <script src="{{ URL::asset('/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
@endsection
