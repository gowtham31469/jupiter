//Global Variable
var td_row = "";

/**
 * method to show the Orchestra order status table
 * @return {[type]} [description]
 */
function orderStatus() {
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.top-content').html('');
    $('.template-error').html('');
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/order-status",
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
                var path = base_url + "/admin/" + buyer + "/order-management/order-status";
                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_polybag_sticker_orders)))))));

                var newObj1 = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_polybag_sticker_orders)))))));

                $('.main-content').html("<button style='margin-bottom:10px' id='po-file-upload-button' class='btn btn-sm btn-primary pull-right' type='button'>Update PO</button>");
                newObj.sort(function (obj1, obj2) {
                    var date1 = obj1.OrderDate;
                    var date2 = obj2.OrderDate;
                    if (date1 > date2) return -1;
                    if (date1 < date2) return 1;
                    return 0;
                });
                $('.main-content').append(orchestraStatusTable(newObj, path));
                $('.top-content').html("")
                var uniqueNames = [];

                newObj1.sort(function (obj1, obj2) {
                    var date1 = obj1.OrderDate;
                    var date2 = obj2.OrderDate;
                    if (date1 > date2) return -1;
                    if (date1 < date2) return 1;
                    return 0;
                });

                // push uvc into array and show it in uvc selectio dropdown
                for (i = 0; i < newObj1.length; i++) {
                    if (uniqueNames.indexOf(newObj1[i].UVC) === -1) {
                        uniqueNames.push(newObj1[i].UVC);
                    }
                }

                var options = "<option>Select UVC</option>";
                for (i = 0; i < uniqueNames.length; i++) {
                    options += "<option>" + uniqueNames[i] + "</option>";
                }

                $('#po-file-upload-button').click(function () {
                    $('#po-file-upload-group').modal("show")
                    $('#uvc_select_div').html('<label>Select UVC</label><select id="uvc_selection" class="form-control selectpicker" data-live-search="true">' + options + '</select>')
                    $('#uvc_selection').selectpicker('refresh');
                    $('#new_po_div_status').addClass('hidden');
                    $('#new_po1_status').addClass('hidden');
                    $('#map_po_status').addClass('hidden');
                    $('#update_new_po_status').addClass('hidden');
                    $('#update_po_status').addClass('hidden');
                    $('#new_pos_status').addClass('hidden');
                    $('#map_po_div_status').addClass('hidden');
                    $('#new_po_no_status').val("")
                    $('#new_po_file_status').val("")
                    $('#exist_artwork_div_status').addClass('hidden')
                    $('#new_po_error_status').addClass('hidden')
                    $('#new_po_file_error_status').addClass('hidden')
                    $('#new_po_error_length_status').addClass('hidden');
                    $('#uvc_selection').change(function () {
                        if ($(this).val() != "Select UVC") {
                            $('#new_po_div_status').addClass('hidden');
                            $('#new_po1_status').addClass('hidden');
                            $('#map_po_status').addClass('hidden');
                            $('#update_new_po_status').addClass('hidden');
                            $('#update_po_status').addClass('hidden');
                            $('#new_pos_status').addClass('hidden');
                            $('#map_po_div_status').addClass('hidden');
                            $('#exist_artwork_div_status').removeClass('hidden')

                            var selected = $(this).find('option:selected');
                            var uvc = selected.val();
                            $.ajax({
                                url: base_url + "/admin/" + buyer + "/order-management/get-existing-label-orders-status-po",
                                type: "get",
                                dataType: "json",
                                data: {
                                    uvc: uvc
                                },
                                beforeSend: function () {

                                },
                                statusCode: {
                                    /** [Show response status based on HTTP status code] */
                                    200: function (data) {
                                        $('#check_error_status').addClass('hidden')
                                        $('#new_po_error_status').addClass('hidden')
                                        $('#new_po_file_error_status').addClass('hidden')
                                        $('#new_po_error_length_status').addClass('hidden');
                                        $('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
                                        var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_polybag_sticker_orders)))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var old_po = ""
                                        var new_po_div_status = 0;
                                        $(newObj).each(function (index1, value1) {

                                            if (value1.po_number != "" && value1.po_number != null) {
                                                if (old_po != value1.po_number) {
                                                    options += "<option>" + value1.po_number + "</option>";
                                                }
                                                old_po = value1.po_number
                                                coun++;
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">' + value1.TemplateName + '</li>')
                                                new_po_div_status++;

                                            } else {
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">' + value1.TemplateName + '</li>')
                                            }

                                        });

                                        $('.list-group.checked-list-box .list-group-item').each(function () {

                                            // Settings
                                            var $widget = $(this),
                                                $checkbox = $('<input type="checkbox" class="hidden" />'),
                                                color = ($widget.data('color') ? $widget.data('color') : "primary"),
                                                style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
                                                settings = {
                                                    on: {
                                                        icon: 'glyphicon glyphicon-check'
                                                    },
                                                    off: {
                                                        icon: 'glyphicon glyphicon-unchecked'
                                                    }
                                                };
                                            $widget.css('cursor', 'pointer')
                                            $widget.append($checkbox);

                                            // Event Handlers
                                            $widget.on('click', function () {
                                                $checkbox.prop('checked', !$checkbox.is(':checked'));
                                                $checkbox.triggerHandler('change');
                                                updateDisplay();
                                            });
                                            $checkbox.on('change', function () {
                                                updateDisplay();
                                            });


                                            // Actions
                                            function updateDisplay() {
                                                var isChecked = $checkbox.is(':checked');

                                                // Set the button's state
                                                $widget.data('state', (isChecked) ? "on" : "off");

                                                // Set the button's icon
                                                $widget.find('.state-icon')
                                                    .removeClass()
                                                    .addClass('state-icon ' + settings[$widget.data('state')].icon);

                                                // Update the button's color
                                                if (isChecked) {
                                                    $widget.addClass(style + color + ' active');
                                                } else {
                                                    $widget.removeClass(style + color + ' active');
                                                }
                                            }

                                            // Initialization
                                            function init() {

                                                if ($widget.data('checked') == true) {
                                                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                                                }

                                                updateDisplay();

                                                // Inject the icon if applicable
                                                if ($widget.find('.state-icon').length == 0) {
                                                    $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
                                                }
                                            }
                                            init();
                                        });

                                        $('#get-checked-data').on('click', function (event) {
                                            event.preventDefault();
                                            var checkedItems = {},
                                                counter = 0;
                                            $("#check-list-box li.active").each(function (idx, li) {
                                                checkedItems[counter] = $(li).text();
                                                counter++;
                                            });
                                            $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
                                        });


                                        //company wise
                                        $('.list-group-item').click(function () {
                                            $count_li = 0;
                                            $('.list-group li').each(function (key, val) {
                                                if ($(this).hasClass('active')) {
                                                    $count_li++;
                                                }
                                            });
                                            if ($count_li > 0) {
                                                var company_id = $(this).data('company');
                                                $('.list-group li').each(function (key, val) {
                                                    if ($(this).data('company') != company_id) {
                                                        $(this).addClass('hidden');
                                                    }
                                                })
                                            } else {
                                                $('.list-group li').each(function (key, val) {
                                                    $(this).removeClass('hidden');
                                                })
                                            }

                                        })


                                        $('#po_select_div').html('<label>Select PO</label><select id="select_po_status" class="form-control">' + options + '</select>')
                                        if (coun == 0) {
                                            $('#map_po_status').addClass('hidden');
                                            $('#new_po1_status').addClass('hidden');
                                            $('#new_po_div_status').removeClass('hidden');
                                            $('#new_pos_status').removeClass('hidden');
                                        } else {
                                            $('#map_po_status').removeClass('hidden');
                                            $('#new_po1_status').removeClass('hidden');
                                            $('#new_pos_status').addClass('hidden');
                                        }
                                        $('#new_po1_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {

                                                $('#update_new_po_status').removeClass('hidden')
                                                $('#update_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#new_po_div_status').removeClass('hidden')
                                                $('#map_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
                                        $('#map_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) { 
                                                $('#update_po_status').removeClass('hidden')
                                                $('#update_new_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#map_po_div_status').removeClass('hidden')
                                                $('#new_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')
                                                $('#check_error_status').addClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
                                        $('#update_new_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {

                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');
                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                            url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                orderStatus()
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }

                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });

                                        $('#new_pos_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {
                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');

                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                            url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                orderStatus()
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }
                                                }

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });
                                        $('#update_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')

                                                if ($('#select_po_status').val() != "Select PO") {
                                                    var a = [];
                                                    $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                        if (this.checked) {
                                                            a.push($(this).parent().attr('data-id'));
                                                        }
                                                    });
                                                    var aa = a.join(',');
                                                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                    $.ajax({
                                                        url: base_url + "/admin/" + buyer + "/order-management/map-label-orders-po",
                                                        type: "Post",
                                                        dataType: "json",
                                                        data: {
                                                            _token: csrf_token,
                                                            id: aa,
                                                            po: $('#select_po_status').val()
                                                        },
                                                        beforeSend: function () {

                                                        },
                                                        success: function (data) {
                                                            orderStatus()
                                                            $('#po-file-upload-group').modal('hide')
                                                            $('.snackbar_span').html('PO Updated Successfully');
                                                            showSnackbar();
                                                        },
                                                        error: function (data) {

                                                        },
                                                        complete: function () {
                                                            $(".loader").hide();
                                                        }
                                                    })
                                                } else {
                                                    $('#select_error_status').removeClass('hidden')
                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });

                                        if (newObj.length == new_po_div_status) {
                                            $('#new_po_div_status').addClass('hidden')
                                            $('#new_po1_status').addClass('hidden')
                                            $('#map_po_status').addClass('hidden')
                                        }

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
                    });

                });

                var $tableSel = $('#datatable-orchestra-orders');
                $tableSel.DataTable({
                    "pageLength": 25,
                    "order": [
                        [7, "desc"]
                    ],
                    "aoColumns": [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        {
                            "sType": "date-uk"
                        },
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                    ],
                    initComplete: function () {
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Customer</option></select></div><div class="col-md-3"><select class="selectpicker" id="styleNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectTemplate"> <option disabled selected>Select Template Name</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');
                        //For Custom Filters
                        this.api().columns().every(function (i) {
                            var column = this;
                            if (i === 0 || i === 5 || i === 3) {
                                var select;
                                switch (i) {
                                    case 0:
                                        select = $('#styleNumber')
                                        break;
                                    case 5:
                                        select = $('#supplierName')
                                        break;
                                    case 3:
                                        select = $('#selectTemplate')
                                        break;
                                }
                                select.on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                                select.append('<option value=""> </option>');
                                column.data().unique().sort().each(function (d, j) {
                                    cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
                                    select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
                                });
                            }
                        });
                    }
                });

                $("#reset").on("click", function () {
                    $('#styleNumber').val('').change();
                    $('#supplierName').val('').change();
                    $('#selectTemplate').val('').change();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                    $('input[name="daterange"]').val('Filter by Order Date');
                    $('input[name="daterange"]').datepicker("refresh");
                    $('.selectpicker').selectpicker('val', 1);
                    $('.selectpicker').selectpicker('refresh');

                });

                $('.selectpicker').selectpicker();
                $('input[name="daterange"]').daterangepicker({

                    },
                    function (start, end, label) {
                        $.fn.dataTableExt.afnFiltering.length = 0;
                        $tableSel.dataTable().fnDraw();
                        filterByDate(6, start, end); // We call our filter function
                        $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
                    });

                $('input[name="daterange"]').val('Filter by Order Date');


                // Clear the filter. Unlike normal filters in Datatables,
                // custom filters need to be removed from the afnFiltering array.
                $('.cancelBtn').on('click', function (e) {
                    e.preventDefault();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                });
                $('.pi-view').tooltip('show')
                $('.pi-view').tooltip('toggle')
                $('.po-view').tooltip('show')
                $('.po-view').tooltip('toggle')
                $("#datatable-orchestra-orders_filter input").addClass('form-control input-sm');
                $("#datatable-orchestra-orders_filter input").attr('placeholder', 'Data Search');
                $('#datatable-orchestra-orders_length select').addClass('form-control input-sm');
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
 * [method append data to orchestraStatusTable ]
 * @param  {[type]} data [description]
 * @param  {[type]} path [description]
 * @return {[type]}      [description]
 */
