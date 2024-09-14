@extends('layouts.merchandiser.app') @section('content')
<style>
.left-panel.active {
    background: rgba(0, 0, 0, 0.56);
    margin-right: 0% !important;
    transition: 0.5s ease all;
}

.filter-window-jobcard {
    display: block;
    z-index: 10;
    background-color: #f9f9f9;
    width: 100%;
    padding: 15px 15px 60px 15px;
    margin: 0;
    border: 1px solid #ccc;
    border-radius: 3px;
}

</style>
<div class="col-sm-12 col-md-12 main">
    <div class="main-content">

    <div class="filter-window-jobcard" style="display: block;">
        <h5><strong>Past Orders Filter</strong></h5>
        <div class="row">
        <div class="col-md-3"><input type="text" placeholder="Start Date - End Date" class="form-control startdateos" autocomplete="off" multiple style="width:100%" name="startdateos" /></div>

        <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="ukstylenoos" title="Select Style Number" multiple data-selected-text-format="count" data-done-button="true" data-actions-box="true" data-live-search="true" data-size="10">
            @foreach($owcl_style as $style)
            <option value="{{ $style->uvc }}">{{ $style->uvc }}</option>
            @endforeach
        </select></div></div>

        <div class="col-md-3"><div class="input-group"><select class="selectpicker" id="supplieros" title="Select Supplier" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10">
            @foreach($owcl_supplier as $supplier)
            <option value="{{ $supplier->customer_id }}">{{ $supplier->company_name }}</option>
            @endforeach
        </select></div></div>

        <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="seasonos" title="Select Season" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10">
            @foreach($owcl_season as $season)
            <option value="{{ $season->season_code }}">{{ $season->season_code }}</option>
            @endforeach
        </select></div></div>

        <div class="col-md-2"><div class="input-group"><select class="selectpicker" id="statusos" title="Select Status" data-done-button="true" data-actions-box="true" multiple data-selected-text-format="count" data-live-search="true" data-size="10">
            <option value="Order Approved">Order Approved</option>
            <option value="Order Rejected">Order Rejected</option>
        </select></div></div>

        <input type="hidden" id="start_date">
        <input type="hidden" id="end_date">
        
        </div>

        <button class="btn btn-primary input-sm pull-right" style="margin-top: 15px;margin-bottom: 30px;" onclick="submitmos(this)" id="submitos">Submit</button>

        </div>
        <div style="padding-top:15px;" id="merchandiserOrderTable"></div>
    </div>
    @section('jqueryIncludes')
    <script src="{{ asset('js/Merchandiser/merchandiser.js') }}"></script>
    <script type="text/javascript">


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
                url: base_url + "/merchandiser/orchestra/orders-submit-filter",
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
                        $("#supplieros").html("");

                        $(data.owcl_season).each(function(index,value){
                            if(value.season_code != null)
                            {
                                $("#seasonos").append("<option value='"+ value.season_code + "'>" + value.season_code + "</option>");
                            }
                        })

                        $(data.owcl_style).each(function(index,value){
                            if(value.uvc != null)
                            {
                                $("#ukstylenoos").append("<option value='"+ value.uvc + "'>" + value.uvc + "</option>");
                            }
                        })

                        $(data.owcl_supplier).each(function(index,value){
                            if(value.company_name != null)
                            {
                                $("#supplieros").append("<option value='"+ value.customer_id + "'>" + value.company_name + "</option>");
                            }
                        })

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

