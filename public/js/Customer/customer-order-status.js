/* Customer Order Management Script*/
$(document).ready(function() {
      var buyer_count = $('#invisible__count_id').val();
      if(buyer_count <= 1) {
        $('.buyer-select option:first').remove();
        localStorage.setItem("pricing_buyer", $('.buyer-select').find("option:selected").text().toLowerCase());
        localStorage.setItem("pricing_buyer_id", $('.buyer-select').find("option:selected").val());
        orderStatus();
      }

      $('.select-buyer').click(function() {
        $('.select-buyer').addClass('hidden');
      })

      $('.buyer-nav-select').html($('.buyer-div').html())
      if (localStorage.getItem("pricing_buyer") === null) {
        $('.select-buyer').html('<img src="../../images/select-buyer.png" class="img-responsive">');
      } else {
        orderStatus();
      }
      $('.buyer-select').val(localStorage.getItem("pricing_buyer_id"));
      $('.buyer-select').selectpicker({
        style: 'btn-default',
        size: 4
      });

      $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
        if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
            localStorage.setItem("pricing_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
            localStorage.setItem("pricing_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
            orderStatus();
        } else {
            localStorage.removeItem("pricing_buyer");
            localStorage.removeItem("pricing_buyer_id");
            location.reload()
        }
      });
});

