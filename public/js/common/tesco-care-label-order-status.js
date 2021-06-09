$( () => { 
    const table_name = 'tesco_washcare_orders';
    
    viewOrderDetailsPanel = (order_data, user_role) => {
        var user_type = user_role.toLowerCase();
        
        $('.garment-component-div, .label-metadata-select, .teasco-care-quantity-div').html('');

        var metadata_select_div = ` <div class="form-group has-feedback">
                                     <label for="tesco_care_coo" class="component-label">Country Of Origin </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_coo" name="tesco_care_coo">
                                            <option value="">-- Select Country Of Origin --</option>`;
                                            $.each(order_data.metadata.coo, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_supplier" class="component-label">Supplier </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_supplier" name="tesco_care_supplier" onchange="getMoq('`+user_type+`', 'update')">
                                            <option value="">-- Select Supplier --</option>`;
                                            $.each(order_data.metadata.tesco_suppliers, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <hr>
                                    <h4 class="text-center">Care Instructions</h4>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_wash_instruction" class="component-label">Wash Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_wash_instruction" name="tesco_care_wash_instruction" required>
                                            <option value="">-- Select Wash Instruction --</option>`;
                                            $.each(order_data.metadata.symbols['Washing'], function (id, value) {

                                                metadata_select_div += `<option title="`+value.degree+`" data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_bleach_instruction" class="component-label">Bleach Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_bleach_instruction" name="tesco_care_bleach_instruction" required>
                                            <option value="">-- Select Bleach Instruction --</option>`;
                                            $.each(order_data.metadata.symbols['Whitening'], function (id, value) {

                                                metadata_select_div += `<option title="`+value.degree+`" data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_dry_instruction" class="component-label">Dry Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_dry_instruction" name="tesco_care_dry_instruction" required>
                                            <option value="">-- Select Dry Instruction --</option>`;
                                            $.each(order_data.metadata.symbols['Dryer'], function (id, value) {
                                                
                                                metadata_select_div += `<option title="`+value.degree+`" data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_iron_instruction" class="component-label">Iron Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_iron_instruction" name="tesco_care_iron_instruction" required>
                                            <option value="">-- Select Iron Instruction --</option>`;
                                            $.each(order_data.metadata.symbols['Ironing'], function (id, value) {

                                                metadata_select_div += `<option title="`+value.degree+`" data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="tesco_care_dry_clean_instruction" class="component-label">Dry Clean Instruction </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_dry_clean_instruction" name="tesco_care_dry_clean_instruction" required>
                                            <option value="">-- Select Dry Clean Instruction --</option>`;
                                            $.each(order_data.metadata.symbols['Prof Cleaning'], function (id, value) {

                                                metadata_select_div += `<option title="`+value.degree+`" data-id="`+value.id+`" data-content="<img class='tesco-care-symbols' src='`+value.iso_symbols+`' /> `+value.degree+`" value="`+value.id+`" >`+value.degree+`</option>`;

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
                                                                        $.each(order_data.metadata.additional_instructions, function (id, name) {

                                                                            metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                                                        });
                                                                    metadata_select_div += `</select>
                                                                </div>`;
                                        
                                    }

                                    metadata_select_div += `<div class="form-group has-feedback">
                                                                    <label for="tesco_care_kids_warning" class="component-label">Kids Warning Statement </label>
                                                                    <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_kids_warning" name="tesco_care_kids_warning">
                                                                        <option value="">-- Select Kids Warning Statement --</option>`;
                                                                        $.each(order_data.metadata.kids_warning, function (id, name) {

                                                                            metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                                                        });
                                                                    metadata_select_div += `</select>
                                                                </div>
                                                                `

        $('.label-metadata-select').append(metadata_select_div);

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
                        $.each(order_data.metadata.components, function (id, name) {

                            fiber_component_div += `<option value=` + id + `>` + name + `</option>`;

                        });
                    fiber_component_div += `</select>
                    <div class="help-block with-errors"></div>
                </div>
            </div>`;

            for (let fiber_index = 1; fiber_index <= 6; fiber_index++) {
                fiber_component_div +=  `<div class="col-xs-9">
                    <div class="form-group has-feedback">
                        <select class="selectpicker form-control input-sm " data-live-search="true" id="tesco_care_fiber_`+component_index+`_`+fiber_index+`" name="tesco_care_fiber_`+component_index+`[]">
                            <option value="">-- Select Select Fiber --</option>`;
                            $.each(order_data.metadata.fabriclists, function (id, name) {

                                fiber_component_div += `<option value=` + id + `>` + name + `</option>`;

                            });
                        fiber_component_div += `</select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group has-feedback">
                    <div class="input-group">
                        <input type="number" class="form-control input-sm fiber-individual-percentage-`+component_index+`" onkeyup="return calculateTotalFiberPercentage()" max="100" id="fiber_individual_percentage_`+component_index+`_`+fiber_index+`" name="fiber_individual_percentage_`+component_index+`[]" data-max-error= "Value must be <= 100">
                        <div class="input-group-addon">%</div>
                    </div>
                    <div class="help-block with-errors"></div>
                    </div>
                </div>`;
                
            }
            
            fiber_component_div +=  `<div class="col-xs-9">
                <h5 class="text-right"><strong>Total</strong></h5>
            </div>
            <div class="col-xs-3">
                <div class="form-group has-feedback">
                <div class="input-group">
                    <input type="number" class="form-control input-sm composition-percentage total-percentage-`+component_index+`" name="fiber_total_percentage[]" id="fiber_total_percentage_`+component_index+`">
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
        $('#label_template_name').val(order_data.tesco_item_master[0].item_name);
        $('.label-order-details').text(" Order Details - " + order_data.tesco_item_master[0].item_name);

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
    }

    validateTescoLabelOrder = (user_role) => {

        $('#update_tesco_label_order_status_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {

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

});