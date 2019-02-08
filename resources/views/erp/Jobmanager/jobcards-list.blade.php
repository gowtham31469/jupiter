@extends('layouts.Erp.app')
<style>
.highlight_row {
		background: #eee;
	}
	.edit-ratio-list-table tr,
	.edit-ratio-list-table th,
	.edit-ratio-list-table td,
	.edit-single-list-table tr,
	.edit-single-list-table th,
	.edit-single-list-table td {
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
	<li><a href="{{ route('jobcard-manager') }}">Create Jobcard</a></li>
	<li><a href="javascript:void(0)"  class="active">Jobcards List</a></li>
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
              </div>
             <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader">
						<div class=" fa-3x">
                            <i class="fa fa-spinner fa-pulse"></i>
                        </div>
					</div>
                    <div class="col" id="jobcard-lists-table">
                        <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
                        <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
                            <table id="jobcard-list-table" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Jobcard ID</th>
                                        <th>Customer name</th>
                                        <th>Buyer name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobcards as $jobcard)
                                    <tr id="{{$jobcard->id}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$jobcard->work_order_id}}</td>
                                        <td>{{$jobcard->company_name}}</td>
                                        <td>{{$jobcard->buyer_name}}</td>
                                        <td><a href="#"><i class="fa fa-pencil fa-button edit-jobcard filterbtn options" title="Edit Jobcard" data-placement="left"></i></a></td>
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

 <!-- Job card updation Modal -->
