@extends('layouts.Erp.app')
<style>
	.action-button-view {
		border: 1px #333 solid;
		background-color: #fff;
		border-radius: 2px;
		padding: 3px;
		margin-right: 10px;
	}
	
	.red-icon {
		color: grey;
		float: right;
	}
	
	.active {
		color: #dc3545 !important;
	}
	
	.active-border {
		border-color: #dc3545 !important;
	}
	
	.bad {
		background-color: #dc3545;
		color: #fff;
	}
	
	.cur-po {
		cursor: pointer;
		color: grey;
		float: right;
	}
	
	.cur-po-comp {
		cursor: pointer;
		color: grey;
	}
	
	.btn-loader {
		border: 8px solid #f3f3f3 !important;
		border-radius: 50% !important;
		border-top: 8px solid #3498db !important;
		width: 50px !important;
		height: 50px !important;
		-webkit-animation: spin 2s linear infinite !important;
		/* Safari */
		animation: spin 2s linear infinite !important;
	}
	/* Safari */
	
	@-webkit-keyframes spin {
		0% {
			-webkit-transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
		}
	}
	
	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
	
	label.btn span {
		font-size: 1.5em;
	}
	
	label input[type="radio"]~i.fa.fa-circle-o {
		color: #c8c8c8;
		display: inline;
	}
	
	label input[type="radio"]~i.fa.fa-dot-circle-o {
		display: none;
	}
	
	label input[type="radio"]:checked~i.fa.fa-circle-o {
		display: none;
	}
	
	label input[type="radio"]:checked~i.fa.fa-dot-circle-o {
		color: #7AA3CC;
		display: inline;
	}
	
	label:hover input[type="radio"]~i.fa {
		color: #7AA3CC;
	}
	
	label input[type="checkbox"]~i.fa.fa-square-o {
		color: #c8c8c8;
		display: inline;
	}
	
	label input[type="checkbox"]~i.fa.fa-check-square-o {
		display: none;
	}
	
	label input[type="checkbox"]:checked~i.fa.fa-square-o {
		display: none;
	}
	
	label input[type="checkbox"]:checked~i.fa.fa-check-square-o {
		color: #7AA3CC;
		display: inline;
	}
	
	label:hover input[type="checkbox"]~i.fa {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label.active {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label {
		display: inline-block;
		padding: 6px 12px;
		margin-bottom: 0;
		font-size: 14px;
		font-weight: normal;
		line-height: 2em;
		text-align: left;
		white-space: nowrap;
		vertical-align: top;
		cursor: pointer;
		background-color: none;
		border: 0px solid #c8c8c8;
		border-radius: 3px;
		color: #c8c8c8;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
	}
	
	div[data-toggle="buttons"] label:hover {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label:active,
	div[data-toggle="buttons"] label.active {
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	
	.first-padding {
		padding: 10px 15px;
		font-weight: bold;
		font-size: 14px;
	}
	
	.first-padding:nth-child(even),
	.odd-padding {
		background-color: #f2f2f2;
	}
	
	.first-padding.col-md-4.text-center {
		border-right: 5px solid #fff;
	}
	
	.confirmation-container {
		margin-top: 15px;
	}
	
	.remark-align {
		padding-left: 0px !important;
	}
	.highlight_row {
		background: #eee;
	}
	#ratio-list-table tr,
	#ratio-list-table th,
	#ratio-list-table td,
	#single-list-table tr,
	#single-list-table th,
	#single-list-table td {
		border-right-width: 1px !important;
		border-bottom-width: 1px !important;
		border-left-width: 1px !important;
		border-top-width: 1px !important;
		border-left: 1px solid #DDD !important;
		border-top: 1px solid #DDD !important;
		border-right: 1px solid #DDD !important;
		border-bottom: 1px solid #DDD !important;
	}

</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="javascript:void(0)" class="active">Create Jobcard</a></li>
</ul>

@endsection @section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12" style="padding-top: 25px;">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
			    <div class="company-nav-div hidden">
			    	<a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
				</div>
                <div class="action-group pull-right sub-menu-button">
                  <button class=" btn btn-primary btn-sm" onclick="generateJobcard()">
                   Create Jobcard
				  </button>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader">
						<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
					</div>
                    <div class="col" id="approved-orders-table">
					<input type="hidden" value="{{ session()->get('company') }}" id="company_id">
					<input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
					<table id="approved-order-list" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
						<thead>
							<tr>
								<th>Sl. No</th>
								<th>ERP ID</th>
								<th>Customer name</th>
								<th>Buyer name</th>
								<th>PO Number</th>
								<th>Style Number</th>
								<th>Reference Number</th>
								<th>Delivery Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($orders as $vals)
							<tr id="{{$vals->id}}" data-refname="{{$vals->reference_name}}">
								<td class="">
								
									<div class="form-check">
									<a href="javascript:void(0)">
										<label>
											<input class="row-checkbox" type="checkbox" data-customer-name="{{$vals->company_name}}" style="margin-right: 10px;" onchange="return selectOrders(this)" data-buyer-name="{{$vals->buyer_name}}"> <span class="label-text" class="hidden-val">{{ $loop->iteration }}</label>
									</a>
									</div>
								</td>
								<td>{{$vals->erp_id}}</td>
								<td>{{$vals->company_name}}</td>
								<td data-buyer-id="{{$vals->buyer_id}}">{{$vals->buyer_name}}</td>
								<td>{{$vals->po_no}}</td>
								<td>{{$vals->style_no}}</td>
								<td>{{$vals->reference_name}}</td>
								@if ($vals->delivery_date != '')
									<td>{{$vals->delivery_date}}</td>
								@else
									<td>-</td>
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
    </div>
<!-- company selection Modal -->
<div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog buyer-selection-modal-dialog" role="document">
         <div class="modal-content buyer-selection-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Company</h4>
                 </div>
                 <div class="modal-body buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                      <strong>Warning!</strong> Select a Company to proceed.
                    </div>
                    @foreach($companies as $company)
                     <a class="buyer-card-link" style="cursor: pointer;">
                         <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$company->name}}"  data-value ="{{$company->company}}" id="{{ strtolower( str_replace(' ','',$company->name)) }}">
                          {{ $company->name }}
                         </div>
                     </a>
                    @endforeach
                 </div>
                 <div class="modal-footer buyer-selection-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
<!-- Job card creation Modal -->
<div class="modal jobcard-creation-modal fade" id="jobcardCreationModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog modal-lg jobcard-creation-modal-dialog" role="document">
         <div class="modal-content jobcard-creation-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title jobcard-modal-title" id="myModalLabel">Create Jobcard</h4>
                 </div>
				 {!! Form::open(['id' => 'create_jobcard','data-toggle' => 'validator','role' => 'form']) !!}
                 <div class="modal-body jobcard-creation-modal-body">
                    <div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p><strong id="company-name"></strong></p>
									<p id="company-address"></p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <img src="" class="pull-right " id="company-logo" style="width: 150px;height: 60px;">
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('work_order', 'WORKORDER :', ['class' => 'form_label']) !!}
								    <span id="work_order"></span>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									 {!! Form::label('today-date', 'DATE :', ['class' => 'form_label']) !!}
								    <span id="today-date"></span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('article', 'ARTICLE :', ['class' => 'form_label']) !!}
								    <span id="article"></span>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									 {!! Form::label('material', 'MATERIAL :', ['class' => 'form_label']) !!}
								    <span id="material"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('customer', 'CUST NAME :', ['class' => 'form_label']) !!}
								    <span id="customer" data-customer-id=""></span>	
								</div>
								<div class="col-md-4">
									{!! Form::label('style_no', 'STYLENUM :', ['class' => 'form_label']) !!}
								    <span id="style_no" ></span>	
								</div>
								<div class="col-md-4">
									 {!! Form::label('order_type', 'ORDER TYPE :', ['class' => 'form_label']) !!}
								    <span  id="order_type" ></span>	
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('buyer', 'BUYER :', ['class' => 'form_label']) !!}
								    <span id="buyer"></span>	
								</div>
								<div class="col-md-4">
								    {!! Form::label('reference', 'REFERENCE :', ['class' => 'form_label']) !!}
								    <span id="reference"></span>	
								</div>
								<div class="col-md-4">
									 <div class="form-group has-feedback form-inline">
										{!! Form::label('disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
										{!! Form::text('disp_mode',$value = '', array('class' => 'form-control input-sm','placeholder' => 'DISPATCH MODE','required','maxlength' => '10')) !!}
										<div class="help-block with-errors"></div>
									 </div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									{!! Form::label('order_date', 'ORDER DT :', ['class' => 'form_label']) !!}
								    <span id="order_date"></span>
								</div>
								<div class="col-md-3">
									{!! Form::label('length', 'LENGTH :', ['class' => 'form_label']) !!}
								    <span id="length"></span>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
										{!! Form::number('repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('machine', 'MACHINE :', ['class' => 'form_label']) !!}
										{!! Form::number('machine',$value = '', array('class' => 'form-control input-sm','placeholder' => 'MACHINE','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
									{!! Form::label('cus_del_date', 'CUS D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group cus_del_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="cus_del_date" name="cus_del_date" type="text" class="form-control input-sm " required="" placeholder="CUS D.DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
									{!! Form::label('width', 'WIDTH :', ['class' => 'form_label']) !!}
								    <span id="width"></span>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('roll_width', 'ROLL WIDTH :', ['class' => 'form_label']) !!}
										{!! Form::number('roll_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL WIDTH','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('film_no', 'FILM NO :', ['class' => 'form_label']) !!}
										{!! Form::number('film_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FILM NO','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
									{!! Form::label('act_del_date', 'ACT D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group act_del_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="act_del_date" name="act_del_date" type="text" class="form-control input-sm " required="" placeholder="ACT D.DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
										{!! Form::label('length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
										{!! Form::number('length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
								   </div>
								</div>
								<div class="col-md-3">
							        <div class="form-group has-feedback form-inline">
										{!! Form::label('excess', 'EXCESS % :', ['class' => 'form_label']) !!}
										{!! Form::number('excess',$value = '', array('class' => 'form-control input-sm','placeholder' => 'EXCESS','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0', 'onkeyup' => 'return calculateExcess(this)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('teeth_no', 'TEETH NO :', ['class' => 'form_label']) !!}
										{!! Form::number('teeth_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'TEETH NO','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('inv_no', 'INV NO :', ['class' => 'form_label']) !!}
										{!! Form::number('inv_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'INV NO','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('no_sticker', 'NO OF STICKERS :', ['class' => 'form_label']) !!}
										{!! Form::number('no_sticker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF STICKERS','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('finish_type', 'FINISH TYPE :', ['class' => 'form_label']) !!}
										{!! Form::number('finish_type',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FINISH TYPE','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
							   <div class="col-md-8">
								  {!! Form::label('print_color', 'PRINT COLORS :', ['class' => 'form_label']) !!}
								    <span id="print_color"></span>
							   </div>
							   <div class="col-md-4">
							   </div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 ratio-list-table-div">
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12 single-list-table-div">
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								{!! Form::label('act_excess', 'ACT EXCESS % :', ['class' => 'form_label']) !!}
								<span>7</span>
							</div>
							<div class="col-md-3">
								{!! Form::label('sticker_mtr', 'MTRS FOR STICKERS :', ['class' => 'form_label']) !!}
								<span>30</span>
							</div>
							<div class="col-md-3">
							<div class="form-group has-feedback form-inline">
								{!! Form::label('settings', 'SETTINGS :', ['class' => 'form_label']) !!}
								{!! Form::number('settings',$value = '', array('class' => 'form-control input-sm','placeholder' => 'SETTINGS','required','min' => '1','maxlength' => '10', 'data-min-error' => 'Value should be greater than 0')) !!}
								<div class="help-block with-errors"></div>
							</div>
							</div>
							<div class="col-md-3">
								{!! Form::label('total_mtrs', 'TOTAL MTRS :', ['class' => 'form_label']) !!}
								<span>30</span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('compiled_by', 'COMPILED BY :', ['class' => 'form_label']) !!}
									{!! Form::text('compiled_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'COMPILED BY','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('barcode_read', 'BARCODE READ :', ['class' => 'form_label']) !!}
									{!! Form::text('barcode_read',$value = '', array('class' => 'form-control input-sm','placeholder' => 'BARCODE READ','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('operator', 'OPERATOR :', ['class' => 'form_label']) !!}
									{!! Form::text('operator',$value = '', array('class' => 'form-control input-sm','placeholder' => 'OPERATOR','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('checker', 'CHECKER :', ['class' => 'form_label']) !!}
									{!! Form::text('checker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CHECKER','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('author', 'AUTHORIZED BY :', ['class' => 'form_label']) !!}
									{!! Form::text('author',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AUTHORIZED BY','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('axicon_verifier', 'AXICON VERIFIER :', ['class' => 'form_label']) !!}
									{!! Form::text('axicon_verifier',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AXICON VERIFIER','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('approved_by', 'APPROVED BY :', ['class' => 'form_label']) !!}
									{!! Form::text('approved_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'APPROVED BY','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('packer', 'PACKER :', ['class' => 'form_label']) !!}
									{!! Form::text('packer',$value = '', array('class' => 'form-control input-sm','placeholder' => 'PACKER','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
									{!! Form::label('approval_date', 'APPROVAL DT :', ['class' => 'form_label']) !!}
									<div class="input-group approval_date date" data-provide="datepicker" id="datePicker1">
										<input value="" id="approval_date" name="approval_date" type="text" class="form-control input-sm " required="" placeholder="APPROVAL DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('roll_issued_by', 'ROLL ISSUED BY :', ['class' => 'form_label']) !!}
									{!! Form::text('roll_issued_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL ISSUED BY','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('cutter', 'CUTTER :', ['class' => 'form_label']) !!}
									{!! Form::text('cutter',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CUTTER','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback form-inline">
									{!! Form::label('cartons', 'CARTONS :', ['class' => 'form_label']) !!}
									{!! Form::text('cartons',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CARTONS','required','maxlength' => '40')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>
                 </div>
                 <div class="modal-footer jobcard-creation-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
					 {!! Form::button('Print & Send', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection @section('jqueryIncludes')

@endsection
