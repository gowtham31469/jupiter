@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) 
<style type="text/css">

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

		<form name="non_artwork_orders" id="non_artwork_orders">  
			<div class="row">
				<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="due_date">Order ID</label>
							<div class="input-group tesco_manufacture_date date">
								<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="order_id" id="order_id" required="" placeholder="Enter Order ID" maxlength="7">
								<div class="input-group-addon" id="getOrderDetails">
									<span class="glyphicon glyphicon-search"> </span >
								</div >
							</div >
							<div class="help-block with-errors"></div>
						</div>
					</div>

					
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="current_date">Current Date</label>
							<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="current_date" id="current_date" required="" placeholder="Enter PO. Number" maxlength="7">
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="buyer">Buyer</label>
							{!! Form::select('buyer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Buyer",'id'=>'buyer']) !!}
							<div class="help-block with-errors"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="customer_name">Customer Name</label>
							{!! Form::select('customer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Customer Name"]) !!}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="tesco_po_number">PO Number</label>
							<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number"  maxlength="7">
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
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
					<div class="col-md-12">                                            
						<hr>                                            
						<h4>Order Quantity</h4>                                            
						<p>                                                
							<button type="button" id="add_single_row" class="btn btn-default add-row-btn clone"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>

							<button type="button" id="clone_selected"  class="btn btn-default add-row-btn"><i class="fa fa-clone" aria-hidden="true"></i> Clone Selected</button>

							<button type="button" id="delete_selected" class="btn btn-default add-row-btn"><i class="fa fa-trash" aria-hidden="true"></i> Delete Selected</button>

							<button type="button" id="save_data" class="btn btn-default add-row-btn submit_data" data-flag="1"><i class="fa fa-save" aria-hidden="true"></i> Save</button>

							<button type="button" id="new_order" class="btn btn-default add-row-btn submit_data hidden" data-flag="3"><i class="fa fa-save" aria-hidden="true"></i> Save as new order</button>
						</p>                                        
					</div>
				</div>
				
				<div class="row clearfix">
    <div class="col-md-12">
      <table class="table table-bordered table-hover" id="tab_logic">
        <thead>
          <tr>
            	<th class="text-center">Sl.No</th>
							<th class="text-center">Jc No.</th>
							<th class="text-center">OCN</th>
							<th class="text-center">B.Po. No.</th>
							<th class="text-center">Style/UVC</th>
							<th class="text-center">Item</th>
							<th class="text-center">A/W.Appr.Dt</th>
							<th class="text-center">Quantity</th>
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
								<td>
									<input type="text" name="ocn[]"  class="form-control input-sm label-ean-field" placeholder="OCN Number">
								</td>
								<td>
									<input type="text" name="bpo[]" class="form-control input-sm label-ean-field" placeholder="BPO Number">
								</td>
								<td>
									<input type="text" name="style[]" class="form-control input-sm label-ean-field" placeholder="Style/UVC">
								</td>
								<td>
									{!! Form::select('list[]', $buyers, null, ['class' => 'form-control input-sm label-ean-field']) !!}
								</td>
								<td>
									<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="" name="approve_date[]" type="text" class="form-control"placeholder="Select Due Date">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"> </span >
										</div >
									</div >
								</td>
								<td>
									<input type="text" name="quantity[]" class="form-control input-sm label-ean-field quantity" placeholder="Quantity">
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
			</form>  
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
		var postURL = "<?php echo url('admin/save-non-artwork'); ?>";
		var getDetailsURL = "<?php echo url('admin/get-non-artwork-order-details'); ?>";
		var i=1;  

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$('#add_single_row').click(function()
		{  
			 $('#tab_logic').append('<tr id="addr'+(i+1)+'" class="clonable"></tr>');
			 $('#addr'+i).html($('#tab_logic').find(' tbody tr:eq(0)').html()).find('td:first-child').html('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input select_row_to_clone" name="customCheck'+(i+1)+'"><label class="custom-control-label">&nbsp;&nbsp;'+(i+1)+'</label></div>')
			   i++;
			
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


		$(document).on("keyup change",".quantity",function(){	
				total=0;
				$('.quantity').each(function() {
					total += parseInt($(this).val());
				});
				$("#sub_total").val(total)
				$("#total_amount").val(total)
  	});


		$(document).on("click",".submit_data",function(){
				$.ajax({  
					url:postURL,  
					method:"POST",  
					data:$('#non_artwork_orders').serialize(),
					type:'json',
					success:function(data)  
					{
						if(data.error){
							printErrorMsg(data.error);
						}else{
							i=1;
							$('.dynamic-added').remove();
							$('#non_artwork_orders')[0].reset();
							$(".print-success-msg").find("ul").html('');
							$(".print-success-msg").css('display','block');
							$(".print-error-msg").css('display','none');
							$(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
						}
					}  
				});  
		});  


		function printErrorMsg (msg) {
			$(".print-error-msg").find("ul").html('');
			$(".print-error-msg").css('display','block');
			$(".print-success-msg").css('display','none');
			$.each( msg, function( key, value ) {
				$(".print-error-msg").find("ul").append('<li>'+value+'</li>');
			});
		}

		$("#getOrderDetails").on('click', function () {

				$("#save_data").html('<i class="fa fa-save" aria-hidden="true"></i> Update')
				$("#new_order").removeClass('hidden')
          $.ajax({
           type:'POST',
           url:getDetailsURL,
           data:{id:$("#order_id").val()},
           success:function(data){
           		console.log(data)
              // $("#dynamic_field").find("tr:gt(0)").remove();
              $("#dynamic_field").html('')
              $("#dynamic_field").html(data.order_details)
              $("#buyer").val(data.order.buyer_id)
              i=3
              console.log(data);
           }
        });
      });
	});  
</script>
@endsection