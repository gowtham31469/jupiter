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
                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))))))));

                var newObj1 = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))))))));

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
                                        var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders)))))));
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
                                error: function (data) {},
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
        error: function (data) {},
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
            }else if (key == "BuyerPONo") {
                th += "<th style='text-align: center;'>BuyerPO No</th>";
            }else if (key == "ArtworkType") {
                th += "<th style='text-align: center;'>Artwork Type</th>";
            }else if (key == "SupplierName") {
                th += "<th style='text-align: center;'>Supplier Name</th>";
            } else if (key == "OrderDate") {
                th += "<th style='text-align: center;'>Order Date</th>";
            } else if (key == "DeliveryDate") {
                th += "<th style='text-align: center;'>Delivery Date</th>";
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
                    let customer_po_number = value[key] === null ? '' : value[key];
                    tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="orchestra-po" id="orchestra-po-' + value.tableID + '" class="form-control input-sm orchestra-po" placeholder="Enter PO" maxlength="11" value="' + customer_po_number + '"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default orchestra-po-submit" onclick=orchestraSetPO(this,"' + value.tableID + '","' + value.tableName + '","' + po_file + '")><span class="fa fa-check"></span></button></span></div></div></td>';
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
                    tr += '<td align="center" data-id="' + value.tableID + '" data-artwork="' + value.ArtworkType + '" data-season="' + value.Season + '" data-uvc="' + value.UVC + '" data-table="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" onclick="updateArtworkOrc(this)">' + span_style + '' + value.UVC + '</a></td>';
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
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (po_required == 1 && pi_required == 0) {

                            tr += '<td align="center"><a href="javascript:void(0)" class="po-style" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (pi_required == 1 && po_required == 0) {

                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a></td>';
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
const orchestraSetPO = (obj, id, tableName, po_file) => {
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
            error: function (data) {},
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
const orchestraPOUpload = (obj, id, po_file, tableName) => {
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
                } else {
                    $('.update-po-file').show();
                    $('#error_msg_span').html('');
                }
            } else {
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

function updateArtworkOrc(obj) {

    var id = $(obj).parent().attr('data-id');
    var table_name = $(obj).parent().attr('data-table');
    var TemplateName = $(obj).parent().attr('data-template');
    var season = $(obj).parent().attr('data-season');
    var uvc = $(obj).parent().attr('data-uvc');
    var artwork = $(obj).parent().attr('data-artwork');
	var at = artwork.replace(" ", "");

    
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/edit-artwork",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            id: id,
            uvc: uvc,
            season: season,
			at:at
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
				$('.top-content').html('<input type="hidden" value="' + data.order_details[0].UVC + '" id="uvc_id"><input type="hidden" value="' + data.order_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.order_details[0].Season_Code + '" id="season_code"><input type="hidden" value="'+data.customers[0].id+'" id="customer_id">');
				if(at == "HangTag")
				{
					hangtagmodalOs(data)
				}
				else if(at == "SpecialTag")
				{
					specialtagsOs(data)
				}
				else if(at == "MainLabel")
				{
					mainlabelsOs(data)
				}
				else if(at == "WashCareLabel")
				{
					washcarelabelsOs(data, "")
				}
				else if(at == "SizeLabel")
				{
					sizelabelsOs(data)
				}
				else if(at == "HeatTransfer")
				{
					heatTransferOs(data)
				}
				$('.left-panelos').addClass('active');
				$('.right-panelos').addClass('active');
    			$('.left-panelos').show();
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

    

}


function hangtagmodalOs(data) {
    var order_details = data.order_details;
    var tr = "";
    $qu = 0;
    if (data.orchestra_hang_tag_orders != "") {
       

        $template_front_id = data.orchestra_hang_tag_orders[0].template_front_id;
        $exist_id = data.orchestra_hang_tag_orders[0].id;
        
        var str=data.orchestra_hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="hidden";
		$save_button_class="hidden";
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
    }
    var ttots = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_hang_tag_orders != "") {

            $hang_tag_count = data.orchestra_hang_tag_orders_qty[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablehang" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (data.orchestra_hang_tag_orders != "") {
            $hang_cm = data.orchestra_hang_tag_orders[0].cm;
			$hang_in = data.orchestra_hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		var sizebox='<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm onesizecm" value="'+$hang_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label>Enter Size in inches</label><input onkeypress="return checkNumbersOnly(event)" type="text" class="form-control input-sm onesizein" maxlength="3" value="'+$hang_in+'" placeholder="Enter Size in inches"><div class="help-block with-errors"></div></div>';
	}
	else
	{
		var sizebox="";
	}
    
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="left-panelos col-md-8"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div>'+sizebox+'</div><div class="col-md-4"><h4>Front</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections text-center" ><input type="hidden" value="' + data.orchestra_template_id + '" id="hang_tag_id"><div class="sample_back"></div><label style="margin-top:15px">Hang Tag Print Date : ' + data.order_details[0].HangTag_Print_Date + '</label><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagehang" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + data.total_qty + '</td><td class="text-center tot_qty_tablehang_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_hang_button" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="hangclose">Close</button><button type="button" class="btn btn-primary '+$print_button_class+'" id="print_hang_button" onclick=printHangDiv("svg2","'+data.order_details[0].Licence_Code+'")>Print</button> </div> </div>  <div class="backdrop"></div></form>');


//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
            }
        })
        $('#hangtagform .tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
    });
	
	$('.extra_percentagehang').keyup(function () {
		
        
        var tot_qty_tablehang_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablehang').val(tot_qty);
                if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                    tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablehang').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text("0");
        }

    })
	
	$('#hangtagform .onesizein').keyup(function () {
		
		var inches=$(this).val();
        var mySVG = document.getElementById("svg2");
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("in").innerHTML = inches;

		
    });
	$('#hangtagform .onesizecm').keyup(function () {
		
		var cm=$(this).val();
        var mySVG = document.getElementById("svg2");
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("cm").innerHTML = cm;
		
    });

    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panelos").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panelos").removeClass('active');
        $("#hangtagform .right-panelos").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
		//$('.top-content').html('');
    })

    $('#tree1').jstree({
        'core': {
            'data': data.treefinalData
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
    });
    $('#expand').bind("click", function () {
        $('#tree1').jstree("open_all");
    });
    $('#collapse').bind("click", function () {
        $('#tree1').jstree("close_all");
    });
    var to = false;
    $('#tree1_q').keyup(function () {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function () {
            var v = $('#tree1_q').val();
            $('#tree1').jstree(true).search(v);
        }, 250);
    });
    //Show Template when tree loads 
    $("#tree1").on("loaded.jstree", function (e, data) {
        $('#tree1').jstree(true).select_node($template_front_id);
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
                            svgDoc.getElementById("theme").innerHTML = order_details[0].Theme;
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "TU";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm;
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-");

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-")+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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
    //Show Template when Click tree node 
    $('#tree1').on('activate_node.jstree', function (e, data) {
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
                            svgDoc.getElementById("theme").innerHTML = order_details[0].Theme;
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "TU";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm;
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-");

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-")+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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

    $('#hangtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setHangTagStatus()
        }
    })


    selectpicker()
    $('.template-select.bootstrap-select').on('changed.bs.select', function () {
        if ($('.template-select option:selected').val() != "") {
            $('.sample-artwork').html('<object id="svg1" data="' + $('.template-select option:selected').val() + '" style="width:233px;height:500px" type="image/svg+xml"></object>')
            var mySVG = document.getElementById("svg1");
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc = mySVG.contentDocument;
                var pri = data.order_details[0].PrixEurope.split(",")

                if (pri.length == 1) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = data.order_details[0].PrixEurope;
                    svgDoc.getElementById("price2").innerHTML = "";
                } else if (pri.length == 2) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = pri[0];
                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                }
                var pri1 = data.order_details[0].PrixClubEurope.toString().split(",")
                if (pri1.length == 1) {
                    svgDoc.getElementById("club_price1").innerHTML = data.order_details[0].PrixClubEurope;
                    svgDoc.getElementById("club_price2").innerHTML = "";
                } else if (pri1.length == 2) {
                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                }

            }, false);
            $('.sample-artwork').css('background', '#fff');
        } else {
            $('.sample-artwork').html('');
            $('.sample-artwork').css('background', '#eee');
        }

    });
}

function specialtagsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_special_tag_orders != "") {

      
        $template_id = data.orchestra_special_tag_orders[0].template_id;
        $exist_id = data.orchestra_special_tag_orders[0].id;
        $special_tag_count=data.orchestra_special_tag_orders[0].final_qty;
        
        var str=data.orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $special_tag_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Special Tag Count" type="number" required><div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#specialtagform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmespecial").click(function () {
        // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panelos").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panelos").removeClass('active');
        $("#specialtagform .right-panelos").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
		//$('.top-content').html('');
    })



    treestructurespecial(data.treeviewspecial, "tree2", $template_id);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus()
        }
    })
}

function heatTransferOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_heat_transfer_orders != "") {

      
        $template_id = data.orchestra_heat_transfer_orders[0].template_id;
        $exist_id = data.orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=data.orchestra_heat_transfer_orders[0].final_qty;
        
        var str=data.orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = data.orchestra_heat_transfer_orders_qty[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tableheat" value="' + $heat_transfer_count + '" required placeholder="Heat Transfer Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
        $qu++;
    });
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="heat_transferform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="heat_transfer_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treeht_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeht" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentageheat" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + data.total_qty + '</td><td class="text-center tot_qty_tableheat_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="heat_transferclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
            }
        })
        $('#heat_transferform .tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
    });
	
	$('.extra_percentageheat').keyup(function () {
		
        
        var tot_qty_tableheat_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tableheat').val(tot_qty);
                if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                    tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tableheat').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text("0");
        }

    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panelos").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panelos").removeClass('active');
        $("#heat_transferform .right-panelos").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
		//$('.top-content').html('');
    })



    treestructureheat_transfer(data.treeviewheat_transfer, "treeht", $template_id);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus()
        }
    })
}

function sizelabelsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_size_label_orders != "") {

      
        $template_id = data.orchestra_size_label_orders[0].template_id;
        $exist_id = data.orchestra_size_label_orders[0].id;
        $size_label_count=data.orchestra_size_label_orders[0].final_qty;
        
        var str=data.orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_size_label_orders != "") {

            $size_label_count = data.orchestra_size_label_orders_qty[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablesize" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
        $qu++;
    });
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="size_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="size_label_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treesl_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treesl" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="size_label_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagesize" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + data.total_qty + '</td><td class="text-center tot_qty_tablesize_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_size_label_button" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="size_labelclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
            }
        })
        $('#size_labelform .tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
    });
	
	 $('.extra_percentagesize').keyup(function () {
		
        
        var tot_qty_tablesize_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablesize').val(tot_qty);
                if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                    tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablesize').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text("0");
        }

    })
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panelos").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panelos").removeClass('active');
        $("#size_labelform .right-panelos").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
		//$('.top-content').html('');
    })



    treestructuresize_label(data.treeviewsize_label, "treesl", $template_id);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus()
        }
    })
}

function mainlabelsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_main_label_orders != "") {

      
        $template_id = data.orchestra_main_label_orders[0].template_id;
        $exist_id = data.orchestra_main_label_orders[0].id;
        $main_label_count=data.orchestra_main_label_orders_qty[0].quantity;
        
        var str=data.orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $main_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#main_labelform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmemain_label").click(function () {
        // $('.sample-artwork').html("")
        $("#main_labelform .backdrop").toggleClass('active');
        $("#main_labelform .right-panelos").toggleClass('active');
        $('#main_labelform .scalable').toggleClass('active');
        $("#main_labelform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #main_labelclose").click(function () {
        $("#main_labelform .left-panelos").removeClass('active');
        $("#main_labelform .right-panelos").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
		//$('.top-content').html('');
    })



    treestructuremain_label(data.treeviewmain_label, "treeml", $template_id);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus()
        }
    })
}


