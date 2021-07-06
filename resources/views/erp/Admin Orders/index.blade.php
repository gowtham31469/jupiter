@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{route('erp-users')}}">User Management</a></li>
	<li><a href="{{route('erp.admin.orders.show')}}" class="active">Order Management</a></li>  
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
					<table id="admin-orders-table" class="table table-condensed table-responsive table-bordered" style="width:100%">
						<thead>
							<tr>
								<th style="width: 1px;"></th>

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
								@if(is_null($value->deleted_at))
								@switch($value->status)
								@case("Cancelled")
								<td style="color:red;"><i class="fa fa-flag" style="color:red;" aria-hidden="true"></i></td>

								@break
								@default
								<td><i class="fa fa-flag" style="color:#4CAF50;" aria-hidden="true"></i></td>

								@break
								@endswitch
								@else
								<td style="color:red;"><i class="fa fa-flag" aria-hidden="true"></i></td>
								@endif
								
								<td>{{$value->erp_id}}</td>
								<td>{{$value->po_no}}</td>
								<td>{{$value->customer}}
								</td>
								<td>{{$value->buyer}}</td>
								<td><a target="_blank" href="{{$value->po_file}}"><i class="fa fa-file-pdf-o" data-toggle="tooltip" title="{!! substr($value->po_file, strrpos($value->po_file, '/') + 1); !!}"></i></a>
								</td>
								@if(is_null($value->deleted_at))
								@switch($value->status)
								@case("Cancelled")
								<td> <span class="label label-danger"> {{$value->status}} </span> </td>

								@break
								@default
								<td> <span class="label label-success"> {{$value->status}} </span> </td>

								@break
								@endswitch
								@else
								<td> <span class="label label-warning"> HOLD </span></td>
								@endif
								<td>
									<span class="label label-primary getODetails">View Order</span>
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

<div id="appendModal"></div>

@endsection
@else
<script type="text/javascript">
	window.location = "{{ url('/erp') }}";

</script>
@endif

@section('jqueryIncludes')
<script src="{{ asset('js/Erp/bootstrap-tooltip.js') }}"></script>
<script src="{{ asset('js/Erp/bootstrap-confirmation.js') }}"></script>
<script src="{{ asset('js/Erp/holdOrCancelOrder.js') }}"></script>
@endsection