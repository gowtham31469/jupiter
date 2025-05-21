$( () => {

    // Method to render view tesco care label orders page
    renderLabelViewOrderPage = (order_data, role) => {
        user_role = role.toLowerCase();
        $('.main-content').load('/' + user_role + '/tesco/care-label-orders?size='+order_data.order_details.primary_size_id+'&template='+order_data.order_details.template_id, (response, status, xhr) => {
            $('li.year-nav-menu, li.buyer-nav-menu').addClass('hidden');

            $('li.buyer-nav-menu').addClass('hidden');

            /** Make form as readonly */
            $('#tesco_care_label_order_form input').prop('disabled', 'true');
            $('.selectpicker').prop('disabled', true);
            $('.label-order-submit-btn').remove();

            // Map stored values 
            mapLabelStoredValues(order_data, user_role);

            if (order_data.order_details.order_status === "Order Placed") {
                $('.order-cancel-btn').show();
            }

            $(".selectpicker").selectpicker("refresh");
            $('#add_garment_component_btn').remove();
            $('#remove_component_btn').remove();
            $('.top-content').html('<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;"><h4><strong>Create Order For - ' + order_data.order_details.item_master.template_name + '</strong></h4></div>');

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

        });

    }

    mapLabelStoredValues = (order_data, user_role) => {
        $('#hidden_id').val(order_data.order_details.id)
        $('#hidden_name').val(order_data.order_details.item_master.template_name)

        if(order_data.order_details.order_status != "Order Pending")
        {
            $('.create-download-artwork').css('display','inline-block')
        }
        
        $('#tesco_care_po').val(order_data.order_details.po_number)
        $('#tesco_care_eqos').val(order_data.order_details.eqos_site_code)
        $('#tesco_care_ce_style').val(order_data.order_details.ce_style_ref)
        $('#tesco_care_uk_style').val(order_data.order_details.uk_style_ref)
        $('#tesco_care_fabric_code').val(order_data.order_details.fabric_batch_code)
        $('#tesco_care_leather_id').val(order_data.order_details.leather_suede_code)
        $('#tesco_care_coo').val(order_data.order_details.country_of_origin_id)
        $('#tesco_care_supplier').val(order_data.order_details.tesco_metadata_supplier_id)
        $('#tesco_care_part_of_two_piece_set').val(order_data.order_details.potps_id)
        $('#tesco_care_woolmark_logo').val(order_data.order_details.woolmark_logo_id)
        $('#tesco_ex_non_ex').val(order_data.order_details.exclusive_or_non_exclusive)
        $('#tesco_care_address_name').val(order_data.order_details.address_name)
        $('#tesco_care_address_line_1').val(order_data.order_details.address_line1)
        $('#tesco_care_address_line_2').val(order_data.order_details.address_line2)
        $('#tesco_care_address_line_3').val(order_data.order_details.address_line3)
        $('#tesco_care_woolmark_batch_code').val(order_data.order_details.woolmark_batch_code)
        $('#tesco_care_uk_ce_mark').val(order_data.order_details.uk_ce_mark)
        $('#tesco_care_flammability').val(order_data.order_details.flammability_id)
        $('#tesco_care_garment_statements').val(order_data.order_details.garment_statement_id)

        $('#tesco_care_button_space').prop('checked', order_data.order_details.include_btn_sew_space);

        $('#tesco_care_wash_instruction').val(order_data.order_details.wash_symbol_id)
        $('#tesco_care_bleach_instruction').val(order_data.order_details.bleach_symbol_id)
        $('#tesco_care_dry_instruction').val(order_data.order_details.dry_symbol_id)
        $('#tesco_care_iron_instruction').val(order_data.order_details.ironing_symbol_id)
        $('#tesco_care_dry_clean_instruction').val(order_data.order_details.dryclean_symbol_id)

        $('#tesco_care_additional_instruction_1').val(order_data.order_details.additional_care_id_1)
        $('#tesco_care_additional_instruction_2').val(order_data.order_details.additional_care_id_2)
        $('#tesco_care_additional_instruction_3').val(order_data.order_details.additional_care_id_3)

        $('#tesco_care_kids_warning').val(order_data.order_details.kids_warning_id)

        var order_template_id = order_data.order_details.template_id

        if(order_template_id == 57 || order_template_id == 58 || order_template_id == 59 || order_template_id == 60 || order_template_id == 61 || order_template_id == 63 || order_template_id == 65 || order_template_id == 68 || order_template_id == 70 || order_template_id == 72 || order_template_id == 74 || order_template_id == 78 || order_template_id == 79 || order_template_id == 81 || order_template_id == 83)
        {
            $('.ex_non_ex_div').addClass('hidden');
        }

        if (order_data.order_details.size_department_text === 'Baby Sleeping Bags') {
            $('#baby_sleeping_bag_div').removeClass("hidden");

            $('#tesco_care_bs_en').prop('checked', order_data.order_details.bs_en_declaration)
            $('#tesco_care_sleep_bag_symbol').prop('checked', order_data.order_details.baby_sleeping_bag_symbol)
            $('#tesco_care_tog_rating').val(order_data.order_details.tog)
        }

        if(order_template_id == '64' || order_template_id == '65' )
        {
            $('#baby_sleeping_bag_div').removeClass("hidden");

            $('#tesco_care_bs_en').prop('checked', order_data.order_details.bs_en_declaration)
            $('#tesco_care_sleep_bag_symbol').prop('checked', order_data.order_details.baby_sleeping_bag_symbol)
            $('#tesco_care_tog_rating').val(order_data.order_details.tog)
        }

        var div_html = '<input class="table-id-hidden" id="Washcare_order_id" name="washcare_order_id" type="hidden" value="' + order_data.order_details.id + '">';

        $('.label-order-invisible-div').html(div_html);
        
        var temp_file = order_data.order_details.item_master.template_file.split(',');

        var quantity_html = `<input type="hidden" id="primary_size_id" name="primary_size_id">
        <object style="opacity:0;width:0px;height:0px" id="tesco_svg_modal" class="center-block tesco_svg" data="` + temp_file[0]+`" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="tesco_svg_back_modal" class="center-block tesco_svg" data="` + temp_file[1]+`" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="tesco_svg_back_text_modal" class="center-block tesco_svg" data="` + temp_file[2]+`" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="tesco_svg_front_modal" class="center-block tesco_svg" data="` + temp_file[3]+`" type="image/svg+xml"></object>
                                        <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>UK</th>
                                                <th>EUR</th>
                                                <th>Secondary Size</th>
                                                <th>UK EAN</th>
                                                <th>CE EAN</th>
                                                <th>Quantity <small>(Units)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>`;

        $.each(order_data.order_details.quantities, (indexInArray, valueOfElement) => {
            quantity_html += `<tr>
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.size + `</td>
                                            <td >` + valueOfElement.ce_size + `</td>  <td >`;

            for (var index = 0; index < valueOfElement.selected_secondary_sizes.length; index++) {

                quantity_html += `<strong class="text-justify">` + $.trim(valueOfElement.selected_secondary_sizes[index].secondary_size) + `</strong> : ` + $.trim(valueOfElement.selected_secondary_sizes[index].value) + `<br>`;

            }

            var ce_ean = valueOfElement.ce_ean != null?valueOfElement.ce_ean:"";
            var uk_ean = valueOfElement.uk_ean != null?valueOfElement.uk_ean:"";
                quantity_html += `</td> <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" id="uk_ean_` + valueOfElement.id + `" name="uk_ean[]" value="` + uk_ean + `" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)" onkeyup="return validateEanBarcodeValue(this)" disabled><div class="help-block with-errors"></div></div></td>
                                                <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" id="ce_ean_` + valueOfElement.id + `" name="ce_ean[]" value="` + ce_ean + `" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)" onkeyup="return validateEanBarcodeValue(this)" disabled><div class="help-block with-errors"></div></div></td>
                                                <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control input-sm tesco-quantity-field" placeholder="Quantity" id="quantity_` + valueOfElement.id + `" name="quantity[]" value="` + (valueOfElement.count !== 0 ? valueOfElement.count : '') + `"
                                                ` + (order_data.moq ? `min="` + order_data.moq + `" data-min-error="Quantity Should be >= ` + order_data.moq + `."` : '') + `
                                                ` + (order_data.max ? `max="` + order_data.max + `" data-max-error="Quantity Should be <= ` + order_data.max + `."` : '') + `
                                                disabled><div class="help-block with-errors"></div></div></td>
                                                <input type='hidden' id='secondary_size_data_` + valueOfElement.id + `' name='secondary_size_data[]'>
                                            </tr>`;
        });
        quantity_html += `</tbody>
                                        </table>
                                        </div><div id="barcode-svg-div" style="display:none;"><svg id="barcode-ean-13"></svg></div>`;
        $('.tesco-care-quantity-div').html(quantity_html);

        // dynamic fiber component values mapping
        $.each(order_data.order_details.garment_components, (i, component) => {
            var index = i + 1;

            if (index > 3) {

                $.ajax({
                    url: base_url + "/" + user_role + "/tesco/care-label-fiber-components",
                    type: "get",
                    dataType: "json",
                    data: {
                        _token: csrf_token
                    },
                    statusCode: {
                        200: function (data) {

                            var fiber_count = 1;
                            let fiber_count_index = 0;

                            var garment_comp_div = `<div class="col-md-4 component-div">
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                <div class="form-group has-feedback">
                                                                    <label for="tesco_care_garment_component_` + index + `" class="component-label">Garment Component </label>
                                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_garment_component_` + index + `" name="tesco_care_garment_component[]" value="` + component.garment_component_id + `" disabled>
                                                                    <option value="">-- Select Garment Component --</option>`;
                            $.each(data.data.components, function (component_index, item) {

                                garment_comp_div += component.garment_component_id === parseInt(component_index) ? `<option value=` + component_index + ` selected>` + item + `</option>` : `<option value=` + component_index + `>` + item + `</option>`;

                            });
                            garment_comp_div += `</select><div class="help-block with-errors"></div>
                                                            </div>
                                                        </div>`;

                            while (fiber_count <= 6) {
                                var fibre_composition_id = component.fibers[fiber_count_index] != undefined ? component.fibers[fiber_count_index].fibre_composition_id : 0;
                                var fibre_composition_percentage = component.fibers[fiber_count_index] != undefined ? component.fibers[fiber_count_index].fibre_composition_percentage : "";

                                garment_comp_div += `<div class="col-xs-8">
                                                                    <div class="form-group has-feedback">
                                                                    <select class="selectpicker form-control input-sm tesco-care-fiber-class" data-live-search="true" id="tesco_care_fiber_` + index + `_` + fiber_count + `" name="tesco_care_fiber[]" disabled>
                                                                    <option value="">-- Select Fibre --</option>`;
                                $.each(data.data.fabriclists, function (fabric_index, item) {

                                    garment_comp_div += fibre_composition_id === parseInt(fabric_index) ? `<option value=` + fabric_index + ` selected>` + item + `</option>` : `<option value=` + fabric_index + `>` + item + `</option>`;

                                });

                                garment_comp_div += `</select><div class="help-block with-errors"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="form-group has-feedback">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control input-sm fiber-individual-percentage" max="100"  id="fiber_individual_percentage_` + index + `_` + fiber_count + `" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100" value="` + fibre_composition_percentage + `" disabled min="0">
                                                                    <div class="input-group-addon">%</div>
                                                                </div>
                                                                <div class="help-block with-errors"></div>
                                                                </div>
                                                            </div>`;
                                fiber_count++;
                                fiber_count_index++;
                            }
                            garment_comp_div += `<div class="col-xs-8">
                                                                    <h5 class="text-right"><strong>Total</strong></h5>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                    <div class="form-group has-feedback">
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control input-sm composition-percentage total-percentage" id="fiber_total_percentage_` + index + `" name="fiber_total_percentage[]" value="` + component.total_composition_percentage + `" disabled>
                                                                        <div class="input-group-addon">%</div>
                                                                    </div>
                                                                    <div class="help-block with-errors"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>`;
                            $('.garment-component-div').append(garment_comp_div);

                            $(".selectpicker").selectpicker("refresh");

                        }
                    }
                });

            }

            $('#tesco_care_garment_component_' + index).val(component.garment_component_id);
            $('#fiber_total_percentage_' + index).val(component.total_composition_percentage);

            $.each(component.fibers, (j, fiber) => {
                var index_1 = j + 1;

                $('#tesco_care_fiber_' + index + '_' + index_1).val(fiber.fibre_composition_id);
                $('#fiber_individual_percentage_' + index + '_' + index_1).val(fiber.fibre_composition_percentage);


            });

        });
    }

});