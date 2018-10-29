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
                    </div -->>
                </div>
            <!-- </div> -->
        </div>
        
    </div>
</div>
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