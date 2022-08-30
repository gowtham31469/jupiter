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
                                <input name="color_bar" type="radio" class="custom-control-input" checked>
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
                            <input name="print_spec_bar" type="radio" class="custom-control-input" checked>
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
                            <input name="tone_bar" type="radio" class="custom-control-input" checked>
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
                        <input type="checkbox" class="custom-control-input match-to-checkbox" id="cust_sample">
                        <label class="custom-control-label" for="cust_sample">Cust Sample</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input match-to-checkbox" id="shade_card">
                        <label class="custom-control-label" for="shade_card">Shade Card</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input match-to-checkbox" id="delta_e">
                        <label class="custom-control-label" for="delta_e">Delta E</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input match-to-checkbox" id="mellow">
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
                    {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --', 'yes' => 'Yes', 'no' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'fsc_non_fsc','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('gsm',$value = '', array('class' => 'form-control input-sm','placeholder' => 'GSM','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('thickness', 'Thickness ( in microns ):', ['class' => 'form_label']) !!}
                    {!! Form::number('thickness',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Thickness','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <label for="board_size_label" class="form_label pull-left" style="margin-top: 25px;">Board Size  ( in inches ) :</label>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_size_length', 'Length :', ['class' => 'form_label']) !!}
                    {!! Form::number('board_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('board_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <label for="print_size_label" class="form_label pull-left" style="margin-top: 25px;">Print Size ( in inches ) :</label>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('print_size_length', 'Length :', ['class' => 'form_label']) !!}
                    {!! Form::number('print_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('print_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('print_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <label for="design_size_label" class="form_label pull-left" style="margin-top: 25px;">Design Size ( in inches ) :</label>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('design_size_length', 'Length :', ['class' => 'form_label']) !!}
                    {!! Form::number('design_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('design_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('design_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <label for="cut_as_label" class="form_label pull-left" style="margin-top: 25px;">Cut As :</label>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('cut_as_1', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
                    {!! Form::number('cut_as_1',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-1"><h5 style="margin-left: 10px;margin-top: 30px;">By</h5></div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('cut_as_2', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
                    {!! Form::number('cut_as_2',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <label for="number_of_color_label" class="form_label pull-left" style="margin-top: 25px;">Number Of Colors :</label>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('front_color_count', 'Front Color Count :', ['class' => 'form_label']) !!}
                    {{ Form::select('front_color_count',(['' => '-- Select Front Color Count --', '1' => '1', '2' => '2', '3' => '3', '4' => '4']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'front_color_count','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        {!! Form::label('back_color_count', 'Back Color Count :', ['class' => 'form_label']) !!}
                        {{ Form::select('back_color_count',(['' => '-- Select Back Color Count --', '1' => '1']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'back_color_count','required')) }}
                        <div class="help-block with-errors"></div>
                    </div>
            </div>
        </div>

        <div class="col-md-12 front-colors-div">
        </div>

        <div class="col-md-12 back-colors-div">
        </div>

        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('repeats', 'Repeats :', ['class' => 'form_label']) !!}
                    {!! Form::number('repeats',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Repeats','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('no_of_plates', 'No of Plates :', ['class' => 'form_label']) !!}
                    {!! Form::number('no_of_plates',$value = '', array('class' => 'form-control input-sm','placeholder' => 'No of Plates','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <h4>Process Involved</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('cutting', 'Cutting :', ['class' => 'form_label']) !!}
                    {{ Form::select('cutting',(['' => '-- Select Cutting --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'cutting','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('printing', 'Printing :', ['class' => 'form_label']) !!}
                    {{ Form::select('printing',(['' => '-- Select Printing --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'printing','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('coating', 'Coating :', ['class' => 'form_label']) !!}
                    {{ Form::select('coating',(['' => '-- Select Coating --', 'matt' => 'Matt', 'gloss' => 'Gloss', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'coating','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('uv', 'UV :', ['class' => 'form_label']) !!}
                    {{ Form::select('uv',(['' => '-- Select UV --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'uv','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('lamination', 'Lamination :', ['class' => 'form_label']) !!}
                    {{ Form::select('lamination',(['' => '-- Select Lamination --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'lamination','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 lamination-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('lamination_spec', 'Lamination Spec:', ['class' => 'form_label']) !!}
                    {{ Form::select('lamination_spec',(['' => '-- Select Lamination Spec --', 'matt' => 'Matt', 'gloss' => 'Gloss']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'lamination_spec','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('pasting', 'Pasting :', ['class' => 'form_label']) !!}
                    {{ Form::select('pasting',(['' => '-- Select Pasting --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'pasting','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 pasting-board-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('pasting_board', 'Board :', ['class' => 'form_label']) !!}
                    {{ Form::select('pasting_board',(['' => '-- Select Board --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('die_cutting', 'Die Cutting :', ['class' => 'form_label']) !!}
                    {{ Form::select('die_cutting',(['' => '-- Select Die Cutting --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'die_cutting','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('window_patch', 'Window Patch :', ['class' => 'form_label']) !!}
                    {{ Form::select('window_patch',(['' => '-- Select Window Patch --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'window_patch','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 window-patch-gsm-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('window_patch_gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('window_patch_gsm',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1', 'placeholder' => 'GSM','required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('folder_gluer', 'Folder Gluer :', ['class' => 'form_label']) !!}
                    {{ Form::select('folder_gluer',(['' => '-- Select Folder Gluer --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'folder_gluer','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('embossing', 'Embossing :', ['class' => 'form_label']) !!}
                    {{ Form::select('embossing',(['' => '-- Select Embossing --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'embossing','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('debossing', 'Debossing :', ['class' => 'form_label']) !!}
                    {{ Form::select('debossing',(['' => '-- Select Debossing --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'debossing','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('perforation', 'Perforation :', ['class' => 'form_label']) !!}
                    {{ Form::select('perforation',(['' => '-- Select Perforation --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'perforation','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('hole', 'Hole :', ['class' => 'form_label']) !!}
                    {{ Form::select('hole',(['' => '-- Select Hole --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'hole','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 hole-size-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('hole_size', 'Hole Size ( in mm ):', ['class' => 'form_label']) !!}
                    {!! Form::number('hole_size',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1', 'placeholder' => 'Hole Size', 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('eyelet', 'Eyelet:', ['class' => 'form_label']) !!}
                    {{ Form::select('eyelet',(['' => '-- Select Eyelet --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'eyelet','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 eyelet-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('eyelet_spec', 'Eyelet Spec:', ['class' => 'form_label']) !!}
                    {!! Form::text('eyelet_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Eyelet Spec', 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('string_required', 'String:', ['class' => 'form_label']) !!}
                    {{ Form::select('string_required',(['' => '-- Select String --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'string_required','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 string-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('string_spec', 'String Spec:', ['class' => 'form_label']) !!}
                    {!! Form::text('string_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'String Spec', 'required')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('foil', 'Foil:', ['class' => 'form_label']) !!}
                    {{ Form::select('foil',(['' => '-- Select Foil --', 'yes' => 'Yes', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'foil','required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

    </div>
</div>