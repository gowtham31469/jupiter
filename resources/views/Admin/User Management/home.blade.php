@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li><a class="" href="{{url('admin/user-management/users')}}"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li><a class="" href="{{url('admin/user-management/roles')}}"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>
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
    @endsection
