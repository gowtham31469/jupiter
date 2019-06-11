@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
	<div class="col-sm-12 col-md-12 main">
		<div class="top-content">
			<div class="buyer-nav-div hidden">
			   <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
			</div>
			{{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
			<div class="clearfix"></div>
		</div>
		<div class="main-content">
			<table id="po-list-table" class="table table-hover table-striped table-bordered table-responsive">
				<thead>
					<tr>
						<th>Sl. No</th>
						<th>ERP ID</th>
						<th>Customer Name</th>
						<th>PO Number</th>
						<th>Partial</th>
						<th>Status</th>
						<th>Created Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($orders as $vals)

					<tr id="{{$vals->id}}">
						<td class="">{{ $loop->iteration }}</td>
						<td>{{$vals->id}}</td>
						<td>{{$vals->company_name}}</td>
						<td>{{$vals->po_no}}</td>
						<td>{{$vals->partial_status == 0 ? "NO":"YES"}}</td>
						@if($vals->status == 2)
						<td><span class="label label-danger">{{$vals->status_name}}</span></td>
						@else
						<td><span class="label label-info">{{$vals->status_name}}</span></td>
						@endif
						<td>{{$vals->po_created_date}}</td>
						<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick="view_order_details('{{ $vals->id }}','{{ $vals->po_no }}','{{ $vals->buyer_id }}','{{ $vals->customer_id }}','{{ $vals->po_file }}')"><i class="fa fa-eye action-button-view"  title="" data-original-title="view Details"></i></a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

	</div>

@endsection @else
<script type="text/javascript">
	window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes') 
<script src="{{ asset('js/Admin/tesco-pi.js') }}"></script>
<script src="{{ asset('js/Admin/parser.js') }}"></script>
<script src="{{ asset('js/Admin/non-artwork.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script>
	//nonArtworkOrders();
</script>
@endsection
