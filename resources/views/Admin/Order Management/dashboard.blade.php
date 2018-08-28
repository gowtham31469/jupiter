@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
	.washing_select+.select2,
	.instruction_select+.select2,
	.parts_select+.select2,
	.cleaning_select+.select2,
	.ironing_select+.select2,
	.dryer_select+.select2,
	.whitening_select+.select2 {
		width: 100% !important;
		height: 50px !important;
	}
	
	.washing_select+.select2-container .select2-selection--single,
	.instruction_select+.select2-container .select2-selection--single,
	.parts_select+.select2-container .select2-selection--single,
	.cleaning_select+.select2-container .select2-selection--single,
	.ironing_select+.select2-container .select2-selection--single,
	.dryer_select+.select2-container .select2-selection--single,
	.whitening_select+.select2-container .select2-selection--single {
		height: 34px;
	}
	
	.washing_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.cleaning_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.ironing_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.instruction_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.parts_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.dryer_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow,
	.whitening_select+.select2.select2-container--default .select2-selection--single .select2-selection__arrow {
		top: 10px;
		right: 10px;
	}
	
	.washing_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
	.cleaning_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
	.ironing_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
	.instruction_select+.select2.select2-container--default .select2-selection--multiple .select2-selection__rendered,
	.parts_select+.select2.select2-container--default .select2-selection--multiple .select2-selection__rendered,
	.dryer_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered,
	.whitening_select+.select2.select2-container--default .select2-selection--single .select2-selection__rendered {
		color: #444;
		line-height: 45px;
	}
	
	.washing_select+.select2-container .select2-selection--single,
	.cleaning_select+.select2-container .select2-selection--single,
	.ironing_select+.select2-container .select2-selection--single,
	.instruction_select+select2-container .select2-selection--multiple,
	.parts_select+select2-container .select2-selection--multiple,
	.dryer_select+.select2-container .select2-selection--single,
	.whitening_select+.select2-container .select2-selection--single {
		height: 50px;
	}
	
	.washing_select+.select2-container--default .select2-selection--single,
	.cleaning_select+.select2-container--default .select2-selection--single,
	.ironing_select+.select2-container--default .select2-selection--single,
	.instruction_select+.select2-container--default .select2-selection--multiple,
	.parts_select+.select2-container--default .select2-selection--multiple,
	.dryer_select+.select2-container--default .select2-selection--single,
	.whitening_select+.select2-container--default .select2-selection--single {
		border: 2px solid #dce4ec;
	}

