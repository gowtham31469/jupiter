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
                <div class="col-md-5"><input type="text" placeholder="Start Date - End Date" class="form-control startdateos" autocomplete="off" multiple style="width:100%" name="startdateos" /></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="ukstylenoos" title="Select Style Number" multiple data-selected-text-format="count" data-done-button="true" data-actions-box="true" data-live-search="true" data-size="10"></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="seasonos" title="Select Season" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10"></select></div></div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                <button style='margin-bottom: 15px;margin-right: 15px;' id='artwork-approve-upload-button' class='btn btn-sm btn-primary pull-right' type='button'>Approve Artwork</button>
                </div>
                </div>
                <div class="row" style="margin-top: 15px;">
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="orderstatusos" title="Select Order Status" data-actions-box="true" multiple data-done-button="true" data-selected-text-format="count" data-live-search="true" data-size="10"><option value="Saved">Saved</option><option value="Order Placed">Submited</option><option value="Order Pending">Pending</option><option value="Order Cancelled">Cancelled</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="artworkstatusos" title="Select Artwork Status" data-actions-box="true" multiple data-done-button="true" data-selected-text-format="count" data-live-search="true" data-size="10"><option value="Approval Pending">Approval Pending</option><option value="Order Approved">Order Approved</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="postatusos" title="Select PO Status" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="PO Pending">PO Pending</option><option value="PO Uploaded">PO Uploaded</option></select></div></div>
                <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="pistatusos" title="Select PI Status" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10"><option value="PI Approval Pending">PI Approval Pending</option><option value="PI Approved">PI Approved</option><option value="PI Rejected">PI Rejected</option></select></div></div>
                <div class="col-md-1">
                <button class="btn btn-primary input-sm pull-right" onclick="submitOs(this)" id="submitos">Submit</button></div>
                <div class="col-md-3"><button style='margin-bottom: 15px;margin-right: 15px;' id='po-file-upload-button' class='btn btn-sm btn-primary pull-right' type='button'>Update PO</button></div>
                </div>
                <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                <input type="hidden" id="start_date">
                <input type="hidden" id="end_date">
                </div>
                </div>
                <div style="padding-top:15px;" id="OrderStatusTable"></div>
                `);

                $(data.data).each(function(index,value){
                    $("#seasonos").append("<option value='"+ value.season_code + "'>" + value.season_code + "</option>");
                })

                $(data.style_no).each(function(index,value){
                    if(value.type == "stickers")
                    {
                        if (value.zone == "CE") {
                            $("#ukstylenoos").append("<option data-id='CE' value='"+ value.ce_style_ref + "'>" + value.ce_style_ref + "</option>");
                        } else {
                            $("#ukstylenoos").append("<option data-id='UK' value='"+ value.uk_style_ref + "'>" + value.uk_style_ref + "</option>");
                        }
                    }
                    else
                    {
                        $("#ukstylenoos").append("<option data-id='labels' value='"+ value.uk_style_ref +"/"+ value.ce_style_ref + "'>"+ value.uk_style_ref +"/"+ value.ce_style_ref + "</option>");
                    }
                })
                
                $('.selectpicker').selectpicker();
                if(document.getElementsByClassName("bs-donebutton")) {
                    $('.bs-donebutton button').text("OK")
                }

                var d = new Date();
                var maxDate = d.toLocaleDateString();
                d.setMonth(d.getMonth() - 6);
                var minDate = d.toLocaleDateString();

                //alert(minDate)
                //alert(maxDate)


                var startmoment = moment().subtract(12, 'month');
                var endmoment = moment();

                $('input[name="startdateos"]').daterangepicker({
                    "minDate": startmoment,
                    "maxDate": endmoment,
                    autoUpdateInput: false,
                    locale: { format: 'DD/MM/YYYY' }
                },
                function (start, end, label) {
                    var start_date = start.format('YYYY-MM-DD');
                    var end_date = end.format('YYYY-MM-DD');
                    $('#start_date').val(start_date);
                    $('#end_date').val(end_date);
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
                                            $("#ukstylenoos").append("<option data-id='CE' value='"+ value.ce_style_ref + "'>" + value.ce_style_ref + "</option>");
                                        }
                                    } else {
                                        if (uniqueNames.indexOf(value.uk_style_ref) === -1) {
                                            $("#ukstylenoos").append("<option data-id='UK' value='"+ value.uk_style_ref + "'>" + value.uk_style_ref + "</option>");
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


                $('input[name="startdateos"]').on('apply.daterangepicker', function(ev, picker){
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                });

                $('input[name="startdateos"]').on('cancel.daterangepicker', function(ev, picker) {
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $(this).val('');
                });

                var options = "<option>Select Style Number</option>";
                $(data.style_no).each(function(index,value){
                    if(value.type == "stickers")
                    {
                        if (value.zone == "CE") {
                            options += "<option data-zone=" + value.zone + ">" + value.ce_style_ref + "</option>";
                        } else {
                            options += "<option data-zone=" + value.zone + ">" + value.uk_style_ref + "</option>";
                        }
                    }
                    else
                    {
                        options += "<option data-zone='labels'>" + value.uk_style_ref+"/"+ value.ce_style_ref  + "</option>";
                    }
                })

                $('#artwork-approve-upload-button').click(function () {
                    $('#approve-upload-group').modal("show")
                    $('#uvc_approve_select_div').html('<label>Select Style Number</label><select id="style_approve_num" class="form-control selectpicker" data-live-search="true">' + options + '</select>')
                    $('#style_approve_num').selectpicker('refresh');
                    $('#style_approve_num').change(function () {
                        if ($(this).val() != "Select Style Number") 
                        {
                            $('#approve_button').removeClass('hidden')
                            var selected = $(this).find('option:selected');
                            var styleNum = selected.val();
                            var zone = selected.data('zone');
                            $.ajax({
                                url: base_url + "/admin/tesco/get-approve-artworks",
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
                                        $('#exist_artwork_approve_div_status').removeClass('hidden')
                                        $('#approve-upload-group').find('#exist_artwork_approve_div_status ul').html("");
                                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3'].concat(data.data['wcl'].concat(data.data['tsl2'].concat(data.data['tcl5'])))))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var unique_po = []
                                        var old_po = ""
                                        var new_po_div_status = 0;
                                        $(newObj).each(function (index1, value1) {

                                            if (value1.order_status != "Order Approved") {
                                                $('#approve-upload-group').find('#exist_artwork_approve_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">' + value1.TemplateName +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>'+ value1.po_number+ '</li>')
                                                

                                            } else {
                                                if (old_po != value1.purchase_order_no) {
                                                    options += "<option>" + value1.purchase_order_no + "</option>";
                                                }
                                                old_po = value1.purchase_order_no
                                                coun++;
                                                $('#approve-upload-group').find('#exist_artwork_approve_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">' + value1.TemplateName +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>'+ value1.po_number+ '</li>')
                                                new_po_div_status++;
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

                                        $('#approve_submit').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_approve_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {

                                                // $('#update_new_po_status').removeClass('hidden')
                                                // $('#update_po_status').addClass('hidden')
                                                // $('#new_pos_status').addClass('hidden')
                                                // $('#new_po_div_status').removeClass('hidden')
                                                // $('#map_po_div_status').addClass('hidden')
                                                // $('#exist_artwork_div_status').removeClass('hidden')

                                            } else {
                                                //$('#check_error_status').removeClass('hidden')
                                            }

                                        });

                                        $('#approve_button').on('click', function (event) {
                                            $count_li = 0;
                                            $('.list-group li').each(function (key, val) {
                                                if ($(this).hasClass('active')) {
                                                    $count_li++;
                                                }
                                            });
                                            if ($count_li > 0) {
                                                $('#check_approve_error_status').addClass('hidden')
                                                var a = [];
                                                $('#exist_artwork_approve_div_status input[type=checkbox]').each(function () {
                                                    if (this.checked) {
                                                        a.push($(this).parent().attr('data-id'));
                                                    }
                                                });
                                                var aa = a.join(',');
                                                $.ajax({
                                                    url: base_url + "/admin/tesco/approve-artworks-bulk",
                                                    type: "Post",
                                                    dataType: "json",
                                                    data: {
                                                        _token: csrf_token,
                                                        id: aa
                                                    },
                                                    beforeSend: function () {

                                                    },
                                                    success: function (data) {
                                                        //alert("asda");
                                                        $('#ukstylenoos').selectpicker('val', styleNum);
                                                        submitOs(this)
                                                        $('#approve-upload-group').modal("hide")
                                                        $('.snackbar_span').html('Order Approved Successfully');
                                                        showSnackbar();
                                                        //location.reload()
                                                    },
                                                    error: function (data) {

                                                    },
                                                    complete: function () {
                                                        $(".loader").hide();
                                                    }
                                                })
                                            }
                                            else{
                                                $('#check_approve_error_status').removeClass('hidden')
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
                                }
                            })
                        }
                        else
                        {
                            $('#approve_button').addClass('hidden')
                            $('#check_approve_error_status').addClass('hidden')
                            $('#exist_artwork_approve_div_status').addClass('hidden')
                        }

                    })

                })

                $('#po-file-upload-button').click(function () {
                    $('#po-file-upload-group').modal("show")
                    $('#uvc_select_div').html('<label>Select Style Number</label><select id="style_num" class="form-control selectpicker" data-live-search="true">' + options + '</select>')
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
                                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3'].concat(data.data['wcl'].concat(data.data['tsl2'].concat(data.data['tcl5'])))))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var unique_po = []
                                        var old_po = ""
                                        var new_po_div_status = 0;
                                        $(newObj).each(function (index1, value1) {
                                            //alert(value1.tableName.length)
                                            //console.log(value1.TemplateName.length)
                                            if(value1.TemplateName.length <= 5)
                                            {
                                                var margin_right="160px";
                                            }
                                            else
                                            {
                                                var margin_right="85px";
                                            }

                                            if (value1.purchase_order_no != "" && value1.purchase_order_no != null && value1.po_file != "" && value1.po_file != null) {
                                                if (old_po != value1.purchase_order_no) {
                                                    options += "<option>" + value1.purchase_order_no + "</option>";
                                                }
                                                old_po = value1.purchase_order_no
                                                coun++;
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">' + value1.TemplateName +'<span style="display: inline-block;margin-left: '+margin_right+';" class="tab"></span>'+ value1.po_number +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>'+ value1.purchase_order_no+ '</li>')
                                                new_po_div_status++;

                                            } else {
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">' + value1.TemplateName +'<span style="display: inline-block;margin-left: '+margin_right+';" class="tab"></span>'+ value1.po_number +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>-'+ '</li>')
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
                                                                setTimeout(function(){
                                                                    $(".loader").hide();
                                                                    submitOs(this)
                                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                                    showSnackbar();
                                                                }, 10000);

                                                                
                                                                //tescoOrderStatus()
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
                                                                setTimeout(function(){
                                                                    $(".loader").hide();
                                                                    submitOs(this)
                                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                                    showSnackbar();
                                                                }, 10000);
                                                                //tescoOrderStatus()
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
                                                            setTimeout(function(){
                                                                $(".loader").hide();
                                                                submitOs(this)
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            }, 10000);
                                                            //tescoOrderStatus()
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

    var ukstylenoos = [];
    var stylenoos = [];
    $('#ukstylenoos :selected').each(function() {
        if($(this).data("id") == "CE")
        {
            stylenoos.push($(this).val());
        }
        else if($(this).data("id") == "labels")
        {
            var splitstyle = $(this).val().split("/");
            ukstylenoos.push(splitstyle[0]);
            stylenoos.push(splitstyle[1]);
        }
        else
        {
            ukstylenoos.push($(this).val());
        }
    })
    var seasonos = $('#seasonos').val();
    var orderstatusos = $('#orderstatusos').val();
    var artworkstatusos = $('#artworkstatusos').val();
    var postatusos = $('#postatusos').val();
    var pistatusos = $('#pistatusos').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
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
                $('#OrderStatusTable').html(tescoStatusTable(data.data, "path"))
                var $tableSel = $('#datatable-tesco-orders');
                var tableSelection = $tableSel.DataTable({
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
                        null,
                        null,
                        null,
                    ],
                    initComplete: function () {
                        $("#datatable-tesco-orders_filter input").addClass('form-control input-sm');
                        $("#datatable-tesco-orders_filter input").attr('placeholder', 'Data Search');
                        $('#datatable-tesco-orders_length select').addClass('form-control input-sm');
                        // $('.datatable-tesco-orders .filters td').each( function () {
                        //     if($(this).text() != "")
                        //     {
                        //         var title = $('.datatable-tesco-orders thead th').eq( $(this).index() ).text();
                        //         $(this).html( '<input style="width:70%" type="text" placeholder="Search '+title+'" />' );
                        //     }
                        // } );
                    }
                });

                // tableSelection.columns().eq( 0 ).each( function ( colIdx ) {
                //     $( 'input', $('.filters td')[colIdx] ).on( 'keyup change', function () {
                //         tableSelection
                //             .column( colIdx )
                //             .search( this.value )
                //             .draw();
                //     } );
                // } );
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
    var th = "<th style='text-align: center;'>Style No</th><th style='text-align: center;display:none'>PO Number</th><th style='text-align: center;'>OCN No</th><th style='text-align: center;'>Season Code</th><th style='text-align: center;'>Buyer Po</th><th style='text-align: center;'>Zone</th><th style='text-align: center;'>Reference No</th><th style='text-align: center;'>Template Type</th><th style='text-align: center;'>Company</th><th style='text-align: center;'>Order Date</th><th style='text-align: center;'>Delivery Date</th><th style='text-align: center;'>Order Status</th><th style='text-align: center;'>Artwork</th><th style='text-align: center;'>Artwork Status</th><th style='text-align: center;'>PI Status</th><th style='text-align: center;'>PO Status</th><th style='text-align: center;'>PO Number</th><th style='text-align: center;'>Print Proof</th><th style='text-align: center;'>Update Po</th>";
    var thfilter = "<td style='text-align: center;'></td><td style='text-align: center;display:none'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'></td><td style='text-align: center;'>PO Number</td><td style='text-align: center;'></td>";
    var tr = "";
    var columnsIn = data[0];
    // for (var key in columnsIn) {
    //     if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uk_style_ref" && key != "ce_style_ref" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty" && key != "template_file") {
    //         if (key == "TemplateName") {
    //             th += "<th style='text-align: center;'>Reference No</th>";
    //         } else if (key == "order_status") {
    //             th += "<th style='text-align: center;'>Order Status</th>";
    //         } else if (key == "artwork_status") {
    //             th += "<th style='text-align: center;'>Artwork Status</th>";
    //         } else {
    //             th += "<th style='text-align: center;'>" + key + "</th>";
    //         }
    //     }
    // }

    var template_array = [];
    $('#objectDiv').html("")
    $.each(data, function (index, value) {
        if(template_array.includes(value.TemplateName.replace(/ /g, "")+"_"+value.template_id) == false)
        {
            template_array.push(value.TemplateName.replace(/ /g, "")+"_"+value.template_id)
            if(value.tableName == "tesco_washcare_orders")
            {
                var temp_file = value.template_file.split(',');
                $('#objectDiv').append('<object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_modal" class="center-block tesco_svg" data="' + temp_file[0]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_back_modal" class="center-block tesco_svg" data="' + temp_file[1]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_back_text_modal" class="center-block tesco_svg" data="' + temp_file[2]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_front_modal" class="center-block tesco_svg" data="' + temp_file[3]+'" type="image/svg+xml"></object>')
            }
            else
            {
                $('#objectDiv').append('<object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_modal" class="center-block tesco_svg" data="' + value.template_file+'" type="image/svg+xml"></object>')
            }
        }
        var action_status = false;
        tr += '<tr class="trClass" id="' + value.tableName + '_' + value.tableID + '">';
        for (var key in columnsIn) {
            if (key != "template_file") {
                if (key == "PO Number" && value['po_required'] == 1 && value['is_order_count_empty'] == 0 && value["order_status"] != "Order Cancelled" && value["order_status"] != "Saved") {
                    var po_file = value['po_file'];

                    let customer_po_number = value[key] === null ? '-' : value[key];
                    customer_po_number = customer_po_number === "" ? '-': customer_po_number;
                    tr += '<td class="text-center">' + customer_po_number + '</td>';
                } else if (key == "OrderDate") {
                    if (value['is_order_count_empty'] == 0 && value['order_status'] != "Saved"){
                        var orderDate = moment(value[key]).format('DD-MM-YYYY');
                        tr += '<td align="center">' + orderDate + '</td>';
                    } else {
                        tr += '<td align="center"> - </td>';
                    }
                } else if (key == "PI Status") {
                    var str = value[key];
                    var style_no = "";
                    if(value.tableName == "tesco_washcare_orders")
                    {
                        style_no = value.uk_style_ref+"/"+value.ce_style_ref;
                    }
                    else
                    {
                        if (zone == "CE") {
                            style_no = value.ce_style_ref;
                        } else {
                            style_no = value.uk_style_ref;
                        }
                    }
                    if (value['pi_required'] == 1 && value['is_order_count_empty'] == 0) {
                        if (str === 'PI Approved') {
                            action_status = true;
                            status = str.replace(/ /g, "");
                            status_text = '<span class="pi_class label label-' + status + '">' + value[key] + '</span>';
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")>'+status_text+'</a></td>';
                        }
                        else if (str === 'PI Rejected') {
                            status = str.replace(/ /g, "");
                            status_text = '<span class="pi_class label label-' + status + '">' + value[key] + '</span>';
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")>'+status_text+'</a></td>';
                        } else {
                            if (value['order_status'] === 'Order Placed' || value['order_status'] === 'Order Approved') {
                                status_text = '<span class="pi_class label label-PIApprovalPending"> PI Approval Pending </span>';
                                tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI("' + value.tableID + '","' + value.tableName + '","' + action_status + '","' + style_no + '","' + value.template_id + '","' + value.BuyerPO + '","' + value.TemplateName.replace(/ /g, "") + '","' + order_flag + '")>'+status_text+'</a></td>';
                            } else {
                                tr += '<td align="center"><span class="pi_class label label-default"> PI Pending </span></td>';
                            }
                        }
                    } else {
                        tr += '<td align="center"> - </td>';
                    }

                } else if (key == "Artwork") {

                    if (value['order_status'] != 'Saved') {
                        var zone = value.Zone
                        var style_no = "";
                        if(value.tableName == "tesco_washcare_orders")
                        {
                            style_no = value.uk_style_ref+"/"+value.ce_style_ref;
                        }
                        else
                        {
                            if (zone == "CE") {
                                style_no = value.ce_style_ref;
                            } else {
                                style_no = value.uk_style_ref;
                            }
                        }
                        if(value.tableName == "tesco_washcare_orders")
                        {
                            tr += '<td align="center"><a data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value.order_status + '" class="" href="javascript:void(0)" onclick=downloadArtwork("' + value.TemplateName.replace(/ /g, "") +"_"+value.template_id+'_modal","'+value.TemplateName.replace(/ /g, "")+'","'+style_no+'","'+value.BuyerPO+'","' + value.tableID + '","' + value.order_flag + '",this,"'+value.template_id+'")><span style="cursor:pointer" class="fa fa-download"></span></a></td>';
                        }
                        else
                        {
                            tr += '<td align="center"><a data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value.order_status + '" class="" href="javascript:void(0)" onclick=downloadArtwork("' + value.TemplateName.replace(/ /g, "") +"_"+value.template_id+'_modal","'+value.TemplateName.replace(/ /g, "")+'","'+style_no+'","'+value.BuyerPO+'","' + value.tableID + '","' + value.order_flag + '",this,"'+value.template_id+'")><span style="cursor:pointer" class="fa fa-download"></span></a></td>';
                        }
                        
                    }
                    else{
                        tr += '<td align="center">-</td>';
                    }

                        

                }else if (key == "Print Proof") {
                    if (value['order_status'] != 'Saved') {
                        var zone = value.Zone
                        var style_no = "";
                        if(value.tableName == "tesco_washcare_orders")
                        {
                            style_no = value.uk_style_ref+"/"+value.ce_style_ref;
                        }
                        else
                        {
                            if (zone == "CE") {
                                style_no = value.ce_style_ref;
                            } else {
                                style_no = value.uk_style_ref;
                            }
                        }
                        if(value.tableName == "tesco_washcare_orders")
                        {
                            var temp_file = value.template_file.split(',');
                            tr += '<td align="center"><a data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value.order_status + '" class="" href="javascript:void(0)" onclick=downloadPrint("' + value.TemplateName.replace(/ /g, "") +"_"+value.template_id+'_modal","'+value.TemplateName.replace(/ /g, "")+'","'+style_no+'","'+value.BuyerPO+'","' + value.tableID + '","' + value.order_flag + '",this,"'+value.template_id+'")><span style="cursor:pointer" class="fa fa-download"></span></a></td>';
                        }
                        else
                        {
                            tr += '<td align="center"><a data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value.order_status + '" class="" href="javascript:void(0)" onclick=downloadPrint("' + value.TemplateName.replace(/ /g, "") +"_"+value.template_id+'_modal","'+value.TemplateName.replace(/ /g, "")+'","'+style_no+'","'+value.BuyerPO+'","' + value.tableID + '","' + value.order_flag + '",this,"'+value.template_id+'")><span style="cursor:pointer" class="fa fa-download"></span></a></td>';
                        }
                        
                    }
                    else{
                        tr += '<td align="center">-</td>';
                    }
                    var po_file = value['po_file'];
                     let customer_po_number = value['PO Number'] === null?'':value['PO Number'];
                     tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11" value="'+customer_po_number+'"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"' + value.tableID + '","' + value.tableName + '","' + po_file + '")><span class="fa fa-check"></span></button></span></div></div></td>';
                    

            }else if (key == "order_status") {
                    if (value['order_status'] != 'Saved') {
                        var str = value[key];
                        $status = str.replace(/ /g, "");

                        tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                    } else {
                        tr += '<td align="center"><span class="label label-draft" title="Please SUBMIT your order at the Create Order Page." data-placement="top">Order Saved</span></td>';
                    }

                } else if (key == "artwork_status") {
                    if (value['artwork_status'] == "Order Approved") {
                        var str = value[key];
                        $status = str.replace(/ /g, "");

                        tr += '<td align="center"><span class="label label-OrderApproved">Artwork Approved</span></td>';
                    } else if (value['artwork_status'] == "Order Placed"){
                        tr += '<td align="center"><button type="button" class="btn btn-xs table-btn-xs btn-primary artwork-approval-pending"  data-table-id="' + value.tableID +'" data-table-name="' + value.tableName +'" >Approval Pending</button></td>';
                    } else {
                        tr += '<td align="center"> - </td>';
                    }

                } else if (key == "PO Status") {
                    var str = value[key];
                    var status_text = " - ";
                    var po_file = value['po_file'];
                    var style_no = "";
                    if(value.tableName == "tesco_washcare_orders")
                    {
                        style_no = value.uk_style_ref+"/"+value.ce_style_ref;
                    }
                    else
                    {
                        if (zone == "CE") {
                            style_no = value.ce_style_ref;
                        } else {
                            style_no = value.uk_style_ref;
                        }
                    }
                    if (value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                        if (value['PO Status'] === 'PO Uploaded') {
                            status = str.replace(/ /g, "");
                            status_text = '<span class="po_class label label-' + status + '">' + value['PO Status'] + '</span>';
                            tr += '<td align="center" class="po_status"><a href="javascript:void(0)" class="po-style" onclick=tescoPOUploaded(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '","'+style_no+'","'+value['PO Number']+'") > '+status_text+' </a></td>';
                        } else {
                            if (value['order_status'] === 'Order Placed' || value['order_status'] === 'Order Approved') {
                                status_text = '<span class="po_class label label-POPending"> PO Pending </span>';
                                tr += '<td align="center" class="po_status"><a href="javascript:void(0)" class="po-style" onclick=tescoPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '","'+style_no+'") > '+status_text+' </a></td>';
                            } else {
                                tr += '<td align="center class="po_status"><span class="po_class label label-default"> PO Pending </span></td>';
                            }
                        }
                    } else {
                        tr += '<td align="center class="po_status"> - </td>';
                    }

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
                    if(value.tableName == "tesco_washcare_orders")
                    {
                        style_no = value.uk_style_ref+"/"+value.ce_style_ref;
                    }
                    else
                    {
                        if (zone == "CE") {
                            style_no = value.ce_style_ref;
                        } else {
                            style_no = value.uk_style_ref;
                        }
                    }
                    

                    if (value['order_status'] != 'Saved'){
                        tr += '<td align="center" data-id="' + value.tableID + '" data-table="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" onclick=updateArtwork(this)>' + span_style + '' + style_no + '</a></td><td style="text-align: center;display:none">'+value['PO Number']+'</td>';
                    } else {
                        tr += '<td align="center" data-id="' + value.tableID + '" data-table="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" data-toggle= "modal" data-target="#savedOrderAlertBox">' + span_style + '' + style_no + '</a></td><td style="text-align: center;display:none">'+value['PO Number']+'</td>';
                    }

                } else if (key == "DeliveryDate") {

                    if (value['is_order_count_empty'] == 0 && value["order_status"] != "Order Cancelled" && value["order_status"] != "Saved") {
                        if (value[key] == null) {
                            tr += '<td><span style=display:none;>' + value.OrderDate + '</span><input class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '")  readonly></td>';
                        } else {

                            tr += '<td><span style=display:none;>' + value.OrderDate + '</span><input value="' + value[key] + '"class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px;" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '") readonly></td>';
                        }
                    } else {
                        tr += '<td><span style=display:none;>' + value.OrderDate + '</span> - </td>';
                    }
                } else if (key == "Zone") {

                    if(value.tableName == "tesco_washcare_orders")
                    {
                        tr += '<td> - </td>';
                    }
                    else
                    {
                        tr += '<td>' + value[key] + '</td>';
                    }

                } else if (key == "Template Type") {

                    if(value.tableName == "tesco_washcare_orders")
                    {
                        tr += '<td> - </td>';
                    }
                    else
                    {
                        tr += '<td>' + value[key] + '</td>';
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
    return '<table id="datatable-tesco-orders" class="table datatable-tesco-orders table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


$('body').on('focus', ".datepicker_recurring_start", function () {
    var count = $(this).closest('tr').find('.datepicker_recurring_start').attr("data-id");

    $(this).datepicker({
        autoclose: true,
        startDate: "+" + count + "d",
        format: 'dd-mm-yyyy'
    });
});


function downloadPrint(data, name,style_no,buyer_no,id,order_type,obj,art_temp_id) {

    var created_at = $(obj).parent().attr('data-orderdate');
    var status = $(obj).parent().attr('data-order_status');
    var template_id = $(obj).parent().attr('data-template_id');
    var table_name_print = $(obj).attr('data-table1');
    var buyer = "tesco";
	var a = document.getElementById(data);
    var svgDoc = a.contentDocument;
    

	
    
	var name2=name;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker" || name == "TSL2-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var name_count=0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print:table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			var zone=val.zone;
			localStorage.setItem("zone", zone);

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl3",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmallPrintManager(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				//jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmallPrintManager(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			} else {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				//jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmallPrintManager(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
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
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = '';
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
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
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
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
							}
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);
					var svg = encodeURI(str)
					svgarr.push(svg)

				}
			} else {
				var description =val.short_description.replace(/&/g,'&#038;');
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
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
				}
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);
				var svg = encodeURI(str)
				svgarr.push(svg)
			}
			name_count++;
		})
		},
		error: function(data) {

		},
		complete: function() {

		}
	})

		

	} else {
        if(table_name_print != "tesco_washcare_orders")
        {
            var svgarr = [];
		var qr_code_arr = [];
		var qr_code_check_arr = [];
		var ajax_count = 0;
		var name_count=0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
					$(data.order_details).each(function (key, val) {
						var zone=val.zone;
						localStorage.setItem("zone", zone);
			var svgRoot = svgDoc.documentElement;
						
		if (val.supplier_name != '') {
		    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
						
		if (val.po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (val.ce_style_ref != '') {
			svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (val.uk_style_ref != '') {
			svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (val.eqos_site_code != '') {
			svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		
					
						/** [ Dynamically change  svg department ] */
		if (val.department != '') {
			/** [Give font-size for department] */
			if (val.department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if (val.brand != '') {
			/** [Give font-size for brand] */
			if (val.brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
						
										
		var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
		var rfid = val.rfid_compliant == 0 ? "no" : "yes";
						
		/** [ Dynamically svg RFID ] */
		if (rfid != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (tag_at_source != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (val.season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (val.manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
						
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;
				//alert(data.tesco_item_master[0])
				svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "TCL5-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
				
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
					//jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeeanbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								var qr_code_fin = qr_code_arr.join(',_');
                                var qr_code_check_fin = qr_code_check_arr.join(',_');
								PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "TCL5-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
					//jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeitfbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								var qr_code_fin = qr_code_arr.join(',_');
                                var qr_code_check_fin = qr_code_check_arr.join(',_');
								PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TCL4-sticker") || (name == "TCL5-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
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
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
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
								
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
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
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
							qr_code_arr.push(val.qr_code)
                            qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} 
				else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
							if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

                                    }
                                    else if(name2 === "TCL5")
                                    {
                                        svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
                                        svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                        svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
                                        svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
                                        svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                        svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
                                        svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
                                        
                                    }
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

                                    }
                                    else if(name2 === "TCL5")
                                    {
                                        svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
                                        svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
                                        svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
                                        svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
                                        svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
                                        svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
                                        svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
                                        
                                    }
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
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
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)
					qr_code_arr.push(val.qr_code)
                    qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)

				}
			} else {
				if (description != '') {
					if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name2 === "THL1")
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
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
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
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
				qr_code_arr.push(val.qr_code)
				qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
			}


	
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				ajax_count++;
			}
			name_count++;
		})
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				if (ajax_count == data.order_details.length) {
					var svg_fin = svgarr.join(',_');
					var qr_code_fin = qr_code_arr.join(',_');
                    var qr_code_check_fin = qr_code_check_arr.join(',_');
					var zone = localStorage.getItem("zone");

					  PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);

				}
			}
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
        }
        else
        {
            var a_back = document.getElementById(name2+"_"+art_temp_id+"_back_modal");
    var svgDoc_back = a_back.contentDocument;

    var a_back_text = document.getElementById(name2+"_"+art_temp_id+"_back_text_modal");
    var svgDoc_back_text = a_back_text.contentDocument;

    var a_front_text = document.getElementById(name2+"_"+art_temp_id+"_front_modal");
    var svgDoc_front_text = a_front_text.contentDocument;
    
            var svgarr = [];
            var svgarr_back = [];
            var svgarr_back_text = [];
            var svgarr_front = [];
            ajax_count=0;
            $.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
                    
                    var svgRoot_back =svgDoc_back.documentElement;
                    var svgRoot_back_text =svgDoc_back_text.documentElement;
                    var svgRoot_front_text =svgDoc_front_text.documentElement;

                    if(data.order_details[0].bs_en_declaration == 0)
                    {
                        console.log("hai")
                        svgDoc_front_text.getElementById("sleeping_bag_name").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].baby_sleeping_bag_symbol == 0)
                    {
                        svgDoc_front_text.getElementById("tog_image").setAttribute('style', "visibility:hidden");
                    }

                    // if(data.order_details[0].tog == "" || data.order_details[0].tog == null)
                    // {
                    //     svgDoc_front_text.getElementById("tog_name").setAttribute('style', "visibility:hidden");
                    // }

                    $(data.order_details).each(function (key, val) {
                        svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                        svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                        svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;
                        if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                        {
                            svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                        }
                        else
                        {
                            //alert()
                            svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 103)");
                            svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 110)");
                            svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 117)");
                            svgDoc.getElementById("batch_code").innerHTML = "";
                        }

                        if(val.internal_reference != "" && val.internal_reference != null)
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;
                        }
                        else
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = "";
                            svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 4.8456, 160.7462)");
                            
                        }
                        
                        if(val.tog != "" && val.tog != null)
                        {
                            svgDoc.getElementById("tog_value_1_").innerHTML = "TOG "+val.tog;
                        }
                        else
                        {
                            //console.log("hai")
                            svgDoc.getElementById("tog_value_1_").innerHTML = "";
                        }


                        var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                        var selected_secondary_sizes_text = "";
                        console.log(selected_secondary_sizes)
                        $size_y=0;
                        $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                            if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                            {
                                selected_secondary_sizes_text += '<tspan text-anchor="middle" x="" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                                $size_y += 6;
                            }
                        })
                        svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                        


                        svgDoc.getElementById("uk_ean_barcode").innerHTML = "UK EAN: " + val.uk_ean;
                        svgDoc.getElementById("ce_ean_barcode").innerHTML = "CE EAN: " +val.ce_ean;
                        
                        if(val.uk_size == "tiny baby")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");
                            

                            //svgDoc.getElementById("small_english").innerHTML = "tiny baby";

                            var polish = "tiny baby"
                            var czech = data.tesco_tiny[0].czech
                            var slovak = data.tesco_tiny[0].slovak
                            var hungarian = data.tesco_tiny[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "Newborn")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");


                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "Newborn";

                            var polish = "Newborn"
                            var czech = data.tesco_nb[0].czech
                            var slovak = data.tesco_nb[0].slovak
                            var hungarian = data.tesco_nb[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 17.9806, 257.061)");
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 1M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 1M";
                            
                            var polish = "up to 1M"
                            var czech = data.tesco_up1[0].czech
                            var slovak = data.tesco_up1[0].slovak
                            var hungarian = data.tesco_up1[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.3px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 3M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 3M";
                            
                            var polish = "up to 3M"
                            var czech = data.tesco_up3[0].czech
                            var slovak = data.tesco_up3[0].slovak
                            var hungarian = data.tesco_up3[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.35px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "One size")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "One size";
                            
                            var polish = "One size"
                            var czech = data.tesco_onesize[0].czech.split(' ')
                            var slovak = data.tesco_onesize[0].slovak
                            var hungarian = data.tesco_onesize[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 16.9806, 257.061)");
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else
                        {
                            svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                            svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
                        }
                        svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                        var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                        svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;
                        var svgRoot = svgDoc.documentElement;
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax_count++;
                    })

                    var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                    svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan  x="2" y="0">'+country_of_origin_text[0].polish+'</tspan><tspan   x="2.53" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2.53" y="12.88">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="2" y="18.82">'+country_of_origin_text[0].hungarian+'</tspan>';

                    // svgDoc_back.getElementById("wash_iso").setAttribute('xlink:href',data.order_details[0].wash_iso);
                    // svgDoc_back.getElementById("clean_iso").setAttribute('xlink:href',data.order_details[0].clean_iso);
                    // svgDoc_back.getElementById("whit_iso").setAttribute('xlink:href',data.order_details[0].whit_iso);
                    // svgDoc_back.getElementById("iron_iso").setAttribute('xlink:href',data.order_details[0].iron_iso);
                    // svgDoc_back.getElementById("dry_iso").setAttribute('xlink:href',data.order_details[0].dry_iso);
                    // svgDoc_back.getElementById("wash_astm").setAttribute('xlink:href',data.order_details[0].wash_astm);
                    // svgDoc_back.getElementById("dry_astm").setAttribute('xlink:href',data.order_details[0].dry_astm);
                    // svgDoc_back.getElementById("whit_astm").setAttribute('xlink:href',data.order_details[0].whit_astm);
                    // svgDoc_back.getElementById("iron_astm").setAttribute('xlink:href',data.order_details[0].iron_astm);
                    // svgDoc_back.getElementById("clean_astm").setAttribute('xlink:href',data.order_details[0].clean_astm);

                    var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
                    var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
                    var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
                    var degree =data.order_details[0].degree

                    var en_care_instructions = "";
                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        en_care_instructions += additional_care_text_1[0].english
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_2[0].english
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_3[0].english
                    }
                    
                    if(degree >= 30)
                    {
                        en_care_instructions += " / " + data.degree_text[0].english
                    }
                    
                    $chunk_str = chunkSubstr(en_care_instructions, 28).split(/\r?\n/);

                    //console.log($chunk_str);
                    var chunks='<tspan  x="0" y="0">EN</tspan>';
                    $y=0;
                    $ind=1;
                    console.log("gy:"+$chunk_str.length)
                    $($chunk_str).each(function(index,chunk_val){
                        chunks += '<tspan  x="12" y="'+$y+'">'+chunk_val+'</tspan>';
                        if($chunk_str.length == $ind)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind++;
                        
                    })

                    var en_garment_component = "";
                    var pl_garment_component = "";
                    var cz_garment_component = "";
                    var sk_garment_component = "";
                    var hu_garment_component = "";
                    $(data.component).each(function(index,comp_val){
                        
                        var garment_component_text = JSON.parse(comp_val.garment_component_text)
                        if(index != 0)
                        {
                            en_garment_component += " / " +garment_component_text[0].english;
                            pl_garment_component += " / " +garment_component_text[0].polish;
                            cz_garment_component += " / " +garment_component_text[0].czech;
                            sk_garment_component += " / " +garment_component_text[0].slovak;
                            hu_garment_component += " / " +garment_component_text[0].hungarian;
                        }
                        else
                        {
                            en_garment_component += garment_component_text[0].english;
                            pl_garment_component += garment_component_text[0].polish;
                            cz_garment_component += garment_component_text[0].czech;
                            sk_garment_component += garment_component_text[0].slovak;
                            hu_garment_component += garment_component_text[0].hungarian;
                        }
                        
                        $(comp_val.fibre).each(function(index,fibre_val){
                            var fibre_text = JSON.parse(fibre_val.fibre_composition_text)
                            en_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].english;

                            pl_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].polish;

                            cz_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].czech;

                            sk_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].slovak;

                            hu_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].hungarian;
                        });
                    })

                    var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
                    var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
                    var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
                    var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
                    

                    if(exclusive_trims_text != "" && exclusive_trims_text != null)
                    {
                        en_garment_component += " / " + exclusive_trims_text[0].english
                        pl_garment_component += " / " + exclusive_trims_text[0].polish
                        cz_garment_component += " / " + exclusive_trims_text[0].czech
                        sk_garment_component += " / " + exclusive_trims_text[0].slovak
                        hu_garment_component += " / " + exclusive_trims_text[0].hungarian
                    }

                    if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
                    {
                        en_garment_component += " / " + contains_non_textile_part_text[0].english
                        pl_garment_component += " / " + contains_non_textile_part_text[0].polish
                        cz_garment_component += " / " + contains_non_textile_part_text[0].czech
                        sk_garment_component += " / " + contains_non_textile_part_text[0].slovak
                        hu_garment_component += " / " + contains_non_textile_part_text[0].hungarian
                    }

                    if(kids_warning_text != "" && kids_warning_text != null)
                    {
                        en_garment_component += " / " + kids_warning_text[0].english
                        pl_garment_component += " / " + kids_warning_text[0].polish
                        cz_garment_component += " / " + kids_warning_text[0].czech
                        sk_garment_component += " / " + kids_warning_text[0].slovak
                        hu_garment_component += " / " + kids_warning_text[0].hungarian
                    }

                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        console.log("Polish : "+additional_care_text_1[0].polish)
                        pl_garment_component += " / " + additional_care_text_1[0].polish
                        cz_garment_component += " / " + additional_care_text_1[0].czech
                        sk_garment_component += " / " + additional_care_text_1[0].slovak
                        hu_garment_component += " / " + additional_care_text_1[0].hungarian
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_2[0].polish
                        cz_garment_component += " / " + additional_care_text_2[0].czech
                        sk_garment_component += " / " + additional_care_text_2[0].slovak
                        hu_garment_component += " / " + additional_care_text_2[0].hungarian
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_3[0].polish
                        cz_garment_component += " / " + additional_care_text_3[0].czech
                        sk_garment_component += " / " + additional_care_text_3[0].slovak
                        hu_garment_component += " / " + additional_care_text_3[0].hungarian
                    }

                    if(part_of_set_text != "" && part_of_set_text != null)
                    {
                        en_garment_component += " / " + part_of_set_text[0].english
                        pl_garment_component += " / " + part_of_set_text[0].polish
                        cz_garment_component += " / " + part_of_set_text[0].czech
                        sk_garment_component += " / " + part_of_set_text[0].slovak
                        hu_garment_component += " / " + part_of_set_text[0].hungarian
                    }

                    if(degree >= 30)
                    {
                        pl_garment_component += " / " + data.degree_text[0].polish
                        cz_garment_component += " / " + data.degree_text[0].czech
                        sk_garment_component += " / " + data.degree_text[0].slovak
                        hu_garment_component += " / " + data.degree_text[0].hungarian
                    }

                    $garment_str = chunkSubstr(en_garment_component, 28).split(/\r?\n/);
                    $pl_garment_str = chunkSubstr(pl_garment_component, 28).split(/\r?\n/);
                    $cz_garment_str = chunkSubstr(cz_garment_component, 28).split(/\r?\n/);
                    $sk_garment_str = chunkSubstr(sk_garment_component, 28).split(/\r?\n/);
                    $hu_garment_str = chunkSubstr(hu_garment_component, 28).split(/\r?\n/);

                    chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

                    $ind_en=1;
                    $z=0;
                    $($garment_str).each(function(index,garment_val){
                        chunks += '<tspan x="12" y="'+$y+'">'+garment_val+'</tspan>';
                        if($garment_str.length == $ind_en)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind_en++;
                    })

                    // chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                     var new_chunks = '';
                    // $($pl_garment_str).each(function(index,pl_garment_val){
                    //     if($y == 96)
                    //     {
                    //         $z=0;
                    //     }

                    //     if($y > 96)
                    //     {
                    //         new_chunks += '<tspan x="12" y="'+$z+'">'+pl_garment_val+'</tspan>';
                    //     }
                    //     else
                    //     {
                    //         chunks += '<tspan x="12" y="'+$y+'">'+pl_garment_val+'</tspan>';   
                    //     }
                        
                    //     $y+=6;
                    //     $z+=6;
                    // })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
                    }

                    $($cz_garment_str).each(function(index,cz_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+cz_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+cz_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                    }

                    $($sk_garment_str).each(function(index,sk_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+sk_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                    }

                    $($hu_garment_str).each(function(index,hu_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+hu_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })
                    
                    svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
                    svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


                    
                    // console.log(en_care_instructions)
                    // console.log(en_care_instructions)

                    var s_front = new XMLSerializer();
                    var d_front = svgRoot_front_text;
                    var str_front = s_front.serializeToString(d_front);
                    var svg_front = encodeURI(str_front)
                    svgarr_front.push(svg_front)
                    
                    var s_back = new XMLSerializer();
                    var d_back = svgRoot_back;
                    var str_back = s_back.serializeToString(d_back);
                    var svg_back = encodeURI(str_back)
                    svgarr_back.push(svg_back)

                    var s_back_text = new XMLSerializer();
                    var d_back_text = svgRoot_back_text;
                    var str_back_text = s_back_text.serializeToString(d_back_text);
                    var svg_back_text = encodeURI(str_back_text)
                    svgarr_back_text.push(svg_back_text)
                   
                    if (ajax_count == data.order_details.length) {
                        var svg_fin = svgarr.join(',_');
                        var svg_fin_back = svgarr_back.join(',_');
                        var svg_fin_back_text = svgarr_back_text.join(',_');
                        var svg_fin_front = svgarr_front.join(',_');
                        PopupTescoLabelsGeneratePrintManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
                    }
				
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
        }
		
	}


}


function PopupTescoGeneratePrintManager(data, name,svgDoc,template_id,qr_flag,qr_code_fin,qr_code_check_fin) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tescoos",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			qr_code_fin: qr_code_fin,
			qr_code_check_fin: qr_code_check_fin,
			qr_flag:qr_flag,
			template_id: template_id,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function PopupTescoLabelsGeneratePrintManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function PopupTescoLabelsProofManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/proof-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoGenerateSmallPrintManager(data, name,svgDoc) {
	

	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tesco-smallos",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function downloadArtwork(data, name,style_no,buyer_no,id,order_type,obj,art_temp_id) {

    var created_at = $(obj).parent().attr('data-orderdate');
    var status = $(obj).parent().attr('data-order_status');
    var template_id = $(obj).parent().attr('data-template_id');
    var table_name_print = $(obj).attr('data-table1');
    var buyer = "tesco";

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var name2=name;
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker" || name == "TSL2-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
	
		
			$.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
					
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			var zone=val.zone;
			localStorage.setItem("zone", zone);

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				//alert(barcode_change);
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			} else {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
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
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = '';
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
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
							var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
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
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
							}
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)

				}
			} else {
				var description =val.short_description.replace(/&/g,'&#038;');
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
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
				}
				
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
			}
			ajax_count++;
		})
					if (ajax_count == data.order_details.length) {
						var svg_fin = svgarr.join(',_');
						var zone = localStorage.getItem("zone");
						
					 
						  PopupTescoSmallPrintManager(svg_fin, name,zone,style_no,buyer_no,id,created_at);
					  
						
					}
				},
				error: function(data) {

				},
				complete: function() {

				}
			})

		
		


	} else {
        if(table_name_print != "tesco_washcare_orders")
        {
            var svgarr = [];
		var qr_code_arr = [];
		var qr_code_check_arr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		
			$.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
					$(data.order_details).each(function (key, val) {
						var zone=val.zone;
						localStorage.setItem("zone", zone);
			var svgRoot = svgDoc.documentElement;
						
		if (val.supplier_name != '') {
		    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
						
		if (val.po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (val.ce_style_ref != '') {
			svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (val.uk_style_ref != '') {
			svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (val.eqos_site_code != '') {
			svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		
					
						/** [ Dynamically change  svg department ] */
		if (val.department != '') {
			/** [Give font-size for department] */
			if (val.department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if (val.brand != '') {
			/** [Give font-size for brand] */
			if (val.brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
						
										
		var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
		var rfid = val.rfid_compliant == 0 ? "no" : "yes";
						
		/** [ Dynamically svg RFID ] */
		if (rfid != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (tag_at_source != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (val.season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (val.manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
						
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "TCL5-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
					jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "TCL5-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
					jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TCL4-sticker") || (name == "TCL5-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
                            //alert(data.size_chart)
							/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
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
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											
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
								
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
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
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
                                        }
                                        else if(name2 === "TCL5")
										{
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											
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
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
							qr_code_arr.push(val.qr_code)
                            qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
							if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

                                    }
                                    else if(name2 === "TCL5")
                                    {
                                        svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 111.7295)");
                                        svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                        svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
                                        svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 111.3771)");
                                        svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                        svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
                                        svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
                                        
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
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

                                    }
                                    else if(name2 === "TCL5")
                                    {
                                        svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 109.7295)");
                                        svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 132)");
                                        svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 142)");
                                        svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 109.3771)");
                                        svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 132)");
                                        svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 142)");
                                        svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
                                        
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
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
						} else {
							svgDoc.getElementById("description").innerHTML = '';
						}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)
					qr_code_arr.push(val.qr_code)
                    qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)

				}
			} else {
				if (description != '') {
					if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name2 === "THL1")
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
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
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
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
				qr_code_arr.push(val.qr_code)
				qr_code_check_arr.push(val.po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
			}


	
			ajax_count++;
		})
			if (ajax_count == data.order_details.length) {
				var svg_fin = svgarr.join(',_');
				var qr_code_fin = qr_code_arr.join(',_');
                var qr_code_check_fin = qr_code_check_arr.join(',_');
				var zone = localStorage.getItem("zone");
				
				  PopupTescoPrintManager(svg_fin, name,zone,style_no,buyer_no,id,created_at,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
			   
			}
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
        }
        else
        {
            
            var a_back = document.getElementById(name2+"_"+art_temp_id+"_back_modal");
    var svgDoc_back = a_back.contentDocument;

    var a_back_text = document.getElementById(name2+"_"+art_temp_id+"_back_text_modal");
    var svgDoc_back_text = a_back_text.contentDocument;

    var a_front_text = document.getElementById(name2+"_"+art_temp_id+"_front_modal");
    var svgDoc_front_text = a_front_text.contentDocument;
    
            var svgarr = [];
            var svgarr_back = [];
            var svgarr_back_text = [];
            var svgarr_front = [];
            ajax_count=0;
            $.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
                    table_name_print
                },
				beforeSend: function() {

				},
				success: function(data) {
                    
                    var svgRoot_back =svgDoc_back.documentElement;
                    var svgRoot_back_text =svgDoc_back_text.documentElement;
                    var svgRoot_front_text =svgDoc_front_text.documentElement;

                    if(data.order_details[0].bs_en_declaration == 0)
                    {
                        console.log("hai")
                        svgDoc_front_text.getElementById("sleeping_bag_name").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].baby_sleeping_bag_symbol == 0)
                    {
                        svgDoc_front_text.getElementById("tog_image").setAttribute('style', "visibility:hidden");
                    }

                    // if(data.order_details[0].tog == "" || data.order_details[0].tog == null)
                    // {
                    //     svgDoc_front_text.getElementById("tog_name").setAttribute('style', "visibility:hidden");
                    // }

                    $(data.order_details).each(function (key, val) {
                        svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                        svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                        svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;
                        if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                        {
                            svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                        }
                        else
                        {
                            //alert()
                            svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 103)");
                            svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 110)");
                            svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 117)");
                            svgDoc.getElementById("batch_code").innerHTML = "";
                        }

                        if(val.internal_reference != "" && val.internal_reference != null)
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;
                        }
                        else
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = "";
                            svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 4.8456, 160.7462)");
                            
                        }
                        
                        if(val.tog != "" && val.tog != null)
                        {
                            svgDoc.getElementById("tog_value_1_").innerHTML = "TOG "+val.tog;
                        }
                        else
                        {
                            //console.log("hai")
                            svgDoc.getElementById("tog_value_1_").innerHTML = "";
                        }


                        var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                        var selected_secondary_sizes_text = "";
                        console.log(selected_secondary_sizes)
                        $size_y=0;
                        $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                            if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                            {
                                selected_secondary_sizes_text += '<tspan text-anchor="middle" x="10" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                                $size_y += 6;
                            }
                        })
                        svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                        


                        svgDoc.getElementById("uk_ean_barcode").innerHTML =  "UK EAN: " + val.uk_ean;
                        svgDoc.getElementById("ce_ean_barcode").innerHTML =  "CE EAN: " + val.ce_ean;

                        if(val.potps_id == null)
                        {
                            svgDoc.getElementById("part_of_two_piece_set").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 302.3271)");
                            svgDoc.getElementById("ce_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 312.2111)");
                            svgDoc.getElementById("top_line").setAttribute('y1', "294.16");
                            svgDoc.getElementById("top_line").setAttribute('y2', "294.16");
                            svgDoc.getElementById("bottom_line").setAttribute('y1', "315.63");
                            svgDoc.getElementById("bottom_line").setAttribute('y2', "315.63");
                        }
                        else
                        {
                            svgDoc.getElementById("part_of_two_piece_set").removeAttribute("visibility", "hidden");
                        }
                        
                        if(val.uk_size == "tiny baby")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "tiny baby";

                            var polish = "tiny baby"
                            var czech = data.tesco_tiny[0].czech
                            var slovak = data.tesco_tiny[0].slovak
                            var hungarian = data.tesco_tiny[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "Newborn")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "Newborn";

                            var polish = "Newborn"
                            var czech = data.tesco_nb[0].czech
                            var slovak = data.tesco_nb[0].slovak
                            var hungarian = data.tesco_nb[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation1").style.letterSpacing = "-0.2px";
                            //svgDoc.getElementById("small_translation_1").style.letterSpacing = "-0.2px";
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 48.9806, 257.061)");
                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 18.9806, 257.061)");
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 1M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 1M";
                            
                            var polish = "up to 1M"
                            var czech = data.tesco_up1[0].czech
                            var slovak = data.tesco_up1[0].slovak
                            var hungarian = data.tesco_up1[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.05px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 3M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 3M";
                            
                            var polish = "up to 3M"
                            var czech = data.tesco_up3[0].czech
                            var slovak = data.tesco_up3[0].slovak
                            var hungarian = data.tesco_up3[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.05px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "One size")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "One size";
                            
                            var polish = "One size"
                            var czech = data.tesco_onesize[0].czech
                            var slovak = data.tesco_onesize[0].slovak
                            var hungarian = data.tesco_onesize[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation1").style.letterSpacing = "-0.2px";
                            //svgDoc.getElementById("small_translation_1").style.letterSpacing = "-0.2px";
                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 17.786, 257.061)");
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 48.786, 257.061)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(1.0039 0 0 1 35.5806 264.061)");
                            
                            svgDoc.getElementById("small_translation_1").innerHTML = "  • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML ="• "+slovak+ " • "+hungarian;
                        }
                        else
                        {
                            svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                            svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
                        }
                        svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                        var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                        svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;
                        var svgRoot = svgDoc.documentElement;
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax_count++;
                    })

                    var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                    svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan  x="2" y="0">'+country_of_origin_text[0].polish+'</tspan><tspan   x="2.53" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2.53" y="12.88">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="2" y="18.82">'+country_of_origin_text[0].hungarian+'</tspan>';

                    // svgDoc_back.getElementById("wash_iso").setAttribute('xlink:href',data.order_details[0].wash_iso);
                    // svgDoc_back.getElementById("clean_iso").setAttribute('xlink:href',data.order_details[0].clean_iso);
                    // svgDoc_back.getElementById("whit_iso").setAttribute('xlink:href',data.order_details[0].whit_iso);
                    // svgDoc_back.getElementById("iron_iso").setAttribute('xlink:href',data.order_details[0].iron_iso);
                    // svgDoc_back.getElementById("dry_iso").setAttribute('xlink:href',data.order_details[0].dry_iso);
                    // svgDoc_back.getElementById("wash_astm").setAttribute('xlink:href',data.order_details[0].wash_astm);
                    // svgDoc_back.getElementById("dry_astm").setAttribute('xlink:href',data.order_details[0].dry_astm);
                    // svgDoc_back.getElementById("whit_astm").setAttribute('xlink:href',data.order_details[0].whit_astm);
                    // svgDoc_back.getElementById("iron_astm").setAttribute('xlink:href',data.order_details[0].iron_astm);
                    // svgDoc_back.getElementById("clean_astm").setAttribute('xlink:href',data.order_details[0].clean_astm);

                    var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
                    var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
                    var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
                    var degree =data.order_details[0].degree

                    var en_care_instructions = "";
                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        en_care_instructions += additional_care_text_1[0].english
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_2[0].english
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_3[0].english
                    }
                    
                    if(degree >= 30)
                    {
                        en_care_instructions += " / " + data.degree_text[0].english
                    }
                    
                    $chunk_str = chunkSubstr(en_care_instructions, 28).split(/\r?\n/);

                    //console.log($chunk_str);
                    var chunks='<tspan  x="0" y="0">EN</tspan>';
                    $y=0;
                    $ind=1;
                    console.log("gy:"+$chunk_str.length)
                    $($chunk_str).each(function(index,chunk_val){
                        chunks += '<tspan  x="12" y="'+$y+'">'+chunk_val+'</tspan>';
                        if($chunk_str.length == $ind)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind++;
                        
                    })

                    var en_garment_component = "";
                    var pl_garment_component = "";
                    var cz_garment_component = "";
                    var sk_garment_component = "";
                    var hu_garment_component = "";
                    $(data.component).each(function(index,comp_val){
                        
                        var garment_component_text = JSON.parse(comp_val.garment_component_text)
                        if(index != 0)
                        {
                            en_garment_component += " / " +garment_component_text[0].english;
                            pl_garment_component += " / " +garment_component_text[0].polish;
                            cz_garment_component += " / " +garment_component_text[0].czech;
                            sk_garment_component += " / " +garment_component_text[0].slovak;
                            hu_garment_component += " / " +garment_component_text[0].hungarian;
                        }
                        else
                        {
                            en_garment_component += garment_component_text[0].english;
                            pl_garment_component += garment_component_text[0].polish;
                            cz_garment_component += garment_component_text[0].czech;
                            sk_garment_component += garment_component_text[0].slovak;
                            hu_garment_component += garment_component_text[0].hungarian;
                        }
                        
                        $(comp_val.fibre).each(function(index,fibre_val){
                            var fibre_text = JSON.parse(fibre_val.fibre_composition_text)
                            en_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].english;

                            pl_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].polish;

                            cz_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].czech;

                            sk_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].slovak;

                            hu_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].hungarian;
                        });
                    })

                    var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
                    var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
                    var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
                    var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
                    

                    if(exclusive_trims_text != "" && exclusive_trims_text != null)
                    {
                        en_garment_component += " / " + exclusive_trims_text[0].english
                        pl_garment_component += " / " + exclusive_trims_text[0].polish
                        cz_garment_component += " / " + exclusive_trims_text[0].czech
                        sk_garment_component += " / " + exclusive_trims_text[0].slovak
                        hu_garment_component += " / " + exclusive_trims_text[0].hungarian
                    }

                    if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
                    {
                        en_garment_component += " / " + contains_non_textile_part_text[0].english
                        pl_garment_component += " / " + contains_non_textile_part_text[0].polish
                        cz_garment_component += " / " + contains_non_textile_part_text[0].czech
                        sk_garment_component += " / " + contains_non_textile_part_text[0].slovak
                        hu_garment_component += " / " + contains_non_textile_part_text[0].hungarian
                    }

                    if(kids_warning_text != "" && kids_warning_text != null)
                    {
                        en_garment_component += " / " + kids_warning_text[0].english
                        pl_garment_component += " / " + kids_warning_text[0].polish
                        cz_garment_component += " / " + kids_warning_text[0].czech
                        sk_garment_component += " / " + kids_warning_text[0].slovak
                        hu_garment_component += " / " + kids_warning_text[0].hungarian
                    }

                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        console.log("Polish : "+additional_care_text_1[0].polish)
                        pl_garment_component += " / " + additional_care_text_1[0].polish
                        cz_garment_component += " / " + additional_care_text_1[0].czech
                        sk_garment_component += " / " + additional_care_text_1[0].slovak
                        hu_garment_component += " / " + additional_care_text_1[0].hungarian
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_2[0].polish
                        cz_garment_component += " / " + additional_care_text_2[0].czech
                        sk_garment_component += " / " + additional_care_text_2[0].slovak
                        hu_garment_component += " / " + additional_care_text_2[0].hungarian
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_3[0].polish
                        cz_garment_component += " / " + additional_care_text_3[0].czech
                        sk_garment_component += " / " + additional_care_text_3[0].slovak
                        hu_garment_component += " / " + additional_care_text_3[0].hungarian
                    }

                    if(part_of_set_text != "" && part_of_set_text != null)
                    {
                        en_garment_component += " / " + part_of_set_text[0].english
                        pl_garment_component += " / " + part_of_set_text[0].polish
                        cz_garment_component += " / " + part_of_set_text[0].czech
                        sk_garment_component += " / " + part_of_set_text[0].slovak
                        hu_garment_component += " / " + part_of_set_text[0].hungarian
                    }

                    if(degree >= 30)
                    {
                        pl_garment_component += " / " + data.degree_text[0].polish
                        cz_garment_component += " / " + data.degree_text[0].czech
                        sk_garment_component += " / " + data.degree_text[0].slovak
                        hu_garment_component += " / " + data.degree_text[0].hungarian
                    }

                    $garment_str = chunkSubstr(en_garment_component, 28).split(/\r?\n/);
                    $pl_garment_str = chunkSubstr(pl_garment_component, 28).split(/\r?\n/);
                    $cz_garment_str = chunkSubstr(cz_garment_component, 28).split(/\r?\n/);
                    $sk_garment_str = chunkSubstr(sk_garment_component, 28).split(/\r?\n/);
                    $hu_garment_str = chunkSubstr(hu_garment_component, 28).split(/\r?\n/);

                    chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

                    $ind_en=1;
                    $z=0;
                    $($garment_str).each(function(index,garment_val){
                        chunks += '<tspan x="12" y="'+$y+'">'+garment_val+'</tspan>';
                        if($garment_str.length == $ind_en)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind_en++;
                    })

                    // chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                     var new_chunks = '';
                    // $($pl_garment_str).each(function(index,pl_garment_val){
                    //     if($y == 96)
                    //     {
                    //         $z=0;
                    //     }

                    //     if($y > 96)
                    //     {
                    //         new_chunks += '<tspan x="12" y="'+$z+'">'+pl_garment_val+'</tspan>';
                    //     }
                    //     else
                    //     {
                    //         chunks += '<tspan x="12" y="'+$y+'">'+pl_garment_val+'</tspan>';   
                    //     }
                        
                    //     $y+=6;
                    //     $z+=6;
                    // })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
                    }

                    $($cz_garment_str).each(function(index,cz_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+cz_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+cz_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                    }

                    $($sk_garment_str).each(function(index,sk_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+sk_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                    }

                    $($hu_garment_str).each(function(index,hu_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+hu_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })
                    
                    svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
                    svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


                    
                    // console.log(en_care_instructions)
                    // console.log(en_care_instructions)

                    var s_front = new XMLSerializer();
                    var d_front = svgRoot_front_text;
                    var str_front = s_front.serializeToString(d_front);
                    var svg_front = encodeURI(str_front)
                    svgarr_front.push(svg_front)
                    
                    var s_back = new XMLSerializer();
                    var d_back = svgRoot_back;
                    var str_back = s_back.serializeToString(d_back);
                    var svg_back = encodeURI(str_back)
                    svgarr_back.push(svg_back)

                    var s_back_text = new XMLSerializer();
                    var d_back_text = svgRoot_back_text;
                    var str_back_text = s_back_text.serializeToString(d_back_text);
                    var svg_back_text = encodeURI(str_back_text)
                    svgarr_back_text.push(svg_back_text)
                   
                    if (ajax_count == data.order_details.length) {
                        var svg_fin = svgarr.join(',_');
                        var svg_fin_back = svgarr_back.join(',_');
                        var svg_fin_back_text = svgarr_back_text.join(',_');
                        var svg_fin_front = svgarr_front.join(',_');
                        PopupTescoLabelsProofManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
                    }
				
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
        }

	}


}


function PopupTescoPrintManager(data, name,zone,style_no,buyer_no,id,created_at,template_id,qr_flag,qr_code_fin,qr_code_check_fin) {

	//	var s = new XMLSerializer();
	//	var d = data;
	//	var str = s.serializeToString(d);
	//	var svg = encodeURI(str)
	//var success = false;
	
	if(name == "TCL1-sticker")
	{
		var db = "tesco_tcl1_orders";
	}
	else if(name == "TCL2-sticker")
	{
		var db = "tesco_tcl2_orders";
	}
	else if(name == "TCL4-sticker")
	{
		var db = "tesco_tcl4_orders";
	}
	else if(name == "THL1-sticker")
	{
		var db = "tesco_thl1_orders";
	}
	else if(name == "THL3-sticker")
	{
		var db = "tesco_thl3_orders";
	}
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tescoos",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name:name,
			zone:zone,
			style:style_no,
			qr_code_fin: qr_code_fin,
			qr_code_check_fin: qr_code_check_fin,
			tesco_po_number:buyer_no,
			id:id,
			template_id:template_id,
			created_at:created_at,
			qr_flag:qr_flag,
			db:db
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			//window.location.href=data;
			//			success = true;
			//			url = data;

			var element = document.createElement('a');
			element.setAttribute('href', data.url);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);


		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoSmallPrintManager(data, name,zone,style_no,buyer_no,id,created_at) {
	
		
	if(name == "TSL1-sticker")
	{
		var db = "tesco_tsl1_orders";
	}
	else if(name == "TSL3-sticker")
	{
		var db = "tesco_tsl3_orders";
	}
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco-smallos",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name:name,
			zone:zone,
			style:style_no,
			tesco_po_number:buyer_no,
			id:id,
			created_at:created_at,
			db:db
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data.url);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);


		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
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
                    if(data.po_status == "PO Uploaded")
                    {
                        $(obj).closest('tr').find('.po_status a span').text("PO Uploaded")
                        $(obj).closest('tr').find('.po_status a span').removeClass("label-POPending")
                        $(obj).closest('tr').find('.po_status a span').addClass("label-POUploaded")
                    }
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

function tescoPOUploaded(obj, id, po_file, tableName,style_no,po_number) {
    
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
            $('#po_number_updation').val(po_number)
        }
    })
}
/**
 * [tescoPOUpload description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoPOUpload(obj, id, po_file, tableName,style_no) {
    
    // $.ajax({
    //     url: base_url + "/admin/tesco/get-erp-status",
    //     type: 'get',
    //     data: {
    //         table_name: tableName,
    //         id: id,
    //         _token: csrf_token
    //     },
    //     beforeSend: function () {
    //          $(".loader").show();
    //     },
    //     success: function (data) {
    //         // erp_details array null , if order is not moved to erp tables
    //         // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
    //         if (data.erp_details[0] != null) {
    //             if (data.erp_details[0].status_name !== 'Hold For JC Queue' && data.erp_details[0].status_name !== 'Moved To ERP') {
    //                 $('.update-po-file').hide();

    //                 $('#error_msg_span').html('ERP Order Status : <strong>' + data.erp_details[0].status_name + '</strong>. You are not permitted to upload any more po files for this order.')
    //             }
    //             else{
    //                 $('.update-po-file').show();
    //                 $('#error_msg_span').html('');
    //             }
    //         }
    //         else
    //         {
    //             $('.update-po-file').show();
    //             $('#error_msg_span').html('');
    //         }
    //     },
    //     error: function (data) {

    //     },
    //     complete: function () {
    //         $(".loader").hide();
    //         if (po_file == "null") {
    //             $('#po_upload').removeClass('hidden')
    //             $('.upload-po').removeClass('hidden')
    //             $('#po_file').addClass('hidden')
    //         } else {
    //             $('#po_upload').addClass('hidden')
    //             $('.upload-po').addClass('hidden')
    //             $('#po_file').removeClass('hidden')
    //         }
    //         var po_link = base_url + po_file
    //         $('#po_link').attr("href", po_link);
    //         $('#rowID').val(id);
    //         $('#tableName').val(tableName);
    //         $('#buyer_id').val(localStorage.getItem("buyer_id"));
    //         $('#po-file-upload').modal('show')
    //     }
    // })
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
                var options = "<option>Select Style Number</option>";
                $(data.style_no).each(function(index,value){
                    if(value.type == "stickers")
                    {
                        if (value.zone == "CE") {
                            options += "<option data-zone=" + value.zone + ">" + value.ce_style_ref + "</option>";
                        } else {
                            options += "<option data-zone=" + value.zone + ">" + value.uk_style_ref + "</option>";
                        }
                    }
                    else
                    {
                        options += "<option data-zone='labels'>" + value.uk_style_ref+"/"+ value.ce_style_ref  + "</option>";
                    }
                })
                $('#uvc_select_div').html('<label>Select Style Number</label><select id="style_num" class="form-control selectpicker" data-live-search="true">' + options + '</select>')

                $('.selectpicker').selectpicker();
                
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
                                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3'].concat(data.data['wcl'].concat(data.data['tsl2'].concat(data.data['tcl5'])))))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var unique_po = []
                                        var old_po = ""
                                        var new_po_div_status = 0;
                                        $(newObj).each(function (index1, value1) {
                                            if(value1.TemplateName.length <= 5)
                                            {
                                                var margin_right="160px";
                                            }
                                            else
                                            {
                                                var margin_right="85px";
                                            }

                                            if (value1.purchase_order_no != "" && value1.purchase_order_no != null && value1.po_file != "" && value1.po_file != null) {
                                                if (old_po != value1.purchase_order_no) {
                                                    options += "<option>" + value1.purchase_order_no + "</option>";
                                                }
                                                old_po = value1.purchase_order_no
                                                coun++;
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="check-list">' + value1.TemplateName +'<span style="display: inline-block;margin-left: '+margin_right+';" class="tab"></span>'+ value1.po_number +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>'+ value1.purchase_order_no+ '</li>')
                                                new_po_div_status++;

                                            } else {
                                                $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="' + value1.tableName + '-' + value1.id + '" data-company="' + value1.company_id + '"class="list-group-item">' + value1.TemplateName +'<span style="display: inline-block;margin-left: '+margin_right+';" class="tab"></span>'+ value1.po_number +'<span style="display: inline-block;margin-left: 160px;" class="tab"></span>-'+ '</li>')
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

                                            if($(this).data('id') == tableName+'-'+id)
                                            {
                                                $checkbox.prop('checked', !$checkbox.is(':checked'));
                                                $checkbox.triggerHandler('change');
                                                updateDisplay();
                                            }

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
                                                                setTimeout(function(){
                                                                    $(".loader").hide();
                                                                    submitOs(this)
                                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                                    showSnackbar();
                                                                }, 10000);
                                                                //tescoOrderStatus()
                                                                
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
                                                                setTimeout(function(){
                                                                    $(".loader").hide();
                                                                    submitOs(this)
                                                                    $('.snackbar_span').html('PO Updated Successfully');
                                                                    showSnackbar();
                                                                }, 10000);
                                                                //tescoOrderStatus()
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
                                                            setTimeout(function(){
                                                                $(".loader").hide();
                                                                submitOs(this)
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            }, 10000);
                                                            //tescoOrderStatus()
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



                    $('#style_num').selectpicker('val', style_no).change();
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
    $('#po-file-upload-group').modal("show")
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
               
                if (data.order_details.order_status == "Saved") {
                    $('.snackbar_span').html('Please Check in Create Order Page!!!');
                    showSnackbar();
                } else if (data.order_details.order_status == "Order Cancelled") {
                    $('.snackbar_span').html('Order Cancelled!!!');
                    showSnackbar();
                } else {
                   if( table_name === 'tesco_washcare_orders') {
                    viewOrderDetailsPanel(data, 'admin')
                   } else {
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

                            if (data.order_details.full_description != null) {
                                $('#tesco_description').prev().text($('#tesco_description').prev().text() + '(Full Description: ' + data.order_details.full_description + ')');
                            } else {
                                $('#tesco_description').prev().text('Short Description');
                            }


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
                            $(".dropdown-toggle").css("background-color", "#eee");
                            $('#tesco_department').prop('disabled', true);
                            $('#tesco_rfid').prop('disabled', true);
                            $('#tesco_tag_at_source').prop('disabled', true);
                            //					}


                            $('.artwork_tesco').html('<object style="border: 2px #333 solid;" id="svg_tesco_os" class="center-block svg2" data="' + data.tesco_item_master[0].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;position: absolute;"><svg id="ean-13"></svg></div>')

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
                            $(data.order_details.quantities).each(function (key, value) {

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

                                if (value.id == data.order_details.quantities[0].id) {
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
                                $('.edit-sticker-order-panel').removeClass('active');
                            })

                            var mySVG1 = document.getElementById("svg_tesco_os");
                            var svgDoc1;
                            mySVG1.addEventListener("load", function () {
                                svgDoc1 = mySVG1.contentDocument;
                                dynamicSVGTemplatescard(svgDoc1, TemplateName, data.order_details.zone, data.order_details.template_type)
                            }, false);
                            if(data.order_details.is_order_count_empty == 1)
                            {
                                editArtwork()
                            }

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
                        if(data.order_details.pi_status == "PI Approved")
                        {
                            $('#enable_edit').remove();
                            $('#disable_edit').remove();
                            $('#edit').remove();
                            $('#submit').remove();
                            $('#error_msg_alert').html('<strong>PI is approved so you are not allowed to edit this order.</strong>')
                        }

                        $('.edit-sticker-order-panel').addClass('active');
                        $('.edit-sticker-order-panel').show();
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

}
$("#close").click(function () {
    $(".edit-sticker-order-panel").removeClass('active');
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
                    $('.edit-sticker-order-panel').removeClass('active');
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
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 150)");
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
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 163)");
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
									if(desc_size.length <= 22)
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
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
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
									if(desc_size.length <= 22)
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
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(template_name === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
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


function chunkSubstr(str, maxWidth) {
    var newLineStr = "\n"; done = false; res = '';
    while (str.length > maxWidth) {                 
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + [str.slice(0, i), newLineStr].join('');
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res += [str.slice(0, maxWidth), newLineStr].join('');
            str = str.slice(maxWidth);
        }

    }

    return res + str;
}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}

// function oslabelwrap(text, width){
//     text.each(function() {
//         var text = d3.select(this),
//             words = text.text().split(/\s+/).reverse(),
//             word,
//             line = [],
//             lineNumber = 0,
//             lineHeight = 1.1, // ems
//             y = text.attr("y"),
//             dy = parseFloat(text.attr("dy")),
//             tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
//         while (word = words.pop()) {
//           line.push(word);
//           tspan.text(line.join(" "));
//           if (tspan.node().getComputedTextLength() > width) {
//             line.pop();
//             tspan.text(line.join(" "));
//             line = [word];
//             tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
//           }
//         }
//       });
// }

/**
 * Approve tesco artworks from order status page with confirmation modal
 * @author <praveen>
 */
$(document).on('click','.artwork-approval-pending',function() {
    $('#ts_table_id').val($(this).data('table-id'));
    $('#ts_table_name').val($(this).data('table-name'));
    // show Modal
    $('#approveTescoArtworkModal').modal('show');
});

function approveTescoArtwork() {
	try {
        var table_id = $('#ts_table_id').val();
        var table_name = $('#ts_table_name').val();
		$.ajax({
			url: base_url + "/admin/tesco/approve-tesco-artwork",
			type: 'PUT',
			data: {
				_token: csrf_token,
                table_id: table_id,
                table_name:table_name
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
                $('#approveTescoArtworkModal').modal('hide');
				$('.snackbar_span').html('Artwork Approved Successfully');
                showSnackbar();
                submitOs(this);

			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
			}
		})
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}
