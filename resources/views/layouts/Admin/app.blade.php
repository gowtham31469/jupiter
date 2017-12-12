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
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqtree/1.4.3/jqtree.min.css" rel="stylesheet" /> 
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
      <!-- Bootstrap datepicker -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.standalone.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
        @if (Auth::guard("web")->user()) @include('layouts.Admin.navbar')
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
    <script src="http://cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.0/js/jquery.dataTables.min.js"></script>
    <script src="http://cdn.jsdelivr.net/bootstrap.datatables/0.1/js/datatables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
    <script src="{{ asset('js/dropzone.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/Admin/customer.js') }}"></script>
    <script src="{{ asset('js/Admin/users.js') }}"></script>
    <script src="{{ asset('js/Admin/buyers.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/jsbarcode/3.6.0/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqtree/1.4.3/tree.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.js"></script>
    
      <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.js" type="text/javascript"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
    @yield('jqueryIncludes')
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script>
        $('.logout-button').click(function() 
        {
            localStorage.clear();
        });
        
        $(document).ready(function() {
            $('#buyer-select').selectpicker({
               style: 'btn-default',
               size: 4
            });
   
            $('#example').DataTable();
            $("#example_filter input").addClass('form-control input-sm');
            $("#example_filter input").attr('placeholder', 'Data Search');
            $('#datatable-example-2_length select').addClass('form-control input-sm');

        });
        $(function () {
        var url = window.location;
        $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
        $('.dynamic-menu a').filter(function () {
            return this.href == url;
        }).parent('li').addClass('current-page').find('a').addClass('active');
    });

        var myDoughnutChart = new Chart(myChart, {
            type: 'doughnut',
            data: data = {
                datasets: [{
                    data: [1600, 2230],
                    backgroundColor: [
                        '#facc55', '#5ac0c0', '#e74c3c'
                    ],
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Orchestra',
                    'Enrich',
                ]
            },
            options: [{
            }]
        });
        var myDoughnutChart = new Chart(myChart1, {
            type: 'doughnut',
            data: data = {
                datasets: [{
                    data: [1600, 2230],
                    backgroundColor: [
                        '#facc55', '#5ac0c0', '#e74c3c'
                    ],
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Orchestra',
                    'Enrich',
                ]
            },
            options: [{

            }]
        });
        var myDoughnutChart = new Chart(myChart2, {
            type: 'doughnut',
            data: data = {
                datasets: [{
                    data: [1600, 2230],
                    backgroundColor: [
                        '#facc55', '#5ac0c0', '#e74c3c'
                    ],
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Orchestra',
                    'Enrich',
                ]
            },
            options: [{

            }]
        });
//         $('.buyer-select').select2({
//            placeholder: "Buyer Listing",
//            allowClear: true
//        });
    </script>
    @yield('jqueryIncludes')
</body>
</html>
