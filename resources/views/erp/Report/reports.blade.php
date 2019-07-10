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
	.table-responsive{height: 50%;}
	h3{color: black !important;}
	.more{
		margin-right: 16%;
		margin-top: 12%;
		float: right;
	}
	.checklist1{min-height: 250px;cursor: pointer;max-height: 350px;overflow: auto;}
	.checklist2{min-height: 250px;cursor: pointer;max-height: 350px;overflow: auto;}
	.checkbox-inline i{margin-left: 15px;}
	#checklist li{padding: .4%;font: 15px;}
	#reportTable{overflow-x:auto;max-width: 90%;overflow: auto;}
	.tableContainer{width: 100%;overflow: auto;}
	.roleSelection{display: inline-block;}
	.filterTable td{max-width: 120px!important;}

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
.table-fixed thead {
  /*width: 97%;*/
}
.table-fixed tbody {
  height: 230px;
  overflow-y: auto;
  width: 100%;
}
/*.table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
  display: block;
}*/
.table-fixed thead > tr> th {
  /*float: left;*/
  /*border-bottom-width: 0;*/
}
</style>

@section('content')

<div class="container-fluid setMarginTop4">
	<div class="row">
		<div class="col-md-5">
			<h5>Set the date interval</h5>
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
				{{-- 	<td style="border: 1px solid lightgrey;padding: 0.6%; cursor:pointer;" id="toggleMe" data-target="up"></td> --}}
				</tr>
			</table>
		</div>
		<div class="col-md-4">
			<h5>Select one of the following </h5>
			@foreach($allActions as $key=> $action)
			<input type="radio" class="roleSelection" name="roleSelection" data-target="{{ $action_as_id[$key] }}" value="{{ $action  }}"> {{ $action }} &emsp;
			@endforeach
		</div>
		<div class="col-md-3">
			<h5 class="select_Status hidden">Select Status</h5>

			{!! Form::select('role_Status[]', $po,null,['class' => 'selectpicker all_status _poMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select status [ PO Mngr ]','id'=>'_poMgr']) !!}

			{!! Form::select('role_Status[]', $jc,null,['class' => 'selectpicker all_status _jcMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select status [ JC Mngr ]','id'=>'_jcMgr']) !!}

			{!! Form::select('role_Status[]', $order,null,['class' => 'selectpicker all_status _order hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select status [ Order ]','id'=>'_order']) !!}

			{!! Form::select('role_Status[]', $invoice,null,['class' => 'selectpicker all_status _invoice hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select status [ Invoice ]','id'=>'_invoice']) !!}
		</div>
	</div>

	{{-- <div class="row setMarginTop2">
		<div class="col-md-12"> 
			<div class="">
				<table class="table table-striped filterTable">
					<tbody>
						<tr class="level1filter">
							<td>Buyer</td>
							<td>Template</td>
							<td>Season</td>
							<td>Country</td>
						</tr>
						<tr class="level1filter">
							<td>
								{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
							</td>
							<td>
								{!! Form::select('sel_1[]',array_unique(App\History::all()->sortBy('template')->pluck('template')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
							</td>
							<td>
								{!! Form::select('sel_1[]', array_unique(App\History::all()->sortBy('season')->pluck('season')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
							</td>
							<td>
								{!! Form::select('sel_1[]',array_unique(App\History::all()->sortBy('country')->pluck('country')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
							</td>
							<td>
								<button class="btn btn-primary" id="filter1">Next</button>
							</td>
						</tr>
						<tr class="level2filter hidden">
							<td>Customer</td>
							<td>Material</td>
							<td>Style</td>
							<td>Mat-Sub</td>
						</tr>
						<tr class="level2filter hidden">
							<td>
								<select id="customer" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Customer" multiple></select>
							</td>
							<td>

								<select id="material" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Material" multiple></select>

							</td>
							<td>
								<select id="style" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Style" multiple data-live-search="true"></select>
							</td>
							<td>
								<select id="mat_sub" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Mat-Sub" multiple data-live-search="true"></select>
							</td>
							<td>
								<button class="btn btn-primary" id="filter2">Next</button>
							</td>
						</tr>
						<tr class="level3filter hidden">
							<td>Reference</td>
							<td>PO</td>
							<td>Buyer PO</td>
							<td>OCN</td>
						</tr>
						<tr class="level3filter hidden">
							<td>
								<select id="reference" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Reference" multiple data-live-search="true"></select>
							</td>
							<td>

								<select id="po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select PO" multiple data-live-search="true"></select>

							</td>
							<td>
								<select id="buyer_po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Buyer PO" multiple data-live-search="true"></select>
							</td>
							<td>
								<select id="ocn" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select OCN" multiple data-live-search="true"></select>
							</td>
							<td>
								<button class="btn btn-primary" id="filter3">Make</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div> --}}

	<div class="row setMarginTop2 filter-panel" id="filter-panel" style="padding: 1%;">
		<div class="col-md-12" style="border: 1px solid lightgrey;">
			<div class="row" style="padding: 1%;">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							{!! Form::select('sel_1[]', $buyers,null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
						</div>
						<div class="col-md-3">
							{!! Form::select('sel_1[]',array_unique(App\History::all()->sortBy('template')->pluck('template')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}
						</div>
						<div class="col-md-3">
							{!! Form::select('sel_1[]', array_unique(App\History::all()->sortBy('season')->pluck('season')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}
						</div>
						<div class="col-md-3">
							{!! Form::select('sel_1[]',array_unique(App\History::all()->sortBy('country')->pluck('country')->toArray()),null,['class' => 'selectpicker filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class="btn btn-primary" id="filter1">Next</button></div>
			</div>
				<div class="row level2filter hidden" style="padding: 1%;">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							<select id="customer" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Customer" multiple></select>
						</div>
						<div class="col-md-3">
							<select id="material" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Material" multiple></select>
						</div>
						<div class="col-md-3">
							<select id="style" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Style" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<select id="mat_sub" class="selectpicker filterLevel2" data-actions-box="true" data-size="10" title="Select Mat-Sub" multiple data-live-search="true"></select>
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class="btn btn-primary" id="filter2">Next</button></div>
			</div>
				<div class="row level3filter hidden" style="padding: 1%;">
				<div class="col-md-11">
					<div class="row">
						<div class="col-md-3">
							<select id="reference" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Reference" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<select id="po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select PO" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<select id="buyer_po" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select Buyer PO" multiple data-live-search="true"></select>
						</div>
						<div class="col-md-3">
							<select id="ocn" class="selectpicker filterLevel3" data-actions-box="true" data-size="10" title="Select OCN" multiple data-live-search="true"></select>
						</div>
					</div>
				</div>
				<div class="col-md-1"><button class="btn btn-primary" id="filter3">Make</button></div>
			</div>
		</div>
	</div>




{{-- <div class="row ReportSection hidden ">
<div class="col-md-9">
<h3 class="text-center">Consolidated Data</h3>
</div>
<div class="col-md-2">
<button class="btn-primary more" id="moreOtions">More Options</button>
</div>
<div class="col-md-1">	 --}}
{{--  --}}
{{-- </div>
</div>  --}}

	<div class="row ReportSection   tableContainer" style="
     border: 1px solid lightgray;
    margin-left: -0.1%;
    margin-right: 0%;
    /*margin-left: -0.2%;*/
    ">  
		<div class="col-md-12"> 
		  <div class="">
    {{--     <div class="">
          <h4>
            Fixed Header Scrolling Table 
          </h4>
        </div> --}}
        <table class="table table-fixed">
 {{--          <thead>
            <tr>
              <th class="col-xs-2">#</th><th class="col-xs-8">Name</th><th class="col-xs-2">Points</th>
            </tr>

          </thead> --}}
          <thead>
					<tr id="all_fields">
				
					</tr>
				</thead>
          <tbody id="all_data">
            
          </tbody>
        </table>
      </div>
			{{-- <table class="table table-striped" id="reportTable">
				<thead>
					<tr>
						<th colspan="4">Consolidated Data</th>
						<th colspan="2"><button class="btn-primary more" id="moreOtions">More Options</button></th>
						<th>	<i class="fa fa-angle-down" id="toggleBtn" aria-hidden="true" data-toggle="collapse" data-target="#filter-panel" 
	style="    margin-top: 22%;
    font-size: 20px;
    border: 1px solid lightgray;
    padding: 6%;
    float: right;cursor: pointer;" data-target="down">
    	
    </i></th>
					</tr>
				</thead>
				<thead>
					<tr id="all_fields">
				
					</tr>
				</thead>
				<tbody id="all_data">
					
				</tbody>
			</table> --}}
		</div>
	</div>

{{-- 	  <div class="row">
    
  </div> --}}
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
				<button type="button" class="btn-primary" data-dismiss="modal" id="update_table">Update</button>
				<button type="button" class="btn-default" data-dismiss="modal">Close</button>
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
			});``
			$('.selectpicker').selectpicker('refresh');
			$('.'+$(this).data('target')).addClass('open').removeClass('hidden')
		});

		$(document).on('click', '#filter1', function(event) {
			event.preventDefault();
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

							$.each(data.customers, function(key, value) {   
								$('#customer')
								.append($("<option></option>")
									.attr("value",key)
									.text(value)); 
							});

							$.each(data.result, function(key, value) { 
								$('#material')
								.append($("<option></option>")
									.text(value['material'])); 
							});

							$.each(data.result, function(key, value) { 
								$('#style')
								.append($("<option></option>")
									.text(value['style'])); 
							});

							$.each(data.result, function(key, value) { 
								$('#mat_sub')
								.append($("<option></option>")
									.text(value['mat_sub'])); 
							});


							$('.selectpicker').selectpicker('refresh');
							$(".level2filter").removeClass('hidden')
						}

					});
				}
				else
				{
					alert("Please select atleast one value from each of the select boxes")
				}
			}
			else{
				alert("Please set the date interval");
			}
		});

		$(document).on('click', '#filter2', function(event) {
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
					mat_sub.push($(this).html());
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
						// console.log(data)
						$('#reference').html('')
						$('#po').html('')
						$('#buyer_po').html('')
						$('#ocn').html('')

						$.each(data, function(key, value) { 
							// console.log(value)
							$('#reference')
							.append($("<option></option>")
								.text(value['reference'])); 
						});

						$.each(data, function(key, value) { 
							$('#po')
							.append($("<option></option>")
								.text(value['po'])); 
						});

						$.each(data, function(key, value) { 
							$('#buyer_po')
							.append($("<option></option>")
								.text(value['buyer_po'])); 
						});

						$.each(data, function(key, value) { 
							$('#ocn')
							.append($("<option></option>")
								.text(value['ocn'])); 
						});


						$('.selectpicker').selectpicker('refresh');
						$(".level3filter").removeClass('hidden')
					}

				});
			}
			else
			{
				alert("Please select atleast one value from each of the select boxes")
			}
		});

		$(document).on('click', '#filter3', function(event) {
			$('.selectpicker').removeClass('open');
			if($(".roleSelection").is(':checked'))
			{
				if($('#'+$('input[name=roleSelection]:checked').data('target')).val().length > 0)
				{
					if($("#reference").val().length > 0 && $("#po").val().length > 0 && $("#buyer_po").val().length > 0 && $("#ocn").val().length > 0 && $("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
					{
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

						$.ajax({
							type : 'get',
							url : '/getHistory',
							data:{
								'role' : $('input[name=roleSelection]:checked').val(),
								'status' : $('#'+$('input[name=roleSelection]:checked').data('target')).val(),
								'references' : reference,
								'pos' : po,
								'buyer_pos' : buyer_po,
								'ocns' : ocn,
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
								create_table(data.Default,data.Extra,data.reportData)
							}
						});
					}
					else
					{
						alert("Please select atleast one value from each of the select boxes")
					}
				}
				else{
					alert("Please select the status");
				}
			}
			else{
				alert("Please Select One Of The Radio Buttons")
			}
		});

		$('.filterLevel1').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
			event.preventDefault();
			/* Act on the event */
			$(".filterLevel2").selectpicker('deselectAll');
			$(".filterLevel3").selectpicker('deselectAll');
		});

		$('.filterLevel2').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
			event.preventDefault();
			/* Act on the event */
			$(".filterLevel3").selectpicker('deselectAll');
		});

		function create_table(def,extra,tableData)
		{ 
			extraFields = extra.slice();
			defaultFields = def.slice(); 

			dataSet = [];
			heading="";
			tdData="";

			for (var i = 0; i < def.length ; i++)
			{
				heading+='<th>'+def[i]+'</th>'
				$("#all_fields").html('')
				$("#all_fields").html(heading)
				$(".ReportSection").removeClass('hidden')
			}

			for (var p = 0; p < tableData.length ; p++) {
				temp=""
				sno=0;
				dataSet.push(tableData[p])
				for (var i = 0; i < def.length ; i++) 
				{	
					if(def[i]=="S.No")
					{	sno=+p+1;
						temp+='<td>'+sno+'</td>'
					}
					else if(tableData[p][def[i]]==null)
					{
						temp+='<td>-</td>'
					}
					else{
						temp+='<td>'+tableData[p][def[i]]+'</td>'
					}
				}
				temp='<tr>'+temp+'</tr>'
				tdData+=temp
			}

			$("#all_data").empty();
			$("#all_data").html(tdData);

			$('html,body').animate({
				scrollTop: $(window).scrollTop() + 500
			});
		}

		$(document).on('click', '#update_table', function(event) {

			event.preventDefault();
			/* Act on the event */
			extras_Add=[];
			extras_Rem=[];
			updated_heading="";
			tdData="";
			$("#all_fields").empty()
			$("#all_data").empty()

			var listItems1 = $(".checklist1 li");
			var listItems2 = $(".checklist2 li");

			defaultFields=[]
			extraFields=[]

			listItems2.each(function(idx, vl) {
				defaultFields.push($(this).html())
			});

			listItems1.each(function(idx, vl) {
				extraFields.push($(this).html())
			});

			for (var i = 0; i < defaultFields.length ; i++) 
			{
				updated_heading+='<th>'+defaultFields[i]+'</th>'
			}

			for (var p = 0; p < dataSet.length ; p++) {
				temp=""
				sno=0
				for (var i = 0; i < defaultFields.length ; i++) 
				{	
					if(defaultFields[i]=="S.No")
					{	sno=+p+1;
						temp+='<td>'+sno+'</td>'
					}
					else if(dataSet[p][defaultFields[i]]==null)
					{
						temp+='<td>-</td>'
					}
					else{
						temp+='<td>'+dataSet[p][defaultFields[i]]+'</td>'
					}
				}
				temp='<tr>'+temp+'</tr>'
				tdData+=temp
			}

			$("#all_fields").html(updated_heading)
			$("#all_data").html(tdData);
		});

		$(document).on('click', '#moreOtions', function(event) {
			event.preventDefault();
			/* Act on the event */
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
		});

		$(document).on('click', '#toggleBtn', function(event) {
			event.preventDefault();
			/* Act on the event */
			if($(this).data('target')=="up")
			{
				$(this).removeClass('fa fa-angle-down').addClass('fa fa-angle-up')
				$(this).data('target',"down")
			}
			else
			{
				$("this").removeClass('fa fa-angle-up').addClass('fa fa-angle-down')
				$(this).data('target',"up")
			}
		});
	});
</script>
@endsection 