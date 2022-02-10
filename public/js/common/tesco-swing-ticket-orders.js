/** 
 * Tesco Swing tickets JS 
 * @author Praveen
 * 
 */

// Method to render tesco swing ticket orders page
renderTescoSwingTicketOrders = (role, template_id, callback=null) => {
    var user_role = role.toLowerCase();
    var size_id = $('#tesco_care_size_chart').val();
    

    $('.main-content').load('/' + user_role + '/tesco/swing-ticket-orders?size='+size_id+'&template='+template_id, ( response, status, xhr ) => {
        $(".selectpicker").selectpicker("refresh");
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');
        $('.top-content').html('<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;"><h4><strong>Create Order For - ' + localStorage.getItem("template_group") + '</strong></h4></div>');
        callback();
    }); 
}

// $(document).on('change','#swing_product_benefit',function(e){
//     if (e.target.value === "") {
//         $('#swing_mm_product_benefit').prop('required', true);
//         $('#swing_product_benefit').prop('required', false);
//     } else {
//         $('#swing_mm_product_benefit').prop('required', false);
//         $('#swing_product_benefit').prop('required', true);
//     }
//     $('#swing_mm_product_benefit').val('');
//     $(".selectpicker").selectpicker("refresh");
// });

// $(document).on('change','#swing_mm_product_benefit',function(e){
//     if (e.target.value === "") {
//         $('#swing_product_benefit').prop('required', true);
//         $('#swing_mm_product_benefit').prop('required', false);
//     } else {
//         $('#swing_product_benefit').prop('required', false);
//         $('#swing_mm_product_benefit').prop('required', true);
//     }
//     $('#swing_product_benefit').val('');
//     $(".selectpicker").selectpicker("refresh");
// });

$(document).on('change','#swing_product_benefit',function(e){
    if (e.target.value === "") {
        $('#swing_mm_product_benefit').prop('required', true);
        $('#swing_product_benefit').prop('required', false);
        $('#swing_mm_product_benefit').prop('disabled', false);
    } else {
        $('#swing_mm_product_benefit').prop('required', false);
        $('#swing_product_benefit').prop('required', true);
        $('#swing_mm_product_benefit').prop('disabled', true);
    }
    $('#swing_mm_product_benefit').val('');
    $(".selectpicker").selectpicker("refresh");
});

$(document).on('change','#swing_mm_product_benefit',function(e){
    if (e.target.value === "") {
        $('#swing_product_benefit').prop('required', true);
        $('#swing_mm_product_benefit').prop('required', false);
        $('#swing_product_benefit').prop('disabled', false);
    } else {
        $('#swing_product_benefit').prop('required', false);
        $('#swing_mm_product_benefit').prop('required', true);
        $('#swing_product_benefit').prop('disabled', true);
    }
    $('#swing_product_benefit').val('');
    $(".selectpicker").selectpicker("refresh");
});

// Method to validate and submit tesco swing ticket orders
validateTescoSwingTicketOrder = (user_type) => {
    /** [ bootstrap validator for create tesco order form ] **/
    $('#tesco_swing_ticket_order_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            submitSwingTicketOrder(user_type);
        }
    });
}

// Method to Save tesco care label orders
submitSwingTicketOrder = (user_type) => {
    $.ajax({
        type: "post",
        url: base_url + "/" + user_type + "/tesco/submit-swing-ticket-order",
        data: $('#tesco_swing_ticket_order_form').serialize(),
        headers: {
            'X-CSRF-TOKEN': csrf_token
        },
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
            $('.swing-ticket-order-submit-btn').prop('disabled', true);
        },
        statusCode: {
            200: function (response) {

                    $('#hidden_id').val(response.data.last_insertion);

                    $emptyQty = $('.tesco-quantity-field').filter((index, quantity) => quantity.value != '' );
                    $emptyBarcode = $('.swing-ticket-ean').filter((index, ean) => ean.value != '');

                    if($emptyQty.length == 0 || $emptyBarcode.length == 0){
                        $('.create-download-artwork').remove();
                    } else  {
                        $('.create-download-artwork').css('display','inline-block');
                    }

                    $('#result').html('<div class="alert alert-success alert-dismissible" id="alert_success"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been placed successfully</div>');

                    $('.order-cancel-btn').show();
                    $(window).scrollTop(0);
                    postSwingTicketOrderSubmit(user_type, response.data);
                },
                400: function (error) {
                    $('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
                    $('.swing-ticket-order-submit-btn').prop('disabled', false);
                },
                422: function (errorXHR) {
                    var error_msg = ``;
                    $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                        error_msg += `<li>` + valueOfElement + `</li>`;
                    });
                    $('#result').html('<div class="alert alert-danger">' + error_msg + '</div>');
                    $('.swing-ticket-order-submit-btn').prop('disabled', false);
                    $(window).scrollTop(0);
                },
                409: function (response) {
					var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                             <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                              <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                <span aria-hidden="true">×</span>\
                              </button>\
							  </div>';

                    $('#order-exists-msg').html(html_content);
                    $('#warningModal').modal('show');
					$('.swing-ticket-order-submit-btn').prop('disabled', false);

				},
        },
        complete: function () {
            
            $(".loader").hide();
        }
    });
}

