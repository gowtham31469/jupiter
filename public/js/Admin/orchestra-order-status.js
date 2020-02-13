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
                                         var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))))))));
										
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var old_po = ""
                                        var new_po_div_status = 0;
                                        $(newObj).each(function (index1, value1) {

                                            if (value1.po_number != "" && value1.po_number != null) {
                                                if (old_po != value1.po_number) {
                                                    options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
                                                }
												
												if(value1.color_code != "" && value1.color_code != null)
												{
													var c_name = value1.color_code+' ' + value1.TemplateName ;
												}
												else
												{
													var c_name = value1.TemplateName;
												}
												
                                                old_po = value1.po_number
                                                coun++;
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">'+c_name+ '</li>')
                                                new_po_div_status++;

                                            } else {
													if(value1.color_code != "" && value1.color_code != null)
													{
														var c_name = value1.color_code+' ' + value1.TemplateName;
													}
													else
													{
														var c_name = value1.TemplateName;
													}
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">'+ c_name + '</li>')
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
                                                            po: $('#select_po_status').val(),
															po_file:$('#select_po_status option:selected').attr('data-id')
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
                            if (i === 0 || i === 6 || i === 4) {
                                var select;
                                switch (i) {
                                    case 0:
                                        select = $('#styleNumber')
                                        break;
                                    case 6:
                                        select = $('#supplierName')
                                        break;
                                    case 4:
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
                        filterByDate(7, start, end); // We call our filter function
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
        if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uvc" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty" && key != "color_code") {
            if (key == "TemplateName") {
                th += "<th style='text-align: center;'>Template Name</th>";
            }else if (key == "BuyerPONo") {
                th += "<th style='text-align: center;'>BuyerPO No</th>";
            }else if (key == "ArtworkType") {
                th += "<th style='display:none'>Artwork Type</th>";
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
                    tr += '<td align="center" data-id="' + value.tableID + '" data-artwork="' + value.ArtworkType + '" data-season="' + value.Season + '" data-uvc="' + value.UVC + '" data-table="' + value.tableName + '" data-buyer="' + value.BuyerPONo + '" data-color_code="' + value.color_code + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" onclick="updateArtworkOrc(this)">' + span_style + '' + value.UVC + '</a></td>';
                }else if (key == "TemplateName") {
					if(value["color_code"] != "" && value["color_code"] != null)
					{
						tr += '<td align="center"> '+ value.color_code +' '+value.TemplateName+' </td>';
					}
					else
					{
						tr += '<td align="center"> '+value.TemplateName+' </td>';
					}
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
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '","'+value.BuyerPONo+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (po_required == 1 && pi_required == 0) {

                            tr += '<td align="center"><a href="javascript:void(0)" class="po-style" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" ' + style_po + '></i></a></td>';
                        } else if (pi_required == 1 && po_required == 0) {

                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '","'+value.BuyerPONo+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a></td>';
                        }
                    } else {
                        tr += '<td align="center"> - </td>';
                    }
                } else if (key == "ArtworkType") {
					tr += '<td style="display:none">' + value[key] + '</td>';
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
                } else if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uk_style_ref" && key != "ce_style_ref" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty" && key != "color_code") {
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
    var color_code = $(obj).parent().attr('data-color_code');
    var buyer = $(obj).parent().attr('data-buyer');
	var at = artwork.replace(/ /g, '');


    
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
            color_code: color_code,
            buyer: buyer,
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
					
					if (data.hang_erp_details != null) {
							if (data.hang_erp_details.status_name !== 'Hold For JC Queue' && data.hang_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablehang').prop("disabled", true);
								$('.extra_percentagehang').prop("disabled", true);
								$('#save_hang_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.hang_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
					
				}
				else if(at == "SpecialTag")
				{
					specialtagsOs(data)
					
					if (data.special_erp_details != null) {
							if (data.special_erp_details.status_name !== 'Hold For JC Queue' && data.special_erp_details.status_name !== 'Moved To ERP') {
						    	$('.special_qty').prop("disabled", true);
								$('#save_special_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.special_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "MainLabel")
				{
					mainlabelsOs(data)
					
					if (data.main_erp_details != null) {
							if (data.main_erp_details.status_name !== 'Hold For JC Queue' && data.main_erp_details.status_name !== 'Moved To ERP') {
								$('.main_label_qty').prop("disabled", true);
								$('#save_main_label_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.main_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "WashCareLabel")
				{
					washcarelabelsOs(data, "")
					$('.top-content').append('<input type="hidden" value="' + data.orchestra_wash_care_label_orders[0].po + '" id="po_select">');
					if (data.wash_erp_details != null) {
							if (data.wash_erp_details.status_name !== 'Hold For JC Queue' && data.wash_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablewash').prop("disabled", true);
								$('.extra_percentagewash').prop("disabled", true);
								$('#save_wash_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.wash_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "SizeLabel")
				{
					sizelabelsOs(data)
					
					if (data.size_erp_details != null) {
							if (data.size_erp_details.status_name !== 'Hold For JC Queue' && data.size_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablesize').prop("disabled", true);
								$('.extra_percentagesize').prop("disabled", true);
								$('#save_size_label_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.size_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "HeatTransfer")
				{
					heatTransferOs(data)
					
					if (data.heat_erp_details != null) {
							if (data.heat_erp_details.status_name !== 'Hold For JC Queue' && data.heat_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tableheat').prop("disabled", true);
								$('.extra_percentageheat').prop("disabled", true);
								$('#save_heat_transfer_button').addClass('hidden')
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.heat_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "SolidCartonStickers")
				{
					SolidCartonStickersOs(data)
					
					if (data.solid_carton_erp_details != null) {
							if (data.solid_carton_erp_details.status_name !== 'Hold For JC Queue' && data.solid_carton_erp_details.status_name !== 'Moved To ERP') {
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.solid_carton_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "SolidPolyBagStickers")
				{
					SolidPolyBagStickersOs(data)
					
					if (data.solid_poly_erp_details != null) {
							if (data.solid_poly_erp_details.status_name !== 'Hold For JC Queue' && data.solid_poly_erp_details.status_name !== 'Moved To ERP') {
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.solid_poly_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "StandardCartonStickers")
				{
					StandardCartonStickersOs(data)
					
					if (data.standard_carton_erp_details != null) {
							if (data.standard_carton_erp_details.status_name !== 'Hold For JC Queue' && data.standard_carton_erp_details.status_name !== 'Moved To ERP') {
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.standard_carton_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
				}
				else if(at == "StandardPolyBagStickers")
				{
					StandardPolyBagStickersOs(data)
					
					if (data.standard_poly_erp_details != null) {
							if (data.standard_poly_erp_details.status_name !== 'Hold For JC Queue' && data.standard_poly_erp_details.status_name !== 'Moved To ERP') {
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.standard_poly_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}
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

function SolidCartonStickersOs(data)
{
	var tr = "";
    $qu = 0;
    if (data.orchestra_solid_carton_sticker_orders != "") {
        if (data.orchestra_solid_carton_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_solid_carton_sticker_orders[0].height;
		}
		
		if (data.orchestra_solid_carton_sticker_orders[0].width == null) {
			$width = "";
		} else {
			$width = data.orchestra_solid_carton_sticker_orders[0].width;
		}
		
		var num = 600 * $width * $height
		var val = num / (1000 * 1000 * 1000)
		$parcelVolume = val;
        $template_id = data.orchestra_solid_carton_sticker_orders[0].template_id;
        $exist_id = data.orchestra_solid_carton_sticker_orders[0].id;
        $solid_carton_count=data.orchestra_solid_carton_sticker_orders[0].final_qty;
        
        var str=data.orchestra_solid_carton_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_solid_carton_sticker_orders[0].group_id;
    } else {
     	$height = "";
     	$width = 395;
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $solid_carton_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$age =0;
    }
		var tpolytot = 0;
        var tcarttot = 0;
        var ttots = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_carton_sticker_orders != "") {
                $carton_count = data.orchestra_solid_carton_sticker_orders_qty[$qu].quantity;
            } else {
                $carton_count = "";
            }

            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Quantity + '</td><td class="text-center"><div class="form-group has-feedback has-success"><input  min="'+data.carton_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)"  required type="number" value="' + $carton_count + '" class="form-control tot_qty_table_carton input-sm"><div class="help-block with-errors"></div></div></td></tr>';
			if($carton_count != "")
			{
				tcarttot += parseInt($carton_count);
			}
			ttots += parseInt(value.Quantity)
            $qu++;
        });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.top-content').append('<form id="solid_cartonform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label">Height</label><div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div><div class="form-group"><label>Width</label><div class="input-group"><input type="text" onkeypress="return checkNumbersOnly(event)" value="' + $width + '" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Solid Carton Stickers</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="solid_carton_table"><thead><tr><th>UVC_TCO</th><th>Size</th><th>Barcode</th><th>Item Qty</th><th>Parcel Sticker Qty</th></tr></thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots + '</td><td class="text-center tot_qty_tablecarton_fin">' + tcarttot + '</td></tr></tbody></table></div> <span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_solid_carton_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="solid_cartonclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	$('#age').val($age);
	
	barcode_cartonOs('svg2', data)
	
	//$('.left').removeClass('disabled')
    $('#solid_cartonform .tot_qty_table_carton').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#solid_cartonform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_carton').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table_carton').val());
            }
        })
        $('#solid_cartonform .tot_qty_tablecarton_fin').text(tot_qty_table_carton_final);
    });
	
    $(".backdrop, #solid_cartonclose").click(function () {
        $("#solid_cartonform .left-panelos").removeClass('active');
        $("#solid_cartonform .right-panelos").removeClass('active');
        $("#solid_cartonform .backdrop").removeClass('active');
        $('#solid_cartonform .scalable').removeClass('active');
		//$('.top-content').html('');
    })

	
    $("#modal_height").keyup(function () {
        var num = 600 * $('#width').val() * this.value
        var val = num / (1000 * 1000 * 1000)
        $('#parcelVolume').val(val)
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("volume").innerHTML = val;
        svgDoc1.getElementById("height").innerHTML = this.value;
    });
	$("#width").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("width").innerHTML = this.value;
    });
    $('#solid_cartonform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setsolid_cartonStatusOs()
        }
    })
}

function SolidPolyBagStickersOs(data)
{
	var tr = "";
    $qu = 0;
    if (data.orchestra_solid_poly_bag_sticker_orders != "") {
        if (data.orchestra_solid_poly_bag_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_solid_poly_bag_sticker_orders[0].height;
		}
		var num = 600 * 395 * $height
		var val = num / (1000 * 1000 * 1000)
		$parcelVolume = val;
        $template_id = data.orchestra_solid_poly_bag_sticker_orders[0].template_id;
        $exist_id = data.orchestra_solid_poly_bag_sticker_orders[0].id;
        $solid_poly_bag_count=data.orchestra_solid_poly_bag_sticker_orders[0].final_qty;
        
        var str=data.orchestra_solid_poly_bag_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_solid_poly_bag_sticker_orders[0].group_id;
    } else {
     	$height = "";
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $solid_poly_bag_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$age =0;
    }
		var tpolytot = 0;
        var tcarttot = 0;
        var ttots = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_poly_bag_sticker_orders != "") {
                $poly_count = data.orchestra_solid_poly_bag_sticker_orders_qty[$qu].quantity;
            } else {
                $poly_count = "";
            }

            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Quantity + '</td><td class="text-center"><div class="form-group has-feedback has-success"><input  min="'+data.polybag_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)"  required type="number" value="' + $poly_count + '" class="form-control tot_qty_table_poly input-sm"><div class="help-block with-errors"></div></div></td></tr>';
			if($poly_count != "")
			{
				tcarttot += parseInt($poly_count);
			}
			ttots += parseInt(value.Quantity)
            $qu++;
        });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.top-content').append('<form id="solid_poly_bagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label hidden">Height</label><div class="input-group hidden"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.polybag_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Solid Poly Bag Stickers</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="solid_poly_bag_table"><thead><tr><th>UVC_TCO</th><th>Size</th><th>Barcode</th><th>Item Qty</th><th>Polybag Sticker Qty</th></tr></thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots + '</td><td class="text-center tot_qty_tablepoly_fin">' + tcarttot + '</td></tr></tbody></table></div> <span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_solid_poly_bag_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="solid_poly_bagclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	$('#age').val($age);
	
	barcode_polybagOs('svg2', data)
	
	//$('.left').removeClass('disabled')
    $('#solid_poly_bagform .tot_qty_table_poly').keyup(function () {
        var tot_qty_table_poly_final = 0;
        $('#solid_poly_bagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_poly').val() != undefined) {
                tot_qty_table_poly_final += parseInt($(this).find('.tot_qty_table_poly').val());
            }
        })
        $('#solid_poly_bagform .tot_qty_tablepoly_fin').text(tot_qty_table_poly_final);
    });
	
    $(".backdrop, #solid_poly_bagclose").click(function () {
        $("#solid_poly_bagform .left-panelos").removeClass('active');
        $("#solid_poly_bagform .right-panelos").removeClass('active');
        $("#solid_poly_bagform .backdrop").removeClass('active');
        $('#solid_poly_bagform .scalable').removeClass('active');
		//$('.top-content').html('');
    })
	
	$('#age').change(function () {
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
    });


    $('#solid_poly_bagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setsolid_poly_bagStatusOs()
        }
    })
}
function StandardCartonStickersOs(data)
{
	var tr = "";
    $qu = 0;
    if (data.orchestra_standard_carton_sticker_orders != "") {
        if (data.orchestra_standard_carton_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_standard_carton_sticker_orders[0].height;
		}
		
		if (data.orchestra_standard_carton_sticker_orders[0].height == null) {
			$width = "";
		} else {
			$width = data.orchestra_standard_carton_sticker_orders[0].width;
		}
		var num = 600 * $width * $height
		var val = num / (1000 * 1000 * 1000)
		$parcelVolume = val;
        $template_id = data.orchestra_standard_carton_sticker_orders[0].template_id;
        $exist_id = data.orchestra_standard_carton_sticker_orders[0].id;
        $standard_carton_count=data.orchestra_standard_carton_sticker_orders_qty[0].quantity;
		
		if(data.orchestra_standard_carton_sticker_orders[0].total_polybag != "" && data.orchestra_standard_carton_sticker_orders[0].total_polybag != null)
		{
			$total_polybag = data.orchestra_standard_carton_sticker_orders[0].total_polybag;
		}
		else
		{
			$total_polybag = "";
		}
		
		if(data.orchestra_standard_carton_sticker_orders[0].no_of_carton != "" && data.orchestra_standard_carton_sticker_orders[0].no_of_carton != null)
		{
			$no_of_carton = data.orchestra_standard_carton_sticker_orders[0].no_of_carton;
		}
		else
		{
			$no_of_carton = "";
		}
		
        
        var str=data.orchestra_standard_carton_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_standard_carton_sticker_orders[0].group_id;
    } else {
     	$height = "";
     	$width = 395;
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $total_polybag = "";
        $standard_carton_count="";
        $status="";
		$no_of_carton = "";
        $status_text="";
        $save_button_class="";
		$age =0;
    }
	var tr = "";
	var ttots = 0;
	var thead = '<thead><tr><th>UVC_TCO	</th><th>Size</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th><th>Ratio</th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + data.orchestra_parcel_details[0].ParcelBarCode + '</td><td class="qty">' + value.Sum + '</td></tr>';
			ttots += parseInt(value.Sum)
     });
	
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.top-content').append('<form id="standard_cartonform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label">Height</label><div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div><div class="form-group"><label>Width</label><div class="input-group"><input type="text" onkeypress="return checkNumbersOnly(event)" value="' + $width + '" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6" style="width: 130px;"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left:-135px;">/</span> <div class="col-md-6" style="width: 130px;"><input data-toggle="modal" data-target=".needed_modal" value="' + $no_of_carton + '" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div><div class="form-group"><label>Total Polybags per carton: </label><div class="form-group has-feedback has-success"><input id="total_polybag" value="' + $total_polybag + '" onkeypress="return checkNumbersOnly(event)" type="text" placeholder="Total Polybags per carton" class="form-control input-sm"><div class="help-block with-errors"></div></div></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Standard Carton Stickers</h4> <hr><div class="table-responsive hidden"><table class="table table-striped table-hover table-condensed qty-table-modal" id="standard_carton_table">'+thead+'<tbody>' + tr + '</tbody></table></div><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm standard_carton_qty" value="' + $standard_carton_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_standard_carton_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="standard_cartonclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	$('#age').val($age);
	
	barcode_std_cartonOs('svg2', data)
	
    $(".backdrop, #standard_cartonclose").click(function () {
        $("#standard_cartonform .left-panelos").removeClass('active');
        $("#standard_cartonform .right-panelos").removeClass('active');
        $("#standard_cartonform .backdrop").removeClass('active');
        $('#standard_cartonform .scalable').removeClass('active');
		//$('.top-content').html('');
    })

	
    $("#modal_height").keyup(function () {
        var num = 600 * $('#width').val() * this.value
        var val = num / (1000 * 1000 * 1000)
        $('#parcelVolume').val(val)
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("volume").innerHTML = val;
        svgDoc1.getElementById("height").innerHTML = this.value;
    });
	$("#total_polybag").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("total_polybags_per_carton").innerHTML = this.value;
    });
	$("#width").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("width").innerHTML = this.value;
    });
	$("#needed_modal").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("no_of_cartons").innerHTML = this.value;
    });
    $('#standard_cartonform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setstandard_cartonStatusOs()
        }
    })
}

