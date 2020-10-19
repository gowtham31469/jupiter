//Global Variable
var td_row = "";

/**
 * method to show the tesco order status table
 * @return {[type]} [description]
 */
function tescoOrderStatus() {
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.top-content').html('');
    $('.template-error').html('');
    
    $.ajax({
        url: base_url + "/admin/tesco/tesco-orders-filter",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
                var uniqueNames = [];
                var zones = [];

                $('.top-content').html(`
                <div class="row">
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="stylenoos" title="Select CE Style Number" multiple data-selected-text-format="count" data-live-search="true" data-size="10"></select></div></div>
                <div class="col-md-2"></div>
                <div class="col-md-4"><input type="text" placeholder="Start Date" class="form-control startdateos" multiple style="width:100%" name="startdateos" /></div>
                <div class="col-md-2"></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="ukstylenoos" title="Select UK Style Number" multiple data-selected-text-format="count" data-live-search="true" data-size="10"></select></div></div>
                </div>
                <div class="row" style="margin-top: 15px;">
                <div class="col-md-1"></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="seasonos" title="Select Season" multiple data-selected-text-format="count" data-live-search="true" data-size="10"></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="orderstatusos" title="Select Order Status" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="Saved">Saved</option><option value="Submited">Submited</option><option value="Pending">Pending</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="artworkstatusos" title="Select Artwork Status" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="Approval Pending">Approval Pending</option><option value="Order Approved">Order Approved</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="postatusos" title="Select PO Status" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="PO Pending">PO Pending</option><option value="PO Uploaded">PO Uploaded</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="pistatusos" title="Select PI Status" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="PI Approval Pending">PI Approval Pending</option><option value="PI Approved">PI Approved</option></select></div></div>
                <div class="col-md-1"></div>
                </div>
                <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                <button class="btn btn-primary input-sm pull-right" onclick="submitOs(this)" id="submitos">Submit</button>
                </div>
                </div>
                <div id="OrderStatusTable"></div>
                `);

                $(data.data).each(function(index,value){
                    $("#seasonos").append("<option value='"+ value.season_code + "'>" + value.season_code + "</option>");
                })

                $(data.style_no).each(function(index,value){
                    if (value.zone == "CE") {
                        if (uniqueNames.indexOf(value.ce_style_ref) === -1) {
                            $("#stylenoos").append("<option value='"+ value.ce_style_ref + "'>" + value.ce_style_ref + "</option>");
                        }
                    } else {
                        if (uniqueNames.indexOf(value.uk_style_ref) === -1) {
                            $("#ukstylenoos").append("<option value='"+ value.uk_style_ref + "'>" + value.uk_style_ref + "</option>");
                        }
                    }
                })

                var d = new Date();
                var maxDate = d.toLocaleDateString('en-US');
                d.setMonth(d.getMonth() - 6);
                var minDate = d.toLocaleDateString('en-US');

                // alert(minDate)
                // alert(maxDate)

                $('input[name="startdateos"]').daterangepicker({
                    "minDate": minDate,
                    "maxDate": maxDate
                },
                function (start, end, label) {
                    var start_date = start.format('YYYY-MM-DD');
                    var end_date = end.format('YYYY-MM-DD');
                    $.ajax({
                        url: base_url + "/admin/tesco/tesco-orders-submit-filter",
                        type: "Post",
                        dataType: "json",
                        data: {
                            _token: csrf_token,
                            start_date:start_date,
                            end_date:end_date
                        },
                        beforeSend: function () {
                            $(".loader").show();
                            $(".loader").css('margin-top', '12%');
                        },
                        statusCode: {
                            /** [Show response status based on HTTP status code] */
                            200: function (data) {
                                
                                $("#seasonos").html("");
                                $("#ukstylenoos").html("");
                                $("#stylenoos").html("");

                                $(data.data).each(function(index,value){
                                    $("#seasonos").append("<option value='"+ value.season_code + "'>" + value.season_code + "</option>");
                                })
                
                                $(data.style_no).each(function(index,value){
                                    if (value.zone == "CE") {
                                        if (uniqueNames.indexOf(value.ce_style_ref) === -1) {
                                            $("#stylenoos").append("<option value='"+ value.ce_style_ref + "'>" + value.ce_style_ref + "</option>");
                                        }
                                    } else {
                                        if (uniqueNames.indexOf(value.uk_style_ref) === -1) {
                                            $("#ukstylenoos").append("<option value='"+ value.uk_style_ref + "'>" + value.uk_style_ref + "</option>");
                                        }
                                    }
                                })
                                $(".selectpicker").val('');
                                $('.selectpicker').selectpicker('refresh');
                            },
                            400: function () {
                                $('.snackbar_span').html('Something went wrong');
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
                });

            },
            400: function () {
                $('.snackbar_span').html('Something went wrong');
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

function submitOs(obj)
{
    var seasonos = $('#seasonos').val();
    var ukstylenoos = $('#ukstylenoos').val();
    var orderstatusos = $('#orderstatusos').val();
    var artworkstatusos = $('#artworkstatusos').val();
    var postatusos = $('#postatusos').val();
    var pistatusos = $('#pistatusos').val();
    var stylenoos = $('#stylenoos').val();
    $.ajax({
        url: base_url + "/admin/tesco/tesco-submit-os",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            seasonos:seasonos,
            ukstylenoos:ukstylenoos,
            orderstatusos:orderstatusos,
            artworkstatusos:artworkstatusos,
            postatusos:postatusos,
            pistatusos:pistatusos,
            stylenoos:stylenoos,
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
                

            },
            400: function () {
                $('.snackbar_span').html('Something went wrong');
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

jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function (a) {
        var ukDatea = a.split('-');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },

    "date-uk-asc": function (a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-uk-desc": function (a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});

function showFilter() {
    $('.filter-window').toggle();
}
/* Our main filter function
 * We pass the column location, the start date, and the end date
 */
var filterByDate = function (column, startDate, endDate) {
    // Custom filter syntax requires pushing the new filter to the global filter array

    $.fn.dataTableExt.afnFiltering.push(
        function (oSettings, aData, iDataIndex) {
            var columnDate = aData[column].toString().split("-").reverse().join("-");

            var rowDate = normalizeDate(columnDate),
                start = normalizeDate(startDate),
                end = normalizeDate(endDate);
            // If our date from the row is between the start and end
            if (start <= rowDate && rowDate <= end) {
                return true;
            } else if (rowDate >= start && end === '' && start !== '') {
                return true;
            } else if (rowDate <= end && start === '' && end !== '') {
                return true;
            } else {
                return false;
            }
        }
    );
};

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
    return normalized;
}

/**
 * [method append data to tescoStatusTable ]
 * @param  {[type]} data [description]
 * @param  {[type]} path [description]
 * @return {[type]}      [description]
 */
function tescoStatusTable(data, path) {
    var th = "<th style='text-align: center;'>Style No</th><th style='text-align: center;'>Buyer PO No</th><th style='text-align: center;'>Zone</th><th style='text-align: center;'>Reference No</th><th style='text-align: center;'>Template Type</th><th style='text-align: center;'>Company</th><th style='text-align: center;'>Order Date</th><th style='text-align: center;'>Delivery Date</th><th style='text-align: center;'>Artwork Status</th><th style='text-align: center;'>PI Status</th><th style='text-align: center;'>PO Status</th><th style='text-align: center;'>PO Number</th><th style='text-align: center;'>Actions</th>";
    var tr = "";
    var columnsIn = data[0];
    $.each(data, function (index, value) {
        var action_status = false;
        tr += '<tr class="trClass" id="' + value.tableName + '_' + value.tableID + '">';
        for (var key in columnsIn) {
            if (key != "id") {

                if (key == "PO Number" && value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                    var po_file = value['po_file'];
                    let customer_po_number = value[key] === null?'':value[key];
                    tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11" value="'+customer_po_number+'"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"' + value.tableID + '","' + value.tableName + '","' + po_file + '")><span class="fa fa-check"></span></button></span></div></div></td>';
                } else if (key == "OrderDate") {
                    if (value['is_order_count_empty'] == 0) {
                        var orderDate = moment(value[key]).format('DD-MM-YYYY');
                        tr += '<td align="center">' + orderDate + '</td>';
                    } else {
                        tr += '<td align="center"> - </td>';
                    }


                } else if (key == "PI Status") {
                    var str = value[key];
                    if (str != null && str != "" && value['is_order_count_empty'] == 0) {
                        if (str == "PI Approved") {
                            action_status = true;
                        }
                        $status = str.replace(/ /g, "");
                        $status_text = '<span class="pi_class label label-' + $status + '">' + value[key] + '</span>';
                    } else {
                        $status_text = " - ";
                    }


                    tr += '<td align="center">' + $status_text + '</td>';

                } else if (key == "Artwork Status") {
                    if (value['is_order_count_empty'] == 0) {
                        var str = value[key];
                        $status = str.replace(/ /g, "");

                        tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                    } else {
                        tr += '<td align="center"><span class="label label-CreateOrder">Create Order</span></td>';
                    }

                } else if (key == "PO Status") {
                    var str = value[key];
                    var status_text = " - "
                    if (value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                        if (str != null && str != "") {
                            status = str.replace(/ /g, "");
                            status_text = '<span class="po_class label label-' + status + '">' + value[key] + '</span>';
                        } else {
                            str = "PO Pending"
                            status = str.replace(/ /g, "");
                            status_text = '<span class="po_class label label-' + status + '">' + str + '</span>';
                        }
                    } else {
                        status_text = " - ";
                    }

                    tr += '<td align="center class="po_status">' + status_text + '</td>';

                } else if (key == "StyleNo") {
                    var order_flag = value.order_flag;
                    var span_style = '';
                    if (order_flag == "reOrder") {
                        span_style = '<span title="Additional Quantity" class="batch-additional">A</span>'
                    }
                    if (order_flag == "repeatOrder") {
                        span_style = '<span title="Repeat Order" class="batch-reorder">R</span>'
                    }
                    var zone = value.Zone
                    var style_no = "";
                    if (zone == "CE") {
                        style_no = value.ce_style_ref;
                    } else {
                        style_no = value.uk_style_ref;
                    }
                    tr += '<td align="center" data-id="' + value.tableID + '" data-table="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" onclick=updateArtwork(this)>' + span_style + '' + style_no + '</a></td>';
                } else if (key == "Actions") {
                    var po_file = value['po_file'];
                    if (po_file != null) {
                        style_po = "style='border-color:#FFFFFF'"
                    } else {
                        style_po = "style='border-color:#FF0000'"
                    }
                    var pi_required = value['pi_required'];
                    var po_required = value['po_required'];
                    if (value["Artwork Status"] != "Order Cancelled" && value['is_order_count_empty'] == 0) {
                        if (pi_required == 1 && po_required == 1) {
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=tescoPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (po_required == 1 && pi_required == 0) {

                            tr += '<td align="center"><a href="javascript:void(0)" class="po-style" onclick=tescoPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (pi_required == 1 && po_required == 0) {

                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a></td>';
                        }
                    } else {
                        tr += '<td align="center"> - </td>';
                    }
                } else if (key == "DeliveryDate") {

                    if (value['is_order_count_empty'] == 0) {
                        if (value[key] == null) {
                            tr += '<td><span style=display:none;>' + value.OrderDate + '</span><input class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '")  readonly></td>';
                        } else {

                            tr += '<td><span style=display:none;>' + value.OrderDate + '</span><input value="' + value[key] + '"class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px;" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '") readonly></td>';
                        }
                    } else {
                        tr += '<td><span style=display:none;>' + value.OrderDate + '</span> - </td>';
                    }
                } else if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uk_style_ref" && key != "ce_style_ref" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty") {
                    if (value[key] == "" || value[key] == null) {
                        tr += '<td> - </td>';
                    } else {
                        tr += '<td>' + value[key] + '</td>';
                    }
                }
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-tesco-orders" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


$('body').on('focus', ".datepicker_recurring_start", function () {
    var count = $(this).closest('tr').find('.datepicker_recurring_start').attr("data-id");

    $(this).datepicker({
        autoclose: true,
        startDate: "+" + count + "d",
        format: 'dd-mm-yyyy'
    });
});


function updateDeliveryDate(obj, tableID, tableName, count) {
    $(obj).closest('tr').find('.datepicker_recurring_start').css('border-color', '');
    
    var deliveryDate = $(obj).closest('tr').find('.datepicker_recurring_start').val();
    if (deliveryDate == "" || deliveryDate === '01-01-1970') {
        $(obj).closest('tr').find('.datepicker_recurring_start').css('border-color', 'rgb(244, 67, 54)');
    } else {
        $('.snackbar_span').html('');
        $.ajax({
            url: base_url + "/admin/tesco/update-delivery-date",
            type: "Post",
            dataType: "json",
            data: {
                _token: csrf_token,
                deliveryDate: deliveryDate,
                tableName: tableName,
                id: tableID
            },
            beforeSend: function () {
                $(".loader").show();
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html('Delivery Date updated Successfully');

                    showSnackbar();
                },
                400: function () {
                    $('.snackbar_span').html('Something went wrong');
                    showSnackbar();
                }
            },
            error: function (data) {
            },
            complete: function () {
                $(".loader").hide();
            }
        })
    }


}

/**
 * [tescoSetPO description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoSetPO(obj, id, tableName, po_file) {
	$(obj).attr('disabled','disabled')
    $('.tesco-po').css('border-color', '');
    var po_number = $(obj).closest('tr').find('.tesco-po').val();
    
    var buyer_id = localStorage.getItem("buyer_id");
    if (po_number != "") {
        $.ajax({
            url: base_url + "/admin/tesco/order-management/set-po-number",
            type: "Post",
            dataType: "json",
            data: {
                _token: csrf_token,
                po_number: po_number,
                tableName: tableName,
                id: id,
                buyer_id: buyer_id
            },
            beforeSend: function () {
                $(".loader").show();
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.message);
                    //$(obj).closest('tr').find('.tesco-po').val(po_number);
                   // $(obj).closest('tr').find('.po-span').addClass('hidden');
                    $ponumber = $(obj).closest('tr').find('.tesco-po').val();
                    // $div = $(obj).closest('tr').find('.text-center');
                    // $(obj).closest('tr').find('.tesco-po').parent().parent().remove();
                    // $div.text($ponumber)
                    if ($(obj).closest('tr').find('.po_class').text() == "PO Pending" && po_file != "null") {
                        $(obj).closest('tr').find('.po_class').html("PO Uploaded")
                        $(obj).closest('tr').find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                    }
                    showSnackbar();
                    if (po_number == "") {
                        $(obj).closest('tr').find('.tesco-po').attr('disabled');
                        $(obj).closest('tr').find('.tesco-po').attr('placeholder', "Enter PO");
                    }
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                },
                403: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                },
                400: function () {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                }
            },
            error: function (data) {
            },
            complete: function () {
                $(".loader").hide();
            }
        })
    } else {
        $(obj).closest('tr').find('.tesco-po').focus();
        $(obj).closest('tr').find('.tesco-po').css('border-color', '#F44336');
        $('.snackbar_span').html('Please enter a valid PO number');
        showSnackbar();
		setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
    }
}
/**
 * [tescoPOUpload description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoPOUpload(obj, id, po_file, tableName) {
    
    $.ajax({
        url: base_url + "/admin/tesco/get-erp-status",
        type: 'get',
        data: {
            table_name: tableName,
            id: id,
            _token: csrf_token
        },
        beforeSend: function () {
             $(".loader").show();
        },
        success: function (data) {
            // erp_details array null , if order is not moved to erp tables
            // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
            if (data.erp_details[0] != null) {
                if (data.erp_details[0].status_name !== 'Hold For JC Queue' && data.erp_details[0].status_name !== 'Moved To ERP') {
                    $('.update-po-file').hide();

                    $('#error_msg_span').html('ERP Order Status : <strong>' + data.erp_details[0].status_name + '</strong>. You are not permitted to upload any more po files for this order.')
                }
                else{
                    $('.update-po-file').show();
                    $('#error_msg_span').html('');
                }
            }
            else
            {
                $('.update-po-file').show();
                $('#error_msg_span').html('');
            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            if (po_file == "null") {
                $('#po_upload').removeClass('hidden')
                $('.upload-po').removeClass('hidden')
                $('#po_file').addClass('hidden')
            } else {
                $('#po_upload').addClass('hidden')
                $('.upload-po').addClass('hidden')
                $('#po_file').removeClass('hidden')
            }
            var po_link = base_url + po_file
            $('#po_link').attr("href", po_link);
            $('#rowID').val(id);
            $('#tableName').val(tableName);
            $('#buyer_id').val(localStorage.getItem("buyer_id"));
            $('#po-file-upload').modal('show')
        }
    })
}

$(".update-po-file").click(function () {
    $('#po_upload').removeClass('hidden')
    $('.upload-po').removeClass('hidden')
});


function updateArtwork(obj) {
    $('#tesco_po_number').prop('disabled', true);
    $('#order_type').prop('disabled', true);
    $('#tesco_uk_style').prop('disabled', true);
    $('#tesco_ce_style').prop('disabled', true);
    $('#tesco_eqos').prop('disabled', true);
    $('#tesco_ocn').prop('disabled', true);
    $('#tesco_description').prop('disabled', true);
    $('#tesco_season_code').prop('disabled', true);
    $('#tesco_manufacture_date').prop('disabled', true);
    $('#tesco_supplier').prop('disabled', true);
    $('#tesco_brand').prop('disabled', true);
    $('#tesco_department').prop('disabled', true);
    $('#tesco_rfid').prop('disabled', true);
    $('#tesco_tag_at_source').prop('disabled', true);
    // $('#orms_tpnd').prop('disabled',true);
    // $(obj).closest('tr').find('.orms_tpnd').prop('disabled',true);
    $("#create_tesco_order_status_form input").prop("disabled", true);

    $("form#create_tesco_order_status_form")[0].reset();
    $('#po_already_error').addClass('hidden')
    var id = $(obj).parent().attr('data-id');
    var table_name = $(obj).parent().attr('data-table');
    var TemplateName = $(obj).parent().attr('data-template');

    td_row = $(obj).parent().parent();

    
    $.ajax({
        url: base_url + "/admin/tesco/edit-artwork",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
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

                if (data.order_details != 0) {
                    $('#tesco_po_number').val(data.order_details.po_number);
					$('#qr_flag').val(data.tesco_item_master[0].is_qrcode_required);
                    $('#order_type').val(data.order_details.order_flag);
                    $('#tesco_uk_style').val(data.order_details.uk_style_ref);
                    $('#tesco_ce_style').val(data.order_details.ce_style_ref);
                    $('#tesco_eqos').val(data.order_details.eqos_site_code);
                    $('#tesco_ocn').val(data.order_details.ocn_number);
                    $('#tesco_description').val(data.order_details.short_description);
                    $('#tesco_season_code').val(data.order_details.season_code);
                    $('#tesco_manufacture_date').val(data.order_details.manufacture_date);
                    var tag_at_source = data.order_details.tag_at_source == 0 ? "no" : "yes";
                    var rfid = data.order_details.rfid_compliant == 0 ? "no" : "yes";

                    $('#tesco_supplier').selectpicker('val', data.order_details.tesco_metadata_supplier_id);
                    $('#tesco_brand').selectpicker('val', data.order_details.tesco_brand_id);
                    $('#tesco_department').selectpicker('val', data.order_details.tesco_department_id);
                    $('#tesco_rfid').selectpicker('val', rfid);
                    $('#tesco_tag_at_source').selectpicker('val', tag_at_source);
                    if(data.order_details.full_description != null)
                    {
                        $('#tesco_description').prev().text($('#tesco_description').prev().text()+'(Full Description: '+data.order_details.full_description+')');
                    }
                    else 
                    {
                        $('#tesco_description').prev().text('Short Description');
                    }


                    //					if(data.order_details.order_flag != "newOrder")
                    //					{
                    //						if(data.order_details.order_flag == "reOrder")
                    //						{
                    $('#tesco_po_number').prop('disabled', true);
                    //						}
                    $('#order_type').prop('disabled', true);
                    $('#tesco_uk_style').prop('disabled', true);
                    $('#tesco_ce_style').prop('disabled', true);
                    $('#tesco_eqos').prop('disabled', true);
                    $('#tesco_ocn').prop('disabled', true);
                    $('#tesco_description').prop('disabled', true);
                    $('#tesco_season_code').prop('disabled', true);
                    $('#tesco_manufacture_date').prop('disabled', true);
                    $('#tesco_supplier').prop('disabled', true);
                    $('#tesco_brand').prop('disabled', true);
                    $(".dropdown-toggle").css("background-color", "#eee");
                    $('#tesco_department').prop('disabled', true);
                    $('#tesco_rfid').prop('disabled', true);
                    $('#tesco_tag_at_source').prop('disabled', true);
                    //					}


                    $('.artwork_tesco').html('<object style="border: 2px #333 solid;" id="svg_tesco_os" class="center-block svg2" data="' + data.template_file[0].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;position: absolute;"><svg id="ean-13"></svg></div>')

                    var zone = data.order_details.zone;
                    var moq = data.moq;

                    if ((TemplateName == "TCL1" && zone == "CE") || (TemplateName == "TCL2" && zone == "CE") || (TemplateName == "TSL1" && zone == "CE") || (TemplateName == "THL1" && zone == "CE")) {
                        var qty = "Cases Per Carton";
                        var barcode_tr = "ITF Barcode";
                        var tpnd_tr = "ORMS TPND";
                        var th_tr = "";
                    } else if ((TemplateName == "TCL4" && zone == "CE") || (TemplateName == "TSL3" && zone == "CE") || (TemplateName == "THL3" && zone == "CE")) {
                        var qty = "Case Size";
                        var barcode_tr = "EAN Barcode";
                        var tpnd_tr = "ORMS TPND";
                        var th_tr = "<th>Size</th>";
                    } else if ((TemplateName == "TCL4" && zone == "UK") || (TemplateName == "TSL3" && zone == "UK") || (TemplateName == "THL3" && zone == "UK")) {
                        var qty = "Case Size";
                        var barcode_tr = "EAN Barcode";
                        var tpnd_tr = "TPND";
                        var th_tr = "<th>Size</th>";
                    } else if ((TemplateName == "TCL1" && zone == "UK") || (TemplateName == "TCL2" && zone == "UK") || (TemplateName == "TSL1" && zone == "UK") || (TemplateName == "THL1" && zone == "UK")) {
                        var qty = "Units Per Carton";
                        var barcode_tr = "ITF Barcode";
                        var tpnd_tr = "TPND";
                        var th_tr = "";
                    }

                    var tr = "";
                    var i = 1;
                    $(data.quantity_details).each(function (key, value) {

                        if ((TemplateName == "TCL1" && zone == "CE") || (TemplateName == "TCL2" && zone == "CE") || (TemplateName == "TSL1" && zone == "CE") || (TemplateName == "THL1" && zone == "CE")) {
                            var unit_qty = value.cases_per_carton;
                            var barcode_td = value.itf_barcode;
                            var maxlength = 14;
                            var tpnd = value.orms_tpn;
                            var th_td = "";
                            var tpn_th = "Enter ORMS TPND";
                            var tpn_len = "13";
                            var unit_len = "5";
                        } else if ((TemplateName == "TCL4" && zone == "CE") || (TemplateName == "TSL3" && zone == "CE") || (TemplateName == "THL3" && zone == "CE")) {
                            var unit_qty = value.case_size;
                            var barcode_td = value.ean_barcode;
                            var maxlength = 13;
                            var tpnd = value.orms_tpn;

                            var th_td = '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" disabled required="" id="os_single_size_' + i + '" name="os_single_size_' + i + '"  value="' + value.size + '" placeholder="Enter Size" maxlength="9" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';

                            var tpn_th = "Enter ORMS TPND";
                            var tpn_len = "13";
                            var unit_len = "6";
                        } else if ((TemplateName == "TCL4" && zone == "UK") || (TemplateName == "TSL3" && zone == "UK") || (TemplateName == "THL3" && zone == "UK")) {
                            var unit_qty = value.case_size;
                            var barcode_td = value.ean_barcode;
                            var maxlength = 13;
                            var tpnd = value.tpnd;
                            var th_td = '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" disabled required="" id="os_single_size_' + i + '" name="os_single_size_' + i + '" value="' + value.size + '" placeholder="Enter Size" maxlength="9" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
                            var tpn_th = "Enter TPND";
                            var tpn_len = "9";
                            var unit_len = "4";
                        } else if ((TemplateName == "TCL1" && zone == "UK") || (TemplateName == "TCL2" && zone == "UK") || (TemplateName == "TSL1" && zone == "UK") || (TemplateName == "THL1" && zone == "UK")) {
                            var unit_qty = value.units_per_carton;
                            var barcode_td = value.itf_barcode;
                            var maxlength = 14;
                            var tpnd = value.tpnd;
                            var th_td = "";
                            var tpn_th = "Enter TPND";
                            var tpn_len = "9";
                            var unit_len = "4";
                        }

                        if (value.id == data.quantity_details[0].id) {
                            var delete_td = '';
                        } else {
                            var delete_td = '<a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRowOs(this)"></i></a>';
                        }

                        tr += '<tr><td class="tpnd"><div class="form-group has-feedback"> <input disabled type="text" class="form-control input-sm orms_tpnd" required="" id="os_tpnd_' + i + '" data-minlength="' + tpn_len + '" maxlength="' + tpn_len + '" data-required-error="Mandatory Field." data-minlength-error="Enter ' + tpn_len + ' digits."  name="os_tpnd_' + i + '" onkeypress="return isNumber(event)" value="' + tpnd + '" placeholder="' + tpn_th + '" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td class="barcode"><div class="form-group has-feedback"> <input  disabled type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="' + maxlength + '" maxlength="' + maxlength + '" data-required-error="Mandatory Field." data-minlength-error="Enter ' + maxlength + ' digits." id="os_barcode_' + i + '" onkeyup="dynamicSVGTemplates()" name="os_barcode_' + i + '" onkeypress="return isNumber(event)" value="' + barcode_td + '" placeholder="Enter ITF Barcode"><label class="barcode_error hidden" style="color:red;margin-left: -55px;position: absolute;">Enter Valid Barcode</label><div class="help-block with-errors"></div></div></td><td class="size"><div class="form-group has-feedback"> <input disabled type="text" maxlength="' + unit_len + '" class="form-control input-sm ce_ratio_cpc" required="" id="os_case_qty_' + i + '" name="os_case_qty_' + i + '" onkeypress="return isNumber(event)" value="' + unit_qty + '" placeholder="Enter ' + qty + '" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>' + th_td + '<td> <div class="form-group has-feedback has-success"> <input disabled min="' + moq + '" onInput="checkLength(6,this)" type="number" class="form-control  input-sm tot_qty_table" value="' + value.count + '" required placeholder="Quantity" data-min-error="Quantity Should be >= ' + moq + '."> <div class="help-block with-errors"></div></div></td></tr>';
                        row_id = i;
                        i++;
                        
                    })
                    $('.edit-qty-table-div').html('<hr><h4 class="text-center">Order Quantity - ' + TemplateName + '</h4><button type="button" onclick=addRowOs(this,"' + TemplateName + '","' + data.order_details.zone + '","' + moq + '") data-row-id="'+row_id+'" id="add_row" class="btn btn-default add-row-btn" disabled><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button><table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px;margin-top: 15px;"> <thead> <tr> <th width="180">' + tpnd_tr + '</th><th width="180">' + barcode_tr + '</th><th>' + qty + '</th>' + th_tr + ' <th>Quantity</th> <th>Actions</th></tr></thead> <tbody style="font-size: 11px">' + tr + '</tbody></table>')

                    $('#edit_template_name').val(TemplateName)
                    $('#edit_zone').val(data.order_details.zone)
                    $('#edit_template_type').val(data.order_details.template_type)
                    $('#edit_id').val(data.order_details.id)

                    $('.osorderdetails').text(" Order Details - " + TemplateName)
                    $('.osartwork').text(" Artwork - " + TemplateName)

                    $('.osfooter').html('<button style="margin-left: 10px;" class="btn btn-primary pull-right input-sm" id="submit" type="submit" disabled="true">Submit</button><button class="btn btn-warning  pull-right input-sm" id="edit" style="width: 55px;margin-left: 10px;" onclick ="editArtwork()" type="button">Edit</button><button class="btn btn-success  pull-right input-sm hidden" id="enable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick =enableArtworkEdit("' + table_name + '","' + id + '")>Enable Edit</button><button class="btn btn-danger pull-right input-sm hidden" id="disable_edit" style="width: 82px;margin-left: 10px;" type="button" onclick =disableArtworkEdit("' + table_name + '","' + id + '")>Disable Edit</button><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-default pull-right remove input-sm" id="close" type="button">Close</button>')
                    if (data.order_details.edit_permission == 1) {
                        $('#enable_edit').addClass('hidden')
                        $('#disable_edit').removeClass('hidden')
                    } else {
                        $('#disable_edit').addClass('hidden')
                        $('#enable_edit').removeClass('hidden')
                    }
                    $('#create_tesco_order_status_form').validator().on('submit', function (e) {
                        if (e.isDefaultPrevented()) {

                        } else {
                            e.preventDefault();
							$('#submit').prop('disabled',true)
                            setEditOrderStatus()
                        }
                    })
                    $('#create_tesco_order_status_form').validator('update');


                    $('.remove').click(function () {
                        $('.left-panel').removeClass('active');
                    })

                    var mySVG1 = document.getElementById("svg_tesco_os");
                    var svgDoc1;
                    mySVG1.addEventListener("load", function () {
                        svgDoc1 = mySVG1.contentDocument;
                        dynamicSVGTemplatescard(svgDoc1, TemplateName, data.order_details.zone, data.order_details.template_type)
                    }, false);

                }

                // erp_details array null , if order is not moved to erp tables
                // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
                if (data.erp_details[0] != null) {
                    if (data.erp_details[0].status_name !== 'Hold For JC Queue' && data.erp_details[0].status_name !== 'Moved To ERP') {
                        $('#enable_edit').remove();
                        $('#disable_edit').remove();
                        $('#edit').remove();
                        $('#submit').remove();

                        $('#error_msg_alert').html('ERP Order Status : <strong>' + data.erp_details[0].status_name + '</strong>. You are not allowed to edit this order.')
                    }
                }

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

    $('.left-panel').addClass('active');
    $('.left-panel').show();

}
$("#close").click(function () {
    $(".left-panel").removeClass('active');
})

function checkLength(len, ele) {
    var fieldLength = ele.value.length;
    if (fieldLength <= len) {
        return true;
    } else {
        var str = ele.value;
        str = str.substring(0, str.length - 1);
        ele.value = str;
    }
}

function editArtwork() {
    $("#create_tesco_order_status_form :input").prop("disabled", false);
    $(".dropdown-toggle").css("background-color", "#fff");
    // $('#tesco_po_number').prop('disabled',true);
    // $('#tesco_uk_style').prop('disabled',true);
    // $('#tesco_ce_style').prop('disabled',true);
    // $('#add_row').removeClass('disabled');
    $('#add_row').removeAttr('disabled');

}

function enableArtworkEdit(tablename, rowid) {
    
    $.ajax({
        url: base_url + "/admin/tesco/enable-artwork-edit",
        type: 'post',
        data: {
            tablename: tablename,
            rowid: rowid,
            _token: csrf_token
        },
        beforeSend: function () {
            // $(".loader").show();
        },
        success: function (data) {
            $('#enable_edit').addClass('hidden')
            $('#disable_edit').removeClass('hidden')
        },
        error: function (data) {

        },
        complete: function () {

        }
    })
}

function disableArtworkEdit(tablename, rowid) {
    
    $.ajax({
        url: base_url + "/admin/tesco/disable-artwork-edit",
        type: 'post',
        data: {
            tablename: tablename,
            rowid: rowid,
            _token: csrf_token
        },
        beforeSend: function () {

        },
        success: function (data) {
            $('#enable_edit').removeClass('hidden')
            $('#disable_edit').addClass('hidden')
        },
        error: function (data) {
        },
        complete: function () {

        }
    })
}

function setEditOrderStatus() {
    var tpnd = [];
    var barcode = [];
    var size = [];
    var count = [];
    var single_size = [];
    var barcode_checks = 0;
    var po_number = $('#tesco_po_number').val();
    var uk_style = $('#tesco_uk_style').val();
    var ce_style = $('#tesco_ce_style').val();
    var season_code = $('#tesco_season_code').val();
    var description = $('#tesco_description').val().replace(/&/g, '&#038;');
    var eqos = $('#tesco_eqos').val();
    var manufacture_date = $('#tesco_manufacture_date').val();
    
    var department = $("#tesco_department option:selected").val();
    var brand = $("#tesco_brand option:selected").val();
    var supplier = $("#tesco_supplier option:selected").val();
    var rfid = $("#tesco_rfid option:selected").val() === "yes" ? 1 : 0;
    var tag_at_source = $("#tesco_tag_at_source option:selected").val() === "yes" ? 1 : 0;
    var zone = $('#edit_zone').val()
    var type = $('#edit_template_type').val()
    var template_name = $('#edit_template_name').val()
    var edit_id = $('#edit_id').val()
    var order_type = $('#order_type').val()
    var tesco_ocn = $('#tesco_ocn').val()
    var qr_code = [];
    var barcode_array = [];

    /** [ Create array of ce ratio itf barcode ] */
    $('.ce_ratio_itf').each(function (i, item) {
        if ((template_name == "TCL1" && zone == "CE") || (template_name == "TCL2" && zone == "CE") || (template_name == "TSL1" && zone == "CE") || (template_name == "THL1" && zone == "CE")) {
            var barcodecheck = checkjsbarcodeitf($(item).val());
            if (barcodecheck == "false") {

                barcode_checks++;

                $(this).parent().find('.barcode_error').removeClass('hidden')
            } else {
                $(this).parent().find('.barcode_error').addClass('hidden')
            }
        } else if ((template_name == "TCL4" && zone == "CE") || (template_name == "TSL3" && zone == "CE") || (template_name == "THL3" && zone == "CE")) {
            var barcodecheck = checkjsbarcodeean($(item).val());
            if (barcodecheck == "false") {

                barcode_checks++;

                $(this).parent().find('.barcode_error').removeClass('hidden')
            } else {
                $(this).parent().find('.barcode_error').addClass('hidden')
            }
        } else if ((template_name == "TCL4" && zone == "UK") || (template_name == "TSL3" && zone == "UK") || (template_name == "THL3" && zone == "UK")) {
            var barcodecheck = checkjsbarcodeean($(item).val());
            if (barcodecheck == "false") {

                barcode_checks++;

                $(this).parent().find('.barcode_error').removeClass('hidden')
            } else {
                $(this).parent().find('.barcode_error').addClass('hidden')
            }
        } else if ((template_name == "TCL1" && zone == "UK") || (template_name == "TCL2" && zone == "UK") || (template_name == "TSL1" && zone == "UK") || (template_name == "THL1" && zone == "UK")) {
            var barcodecheck = checkjsbarcodeitf($(item).val());
            if (barcodecheck == "false") {

                barcode_checks++;

                $(this).parent().find('.barcode_error').removeClass('hidden')
            } else {
                $(this).parent().find('.barcode_error').addClass('hidden')
            }
        }

        barcode[i] = $(item).val();
        barcode_array.push($(item).val());
    });
    barcode = barcode.join(',');
    /** [ Create array of ce ratio case per carton ] */
    $('.ce_ratio_cpc').each(function (i, item) {
        size[i] = $(item).val();

    });
    size = size.join(',');
    /** [ Create array of ce ratio tcl1 count ] */
    $('.tot_qty_table').each(function (i, item) {
        count[i] = $(item).val() === '' ? 0 : $(item).val();

    });
    count = count.join(',');


    if ((template_name == "TCL1") || (template_name == "TCL2") || (template_name == "TSL1") || (template_name == "THL1")) {
        single_size = "";
    } else if ((template_name == "TCL4") || (template_name == "TSL3") || (template_name == "THL3")) {
        /** [ Create array of ce ratio tcl1 count ] */
        $('.ce_single_size').each(function (i, item) {
            single_size[i] = $(item).val();

        });
        single_size = single_size.join(',');
    }

    /** [ Create array of ce ratio orms tpn  and ce qr code ] */
    $('.orms_tpnd').each(function (i, item) {
        tpnd[i] = $(item).val();

        // qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
        qr_code[i] = po_number+','+barcode_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

    });
    tpnd = tpnd.join(','); 
    qr_code = qr_code.join('|');

    if (barcode_checks == 0) {
        $.ajax({
            url: base_url + "/admin/tesco/set-tesco-order-status",
            type: 'post',
            data: {
                _token: csrf_token,
                po_number: po_number,
                uk_style: uk_style,
                ce_style: ce_style,
                season_code: season_code,
                description: description,
                eqos: eqos,
                manufacture_date: manufacture_date,
                department: department,
                brand: brand,
                supplier: supplier,
                rfid: rfid,
                tag_at_source: tag_at_source,
                zone: zone,
                type: type,
                tpnd: tpnd,
                barcode: barcode,
                size: size,
                count: count,
                edit_id: edit_id,
                template_name: template_name,
                single_size: single_size,
                tesco_ocn: tesco_ocn,
                qr_code:qr_code
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html('Order updated Successfully');
                    showSnackbar();
                    $('.left-panel').removeClass('active');
                    location.reload()
                },
                409: function (data) {
                    $('#error_msg_alert').html('<strong>Order with same PO number and Style number already exists.</strong>')
                }
            },
            error: function (data) {

            },
            complete: function () {

            }
        })

    }


}

/**
 * Dynamically add rows
 * @param {*} obj 
 * @param {*} TemplateName 
 * @param {*} zone 
 * @param {*} moq 
 */
function addRowOs(obj, TemplateName, zone, moq) {
    var maxlength = $('#os_barcode_1').attr('maxlength')
    var i = parseInt($(obj).attr('data-row-id')) + 1;
    $(obj).attr('data-row-id', i);

    if ((TemplateName == "TCL1" && zone == "CE") || (TemplateName == "TCL2" && zone == "CE") || (TemplateName == "TSL1" && zone == "CE") || (TemplateName == "THL1" && zone == "CE")) {
        var qty = "Cases per carton";
        var th_td = "";
        var tpn_th = "Enter ORMS TPND";
        var tpn_len = "13";
        var unit_len = "5";
    } else if ((TemplateName == "TCL4" && zone == "CE") || (TemplateName == "TSL3" && zone == "CE") || (TemplateName == "THL3" && zone == "CE")) {
        var qty = "Case Size";
        var th_td = '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_' + i + '" name="os_single_size_' + i + '"  value="" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
        var tpn_th = "Enter ORMS TPND";
        var tpn_len = "13";
        var unit_len = "6";
    } else if ((TemplateName == "TCL4" && zone == "UK") || (TemplateName == "TSL3" && zone == "UK") || (TemplateName == "THL3" && zone == "UK")) {
        var qty = "Case Size";
        var th_td = '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_' + i + '" name="os_single_size_' + i + '"  value="" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
        var tpn_th = "Enter TPND";
        var tpn_len = "9";
        var unit_len = "4";
    } else if ((TemplateName == "TCL1" && zone == "UK") || (TemplateName == "TCL2" && zone == "UK") || (TemplateName == "TSL1" && zone == "UK") || (TemplateName == "THL1" && zone == "UK")) {
        var qty = "Units Per Carton";
        var th_td = "";
        var tpn_th = "Enter TPND";
        var tpn_len = "9";
        var unit_len = "4";
    }


    $('.edit-qty-table-div table tbody').append('<tr><td class="tpnd"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm orms_tpnd" required="" id="os_tpnd_' + i + '" name="os_tpnd_' + i + '" data-minlength="' + tpn_len + '" maxlength="' + tpn_len + '" data-required-error="Mandatory Field." data-minlength-error="Enter ' + tpn_len + ' digits." onkeypress="return isNumber(event)" value="" placeholder="' + tpn_th + '" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td class="barcode"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="' + maxlength + '" maxlength="' + maxlength + '" data-required-error="Mandatory Field." data-minlength-error="Enter ' + maxlength + ' digits." id="os_barcode_' + i + '" onkeyup="dynamicSVGTemplates()" name="os_barcode_' + i + '" onkeypress="return isNumber(event)" value="" placeholder="Enter ITF Barcode"><label class="barcode_error hidden" style="color:red;margin-left: -55px;position: absolute;">Enter Valid Barcode</label><div class="help-block with-errors"></div></div></td><td class="size"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="os_case_qty_' + i + '" name="os_case_qty_' + i + '" maxlength="' + unit_len + '" onkeypress="return isNumber(event)" value="" placeholder="Enter ' + qty + '" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>' + th_td + '<td> <div class="form-group has-feedback has-success"><input min="' + moq + '" onInput="checkLength(6,this)" type="number" class="form-control  input-sm tot_qty_table" value="" required placeholder="Quantity" data-min-error="Quantity Should be >= ' + moq + '."><div class="help-block with-errors"></div></div></td><td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRowOs(this)"></i></a></td></tr>');
    $('#create_tesco_order_status_form').validator('update');
}

function removeRowOs(obj) {
    $(obj).parent().parent().parent().remove();
    $('#create_tesco_order_status_form').validator('update');
}

function dynamicSVGTemplates() {
    $('.barcode_error').addClass('hidden')
    var template_name = $('#edit_template_name').val()
    var zone = $('#edit_zone').val()
    var template_type = $('#edit_template_type').val()
    var mySVG1 = document.getElementById("svg_tesco_os");
    var svgDoc1 = mySVG1.contentDocument;
    dynamicSVGTemplatescard(svgDoc1, template_name, zone, template_type)

}

function dynamicSVGTemplatescard(svgDoc, template_name, zone, type) {

    /** [ separate conditions for TSL1,TSL3 and other stickers ] */
    if (template_name === "TSL1" || template_name === "TSL3") {

        if (zone === "CE") {
            /**  [ if CE ratio template ] */
            if (type === "ratio") {
                var tpnd = $('#os_tpnd_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                var ce_ratio_itf = $('#os_barcode_1').val();

                /** [ Dynamically change svg short description ] */
                if (description != '') {
                    if (description.length > 24) {
                        if (/\S/.test(description.charAt(24))) {
                            //No Space
                            if (description.indexOf(" ") == "-1") {
                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                            } else {
                                svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                            }

                        } else {
                            //Space
                            svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                            svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                        }
                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                    } else {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                        svgDoc.getElementById("description1").innerHTML = "";
                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                    }
                } else {
                    svgDoc.getElementById("description").innerHTML = '';
                }
                /** [ Dynamically change svg CE ratio tpnd number ] */
                if (tpnd != '') {
                    svgDoc.getElementById("tpnd").innerHTML = tpnd;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }


                /** [ Dynamically change svg barcode ] */
                if (ce_ratio_itf.length === 14) {
                    jsbarcodetescotsl(ce_ratio_itf, 'barcode', svgDoc)
                    svgDoc.getElementById("barcode_number").innerHTML = ce_ratio_itf;
                } else {
                    svgDoc.getElementById("barcode").innerHTML = '';
                    svgDoc.getElementById("barcode_number").innerHTML = '';
                }
            } else {
                /**  [ if CE single template ] */
                var orms_tpn = $('#os_tpnd_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');

                var ce_single_ean = $('#os_barcode_1').val();

                /** [ Dynamically change svg CE single tpnd number ] */
                if (orms_tpn != '') {
                    svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }

                if (template_name === "TSL3") {

                    /** [ Dynamically change svg barcode ] */
                    if (ce_single_ean.length === 13) {
                        jsbarcodetescotslean(ce_single_ean, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = ce_single_ean;
                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                var size = $('#os_single_size_1').val();
                /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                if (size != '') {
                    $.ajax({
                        url: base_url + "/admin/tesco/get-tsl-size",
                        type: 'GET',
                        data: {
                            size: size,
                            zone: zone
                        },
                        tryCount: 0,
                        retryLimit: 2,
                        success: function (data) {
                            /** [ Dynamically change svg short description ] */
                            if (description != '' && data.size_chart.length != 0) {
                                var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;


                                if (str.length > 24) {
                                    if (/\S/.test(str.charAt(24))) {
                                        //No Space
                                        if (str.indexOf(" ") == "-1") {
                                            svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                        } else {
                                            svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                        }

                                    } else {
                                        //Space
                                        svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                        svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                    }
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                } else {
                                    svgDoc.getElementById("description").innerHTML = str.toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = "";
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                                }
                            } else if (data.size_chart.length != 0) {
                                svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
                                svgDoc.getElementById("description1").innerHTML = "";
                                svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                            } else if (description != '') {
                                if (description.length > 24) {
                                    if (/\S/.test(description.charAt(24))) {
                                        //No Space
                                        if (description.indexOf(" ") == "-1") {
                                            svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                        } else {
                                            svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                        }

                                    } else {
                                        //Space
                                        svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                        svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                    }
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                } else {
                                    svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = "";
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                                }
                            } else {
                                svgDoc.getElementById("description").innerHTML = '';
                                svgDoc.getElementById("description1").innerHTML = '';
                            }
                        },
                        error: function (errorXHR, errorStatus, errorThrown) {
                            this.tryCount++;
                            if (this.tryCount <= this.retryLimit) {
                                $.ajax(this);
                                return;
                            }
                        },
                        complete: function () {
                            /** [To make top menu active] */
                            $(function () {
                                var url = window.location;
                                $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                                $('.dynamic-menu a').filter(function () {
                                    return this.href == url;
                                }).parent('li').addClass('current-page').find('a').addClass('active');
                            });
                        }
                    })
                } else {
                    /** [ Dynamically change svg short description & size chart ] */
                    if (description != '') {
                        if (description.length > 24) {
                            if (/\S/.test(description.charAt(24))) {
                                //No Space
                                if (description.indexOf(" ") == "-1") {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                } else {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                }

                            } else {
                                //Space
                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                            }
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                        } else {
                            svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                            svgDoc.getElementById("description1").innerHTML = "";
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                        }
                    } else {
                        svgDoc.getElementById("description").innerHTML = '';
                    }
                }
            }
        } else {
            /**  [ if UK ratio template ] */
            if (type === "ratio") {
                var tpnd = $('#os_tpnd_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                var uk_ratio_itf = $('#os_barcode_1').val();

                /** [ Dynamically change svg UK ratio tpnd number ] */
                if (tpnd != '') {
                    svgDoc.getElementById("tpnd").innerHTML = tpnd;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                /** [ Dynamically change svg short description ] */
                if (description != '') {
                    if (description.length > 24) {
                        if (/\S/.test(description.charAt(24))) {
                            //No Space
                            if (description.indexOf(" ") == "-1") {
                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                            } else {
                                svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                            }

                        } else {
                            //Space
                            svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                            svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                        }
                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                    } else {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                        svgDoc.getElementById("description1").innerHTML = "";
                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                    }
                } else {
                    svgDoc.getElementById("description").innerHTML = '';
                }
                /** [ Dynamically change svg barcode ] */
                if (uk_ratio_itf.length === 14) {
                    jsbarcodetescotsl(uk_ratio_itf, 'barcode', svgDoc)
                    svgDoc.getElementById("barcode_number").innerHTML = uk_ratio_itf;
                } else {
                    svgDoc.getElementById("barcode").innerHTML = '';
                    svgDoc.getElementById("barcode_number").innerHTML = '';
                }
            } else {
                /**  [ if UK single template ] */
                var orms_tpn = $('#os_tpnd_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                var uk_single_ean = $('#os_barcode_1').val();
                /** [ Dynamically change svg UK single tpnd number ] */
                if (orms_tpn != '') {
                    svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                if (template_name === "TSL3") {

                    /** [ Dynamically change svg barcode ] */
                    if (uk_single_ean.length === 13) {
                        jsbarcodetescotslean(uk_single_ean, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = uk_single_ean;
                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                var size = $('#os_single_size_1').val();
                /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                if (size != '') {
                    $.ajax({
                        url: base_url + "/admin/tesco/get-tsl-size",
                        type: 'GET',
                        data: {
                            size: size,
                            zone: zone
                        },
                        tryCount: 0,
                        retryLimit: 2,
                        success: function (data) {
                            /** [ Dynamically change svg short description ] */
                            if (description != '' && data.size_chart.length != 0) {
                                var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;


                                if (str.length > 24) {
                                    if (/\S/.test(str.charAt(24))) {
                                        //No Space
                                        if (str.indexOf(" ") == "-1") {
                                            svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                        } else {
                                            svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                        }

                                    } else {
                                        //Space
                                        svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                        svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                    }
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                } else {
                                    svgDoc.getElementById("description").innerHTML = str.toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = "";
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                                }
                            } else if (data.size_chart.length != 0) {
                                svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
                                svgDoc.getElementById("description1").innerHTML = "";
                                svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                            } else if (description != '') {
                                if (description.length > 24) {
                                    if (/\S/.test(description.charAt(24))) {
                                        //No Space
                                        if (description.indexOf(" ") == "-1") {
                                            svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                        } else {
                                            svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                        }

                                    } else {
                                        //Space
                                        svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                        svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                    }
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                } else {
                                    svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = "";
                                    svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                                }
                            } else {
                                svgDoc.getElementById("description").innerHTML = '';
                                svgDoc.getElementById("description1").innerHTML = '';
                            }
                        },
                        error: function (errorXHR, errorStatus, errorThrown) {
                            this.tryCount++;
                            if (this.tryCount <= this.retryLimit) {
                                $.ajax(this);
                                return;
                            }
                        },
                        complete: function () {
                            /** [To make top menu active] */
                            $(function () {
                                var url = window.location;
                                $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                                $('.dynamic-menu a').filter(function () {
                                    return this.href == url;
                                }).parent('li').addClass('current-page').find('a').addClass('active');
                            });
                        }
                    })
                } else {
                    /** [ Dynamically change svg short description & size chart ] */
                    if (description != '') {
                        if (description.length > 24) {
                            if (/\S/.test(description.charAt(24))) {
                                //No Space
                                if (description.indexOf(" ") == "-1") {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                } else {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                }

                            } else {
                                //Space
                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                            }
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                        } else {
                            svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                            svgDoc.getElementById("description1").innerHTML = "";
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 65.1)");
                        }
                    } else {
                        svgDoc.getElementById("description").innerHTML = '';
                    }
                }
            }
        }
    } else {
        var po_number = $('#tesco_po_number').val();
        var uk_style = $('#tesco_uk_style').val();
        var ce_style = $('#tesco_ce_style').val();
        var tesco_eqos = $('#tesco_eqos').val();
        var tesco_ocn = $('#tesco_ocn').val();
        var supplier = $('#tesco_supplier option:selected').text();
        var department = $('#tesco_department option:selected').text();
        var brand = $('#tesco_brand option:selected').text();
        var rfid = $('#tesco_rfid option:selected').text();
        var tag_at_source = $('#tesco_tag_at_source option:selected').text();
        var season_code = $('#tesco_season_code').val();
        var manufacture_date = $('#tesco_manufacture_date').val();


        if (po_number != '') {
            svgDoc.getElementById("po_number").innerHTML = po_number.toUpperCase();
        } else {
            svgDoc.getElementById("po_number").innerHTML = '';
        }

        /** [ Dynamically change svg CE style reference number ] */
        if (ce_style != '') {
            svgDoc.getElementById("ce_style").innerHTML = ce_style.toUpperCase();
        } else {
            svgDoc.getElementById("ce_style").innerHTML = '';
        }
        /** [ Dynamically change svg UK style reference number ] */
        if (uk_style != '') {
            svgDoc.getElementById("uk_style").innerHTML = uk_style.toUpperCase();
        } else {
            svgDoc.getElementById("uk_style").innerHTML = '';
        }
        /** [ Dynamically change svg EQOS code ] */
        if (tesco_eqos != '') {
            svgDoc.getElementById("eqos").innerHTML = tesco_eqos.toUpperCase();
        } else {
            svgDoc.getElementById("eqos").innerHTML = '';
        }
        /** [ Dynamically change  svg supplier name ] */
        if ($('#tesco_supplier option:selected').val() != '') {
            svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace('&', '&amp;');
        } else {
            svgDoc.getElementById("supplier").innerHTML = '';
        }
        /** [ Dynamically change  svg department ] */
        if ($('#tesco_department option:selected').val() != '') {

            svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace('&', '&amp;');

            var str = department.trim();
            var justify_len = str.length;
            if (justify_len < 17) {
                svgDoc.getElementById("department").style.fontSize = "11px";
            } else {
                svgDoc.getElementById("department").style.fontSize = "9px";
            }
        } else {
            svgDoc.getElementById("department").innerHTML = '';
        }
        /** [ Dynamically change svg brand ] */
        if ($('#tesco_brand option:selected').val() != '') {
            svgDoc.getElementById("brand").innerHTML = brand.toUpperCase().replace('&', '&amp;');

            var str = brand.trim();
            var justify_len = str.length;
            if (justify_len < 15) {
                svgDoc.getElementById("brand").style.fontSize = "11px";
            } else {
                svgDoc.getElementById("brand").style.fontSize = "9px";
            }
        } else {
            svgDoc.getElementById("brand").innerHTML = '';
        }
        /** [ Dynamically svg RFID ] */
        if ($('#tesco_rfid option:selected').val() != '') {
            svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
        } else {
            svgDoc.getElementById("rfid").innerHTML = '';
        }
        /** [ Dynamically change svg tag at source ] */
        if ($('#tesco_tag_at_source option:selected').val() != '') {
            svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
        } else {
            svgDoc.getElementById("tag_at_source").innerHTML = '';
        }
        /** [ Dynamically change svg tag at source ] */
        if (season_code != '') {
            svgDoc.getElementById("season_code").innerHTML = season_code.toUpperCase();
        } else {
            svgDoc.getElementById("season_code").innerHTML = '';
        }
        /** [ Dynamically change svg manufacture date ] */
        if (manufacture_date != '') {
            svgDoc.getElementById("manufactured_date").innerHTML = manufacture_date.toUpperCase();
        } else {
            svgDoc.getElementById("manufactured_date").innerHTML = '';
        }
        if (zone === "CE") {
            /**  [ if CE ratio template ] */
            if (type === "ratio") {
                var pieces_in_ratio = $('#os_case_qty_1').val();
                var tpnd = $('#os_tpnd_1').val();
                var ce_ratio_itf = $('#os_barcode_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                /** [ Dynamically change svg CE pieces in ratio ] */
                if (pieces_in_ratio != '') {
                    svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                } else {
                    svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                }
                /** [ Dynamically change svg CE ratio tpnd number ] */
                if (tpnd != '') {
                    svgDoc.getElementById("tpnd").innerHTML = tpnd;
					if($('#qr_flag').val() == 1)
					{
						svgDoc.getElementById("tpnd_last_digit").innerHTML = tpnd.substr(tpnd.length - 3);
					}
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                if (template_name === "TCL1" || template_name === "THL1") {

                    /** [ Dynamically change svg barcode ] */
                    if (ce_ratio_itf != '' && ce_ratio_itf.length === 14) {

                        jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = ce_ratio_itf;

                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                /** [ Dynamically change svg short description ] */
                if (description != '') {
					if($('#qr_flag').val() == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (template_name === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (template_name === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(template_name === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = oswordWrap(description, 23);
							svgDoc.getElementById("description").innerHTML = str;
							if (template_name === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (template_name === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(template_name === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
							}
						}
					}
					else
					{
						/** [ Dynamically change svg short description ] */
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					}					
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
            } else {
                /**  [ if CE single template ] */
                var orms_tpn = $('#os_tpnd_1').val();
                var case_size = $('#os_case_qty_1').val();
                var ce_single_ean = $('#os_barcode_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                /** [ Dynamically change svg CE single tpnd number ] */
                if (orms_tpn != '') {
                    svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
					if($('#qr_flag').val() == 1)
					{
						svgDoc.getElementById("tpnd_last_digit").innerHTML = orms_tpn.substr(orms_tpn.length - 3);
					}
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                /** [ Dynamically change svg CE single case size ] */
                if (case_size != '') {
                    svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                } else {
                    svgDoc.getElementById("unit_per_case").innerHTML = '';
                }
                if (template_name === "THL3") {
                    /** [ Dynamically change svg barcode ] */
                    if (ce_single_ean != '' && ce_single_ean.length === 13) {

                        jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = ce_single_ean;

                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                var size = $('#os_single_size_1').val();
                /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                if (size != '') {
                    $.ajax({
                        url: base_url + "/admin/tesco/get-tsl-size",
                        type: 'GET',
                        data: {
                            size: size,
                            zone: zone
                        },
                        tryCount: 0,
                        retryLimit: 2,
                        success: function (data) {
                            /** [ Dynamically change svg short description ] */
                            if (description != '' && data.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 23)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 23);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136.2173)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150.5557)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136.2173)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150.5557)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 23)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 23);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136.2173)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150.5557)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136.2173)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150.5557)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
                        },
                        error: function (errorXHR, errorStatus, errorThrown) {
                            this.tryCount++;
                            if (this.tryCount <= this.retryLimit) {
                                $.ajax(this);
                                return;
                            }
                        },
                        complete: function () {
                            /** [To make top menu active] */
                            $(function () {
                                var url = window.location;
                                $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                                $('.dynamic-menu a').filter(function () {
                                    return this.href == url;
                                }).parent('li').addClass('current-page').find('a').addClass('active');
                            });
                        }
                    })
                } else {
                    /** [ Dynamically change svg short description & size chart ] */
                    if (description != '') {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                    } else {
                        svgDoc.getElementById("description").innerHTML = '';
                    }
                }

            }
        } else {

            /**  [ if UK ratio template ] */
            if (type === "ratio") {
                var pieces_in_ratio = $('#os_case_qty_1').val();
                var tpnd = $('#os_tpnd_1').val();
                var uk_ratio_itf = $('#os_barcode_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');

                /** [ Dynamically change svg UK pieces in ratio ] */
                if (pieces_in_ratio != '') {
                    svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                } else {
                    svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                }
                /** [ Dynamically change svg UK ratio tpnd number ] */
                if (tpnd != '') {
                    svgDoc.getElementById("tpnd").innerHTML = tpnd;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                if (template_name === "TCL1" || template_name === "THL1") {
                    /** [ Dynamically change svg barcode ] */
                    if (uk_ratio_itf != '' && uk_ratio_itf.length === 14) {

                        jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = uk_ratio_itf;

                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                /** [ Dynamically change svg short description ] */
                if (description != '') {
                    svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                } else {
                    svgDoc.getElementById("description").innerHTML = '';
                }
            } else {
                /**  [ if UK single template ] */
                var orms_tpn = $('#os_tpnd_1').val();
                var case_size = $('#os_case_qty_1').val();
                var uk_single_ean = $('#os_barcode_1').val();
                var description = $('#tesco_description').val().replace(/&/g, '&#038;');
                /** [ Dynamically change svg Uk single tpnd number ] */
                if (orms_tpn != '') {
                    svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                } else {
                    svgDoc.getElementById("tpnd").innerHTML = '';
                }
                /** [ Dynamically change svg Uk single case size ] */
                if (case_size != '') {
                    svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                } else {
                    svgDoc.getElementById("unit_per_case").innerHTML = '';
                }

                if (template_name === "THL3") {
                    /** [ Dynamically change svg barcode ] */
                    if (uk_single_ean != '' && uk_single_ean.length === 13) {

                        jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = uk_single_ean;

                    } else {
                        svgDoc.getElementById("barcode").innerHTML = '';
                        svgDoc.getElementById("barcode_number").innerHTML = '';
                    }
                }
                var size = $('#os_single_size_1').val();
                /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                if (size != '') {
                    $.ajax({
                        url: base_url + "/admin/tesco/get-tsl-size",
                        type: 'GET',
                        data: {
                            size: size,
                            zone: zone
                        },
                        tryCount: 0,
                        retryLimit: 2,
                        success: function (data) {
                            /** [ Dynamically change svg short description ] */
                            if (description != '' && data.size_chart.length != 0) {
                                svgDoc.getElementById("description").innerHTML = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
                            } else if (data.size_chart.length != 0) {
                                svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
                            } else if (description != '') {
                                svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                            } else {
                                svgDoc.getElementById("description").innerHTML = '';
                            }
                        },
                        error: function (errorXHR, errorStatus, errorThrown) {
                            this.tryCount++;
                            if (this.tryCount <= this.retryLimit) {
                                $.ajax(this);
                                return;
                            }
                        },
                        complete: function () {
                            /** [To make top menu active] */
                            $(function () {
                                var url = window.location;
                                $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                                $('.dynamic-menu a').filter(function () {
                                    return this.href == url;
                                }).parent('li').addClass('current-page').find('a').addClass('active');
                            });
                        }
                    })
                } else {
                    /** [ Dynamically change svg short description & size chart ] */
                    if (description != '') {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                    } else {
                        svgDoc.getElementById("description").innerHTML = '';
                    }
                }
            }
        }
    }
}

function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetesco(code, id, svgDoc) {

    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 3.28148,
        height: 202,
        fontSize: 30,
        flat: true,
        displayValue: false,
        valid: function (valid) {
            if (valid == false) {

            }
        }
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '30px');
    svg.setAttribute('font-family', 'frankgothcnbt');

    svg.setAttribute('width', '408.900px');
    svg.setAttribute('height', '328.02px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetescoean(code, id, svgDoc) {

    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 3.9260,
        height: 170,
        fontSize: 30,
        flat: true,
        displayValue: false
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '50px');
    svg.setAttribute('font-family', 'frankgothcnbt');

    svg.setAttribute('width', '412.25px');
    svg.setAttribute('height', '378.02px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetescotslean(code, id, svgDoc) {
    //		format: "ean13",
    //		width: 3.45,
    //		height: 125,
    //		fontSize: 35,
    //		flat: true,
    //		displayValue: false
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 3.9260,
        height: 167,
        fontSize: 30,
        flat: true,
        displayValue: false
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '50px');
    svg.setAttribute('font-family', 'frankgothcnbt');

    svg.setAttribute('width', '419.075px');
    svg.setAttribute('height', '275.02px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // img.src - contains base64 URI from svg
    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode 
 */


function jsbarcodetescotsl(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 3.28148,
        height: 199,
        fontSize: 30,
        flat: true,
        displayValue: false
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '30px');
    svg.setAttribute('font-family', 'frankgothcnbt');

    svg.setAttribute('width', '415.100px');
    svg.setAttribute('height', '328.02px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;
    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');
    /* Time to play ;) */
    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');
    // JavaScript (HTML) image
    img = new Image();
    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // img.src - contains base64 URI from svg
    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
 * This Function is used to check ITF barcode is valid or not.
 * 
 * @return boolean
 */

function checkjsbarcodeitf(code) {
    var return_value = "";
    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 2.50,
        height: 100,
        fontSize: 30,
        flat: true,
        valid: function (valid) {
            if (valid == false) {
                return_value = "false";
            } else {
                return_value = "true";
            }
        }
    });
    return return_value;
}

/**
 * This Function is used to check EAN barcode is valid or not.
 * 
 * @return boolean
 */

function checkjsbarcodeean(code) {
    var return_value = "";
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 3.45,
        height: 125,
        fontSize: 35,
        flat: true,
        valid: function (valid) {
            if (valid == false) {
                return_value = "false";
            } else {
                return_value = "true";
            }
        }
    });
    return return_value;
}

function enableArtworkEdit(tablename, rowid) {
    
    $.ajax({
        url: base_url + "/admin/tesco/enable-artwork-edit",
        type: 'post',
        data: {
            tablename: tablename,
            rowid: rowid,
            _token: csrf_token
        },
        beforeSend: function () {
            // $(".loader").show();
        },
        success: function (data) {
            $('#enable_edit').addClass('hidden')
            $('#disable_edit').removeClass('hidden')
        },
        error: function (data) {

        },
        complete: function () {}
    })
}

function disableArtworkEdit(tablename, rowid) {
    
    $.ajax({
        url: base_url + "/admin/tesco/disable-artwork-edit",
        type: 'post',
        data: {
            tablename: tablename,
            rowid: rowid,
            _token: csrf_token
        },
        beforeSend: function () {},
        success: function (data) {
            $('#enable_edit').removeClass('hidden')
            $('#disable_edit').addClass('hidden')
        },
        error: function (data) {
        },
        complete: function () {}
    })
}

function oswordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'  x='0' y='0'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }
        if (str.length < maxWidth)
            done = true;
    } while (!done);
    return res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;' x='0' y='12'>"+str+"</tspan>";
}
function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}
