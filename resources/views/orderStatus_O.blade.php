@extends('layouts.Erp.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
@section('navbar')
@endsection

<div class="container-fluid" style="margin-top: 5%;">
  <table id="users" class="table table-hover table-striped table-bordered dataTable no-footer" style="width:100%">
    <thead>
        <tr>
            <th class="select-filter">UVC</th>
            <th>Season</th>
            <th>BuyerPO</th>
            <th>Artwork Type</th>
            <th class="select-filter">Template Name</th>
            <th>ReferenceNo</th>
            <th class="select-filter">Supplier Name</th>
            <th>OrderDate</th>
            <th>DeliverDate</th>
            <th>Artwork Status</th>
            <th>PI Status</th>
            <th>PO Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
            <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            </tr>
        </tfoot>
  </table>
</div>

@section('jqueryIncludes')
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {


    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth());
    $('#datePicker1').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: 'MM,yyyy'
    })



    oTable = $('#users').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('getOrderStatusesOrch') }}",
        "columns": [
            {data: 'UVC', name: 'UVC'},
            {data: 'Season', name: 'Season'},
            {data: 'BuyerPONo', name: 'BuyerPONo'},
            {data: 'ArtworkType', name: 'ArtworkType'},
            {data: 'TemplateName', name: 'TemplateName'},
            {data: 'Reference No', name: 'Reference No'},
            {data: 'SupplierName', name: 'SupplierName'},
            {data: 'OrderDate', name: 'OrderDate'},
            {data: 'deliveryDate', name: 'deliveryDate'},
            {data: 'artworkStatus', name: 'artworkStatus'},
            {data: 'pIstatus', name: 'pIstatus'},
            {data: 'inputPo', name: 'inputPo'},
            {data: 'actions', name: 'actions'},
            
   
        ],
         initComplete: function () {
            this.api().columns('.select-filter').every( function () {
                var column = this;
                var select = $('<select><option value="">Select</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    });
});
</script>
@endsection 
@endsection 
