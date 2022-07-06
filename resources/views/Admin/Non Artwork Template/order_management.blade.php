@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) 
<style type="text/css">
	.table{max-width: unset!important;
		width: 150%!important;}
/*.dropdown-menu open{z-index: 100!important;}*/
</style>
@section('content')

<div class="col-sm-12 col-md-12 main">
	<div class="top-content">
		<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;">
			<h4><strong>Create Order For Non Artwork Template</strong></h4>
		</div>
	</div>
	<div class="main-content">

		<div class="alert alert-danger print-error-msg" style="display:none">
			<ul></ul>
		</div>

		<div class="alert alert-success print-success-msg" style="display:none">
			<ul></ul>
		</div>

		<!-- <form name="non_artwork_orders" id="non_artwork_orders">   -->

			{!! Form::open(['id' => 'non_artwork_orders','data-toggle' => 'validator','role' => 'form']) !!}
			<div class="row">
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="order_">Order ID</label>
								<div class="input-group tesco_manufacture_date date">
									<input type="text" class="form-control" name="order_id" id="order_id" placeholder="Enter Order ID" maxlength="7">
									<div class="input-group-addon" id="getOrderDetails">
										<span class="glyphicon glyphicon-search"> </span >
									</div >
								</div >
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="buyer">Buyer</label>
								{!! Form::select('buyer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Buyer",'id'=>'buyer']) !!}
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="current_date">Current Date</label>
								<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
									<input value="" id="current_date" name="current_date" type="text" class="form-control" required="" placeholder="Current Date">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								</div >
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="due_date">Due Date</label>
								<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
									<input value="" id="due_date" name="due_date" type="text" class="form-control" required="" placeholder="Select Due Date">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								</div >
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="customer_name">Customer Name</label>
								{!! Form::select('customer',$customers, null, ['class' => ' selectpicker','id'=>'customer','data-live-search'=>"true",'title'=>"Select Customer Name"]) !!}
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<label for="tesco_po_number">PO Number</label>
								<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number"  maxlength="7">
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>

			<div class="row">
				<div class="col-md-12">                                            
					<hr>                                            
					<h4>Order Quantity</h4>                                            
					<p>                                                
						<button type="button" id="add_single_row" class="btn btn-default add-row-btn clone"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>

						<button type="button" id="clone_selected"  class="btn btn-default add-row-btn"><i class="fa fa-clone" aria-hidden="true"></i> Clone Selected</button>

						<button type="button" id="delete_selected" class="btn btn-default add-row-btn"><i class="fa fa-trash" aria-hidden="true"></i> Delete Selected</button>

						<button type="submit" id="save_data" class="btn btn-default add-row-btn submit_data" data-flag="1" data-action="save"><i class="fa fa-save" aria-hidden="true"></i> Save</button>

						<!-- <button type="button" id="new_order" class="btn btn-default add-row-btn submit_data hidden" data-flag="3"><i class="fa fa-save" aria-hidden="true"></i> Save as new order</button> -->
					</p>                                        
				</div>
			</div>

			<div class="row clearfix">
				<div class="col-md-12" >
					<table class="table table-bordered table-hover" id="tab_logic">
						<thead>
							<tr>
								<th class="text-center">Sl.No</th>
								<th class="text-center">Jc No.</th>
								<th class="text-center">OCN</th>
								<th class="text-center">B.Po. No.</th>
								<th class="text-center">Style/UVC</th>
								<th class="text-center">Item</th>
								<th class="text-center">Reference</th>
								<th class="text-center">Color</th>
								<th class="text-center">Composition</th>
								<th class="text-center">A/W. Status</th>
								<th class="text-center">A/W.Appr.Dt</th>
								<th class="text-center">Size</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">UOM</th>
								<th class="text-center">Rate</th>
								<th class="text-center">Value</th>
							</tr>
						</thead>
						<tbody id="dynamic_field">
							<tr id='addr0' class="clonable">
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck1">
										<label class="custom-control-label">&nbsp;1</label>
									</div>
								</td>
								<td>
									<div class="form-group has-feedback label-ean-feedback">
										<p>32123414</p>
									</div>
								</td>
								<td width="150">
									<input type="number" name="ocn[]"  class="form-control input-sm label-ean-field" placeholder="OCN Number">
								</td>
								<td width="150">
									<input type="number" name="bpo[]" class="form-control input-sm label-ean-field" placeholder="BPO Number">
								</td>
								<td width="150">
									<input type="text" name="style[]" class="form-control input-sm label-ean-field" placeholder="Style/UVC">
								</td>	
								<td width="100">
									{!! Form::select('item[]', $select_item_data, null, ['class' => 'selectpicker item_select form-control input-group', 'data-live-search' => 'true', 'title' =>'Select item']) !!}
								</td>
								<td width="150" class="reference_td">
									<select data-live-search="true" name="reference[]" class="selectpicker reference_select" title="Select reference"></select>
								</td>
								<td width="150">
									<input type="text" name="color[]" class="form-control input-sm label-ean-field" placeholder="Color">
								</td>
								<td width="150">
									<input type="text" name="composition[]" class="form-control input-sm label-ean-field" placeholder="Composition">
								</td>
								<td width="150">
									<select class="selectpicker aw_status" name="A_W_Status">
										<option value="0">Not Approved</option>
										<option value="1" selected>Approved</option>
									</select>
								</td>
								<td width="170" class="aw_td">
									<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="" name="approve_date[]" type="text" class="form-control aw_date" placeholder="Select Due Date">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"> </span >
										</div >
									</div >
								</td>
								<td width="150">
									{!! Form::select('size[]', $sizes, null, ['class' => 'form-control input-sm label-ean-field']) !!}
								</td>
								<td width="150" class="quantity_td">
									<input type="number" name="quantity[]" class="form-control input-sm label-ean-field quantity" placeholder="Quantity">
								</td>
								<td width="150">
									{!! Form::select('uom[]', $uom, null, ['class' => 'form-control input-sm label-ean-field']) !!}
								</td>
<!-- <td width="150"> 
{!! Form::select('rate[]', $buyers, null, ['class' => 'form-control input-sm label-ean-field rate']) !!}
</td> -->
<td width="150" class="rate_td">
	<input type="number" name="rate[]" class="form-control input-sm label-ean-field" placeholder="Value" readonly>
</td>
<td width="150">
	<input type="number" name="rate_value[]" class="form-control input-sm label-ean-field" placeholder="Value">
</td>
</tr>
<tr id='addr1' class="clonable"></tr>
</tbody>
</table>
</div>
</div>

<div class="row clearfix">
	<div class="pull-right col-md-4">
		<table class="table table-bordered table-hover" id="tab_logic_total">
			<tbody>
				<tr>
					<th class="text-center">Total Quantity</th>
					<td class="text-center"><input type="number" name='sub_total' placeholder='0.00' class="form-control" id="sub_total" readonly/></td>
				</tr>
				<tr>
					<th class="text-center">Tax</th>
					<td class="text-center"><div class="input-group mb-2 mb-sm-0">
						<input type="number" class="form-control" id="tax" placeholder="0">
						<div class="input-group-addon">%</div>
					</div></td>
				</tr>
				<tr>
					<th class="text-center">Tax Amount</th>
					<td class="text-center"><input type="number" name='tax_amount' id="tax_amount" placeholder='0.00' class="form-control" readonly/></td>
				</tr>
				<tr>
					<th class="text-center">Total Quantity</th>
					<td class="text-center"><input type="number" name='total_amount' id="total_amount" placeholder='0.00' class="form-control" readonly/></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

{{Form::close()}}
<!-- </form>   -->
</div>
</div>
</div>
@endsection
@else
<script type="text/javascript">
	window.location = "{{ url('/admin') }}";
</script>
@endif

@section('jqueryIncludes')

<script type="text/javascript">
	$(document).ready(function()
	{   
		$('.datepicker').datepicker({
			format: 'dd-mm-yyyy'
		}); 


		var getDetailsURL = "<?php echo url('admin/get-non-artwork-order-details'); ?>";
		var row_to_duplicate = $('#tab_logic').find(' tbody tr:eq(0)').html()
		var i=1;  


		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$('#add_single_row').click(function()
		{  
			$('#tab_logic').append('<tr id="addr'+(i+1)+'" class="clonable"></tr>');
			$('#addr'+i).html(row_to_duplicate).find('td:first-child').html('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck'+(i+1)+'"><label class="custom-control-label">&nbsp;&nbsp;'+(i+1)+'</label></div>')
			i++;
			$(".selectpicker").selectpicker('refresh')

		});   

		$(document).on("click","#clone_selected",function()
		{
			var getSelectedRows = $(".clonable input:checked").parents("tr").clone();
			$("#dynamic_field").find('tr:last').prev().after(getSelectedRows)

			$('#tab_logic tbody tr').each(function(key,val)
			{		
				$(this).attr('id','addr'+key).find("td:eq(0)").html('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck'+1+'"><label class="custom-control-label">&nbsp;&nbsp;'+(key+1)+'</label></div>')
			})
			i= $('#tab_logic tr').length-2;
		})


		$(document).on("click","#delete_selected",function()
		{
			$('input[type=checkbox]:checked').each(function () 
			{
				if($(this).parents("tr").attr('id')!="addr0")
				{
					$(this).parents("tr").remove()
				}
				else
				{
					$('.snackbar_span').html('System will not delete the first row !!!');
					showSnackbar();
				}
			});

			$('#tab_logic tbody tr').each(function(key,val)
			{	
				$(this).attr('id','addr'+key).find("td:eq(0)").html('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input select_row_to_clone" id="customCheck1"><label class="custom-control-label" for="customCheck1">&nbsp;&nbsp;'+(key+1)+'</label></div>')
			})
			i= $('#tab_logic tr').length-2;
		})


		$(document).on("change","#buyer",function(){	

			$.ajax({
				method:'POST',
				type:'json',
				url:"{{ route('get-item-nat') }}",
				data: { 'buyer_id' : $("#buyer").val() },
				success:function(data)
				{
					$("#item_td").html('')
					$("#item_td").html(data.items)

					$(".selectpicker").selectpicker('refresh')
				}
			});
		});


		$(document).on("change",".aw_status",function(e)
		{	
			if($('.aw_status option:selected').val() == 0)
			{
				$(this).closest('tr').find('td.aw_td').css("pointer-events",'none')
				$(this).closest('tr').find('td.aw_td .aw_date').val('')
			}
			else
			{
				$(this).closest('tr').find('td.aw_td').css("pointer-events",'auto')
			}
		});

		$(document).on("changed.bs.select",".item_select",function(e){	
			e.stopImmediatePropagation()
			$target = $(this).closest('td').next()
			item_id = $(this).val()
			$.ajax({
				method:'POST',
				type:'json',
				url:"{{ route('get-reference-nat') }}",
				data: { 'buyer_id' : $("#buyer").val() ,'item_id' : item_id },
				success:function(data)
				{
					$target.html('')
					$target.html(data.reference)
					$(".selectpicker").selectpicker('refresh')
				}
			});
		});

		$(document).on("changed.bs.select",".reference_select",function(e)
			{	e.stopImmediatePropagation()

				if($(this).closest('tr').find('td.quantity_td input[type=number]').val() > 0)
				{
					calculate_rate($(this).val(),$(this).closest('tr').find('td.quantity_td input[type=number]').val(),$(this).closest('tr').find('td.rate_td input[type=number]'))	
				}

			});

		$(document).on("keyup change",".quantity",function()
		{	
			if($(this).val() > 0)
			{
				calculate_rate($(this).closest('tr').find('td.reference_td .selectpicker option:selected').val(),$(this).val(),$(this).closest('tr').find('td.rate_td input[type=number]'))
			}

		});
// total=0;
// 

// $('.quantity').each(function() {
// 	total += parseInt($(this).val());
// });
// $("#sub_total").val(total)
// $("#total_amount").val(total)
// calculate_rate()
// console.log($(this))




function calculate_rate(ref,qty,target)
{
	$.ajax({
		method:'POST',
		type:'json',
		url:"{{ route('calculate-nat-rate') }}",
		data: { 'buyer_id' : $("#buyer").val() ,'template_id' : ref ,'customer_id' : $("#customer").val(),'quantity' : qty},
		success:function(data)
		{
			target.val(data.rate)
		}
	});
}


$('#non_artwork_orders').validator().on('submit', function (e) {
	if (e.isDefaultPrevented()) {

	} else {
		e.preventDefault();
		submitNonArtworkOrder();
	}
});

function submitNonArtworkOrder()
{
	if($("#save_data").attr('data-action') == "save")
	{
		postURL = "<?php echo url('admin/save-non-artwork'); ?>"
		action ="save"
	}
	else
	{
		postURL = "<?php echo url('admin/save-non-artwork'); ?>"
		action ="update"
	}
	order_id = $("#order_id").val()
	$.ajax({  
		url:postURL,  
		method:"POST",  
		data:$('#non_artwork_orders').serialize()+ '&action=' + action+ '&order_id=' + order_id,
		type:'json',
		success:function(data)  
		{
			if(data.error){
				printErrorMsg(data.error);
			}else{
				i=1;
				$('.dynamic-added').remove();
				$('#non_artwork_orders')[0].reset();
				$("#tab_logic").find("tr:gt(1)").remove();
				$(".print-success-msg").find("ul").html('');
				$(".print-error-msg").css('display','none');
				$(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
				$('.print-success-msg').css('display','block').delay(1000).fadeOut('slow/400/fast', function() {
					location.reload(true);
				});
			}
		}  
	});  
}


function printErrorMsg (msg) {
	$(".print-error-msg").find("ul").html('');
	$(".print-error-msg").css('display','block').delay(1000).fadeOut('slow/1000/fast', function() {
				});
	$(".print-success-msg").css('display','none');
	$.each( msg, function( key, value ) {
		$(".print-error-msg").find("ul").append('<li>'+value+'</li>');
	});
}

$("#getOrderDetails").on('click', function () 
{

	$("#save_data").html('<i class="fa fa-save" aria-hidden="true"></i> Update')
	$("#save_data").attr('data-action', 'update');
// $("#new_order").removeClass('hidden')

$.ajax({
	type:'POST',
	url:getDetailsURL,
	data:{id:$("#order_id").val()},
	success:function(data)
	{
		$('select[name=buyer]').val(data.row['buyer_id']);
		$('select[name=customer]').val(data.row['tesco_supplier_id']);
		$("#tesco_po_number").val(data.row['purchase_order_no'])
		$("#dynamic_field").find("tr:gt(0)").remove();
		$("#dynamic_field").html('')
		$("#dynamic_field").html(data.td)
		$('.selectpicker').selectpicker('refresh')
		$('#current_date').datepicker("setDate", new Date(data.row['order_created_at']) );
		$('#due_date').datepicker("setDate", new Date(data.row['delivery_date']) );

		$(".aw_date").each(function(i,v)
		{	
			$(this).datepicker("setDate", new Date(data.approved_dates[i]));
		})
// $("#buyer").val(data.order.buyer_id)
// i=3
// console.log(data[row]);
}
});
});
});  
</script>
@endsection