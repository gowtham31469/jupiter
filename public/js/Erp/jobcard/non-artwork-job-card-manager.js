
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
            _token: csrf_token
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

            console.log(response);
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
    var checked_count = $("input[name='nat-row-checkbox']:checked").length;
    
    if (checked_count < 1) {
        $('.snackbar_span').html("Please select at least 1 order.");
        showSnackbar();
        return false;
    }

    if(checked_count > 3) {
        $('.snackbar_span').html("Please select only a maximum of 3 orders.");
        showSnackbar();
        return false;
    }
    
    var buyerArray = [];
    var customerArray = [];
    var uuid_array = [];
    var erp_id_array = [];
    $('input[name="nat-row-checkbox"]').each(function (i, item) {
        if ($(this).is(':checked')) {
            buyerArray.push($(this).data('buyer-name'));
            customerArray.push($(this).data('customer-name'));

            uuid_array.push($(this).data('uuid'));
            erp_id_array.push($(this).data('erp-id'));
        }
    });

    uuid_array   = uuid_array.join(',');
    erp_id_array   = erp_id_array.join(',');

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
        data:{uuid_array:uuid_array, erp_id_array:erp_id_array},
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
          $(".loader").show();
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function(data) {

                // renderCommonJCView(data);
                
                /** [ Backdrop for bootstrap modal ] */
                $('#natJobcardCreationModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                /** [ Validate create_jobcard form using bootstrap validator ] */
                $('#create_jobcard').validator('update');
                $('#natJobcardCreationModal').modal('show');
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy'
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
                $('.snackbar_span').html("Something went wrong");
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