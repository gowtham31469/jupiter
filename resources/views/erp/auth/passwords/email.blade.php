@extends('layouts.erp.app')

<!-- Main Content -->
@section('content')
<div class="container-fluid wallpaper" style="height:100vh">
	<div class="row">
		<div class="login-section">
			<div class="container">
				<div class="login-card card-container">
					@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/erp/password/email') }}">
						{{ csrf_field() }}

						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="col-md-12 text-center" style="font-size: 17px;">Reset Password</label>

							<div class="col-md-12" style="margin-top: 15px;">
								<input id="email" type="email" class="form-control" name="email" placeholder="Enter Email Address" value="{{ old('email') }}">

								@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12 text-center">
								<button type="submit" class="btn btn-primary">
									Send Password Reset Link
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