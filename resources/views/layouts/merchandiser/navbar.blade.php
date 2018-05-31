<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
		  <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
            <a class="navbar-brand grid-menu" href="#"><i class="fa fa-th" aria-hidden="true"></i></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			
          <span class="navbar-brand logo" href="#">JGA</span>
			<ul class="nav navbar-nav dynamic-menu">
             <li><a href="{{ url('/merchandiser/pending-orders') }}">Pending Orders</a></li>
             <li><a  href="{{ url('/merchandiser/past-orders') }}">Past Orders</a></li>
             </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown buyer-nav-select"></li>
                <li><a class="notification" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i style="font-size: 18px; margin-top: -1px;" class="fa fa-comment fa-1x" aria-hidden="true"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">JGA Merchandiser<span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a  onclick="getMerchandiserProfile({{ Auth::guard("web_merchandiser")->user()->id }})">Profile</a></li>
                        <li><a  data-target="#changePasswordModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">Change Password</a></li>
                    </ul>    
                       <li> <a class="dropdown-toggle profile-dropdown-admin" href="{{ url('/merchandiser/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ url('/merchandiser/logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>
                    
                </li>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
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
{{-- Snackbar html code --}}
 <div id="snackbar"><span class="snackbar_span"></span></div>
 {{-- Edit merchandiser modal --}}
 <div class="modal fade" id="editMerchandiserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Merchandiser</h4>
            </div>
            {!! Form::open(['id' => 'merchandiser_profile_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                 {{-- hidden id for edit functionality --}}
                  {!! Form::hidden('merchandiser_profile_id',$value = null, array('id'=>'merchandiser_profile_id','class' => 'form-control input-sm')) !!}
                  <div id="edit_merchandiser_result"></div>
                    <div class="form-group has-feedback">
                      {!! Form::label('merchandiser_profile_name', 'Name', ['class' => 'form_label']) !!}
                      {!! Form::text('merchandiser_profile_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                      {!! Form::label('merchandiser_profile_email', 'Email', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                        {!! Form::email('merchandiser_profile_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                      {!! Form::label('merchandiser_profile_phone', 'Phone', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                        {!! Form::text('merchandiser_profile_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                     </div>
                     <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                      {!! Form::label('merchandiser_profile_address', 'Address', ['class' => 'form_label']) !!}
                      {!! Form::textarea('merchandiser_profile_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                       {!! Form::label('merchandiser_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                       {{  Form::select('merchandiser_buyer',(['' => '-- Select Buyer --']+ $buyers),'',array('class' => 'form-control input-sm','id'=>'merchandiser_buyer','required')) }}
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
                {!! Form::open(['id' => 'change_merchandiser_password','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                    <div id="change_password_result"></div>
                    {{-- hidden id for edit functionality --}}
                     {!! Form::hidden('merchandiser_id',$value = Auth::guard("web_merchandiser")->user()->id, array('id'=>'merchandiser_id','class' => 'form-control input-sm')) !!}
                     <div class="form-group has-feedback">
                       {!! Form::label('current_password', 'Current Password', ['class' => 'form_label']) !!}
                       {!! Form::text('current_password',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Current Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('new_password', 'New Password', ['class' => 'form_label']) !!}
                       {!! Form::text('new_password',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'New Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('confirm_new_password', 'Confirm New Password', ['class' => 'form_label']) !!}
                       {!! Form::text('confirm_new_password',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Confirm New Password','required','data-match' => '#new_password', 'data-match-error' => "Whoops, these don't match", 'data-minlength' => 6)) !!}
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