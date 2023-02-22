<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
            
            <span class="navbar-brand" href="#">Jupiter Garment Accessories</span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- <ul class="nav navbar-nav dynamic-menu">
                @foreach($sub_modules as $mod) @if($mod->parent_id != 0)
                <li><a class="" href="{{$mod->url_path}}">{{$mod->module_name}}</a></li>
                @endif @endforeach
            </ul> -->

            <ul class="nav navbar-nav navbar-right">
                <li><a style="padding:11px 20px;" class="navbar-brand grid-menu" data-toggle="tooltip" data-placement="bottom" title="Main menu" href="/admin/dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><g transform="translate(-1132 18)"><rect width="8" height="8" rx="3" transform="translate(1132 -8)" fill="#e3a7c0"/><rect width="8" height="8" rx="3" transform="translate(1142 -8)" fill="#b0abcb"/><rect width="8" height="8" rx="3" transform="translate(1132 -18)" fill="#bad5f0"/><rect width="8" height="8" rx="3" transform="translate(1142 -18)" fill="#a5d5d5"/></g></svg></a></li>
                <!-- <li class="dropdown season_code-nav-menu"></li> -->
                <!-- <li class="dropdown year-nav-menu"></li> -->
                <li class="dropdown buyer-nav-menu"></li>
                 <li><a class="notification" style="cursor: pointer; padding:7px 20px; margin-top: -3px;" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><span style="font-size: 18px; margin-top: -1px;"><svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M10.75 14A2.25 2.25 0 0 1 13 16.25v1.502l-.008.108c-.31 2.127-2.22 3.149-5.425 3.149-3.193 0-5.134-1.01-5.553-3.112L2 17.75v-1.5A2.25 2.25 0 0 1 4.25 14h6.5ZM7.5 6a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7Zm12.25-4A2.25 2.25 0 0 1 22 4.25v3.5A2.25 2.25 0 0 1 19.75 10h-1.553l-2.541 2.207A1 1 0 0 1 14 11.452V9.986a2.25 2.25 0 0 1-2-2.236v-3.5A2.25 2.25 0 0 1 14.25 2h5.5Z" fill="#ffffff"/></svg></span></a></li>
                <li class="dropdown">
                    <!-- <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::user()->name[0] }}</span> {{ Auth::user()->name }}<span class="caret"></span></a> -->

                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome {{ Auth::user()->name }}   <span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a style="cursor: pointer;" onclick="getAdminProfile({{ Auth::user()->id }})">Profile</a></li>
                        <li><a style="cursor: pointer;" data-target="#changePasswordModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">Change Password</a></li>
                       <!--  <li><a class="dropdown-item logout-button" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form> -->
                    </ul>
                </li>
               
                
                <li><a style="padding:7px 20px " class="dropdown-toggle profile-dropdown-admin" href="{{ 'admin/logout' }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><svg width="24" height="24" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8.502 11.5a1.002 1.002 0 1 1 0 2.004 1.002 1.002 0 0 1 0-2.004Z" fill="#ffffff"/><path d="M12 4.354v6.651l7.442-.001L17.72 9.28a.75.75 0 0 1-.073-.976l.073-.084a.75.75 0 0 1 .976-.073l.084.073 2.997 2.997a.75.75 0 0 1 .073.976l-.073.084-2.996 3.004a.75.75 0 0 1-1.134-.975l.072-.085 1.713-1.717-7.431.001L12 19.25a.75.75 0 0 1-.88.739l-8.5-1.502A.75.75 0 0 1 2 17.75V5.75a.75.75 0 0 1 .628-.74l8.5-1.396a.75.75 0 0 1 .872.74Zm-1.5.883-7 1.15V17.12l7 1.236V5.237Z" fill="#ffffff"/><path d="M13 18.501h.765l.102-.006a.75.75 0 0 0 .648-.745l-.007-4.25H13v5.001ZM13.002 10 13 8.725V5h.745a.75.75 0 0 1 .743.647l.007.102.007 4.251h-1.5Z" fill="#ffffff"/></svg></a></li>
                
                        <form id="logout-form" action="{{ '/admin/logout' }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
