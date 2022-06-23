/**
 * Method to render common view elements in jobcard creation modal.
 * @author Praveen
 */
 const renderCommonJCView = (data) => {
    $('#style_no').val('');
    $('#order_type').text('');
    $('#length').val('');
    $('#width').val('');
    $('#print_color').val('');
    $('#cus_del_date').val('');
    $('#customer_po').val('');
    $(".common-data-div").addClass("hidden");

    if (data.template_data.company) {
        $('#jc_company_name').text(data.template_data.company.company)
        $('#company_address').text(data.template_data.company.company_address)
        $('#company_logo').attr('src', data.template_data.company.company_logo);
        $('#jc_company_name').attr('data-company-id', data.template_data.company.company_id)
    }

    $('#work_order').text(data.template_data.workorder_id);

    $('#today_date').text(data.template_data.today_date);

    data.template_data.article.length > 0 ? $('#article').text(data.template_data.article) : '';

    $('#material').val(data.template_data.material)

    if (data.template_data.customer) {
        $('#customer').text(data.template_data.customer.short_name)
        $('#customer').attr('data-customer-id', data.template_data.customer.id)
    }

    if (data.template_data.buyer) {
        $('#buyer').text(data.template_data.buyer.buyer_name)
        $('#buyer').attr('data-buyer-id', data.template_data.buyer.id)

        // show tint color and customer po for orchestra jc modal
        if (data.template_data.buyer.buyer_name.toLowerCase() === 'orchestra') {
            $(".common-data-div").removeClass("hidden");

            data.template_data.tint_color.length === 1 ? $('#tint_color').val(data.template_data.tint_color) : $('#tint_color').val('');
        }
    }

    if (data.template_data.style_array.length === 1) {
        if (data.template_data.buyer.buyer_name.toLowerCase() === 'orchestra') {
            data.template_data.tint_color.length === 1 ? $('#style_no').val(data.template_data.style_array + " - " + data.template_data.color_code) : $('#style_no').val(data.template_data.style_array);
        } else {
            $('#style_no').val(data.template_data.style_array)
        }

    }

    data.template_data.refnum_array.length === 1 ? $('#reference').val(data.template_data.refnum_array) : $('#reference').val('');

    if (data.template_data.ordertype_array.length === 1) {
        switch (data.template_data.ordertype_array[0]) {
            case 'newOrder':
                $('#order_type').text('Initial');
                break;
            case 'reOrder':
                $('#order_type').text('ReOrder');
                break;
            case 'repeatOrder':
                $('#order_type').text('RepeatOrder');
                break;
            default:
                $('#order_type').text('')
                break;
        }
    }

    $('#order_date').text(data.template_data.order_date);

    if (data.template_data.length.length === 1) {
        $('#length').val(data.template_data.length);
    }

    if (data.template_data.width.length === 1) {
        $('#width').val(data.template_data.width);
    }

    if (data.template_data.color.length === 1) {
        $('#print_color').val(data.template_data.color);
    }

    if (data.template_data.cus_del_date.length === 1) {
        $('#cus_del_date').val(data.template_data.cus_del_date);
    }

    if (data.template_data.customer_po_array.length === 1) {
        $('#customer_po').text(data.template_data.customer_po_array[0]);
    }

    /** append erp_details_ids  as hidden */
    var hidden_html = '';
    $.each(data.template_data.order_detail_ids, function (index, erp_details_id) {
        hidden_html += '<div hidden class="erp-details-id">' + erp_details_id + '</div>'
    });
    $('.order-details-div').html(hidden_html);

    var disp_modes_options = '';
    $.each(data.disp_modes, function (key, item) {
        disp_modes_options += '<option value="' + key + '">' + item + '</option>';
    });
    $('#disp_mode').append(disp_modes_options);

    var machine_options = '';
    $.each(data.machines, function (key, item) {
        machine_options += '<option value="' + key + '">' + item + '</option>';
    });
    $('#machine').append(machine_options);

    var teeth_no_options = '';
    $.each(data.teeth_nos, function (key, item) {
        teeth_no_options += '<option value="' + key + '">' + item + '</option>';
    });
    $('#teeth_no').append(teeth_no_options);

    var finish_type_options = '';
    $.each(data.finish_types, function (key, item) {
        finish_type_options += '<option value="' + key + '">' + item + '</option>';
    });
    $('#finish_type').append(finish_type_options);
    $('.selectpicker').selectpicker('refresh');
}