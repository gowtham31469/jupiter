@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <div class="action-group pull-right">
                    <a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a>
                    <a href="#" data-toggle="modal" data-target="#myModal" class=" btn btn-primary btn-sm"><i class="ion-person-add"></i>Add Users</a>
                    <a href="#" class=" btn btn-primary btn-sm"><i class="ion-plus-round"></i>Create Order</a>

                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action <span class="caret"></span>
  </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                </div>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li><a class="" href="#"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li><a class="" href="#"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>

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
    $('#example').DataTable();
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
