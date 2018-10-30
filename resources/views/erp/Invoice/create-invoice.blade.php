@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('content')
<style>
    .order-footer{
       position: fixed;
       right: 16px;
       bottom: 10px;
       width: 100%;
       /*background-color: #fff;*/
       color: white;
       text-align: right;
    }
</style>
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
                    <input type="text" class="form-control" name="invoice-no" id="invoice-no" style="margin-left: -145px;width: 91px;">
                </div>
                <div class="row">
                    <div class="col-xs-2">
                    <label for="invoice-date" style="padding-top: 8px;margin-left: -240px;">Date</label>
                   
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" name="" style="margin-left: -412px;width: 138px;" name="invoice-date" id="invoice-date" value ="{{ now('Asia/Kolkata')->toDateString() }}" readonly="">
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
                        <u><strong>ORDER DETAILS :</strong></u>
                    </div>                     
                    
                    <div class="table-responsive order-details hidden">
                    
                    </div> 
                    <div class="col-md-4 remarks hidden" style="margin-left: -16px;">
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
                    <!-- <div class="w3-card order-footer">
                         <button type="button" class="btn btn-primary create-invoice hidden">Create Invoice</button>
                    </div -->
                </div>                    
            </form>
        </div>
        
    </div>
</div>
<div class="modal fade" id="erp-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="margin-left: 5px" class="pi_actions pull-right">

                    </div>
                    <h5><strong>Invoice for <span id="erp_id"></span></strong></h5>

                </div>
                <div class="modal-body" id="erp-invoice">
                    <div class="col-md-12 text-center">
                        <h4><strong><u>Invoice</u></strong></h4>
                        <br/>
                        <br/>
                    </div>
                    <div class="row color-invoice">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="right" style="float: right">
                                        <img id="company-logo" style="height: 45px;" src="" class="img-responsive">
                                        <strong>Date</strong>
                                        <span style="padding-left: 14px;"> : <span style="padding-left: 6px;" class="pi-date"></span>
                                        </span></br>
                                        <strong>PI </strong>
                                            <span style="padding-left: 31px;"> : </span>
                                            <span style="padding-left: 8px;" class="pi_number"></span>
                                        <p class="rev hidden"><strong>Rev no</strong><span style="padding-left: 3px;"> : </span>
                                        <span style="padding-left: 4px;" class="rev_number"> </span></p>
                                    </div>
                                    <div id="company_details">

                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <strong style="margin-left: 15px; font-size:11px;">Customer Details : </strong>
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
                            <strong>ORDER DETAILS :</strong>
                            <div class="row" id="item_details">

                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <p class="text-left"><strong>Payment Terms : </strong><span class="advnc"></span></p><p class="text-left"><strong>Freight Type  : <span class="bond"></span></strong><span class="freight"> </span></p>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12" id="bank_details">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div style="float:right" class="pi_actions">
                    </div>
                </div>
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

</script>
 @endsection