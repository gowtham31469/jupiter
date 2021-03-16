<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="">
	<title>JGA</title>
	
	
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
	<link rel="stylesheet" href="{{ asset('css/Erp/custom.css') }}">
	<link href="{{ asset('js/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('js/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    
	    <link rel="stylesheet" href="{{ asset('css/ion-icons.min.css') }}">
	<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" />
	
	 <link href="{{ asset('css/jqtree.min.css') }}" rel="stylesheet" /> 
	<link rel="stylesheet" href="{{ asset('css/jstree.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}" />
	<!-- Bootstrap datepicker -->
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">
	  <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/easy-autocomplete.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/easy-autocomplete.themes.min.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />

<style>
	#snackbar{
		z-index: 9999;
	}	
	.navbar {
     border-radius: 0px; 
}
</style>
</head>

<body>
<div class="container-fluid">
        <div class="row">
        @if (Auth::guard("web")->user()) 
        <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand grid-menu" href="/admin/dashboard"><i class="fa fa-th" aria-hidden="true"></i></a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav dynamic-menu">
                <li><a class="" href="{{url('admin/generate-report')}}">Get Reports</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown year-nav-menu"></li>
                <li class="dropdown buyer-nav-menu"></li>
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
               
                
                <li><a class="dropdown-toggle profile-dropdown-admin" href="{{ 'admin/logout' }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                
                        <form id="logout-form" action="{{ '/admin/logout' }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

            </ul>
        </div>

  </div>
</nav>

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
        @elseif(!Auth::guest())
            <script type="text/javascript">
       customer         window.location = "{{ url('/admin/login') }}";
            </script>
        @endif
        @yield('content')
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
   	<!-- Datatables related js files  -->
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('js/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/datatables/responsive.bootstrap.min.js') }}"></script>
    
	 <script type="text/javascript" src="{{ asset('js/Chart.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>

	 <script src="{{ asset('js/JsBarcode.all.min.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/tree.jquery.min.js') }}"></script>
    <script src="{{ asset('js/jstree.min.js') }}"></script>  
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    
        <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll-ajax.js') }}" type="text/javascript"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script> -->
<script src="{{ asset('js/jquery.easy-autocomplete.min.js') }}">
	</script><script src="{{ asset('js/toastr.js') }}"></script>
	<!-- For Data Manipulation   -->
	<script src="{{ asset('js/Erp/store.js') }}"></script>
	<script src="{{ asset('js/Erp/update.js') }}"></script>
	<script src="{{ asset('js/Erp/userProfile.js') }}"></script>
	<script src="{{ asset('js/Erp/job-card-manager.js') }}"></script>
	<script src="{{ asset('js/Erp/job-card-artwork.js') }}"></script>
	<script src="{{ asset('js/Erp/print-manager.js') }}"></script>
	<script src="{{ asset('js/Erp/erp-settings.js') }}"></script>

@yield('jqueryIncludes')
	
</body>

</html>