@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')
<style>
    .navbar-nav > li > a {
    line-height: 12px !important;
    }
  /*  .invoice-modal{
        width: 1100px;
    }*/
    .nopadding {
       padding: 0 !important;
       margin: 0 !important;
    }
</style>
<ul class="nav navbar-nav dynamic-menu">
<li><a href="{{ url('/erp/invoice') }}" class="">Invoice List</a></li>
    <li><a href="{{ url('/erp/create-invoice') }}" class="active">Create Invoice</a></li>
</ul>
@endsection
@section('content')
<div class="col-sm-12 col-md-12 main">
     <div class="main-content">
        <div class="select-buyer"></div>
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="col-md-12">
            <!-- <div class="card"> -->
               <form id="invoice_form">
               <h4 class="text-center">Invoice Details</h4>
                <div class="col-xs-2">
                    <label for="invoice-no" style="padding-top: 12px;margin-left: -12px;">No</label>
                </div>
                <div class="col-xs-2">
                    <input type="text" class="form-control" name="invoice-no" id="invoice-no" style="margin-left: -145px;width: 91px;" value="{{$invID}}">
                </div>
                <div class="row">
                    <div class="col-xs-2">
                    <label for="invoice-date" style="padding-top: 8px;margin-left: -240px;">Date</label>
                   
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control invoice-date" id="invoice-date" name="invoice-date" style="margin-left: -412px;width: 136px;;" name="invoice-date" id="invoice-date" value ="{{ now('Asia/Kolkata')->format('d-m-Y') }}" readonly="">
                        <!-- <input class="form-control input-sm invoice-date" id="invoice-date" name="invoice-date" style="margin-left: -412px;width: 138px;" placeholder="Select Date" readonly="" value ="{{ now('Asia/Kolkata')->format('d-m-Y') }}"> -->
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-2">
                    <label for="customer" style="padding-top: 12px;">Customer</label>     
                </div>
                <div class="col-xs-3" style="margin-left:-135px;padding-top: 5px;">
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="customer" name="customer" required="" onchange="dynamicTable()">
                        <option value="">Select Customers</option> 
                        @foreach($customers as $vals)
                            <option title="{{$vals->company_name}}" value="{{$vals->id}}">{{$vals->company_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="row po-list hidden">
                <div class="col-xs-2">
                    <label for="erp-orders" style="padding-top: 12px;">Orders</label>
                    
                </div>
                <!-- <div class="panel"> -->
                <div class="table-bordered table-responsive dynamic-table pull-left" style="margin-left:-120px; width: 350px;margin-top: 6px;border-radius: 5px;">
                </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="order-heading hidden"> 
                        <u><strong style="margin-left: 16px;">ORDER DETAILS :</strong></u>
                    </div>                     
                    
                    <div class="table-responsive order-details hidden">
                    
                    </div> 
                    <div class="col-md-4 remarks hidden">
                        <label>Remarks</label>
                        <div class='form-group'>
                            <textarea class='form-control input-sm' name='remarks' style='resize: vertical;min-height:10vh;'></textarea>
                        </div>

                    </div>
                    <div class="col-md-3">
                        

                    </div>
                    <div class="col-md-5">
                        <div class="orders-tax">
                            
                        </div>
                        <div class="extra-charges">
                            
                        </div>
                    </div>
                </div>                    
            </form>
        </div>
        
    </div>
</div>
<div class="modal fade" id="erp-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg invoice-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="margin-left: 5px" class="invoice_actions pull-right">

                    </div>
                    <h5><strong>Invoice for <span id="erp_id"></span></strong></h5>

                </div>
                <div class="modal-body" id="invoice">
                  <div class="container-fluid ">
                  <div class="row">
                            <div class="col-md-12">
                               <div class="row text-center color-invoice">
                                    <h4><strong><u>Invoice</u></strong></h4>
                                </div> 
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="right" style="float: right;margin-right: 16px;">
                                <img id="company-logo" style="height: 45px;" src="" class="img-responsive">
                                <strong>Date</strong>
                                <span style="padding-left: 37px;"> : <span style="padding-left: 6px;" class="invoice-date"></span>
                                </span><br>
                                <strong>Invoice No </strong>
                                    <span style="padding-left: 3px;"> : </span>
                                    <span style="padding-left: 4px;" class="invoice_number"></span>
                                  
                            </div>
                        </div> -->
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" style="padding-left: 50px;">
                                    <img id="company-logo" style="height: 45px;" src="" class="img-responsive">
                                    <strong>Date</strong>
                                    <span style="padding-left: 37px;"> : <span style="padding-left: 6px;" class="invoice-date"></span>
                                    </span><br>
                                    <strong>Invoice No </strong>
                                    <span style="padding-left: 3px;"> : </span>
                                    <span style="padding-left: 4px;" class="invoice_number"></span>

                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">

                                </div>
                            </div>
                            <div class="row">
                                <div id="company_details">

                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <strong style="font-size:11px;margin-left: 14px;">Customer Details : </strong>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4" id="bill_to">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 50px;">

                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">

                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <strong style="margin-left: 14px;">ORDER DETAILS :</strong>
                                <div class="col-md-12" id="item_details">
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                <div class="col-md-4 nopadding remarks hidden">
                                <label>Remarks</label>
                                <div class="form-group">
                                    <span>Datataatata</span>
                                </div>

                            </div>
                            <div class="col-md-3 nopadding">
                                

                            </div>
                            <div class="col-md-5 nopadding">
                                <div class="preview-orders-tax"></div>
                                <div class="preview-extra-charges"></div>
                                
                            </div>
                            </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer"></div>
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
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('js/Erp/invoice.js') }}"></script>

<script>       
$('body').on('focus',".invoice-date", function(){
    
    $(this).datepicker({
        autoclose: true,
        format:'dd-mm-yyyy'
    });
});
</script>
 @endsection