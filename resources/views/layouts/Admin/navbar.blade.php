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
            <a class="navbar-brand grid-menu" href="/admin/dashboard"><i class="fa fa-th" aria-hidden="true"></i></a>
            <span class="navbar-brand logo" href="#"></span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav dynamic-menu">
                @foreach($sub_modules as $mod) @if($mod->parent_id != 0)
                <li><a class="" href="{{$mod->url_path}}">{{$mod->module_name}}</a></li>
                @endif @endforeach
            </ul>

            <ul class="nav navbar-nav navbar-right">
                
                <li class="dropdown buyer-nav-menu"></li>
                <li><a class="notification" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="#"><i class="fa fa-bell fa-1x" aria-hidden="true"></i></a>
                  <ul class="dropdown-menu notify-drop">
            <div class="notify-drop-title">
            	<div class="row">
            		<div class="col-md-6 col-sm-6 col-xs-6">Notifications (<b>2</b>)</div>
            		<div class="col-md-6 col-sm-6 col-xs-6 text-right"></div>
            	</div>
            </div>
            <!-- end notify title -->
            <!-- notify content -->
            <div class="drop-content">
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Orchestra</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Tesco</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Orchestra</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Orchestra</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Tesco</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Orchestra</p>
            		</div>
            	</li>
            	<li>
            		<div class="col-md-3 col-sm-3 col-xs-3"><div class="notify-img"><img src="https://image.flaticon.com/icons/svg/178/178199.svg" alt=""></div></div>
                    <div class="col-md-9 col-sm-9 col-xs-9 pd-l0">Price Details 
            		<p>Update Price details for Disney baby hang tag.</p>
            		<p class="time">Orchestra</p>
            		</div>
            	</li>
   
            </div>
            <div class="notify-drop-footer text-center">
            	<a href=""><i class="fa fa-eye"></i> View All</a>
            </div>
          </ul>
                </li>
                 <li><a class="notification" style="cursor: pointer;" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i style="font-size: 18px; margin-top: -1px;" class="fa fa-comment fa-1x" aria-hidden="true"></i></a></li>
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
               
                
                <li><a class="dropdown-toggle profile-dropdown-admin" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
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
                           {!! Form::email('feedback_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
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
                       {!! Form::text('admin_profile_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('admin_profile_email', 'Email', ['class' => 'form_label']) !!}
                       <div class="input-group">
                         <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                         {!! Form::email('admin_profile_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
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
                       {!! Form::textarea('admin_profile_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
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


