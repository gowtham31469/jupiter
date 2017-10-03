@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="dark" class="pace-done">
    <div id="fakeloader"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="left-sidebar-placeholder"></div>
            <div class="col main">
                <!-- jumbotron-1 -->
                <div class="jumbotron jumbotron-1">
                    <div class="container-fluid">
				<div class="row">
                                    <div class="col">
                                        <ol class="breadcrumb icon-angle-right">
                                            <li><a href="{{url('admin/dashboard')}}">Dashboard</a>
                                            </li>
                                            <li class="active">Sticker Orders</li>
                                        </ol>
                                    </div>
                                </div>
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-info btn-rounded btn-icon" href="#">
                                    <i class="fa fa-plus"></i>
                                    <span class="btn-text">Create Order</span>
                                </a>
                                <h4>Order Management</h4>
                                <h5>Sticker Orders</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end jumbotron-1 -->
                <div class="page">
                    <!-- pages/empty-page -->
                    <div class="row">
                        <div class="col">
                            <div class="widget">
				<div class="row">
                                    <div class="col">
                                        <table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>UVC</th>
                                                    <th>Purchase Order</th>
                                                    <th>Supplier Name</th>
                                                    <th>Theme</th>
                                                    <th>Order Qty</th>
						    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
						@foreach($orders as $value)
		                                        <tr id="{{$value->id}}">
		                                            <td>{{$value->UVC}}</td>
		                                            <td>{{$value->BuyerPONo}}</td>
		                                            <td>{{$value->SupplierName}}</td>
		                                            <td>{{$value->Theme}}</td>
		                                            <td>{{$value->Quantity}}</td>
							    <td>{{$value->IsSTD == 0 ? "Solid":"Standard"}}</td>
		                                        </tr>
						@endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end pages/empty-page -->
                </div>
            </div>
        </div>
    </div>
@endsection

@else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif

@section('jqueryIncludes')
<script>
    $('#datatable-example-2').DataTable();
</script>
@endsection

