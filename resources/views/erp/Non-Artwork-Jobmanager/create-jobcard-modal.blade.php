<div class="modal nat-jobcard-creation-modal fade" id="natJobcardCreationModal" tabindex="-1" role="dialog" aria-labelledby="natJobcardCreationModal">
	<div class="modal-dialog modal-lg nat-jobcard-creation-modal-dialog" role="document">
		<div class="modal-content nat-jobcard-creation-modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title nat-jobcard-modal-title" id="natJobcardCreationModal"></h4>
			</div>
			{!! Form::open(['id' => 'create_nat_jobcard','data-toggle' => 'validator','role' => 'form']) !!}
			<div class="modal-body nat-jobcard-creation-modal-body">
				<div class="container-fluid">
					<div id="nat_result"></div>
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
								<span name="work_order_id" id="work_order"></span>
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
									{!! Form::label('reference', 'REFERENCE :', ['class' => 'form_label']) !!}
									{!! Form::text('reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE', 'maxlength' => '20')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-4">
								{!! Form::label('order_type', 'ORDER TYPE :', ['class' => 'form_label']) !!}
								<span id="order_type"></span>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-4">
								{!! Form::label('buyer', 'BUYER :', ['class' => 'form_label']) !!}
								<span id="buyer"></span>
							</div>
							<div class="col-md-4">
								<div class="form-group has-feedback">
									{!! Form::label('disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
									{{ Form::select('disp_mode',(['' => '-- Select Dispatch Mode --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'disp_mode','required')) }}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-4">
								<input type="hidden" value="" id="nat_template_type">
								<input type="hidden" value="" id="jobcard_id">
								<input type="hidden" value="" id="crud_operation_type">
								<input type="hidden" value="" id="material_hierarchy">
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
									{!! Form::label('length', 'LENGTH MM :', ['class' => 'form_label']) !!}
									{!! Form::number('length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
									{!! Form::number('repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForNatStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('machine', 'MACHINE :', ['class' => 'form_label']) !!}
									{{ Form::select('machine',(['' => '-- Select Machine --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'machine','required', 'onchange' => 'return getMachineExcess()')) }}
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('aw_app_date', 'AW APPR DT :', ['class' => 'form_label']) !!}
									<div class="input-group aw_app_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="aw_app_date" name="aw_app_date" type="text" class="form-control input-sm " required="" placeholder="AW APPR DT">
										<div class="input-group-addon input-sm">
											<span class="glyphicon glyphicon-th"> </span>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('width', 'WIDTH MM :', ['class' => 'form_label']) !!}
									{!! Form::number('width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'WIDTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('roll_width', 'ROLL WIDTH :', ['class' => 'form_label']) !!}
									{!! Form::number('roll_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'ROLL WIDTH','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('film_no', 'FILM NO :', ['class' => 'form_label']) !!}
									{!! Form::number('film_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'FILM NO','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumber(event)')) !!}
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
											<span class="glyphicon glyphicon-th"> </span>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
									{!! Form::number('length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForNatStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('no_repeats', 'NO OF REPEATS :', ['class' => 'form_label']) !!}
									{!! Form::number('no_repeats',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF REPEATS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeyup' => 'return mtrsForNatStickers()', 'onkeypress' => 'return isNumber(event)')) !!}
										<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('teeth_no', 'TEETH NO :', ['class' => 'form_label']) !!}
									{{ Form::select('teeth_no',(['' => '-- Select Teeth No --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'teeth_no','required')) }}
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('act_del_date', 'ACT D.DT :', ['class' => 'form_label']) !!}
									<div class="input-group act_del_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="act_del_date" name="act_del_date" type="text" class="form-control input-sm " placeholder="ACT D.DT">
										<div class="input-group-addon input-sm">
											<span class="glyphicon glyphicon-th"> </span>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('finish_type', 'FINISH TYPE :', ['class' => 'form_label']) !!}
									{{ Form::select('finish_type',(['' => '-- Select Finish Type --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'finish_type','required')) }}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('print_color', 'PRINT COLORS :', ['class' => 'form_label']) !!}
									{!! Form::text('print_color',$value = '', array('class' => 'form-control input-sm','placeholder' => 'PRINT COLORS', 'maxlength' => '20')) !!}
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group has-feedback">
									{!! Form::label('excess', 'EXCESS %:', ['class' => 'form_label']) !!}
									<span id="excess"></span>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12 ratio-list-table-div">
						</div>
					</div>
					<div class="order-details-div">
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
										<input value="" id="approval_date" name="approval_date" type="text" class="form-control input-sm " placeholder="APPROVAL DT">
										<div class="input-group-addon input-sm">
											<span class="glyphicon glyphicon-th"> </span>
										</div>
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
					<div class="row">
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
									{!! Form::label('invoice_date', 'INVOICE DATE :', ['class' => 'form_label']) !!}
									<div class="input-group invoice_date date datepicker" data-provide="datepicker" id="datePicker1">
										<input value="" id="invoice_date" name="invoice_date" type="text" class="form-control input-sm " placeholder="INVOICE DATE">
										<div class="input-group-addon input-sm">
											<span class="glyphicon glyphicon-th"> </span>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3">
							</div>
							<div class="col-md-3">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer nat-jobcard-creation-modal-footer">
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				{!! Form::button('Print', ['class' => 'btn btn-primary btn-sm','type' => 'submit', 'id' => 'jc_print_btn', 'onclick'  => 'return validateNatJCForm()'] ) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>