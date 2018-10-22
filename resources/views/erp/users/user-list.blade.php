@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('content')

<style>

#addModal .modal-dialog {
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
					<a href="#" data-target="#addModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add User</a>
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
								<th>Buyer</th>
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
									
<!--									<a href="#" data-target="#editModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil filterbtn options edit-user fa-button" title="Edit" data-placement="left"></i></a>-->
									
									<a href="#" data-target="#deleteModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-trash filterbtn options delete-user fa-button" title="Delete" data-placement="left"></i></a>
									
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

					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('user_name', 'User Name', ['class' => 'form_label']) !!}
							{!! Form::text('user_name',$value = '', array('class' => 'form-control input-sm','id' => 'user_name','placeholder' => 'User Name', 'required')) !!}
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
					<div class="col-md-3">
						<div class="form-group has-feedback">
							{!! Form::label('user_company', 'Select Company', ['class' => 'form_label']) !!}
							{{ Form::select('user_company',$company,'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','id' => 'user_company', 'required', 'multiple')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
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
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				{!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
	/** submit add customer form and check validation **/
    $('#add_user_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         /* call addCustomer function from customer.js*/
		 var data = { name : $('#user_name').val(), 
					  email: $('#user_email').val(), 
					  company: $('#user_company').val(), 
					  buyer: $('#user_buyer').val(),
					 _token: $('meta[name="csrf-token"]').attr('content')
					};
         store('/erp/users', data);
      }
    });
	/* Delete User */
	$(document).on('click', '.delete-user',function () {
		$(document).find('#delete_id').val($(this).closest('tr').attr('id'));
	});
	/* Delete User */
	$(function () {
		$("#user_buyer").on("changed.bs.select", function(e, clickedIndex, newValue) {
			var sVal = $(this).find('option').eq(clickedIndex).val();
			var selectedTxt = $(this).find('option').eq(clickedIndex).text();
			if(newValue){
				getTemplate(selectedTxt.toLowerCase(), sVal);
			}else{
				$('#templateHiecs').find('div#'+sVal).remove();
				if(!$("#user_buyer").val().length){
					$('#templateDiv').addClass('hidden')
				}
			}
		});
	});
	
	
</script>
@endsection