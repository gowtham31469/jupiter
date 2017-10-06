@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <div class="action-group pull-right">
                    <a href="#" data-toggle="modal" data-target="#myModal" class=" btn btn-primary btn-sm"><i class="ion-person-add"></i>Add Roles</a>
                </div>
                <ul class="sub-nav-list">
                    <li><a class="" href="{{url('admin/user-management')}}"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li><a class="" href="{{url('admin/user-management/users')}}"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li><a class="active" href="#"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col main">
                <div class="page">
                    <!-- pages/empty-page -->
                    <div class="row">
                        <div class="col">
                            <div class="widget">
                                <div class="row">
                                    <div class="col">
                                        <table id="roles-list-table" class="table table-hover table-striped table-bordered" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Role Name</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              @foreach ($roles as $role)
                                                <tr id="{{$role->id}}">
                                                    <td>{{$role->role_id}}</td>
                                                    <td><span class="badge badge-pill badge-success">{{$role->user_role_name}}</span></td>
                                                    <td>{{$role->user_role_description}}</td>
                                                    @if($role->id != 1)
                                                      <td><a href="#"><i class="ion-edit" title="Edit" ></i></a><a href="#"><i class="ion-trash-a" data-userid="{{$role->id}}" title="Delete" data-toggle="modal" data-target="#deleteVendor" ></i></a></td>
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
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add User</h4>
                </div>
                <form>

                    <div class="modal-body">

                        <div class="form-group">
                            <label>User ID</label>
                            <input type="email" class="form-control input-sm" id="" placeholder="User ID">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Name</label>
                            <input type="password" class="form-control input-sm" id="exampleInputPassword1" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email</label>
                            <input type="password" class="form-control input-sm" id="exampleInputPassword1" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <div class="input-group">
                                <input type="text" value="" class="form-control input-sm" id="password" placeholder="password">
                                <span id="generatePwd" class="input-group-addon">Generate Password</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Phone</label>
                            <input type="password" class="form-control input-sm" id="exampleInputPassword1" placeholder="Phone">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Address</label>
                            <textarea placeholder="Address" class="form-control">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">User Role</label>
                            <select class="form-control input-sm">
                            <option>Akshay Bhat</option>
                            </select>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <p>By Clicking ADD USER a email will be sent to user with his/her login details.</p>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Add User</button>
                    </div>
                </form>
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
    $(document).ready(function() {
    $('#roles-list-table').DataTable();
    $("#example_filter input").addClass('form-control');
    $("#example_filter input").attr('placeholder', 'Data Search');
    });
    </script>
@endsection
