@extends('layouts.Admin.app')
@section('content')
<body id="pages-login" data-layout="empty-view-1" data-background="light">
<div class="bg-dark"></div>
<div class="container-fluid">
<div class="row">
    <div class="col">
        <!-- pages/login -->
        <div class="sample-form-2 login">
            <div class="side-bg-1"></div>
            <div class="side-bg-2 bg-danger"></div>
            <div class="side-description">
                <div class="logo">
                    <i class="sli-compass"></i>
                    <span class="title">JGA</span>
                </div>
                <p>Quasi et incidunt dicta qui aut velit.</p>
                <p>Voluptatem in excepturi aut et nulla.</p>
                <p>Odit nostrum tempora magni et autem necessitatibus.</p>
            </div>
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-description">
                    <h3>Login</h3>
                    <p>Please enter your name and email to login.</p>
                </div>
                <div class="form-group">
                    <label>Your email</label>
                    <div class="input-group">
                        <span class="input-group-addon">
			<i class="sli-envelope"></i>
		</span>
                        <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span> @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>Your password</label>
                    <div class="input-group">
                        <span class="input-group-addon">
			<i class="sli-lock"></i>
		</span>
                        <input id="password" type="password" class="form-control" name="password" required placeholder="Password"> @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span> @endif
                    </div>
                </div>
                <div class="form-group">
                    <label class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">Remember me</span>
    </label>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-rounded" type="submit">Submit</button>
                </div>
                <div class="links">
                    <p>Already have an account?
                        <a href="#">Sign in here</a>
                    </p>
                    <p>New user?
                        <a href="#">Sign up here</a>
                    </p>
                </div>
            </form>

        </div>
        <!-- end pages/login -->
    </div>
</div>
</div>
@endsection