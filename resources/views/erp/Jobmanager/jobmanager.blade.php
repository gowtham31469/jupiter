@extends('layouts.Erp.app')
<style>
	.action-button-view {
		border: 1px #333 solid;
		background-color: #fff;
		border-radius: 2px;
		padding: 3px;
		margin-right: 10px;
	}
	
	.red-icon {
		color: grey;
		float: right;
	}
	
	.active {
		color: #dc3545 !important;
	}
	
	.active-border {
		border-color: #dc3545 !important;
	}
	
	.bad {
		background-color: #dc3545;
		color: #fff;
	}
	
	.cur-po {
		cursor: pointer;
		color: grey;
		float: right;
	}
	
	.cur-po-comp {
		cursor: pointer;
		color: grey;
	}
	
	.btn-loader {
		border: 8px solid #f3f3f3 !important;
		border-radius: 50% !important;
		border-top: 8px solid #3498db !important;
		width: 50px !important;
		height: 50px !important;
		-webkit-animation: spin 2s linear infinite !important;
		/* Safari */
		animation: spin 2s linear infinite !important;
	}
	/* Safari */
	
	@-webkit-keyframes spin {
		0% {
			-webkit-transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
		}
	}
	
	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
	
	label.btn span {
		font-size: 1.5em;
	}
	
	label input[type="radio"]~i.fa.fa-circle-o {
		color: #c8c8c8;
		display: inline;
	}
	
	label input[type="radio"]~i.fa.fa-dot-circle-o {
		display: none;
	}
	
	label input[type="radio"]:checked~i.fa.fa-circle-o {
		display: none;
	}
	
	label input[type="radio"]:checked~i.fa.fa-dot-circle-o {
		color: #7AA3CC;
		display: inline;
	}
	
	label:hover input[type="radio"]~i.fa {
		color: #7AA3CC;
	}
	
	label input[type="checkbox"]~i.fa.fa-square-o {
		color: #c8c8c8;
		display: inline;
	}
	
	label input[type="checkbox"]~i.fa.fa-check-square-o {
		display: none;
	}
	
	label input[type="checkbox"]:checked~i.fa.fa-square-o {
		display: none;
	}
	
	label input[type="checkbox"]:checked~i.fa.fa-check-square-o {
		color: #7AA3CC;
		display: inline;
	}
	
	label:hover input[type="checkbox"]~i.fa {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label.active {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label {
		display: inline-block;
		padding: 6px 12px;
		margin-bottom: 0;
		font-size: 14px;
		font-weight: normal;
		line-height: 2em;
		text-align: left;
		white-space: nowrap;
		vertical-align: top;
		cursor: pointer;
		background-color: none;
		border: 0px solid #c8c8c8;
		border-radius: 3px;
		color: #c8c8c8;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
	}
	
	div[data-toggle="buttons"] label:hover {
		color: #7AA3CC;
	}
	
	div[data-toggle="buttons"] label:active,
	div[data-toggle="buttons"] label.active {
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	
	.first-padding {
		padding: 10px 15px;
		font-weight: bold;
		font-size: 14px;
	}
	
	.first-padding:nth-child(even),
	.odd-padding {
		background-color: #f2f2f2;
	}
	
	.first-padding.col-md-4.text-center {
		border-right: 5px solid #fff;
	}
	
	.confirmation-container {
		margin-top: 15px;
	}
	
	.remark-align {
		padding-left: 0px !important;
	}
	.highlight_row {
		background: #eee;
	}
</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="javascript:void(0)" class="active">Job Card Manager</a></li>
</ul>

@endsection @section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12" style="padding-top: 25px;">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
			    <div class="company-nav-div hidden">
			    	<a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
				</div>
                <div class="action-group pull-right sub-menu-button">
                  <button class=" btn btn-primary btn-sm" onclick="generateJobcard()">
                   Create Jobcard
				  </button>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader">
						<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
					</div>
                    <div class="col" id="customer-table">
					<input type="hidden" value="{{ session()->get('company') }}" id="company_id">
					<input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
					<table id="approved-order-list" class="table table-bordered table-responsive table-hover table-striped" style="width:100%;font-size: 12px;">
						<thead>
							<tr>
								<th>Sl. No</th>
								<th>ERP ID</th>
								<th>Customer name</th>
								<th>PO Number</th>
								<th>Style Number</th>
								<th>Reference Number</th>
								<th>Delivery Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($orders as $vals)

							<tr id="{{$vals->id}}">
								<td class="">
								
									<div class="form-check">
									<a href="javascript:void(0)">
										<label>
											<input class="row-checkbox" type="checkbox" name="row-check-{{ $loop->iteration }}" style="margin-right: 10px;"> <span class="label-text" class="hidden-val">{{ $loop->iteration }}</label>
									</a>
									</div>
								</td>
								<td>{{$vals->id}}</td>
								<td>{{$vals->company_name}}</td>
								<td>{{$vals->po_no}}</td>
								<td>{{$vals->style_no}}</td>
								<td>{{$vals->reference_name}}</td>
								@if ($vals->delivery_date != '')
									<td>{{$vals->delivery_date}}</td>
								@else
									<td>N/A</td>
								@endif
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
              </div>
                </div>
            </div>
        </div>
    </div>

<!-- company selection Modal -->
<div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog buyer-selection-modal-dialog" role="document">
         <div class="modal-content buyer-selection-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Company</h4>
                 </div>
                 <div class="modal-body buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                      <strong>Warning!</strong> Select a Company to proceed.
                    </div>
                    @foreach($companies as $company)
                     <a class="buyer-card-link" style="cursor: pointer;">
                         <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$company->name}}"  data-value ="{{$company->company}}" id="{{ strtolower( str_replace(' ','',$company->name)) }}">
                          {{ $company->name }}
                         </div>
                     </a>
                    @endforeach
                 </div>
                 <div class="modal-footer buyer-selection-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
<!-- Job card creation Modal -->
<div class="modal jobcard-creation-modal fade" id="jobcardCreationModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog modal-lg jobcard-creation-modal-dialog" role="document">
         <div class="modal-content jobcard-creation-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title jobcard-modal-title" id="myModalLabel">Create Jobcard</h4>
                 </div>
                 <div class="modal-body jobcard-creation-modal-body">
                    <div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p>Jupiter Garments Accessories			
										1-J-27-1&3, Jupiter Avenue			
										Anupparpalayam, Tirupur - 641652
									</p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <img src="/images/logo.png">
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p>W O R K O R D E R - 1234
									</p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <p>DATE : 	11/10/2018</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									<p>ARTICLE : Sticker & Labels
									</p>
								</div>
								<div class="col-md-4">
								</div>
								<div class="col-md-4">
								     <p>MATERIAL: double side woven edge- white</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<p>CUST NAME	SPA
									</p>
								</div>
								<div class="col-md-4">
								    <p>STYLENUM		AA1234567
									</p>
								</div>
								<div class="col-md-4">
								     <p>ORDER TYPE	INITIAL</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-4">
									<p>BUYER	TESCO
									</p>
								</div>
								<div class="col-md-4">
								    REFERENCE		FF/01/12345G	
								</div>
								<div class="col-md-4">
								     <p>DISP MODE</p>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-3">
									<p>ORDER DT	10/10/2018
									</p>
								</div>
								<div class="col-md-3">
								    <p>LENGTH		25
									</p>
								</div>
								<div class="col-md-3">
								     <p>REPEAT GAP		25</p>
								</div>
								<div class="col-md-3">
								     <p>MACHINE		5</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<p>CUS D.DT	10/10/2018
									</p>
								</div>
								<div class="col-md-3">
								    <p>WIDTH		25</p>
								</div>
								<div class="col-md-3">
								     <p>ROLL WIDTH		</p>
								</div>
								<div class="col-md-3">
								     <p>FILM NO		5</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<p>ACT D.DT	10/10/2018
									</p>
								</div>
								<div class="col-md-3">
								    <p>LENGTH SIDE		25</p>
								</div>
								<div class="col-md-3">
								     <p>EXCESS %		</p>
								</div>
								<div class="col-md-3">
								     <p>TEETH NO		5</p>
								</div>
							</div>
							<div class="col-md-12">
								<div class="col-md-3">
									<p>INV NO	1234
									</p>
								</div>
								<div class="col-md-3">
								    <p>NO OF STI		25</p>
								</div>
								<div class="col-md-3">
								</div>
								<div class="col-md-3">
								     <p>FINISH TYPE		5</p>
								</div>
							</div>
							<div class="col-md-12">
							   <div class="col-md-8">
							      <p>PRINT COLORS		485C, 201C,300C 485C, 201C,300C				</p>
							   </div>
							   <div class="col-md-4">
							   </div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered">
								    <thead>
										<tr>
											<th>REFNUM</th>
											<th>PONUM</th>
											<th>IDNUM</th>
											<th>OCN</th>
											<th>ORD NO</th>
											<th>QNTY</th>
											<th>P.QNTY</th>
											<th>TOTAL</th>
										</tr>
									</thead>
									<tbody>
									<tr>
										<td>TCL1 RatA</td>
										<td>460-12344</td>
										<td></td>
										<td></td>
										<td>999</td>
										<td>150</td>
										<td></td>
										<td>800</td>
									</tr>
									<tr>
											<td>TCL1 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TCL2 RatA</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TCL2 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TSL1 RatA</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td>3434</td>
										</tr>
										<tr>
											<td>TSL1 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>REFNUM</th>
											<th>PONUM</th>
											<th>IDNUM</th>
											<th>OCN</th>
											<th>ORD NO</th>
											<th>QNTY</th>
											<th>P.QNTY</th>
											<th>TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>TCL1 RatA</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TCL1 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TCL2 RatA</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TCL2 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TSL1 RatA</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>TSL1 RatB</td>
											<td>460-12344</td>
											<td></td>
											<td></td>
											<td>999</td>
											<td>150</td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                 </div>
                 <div class="modal-footer jobcard-creation-modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection @section('jqueryIncludes')
<script>
$(document).ready(function() {
	var base_url = "<?php echo  url('/') ?>";
	$('input[class="row-checkbox"]').prop('checked', false);
	/** [ Make companies card active ] */
	$('div.buyers-card').click(function(){
	  $('div.buyers-card').removeClass("active");
	  $(this).addClass("active");
	  $('#hidden-buyer-div').hide();

	  /* Save selected buyer into localdb */
      if ($('.buyers-card.active-buyer.active').length != 0) 
      {
          var company_id = $('.buyers-card.active-buyer.active').data("value"); 
		  var company_name = $('.buyers-card.active-buyer.active').attr("id");
          $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
          $('#buyerSelectionModalCenter').modal('hide');
		  
		  var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/erp/po-manager/set_company_session",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					company_id: company_id,
					company_name:company_name
				},
				beforeSend: function() {
					location.reload();

				},
				success: function(data) {
				},
				error: function(data) {

				},
				complete: function() {

				}
			})
      }
	  
	});
	
	/** [ Show already selected(active) buyer on clicking buyer menu] */
	$('.company-nav-menu').on('click', function() {
	    $("#buyerSelectionModalCenter").modal("show");
	});

	if($('#company_name').val() != null && $('#company_name').val() != "")
	{
		$('#'+$('#company_name').val()).addClass('active');
	}
	else
	{
		$('div.buyers-card').first().addClass("active");
	}
   
    $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
	
	$('.company-nav-menu').html($('.company-nav-div').html());

	/** Datatable filter script**/
	var $tableSel = $('#approved-order-list');
	$tableSel.DataTable({
		/** Table column filter functionality **/
		initComplete: function () {
			$('.main-content > #customer-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first').append(' <button class="filterbtn" type="button" onClick="showFilter()" title="Filter" data-placement="bottom"><i class="fa fa-filter" aria-hidden="true"></i></button>');
			$('.main-content > #customer-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first .dataTables_filter').css('display', 'inline-block');
			$('.main-content > #customer-table > .dataTables_wrapper > .row > .col-sm-12:first').prepend('<div class="filter-window"><h5><strong>Jobcard Filter</strong></h5> <div class="row"> <div class="col-md-2"><select class="selectpicker" id="erpID"> <option disabled selected>SELECT AN ERP ID</option></select></div><div class="col-md-2"><select class="selectpicker" id="customerName"> <option disabled selected>SELECT A CUSTOMER</option></select></div><div class="col-md-2"><select class="selectpicker" id="poNumber"> <option disabled selected>SELECT A PO NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="styleNumber"> <option disabled selected>SELECT A STYLE NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="refNumber"> <option disabled selected>SELECT A REFERENCE</option></select></div><div class="col-md-2"><select class="selectpicker" id="delDate"> <option disabled selected>SELECT A DELIVERY DATE</option></select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

			//For Custom Filters
			this.api().columns().every( function (i) {
				var column = this;
				if(i === 1 || i === 2 || i === 3 || i === 4 || i === 5 || i === 6) {
					var select;
					switch(i) {
						case 1:
							select = $('#erpID')
							break;
						case 2:
							select = $('#customerName')
							break;
						case 3:
							select = $('#poNumber')
							break;
						case 4:
							select = $('#styleNumber')
							break;
						case 5:
							select = $('#refNumber')
							break;
						case 6:
							select = $('#delDate')
							break;
					}
					select.on( 'change', function () {
						var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);
	
						column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
					});
					select.append( '<option value=""> </option>' );
					column.data().unique().sort().each( function ( d, j ) {
						cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
						select.append('<option value="'+cleanText+'">'+cleanText+'</option>')
					});
				}
				
			});

			
		}
    } );

	 $("#reset").on("click", function () {
		$('#erpID').val('').change();
		$('#customerName').val('').change();
		$('#poNumber').val('').change();
		$('#styleNumber').val('').change();
		$('#refNumber').val('').change();
		$('#delDate').val('').change();
		$.fn.dataTableExt.afnFiltering.length = 0;
		$tableSel.dataTable().fnDraw();
		$('.selectpicker').selectpicker('val', 1);
        $('.selectpicker').selectpicker('refresh');
	});

	$('.selectpicker').selectpicker();


	// Clear the filter. Unlike normal filters in Datatables,
	// custom filters need to be removed from the afnFiltering array.
	$('.cancelBtn').on('click', function(e){
		e.preventDefault();
		$.fn.dataTableExt.afnFiltering.length = 0;
		$tableSel.dataTable().fnDraw();
	});
	$("#approved-order-list_filter input[type=search]").addClass('form-control');
	$('.dataTables_length select').addClass('form-control');
	$("#approved-order-list_filter input[type=search]").attr('placeholder', 'Data Search');

	$('.approved-order-list tr').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

	/** change table row background color	**/
	$("input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass("highlight_row");
        } else {
            $(this).closest('tr').removeClass("highlight_row");
        }
    });
} );
</script>

@endsection