<div class="modal jobcard-updation-modal fade" id="jobcardUpdationModalCenter" tabindex="-1" role="dialog" aria-labelledby="jobcardUpdationModalCenter">
     <div class="modal-dialog modal-lg jobcard-updation-modal-dialog" role="document">
         <div class="modal-content jobcard-updation-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title jobcard-modal-title" id="jobcardUpdationModalCenter">Edit Jobcard</h4>
                 </div>
				 {!! Form::open(['id' => 'edit_jobcard','data-toggle' => 'validator','role' => 'form']) !!}
				 {{-- hidden id for edit functionality --}}
                 {!! Form::hidden('jobcard_id',$value = null, array('id'=>'jobcard_id','class' => 'form-control input-sm')) !!}
                 <div class="modal-body jobcard-updation-modal-body">
                    <div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p><strong id="edit_jc_company_name"></strong></p>
									<p id="edit_company_address"></p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <img src="" class="pull-right " id="edit_company_logo" style="width: 150px;height: 60px;">
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('edit_work_order', 'WORKORDER :', ['class' => 'form_label']) !!}
								    <span id="edit_work_order"></span>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									 {!! Form::label('edit_today_date', 'DATE :', ['class' => 'form_label']) !!}
								    <span id="edit_today_date"></span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('edit_article', 'ARTICLE :', ['class' => 'form_label']) !!}
								    <span id="edit_article"></span>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
									 {!! Form::label('edit_material', 'MATERIAL :', ['class' => 'form_label']) !!}
								    <span id="edit_material"></span>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('edit_customer', 'CUST NAME :', ['class' => 'form_label']) !!}
								    <span id="edit_customer"></span>	
								</div>
								<div class="col-md-4">
									{!! Form::label('edit_style_no', 'STYLENUM :', ['class' => 'form_label']) !!}
								    <span id="edit_style_no" ></span>	
								</div>
								<div class="col-md-4">
									 {!! Form::label('edit_order_type', 'ORDER TYPE :', ['class' => 'form_label']) !!}
								    <span  id="edit_order_type" ></span>	
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									{!! Form::label('edit_buyer', 'BUYER :', ['class' => 'form_label']) !!}
								    <span id="edit_buyer"></span>	
								</div>
								<div class="col-md-4">
								    {!! Form::label('edit_reference', 'REFERENCE :', ['class' => 'form_label']) !!}
								    <span id="edit_reference"></span>	
								</div>
								<div class="col-md-4">
									 <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_disp_mode',$value = '', array('class' => 'form-control input-sm','placeholder' => 'DISPATCH MODE','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									 </div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									{!! Form::label('edit_order_date', 'ORDER DT :', ['class' => 'form_label']) !!}
								    <span id="edit_order_date"></span>
								</div>
								<div class="col-md-3">
									{!! Form::label('edit_length', 'LENGTH :', ['class' => 'form_label']) !!}
								    <span id="edit_length"></span>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickersEdit()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_machine', 'MACHINE :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_machine',$value = '', array('class' => 'form-control input-sm','placeholder' => 'MACHINE','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
									{!! Form::label('edit_cus_del_date', 'CUS D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group edit_cus_del_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="edit_cus_del_date" name="edit_cus_del_date" type="text" class="form-control input-sm " required="" placeholder="CUS D.DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
									{!! Form::label('edit_width', 'WIDTH :', ['class' => 'form_label']) !!}
								    <span id="edit_width"></span>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_roll_width', 'ROLL WIDTH :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_roll_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL WIDTH','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_film_no', 'FILM NO :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_film_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FILM NO','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
									{!! Form::label('edit_act_del_date', 'ACT D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group edit_act_del_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="edit_act_del_date" name="edit_act_del_date" type="text" class="form-control input-sm " required="" placeholder="ACT D.DT">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
								   <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								   </div>
								</div>
								<div class="col-md-3">
							        <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_excess', 'EXCESS % :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_excess',$value = '', array('class' => 'form-control input-sm','placeholder' => 'EXCESS','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'onkeyup' => 'return calculateExcessEdit(this)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_teeth_no', 'TEETH NO :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_teeth_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'TEETH NO','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_inv_no', 'INV NO :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_inv_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'INV NO','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_no_sticker', 'NO OF STICKERS :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_no_sticker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF STICKERS','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback form-inline">
										{!! Form::label('edit_finish_type', 'FINISH TYPE :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_finish_type',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FINISH TYPE','required','min' => '1','max' => '999999999', 'data-max-error' => 'Value should be <= 999999999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
							   <div class="col-md-8">
								  {!! Form::label('edit_print_color', 'PRINT COLORS :', ['class' => 'form_label']) !!}
								    <span id="edit_print_color"></span>
							   </div>
							   <div class="col-md-4">
							   </div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 edit-ratio-list-table-div">
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12 edit-single-list-table-div">
							</div>
						</div>
						<div class="row edit-meter-calculation-div">
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_compiled_by', 'COMPILED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_compiled_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'COMPILED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_barcode_read', 'BARCODE READ :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_barcode_read',$value = '', array('class' => 'form-control input-sm','placeholder' => 'BARCODE READ','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_operator', 'OPERATOR :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_operator',$value = '', array('class' => 'form-control input-sm','placeholder' => 'OPERATOR','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_checker', 'CHECKER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_checker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CHECKER','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_author', 'AUTHORIZED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_author',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AUTHORIZED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_axicon_verifier', 'AXICON VERIFIER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_axicon_verifier',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AXICON VERIFIER','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_approved_by', 'APPROVED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_approved_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'APPROVED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_packer', 'PACKER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_packer',$value = '', array('class' => 'form-control input-sm','placeholder' => 'PACKER','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_approval_date', 'APPROVAL DT :', ['class' => 'form_label']) !!}
										<div class="input-group edit_approval_date date datepicker" data-provide="datepicker" id="datePicker1">
											<input value="" id="edit_approval_date" name="edit_approval_date" type="text" class="form-control input-sm " required="" placeholder="APPROVAL DT">
											<div class="input-group-addon input-sm">
											 <span class="glyphicon glyphicon-th"> </span >
										    </div >
									    </div>
									    <div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_roll_issued_by', 'ROLL ISSUED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_roll_issued_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL ISSUED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_cutter', 'CUTTER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_cutter',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CUTTER','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback form-inline">
										{!! Form::label('edit_cartons', 'CARTONS :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_cartons',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CARTONS','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
                 </div>
                 <div class="modal-footer edit-jobcard-creation-modal-footer">
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
    /** [ Toggle bootstrap tooltip ] */
	$('.edit-jobcard').tooltip('show')
	$('.edit-jobcard').tooltip('toggle')
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
