@extends('layouts.Erp.app')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<link rel="stylesheet" href="{{ asset('css/Erp/reports.css') }}">
@section('navbar')
<style>
	.navbar-nav > li > a {
		line-height: 12px !important;
	}
</style>
<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="#" onClick="window.location.reload();return false;" class="active">Get Reports</a></li>
</ul>
@endsection

<div class="container-fluid setMarginTop4">

	<div id="snackbar">
		<span class="snackbar_span"></span>
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
							<h5 class="modal-title text-center">Available Fields</h5>
							<ul class="connected-sortable droppable-area2 checklist1">
							</ul>
						</div>
						<div class="col-md-6">
							<h5 class="modal-title text-center">Fields Currently Displayed</h5>
							<ul class="connected-sortable droppable-area1 checklist2">
							</ul>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" id="update_table">Update</button>
					<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>

	<div class="row">
		<!-- Modal -->
		<div class="modal fade" id="_prefModal" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Report Generation</h4>
					</div>
					<div class="modal-body model1">
						<div class="row rowSep">
							<div class="col-md-2" style="margin-right: 2%;">
								<input class="dateGap" type="text" id="txtFrom" placeholder="Start Date" autocomplete="off" value="2019-09-01" />
							</div>
							<div class="col-md-2" style="margin-right: 3.1%;">
								<input class="dateGap" type="text" id="txtTo" placeholder="End Date" autocomplete="off" value="2019-11-30" />
							</div>

							<div class="col-md-3 movLft">
								<select class="selectpicker roleSelection" name="roleSelection" title="Select Role">
									@foreach($allActions as $key=> $action)
									<option value="{{ $action_as_id[$key] }}"> {{ $action }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3" style="padding-left: 0;width: 24%;">
								<select class="selectpicker tempSelection" id="tempSelect" title="Select Status" disabled>
								</select>

								{!! Form::select('role_Status[]', $po,null,['class' => 'selectpicker all_status _poMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ PO Mngr ]','id'=>'_poMgr']) !!}

								{!! Form::select('role_Status[]', $jc,null,['class' => 'selectpicker all_status _jcMgr hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ JC Mngr ]','id'=>'_jcMgr']) !!}

								{!! Form::select('role_Status[]', $order,null,['class' => 'selectpicker all_status _order hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ Order ]','id'=>'_order']) !!}

								{!! Form::select('role_Status[]', $invoice,null,['class' => 'selectpicker all_status _invoice hidden','required','data-size'=>"10",'data-actions-box'=>"true","title"=>'Select Status [ Invoice ]','id'=>'_invoice']) !!}
							</div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								{!! Form::select('sel_1[]', $buyers,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!}
							</div>
							<div class="col-md-2 filters">
								{!! Form::select('sel_1[]',$templates,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template']) !!}

							</div>
							<div class="col-md-2 filters">
								{!! Form::select('sel_1[]',$seasons,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}

							</div>
							<div class="col-md-2 filters">
								{!! Form::select('sel_1[]',$countries,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','id'=>'country']) !!}

							</div>
							<div class="col-md-2">
								<button class="btn btn-default" id="filter1"><span class="multiselect-selected-text">&nbsp;Next&nbsp;</span></button>
							</div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								<select id="customer" class="multiselect filterLevel2" 	data-actions-box="true" data-size="10" nonSelectedText="Select Customer" multiple>
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="material" class="multiselect filterLevel2" 	data-actions-box="true" data-size="10" title="Select 	 Material" multiple>
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="style" class="multiselect filterLevel2" 		data-actions-box="true" data-size="10" title="Select 	 Style" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="mat_sub" class="multiselect filterLevel2" 		data-actions-box="true" data-size="10" title="Select 	 Mat-sub" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2">
								<button class="btn btn-default" id="filter2" disabled=""><span class="multiselect-selected-text">&nbsp;Next&nbsp;</span></button>
							</div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								<select id="reference" class="multiselect filterLevel3" 	data-actions-box="true" data-size="10" title="Select 	Reference" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="po" class="multiselect filterLevel3" 			data-actions-box="true" data-size="10" title="Select 	 PO" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="buyer_po" class="multiselect filterLevel3" 	data-actions-box="true" data-size="10" title="Select 	 Buyer PO" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<select id="ocn" class="multiselect filterLevel3" 			data-actions-box="true" data-size="10" title="Select 	 OCN" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<button class="btn btn-default" id="filter3" disabled=""><span class="multiselect-selected-text">&nbsp;Done&nbsp;</span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row ReportSection hidden reportSec">
		<h4 class="text-center" id="reportTitle">Consolidated Data</h4>
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
	</div>
</div>

@section('jqueryIncludes')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>

<script type="text/javascript" src="{{ asset('js/Erp/report.js') }}"></script>
<script type="text/javascript">

	function triggerLevel1()
	{	

		if($("#txtFrom").val().length > 0 && $("#txtTo").val().length > 0)
		{
			if($("#buyer").val().length > 0 && $("#season").val().length > 0 && $("#template").val().length > 0 && $("#country").val().length > 0)
			{
				countries = [];
				seasons = [];
				templates = [];
				_invSelectd = 0;

				$.each($("#country option:selected"), function(){            
					countries.push($(this).html());
				});

				$.each($("#season option:selected"), function(){            
					seasons.push($(this).html());
				});

				$.each($("#template option:selected"), function(){            
					templates.push($(this).html());
				});

				if($(".roleSelection option:selected").val().length > 0)
				{
					if($(".roleSelection option:selected").val() == "_invoice")
					{
						_invSelectd = 1;
					}
				}
				$.ajax({
					type : 'post',
					url: "{{ url('/level1Filter') }}",
					data:{
						'buyer': $("#buyer").val(),
						'country': countries,
						'season': seasons,
						'template': templates,
						'date_start' : $("#txtFrom").val(),
						'date_end' : $("#txtTo").val(),
						'invoiceStatus' : _invSelectd,
						'_token': "{{ csrf_token() }}",
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
							$('.filterLevel2').multiselect('destroy');

							$('.filterLevel2').multiselect({
							allSelectedText: 'All Selected',
							maxHeight: 200,
							buttonWidth: 123,
							includeSelectAllOption: true,
							enableFiltering: true
							})	
							.multiselect('selectAll', false)
							.multiselect('updateButtonText');

						}
						$("#filter2").removeAttr('disabled')

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
			type : 'post',
			url: "{{ url('/level2Filter') }}",
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
				'date_end' : $("#txtTo").val(),
				'_token': "{{ csrf_token() }}",
			},

			success:function(data)
			{
				$('#reference').html('')
				$('#po').html('')
				$('#buyer_po').html('')
				$('#ocn').html('')

				console.log(data)

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
				}
				else{
					$('.snackbar_span').html("No results could be fetched for this combination.");
					showSnackbar();
				}
				$("#filter3").removeAttr('disabled')
				$('.filterLevel3').multiselect('destroy');

				$('.filterLevel3').multiselect({
				allSelectedText: 'All Selected',
				maxHeight: 200,
				buttonWidth: 123,
				includeSelectAllOption: true,
				enableFiltering: true
				})	
				.multiselect('selectAll', false)
				.multiselect('updateButtonText');
			}

		});
	}
	else
	{
		$('.snackbar_span').html("Please select atleast one value from each of the select boxes.");
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

		reportTable = $('#reportTable').dataTable( {
		processing: true,
		serverSide: true,
		"scrollX": true,
		dom: 'lBfrtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

		"buttons":
		[
		  {
		extend: 'excel',
		text: 'Export to Excel',
		title : "Consolidated Report [ "+$(".roleSelection option:selected").html()+" ] " + date_today,
				action: function ( e, dt, node, config ) 
				{
					var evt = this;
					dt.one( 'draw', function () {
					$.fn.dataTable.ext.buttons.excelHtml5.action.call(evt, e, dt, node, config);
					});
					dt.page.len(-1).draw();
				},
				customize: function( xlsx ) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                $('row c[r^="G"]', sheet).attr( 's', '50' );
            }
		  }
		],

		fixedHeader: {
			header: true,
		},
		"ajax": {
			"url": "{{ url('setUpDatatable') }}",
			"dataType": "json",
			"type": "POST",
			"data":{
				_token: "{{csrf_token()}}",
				role : $(".roleSelection option:selected").html(),
				status : $("#"+selectedRoles).val(),
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
				date_start : date1,
				date_end : date2
			}
		},
		"columns" :allColoumns
	});
		$("#reportTitle").html("Consolidated Report [ "+$(".roleSelection option:selected").html()+" ]")
		$("#moreOtions").removeClass('hidden')
		$('.dt-buttons').append('<button class="dt-button buttons-html5" tabindex="0" aria-controls="reportTable" type="button" id="moreOtions"><span>More Options</span></button><button class="dt-button buttons-html5" tabindex="0" aria-controls="reportTable" type="button" id="setFilters"><span class="fa fa-cogs"></span></button>')

	}

</script>
@endsection 
@endsection 