function StandardPolyBagStickersOs(data)
{
	var tr = "";
    $qu = 0;
	
	
    if (data.orchestra_standard_poly_bag_sticker_orders != "") {
        if (data.orchestra_standard_poly_bag_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_standard_poly_bag_sticker_orders[0].height;
		}
		var num = 600 * 395 * $height
		var val = num / (1000 * 1000 * 1000)
		$parcelVolume = val;
        $template_id = data.orchestra_standard_poly_bag_sticker_orders[0].template_id;
        $exist_id = data.orchestra_standard_poly_bag_sticker_orders[0].id;
        $standard_poly_bag_count=data.orchestra_standard_poly_bag_sticker_orders_qty[0].quantity;
        
        var str=data.orchestra_standard_poly_bag_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_standard_poly_bag_sticker_orders[0].group_id;
    } else {
     	$height = "";
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $standard_poly_bag_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$age =0;
    }
		var tpolytot = 0;
        var tcarttot = 0;
       var ttots = 0;
	var thead = '<thead><tr><th>UVC_TCO	</th><th>Size</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th><th>Ratio</th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + data.orchestra_parcel_details[0].ParcelBarCode + '</td><td class="qty">' + value.Sum + '</td></tr>';
			ttots += parseInt(value.Sum)
     });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
	$('.top-content').append('<form id="standard_poly_bagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label hidden">Height</label><div class="input-group hidden"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.polybag_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"><div class="table-responsive hidden"> <table class="table table-condensed table-bordered table-hover" id="standardpolybagform">' + thead + '<tbody id="standardpolybagformtbody">' + tr + '</tbody></table></div><div class="div-max"> <h4>Quantity Info for Standard Poly Bag Stickers</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm standard_poly_bag_qty" value="' + $standard_poly_bag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_standard_poly_bag_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="standard_poly_bagclose">Close</button>  </div> </div> </div>  <div class="backdrop"></div></form>');
	$('#age').val($age);
	
	barcode_std_polybagOs('svg2', data)
	
    $(".backdrop, #standard_poly_bagclose").click(function () {
        $("#standard_poly_bagform .left-panelos").removeClass('active');
        $("#standard_poly_bagform .right-panelos").removeClass('active');
        $("#standard_poly_bagform .backdrop").removeClass('active');
        $('#standard_poly_bagform .scalable').removeClass('active');
		//$('.top-content').html('');
    })

	$('#age').change(function () {
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
    });

    $('#standard_poly_bagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setstandard_poly_bagStatusOs()
        }
    })
}