<div class="header-bar">
<div class="dropdown year-nav-menu" style="float:right"></div>
<div class="dropdown season_code-nav-menu" style="float:right"></div>
<h2 class="logo"><span style="visibility:hidden">Page title</span></h2>
<ul class="navTabs dynamic-menu">
@foreach($sub_modules as $mod) @if($mod->parent_id != 0)
                <li><a class="" href="{{$mod->url_path}}">{{$mod->module_name}}</a></li>
                @endif @endforeach
</ul>
</div>
{{-- Snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>
{{-- Send Feedback modal --}}
     <div class="modal fade" id="sendFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Send Feedback</h4>
                </div>
                {!! Form::open(['id' => 'send_feedback','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                         {!! Form::label('feedback_email', 'Your Email', ['class' => 'form_label']) !!}
                         <div class="input-group">
                           <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                           {!! Form::email('feedback_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());','maxlength' => '50')) !!}
                         </div>
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('feedback', 'Description', ['class' => 'form_label']) !!}
                         {!! Form::textarea('feedback',$value = '', array('class' => 'form-control','placeholder' => 'Describe your issue or share your ideas','required','style'=>'resize:vertical;','rows'=>10)) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('image[]', 'Attach Screenshots', ['class' => 'form_label']) !!}
                            {!! Form::file('image[]', array('multiple'=>true,'accept'=>'image/*','class'=>'form-control TheFiles'))  !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Send', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
  {{-- Edit admin profile modal --}}
  <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Edit Admin Profile</h4>
             </div>
             {!! Form::open(['id' => 'admin_profile_form','data-toggle' => 'validator','role' => 'form']) !!}
                 <div class="modal-body">
                  {{-- hidden id for edit functionality --}}
                   {!! Form::hidden('admin_profile_id',$value = null, array('id'=>'admin_profile_id','class' => 'form-control input-sm')) !!}
                   <div id="admin_profile_result"></div>
                     <div class="form-group has-feedback">
                       {!! Form::label('admin_profile_name', 'Name', ['class' => 'form_label']) !!}
                       {!! Form::text('admin_profile_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('admin_profile_email', 'Email', ['class' => 'form_label']) !!}
                       <div class="input-group">
                         <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                         {!! Form::email('admin_profile_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());','maxlength' => '50')) !!}
                       </div>
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('admin_profile_phone', 'Phone', ['class' => 'form_label']) !!}
                       <div class="input-group">
                         <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                         {!! Form::text('admin_profile_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('admin_profile_address', 'Address', ['class' => 'form_label']) !!}
                       {!! Form::textarea('admin_profile_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3, 'style' => 'text-transform: capitalize;')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                     {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
{{-- Change password modal --}}
     <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                </div>
                {!! Form::open(['id' => 'change_admin_password','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                    <div id="change_password_result"></div>
                    {{-- hidden id for edit functionality --}}
                     {!! Form::hidden('admin_id',$value = Auth::user()->id, array('id'=>'admin_id','class' => 'form-control input-sm')) !!}
                     <div class="form-group has-feedback">
                       {!! Form::label('current_password', 'Current Password', ['class' => 'form_label']) !!}
                       {!! Form::password('current_password', array('class' => 'form-control input-sm ','placeholder' => 'Current Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('new_password', 'New Password', ['class' => 'form_label']) !!}
                       {!! Form::password('new_password',array('class' => 'form-control input-sm ','placeholder' => 'New Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('confirm_new_password', 'Confirm New Password', ['class' => 'form_label']) !!}
                       {!! Form::password('confirm_new_password',array('class' => 'form-control input-sm ','placeholder' => 'Confirm New Password','required','data-match' => '#new_password', 'data-match-error' => "Whoops, these don't match", 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Change Password', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
