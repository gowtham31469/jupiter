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
    <link rel="stylesheet" href="{{ asset('bootstrap-3.3.7-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/DataTables-1.10.16/css/dataTables.bootstrap.min.css') }}">
    <link href="{{ asset('font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Erp/custom.css') }}">
</head>
<body class="wallpaper">
    <div class="container-fluid">
        <div class="row">
        @if (Auth::guard("web_erp_user")->user())
        @include('layouts.Erp.navbar')
        @elseif(!Auth::guest())
            <script type="text/javascript">
                window.location = "{{ url('/admin/login') }}";
            </script>
        @endif
        @yield('content')
        </div>
    </div>
    <script>
    var base_url = "<?php echo  url('/') ?>";
    </script>
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('bootstrap-3.3.7-dist/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="http://cdn.jsdelivr.net/bootstrap.datatables/0.1/js/datatables.js"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    @yield('jqueryIncludes')
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script>
      
    </script>
</body>
</html>
