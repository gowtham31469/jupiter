@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <div class="action-group pull-right hidden add-users-div" >
                    <a href="#" data-toggle="modal" data-target="#myModal" class=" btn btn-primary btn-sm" data-backdrop=“static” data-keyboard=“false”><i class="ion-person-add"></i>Add Users</a>
                </div>
                <div class="action-group pull-right hidden add-roles-div" >
                    <a href="#" data-toggle="modal" data-target="#myModal" class=" btn btn-primary btn-sm"><i class="ion-person-add"></i>Add Roles</a>
                </div>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#" onClick="window.location.reload()"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li class="users-list-view"><a class="" href="#" onclick="users_list_view()"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li class="roles-list-view"><a class="" href="#" onclick="roles_list_view()"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>
                <div class="main-content">
                    <div class="loader"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart" width="100px" height="100px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart1" width="100px" height="100px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart2" width="100px" height="100px"></canvas>
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
                        <div class="card"></div>
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
                {!! Form::open(['id' => 'add_user_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                            {!! Form::label('user_id', 'User ID', ['class' => 'form_label']) !!}
                            {!! Form::text('user_id',$value = $user_id, array('class' => 'form-control input-sm','placeholder' => 'User ID','readonly')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('email', 'Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">@</span>
                            {!! Form::email('email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('password', 'Password', ['class' => ' form_label']) !!}
                          <div class="input-group">
                            {!! Form::text('password',$value = '', array('class' => 'form-control input-sm js-copytextarea','placeholder' => 'Password','required')) !!}
                            <span id="generatePwd" class="input-group-addon">Generate Password</span>
                            <span id="generatePwd" class="input-group-addon js-textareacopybtn">Copy Password</span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('phone', 'Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">+91</span>
                            {!! Form::text('phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','data-maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('address',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('user_role', 'User Role', ['class' => 'form_label']) !!}
                          {{  Form::select('user_role',(['' => 'Select User Role']+ $roles->toArray()),'',array('class' => 'form-control input-sm','id'=>'user_role','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p>By clicking ADD USER a email will be sent to user with his/her login details.</p>
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Add User', ['class' => 'btn btn-primary','type' => 'submit'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div id="snackbar">Some text some message..</div>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script>
    /** copy password **/
    var copyTextareaBtn = document.querySelector('.js-textareacopybtn');
    copyTextareaBtn.addEventListener('click', function(event) {
      var copyTextarea = document.querySelector('.js-copytextarea');
      copyTextarea.select();

      try {
        var successful = document.execCommand('copy');
        var msg = successful ? 'successful' : 'unsuccessful';
        console.log('Copying text command was ' + msg);
      } catch (err) {
        console.log('Oops, unable to copy');
      }
    });
    /** generate password **/
    $("#generatePwd").validator().click(function(){
      $('#password').validator('validate');
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
      $('#add_user_form').validator('validate');
      })
      /** restrict special characters **/
      $('input').on('input', function() {
        var c = this.selectionStart,
            r = /[^a-z0-9@._]/gi,
            v = $(this).val();
        if(r.test(v)) {
          $(this).val(v.replace(r, ''));
          c--;
        }
        this.setSelectionRange(c, c);
      });
      /** To refresh modal **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $(this).find("input:not('#user_id'),textarea,select").val('').end();
        $('#add_user_form').trigger("reset");
       })
     /**  Validation for mobile number **/
     function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
      }
      /** submit add user form **/
      $('#add_user_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
          return false;
        } else {
          add_user()
        }
      })
    </script>
    @endsection
