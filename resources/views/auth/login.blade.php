@extends('layouts.auth.app')
@section('title','Login')
@section('css')
    {{-- customer css --}}
@endsection
@section('main_content')
<div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div><a class="logo" href=""><img class="img-fluid for-light"
                                    src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage"><img class="img-fluid for-dark"
                                    src="{{ asset('assets/images/logo/logo_dark.png') }}" alt="looginpage"></a></div>
                        <div class="login-main">
                            <form class="theme-form"  method="POST" action="{{ route('login_attempt') }}">
                             @csrf
                                <h4>Sign in to account</h4>
                                <p>Enter your email & password to login</p>

                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input id="email" type="email"  class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" autofocus placeholder="test@gmail.com">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  required autocomplete="current-password"
                                            placeholder="*********">
                                        <div class="show-hide"><span class="show"> </span></div>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Remember Me -->

                                {{-- <div class="form-check ">
                                    <input class="form-check-input" id="gridCheck1" name="remember" type="checkbox">
                                    <label class="form-check-label" for="gridCheck1">Check me out</label>
                                </div> --}}
                                <div class="form-group mb-0">
                                    <div class="form-check">
                                        <div class="block mt-4">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                    </label>
                                </div>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                                    @endif
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-block w-100 mt-3 spinner-btn" type="submit">Sign in</button>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
