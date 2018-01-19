@extends('layouts.merchandiser.app')
@section('content')
<div class="container-fluid wallpaper" style="height:100vh">
        <div class="row">
            <div class="login-section">
                <div class="container">
                    <div class="login-card card-container">
                        <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
                        <img id="profile-img" class="profile-img-card" src="{{ asset('images/logo.png') }}" />
                    
                       <form class="form-group" method="POST" action="{{ url('/merchandiser') }}">
                            {{ csrf_field() }}
                            @if ($errors->has('email'))
                            <span class="help-block" style="color:red">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                            @endif
                            @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                            <span id="reauth-email" class="reauth-email"></span>
                            <input id="email" type="email" class="form-control" placeholder="Email address" required autofocus name="email" value="{{ old('email') }}">
                           <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                            <div id="remember" class="checkbox">
                                <label>
                       <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                    </label>
                            </div>
                            <button class="btn btn-sm btn-primary btn-block " type="submit">Sign in</button>
                        </form>
                        <!-- /form -->
                        <a href="#" class="forgot-password">
                Forgot the password?
            </a>
                    </div>
                    <!-- /card-container -->
                </div>
                <p class="text-center terms">By clicking on SIGNIN you will be agreeing all the terms and conditions.</p>
                <!-- /container -->
            </div>
        </div>
    </div>
@endsection