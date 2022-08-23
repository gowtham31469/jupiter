<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Color Match</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('color_bar', 'Color Bar :', ['class' => 'form_label']) !!}
                        <div class="custom-control custom-radio">
                            <label class="custom-control custom-radio">
                                <input name="color_bar" type="radio" class="custom-control-input">
                                <span class="custom-control-description">Yes</span>
                            </label>
                        </div>

                        <div class="custom-control custom-radio">
                            <label class="custom-control custom-radio">
                                <input name="color_bar" type="radio" class="custom-control-input">
                                <span class="custom-control-description">No</span>
                            </label>
                        </div>
                    
                        <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('print_spec_bar', 'Print Spec Bar :', ['class' => 'form_label']) !!}
                    
                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="print_spec_bar" type="radio" class="custom-control-input">
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>

                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="print_spec_bar" type="radio" class="custom-control-input">
                            <span class="custom-control-description">No</span>
                        </label>
                    </div>
                    
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('tone_bar', 'Tone Bar :', ['class' => 'form_label']) !!}
                    
                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="tone_bar" type="radio" class="custom-control-input">
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>

                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="tone_bar" type="radio" class="custom-control-input">
                            <span class="custom-control-description">No</span>
                        </label>
                    </div>
                    
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('match_to', 'Match To :', ['class' => 'form_label']) !!}
                    
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="cust_sample" checked>
                        <label class="custom-control-label" for="cust_sample">Cust Sample</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="shade_card" checked>
                        <label class="custom-control-label" for="shade_card">Shade Card</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="delta_e" checked>
                        <label class="custom-control-label" for="delta_e">Delta E</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="mellow" checked>
                        <label class="custom-control-label" for="mellow">Mellow</label>
                    </div>
                    
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h4>Board & Print Colors</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_category', 'Board Category :', ['class' => 'form_label']) !!}
                    {{ Form::select('board_category',(['' => '-- Select Board Category --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_category','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_name', 'Board Name :', ['class' => 'form_label']) !!}
                    {{ Form::select('board_name',(['' => '-- Select Board Name --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_name','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('fsc_non_fsc', 'FSC / Non Fsc :', ['class' => 'form_label']) !!}
                    {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'fsc_non_fsc','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('gsm',$value = '', array('class' => 'form-control input-sm','placeholder' => 'GSM','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('thickness', 'Thickness :', ['class' => 'form_label']) !!}
                    {!! Form::number('thickness',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Thickness','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
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
            <h4>Board & Print Colors</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_category', 'Board Category :', ['class' => 'form_label']) !!}
                    {{ Form::select('board_category',(['' => '-- Select Board Category --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_category','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_name', 'Board Name :', ['class' => 'form_label']) !!}
                    {{ Form::select('board_name',(['' => '-- Select Board Name --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_name','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('fsc_non_fsc', 'FSC / Non Fsc :', ['class' => 'form_label']) !!}
                    {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'fsc_non_fsc','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('gsm',$value = '', array('class' => 'form-control input-sm','placeholder' => 'GSM','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('thickness', 'Thickness :', ['class' => 'form_label']) !!}
                    {!! Form::number('thickness',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Thickness','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeypress' => 'return isNumberKey(event)')) !!}
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
    </div>
</div>