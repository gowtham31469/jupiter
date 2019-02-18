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
	.ratio-list-table tr,
	.ratio-list-table th,
	.ratio-list-table td,
	.single-list-table tr,
	.single-list-table th,
	.single-list-table td {
		border-right-width: 1px !important;
		border-bottom-width: 1px !important;
		border-left-width: 1px !important;
		border-top-width: 1px !important;
		border-left: 1px solid #DDD !important;
		border-top: 1px solid #DDD !important;
		border-right: 1px solid #DDD !important;
		border-bottom: 1px solid #DDD !important;
	}

	.borderless tbody td, .borderless tbody th {
	    border: none;
	    padding: 3px;
	    text-align: left;
	}

	.workorder {
		font-size: 16px;
		text-align: center;
		background: #eee;
		font-weight: bold;
	    letter-spacing: 2px;	
	}
	.jc-hr {
		margin-top: 3px !important;
		margin-bottom: 3px !important;
	}

	.vertical-table th {
	    font-size: 12px;
	    background: #eee;
	    border-top: none !important;
	    padding: 0px !important;
	}
	.vertical-table td{ 
		font-size: 12px;
	    border-top: none !important;
	    padding: 0px !important;
	}

</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="javascript:void(0)" class="active">Create Jobcard</a></li>
	<li><a href="{{ route('jobcards-list') }}">Jobcards List</a></li>
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
					    <div class=" fa-3x">
						  <i class="fa fa-spinner fa-pulse"></i>
						</div>
					</div>
                    <div class="col" id="approved-orders-table">
						<input type="hidden" value="{{ session()->get('company') }}" id="company_id">
						<input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
						<table id="approved-order-list" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
							<thead>
								<tr>
									<th>Sl. No</th>
									<th>ERP ID</th>
									<th>Customer Name</th>
									<th>Buyer Name</th>
									<th>Customer PO Number</th>
									<th>Buyer PO Number</th>
									<th>Style Number</th>
									<th>Reference Number</th>
									<th>Delivery Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($orders as $vals)
								<tr id="{{$vals->id}}" data-refname="{{$vals->reference_name}}" data-buyer-po="{{$vals->buyer_po_number}}" data-order-type="{{$vals->order_type}}">
									<td>
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
									<td>{{$vals->buyer_po_number}}</td>
									<td>{{$vals->style_no}}</td>
									<td>{{$vals->reference_name}}</td>
									@if ($vals->delivery_date != '')
										<td>{{$vals->delivery_date}}</td>
									@else
										<td>-</td>
									@endif
									<?php $ref_name = str_replace(" ","",$vals->reference_name) ?>
									<td> <object style="opacity:0;width:0px;height:0px" id="tesco_svg_modal{{$loop->iteration}}" class="center-block tesco_svg{{$loop->iteration}}" data="{{$vals->template_file}}" type="image/svg+xml"></object><a class="artork_download" href="javascript:void(0)" onclick="openmodaltesco('{{$ref_name}}','{{ $loop->iteration }}','{{$vals->template_file}}','{{$vals->style_no}}','{{$vals->buyer_po_number}}','{{$vals->id}}')"><i class="fa fa-file-pdf-o fa-button preview-pdf" data-placement="left" title="Download Artwork" data-original-title="Download Artwork"></i></a></td>
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

