@section('order-detail-modal')
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="detailsModalLabel">Order Details</h4>
			</div>
			<div class="modal-body">
				<div>
					<!-- Nav tabs -->
					<ul class="oDetailsNav nav nav-tabs text-center" role="tablist">
						<li role="presentation" class="active col-md-6"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">Order Overview</a></li>
						<li role="presentation" class="col-md-6"><a href="#oDetails" aria-controls="oDetails" role="tab" data-toggle="tab">Order Details</a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="overview">
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-6 customer_design">
										<!--		ERP ID of Order			-->
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													{{ Form::label('erp_id', 'ERP ID : *', ['class' => 'form_label']) }}
												</div>
												<div class="col-md-8">
													{{ Form::text('erp_id',$value = $order->erp_id, array('class' => 'form-control input-sm', 'readonly')) }}
												</div>
											</div>
										</div>
										<!--		PO Number of Order			-->
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													{{ Form::label('po_no', 'PO Number : *', ['class' => 'form_label']) }}
												</div>
												<div class="col-md-8">
													{{ Form::text('po_no',$value = $order->po_no, array('class' => 'form-control input-sm', 'readonly')) }}
												</div>
											</div>
										</div>
										<!--		Customer Name		-->
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													{{ Form::label('customer', 'Customer : *', ['class' => 'form_label']) }}
												</div>
												<div class="col-md-8">
													{{ Form::text('customer',$value = $order->customer, array('class' => 'form-control input-sm', 'readonly')) }}
												</div>
											</div>
										</div>
										<!--		Buyer Name		-->
										<div class="form-group">
											<div class="row">
												<div class="col-md-4">
													{{ Form::label('buyer', 'Buyer : *', ['class' => 'form_label']) }}
												</div>
												<div class="col-md-8">
													{{ Form::text('buyer',$value = $order->buyer, array('class' => 'form-control input-sm', 'readonly')) }}
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="customer_design">
											<!--	ERP Status of Order		-->
											<div class="form-group">
												<div class="row">
													<div class="col-md-4">
														{{ Form::label('erp_status', 'ERP Status : *', ['class' => 'form_label']) }}
													</div>
													<div class="col-md-8 text-center">
														@if(is_null($order->deleted_at))
														@switch($order->status)
														@case("Cancelled")
														<span class="label label-danger">{{$order->status}}</span>

														@break
														@default
														<span class="label label-success">{{$order->status}}</span>

														@break
														@endswitch
														@else
														<span class="label label-warning"> HOLD </span>
														@endif
													</div>
												</div>
											</div>

										</div>
										<!--		PO File			-->
										<div class="customer_design">
											<div class="form-group">
												<div class="row">
													<div class="col-md-4">
														{{ Form::label('po_file', 'PO File : *', ['class' => 'form_label']) }}
													</div>
													<div class="col-md-8 text-center">
														<a target="_blank" href="{{$order->po_file}}"><i class="fa fa-5x fa-file-pdf-o" data-toggle="tooltip" title="{!! substr($order->po_file, strrpos($order->po_file, '/') + 1); !!}"></i></a>
													</div>
												</div>

											</div>
											<div class="form-group">

												<div class="row">
													<div class="col-md-offset-4 col-md-8 text-center">
														<label>{!! substr($order->po_file, strrpos($order->po_file, '/') + 1); !!}</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="oDetails">
							<div class="row">
								<div class="col-md-12">
									<table id="order-items-table" class="table table-condensed table-bordered table-responsive" style="width:100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Style Number</th>
												<th>Reference</th>
												<th>OCN Number</th>
												<th>Company</th>
												<th>Quantity Ordered</th>
												<th>Quantity Delivered</th>
												<th>Pending Quantity</th>
												<th>Order Item Status</th>
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($details as $detail)
											<tr>
												<td>{{$loop->iteration}}</td>
												<td>{{$detail->style_no}}</td>
												<td>{{$detail->reference_name}}</td>
												<td>{{$detail->ocn_number}}
												</td>
												<td>{{$detail->company}}</td>
												<td>{{$detail->order_quantity}}</td>
												<td>{{$detail->delivered_quantity}}</td>
												<td>{{$detail->pending_orders}}</td>
												
												@if(is_null($detail->deleted_at))

												@switch($detail->status)
												@case("Cancelled")
												<td><span class="label label-danger">{{$detail->status}}</span></td>
												<td></td>

												@break
												@default
												<td><span class="label label-success">{{$detail->status}}</span></td>
												<td>

													<i id="{{$detail->id}}" data-toggle="confirmation" data-placement="left" data-popout="true" data-holdordername="{{$detail->status}}" data-singleton="true" class="fa fa-pencil filterbtn options updateOItem fa-button activeItems"></i>

												</td>

												@break
												@endswitch
												@else
												<td> <span class="label label-warning"> HOLD </span></td>
												<td>

													<i id="{{$detail->id}}" data-toggle="confirmation" data-holdordername="{{$detail->status}}" data-placement="left" data-popout="true" data-singleton="true" class="fa fa-pencil filterbtn options updateOItem fa-button holdItems"></i>

												</td>
												
												@endif

											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Back to Order List</button>
					@if(is_null($order->deleted_at ))

					@switch($order->status)
					@case("Cancelled")
					<!--	Do Nothing		-->

					@break
					@case("Hold")

					@break
					@default
					<button type="button" class="btn btn-warning hOrder" data-id="{{$order->id}}" data-status="Hold">Hold Order</button>
					<button type="button" class="btn btn-danger cOrder" data-id="{{$order->id}}" data-status="Cancel">Cancel Order</button>
					@break
					@endswitch
					@else
					<button type="button" class="btn btn-warning rOrder" data-id="{{$order->id}}" data-status="Restore">Restore Order</button>
					<button type="button" class="btn btn-danger cOrder" data-id="{{$order->id}}" data-status="Cancel">Cancel Order</button>
					@endif
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	@endsection
