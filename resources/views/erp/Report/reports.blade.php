@extends('layouts.Erp.app')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

<style type="text/css">
	input:focus{
	outline: none !important;
	}
	.card {
	box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
	transition: 0.3s;
	height: 20%;
	min-height: 80px;
	margin-bottom: 0px;
	border-radius: 0px;
	margin-top: 0px;
	}
	#reportTitle{margin-left: 34%;}
	.mrgBtm{margin-bottom: 5%;}
	.card:hover {
	box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
	}
	#reportTable_wrapper{margin-bottom: 1%;}
	.dt-buttons{margin-left: 2%;}
	.dateGap
	{
		background: transparent;
		border: none;
		border-bottom: 1px solid teal;
		-webkit-box-shadow: none;
		box-shadow: none;
		border-radius: 0;
	}
	.setMarginTop4{margin-top: 4%;width: 97%;}
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
	.table-responsive{height: 50%;}
	h3{color: black !important;}
	.more{
		margin-right: 16%;
		margin-top: 12%;
		float: right;
	}
	#filter2,#filter1{margin-top: 34%;height: 29px;}
	.btn .caret, .btn-group>.btn:first-child {height: 30px!important;}
	.bootstrap-select>.dropdown-toggle.bs-placeholder{height: 30px!important;}
	.filter-option{font-size: smaller !important;}
	.checklist1{min-height: 250px;cursor: pointer;max-height: 350px;overflow: auto;}
	.checklist2{min-height: 250px;cursor: pointer;max-height: 350px;overflow: auto;}
	.checkbox-inline i{margin-left: 15px;}
	#filter3{    
	margin-top: 32%;
    height: 29px;}
	#checklist li{padding: .4%;font: 15px;}
	#reportTable{
		min-width: 100%;
		font-size: 12px; 
		font-weight: 400;}
		.tableContainer{width: 100%;overflow: auto;}
		.roleSelection{display: inline-block;}
		.filterTable td{max-width: 120px!important;}
		.reportSec{margin-left: -0.1%;margin-right: 0%;}
		.connected-sortable {
			list-style: none;
			width: 100%;
			padding-inline-start: unset;
		}

		li.draggable-item {
			width: 100%;
			padding: 2.5%;
			margin-bottom: 1%;
			background-color: #f5f5f5;
			-webkit-transition: transform .25s ease-in-out;
			-moz-transition: transform .25s ease-in-out;
			-o-transition: transform .25s ease-in-out;
			transition: transform .25s ease-in-out;

			-webkit-transition: box-shadow .25s ease-in-out;
			-moz-transition: box-shadow .25s ease-in-out;
			-o-transition: box-shadow .25s ease-in-out;
			transition: box-shadow .25s ease-in-out;
			&:hover {
				cursor: pointer;
				background-color: #eaeaea;
			}
		}
		li.draggable-item.ui-sortable-helper {
			background-color: #e5e5e5;
			-webkit-box-shadow: 0 0 8px rgba(53,41,41, .8);
			-moz-box-shadow: 0 0 8px rgba(53,41,41, .8);
			box-shadow: 0 0 8px rgba(53,41,41, .8);
			transform: scale(1.015);
			z-index: 100;
		}
		li.draggable-item.ui-sortable-placeholder {
			background-color: #ddd;
			-moz-box-shadow:    inset 0 0 10px #000000;
			-webkit-box-shadow: inset 0 0 10px #000000;
			box-shadow:         inset 0 0 10px #000000;
		}
		table {
			background: unset!important; 
		}

		@-webkit-keyframes blinker {
			from {opacity: 1.0;}
			to {opacity: 0.0;}
		}
		.blink{
			text-decoration: blink;
			-webkit-animation-name: blinker;
			-webkit-animation-duration: 0.6s;
			-webkit-animation-iteration-count:infinite;
			-webkit-animation-timing-function:ease-in-out;
			-webkit-animation-direction: alternate;
			color: red;	
		}
		.pading1{padding: 1%;}
		.clrGrey{
				color:grey;  
				border: 1px solid lightgray;
				right: -34%;
				top: 0;
				padding: 3.5%;
				margin-left: -19%;
				position: absolute;
			}
		.paddingSmaller{padding: 0.5%;}
		.bordr1px{border: 1px solid;}
		.togBtn{
			font-size: 20px;
			border: 1px solid lightgray;
			padding: 5%;
			cursor: pointer;
			position: absolute;
			right: 0;"
		}
		.dataTables_wrapper.no-footer .dataTables_scrollBody {
		border-bottom:1px solid #ddd !important; 
		}
		.dataTables_scrollBody thead tr
		{visibility:collapse;}


		#wrapper {
  /*background: #ccc;*/
  /*overflow: hidden;*/
  transition: height 300ms;
  height: 230px;
  /*max-height: 230px !important;*/
  /*height: unset !important;*/
}
</style>