<div id="modaldiv">
	<div id="svgdiv" style="position:absolute;visibility:hidden">\
	 <svg id="ean-13"></svg>\
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
                          <img src="{{$company->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
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
<div class="modal jobcard-creation-modal fade" id="jobcardCreationModalCenter" tabindex="-1" role="dialog" aria-labelledby="jobcardCreationModalCenter">
     <div class="modal-dialog modal-lg jobcard-creation-modal-dialog" role="document">
         <div class="modal-content jobcard-creation-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title jobcard-modal-title" id="jobcardCreationModalCenter">Create Jobcard</h4>
                 </div>
				 {!! Form::open(['id' => 'create_jobcard','data-toggle' => 'validator','role' => 'form']) !!}
                 <div class="modal-body jobcard-creation-modal-body">
                    <div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p><strong id="jc_company_name"></strong></p>
									<p id="company_address"></p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <img src="" class="pull-right " id="company_logo" style="width: 150px;height: 60px;">
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
									 {!! Form::label('today_date', 'DATE :', ['class' => 'form_label']) !!}
								    <span id="today_date"></span>
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
								    <div class="form-group has-feedback">
										{!! Form::label('material', 'MATERIAL :', ['class' => 'form_label']) !!}
										{!! Form::text('material',$value = '', array('class' => 'form-control input-sm','placeholder' => 'MATERIAL','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('customer', 'CUST NAME :', ['class' => 'form_label']) !!}
								    <span id="customer"></span>	
								</div>
								<div class="col-md-4">
									<div class="form-group has-feedback">
										{!! Form::label('style_no', 'STYLENUM :', ['class' => 'form_label']) !!}
										{!! Form::text('style_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'STYLENUM', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									 </div>		
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
									<div class="form-group has-feedback">
										{!! Form::label('reference', 'REFERENCE :', ['class' => 'form_label']) !!}
										{!! Form::text('reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									 </div>	
								</div>
								<div class="col-md-4">
									 <div class="form-group has-feedback">
										{!! Form::label('disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
										{{ Form::select('disp_mode',(['' => '-- Select Dispatch Mode --']+ $disp_modes),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'disp_mode','required')) }}
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
									<div class="form-group has-feedback">
									{!! Form::label('length', 'LENGTH :', ['class' => 'form_label']) !!}
									{!! Form::number('length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
									<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
										{!! Form::number('repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('machine', 'MACHINE :', ['class' => 'form_label']) !!}
										{{ Form::select('machine',(['' => '-- Select Machine --']+ $machines),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'machine','required')) }}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback">
									{!! Form::label('cus_del_date', 'CUS D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group cus_del_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="cus_del_date" name="cus_del_date" type="text" class="form-control input-sm " required="" placeholder="CUS D.DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
									{!! Form::label('width', 'WIDTH :', ['class' => 'form_label']) !!}
									{!! Form::number('width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'WIDTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
									<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('roll_width', 'ROLL WIDTH :', ['class' => 'form_label']) !!}
										{!! Form::number('roll_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL WIDTH','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('film_no', 'FILM NO :', ['class' => 'form_label']) !!}
										{!! Form::number('film_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FILM NO','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback">
									{!! Form::label('invoice_date', 'INVOICE DATE :', ['class' => 'form_label']) !!}
									<div class="input-group invoice_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="invoice_date" name="invoice_date" type="text" class="form-control input-sm " placeholder="INVOICE DATE">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
								   <div class="form-group has-feedback">
										{!! Form::label('length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
										{!! Form::number('length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								   </div>
								</div>
								<div class="col-md-3">
							        <div class="form-group has-feedback">
										{!! Form::label('excess', 'EXCESS % :', ['class' => 'form_label']) !!}
										{!! Form::number('excess',$value = '', array('class' => 'form-control input-sm','placeholder' => 'EXCESS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeyup' => 'return calculateExcess(this)', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('teeth_no', 'TEETH NO :', ['class' => 'form_label']) !!}
										{{ Form::select('teeth_no',(['' => '-- Select Teeth No --']+ $teeth_nos),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'teeth_no','required')) }}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('inv_no', 'INV NO :', ['class' => 'form_label']) !!}
										{!! Form::text('inv_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'INV NO','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('no_sticker', 'NO OF STICKERS :', ['class' => 'form_label']) !!}
										{!! Form::number('no_sticker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF STICKERS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('finish_type', 'FINISH TYPE :', ['class' => 'form_label']) !!}
										{{ Form::select('finish_type',(['' => '-- Select Finish Type --']+ $finish_types),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'finish_type','required')) }}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
							   <div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('print_color', 'PRINT COLORS :', ['class' => 'form_label']) !!}
										{!! Form::text('print_color',$value = '', array('class' => 'form-control input-sm','placeholder' => 'STYLENUM', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>	
							   </div>
							   <div class="col-md-9">
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
						<div class="row meter-calculation-div">
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback">
									    @php $login_user = Auth::guard('erpuser')->user()->name; @endphp
										{!! Form::label('compiled_by', 'COMPILED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('compiled_by',$value = $login_user, array('class' => 'form-control input-sm','placeholder' => 'COMPILED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('barcode_read', 'BARCODE READ :', ['class' => 'form_label']) !!}
										{!! Form::text('barcode_read',$value = '', array('class' => 'form-control input-sm','placeholder' => 'BARCODE READ','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('operator', 'OPERATOR :', ['class' => 'form_label']) !!}
										{!! Form::text('operator',$value = '', array('class' => 'form-control input-sm','placeholder' => 'OPERATOR','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('checker', 'CHECKER :', ['class' => 'form_label']) !!}
										{!! Form::text('checker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CHECKER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('author', 'AUTHORIZED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('author',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AUTHORIZED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('axicon_verifier', 'AXICON VERIFIER :', ['class' => 'form_label']) !!}
										{!! Form::text('axicon_verifier',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AXICON VERIFIER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('approved_by', 'APPROVED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('approved_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'APPROVED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('packer', 'PACKER :', ['class' => 'form_label']) !!}
										{!! Form::text('packer',$value = '', array('class' => 'form-control input-sm','placeholder' => 'PACKER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('approval_date', 'APPROVAL DT :', ['class' => 'form_label']) !!}
										<div class="input-group approval_date date datepicker" data-provide="datepicker" id="datePicker1">
											<input value="" id="approval_date" name="approval_date" type="text" class="form-control input-sm "  placeholder="APPROVAL DT">
											<div class="input-group-addon input-sm">
											 <span class="glyphicon glyphicon-th"> </span >
										    </div >
									    </div>
									    <div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('roll_issued_by', 'ROLL ISSUED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('roll_issued_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL ISSUED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('cutter', 'CUTTER :', ['class' => 'form_label']) !!}
										{!! Form::text('cutter',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CUTTER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('cartons', 'CARTONS :', ['class' => 'form_label']) !!}
										{!! Form::text('cartons',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CARTONS','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
                 </div>
                 <div class="modal-footer jobcard-creation-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
					 {!! Form::button('Print', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection @section('jqueryIncludes')
<script>
$(document).ready(function() {
	/** reset all filters  **/
	$('#erp_id_filter').val('').change();
	$('#customer_name_filter').val('').change();
	$('#po_number_filter').val('').change();
	$('#style_number_filter').val('').change();
	$('#ref_number_filter').val('').change();
	$('#delivery_date_filter').val('').change();

	/** [ Refresh  bootstrap selectpicker ] */
	$('#erp_id_filter').selectpicker('val', 1);
	$('#erp_id_filter').selectpicker('refresh');

	$('#customer_name_filter').selectpicker('val', 1);
	$('#customer_name_filter').selectpicker('refresh');

	$('#po_number_filter').selectpicker('val', 1);
	$('#po_number_filter').selectpicker('refresh');

	$('#style_number_filter').selectpicker('val', 1);
	$('#style_number_filter').selectpicker('refresh');

	$('#ref_number_filter').selectpicker('val', 1);
	$('#ref_number_filter').selectpicker('refresh');

	$('#delivery_date_filter').selectpicker('val', 1);
	$('#delivery_date_filter').selectpicker('refresh');

	$('#finish_type').selectpicker('refresh');
	$('#teeth_no').selectpicker('refresh');
	$('#machine').selectpicker('refresh');
	$('#disp_mode').selectpicker('refresh');

	/** [ Toggle bootstrap tooltip ] */
	$('.preview-pdf').tooltip('show');
	$('.preview-pdf').tooltip('toggle');
	
	/** [ Make companies card active ] */
	$('div.buyers-card').click(function(){

		  $('div.buyers-card').removeClass("active");
		  $(this).addClass("active");
		  $('#hidden-buyer-div').hide();

		  /* Save selected company into localdb */
	      if ($('.buyers-card.active-buyer.active').length != 0) 
	      {
         	var company_id = $('.buyers-card.active-buyer.active').data("value"); 
		  	var company_name = $('.buyers-card.active-buyer.active').attr("id");
         	 $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
          	$('#buyerSelectionModalCenter').modal('hide');
		  
			$.ajax({
				url: base_url + "/erp/po-manager/set_company_session",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					company_id: company_id,
					company_name:company_name
				},
				beforeSend: function() {
					location.reload();
				},
				success: function(data) {
				},
				error: function(data) {

				},
				complete: function() {

				}
			})
	      }
	  
	});
	
	/** [ Show already selected(active) buyer on clicking company menu] */
	$('.company-nav-menu').on('click', function() {
	    $("#buyerSelectionModalCenter").modal("show");
	});

	if($('#company_name').val() != null && $('#company_name').val() != "")
	{
		$('#'+$('#company_name').val()).addClass('active');
	}
	else
	{
		$('div.buyers-card').first().addClass("active");
	}
   
    $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
	
	$('.company-nav-menu').html($('.company-nav-div').html());
});
</script>
@endsection