function submitmos(obj)
{
    var seasonos = $('#seasonos').val();
    var ukstylenoos = $('#ukstylenoos').val();
    var supplieros = $('#supplieros').val();
    var statusos = $('#statusos').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    $.ajax({
        url: base_url + "/merchandiser/orchestra/orchestra-submit-os",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            seasonos:seasonos,
            ukstylenoos:ukstylenoos,
            supplieros:supplieros,
            start_date:start_date,
            end_date:end_date,
            statusos:statusos
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {

                var tbody = ""
                $(data.orders).each(function(index,value){

                    var orderDate = moment(value.OrderDate).format('DD-MM-YYYY');
                    var approveDate = value.ApprovedDate != null ? moment(value.ApprovedDate).format('DD-MM-YYYY') : "-";
                    var rejectDate = value.RejectedDate != null ? moment(value.RejectedDate).format('DD-MM-YYYY') : "-";
                    var status_label = value.Status == "Order Approved" ? '<span class="label label-Approved">'+value.Status+'</span>' : '<span class="label label-Rejected">'+value.Status+'</span>';

                    tbody += `<tr id="`+value.id+`"><td>`+value.UVC+`</td><td>`+value.Artwork+`</td><td>`+value.SupplierName+`</td><td>`+value.merchandiser_name+`</td><td>`+value.Season+`</td><td>`+orderDate+`</td><td>`+approveDate+`</td><td>`+rejectDate+`</td><td>`+status_label+`</td></tr>`
                });

                $('#merchandiserOrderTable').html(`
                    <table style="font-size:12px" class="table table-striped table-hover table-bordered" id="past-orders-table">
                        <thead>
                            <tr>
                                <th>UVC</th>
                                <th>Artwork</th>
                                <th>Supplier Name</th>
                                <th>Merchandiser Name</th>
                                <th>Season</th>
                                <th>Order Date</th>
                                <th>Approved Date</th>
                                <th>Rejected Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        `+tbody+`
                        </tbody>
                    </table>
                `)

                $('#past-orders-table').DataTable();
                $("#past-orders-table_filter input").addClass('form-control input-sm');
                $("#past-orders-table_filter input").attr('placeholder', 'Data Search');
                $('#past-orders-table_length select').addClass('form-control input-sm');

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

    //    $( document ).ready(function() {
    //         $("#past-orders-table_filter input").addClass('form-control input-sm');
    //         $("#past-orders-table_filter input").attr('placeholder', 'Data Search');
    //         $('#past-orders-table_length select').addClass('form-control input-sm');
    //         $('.selectpicker').selectpicker();
    //     });

    //   /** Filter funtionality */
    //   var $tableSel = $('#past-orders-table');
    //     $tableSel.DataTable({
    //         "pageLength": 25,
    //         initComplete: function() {
    //             $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
    //             $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
    //             $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

    //             /*For Custom Filters*/
    //             this.api().columns().every(function(i) {
    //                 var column = this;
    //                 if (i === 0 || i === 2 || i === 8) {
    //                     var select;
    //                     switch (i) {
    //                         case 0:
    //                             select = $('#uvcNumber')
    //                             break;
    //                         case 2:
    //                             select = $('#supplierName')
    //                             break;
    //                         case 8:
    //                             select = $('#selectStatus')
    //                             break;
    //                     }
    //                     select.on('change', function() {
    //                         var val = $.fn.dataTable.util.escapeRegex(
    //                             $(this).val()
    //                         );

    //                         column
    //                             .search(val ? '^' + val + '$' : '', true, false)
    //                             .draw();
    //                     });
    //                     select.append('<option value=""> </option>');
    //                     column.data().unique().sort().each(function(d, j) {
    //                         cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
    //                         select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
    //                     });
    //                 }
    //             });
    //         }
    //     });

    //     /** Reset filter */
    //     $("#reset").on("click", function() {
    //         $('#uvcNumber').val('').change();
    //         $('#supplierName').val('').change();
    //         $('#selectStatus').val('').change();
    //         $.fn.dataTableExt.afnFiltering.length = 0;
    //         $tableSel.dataTable().fnDraw();
    //         $('input[name="daterange"]').val('Filter by Order Date');
    //         $('input[name="daterange"]').datepicker("refresh");
    //         $('.selectpicker').selectpicker('val', 1);
    //         $('.selectpicker').selectpicker('refresh');

    //     });

    //     $('input[name="daterange"]').daterangepicker({

    //         },
    //         function(start, end, label) {
    //             $.fn.dataTableExt.afnFiltering.length = 0;
    //             $tableSel.dataTable().fnDraw();
    //             filterByDate(5, start, end); // We call our filter function
    //             $tableSel.dataTable().fnDraw();
    //         });

    //     $('input[name="daterange"]').val('Filter by Order Date');

    //     /*Clear the filter. Unlike normal filters in Datatables,
    //     custom filters need to be removed from the afnFiltering array.*/
    //     $('.cancelBtn').on('click', function(e) {
    //         e.preventDefault();
    //         $.fn.dataTableExt.afnFiltering.length = 0;
    //         $tableSel.dataTable().fnDraw();
    //     });

    //     // Toggle filter menu functionality
    //     function showFilter() {
    //     $('.filter-window').toggle();
    //     }

    //     /* Our main filter function
    //     * We pass the column location, the start date, and the end date
    //     */
    //     var filterByDate = function(column, startDate, endDate) {
    //     /*Custom filter syntax requires pushing the new filter to the global filter array*/
    //     $.fn.dataTableExt.afnFiltering.push(
    //         function(oSettings, aData, iDataIndex) {
    //             var columnDate = aData[column].toString().split("-").reverse().join("-");
	// 		    var rowDate = normalizeDate(columnDate),
    //                 start = normalizeDate(startDate),
    //                 end = normalizeDate(endDate);

    //              /*If our date from the row is between the start and end*/
    //             if (start <= rowDate && rowDate <= end) {
    //                 return true;
    //             } else if (rowDate >= start && end === '' && start !== '') {
    //                 return true;
    //             } else if (rowDate <= end && start === '' && end !== '') {
    //                 return true;
    //             } else {
    //                 return false;
    //             }
    //         }
    //     );
    //     };
        
    //     /*converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220*/
    //     var normalizeDate = function(dateString) {
    //         var date = new Date(dateString);
    //         var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
    //         return normalized;
    //     }
    //     /** filter functionality ends */
    </script>
    @endsection