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
                  <div class="row">
                      <div class="col">
                          <ol class="breadcrumb icon-home icon-angle-right">
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
                                    <i class="sli-wrench"></i>
                                    <span class="btn-text">Add User</span>
                                </a>
                                <h4>User Management</h4>
                                <h5>Users</h5>
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
                                <div class="col-12 col-md-6 add-user-form" style="display:none">
                                    <div class="widget">
                                        <div class="row">
                                            <div class="col">
                                                <div class="title">Add User</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                {!! Form::open(['method' => 'post','id' => 'add_user']) !!}
                                                    <div class="form-group">
                                                        {!! Form::label('user_id', 'User ID * :', ['class' => 'form_label']) !!}
                                                        {!! Form::text('user_id',$value = '', array('class' => 'form-control ','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                      {!! Form::label('name', 'Name * :', ['class' => 'form_label']) !!}
                                                      {!! Form::text('name',$value = '', array('class' => 'form-control ','placeholder' => 'Enter Name','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                      {!! Form::label('phone', 'Phone * :', ['class' => 'form_label']) !!}
                                                      {!! Form::text('phone',$value = '', array('class' => 'form-control ','placeholder' => 'Enter Phone','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                      {!! Form::label('email', 'Email * :', ['class' => 'form_label']) !!}
                                                      {!! Form::email('email',$value = '', array('class' => 'form-control ','placeholder' => 'Enter Email','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                      {!! Form::label('address', 'Address * :', ['class' => 'form_label']) !!}
                                                      {!! Form::textarea('address',$value = '', array('class' => 'form-control ','placeholder' => 'Enter Email','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                      {!! Form::label('password', 'Password * :', ['class' => ' form_label']) !!}
                                                      {!! Form::password('password', array('class' => 'form-control ','placeholder' => 'Enter Password','required')) !!}
                                                    </div>
                                                    <div class="form-group">
                                                        {!! Form::label('user_role', 'User Role * :', ['class' => 'form_label']) !!}
                                                        {{  Form::select('user_role',(['' => '-- Select User Role --']+ $roles),'',array('class' => 'form-control ')) }}
                                                    </div>
                                                    {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                                                    {!! Form::submit('Cancel', ['class' => 'btn btn-primary','id' => 'cancel_btn'] ) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
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

    $("#add_user_btn").click(function(){
      $('#user-table').hide();
      $('#add_user_btn').hide();
      $('.add-user-form').show();
    });
    $("#cancel_btn").click(function(){
      $('#user-table').show();
      $('#add_user_btn').show();
      $('.add-user-form').hide();
    });
    });
</script>
@endsection
