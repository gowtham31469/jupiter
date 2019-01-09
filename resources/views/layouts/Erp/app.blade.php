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
</style>
</head>

<body>
 <div class="container-fluid">
        <div class="row">
        @if (Auth::guard("erpuser")->user()) @include('layouts.Erp.navbar')@yield('navbar')
			@include('layouts.Erp.profile-modal')
        @elseif(!Auth::guest())
            <script type="text/javascript">
                customer  window.location = "{{ url('/erp/login') }}";
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
<script src="{{ asset('js/jquery.easy-autocomplete.min.js') }}"></script><script src="{{ asset('js/toastr.js') }}"></script>
	<!-- For Data Manipulation   -->
	<script src="{{ asset('js/Erp/store.js') }}"></script>
	<script src="{{ asset('js/Erp/update.js') }}"></script>
	<script src="{{ asset('js/Erp/userProfile.js') }}"></script>
	<script src="{{ asset('js/Erp/job-card-manager.js') }}"></script>
	<script>
		var base_url = "<?php echo  url('/') ?>";
		function showSnackbar() {
			try {
				/** [ Get the snackbar DIV ] */
				var x = document.getElementById("snackbar")

				/** [ Add the "show" class to DIV ] */
				x.className = "show";

				/** [  After 3 seconds, remove the show class from DIV ] */
				setTimeout(function() {
					x.className = x.className.replace("show", "");
				}, 3000);
			} catch (e) {
				console.log("Error:" + e)
			}
		}
		
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip(); 
			$('.dropdown-toggle').dropdown()
		});
	</script>
@yield('jqueryIncludes')
	
</body>

</html>