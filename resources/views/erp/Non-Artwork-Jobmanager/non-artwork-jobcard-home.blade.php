@extends('layouts.Erp.app')

@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
    <li><a href="{{ route('non-artwork-jobcard-manager') }}" class="active">Create Non Artwork Jobcard</a></li>
    <li><a href="">Non Artwork Jobcards List</a></li>
</ul>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12" style="padding-top: 25px;">
            <div class="top-content">
                <div class="filter-window-jobcard" style="display: block;">
                    <h5><strong>Jobcard Filter</strong></h5>
                    <div class="row">
                    <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_erp_id" title="Select ERP Id" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_erp_id[]">
                                @foreach($erp_po_ids as $erp_id => $po)
                                    <option value="{{$erp_id}}">{{ $erp_id }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_customer_po" title="Select Customer PO" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_customer_po[]">
                                @foreach($erp_po_ids as $erp_id => $po)
                                    <option value="{{$po}}">{{ $po }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_buyer" title="Select Buyer" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_buyer[]">
                                @foreach($buyers as $id => $buyer)
                                    <option value="{{$id}}">{{ $buyer }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_customer" title="Select Customer" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_customer[]">
                                @foreach($customers as $id => $customer)
                                    <option value="{{$id}}">{{ $customer }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_item_name" title="Select Item Name" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10">
                                @foreach($item_names as $id => $item_name)
                                    <option value="{{$id}}">{{ $item_name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_reference" title="Select Reference" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_style_number" title="Select Style Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_ocn_number" title="Select OCN Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <select class="selectpicker" id="nat_buyer_po_number" title="Select Buyer PO Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        
                    </div>
                    <div class="filter-actions-jobcard">
                        <button class="btn btn-primary input-sm pull-right" onclick="submitOs(this)" id="submitos">Submit</button>
                        <button class="btn btn-default input-sm pull-right" id="reset">Clear Filter</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="main-sub-menu" style="padding-top: inherit;">
                <div class="action-group pull-right sub-menu-button">
                    <button class=" btn btn-primary btn-sm" onclick="generateJobcard()">
                        Create Jobcard
                    </button>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="main-content">
                <div class="loader" style="display:none;">
                    <div class="fa-3x">
                        <i class="fa fa-spinner fa-pulse"></i>
                    </div>
                </div>
                <div class="col" id="non-artwork-jobcard-table-div">
                    <table id="non-artwork-jobcard-table" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
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
                    </table>
                </div>
            </div>
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
                                    {!! Form::label('reference', 'REFERENCE :', ['class' => 'form_label']) !!}
                                    {!! Form::text('reference',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REFERENCE', 'maxlength' => '20')) !!}
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-feedback">
                                    {!! Form::label('disp_mode', 'DISP MODE :', ['class' => 'form_label']) !!}
                                    {{ Form::select('disp_mode',(['' => '-- Select Dispatch Mode --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'disp_mode','required')) }}
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
                                    {!! Form::number('length',$value = '', array('class' => 'form-control input-sm','placeholder' => 'LENGTH', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'onkeypress' => 'return isNumberKey(event)', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.')) !!}
                                        <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    {!! Form::label('repeat_gap', 'REPEAT GAP :', ['class' => 'form_label']) !!}
                                    {!! Form::number('repeat_gap',$value = '', array('class' => 'form-control input-sm','placeholder' => 'REPEAT GAP', 'min' => '1','max' => '99999', 'data-max-error' => 'Value should be <= 99999', 'data-min-error'=> 'Value should be greater than 0', 'step' => '0.01', 'data-step-error' => 'Max 2 decimal places allowed.', 'onkeyup' => 'return mtrsForStickers()', 'onkeypress' => 'return isNumberKey(event)')) !!}
                                        <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    {!! Form::label('machine', 'MACHINE :', ['class' => 'form_label']) !!}
                                    {{ Form::select('machine',(['' => '-- Select Machine --']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'machine','required')) }}
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
                </div>
            </div>
            <div class="modal-footer jobcard-creation-modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                {!! Form::button('Print', ['class' => 'btn btn-primary btn-sm','type' => 'submit', 'id' => 'jc_print_btn'] ) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection
@section('jqueryIncludes')
<script>
    $(document).ready(function() {
        // disable mousewheel on a input number field when in focus
        // (to prevent Cromium browsers change the value when scrolling)

        $(document).on("wheel", "input[type=number]", function(e) {
            $(this).blur();
        });

        var listTable = $('#non-artwork-jobcard-table').dataTable({});

    });
</script>
@endsection