<div class="container-fluid setMarginTop4">
	<h4 class="text-center">REPORT GENERATION</h4>
	<div class="row card">
		<div class="col-md-5">
			<h5 id="dateLabel">Set the date interval</h5>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input class="dateGap" type="text" id="txtFrom" placeholder="Start date" />
					</td>
					<td>
						&emsp;TO&emsp;
					</td>
					<td>
						<input class="dateGap" type="text" id="txtTo" placeholder="End date" />
					</td>
					<td>
						&emsp;
					</td>
				</tr>
			</table>
		</div>
		<div class="col-md-4">
			<h5>Select one of the following </h5>
			@foreach($allActions as $key=> $action)
			<label><input type="radio" class="roleSelection" name="roleSelection" data-target="{{ $action_as_id[$key] }}" value="{{ $action  }}">&nbsp; {{ $action }} &emsp;</label>
			@endforeach
		</div>
		<div class="col-md-3">
			<h5 class="select_Status hidden">Select status</h5>

			{!! Form::select('role_Status[]', $po,null,['class' => 'selectpicker all_status _poMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ PO Mngr ]','id'=>'_poMgr']) !!}

			{!! Form::select('role_Status[]', $jc,null,['class' => 'selectpicker all_status _jcMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ JC Mngr ]','id'=>'_jcMgr']) !!}

			{!! Form::select('role_Status[]', $order,null,['class' => 'selectpicker all_status _order hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ Order ]','id'=>'_order']) !!}

			{!! Form::select('role_Status[]', $invoice,null,['class' => 'selectpicker all_status _invoice hidden','required','data-size'=>"10",'data-actions-box'=>"true","title"=>'Select Status [ Invoice ]','id'=>'_invoice']) !!}
		</div>
	</div>

	<div class="row setMarginTop2  card open" id="wrapper">
		<div class="col-md-12" id="list">
			<div class="row paddingSmaller">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							<label>Buyer</label>
							{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
						</div>
						<div class="col-md-3">
							<label>Template</label>
							{!! Form::select('sel_1[]',$templates,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
						</div>
						<div class="col-md-3">
							<label>Season</label>
							{!! Form::select('sel_1[]',$seasons,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
						</div>
						<div class="col-md-3">
							<label>Country</label>
							{!! Form::select('sel_1[]',$countries,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class="btn btn-default input-sm" id="filter1">OK</button></div>
			</div>
			<div class="row level2filter hidden paddingSmaller">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							<label>Customer</label>
							<select id="customer" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Customer" multiple></select>
						</div>
						<div class="col-md-3">
							<label>Material</label>
							<select id="material" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Material" multiple></select>
						</div>
						<div class="col-md-3">
							<label>Style</label>
							<select id="style" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Style" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<label>Mat-Sub</label>
							<select id="mat_sub" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Mat-sub" multiple data-live-search="true"></select>
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class="btn btn-default input-sm" id="filter2">OK</button></div>
			</div>
			<div class="row level3filter hidden paddingSmaller">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							<label>Reference</label>
							<select id="reference" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Reference" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<label>PO</label>
							<select id="po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select PO" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<label>Buyer PO</label>
							<select id="buyer_po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Buyer PO" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<label>OCN</label>
							<select id="ocn" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select OCN" multiple data-live-search="true"></select>
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class=" btn btn-primary btn-sm" id="filter3">Get Report</button></div>
			</div>
		</div>
	</div>

	<div class="row ReportSection hidden reportSec">
		<hr>  
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-9">
					<h4 class="text-center" id="reportTitle">Consolidated Data</h4>
				</div>
				<div class="col-md-2">
					<h4 class="text-center">
						<a href="" id="moreOtions" class="clrGrey" >More options</a>
						{{-- <a href="" id="gen_report" class="clrGrey1" >Generate Report</a> --}}
					</h4>
				</div>
				<div class="col-md-1" id="toggleBtn" data-icon="down">
					<i class="fa fa-angle-down togBtn" aria-hidden="true" data-toggle="collapse" data-target="#filter-panel" id="toggleFa">
					</i>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<table class="table table-hover table-striped table-bordered dataTable no-footer" id="reportTable">
					<thead>
						<tr id="all_fields">
							<th>S.No</th><th>Order.Dt</th><th>ErpId</th><th>Customer</th><th>Material</th><th>Style</th><th>PO</th><th>OCN</th><th>Reference</th><th>OCN</th><th>Reference</th><th>Reason</th><th>Reapprove.Dt</th>
						</tr>
					</thead>
					<tbody id="all_data">
					</tbody>
				</table>
			</div>
		</div>

		<div id="optionsModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-center">Drag & Drop To Add/Remove Fields From Table</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<h4 class="modal-title text-center">Available Fields</h4>
								<ul class="connected-sortable droppable-area2 checklist1">
								</ul>
							</div>
							<div class="col-md-6">
								<h4 class="modal-title text-center">Fields Currently Displayed</h4>
								<ul class="connected-sortable droppable-area1 checklist2">
								</ul>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn-primary" id="update_table">Update</button>
						<button type="button" class="btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	{{-- Snackbar html code --}}
	<div id="snackbar">
		<span class="snackbar_span"></span>
</div>


@section('jqueryIncludes')

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>


		<script type="text/javascript">
		$(function() {
		var b = $("#toggleBtn");
		var w = $("#wrapper");
		var l = $("#list");

		w.height(l.outerHeight(true));

		b.click(function() {

		if(w.hasClass('open')) {
		w.removeClass('open');
		w.height(0);
		w.css('overflow', 'hidden');
		} else {
		w.addClass('open');
		w.height(l.outerHeight(true));
		}

		});
		});
		</script>

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
						$(".filterLevel2").selectpicker('deselectAll');
						$(".filterLevel2").find('option').remove();
						$(".filterLevel3").selectpicker('deselectAll');
						$(".filterLevel3").find('option').remove();
						$(".filterLevel2").selectpicker('refresh');
						$(".filterLevel3").selectpicker('refresh');
						
						$("#filter1").removeClass('hidden')
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
						$(".filterLevel2").selectpicker('deselectAll');
						$(".filterLevel2").find('option').remove();
						$(".filterLevel3").selectpicker('deselectAll');
						$(".filterLevel3").find('option').remove();
						$(".filterLevel2").selectpicker('refresh');
						$(".filterLevel3").selectpicker('refresh');
						$("#filter1").removeClass('hidden');
					}
				});
			});
	</script>

    <script type="text/javascript">
			$(document).ready(function($) {
				$( init );
				function init() {
					$( ".droppable-area1, .droppable-area2" ).sortable({
						connectWith: ".connected-sortable",
						stack: '.connected-sortable ul'
					}).disableSelection();
				}

				$('input[type=radio][name=roleSelection]').change(function() {
					$(".select_Status").removeClass('hidden')
					$(".all_status").each(function(index, el) {
						$(this).addClass('hidden')
						$(this).val('')
					});
					$('.selectpicker').selectpicker('refresh');
					$('.'+$(this).data('target')).addClass('open').removeClass('hidden')
					$("#moreOtions").addClass('hidden')
					$("#filter-panel").removeClass('collapse')
				});

				$(document).on('click', '#filter1', function(event) {
					event.preventDefault();
					triggerLevel1()
				});

				$(document).on('click', '#filter2', function(event) {
					triggerLevel2()
				});

				$(document).on('click', '#filter3,#gen_report', function(event) {
					event.preventDefault();
					triggerLevel3()
				});

				$('.filterLevel1').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
					e.preventDefault();
					/* Act on the event */
					$(".filterLevel2").selectpicker('deselectAll');
					$(".filterLevel3").selectpicker('deselectAll');

					if($("#txtFrom").val().length > 0 && $("#txtTo").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
					{
						triggerLevel1()
					}
				});

				$('.filterLevel2').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
					e.preventDefault();
					/* Act on the event */
					$(".filterLevel3").selectpicker('deselectAll');
					if($("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
					{
						triggerLevel2()
					}
				});

				function triggerLevel1()
				{	
					console.log($('input[name=roleSelection]:checked').val())
					$('.selectpicker').removeClass('open');
					/* Act on the event */
					if($("#txtFrom").val().length > 0 && $("#txtTo").val().length > 0)
					{
						if($("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
						{
							countries = [];
							seasons = [];
							templates = [];

							$.each($("#country option:selected"), function(){            
								countries.push($(this).html());
							});

							$.each($("#season option:selected"), function(){            
								seasons.push($(this).html());
							});

							$.each($("#template option:selected"), function(){            
								templates.push($(this).html());
							});

							$.ajax({
								type : 'get',
								url : '/level1Filter',
								data:{
									'buyer': $("#buyer").val(),
									'country': countries,
									'season': seasons,
									'template': templates,
									'date_start' : $("#txtFrom").val(),
									'date_end' : $("#txtTo").val()
								},

								success:function(data)
								{
									$('#customer').html('')
									$('#material').html('')
									$('#style').html('')
									$('#mat_sub').html('')

									if(data.customers.length == 0 || data.result.length == 0)
									{
										$('.snackbar_span').html("No results could be fetched for this combination.");
											showSnackbar();
									}
									else{
											$.each(data.customers, function(key, value) {   
										$('#customer')
										.append($("<option></option>")
											.attr("value",key)
											.text(value)); 
									});

									var temp1=[]
									$.each(data.result, function(key, value) { 

										if($.inArray( value['material'], temp1 )==-1)	
										{	
											$('#material').append($("<option></option>")
											.text(value['material']));
										}
										 	temp1.push(value['material'])
									});
									
									var temp2=[]
									$.each(data.result, function(key, value) 
									{ 
										if($.inArray( value['style'], temp2 )==-1)	
										{	
										$('#style').append($("<option></option>")
										.text(value['style'])); 
										}
										temp2.push(value['style'])
									});

									var temp3=[]
									$.each(data.result, function(key, value) 
									{ 
										if($.inArray( value['mat_sub'], temp3 )==-1)	
										{
										$('#mat_sub').append($("<option value='"+value['mat_sub']+"'></option>")
											.text(value['mat_sub'])); 
										}
										temp3.push(value['mat_sub'])
									});

									$('.selectpicker').selectpicker('refresh');
									$(".level2filter").removeClass('hidden')
									$("#filter1").addClass('hidden')

									}
								}
							});
						}
						else
						{
							$('.snackbar_span').html("Please select atleast one value from each of the select boxes.");
							showSnackbar();
						}
					}
					else{
						$('.snackbar_span').html("Please set the date interval.");
						showSnackbar();
					}
				}

				function triggerLevel2()
				{
					$('.selectpicker').removeClass('open');

					if($("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
					{
						material = [];
						style = [];
						mat_sub = [];

						$.each($("#material option:selected"), function(){            
							material.push($(this).html());
						});

						$.each($("#style option:selected"), function(){            
							style.push($(this).html());
						});

						$.each($("#mat_sub option:selected"), function(){            
							mat_sub.push($(this).val().replace('&amp;','&'));
						});

						$.ajax({
							type : 'get',
							url : 'level2Filter',
							data:{
								'buyer': $("#buyer").val(),
								'materials': material,
								'styles': style,
								'mat_subs': mat_sub,
								'customers' : $("#customer").val(),
								'country': countries,
								'season': seasons,
								'template': templates,
								'date_start' : $("#txtFrom").val(),
								'date_end' : $("#txtTo").val()
							},

							success:function(data)
							{
								$('#reference').html('')
								$('#po').html('')
								$('#buyer_po').html('')
								$('#ocn').html('')

								if(data.length > 0)
								{
								var temp4=[]	
								$.each(data, function(key, value) 
								{ 
									if($.inArray( value['reference'], temp4 )==-1)	
									{
									$('#reference').append($("<option></option>")
										.text(value['reference'])); 
									}
									temp4.push(value['reference'])
								});

								var temp5=[]
								$.each(data, function(key, value)
								 { 
									if($.inArray( value['po'], temp5 )==-1)	
									{
									$('#po').append($("<option></option>")
										.text(value['po'])); 
									}
									temp5.push(value['po'])
								});

								var temp6=[]
								$.each(data, function(key, value)
								 { 
									if($.inArray( value['buyer_po'], temp6 )==-1)	
									{
									$('#buyer_po').append($("<option></option>")
										.text(value['buyer_po'])); 
									}
									temp6.push(value['buyer_po'])
								});

								var temp7=[]
								$.each(data, function(key, value)
								 { 
								 	if($.inArray( value['ocn'], temp7 )==-1)	
									{
									$('#ocn').append($("<option></option>")
										.text(value['ocn'])); 
									}
									temp7.push(value['ocn'])
								});

								$('.selectpicker').selectpicker('refresh');
								$(".level3filter").removeClass('hidden')
								$("#filter2").addClass('hidden')
								}
								else{
									$('.snackbar_span').html("No results could be fetched for this combination.");
										showSnackbar();
								}
								
							}

						});
					}
					else
					{
						$('.snackbar_span').html("Please select atleast one value from each of the select boxes.");
						showSnackbar();
					}
				}

				function triggerLevel3()
				{
					$('.selectpicker').removeClass('open');
					if($(".roleSelection").is(':checked'))
					{
						if($('#'+$('input[name=roleSelection]:checked').data('target')).val().length > 0)
						{
							if($("#reference").val().length > 0 && $("#po").val().length > 0 && $("#buyer_po").val().length > 0 && $("#ocn").val().length > 0 && $("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
							{
								$.ajax({
									type : 'get',
									url : '/getTableFields',
									data:{
										'role' : $('input[name=roleSelection]:checked').val(),
										'status' : $('#'+$('input[name=roleSelection]:checked').data('target')).val(),
									},
									success:function(data)
									{	
										if(data.Default.length > 0)
										{
											create_table(data.Default,data.Extra)
										}
										else{
											$('.snackbar_span').html("No values found for this combination !!.");
											showSnackbar();
										}
									}
								});
							}
							else
							{
								$('.snackbar_span').html("Please select atleast one value from each of the select boxes.");
								showSnackbar();
							}
						}
						else{
							$('.snackbar_span').html("Please select the status.");
							showSnackbar();
						}
					}
					else{
						$('.snackbar_span').html("Please Select One Of The Radio Buttons.");
						showSnackbar();
					}
				}

				function create_table(def,extra)
				{ 
					$("#reportTable").DataTable().destroy();
					$("#all_data").empty();
					$("#all_fields").html('')
					extraFields = extra.slice();
					defaultFields = def.slice(); 

					dataSet = [];
					heading="";
					tdData="";

					reference = [];
					po = [];
					buyer_po = [];
					ocn = [];

					$.each($("#reference option:selected"), function(){            
						reference.push($(this).html());
					});

					$.each($("#po option:selected"), function(){            
						po.push($(this).html());
					});

					$.each($("#buyer_po option:selected"), function(){            
						buyer_po.push($(this).html());
					});

					$.each($("#ocn option:selected"), function(){            
						ocn.push($(this).html());
					});

					allColoumns=[]
					for (var i = 0; i < def.length ; i++)
					{
						heading+='<th>'+def[i]+'</th>'
						$("#all_fields").html(heading)
						$(".ReportSection").removeClass('hidden')
						allColoumns.push({
							data: def[i]
						});
					}

					var t = $('#reportTable').dataTable( {
						processing: true,
						serverSide: true,
						// sScrollX: '100%',	
						// order: [[ 5, "asc" ]],
						"scrollX": true,
						dom: 'lBfrtip',
						// lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
						// buttons: ['copy', 'csv', 'excel', 'pdf'],
						buttons: ['copy', 'csv','excel'],
						fixedHeader: {
							header: true,
						},
						"ajax": {
							"url": "{{ url('setUpDatatable') }}",
							"dataType": "json",
							"type": "POST",
							"data":{
								_token: "{{csrf_token()}}",
								role : $('input[name=roleSelection]:checked').val(),
								status :  $('#'+$('input[name=roleSelection]:checked').data('target')).val(),
								references : reference,
								pos : po,
								buyer_pos : buyer_po,
								ocns : ocn,
								buyer : $("#buyer").val(),
								materials : material,
								styles : style,
								mat_subs : mat_sub,
								customers : $("#customer").val(),
								country : countries,
								season : seasons,
								template : templates,
								date_start : $("#txtFrom").val(),
								date_end : $("#txtTo").val()
							}
						},
						"columns" :allColoumns
					});

					$("#filter-panel").removeClass('collapse in').addClass('collapse')
					$("#reportTitle").html("Consolidated Report [ "+$('input[name=roleSelection]:checked').val()+" ]")
					// $("#moreOtions").removeClass('disabled_now')
					$("#moreOtions").removeClass('hidden')
					$('html,body').animate({
						scrollTop: $(window).scrollTop() + 500
					});
				}

				//The default and extra fields are updated and pushed to corresponding arrays
				$(document).on('click', '#update_table', function(event) {
					event.preventDefault();

					if($("#reference").val().length > 0 && $("#po").val().length > 0 && $("#buyer_po").val().length > 0 && $("#ocn").val().length > 0 && $("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
					{
					var listItems1 = $(".checklist1 li");
					var listItems2 = $(".checklist2 li");

					if(listItems2.length > 0)
					{
						defaultFields=[]
						extraFields=[]
						listItems2.each(function(idx, vl) {
							defaultFields.push($(this).html())
						});

						listItems1.each(function(idx, vl) {
							extraFields.push($(this).html())
						});

						arr1 = defaultFields.slice(); 
						arr2 = extraFields.slice();
						$("#optionsModal").modal('hide');	
						create_table(arr1,arr2)
					}
					else{
						$('.snackbar_span').html("No fields are selected,please add some to display the table.");
					    showSnackbar();
					}
					
				}
				else{
					$('.snackbar_span').html("Please make sure all the values are selected.");
					showSnackbar();
				}
				});

				//logic to make the default and extra fields draggable
				$(document).on('click', '#moreOtions', function(event) {
					event.preventDefault();
					/* Act on the event */

					// if($("#moreOtions").hasClass('disabled_now'))
					// {
					// 	$('.snackbar_span').html("Generate report again and try to customize.");
					// 	showSnackbar();
					// }
					// else{
					def_list="";
					extra_list="";

					for (var i = 0; i < defaultFields.length ; i++)
					{
						def_list+=' <li class="draggable-item">'+defaultFields[i]+'</li>'
					}

					for (var i = 0; i < extraFields.length ; i++)
					{
						extra_list+=' <li class="draggable-item">'+extraFields[i]+'</li>'
					}

					$(".checklist1").html('')
					$(".checklist1").html(extra_list)
					$(".checklist2").html('')
					$(".checklist2").html(def_list)
					$("#optionsModal").modal();	
					// }
					
				});

				//logic to animate the expand and collapse icon
				// $(document).on('click', '#toggleBtn', function(event) {
				// 	event.preventDefault();
				// 	/* Act on the event */
				// 	if($(this).data('icon')=="up")
				// 	{
				// 		$("#toggleFa").removeClass('fa fa-angle-up').addClass('fa fa-angle-down')
				// 		$(this).data('icon',"down")
				// 	}
				// 	else
				// 	{
				// 		$("#toggleFa").removeClass('fa fa-angle-down').addClass('fa fa-angle-up')
				// 		$(this).data('icon',"up")
				// 	}
				// });
			});
	</script>
@endsection 
@endsection 