</style>
<div class="col-sm-12 col-md-12 main">
	<div class="top-content">
		<!--
        <ol class="breadcrumb">
            <li><a href="/admin/dashboard">Dashboard</a></li>
            <li class="active">Order Management</li>
        </ol>
        <form class="navbar-form pull-right" style="margin-top:0px;">
            <label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label>
            <select class="js-example-responsive buyer-select">
                  <option></option>
                    @foreach($buyers as $buyer)
                    <option value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
        </form>

        <h3><i class="ion-ios-browsers-outline"></i>Order Management</h3>-->
		<div class="main-sub-menu hidden">
			<div class="action-group pull-right">
				<!-- <a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a> -->
				<a href="#" id="uploadModal" data-target="#uploadData" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-upload"></i>Upload Data</a>
			</div>
			<ul class="sub-nav-list">
				<li class="dashboard sub_modules"><a href=""><i class="ion-grid"></i>Dashboard</a></li>
				<li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li>
				<li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li>
			</ul>
			<hr/>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="main-content">

		<div class="loader">
			<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<h4>New orders</h4>
				<span class="numbers new_order_count"><?php echo $new_orders; ?></span><i onclick="new_orders_modal()" class="fa fa-arrow-right" aria-hidden="true" style="cursor:pointer;float: right;margin-top: 50px;color: #717171;font-size: 18px;"></i>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<h4>Approved orders</h4>
				<span class="numbers app_pipo_count"><?php echo $approved_orders; ?></span>
				
				<div class="dropdown">
					<i style="cursor: pointer;float: right; color: #717171;font-size: 18px;"class="fa fa-arrow-right dropdown-toggle" data-toggle="dropdown" aria-hidden="true"></i>
					  <ul class="dropdown-menu pull-right" style="margin-top: 21px;right: 10px;">
						<li><a href="#"  onclick="aowp_modal()">Approved orders without PI/PO</a></li>
						<li><a href="#"  onclick="app_orders_modal()">Approved orders</a></li>
					  </ul>
					</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<h4>Pending orders</h4>
				<span class="numbers pen_order_count"><?php echo $pending_orders; ?></span><i onclick="pen_orders_modal()" class="fa fa-arrow-right" aria-hidden="true" style="cursor:pointer;float: right;margin-top: 50px;color: #717171;font-size: 18px;"></i>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<h4 class="text-center">Pending For Production</h4>
				<form id="frm-example" action="#" method="POST">
				<div class="table-responsive" id="penproductiondiv">
					<table class="table pending_table table-striped text-center">

						<thead>
							<tr>
								 <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
								<th class="text-center">Customer Name</th>
								<th class="text-center">Buyer</th>
								<th class="text-center">Style No</th>
								<th class="text-center">Reference No</th>
								<th class="text-center">Zone</th>
								<th class="text-center">Actions</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($pendingproduction['tcl1'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>TCL 1</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_tcl1_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['tcl2'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>TCL 2</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_tcl2_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['tcl4'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>TCL 4</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_tcl4_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['tsl1'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>TSL 1</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_tsl1_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['tsl3'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>TSL 3</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_tsl3_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['thl1'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>THL 1</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_thl1_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
							@foreach ($pendingproduction['thl3'] as $value)
							<tr>
								<td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-{{ $value->id }}" class="checkbox"></td>
								<td>{{ $value->company_name }}</td>
								<td>Tesco</td>
								@if($value->zone == "CE")
									<td>{{ $value->ce_style_ref }}</td>
								@else
									<td>{{ $value->uk_style_ref }}</td>
								@endif
								
								<td>THL 3</td>
								<td>{{ $value->zone }}</td>
								<td><label onclick="viewed({{ $value->id }},'tesco_thl3_orders')" style="cursor:pointer" class="label label-warning">Mark as viewed</label></td>
							</tr>
							@endforeach
						</tbody>

					</table>
				</div>
				</form>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<h4 class="text-center">New Enquiries</h4>
				<div class="table-responsive">
					<table class="table table-striped text-center">

						<thead>
							<tr>
								<th class="text-center">Style No</th>
								<th class="text-center">Reference No</th>
								<th class="text-center">Zone</th>
							</tr>
						</thead>

						<tbody>
							
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="age_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header alert-warning">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p class="modal-title" id="myModalLabel">SUPPLIER IS SOLELY RESPONSIBLE TO CHOOSE CORRECT AGE GROUP. IN CASE OF ANY WRONG SELECTION, <strong>ORCHESTRA/JUPITER</strong> ARE NOT LAIBLE FOR CONSEQUENCES THERE OFF.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="modal needed_modal fade" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header alert-danger">
					<button type="button" class="close modal_close1" data-dismiss="modal" aria-hidden="true">&times;</button>
					<p>Use this column only if needed</p>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="new_orders_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">New Orders</h4>
				</div>
				<form id="frm-example-new" action="#" method="POST">
				<div class="modal-body">
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="aowp_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Approved orders without PI/PO
</h4>
				</div>
				<form id="frm-example-aowp" action="#" method="POST">
				<div class="modal-body">
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="app_orders_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Approved orders</h4>
				</div>
				<form id="frm-example-app" action="#" method="POST">
				<div class="modal-body">
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="pen_orders_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog" style="width:70%">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Pending orders</h4>
				</div>
				<form id="frm-example-pen" action="#" method="POST">
				<div class="modal-body">
				</div>
				</form>
			</div>
		</div>
	</div>
	@endsection @else
	<script type="text/javascript">
		window.location = "{{ url('/admin') }}";

	</script>
	@endif @section('jqueryIncludes')
	<script>
		var table = 	$('.pending_table').DataTable({
                            dom: 'Bfrtip',
                            
                            buttons: [
                                'excelHtml5',
								 {
									text: 'Mark as viewed',
									action: function ( e, dt, node, config ) {
										pendingproductionsubmit()
									}
								}
                            ],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
                        });
		
		function pendingproductionsubmit()
		{
			var myCheckboxes = new Array();
										$("input:checked").each(function() {
											myCheckboxes.push($(this).val());
										});
										$.ajax({
											url: base_url + "/admin/order-management/viewed_pen_production_all",
											type: "Post",
											dataType: "json",
											data:{
												id:myCheckboxes,
												_token : $('meta[name="csrf-token"]').attr('content')
											},
											beforeSend: function() {

											},
											success: function(data) {
											   var html="";
												$(data.pendingproduction.tcl1).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												});
												$(data.pendingproduction.tcl2).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 2</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												});  
												$(data.pendingproduction.tcl4).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 4</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												}); 
												$(data.pendingproduction.tsl1).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												});  
												$(data.pendingproduction.tsl3).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 3</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												});  
												$(data.pendingproduction.thl1).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												}); 
												$(data.pendingproduction.thl3).each(function(index, value)
												{
													if(value.zone == "CE")
													{
														var style_no = value.ce_style_ref;
													}
													else
													{
														var style_no = value.uk_style_ref;
													}	
													html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 3</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
												});     

												$('#penproductiondiv').html('<table class="table pending_table table-striped text-center"><thead><tr><th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th><th class="text-center">Customer Name</th><th class="text-center">Buyer</th><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Actions</th></tr></thead><tbody>'+html+'</tbody></table>');  

												  var table = $('.pending_table').DataTable({
													dom: 'Bfrtip',

													buttons: [
														'excelHtml5',
														 {
															text: 'Mark as viewed',
															action: function ( e, dt, node, config ) {
																pendingproductionsubmit()
															}
														}
													],
													'columnDefs': [{
														 'targets': 0,
														 'searchable':false,
														 'orderable':false,
														 'className': 'dt-body-center'
													 }],
													 'order': [1, 'asc']
												});

													// Handle click on "Select all" control
													$('#example-select-all').on('click', function(){
													  // Check/uncheck all checkboxes in the table
													  var rows = table.rows({ 'search': 'applied' }).nodes();
													  $('input[type="checkbox"]', rows).prop('checked', this.checked);
													});

												 $("#penproductiondiv input[type=search]").addClass('form-control');
												$('.dataTables_length select').addClass('form-control');
												$("#penproductiondiv input[type=search]").attr('placeholder', 'Data Search');
											},
											error: function(data) {

											},
											complete: function() {
												$(".loader").hide();
												$(".loader").css('margin-top', '0%');
											}
										})
		}
	   // Handle click on "Select all" control
	   $('#example-select-all').on('click', function(){
		  // Check/uncheck all checkboxes in the table
		  var rows = table.rows({ 'search': 'applied' }).nodes();
		  $('input[type="checkbox"]', rows).prop('checked', this.checked);
	   }); 
		
	
		function neworderssubmit()
		{
			var myCheckboxes = new Array();
				$("input:checked").each(function() {
					myCheckboxes.push($(this).val());
				});
				$.ajax({
					url: base_url + "/admin/order-management/viewedneworders_all",
					type: "Post",
					dataType: "json",
					data:{
						id:myCheckboxes,
						_token : $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
							
					},
					success: function(data) {
					  $('.new_order_count').text(data.tot_count)
					  newordersclass(data);
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		function aowpsubmit()
		{
			var myCheckboxes = new Array();
				$("input:checked").each(function() {
					myCheckboxes.push($(this).val());
				});
				$.ajax({
					url: base_url + "/admin/order-management/viewedaowp_all",
					type: "Post",
					dataType: "json",
					data:{
						id:myCheckboxes,
						_token : $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
							
					},
					success: function(data) {
					  aowpclass(data);
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		function approvedorderssubmit()
		{
			var myCheckboxes = new Array();
				$("input:checked").each(function() {
					myCheckboxes.push($(this).val());
				});
				$.ajax({
					url: base_url + "/admin/order-management/viewedapporders_all",
					type: "Post",
					dataType: "json",
					data:{
						id:myCheckboxes,
						_token : $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
							
					},
					success: function(data) {
						$('.app_pipo_count').text(data.tot_count)
						approvedclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		
		function pendingorderssubmit()
		{
			var myCheckboxes = new Array();
				$("input:checked").each(function() {
					myCheckboxes.push($(this).val());
				});
				$.ajax({
					url: base_url + "/admin/order-management/viewedpenorders_all",
					type: "Post",
					dataType: "json",
					data:{
						id:myCheckboxes,
						_token : $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() {
							
					},
					success: function(data) {
						$('.pen_order_count').text(data.pending_count)
						pendingclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
	    $("#penproductiondiv input[type=search]").addClass('form-control');
	    $('.dataTables_length select').addClass('form-control');
	    $("#penproductiondiv input[type=search]").attr('placeholder', 'Data Search');
		function new_orders_modal() {
			
			
				var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/new_orders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token
					},
					beforeSend: function() {

					},
					success: function(data) {
						newordersclass(data);
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		

		function aowp_modal() {
			
			
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/aowp",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token
					},
					beforeSend: function() {

					},
					success: function(data) {
						aowpclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}

		function app_orders_modal() {
			
			
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/approved_orders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token
					},
					beforeSend: function() {

					},
					success: function(data) {
						approvedclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}

		function pen_orders_modal() {
			
			
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/pending_orders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token
					},
					beforeSend: function() {

					},
					success: function(data) {
						pendingclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		$('.logo').html('Orders')
		$('.buyer-select').change(function() {
			if ($(this).val() != "") {

				var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/order_sub_modules",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						buyer: $(this).find("option:selected").text().toLowerCase()
					},
					beforeSend: function() {

					},
					success: function(data) {
						//                         $('.sub-nav-list').html('<li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li>')
						//                         $.each(data, function (index, value) {
						//                            $('.sub-nav-list').append('<li class="'+value.module_name.toLowerCase().replace(" ", "_")+' sub_modules"><a href="#" onclick="'+value.url_path+'"><i class="'+value.image+'"></i>'+value.module_name+'</a></li>')
						//                        });
						//                         $('.main-sub-menu').removeClass('hidden');

						//                        if(data[0].module_name == "Label Orders")
						//                            {
						//                                label_orders();
						//                            }
						//                         if(data[0].module_name == "Sticker Orders")
						//                             {
						sticker_orders();
						//}                        
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
			} else {
				location.reload()
			}
		});
		
		function viewed(id,table_name)
		{
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/viewed_pen_production",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						table_name: table_name,
						id: id
					},
					beforeSend: function() {

					},
					success: function(data) {
					   var html="";
						$(data.pendingproduction.tcl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.pendingproduction.tcl2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 2</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pendingproduction.tcl4).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 4</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.pendingproduction.tsl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pendingproduction.tsl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 3</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pendingproduction.thl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 1</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.pendingproduction.thl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 3</td><td>'+value.zone+'</td><td><label onclick=viewed('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});     
						
						$('#penproductiondiv').html('<table class="table pending_table table-striped text-center"><thead><tr><th><input name="select_all" value="1" id="example-select-all" type="checkbox" /><th class="text-center">Customer Name</th><th class="text-center">Buyer</th></th><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Actions</th></tr></thead><tbody>'+html+'</tbody></table>');  
						
						   var table = $('.pending_table').DataTable({
                            dom: 'Bfrtip',
                            
                            buttons: [
                                'excelHtml5',
								 {
									text: 'Mark as viewed',
									action: function ( e, dt, node, config ) {
										pendingproductionsubmit()
									}
								}
                            ],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
                        });
						
							// Handle click on "Select all" control
							$('#example-select-all').on('click', function(){
							  // Check/uncheck all checkboxes in the table
							  var rows = table.rows({ 'search': 'applied' }).nodes();
							  $('input[type="checkbox"]', rows).prop('checked', this.checked);
							});
						
						$("#penproductiondiv input[type=search]").addClass('form-control');
						$('.dataTables_length select').addClass('form-control');
						$("#penproductiondiv input[type=search]").attr('placeholder', 'Data Search');
						
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		function viewedneworders(id,table_name)
		{
			
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/viewedneworders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						table_name: table_name,
						id: id
					},
					beforeSend: function() {

					},
					success: function(data) {
						var count = parseInt($('.new_order_count').text()) - 1;
						$('.new_order_count').text(count)
						newordersclass(data);
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		function viewedaowp(id,table_name)
		{
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/viewedaowp",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						table_name: table_name,
						id: id
					},
					beforeSend: function() {

					},
					success: function(data) {
						var count = parseInt($('.app_pipo_count').text()) - 1;
						$('.app_pipo_count').text(count)
						aowpclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		function viewedapporders(id,table_name)
		{
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/viewedapporders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						table_name: table_name,
						id: id
					},
					beforeSend: function() {

					},
					success: function(data) {
						var count = parseInt($('.app_pipo_count').text()) - 1;
						$('.app_pipo_count').text(count)
						approvedclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		function viewedpenorders(id,table_name)
		{
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
				$.ajax({
					url: base_url + "/admin/order-management/viewedpenorders",
					type: "Post",
					dataType: "json",
					data: {
						_token: csrf_token,
						table_name: table_name,
						id: id
					},
					beforeSend: function() {

					},
					success: function(data) {
						var count = parseInt($('.pen_order_count').text()) - 1;
						$('.pen_order_count').text(count)
						pendingclass(data)
					},
					error: function(data) {

					},
					complete: function() {
						$(".loader").hide();
						$(".loader").css('margin-top', '0%');
					}
				})
		}
		
		
		
		function newordersclass(data)
		{
			
						
						var html="";
						$(data.new_orders.tcl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 1</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.new_orders.tcl2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 2</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.new_orders.tcl4).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TCL 4</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.new_orders.tsl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 1</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.new_orders.tsl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>TSL 3</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.new_orders.thl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 1</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.new_orders.thl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							if(value.delivery_date != null && value.delivery_date!= "")
							{
								var delivery_date = value.delivery_date;
							}
							else
							{
								var delivery_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>THL 3</td><td>'+value.zone+'</td><td>'+value.sum+'</td><td>'+delivery_date+'</td><td><label onclick=viewedneworders('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$('#new_orders_modal .modal-body').html('<table class="table new_orders"><thead><tr><th><input name="select_all" value="1" id="example-select-all-new" type="checkbox" /></th><th class="text-center">Order Date</th><th class="text-center">Customer Name</th><th class="text-center">Buyer</th><th>Style No</th><th>Reference No</th><th>Zone</th><th class="text-center">Quantity</th><th class="text-center">Delivery Date</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
						$('#new_orders_modal').modal("show")
						   var table = $('.new_orders').DataTable({
								dom: 'Bfrtip',

								buttons: [
									'excelHtml5',
									 {
										text: 'Mark as viewed',
										action: function ( e, dt, node, config ) {
											neworderssubmit()
										}
									}
								],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
							});
			
							// Handle click on "Select all" control
							$('#example-select-all-new').on('click', function(){
							  // Check/uncheck all checkboxes in the table
							  var rows = table.rows({ 'search': 'applied' }).nodes();
							  $('input[type="checkbox"]', rows).prop('checked', this.checked);
							});
			
						  $(".modal-body input[type=search]").addClass('form-control');
						  $('.dataTables_length select').addClass('form-control');
						  $(".modal-body input[type=search]").attr('placeholder', 'Data Search');
		}
		function aowpclass(data)
		{
			
					    var html="";
						$(data.app_pipo.tcl1_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.app_pipo.tcl1_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.app_pipo.tcl2_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 2</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.app_pipo.tcl2_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 2</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.app_pipo.tcl4_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 4</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.app_pipo.tcl4_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 4</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.app_pipo.tsl1_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.app_pipo.tsl1_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.app_pipo.tsl3_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.app_pipo.tsl3_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.app_pipo.thl1_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.app_pipo.thl1_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.app_pipo.thl3_1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.app_pipo.thl3_2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td><label onclick=viewedaowp('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$('#aowp_modal .modal-body').html('<table class="table aowp_orders"><thead><tr><th><input name="select_all" value="1" id="example-select-all-aowp" type="checkbox" /></th><th class="text-center">Customer Name</th><th class="text-center">Buyer</th><th>Style No</th><th>Product</th><th>Reference No</th><th>Zone</th><th>A/W Date</th><th>A/W Approved Date</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');  
						$('#aowp_modal').modal("show")
						   var table = $('.aowp_orders').DataTable({
								dom: 'Bfrtip',

								buttons: [
									'excelHtml5',
									 {
										text: 'Mark as viewed',
										action: function ( e, dt, node, config ) {
											aowpsubmit()
										}
									}
								],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
							});
			
							// Handle click on "Select all" control
							$('#example-select-all-aowp').on('click', function(){
							  // Check/uncheck all checkboxes in the table
							  var rows = table.rows({ 'search': 'applied' }).nodes();
							  $('input[type="checkbox"]', rows).prop('checked', this.checked);
							});
			
						  $(".modal-body input[type=search]").addClass('form-control');
						  $('.dataTables_length select').addClass('form-control');
						  $(".modal-body input[type=search]").attr('placeholder', 'Data Search');
		}
		function approvedclass(data)
		{
			
					    var html="";
						$(data.approved_orders.tcl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.approved_orders.tcl2).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 2</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.approved_orders.tcl4).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 4</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.approved_orders.tsl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.approved_orders.tsl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.approved_orders.thl1).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.approved_orders.thl3).each(function(index, value)
						{
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							if(value.order_approved_at != null)
							{
								var approved_date = value.order_approved_at.split(' ')[0].split('-').reverse().join('-');
							}
							else
							{
								var approved_date = "-";
							}
							
							if(value.purchase_order_no != null && value.purchase_order_no!= "")
							{
								var pono = value.purchase_order_no;
							}
							else
							{
								var pono = "-";
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+approved_date+'</td><td>'+pono+'</td><td><label onclick=viewedapporders('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$('#app_orders_modal .modal-body').html('<table class="table approved_orders"><thead><tr><th><input name="select_all" value="1" id="example-select-all-app" type="checkbox" /></th><th class="text-center">Customer Name</th><th class="text-center">Buyer</th><th>Style No</th><th>Product</th><th>Reference No</th><th>Zone</th><th>Order Date</th><th>Approval Date</th><th>PO Number</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');  
						$('#app_orders_modal').modal("show")
						     var table = $('.approved_orders').DataTable({
								dom: 'Bfrtip',

								buttons: [
									'excelHtml5',
									 {
										text: 'Mark as viewed',
										action: function ( e, dt, node, config ) {
											approvedorderssubmit()
										}
									}
								],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
							});
			
							// Handle click on "Select all" control
							$('#example-select-all-app').on('click', function(){
							  // Check/uncheck all checkboxes in the table
							  var rows = table.rows({ 'search': 'applied' }).nodes();
							  $('input[type="checkbox"]', rows).prop('checked', this.checked);
							});
			
						  $(".modal-body input[type=search]").addClass('form-control');
						  $('.dataTables_length select').addClass('form-control');
						  $(".modal-body input[type=search]").attr('placeholder', 'Data Search');
		}
		function pendingclass(data)
		{
			
					    var html="";
						$(data.pending_orders.tcl1).each(function(index, value)
						{
							
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_tcl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});
						$(data.pending_orders.tcl2).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl2_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 2</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_tcl2_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pending_orders.tcl4).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tcl4_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TCL 4</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_tcl4_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.pending_orders.tsl1).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_tsl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pending_orders.tsl3).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_tsl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>TSL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_tsl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$(data.pending_orders.thl1).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl1_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 1</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_thl1_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						}); 
						$(data.pending_orders.thl3).each(function(index, value)
						{
							var date1 = new Date();
							var date2 = new Date(value.created_at.split(' ')[0]);
							var timeDiff = Math.abs(date2.getTime() - date1.getTime());
							var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
							if(value.zone == "CE")
							{
								var style_no = value.ce_style_ref;
							}
							else
							{
								var style_no = value.uk_style_ref;
							}	
							html += '<tr><td><input type="checkbox" name="checkbox" value="tesco_thl3_orders-'+value.id+'" class="checkbox"></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>THL 3</td><td>'+value.zone+'</td><td>'+value.created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedpenorders('+value.id+',"tesco_thl3_orders") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
						});  
						$('#pen_orders_modal .modal-body').html('<table class="table pending_orders"><thead><tr><th><input name="select_all" value="1" id="example-select-all-pen" type="checkbox" /></th><th class="text-center">Customer Name</th><th class="text-center">Buyer</th><th>Style No</th><th>Product</th><th>Reference No</th><th>Zone</th><th>A/W Date</th><th>Pending Days</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');  
						$('#pen_orders_modal').modal("show")   
			
			
						    var table = $('.pending_orders').DataTable({
								dom: 'Bfrtip',

								buttons: [
									'excelHtml5',
									 {
										text: 'Mark as viewed',
										action: function ( e, dt, node, config ) {
											pendingorderssubmit()
										}
									}
								],
							'columnDefs': [{
								 'targets': 0,
								 'searchable':false,
								 'orderable':false,
								 'className': 'dt-body-center'
							 }],
							 'order': [1, 'asc']
							});
			
							// Handle click on "Select all" control
							$('#example-select-all-pen').on('click', function(){
							  // Check/uncheck all checkboxes in the table
							  var rows = table.rows({ 'search': 'applied' }).nodes();
							  $('input[type="checkbox"]', rows).prop('checked', this.checked);
							});
			
						  $(".modal-body input[type=search]").addClass('form-control');
						  $('.dataTables_length select').addClass('form-control');
						  $(".modal-body input[type=search]").attr('placeholder', 'Data Search');
		}

	</script>
	<script src="{{ asset('js/dropzone.js') }}"></script>
	<script type="text/javascript">
		Dropzone.options.imageUpload = {
			maxFilesize: 10,
			acceptedFiles: ".csv",
			".xls"
		};
		$('.modal').on('hidden.bs.modal', function(e) {
			$('#uploadMultiData').trigger("reset");
			$('#uploadData').trigger('reset');
		})

	</script>
	@endsection
