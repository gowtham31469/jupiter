<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4>Color Match</h4>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('color_bar', 'Color Bar :', ['class' => 'form_label']) !!}
                        <input type="hidden" name="template_id_spectrra" id="template_id_spectrra">
                        <input type="hidden" name="buyer_spectrra" id="buyer_spectrra">
                        <div class="custom-control custom-radio">
                            <label class="custom-control custom-radio">
                                <input name="color_bar" type="radio" class="custom-control-input" id="color_bar_yes" value="1">
                                <span class="custom-control-description">Yes</span>
                            </label>
                        </div>

                        <div class="custom-control custom-radio">
                            <label class="custom-control custom-radio">
                                <input name="color_bar" type="radio" class="custom-control-input" id="color_bar_no" value="0" checked>
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
                            <input name="print_spec_bar" type="radio" class="custom-control-input" id="print_spec_bar_yes" value="1">
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>

                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="print_spec_bar" type="radio" class="custom-control-input" id="print_spec_bar_no" value="0" checked>
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
                            <input name="tone_bar" type="radio" class="custom-control-input" id="tone_bar_yes" value="1">
                            <span class="custom-control-description">Yes</span>
                        </label>
                    </div>

                    <div class="custom-control custom-radio">
                        <label class="custom-control custom-radio">
                            <input name="tone_bar" type="radio" class="custom-control-input" id="tone_bar_no" value="0" checked>
                            <span class="custom-control-description">No</span>
                        </label>
                    </div>
                    
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('color_match', 'Color Match :', ['class' => 'form_label']) !!}
                    {{ Form::select('color_match',([]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'color_match','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...', 'name' => 'color_match[]')) }}
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
                    {{ Form::select('board_category',([]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_category','data-live-search'=>'true')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_name', 'Board Name :', ['class' => 'form_label']) !!}
                    {{ Form::select('board_name',([]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board_name','data-live-search'=>'true')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('fsc_non_fsc', 'FSC / Non Fsc :', ['class' => 'form_label']) !!}
                    {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'fsc_non_fsc', 'required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('gsm',$value = '', array('class' => 'form-control input-sm','placeholder' => 'GSM','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                        <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('thickness', 'Thickness ( in microns ):', ['class' => 'form_label']) !!}
                    {!! Form::number('thickness',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Thickness','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {!! Form::number('board_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('board_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('board_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {!! Form::number('print_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('print_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('print_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {!! Form::number('design_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('design_size_width', 'width :', ['class' => 'form_label']) !!}
                    {!! Form::number('design_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {!! Form::number('cut_as_1',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-1"><h5 style="margin-left: 10px;margin-top: 30px;">By</h5></div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('cut_as_2', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
                    {!! Form::number('cut_as_2',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {{ Form::select('front_color_count',(['' => '-- Select Front Color Count --', '1' => '1', '2' => '2', '3' => '3', '4' => '4']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'front_color_count')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        {!! Form::label('back_color_count', 'Back Color Count :', ['class' => 'form_label']) !!}
                        {{ Form::select('back_color_count',(['' => '-- Select Back Color Count --', '1' => '1']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'back_color_count')) }}
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
                    {!! Form::number('repeats',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Repeats','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('no_of_plates', 'No of Plates :', ['class' => 'form_label']) !!}
                    {!! Form::number('no_of_plates',$value = '', array('class' => 'form-control input-sm','placeholder' => 'No of Plates','min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1')) !!}
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
                    {{ Form::select('cutting',(['' => '-- Select Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'cutting')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('printing', 'Printing :', ['class' => 'form_label']) !!}
                    {{ Form::select('printing',(['' => '-- Select Printing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'printing')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('coating', 'Coating :', ['class' => 'form_label']) !!}
                    {{ Form::select('coating',(['' => '-- Select Coating --', 'matt' => 'Matt', 'gloss' => 'Gloss', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'coating')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('uv', 'UV :', ['class' => 'form_label']) !!}
                    {{ Form::select('uv',(['' => '-- Select UV --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'uv')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('lamination', 'Lamination :', ['class' => 'form_label']) !!}
                    {{ Form::select('lamination',(['' => '-- Select Lamination --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'lamination')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 lamination-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('lamination_spec', 'Lamination Spec:', ['class' => 'form_label']) !!}
                    {{ Form::select('lamination_spec',(['' => '-- Select Lamination Spec --', 'matt' => 'Matt', 'gloss' => 'Gloss']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'lamination_spec')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('pasting', 'Pasting :', ['class' => 'form_label']) !!}
                    {{ Form::select('pasting',(['' => '-- Select Pasting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'pasting')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 pasting-board-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('pasting_board', 'Board :', ['class' => 'form_label']) !!}
                    {{ Form::select('pasting_board',(['' => '-- Select Board --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'board')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('die_cutting', 'Die Cutting :', ['class' => 'form_label']) !!}
                    {{ Form::select('die_cutting',(['' => '-- Select Die Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'die_cutting')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('window_patch', 'Window Patch :', ['class' => 'form_label']) !!}
                    {{ Form::select('window_patch',(['' => '-- Select Window Patch --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'window_patch')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 window-patch-gsm-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('window_patch_gsm', 'GSM :', ['class' => 'form_label']) !!}
                    {!! Form::number('window_patch_gsm',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1', 'placeholder' => 'GSM')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('folder_gluer', 'Folder Gluer :', ['class' => 'form_label']) !!}
                    {{ Form::select('folder_gluer',(['' => '-- Select Folder Gluer --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'folder_gluer')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('embossing', 'Embossing :', ['class' => 'form_label']) !!}
                    {{ Form::select('embossing',(['' => '-- Select Embossing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'embossing')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('debossing', 'Debossing :', ['class' => 'form_label']) !!}
                    {{ Form::select('debossing',(['' => '-- Select Debossing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'debossing')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('perforation', 'Perforation :', ['class' => 'form_label']) !!}
                    {{ Form::select('perforation',(['' => '-- Select Perforation --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'perforation')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('hole', 'Hole :', ['class' => 'form_label']) !!}
                    {{ Form::select('hole',(['' => '-- Select Hole --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'hole')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 hole-size-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('hole_size', 'Hole Size ( in mm ):', ['class' => 'form_label']) !!}
                    {!! Form::number('hole_size',$value = '', array('class' => 'form-control input-sm', 'min' => '1','max' => '999', 'data-max-error' => 'Value should be <= 999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '1', 'placeholder' => 'Hole Size')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('eyelet', 'Eyelet:', ['class' => 'form_label']) !!}
                    {{ Form::select('eyelet',(['' => '-- Select Eyelet --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'eyelet')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 eyelet-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('eyelet_spec', 'Eyelet Spec:', ['class' => 'form_label']) !!}
                    {!! Form::text('eyelet_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Eyelet Spec')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('string_required', 'String:', ['class' => 'form_label']) !!}
                    {{ Form::select('string_required',(['' => '-- Select String --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'string_required')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3 string-spec-div hidden">
                <div class="form-group has-feedback">
                    {!! Form::label('string_spec', 'String Spec:', ['class' => 'form_label']) !!}
                    {!! Form::text('string_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'String Spec')) !!}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group has-feedback">
                    {!! Form::label('foil', 'Foil:', ['class' => 'form_label']) !!}
                    {{ Form::select('foil',(['' => '-- Select Foil --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'foil')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>

    </div>
</div>