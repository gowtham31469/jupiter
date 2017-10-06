@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body id="forms-validation" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="dark" class="pace-done">
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
    function jumbotron_without_button(name,main_menu)
    {
        return '<div class="loader"></div><div class="jumbotron jumbotron-1"><div class="container-fluid"><div class="row"><div class="col"><ol class="breadcrumb icon-angle-right"><li><a href="#">Dashboard</a></li><li class="active">'+name+'</li></ol></div></div><div class="row"><div class="col"><h4>'+main_menu+'</h4><h5>'+name+'</h5></div></div></div></div>';
    }

    $("#add_user_btn").click(function(){
      $.ajax({
            url:"get-roles",
            method:"get",
            beforeSend: function () {

            },
            success: function(data){
              $('.main').html(jumbotron_without_button('Users','User Management'));
              var html = '<div class="page"><div class="row"><div class="col"><div class="widget"><div class="col-12 col-md-6 add-user-form"><div class="widget"><div class="row"><div class="col"><form id="form-validation-1"><div class="form-group"><label class="form-control-label">User ID *</label><input type="text" class="form-control" name="user_id" id="user_id"  ><small class="form-control-feedback"></small></div><div class="form-group"><label class="form-control-label">Name *</label><input type="text" class="form-control"  placeholder="Enter name" name="name" id="name" ><small class="form-control-feedback"></small></div><div class="form-group"><label class="form-control-label">Phone *</label><input type="text" class="form-control"  placeholder="Enter phone" name="phone" id="phone" ><small class="form-control-feedback"></small></div><div class="form-group"><label class="form-control-label">Email *</label><input type="email" class="form-control"  placeholder="Enter email" name="email" id="email" ><small class="form-control-feedback"></small></div><div class="form-group"><label class="form-control-label">Address *</label><textarea class="form-control" rows="6" name="address" id="address" ></textarea><small class="form-control-feedback"></small></div><div class="form-group"><label class="form-control-label">Password *</label><input type="password" class="form-control" placeholder="Enter password" name="password" id="password" ><small class="form-control-feedback"></small></div><div class="form-group"><label class="select-label"><span>User Role *</span></label><select class="custom-select form-control" name="user_role" id="user_role" ><option value="">Select User Role</option>';
              $.each(data.roles, function(i, item){
                html += '<option value="'+item.id+'">'+item.user_role_name+'</option>';
              });
              html += '</select><small class="form-control-feedback"></small></div><button class="btn btn-primary" type="submit" name="submit">Submit</button><input class="btn btn-primary" type="button" value="Cancel" onClick="window.location.reload()"></form></div></div></div></div></div></div></div></div>';
              $('.main').append(html);
            },
            error: function (data) {

            },
            complete: function () {

         }
      })
     })
    });
    /** approvejs validation **/
    (function() {
        $(function() {
            if (!element_exists('#forms-validation')) {
                return false;
            }
            //user_id
            validateOnChange('#user_id', {
                title: 'User ID',
                required: true
            }, 'User ID field is valid', 'Please enter a valid User ID');
            //name
            validateOnChange('#name', {
                title: 'Name',
                required: true
            }, 'Name field is valid', 'Please enter a valid name');
            //email
            validateOnChange('#email', {
                title: 'Email',
                required: true,
                email: true
            }, 'Email field is valid', 'Please enter a valid email');
            //password
            validateOnChange('#password', {
                title: 'Password',
                range: {
                    min: 8,
                    max: 16
                }
            }, 'Password field is valid', 'Please enter a valid password with at least 8 characters');
            //phone
            validateOnChange('#phone', {
                title: 'Phone',
                numeric: true,
                range: {
                    min: 10,
                    max: 10
                }
            }, 'Phone field is valid', 'Please enter numbers only');
            //address
            validateOnChange('#address', {
                title: 'Address',
                required: true
            }, 'Address field is valid', 'Please enter an address');
            //user role
            validateOnChange('#user_role', {
                title: 'User Role',
                required: true
            }, 'User role selected', 'Please select user role');
        });
    })();
    $('#form-validation-1').submit(function(e) {
        e.preventDefault();
        var fields = [];
        $('#form-validation-1 .form-control').each(function() {
            $(this).focus().blur();
            fields.push($(this).attr('data-valid') === 'true' ? true : false);
        });
        if (fields.includes(false)) {
            console.log('form is not ok and cannot be submitted');
        } else {
            console.log('form is ok and can be submitted');
        }
        return false;
    });

</script>
@endsection
