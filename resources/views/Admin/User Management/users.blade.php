@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body id="forms-validation" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="dark" class="pace-done">
  <div id="fakeloader"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <div class="action-group pull-right">
                    <a href="#" data-toggle="modal" data-target="#myModal" class=" btn btn-primary btn-sm"><i class="ion-person-add"></i>Add Users</a>
                </div>
                <ul class="sub-nav-list">
                    <li><a class="" href="{{url('admin/user-management')}}"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li><a class="active" href="#"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li><a class="" href="{{url('admin/user-management/roles')}}"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>
            </div>
        </div>
    </div>
  <div class="col main">
    <div class="page">
        <!-- pages/empty-page -->
        <div class="row">
            <div class="col">
                <div class="widget">
                  <div class="container-fluid">
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
                                        <td><span class="badge badge-pill ">{{$user->user_role_name}}</span></td>
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
  </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add User</h4>
                </div>
                {!! Form::open(['method' => 'post','id' => 'add_user']) !!}
                    <div class="modal-body">
                        <div class="form-group">
                            {!! Form::label('user_id', 'User ID', ['class' => 'form_label']) !!}
                            {!! Form::text('user_id',$value = '', array('class' => 'form-control input-sm ','required','placeholder'=>'User ID')) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::label('name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::label('email', 'Email', ['class' => 'form_label']) !!}
                          {!! Form::email('email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::label('password', 'Password', ['class' => ' form_label']) !!}
                          <div class="input-group">
                            {!! Form::text('password',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Password','required')) !!}
                            <span id="generatePwd" class="input-group-addon">Generate Password</span>
                          </div>
                        </div>
                        <div class="form-group">
                          {!! Form::label('phone', 'Phone', ['class' => 'form_label']) !!}
                          {!! Form::text('phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','required')) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::label('address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('address',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                        </div>
                        <div class="form-group">
                          {!! Form::label('user_role', 'User Role', ['class' => 'form_label']) !!}
                          {{  Form::select('user_role',(['' => '-- Select User Role --']+ $roles->toArray()),'',array('class' => 'form-control input-sm')) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p>By Clicking ADD USER a email will be sent to user with his/her login details.</p>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
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
      $('#users-list-table').DataTable();
      $("#example_filter input").addClass('form-control');
      $("#example_filter input").attr('placeholder', 'Data Search');
    $("#generatePwd").click(function(){
    function randomString(length, chars)
    {
      var mask = '';
      if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
      if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      if (chars.indexOf('#') > -1) mask += '0123456789';
      if (chars.indexOf('!') > -1) mask += '!@#$%&*()+=?';
      var result = '';
      for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
      return result;
    }
      $("#password").val( randomString(10, 'aA#!'));
      })
    });
    </script>
@endsection
