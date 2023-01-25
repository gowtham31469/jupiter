/**
 * 
 * To reset filter state.
 */
 const filterDefaultLevel = () => {
    $(`#nat_customer, 
        #nat_item_name,
        #nat_reference, 
        #nat_style_number, 
        #nat_ocn_number, 
        #nat_buyer_po_number`)
            .prop('selectedIndex', -1)
            .prop("disabled", true)
            .prop("required", false)
            .parent().parent().removeClass('has-error');

    $(`#nat_buyer,
        #nat_customer,
        #nat_item_name,
        #nat_reference`).parent().next('.help-block').html('');

    $('#nat_jobcard_filter_form').validator('update');

    $(`#nat_buyer`).prop("required", true);

}

/**
 * Method to disable level 2 filters (buyer,customer,item name, reference, style, ocn and buyer po)
 * when level 1 filter values are selected.
 */
const disableLevelTwoFilters = () => {
    
    filterDefaultLevel();

    $(`#nat_buyer`)
    .prop('selectedIndex', -1)
    .prop("disabled", true)
    .prop("required", false)
    .parent().parent().removeClass('has-error');

    if ($('#nat_customer_po').val().length <= 0 && $('#nat_erp_id').val().length <= 0) {
        $(`#nat_buyer`)
        .prop("required", true)
        .prop("disabled", false);
    }

    $('.selectpicker').selectpicker('refresh');

}

/**
 * 
 * To clear all selected filter values.
 */
 const clearNATJobcardFilter = () => {

    filterDefaultLevel();

    $(`#nat_buyer,
       #nat_erp_id,
       #nat_customer_po`)
    .prop('selectedIndex', -1)
    .prop("disabled", false)
    .parent().parent().removeClass('has-error');

    $('.selectpicker').selectpicker('refresh');
    $('#nat_jobcard_filter_form').validator('update');
}

/**
 * 
 * To fetch non artwork jobcard multi-level filter values by buyer id
 */
