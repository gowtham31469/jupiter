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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.min.css">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			@if (Auth::guard("web_merchandiser")->user()) @include('layouts.merchandiser.navbar') @elseif(!Auth::guest())
			<script type="text/javascript">
				window.location = "{{ url('/merchandiser/login') }}";

			</script>
			@endif @yield('content')
		</div>
	</div>
	<script>
		var base_url = "<?php echo  url('/') ?>";

	</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="http://cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script src="http://cdn.jsdelivr.net/bootstrap.datatables/0.1/js/datatables.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.js"></script>
	<script src="{{ asset('js/dropzone.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>

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
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	@yield('jqueryIncludes')
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script>
		$('.logout-button').click(function() {
			localStorage.clear();
		});

		$(document).ready(function() {
			$('#buyer-select').selectpicker({
				style: 'btn-default',
				size: 4
			});
			var $tableSel = $('#datatable-example-2');
			$tableSel.DataTable({
				"pageLength": 25,
				initComplete: function() {
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

					//For Custom Filters
					this.api().columns().every(function(i) {
						var column = this;
						if (i === 0 || i === 2 || i === 6) {
							var select;
							switch (i) {
								case 0:
									select = $('#uvcNumber')
									break;
								case 2:
									select = $('#supplierName')
									break;
								case 6:
									select = $('#selectStatus')
									break;
							}
							select.on('change', function() {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);

								column
									.search(val ? '^' + val + '$' : '', true, false)
									.draw();
							});
							select.append('<option value=""> </option>');
							column.data().unique().sort().each(function(d, j) {
								cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
								select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
							});
						}

					});


				}
			});

			$("#datatable-example-2_filter input").addClass('form-control input-sm');
			$("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
			$('#datatable-example-2_length select').addClass('form-control input-sm');
			$("#reset").on("click", function() {
				$('#uvcNumber').val('').change();
				$('#supplierName').val('').change();
				$('#selectStatus').val('').change();
				$.fn.dataTableExt.afnFiltering.length = 0;
				$tableSel.dataTable().fnDraw();
				$('input[name="daterange"]').val('Filter by Order Date');
				$('input[name="daterange"]').datepicker("refresh");
				$('.selectpicker').selectpicker('val', 1);
				$('.selectpicker').selectpicker('refresh');

			});

			$('.selectpicker').selectpicker();


			$('input[name="daterange"]').daterangepicker({

				},
				function(start, end, label) {
					// alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
					filterByDate(4, start, end); // We call our filter function
					$tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
				});

			$('input[name="daterange"]').val('Filter by Order Date');


			// Clear the filter. Unlike normal filters in Datatables,
			// custom filters need to be removed from the afnFiltering array.
			$('.cancelBtn').on('click', function(e) {
				e.preventDefault();
				$.fn.dataTableExt.afnFiltering.length = 0;
				$tableSel.dataTable().fnDraw();
			});

		});

		function showFilter() {
			$('.filter-window').toggle();
		}

		/* Our main filter function
		 * We pass the column location, the start date, and the end date
		 */
		var filterByDate = function(column, startDate, endDate) {
			// Custom filter syntax requires pushing the new filter to the global filter array
			$.fn.dataTableExt.afnFiltering.push(
				function(oSettings, aData, iDataIndex) {
					var rowDate = normalizeDate(aData[column]),
						start = normalizeDate(startDate),
						end = normalizeDate(endDate);

					// If our date from the row is between the start and end
					if (start <= rowDate && rowDate <= end) {
						return true;
					} else if (rowDate >= start && end === '' && start !== '') {
						return true;
					} else if (rowDate <= end && start === '' && end !== '') {
						return true;
					} else {
						return false;
					}
				}
			);
		};

		// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
		var normalizeDate = function(dateString) {
			var date = new Date(dateString);
			var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
			return normalized;
		}
		$(function() {
			var url = window.location;
			$('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
			$('.dynamic-menu a').filter(function() {
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
			options: [{}]
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
