
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
            .empty()
            .prop("disabled", true);
}

/**
 * Method to disable level 2 filters (buyer,customer,item name, reference, style, ocn and buyer po)
 * when level 1 filter values are selected.
 */
const disableLevelTwoFilters = () => {
    
    filterDefaultLevel();

    $(`#nat_buyer`)
    .prop('selectedIndex', -1)
    .prop("disabled", true);

    if ($('#nat_customer_po').val().length <= 0 && $('#nat_erp_id').val().length <= 0) {
        $(`#nat_buyer`)
        .prop("disabled", false);
    }

    $('.selectpicker').selectpicker('refresh');

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
        success: function (response) {
            $("#nat_customer").empty();
            $(response.customers).each(function (index, value) {
                $("#nat_customer")
                    .append($("<option></option>")
                    .attr("value", value.id)
                    .text(value.company_name)) 
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
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
        success: function (response) {
            $("#nat_item_name").empty();
            $(response.item_names).each(function (index, value) {
                $("#nat_item_name")
                    .append($("<option></option>")
                    .attr("value", value.id)
                    .text(value.name)) 
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
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
        success: function (response) {
            $("#nat_reference").empty();

            $(response.references).each(function (index, value) {
                $("#nat_reference")
                    .append($("<option></option>")
                    .attr("value", value.reference_name)
                    .text(value.reference_name)) 
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
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
        success: function (response) {

            console.log(response);
            $("#nat_style_number").empty();
            $("#nat_buyer_po_number").empty();
            $("#nat_ocn_number").empty();

            $(response.style_no).each(function (index, value) {
                $("#nat_style_number")
                    .append($("<option></option>")
                    .attr("value", value.style_no)
                    .text(value.style_no)) 
                    .prop("disabled", false);
            });

            $(response.buyer_po_number).each(function (index, value) {
                $("#nat_buyer_po_number")
                    .append($("<option></option>")
                    .attr("value", value.buyer_po_number)
                    .text(value.buyer_po_number)) 
                    .prop("disabled", false);
            });
            
            $(response.ocn_numbers).each(function (index, value) {
                $("#nat_ocn_number")
                    .append($("<option></option>")
                    .attr("value", value.ocn_number)
                    .text(value.ocn_number)) 
                    .prop("disabled", false);
            });

            $('.selectpicker').selectpicker('refresh');
        }
    });
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
    .prop("disabled", false);

    $('.selectpicker').selectpicker('refresh');
}

/** Create job card form validation **/
$('#nat_jobcard_filter_form').validator().on('submit', function (e) {
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
    alert()
}