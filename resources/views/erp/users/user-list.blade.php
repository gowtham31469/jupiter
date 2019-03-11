@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())

@section('navbar') 

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
    <li><a href="{{route('erp-users')}}" class="active">User Management</a></li>
    <li><a href="{{route('erp.admin.orders.show')}}">Order Management</a></li>
</ul>

@endsection

@section('content')

<style>

	#addModal .modal-dialog, #editModal .modal-dialog {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}
	
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-md-12 main">
			<div class="top-content">
			</div>
			<div class="main-sub-menu">
				<div class="action-group pull-right sub-menu-button">
					<a href="#" data-target="#addModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add User</a>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="main-content">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif @if (session('message'))
				<div class="alert alert-success">
					{{ session('message') }}
				</div>
				@endif
				<div class="loader">
					<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
				</div>

				<div class="col" id="user-table">
					<table id="user-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Email</th>
								<th>Company</th>
								<th>Buyers</th>
								<th>Roles</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($users as $value)
							<tr id="{{$value->id}}">
								<td>{{ $loop->iteration }}</td>
								<td>{{$value->name}}</td>
								<td>{{$value->email}}</td>
								<td>
									{{implode(', ', array_pluck($value->company, 'name'))}}
								</td>
								<td>
									{{implode(', ', array_pluck($value->buyer, 'name'))}}
								</td>
								<td>
									{{implode(', ', array_pluck($value->roles, 'name'))}}
								</td>
								<td>

									@if(!$value->is_admin)
									<a href="#" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil filterbtn options edit-user fa-button" title="Edit" data-placement="left"></i></a>
									<a href="#" data-target="#deleteModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-trash filterbtn options delete-user fa-button" title="Delete" data-placement="left"></i></a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{-- Show snackbar html code --}}
	<div id="snackbar"><span class="snackbar_span"></span></div>
</div>


