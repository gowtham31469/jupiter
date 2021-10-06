$( () => {

    // Method to render view tesco care label orders page
    renderLabelViewOrderPage = (order_data, role) => {
        user_role = role.toLowerCase();
        $('.main-content').load('/' + user_role + '/tesco/care-label-orders', (response, status, xhr) => {
            $('li.year-nav-menu, li.buyer-nav-menu').addClass('hidden');

            $('li.buyer-nav-menu').addClass('hidden');

            /** Make form as readonly */
            $('#tesco_care_label_order_form input').prop('disabled', 'true');
            $('.selectpicker').prop('disabled', true);
            $('.label-order-submit-btn').remove();

            // Map stored values 
            mapLabelStoredValues(order_data, user_role);

            if (order_data.order_details.order_status === "Order Placed") {
                $('.label-order-cancel-btn').show();
            }

            $(".selectpicker").selectpicker("refresh");
            $('#add_garment_component_btn').remove();
            $('#remove_component_btn').remove();
        });

    }

    mapLabelStoredValues = (order_data, user_role) => {
        $('#hidden_id').val(order_data.order_details.id)
        $('#hidden_name').val(order_data.order_details.item_master.template_name)
        $('.create-download-artwork').css('display','inline-block')
        $('#tesco_care_po').val(order_data.order_details.po_number)
        $('#tesco_care_po').val(order_data.order_details.po_number)
        $('#tesco_care_eqos').val(order_data.order_details.eqos_site_code)
        $('#tesco_care_ce_style').val(order_data.order_details.ce_style_ref)
        $('#tesco_care_uk_style').val(order_data.order_details.uk_style_ref)
        $('#tesco_care_fabric_code').val(order_data.order_details.fabric_batch_code)
        $('#tesco_care_leather_id').val(order_data.order_details.leather_suede_code)
        $('#tesco_care_coo').val(order_data.order_details.country_of_origin_id)
        $('#tesco_care_supplier').val(order_data.order_details.tesco_metadata_supplier_id)
        $('#tesco_care_part_of_two_piece_set').val(order_data.order_details.potps_id)

        $('#tesco_care_button_space').prop('checked', order_data.order_details.include_btn_sew_space);
        $('#tesco_care_exclusive_trim').prop('checked', order_data.order_details.exclusive_trims);
        $('#tesco_care_non_textile_part').prop('checked', order_data.order_details.contains_non_textile_part);
        $('#tesco_care_part_of_set').prop('checked', order_data.order_details.part_of_set);

        $('#tesco_care_wash_instruction').val(order_data.order_details.wash_symbol_id)
        $('#tesco_care_bleach_instruction').val(order_data.order_details.bleach_symbol_id)
        $('#tesco_care_dry_instruction').val(order_data.order_details.dry_symbol_id)
        $('#tesco_care_iron_instruction').val(order_data.order_details.ironing_symbol_id)
        $('#tesco_care_dry_clean_instruction').val(order_data.order_details.dryclean_symbol_id)

        $('#tesco_care_additional_instruction_1').val(order_data.order_details.additional_care_id_1)
        $('#tesco_care_additional_instruction_2').val(order_data.order_details.additional_care_id_2)
        $('#tesco_care_additional_instruction_3').val(order_data.order_details.additional_care_id_3)

        $('#tesco_care_kids_warning').val(order_data.order_details.kids_warning_id)

        if (order_data.order_details.primary_size_id === 4) {
            $('#baby_sleeping_bag_div').removeClass("hidden");

            $('#tesco_care_bs_en').prop('checked', order_data.order_details.bs_en_declaration)
            $('#tesco_care_sleep_bag_symbol').prop('checked', order_data.order_details.baby_sleeping_bag_symbol)
            $('#tesco_care_tog').val(order_data.order_details.tog)
        }
        
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
                                            <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control input-sm label-quantity-field" placeholder="Quantity" id="quantity_` + valueOfElement.id + `" name="quantity[]" value="` + valueOfElement.count + `" min="`+order_data.moq+`" data-min-error="Quantity Should be >= `+order_data.moq+`." disabled><div class="help-block with-errors"></div></div></td>
                                            <input type='hidden' id='secondary_size_data_` + valueOfElement.id + `' name='secondary_size_data[]'>
                                        </tr>`;
        });
        quantity_html += `</tbody>
                                        </table>
                                        </div><div id="barcode-svg-div" style="display:none;"><svg id="barcode-ean-13"></svg></div>`;
        $('.teasco-care-quantity-div').html(quantity_html);

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
                                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber_` + index + `_` + fiber_count + `" name="tesco_care_fiber[]" disabled>
                                                                    <option value="">-- Select Fiber --</option>`;
                                $.each(data.data.fabriclists, function (fabric_index, item) {

                                    garment_comp_div += fibre_composition_id === parseInt(fabric_index) ? `<option value=` + fabric_index + ` selected>` + item + `</option>` : `<option value=` + fabric_index + `>` + item + `</option>`;

                                });

                                garment_comp_div += `</select><div class="help-block with-errors"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-4">
                                                                <div class="form-group has-feedback">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control input-sm fiber-individual-percentage" max="100"  id="fiber_individual_percentage_` + index + `_` + fiber_count + `" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100" value="` + fibre_composition_percentage + `" disabled>
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