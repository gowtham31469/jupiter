@extends('layouts.Erp.app')
<style>
    
</style>
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
                    {!! Form::open(['id' => 'nat_jobcard_filter_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_erp_id', 'Erp Id', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_erp_id" title="Select ERP Id" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_erp_id[]" onchange="disableLevelTwoFilters()">
                                @foreach($erp_po_ids as $erp_id => $po)
                                    <option value="{{$erp_id}}">{{ $erp_id }}</option>
                                @endforeach
                                </select>
                                    <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_customer_po', 'Customer Po Number', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_customer_po" title="Select Customer PO" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_customer_po[]" onchange="disableLevelTwoFilters()">
                                @foreach($erp_po_ids as $erp_id => $po)
                                    <option value="{{$po}}">{{ $po }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_buyer', 'Buyer', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_buyer" title="Select Buyer"  data-live-search="true" name="nat_buyer" onchange="getNATCustomers()" required>
                                <option value="">Select Buyer</option>
                                @foreach($buyers as $id => $buyer)
                                    <option value="{{$id}}">{{ $buyer }}</option>
                                @endforeach
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_customer', 'Customer', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_customer" title="Select Customer" data-live-search="true" name="nat_customer[]"  onchange="getNATItemNames()" name="nat_customer" disabled>
                                <option value="">Select Customer</option>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_item_name', 'Item Name', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_item_name" title="Select Item Name" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" onchange="getNATReferences()" name="nat_item_name[]" disabled>
                                <option value="">Select Item Name</option>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_reference', 'Reference', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_reference" title="Select Reference" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" onchange="getNATStyleOCNBuyerPO()" name="nat_reference[]" disabled>
                                <option value="">Select Reference</option>
                                </select>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="nat_jc_filter_2" style="margin-top: 15px;">
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_style_number', 'Style Number', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_style_number" title="Select Style Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_style_number[]"  disabled>
                                <option value="">Select Style Number</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_ocn_number', 'OCN Number', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_ocn_number" title="Select OCN Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_ocn_number[]" disabled>
                                <option value="">Select OCN Number</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                {!! Form::label('nat_buyer_po_number', 'Buyer Po Number', ['class' => 'form_label']) !!}
                                <select class="selectpicker" id="nat_buyer_po_number" title="Select Buyer PO Number" data-done-button="true" multiple data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="10" name="nat_buyer_po_number[]" disabled>
                                <option value="">Select Buyer PO Number</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        
                    </div>
                    <div class="filter-actions-jobcard">
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm pull-right','type' => 'submit'] ) !!}
                        {!! Form::button('Clear Filter', ['class' => 'btn btn-default btn-sm pull-right','type' => 'button', 'onclick' => 'clearNATJobcardFilter()'] ) !!}
                        <div class="clearfix"></div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="main-sub-menu" style="padding-top: inherit;">
                <div class="action-group pull-right sub-menu-button">
                    <button class=" btn btn-primary btn-sm" onclick="generateNatJobcard()">
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
                                <th>Sl. No.</th>
                                <th>ERP ID</th>
                                <th>Customer</th>
                                <th>Buyer</th>
                                <th>Customer PO No.</th>
                                <th>Buyer PO No.</th>
                                <th>Style No.</th>
                                <th>Reference</th>
                                <th>OCN</th>
                                <th>Item Name</th>
                                <th>Color</th>
                                <th>Composition</th>
                                <th>Qnty</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
        // (to prevent Chromium browsers change the value when scrolling)

        $(document).on("wheel", "input[type=number]", function(e) {
            $(this).blur();
        });

        var listTable = $('#non-artwork-jobcard-table').dataTable({});

    });
</script>
@endsection