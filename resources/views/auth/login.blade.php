@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-8">
            <div class="card mt-5 p-lg-5 p-sm-2">

                <p class="h2 text-center font-weight-bold">
                    {{ __('Login') }}
                </p>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12 form-group has-feedback">
                                <input type="email" id="email"
                                    class="form-control has-feedback-left @error('email') is-invalid @enderror"
                                    placeholder="First Name" name="email" value="{{ old('email') }}" required
                                    autocomplete="email" autofocus>
                                <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 form-group has-feedback">
                                <input type="password"
                                    class="form-control has-feedback-left @error('password') is-invalid @enderror"
                                    placeholder="Password" name="password" required autocomplete="current-password">
                                <span class="fa fa-lock form-control-feedback left" aria-hidden="true"></span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <!-- <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> -->

                                    <!-- <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label> -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection