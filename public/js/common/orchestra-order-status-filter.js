/**
 * Method to display orchestra order status page 
 */
orderStatus = (user_type) => {
    $('.main-content').html('');
    $('.top-content').html('');
    $('.template-error').html('');
    $("#orch_filter_season").html("");
    $("#orch_filter_style").html("");
    var start_date = null;
    var end_date = null;
    $('.top-content').html(`
                <form id="orchestra_order_filter_form" role="form">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" placeholder="Start Date - End Date" class="form-control input-sm orch-filter-daterange" autocomplete="off" multiple style="width:100%" name="orch_filter_daterange" />
                        <input type="hidden" id="start_date" name="start_date">
                        <input type="hidden" id="end_date" name="end_date">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_style" name="orch_filter_style[]" title="Select Style Number" multiple data-selected-text-format="count" data-done-button="true" data-actions-box="true" data-live-search="true" data-size="10"></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_season" name="orch_filter_season[]" title="Select Season" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10"></select>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_orderstatus" name="orch_filter_orderstatus[]" title="Select Order Status" data-actions-box="true" multiple data-done-button="true" data-selected-text-format="count" data-live-search="true" data-size="10">
                                <option value="Saved">Saved</option>
                                <option value="Order Placed">Submited</option>
                                <option value="Order Pending">Pending</option>
                                <option value="Order Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_artworkstatus" name="orch_filter_artworkstatus[]" title="Select Artwork Status" data-actions-box="true" multiple data-done-button="true" data-selected-text-format="count" data-live-search="true" data-size="10">
                                <option value="Approval Pending">Approval Pending</option>
                                <option value="Order Approved">Order Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_postatus" name="orch_filter_postatus[]" title="Select PO Status" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10">
                                <option value="PO Pending">PO Pending</option>
                                <option value="PO Uploaded">PO Uploaded</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="selectpicker form-control input-sm" id="orch_filter_pistatus" name="orch_filter_pistatus[]" title="Select PI Status" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10">
                                <option value="PI Approval Pending">PI Approval Pending</option>
                                <option value="PI Approved">PI Approved</option>
                                <option value="PI Rejected">PI Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary input-sm pull-right" type="button" onclick="getOrchestraOrders('${user_type}')" id="orch_filter_submit_btn">Submit</button>
                    </div>
                    </form>
                    <div class="col-md-3">
                        <button style='margin-bottom: 15px;margin-right: 15px;' id='po-file-upload-button'
                            class='btn btn-sm btn-primary pull-right' type='button'>Update PO</button>
                    </div>
                </div>
                <div style="padding-top:15px;" id="orchestraStatusTable"></div>
                `);
    getFilterData(start_date, end_date, user_type)

    $('#po-file-upload-button').on('click', () => {
        uploadOrchestraPOFile()
    });

}


getFilterData = (start_date, end_date, user_type) => {
    $("#orch_filter_season").html("");
    $("#orch_filter_style").html("");
    $.ajax({
        url: base_url + "/"+user_type+"/orchestra/orchestra-orders-filter",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token,
            start_date: start_date,
            end_date: end_date
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            200: function (data) {

                $(data.data).each(function (index, value) {
                    $("#orch_filter_season").append("<option value='" + value.season_code + "'>" + value.season_code + "</option>");
                })

                var already_array = [];
                var options = "<option>Select Style Number</option>";
                $(data.style_no).each(function (index, value) {
                    if (already_array.includes(value.uvc) != true) {
                        $("#orch_filter_style").append("<option value='" + value.uvc + "'>" + value.uvc + "</option>");
                        already_array.push(value.uvc)
                        options += "<option value=" + value.uvc + ">" + value.uvc + "</option>";
                    }

                })

                if (document.getElementsByClassName("bs-donebutton")) {
                    $('.bs-donebutton button').text("OK")
                }

                var d = new Date();
                d.setMonth(d.getMonth() - 6);
                var startmoment = moment().subtract(12, 'month');
                var endmoment = moment();

                $('input[name="orch_filter_daterange"]').daterangepicker({
                        "minDate": startmoment,
                        "maxDate": endmoment,
                        autoUpdateInput: false,
                        locale: {
                            format: 'DD/MM/YYYY',
                            cancelLabel: 'Clear'
                        }
                    },
                    function (start, end, label) {
                        var start_date = start.format('YYYY-MM-DD');
                        var end_date = end.format('YYYY-MM-DD');
                        $('#start_date').val(start_date);
                        $('#end_date').val(end_date);
                        getFilterData(start_date, end_date, user_type)
                    });

                $('input[name="orch_filter_daterange"]').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                });

                $('input[name="orch_filter_daterange"]').on('cancel.daterangepicker', function (ev, picker) {
                    $(this).val('')
                    picker.setStartDate({})
                    picker.setEndDate({})
                });

                $('#uvc_select_div').html('<label>Select Style Number</label><select id="style_num" class="form-control selectpicker" data-live-search="true">' + options + '</select>');

                $(".selectpicker").val('');
                $('.selectpicker').selectpicker('refresh');

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

uploadOrchestraPOFile = () => {
    $('#po-file-upload-group').modal("show");
    $('#style_num').selectpicker('refresh');
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
    $('#style_num').change(function () {
        if ($(this).val() != "Select Style Number") {
            $('#new_po_div_status').addClass('hidden');
            $('#new_po1_status').addClass('hidden');
            $('#map_po_status').addClass('hidden');
            $('#update_new_po_status').addClass('hidden');
            $('#update_po_status').addClass('hidden');
            $('#new_pos_status').addClass('hidden');
            $('#map_po_div_status').addClass('hidden');
            $('#exist_artwork_div_status').removeClass('hidden')

            var selected = $(this).find('option:selected');
            var styleNum = selected.val();
            var zone = selected.data('zone');
            $.ajax({
                url: base_url + "/admin/tesco/get-artworks",
                type: "get",
                dataType: "json",
                data: {
                    styleNum: styleNum,
                    zone: zone
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
                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3'].concat(data.data['wcl'].concat(data.data['tsl2'].concat(data.data['tcl5']).concat(data.data['swingtickets'])))))))));
                        var coun = 0;
                        var options = "<option>Select PO</option>";
                        var unique_po = []
                        var old_po = ""
                        var new_po_div_status = 0;
                        $(newObj).each(function (index1, value1) {
                            //alert(value1.tableName.length)
                            //console.log(value1.TemplateName.length)
                            if (value1.TemplateName.length <= 5) {
                                var margin_right = "160px";
                            } else {
                                var margin_right = "85px";
                            }

                            if (value1.purchase_order_no != "" && value1.purchase_order_no != null && value1.po_file != "" && value1.po_file != null) {
                                if (old_po != value1.purchase_order_no) {
                                    options += "<option>" + value1.purchase_order_no + "</option>";
                                }
                                old_po = value1.purchase_order_no
                                coun++;
                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">' + value1.TemplateName + '<span style="display: inline-block;margin-left: ' + margin_right + ';" class="tab"></span>' + value1.po_number + '<span style="display: inline-block;margin-left: 160px;" class="tab"></span>' + value1.purchase_order_no + '</li>')
                                new_po_div_status++;

                            } else {
                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">' + value1.TemplateName + '<span style="display: inline-block;margin-left: ' + margin_right + ';" class="tab"></span>' + value1.po_number + '<span style="display: inline-block;margin-left: 160px;" class="tab"></span>-' + '</li>')
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
                        $('#update_new_po_status').unbind().click(function () {

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

                                        var form_data = new FormData();
                                        form_data.append('id', aa);
                                        form_data.append('po_file', new_po_file);
                                        form_data.append('po', new_po_no);
                                        form_data.append('_token', csrf_token);
                                        $.ajax({
                                            url: base_url + "/admin/tesco/update-po",
                                            dataType: 'text',
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            data: form_data,
                                            type: 'post',
                                            beforeSend: function () {
                                                $(".loader").show();
                                            },
                                            success: function (data) {
                                                $('#ukstylenoos').selectpicker('val', styleNum);
                                                $('#po-file-upload-group').modal('hide')
                                                setTimeout(function () {
                                                    $(".loader").hide();
                                                    submitOs(this)
                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                    showSnackbar();
                                                }, 10000);

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

                        $('#new_pos_status').unbind().click(function () {
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

                                        var form_data = new FormData();
                                        form_data.append('id', aa);
                                        form_data.append('po_file', new_po_file);
                                        form_data.append('po', new_po_no);
                                        form_data.append('_token', csrf_token);
                                        $.ajax({
                                            url: base_url + "/admin/tesco/update-po",
                                            dataType: 'text',
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            data: form_data,
                                            type: 'post',
                                            beforeSend: function () {
                                                $(".loader").show();
                                            },
                                            success: function (data) {
                                                $('#ukstylenoos').selectpicker('val', styleNum);
                                                $('#po-file-upload-group').modal('hide')
                                                setTimeout(function () {
                                                    $(".loader").hide();
                                                    submitOs(this)
                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                    showSnackbar();
                                                }, 10000);
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
                        $('#update_po_status').unbind().click(function () {

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

                                    $.ajax({
                                        url: base_url + "/admin/tesco/copy-po",
                                        type: "Post",
                                        dataType: "json",
                                        data: {
                                            _token: csrf_token,
                                            id: aa,
                                            po: $('#select_po_status').val()
                                        },
                                        beforeSend: function () {
                                            $(".loader").show();
                                        },
                                        success: function (data) {
                                            $('#ukstylenoos').selectpicker('val', styleNum);
                                            $('#po-file-upload-group').modal('hide')
                                            setTimeout(function () {
                                                $(".loader").hide();
                                                submitOs(this)
                                                $('.snackbar_span').html('PO Updated Successfully');
                                                showSnackbar();
                                            }, 10000);
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
}


getOrchestraOrders = (user_type) => {
    $.ajax({
        url: base_url + "/"+user_type+"/orchestra/get-orchestra-orders",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        dataType: "json",
        data:$('#orchestra_order_filter_form').serialize(),
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
                console.log(data);
                var path = base_url + "/admin/orchestra/get-orchestra-orders";
                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))))))));

                newObj.sort(function (obj1, obj2) {
                    var date1 = obj1.OrderDate;
                    var date2 = obj2.OrderDate;
                    if (date1 > date2) return -1;
                    if (date1 < date2) return 1;
                    return 0;
                });
                $('#orchestraStatusTable').html(orchestraStatusTable(newObj, path));
                var $tableSel = $('#datatable-orchestra-orders');
                $tableSel.DataTable({
                    "columnDefs": [{
                        "targets": '_all',
                        "createdCell": function (td, cellData, rowData, row, col) {
                            $(td).css('padding', '5px')
                        }
                    }],
                    "pageLength": 25,
                    "order": [
                        [10, "desc"]
                    ],
                    scrollX:        true,
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
                        $("#datatable-orchestra-orders_filter input").addClass('form-control input-sm');
                        $("#datatable-orchestra-orders_filter input").attr('placeholder', 'Data Search');
                        $('#datatable-orchestra-orders_length select').addClass('form-control input-sm');
                    }
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