/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */

function barcode_cartonOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = 600;
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		svgDoc.getElementById("volume").innerHTML = parseFloat($('#parcelVolume').val()).toFixed(3);
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;

		
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			jsbarcodesolidcartonAOs(code, 'barcode1', svgDoc)
			jsbarcodesolidcartonBOs(code, 'barcode2', svgDoc)

	}, false);

}


/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_polybagOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");

			svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			jsbarcodesolidpolyOs(code, 'barcode', svgDoc)
		
	}, false);

}

/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */
function barcode_std_cartonOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = 600;
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		svgDoc.getElementById("volume").innerHTML = parseFloat($('#parcelVolume').val()).toFixed(3);
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
			jsbarcodestdcartonAOs(code, 'barcode1', svgDoc)
			jsbarcodestdcartonBOs(code, 'barcode2', svgDoc)
			svgDoc.getElementById("total_polybags_per_carton").innerHTML = $('#total_polybag').val();
			svgDoc.getElementById("no_of_cartons").innerHTML = $('#needed_modal').val();
	}, false);
}
/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_std_polybagOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");

		svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;

		$td_col = "";
		$td_size = "";
		$total = 0;
		var index_arr=[];
		$('#standardpolybagform tbody tr').each(function () {

		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if ($(this).find('.size').text() != "") {
					index_arr.push($(this).find('.size').text())
					$td_size += "<td>" + $(this).find('.size').text() + "</td>";
					$td_col += "<td>" + $(this).find('.qty').text() + "</td>";
					$total += parseInt($(this).find('.qty').text());
			}
		}



		})
		$tbl = '<tr><td>Size:</td>' + $td_size + '<td>Total</td></tr><tr> <td>ERCU</td>' + $td_col + '<td>' + $total + '</td></tr>';
		svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
		var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		svgDoc.getElementById("cartontable").innerHTML = $tbl;
		jsbarcodestdpolyOs(code, 'barcode', svgDoc)
		
		
	}, false);

}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonAOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 347,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonBOs(code, id, svgDoc) {	
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.5,
		height: 345,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidpolyOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.7,
		height: 370,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.50px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonAOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 355,
		fontSize: 50
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
	svg.setAttribute('font-size', '59.80px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonBOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.43,
		height: 349,
		fontSize: 50
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
	svg.setAttribute('font-size', '55px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdpolyOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width:6.595,
		height: 350,
		fontSize: 60
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
	svg.setAttribute('font-size', '64px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '12px');

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

function hangtagmodalOs(data) {
    var order_details = data.order_details;
    var orchestra_hang_tag_orders_data = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (data.orchestra_hang_tag_orders != "") {
       

        $template_front_id = data.orchestra_hang_tag_orders[0].template_front_id;
        $exist_id = data.orchestra_hang_tag_orders[0].id;
        
        var str=data.orchestra_hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="hidden";
		$save_button_class="";
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_hang_tag_orders != "") {

            $hang_tag_count = data.orchestra_hang_tag_orders_qty[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
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
		//<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm onesizecm" value="'+$hang_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>
		var sizebox='';
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
    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="left-panelos col-md-8"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="hang_template_error" class="hidden" style="color:red;display: inherit;">Please Select the Template</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div>'+sizebox+'</div><div class="col-md-4"><h4>Front</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections text-center" ><input type="hidden" value="' + data.orchestra_template_id + '" id="hang_tag_id"><div class="sample_back"></div><label style="margin-top:15px">Hang Tag Print Date : ' + data.order_details[0].HangTag_Print_Date + '</label><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagehang" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablehang_fin">' + ttots + '</td></tr></tbody></table></div><span id="error_msg_alert" style="color:red;"></span> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_hang_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button><button type="button" class="btn btn-primary '+$print_button_class+'" id="print_hang_button" onclick=printHangDiv("svg2","'+data.order_details[0].Licence_Code+'")>Print</button> </div> </div>  <div class="backdrop"></div></form>');


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
	
//	$('#hangtagform .onesizein').keyup(function () {
//		
//		var inches=$(this).val();
//        var mySVG = document.getElementById("svg2");
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("in").innerHTML = inches;
//
//		
//    });
//	$('#hangtagform .onesizecm').keyup(function () {
//		
//		var cm=$(this).val();
//        var mySVG = document.getElementById("svg2");
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("cm").innerHTML = cm;
//		
//    });

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
		var node = $("#tree1").jstree('get_selected', true)[0].text;
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
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
									svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
									svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
							
							//svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
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
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm+" CM";
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
									
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

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
									//alert(order_details[0].Age);
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
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
		var node = $("#tree1").jstree('get_selected', true)[0].text;
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
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
									svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";

									svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
									svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
									
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
							
							//svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
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
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
							
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
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
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm+" CM";
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
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

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
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
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
            setHangTagStatusOs()
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
        
        var str=data.orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var st="";
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$special_tag_count=data.orchestra_special_tag_orders_qty[0].quantity;
	
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Special Tag Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
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



    treestructurespecialOs(data.treeviewspecial, "tree2", $template_id);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatusOs()
        }
    })
}


function treestructurespecialOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
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
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
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
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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

	$('#' + tree).on('activate_node.jstree', function (e, data) {
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
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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
        $save_button_class="";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = data.orchestra_heat_transfer_orders_qty[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
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

    $('.top-content').append('<form id="heat_transferform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="heat_transfer_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeht_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeht" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentageheat" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tableheat_fin">' + ttots + '</td></tr></tbody></table></div><span id="error_msg_alert" style="color:red;"></span> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button></div> </div>  <div class="backdrop"></div></form>');

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
    })



    treestructureheat_transferOs(data.treeviewheat_transfer, "treeht", $template_id);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.color_wcl)
        }
    })
}

function treestructureheat_transferOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
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
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
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
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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

	$('#' + tree).on('activate_node.jstree', function (e, data) {
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
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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
        $save_button_class="";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_size_label_orders != "") {

            $size_label_count = data.orchestra_size_label_orders_qty[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
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

    $('.top-content').append('<form id="size_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="size_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treesl_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treesl" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Size Label</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="size_label_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagesize" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablesize_fin">' + ttots + '</td></tr></tbody></table></div><span id="error_msg_alert" style="color:red;"></span> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_size_label_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button></div> </div>  <div class="backdrop"></div></form>');

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
    })



    treestructuresize_labelOs(data.treeviewsize_label, "treesl", $template_id,data.orchestra_size_label_orders);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatusOs(data.color_wcl)
        }
    })
}


/**
 * This Function is used to invoke Jstree For Size Labels.
 * 
 * @return tree
 */
function treestructuresize_labelOs(data, tree, template_id,orchestra_size_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
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
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
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
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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

	$('#' + tree).on('activate_node.jstree', function (e, data) {
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
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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
        $save_button_class="";
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
    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
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



    treestructuremain_labelOs(data.treeviewmain_label, "treeml", $template_id);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatusOs()
        }
    })
}


function treestructuremain_labelOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
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
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
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
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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

	$('#' + tree).on('activate_node.jstree', function (e, data) {
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
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
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
//			if(str == "Order Approved")
//			{
//				$save_button_class = "hidden";
//			}
//			else
//			{
//				$save_button_class = "";
//			}
			$save_button_class = "";
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
		//var sizebox='<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm washonesizecm" value="'+$wash_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label>Enter Size in inches</label><input onkeypress="return checkNumbersOnly(event)" type="text" class="form-control input-sm washonesizein" maxlength="3" value="'+$wash_in+'" placeholder="Enter Size in inches"><div class="help-block with-errors"></div></div>';
		var sizebox='';
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

    $('.top-content').append('<form id="washcarelabelform' + dataid + '" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id' + dataid + '"><div class="left-panelos col-md-8"> <div class="div-max"> <div class="col-md-3"> <h4>Options</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">WCL Name</label><input type="text" class="form-control input-sm" required="" placeholder="WCL Name" value="' + $wcl_name + '" id="wcl_name' + dataid + '"><div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select data-live-search="true" class="form-control selectpicker input-sm washing_select' + dataid + '" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select data-live-search="true" class="form-control selectpicker input-sm whitening_select' + dataid + '" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select data-live-search="true" class="form-control selectpicker input-sm dryer_select' + dataid + '" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select data-live-search="true" class="form-control selectpicker input-sm ironing_select' + dataid + '" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select data-live-search="true" class="form-control selectpicker input-sm cleaning_select' + dataid + '" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div>'+treestruct+'  </div></div> <div class="col-md-5"> <h4>Art Work</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span> <hr> <div class="section-1 sections"> <div class="sample-artwork"><object style="height: calc( 100vh - 125px );"  id="washcare_svg_front' + dataid + '" data="' + data.orchestra_wash_template_front + '" type="image/svg+xml"></object> <object style="height: calc( 100vh - 125px );margin-left: 10px;"  id="washcare_svg_back' + dataid + '"  data="' + data.orchestra_wash_template_back + '" type="image/svg+xml"></object><object style="height: calc( 100vh - 125px );width: 145px;margin-left: 10px;"  id="washcare_svg_middle' + dataid + '" data="' + data.orchestra_wash_template_middle + '" type="image/svg+xml"></object></div><input type="hidden" value="' + disney_flag + '" id="wash_label_id"></div> </div> <div class="col-md-4 " style="padding-left:0px"> <h4>Options</h4> <hr>'+sizebox+' <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select data-live-search="true" class="form-control selectpicker input-sm made_in_select' + dataid + '" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select data-live-search="true" class="form-control selectpicker input-sm care_instruction_select' + dataid + '" required multiple>' + instruction_option + '</select><div class="help-block with-errors"></div></div> <div class="section-1 sections"> <h5>Compositions</h5> <select class="selectpicker composition_select' + dataid + '"  data-live-search="true">  ' + composition_option + '  </select><p class="hidden" style="color:red;float:right" id="composition_error_required' + dataid + '">Please Select Composition</p> </div> <h5>Percentage</h5> <input type="number" min="1" max="100" class="form-control input-sm composition_percent' + dataid + '"><p class="hidden" style="color:red;text-align:right" id="percentage_error_required' + dataid + '">Please Enter Percentage</p>  <button type="button" class="btn  btn-primary pull-right input-sm add_to_list' + dataid + '" onclick="add_to_list(' + dataid + ')">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty' + dataid + '"></tbody></table><p id="wash_composition_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Select composition</p><p id="wash_composition_page_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Add Composition to Page</p><div style="height: 30px;"><button class="btn  btn-primary pull-right input-sm add_to_composition' + dataid + '"  type="button" disabled onclick="add_to_composition(' + dataid + ')">Add To Composition</button></div><div><h4>Compositions :</h4><div><table class="table table-striped table-hover table-condensed"><thead><tr><th>Composition Name</th><th>Parts</th><th>Actions</th></tr></thead><tbody id="composition_remove_qty' + dataid + '"></tbody></table><div><p class="hidden composition_error_hidden" style="color:red;text-align: right;margin-right: 10px;">Please select parts</p></div><div><button class="btn  btn-primary pull-right input-sm add_to_page' + dataid + '"  style="position:relative;" type="button" onclick="add_to_page(' + dataid + ')">Add To Page</button></div></div></div><div class="parts_none'+dataid+'" style="display:none"><select>' + parts_option + '</select></div> </div> </div> </div></div> <div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for <span class="washlabelnameqty' + dataid + '"> WashCare Label</span></h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_'+dataid+'" style="font-size:11px"> <thead> <tr> <th>UVC TCO#</th> <th>Size in ANS</th> <th>Barcode</th> <th>Qty.</th> <th><input type="text" class="form-control input-sm extra_percentagewash" onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th> </tr></thead> <tbody style="font-size: 11px">' + tr + ' <tr class="strong"> <td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center tot_qty_tablewash_fin">' + ttots + '</td></tr></tbody></table><hr>'+ $comment +' <span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_wash_button" style="margin-left:10px">Update</button> <button class="btn btn-default pull-right input-sm" id="close" type="button">Close</button> <button type="button" class="btn btn-primary '+$print_button_class+'" id="print_wash_button" onclick=printWashDiv("washcare_svg_front","washcare_svg_back","washcare_svg_middle","'+dataid+'")>Print</button></div> </div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div>  <div class="backdrop"></div></form>');
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
        var barcode = [];
        $('#' + index + ' tbody tr').each(function () {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash.push($(this).find('.tot_qty_tablewash').val())
                uvc_tco.push($(this).find('.uvc_tco').text())
                size.push($(this).find('.size').text())
                barcode.push($(this).find('.barcode').text())
            }

        })
        tot_qty_tablewash_fin = tot_qty_tablewash.join(',');
        uvc_tco_fin = uvc_tco.join(',');
        size_fin = size.join(',');
        barcode_fin = barcode.join(',');
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_wash_labelos",
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
				po_select: $('#po_select').val(),
                made_in_select: made_in_select,
                care_instruction_select: care_instruction_select_fin,
                composition: wash_finals,
                tot_qty_table: tot_qty_tablewash_fin,
                uvc_tco: uvc_tco_fin,
                barcode: barcode_fin,
                merchandisers: merchandisers,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (data) {
				$('#print_wash_button').removeClass('hidden')
				$('#save_wash_button').addClass('hidden')
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

/**
 * This Function is used to set special tag in local storage.
 * 
 * @return void
 */

function setSpecialTagStatusOs() {

    if ($('#specialtagform').find('#exist_id').val() != "") {
        var exist_id = $('#specialtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("special_tag", exist_id);
        setSpecialTagOs(localStorage.getItem("special_tag"))
        $('#special_template_error').addClass('hidden')
        $("#specialtagform .left-panelos").removeClass('active');
        $("#specialtagform .right-panelos").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    } else {
        $('#special_template_error').removeClass('hidden')
    }
}


function setSpecialTagOs(id) {
  
    var special_qty = $('.special_qty').val();
    
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    template_front_id = template_id[0].id;
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#specialtagform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/set_special_tag",
        type: "Post",
        data: {
            
            template_front_id: template_front_id,
            id: id,
            tot_qty_table: special_qty,
            uvc_tco: uvc_tco_fin,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#save_special_button').addClass('hidden')
        },
        error: function (data) {

        }
    })
	$('.special-card span').text('Order Approved')
	$('.special-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}


function setMainLabelStatusOs() {

    if ($('#main_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#main_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("main_label", exist_id);
        setMainLabelOs(localStorage.getItem("main_label"))
        $('#main_label_template_error').addClass('hidden')
        $("#main_labelform .left-panelos").removeClass('active');
        $("#main_labelform .right-panelos").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    } else {
        $('#main_label_template_error').removeClass('hidden')
    }
}



function setMainLabelOs(id) {
  
    var main_label_qty = $('.main_label_qty').val();
    
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    template_id = template_id[0].id;
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#main_labelform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/set_main_label",
        type: "Post",
        data: {
            
            template_id: template_id,
            id: id,
            tot_qty_table: main_label_qty,
            uvc_tco: uvc_tco_fin,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#save_main_label_button').addClass('hidden')
        },
        error: function (data) {

        }
    })
	$('.main_label-card span').text('Order Approved')
	$('.main_label-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}

/**
 * This Function is used to set Size Labels in local storage.
 * 
 * @return void
 */

function setSizeLabelStatusOs() {

    if ($('#size_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#size_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("size_label", exist_id);
        setSizeLabelOs(localStorage.getItem("size_label"))
        $('#size_label_template_error').addClass('hidden')
        $("#size_labelform .left-panelos").removeClass('active');
        $("#size_labelform .right-panelos").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    } else {
        $('#size_label_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Size Label in db.
 * 
 * @return void
 */


function setSizeLabelOs(id) {
  
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
	console.log(template_id)
	template_id = template_id[0].id;
	
	var tot_qty_tablesize = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#size_labelform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablesize').val() != undefined) {
			tot_qty_tablesize.push($(this).find('.tot_qty_tablesize').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tablesize_fin = tot_qty_tablesize.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_size_label",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tablesize_fin,
			id: id,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#save_size_label_button').addClass('hidden')
		},
		error: function (data) {

		}
	})
		$('.size_label-card span').text('Order Approved')
		$('.size_label-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}

/**
 * This Function is used to Update Solid Carton Stickers.
 * 
 * @return void
 */
function setsolid_cartonStatusOs(){
	var tot_qty_table_poly = [];
		var tot_qty_carton = [];
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		
		$('#solid_cartonform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_carton').val() != undefined) {
				tot_qty_carton.push($(this).find('.tot_qty_table_carton').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		tot_qty_carton_fin = tot_qty_carton.join(',');
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');

		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_solid_stickers_os",
			type: "Post",
			data: {
				tot_qty_carton: tot_qty_carton_fin,
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				uvc: $('#uvc_id').val(),
				height: $('#modal_height').val(),
				width: $('#width').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				carton_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#solid_cartonform .left-panelos").removeClass('active');
				$("#solid_cartonform .right-panelos").removeClass('active');
				$("#solid_cartonform .backdrop").removeClass('active');
				$('#solid_cartonform .scalable').removeClass('active');
			},
			error: function (data) {

			}
		})
}
/**
 * This Function is used to Update Solid Poly Bag Stickers.
 * 
 * @return void
 */
function setsolid_poly_bagStatusOs(){
	var tot_qty_table_poly = [];
		var tot_qty_poly = [];
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		
		$('#solid_poly_bagform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		tot_qty_poly_fin = tot_qty_poly.join(',');
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_solid_poly_bag_stickers_os",
			type: "Post",
			data: {
				tot_qty_poly: tot_qty_poly_fin,
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				uvc: $('#uvc_id').val(),
				height: $('#modal_height').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				poly_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#solid_poly_bagform .left-panelos").removeClass('active');
				$("#solid_poly_bagform .right-panelos").removeClass('active');
				$("#solid_poly_bagform .backdrop").removeClass('active');
				$('#solid_poly_bagform .scalable').removeClass('active');
			},
			error: function (data) {

			}
		})
}


/**
 * This Function is used to Update Standard Carton Stickers.
 * 
 * @return void
 */
function setstandard_cartonStatusOs(){
	
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		$('#standard_cartonform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
	
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_standard_carton_stickers_os",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				carton_id: $('#exist_id').val(),
				height: $('#modal_height').val(),
				width: $('#width').val(),
				total_polybag: $('#total_polybag').val(),
				final_order_qty_sets: $('.standard_carton_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				age: $('#age').val(),
				no_of_carton: $('#needed_modal').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#standard_cartonform .left-panelos").removeClass('active');
				$("#standard_cartonform .right-panelos").removeClass('active');
				$("#standard_cartonform .backdrop").removeClass('active');
				$('#standard_cartonform .scalable').removeClass('active');
			},
			error: function (data) {

			}
		})
}
/**
 * This Function is used to Update Standard Poly Bag Stickers.
 * 
 * @return void
 */
function setstandard_poly_bagStatusOs(){
	var size = [];
		var barcode = [];
		var uvc_tco = [];

		$('#standard_poly_bagform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
	var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_standard_poly_bag_stickers_os",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				height: $('#modal_height').val(),
				final_order_qty_sets: $('.standard_poly_bag_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				age: $('#age').val(),
				poly_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#standard_poly_bagform .left-panelos").removeClass('active');
				$("#standard_poly_bagform .right-panelos").removeClass('active');
				$("#standard_poly_bagform .backdrop").removeClass('active');
				$('#standard_poly_bagform .scalable').removeClass('active');
			},
			error: function (data) {

			}
		})
}

/**
 * This Function is used to set Heat Transfer in local storage.
 * 
 * @return void
 */

function setheat_transferStatusOs() {

    if ($('#heat_transferform').find('#exist_id').val() != "") {
        var exist_id = $('#heat_transferform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("heat_transfer", exist_id);
        setHeatTransferOs(localStorage.getItem("heat_transfer"))
        $('#heat_transfer_template_error').addClass('hidden')
        $("#heat_transferform .left-panelos").removeClass('active');
        $("#heat_transferform .right-panelos").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    } else {
        $('#heat_transfer_template_error').removeClass('hidden')
    }
}

function setHeatTransferOs(id) {
  
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
	console.log(template_id)
	template_id = template_id[0].id;
	
	var tot_qty_tableheat = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#heat_transferform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tableheat').val() != undefined) {
			tot_qty_tableheat.push($(this).find('.tot_qty_tableheat').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tableheat_fin = tot_qty_tableheat.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_heat_transfer",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tableheat_fin,
			id: id,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#save_heat_transfer_button').addClass('hidden')
		},
		error: function (data) {

		}
	})
		$('.heat_transfer-card span').text('Order Approved')
		$('.heat_transfer-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}


function setHangTagStatusOs() {
    if ($('#hangtagform').find('#exist_id').val() != "") {
        var exist_id = $('#hangtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }

    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("hang_tag", exist_id);
        setHangTagOs(localStorage.getItem("hang_tag"))
        $('#hang_template_error').addClass('hidden')
        $("#hangtagform .left-panelos").removeClass('active');
        $("#hangtagform .right-panelos").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    } else {
        $('#hang_template_error').removeClass('hidden')
    }
}

function setHangTagOs(id) {
	
	var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
	template_front_id = template_id[0].id;
	var template_back_id = $('#hang_tag_id').val();
	var onesizecm = $('.onesizecm').val();
	var onesizein = $('.onesizein').val();
	
	var tot_qty_tablehang = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	var barcode = [];
	$('#hangtagform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablehang').val() != undefined) {
			tot_qty_tablehang.push($(this).find('.tot_qty_tablehang').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			barcode.push($(this).find('.barcode').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	console.log(tot_qty_tablehang)
	console.log(uvc_tco)
	console.log(size)
	tot_qty_tablehang_fin = tot_qty_tablehang.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	barcode_fin = barcode.join(',');
	var buyer = localStorage.getItem("buyer");
//	alert()
//	alert($('#parcel_id').val());
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_hang_tag",
		type: "Post",
		data: {
			
			template_front_id: template_front_id,
			template_back_id: template_back_id,
			tot_qty_table: tot_qty_tablehang_fin,
			id: id,
			cm:onesizecm,
			in:onesizein,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			barcode: barcode_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#print_hang_button').removeClass('hidden')
			$('#save_hang_button').addClass('hidden')
		},
		error: function (data) {

		}
	})
		$('.hang-card span').text('Order Approved')
		$('.hang-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}