@extends('layouts.Erp.app')

@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{ route('non-artwork-orders') }}" class="active">Create Non Artwork Order</a></li>

</ul>

@endsection
@section('content')

<div class="col-sm-12 col-md-12">
	<div class="top-content">
		<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;">
			<h4><strong>Create Order For Non Artwork Template</strong></h4>
		</div>
	</div>
	<div class="main-content">
		<div class="error-bag">
			<div class="alert alert-danger print-error-msg alert-dismissable" style="display:none">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<ul></ul>
			</div>
		</div>
		<div class="alert alert-success print-success-msg" style="display:none">
			<ul></ul>
		</div>
	
		{!! Form::open(['id' => 'non_artwork_orders','data-toggle' => 'validator','role' => 'form']) !!}
		<input type="hidden" name="order_to_edit" id="order_to_edit">
		<div class="row">

			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="buyer">Seacrh For Order ID</label>
					{!! Form::select('order_id',$order_id, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Order ID",'id'=>'order_id','data-size'=>'5']) !!}
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-9">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group has-feedback">
					<label for="buyer">Buyer</label>
					{!! Form::select('buyer',$buyers, null, ['class' => ' selectpicker','data-live-search'=>"true",'title'=>"Select Buyer",'data-size'=>'5','id'=>'buyer','required']) !!}
					<div class="help-block with-errors"></div>
				</div>
			</div>
			<div class="col-md-4">
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
			<div class="col-md-4">
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
			<div class="col-md-4">
				<div class="form-group has-feedback">
					<label for="customer_name">Customer Name</label>
					{!! Form::select('customer',[], null, ['class' => ' selectpicker customer_select','id'=>'customer','data-live-search'=>"true",'title'=>"Select Customer Name",'required','data-size'=>'5']) !!}
					<div class="help-block with-errors"></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group has-feedback">
					<label for="tesco_po_number">PO Number</label>
					<input type="text" class="form-control" name="po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number"  maxlength="15">
					<div class="help-block with-errors"></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group has-feedback">
					<label for="hsn_code">HSN Code</label>
					<input type="text" class="form-control" name="hsn_code" id="hsn_code" readonly placeholder="Enter HSN Code"  maxlength="7">
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="row">
			<div class="col-md-12">
				<div class="card order_status_card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								{{ Html::image('images/status_img.png', 'alt text', array('class' => 'status-img-class')) }}
							</div>
							<div class="col-md-8">
								<h5 class="card-title">Order Status</h5>
								<h4 class="card-title" id="_order_status">
									<label style="margin-left: 14%;">- Nil -</label>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</div>	 			
		</div>
	</div>
</div>
		<div class="row">
			<div class="col-md-12">                                            
				<hr>                                            
				<h4>Order Quantity</h4>                                            
				<p>                                                
					<button type="button" id="add_single_row" class="btn btn-default add-row-btn clone row_btns"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>
					<button type="button" id="clone_selected"  class="btn btn-default add-row-btn row_btns"><i class="fa fa-clone" aria-hidden="true"></i> Clone Selected</button>
					<button type="button" id="delete_selected" class="btn btn-default add-row-btn row_btns"><i class="fa fa-trash" aria-hidden="true"></i> Delete Selected</button>
					
				</p>                                        
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="nat-table">
				<table class="table table-bordered table-responsive" id="nat_tab_logic">
					<thead>
						<tr>
							<th class="text-center">Sl.No</th>
							<th class="text-center">Jc No.</th>
							<th class="text-center hidden">Status</th>
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
									<p>-</p>
								</div>
							</td>
							<td class="hidden">
								<input type="text" name="status[]"  class="form-control input-sm label-ean-field" placeholder="Status" value="3">
							</td>
							<td>
								<input type="text" name="ocn[]"  class="form-control input-sm label-ean-field" maxlength="12" placeholder="OCN Number">
							</td>
							<td>
								<input type="text" name="bpo[]" class="form-control input-sm label-ean-field" placeholder="BPO Number" maxlength="10">
							</td>
							<td>
								<input type="text" name="style[]" class="form-control input-sm label-ean-field" placeholder="Style/UVC" maxlength="12">
							</td>	
							<td class="selectpicker_td item_td">
								{!! Form::select('item[]', $select_item_data, null, ['class' => 'selectpicker item_select input-group', 'data-live-search' => 'true', 'title' =>'Select item','data-container' =>'body']) !!}
							</td>
							<td class="selectpicker_td reference_td">
								{!! Form::select('reference[]', [], null, ['class' => 'selectpicker reference_select input-group', 'data-live-search' => 'true', 'title' =>'Select reference','data-container' =>'body']) !!}
							</td>
							<!-- <td class="reference_td selectpicker_td">
								<select data-live-search="true" name="reference[]" class="selectpicker form-control input-group reference_select" data-container="body" title="Select reference"></select>
							</td> -->
							<td>
								<input type="text" name="color[]" class="form-control input-sm label-ean-field" placeholder="Color" maxlength="8">
							</td>
							<td>
								<input type="text" name="composition[]" class="form-control input-sm label-ean-field" placeholder="Composition" maxlength="25">
							</td>
							<td class="selectpicker_td">
								<select class="selectpicker aw_status" name="A_W_Status[]" data-container=".main-content">
									<option value="1" selected>Approved</option>
									<option value="0">Not Approved</option>
								</select>
							</td>
							<td class="aw_td">
								<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">
									<input value="" id="" name="approve_date[]" type="text" class="form-control aw_date" placeholder="Select Due Date">
									<div class="input-group-addon">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								</div >
							</td>
							<!-- <td>
								{!! Form::select('size[]', $sizes, null, ['class' => 'form-control input-sm label-ean-field']) !!}
							</td> -->
							<td class="selectpicker_td">
								{!! Form::select('size[]', $sizes, null, ['class' => 'selectpicker input-group size_select no_clone', 'data-live-search' => 'true', 'title' =>'Select size','data-container' =>'body']) !!}
							</td>
							<td class="quantity_td">
								<input type="number" name="quantity[]" class="form-control input-sm label-ean-field quantity no_clone" placeholder="Quantity" max="99999999">
							</td>
							<td class="selectpicker_td">
								{!! Form::select('uom[]', $uom, null, ['class' => 'selectpicker uom_select','data-live-search'=>'true','title'=>'Select uom','data-container'=>'.main-content']) !!}
							</td>
							<td class="rate_td">
								<input type="number" name="rate[]" class="form-control input-sm label-ean-field rates no_clone" placeholder="rate" readonly>
							</td>
							<td class="value_td">
								<input type="number" name="rate_value[]" class="form-control input-sm label-ean-field rate_value no_clone" placeholder="qty*rate" readonly>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				</div>
				<div class="col-md-8"></div>
				<div class="col-md-4 calculations">
				<table class="table table-bordered" id="nat_tab_logic_total">
					<tbody>
						<tr>
							<td>Qty Total</td>
							<td><input type="text" class="form-control" name="qty_total" placeholder="Qty total" readonly id="qty_total"></td>
							<td><input type="text" id="value_total" class="form-control" name="" placeholder="Value total" readonly></td>
						</tr>
						<tr>
							<td>Tax</td>
							<td id="tax"></td>
							<td><input type="text" id="tax_value" class="form-control" name="" placeholder="Tax value" readonly></td>
						</tr>
						<tr>
							<td>Total</td>
							<td colspan="2"><input type="text" id="tax_value_total" class="form-control" name="" placeholder="Value total + tax" readonly></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row clearfix">
		</div>
		<div class="row clearfix" style="height: 50px;"></div>
		<div class="bottom-bar">
			<div class="modal-footer" id="action_buttons">
				<button type="button" id="cancel_order" class="btn btn-xs btn-danger hidden"><i class="fa fa-trash" aria-hidden="true"></i>Cancel Order</button>
				<button type="submit" id="save_data" class="btn btn-xs btn-success" data-flag="1" data-action="save">
					<i class="fa fa-save" aria-hidden="true"></i> 
				Save</button>
				<button class="btn btn-xs btn-info reload-btn" type="button">
					<i class="fa fa-refresh" aria-hidden="true"></i> 
				Reload</button>
			</div>
		</div>
		{{Form::close()}}
	 </div>
  </div>
</div>
<div id="snackbar"><span class="snackbar_span"></span></div>
<input type="hidden" id="_tax_value">
<div id="confirmCancellation" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancel Order</h4>
      </div>
      <div class="modal-body">
        <h5>Please Confirm Order Cancellation !!!</h5>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-danger" id="confirm">Confirm</button>
    	<button type="button" data-dismiss="modal" class="btn btn-info" id="cancel_cancellation">Cancel</button>
      </div>
    </div>

  </div>
</div>
@endsection
@section('jqueryIncludes')
<script src="{{ asset('js/Erp/non-artwork-order-create.js') }}"></script>
@endsection