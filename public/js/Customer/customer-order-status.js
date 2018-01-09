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
            } else {

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