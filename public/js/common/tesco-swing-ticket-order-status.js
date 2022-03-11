$( () => { 
    const table_name = 'tesco_swingticket_orders';

    getSwingTicketDetails = (obj, user_type) => {

    var id = $(obj).parent().attr('data-id');
    var table_name = $(obj).parent().attr('data-table');

    $.ajax({
        url: base_url + `/${user_type}/tesco/get-swing-ticket-order-details`,
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
               
                loadSwingTicketDetails(data, user_type)

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
    
    loadSwingTicketDetails = (order_data, user_role) => {
        var user_type = user_role.toLowerCase();
        
        $('#swing_ticket_select, #swing_ticket_qty_table').html('');

        var metadata_select_div = ` <div class="form-group has-feedback">
                                     <label for="swing_product_desc" class="component-label">Product Description </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_product_desc" name="swing_product_desc" required>
                                            <option value="">-- Select Product Description --</option>`;
                                            $.each(order_data.product_description, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="swing_tesco_supplier" class="component-label">Supplier </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_tesco_supplier" name="swing_tesco_supplier" onchange="getMoq(this, '`+user_type+`', 'update')" required>
                                            <option value="">-- Select Supplier --</option>`;
                                            $.each(order_data.tesco_suppliers, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="swing_product_benefit" class="component-label">Product Benefit </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_product_benefit" name="swing_product_benefit" >
                                            <option value="">-- Select Product Benefit --</option>`;
                                            $.each(order_data.product_benefits, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="swing_mm_product_benefit" class="component-label">MM Product Benefit </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_mm_product_benefit" name="swing_mm_product_benefit" >
                                            <option value="">-- Select MM Product Benefit --</option>`;
                                            $.each(order_data.mm_product_benefits, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>
                                    <div class="form-group has-feedback">
                                     <label for="swing_season_code" class="component-label">Season Code </label>
                                        <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_season_code" name="swing_season_code" required>
                                            <option value="">-- Select Season Code --</option>`;
                                            $.each(order_data.season_codes, function (id, name) {

                                                metadata_select_div += `<option value=` + id + `>` + name + `</option>`;

                                            });
                                        metadata_select_div += `</select>
                                    </div>`;

        $('#swing_ticket_select').append(metadata_select_div);

        $('#swingticket_order_id').val(order_data.order_details.id);
        $('#swingticket_template_id').val(order_data.order_details.template_id);
        $('#swingticket_template_name').val(order_data.order_details.item_master.template_name);
        $('.swingticket-order-details').text(" Order Details - " + order_data.order_details.item_master.template_name + " (" + order_data.order_details.quantity_details[0].sizes.department_title +")");
        
        renderQtyTableBody(order_data.order_details.quantity_details, order_data.moq)

        var footer_btn = `<button style="margin-left: 10px;" class="btn btn-primary pull-right input-sm hidden" id="submit_swingticket_order_btn" type="submit" disabled="true" onclick="validateTescoSwingTicketOrder('`+user_type+`')">Submit</button>
        <button class="btn btn-warning  pull-right input-sm" id="edit_swingticket_order_btn" style="width: 55px;margin-left: 10px;" onclick="editSwingTicketOrder()" type="button">Edit</button>
        <button class="btn btn-success  pull-right input-sm hidden" id="enable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick =enableArtworkEdit("`+ table_name +`","` + order_data.order_details.id +`")>Enable Edit</button>
        <button class="btn btn-danger pull-right input-sm hidden" id="disable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick = disableArtworkEdit("`+ table_name +`","` + order_data.order_details.id +`")>Disable Edit</button>
        <span id="error_msg_alert" style="color:red;"></span>
        <button class="btn btn-default pull-right remove-panel input-sm" id="close_panel_btn" type="button">Close</button>`;

        $('.swingticket-order-footer').html(footer_btn);

        templateDependentData(order_data.order_details.template_id, order_data);

        if (user_type === 'admin') {
            $('#submit_swingticket_order_btn').removeClass('hidden');
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
                $('#submit_swingticket_order_btn, #edit_swingticket_order_btn').removeClass('hidden');
            } else {
                $('#submit_swingticket_order_btn, #edit_swingticket_order_btn').remove();
            }
        }

        $('#update_tesco_swingticket_order_status_form input').prop('disabled', 'true');
        $('.selectpicker').prop('disabled', true);

        $('.remove-panel').on('click', function () {
            $('.edit-swingticket-order-panel').removeClass('active');
            $('.selectpicker').prop('disabled', false);
            $(".selectpicker").selectpicker("refresh");
        });

        // erp_details array null , if order is not moved to erp tables
        // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
        if (order_data.erp_details[0] != null) {

            if (order_data.erp_details[0].status_name !== 'Hold For JC Queue' && order_data.erp_details[0].status_name !== 'Moved To ERP') {
                $('#enable_edit').remove();
                $('#disable_edit').remove();
                $('#edit_swingticket_order_btn').remove();
                $('#submit_swingticket_order_btn').remove();

                $('#error_msg_alert').html('ERP Order Status : <strong>' + order_data.erp_details[0].status_name + '</strong>. You are not allowed to edit this order.')
            }
        }
        if (order_data.order_details.pi_status === "PI Approved") {
            $('#enable_edit').remove();
            $('#disable_edit').remove();
            $('#edit_swingticket_order_btn').remove();
            $('#submit_swingticket_order_btn').remove();
            $('#error_msg_alert').html('<strong>PI is approved so you are not allowed to edit this order.</strong>')
        }

        mapSwingTicketStoredValues(order_data);

        $('.edit-swingticket-order-panel').addClass('active');
        $(".selectpicker").selectpicker("refresh");
        $('.edit-swingticket-order-panel').show();
        
    } 


    templateDependentData = (template, order_data) => {
        var contentHtml = '';

        if([44, 46, 48].find(data => data === template)){
            contentHtml += `<div class="form-group has-feedback">
            <label for="swing_wee_symbol" class="component-label">WEE Symbol </label>
               <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_wee_symbol" name="swing_wee_symbol">
                   <option value="">-- Select WEE Symbol --</option>`;
                   $.each(order_data.wee_symbols, function (id, value) {

                       contentHtml += `<option data-id="`+value.id+`" data-content="<img class='tesco-swing-symbols' src='`+value.wee_symbol+`' /> `+value.wee_symbol_text+`" value="`+value.id+`">`+value.wee_symbol_text+`</option>`;

                   });
               contentHtml += `</select>
           </div>`
        }

        if([46, 50].find(data => data === template)){
            contentHtml += `<div class="form-group has-feedback">
                <label for="swing_ce_mark" class="form_label" style="visibility:hidden">Dont Show</label>
                <div class="checkbox">
                    <label>
                        <input id="swing_ce_mark" name="swing_ce_mark" type="checkbox" disabled=""> CE mark
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>`;
        }

        if([53].find(data => data === template)){
            contentHtml += `<div class="form-group has-feedback">
                <label for="swing_machine_washable" class="form_label" style="visibility:hidden">Dont Show</label>
                <div class="checkbox">
                    <label>
                        <input id="swing_machine_washable" name="swing_machine_washable" type="checkbox" disabled=""> Machine Washable
                    </label>
                </div>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
            <label for="swing_woolmark_logo" class="component-label">Woolmark Logo </label>
               <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_woolmark_logo" name="swing_woolmark_logo">
                   <option value="">-- Select Woolmark Logo --</option>`;
                   $.each(order_data.woolmark_logos, function (id, value) {

                       contentHtml += `<option data-id="`+value.id+`" data-content="<img class='tesco-swing-logos' src='`+value.woolmark_logo+`' /> `+value.woolmark_logo_text+`" value="`+value.id+`">`+value.woolmark_logo_text+`</option>`;

                   });
               contentHtml += `</select>
           </div>`;
        }

        if([45, 51, 52, 49].find(data => data === template)){
            contentHtml += `<div class="form-group has-feedback">
                <label for="swing_style_number" class="form_label">Style Number</label>
                <input type="text" class="form-control input-sm" placeholder="Style Number" id="swing_style_number" name="swing_style_number" maxlength="8" onkeypress="return isNumber(event)" required>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback">
            <label for="swing_coo" class="component-label">Country Of Origin </label>
               <select class="selectpicker form-control input-sm " data-live-search="true" id="swing_coo" name="swing_coo" required>
                   <option value="">-- Select Country Of Origin --</option>`;
                   $.each(order_data.coo, function (id, name) {

                    contentHtml += `<option value=` + id + `>` + name + `</option>`;

                });
               contentHtml += `</select>
           </div>`;
        }

        $('#template_dependent_data').html(contentHtml);
    }

    editSwingTicketOrder = () => {
        $("#update_tesco_swingticket_order_status_form :input").prop("disabled", false);
        $(".dropdown-toggle").css("background-color", "#fff");
        $('.selectpicker').prop('disabled', false);
        $(".selectpicker").selectpicker("refresh");
        $('#update_tesco_swingticket_order_status_form').validator('update');
    }

    validateTescoSwingTicketOrder = (user_role) => {

        $('#update_tesco_swingticket_order_status_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {

            } else {
                e.preventDefault();
                $('#submit_swingticket_order_btn').prop('disabled',true)
                console.log("PKDDD");
                updateSwingTicketOrder(user_role);
            }
        })
    }

    updateSwingTicketOrder = (user_role) => {

        $.ajax({
            type: "put",
            url: base_url + "/" + user_role + "/tesco/update-swing-ticket-order",
            data: $('#update_tesco_swingticket_order_status_form').serialize(),
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            dataType: "json",
            beforeSend: function () {
                $(".loader").show();
                $('#submit_swingticket_order_btn').prop('disabled', true);
                $('#update_tesco_swingticket_order_status_form input').prop('disabled', 'true');
                $('.selectpicker').prop('disabled', true);
                $(".selectpicker").selectpicker("refresh");
            },
            statusCode: {
                200: function (response) {

                    $('#edit_swingticket_order_result').html('<div class="alert alert-success alert-dismissible" id="alert_success"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been updated successfully</div>');
                    
                },
                400: function (error) {
                    $('#edit_swingticket_order_result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
                },
                422: function (errorXHR) {
                    var error_msg = ``;
                    $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                        error_msg += `<li>` + valueOfElement + `</li>`;
                    });
                    $('#edit_swingticket_order_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
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