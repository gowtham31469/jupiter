@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')
<style>
    .navbar-nav > li > a {
    line-height: 12px !important;
    }
</style>
<ul class="nav navbar-nav dynamic-menu">
<li><a href="{{ url('/erp/invoice') }}" class="active">Invoice List</a></li>
    <li><a href="{{ url('/erp/create-invoice') }}" class="">Create Invoice</a></li>
</ul>

@endsection
@section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="company-nav-div hidden">
        <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
    </div>
    <div class="main-content">


        <div class="loader">
            <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
        </div>
        <div class="col" id="customer-table">
            <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
            <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
            <table id="invoice-list-table" class="table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Sl. No</th>
                        <th>Invoice ID</th>
                        <th>ERP ID</th>
                        <th>Customer name</th>
                        <th>PO Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                {{$i=0}}
                @foreach($invoices as $vals)
                    <tr id="{{$vals->id}}">
                        <td>{{++$i}}</td>
                        <td>{{$vals->invoice_id}}</td>
                        <td>{{$vals->erp_id}}</td>
                        <td>{{$vals->company_name}}</td>
                        <td>{{$vals->po_no}}</td>
                        <td align="center"><a class="pi_generation" href="javascript:void(0)" ><i class="fa fa-eye action-button-view" data-placement="top" title="" data-original-title="view Details"></i></a></td>
                    </tr>
                @endforeach    
                </tbody>
            </table>
        </div>

    </div>
</div>
{{-- Show snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/erp') }}";
</script>
@endif 
@section('jqueryIncludes')
<script src="{{ asset('js/Erp/invoice.js') }}"></script>
<script>       
var table = $('#invoice-list-table').DataTable();
</script>
 @endsection