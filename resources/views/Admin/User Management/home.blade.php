@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">User Management</li>
                  </ol>
                <h3><i class="ion-ios-browsers-outline"></i>User Management</h3>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#" onClick="window.location.reload()"><i class="ion-social-codepen"></i>User Management Dashboard</a></li>
                    <li class="users-list-view"><a class="" href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li>
                    <li class="roles-list-view"><a class="" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li>
                </ul>
                <hr/>
              </div>
          <div class="clearfix"></div>
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
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add User</h4>
                </div>
                {!! Form::open(['id' => 'add_user_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="result"></div>
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
                            <span id="generatePwd" class="input-group-addon" style="background-color:#18bc9c;border-right: white;border-style: solid;">Generate Password</span>
                            <span class="input-group-addon js-textareacopybtn clipboard" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#password">Copy</span>
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
                        {!! Form::submit('Add User', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit User</h4>
                </div>
                {!! Form::open(['id' => 'edit_user_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('user_edit_id',$value = null, array('id'=>'user_edit_id','class' => 'form-control input-sm')) !!}
                      <div id="edit_result"></div>
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_user_id', 'User ID', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_user_id',$value = '', array('class' => 'form-control input-sm','placeholder' => 'User ID','readonly')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_email', 'Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">@</span>
                            {!! Form::email('edit_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_phone', 'Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">+91</span>
                            {!! Form::text('edit_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','data-maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('edit_address',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_user_role', 'User Role', ['class' => 'form_label']) !!}
                          {{  Form::select('edit_user_role',(['' => 'Select User Role']+ $roles->toArray()),'',array('class' => 'form-control input-sm','id'=>'edit_user_role','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p>By clicking ADD USER a email will be sent to user with his/her login details.</p>
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Edit User', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete User</h4>
                </div>
                {!! Form::open(['id' => 'delete_user_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="delete_result"></div>
                        <div class="form-group has-feedback">
                          <p>You are about to delete user. Do you want to proceed?</p>
                           {!! Form::hidden('id',$value = null, array('id'=>'delete_user_id','class' => 'form-control input-sm')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Delete User', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Role</h4>
                </div>
                {!! Form::open(['id' => 'add_role_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="role_result"></div>
                      <div class="row">
                          <div class="col-md-6">
                            <div class="col-md-12">
                              <div class="form-group has-feedback">
                                  {!! Form::label('role_id', 'Role ID', ['class' => 'form_label']) !!}
                                  {!! Form::text('role_id',$value = $role_id, array('class' => 'form-control input-sm','placeholder' => 'Role ID','readonly')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group has-feedback">
                                  {!! Form::label('role_name', 'Role Name', ['class' => 'form_label']) !!}
                                  {!! Form::text('role_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Role Name','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group has-feedback">
                                {!! Form::label('role_description', 'Description', ['class' => 'form_label']) !!}
                                {!! Form::textarea('role_description',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required','style'=>'resize:none;','rows'=>3)) !!}
                                <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                      </div>
                      <div class="col-md-6 modules_div">
                        <div class="row">
                            {!! Form::label('role_permissions', 'Role Permissions', ['class' => ' form_label']) !!}
                        </div>
                            @foreach($modules_tree as $key => $sub_mod)
                            <div class="main-menu" id="{{$sub_mod->id}}">
                                <div class="main-module">
                                    <input class="main-input" type="checkbox" value="{{$sub_mod->id}}"> {{$sub_mod->module_name}}

                                    <span class="glyphicon glyphicon-chevron-right" style="margin-top:3px;float: right;"></span>
                                </div>
                                <div class="new-div">
                                    @foreach($sub_mod->second as $key1 => $sub_mod1)
                                    <div class="sub-module" id="{{$sub_mod1->id}}" data-id="{{$sub_mod->id}}">
                                        <input class="sub-input" type="checkbox" data-id="{{$sub_mod->id}}" value="{{$sub_mod1->id}}">
                                        <span class="glyphicon glyphicon-chevron-right" style="margin-top:3px;float: right;"></span> {{$sub_mod1->module_name}} </div>
                                        <div class="new-div2">
                                        @foreach($sub_mod1->third as $key2 => $sub_mod2)

                                        <div class="sub-module2" data-id="{{$sub_mod1->id}}" style="display:none;">
                                            <input class="sub-input2" type="checkbox" data-id="{{$sub_mod1->id}}" value="{{$sub_mod2->id}}"> {{$sub_mod2->module_name}} </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                      </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Role', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
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
    /** generate password **/
    $("#generatePwd").validator().click(function(){
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
            r = /[^a-z0-9@._ ]/gi,
            v = $(this).val();
        if(r.test(v))
        {
          $(this).val(v.replace(r, ''));
          c--;
        }
        this.setSelectionRange(c, c);
      });
      /** To refresh modal **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_user_form').trigger("reset");
        $('#add_role_form').trigger('reset');
        $('#edit_result').hide();
        $('#result').hide();
        $('#delete_result').hide();
        $('.main-input').prop('checked', false)
        $('.sub-input').prop('checked', false)
        $('.sub-input2').prop('checked', false)
       })
     /**  Validation for mobile number **/
     function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57))
      {
          return false;
      }
        return true;
      }
      /** submit add user form **/
      $('#add_user_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           addUser();
        }
      })
      // get edit user details
      $(document).on('click','.edit-user',function(){
          var id = $(this).closest('tr').attr('id');
          getUser(id);
      });
      /** submit edit user form **/
      $('#edit_user_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           editUser();
        }
      })
      $('#deleteUserModal').on('show.bs.modal', function (e) {
          var config = $(e.relatedTarget).data('userid');
          $('#delete_user_id').attr('value', config);
      });
      /** submit edit user form **/
      $('#delete_user_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           var id   = $('#delete_user_id').val();
           deleteUser(id);
        }
      })
      /** Copy password to clipboard **/
      $( document ).ready(function() {
        var clipboard = new Clipboard('.clipboard');
        clipboard.on('success', function(e) {
          $(e.trigger).text("Copied!");
          e.clearSelection();
          setTimeout(function() {
            $(e.trigger).text("Copy");
          }, 2500);
        });
      });
    </script>
    <script>
    $('.modules_div').ready(function () {
    $('new-div').each(function () {
        var submod_no = $(this).find('.sub-module').length;
        if (submod_no == 0)
        {
            $(this).prev('.main-module').find('span').css('display', 'none')
        }
    })
    })
    </script>
    <script>
    /** Open and close sub modules level 1 **/
    $('.main-menu').on('click', function (e) {
    // $('.main-module').css('background', 'white')
    // $('.main-module').css('color', '#73879C')
    // $('.main-module').css('border-color', '#e5e5e5')
    // $(this).find('.main-module').css('background', 'rgb(238, 238, 238)')
    // $(this).find('.main-module').css('color', '#000')
    // $(this).find('.main-module').css('border-color', 'rgb(238, 238, 238)')
    $('.main-module').find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
    $(this).find('span').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
    var dataId = $(this).attr('id')
    $(".sub-module").css('display', 'none');
    $(".sub-module2").css('display', 'none');
    $(".sub-module[data-id='" + dataId + "']").css('display', 'inline-block');
    e.stopPropagation()
    })
    /** Open and close sub modules level 2 **/
    $('.sub-module').on('click', function (e) {
    // $('.sub-module').css('background', 'white')
    // $('.sub-module').css('color', '#73879C')
    // $('.sub-module').css('border-color', '#e5e5e5')
    // $(this).find('.sub-module').css('background', 'rgb(238, 238, 238)')
    // $(this).find('.sub-module').css('color', '#000')
    // $(this).find('.sub-module').css('border-color', 'rgb(238, 238, 238)')
    $('.sub-module').find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
    $(this).find('span').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
    var dataId = $(this).attr('id')
    $(".sub-module2").css('display', 'none');
    $(".sub-module2[data-id='" + dataId + "']").css('display', 'block');
    e.stopPropagation()
    })
    /** hide all on body click **/
    $('body').on('click', function (e) {
        $(".sub-module").css('display', 'none');
        $(".sub-module2").css('display', 'none');
        $('.main-module').find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
        $('.main-module').css('background', 'white')
        $('.main-module').css('color', '#73879C')
        $('.main-module').css('border-color', '#e5e5e5')
    })
    /** Checked 1st level **/
    $('.main-module').find('input').on('change', function () {
    var mainModule = $(this).val();
    if ($(this).is(":checked"))
    {
        $('.sub-input[data-id="' + mainModule + '"]').prop('checked', true)
        if($('.new-div').html() == "")
        {
          alert('empty')
        }
        else
        {
          alert('not empty')
        }
        $('.sub-input2').prop('checked', true)
    }
    else
    {
        $('.sub-input[data-id="' + mainModule + '"]').prop('checked', false)
        $('.sub-input2').prop('checked', false)
    }
    })
    /** Checked 2nd level **/
    $('.sub-module').find('input').on('change', function () {
    var mainModule = $(this).attr('data-id');
    var subModule = $(this).val();
    if ($(this).is(":checked"))
    {
        $('.main-module').find('input[value="' + mainModule + '"]').prop('checked', true)
        $('.sub-input2[data-id="' + subModule + '"]').prop('checked', true)
    }
    else
    {
      var checked = [];
      $(this).closest('new-div').find('.sub-input').each(function () {
          if ($(this).is(':checked'))
          {
              checked.push($(this).val())
          }
          else
          {

          }
      })
      if ($.isEmptyObject(checked) == true)
      {
          $('.sub-input2[data-id="' + subModule + '"]').prop('checked', false)
      }
      else
      {

      }

    }
    })
    /** Checked 3rd level **/
    $('.sub-module2').find('input').on('change', function () {
      var mainModule1 = $(this).attr('data-id');
      if ($(this).is(":checked"))
      {
          $('.sub-module').find('input[value="' + mainModule1 + '"]').prop('checked', true)
          $('new-div2').each(function () {
              var submod_no = $(this).find('.sub-module2').length;
              if (submod_no != 0)
              {
                var mainModule2 = $(this).parent().parent().parent().parent().attr("id");
                $('.main-module').find('input[value="' + mainModule2 + '"]').prop('checked', true)
              }
          })

      }
      else
      {
      }
    })
    /** submit edit user form **/
    $('#add_role_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         var permissions = [];
         $('.sub-input').each(function () {
             if ($(this).is(':checked')) {
                 permissions.push($(this).val());
             } else {}
         });
         $('.main-input').each(function () {
             if ($(this).is(':checked')) {
                 permissions.push($(this).val());
             } else {}
         });
         $('.sub-input2').each(function () {
             if ($(this).is(':checked')) {
                 permissions.push($(this).val());
             } else {}
         });
         if ($.isEmptyObject(permissions) == false)
         {
             permissions = permissions.join(',');
         }
         alert(permissions)
         addRole(permissions)
      }
    })
    </script>
    @endsection
