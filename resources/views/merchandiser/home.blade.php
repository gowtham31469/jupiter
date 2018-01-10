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
        <table class="table table-striped table-hover table-bordered" id="datatable-example-2">
            <thead>
                <tr>
                    <th>UVC</th>
                    <th>Artwork</th>
                    <th>Supplier Name</th>
                    <th>Merchandiser Name</th>
                    <th>Season</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->UVC }}</td>
                    <td>{{ $order->Artwork }}</td>
                    <td>{{ $order->SupplierName }}</td>
                    <td>{{ $order->merchandiser_name }}</td>
                    <td>{{ $order->Season }}</td>
                    <td>{{ $order->OrderDate }}</td>
                    <td>{{ $order->DeliveryDate }}</td>
                    @if($order->Status == "Pending")
                    <td><span class="label label-Pending">{{  $order->Status }}</span></td>
                    @else
                    <td><span class="label label-Approved">Approved</span></td>
                    @endif
                    <td><a href="#" onclick="viewWcl()"><span><i class="fa fa-eye fa-button" title="view"></i> View</span></a></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <div class="left-panel col-md-12">
        <div class="div-max">
            <div class="col-md-7">
                <h4>Washcare Label</h4>
                <hr>
                 <div class="section-1 sections">
                    <div class="sample-artwork" style="text-align:center"><img style="height: calc(100vh - 150px); box-sizing: border-box;" src="../../images/wcl_default.png"></div>
                </div>
            </div>
            <div class="col-md-5">
                <h4>Quantity Information</h4>
                <hr>
               <table class="table table-bordered "><thead><tr><th>UVC TCO</th><th>Size ANS</th><th>Barcode</th><th>Qty</th></tr></thead><tbody><tr><td>0F008S</td><td>2</td><td>3534444391471</td><td>2</td></tr><tr><td>0F008S</td><td>2</td><td>3534444391471</td><td>2</td></tr><tr><td>0F008S</td><td>2</td><td>3534444391471</td><td>2</td></tr><tr><td>0F008S</td><td>2</td><td>3534444391471</td><td>2</td></tr><tr><td>0F008S</td><td>2</td><td>3534444391471</td><td>2</td></tr></tbody></table>
                <div class="merchandiser-area">
                <textarea class="form-control" placeholder="Enter your comments here" rows="10" cols="50"></textarea>
                </div>
                <div class="buttons pull-right" style="margin-top:10px;">
                <button class="btn btn-primary" >Approve</button>
                <button class="btn btn-warning" >Reject</button>
                <button class="btn btn-danger remove" >Close</button>
                    </div>
            </div>
       
        </div>
    </div>
    @section('jqueryIncludes')
    <script>
        function viewWcl(){
         $('.left-panel').addClass('active');
        }
        
        $('.remove').click(function(){
             $('.left-panel').removeClass('active');
        })
    </script>
    @endsection