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
	<li><a href="{{ route('jobcards-list') }}"  class="active">Jobcards List</a></li>
</ul>

@endsection @section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12" style="padding-top: 25px;">
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
					<div class="filter-window-jobcard-list">
					{!! Form::open(['id' => 'jobcard_filter_form','data-toggle' => 'validator','role' => 'form']) !!}
					<h5><strong>Jobcard List Filter</strong></h5>
					<div class="col-xs-6 col-sm-4">
					    <div class="form-group has-feedback">
							{{ Form::select('buyer_filter', (['' => 'SELECT A BUYER']+ $buyers), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'buyer_filter', 'required', 'onchange' => 'getCustomersList(this)')) }}
						    <div class="help-block with-errors"></div>
					    </div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('customer_filter', (['' => 'SELECT A CUSTOMER']), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'customer_filter', 'required', 'onchange' => 'getJobcardIDList(this)')) }}
						    <div class="help-block with-errors"></div>
					    </div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('jobcard_id_filter',(['' => 'SELECT A JOBCARD ID']),'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true','id' => 'jobcard_id_filter','required')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
						<div class="filter-actions-erp">
							{!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm pull-right','type' => 'submit', 'style' => 'margin-top: 0.5em;'] ) !!}
							<div class="clearfix"></div>
						</div>
					{!! Form::close() !!}
					</div>
					<br>
                    <div class="col" id="jobcard-lists-table">
                        <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
                        <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
                            <table id="jobcard-list-table" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Jobcard ID</th>
                                        <th>Customer Name</th>
                                        <th>Buyer Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="jobcard_list_table_body">
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
									 <div class="form-group has-feedback">
										{!! Form::label('edit_material', 'MATERIAL :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_material',$value = '', array('class' => 'form-control input-sm','placeholder' => 'MATERIAL','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
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
									<div class="form-group has-feedback">
										{!! Form::label('edit_style_no', 'STYLENUM :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_style_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'STYLENUM', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									 </div>	
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
								    <div class="form-group has-feedback">
										{!! Form::label('edit_reference', 'REFERENCE :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									 </div>	
								</div>
								<div class="col-md-4">
									 <div class="form-group has-feedback">
										{!! Form::label('edit_disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
										{{ Form::select('edit_disp_mode',(['' => '-- Select Dispatch Mode --']+ $disp_modes),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_disp_mode','required')) }}
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
									<div class="form-group has-feedback">
									{!! Form::label('edit_length', 'LENGTH :', ['class' => 'form_label']) !!}
									{!! Form::number('edit_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
									<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickersEdit()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_machine', 'MACHINE :', ['class' => 'form_label']) !!}
										{{ Form::select('edit_machine',(['' => '-- Select Machine --']+ $machines),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_machine','required')) }}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback">
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
									<div class="form-group has-feedback">
									{!! Form::label('edit_width', 'WIDTH :', ['class' => 'form_label']) !!}
									{!! Form::number('edit_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'WIDTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
									<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_roll_width', 'ROLL WIDTH :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_roll_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL WIDTH','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_film_no', 'FILM NO :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_film_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FILM NO','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								   <div class="form-group has-feedback">
									{!! Form::label('edit_invoice_date', 'INVOICE DATE :', ['class' => 'form_label']) !!}
									<div class="input-group edit_invoice_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="edit_invoice_date" name="edit_invoice_date" type="text" class="form-control input-sm " placeholder="INVOICE DATE">
										<div class="input-group-addon input-sm">
										<span class="glyphicon glyphicon-th"> </span >
									</div >
								  </div>
								  <div class="help-block with-errors"></div>
								  </div>
								</div>
								<div class="col-md-3">
								   <div class="form-group has-feedback">
										{!! Form::label('edit_length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								   </div>
								</div>
								<div class="col-md-3">
							        <div class="form-group has-feedback">
										{!! Form::label('edit_excess', 'EXCESS % :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_excess',$value = '', array('class' => 'form-control input-sm','placeholder' => 'EXCESS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'onkeyup' => 'return calculateExcessEdit(this)', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_teeth_no', 'TEETH NO :', ['class' => 'form_label']) !!}
										{{ Form::select('edit_teeth_no',(['' => '-- Select Teeth No --']+ $teeth_nos),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_teeth_no','required')) }}
										<div class="help-block with-errors"></div>
								    </div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_inv_no', 'INV NO :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_inv_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'INV NO','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_no_sticker', 'NO OF STICKERS :', ['class' => 'form_label']) !!}
										{!! Form::number('edit_no_sticker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF STICKERS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error' => 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_finish_type', 'FINISH TYPE :', ['class' => 'form_label']) !!}
										{{ Form::select('edit_finish_type',(['' => '-- Select Finish Type --']+ $finish_types),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_finish_type','required')) }}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
							   <div class="col-md-3">
								    <div class="form-group has-feedback">
										{!! Form::label('edit_print_color', 'PRINT COLORS :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_print_color',$value = '', array('class' => 'form-control input-sm','placeholder' => 'STYLENUM', 'maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
							   </div>
							   <div class="col-md-9">
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
									<div class="form-group has-feedback">
										{!! Form::label('edit_compiled_by', 'COMPILED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_compiled_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'COMPILED BY','required','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_barcode_read', 'BARCODE READ :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_barcode_read',$value = '', array('class' => 'form-control input-sm','placeholder' => 'BARCODE READ','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_operator', 'OPERATOR :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_operator',$value = '', array('class' => 'form-control input-sm','placeholder' => 'OPERATOR','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_checker', 'CHECKER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_checker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CHECKER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_author', 'AUTHORIZED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_author',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AUTHORIZED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_axicon_verifier', 'AXICON VERIFIER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_axicon_verifier',$value = '', array('class' => 'form-control input-sm','placeholder' => 'AXICON VERIFIER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_approved_by', 'APPROVED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_approved_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'APPROVED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_packer', 'PACKER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_packer',$value = '', array('class' => 'form-control input-sm','placeholder' => 'PACKER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_approval_date', 'APPROVAL DT :', ['class' => 'form_label']) !!}
										<div class="input-group edit_approval_date date datepicker" data-provide="datepicker" id="datePicker1">
											<input value="" id="edit_approval_date" name="edit_approval_date" type="text" class="form-control input-sm "  placeholder="APPROVAL DT">
											<div class="input-group-addon input-sm">
											 <span class="glyphicon glyphicon-th"> </span >
										    </div >
									    </div>
									    <div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_roll_issued_by', 'ROLL ISSUED BY :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_roll_issued_by',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL ISSUED BY','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_cutter', 'CUTTER :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_cutter',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CUTTER','maxlength' => '20')) !!}
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group has-feedback">
										{!! Form::label('edit_cartons', 'CARTONS :', ['class' => 'form_label']) !!}
										{!! Form::text('edit_cartons',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CARTONS','maxlength' => '20')) !!}
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

	/** [ Refresh  bootstrap selectpicker and fprm ] */
	$('#jobcard_filter_form').trigger("reset");
	$('#buyer_filter').selectpicker('refresh');


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
