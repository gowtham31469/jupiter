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
							{!! Form::select('Buyer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Buyer"]) !!}
							<div class="help-block with-errors"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="customer_name">Customer Name</label>
							{!! Form::select('Customer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Customer Name"]) !!}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="tesco_po_number">PO Number</label>
							<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number"  maxlength="7">
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

							<button type="button" onclick="clone()"  class="btn btn-default add-row-btn"><i class="fa fa-clone" aria-hidden="true"></i> Clone Selected</button>

							<button type="button" onclick=""class="btn btn-default add-row-btn"><i class="fa fa-trash" aria-hidden="true"></i> Delete Selected</button>

							<button type="button" id="save_data" class="btn btn-default add-row-btn"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
						</p>                                        
					</div>
				</div>
				<div class="alert alert-danger print-error-msg" style="display:none">
					<ul></ul>
				</div>
				<div class="alert alert-success print-success-msg" style="display:none">
					<ul></ul>
				</div>
				<div class="table-responsive">  
					<table class="table table-bordered">  
						<tr>
							<th>Sl No.</th>
							<th>Jc No.</th>
							<th>OCN</th>
							<th>B.Po. No.</th>
							<th>Style/UVC</th>
							<th>Item</th>
							<th>A/W.Appr.Dt</th>
						</tr>
						<tbody id="dynamic_field">
							<tr>
								<td>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="customCheck1">
										<label class="custom-control-label" for="customCheck1">&nbsp;1</label>
									</div>
								</td>
								<td>
									<div class="form-group has-feedback label-ean-feedback">
										<p>32123414</p>
									</div>
								</td>
								<td>
									<input type="text" name="Orders[1][ocn]"  class="form-control input-sm label-ean-field" placeholder="OCN Number">
								</td>
								<td>
									<input type="text" name="Orders[1][bpn]" class="form-control input-sm label-ean-field" placeholder="BPO Number">
								</td>
								<td>
									<input type="text" name="Orders[1][style]" class="form-control input-sm label-ean-field" placeholder="Style/UVC">
								</td>
								<td>
									{!! Form::select('List[]', $buyers, null, ['class' => 'form-control input-sm label-ean-field']) !!}
								</td>
								<td>
									<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="" name="Orders[0][approve_date]" type="text" class="form-control"placeholder="Select Due Date">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"> </span >
										</div >
									</div >
								</td>
							</tr> 
						</tbody>
					</table>  
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
	$(document).ready(function(){      
		var postURL = "<?php echo url('admin/save-non-artwork'); ?>";
		var getDetailsURL = "<?php echo url('admin/get-non-artwork-order-details'); ?>";
		var i=1;  


		$('#add_single_row').click(function(){  
			i++;  

			$('#dynamic_field').append('<tr><td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1"><label class="custom-control-label" for="customCheck1">&nbsp;&nbsp;'+i+'</label></div></td><td><div class="form-group has-feedback label-ean-feedback"><p>32123414</p></div></td><td><input type="text" name= "Orders['+i+'][ocn]" id="" class="form-control input-sm label-ean-field" placeholder="OCN Number"></td><td><input type="text" name="Orders['+i+'][bpo]"class="form-control input-sm label-ean-field" placeholder="BPO Number"></td><td><input type="text" name="Orders['+i+'][style]" class="form-control input-sm label-ean-field" placeholder="Style/UVC"></td><td>{!! Form::select("List[]", $buyers, null, ['class' => "form-control input-sm label-ean-field"]) !!}</td><td><div class="input-group tesco_manufacture_date date" data-provide="datepicker"><input value="" id="approveDate_v1" name=Orders['+i+'][approved_orders] type="text" class="form-control"placeholder="Select Due Date"><div class="input-group-addon"><span class="glyphicon glyphicon-th"> </span ></div ></div ></td></tr>');  
		});   


		$(document).on('click', '.btn_remove', function(){  
			var button_id = $(this).attr("id");   
			$('#row'+button_id+'').remove();  
		});  


		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});


		$('#save_data').click(function(){            
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
          $.ajax({
           type:'POST',
           url:getDetailsURL,
           data:{id:$("#order_id").val()},
           success:function(data){

              // $("#dynamic_field").find("tr:gt(0)").remove();
              // $("#dyanamics_here").html(data)
              console.log(data);
           }
        });
      });
	});  
</script>
@endsection