@extends('layouts.Customer.app') @if (Auth::guard("web_customer")->user()) @section('content')
@section('content')
<link rel="stylesheet" href="{{ asset('css/Erp/report/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('css/Erp/report/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/Erp/report/buttons.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/Erp/report/bootstrap-multiselect.css') }}">
<link rel="stylesheet" href="{{ asset('css/Erp/report/reports.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style type="text/css">
	.container-fluid {
     min-height: unset !important; 
}
</style>
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
				<div class="modal-content" style="width: 112%;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Report Generation</h4>
					</div>
					<div class="modal-body model1">
						<div class="row rowSep">
							<div class="col-md-2" style="margin-right: 2%;">
								<input class="dateGap" type="text" id="txtFrom" placeholder="Start Date" autocomplete="off"/>
							</div>
							<div class="col-md-2" style="margin-right: 3.1%;">
								<input class="dateGap" type="text" id="txtTo" placeholder="End Date" autocomplete="off"/>
							</div>

							<div class="col-md-3 movLft">
								{{-- <select class="selectpicker roleSelection" name="roleSelection" title="Select Role">
									@foreach($allActions as $key=> $action)
									<option value="{{ $action_as_id[$key] }}"> {{ $action }}</option>
									@endforeach --}}

									{!! Form::select('roleSelection', $allActions,null,['class' => 'selectpicker roleSelection','required','data-size'=>"10","title"=>'Select Role','id'=>'roleSelection']) !!}
								</select>
							</div>
							<div class="col-md-3" style="padding-left: 0;width: 24%;">
								<select class="selectpicker tempSelection" id="tempSelect" title="Select Status" disabled>
								</select>

								{!! Form::select('options_for_orders[]', $options_for_orders,null,['class' => 'selectpicker all_status options_for_orders hidden','required','data-size'=>"10",'multiple',"title"=>'Select Status [ Orders ]','id'=>'options_for_orders']) !!}

								{!! Form::select('options_for_delivery[]', $options_for_delivery,null,['class' => 'selectpicker all_status options_for_delivery hidden','required','data-size'=>"10",'multiple',"title"=>'Select Status [ Delivery ]','id'=>'options_for_delivery']) !!}

								{{-- {!! Form::select('role_Status[]', $order,null,['class' => 'selectpicker all_status _order hidden','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Status [ Order ]','id'=>'_order']) !!}

								{!! Form::select('role_Status[]', $invoice,null,['class' => 'selectpicker all_status _invoice hidden','required','data-size'=>"10",'data-actions-box'=>"true","title"=>'Select Status [ Invoice ]','id'=>'_invoice']) !!} --}}
							</div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								<label>Select Buyer</label>
								{!! Form::select('buyer', $buyers,null,['class' => 'selectpicker buyer_selection filterLevel1','required','data-size'=>"10","title"=>'None Selected','id'=>'buyer']) !!}

							{{-- 	{!! Form::select('sel_1[]', $buyers,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'data-actions-box'=>"true","title"=>'Select Buyer','id'=>'buyer']) !!} --}}
							</div>
							<div class="col-md-2 filters">
								<label>Select Template</label>
								{!! Form::select('sel_1[]',$templates,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Template','id'=>'template','disabled']) !!}

							</div>
							<div class="col-md-2 filters">
								<label>Select Season</label>
								{!! Form::select('sel_1[]',$seasons,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Season','id'=>'season']) !!}

							</div>
							<div class="col-md-2 filters">
								<label>Select Country</label>
								{!! Form::select('sel_1[]',$countries,null,['class' => 'multiselect filterLevel1','required','data-size'=>"10",'multiple','data-actions-box'=>"true","title"=>'Select Country','disabled','id'=>'country']) !!}

							</div>
							<div class="col-md-2">
								<button class="btn btn-default nxtBtn" id="filter1"><span class="multiselect-selected-text">&nbsp;Next&nbsp;</span></button>
							</div>
						</div>
						<div class="loaderAnim hidden" id="loader1">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								<label>Select Customer</label>
								
								@if($admin_flag)
									<select id="customer" class="multiselect filterLevel2" 	data-actions-box="true" data-size="10" nonSelectedText="Select Customer" multiple>
								</select>
								@else
									{!! Form::select('customer',$customers,null,['class' => 'multiselect filterLevel2','required',"nonSelectedText"=>'Select Customer','id'=>'customer']) !!}
								@endif
								
							</div>
							<div class="col-md-2 filters">
								<label>Select Material</label>
								<select id="material" class="multiselect filterLevel2" 	data-actions-box="true" data-size="10" title="Select 	 Material" multiple>
								</select>
							</div>
							<div class="col-md-2 filters">
								<label>Select Style</label>
								<select id="style" class="multiselect filterLevel2" 		data-actions-box="true" data-size="10" title="Select 	 Style" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<label>Select Mat-sub</label>
								<select id="mat_sub" class="multiselect filterLevel2" 		data-actions-box="true" data-size="10" title="Select 	 Mat-sub" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2">
								<button class="btn btn-default nxtBtn" id="filter2" disabled=""><span class="multiselect-selected-text">&nbsp;Next&nbsp;</span></button>
							</div>
						</div>
						<div class="loaderAnim hidden" id="loader2">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
						<div class="row rowSep">
							<div class="col-md-2 filters">
								<label>Select Reference</label>
								<select id="reference" class="multiselect filterLevel3" 	data-actions-box="true" data-size="10" title="Select 	Reference" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<label>Select PO</label>
								<select id="po" class="multiselect filterLevel3" 			data-actions-box="true" data-size="10" title="Select 	 PO" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<label>Select Buyer PO</label>
								<select id="buyer_po" class="multiselect filterLevel3" 	data-actions-box="true" data-size="10" title="Select 	 Buyer PO" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<label>Select OCN</label>
								<select id="ocn" class="multiselect filterLevel3" 			data-actions-box="true" data-size="10" title="Select 	 OCN" multiple data-live-search="true">
								</select>
							</div>
							<div class="col-md-2 filters">
								<button class="btn btn-default nxtBtn" id="filter3" disabled=""><span class="multiselect-selected-text">&nbsp;Done&nbsp;</span></button>
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
@endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
@endsection 
@section('jqueryIncludes')

<script type="text/javascript" src="{{ asset('js/Erp/report/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/Erp/report/bootstrap-multiselect.min.js') }}"></script>


<script type="text/javascript" src="{{ asset('js/Admin/report.js') }}"></script>
<script type="text/javascript">

	function triggerLevel1()
	{	

		if($("#txtFrom").val().length > 0 && $("#txtTo").val().length > 0)
		{
			if($("#buyer").val().length > 0 && $("#season").val().length > 0 )
			{
				$("#loader1").removeClass('hidden')
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
					url: "{{ $level1_route }}",
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

			
							$.each(data.Materials, function(key, value) { 

									$('#material').append($("<option></option>")
										.text(value));
							});

							var temp2=[]
							$.each(data.Style, function(key, value) 
							{ 
									$('#style').append($("<option></option>")
										.text(value)); 
							});
							

							$.each(data.SubMaterials, function(key, value) 
							{ 
									$('#mat_sub').append($("<option value='"+value+"'></option>")
										.text(value)); 
							});

							$('.filterLevel2').multiselect('destroy');

							$('.filterLevel2').multiselect({
							allSelectedText: 'All',
							maxHeight: 200,
							buttonWidth: 123,
							includeSelectAllOption: true,
							enableFiltering: true
							})	
							.multiselect('selectAll', false)
							.multiselect('updateButtonText');

						}
						$("#filter2").removeAttr('disabled')
						$("#loader1").addClass('hidden')

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

	if($("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0)
	{
		$("#loader2").removeClass('hidden')
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
			url: "{{ $level2_route }}",
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

				if(data)
				{
					$.each(data.Reference, function(key, value) 
					{ 

							$('#reference').append($("<option></option>")
								.text(value)); 
					});

					$.each(data.CustomerPo, function(key, value)
					{ 

							$('#po').append($("<option></option>")
								.text(value)); 
					});

					$.each(data.BuyerPo, function(key, value)
					{ 

							$('#buyer_po').append($("<option></option>")
								.text(value)); 

					});

					$.each(data.Ocn, function(key, value)
					{ 

							if(value != null && value != ',')
							{
								$('#ocn').append($("<option></option>")
								.text(value)); 
							}
					});
				}
				else{
					$('.snackbar_span').html("No results could be fetched for this combination.");
					showSnackbar();
				}
				$("#filter3").removeAttr('disabled')
				$("#loader2").addClass('hidden')
				$('.filterLevel3').multiselect('destroy');

				$('.filterLevel3').multiselect({
				allSelectedText: 'All',
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


function triggerLevel3()
		{
			$('.selectpicker').removeClass('open');
			if($(".roleSelection option:selected").val().length > 0)
			{
				console.log($("#options_for_orders").val().length)
				if($("#options_for_orders").val().length || $("#options_for_delivery").val().length )
				{
		
					if($("#reference").val().length > 0 && $("#buyer_po").val().length > 0 && $("#ocn").val().length > 0 && $("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0)
					{
						$status =[]
						if($(".roleSelection option:selected").html() == "Orders")
						{
							$status = $("#options_for_orders").val()
						}
						else
						{
							$status = $("#options_for_delivery").val()
						}
						$.ajax({
							type : 'post',
							url: "{{ $get_table_fields_route }}",
							data:{
								'role' : $(".roleSelection option:selected").html(),
								'status' : $status,
								'_token': "{{ csrf_token() }}",	
							},
							success:function(data)
							{	
								if(data.Default.length > 0)
								{
									$("#_prefModal").modal('hide')
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
				$('.snackbar_span').html("Please select one of the roles.");
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
	$status =[]

	if($(".roleSelection option:selected").html() == "Orders")
	{
		$status = $("#options_for_orders").val()
	}
	else
	{
		$status = $("#options_for_delivery").val()
	}

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
		"language": {
		"processing": '<div class="spinner"></div>' 
		},
		serverSide: true,
		"scrollX": true,
		dom: 'lBfrtip',
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

		"buttons":
		[
		  {
		extend: 'excel',
		text: 'Export to Excel',
		// className: 'excelButton',
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
                	$('c:nth-child(n)', sheet).attr('s','50');
                	$('row:first c', sheet).attr('s','7');
                	$('row:eq(1) c', sheet).attr('s','7');
            }
		  }
		],

		fixedHeader: {
			header: true,
		},
		"ajax": {
			"url": "{{ $set_Datatable_route }}",
			"dataType": "json",
			"type": "POST",
			"data":{
				_token: "{{csrf_token()}}",
				role : $(".roleSelection option:selected").html(),
				status : $status,
				references : reference,
				pos : po,
				buyer_pos : buyer_po,
				ocns : ocn,
				buyer : $("#buyer").val(),
				materials : material,
				styles : style,
				mat_subs : mat_sub,
				customers : $("#customer").val(),
				season : seasons,
				date_start : date1,
				date_end : date2
			}
		},
		"columns" :allColoumns
	});
		$('table tr:first th:first').text('S.No');
		$("#reportTitle").html($(".roleSelection option:selected").html()+" Report of "+$("#buyer option:selected").text())
		$("#moreOtions").removeClass('hidden')
		$('.dt-buttons').append('<button class="dt-button buttons-html5" tabindex="0" aria-controls="reportTable" type="button" id="moreOtions"><span>More Options</span></button><button class="dt-button buttons-html5" tabindex="0" aria-controls="reportTable" type="button" id="setFilters"><span class="fa fa-cogs"></span></button>')

	}

</script>
@endsection 
