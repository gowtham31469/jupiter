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
    
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqtree/1.4.3/jqtree.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/1.3.1/css/toastr.css" />
	<!-- Bootstrap datepicker -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.min.css">

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
    
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>

	<script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqtree/1.4.3/tree.jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

	<!-- Bootstrap Date-Picker Plugin -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.js" type="text/javascript"></script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

	<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/1.3.1/js/toastr.js"></script>
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