const getNATCustomers = () => {
    let buyer_selected = $('#nat_buyer').val();

    filterDefaultLevel();

    $('.selectpicker').selectpicker('refresh');

    if ($('#nat_buyer').val().length <= 0) {
        disableLevelTwoFilters();
        return false;
    }

    $.ajax({
        type: "post",
        url: base_url + "/erp/non-artwork-job-manager/get-customers-filter",
        data: {
            'buyer_id': buyer_selected,
            _token: csrf_token,
            'jobcard_created' : false
        },
        dataType: "json",
        beforeSend:  () => {
            $(".loader").show();
        },
        success: (response) => {
            $("#nat_customer").empty();
            $(response.customers).each( (index, value) => {
                $("#nat_customer")
                    .append($("<option></option>")
                    .attr("value", value.id)
                    .text(value.company_name)) 
                    .prop("required", true)
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
        },
        complete:() => {
            $(".loader").hide();
        }
    });
}

/**
 * 
 * To fetch non artwork jobcard multi-level filter values by buyer id, customer id
 */
const getNATItemNames = () => {
    let customer_selected = $('#nat_customer').val();
    let buyer_selected = $('#nat_buyer').val();

    $(`#nat_item_name,
    #nat_reference, 
    #nat_style_number, 
    #nat_ocn_number, 
    #nat_buyer_po_number`)
        .empty()
        .prop("disabled", true);

    $('.selectpicker').selectpicker('refresh');

    if ($('#nat_customer').val().length <= 0) {

        return false;
    }

    $.ajax({
        type: "post",
        url: base_url + "/erp/non-artwork-job-manager/get-item-names-filter",
        data: {
            'customer_id': customer_selected,
            'buyer_id'    : buyer_selected,
            _token: csrf_token
        },
        dataType: "json",
        beforeSend:  () => {
            $(".loader").show();
        },
        success: (response) => {
            $("#nat_item_name").empty();
            $(response.item_names).each((index, value) => {
                $("#nat_item_name")
                    .append($("<option></option>")
                    .attr("value", value.id)
                    .text(value.name)) 
                    .prop("required", true)
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
        },
        complete:() => {
            $(".loader").hide();
        }
    });
}

/**
 * 
 * To fetch non artwork jobcard multi-level filter values by buyer id, customer id & item names
 */
const getNATReferences = () => {
    let item_name_selected = $('#nat_item_name').val();
    let customer_selected = $('#nat_customer').val();
    let buyer_selected = $('#nat_buyer').val();

    $(`#nat_reference, 
    #nat_style_number, 
    #nat_ocn_number, 
    #nat_buyer_po_number`)
        .empty()
        .prop("disabled", true);

    $('.selectpicker').selectpicker('refresh');

    if ($('#nat_item_name').val().length <= 0) {

        return false;
    }

    $.ajax({
        type: "post",
        url: base_url + "/erp/non-artwork-job-manager/get-references-filter",
        data: {
            'item_name_id': item_name_selected,
            'customer_id': customer_selected,
            'buyer_id'    : buyer_selected,
            _token: csrf_token
        },
        dataType: "json",
        beforeSend:  () => {
            $(".loader").show();
        },
        success: (response) => {
            $("#nat_reference").empty();

            $(response.references).each((index, value) => {
                $("#nat_reference")
                    .append($("<option></option>")
                    .attr("value", value.reference_name)
                    .text(value.reference_name)) 
                    .prop("required", true)
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
        },
        complete:() => {
            $(".loader").hide();
        }
    });
}


/**
 * 
 * To fetch non artwork jobcard multi-level filter values by buyer id, customer id, item names and references
 */
const getNATStyleOCNBuyerPO = () => {
    let item_name_selected = $('#nat_item_name').val();
    let customer_selected = $('#nat_customer').val();
    let buyer_selected = $('#nat_buyer').val();
    let reference_selected = $('#nat_reference').val();

    $(`#nat_style_number, 
    #nat_ocn_number, 
    #nat_buyer_po_number`)
        .empty()
        .prop("disabled", true);

    $('.selectpicker').selectpicker('refresh');

    if ($('#nat_item_name').val().length <= 0) {

        return false;
    }

    $.ajax({
        type: "post",
        url: base_url + "/erp/non-artwork-job-manager/get-style-ocn-po-filter",
        data: {
            'item_name_id': item_name_selected,
            'customer_id': customer_selected,
            'buyer_id'    : buyer_selected,
            'reference_name' : reference_selected,
            _token: csrf_token
        },
        dataType: "json",
        beforeSend:  () => {
            $(".loader").show();
        },
        success: (response) => {

            $("#nat_style_number").empty();
            $("#nat_buyer_po_number").empty();
            $("#nat_ocn_number").empty();

            $(response.style_no).each((index, value) => {
                $("#nat_style_number")
                    .append($("<option></option>")
                    .attr("value", value.style_no)
                    .text(value.style_no)) 
                    .prop("disabled", false);
            });

            $(response.buyer_po_number).each((index, value) => {
                $("#nat_buyer_po_number")
                    .append($("<option></option>")
                    .attr("value", value.buyer_po_number)
                    .text(value.buyer_po_number)) 
                    .prop("disabled", false);
            });
            
            $(response.ocn_numbers).each((index, value) => {
                $("#nat_ocn_number")
                    .append($("<option></option>")
                    .attr("value", value.ocn_number)
                    .text(value.ocn_number)) 
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
        },
        complete:() => {
            $(".loader").hide();
        }
    });
}


/** Create job card form validation **/
$('#nat_jobcard_filter_form').validator().on('submit', (e) => {
    if (e.isDefaultPrevented())
    {

    }
    else
    {
        e.preventDefault();
        getNatJobcardFiltredData();
    }
});

const getNatJobcardFiltredData = () => {
   
    nat_datatable  = $('#non-artwork-jobcard-table').DataTable({
        bDestroy:true,
        processing: true,
        serverSide: true,
        pageLength:25,
        ajax: {
            url: base_url + "/erp/non-artwork-job-manager/get-nat-filtered-orders",
            type: 'post',
            data: {
                'erp_id'    : $('#nat_erp_id').val(),
                'customer_po'  : $('#nat_customer_po').val(),
                'buyer_id'    : $('#nat_buyer').val(),
                'customer_id': $('#nat_customer').val(),
                'item_name_id': $('#nat_item_name').val(),
                'reference_name' : $('#nat_reference').val(),
                'style_number' : $('#nat_style_number').val(),
                'ocn_number' : $('#nat_ocn_number').val(),
                'buyer_po_number' : $('#nat_buyer_po_number').val(),
                _token: csrf_token
            },
        },
        columns: [
            { data: 'Sl_No', name: 'Sl_No'},
            {data: 'erp_id', name: 'erp_id'},
            {data: 'customer', name: 'customer'},
            {data: 'buyer', name: 'buyer'},
            {data: 'customer_po', name: 'customer_po'},
            {data: 'buyer_po_number', name: 'buyer_po_number'},
            {data: 'style_no', name: 'style_no'},
            {data: 'reference_name', name: 'reference_name'},
            {data: 'ocn_number', name: 'ocn_number'},
            {data: 'item_name', name: 'item_name'},
            {data: 'color', name: 'color'},
            {data: 'composition', name: 'composition'},
            {data: 'quantity', name: 'quantity'}
        ]
    });

    /** [ Data Tabeles scripts ] */
    $('#non-artwork-jobcard-table').DataTable();
    $("#non-artwork-jobcard-table_filter input").addClass('form-control input-sm');
    $("#non-artwork-jobcard-table_filter input").attr('placeholder', 'Data Search');
    $('#non-artwork-jobcard-table_length select').addClass('form-control input-sm');

    return nat_datatable;

}

function selectNatOrders(event)
{
    /** Table checkbox click functionality	**/
    let checkbox = $("input[class='row-checkbox']");
    let customer_name = $(event).data('customer-name');
    let buyer_name = $(event).data('buyer-name');
    let ischecked= $(event).is(':checked');
    let length= $(checkbox).filter(':checked').length;

    if(ischecked)
    {
        /** change table row background color	**/
        $(event).closest('tr').addClass("highlight_row");
        /** Make checkboxes disabled(having different customer and buyer names) **/
        $(checkbox).not("[data-customer-name='"+customer_name+"']").attr("disabled", "true");
        $(checkbox).not("[data-buyer-name='"+buyer_name+"']").attr("disabled", "true");
    }
    else
    {
        /** change table row background color	**/
        $(event).closest('tr').removeClass("highlight_row");
        /** Make checkboxes enabled iff all checkboxes unchecked **/
        if(length === 0)
         $(checkbox).not("[data-customer-name='"+customer_name+"']").removeAttr("disabled");
         $(checkbox).not("[data-buyer-name='"+buyer_name+"']").removeAttr("disabled");
    }
}


const generateNatJobcard = () => {
    var checked_count = $("input[class='row-checkbox']:checked").length;

    if ( checked_count < 1 ) {
        $('.snackbar_span').html("Please select at least 1 order.");
        showSnackbar();
        return false;
    }

    var buyerArray = [];
    var customerArray = [];
    var uuid_array = [];
    var erp_id_array = [];
    $('input[class="row-checkbox"]').each(function (i, item) {
        if ($(this).is(':checked')) {
            buyerArray.push($(this).data('buyer-name'));
            customerArray.push($(this).data('customer-name'));

            uuid_array.push($(this).data('uuid'));
            erp_id_array.push($(this).data('erp-id'));
        }
    });

    uuid_array   = uuid_array.join(',');
    erp_id_array   = erp_id_array.join(',');
    
    if (
        $(".buyers-card.active-buyer.active").attr("id") ===
        "jupitergarmentsaccessories"
      ) {
        getNatJGAOrderDetails(checked_count, buyerArray, customerArray, uuid_array, erp_id_array);
      } else {
        getSpectrraOrderDetails(checked_count, erp_id_array, uuid_array);
      }
    
}

const getNatJGAOrderDetails = (checked_count, buyerArray, customerArray, uuid_array, erp_id_array) => {

    if(checked_count > 3) {
        $('.snackbar_span').html("Please select only a maximum of 3 orders.");
        showSnackbar();
        return false;
    }

    if([...new Set(buyerArray)].length != 1) {
        $('.snackbar_span').html("Please select the same buyer orders.");
        showSnackbar();
        return false;
    }

    if([...new Set(customerArray)].length != 1) {
        $('.snackbar_span').html("Please select the same customer orders.");
        showSnackbar();
        return false;
    }

    $.ajax({
        url:base_url + "/erp/non-artwork-job-manager/get-nat-jc-details",
        dataType:"json",
        method:"get",
        data:{uuid_array, erp_id_array},
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
          $(".loader").show();
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function(data) {

                renderCommonJCView(data);

                $('#teeth_no').prop("disabled", true).selectpicker('refresh');
                $('#nat_template_type').val(data.template_data.template_type[0]);
                $('.nat-jobcard-modal-title').text(data.modal_title);
                $('#crud_operation_type').text('created_at');
                $('#material_hierarchy').text(data.template_data.material_hierarchy)

                /** Show single templates dynamic table */
                var single_table_html = '';
                var meters_div_html = '';
                if (data.template_data.single.length != 0) {
                    var row = 0;
                    var table = 0;
                    $.each(data.template_data.single, function (key, erpid) {
                        single_table_html += '<table class="table table-bordered table-responsive single-list-table"  style="font-size: 12px;" id="single_list_table_' + table + '"  border="0.2">\
                        <tbody id="single_list_table_body"><tr>\
                        <th width="80px">ID.NO</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            single_table_html += '<td colspan="2" data-template-id="' + common_data.template_id + '" class="single-erpid-' + index + '">' + common_data.erp_id + '</td>';
                        });
                        var template_name = key.split("_");
                        single_table_html += '<td colspan="4" class="highlight_row" rowspan="6" data-single-ref-name ="' + template_name[0] + '"></td></tr>\
                                                    <tr>\
                                                        <th width="80px">StyleNo/UVC</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            let style_num = (common_data.style_no === null || "") ? '-' : common_data.style_no;
                            single_table_html += '<td colspan="2" class="single-style-no-' + index + '">' + style_num + '</td>';
                        });

                        single_table_html += '</tr>\
                                                    <tr>\
                                                        <th width="80px">B.PO.NO</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            let buyer_po_no = (common_data.buyer_po_number === null || "") ? '-' : common_data.buyer_po_number;
                            single_table_html += '<td colspan="2" class="single-buyer-po-' + index + '">' + buyer_po_no + '</td>';
                        });
                        single_table_html += '</tr>\
                                                    <tr>\
                                                        <th width="80px">OCN</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            let ocn_no = (common_data.ocn_number === null || "") ? '-' : common_data.ocn_number;
                            single_table_html += '<td colspan="2" class="single-ocn-' + index + '">' + ocn_no + '</td>';
                        });

                        single_table_html += '</tr>\
                        <tr>\
                                                        <th width="80px">Color</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            let color = (common_data.color === null || "") ? '-' : common_data.color;
                            single_table_html += '<td colspan="2" class="single-color-' + index + '">' + color + '</td>';
                        });

                        single_table_html += '</tr>\
                        <tr>\
                                                        <th width="80px">Composition</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            let composition = (common_data.composition === null || "") ? '-' : common_data.composition;
                            single_table_html += '<td colspan="2" class="single-composition-' + index + '">' + composition + '</td>';
                        });

                        single_table_html += '</tr>\
                                                <th width="80px">SIZE /PRICE</th>';
                        $.each(erpid.order_data, function (index, common_data) {
                            single_table_html += '<th>O.QNT</th>\
                            <th>P.QNT</th>';
                        });
                        single_table_html += '<th>TOTAL</th>\
                                                        <th width="180px">EXCESS</th>\
                                                        <th>MTRS</th>\
                                                        <th>STOCK</th>\
                                                    </tr>';
                        var total_quantity_final = 0;
                        var sum = [];
                        $.each(erpid.size, function (index2, quantity_data) {
                            var total_qty_sizewise = 0;
                            var size_text = index2.split("_");
                            var col = 0;
                            single_table_html += '<tr><td class="single-size-row">' + size_text[0] + '</td>';

                            $.each(quantity_data, function (index3, qty) {
                                total_qty_sizewise += parseInt(qty);
                                single_table_html += '<td class="quantity-' + col + ' quantity-td" data-size-value="' + size_text[0] + '">' + parseInt(qty) + '</td><td></td>';
                                /** Column wise total quantity calculation */
                                sum[col] = parseInt(qty) + (sum[col] || 0);
                                col++;
                            });

                            single_table_html += '<td class="total-quantity-class total-quantity-row-' + row + '"><strong>' + parseInt(total_qty_sizewise) + '</strong></td><td class="total-excess-td"><div class="form-group has-feedback"><input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm total-excess-row-table-' + table + '" id="total_excess_row_table_' + row + '" data-required-error="Mandatory Field." name="total_excess_row_table_' + row + '"" placeholder="Excess %" onkeyup="return totalQtyExcess()"><div class="help-block with-errors"></div></div></td><td></td><td></td></tr>';

                            total_quantity_final += parseInt(total_qty_sizewise);
                            row++;
                        });

                        single_table_html += '<tr>\
                                                <th class="highlight_row total-text" data-ref-name = "' + template_name[0].replace(/ |\//g, '_') + '">TOTAL</th>';
                        var total_col = 0;
                        $.each(erpid.order_data, function (index, common_data) {
                            single_table_html += '<td class="total-quantity-col-' + total_col + '"><strong>' + sum[total_col] + '</strong></td>\
                                                    <td></td>';
                            total_col++;
                        });

                        single_table_html += '<td><strong class="total-qty">' + parseInt(total_quantity_final) + '</strong></td>\
                                                <td class="total-excess-col-table-' + table + ' total-excess"></td>\
                                                <td></td>\
                                                <td></td>\
                                            </tr>';
                        single_table_html += ' </tbody>\
                                        </table>';
                        table++;
                        /** Show actual excess % and meters div only once for each references */
                        if (template_name[1] === '0') {
                            meters_div_html += '<div class="col-md-12">\
                                        <div class="col-md-3">\
                                            <label for="act_excess" >ACT EXCESS % :</label>\
                                            <span class="act-excess" name="act_excess" data-act-excess-ref="' + template_name[0] + '" id="act_excess"></span>\
                                        </div>\
                                        <div class="col-md-3">\
                                            <label for="sticker_mtr" >MTRS FOR STKRS :</label>\
                                            <span class="sticker-mtrs" name="sticker_mtr" id="sticker_mtr"></span>\
                                        </div>\
                                        <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="settings" >SETTINGS  :</label>\
                                            <input type="number" min="0" data-step-error="Decimal values not allowed." data-min-error="Value must be >= 0" class="form-control input-sm settings"  name="settings" placeholder="SETTINGS" id="settings" max="99999"  data-max-error="Value should be <= 99999">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        </div>\
                                    </div>';
                        }
                    });

                    $('.single-list-table-div').html(single_table_html);
                    $('.meter-calculation-div').html(meters_div_html);
                } else {
                    $('.single-list-table-div').html(single_table_html);
                    $('.meter-calculation-div').html(meters_div_html);
                }
                
                /** [ Backdrop for bootstrap modal ] */
                $('#natJobcardCreationModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                /** [ Validate create_jobcard form using bootstrap validator ] */
                $('#create_nat_jobcard').validator('update');
                $('#natJobcardCreationModal').modal('show');
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy'
                });

                $('#aw_app_date').datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: new Date()
                });

                // enable print button (for old version browsers)
                $('#jc_print_btn').prop('disabled', false);
            },
            406: function(data) {
                 $('.snackbar_span').html(data.responseJSON.message);
                 showSnackbar();
            },
            404: function(data) {
                $('.snackbar_span').html(data.responseJSON.message);
                showSnackbar();
           },
            400: function() {
                $('.snackbar_span').html(data.responseJSON.message);
                showSnackbar();
            }
       },
        error: function(errorXHR, errorStatus, errorThrown) {
            console.log(errorThrown);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

const getMachineExcessData = () => {
    return $.ajax({
        type: "get",
        url: base_url + "/erp/non-artwork-job-manager/get-machine-excess-data",
        data: {
            machine: $('#machine option:selected').val()
        },
        dataType: "json",
    })
}

const getMachineExcess = () => {
    var order_qty = 0;
    $('#excess').text('');

    getMachineExcessData().done((response) => {
        $('.single-list-table').find('.total-quantity-class strong').each( (index, row) => {
            order_qty = row.innerHTML;
            $(row).parent().next().find(`div input`).val('');

            $.each(response,  (indexInArray, valueOfElement) => { 
                 if ( order_qty >= valueOfElement.min_range && order_qty <= valueOfElement.max_range ) {
                    switch (valueOfElement.excess_unit) {
                        case 'percentage':
                            let total_excess = parseFloat(order_qty * (valueOfElement.excess*1/100)).toFixed(2);
                            excess_percentage = Math.round(total_excess);
                            break;
                        case 'pieces':
                            excess_percentage = valueOfElement.excess;
                            break;
                    
                        default:
                            break;
                    }

                    $(row).parent().next().find(`div input`).val(excess_percentage);

                    /** refresh excess values fields in single ref table */
                    $('#total_excess_row_table_'+index).trigger('input');
                  }
            });
        });
        totalQtyExcess();
    });
} 

/**
 * Dynamic Total Excess calculation based on calculated excess value.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
 const totalQtyExcess = () => {
     var table = 0;
     $('.single-list-table').each(function () {
         var total_excess_final = 0;
         $(this).find('.total-excess-row-table-'+table).each(function () {
             var excess = $(this).val();
             if (!isNaN(excess) && excess.length !== 0) {
                 total_excess_final += parseInt(excess);
             }
         });
         $('.total-excess-col-table-'+table).html('<strong>'+Math.round(total_excess_final)+'</strong>');
 
         table++;
     });
     actualQtyExcess()
 }
 
 /**
  * Dynamic Actual Excess calculation based on excess value and total quantity.(for JGA company)
  * @author <Praveen> [praveen@wizroots.com]
  * 
  */
 const actualQtyExcess = () => {
    var total_qty      = parseInt($('.total-qty').text()) || 0;
    var total_excess   = parseInt($('.total-excess').text()) || 0;
    var act_excess     = 0;

    if (!isNaN(total_qty) && !isNaN(total_excess)) {
        act_excess = parseFloat((total_excess/total_qty)*100).toFixed(2);
    }

    $('#act_excess').text(Math.round(act_excess))
    $('#excess').text(Math.round(act_excess));

     mtrsForNatStickers();
 }

const mtrsForNatStickers = () => {
    let total_qty             = parseInt($('.total-qty').text()) || 0;
    let total_excess          = parseInt($('.total-excess').text()) || 0;
    let mtrs                  = 0;
    var machine_text          = $( '#machine option:selected' ).text() === ''?null:$( '#machine option:selected' ).text();
    var length_side_dimension = $('#length_side').val() === ''?0:$('#length_side').val();
    let no_of_repeats         = $('#no_repeats').val() === ''?0:$('#no_repeats').val();
    let repeat_gap            = $('#repeat_gap').val() === ''?0:$('#repeat_gap').val();

    switch (machine_text.toLowerCase().trim()) {
        case 'rotary':
                $('#teeth_no').prop("disabled", false).selectpicker('refresh');
                mtrs = machineExcessCalculation(total_qty, length_side_dimension, 1, 0, total_excess);
            break;
        case 'flexo':
                $('#teeth_no').prop("disabled", false).selectpicker('refresh');
                mtrs = machineExcessCalculation(total_qty, length_side_dimension, no_of_repeats, repeat_gap, total_excess);
            break;
        case 'flat bed':
                $('#teeth_no').prop("disabled", true).selectpicker('refresh');
                mtrs = machineExcessCalculation(total_qty, length_side_dimension, no_of_repeats, repeat_gap, total_excess);
            break;
        default:
                $('#teeth_no').prop("disabled", true).selectpicker('refresh');
                mtrs = machineExcessCalculation(0, 0, 1, 0, 0);
            break;
    }

    $('#sticker_mtr').text(Math.round(mtrs));
}

const machineExcessCalculation = (total_qty, length_side_dimension, no_of_repeats, repeat_gap, total_excess) => {
    var mtrs  = 0;
    if(!isNaN(total_qty) && !isNaN(length_side_dimension) && !isNaN(no_of_repeats) && !isNaN(repeat_gap) && no_of_repeats > 0 && repeat_gap >= 0)
    {
        mtrs = parseFloat( ( ( ( parseInt(total_qty) + parseInt(total_excess) ) / parseInt(no_of_repeats) ) * ( ( parseFloat(length_side_dimension) + parseInt(repeat_gap) ) ) ) / 1000 ).toFixed(2);
    }
    return mtrs;
}

const validateNatJCForm = () => {
    $('#create_nat_jobcard').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
            e.preventDefault();
            storeNatJobcard();
        }
    })
}

