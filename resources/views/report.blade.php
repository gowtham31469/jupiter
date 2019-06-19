<!doctype html>

<html lang="en">

<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">

	<!-- <link rel="stylesheet" href="./css/style.css"> -->

	<style type="text/css">
		.filter1{right: 5%;position: absolute;}
	</style>
</head>

<body>

	<div class="container">

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
					url : '/filter_1',
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
</body>

</html>