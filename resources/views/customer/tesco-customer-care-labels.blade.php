<h3><i class="ion-ios-browsers-outline"></i>Create Order</h3>
<hr>
{!! Form::open(['id' => 'tesco_care_label_order_form','data-toggle' => 'validator','role' => 'form']) !!}
<input type="hidden" id="order_type" name="order_type" value="newOrder">
<span id="result"></span>
<div class="row">
    <div class="col-md-12">
        <h4>Order Details</h4>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_po', 'Tesco PO Number', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_po',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tesco PO Number','required','maxlength' => '10')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_eqos', 'EQOS Supplier Number', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_eqos',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'EQOS Supplier Number','required','maxlength' => '10')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_ce_style', 'Option ID', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_ce_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Option ID','required','maxlength' => '')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_uk_style', 'UK Style Number', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_uk_style',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'UK Style Number','required','maxlength' => '8')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_fabric_code', 'Fabric Batch Code', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_fabric_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fabric Batch Code', 'maxlength' => '7')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group has-feedback">
        {!! Form::label('tesco_care_leather_id', 'Leather & Suede ID', ['class' => 'form_label']) !!}
        {!! Form::text('tesco_care_leather_id',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Leather & Suede ID', 'maxlength' => '')) !!}
        <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_coo', 'Country Of Origin', ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_coo',(['' => '-- Select Country Of Origin --'] + $coo),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_coo','required', 'data-live-search' => 'true')) }}
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_supplier', 'Supplier', ['class' => 'form_label']) !!}
            {{  Form::select('tesco_care_supplier',(['' => '-- Select Supplier --'] + $tesco_suppliers),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_supplier','required', 'data-live-search' => 'true', 'onchange' => 'getMoq("customer", "create")')) }}
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
<div class="col-md-12 teasco-care-quantity-div"></div>
                            
</div>
<hr>
<div class="row garment-component-div">
    <div class="col-md-12">
        <h4 class="pull-left">Garment Component & Fibres</h4>
        <button class="btn btn-primary add-garment-component-btn pull-right" type="button" data-id="3" id="add_garment_component_btn" onclick="addComponent(this, 'customer', 'tesco_care_label_order_form')"><i class="fa fa-plus" aria-hidden="true"></i> Garment Component</button> 
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
            <div class="col-xs-9">
                <div class="form-group has-feedback">
                    {{  Form::select('tesco_care_fiber_'.$j.'_'.$i,(['' => '-- Select Fiber --']+$fabriclists),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_fiber_'.$j.'_'.$i, 'data-live-search' => 'true', 'name' => 'tesco_care_fiber_'.$j.'[]')) }}
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group has-feedback">
                <div class="input-group">
                    <input type="number" class="form-control input-sm fiber-individual-percentage-{{$j}}" onkeyup="return calculateTotalFiberPercentage()" max="100" id="fiber_individual_percentage_{{$j}}_{{$i}}" name="fiber_individual_percentage_{{$j}}[]" data-max-error= "Value must be <= 100">
                    <div class="input-group-addon">%</div>
                </div>
                <div class="help-block with-errors"></div>
                </div>
            </div>
        @endfor
        <div class="col-xs-9">
            <h5 class="text-right"><strong>Total</strong></h5>
        </div>
        <div class="col-xs-3">
            <div class="form-group has-feedback">
            <div class="input-group">
                <input type="number" class="form-control input-sm composition-percentage total-percentage-{{ $j }}" name="fiber_total_percentage[]" id="fiber_total_percentage_{{ $j }}" min="100" max="100">
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
        <div class="align-items form-group has-feedback">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('tesco_care_exclusive_trim',null,null,['id' => 'tesco_care_exclusive_trim']) !!} Exclusive of Trims
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('tesco_care_non_textile_part',null,null,['id' => 'tesco_care_non_textile_part']) !!} Contains Non Textile Parts
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('tesco_care_part_of_set',null,null,['id' => 'tesco_care_part_of_set']) !!} Part of Set
            </label>
            <div class="help-block with-errors"></div>
        </div>

        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h4>Care Instructions</h4>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_wash_instruction', 'Wash Instructions', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_wash_instruction"  id="tesco_care_wash_instruction">
                <option value="">-- Select Wash Instructions --</option>
                @foreach($symbols['Washing'] as $key => $value)
                        <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required >{{ $value->degree }}</option>
                @endforeach
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_bleach_instruction', 'Bleach Instructions', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_bleach_instruction"  id="tesco_care_bleach_instruction">
                <option value="">-- Select Bleach Instructions --</option>
                @foreach($symbols['Whitening'] as $key => $value)
                        <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                @endforeach
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_instruction', 'Dry Instructions', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_instruction"  id="tesco_care_dry_instruction">
                <option value="">-- Select Dry Instructions --</option>
                @foreach($symbols['Dryer'] as $key => $value)
                        <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                @endforeach
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_iron_instruction', 'Iron Instructions', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_iron_instruction"  id="tesco_care_iron_instruction">
                <option value="">-- Select Iron Instructions --</option>
                @foreach($symbols['Ironing'] as $key => $value)
                        <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                @endforeach
            </select>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group has-feedback">
            {!! Form::label('tesco_care_dry_clean_instruction', 'Dry Clean Instructions', ['class' => 'form_label']) !!}
            <select class="form-control selectpicker input-sm" data-live-search="true" name="tesco_care_dry_clean_instruction"  id="tesco_care_dry_clean_instruction">
                <option value="">-- Select Dry Clean Instructions --</option>
                @foreach($symbols['Prof Cleaning'] as $key => $value)
                        <option title="{{ $value->degree }}" data-id="{{ $value->id }}" data-content="<img class='tesco-care-symbols' src='{{ asset($value->iso_symbols ) }}' /> {{$value->degree}}" value="{{ $value->id }}" required>{{ $value->degree }}</option>
                @endforeach
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
                {!! Form::label('tesco_care_kids_warning', 'Kids Warning Statement', ['class' => 'form_label']) !!}
                {{  Form::select('tesco_care_kids_warning',(['' => '-- Select Kids Warning Statement --'] + $kids_warning),'',array('class' => 'selectpicker form-control input-sm','id'=>'tesco_care_kids_warning', 'data-live-search' => 'true')) }}
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div>
    <div class="label-order-invisible-div"></div>
</div>
</div>
<hr>
<div class="row hidden" id="baby_sleeping_bag_div">
    <div class="col-md-6 col-xs-12">
        <div class="alert alert-danger" role="alert">These 3 fields are required only if the size chart selected is “Baby Sleeping Bags”</div>
    </div>
    <div class="col-xs-12">
        <div class="align-items form-group has-feedback">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('tesco_care_bs_en',null,null,['id' => 'tesco_care_bs_en']) !!} Conforms to BS EN 16781:2018
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="checkbox form-group has-feedback">
            <label>
                {!! Form::checkbox('tesco_care_sleep_bag_symbol',null,null,['id' => 'tesco_care_sleep_bag_symbol']) !!} <img class="baby-sleep-bag-symbol" src="{{ asset('images/i symbol.png') }}" />
            </label>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback form-inline">
            {!! Form::label('tesco_care_tog', 'TOG', ['class' => 'form_label']) !!}
            {!! Form::text('tesco_care_tog',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TOG')) !!}
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
        <button class="btn btn-xs btn-warning label-order-submit-btn" type="submit" onclick="validateTescoCareLabelOrder('customer')"><i class="fa fa-floppy-o"
            aria-hidden="true"></i> Submit</button> 

        <button class="btn btn-xs btn-danger label-order-cancel-btn" type="button"
        data-toggle="modal" data-target="#cancelLabelOrderModal" style="display: none;"><i class="fa fa-times"
            aria-hidden="true"></i> Cancel</button>
    
        <button class="btn btn-xs btn-danger reload-btn" type="button"
        onclick="reload_back()"><i class=" fa fa-refresh" aria-hidden="true"></i> Reload</button> 
</div>
{!! Form::close() !!}

<!-- Tesco care labels orders js -->
<script src="{{ asset('js/common/tesco-care-label-orders.js') }}"></script>