function washcarelabelsOs(data, dataid) {
    var washing_option = "<option value=''>Select Option</option>";
    var whitening_option = "<option value=''>Select Option</option>";
    var dryer_option = "<option value=''>Select Option</option>";
    var ironing_option = "<option value=''>Select Option</option>";
    var cleaning_option = "<option value=''>Select Option</option>";
    var made_option = "<option value=''>Select Option</option>";
    var instruction_option = "";
    var parts_option = "<option>Select Option</option>";
    var composition_option = "<option>Select Option</option>";


    $.each(data.orchestra_countries, function (index, value) {
        made_option += "<option data-id='" + value.id + "'  data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
        instruction_option += "<option data-id='" + value.id + "' data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german  + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german  + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
        if (value.category_id == 1) {
            washing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        }

    });

	

    var tr = "";
    $qu = 0;
    var ttots = 0;
    if (data.orchestra_wash_care_label_orders != "") {
        if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
        if (data.orchestra_wash_care_label_orders.length > dataid1) {
          
            $exist_id = data.orchestra_wash_care_label_orders[dataid1].id;
            if(data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment != "")
            {
                $comment = "<h4>Comments :</h4><p>"+data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment+"</p><hr>";
            }
            else
            {
                $comment = "";
            }
            
            var str=data.orchestra_wash_care_label_orders[dataid1].status;
			if(str == "Order Approved")
			{
				$save_button_class = "hidden";
			}
			else
			{
				$save_button_class = "";
			}
            $status=str.replace(/ /g, "");
            $status_text=str;
            $wcl_name=data.orchestra_wash_care_label_orders[dataid1].template_name;
			$template_id = data.orchestra_wash_care_label_orders[dataid1].template_id;
			
			$print_button_class = "hidden";
        } else {
            
            $exist_id = "";
            $comment = "";
            $status="";
            $status_text="";
            $wcl_name="";
			$template_id = "122";
			$print_button_class = "hidden";
			$save_button_class = "";
        }

    } else {
        
        $exist_id = "";
        $comment = "";
        $status="";
        $status_text="";
        $wcl_name="";
		$template_id = "122";
		$print_button_class = "hidden";
		$save_button_class = "";
    }
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }
            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $wash_label_count = data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
            } else {
                $wash_label_count = "";
            }

        } else {
            $wash_label_count = "";
        }


        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + (value.Size).replace("ANS", '') + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)" type="number" min="'+data.wash_moq+'" class="form-control input-sm tot_qty_tablewash" value="' + $wash_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors"></div></div></td></tr>';

        $qu++;
        ttots += parseInt($wash_label_count);

    });
	
	if(data.order_details[0].Size == "TAILLE UNIQUE")
	{
		if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
		if (data.orchestra_wash_care_label_orders != "") {
			 if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $wash_cm = data.orchestra_wash_care_label_orders[dataid1].cm;
				$wash_in = data.orchestra_wash_care_label_orders[dataid1].in;
            } else {
                $wash_cm = ""; 
				$wash_in = "";
            }
			
            
        } else {
            $wash_cm = "";
			$wash_in = "";
        }
		var sizebox='<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm washonesizecm" value="'+$wash_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label>Enter Size in inches</label><input onkeypress="return checkNumbersOnly(event)" type="text" class="form-control input-sm washonesizein" maxlength="3" value="'+$wash_in+'" placeholder="Enter Size in inches"><div class="help-block with-errors"></div></div>';
	}
	else
	{
		var sizebox="";
	}
	
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
	//alert(data.order_details[0].Licence_Code)
	if(data.order_details[0].Licence_Code === 'DI')
	{
		//<label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree_wash'+dataid+'_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div id="tree_wash'+dataid+'"></div>
		var treestruct='';
		var disney_flag="1";
	}
	else
	{
		var treestruct="";
		var disney_flag="0";
	}

    $('.top-content').append('<form id="washcarelabelform' + dataid + '" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id' + dataid + '"><div class="left-panelos col-md-8"> <div class="div-max"> <div class="col-md-3"> <h4>Options</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">WCL Name</label><input type="text" class="form-control input-sm" required="" placeholder="WCL Name" value="' + $wcl_name + '" id="wcl_name' + dataid + '"><div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select data-live-search="true" class="form-control selectpicker input-sm washing_select' + dataid + '" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select data-live-search="true" class="form-control selectpicker input-sm whitening_select' + dataid + '" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select data-live-search="true" class="form-control selectpicker input-sm dryer_select' + dataid + '" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select data-live-search="true" class="form-control selectpicker input-sm ironing_select' + dataid + '" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select data-live-search="true" class="form-control selectpicker input-sm cleaning_select' + dataid + '" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div>'+treestruct+'  </div></div> <div class="col-md-5"> <h4>Art Work</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span> <hr> <div class="section-1 sections"> <div class="sample-artwork"><object style="height: calc( 100vh - 125px );"  id="washcare_svg_front' + dataid + '" data="' + data.orchestra_wash_template_front + '" type="image/svg+xml"></object> <object style="height: calc( 100vh - 125px );margin-left: 10px;"  id="washcare_svg_back' + dataid + '"  data="' + data.orchestra_wash_template_back + '" type="image/svg+xml"></object><object style="height: calc( 100vh - 125px );width: 145px;margin-left: 10px;"  id="washcare_svg_middle' + dataid + '" data="' + data.orchestra_wash_template_middle + '" type="image/svg+xml"></object></div><input type="hidden" value="' + disney_flag + '" id="wash_label_id"></div> </div> <div class="col-md-4 " style="padding-left:0px"> <h4>Options</h4> <hr>'+sizebox+' <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select data-live-search="true" class="form-control selectpicker input-sm made_in_select' + dataid + '" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select data-live-search="true" class="form-control selectpicker input-sm care_instruction_select' + dataid + '" required multiple>' + instruction_option + '</select><div class="help-block with-errors"></div></div> <div class="section-1 sections"> <h5>Compositions</h5> <select class="selectpicker composition_select' + dataid + '"  data-live-search="true">  ' + composition_option + '  </select><p class="hidden" style="color:red;float:right" id="composition_error_required' + dataid + '">Please Select Composition</p> </div> <h5>Percentage</h5> <input type="number" min="1" max="100" class="form-control input-sm composition_percent' + dataid + '"><p class="hidden" style="color:red;text-align:right" id="percentage_error_required' + dataid + '">Please Enter Percentage</p>  <button type="button" class="btn  btn-primary pull-right input-sm add_to_list' + dataid + '" onclick="add_to_list(' + dataid + ')">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty' + dataid + '"></tbody></table><p id="wash_composition_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Select composition</p><p id="wash_composition_page_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Add Composition to Page</p><div style="height: 30px;"><button class="btn  btn-primary pull-right input-sm add_to_composition' + dataid + '"  type="button" disabled onclick="add_to_composition(' + dataid + ')">Add To Composition</button></div><div><h4>Compositions :</h4><div><table class="table table-striped table-hover table-condensed"><thead><tr><th>Composition Name</th><th>Parts</th><th>Actions</th></tr></thead><tbody id="composition_remove_qty' + dataid + '"></tbody></table><div><p class="hidden composition_error_hidden" style="color:red;text-align: right;margin-right: 10px;">Please select parts</p></div><div><button class="btn  btn-primary pull-right input-sm add_to_page' + dataid + '"  style="position:relative;" type="button" onclick="add_to_page(' + dataid + ')">Add To Page</button></div></div></div><div class="parts_none'+dataid+'" style="display:none"><select>' + parts_option + '</select></div> </div> </div> </div></div> <div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for <span class="washlabelnameqty' + dataid + '"> WashCare Label</span></h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_'+dataid+'" style="font-size:11px"> <thead> <tr> <th>UVC TCO#</th> <th>Size in ANS</th> <th>Barcode</th> <th>Qty.</th> <th><input type="text" class="form-control input-sm extra_percentagewash" onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th> </tr></thead> <tbody style="font-size: 11px">' + tr + ' <tr class="strong"> <td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center tot_qty_tablewash_fin">' + ttots + '</td></tr></tbody></table><hr>'+ $comment +' <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="left" style="margin-left:10px">Save</button> <button class="btn btn-danger pull-right input-sm" id="close" type="button">Close</button> <button type="button" class="btn btn-primary '+$print_button_class+'" id="print_wash_button_'+dataid+'" onclick=printWashDiv("washcare_svg_front","washcare_svg_back","washcare_svg_middle","'+dataid+'")>Print</button></div> </div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div>  <div class="backdrop"></div></form>');
   //$('.left').removeClass('disabled')

    $('#washcarelabelform' + dataid + ' .tot_qty_tablewash').keyup(function () {
        var tot_qty_tablewash_carton_final = 0;
        $('#washcarelabelform' + dataid + ' tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
            }
        })
        $('#washcarelabelform' + dataid + ' .tot_qty_tablewash_fin').text(tot_qty_tablewash_carton_final);
    });
	
	treestructurewash(data.treeviewwash,dataid, "tree_wash"+dataid, $template_id,data);
	
    $('#washcarelabelform' + dataid).validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setWashCareLabelStatusOs(dataid)
        }
    })
	
	$('#washcarelabelform' + dataid+' .washonesizein').keyup(function () {
		
		//var inches=$(this).val();
		var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val();
        var mySVG = document.getElementById("washcare_svg_front" + dataid);
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";

		
    });
	$('#washcarelabelform' + dataid+' .washonesizecm').keyup(function () {
		//alert(dataid)
		var cm=$(this).val();
		//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val();
        var mySVG = document.getElementById("washcare_svg_front" + dataid);
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
		
    });
	
    $('.care_instruction_select'+dataid).parent().removeClass('has-error')
    $('.help-block').html('')
    $('.extra_percentagewash').keyup(function () {
		
        
        var tot_qty_tablewash_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablewash').val(tot_qty);
                if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                    tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin').text(tot_qty_tablewash_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablewash').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin').text("0");
        }

    })

    selectpicker()

    var date = new Date();
    date.setDate(date.getDate() + 7);

    var dateMsg = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

    $("#clickme" + dataid).click(function () {
        
       if( $('.merchandisers option:selected').val() != "")
       {
            composition_percentage_global = 0;
            $('.composition_select' + dataid).prop('disabled', false)
            $('.parts_select' + dataid).prop('disabled', false)
            $(".parts_select" + dataid).val('');
            $('.parts_select' + dataid).selectpicker('refresh');
            $(".composition_select" + dataid).val('');
            $('.composition_select' + dataid).selectpicker('refresh');
            $(".composition_percent" + dataid).val('');
            $('.add_to_list' + dataid).removeAttr('disabled')
            $('#composition_table_qty' + dataid).html("")
            $('.composition_percent' + dataid).removeAttr('disabled')

            //$('.washlabelnameqty' + dataid).text($('.washlabelname' + dataid).text())
            $("#washcarelabelform" + dataid + " .backdrop").toggleClass('active');
            $("#washcarelabelform" + dataid + " .right-panelos").toggleClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').toggleClass('active');
            $("#washcarelabelform" + dataid + " .left-panelos").toggleClass('active');
            $('.merchandiser_error').addClass('hidden')
       }
       else
       {
                $('.merchandiser_error').removeClass('hidden')
       }
		//alert()
		$('.tot_qty_tablewash').parent().removeClass('has-error').removeClass('has-danger')
        
    })

    $(".backdrop, #close").click(function () {
        $("#washcarelabelform" + dataid + " .left-panelos").removeClass('active');
        $("#washcarelabelform" + dataid + " .right-panelos").removeClass('active');
        $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
        $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
		//$('.top-content').html('');
    })
	
	var mySVG_back = document.getElementById("washcare_svg_back" + dataid);
    var svgDoc_back;
    mySVG_back.addEventListener("load", function () {
        svgDoc_back = mySVG_back.contentDocument;
	
	if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $('.care_instruction_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].care_instruction);
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
                });


               

                var page_id = 0;
                //var comps="";
                $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index, value) {
                    if (value.page_id != page_id) {
                        var comps="";
                        $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index1, value1) {
                            if(value.page_id == value1.page_id)
                            {
                                if(comps != "")
                                {
                                    comps+=","+value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                }
                                else
                                {
                                    comps+=value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                }
                                
                            }
                            
                        });
                        
                        add_page_composition += 1;
                        $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td>Composition ' + add_page_composition + '</td><td><input type="hidden" value="'+comps+'" class="compositions_hidden"><select class="select_parts'+add_page_composition+'">' + parts_option + '</select></td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
                        
                        if(value.parts_id != "")
                        {
                            $('.select_parts'+add_page_composition).val(value.parts_id)
                        }
                        
                        
                        var washcomp=[];
                        $('#composition_remove_qty' + dataid + ' tr').each(function () {
                              var washcomp1=[];
                              var id=$(this).attr('id')
                              var value=$(this).find('select').find(':selected').val();
                              var compo =$(this).find('.compositions_hidden').val();
                              var compss = compo.split(',');
                                $(compss).each(function (index1,value1){ 
                                    var english = value1.split('_');
                                    washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                                });
                            washcomp.push(washcomp1)
                        })
                        
                        
                        var wash_finals = JSON.stringify(washcomp);
                        localStorage.setItem('wash_composition_final'+dataid, wash_finals);
                        
                        if (value.parts_english != null) {
                            svgDoc_back.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.parts_french + "-" + value.parts_english + "-" + value.parts_spanish + "-" + value.parts_portuguese + "-" + value.parts_italian + "-" + value.parts_dutch + "-" + value.parts_german + "-" + value.parts_arabic + "-" + value.parts_greek + '</p>';
                        }
                        
                    }
                    svgDoc_back.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.percentage + "% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german  + "-" + value.arabic + "-" + value.greek + '</p>';

                    page_id = value.page_id;

                });
            }
		
			svgDoc_back.getElementById("product_family").innerHTML = data.product_family.length > 0 ? data.product_family[0].arabic : "";

        }
		
		 $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select' + dataid + ' option:selected').val() != "") {
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
		
		}, false);

    var mySVG = document.getElementById("washcare_svg_front" + dataid);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;

        //$('.washing_select'+dataid+' option[data-id="1"]').prop('selected', true)

        if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $('.made_in_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].made_in_id);
                var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
				
                $('.washing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].washing_id);

                $('.merchandisers').selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].merchandisers_id);

                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
                $('.whitening_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].whitening_id);
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
                $('.dryer_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].dryer_id);
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
                $('.ironing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].ironing_id);
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
                $('.cleaning_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].cleaning_id);
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));

                
            }

        }


        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                var text_for_mad_in =  $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek');
				//console.log(text_for_mad_in);
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

       


        var size = data.order_details[0].Size.split(" ")

        if (size[1] == "ANS") {
            var size1 = "years";
        } else {
            var size1 = "months";
        }
        svgDoc.getElementById("parcel_code1").innerHTML = data.order_details[0].UVC_TCO;
        //svgDoc.getElementById("product_family").innerHTML = data.product_family[0].arabic;
        svgDoc.getElementById("parcel_code2").innerHTML = data.order_details[0].UVC_TCO;
		
        //alert(data.order_details[0].Size)
		
		if(data.order_details[0].Size == "TAILLE UNIQUE")
		{
			//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
			var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()
			
			svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
			svgDoc.getElementById("size1").innerHTML ="TU";
			svgDoc.getElementById("size2").innerHTML ="TU";
		}
		else if(data.order_details[0].Size == "TAILLE L")
		{
			svgDoc.getElementById("size1").innerHTML = "TAILLE L";
			svgDoc.getElementById("size2").innerHTML = "TAILLE L";
			svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
			svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
		}
		else
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = data.order_details[0].cm + "cm";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = data.order_details[0].cm + "cm";
			  svgDoc.getElementById("size1").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
			  svgDoc.getElementById("size2").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}
      


        var code = data.order_details[0].BarcodeForPricesTags.toString();
		//alert(code)
        jsbarcodewashOs(code, 'barcode', svgDoc)
    }, false);
}