postSwingTicketOrderSubmit = (user_type, data) => {

    // Handle po upload modal button issue after page reloading
    $('.po-submit-btn').text('Save');
    $('.po-submit-btn').attr('data-dismiss', '')
    $('.upload-later').show();

    /** Show snackbar if order placed **/
    if (data.last_insertion && data.is_order_count_empty === 0) {

        /** Make form as readonly */
        $('#tesco_swing_ticket_order_form input').prop('disabled', 'true');
        $('.selectpicker').prop('disabled', true);

        $('.swing-ticket-order-submit-btn').remove();

        /** [ store last updated id as hidden input , for cancel order functionality] */

        var div_html = '<input class="table-id-hidden" id="SwingTicket_order_id" name="swingticket_order_id" type="hidden" value="' + data.last_insertion + '">';

        $('.swing-ticket-order-invisible-div').html(div_html);


        // check if po upload is required or not.
        if (data.po_required === 1) {
            var template_name = localStorage.getItem("template_group");
            var template_splitted = template_name.split("-");
            var template = template_splitted[0].trim();
            /** Buyer Po upload modal for combined po upload modal */
            var html = `<strong>Select Artwork :</strong>
                        <div class="row">
                        <div class="col-md-4">
                        <div class="form-check form-group has-feedback">
                            <label class="form-check-label">
                            <input class="form-check-input template-checkbox1" type="checkbox" value="${data.last_insertion}" name="SwingTicket" id="SwingTicket" style="margin-right: 10px;" data-name="SwingTicket"> ${template} </label>
                        <div class="help-block with-errors"></div>
                    </div></div>
                    <div class="col-md-8 SwingTicket-po-div po-number-div" style="display:none; margin-top: 5px;">
                    <strong><span id="SwingTicket-uploaded-po"></span></strong>
                    <i aria-hidden="true" style="color: green;margin-left: 9px;font-size: 15px;" class="fa fa-check"></i>
                    </div></div>`;
            /** Dynamic content for po upload modal tab */
            $('#template-checkbox-div').html(html);

            if (data.delivery_date_required === 1) {
                $('#combined_delivery_date').prop('required', true);
            }

            /** [ Backdrop for bootstrap modal ] */
            $('#poUploadModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#poUploadModal').modal('show');

            /** @type {Date} [Delivery date Datepicker format & validation] */
            var today = new Date(data.delivery_date_start.date);
            $('#delivery_datepicker').datepicker({
                autoclose: true,
                startDate: today,
                format: 'yyyy-mm-dd',
            })

            /* [ Toggle required field validation in for fields ] */
            $('.template-checkbox1:checkbox').on('change', function () {
                var th = $(this),
                    id = th.prop('id');
                if (th.is(':checked')) {
                    var checkbox_clicked = 0;
                    /** [ Get count of checkboxes checked ] */
                    $('.template-checkbox1').each(function (i, item) {
                        if ($(this).is(':checked')) {
                            checkbox_clicked++;
                        }

                    });
                    /** [ Hide validation error, if atleast 1 artwork selected ] */
                    if (checkbox_clicked === 1) {
                        $('.template-error1').html('');
                    }
                } else {
                    /** [ Clear all input fields values] */
                    $('input:not(.template-checkbox1)[id="' + id + '"]').val("");
                    /* [ remove validation error from input field ] */
                    $('#upload_combined_po_form').validator('validate');
                }
            });

        } else if (data.pi_required === 1 && data.po_required != 1) {
            var template_id = localStorage.getItem("templates");
            var order_table_name = "tesco_swingticket_orders";
            var order_flag = "newOrder";
            var pi_status = false;
            var template_name = 'Swing Ticket';
            var order_table_id = data.last_insertion;
            
            genarateTescoPI(order_table_id, order_table_name, pi_status, data.style_number, template_id, data.po_number, template_name, order_flag)
        }

    }

    /** Update bootstrap validator */
    $('#upload_combined_po_form').validator('update');

    /** [ bootstrap validator for upload combined po form ] **/
    $('#upload_combined_po_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            var checkbox_clicked = 0;
            /** [ Get count of checkboxes checked ] */
            $('.template-checkbox1').each(function (i, item) {
                if ($(this).is(':checked')) {
                    checkbox_clicked++;
                }

            });
            /** [ Show validation error, if atleast 1 artwork not selected ] */
            if (checkbox_clicked < 1) {
                /**  [ Show validation error message ] **/
                $('.template-error1').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
            } else {
                $('.template-error1').html('');
                var templateName = [];
                var formData = new FormData();
                var delivery_date = $('input[name="combined-delivery-date"]').val();
                var po_number = $('#po_number').val();
                var po_file = $('#po_file').prop('files')[0];
                template_id = [];
                templates = [];
                /** [ Create array of Templates selected ] */
                $('.template-checkbox1').each(function (i, item) {
                    if ($(this).is(':checked')) {
                        template_id[i] = $(this).val();
                        templates[i] = $(this).data('name');
                        templateName.push($(this).data('name'));
                    }
                });

                formData.append('template_id', template_id);
                formData.append('templates', templates);
                formData.append('delivery_date', delivery_date);
                formData.append('po_number', po_number);
                formData.append('po_file', po_file);

                uploadCombinedPO(user_type, formData, templateName);
            }
        }

    });
}

addHyphen =  (element) => {
    var input = document.getElementById(element.id);
    var str = input.value;
    str = str.replace("-","");
    if (str.length > 3) {
        str = str.substring(0,3) + "-" + str.substring(3);
    }
    input.value = str
}