@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{route('erp-users')}}">User Management</a></li>
	<li><a href="#" class="active">Order Management</a></li>
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
				<div class="col" id="admin-orders-div">
					<table id="admin-orders-table" class="table table-condensed table-responsive" style="width:100%">
						<thead>
							<tr>
								<th>#</th>
								<th>ERP ID</th>
								<th>PO Number</th>
								<th>Customer</th>
								<th>Buyer</th>
								<th>PO File</th>
								<th>ERP Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($orders as $value)
							<tr id="{{$value->id}}">
								<td>{{ $loop->iteration }}</td>
								<td>{{$value->erp_id}}</td>
								<td>{{$value->po_no}}</td>
								<td>{{$value->customer}}
								</td>
								<td>{{$value->buyer}}</td>
								<td><a target="_blank" href="{{$value->po_file}}"><i class="fa fa-file" data-toggle="tooltip" title="{!! substr($value->po_file, strrpos($value->po_file, '/') + 1); !!}"></i></a>
								</td>

								@switch($value->status)
								@case("Cancelled")
								<td style="text-align:left !important;color:red;"><i class="fa fa-flag" style="color:red;" aria-hidden="true"></i> &nbsp;&nbsp; {{$value->status}}</td>

								@break
								@default
								<td style="text-align:left !important;color:#4CAF50;"><i class="fa fa-flag" style="color:#4CAF50;" aria-hidden="true"></i> &nbsp;&nbsp; {{$value->status}}</td>

								@break
								@endswitch
								<td style="text-align:left !important;">
									<div class="dropdown">
                                      <span class="dropdown-toggle" data-toggle="dropdown">• • •
                                      </span>
                                      <ul class="dropdown-menu">
                                      	<li>View</li>
                                      	<li>View</li>
                                      	<li>View</li>
                                      </ul>
                                    </div>
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

<div class="modal fade" id="holdModal" tabindex="-1" role="dialog" aria-labelledby="holdModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
				<h4 class="modal-title">Hold Order</h4>
			</div>
			{!! Form::open(['class' => 'form-horizontal', 'url' => 'erp/admin/order', 'method' => 'delete']) !!}
			{!! Form::hidden('id',$value = null, array('class' => 'form-control input-sm', 'id' => 'hold_id')) !!}
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<p style="font-size:20px;">Are you sure want to hold this order ?</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-sm pull-right">Hold Order</button>
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
		$('#admin-orders-table').DataTable();
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
	$(document).on('click', '.holdOrder', function() {
		console.log("hai")
		$('#holdModal').modal('show')
		$(document).find('#hold_id').val($(this).closest('tr').attr('id'));
	});
	/* Get Template Tree */
	$(function() {
		$("#user_buyer").on("changed.bs.select", function(e, clickedIndex, newValue) {
			var sVal = $(this).find('option').eq(clickedIndex).val();
			var selectedTxt = $(this).find('option').eq(clickedIndex).text();
			if (newValue) {
				getTemplate(selectedTxt, sVal);
			} else {
				$('#templateHiecs').find('div#' + sVal).remove();
				if (!$("#user_buyer").val().length) {
					$('#templateDiv').addClass('hidden')
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