function orchestraStatusTable(data, path) {
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn) {
        if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uvc" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty") {
            if (key == "TemplateName") {
                th += "<th style='text-align: center;'>Reference No</th>";
            } else {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }
        }

    }
    $.each(data, function (index, value) {
        var action_status = false;
        tr += '<tr class="trClass" id="' + value.tableName + '_' + value.tableID + '">';
        for (var key in columnsIn) {
            if (key != "id") {

                if (key == "PO Number" && value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                    var po_file = value['po_file'];
                    let customer_po_number = value[key] === null?'':value[key];
                    tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="orchestra-po" id="orchestra-po-'+value.tableID+'" class="form-control input-sm orchestra-po" placeholder="Enter PO" maxlength="11" value="'+customer_po_number+'"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default orchestra-po-submit" onclick=orchestraSetPO(this,"' + value.tableID + '","' + value.tableName + '","' + po_file + '")><span class="fa fa-check"></span></button></span></div></div></td>';
                } else if (key == "OrderDate") {
                    if (value['is_order_count_empty'] == 0) {
                        var orderDate = value[key];
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

                } else if (key == "UVC") {
                    var order_flag = value.order_flag;
                    var span_style = '';
                    if (order_flag == "reOrder") {
                        span_style = '<span title="Additional Quantity" class="batch-additional">A</span>'
                    }
                    if (order_flag == "repeatOrder") {
                        span_style = '<span title="Repeat Order" class="batch-reorder">R</span>'
                    }
					var style_no = value.UVC;
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
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (po_required == 1 && pi_required == 0) {

                            tr += '<td align="center"><a href="javascript:void(0)" class="po-style" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
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
    return '<table id="datatable-orchestra-orders" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


/**
 * [orchestraSetPO for updating orchestra customer po]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function orchestraSetPO(obj, id, tableName, po_file) {
    $('.orchestra-po').css('border-color', '');
    var po_number = $(obj).closest('tr').find('.orchestra-po').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    if (po_number != "") {
        $.ajax({
            url: base_url + "/admin/orchestra/order-management/set-po-number",
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
                    $ponumber = $(obj).closest('tr').find('.orchestra-po').val();
                    if ($(obj).closest('tr').find('.po_class').text() == "PO Pending" && po_file != "null") {
                        $(obj).closest('tr').find('.po_class').html("PO Uploaded")
                        $(obj).closest('tr').find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                    }
                    showSnackbar();
                    if (po_number == "") {
                        $(obj).closest('tr').find('.orchestra-po').attr('disabled');
                        $(obj).closest('tr').find('.orchestra-po').attr('placeholder', "Enter PO");
                    }

                },
                403: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
                },
                400: function () {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
                }
            },
            error: function (data) {
            },
            complete: function () {
                $(".loader").hide();
            }
        })
    } else {
        $(obj).closest('tr').find('.orchestra-po').focus();
        $(obj).closest('tr').find('.orchestra-po').css('border-color', '#F44336');
        $('.snackbar_span').html('Please enter a valid PO number');
        showSnackbar();
    }
}
/**
 * [orchestraPOUpload for customer po file upload]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function orchestraPOUpload(obj, id, po_file, tableName) {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/get-erp-status",
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
            if (po_file === "null") {
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


function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}