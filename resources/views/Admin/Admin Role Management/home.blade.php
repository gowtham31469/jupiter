@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
                <div class="action-group pull-right sub-menu-button">
                  <a href="#" data-target="#addAdminModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                    <i class="ion-plus"></i> Add Admin</a>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                    <div class="col" id="admin-table">
                    <table id="admin-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($admin as $value)
                        <tr id="{{$value->id}}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->name }}</td>
                          <td>{{ $value->email }}</td>
                          <td>{{ $value->phone }}</td>
                          <td>{{ $value->address }}</td>
                          @if($value->is_active === 1)
                            <td><span class="label label-success">Active</span></td>
                          @else
                            <td><span class="label label-danger">Inactive</span></td>
                          @endif
                          @if($value->is_active === 1)
                            <td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a></td>
                          @else
                            <td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a></td>
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
    {{-- Add admin modal --}}
     <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Admin</h4>
                </div>
                {!! Form::open(['id' => 'add_admin_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="add_admin_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('email', 'Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                            {!! Form::email('email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('password', 'Password', ['class' => ' form_label']) !!}
                          <div class="input-group">
                            {!! Form::text('password',$value = '', array('class' => 'form-control input-sm js-copytextarea','placeholder' => 'Password','required','minlength' => '6')) !!}
                            <span id="generatePwd" class="input-group-addon input-sm" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                            <span class="input-group-addon js-textareacopybtn clipboard input-sm" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#password">Copy</span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('phone', 'Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                            {!! Form::text('phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('address',$value = '', array('class' => 'form-control ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/admin-role.js') }}"></script>
   <script type="text/javascript">
       $( document ).ready(function() {
         /* Show logo on left side*/
         $('.logo').html('Admin')
         $('#admin-list-table').DataTable();
         $("#admin-list-table_filter input").addClass('form-control input-sm');
         $("#admin-list-table_filter input").attr('placeholder', 'Data Search');
         $('#admin-list-table_length select').addClass('form-control input-sm');
         $('#add_admin_form').trigger("reset");
         $('#add_admin_result').hide();
         $('.status').tooltip('show')
         $('.status').tooltip('toggle')
       });
       /** generate random password **/
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
         $('#add_admin_form').validator('validate');
         })
         /** Copy random password to clipboard **/
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
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       /** Add admin bootstrap form validation **/
        $('#add_admin_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addAdmin function from admin-role.js*/
           addAdmin();
        }
      })

      /** To refresh modal while hidden **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_admin_form').trigger("reset");
        $('#add_admin_result').hide();
       })

       /** To activate admin on click **/
       $(document).on('click', '#make_active', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeAdminActive function from admin.js*/
           makeAdminActive(id,csrf_token)
       });

       /** To deactivate admin on click **/
       $(document).on('click', '#make_inactive', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeAdminInactive function from admin.js*/
           makeAdminInactive(id,csrf_token)
       });
    </script>
    @endsection
