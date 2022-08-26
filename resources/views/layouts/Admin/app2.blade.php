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
                <li class="dropdown season_code-nav-menu"></li>
                <li class="dropdown year-nav-menu"></li>
                <li class="dropdown buyer-nav-menu"></li>
                 <li><a class="notification" style="cursor: pointer;" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i style="font-size: 18px; margin-top: -1px;" class="fa fa-comment fa-1x" aria-hidden="true"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome {{ Auth::user()->name }}   <span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a style="cursor: pointer;" onclick="getAdminProfile({{ Auth::user()->id }})">Profile</a></li>
                        <li><a style="cursor: pointer;" data-target="#changePasswordModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">Change Password</a></li>
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
        @elseif(!Auth::guest())
            <script type="text/javascript">
              window.location = "{{ url('/admin/login') }}";
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
@yield('jqueryIncludes')
	
</body>

</html>