<div class="modal fade bd-example-modal-lg" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="addModalLabel">Add User</h4>
			</div>
			{!! Form::open(['id' => 'add_user_form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
			<div class="modal-body">
				<div id="user_result"></div>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('user_name', 'User Name', ['class' => 'form_label']) !!}
							{!! Form::text('user_name',$value = '', array('class' => 'form-control input-sm','id' => 'user_name','placeholder' => 'User Name', 'required', 'style' => 'text-transform: capitalize;')) !!}
							<div class="help-block with-errors"></div>

						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('user_email', 'User Email', ['class' => 'form_label']) !!}
							<div class="input-group">
								<span class="input-group-addon input-sm" id="basic-addon1">@</span>
								{!! Form::email('user_email',$value = '', array('class' => 'form-control input-sm ','id' => 'user_email', 'placeholder' => 'employee@jga.com', 'required', 'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
							</div>
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('user_roles', 'User Role', ['class' => 'form_label']) !!}
							{{ Form::select('user_roles', $roles, '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%', 'id' => 'user_roles', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('user_company', 'Select Company', ['class' => 'form_label']) !!}
							{{ Form::select('user_company', $company,'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','id' => 'user_company', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('user_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
							{{ Form::select('user_buyer', $buyers, '',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%', 'id' => 'user_buyer', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>

				</div>
				<div class="row hidden" id="templateDiv" style="margin-top: 20px;">
					<div class="form-group has-feedback col-md-12 text-center">
						{!! Form::label('templates', 'Template Mapping', ['class' => 'form_label']) !!}
					</div>
					<div class="col-md-offset-1 col-md-10" id="templateHiecs"></div>
				</div>

			</div>
			<div class="modal-footer">
				{!! Form::label('error_label', 'Select Templates *', ['class' => 'form_label hidden error_label', 'style' => 'color:red;']) !!}
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				{!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
<!--Edit ERP User-->
<div class="modal fade bd-example-modal-lg" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="editModalLabel">Edit User</h4>
			</div>
			{!! Form::open(['id' => 'edit_user_form', 'data-toggle' => 'validator', 'role' => 'form']) !!}

			<input type="hidden" name="hiddenid" id="hiddenId">

			<div class="modal-body">
				<div id="user_result"></div>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('edit_user_name', 'User Name', ['class' => 'form_label']) !!}
							{!! Form::text('edit_user_name', $value = '', array('class' => 'form-control input-sm','id' => 'edit_user_name','placeholder' => 'User Name', 'required', 'style' => 'text-transform: capitalize;')) !!}
							<div class="help-block with-errors"></div>

						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('edit_user_email', 'User Email', ['class' => 'form_label']) !!}
							<div class="input-group">
								<span class="input-group-addon input-sm" id="basic-addon1">@</span>
								{!! Form::email('edit_user_email',$value = '', array('class' => 'form-control input-sm ', 'id' => 'edit_user_email', 'placeholder' => 'employee@jga.com', 'required', 'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
							</div>
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('edit_user_roles', 'User Role', ['class' => 'form_label']) !!}

							{{ Form::select('edit_user_roles',$roles,'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','multiple' =>'true', 'id' => 'edit_user_roles', 'required')) }}

							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('edit_user_company', 'Select Company', ['class' => 'form_label']) !!}
							{{ Form::select('edit_user_company',$company,'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','id' => 'edit_user_company', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							{!! Form::label('edit_user_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
							{{ Form::select('edit_user_buyer', $buyers, '',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%', 'id' => 'edit_user_buyer', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>

				</div>
				<div class="row hidden" id="editTemplateDiv" style="margin-top: 20px;">
					<div class="form-group has-feedback col-md-12 text-center">
						{!! Form::label('edit_templates', 'Template Mapping', ['class' => 'form_label']) !!}
					</div>
					<div class="col-md-offset-1 col-md-10" id="editTH"></div>
				</div>

			</div>
			<div class="modal-footer">
				{!! Form::label('edit_error_label', 'Select Templates *', ['class' => 'form_label hidden edit_error_label', 'style' => 'color:red;']) !!}
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				{!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
				<h4 class="modal-title">Delete User</h4>
			</div>
			{!! Form::open(['class' => 'form-horizontal', 'url' => 'erp/users', 'method' => 'delete']) !!}
			{!! Form::hidden('id',$value = null, array('class' => 'form-control input-sm', 'id' => 'delete_id')) !!}
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<p style="font-size:20px;">Are you sure want to delete ?</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-sm pull-right" id="dlt-user">Delete</button>
					</div>
				</div>
			</div>
			{!!Form::close() !!}
		</div>
	</div>
</div>
@endsection
@else
<script type="text/javascript">
	window.location = "{{ url('/erp') }}";

</script>
@endif

@section('jqueryIncludes')
<script src="{{ asset('js/Erp/template.js') }}"></script>
<script>
	$(document).ready(function() {
		$('#edit_user_roles').val('0').selectpicker('refresh');
		$('#user-list-table').DataTable();
	})
	/** submit add customer form and check validation **/
	$('#add_user_form').validator().on('submit', function(e) {
		if (!e.isDefaultPrevented()) {
			e.preventDefault();
			/* Form Data Manipulation */
			var templates = [];
			/* Get value of templates from tree */
			$('#user_buyer').find('option:selected').each(function() {
				$this = $(this);
				var treeTxt = $this.text();
				var treeId = treeTxt + "_tree";

				$.each($('#' + treeId).jstree().get_selected(true), function() {
					templates.push($(this)[0].id + "_" + treeTxt);
				});
			});
			/* Formatted Form Data */
			var data = {
				name: $('#user_name').val(),
				email: $('#user_email').val(),
				company: $('#user_company').val(),
				roles: $('#user_roles').val(),
				buyer: $('#user_buyer').val(),
				templates: templates,
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			if (templates.length) {
				/* Pass to AJAX request */
				store('/erp/users', data);
			} else {
				$('.error_label').removeClass('hidden')
			}
		}
	});
	/** submit add customer form and check validation **/
	$('#edit_user_form').validator().on('submit', function(e) {
		if (!e.isDefaultPrevented()) {
			e.preventDefault();
			/* Form Data Manipulation */
			var templates = [];
			/* Get value of templates from tree */
			$('#edit_user_buyer').find('option:selected').each(function() {
				$this = $(this);
				var treeTxt = $this.text();
				var treeId = treeTxt + "_tree";

				$.each($('#' + treeId).jstree().get_selected(true), function() {
					templates.push($(this)[0].id + "_" + treeTxt);
				});
			});
			/* Formatted Form Data */
			var data = {
				id: $('#hiddenId').val(),
				name: $('#edit_user_name').val(),
				email: $('#edit_user_email').val(),
				company: $('#edit_user_company').val(),
				roles: $('#edit_user_roles').val(),
				buyer: $('#edit_user_buyer').val(),
				templates: templates,
				_token: $('meta[name="csrf-token"]').attr('content')
			};

			if (templates.length) {
				/* Pass to AJAX request */
				update('/erp/users', data);
			} else {
				$('.edit_error_label').removeClass('hidden')
			}
		}
	});
	/* Delete User */
	$(document).on('click', '.delete-user', function() {
		$(document).find('#delete_id').val($(this).closest('tr').attr('id'));
	});
	/* Get Template Tree */
	$(function() {
		$("#user_buyer").on("changed.bs.select", function(e, clickedIndex, newValue) {
			var sVal = $(this).find('option').eq(clickedIndex).val();
			var selectedTxt = $(this).find('option').eq(clickedIndex).text();
			if (newValue) {
				getTemplate(selectedTxt, sVal);
			} else {
				console.log(sVal)
				$('#templateHiecs').find('div#' + sVal).remove();
				if (!$("#user_buyer").val().length) {
					$('#templateDiv').addClass('hidden')
				}
			}
		});
		
		$("#edit_user_buyer").on("changed.bs.select", function(e, clickedI, newVal) {
			var sVal = $(this).find('option').eq(clickedI).val();
			var selectedTxt = $(this).find('option').eq(clickedI).text();
			if (newVal) {
				getEditTemplate(selectedTxt, sVal);
			} else {
				console.log(selectedTxt)
				$('#editTH').find('div#' + selectedTxt.toUpperCase()).remove();
				if (!$("#edit_user_buyer").val().length) {
					$('#editTemplateDiv').addClass('hidden')
				}
			}
		});
	});
	/* Get User Details */
	$(function() {
		$(document).on("click", ".edit-user", function() {

			$('#editTemplateDiv').addClass('hidden');
			$('#editTH').html('')
			var id = $(this).closest('tr').attr('id')
			$('#hiddenId').val(id)
			$.ajax({
				url: base_url + "/erp/get-user",
				type: "get",
				data: {
					id: id
				},
				beforeSend: function() {
					$(".loader").show();
				},
				success: function(data) {
					var data = data.data[0];
					var roles = data.roles.map(a => a.id);
					var buyer = data.buyer.map(a => a.id);
					var company = data.company.map(a => a.id);
					$('#edit_user_roles').selectpicker('val', roles);
					$('#edit_user_name').val(data.name);
					$('#edit_user_email').val(data.email);
					$('#edit_user_company').selectpicker('val', company);
					$('#edit_user_buyer').selectpicker('val', buyer);
					$('#edit_user_form').validator('validate');
					/* Rendering Templates */
					if (Object.values(data.templates).length) {
						/*	Append Jstree Arrays  */
						$.each(Object.values(data.templates), function(index, value) {
							var head = Object.keys(data.templates)[index].toUpperCase();
							$('#editTH').append(`<div class="col-md-6 treeDiv" id="` + head + `" style="box-shadow: 0px 1px 5px 1px #eee;padding:10px 0px;">
								<p style="font-weight: bold;" class="text-center">` + head + `</p>
	
								<div id="` + Object.keys(data.templates)[index] + `_tree"> </div>
							</div>`);

							$('#' + Object.keys(data.templates)[index] + '_tree').jstree({
								'core': {
									'data': JSON.parse(value),
									"themes": {
										"icons": false
									}
								},
								"plugins": ["checkbox"]
							});
						});
						/*	User specific Jstree Data  */
						setTimeout(function() {
							$.each(data.user_template, function(index, value) {
								var $tree = $(document).find('#editTH').find('#' + index + '_tree');
								for (var i = 0; i < value.length; i++) {
									$tree.jstree(true).select_node(value[i])
								}
							});
						}, 100);
						$('#editTemplateDiv').removeClass('hidden');
					}

				},
				complete: function() {
					$(".loader").hide();
					$('#editModal').modal('show');
				}
			});
		});
	});

</script>
@endsection
