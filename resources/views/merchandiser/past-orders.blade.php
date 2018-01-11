@extends('layouts.merchandiser.app') @section('content')
<style>
.left-panel.active {
    background: rgba(0, 0, 0, 0.56);
    margin-right: 0% !important;
    transition: 0.5s ease all;
}
</style>
<div class="col-sm-12 col-md-12 main">
    <div class="main-content">
        <table class="table table-striped table-hover table-bordered" id="past-orders-table">
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
                @foreach($orders as $order)
                <tr id="{{$order->id}}">
                    <td>{{ $order->UVC }}</td>
                    <td>{{ $order->Artwork }}</td>
                    <td>{{ $order->SupplierName }}</td>
                    <td>{{ $order->merchandiser_name }}</td>
                    <td>{{ $order->Season }}</td>
                    <td>{{ $order->OrderDate }}</td>
                    @if($order->ApprovedDate != "")
                        <td>{{ $order->ApprovedDate }}</td>
                    @else
                        <td>N/A</td>
                    @endif
                    @if($order->RejectedDate)
                        <td>{{ $order->RejectedDate }}</td>
                    @else
                         <td>N/A</td>
                    @endif
                    @if($order->Status == "Rejected")
                    <td><span class="label label-warning">{{  $order->Status }}</span></td>
                    @else
                    <td><span class="label label-Approved">{{  $order->Status }}</span></td>
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @section('jqueryIncludes')
    <script src="{{ asset('js/Merchandiser/merchandiser.js') }}"></script>
    <script type="text/javascript">
        /** Filter funtionality */
      var $tableSel = $('#past-orders-table');
        $tableSel.DataTable({
            "pageLength": 25,
            initComplete: function() {
                $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
                $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
                $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-2"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-2"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-2"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-2"><select class="selectpicker" id="selectApproveDate"> <option disabled selected>Select Approve Date</option> </select></div><div class="col-md-2"><select class="selectpicker" id="selectRejectDate"> <option disabled selected>Select Reject Date</option> </select></div><div class="col-md-2"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

                //For Custom Filters
                this.api().columns().every(function(i) {
                    var column = this;
                    if (i === 0 || i === 2 || i === 6 || i === 7 || i === 8) {
                        var select;
                        switch (i) {
                            case 0:
                                select = $('#uvcNumber')
                                break;
                            case 2:
                                select = $('#supplierName')
                                break;
                            case 6:
                                select = $('#selectApproveDate')
                                break;
                            case 7:
                                select = $('#selectRejectDate')
                                break;
                            case 8:
                                select = $('#selectStatus')
                                break;
                        }
                        select.on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                        select.append('<option value=""> </option>');
                        column.data().unique().sort().each(function(d, j) {
                            cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
                            select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
                        });
                    }

                });


            }
        });


        $("#reset").on("click", function() {
            $('#uvcNumber').val('').change();
            $('#supplierName').val('').change();
            $('#selectApproveDate').val('').change();
            $('#selectRejectDate').val('').change();
            $('#selectStatus').val('').change();
            $.fn.dataTableExt.afnFiltering.length = 0;
            $tableSel.dataTable().fnDraw();
            $('input[name="daterange"]').val('Filter by Order Date');
            $('input[name="daterange"]').datepicker("refresh");
            $('.selectpicker').selectpicker('val', 1);
            $('.selectpicker').selectpicker('refresh');

        });
        $("#past-orders-table_filter input").addClass('form-control input-sm');
        $("#past-orders-table_filter input").attr('placeholder', 'Data Search');
        $('#past-orders-table_length select').addClass('form-control input-sm');
        $('.selectpicker').selectpicker();


        $('input[name="daterange"]').daterangepicker({

            },
            function(start, end, label) {
                // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                filterByDate(4, start, end); // We call our filter function
                $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
            });

        $('input[name="daterange"]').val('Filter by Order Date');


        // Clear the filter. Unlike normal filters in Datatables,
        // custom filters need to be removed from the afnFiltering array.
        $('.cancelBtn').on('click', function(e) {
            e.preventDefault();
            $.fn.dataTableExt.afnFiltering.length = 0;
            $tableSel.dataTable().fnDraw();
        });
        function showFilter() {
        $('.filter-window').toggle();
        }

        /* Our main filter function
        * We pass the column location, the start date, and the end date
        */
        var filterByDate = function(column, startDate, endDate) {
        // Custom filter syntax requires pushing the new filter to the global filter array
        $.fn.dataTableExt.afnFiltering.push(
            function(oSettings, aData, iDataIndex) {
                var rowDate = normalizeDate(aData[column]),
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
        var normalizeDate = function(dateString) {
        var date = new Date(dateString);
        var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
        return normalized;
        }
        /** filter functionality ends */
    </script>
    @endsection