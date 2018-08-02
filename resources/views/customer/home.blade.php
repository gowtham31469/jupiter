@extends('layouts.Customer.app') @if (Auth::guard("web_customer")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-div hidden">
            <select class="js-example-responsive buyer-select show-tick show-menu-arrow" data-live-search="true">
                <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option title="Selected Buyer : {{$buyer->buyer_name}}" value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
            </select>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
        <div class="select-buyer"></div>
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="col-md-4">
            <div class="card">
                <h4>Approved orders</h4>
                <span class="numbers approved-count">{{$approved_orders}}</span><i onclick="showApprovedOrders()" class="fa fa-arrow-right" aria-hidden="true" style="cursor:pointer;float: right;margin-top: 50px;color: #717171;font-size: 18px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h4>Approval pending Orders</h4>
                <span class="numbers pending-count">{{$app_pending_orders}}</span><i onclick="showPendingOrders()" class="fa fa-arrow-right" aria-hidden="true" style="cursor:pointer;float: right;margin-top: 50px;color: #717171;font-size: 18px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h4>Cancelled Orders</h4>
                <span class="numbers cancelled-count">{{$cancelled_orders}}</span><i onclick="showCancelledOrders()" class="fa fa-arrow-right" aria-hidden="true" style="cursor:pointer;float: right;margin-top: 50px;color: #717171;font-size: 18px;"></i>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <h4 class="text-center">Tabel Data</h4>
                <div class="table-responsive">
                    <table class="table table-striped text-center">

                        <thead>
                            <tr>
                                <th class="text-center">Product</th>
                                <th class="text-center">Payment Date</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Product1</td>
                                <td>23/11/2013</td>
                                <td><label class="label label-warning">Pending</label></td>
                            </tr>

                            <tr>
                                <td>Product2</td>
                                <td>10/11/2013</td>
                                <td><label class="label label-success">Delivered</label></td>
                            </tr>

                            <tr>
                                <td>Product3</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-info">In Call to confirm</label></td>
                            </tr>

                            <tr>
                                <td>Product4</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-danger">Declined</label></td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
             <div class="card">
                <h4 class="text-center">Tabel Data</h4>
                <div class="table-responsive">
                    <table class="table table-striped text-center">

                        <thead>
                            <tr>
                                <th class="text-center">Product</th>
                                <th class="text-center">Payment Date</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Product1</td>
                                <td>23/11/2013</td>
                                <td><label class="label label-warning">Pending</label></td>
                            </tr>

                            <tr>
                                <td>Product2</td>
                                <td>10/11/2013</td>
                                <td><label class="label label-success">Delivered</label></td>
                            </tr>

                            <tr>
                                <td>Product3</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-info">In Call to confirm</label></td>
                            </tr>

                            <tr>
                                <td>Product4</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-danger">Declined</label></td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Approved Orders</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer ">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="appPendingModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Approval pending Orders</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer ">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="cancelledOrdersModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Cancelled Orders</h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer ">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                </div>
            </div>
        </div>
    </div>
{{-- Show snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/customer') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Customer/customer-dashboard.js') }}"></script>
    <script>

         function showApprovedOrders() {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/approved-orders",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick=viewedApproved("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
                        });
                        $('#approvedModal .modal-body').html('<table class="table approved-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                        $('.approved-orders').DataTable();
                        $('#approvedModal').modal("show");
                        $(".modal-body input").attr('placeholder', 'Data Search');
                    
                    },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }

        function showPendingOrders(argument) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/pending-orders",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick=viewedPending("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
                        });
                        $('#appPendingModal .modal-body').html('<table class="table pending-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                       $('.pending-orders').DataTable();  
                       $('#appPendingModal').modal("show");
                       $(".modal-body input").attr('placeholder', 'Data Search');

                        },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }

        function showCancelledOrders(argument) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/cancelled-orders",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                       
                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick= viewedCancelled("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
                        });
                        $('#cancelledOrdersModal .modal-body').html('<table class="table cancelled-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                        $('.cancelled-orders').DataTable();
                        $(".modal-body input").attr('placeholder', 'Data Search');
                 
                        $('#cancelledOrdersModal').modal("show")
                        
                        },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }

        function  viewedApproved(id,table_name) {

           var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/viewed-approved",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token,
                        id:id,
                        table_name:table_name
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick=viewedApproved("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
                        });
                        $('#approvedModal .modal-body').html('<table class="table approved-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                        $('.approved-count').text(data.approved_count)
                        $('.approved-orders').DataTable();
                        $('#approvedModal').modal("show");
                        $(".modal-body input").attr('placeholder', 'Data Search');
                    },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }
        function  viewedPending(id,table_name) {
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/viewed-pending",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token,
                        id:id,
                        table_name:table_name
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick=viewedPending("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
                        });
                        $('#appPendingModal .modal-body').html('<table class="table pending-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                        $('.pending-count').text(data.pending_count)
                       $('.pending-orders').DataTable();  
                       $('#appPendingModal').modal("show");
                       $(".modal-body input").attr('placeholder', 'Data Search');
                     },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }
        function  viewedCancelled(id,tablename) {
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                var table_name = tablename;
                $.ajax({
                    url: base_url + "/customer/tesco/order-management/viewed-cancelled",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token,
                        id:id,
                        table_name:table_name
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                        var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));

                        var html = "";
                        $(orders).each(function(index, value)
                        {
                            if(value.zone == "CE")
                            {
                                var style_no = value.ce_style_ref;
                            }
                            else
                            {
                                var style_no = value.uk_style_ref;
                            }   
                            html += '<tr><td>'+style_no+'</td><td>'+value.TemplateName+'</td><td>'+value.zone+'</td><td><label onclick= viewedCancelled("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-info">Viewed</label></td></tr>';
                        });
                        $('#cancelledOrdersModal .modal-body').html('<table class="table cancelled-orders"><thead><tr><th>Style No</th><th>Template Name</th><th>Zone</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table>');
                        $('.cancelled-count').text(data.cancelled_count);
                        $('.cancelled-orders').DataTable();
                        $('#cancelledOrdersModal').modal("show")
                        $(".modal-body input").attr('placeholder', 'Data Search');
                    },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
        }
    </script>
    @endsection
