@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('content')
<div class="col-sm-12 col-md-12 main">
	 <div class="main-content">
        <div class="select-buyer"></div>
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="col-md-12">
            <!-- <div class="card"> -->
               <h4 class="text-center">Invoice Details</h4>
                <div class="col-xs-2">
                    <label for="tesco_supplier" style="padding-top: 12px;margin-left: -12px;">No</label>
                </div>
                <div class="col-xs-2">
                    <input type="text" class="form-control" name="" style="margin-left: -145px;width: 91px;">
                </div>
                <div class="row">
                    <div class="col-xs-2">
                    <label for="tesco_supplier" style="padding-top: 8px;margin-left: -240px;">Date</label>
                   
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" name="" style="margin-left: -412px;width: 138px;" value ="{{ now('Asia/Kolkata')->toDateString() }}" readonly="">
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-2">
                    <label for="tesco_supplier" style="padding-top: 12px;">Customer</label>     
                </div>
                <div class="col-xs-3" style="margin-left:-135px;padding-top: 5px;">
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicTable()">
                        <option value="">Select Supplier</option> 
                        @foreach($tesco_suppliers as $suppliers)
                            <option title="{{$suppliers->supplier_name}}" value="{{$suppliers->id}}">{{$suppliers->supplier_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="row po-list hidden">
                <div class="col-xs-2">
                    <label for="tesco_supplier" style="padding-top: 12px;">Orders</label>
                    
                </div>
                <!-- <div class="panel"> -->
                <div class="table-bordered table-responsive dynamic-table pull-left" style="margin-left:-120px; width: 350px;margin-top: 6px;border-radius: 5px;">
                

                </div>
            </div>
                <br>
                <br>
                <div class="row">
                    <div class="table-responsive order-details hidden">
                    
                    </div>  
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