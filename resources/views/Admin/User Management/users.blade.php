@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="dark" class="pace-done">
    <div id="fakeloader"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="left-sidebar-placeholder"></div>
            <div class="col main" style="margin-top: 142px;">
              <div class="loader"></div>
                <!-- jumbotron-1 -->
                <div class="jumbotron jumbotron-1">
                  <div class="container-fluid">
                  <div class="row">
                      <div class="col">
                          <ol class="breadcrumb icon-angle-right">
                              <li><a href="{{url('admin/dashboard')}}">Dashboard</a>
                              </li>
                              <li class="active">Users</li>
                          </ol>
                      </div>
                   </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-info btn-rounded btn-icon" href="#"  id="add_user_btn">
                                  <i class="fa fa-plus"></i>
                                    <span class="btn-text">Add User</span>
                                </a>
                                <h4>User Management</h4>
                                <h5>Users</h5>
                            </div>
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
                                    <div class="col" id="user-table">
                                        <table id="users-list-table" class="table table-hover table-striped table-bordered" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              @foreach ($users as $user)
                                                <tr id="{{$user->id}}">
                                                    <td>{{$user->user_id}}</td>
                                                    <td>{{$user->name}}</td>
                                                    <td><span class="badge badge-pill badge-success">{{$user->user_role_name}}</span></td>
                                                    <td>{{$user->email}}</td>
                                                    <td>{{$user->phone}}</td>
                                                    <td>{{$user->address}}</td>
                                                    @if($user->user_role_id != 1)
                                                      <td><a href="#"><i class="ion-edit" title="Edit" ></i></a><a href="#"><i class="ion-trash-a" data-userid="{{$user->id}}" title="Delete" data-toggle="modal" data-target="#deleteVendor" ></i></a></td>
                                                    @else
                                                    <td></td>
                                                    @endif
                                                </tr>
                                              @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
    <div id="add-user-form"></div>
@endsection

@else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif

@section('jqueryIncludes')
 <script>
 $(document).ready(function(){
    $('#users-list-table').DataTable();

    $("#add_user_btn").click(function(){
      // $('#user-table').hide();
      // $('#add_user_btn').hide();

        $.ajax({
            url:"add-user",
            method:"get",
            beforeSend: function () {
                $("#fakeloader").fadeIn();
                $(".loader").show();
                $(".loader").css('margin-top','12%');
            },
            success: function(data){
              $('#add-user-form').html(data);
            },
            error: function (data) {

            },
            complete: function () {
             $("#fakeloader").fadeOut();
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
         }
      })
    });
    // $("#cancel_btn").click(function(){
    //   $('#user-table').show();
    //   $('#add_user_btn').show();
    //   $('.add-user-form').hide();
    // });
    });
</script>
@endsection
