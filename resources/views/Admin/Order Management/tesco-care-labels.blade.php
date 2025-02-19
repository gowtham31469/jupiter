<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
{!! Form::open(['id' => 'tesco_care_label_order_form','data-toggle' => 'validator','role' => 'form']) !!}
<input type="hidden" id="order_type" name="order_type" value="newOrder">
<input type="hidden" id="hidden_id" name="hidden_id">
<input type="hidden" id="hidden_name" name="hidden_name">
<input type="hidden" id="care_template_id" name="care_template_id" value="{{ $template }}">
<span id="result"></span>
<div class="row">
    <div class="col-md-12">
        <h4>Order Details</h4>
        <h5 style="color:red;">Fields marked (*) are mandatory</h5>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_po', 'Tesco PO Number', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
        {!! Form::text('tesco_care_po',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tesco PO Number','required','maxlength' => '9', "onkeydown" => "return validatePoValue(event)")) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_eqos', 'EQOS Supplier Number', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
        {!! Form::text('tesco_care_eqos',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'EQOS Supplier Number','required','maxlength' => '7', "onkeypress" => "return isNumber(event)")) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_ce_style', 'Option ID', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
        {!! Form::text('tesco_care_ce_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Option ID','required','maxlength' => '8', "onkeydown" => "return validatePoValue(event)")) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_uk_style', 'UK Style Number', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
        {!! Form::text('tesco_care_uk_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'UK Style Number','required','maxlength' => '8')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_fabric_code', 'Fabric Batch Code', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_fabric_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fabric Batch Code', 'maxlength' => '5', "onkeypress" => "return isNumber(event)")) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2 leather_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_leather_id', 'Leather & Suede ID', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_leather_id',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Leather & Suede ID', 'maxlength' => '10')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_coo', 'Country Of Origin', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select name="tesco_care_coo" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_coo" required>
                <option value="">Select Country Of Origin</option>
                @foreach ($coo as $key => $value)
                <option value="{{ $key }}" {{ ( strtolower($value) === 'made in india') ? "selected" : "" }}>
                    {{ $value }}
                </option>
                @endforeach
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_supplier', 'Supplier', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            {{  Form::select('tesco_care_supplier',(['' => '-- Select Supplier --'] + $tesco_suppliers),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_supplier','required', 'data-live-search' => 'true', 'onchange' => 'getMoq(this, "admin", "create")')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_part_of_two_piece_set', 'Part of Set', ['class' => 'form_label']) !!}
            <select name="tesco_care_part_of_two_piece_set" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_part_of_two_piece_set">
            <optgroup label="Select Part of Set">
                <option value="">None</option>
                @foreach ($part_of_two_piece_set as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
                @endforeach
            </optgroup>
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2" id="wool_mark_logo_div">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_woolmark_logo', 'Woolmark Logo', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_woolmark_logo"  id="tesco_care_woolmark_logo">
                <option value="">-- Select Woolmark Logo --</option>
                @if(!empty($woolmark_logos))
                    @foreach($woolmark_logos as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->woolmark_logo ) }}' /> {{$value->woolmark_logo_text}}" value="{{ $value->id }}" >{{ $value->woolmark_logo_text }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2" id="wool_mark_batch_code_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_woolmark_batch_code', 'Woolmark Batch Code', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_woolmark_batch_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Woolmark Batch Code', 'maxlength' => '9','disabled' => 'disabled')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2" id="uk_ce_mark_div">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_uk_ce_mark', 'UK/CE Mark', ['class' => 'form_label']) !!}
            <select name="tesco_care_uk_ce_mark" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_uk_ce_mark">
                <option value="">-- Select UK/CE Mark --</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2" id="flammability_div">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_flammability', 'Flammability', ['class' => 'form_label']) !!}
            <select name="tesco_care_flammability" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_flammability">
            <optgroup label="Select Flammability">
                <option value="">None</option>
                @foreach ($flammability as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
                @endforeach
            </optgroup>
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2 ex_non_ex_div">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_ex_non_ex', 'Exclusive/Non Exclusive', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select name="tesco_ex_non_ex" class="selectpicker form-control input-sm" required data-live-search="true" id="tesco_ex_non_ex">
                <option value="">-- Exclusive/Non Exclusive --</option>
                <option value="exclusive">Exclusive</option>
                <option value="non-exclusive">Non Exclusive</option>
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2 ex_non_ex_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_address_name', 'Address Name', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_address_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address Name','disabled' => 'disabled')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    
     <div class="col-md-2 ex_non_ex_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_address_line_1', 'Address Line 1', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_address_line_1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address Line 1','disabled' => 'disabled')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div class="col-md-2 ex_non_ex_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_address_line_2', 'Address Line 2', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_address_line_2',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address Line 2','disabled' => 'disabled')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    
    <div class="col-md-2 ex_non_ex_div">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_address_line_3', 'Address Line 3', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_address_line_3',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address Line 3','disabled' => 'disabled')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">{!! Form::label('tesco_care_button_space', 'Dont Show', ['class' => 'form_label', 'style' => 'visibility:hidden']) !!}
        <div class="checkbox">
            <label>
            {!! Form::checkbox('tesco_care_button_space',null,null,['id' => 'tesco_care_button_space']) !!} Include Button Sew Space
            </label>
        </div>
        <div class="help-block with-errors"></div>
        </div>
    </div>
