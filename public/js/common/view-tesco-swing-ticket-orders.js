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

        $emptyQty = order_data.order_details.quantities.filter(quantity => quantity.ean_barcode === null || quantity.quantity === null);

        if($emptyQty.length > 0){
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

        $.each(order_data.order_details.quantities, (indexInArray, valueOfElement) => { 
            $(`#swing_ean_${valueOfElement.size_id}`).val(valueOfElement.ean_barcode);
            $(`#swing_quantity_${valueOfElement.size_id}`).val(valueOfElement.quantity);
        });

    }

});