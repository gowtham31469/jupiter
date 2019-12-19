<div class="modal fade bd-example-modal-lg" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog modal-lg" role="document" style="width:95%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">User Profile</h4>
			</div>
			{!! Form::open(['id' => 'profile_form', 'role' => 'form']) !!}
			<div class="modal-body">
				
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-4 customer_design">
							<div class="alert alertProfile fade-in">
							  	
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-md-4">
										{!! Form::label('profile_name', 'User Name : *', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-8">
										{!! Form::text('profile_name',$value = Auth::guard("erpuser")->user()->name, array('class' => 'form-control input-sm', 'placeholder' => 'User Name', 'style' => 'text-transform: capitalize;')) !!}
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-md-4">

										{!! Form::label('profile_email', 'User Email : *', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-8">
										<div class="input-group">
											<span class="input-group-addon input-sm" id="basic-addon1">@</span>
											{!! Form::email('profile_mail',$value = Auth::guard("erpuser")->user()->email, array('class' => 'form-control input-sm ','id' => 'profile_mail', 'placeholder' => 'employee@jga.com')) !!}
										</div>
									</div>
								</div>
							</div>
							<div class="form-group has-feedback">
								<div class="row">
									<div class="col-md-12">

										{!! Form::label('profile_role', 'Roles Assigned : ', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-12">
										<div class="profileRoles text-center">
											<i class="fa fa-spinner fa-pulse"></i>
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-group" style="margin-top:20px">
								<div class="row">
									<div class="col-md-12">
										{!! Form::button('Update Profile', ['class' => 'btn btn-primary btn-sm pull-right', 'type' => 'button', 'id' => 'profileBtn'] ) !!}
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-md-4">

							<div class="customer_design">
								<div class="row">
									<div class="col-md-12">
										{!! Form::label('profile_buyer', 'Buyers Assigned : ', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-12 text-center profileBuyers">
										<i class="fa fa-spinner fa-pulse"></i>
									</div>
								</div>
							</div>
							
							<div class="customer_design">
								<div class="row">
									<div class="col-md-12">
										{!! Form::label('profile_company', 'Companies Assigned : ', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-12 text-center profileCompanies">
										<i class="fa fa-spinner fa-pulse"></i>
									</div>
								</div>
							</div>

							<div class="customer_design">
								<div class="row ">
									<div class="col-md-12">
										{!! Form::label('profile_templates', 'Templates Assigned : ', ['class' => 'form_label']) !!}
									</div>
									<div class="col-md-12 text-center profileTemplates">
										<i class="fa fa-spinner fa-pulse"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							
							<div class="customer_design">
								<div class="alert alertPwd fade-in">
							  	
							</div>
								<div class="form-group has-feedback">
									<div class="row">
										<div class="col-md-5">
											{!! Form::label('old_pwd', 'Old Password : *', ['class' => 'form_label']) !!}
										</div>
										<div class="col-md-7">
											{{ Form::password('old_pwd',array('class' => 'form-control input-sm')) }}
										</div>
									</div>
								</div>
								<div class="form-group has-feedback">
									<div class="row">
										<div class="col-md-5">
											{!! Form::label('new_pwd', 'New Password : *', ['class' => 'form_label']) !!}
										</div>
										<div class="col-md-7">
											{{ Form::password('new_pwd',array('class' => 'form-control input-sm')) }}
										</div>
									</div>
								</div>
								<div class="form-group has-feedback">
									<div class="row">
										<div class="col-md-5">
											{!! Form::label('retype_pwd', 'Confirm Password : *', ['class' => 'form_label']) !!}
										</div>
										<div class="col-md-7">
											{{ Form::password('retype_pwd',array('class' => 'form-control input-sm')) }}
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<div class="row">
										<div class="col-md-12">
											{!! Form::button('Change Password', ['class' => 'btn btn-primary btn-sm pull-right', 'type' => 'button', 'id' => 'pwdBtn'] ) !!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>