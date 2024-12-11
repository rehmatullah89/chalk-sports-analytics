@extends('layouts.web-app')

@section('content')
            <div class="login-pg">
                <div class="card-header1" style="font-size: 26px; border-bottom: #6c757d 1px solid; padding-bottom: 10px;">{{ __('Login') }}</div>

                <div class="card-body1" style="margin-top: 10px;">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row" style="margin-bottom: 5px;">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Email / Username') }}</label>

                            <div class="col-md-8">
                                <input id="username" type="text" placeholder="Email or Username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" style="margin-bottom: 5px;">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <input id="password"  placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" style="margin-bottom: 5px;">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" style="margin-left: 0.5em;" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>&nbsp;

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-outline-primary btn-sm"  style="border-radius: 20px;">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('password.request') }}"  style="border-radius: 20px;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
@endsection
