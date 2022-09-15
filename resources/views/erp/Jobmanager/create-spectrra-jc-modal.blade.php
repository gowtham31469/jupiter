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

    .design-details-table-section{
        padding: 0pt 5.4pt 0pt 5.4pt;
        width: 40%;
    }

    #create_spectrra_jc_form input {
        height: 20px;
    }

</style>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <p><strong id="sp_jc_company_name"></strong></p>
            <p id="sp_company_address"></p>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
                <img src="" class="pull-right " id="sp_company_logo" style="width: 150px;height: 60px;">
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
        <!-- <div class="col-md-4">
        </div> -->
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
                {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20')) !!}
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>
</div>
<hr>
<table class="spectrra-jc-main-table">
    <tr>
        <td class="design-details-table-section" colspan="1" rowspan="1">
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
                            {{ Form::select('machine',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'machine','required')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('machine',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'machine','required')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="4" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('machine',(['1' => 'Yes', '0' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'machine','required')) }}
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
                            {{ Form::select('color_match',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'color_match','data-actions-box' => 'true', 'multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...', 'name' => 'color_match[]')) }}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {{ Form::select('board_category',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'board_category','data-live-search'=>'true')) }}
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
                            {{ Form::select('board_name',([]),'',array('class' => 'form-control input-sm selectpicker','id' => 'board_name','data-live-search'=>'true')) }}
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
                            {{ Form::select('fsc_non_fsc',(['' => '-- Select FSC / Non Fsc --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'fsc_non_fsc', 'required')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>GSM/Thickness (&micro;)</p>
                    </td>
                    <td colspan="3" rowspan="1" style="width: 25%;">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                    <td colspan="2" rowspan="1" style="width: 5%;">
                        <p>By</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                        <p>&nbsp;</p>
                    </td>
                </tr>
            </table>
        </td>
        <td class="table-section" colspan="2" rowspan="1">
            <p class="table-name text-center">PROCESS DETAILS</p>
            <table  class="table table-bordered table-responsive process-details-table"  style="font-size: 12px;" border="0.2">
                <tr>
                    <td colspan="2" rowspan="1" style="width: 40%;">
                        <p>Cutting</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('cutting',(['' => '-- Select Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'cutting')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Printing</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('printing',(['' => '-- Select Printing --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'printing')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>UV</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('uv',(['' => '-- Select UV --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'uv')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Coating</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('coating',(['' => '-- Select Coating --', 'matt' => 'Matt', 'gloss' => 'Gloss', 'no' => 'No']),'',array('class' => 'form-control input-sm selectpicker','id' => 'coating')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Lamination</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
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
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('pasting_board',(['' => '-- Select Board --']),'',array('class' => 'form-control input-sm selectpicker','id' => 'board')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>GSM</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Window Patch (&micro;)</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Folder Gluer</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('folder_gluer',(['' => '-- Select Folder Gluer --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'folder_gluer')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Die Cutting</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('die_cutting',(['' => '-- Select Die Cutting --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'die_cutting')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Embos/ Debos</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Perforation</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('perforation',(['' => '-- Select Perforation --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'perforation')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Hole</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Foil</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {{ Form::select('foil',(['' => '-- Select Foil --', '1' => 'Yes', '0' => 'No' ]),'',array('class' => 'form-control input-sm selectpicker','id' => 'foil')) }}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>String</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Eyelet</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <div class="form-group has-feedback">
                            {!! Form::text('sp_reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE','required','maxlength' => '20', 'data-required-error' => 'Required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Thread</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">
                        <p>Scoring</p>
                    </td>
                    <td colspan="1" rowspan="1">
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
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>StyleNum</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>PO No</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>PO Dt</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>Buyer</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>Order No</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>Order Dt</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" rowspan="1">
                        <p>CustDelDt</p>
                    </td>
                    <td colspan="1" rowspan="1">
                        <p>&nbsp;</p>
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
                        <p>&nbsp;</p>
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
            <p class="table-name">PROCESS OUTPUT </p>
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
            <p class="table-name">DISPATCH DETAILS</p>
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
            </table>
        </td>
    </tr>
</table>