function orderStatus() {
    $('.main-content').html('<div class="loader"></div>');
    var buyer = localStorage.getItem("pricing_buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/" + buyer + "/order-status",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            if (buyer == "orchestra") {
                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders)));

                $('.main-content').html(table(newObj));
                var $tableSel = $('#datatable-example-2');
                $tableSel.DataTable({
                    "pageLength": 25,
                    initComplete: function () {
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

                        //For Custom Filters
                        this.api().columns().every( function (i) {
                            var column = this;
                            if(i === 0 || i === 2 || i === 6) {
                                var select;
                                switch(i) {
                                    case 0:
                                        select = $('#uvcNumber')
                                        break;
                                    case 2:
                                        select = $('#supplierName')
                                        break;
                                    case 6:
                                        select = $('#selectStatus')
                                        break;
                                }
                                select.on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
             
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                });
                                select.append( '<option value=""> </option>' );
                                column.data().unique().sort().each( function ( d, j ) {
                                    cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
                                    select.append('<option value="'+cleanText+'">'+cleanText+'</option>')
                                });
                            }
                            
                        });

                        
                    }
                });

                $("#reset").on("click", function () {
                    $('#uvcNumber').val('').change();
                    $('#supplierName').val('').change();
                    $('#selectStatus').val('').change();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                    $('input[name="daterange"]').val('Filter by Order Date');
                    $( 'input[name="daterange"]' ).datepicker("refresh");
                    $('.selectpicker').selectpicker('val', 1);
                    $('.selectpicker').selectpicker('refresh');
                    
                });

                $('.selectpicker').selectpicker();

                $('input[name="daterange"]').daterangepicker({

                    },
                    function(start, end, label) {
                        $.fn.dataTableExt.afnFiltering.length = 0;
                        $tableSel.dataTable().fnDraw();
                        // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        filterByDate(4, start, end); // We call our filter function
                        $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
                });

                $('input[name="daterange"]').val('Filter by Order Date');


                // Clear the filter. Unlike normal filters in Datatables,
                // custom filters need to be removed from the afnFiltering array.
                $('.cancelBtn').on('click', function(e){
                    e.preventDefault();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                });
                $("#datatable-example-2_filter input").addClass('form-control input-sm');
                $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                $('#datatable-example-2_length select').addClass('form-control input-sm');

            } else if(buyer == "tesco"){
               var path = base_url + "/customer/tesco/order-management/order-status";
               var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
               
               $('.main-content').html("<button style='margin-bottom:10px' id='po-file-upload-button' class='btn btn-primary pull-right' type='button'>Update PO</button>");

               $('.main-content').append(tescoStatusTable(newObj,path));
               $('.top-content').html("")
                
                var uniqueNames = [];
                var zones = [];
                for(i = 0; i< newObj.length; i++){ 
                    if(newObj[i].Zone == "CE")
                    {
                        if(uniqueNames.indexOf(newObj[i].ce_style_ref) === -1){
                            uniqueNames.push(newObj[i].ce_style_ref);
                            zones.push("CE");      
                        } 
                    }
                    else
                    {
                        if(uniqueNames.indexOf(newObj[i].uk_style_ref) === -1){
                            uniqueNames.push(newObj[i].uk_style_ref);
                            zones.push("UK");       
                        }
                    }       
                }
                var options="<option>Select Style Number</option>";
                for(i = 0; i< uniqueNames.length; i++){    
                     options+="<option data-zone="+zones[i]+">"+uniqueNames[i]+"</option>";
                }

                $('#po-file-upload-button').click(function () {
                    $('#po-file-upload-group').modal("show")
                    $('#uvc_select_div').html('<label>Select Style Number</label><select id="style_num" class="form-control">'+options+'</select>')
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

                    $('#style_num').change(function(){
                        if($(this).val() != "Select Style Number")
                        {
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
                            var zone     = selected.data('zone'); 
                            $.ajax({
                                url: base_url + "/customer/tesco/get-artworks",
                                type: "get",
                                dataType: "json",
                                data: {
                                    styleNum: styleNum,
                                    zone:zone
                                },
                                beforeSend: function () {

                                },
                                statusCode:
                                {
                                    /** [Show response status based on HTTP status code] */
                                    200: function(data)
                                    { 
                                        
                                        $('#check_error_status').addClass('hidden')
                                        $('#new_po_error_status').addClass('hidden')
                                        $('#new_po_file_error_status').addClass('hidden')
                                        $('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
                                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var unique_po = []
                                        var old_po = "" 
                                        $(newObj).each(function (index1, value1) {
                                            
                                            
                                            if (value1.po_number != "" && value1.   purchase_order_no != null)
                                            {
                                                if (old_po!=value1.purchase_order_no)
                                                {
                                                    options += "<option>" + value1.purchase_order_no +"</option>";
                                                    console.log(old_po)
                                                }
                                                old_po = value1.purchase_order_no
                                                coun++;
                                            }
                                            $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.tableName+'-'+value1.id+'" data-company="'+value1.company_id+'"class="list-group-item">'+value1.TemplateName+'</li>')
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
                                    $('.list-group-item').click(function(){
                                        $count_li=0;
                                        $('.list-group li').each(function(key,val){
                                            if($(this).hasClass('active'))
                                            {
                                                $count_li++;
                                            }
                                        });
                                        //alert($count_li);
                                        if($count_li > 0)
                                        {
                                            var company_id=$(this).data('company');
                                            $('.list-group li').each(function(key,val){
                                                if($(this).data('company') != company_id)
                                                {
                                                    $(this).addClass('hidden');
                                                }
                                            })
                                        }
                                        else
                                        {
                                            $('.list-group li').each(function(key,val){
                                                $(this).removeClass('hidden');
                                            })
                                        }

                                    })


                                        $('#selectpo_status').html('<label>Select PO</label><select id="select_po_status" class="form-control">' + options + '</select>')
                                        if (coun == 0) {
                                            $('#map_po_status').addClass('hidden');
                                            $('#new_po1_status').addClass('hidden');
                                            $('#new_po_div_status').removeClass('hidden');
                                            $('#new_pos_status').removeClass('hidden');
                                        }
                                        else
                                        {
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

                                        if (sList > 0)
                                        {
                                            $('#update_po_status').removeClass('hidden')
                                            $('#update_new_po_status').addClass('hidden')
                                            $('#new_pos_status').addClass('hidden')
                                            $('#map_po_div_status').removeClass('hidden')
                                            $('#new_po_div_status').addClass('hidden')
                                            $('#exist_artwork_div_status').removeClass('hidden')
                                            $('#check_error_status').addClass('hidden')

                                        }
                                        else {
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
                                        if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() !="") {
                                            var a = [];
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    a.push($(this).parent().attr('data-id'));
                                                    //alert($(this).parent().attr('data-id'))
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
                                                url: base_url + "/customer/tesco/update-po",
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
                                        else
                                        {
                                           if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_error_status').removeClass('hidden')
                                                    $('#new_po_file_error_status').removeClass('hidden') 
                                                }
                                                else if ($('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_file_error_status').removeClass('hidden')
                                                    $('#new_po_error_status').addClass('hidden')   
                                                }
                                                else if ($('#new_po_no_status').val() == "")
                                                {
                                                     $('#new_po_error_status').removeClass('hidden')
                                                     $('#new_po_file_error_status').addClass('hidden')
                                                }
                                            
                                        }
                                        } else {
                                            $('#check_error_status').removeClass('hidden')
                                        }
                                    });
                                    
                                    $('#new_pos_status').click(function ()
                                    {
                                        var sList = 0;
                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                            if (this.checked) {
                                                sList++;
                                            }
                                        });

                                        if (sList > 0) {
                                            $('#check_error_status').addClass('hidden')
                                            if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() !="")
                                            {
                                                var a = [];
                                                $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                    if (this.checked) {
                                                        a.push($(this).parent().attr('data-id'));
                                                        //alert($(this).parent().attr('data-id'))
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
                                                    url: base_url + "/customer/tesco/update-po",
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
                                            } else
                                            {
                                                if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_error_status').removeClass('hidden')
                                                    $('#new_po_file_error_status').removeClass('hidden') 
                                                }
                                                else if ($('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_file_error_status').removeClass('hidden')
                                                    $('#new_po_error_status').addClass('hidden')   
                                                }
                                                else if ($('#new_po_no_status').val() == "")
                                                {
                                                     $('#new_po_error_status').removeClass('hidden')
                                                     $('#new_po_file_error_status').addClass('hidden')
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
                                         
                                                if ($('#select_po_status').val() != "Select PO")
                                                {
                                                var a = [];
                                                $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                    if (this.checked) {
                                                        a.push($(this).parent().attr('data-id'));
                                                    }
                                                });
                                                var aa = a.join(',');
                                                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                $.ajax({
                                                    url: base_url + "/customer/tesco/copy-po",
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
                                            }
                                            else
                                            {
                                                $('#select_error_status').removeClass('hidden')
                                            }
                                        }
                                            else
                                            {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                    });

                                    },
                                    400: function()
                                    {
                                       $('.snackbar_span').html('Something went wrong');
                                       $('#snackbar').css('background-color','#F44336');
                                       showSnackbar();
                                    }
                                },
                                error: function (data){
                                    console.log(data);
                                },
                                complete: function () {
                                    $(".loader").hide();
                                }
                            })
                        }
                    });

                });
                           
               var $tableSel = $('#datatable-tesco-orders');
                $tableSel.DataTable({
                    "pageLength": 25,
                     "order": [[ 6, "desc" ]],
                     "aoColumns": [
                                null,
                                null,
                                null,
                                null,
                                null,
                                { "sType": "date-uk" },
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
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="buyerPo"> <option disabled selected>Select PO Number</option></select></div><div class="col-md-3"><select class="selectpicker" id="styleNumber"> <option disabled selected>Select Style Number</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectTemplate"> <option disabled selected>Select Template Name</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');
                        //For Custom Filters
                        this.api().columns().every(function (i) {
                            var column = this;
                            if (i === 0 || i === 1 || i === 3) {
                                var select;
                                switch (i) {
                                    case 0:
                                        select = $('#styleNumber')
                                        break;
                                    case 1:
                                        select = $('#buyerPo')
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
                    $('#buyerPo').val('').change();
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
                        // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        $.fn.dataTableExt.afnFiltering.length = 0;
                        $tableSel.dataTable().fnDraw();
                        filterByDate(5, start, end); // We call our filter function
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
                $("#datatable-tesco-orders_filter input").addClass('form-control input-sm');
                $("#datatable-tesco-orders_filter input").attr('placeholder', 'Data Search');
                $('#datatable-tesco-orders_length select').addClass('form-control input-sm');
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

function showFilter() {
    $('.filter-window').toggle();
}

/* Our main filter function
 * We pass the column location, the start date, and the end date
 */
var filterByDate = function(column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
        $.fn.dataTableExt.afnFiltering.push(
            function( oSettings, aData, iDataIndex ) {
                var rowDate = normalizeDate(aData[column]),
              start = normalizeDate(startDate),
              end = normalizeDate(endDate);
          
          // If our date from the row is between the start and end
          if (start <= rowDate && rowDate <= end) {
            return true;
          } else if (rowDate >= start && end === '' && start !== ''){
            return true;
          } else if (rowDate <= end && start === '' && end !== ''){
            return true;
          } else {
            return false;
          }
        }
        );
    };

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}

function table(data) {
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn) {
        if (key != "id") {
            if (key == "SupplierName" || key == "Style_Name") {
                th += "<th>" + key + "</th>";
            } else {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }

        }
    }
    $.each(data, function (index, value) {
        tr += '<tr id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id") {
                if (key == "Type") {
                    if (value[key] == "Null") {
                        var Isstd = "-";
                    } else {
                        var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    }
                    tr += '<td align="center">' + Isstd + '</td>';

                } else if (key == "Disney") {
                    if (value[key] == "Null") {
                        var Disney = "-";
                    } else {
                        var Disney = value[key] == "0" ? "No" : "Yes";
                    }

                    tr += '<td align="center">' + Disney + '</td>';
                } else if (key == "UVC") {
                    tr += '<td align="center"><a href="#" onclick=order_details("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
                } else if (key == "Status") {
                    tr += '<td align="center"><span class="label label-' + value[key] + '">' + value[key] + '</span></td>';
                } else {
                    if (key == "SupplierName" || key == "Style_Name") {
                        tr += '<td>' + value[key] + '</td>';
                    } else {
                        tr += '<td align="center">' + value[key] + '</td>';
                    }

                }
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';

}

//Global Variable
var td_row = "";

/**
 * method to show the tesco order status table
 * @return {[type]} [description]
 */
function tescoOrderStatus()
{
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.top-content').html('');
    $('.template-error').html('');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/tesco/tesco-orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               var path = base_url + "/customer/tesco/order-management/order-status";
               var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
               
               $('.main-content').html("<button style='margin-bottom:10px' id='po-file-upload-button' class='btn btn-primary pull-right' type='button'>Update PO</button>");

               $('.main-content').append(tescoStatusTable(newObj,path));
               $('.top-content').html("")
                
                var uniqueNames = [];
                var zones = [];
                for(i = 0; i< newObj.length; i++){ 
                    if(newObj[i].Zone == "CE")
                    {
                        if(uniqueNames.indexOf(newObj[i].ce_style_ref) === -1){
                            uniqueNames.push(newObj[i].ce_style_ref);
                            zones.push("CE");      
                        } 
                    }
                    else
                    {
                        if(uniqueNames.indexOf(newObj[i].uk_style_ref) === -1){
                            uniqueNames.push(newObj[i].uk_style_ref);
                            zones.push("UK");       
                        }
                    }       
                }
                var options="<option>Select Style Number</option>";
                for(i = 0; i< uniqueNames.length; i++){    
                     options+="<option data-zone="+zones[i]+">"+uniqueNames[i]+"</option>";
                }

                $('#po-file-upload-button').click(function () {
                    $('#po-file-upload-group').modal("show")
                    $('#uvc_select_div').html('<label>Select Style Number</label><select id="style_num" class="form-control">'+options+'</select>')
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

                    $('#style_num').change(function(){
                        if($(this).val() != "Select Style Number")
                        {
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
                            var zone     = selected.data('zone'); 
                            $.ajax({
                                url: base_url + "/customer/tesco/get-artworks",
                                type: "get",
                                dataType: "json",
                                data: {
                                    styleNum: styleNum,
                                    zone:zone
                                },
                                beforeSend: function () {

                                },
                                statusCode:
                                {
                                    /** [Show response status based on HTTP status code] */
                                    200: function(data)
                                    { 
                                        
                                        $('#check_error_status').addClass('hidden')
                                        $('#new_po_error_status').addClass('hidden')
                                        $('#new_po_file_error_status').addClass('hidden')
                                        $('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
                                        var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                                        var coun = 0;
                                        var options = "<option>Select PO</option>";
                                        var unique_po = []
                                        var old_po = "" 
                                        $(newObj).each(function (index1, value1) {
                                            
                                            
                                            if (value1.po_number != "" && value1.   purchase_order_no != null)
                                            {
                                                if (old_po!=value1.purchase_order_no)
                                                {
                                                    options += "<option>" + value1.purchase_order_no +"</option>";
                                                    console.log(old_po)
                                                }
                                                old_po = value1.purchase_order_no
                                                coun++;
                                            }
                                            $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.tableName+'-'+value1.id+'" data-company="'+value1.company_id+'"class="list-group-item">'+value1.TemplateName+'</li>')
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
                                    $('.list-group-item').click(function(){
                                        $count_li=0;
                                        $('.list-group li').each(function(key,val){
                                            if($(this).hasClass('active'))
                                            {
                                                $count_li++;
                                            }
                                        });
                                        //alert($count_li);
                                        if($count_li > 0)
                                        {
                                            var company_id=$(this).data('company');
                                            $('.list-group li').each(function(key,val){
                                                if($(this).data('company') != company_id)
                                                {
                                                    $(this).addClass('hidden');
                                                }
                                            })
                                        }
                                        else
                                        {
                                            $('.list-group li').each(function(key,val){
                                                $(this).removeClass('hidden');
                                            })
                                        }

                                    })


                                        $('#selectpo_status').html('<label>Select PO</label><select id="select_po_status" class="form-control">' + options + '</select>')
                                        if (coun == 0) {
                                            $('#map_po_status').addClass('hidden');
                                            $('#new_po1_status').addClass('hidden');
                                            $('#new_po_div_status').removeClass('hidden');
                                            $('#new_pos_status').removeClass('hidden');
                                        }
                                        else
                                        {
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

                                        if (sList > 0)
                                        {
                                            $('#update_po_status').removeClass('hidden')
                                            $('#update_new_po_status').addClass('hidden')
                                            $('#new_pos_status').addClass('hidden')
                                            $('#map_po_div_status').removeClass('hidden')
                                            $('#new_po_div_status').addClass('hidden')
                                            $('#exist_artwork_div_status').removeClass('hidden')
                                            $('#check_error_status').addClass('hidden')

                                        }
                                        else {
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
                                        if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() !="") {
                                            var a = [];
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    a.push($(this).parent().attr('data-id'));
                                                    //alert($(this).parent().attr('data-id'))
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
                                                url: base_url + "/customer/tesco/update-po",
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
                                        else
                                        {
                                           if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_error_status').removeClass('hidden')
                                                    $('#new_po_file_error_status').removeClass('hidden') 
                                                }
                                                else if ($('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_file_error_status').removeClass('hidden')
                                                    $('#new_po_error_status').addClass('hidden')   
                                                }
                                                else if ($('#new_po_no_status').val() == "")
                                                {
                                                     $('#new_po_error_status').removeClass('hidden')
                                                     $('#new_po_file_error_status').addClass('hidden')
                                                }
                                            
                                        }
                                        } else {
                                            $('#check_error_status').removeClass('hidden')
                                        }
                                    });
                                    
                                    $('#new_pos_status').click(function ()
                                    {
                                        var sList = 0;
                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                            if (this.checked) {
                                                sList++;
                                            }
                                        });

                                        if (sList > 0) {
                                            $('#check_error_status').addClass('hidden')
                                            if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() !="")
                                            {
                                                var a = [];
                                                $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                    if (this.checked) {
                                                        a.push($(this).parent().attr('data-id'));
                                                        //alert($(this).parent().attr('data-id'))
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
                                                    url: base_url + "/customer/tesco/update-po",
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
                                            } else
                                            {
                                                if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_error_status').removeClass('hidden')
                                                    $('#new_po_file_error_status').removeClass('hidden') 
                                                }
                                                else if ($('#new_po_file_status').val() =="")
                                                {
                                                    $('#new_po_file_error_status').removeClass('hidden')
                                                    $('#new_po_error_status').addClass('hidden')   
                                                }
                                                else if ($('#new_po_no_status').val() == "")
                                                {
                                                     $('#new_po_error_status').removeClass('hidden')
                                                     $('#new_po_file_error_status').addClass('hidden')
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
                                         
                                                if ($('#select_po_status').val() != "Select PO")
                                                {
                                                var a = [];
                                                $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                    if (this.checked) {
                                                        a.push($(this).parent().attr('data-id'));
                                                    }
                                                });
                                                var aa = a.join(',');
                                                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                $.ajax({
                                                    url: base_url + "/customer/tesco/copy-po",
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
                                            }
                                            else
                                            {
                                                $('#select_error_status').removeClass('hidden')
                                            }
                                        }
                                            else
                                            {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                    });

                                    },
                                    400: function()
                                    {
                                       $('.snackbar_span').html('Something went wrong');
                                       $('#snackbar').css('background-color','#F44336');
                                       showSnackbar();
                                    }
                                },
                                error: function (data){
                                    console.log(data);
                                },
                                complete: function () {
                                    $(".loader").hide();
                                }
                            })
                        }
                    });

                });
                           
               var $tableSel = $('#datatable-tesco-orders');
                $tableSel.DataTable({
                    "pageLength": 25,
                     "order": [[ 7, "desc" ]],
                     "aoColumns": [
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,
                                { "sType": "date-uk" },
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
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="styleNumber"> <option disabled selected>Select Style Number</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectTemplate"> <option disabled selected>Select Template Name</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');
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
                        // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
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
                $("#datatable-tesco-orders_filter input").addClass('form-control input-sm');
                $("#datatable-tesco-orders_filter input").attr('placeholder', 'Data Search');
                $('#datatable-tesco-orders_length select').addClass('form-control input-sm');
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
"date-uk-pre": function ( a ) {
    var ukDatea = a.split('-');
    return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
},

"date-uk-asc": function ( a, b ) {
    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
},

"date-uk-desc": function ( a, b ) {
    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
}
} );

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
            var columnDate=aData[column].toString().split("-").reverse().join("-");
        
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
function tescoStatusTable(data,path)
{
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn)
    {
        if (key != "tableName" && key!="tableID" && key!="po_file" && key!="pi_required" && key!="uk_style_ref" && key!="ce_style_ref"&& key != "template_id" && key!="customer_id" && key!="order_flag")
        {
            th += "<th style='text-align: center;'>" + key + "</th>";
        }
    }
    $.each(data, function (index, value)
    {
        var action_status = false;
        tr += '<tr class="trClass" id="'+value.tableName+'_'+ value.tableID + '">';
        for (var key in columnsIn) {
            if (key != "id")
            {
                
                if (key =="PO Number") 
                {
                    var po_file     = value['po_file'];
                    if (value[key]== "" || value[key]==null)
                    {
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"'+value.tableID+'","'+value.tableName+'","'+po_file+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                    }
                    else
                    {
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" value="'+value[key]+'"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"'+value.tableID+'","'+value.tableName+'","'+po_file+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                    }
                }
                else if (key == "OrderDate")
                {
                    var orderDate = moment(value[key] ).format('DD-MM-YYYY');
                    tr += '<td align="center">'+orderDate+'</td>';

                   
                }
                
                else if (key == "PI Status")
                {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                       if (str=="PI Approved"){
                            action_status = true;
                        }
                       $status=str.replace(/ /g, "");
                       $status_text='<span class="pi_class label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }
                else if (key == "Artwork Status") {
                    var str=value[key] ;
                    $status=str.replace(/ /g, "");
                    
                    tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                   
                }
                else if (key == "PO Status")
                {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $status=str.replace(/ /g, "");
                        $status_text='<span class="po_class label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center class="po_status">'+$status_text+'</td>';
                   
                }
                else if (key =="StyleNo")
                {
                    var order_flag = value.order_flag;
                    var span_style ='';
                    if (order_flag == "reOrder")
                    {
                        span_style = '<span title="Additional Quantity" class="batch-additional">A</span>'
                    }
                    if (order_flag == "repeatOrder")
                    {
                        span_style = '<span title="Reorder" class="batch-reorder">R</span>'
                    }
                    var zone = value.Zone
                    var style_no = "";
                    if (zone=="CE")
                    {
                        style_no = value.ce_style_ref;

                        tr += '<td align="center" data-id="'+value.tableID+'" data-table="'+value.tableName+'" data-template="'+value.TemplateName.replace(/ /g, "")+'"><a class="" href="javascript:void(0)" onclick=updateArtwork(this)>'+span_style+''+style_no+'</a></td>';

                    }
                    else
                    {
                        style_no = value.uk_style_ref;
                        
                        tr += '<td align="center" data-id="'+value.tableID+'" data-table="'+value.tableName+'" data-template="'+value.TemplateName.replace(/ /g, "")+'"><a class="" href="javascript:void(0)" onclick=updateArtwork(this)>'+span_style+''+style_no+'</a></td>'; 
                   
                    }
                }
                else if (key == "Actions")
                {
                   var po_file     = value['po_file'];
                   if (po_file!=null)
                   {
                        style_po = "style='border-color:#FFFFFF'"
                   }
                   else{
                        style_po = "style='border-color:#FF0000'"
                   }                
                   var pi_required = value['pi_required'];
                   
                   if (pi_required==1)
                   {
                        tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateTescoPI(this,"'+value.tableID+'","'+value.tableName+'","'+action_status+'","'+style_no+'","'+value.template_id+'","'+value.BuyerPO+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=tescoPOUpload(this,"'+value.tableID+'","'+po_file+'","'+value.tableName+'") class="po-style" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" '+style_po+'></i></a></td>';
                   }
                   else
                   {
                        tr += '<td align="center"><a href="javascript:void(0)" style="margin-left:36px" class="po-style" onclick=tescoPOUpload(this,"'+value.tableID+'","'+po_file+'","'+value.tableName+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" '+style_po+'></i></a></td>';
                   }                  
                }
                else if (key =="DeliveryDate")
                {   
                    
                   if (value[key] == '00-00-0000')
                   {
                        tr += '<td><span style=display:none;>'+value.OrderDate+'</span><input class="form-control input-sm datepicker_recurring_start" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Enter Delivery Date" onchange=updateDeliveryDate(this,"'+value.tableID+'","'+value.tableName+'")  readonly></td>';
                   }
                   else
                   {
                       
                        tr += '<td><span style=display:none;>'+value.OrderDate+'</span><input value="' + value[key] + '"class="form-control input-sm datepicker_recurring_start" style="width: 107px;" placeholder="Enter Delivery Date" onchange=updateDeliveryDate(this,"'+value.tableID+'","'+value.tableName+'") readonly></td>';
                   }
                  
                   
                  
                }
                else if (key != "tableName" && key!="tableID" && key!="po_file" && key!="pi_required" && key!="uk_style_ref" && key!="ce_style_ref" && key != "template_id"  && key!="customer_id" && key!="order_flag")
                {
                    if (value[key]== "" || value[key]==null)
                    {
                        tr += '<td> - </td>';
                    }
                    else
                    {   
                        tr += '<td>' + value[key] + '</td>';
                    }
                }                
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-tesco-orders" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


$('body').on('focus',".datepicker_recurring_start", function(){
    $(this).datepicker({
        autoclose: true,
        startDate: "+7d",
        format:'dd-mm-yyyy'
    });
});
function updateDeliveryDate(obj,tableID,tableName)
{
    var date = new Date();
    var dd = date.getDate() +7;
    var mm = date.getMonth()+1; //January is 0!
    var yyyy = date.getFullYear();
    if(dd<10){
        dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var today = dd+'-'+mm+'-'+yyyy;
    $(obj).closest('tr').find('.datepicker_recurring_start').css('border-color', '');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var deliveryDate = $(obj).closest('tr').find('.datepicker_recurring_start').val();

    if (deliveryDate=="")
    {
        $(obj).closest('tr').find('.datepicker_recurring_start').css('border-color', 'rgb(244, 67, 54)');
    }
   
            $('.snackbar_span').html('');
            $.ajax({
            url: base_url + "/customer/tesco/update-delivery-date",
            type: "Post",
            dataType: "json",
            data: {
                _token: csrf_token,
                deliveryDate:deliveryDate,
                tableName:tableName,
                id:tableID
            },
            beforeSend: function () {
                $(".loader").show();
            },
            statusCode:
            {
                /** [Show response status based on HTTP status code] */
                200: function(data)
                {
                   $('#snackbar').css('background-color','#00b6ff');
                   $('.snackbar_span').html('Delivery Date updated Successfully');
                  
                   showSnackbar();                        
                },
                400: function()
                {
                   $('.snackbar_span').html('Something went wrong');
                   $('#snackbar').css('background-color','#F44336');
                   showSnackbar();
                }
            },
            error: function (data){
                console.log(data);
            },
            complete: function (){
                $(".loader").hide();
            }
        })    
}

/**
 * [tescoSetPO description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoSetPO(obj,id,tableName,po_file)
{
   $('.tesco-po').css('border-color', '');
   var po_number  = $(obj).closest('tr').find('.tesco-po').val();
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   if (po_number!="")
   {
    $.ajax({
        url: base_url + "/customer/tesco/order-management/set-po-number",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po_number:po_number,
            tableName:tableName,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('#snackbar').css('background-color','#00b6ff');
               $('.snackbar_span').html('Purchase Order Number added Successfully');
               $(obj).closest('tr').find('.tesco-po').val(po_number);
               
               if ($(obj).closest('tr').find('.po_class').text() =="PO Pending" && po_file!="null")
               {
                 $(obj).closest('tr').find('.po_class').html("PO Uploaded")
                 $(obj).closest('tr').find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
               }

               showSnackbar();
               if (po_number==""){
                 $(obj).closest('tr').find('.tesco-po').attr('disabled');
                 $(obj).closest('tr').find('.tesco-po').attr('placeholder', "Enter PO");
               }   
                         
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
   }
   else
   {
       $(obj).closest('tr').find('.tesco-po').focus();
       $(obj).closest('tr').find('.tesco-po').css('border-color', '#F44336');
       $('.snackbar_span').html('Please enter a valid PO number');
       $('#snackbar').css('background-color','#F44336');
       showSnackbar();
   }
}   
/**
 * [tescoPOUpload description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoPOUpload(obj,id,po_file,tableName)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content'); 
    if (po_file== "null") 
    { 
        $('#po_upload').removeClass('hidden') 
        $('.upload-po').removeClass('hidden') 
        $('#po_file').addClass('hidden') 
    } 
    else 
    { 
        $('#po_upload').addClass('hidden') 
        $('.upload-po').addClass('hidden') 
        $('#po_file').removeClass('hidden') 
    } 
    var po_link = base_url+po_file 
    $('#po_link').attr("href",po_link); 
    $('#rowID').val(id); 
    $('#tableName').val(tableName); 
    $('#po-file-upload').modal('show') 
}

$(".update-po-file").click(function () {
    $('#po_upload').removeClass('hidden')
    $('.upload-po').removeClass('hidden')
});

/**
 * call the method for getting all the details for profoma invoice generation
 * @return {[type]} [description]
 */
function genarateTescoPI(obj,id,tableName,pi_status,style_no,template_id,buyer_po)
{
    $('#uvc_number').text(style_no)
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("pricing_buyer_id");
    var tr = "";
    $.ajax({
        url: base_url + "/customer/tesco/order-management/show-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: tableName,
            buyer_id :buyer_id,
            pi_status:pi_status,
            style_no:style_no,
            template_id:template_id,
            buyer_po:buyer_po,
            id:id
        },
        beforeSend: function (){
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               if ((jQuery.isEmptyObject(data.data.company_details)) || (jQuery.isEmptyObject(data.data.bank_details)) || (jQuery.isEmptyObject(data.data.ship_to)) || (jQuery.isEmptyObject(data.data.bill_to)))
               {
                    $('.snackbar_span').html('Something went wrong');
                    $('#snackbar').css('background-color','#F44336');
                    showSnackbar();
               }
               else
               {
                    console.log(data.data.bank_details.pi_date)
                    $('.advnc').text(data.data.bank_details.payment_terms)
                    $('.freight').text(data.data.bank_details.freight_terms)
                    $('.pi_number').text(data.data.company_details.pi_number)
                    $('.pi-date').text(moment(data.data.company_details.pi_date).format('DD/MM/YYYY'))
                    if (data.data.company_details.rev_number!=0)
                    {
                       $('.rev').removeClass('hidden')
                       $('.rev_number').text(data.data.company_details.rev_number) 
                    }
                    

                    $('#company_details').html('<h3><strong>'+data.data.company_details.name+'</strong></h3>'+data.data.company_details.address+'<br/>'+data.data.company_details.phone+'<br/>GSTN : '+data.data.company_details.gstin)
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'</br> GSTN :'+data.data.bank_details.gstin_number+ '</p>')
                    $('#ship_to').html('<h4>Shipping Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.ship_to.address+'</p>')
                    $('#bank_details').html(' <strong> Bank Details: </strong><table class="table table-condensed" style="width:50%;"><tr><td>Bank</td><td>'+data.data.company_details.bank+'</td></tr><tr><td>A/C TYPE</td><td>'+data.data.company_details.account_type+'</td></tr><tr><td>A/C NUMBER</td><td>'+data.data.company_details.account_no+'</td></tr><tr><td>ACCOUNT NAME</td><td>'+data.data.company_details.account_holder+'</td></tr><tr><td>ADDRESS</td><td>'+data.data.company_details.branch+'</td></tr><tr><td>RTGS & IFSC</td><td>'+data.data.company_details.ifsc_code+'</td></tr></table>')
               
               
                    var i = 0;
                    var totalqnty = 0;
                    var subtotal = 0;
                    var tax = 0 ;
                    var tr = '';
                    
                    if (data.data.bank_details.template_type == "single")
                    {
                        tr += '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S. No.</th><th>Artwork</th><th class="size-pi">Size</th><th>Style</th><th>Quantity</th><th>Unit Cost (₹)</th><th>Total (₹)</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';    
                    }
                    else
                    {
                        tr += '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S. No.</th><th>Artwork</th><th>Style</th><th>Quantity</th><th>Unit Cost (₹)</th><th>Total (₹)</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';
                    }        
                     
               
                    if (data.data.item_details.length!=0)
                    {

                        tax = data.data.item_details[0].tax;
                        $.each(data.data.item_details, function (index, value)
                        {
                            if (data.data.bank_details.template_type == "single")
                            {
                                size = value.size ;
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.material_name+'</td><td>'+size+'</td><td>'+value.style+'</td><td>'+value.quantity+'</td><td>'+value.unit_price+'</td><td>'+(value.quantity*value.unit_price).toFixed(2)+'</td></tr>'
                            }
                            else
                            {
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.material_name+'</td><td>'+value.style+'</td><td>'+value.quantity+'</td><td>'+value.unit_price+'</td><td>'+(value.quantity*value.unit_price).toFixed(2)+'</td></tr>'
                            }
                            
                            
                            totalqnty+=value.quantity;
                            subtotal+=value.quantity*value.unit_price;

                        });
                        var taxamnt= subtotal*(tax/100);
                        var billamnt = subtotal+taxamnt;
                        tr += '</tbody></table></div><hr><div><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4></div><hr><div><h4 class="text-right"> Sub-Total : ₹ '+subtotal.toFixed(2)+' </h4></div><hr/><div class="text-right">'
                        if (data.data.bank_details.place_of_supply==1)
                        {
                           tr += '<h4> CGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4><h4> SGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p  style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                        }
                        else if (data.data.bank_details.place_of_supply==2)
                        {
                            tr += '<h4> IGST : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+' </p></div><hr /></div>'
                        }
                        else
                        {
                            tr += '<h4> Taxes : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹'+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                        }
                        $('#item_details').html(tr)
                        if (pi_status=="true")
                        {
                            $('.pi_actions').html('<button type="button" class="btn btn-default btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                        else
                        {
                            $('.pi_actions').html('<button class="btn btn-success btn-xs" onclick=tescoApprovePI("'+id+'","'+tableName+'","tesco")>Approve</button><button class="btn btn-danger btn-xs" onclick=tescoRejectPI("'+id+'","'+tableName+'","tesco")>Reject</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                        var logo = base_url+data.data.company_details.logo
                        $('#company-logo').attr('src',logo);
                        $('#profoma-invoice').modal("show")
                    }
                    else
                    {
                        $('.snackbar_span').html('Price Details are Missing !!');
                        $('#snackbar').css('background-color','#F44336');
                        showSnackbar();
                    }
                } 
            
            },
            400: function(data)
            {
               $('.snackbar_span').html('Something went wrong');
               showSnackbar();
            }
        },
        error: function (data)
        {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}


/**
 * method to call the PI approval functionality
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @return {[type]}            [description]
 */
function tescoApprovePI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("pricing_buyer_id");
    $.ajax({
        url: base_url + "/customer/tesco/approve-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id:buyer_id,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Approved Successfully');
               $('#snackbar').css('background-color','#00b6ff');
               $('#profoma-invoice').modal("hide");
               $('#'+table_name+'_'+id+' .pi_class').removeClass().addClass('pi_class label label-PIApproved').text('PI Approved');
               var attrbs = $('#'+table_name+'_'+id+' .pi_generation').attr('onclick');
               var attrbs = attrbs.replace("false", "true");
               $('#'+table_name+'_'+id+' .pi_generation').attr('onclick', attrbs);
               showSnackbar();

            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })

}
/**
 * method to call the PI rejection functionality
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @return {[type]}            [description]
 */
function tescoRejectPI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("pricing_buyer_id");
    $.ajax({
        url: base_url + "/customer/tesco/reject-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id:buyer_id,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Rejected!');
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               $('#'+table_name+'_'+id+' .pi_class').removeClass().addClass('pi_class label label-PIRejected').text('PI Rejected');
               showSnackbar();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#snackbar').css('background-color','#F44336');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}
function updateArtwork(obj)
{
    $('#po_already_error').addClass('hidden')
    var id          =$(obj).parent().attr('data-id');
    var table_name  =$(obj).parent().attr('data-table');
    var TemplateName=$(obj).parent().attr('data-template');
    
    td_row          =$(obj).parent().parent();
    
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/tesco/edit-artwork",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
                if (data.order_details!=0)
                {
                    $('#tesco_po_number').val(data.order_details.po_number);
                    $('#tesco_uk_style').val(data.order_details.uk_style_ref);
                    $('#tesco_ce_style').val(data.order_details.ce_style_ref);
                    $('#tesco_eqos').val(data.order_details.eqos_site_code);
                    $('#tesco_description').val(data.order_details.short_description);                   
                    $('#tesco_season_code').val(data.order_details.season_code);
                    $('#tesco_manufacture_date').val(data.order_details.manufacture_date);
                    
                    
                    var tag_at_source = data.order_details.tag_at_source == 0 ? "no" : "yes";
                    var rfid = data.order_details.rfid_compliant == 0 ? "no" : "yes";
                
                    $('#tesco_supplier').selectpicker('val',data.order_details.tesco_supplier_id);
                    $('#tesco_brand').selectpicker('val',data.order_details.tesco_brand_id);
                    $('#tesco_department').selectpicker('val',data.order_details.tesco_department_id);
                    $('#tesco_rfid').selectpicker('val',rfid);
                    $('#tesco_tag_at_source').selectpicker('val',tag_at_source);
					
					//Disable option for Customer
					
					$('#tesco_po_number').prop('disabled',true);
                    $('#tesco_uk_style').prop('disabled',true);
                    $('#tesco_ce_style').prop('disabled',true);
                    $('#tesco_eqos').prop('disabled',true);
                    $('#tesco_description').prop('disabled',true);            
                    $('#tesco_season_code').prop('disabled',true);
                    $('#tesco_manufacture_date').prop('disabled',true);
					$('#tesco_supplier').prop('disabled',true);
                    $('#tesco_brand').prop('disabled',true);
                    $('#tesco_department').prop('disabled',true);
                    $('#tesco_rfid').prop('disabled',true);
                    $('#tesco_tag_at_source').prop('disabled',true);
					
                    
                    $('.artwork_tesco').html('<object style="border: 2px #333 solid;" id="svg_tesco_os" class="center-block svg2" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;position: absolute;"><svg id="ean-13"></svg></div>')
                    
                    var zone =data.order_details.zone;
                    
                    if((TemplateName == "TCL1" && zone=="CE") ||(TemplateName == "TCL2" && zone=="CE") || (TemplateName == "TSL1" && zone=="CE") || (TemplateName == "THL1" && zone=="CE"))
                    {
                        var qty="Cases per carton";
                        var barcode_tr="ITF Barcode";
                        var tpnd_tr="ORMS TPND";
                        var th_tr="";
                    }
                    else if((TemplateName == "TCL4" && zone=="CE") || (TemplateName == "TSL3" && zone=="CE") || (TemplateName == "THL3" && zone=="CE"))
                    {
                        var qty="Case Size";
                        var barcode_tr="EAN Barcode";
                        var tpnd_tr="ORMS TPND";
                        var th_tr="<th>Size</th>";
                    }
                    else if((TemplateName == "TCL4" && zone=="UK") || (TemplateName == "TSL3" && zone=="UK") || (TemplateName == "THL3" && zone=="UK"))
                    {
                        var qty="Case Size";
                        var barcode_tr="EAN Barcode";
                        var tpnd_tr="TPND";
                        var th_tr="<th>Size</th>";
                    }
                    else if((TemplateName == "TCL1" && zone=="UK") ||(TemplateName == "TCL2" && zone=="UK") || (TemplateName == "TSL1" && zone=="UK") || (TemplateName == "THL1" && zone=="UK"))
                    {
                        var qty="Units per carton";
                        var barcode_tr="ITF Barcode";
                        var tpnd_tr="TPND";
                        var th_tr="";
                    }
                   
                    var tr="";
                    var i=1;
                    $(data.quantity_details).each(function(key,value){
                        
                        if((TemplateName == "TCL1" && zone=="CE") ||(TemplateName == "TCL2" && zone=="CE") || (TemplateName == "TSL1" && zone=="CE") || (TemplateName == "THL1" && zone=="CE"))
                        {
                            var unit_qty = value.cases_per_carton;
                            var barcode_td=value.itf_barcode;
                            var maxlength=14;
                            var tpnd=value.orms_tpn;
                            var th_td="";
                            var tpn_th="Enter ORMS TPND";
                            var tpn_len="13";
                            var unit_len="5";
                        }
                        else if((TemplateName == "TCL4" && zone=="CE") || (TemplateName == "TSL3" && zone=="CE") || (TemplateName == "THL3" && zone=="CE"))
                        {
                            var unit_qty = value.case_size;
                            var barcode_td=value.ean_barcode;
                            var maxlength=13;
                            var tpnd=value.orms_tpn;
                            var th_td='<td><div class="form-group has-feedback"> <input disabled type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_'+i+'" name="os_single_size_'+i+'"  value="'+value.size+'" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
                            var tpn_th="Enter ORMS TPND";
                            var tpn_len="13";
                            var unit_len="6";
                        }
                        else if((TemplateName == "TCL4" && zone=="UK") || (TemplateName == "TSL3" && zone=="UK") || (TemplateName == "THL3" && zone=="UK"))
                        {
                            var unit_qty = value.case_size;
                            var barcode_td=value.ean_barcode;
                            var maxlength=13;
                            var tpnd=value.tpnd;
                            var th_td='<td><div class="form-group has-feedback"> <input disabled type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_'+i+'" name="os_single_size_'+i+'" value="'+value.size+'" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
                            var tpn_th="Enter TPND";
                            var tpn_len="9";
                            var unit_len="4";
                        }
                        else if((TemplateName == "TCL1" && zone=="UK") ||(TemplateName == "TCL2" && zone=="UK") || (TemplateName == "TSL1" && zone=="UK") || (TemplateName == "THL1" && zone=="UK"))
                        {
                            var unit_qty = value.units_per_carton;
                            var barcode_td=value.itf_barcode;
                            var maxlength=14;
                            var tpnd=value.tpnd;
                            var th_td="";
                            var tpn_th="Enter TPND";
                            var tpn_len="9";
                            var unit_len="4";
                        }
                        
                        if(value.id == data.quantity_details[0].id)
                        {
                            var delete_td = '';
                        }
                        else
                        {
                            var delete_td = '<a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRowOs(this)"></i></a>';
                        }
                        
                            tr +='<tr><td class="tpnd"><div class="form-group has-feedback"> <input type="text" disabled class="form-control input-sm ce_ratio_orms_tpn" required="" id="os_tpnd_'+i+'" maxlength="'+tpn_len+'" name="os_tpnd_'+i+'" onkeypress="return isNumber(event)" value="'+tpnd+'" placeholder="'+tpn_th+'" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td class="barcode"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_itf" disabled required="" data-minlength="'+maxlength+'" maxlength="'+maxlength+'" data-required-error="Mandatory Field." data-minlength-error="Enter '+maxlength+' digits." id="os_barcode_'+i+'" onkeyup="dynamicSVGTemplates()" name="os_barcode_'+i+'" onkeypress="return isNumber(event)" value="'+barcode_td+'" placeholder="Enter ITF Barcode"><label class="barcode_error hidden" style="color:red;margin-left: -55px;position: absolute;">Enter Valid Barcode</label><div class="help-block with-errors"></div></div></td><td class="size"><div class="form-group has-feedback"> <input disabled type="text" maxlength="'+unit_len+'" class="form-control input-sm ce_ratio_cpc" required="" id="os_case_qty_'+i+'" name="os_case_qty_'+i+'" onkeypress="return isNumber(event)" value="'+unit_qty+'" placeholder="Enter '+qty+'" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>'+th_td+'<td> <div class="form-group has-feedback has-success"><input disabled onkeypress="return checkNumbersOnly(event)" class="form-control  input-sm tot_qty_table" value="'+value.count+'" required=""  placeholder="Count" maxlength="4" type="text"><div class="help-block with-errors"></div></div></td></tr>';
                        i++;
                    })
                     $('.edit-qty-table-div').html('<hr><h4 class="text-center">Order Quantity - '+TemplateName+'</h4><button disabled type="button" onclick=addRowOs(this,"'+TemplateName+'","'+data.order_details.zone+'") data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button><table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px;margin-top: 15px;"> <thead> <tr> <th>'+tpnd_tr+'</th><th>'+barcode_tr+'</th> <th>'+qty+'</th>'+th_tr+' <th>Count</th></tr></thead> <tbody style="font-size: 11px">'+tr+'</tbody></table>') 
                    
                    $('#edit_template_name').val(TemplateName)
                    $('#edit_zone').val(data.order_details.zone)
                    $('#edit_template_type').val(data.order_details.template_type)
                    $('#edit_id').val(data.order_details.id)
                    
                    $('.osorderdetails').text(" Order Details - "+TemplateName)
                    $('.osartwork').text(" Artwork - "+TemplateName)
                    
                    $('.osfooter').html('<button class="btn btn-danger pull-right remove input-sm" id="close" type="button">Close</button>')
                    
                    $('#create_tesco_order_status_form').validator().on('submit', function (e) {
                        if (e.isDefaultPrevented()) {

                        } else {
                            e.preventDefault();
                            setEditOrderStatus()
                        }
                    })
                    $('#create_tesco_order_status_form').validator('update'); 
                    
                    
                    $('.remove').click(function() {
                        $('.left-panel').removeClass('active');
                    })
                    
                      var mySVG1 = document.getElementById("svg_tesco_os");
                        var svgDoc1;
                        mySVG1.addEventListener("load", function () {
                            svgDoc1 = mySVG1.contentDocument;
                            dynamicSVGTemplatescard(svgDoc1,TemplateName,data.order_details.zone,data.order_details.template_type)
                        }, false);
                    
                }
                       
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#snackbar').css('background-color','#F44336');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
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

function setEditOrderStatus()
{
            
        var tpnd         = [];
        var barcode      = [];
        var size         = [];
        var count        = [];
        var single_size        = [];
        var barcode_checks=0;
    
        var po_number         =  $('#tesco_po_number').val();
        var uk_style          =  $('#tesco_uk_style').val();
        var ce_style          =  $('#tesco_ce_style').val();
        var season_code       =  $('#tesco_season_code').val();
        var description       =  $('#tesco_description').val();
        var eqos              =  $('#tesco_eqos').val();
        var manufacture_date  =  $('#tesco_manufacture_date').val();
        var csrf_token        =  $('meta[name="csrf-token"]').attr('content');
        var department        =  $("#tesco_department option:selected").val();
        var brand             =  $("#tesco_brand option:selected").val();
        var supplier          =  $("#tesco_supplier option:selected").val();
        var rfid              =  $("#tesco_rfid option:selected").val() === "yes"?1:0;
        var tag_at_source     =  $("#tesco_tag_at_source option:selected").val() === "yes"?1:0;
        var zone              =  $('#edit_zone').val()
        var type              =  $('#edit_template_type').val()
        var template_name     =  $('#edit_template_name').val()
        var edit_id           =  $('#edit_id').val()
        
        
        

        /** [ Create array of ce ratio orms tpn ] */
        $('.ce_ratio_orms_tpn').each(function(i, item)
        {
            tpnd[i]    = $(item).val();
            
        });
        tpnd   = tpnd.join(',');
        /** [ Create array of ce ratio itf barcode ] */
        $('.ce_ratio_itf').each(function(i, item)
        {
            if((template_name == "TCL1" && zone=="CE") ||(template_name == "TCL2" && zone=="CE") || (template_name == "TSL1" && zone=="CE") || (template_name == "THL1" && zone=="CE"))
            {
                var barcodecheck = checkjsbarcodeitf($(item).val());
                if(barcodecheck == "false")
                {

                    barcode_checks++;

                    $(this).parent().find('.barcode_error').removeClass('hidden')
                }
                else
                {
                    $(this).parent().find('.barcode_error').addClass('hidden')
                }
            }
            else if((template_name == "TCL4" && zone=="CE") || (template_name == "TSL3" && zone=="CE") || (template_name == "THL3" && zone=="CE"))
            {
                var barcodecheck = checkjsbarcodeean($(item).val());
                if(barcodecheck == "false")
                {

                    barcode_checks++;

                    $(this).parent().find('.barcode_error').removeClass('hidden')
                }
                else
                {
                    $(this).parent().find('.barcode_error').addClass('hidden')
                }
            }
            else if((template_name == "TCL4" && zone=="UK") || (template_name == "TSL3" && zone=="UK") || (template_name == "THL3" && zone=="UK"))
            {
                var barcodecheck = checkjsbarcodeean($(item).val());
                if(barcodecheck == "false")
                {

                    barcode_checks++;

                    $(this).parent().find('.barcode_error').removeClass('hidden')
                }
                else
                {
                    $(this).parent().find('.barcode_error').addClass('hidden')
                }
            }
            else if((template_name == "TCL1" && zone=="UK") ||(template_name == "TCL2" && zone=="UK") || (template_name == "TSL1" && zone=="UK") || (template_name == "THL1" && zone=="UK"))
            {
                var barcodecheck = checkjsbarcodeitf($(item).val());
                if(barcodecheck == "false")
                {

                    barcode_checks++;

                    $(this).parent().find('.barcode_error').removeClass('hidden')
                }
                else
                {
                    $(this).parent().find('.barcode_error').addClass('hidden')
                }
            }
            
            barcode[i]    = $(item).val();
        });
        barcode = barcode.join(',');
        /** [ Create array of ce ratio case per carton ] */
        $('.ce_ratio_cpc').each(function(i, item)
        {
            size[i]    = $(item).val();
            
        });
        size = size.join(',');
        /** [ Create array of ce ratio tcl1 count ] */
        $('.tot_qty_table').each(function(i, item)
        {
            count[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count = count.join(',');
    
    
        if((template_name == "TCL1") ||(template_name == "TCL2") || (template_name == "TSL1") || (template_name == "THL1"))
        {
            single_size="";
        }
        else if((template_name == "TCL4") || (template_name == "TSL3") || (template_name == "THL3"))
        {
            /** [ Create array of ce ratio tcl1 count ] */
            $('.ce_single_size').each(function(i, item)
            {
                single_size[i]    = $(item).val();

            });
            single_size = single_size.join(',');
        }
    if(barcode_checks == 0)
    {
        $.ajax({
             url: base_url + "/customer/tesco/edit-check-exist-po",
             type: 'post',
             data: { _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,zone:zone,edit_id:edit_id,template_name:template_name},
             success: function (data) {
                if(data == "true")
                {
                    $('#po_already_error').addClass('hidden')
                    $.ajax({
                         url: base_url + "/customer/tesco/set-tesco-order-status",
                         type: 'post',
                         data: { _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,barcode:barcode,size:size,count:count,edit_id:edit_id,template_name:template_name,single_size:single_size},
                         success: function (data) {
                             $('.left-panel').removeClass('active');  
                            // location.reload()
                         },
                         error: function (data) {
                            console.log(data);
                         },
                         complete: function () {

                         }
                     })

                    td_row.find('td:nth-child(6)').text($("#tesco_supplier option:selected").text());
                    td_row.find('td:nth-child(2)').text(po_number);
                    if(zone == "CE")
                    {
                        td_row.find('td:nth-child(1) a').text(ce_style);
                    }
                    else
                    {
                        td_row.find('td:nth-child(1) a').text(uk_style);
                    }

                }
                else
                {
                    $('#po_already_error').removeClass('hidden')
                }
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {

             }
        })
    }
     
    
}

function addRowOs(obj,TemplateName,zone)
{
    var maxlength =$('#os_barcode_1').attr('maxlength')
    var i = parseInt($(obj).attr('data-id'))+1;
    $(obj).attr('data-id',i);
    
    if((TemplateName == "TCL1" && zone=="CE") ||(TemplateName == "TCL2" && zone=="CE") || (TemplateName == "TSL1" && zone=="CE") || (TemplateName == "THL1" && zone=="CE"))
    {
        var qty="Cases per carton";
        var th_td="";
        var tpn_th="Enter ORMS TPND";
        var tpn_len="13";
        var unit_len="5";
    }
    else if((TemplateName == "TCL4" && zone=="CE") || (TemplateName == "TSL3" && zone=="CE") || (TemplateName == "THL3" && zone=="CE"))
    {
        var qty="Case Size";
        var th_td='<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_'+i+'" name="os_single_size_'+i+'"  value="" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
        var tpn_th="Enter ORMS TPND";
        var tpn_len="13";
        var unit_len="6";
    }
    else if((TemplateName == "TCL4" && zone=="UK") || (TemplateName == "TSL3" && zone=="UK") || (TemplateName == "THL3" && zone=="UK"))
    {
        var qty="Case Size";
        var th_td='<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" required="" id="os_single_size_'+i+'" name="os_single_size_'+i+'"  value="" placeholder="Enter Size" maxlength="9"><div class="help-block with-errors"></div></div></td>';
        var tpn_th="Enter TPND";
        var tpn_len="9";
        var unit_len="4";
    }
    else if((TemplateName == "TCL1" && zone=="UK") ||(TemplateName == "TCL2" && zone=="UK") || (TemplateName == "TSL1" && zone=="UK") || (TemplateName == "THL1" && zone=="UK"))
    {
        var qty="Units per carton";
        var th_td="";
        var tpn_th="Enter TPND";
        var tpn_len="9";
        var unit_len="4";
    }
    
    
    $('.edit-qty-table-div table tbody').append('<tr><td class="tpnd"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="os_tpnd_'+i+'" name="os_tpnd_'+i+'" maxlength="'+tpn_len+'" onkeypress="return isNumber(event)" value="" placeholder="'+tpn_th+'" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td class="barcode"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="'+maxlength+'" maxlength="'+maxlength+'" data-required-error="Mandatory Field." data-minlength-error="Enter '+maxlength+' digits." id="os_barcode_'+i+'" onkeyup="dynamicSVGTemplates()" name="os_barcode_'+i+'" onkeypress="return isNumber(event)" value="" placeholder="Enter ITF Barcode"><label class="barcode_error hidden" style="color:red;margin-left: -55px;position: absolute;">Enter Valid Barcode</label><div class="help-block with-errors"></div></div></td><td class="size"><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="os_case_qty_'+i+'" name="os_case_qty_'+i+'" maxlength="'+unit_len+'" onkeypress="return isNumber(event)" value="" placeholder="Enter '+qty+'" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>'+th_td+'<td> <div class="form-group has-feedback has-success"><input onkeypress="return checkNumbersOnly(event)" class="form-control  input-sm tot_qty_table" value="" required=""  placeholder="Count" maxlength="4" type="text"><div class="help-block with-errors"></div></div></td><td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRowOs(this)"></i></a></td></tr>');
    $('#create_tesco_order_status_form').validator('update'); 
}

function removeRowOs(obj)
{
     $(obj).parent().parent().parent().remove();
     $('#create_tesco_order_status_form').validator('update'); 
}

function dynamicSVGTemplates()
{
    $('.barcode_error').addClass('hidden')
    var template_name = $('#edit_template_name').val()
    var zone =$('#edit_zone').val()
    var template_type =$('#edit_template_type').val()
    var mySVG1 = document.getElementById("svg_tesco_os");
    var svgDoc1= mySVG1.contentDocument;
    dynamicSVGTemplatescard(svgDoc1,template_name,zone,template_type)

}

function dynamicSVGTemplatescard(svgDoc,template_name,zone,type)
{
    
         /** [ separate conditions for TSL1,TSL3 and other stickers ] */
        if(template_name === "TSL1" || template_name === "TSL3")
        {
           
           if(zone === "CE")
           {
              /**  [ if CE ratio template ] */
              if(type === "ratio")
              {
                 var tpnd = $('#os_tpnd_1').val();
                 var description  = $('#tesco_description').val();
                 var ce_ratio_itf  = $('#os_barcode_1').val();
                 
                 /** [ Dynamically change svg short description ] */
                 if(description != '')
                 {
                    svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                 }
                 else
                 {
                    svgDoc.getElementById("description").innerHTML = '';
                 }
                 /** [ Dynamically change svg CE ratio tpnd number ] */
                 if(tpnd != '')
                 {
                     svgDoc.getElementById("tpnd").innerHTML = tpnd;
                 }
                 else
                 {
                     svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                  
                  
                   /** [ Dynamically change svg barcode ] */
                  if(ce_ratio_itf.length === 14)
                  {
                    jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
                  }
                  else
                  {
                     svgDoc.getElementById("barcode").innerHTML = '';
                  }
              }
              else
              {
                 /**  [ if CE single template ] */
                 var orms_tpn = $('#os_tpnd_1').val();
                 var description  = $('#tesco_description').val();
                  
                 var ce_single_ean  = $('#os_barcode_1').val();
                 
                 /** [ Dynamically change svg CE single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                  
                 if(template_name === "TSL3")
                 {
                     
                      /** [ Dynamically change svg barcode ] */
                      if(ce_single_ean.length === 13)
                      {
                        jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
                      }
                      else
                      {
                         svgDoc.getElementById("barcode").innerHTML = '';
                      }
                 }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.log(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }
           }
           else
           {
              /**  [ if UK ratio template ] */
              if(type === "ratio")
              { 
                  var tpnd = $('#os_tpnd_1').val();
                  var description  = $('#tesco_description').val();
                  var uk_ratio_itf  = $('#os_barcode_1').val();
                  
                  /** [ Dynamically change svg UK ratio tpnd number ] */
                  if(tpnd != '')
                  {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                  }
                  else
                  {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                  }
                  /** [ Dynamically change svg short description ] */
                  if(description != '')
                  {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                  }
                  else
                  {
                      svgDoc.getElementById("description").innerHTML = ''; 
                  }
                  /** [ Dynamically change svg barcode ] */
                  if(uk_ratio_itf.length === 14)
                  {
                    jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
                  }
                  else
                  {
                     svgDoc.getElementById("barcode").innerHTML = '';
                  }
              }
              else
              {
                 /**  [ if UK single template ] */
                 var orms_tpn = $('#os_tpnd_1').val();
                 var description  = $('#tesco_description').val();
                 var uk_single_ean  = $('#os_barcode_1').val();
                 /** [ Dynamically change svg UK single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                 if(template_name === "TSL3")
                 {
                     
                      /** [ Dynamically change svg barcode ] */
                      if(uk_single_ean.length === 13)
                      {
                        jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
                      }
                      else
                      {
                         svgDoc.getElementById("barcode").innerHTML = '';
                      }
                 }
                 var size  = $('#uk_single_size_1').val();
                 /** if size is not empty, Get equivalent uk size for entered size (single templates)*/
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }  
           }
        }
        else 
        {
            var po_number        = $('#tesco_po_number').val();
            var uk_style         = $('#tesco_uk_style').val();
            var ce_style         = $('#tesco_ce_style').val();
            var tesco_eqos       = $('#tesco_eqos').val();
            var supplier         = $('#tesco_supplier option:selected').text();
            var department       = $('#tesco_department option:selected').text();
            var brand            = $('#tesco_brand option:selected').text();
            var rfid             = $('#tesco_rfid option:selected').text();
            var tag_at_source    = $('#tesco_tag_at_source option:selected').text();
            var season_code      = $('#tesco_season_code').val();
            var manufacture_date = $('#tesco_manufacture_date').val();
            
            
           if(po_number != '')
           {
              svgDoc.getElementById("po_number").innerHTML = po_number.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("po_number").innerHTML = '';
           }

           /** [ Dynamically change svg CE style reference number ] */
           if(ce_style != '')
           {
              svgDoc.getElementById("ce_style").innerHTML = ce_style.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("ce_style").innerHTML = '';
           }
           /** [ Dynamically change svg UK style reference number ] */
           if(uk_style != '')
           {
              svgDoc.getElementById("uk_style").innerHTML = uk_style.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("uk_style").innerHTML = '';
           }
           /** [ Dynamically change svg EQOS code ] */
           if(tesco_eqos != '')
           {
              svgDoc.getElementById("eqos").innerHTML = tesco_eqos.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("eqos").innerHTML = '';
           }
           /** [ Dynamically change  svg supplier name ] */
           if($('#tesco_supplier option:selected').val() != '')
           {
              svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace('&', '&amp;');
           }
           else
           {
              svgDoc.getElementById("supplier").innerHTML = '';
           }
           /** [ Dynamically change  svg department ] */
           if($('#tesco_department option:selected').val() != '')
           {
              
              svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace('&', '&amp;');
               
               var str =department.trim();
               var justify_len = str.length;
               if(justify_len < 17)
               {
                   svgDoc.getElementById("department").style.fontSize = "11px";
               }
               else
               {
                  svgDoc.getElementById("department").style.fontSize = "9px"; 
               }
           }  
           else
           { 
              svgDoc.getElementById("department").innerHTML = '';
           }
           /** [ Dynamically change svg brand ] */
           if($('#tesco_brand option:selected').val() != '')
           {
              svgDoc.getElementById("brand").innerHTML = brand.toUpperCase().replace('&', '&amp;');
               
               var str =brand.trim();
               var justify_len = str.length;
               if(justify_len < 15)
               {
                   svgDoc.getElementById("brand").style.fontSize = "11px";
               }
               else
               {
                  svgDoc.getElementById("brand").style.fontSize = "9px"; 
               }
           } 
           else
           {
              svgDoc.getElementById("brand").innerHTML = '';
           }
           /** [ Dynamically svg RFID ] */
           if($('#tesco_rfid option:selected').val() != '')
           {
              svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("rfid").innerHTML = ''; 
           } 
           /** [ Dynamically change svg tag at source ] */
           if($('#tesco_tag_at_source option:selected').val() != '')
           {
              svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("tag_at_source").innerHTML = '';
           }
           /** [ Dynamically change svg tag at source ] */
           if(season_code != '')
           {
              svgDoc.getElementById("season_code").innerHTML = season_code.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("season_code").innerHTML = '';
           }
           /** [ Dynamically change svg manufacture date ] */
           if(manufacture_date != '')
           {
              svgDoc.getElementById("manufactured_date").innerHTML = manufacture_date.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("manufactured_date").innerHTML = '';
           }
           if(zone === "CE")
           {
              /**  [ if CE ratio template ] */
              if(type === "ratio")
              {
                  var pieces_in_ratio = $('#os_case_qty_1').val();
                  var tpnd = $('#os_tpnd_1').val();
                  var ce_ratio_itf  = $('#os_barcode_1').val();
                  var description      = $('#tesco_description').val();
                  /** [ Dynamically change svg CE pieces in ratio ] */
                   if(pieces_in_ratio != '')
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                   }
                   else
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                   }
                   /** [ Dynamically change svg CE ratio tpnd number ] */
                   if(tpnd != '')
                   {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                   } 
                   else
                   {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                   }
                   if(template_name === "TCL1" || template_name === "THL1")
                   {
                     
                     /** [ Dynamically change svg barcode ] */
                       if(ce_ratio_itf !='' && ce_ratio_itf.length === 14)
                       {
                        
                        jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)

                       }
                       else
                       { 
                         svgDoc.getElementById("barcode").innerHTML = '';
                       }
                   }
                   /** [ Dynamically change svg short description ] */
                   if(description != '')
                   {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                   }
                   else
                   {
                      svgDoc.getElementById("description").innerHTML = '';
                   }
              }
              else
              {
                 /**  [ if CE single template ] */
                 var orms_tpn = $('#os_tpnd_1').val();
                 var case_size = $('#os_case_qty_1').val();
                 var ce_single_ean  = $('#os_barcode_1').val();
                 var description      = $('#tesco_description').val();
                 /** [ Dynamically change svg CE single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                 /** [ Dynamically change svg CE single case size ] */
                 if(case_size != '')
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                 }
                 else
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = '';
                 }
                 if(template_name === "THL3")
                 {
                       /** [ Dynamically change svg barcode ] */
                       if(ce_single_ean !='' && ce_single_ean.length === 13)
                       {

                          jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)

                       }
                       else
                       { 
                          svgDoc.getElementById("barcode").innerHTML = '';
                       }
                 }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
                 
              }
           }
           else
           {
               
              /**  [ if UK ratio template ] */
              if(type === "ratio")
              {
                   var pieces_in_ratio = $('#os_case_qty_1').val();
                   var tpnd = $('#os_tpnd_1').val();
                   var uk_ratio_itf  = $('#os_barcode_1').val();
                   var description      = $('#tesco_description').val();

                   /** [ Dynamically change svg UK pieces in ratio ] */
                   if(pieces_in_ratio != '')
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                   }
                   else
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                   }
                   /** [ Dynamically change svg UK ratio tpnd number ] */
                   if(tpnd != '')
                   {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                   }
                   else
                   {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                   }
                   if(template_name === "TCL1" || template_name === "THL1")
                   {
                     /** [ Dynamically change svg barcode ] */
                       if(uk_ratio_itf !='' && uk_ratio_itf.length === 14)
                       {
                        
                           jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)

                       }
                       else
                       { 
                           svgDoc.getElementById("barcode").innerHTML = '';
                       }
                   }
                   /** [ Dynamically change svg short description ] */
                   if(description != '')
                   {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                   }
                   else
                   {
                      svgDoc.getElementById("description").innerHTML = '';
                   }
              }
              else
              {
                 /**  [ if UK single template ] */
                 var orms_tpn = $('#os_tpnd_1').val();
                 var case_size = $('#os_case_qty_1').val();
                 var uk_single_ean  = $('#os_barcode_1').val();
                 var description      = $('#tesco_description').val();
                 /** [ Dynamically change svg Uk single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                 /** [ Dynamically change svg Uk single case size ] */
                 if(case_size != '')
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                 }
                 else
                 { 
                      svgDoc.getElementById("unit_per_case").innerHTML = '';
                 }
                  
                 if(template_name === "THL3")
                 {
                       /** [ Dynamically change svg barcode ] */
                       if(uk_single_ean !='' && uk_single_ean.length === 13)
                       {

                           jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)

                       }
                       else
                       { 
                           svgDoc.getElementById("barcode").innerHTML = '';
                       }
                 }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }  
           }
        } 
}


/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetesco(code, id, svgDoc) {
    
    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 2.50,
        height: 100,
        fontSize: 30,
        flat:true,
        valid:function(valid){
            if(valid == false)
            {
                
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
    console.log(img.src);

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
        width: 3.45,
        height: 125,
        fontSize: 35,
        flat:true
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
    console.log(img.src);

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
    var return_value="";
    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 2.50,
        height: 100,
        fontSize: 30,
        flat:true,
        valid:function(valid){
            if(valid == false)
            {
                return_value="false";
            }
            else
            {
                return_value="true";
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
    var return_value="";
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 3.45,
        height: 125,
        fontSize: 35,
        flat:true,
        valid:function(valid){
            if(valid == false)
            {
                return_value="false";
            }
            else
            {
                return_value="true";
            }
        }
    });
    return return_value;
}