$( () => { 
    const table_name = 'tesco_washcare_orders';

    getCareLabelDetails = (obj, user_type) => {

    var id = $(obj).parent().attr('data-id');
    var table_name = $(obj).parent().attr('data-table');

    $.ajax({
        url: base_url + `/${user_type}/tesco/get-care-label-order-details`,
        type: "get",
        dataType: "json",
        data: {
            table_name: table_name,
            id: id
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
               
                if (data.order_details.order_status == "Saved") {
                    $('.snackbar_span').html('Please Check in Create Order Page!!!');
                    showSnackbar();
                } else if (data.order_details.order_status == "Order Cancelled") {
                    $('.snackbar_span').html('Order Cancelled!!!');
                    showSnackbar();
                } else {
                    loadCareLabelDetails(data, user_type)

                    var template_id = data.order_details.template_id
                    
                    if(template_id == 54 || template_id == 60 || template_id == 61 || template_id == 75)
                    {
                        $('#uk_ce_mark_div').addClass('hidden');
                        $('#flammability_div').addClass('hidden');
                    } else if(template_id == 55 || template_id == 57 || template_id == 67 || template_id == 68 || template_id == 76 || template_id == 77 || template_id == 78 || template_id == 79)
                    {
                        $('#wool_mark_logo_div').addClass('hidden');
                        $('#wool_mark_batch_code_div').addClass('hidden');
                    } else if(template_id == 58 || template_id == 62 || template_id == 63 || template_id == 64 || template_id == 65 || template_id == 66 || template_id == 71 || template_id == 72 || template_id == 73 || template_id == 74)
                    {
                        $('#flammability_div').addClass('hidden');
                        $('#wool_mark_logo_div').addClass('hidden');
                        $('#wool_mark_batch_code_div').addClass('hidden');
                    } else if(template_id == 56 || template_id == 59 || template_id == 69 || template_id == 70 || template_id == 80 || template_id == 81 || template_id == 82 || template_id == 83)
                    {
                        $('#uk_ce_mark_div').addClass('hidden');
                        $('#flammability_div').addClass('hidden');
                        $('#wool_mark_logo_div').addClass('hidden');
                        $('#wool_mark_batch_code_div').addClass('hidden');
                    }

                    if(template_id == 58 || template_id == 66 || template_id == 73 || template_id == 74)
                    {
                        $('#tesco_care_uk_ce_mark').val("yes")
                        $('#tesco_care_uk_ce_mark').prop('disabled', true);
                        $(".selectpicker").selectpicker("refresh");
                    }

                    if(template_id == 54 || template_id == 55 || template_id == 56 || template_id == 62 || template_id == 64 || template_id == 66 || template_id == 67 || template_id == 69 || template_id == 71 || template_id == 73 || template_id == 75 || template_id == 76 || template_id == 77 || template_id == 80 || template_id == 82)
                    {
                        $('.ex_non_ex_div').addClass('hidden');
                    }

                    if(template_id == 55 || template_id == 56 || template_id == 57 || template_id == 58 || template_id == 59 || template_id == 66 || template_id == 67 || template_id == 68 || template_id == 73 || template_id == 74 || template_id == 76 || template_id == 77 || template_id == 78 || template_id == 79 || template_id == 80 || template_id == 81)
                    {
                        $('.leather_div').addClass('hidden');
                    }

                }

            },
            400: function () {
                $('.snackbar_span').html('Something went wrong!');
                showSnackbar();
            }
        },
        error: function (data) {
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })

    }
    
    loadCareLabelDetails = (order_data, user_role) => {
        var user_type = user_role.toLowerCase();
        
        $('.garment-component-div, .label-metadata-select, .tesco-care-quantity-div, .tog-div').html('');

        $('#baby_sleeping_bag_div').addClass("hidden");
        //console.log(order_data.order_details)
        //console.log(order_data.order_details.address_line1)

        var metadata_select_div = ` <div class="form-group has-feedback">
                                    <label for="tesco_care_coo" class="component-label">Country Of Origin </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_coo" name="tesco_care_coo">
                                            <option value="">-- Select Country Of Origin --</option>`;
                                            $.each(order_data.coo, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                    <label for="tesco_care_supplier" class="component-label">Supplier </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_supplier" name="tesco_care_supplier" onchange="getMoq(this, '`+user_type+`', 'update')">
                                            <option value="">-- Select Supplier --</option>`;
                                            $.each(order_data.tesco_suppliers, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                    <label for="tesco_care_part_of_two_piece_set" class="component-label">Part of Two Piece Set </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_part_of_two_piece_set" name="tesco_care_part_of_two_piece_set">
                                        <optgroup label="Select Part of Two Piece Set">
                                            <option value="">None</option>`;
                                            $.each(order_data.part_of_two_piece_set, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</optgroup></select>
                                    </div>
                                    <div class="form-group has-feedback" id="wool_mark_logo_div">
                                     <label for="tesco_care_woolmark_logo" class="component-label">Woolmark Logo </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_woolmark_logo" name="tesco_care_woolmark_logo">
                                            <option value="">-- Select Woolmark Logo --</option>`;
                                            $.each(order_data.woolmark_logos, function (id, value) {

                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.woolmark_logo+`' /> `+value.woolmark_logo_text+`" value="`+value.id+`" >`+value.woolmark_logo_text+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                            <label for="tesco_care_woolmark_batch_code" class="form_label">Woolmark Batch Code</label>
                                            <input class="form-control input-sm " placeholder="Woolmark Batch Code" maxlength="9" disabled="disabled" name="tesco_care_woolmark_batch_code" type="text" value="`+order_data.woolmark_batch_code+`" id="tesco_care_woolmark_batch_code">
                                            <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback" id="uk_ce_mark_div">
                                    <label for="tesco_care_uk_ce_mark" class="component-label">UK/CE Mark </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_uk_ce_mark" name="tesco_care_uk_ce_mark">
                                                <option value="">-- Select UK/CE Mark --</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group has-feedback" id="flammability_div">
                                     <label for="tesco_care_flammability" class="component-label">Flammability </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_flammability" name="tesco_care_flammability">
                                        <optgroup label="Select Flammability">
                                            <option value="">None</option>`;
                                            $.each(order_data.flammability, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</optgroup></select>
                                    </div>
                                    <div class="ex_non_ex_div">
                                    <div class="form-group has-feedback">
                                        <label for="tesco_ex_non_ex" class="form_label">Exclusive/Non Exclusive</label>
                                        <select name="tesco_ex_non_ex" class="selectpicker form-control input-sm" data-live-search="true" id="tesco_ex_non_ex" tabindex="-98">
                                            <option value="">-- Exclusive/Non Exclusive --</option>
                                            <option value="exclusive">Exclusive</option>
                                            <option value="non-exclusive">Non Exclusive</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="tesco_care_address_name" class="form_label">Address Name</label>
                                        <input class="form-control input-sm " placeholder="Address Name" name="tesco_care_address_name" type="text" disabled="disabled" value="`+order_data.order_details.address_name+`" id="tesco_care_address_name">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback has-success">
                                        <label for="tesco_care_address_line_1" class="form_label">Address Line 1</label>
                                        <input class="form-control input-sm " placeholder="Address Line 1" name="tesco_care_address_line_1" type="text" disabled="disabled" value="`+order_data.order_details.address_line1+`" id="tesco_care_address_line_1">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback has-success">
                                        <label for="tesco_care_address_line_2" class="form_label">Address Line 2</label>
                                        <input class="form-control input-sm " placeholder="Address Line 2" name="tesco_care_address_line_2" type="text" disabled="disabled" value="`+order_data.order_details.address_line2+`" id="tesco_care_address_line_2">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback has-success">
                                        <label for="tesco_care_address_line_3" class="form_label">Address Line 3</label>
                                        <input class="form-control input-sm " placeholder="Address Line 3" name="tesco_care_address_line_3" type="text" disabled="disabled" value="`+order_data.order_details.address_line3+`" id="tesco_care_address_line_3">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Care Instructions</h4>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_wash_instruction" class="component-label">Wash Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_wash_instruction" name="tesco_care_wash_instruction" required>
                                            <option value="">-- Select Wash Instruction --</option>`;
                                            $.each(order_data.symbols['Washing'], function (id, value) {

                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_bleach_instruction" class="component-label">Bleach Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_bleach_instruction" name="tesco_care_bleach_instruction" required>
                                            <option value="">-- Select Bleach Instruction --</option>`;
                                            $.each(order_data.symbols['Whitening'], function (id, value) {

                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_dry_instruction" class="component-label">Drying Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_dry_instruction" name="tesco_care_dry_instruction" required>
                                            <option value="">-- Select Drying Instruction --</option>`;
                                            $.each(order_data.symbols['Dryer'], function (id, value) {
                                                
                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_iron_instruction" class="component-label">Ironing Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_iron_instruction" name="tesco_care_iron_instruction" required>
                                            <option value="">-- Select Ironing Instruction --</option>`;
                                            $.each(order_data.symbols['Ironing'], function (id, value) {

                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_dry_clean_instruction" class="component-label">Dry Clean Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_dry_clean_instruction" name="tesco_care_dry_clean_instruction" required>
                                            <option value="">-- Select Dry Clean Instruction --</option>`;
                                            $.each(order_data.symbols['Prof Cleaning'], function (id, value) {

                                                metadata_select_div += `<option data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Additional Instructions</h4>`;

                                    for (let index = 1; index <= 3; index++) {
                                        metadata_select_div += `<div class="form-group has-feedback">
                                                                    <label for="tesco_care_additional_instruction_` + index + `" class="component-label">Additional Instruction </label>
                                                                    <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_additional_instruction_` + index + `" name="tesco_care_additional_instruction_` + index + `">
                                                                        <option value="">-- Select Additional Instruction --</option>`;
                                                                        $.each(order_data.additional_instructions, function (id, name) {

                                                                            metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                                                        });
                                                                    metadata_select_div += `</select>
                                                                </div>`;
                                        
                                    }

                                    metadata_select_div += `<div class="form-group has-feedback">
                                                                    <label for="tesco_care_kids_warning" class="component-label">Kids Warning Statement </label>
                                                                    <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_kids_warning" name="tesco_care_kids_warning">
                                                                        <option value="">-- Select Kids Warning Statement --</option>`;
                                                                        $.each(order_data.kids_warning, function (id, name) {

                                                                            metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                                                        });
                                                                    metadata_select_div += `</select>
                                                                </div>
                                                                <h4 class="text-center">Optional Garment Statements</h4>
                                                                <div class="form-group has-feedback">
                                                                <label for="tesco_care_garment_statements" class="component-label">Garment Statements</label>
                                                                    <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_garment_statements" name="tesco_care_garment_statements" required>
                                                                        <option value="">-- Select Garment Statements --</option>`;
                                                                        $.each(order_data.garment_statements, function (id, name) {

                                                                            metadata_select_div += `<option value=` + id + `>` + name + `</option>`;
                            
                                                                        });
                                                                    metadata_select_div += `</select>
                                                                </div>
                                                                `

        $('.label-metadata-select').append(metadata_select_div);

        var tog_div_html = `<div class="form-group has-feedback">
        <label for="tesco_care_tog_rating" class="component-label">Tog Rating</label>
            <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_tog_rating" name="tesco_care_tog_rating">
                <option value="">-- Select Tog Rating --</option>`;
                $.each(order_data.tog_rating, function (id, name) {

                    tog_div_html += `<option value=` + id + `>` + name + `</option>`;

                });
            tog_div_html += `</select></div>`;

        $('.tog-div').html(tog_div_html);

        var fiber_component_div = `<div class="col-md-12">
        <h4 class="pull-left">Garment Component & Fibres</h4>
        <button class="btn btn-primary add-garment-component-btn pull-right" type="button" data-id="3" id="add_garment_component_btn" onclick="addComponent(this, '`+user_type+`', 'update_tesco_label_order_status_form')" disabled><i class="fa fa-plus" aria-hidden="true"></i> Garment Component</button> 
        </div>`;
        
        for (let component_index = 1; component_index <= 3; component_index++) {

            fiber_component_div +=  `<div class="col-md-4 component-div">
            <div class="row">
            <div class="col-xs-12">
                <div class="form-group has-feedback">
                    <label for="tesco_care_garment_component_`+component_index+`" class="component-label">Garment Component </label>
                    <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_garment_component_`+component_index+`"  name="tesco_care_garment_component[]">
                        <option value="">-- Select Garment Component --</option>`;
                        $.each(order_data.components, function (id, name) {

                            fiber_component_div += `<option value=` + id + `>` + name + `</option>`;

                        });
                    fiber_component_div += `</select>
                    <div class="help-block with-errors"></div>
                </div>
            </div>`;

            for (let fiber_index = 1; fiber_index <= 6; fiber_index++) {
                fiber_component_div +=  `<div class="col-xs-8">
                    <div class="form-group has-feedback">
                        <select class="selectpicker form-control input-sm tesco-care-fiber-class" data-live-search="true" id="tesco_care_fiber_`+component_index+`_`+fiber_index+`" name="tesco_care_fiber[]">
                            <option value="">-- Select Select Fibre --</option>`;
                            $.each(order_data.fabriclists, function (id, name) {

                                fiber_component_div += `<option value=` + id + `>` + name + `</option>`;

                            });
                        fiber_component_div += `</select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group has-feedback">
                    <div class="input-group">
                        <input type="number" class="form-control input-sm fiber-individual-percentage" max="100" id="fiber_individual_percentage_`+component_index+`_`+fiber_index+`" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100" min="0" readonly>
                        <div class="input-group-addon">%</div>
                    </div>
                    <div class="help-block with-errors"></div>
                    </div>
                </div>`;
                
            }
            
            fiber_component_div +=  `<div class="col-xs-8">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-4">
                <div class="form-group has-feedback">
                <div class="input-group">
                    <input type="number" class="form-control input-sm composition-percentage total-percentage" name="fiber_total_percentage[]" id="fiber_total_percentage_`+component_index+`">
                    <div class="input-group-addon">%</div>
                </div>
                <div class="help-block with-errors"></div>
                </div>
            </div>
    
            </div>
        </div>`;
            
        }

        $('.garment-component-div').append(fiber_component_div);

        $('#label_order_id').val(order_data.order_details.id);
        $('#label_template_id').val(order_data.order_details.template_id);
        $('#label_template_name').val(order_data.order_details.item_master.template_name);
        $('.label-order-details').text(" Order Details - " + order_data.order_details.item_master.template_name);

        var footer_btn = `<button style="margin-left: 10px;" class="btn btn-primary pull-right input-sm hidden" id="submit_label_order_btn" type="submit" disabled="true" onclick="validateTescoLabelOrder('`+user_type+`')">Submit</button>
        <button class="btn btn-warning  pull-right input-sm" id="edit_label_order_btn" style="width: 55px;margin-left: 10px;" onclick="editLabelOrder()" type="button">Edit</button>
        <button class="btn btn-success  pull-right input-sm hidden" id="enable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick =enableArtworkEdit("`+ table_name +`","` + order_data.order_details.id +`")>Enable Edit</button>
        <button class="btn btn-danger pull-right input-sm hidden" id="disable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick = disableArtworkEdit("`+ table_name +`","` + order_data.order_details.id +`")>Disable Edit</button>
        <span id="error_msg_alert" style="color:red;"></span>
        <button class="btn btn-default pull-right remove-panel input-sm" id="close_panel_btn" type="button">Close</button>`;

        $('.label-order-footer').html(footer_btn);

        if (user_type === 'admin') {
            $('#submit_label_order_btn').removeClass('hidden');
            if (order_data.order_details.edit_permission === true) {
                $('#enable_edit').addClass('hidden');
                $('#disable_edit').removeClass('hidden');
            } else {
                $('#disable_edit').addClass('hidden');
                $('#enable_edit').removeClass('hidden');
            }
        } else {
            $('#enable_edit, #disable_edit').remove();

            if (order_data.order_details.edit_permission === true || order_data.order_details.is_order_count_empty === 1) {
                $('#submit_label_order_btn, #edit_label_order_btn').removeClass('hidden');
            } else {
                $('#submit_label_order_btn, #edit_label_order_btn').remove();
            }
        }

        $('#update_tesco_label_order_status_form input').prop('disabled', 'true');
        $('.selectpicker').prop('disabled', true);

        $('.remove-panel').on('click', function () {
            $('.edit-label-order-panel').removeClass('active');
            $('.selectpicker').prop('disabled', false);
            $(".selectpicker").selectpicker("refresh");
        });

        // erp_details array null , if order is not moved to erp tables
        // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
        if (order_data.erp_details[0] != null) {

            if (order_data.erp_details[0].status_name !== 'Hold For JC Queue' && order_data.erp_details[0].status_name !== 'Moved To ERP') {
                $('#enable_edit').remove();
                $('#disable_edit').remove();
                $('#edit_label_order_btn').remove();
                $('#submit_label_order_btn').remove();

                $('#error_msg_alert').html('ERP Order Status : <strong>' + order_data.erp_details[0].status_name + '</strong>. You are not allowed to edit this order.')
            }
        }
        if (order_data.order_details.pi_status === "PI Approved") {
            $('#enable_edit').remove();
            $('#disable_edit').remove();
            $('#edit_label_order_btn').remove();
            $('#submit_label_order_btn').remove();
            $('#error_msg_alert').html('<strong>PI is approved so you are not allowed to edit this order.</strong>')
        }

        mapLabelStoredValues(order_data, user_type);

        $('.edit-label-order-panel').addClass('active');
        $(".selectpicker").selectpicker("refresh");
        $('.edit-label-order-panel').show();
        
    } 

    editLabelOrder = () => {
        $("#update_tesco_label_order_status_form :input").prop("disabled", false);
        $(".dropdown-toggle").css("background-color", "#fff");
        $('#add_garment_component_btn').removeAttr('disabled');
        $('.selectpicker').prop('disabled', false);
        $(".selectpicker").selectpicker("refresh");
        $('#update_tesco_label_order_status_form').validator('update');
        $(".fiber-individual-percentage").prop("readonly", true);

        if(!$('#tesco_care_woolmark_logo').val())
        {
            $('#tesco_care_woolmark_batch_code').prop('disabled', true);
        }

        var label_template_id = $('#label_template_id').val()

        if(label_template_id == 58 || label_template_id == 66 || label_template_id == 73 || label_template_id == 74)
        {
            $('#tesco_care_uk_ce_mark').prop('disabled', true);
        }

        

    }

    validateTescoLabelOrder = (user_role) => {

        $('#update_tesco_label_order_status_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                console.log("Form submission prevented due to validation errors.");
                // Log each invalid field
                $('#update_tesco_label_order_status_form').find(':invalid').each(function(index, element) {
                    console.log("Validation error in field:", element.name || element.id, "Value:", $(element).val());
                    console.log("Error details:", element.validationMessage);
                });
            } else {
                e.preventDefault();
                $('#submit_label_order_btn').prop('disabled',true)
                updateCarelabelOrder(user_role);
            }
        })
    }

    updateCarelabelOrder = (user_role) => {

        var form_obj = [{
            "order_id": $('#label_order_id').val(),
            "template_id": $('#label_template_id').val(),
        }];

        var formData = $('#update_tesco_label_order_status_form').serialize() + '&' + $.param(form_obj[0]);

        $.ajax({
            type: "put",
            url: base_url + "/" + user_role + "/tesco/update-care-label-order",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            dataType: "json",
            beforeSend: function () {
                $(".loader").show();
                $('#submit_label_order_btn').prop('disabled', true);
                $('#update_tesco_label_order_status_form input').prop('disabled', 'true');
                $('.selectpicker').prop('disabled', true);
                $(".selectpicker").selectpicker("refresh");
            },
            statusCode: {
                200: function (response) {

                    $('#edit_label_order_result').html('<div class="alert alert-success alert-dismissible" id="alert_success"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been updated successfully</div>');
                    
                },
                400: function (error) {
                    $('#edit_label_order_result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
                },
                422: function (errorXHR) {
                    var error_msg = ``;
                    $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                        error_msg += `<li>` + valueOfElement + `</li>`;
                    });
                    $('#edit_label_order_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
                    $(window).scrollTop(0);
                }
            },
            complete: function () {
                
                $(".loader").hide();

                $("#alert_success").fadeTo(2000, 500).slideUp(500, function () {
                    $("#alert_success").slideUp(500);
                });
                
            }
        });
    }


$(document).off('change', '#tesco_care_woolmark_logo').on('change', '#tesco_care_woolmark_logo', (e) => {
    if ($(e.target).val()) {
        // Enable the input by setting disabled property to false
        $('#tesco_care_woolmark_batch_code').prop('disabled', false);
    } else {
        // Disable the input by setting disabled property to true
        $('#tesco_care_woolmark_batch_code').prop('disabled', true);
        $('#tesco_care_woolmark_batch_code').val("");
    }
});

$(document).off('change', '#tesco_ex_non_ex').on('change', '#tesco_ex_non_ex', (e) => {
    if ($(e.target).val() == "non-exclusive") {
        // Enable the input by setting disabled property to false
        $('#tesco_care_address_name').prop('disabled', false);
        $('#tesco_care_address_line_1').prop('disabled', false);
        $('#tesco_care_address_line_2').prop('disabled', false);
        $('#tesco_care_address_line_3').prop('disabled', false);
    } else {
        // Disable the input by setting disabled property to true
        $('#tesco_care_address_name').prop('disabled', true);
        $('#tesco_care_address_name').val("");
        $('#tesco_care_address_line_1').prop('disabled', true);
        $('#tesco_care_address_line_1').val("");
        $('#tesco_care_address_line_2').prop('disabled', true);
        $('#tesco_care_address_line_2').val("");
        $('#tesco_care_address_line_3').prop('disabled', true);
        $('#tesco_care_address_line_3').val("");
    }
});

});