@extends('layouts.Erp.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
@section('navbar')
@endsection

<div class="container-fluid" style="margin-top: 5%;">
  <table id="users" class="table table-hover table-striped table-bordered dataTable no-footer" style="width:100%">
    <thead>
        <tr>
            <th class="select-filter">Style No</th>
            <th>BuyerPO</th>
            <th>Zone</th>
            <th class="select-filter">ReferenceNo</th>
            <th>TemplateType</th>
            <th class="select-filter">Company</th>
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
        "ajax": "{{ route('getOrderStatusesTesco') }}",
        "columns": [
            {data: 'StyleNo', name: 'StyleNo'},
            {data: 'BuyerPO', name: 'BuyerPO'},
            {data: 'Zone', name: 'Zone'},
            {data: 'Zone', name: 'TemplateName'},
            {data: 'Template Type', name: 'Template Type'},
            {data: 'Company', name: 'Company'},
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
