@extends('layouts.Erp.app')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
	.dateGap
	{
	    padding: 2.6%;
		background: transparent;
		border: none;
		border-bottom: 1px solid teal;
		-webkit-box-shadow: none;
		box-shadow: none;
		border-radius: 0;
	}
	.setMarginTop4{margin-top: 4%;}
	.setMarginTop2{margin-top: 2%;}
	.table-responsive{height: -webkit-fill-available;}
	.::-webkit-input-placeholder {
	font-size: 13px;
	}
	::-moz-placeholder {
	font-size: 13px;
	}
	:-ms-input-placeholder {
	font-size: 13px;
	}
	::placeholder {
	font-size: 13px;
	}
</style>

@section('content')
	<div class="container-fluid setMarginTop4">
		<h5>Set the date interval</h5>
		<div class="row">
			<div class="col-md-12">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<input class="dateGap" type="text" id="txtFrom" placeholder="Start Date" />
						</td>
						<td>
							&emsp;TO&emsp;
						</td>
						<td>
							<input class="dateGap" type="text" id="txtTo" placeholder="End Date" />
						</td>
						<td>
							&emsp;
						</td>
					</tr>
				</table>
			</div>
		</div>
		

		<div class="row setMarginTop2">
			<div class="col-md-12"> 
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								 <td colspan="5">Level 1 Filter</td>
							</tr>
						</thead>
						<tbody>
						
							<tr>
								<td>
									{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $template,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $season,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $country,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
								</td>
								<td>
									<button class="btn btn-primary" id="filter1">Filter</button>
								</td>
							</tr>
							<tr>
								 <td colspan="5">Level 2 Filter</td>
							</tr>
							<tr>
								<td>
									{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $template,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $season,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
								</td>
								<td>
									{!! Form::select('sel_1[]', $country,null,['class' => 'selectpicker','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
								</td>
								<td>
									<button class="btn btn-primary" id="filter1">Filter</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection 


@section('jqueryIncludes')
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