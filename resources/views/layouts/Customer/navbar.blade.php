<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand grid-menu" href="/customer/dashboard"><i class="fa fa-th" aria-hidden="true"></i></a>   
                    <span class="navbar-brand logo" href="#">JGA</span>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav dynamic-menu">
                        <li><a  href="{{ url('/customer/dashboard') }}">Dashboard</a></li>
                        <li><a  href="{{ url('/customer/create-order') }}">Create Order</a></li>
                        <li><a  href="{{ url('/customer/order-status') }}">Order Status</a></li>
<!--                        <li><a  href="{{ url('/customer/reorder') }}">ReOrder</a></li>-->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown buyer-nav-menu"></li>
                        <li><a class="notification" style="cursor: pointer;" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i style="font-size: 18px; margin-top: -1px;" class="fa fa-comment fa-1x" aria-hidden="true"></i></a></li>
                        <!-- Authentication Links -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::guard('web_customer')->user()->name[0] }}</span> {{ Auth::guard('web_customer')->user()->name }}<span class="caret"></span></a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a style="cursor: pointer;" onclick="getCustomerProfile({{ Auth::guard('web_customer')->user()->id }})">Profile</a></li>
									 @if(Auth::guard('web_customer')->user()->role_id == 1)
									<li><a  href="{{ url('/customer/customers') }}">Customers</a></li>
									@endif
                                      <li><a style="cursor: pointer;" data-target="#changePasswordModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">Change Password</a></li>

                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-toggle profile-dropdown-admin" href="{{ url('/customer/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                            Logout
                                </a>

                                <form id="logout-form" action="{{ url('/customer/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
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
{{-- Change password modal --}}
     <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Change Password</h4>
                </div>
                {!! Form::open(['id' => 'change_customer_password','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                    <div id="change_password_result"></div>
                    {{-- hidden id for edit functionality --}}
                     {!! Form::hidden('customer_id',$value = Auth::guard("web_customer")->user()->id, array('id'=>'merchandiser_id','class' => 'form-control input-sm')) !!}
                     <div class="form-group has-feedback">
                       {!! Form::label('current_password', 'Current Password', ['class' => 'form_label']) !!}
                       {!! Form::password('current_password', array('class' => 'form-control input-sm ','placeholder' => 'Current Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('new_password', 'New Password', ['class' => 'form_label']) !!}
                       {!! Form::password('new_password', array('class' => 'form-control input-sm ','placeholder' => 'New Password','required', 'data-minlength' => 6)) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('confirm_new_password', 'Confirm New Password', ['class' => 'form_label']) !!}
                       {!! Form::password('confirm_new_password', array('class' => 'form-control input-sm ','placeholder' => 'Confirm New Password','required','data-match' => '#new_password', 'data-match-error' => "Whoops, these don't match", 'data-minlength' => 6)) !!}
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

{{-- Edit customer modal  --}}
<div class="modal fade bd-example-modal-lg" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Customer</h4>
      </div>
      {!! Form::open(['id' => 'edit_customer_form','data-toggle' => 'validator','role' => 'form']) !!}
      {!! Form::hidden('customer_edit_id',$value = null, array('id'=>'customer_edit_id','class' => 'form-control input-sm')) !!}
          <div class="modal-body">
          <div id="customer_edit_result"></div>
            <div class="row">
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('customer_title', 'Title', ['class' => 'form_label']) !!}
                          {!! Form::text('customer_title',$value = '', array('class' => 'form-control input-sm','id' => 'customer_title','placeholder' => 'Title','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('customer_name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('customer_name',$value = '', array('class' => 'form-control input-sm','id' => 'customer_name','placeholder' => 'Name','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('customer_email', 'Email', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                        {!! Form::email('customer_email',$value = '', array('class' => 'form-control input-sm ','id' => 'customer_email','placeholder' => 'example@gmail.com','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
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