</div>
<hr>
<div class="row">
<div class="col-md-12">
    <h4>Order Quantity</h4>
</div>
<div class="col-md-12 tesco-care-quantity-div"></div>
                            
</div>
<hr>
<div class="row garment-component-div">
    <div class="col-md-12">
        <h4 class="pull-left">Garment Component & Fibres</h4>
        <span class="asterisk" style="margin-top:11px">(*)</span>
        <button class="btn btn-primary add-garment-component-btn pull-right" type="button" data-id="3" id="add_garment_component_btn" onclick="addComponent(this, 'admin', 'tesco_care_label_order_form')"><i class="fa fa-plus" aria-hidden="true"></i> Garment Component</button> 
    </div>
    
    @for ($j = 1; $j <= 3; $j++)
    <div class="col-md-4 component-div">
        <div class="row">
        <div class="col-xs-12">
            <div class="form-group has-feedback">
                {!! Form::label('tesco_care_garment_component_'.$j, 'Garment Component', ['class' => 'form_label component-label']) !!}
                {{  Form::select('tesco_care_garment_component_'.$j,(['' => '-- Select Garment Component --']+$components),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_garment_component_'.$j, 'data-live-search' => 'true', 'name' => 'tesco_care_garment_component[]')) }}
                <div class="help-block with-errors"></div>
            </div>
        </div>
        @for ($i = 1; $i <= 6; $i++)
            <div class="col-xs-8">
                <div class="form-group has-feedback">
                    {{  Form::select('tesco_care_fiber_'.$j.'_'.$i,(['' => '-- Select Fibre --']+$fabriclists),'',array('class' => 'selectpicker form-control input-sm tesco-care-fiber-class','id'=>'tesco_care_fiber_'.$j.'_'.$i, 'data-live-search' => 'true', 'name' => 'tesco_care_fiber[]')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group has-feedback">
                <div class="input-group">
                    <input type="number" class="form-control input-sm fiber-individual-percentage" max="100" min="0" id="fiber_individual_percentage_{{$j}}_{{$i}}" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100" readonly>
                    <div class="input-group-addon">%</div>
                </div>
                <div class="help-block with-errors"></div>
                </div>
            </div>
        @endfor
        <div class="col-xs-8">
            <h5 class="text-right"><strong>Total</strong></h5>
        </div>
        <div class="col-xs-4">
            <div class="form-group has-feedback">
            <div class="input-group">
                <input type="number" class="form-control input-sm composition-percentage total-percentage" name="fiber_total_percentage[]" id="fiber_total_percentage_{{ $j }}">
                <div class="input-group-addon">%</div>
            </div>
            <div class="help-block with-errors"></div>
            </div>
        </div>

        </div>
    </div>
    @endfor
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h4>Care Instructions</h4>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_wash_instruction', 'Wash Instruction', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_wash_instruction"  id="tesco_care_wash_instruction" required>
                <option value="">-- Select Wash Instruction --</option>
                @if(!empty($symbols['Washing']))
                    @foreach($symbols['Washing'] as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_bleach_instruction', 'Bleach Instruction', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_bleach_instruction"  id="tesco_care_bleach_instruction" required>
                <option value="">-- Select Bleach Instruction --</option>
                @if(!empty($symbols['Whitening']))
                    @foreach($symbols['Whitening'] as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_instruction', 'Drying Instruction', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_instruction"  id="tesco_care_dry_instruction" required>
                <option value="">-- Select Drying Instruction --</option>
                @if(!empty($symbols['Dryer']))
                    @foreach($symbols['Dryer'] as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_iron_instruction', 'Ironing Instruction', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_iron_instruction"  id="tesco_care_iron_instruction" required>
                <option value="">-- Select Ironing Instruction --</option>
                @if(!empty($symbols['Ironing']))
                    @foreach($symbols['Ironing'] as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_clean_instruction', 'Dry Clean Instruction', ['class' => 'form_label']) !!}
        <span class="asterisk">(*)</span>
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_clean_instruction"  id="tesco_care_dry_clean_instruction" required>
                <option value="">-- Select Dry Clean Instruction --</option>
                @if(!empty($symbols['Prof Cleaning']))
                    @foreach($symbols['Prof Cleaning'] as $key => $value)
                            <option data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" >{{ $value->degree }}</option>
                    @endforeach
                @endif
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>
<hr>
<div class="row">
<div class="col-md-8">
    <div class="row">
    <div class="col-md-12">
        <h4>Additional Instructions</h4>
    </div>
    @for ($i = 1; $i <= 3; $i++)
    <div class="col-md-4">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_additional_instruction_'.$i, 'Additional Instruction '.$i, ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_additional_instruction_'.$i,(['' => '-- Select Additional Instruction --'] + $additional_instructions),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_additional_instruction_'.$i, 'data-live-search' => 'true', 'name' => 'tesco_care_additional_instruction_'.$i)) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
    @endfor
    
    </div>
</div>
<div class="col-md-6">
    <div class="row">
        <div class="col-md-12">
            <h4>Additional Instructions</h4>
        </div>
        <div class="col-md-6">
            <div class="form-group has-feedback">
                {!! Form::label('tesco_care_kids_warning', 'Children Warning', ['class' => 'form_label']) !!}
                {{  Form::select('tesco_care_kids_warning',(['' => '-- Select Children Warning --'] + $kids_warning),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_kids_warning', 'data-live-search' => 'true')) }}
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>
    <div class="label-order-invisible-div"></div>
</div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h4>Optional Garment Statements</h4>
    </div>
    <div class="col-md-4">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_garment_statements', 'Garment Statements', ['class' => 'form_label']) !!}
            <select name="tesco_care_garment_statements" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_garment_statements">
            <optgroup label="Select Garment Statements">
                <option value="">None</option>
                @foreach ($garment_statements as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
                @endforeach
            </optgroup>
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</div>
<hr>
<div class="row hidden" id="baby_sleeping_bag_div">
    <div class="col-md-6 col-xs-12">
        <div class="alert alert-danger" role="alert">The Tog Value is required only if the size chart selected is “Baby Sleeping Bags”</div>
    </div>
    <div class="col-xs-12">
        <div class="align-items form-group has-feedback">
        <div class="checkbox hidden">
            <label>
                {!! Form::checkbox('tesco_care_bs_en',null,null,['id' => 'tesco_care_bs_en']) !!} Conforms to BS EN 16781:2018
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="checkbox form-group has-feedback hidden">
            <label>
                {!! Form::checkbox('tesco_care_sleep_bag_symbol',null,null,['id' => 'tesco_care_sleep_bag_symbol']) !!} <img class="baby-sleep-bag-symbol" src="{{ asset('images/i symbol.png') }}" />
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback form-inline">
            {!! Form::label('tesco_care_tog_rating', 'Tog Rating', ['class' => 'form_label']) !!}
                <select name="tesco_care_tog_rating" class="selectpicker form-control input-sm" required data-live-search="true" id="tesco_care_tog_rating">
                    <option value="">-- Select Tog Rating --</option>
                    @foreach ($tog_rating as $key => $value)
                    <option value="{{ $key }}">
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
                <div class="help-block with-errors"></div>
        </div>
        </div>
    </div>
</div>
<hr>
</div>
<div class="bottom-bar">
<div class="modal-footer"> 
    <span class="pagename pull-left hidden-xs">Please save or submit changes before closing
        page.</span> 
        <button class="btn btn-xs btn-warning label-order-submit-btn" type="submit" onclick="validateTescoCareLabelOrder('admin')"><i class="fa fa-floppy-o"
            aria-hidden="true"></i> Submit</button> 

        

        <button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork('print')" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Print</button>

        <button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork('proof')" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Artwork</button>

        <button class="btn btn-xs btn-danger order-cancel-btn" type="button" data-toggle="modal" data-target="#cancelOrderModal" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
    
        <button class="btn btn-xs btn-danger reload-btn" type="button"
        onclick="reload_back()"><i class=" fa fa-refresh" aria-hidden="true"></i> Reload</button> 
</div>
{!! Form::close() !!}

<!-- Tesco care labels orders js -->
<script src="{{ asset('js/common/tesco-care-label-orders.js') }}"></script>