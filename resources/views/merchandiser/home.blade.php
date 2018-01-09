@extends('layouts.merchandiser.app') @section('content')
	<div class="col-sm-12 col-md-12 main">
	<div class="main-content">
		<table class="table table-striped table-hover table-bordered" id="datatable-example-2">
			<thead>
				<tr>
					<th>UVC</th>
					<th>Artwork</th>
					<th>Supplier Name</th>
					<th>Merchandiser Name</th>
					<th>Season</th>
					<th>Order Date</th>
					<th>Delivery Date</th>
					<th>Status</th>
					<th>Operations</th>
				</tr>
			</thead>
			<tbody>
				 @foreach($orders as $order)
					<tr>
						<td>{{  $order->UVC }}</td>
						<td>{{  $order->Artwork }}</td>
						<td>{{  $order->SupplierName }}</td>
						<td>{{  $order->merchandiser_name }}</td>
						<td>{{  $order->Season }}</td>
						<td>{{  $order->OrderDate }}</td>
						<td>{{  $order->DeliveryDate }}</td>
						@if($order->Status == "Pending")
						<td><span class="label label-Pending">{{  $order->Status }}</span></td>
						@else
						<td><span class="label label-Approved">Approved</span></td>
						@endif
						<td><a href="#"><span><i class="fa fa-eye fa-button" title="view"></i> View</span></a></td>
					</tr>
				 @endforeach

			</tbody>
		</table>
	</div>
@endsection