const storeNatJobcard = () => {

    var company_id      = $( '#jc_company_name' ).data('company-id');
    var workorder_id    = $( '#work_order' ).text() === ''?null:$('#work_order').text();
    var created_date    = $( '#today_date' ).text() === ''?null:$('#today_date').text();
    var article         = $( '#article' ).text() === ''?null:$('#article').text();
    var material        = $( '#material' ).val() === ''?null:$('#material').val();
    var customer_id     = $( '#customer' ).data('customer-id');
    var order_type      = $( '#order_type' ).text() === ''?null:$('#order_type').text();
    var buyer_id        = $( '#buyer' ).data('buyer-id');
    var buyer           = $( '#buyer' ).text();
    var reference       = $( '#reference' ).val() === ''?null:$('#reference').val();
    var disp_mode       = $( '#disp_mode option:selected' ).val() === ''?null:$('#disp_mode option:selected').val();
    var disp_mode_text  = $( '#disp_mode option:selected' ).text() === ''?null:$('#disp_mode option:selected').text();
    var order_date      = $( '#order_date' ).text() === ''?null:$('#order_date').text();
    var length          = $( '#length' ).val() === ''?null:$('#length').val();
    var repeat_gap      = $( '#repeat_gap' ).val() === ''?null:$( '#repeat_gap' ).val();
    var machine         = $( '#machine option:selected' ).val() === ''?null:$( '#machine option:selected' ).val();
    var machine_text    = $( '#machine option:selected' ).text() === ''?null:$( '#machine option:selected' ).text();
    var cus_del_date    = $( '#cus_del_date' ).val() === ''?null:$( '#cus_del_date' ).val();
    var roll_width      = $( '#roll_width' ).val() === ''?null:$( '#roll_width' ).val();
    var width           = $( '#width' ).val() === ''?null:$('#width').val();
    var film_no         = $( '#film_no' ).val() === ''?null:$( '#film_no' ).val();
    var invoice_date    = $( '#invoice_date' ).val() === ''?null:$( '#invoice_date' ).val();
    var length_side     = $( '#length_side' ).val() === ''?null:$( '#length_side' ).val();
    var excess          = $( '#excess' ).text() === ''?0:$( '#excess' ).text();
    var teeth_no        = $( '#teeth_no option:selected' ).val() === ''?null:$( '#teeth_no option:selected' ).val();
    var teeth_no_text   = $( '#teeth_no option:selected' ).text() === ''?null:$( '#teeth_no option:selected' ).text();
    var inv_no          = $( '#inv_no' ).val() === ''?null:$( '#inv_no' ).val();
    var finish_type     = $( '#finish_type option:selected' ).val() === ''?null:$( '#finish_type option:selected' ).val();
    var finish_type_text= $( '#finish_type option:selected' ).text() === ''?null:$( '#finish_type option:selected' ).text();
    var print_color     = $( '#print_color' ).val() === ''?null:$('#print_color').val();
    var compiled_by     = $( '#compiled_by' ).val() === ''?null:$('#compiled_by').val();
    var barcode_read    = $( '#barcode_read' ).val() === ''?null:$('#barcode_read').val();
    var operator        = $( '#operator' ).val() === ''?null:$('#operator').val();
    var checker         = $( '#checker' ).val() === ''?null:$('#checker').val();
    var authorized_by   = $( '#author' ).val() === ''?null:$('#author').val();
    var axicon_verifier = $( '#axicon_verifier' ).val() === ''?null:$('#axicon_verifier').val();
    var approved_by     = $( '#approved_by' ).val() === ''?null:$('#approved_by').val();
    var packer          = $( '#packer' ).val() === ''?null:$('#packer').val();
    var approval_date   = $( '#approval_date' ).val() === ''?null:$('#approval_date').val();
    var roll_issued_by  = $( '#roll_issued_by' ).val() === ''?null:$('#roll_issued_by').val();
    var cutter          = $( '#cutter' ).val() === ''?null:$('#cutter').val();
    var cartons         = $( '#cartons' ).val() === ''?null:$('#cartons').val();
    var no_repeats      = $( '#no_repeats' ).val() === ''?null:$( '#no_repeats' ).val();
    var act_del_date    = $( '#act_del_date' ).val() === ''?null:$( '#act_del_date' ).val();
    var aw_app_date     = $( '#aw_app_date' ).val() === ''?null:$( '#aw_app_date' ).val();
    var act_excess      = $('#act_excess').text() === ''?0:$('#act_excess').text();
    var sticker_mtr     = $('#sticker_mtr').text() === ''?0:$('#sticker_mtr').text();
    var settings        = $('#settings').val() === ''?0:$('#settings').val();
    var crud_type      = $('#crud_operation_type').text() === ''?'created_at':$('#crud_operation_type').text();
    var material_hierarchy = $('#material_hierarchy').text() === ''?null:$('#material_hierarchy').text();
    var single_ref      = $('#act_excess').data('act-excess-ref') === ''?'non-artwork':$('#act_excess').data('act-excess-ref');

    var final_json_obj =   {
        "single": {},
        "erp_order_details" : []
          }; 
    
    var single_ref_obj = {};
    
    /** Create single templates json object */
    $('.single-list-table').each(function (index, table) {
        var single_obj = {
            "erp_id": [],
            "style": [],
            "buyer_po": [],
            "ocn": [],
            "color": [],
            "compositions": [],
            "temp_id": [],
            "size": [],
            "total": [],
            "excess": []
              };
        var erp_id_object  = {};
        var erp_id_array   = [];
        var style_no_object  = {};
        var style_no_array   = [];
        var po_object      = {};
        var po_array       = [];
        var ocn_object     = {};
        var ocn_array      = [];
        var color_object     = {};
        var color_array      = [];
        var composition_object  = {};
        var composition_array   = [];
        var temp_id_object = {};
        var temp_id_array  = [];
        single_ref_name    = $(table).find('tbody tr:eq(0) td:last-child').data('single-ref-name').replace(/ |\//g, '_');
        
        // create erp_id array
        $(table).find('tbody tr:eq(0) td:not(:last)').each(function (i, item) {
            erp_id_object = {};
            erp_id_object = item.innerHTML;
            erp_id_array.push(erp_id_object);

            temp_id_object = {};
            temp_id_object = $(item).data('template-id');
            temp_id_array.push(temp_id_object);
        });
        
        single_obj['erp_id']  = erp_id_array;
        single_obj['temp_id'] = temp_id_array;

        // create style number array
        $(table).find('tbody tr:eq(1) td').each(function (i, item) {
            style_no_object = {};
            style_no_object = item.innerHTML;
            style_no_array.push(style_no_object);
        });
        single_obj['style'] = style_no_array;

        // create po number array
        $(table).find('tbody tr:eq(2) td').each(function (i, item) {
            po_object = {};
            po_object = item.innerHTML;
            po_array.push(po_object);
        });
        single_obj['buyer_po'] = po_array;

        // create ocn number array
        $(table).find('tbody tr:eq(3) td').each(function (i, item) {
            ocn_object = {};
            ocn_object = item.innerHTML;
            ocn_array.push(ocn_object);
        });
        single_obj['ocn'] = ocn_array;

        // create color array
        $(table).find('tbody tr:eq(4) td').each(function (i, item) {
            color_object = {};
            color_object = item.innerHTML;
            color_array.push(color_object);
        });
        single_obj['color'] = color_array;

        // create composition array
        $(table).find('tbody tr:eq(5) td').each(function (i, item) {
            composition_object = {};
            composition_object = item.innerHTML;
            composition_array.push(composition_object);
        });
        single_obj['compositions'] = composition_array;
        
        var size_object_final = {};
        var total_qty_object_final = {};
        var excess_object_final = {};
        // create size, qty and excess json object
        $(table).find('tbody tr:gt(6):not(:last-child)').each(function (i, item) {
            
            var size_text  = $(item).find('.single-size-row').text();
            var size_array      = [];
            $(item).find('.quantity-td').each(function (index, row) {
                size_object = {};
                size_object = row.innerHTML;
                size_array.push(size_object);
            });
            size_object_final[size_text] = size_array;

            $(item).find('.total-quantity-class strong').each(function (index1, total_qty) {
                total_qty_object = {};
                total_qty_object = total_qty.innerHTML;
            });
            total_qty_object_final[size_text] = total_qty_object;

            $(item).find('.total-excess-td div input').each(function (index2, excess) {
                
                excess_object = {};
                excess_object = excess.value != ''?excess.value:0;
            });
           
            excess_object_final[size_text] = excess_object;

        });
        single_obj['size'] = size_object_final;
        single_obj['total'] = total_qty_object_final;
        single_obj['excess'] = excess_object_final;
        
        single_ref_obj[single_ref_name+'_'+index] = single_obj;
        // pushing single templates array into json object
        final_json_obj['single'] = single_ref_obj;
    });

    erp_orders_ids = [];
    uuid_array = [];
    jsonArray = [];
    /** [ Create json object of selected orders ] */
    $('input[class="row-checkbox"]').each(function (i, item) {
        erp_order_details_obj = {};
        
        if ($(this).is(':checked')) {
            erp_order_details_obj.buyer_po = $(this).data('buyer-po');
            erp_order_details_obj.refname = $(this).data('refname');
            erp_order_details_obj.erp_id = $(this).data('erp-id');
            erp_order_details_obj.uuid = $(this).data('uuid');

            jsonArray.push(erp_order_details_obj);

            erp_orders_ids.push($(this).data('erp-id'));  
            uuid_array.push($(this).data('uuid')); 

        }
    });

    final_json_obj['erp_order_details'] = jsonArray;
    erp_orders_ids   = erp_orders_ids.join(',');
    uuid_array   = uuid_array.join(',');

    $.ajax({
        url: base_url + "/erp/non-artwork-job-manager/store-nat-jobcard-details",
        type: 'POST',
        data: {
            _token: csrf_token,
            company_id: company_id,
            workorder_id: workorder_id,
            created_date: created_date,
            article: article,
            material: material,
            customer_id: customer_id,
            order_type: order_type,
            buyer_id: buyer_id,
            buyer: buyer,
            reference: reference,
            disp_mode: disp_mode,
            order_date: order_date,
            length: length,
            repeat_gap: repeat_gap,
            machine: machine,
            cus_del_date: cus_del_date,
            roll_width: roll_width,
            width: width,
            film_no: film_no,
            invoice_date: invoice_date,
            length_side: length_side,
            excess: excess,
            teeth_no: teeth_no,
            inv_no: inv_no,
            finish_type: finish_type,
            print_color:print_color,
            compiled_by:compiled_by,
            barcode_read:barcode_read,
            operator:operator,
            checker:checker,
            authorized_by:authorized_by,
            axicon_verifier:axicon_verifier,
            approved_by:approved_by,
            packer:packer,
            approval_date:approval_date,
            roll_issued_by:roll_issued_by,
            cutter:cutter,
            cartons:cartons,
            final_json_obj:JSON.stringify(final_json_obj),
            act_excess:act_excess,
            sticker_mtr:sticker_mtr,
            settings:settings,
            erp_orders_ids:erp_orders_ids,
            uuid_array:uuid_array,
            no_repeats:no_repeats,
            act_del_date:act_del_date,
            aw_app_date:aw_app_date,
            single_ref:single_ref,
            disp_mode_text:disp_mode_text,
            machine_text:machine_text,
            teeth_no_text:teeth_no_text,
            finish_type_text:finish_type_text,
            crud_operation_type:crud_type,
            material_hierarchy:material_hierarchy
        },
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
            $('#jc_print_btn').prop('disabled', true);
        },
        statusCode: {
            200: function(data) {

                var resp = JSON.stringify(data.file_path); 
                window.open(
                    base_url+JSON.parse(resp),
                    '_blank' // <- This is what makes it open in a new window.
                ); 
                $('.snackbar_span').html("Jobcard Created Successfully");
                showSnackbar();
                $('.modal').modal('hide');
                location.reload();
              
            },
            400: function() {
                $('.snackbar_span').html("Parameter is missing !!!");
                showSnackbar();
            },
            422: function (errorXHR) {
                var error_msg = ``;
                $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                    error_msg += `<li>` + valueOfElement + `</li>`;
                });
                $('#nat_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
                $('#jc_print_btn').prop('disabled', false);
                $(window).scrollTop(0);
            }
        },
        error: function (errorXHR, errorStatus, errorThrown) {
            console.log(errorThrown);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
}