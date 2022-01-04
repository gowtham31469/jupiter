<h3><i class="ion-ios-browsers-outline"></i>Create Order</h3>
<hr>
<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
{!! Form::open(['id' => 'tesco_swing_ticket_order_form','data-toggle' => 'validator','role' => 'form']) !!}
<input type="hidden" id="order_type" name="order_type" value="newOrder">
<span id="result"></span>

<div class="row">
    <div class="col-md-12">
        <h4>Order Quantity</h4>
    </div>
    <div class="col-md-12 teasco-care-quantity-div">
    <input type="hidden" id="swing_primary_size_id" name="swing_primary_size_id" value="{{$size_data[0]->tesco_size_department_id}}">
    <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>UK</th>
                <th>EUR</th>
                <th>Secondary Size</th>
                <th>EAN Barcode</th>
                <th>Quantity <small>(Units)</small></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($size_data as $value)
            <tr id="swing_size_{{$loop->iteration}}">
                <td> {{ $value->uk_size }}</td>
                <td>{{ $value->ce_size }}</td>
                <td>
                    @foreach ($value->secSizeChart as $sec_size)
                        <strong class="text-justify">{{ $sec_size->category_name }}</strong> : {{ $sec_size->secondary_size }} <br>
                    @endforeach
                </td>
                <td>
                    <div class="form-group has-feedback label-ean-feedback"><input
                            class="form-control input-sm label-ean-field" placeholder="EAN Barcode" id="swing_ean_{{$loop->iteration}}"
                            name="swing_ean[]" type="text" data-minlength="13" maxlength="13"
                            data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)"
                            onkeyup="return validateEanBarcodeValue(this)">
                        <div class="help-block with-errors"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group has-feedback label-quantity-feedback"><input type="number"
                            class="form-control input-sm label-quantity-field" placeholder="Quantity" id="swing_quantity_{{$loop->iteration}}"
                            name="swing_quantity[]">
                        <div class="help-block with-errors"></div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
                            
</div>
<hr>

<div class="row">
    <div class="col-md-12">
        <h4>Order Details</h4>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_24_char_desc', '24 Character Description', ['class' => 'form_label']) !!}
        {!! Form::text('swing_24_char_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => '24 Character Description','required','maxlength' => '24')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_product_desc', 'Product Description', ['class' => 'form_label']) !!}
        {{  Form::select('swing_product_desc',(['' => '-- Select Product Description --'] + $product_description),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_product_desc','required', 'data-live-search' => 'true')) }}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_po_number', 'PO Number', ['class' => 'form_label']) !!}
        {!! Form::text('swing_po_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PO Number','required')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_product_benefit', 'Product Benefit', ['class' => 'form_label']) !!}
        {{  Form::select('swing_product_benefit',(['' => '-- Select Product Benefit --'] + $product_benefits),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_product_benefit','required', 'data-live-search' => 'true')) }}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_mm_product_benefit', 'MM Product Benefit', ['class' => 'form_label']) !!}
        {{  Form::select('swing_mm_product_benefit',(['' => '-- Select MM Product Benefit --'] + $mm_product_benefits),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_mm_product_benefit','required', 'data-live-search' => 'true')) }}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_cz_price', 'Czech Selling Price', ['class' => 'form_label']) !!}
        {!! Form::text('swing_cz_price',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Czech Selling Price', 'maxlength' => '')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_sk_price', 'Slovak Selling Price', ['class' => 'form_label']) !!}
        {!! Form::text('swing_sk_price',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Slovak Selling Price', 'maxlength' => '')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_hu_price', 'Hungarian Selling Price', ['class' => 'form_label']) !!}
        {!! Form::text('swing_hu_price',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Hungarian Selling Price', 'maxlength' => '')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_swing_supplier', 'Supplier', ['class' => 'form_label']) !!}
            {{  Form::select('tesco_swing_supplier',(['' => '-- Select Supplier --'] + $tesco_suppliers),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_swing_supplier','required', 'data-live-search' => 'true', 'onchange' => 'getMoq("admin", "create")')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_eqos', 'EQOS Code', ['class' => 'form_label']) !!}
        {!! Form::text('swing_eqos',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'EQOS Code')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('swing_season_code', 'Season Code', ['class' => 'form_label']) !!}
            {{  Form::select('swing_season_code',(['' => '-- Select Season Code --'] + $tesco_suppliers),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_season_code','required', 'data-live-search' => 'true')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_option_id', 'Option ID', ['class' => 'form_label']) !!}
        {!! Form::text('swing_option_id',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Option ID')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

</div>
<hr>
<div class="row">
    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_wee_symbol', 'WEE Symbol', ['class' => 'form_label']) !!}
        {{  Form::select('swing_wee_symbol',(['' => '-- Select WEE Symbol --'] + $wee_symbols),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_wee_symbol','required', 'data-live-search' => 'true')) }}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">{!! Form::label('swing_ce_mark', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
        <div class="checkbox">
            <label>
            {!! Form::checkbox('swing_ce_mark',null,null,['id' => 'swing_ce_mark']) !!} CE Mark
            </label>
        </div>
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">{!! Form::label('swing_machine_washable', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
        <div class="checkbox">
            <label>
            {!! Form::checkbox('swing_machine_washable',null,null,['id' => 'swing_machine_washable']) !!} Machine Washable
            </label>
        </div>
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_woolmark_logo', 'Woolmark Logo', ['class' => 'form_label']) !!}
        {{  Form::select('swing_woolmark_logo',(['' => '-- Select Woolmark Logo--'] + $woolmark_logos),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_woolmark_logo','required', 'data-live-search' => 'true')) }}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('swing_style_number', 'Style Number', ['class' => 'form_label']) !!}
        {!! Form::text('swing_style_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Style Number','required')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('swing_coo', 'Country Of Origin', ['class' => 'form_label']) !!}
            {{  Form::select('swing_coo',(['' => '-- Select Country Of Origin --'] + $coo),'',array('class' => 'selectpicker form-control input-sm','id'=>'swing_coo','required', 'data-live-search' => 'true')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>

</div>
<div class="bottom-bar">
    <div class="modal-footer"> 
        <span class="pagename pull-left hidden-xs">Please save or submit changes before closing
            page.</span> 
            <button class="btn btn-xs btn-warning label-order-submit-btn" type="submit" onclick="validateTescoSwingTicketOrder('admin')"><i class="fa fa-floppy-o"
                aria-hidden="true"></i> Submit</button> 

            

            <button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork('print')" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Print</button>

            <button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork('proof')" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Artwork</button>

            <button class="btn btn-xs btn-danger label-order-cancel-btn" type="button"
            data-toggle="modal" data-target="#cancelLabelOrderModal" style="display: none;"><i class="fa fa-times"
                aria-hidden="true"></i> Cancel</button>
        
            <button class="btn btn-xs btn-danger reload-btn" type="button"
            onclick="reload_back()"><i class=" fa fa-refresh" aria-hidden="true"></i> Reload</button> 
    </div>
    {!! Form::close() !!}
</div>

<!-- Tesco care labels orders js -->
<script src="{{ asset('js/common/tesco-swing-ticket-orders.js') }}"></script>