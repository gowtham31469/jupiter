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
                                            <li class="active">Template List</li>
                                        </ol>
                                    </div>
                                </div>
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-info btn-rounded btn-icon" href="#" onclick="orchestra_create_template_view(this)">
                                    <i class="fa fa-plus"></i>
                                    <span class="btn-text">Create Template</span>
                                </a>
                                <h4>Template Management</h4>
                                <h5>Template List</h5>
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
                                                    <th>S.No</th>
                                                    <th>Template Name</th>
                                                    <th>Template Desc</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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

