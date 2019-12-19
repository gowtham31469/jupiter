@extends('layouts.Erp.app')
{{-- <!doctype html> --}}

{{-- <html lang="en"> --}}

{{-- <head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">

	<!-- <link rel="stylesheet" href="./css/style.css"> --> --}}

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<style type="text/css">
		.filter1{right: 5%;position: absolute;}
		.dateGap{padding: 2.6%;    background: transparent;
    border: none;
    border-bottom: 1px solid teal;
    -webkit-box-shadow: none;
    box-shadow: none;
    border-radius: 0;}
	</style>
{{-- </head> --}}

{{-- <body> --}}
@section('navbar')
<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{ route('jobcard-manager') }}">Create Jobcard</a></li>
	<li><a href="{{ route('jobcards-list') }}"  class="active">Jobcards List</a></li>
</ul>
@endsection
@section('content')
	<div class="container">
		<hr>
		
		<hr>
		<div class="row">
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $template,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $season,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $country,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<br>
				<button class="btn btn-primary filter1" id="filter1">filter 1</button>
				<br>
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $template,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $season,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
			</div>
			<div class="col-md-3">
				{!! Form::select('sel_1[]', $country,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
			</div>
		</div>


	</div>

	<script
	src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript">
		$(function () {
			$("#txtFrom").datepicker({
				numberOfMonths: 3,
				defaultDate: '-1m',
				dateFormat: 'yy-mm-dd',
				onSelect: function (selected) {
					var dt = new Date(selected);
					dt.setDate(dt.getDate() + 1);
					$("#txtTo").datepicker("option", "minDate", dt);
				}
			});
			$("#txtTo").datepicker({
				numberOfMonths: 3,
				defaultDate: '-1m',
				dateFormat: 'yy-mm-dd',
				onSelect: function (selected) {
					var dt = new Date(selected);
					dt.setDate(dt.getDate() - 1);
					$("#txtFrom").datepicker("option", "maxDate", dt);
				}
			});
		});
	</script>
	<script type="text/javascript">

		$(document).ready(function($) {

			$(document).on('click', '#filter1', function(event) {
				event.preventDefault();
				/* Act on the event */

				console.log($("#buyer").val())
				console.log($("#country").val())
				console.log($("#season").val())
				console.log($("#template").val())

				$.ajax({
					type : 'get',
					url : '/level1Filter',
					data:{
						'buyer': $("#buyer").val(),
						'country': $("#country").val(),
						'season': $("#season").val(),
						'template': $("#template").val(),
					},

					success:function(data)
					{

					}
				});
			});

		});

	</script>
	@endsection
{{-- </body>

</html> --}}