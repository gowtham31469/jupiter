<style>
    .table-section {
        padding: 0pt 5.4pt 0pt 5.4pt;
        width: 20%;
    }

    .table-name {
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }

    .cust-details-table tr td,
    .for-production-table tr td {
        width: 30%;
    }

    .spectrra-jc-main-table tr p {
        margin: 0 0 0 0;
    }


    #createSpectrraJCModal {
        overflow-x: auto;
    }

    .create-spectrra-jc-modal-content{
        width: fit-content;
    }

</style>
<div class="modal fade" id="createSpectrraJCModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content create-spectrra-jc-modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="spectrra-create-jc-modal-title"></h4>
			</div>
			<div class="modal-body create-spectrra-jc-modal-body">
				<div class="container-fluid">
					<span id="spectrra_jobcard_result"></span>
					{!! Form::open(['method' => 'post', 'id' => 'create_spectrra_jc_form','data-toggle' => 'validator','role' => 'form']) !!}
					<div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <p><strong id="sp_jc_company_name"></strong></p>
                                <p id="sp_company_address"></p>
                            </div>
                            <div class="col-md-4">
								<input type="hidden" value="" id="sp_erp_id">
								<input type="hidden" value="" id="jobcard_id">
								<input type="hidden" value="" id="crud_operation_type">
								<input type="hidden" value="" id="material_hierarchy">
							</div>
                            <div class="col-md-4">
                                    <img src="" class="pull-right " id="sp_company_logo" style="height: 78px;margin-right: 60px;margin-bottom: 0px;margin-top: -5px;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                {!! Form::label('sp_work_order', 'WORKORDER :', ['class' => 'form_label']) !!}
                                <span id="sp_work_order" name="sp_work_order"></span>
                            </div>
                            <div class="col-md-3">
                                    {!! Form::label('sp_today_date', 'DATE :', ['class' => 'form_label']) !!}
                                <span id="sp_today_date" name="sp_today_date"></span>
                            </div>
                            <div class="col-md-3">
                                {!! Form::label('sp_article', 'ARTICLE :', ['class' => 'form_label']) !!}
                                <span id="sp_article" name="sp_article"></span>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    {!! Form::label('sp_reference', 'REFERENCE :', ['class' => 'form_label']) !!}
                                    {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Reference','required','maxlength' => '20')) !!}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                        <table class="spectrra-jc-main-table">
                            <tr>
                                <td class="table-section" colspan="1" rowspan="1">
                                    <p class="table-name text-center">DESIGN DETAILS</p>
                                    <table  class="table table-bordered table-responsive design-details-table"  style="font-size: 12px;" border="0.2">
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>ColorBar</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <p>Printspec Bar</p>
                                            </td>
                                            <td colspan="4" rowspan="1">
                                                <p>20%50%70% ToneBar</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('color_bar',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'color_bar','required', 'name' => 'color_bar')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('print_spec_bar',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'print_spec_bar','name' => 'print_spec_bar')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="4" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('tone_bar',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'tone_bar','name' => 'tone_bar')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1" style="width: 25%;">
                                                <p>Match To</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('color_match',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'color_match','data-actions-box' => 'true', 'multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...', 'name' => 'color_match[]', 'required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Front Colors</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('front_colors',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Front Colors','required','data-required-error' => 'Required', 'name' => 'front_colors', 'id' => 'front_colors')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Back Colors</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('back_colors',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Back Colors','required','maxlength' => '20', 'data-required-error' => 'Required', 'name' => 'back_colors', 'id' => 'back_colors')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Board Categ.</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('board_category',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'board_category','data-live-search'=>'true', 'name' => 'board_category', 'required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Board Name</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('board_name',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'board_name','data-live-search'=>'true', 'name' => 'board_name', 'required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>FSC / Non FSC</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'fsc_non_fsc', 'required', 'name' => 'fsc_non_fsc')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>GSM/Thickness (&micro;)</p>
                                            </td>
                                            <td colspan="3" rowspan="1" style="width: 46%;">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('gsm_value',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Gsm','required','maxlength' => '20', 'data-required-error' => 'Required', 'name' => 'gsm_value', 'id' => 'gsm_value')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('thickness',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Thickness','required','maxlength' => '20', 'data-required-error' => 'Required', 'name' => 'thickness', 'id' => 'thickness')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Brd Size(in)</p>
                                            </td>
                                            <td colspan="3" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('board_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','maxlength' => '20', 'name' => 'board_size_length', 'id' => 'board_size_length')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                {!! Form::text('board_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','maxlength' => '20', 'name' => 'board_size_width', 'id' => 'board_size_width')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Print Size(in)</p>
                                            </td>
                                            <td colspan="3" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('print_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','maxlength' => '20', 'name' => 'print_size_length', 'id' => 'print_size_length')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('print_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','maxlength' => '20', 'name' => 'print_size_width', 'id' => 'print_size_width')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Design Size</p>
                                            </td>
                                            <td colspan="3" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('design_size_length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Length','maxlength' => '20', 'name' => 'design_size_length', 'id' => 'design_size_length')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('design_size_width',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Width','maxlength' => '20', 'name' => 'design_size_width', 'id' => 'design_size_width')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Cut As </p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('cut_as_1',$value = '', array('class' => 'form-control input-sm','maxlength' => '20', 'name' => 'cut_as_1', 'id' => 'cut_as_1')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="2" rowspan="1" style="width: 5%;">
                                                <p>By</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('cut_as_2',$value = '', array('class' => 'form-control input-sm','maxlength' => '20', 'name' => 'cut_as_2', 'id' => 'cut_as_2')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Repeats</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('repeats',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Repeats','required','maxlength' => '20', 'data-required-error' => 'Required', 'name' => 'repeats', 'id' => 'repeats')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Old Plate </p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>New Plate </p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Die No.</p>
                                            </td>
                                            <td colspan="5" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('die_no',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Die No','required','maxlength' => '10', 'data-required-error' => 'Required', 'name' => 'die_no', 'id' => 'die_no')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="table-section" colspan="2" rowspan="1">
                                    <p class="table-name text-center">PROCESS DETAILS</p>
                                    <table  class="table table-bordered table-responsive process-details-table"  style="font-size: 12px;" border="0.2">
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Cutting</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('cutting',(['' => '-- Select Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'cutting', 'name' => 'cutting', 'data-required-error' => 'Required','required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Printing</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('printing',(['' => '-- Select Printing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'printing', 'name' => 'printing', 'data-required-error' => 'Required','required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>UV</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('uv',(['' => '-- Select UV --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'uv', 'name' => 'uv')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Coating</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('coating',(['' => '-- Select Coating --', 'Matt' => 'Matt', 'Gloss' => 'Gloss', 'No' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'coating','name' => 'coating')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Lamination</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('lamination_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Lamination','maxlength' => '20','id' => 'lamination_spec','name' => 'lamination_spec')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="2">
                                                <p c57">Pasting</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>Board</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('pasting_board',(['' => '-- Select Board --']),'',array('class' => 'form-control input-sm selectpicker','id' => 'pasting_board','name' => 'pasting_board')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>GSM</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('pasting_gsm',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Gsm','maxlength' => '20','id' => 'pasting_gsm','name' => 'pasting_gsm')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Window Patch (&micro;)</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('window_patch',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Window Patch','maxlength' => '20','id' => 'window_patch','name' => 'window_patch')) !!}
                                                    
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Folder Gluer</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('folder_gluer',(['' => '-- Select Folder Gluer --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'folder_gluer','name' => 'folder_gluer')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Die Cutting</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('die_cutting',(['' => '-- Select Die Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'die_cutting','name' => 'die_cutting', 'data-required-error' => 'Required','required')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Embos/ Debos</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('embossing',(['' => '-- Select Embossing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'embossing','name' => 'embossing')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('debossing',(['' => '-- Select Debossing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'debossing','name' => 'debossing')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Perforation</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('perforation',(['' => '-- Select Perforation --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'perforation','name' => 'perforation')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Hole</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('hole_size',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Hole Size','maxlength' => '20','id' => 'hole_size','name' => 'hole_size')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Foil</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {{ Form::select('foil',(['' => '-- Select Foil --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'foil','name' => 'foil')) }}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>String</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('string_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'String','maxlength' => '20','id' => 'string_spec','name' => 'string_spec')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Eyelet</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <div class="form-group has-feedback">
                                                    {!! Form::text('eyelet_spec',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Eyelet','maxlength' => '20','id' => 'eyelet_spec','name' => 'eyelet_spec')) !!}
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Thread</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="1">
                                                <p>Scoring</p>
                                            </td>
                                            <td colspan="2" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-section" colspan="1" rowspan="1">
                                    <p class="table-name text-center">CUST DETAILS</p>
                                    <table  class="table table-bordered table-responsive cust-details-table"  style="font-size: 12px;" border="0.2">
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Cust</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_customer_name" name="sp_customer_name">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>StyleNum</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_style_no" name="sp_style_no">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>PO No</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_customer_po" name="sp_customer_po">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>PO Dt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_po_verify_date" name="sp_po_verify_date">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Buyer</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_buyer_name" name="sp_buyer_name">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Order No</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_order_no" name="sp_order_no">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Order Dt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_order_date" name="sp_order_date">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>CustDelDt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_delivery_date" name="sp_delivery_date">&nbsp;</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="table-section" colspan="2" rowspan="1">
                                    <p class="table-name text-center">FOR PRODUCTION</p>
                                    <table  class="table table-bordered table-responsive for-production-table"  style="font-size: 12px;" border="0.2">
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Order Qnty</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p id="sp_order_qty" name="sp_order_qty">&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Stock Avlb</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p><b class="c56">Less Stk</b></p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Print Sheets</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Excess</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Setting</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Total Sheets</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Full Board</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-section" colspan="2" rowspan="1">
                                    <p class="table-name text-center">PROCESS OUTPUT </p>
                                    <table class="table table-bordered table-responsive process-output-table"  style="font-size: 12px;" border="0.2">
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p c68">Process</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>Mc No</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>Good Sheet</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>Date</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>SIgn</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Cutting</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Printing</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Lamination</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Pasting</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Window Patching</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Folder Gluer</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Die Cutting</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>UV</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="table-section" colspan="2" rowspan="1">
                                    <p class="table-name text-center">DISPATCH DETAILS</p>
                                    <table class="table table-bordered table-responsive dispatch-details-table"  style="font-size: 12px;" border="0.2" >
                                        <tr>
                                            <td colspan="1" rowspan="1" style="width: 25%;">
                                                <p>PackingDt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>InvNo</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>InvDt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>DispDt</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Qnty</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Weight</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>QC Sign</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>Stock</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                            <td colspan="1" rowspan="1">
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </div>
                    </div>
				</div>
                <div class="edit-spectra-order-details-div"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				{{ Form::submit('Save', array('class' => 'btn btn-primary btn-sm pull-right', 'id' => 'create_spectrra_jc_btn')) }}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>