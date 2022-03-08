$( () => {

    // Method to render view tesco care label orders page
    renderSwingTicketViewOrderPage = (order_data, role) => {
        user_role = role.toLowerCase();

        $('.main-content').load('/' + user_role + '/tesco/swing-ticket-orders?size='+order_data.order_details.primary_size_id+'&template='+order_data.order_details.template_id, ( response, status, xhr ) => {
            $('li.year-nav-menu, li.buyer-nav-menu').addClass('hidden');

            /** Make form as readonly */
            $('#tesco_swing_ticket_order_form input').prop('disabled', 'true');
            $('.selectpicker').prop('disabled', true);
            $('.swing-ticket-order-submit-btn').remove();

            // Map stored values 
            mapSwingTicketStoredValues(order_data);

            if (order_data.order_details.order_status === "Order Placed") {
                $('.order-cancel-btn').show();
            }

            $(".selectpicker").selectpicker("refresh");
            $('.top-content').html('<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;"><h4><strong>Create Order For - ' + order_data.order_details.item_master.template_name + '</strong></h4></div>');
        }); 

    }

    mapSwingTicketStoredValues = (order_data) => {
        $('#hidden_id').val(order_data.order_details.id);
        $('#hidden_name').val(order_data.order_details.item_master.template_name);
        $('#swing_primary_size_id').val(order_data.order_details.primary_size_id);
        $('#swing_template_id').val(order_data.order_details.template_id);

        $emptyQty = order_data.order_details.quantity_details.filter(quantity => quantity.ean_barcode != null || quantity.quantity != null);

        if($emptyQty.length == 0){
            $('.create-download-artwork').remove();
        } else  {
            $('.create-download-artwork').css('display','inline-block');
        }

        var div_html = '<input class="table-id-hidden" id="SwingTicket_order_id" name="swingticket_order_id" type="hidden" value="' + order_data.order_details.id + '">';

        $('.swing-ticket-order-invisible-div').html(div_html);

        $('#swing_24_char_desc').val(order_data.order_details['24_char_desc'])
        $('#swing_product_desc').val(order_data.order_details.product_desc_id)
        $('#swing_po_number').val(order_data.order_details.po_number)
        $('#swing_product_benefit').val(order_data.order_details.product_benefit_id)
        $('#swing_mm_product_benefit').val(order_data.order_details.mm_product_benefit_id)
        $('#swing_cz_price').val(order_data.order_details.cz_selling_price)
        $('#swing_sk_price').val(order_data.order_details.sk_selling_price)
        $('#swing_hu_price').val(order_data.order_details.hu_selling_price)
        $('#swing_tesco_supplier').val(order_data.order_details.tesco_metadata_supplier_id)
        $('#swing_eqos').val(order_data.order_details.eqos_site_code)
        $('#swing_season_code').val(order_data.order_details.season_code_id)
        $('#swing_option_id').val(order_data.order_details.ce_style_ref)
        $('#swing_wee_symbol').val(order_data.order_details.wee_symbol_id)
        $('#swing_woolmark_logo').val(order_data.order_details.woolmark_logo_id)
        $('#swing_style_number').val(order_data.order_details.uk_style_ref)
        $('#swing_coo').val(order_data.order_details.country_of_origin_id)

        $('#swing_ce_mark').prop('checked', order_data.order_details.is_ce_mark_required);
        $('#swing_machine_washable').prop('checked', order_data.order_details.is_machine_washable);

        $('#swing_ticket_qty_table').html('');

        renderQtyTableBody(order_data.order_details.quantity_details, order_data.moq)

    }

    renderQtyTableBody  = (quantity_details, moq) => {
        var quantity_html = `<input type="hidden" id="primary_size_id" name="primary_size_id">`;
        $.each(quantity_details, (indexInArray, valueOfElement) => {
            quantity_html += `<tr id="swing_size_${indexInArray+1}">
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.sizes.uk_size + `</td>
                                            <td >` + valueOfElement.sizes.ce_size + `</td>  <td >`;

            $.each(valueOfElement.sizes.sec_size_chart, function (index, sec_size) {

                quantity_html += `<strong class="text-justify">${sec_size.category_name}</strong> : ${sec_size.secondary_size} <br>`;

            });

            var ean_barcode = valueOfElement.ean_barcode != null?valueOfElement.ean_barcode:"";
            quantity_html += `</td> <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="EAN Barcode" id="swing_ean_` + valueOfElement.size_id + `" name="swing_ean[]" value="` + ean_barcode + `" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)" onkeyup="return validateEanBarcodeValue(this)" disabled><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback swingticket-quantity-feedback"><input type="number" class="form-control input-sm swingticket-quantity-field" placeholder="Quantity" id="swing_quantity_` + valueOfElement.size_id + `" name="swing_quantity[]" value="` + valueOfElement.quantity + `" min="`+moq+`" data-min-error="Quantity Should be >= `+moq+`." disabled><div class="help-block with-errors"></div></div></td></tr>`;
        });
        
        $('#swing_ticket_qty_table').html(quantity_html);
    }

});