function jsbarcodewashOs(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
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
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '92.1px');
    svg.setAttribute('height', '133px');
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

function setWashCareLabelStatusOs(dataid) {

    if ($('#washcarelabelform' + dataid).find('#exist_id' + dataid).val() != "") {
       var value = $('#washcarelabelform' + dataid).find('#exist_id' + dataid).val();
    } else {
        var value = "null";
    }

    var wash_lab = JSON.stringify(wash_label);
    var i = 0
    $('#composition_remove_qty' + dataid + ' tr').each(function (key, val) {
        i++;
    })
    if (i != 0) {
        if( localStorage.getItem("wash_composition_final"+ dataid) != null)
        {
            $('#wash_composition_error' + dataid).addClass('hidden')
            $('#wash_composition_page_error' + dataid).addClass('hidden')
            localStorage.setItem("wash_label", wash_lab);
            setWashLabelOs('washcarelabelform' + dataid, dataid, value)
            $("#washcarelabelform" + dataid + " .left-panelos").removeClass('active');
            $("#washcarelabelform" + dataid + " .right-panelos").removeClass('active');
            $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
        }
        else {
            $('#wash_composition_page_error' + dataid).removeClass('hidden')
        }
        
    } else {
        $('#wash_composition_error' + dataid).removeClass('hidden')
    }


}
function setWashLabelOs(index, dataid, id) {
	$('.washlabelname'+dataid).text($('#wcl_name' + dataid).val()+" - Washcare Label");
    var check=0;
    var check1=0;
    $('#composition_remove_qty' + dataid + ' tr').each(function () {
         
         if($(this).find('select' + dataid).find(':selected').text() == "Select Option")
             {
                 check++;
             }
         check1++;
    });
    if(check1 <2 )
    {
        check=0;
    }
    if(check == 0 )
    {
        var composition = localStorage.getItem('wash_composition');

        var wash_fins = JSON.parse(composition);
        var wash_finals = localStorage.getItem('wash_composition_final'+dataid);

        
        var merchandisers = $('.merchandisers option:selected').val();
      	
		
		//alert($('#wash_label_id').val())
        if($('#wash_label_id').val() != 0)
		{
			//var template_id = $('#' + index).find('#tree_wash' + dataid).jstree('get_selected', true);
			template_front_id = 5;
		}
		else
		{
			template_front_id=4;
		}
		var washonesizecm = $('#washcarelabelform' + dataid+' .washonesizecm').val();
		var washonesizein = $('#washcarelabelform' + dataid+' .washonesizein').val();
        var template_name = $('#wcl_name' + dataid).val();
        var washing_select = $('.washing_select' + dataid + ' option:selected').attr('data-id');
        var whitening_select = $('.whitening_select' + dataid + ' option:selected').attr('data-id');
        var dryer_select = $('.dryer_select' + dataid + ' option:selected').attr('data-id');
        var ironing_select = $('.ironing_select' + dataid + ' option:selected').attr('data-id');
        var cleaning_select = $('.cleaning_select' + dataid + ' option:selected').attr('data-id');
        var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');
        // var care_instruction_select = $('.care_instruction_select'+dataid+' option:selected').val();

        var care_instruction = $('.care_instruction_select' + dataid + ' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function (index, brand) {
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');

        //template_front_id = template_id[0].id;
        var tot_qty_tablewash = [];
        var uvc_tco = [];
        var size = [];
        $('#' + index + ' tbody tr').each(function () {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash.push($(this).find('.tot_qty_tablewash').val())
                uvc_tco.push($(this).find('.uvc_tco').text())
                size.push($(this).find('.size').text())
            }

        })
        tot_qty_tablewash_fin = tot_qty_tablewash.join(',');
        uvc_tco_fin = uvc_tco.join(',');
        size_fin = size.join(',');
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_wash_label",
            type: "Post",
            data: {
                
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                washing_select: washing_select,
                whitening_select: whitening_select,
				in:washonesizein,
				cm:washonesizecm,
                dryer_select: dryer_select,
                ironing_select: ironing_select,
                cleaning_select: cleaning_select,
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
                made_in_select: made_in_select,
                care_instruction_select: care_instruction_select_fin,
                composition: wash_finals,
                tot_qty_table: tot_qty_tablewash_fin,
                uvc_tco: uvc_tco_fin,
                merchandisers: merchandisers,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (data) {
				$('#print_wash_button_'+dataid).removeClass('hidden')
				$('#save_wash_button_'+dataid).addClass('hidden')
            },
            error: function (data) {

            }
        })
        $('.composition_error_hidden').addClass('hidden')
        $('#washcare_label_card').css('display','inline-block') 
		$('#clone_washcare_label_card'+dataid).css('display','inline-block')
		$('.wash-card'+dataid+' span').text('Buyer Approval Pending')
		$('.wash-card'+dataid+' span').addClass('label label-BuyerApprovalPending label-status')
		$('.wash-card'+dataid+' span').removeClass('label-OrderRejected')
		$('#po-file-upload-button').css('display','block')
    }
    else
    {
        $('.composition_error_hidden').removeClass('hidden')
    }
    

}