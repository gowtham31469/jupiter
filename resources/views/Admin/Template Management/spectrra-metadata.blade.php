<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Color Match</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('color_bar', 'Color Bar :', ['class' => 'form_label']) !!}
                        {!! Form::radio('color_bar', true, '') !!}
                        <Strong>Yes</Strong>
                    
                        {!! Form::radio('color_bar', false, '') !!}
                        <Strong>No</Strong>
                    
                        <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('print_spec_bar', 'Print Spec Bar :', ['class' => 'form_label']) !!}
                    
                        {!! Form::radio('print_spec_bar', true, '') !!}
                        <Strong>Yes</Strong>
                    
                    
                        {!! Form::radio('print_spec_bar', false, '') !!}
                        <Strong>No</Strong>
                    
                        <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('tone_bar', 'Tone Bar :', ['class' => 'form_label']) !!}
                    
                        {!! Form::radio('tone_bar', true, '') !!}
                        <Strong>Yes</Strong>
                    
                    
                        {!! Form::radio('tone_bar', false, '') !!}
                        <Strong>No</Strong>
                    
                        <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('tone_bar', 'Match To :', ['class' => 'form_label']) !!}
                    
                        {!! Form::radio('tone_bar', true, '') !!}
                        <Strong>Yes</Strong>
                    
                    
                        {!! Form::radio('tone_bar', false, '') !!}
                        <Strong>No</Strong>
                    
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
                    {!! Form::label('width', 'WIDTH :', ['class' => 'form_label']) !!}
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
                <div class="form-group has-feedback">
                    {!! Form::label('length_side', 'LENGTH SIDE :', ['class' => 'form_label']) !!}
                    {!! Form::number('length_side',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH SIDE','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('excess', 'EXCESS % :', ['class' => 'form_label']) !!}
                    {!! Form::number('excess',$value = '', array('class' => 'form-control input-sm','placeholder' => 'EXCESS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'onkeyup' => 'return calculateExcess(this)', 'data-step-error' => 'Decimal values not allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
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
                    {!! Form::label('inv_no', 'INV NO :', ['class' => 'form_label']) !!}
                    {!! Form::text('inv_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'INV NO','maxlength' => '20')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('no_sticker', 'NO OF STICKERS :', ['class' => 'form_label']) !!}
                    {!! Form::number('no_sticker',$value = '', array('class' => 'form-control input-sm','placeholder' => 'NO OF STICKERS','min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'data-step-error' => 'Decimal values not allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumber(event)')) !!}
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
        </div>
        <div class="col-md-12 common-data-div hidden">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('tint_color', 'TINT COLORS :', ['class' => 'form_label']) !!}
                    {!! Form::text('tint_color',$value = '', array('class' => 'form-control input-sm','placeholder' => 'TINT COLORS', 'maxlength' => '20')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                {!! Form::label('customer_po', 'CUSTOMER PO :', ['class' => 'form_label']) !!}
                <span id="customer_po"></span>
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div>
</div>