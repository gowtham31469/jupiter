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
	
	#po-list-table {
		font-size: 14px !important;
		width: 100% !important;
	}
	
	#order-details-table {
		font-size: 14px !important;
		width: 100%;
		margin-top: 15px;
	}
	
.modal-header {
    padding: 15px;
    border-bottom: 1px solid #e5e5e5;
    box-shadow: -4px 2px 12px 0px rgba(0, 0, 0, 0.09);
}
	.close {
  
    font-weight: 100 !important;
    color: #000000 !important;
    opacity: 0.5 !important;
}
	.scroll {
    height: calc(100vh - 130px);
    min-height: 300px;
    overflow-x: hidden;
    overflow-y: scroll;
    padding-right: 10px;
}
	
#zoomBtn{
	padding: 22px;
}
.zoom {
position: fixed;
bottom: 45px;
right: 52px;
z-index: 9999;
}
 
.zoom-fab {
display: inline-block;
width: 40px;
height: 40px;
padding-top:14px;
border-radius: 50%;
background-color: #009688;
vertical-align: middle;
text-decoration: none;
text-align: center;
transition: 0.2s ease-out;
box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
cursor: pointer;
color: #FFF;
}
	
 @-moz-document url-prefix() {
	 li.zoom-fab{
		 padding-top:10px !important;
	 }
}
.zoom-fab:hover {
background-color: #4db6ac;
box-shadow: 0 3px 3px 0 rgba(0, 0, 0, 0.14), 0 1px 7px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -1px rgba(0, 0, 0, 0.2);
}
 
.zoom-btn-large {
width: 60px;
height: 60px;
}
 
.zoom-btn-report {
background-color: #2196F3;
}
 
.zoom-btn-report:hover {
background-color: #64b5f6;
}
 
.zoom-menu {
position: fixed;
bottom: 110px;
transform: translateX(-28%);
list-style-type: none;
}

.zoom-menu li {
/* display: inline-block; */
margin-top: 13px;
}
 
.scale-transition {
transition: transform 0.3s cubic-bezier(0.53, 0.01, 0.36, 1.63) !important;
}
 
.scale-transition.scale-out {
transform: scale(0);
transition: transform 0.2s !important;
}
 
.scale-transition.scale-in {
transform: scale(1);
}

.align-button{
  margin-left: 74% !important;
}

.confirmation-container > .col-md-6{
	padding-left: 50px;
}

#comment_box{
	height: 100px;
}
</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="" class="active">Print Orders</a></li>
</ul>

@endsection @section('content')


<div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12" style="padding-top: 25px;">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
			    <div class="company-nav-div hidden">
			    	<a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
				</div>
              </div>
             <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader" style="display:none;">
						<div class=" fa-3x">
                            <i class="fa fa-spinner fa-pulse"></i>
                        </div>
					</div>
					<div class="filter-window-jobcard-list">
					{!! Form::open(['id' => 'printmanager_filter_form','data-toggle' => 'validator','role' => 'form']) !!}
					<h5 style="margin-left:15px;"><strong>Print Orders Filter</strong></h5>
					<div class="col-xs-6 col-sm-4">
					    <div class="form-group has-feedback">
							{{ Form::select('buyer_filter', (['' => 'SELECT A BUYER']+ $buyers), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'buyer_filter', 'required', 'onchange' => 'getCustomersList(this)')) }}
						    <div class="help-block with-errors"></div>
					    </div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('customer_filter', (['' => 'SELECT A CUSTOMER']), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'customer_filter', 'required', 'onchange' => 'getJobcardIDList(this)')) }}
						    <div class="help-block with-errors"></div>
					    </div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('style_no_filter',(['' => 'SELECT A ERP ID']),'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true','id' => 'style_no_filter','required')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
						<div class="filter-actions-erp">
							{!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm pull-right','type' => 'submit', 'style' => 'margin-top: 0.5em;margin-right: 15px;'] ) !!}
							<div class="clearfix"></div>
						</div>
					{!! Form::close() !!}
					</div>
					<br>
                    <div class="col" id="jobcard-lists-table">
                        <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
                        <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
                            <table id="jobcard-list-table" class="table table-bordered table-responsive table-hover " style="font-size: 12px;">
                                <thead>
                                    <tr>
									<th>Sl. No</th>
									<th>Style No</th>
									<th>Buyer PO</th>
									<th>Zone</th>
									<th>Reference Name</th>
									<th>Partial</th>
									<th>Status</th>
									<th>Created Date</th>
									<th>Actions</th>
									</tr>
                                </thead>
                                <tbody id="jobcard_list_table_body">
                                </tbody>
                            </table>
				    </div>
                </div>
                </div>
            </div>
        </div>

<!-- Hidden div for svg rendering -->
<div id="modaldiv">
	<div id="svgdiv" style="position:absolute;visibility:hidden">\
	 <svg id="ean-13"></svg>\
   </div>
</div>

<!-- Buyer selection Modal -->
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
                          <img src="{{$company->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
                         </div>
                     </a> @endforeach
			</div>
			<div class="modal-footer buyer-selection-modal-footer">
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
	//$('.company-nav-div .profile-dropdown-admin').addClass('hidden')
	
	//$("#orderDetailsModal").niceScroll();
	/** [ Make buyers card active ] */

		$('[data-toggle="tooltip"]').tooltip({});
		$('#zoomBtn').click(function() {
		$('.zoom-btn-sm').toggleClass('scale-out');
		});


	$('div.buyers-card').click(function() {
		$('div.buyers-card').removeClass("active");
		$(this).addClass("active");
		$('#hidden-buyer-div').hide();

		/* Save selected buyer into localdb */
		if ($('.buyers-card.active-buyer.active').length != 0) {
			var company_id = $('.buyers-card.active-buyer.active').data("value");
			var company_name = $('.buyers-card.active-buyer.active').attr("id");
			$('.selected-buyer').text('Selected Company : ' + $('.buyers-card.active-buyer.active').data("name"))
			$('#buyerSelectionModalCenter').modal('hide');

			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/erp/po-manager/set_company_session",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					company_id: company_id,
					company_name: company_name
				},
				beforeSend: function() {
					

				},
				success: function(data) {
					location.reload();
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
	//alert($('#company_name').val())
	if ($('#company_name').val() != null && $('#company_name').val() != "") {
		$('#' + $('#company_name').val()).addClass('active');
	} else {
		$('div.buyers-card').first().addClass("active");
	}


	$('.selected-buyer').text('Selected Company : ' + $('.buyers-card.active-buyer.active').data("name"))


	$('.company-nav-menu').html($('.company-nav-div').html());
	var base_url = "<?php echo  url('/') ?>";
	var table = $('#po-list-table').DataTable();

	$("#po-list-table_filter input[type=search]").addClass('form-control');
	$('.dataTables_length select').addClass('form-control');
	$("#po-list-table_filter input[type=search]").attr('placeholder', 'Data Search');

	$(".style_no_check").click(function() {
		$(this).toggleClass('active')
		$('.style_no_td').toggleClass('bad')
	});
	$(".qty_check").click(function() {
		$(this).toggleClass('active')
		$('.qty_td').toggleClass('bad')
	});
	$(".po_complete_check").click(function() {
		$(this).toggleClass('active')
		$('.po-complete-label').toggleClass('active')
		$('.border-div').toggleClass('active-border')
	});
	$(".reference_check").click(function() {
		$(this).toggleClass('active')
		$('.reference_td').toggleClass('bad')
	});
	$(".po_number_check").click(function() {
		$(this).toggleClass('active')
		$('.po_number_td').toggleClass('bad')
	});
	$(".price_check").click(function() {
		$(this).toggleClass('active')
		$('.price_td').toggleClass('bad')
	});
		
	});
	
	
function getCustomersList(evt)
{
    try {
        let selected_buyer_id = evt.value;
        if (selected_buyer_id != '') 
        {
            $.ajax({
                url: base_url + "/print-manager/get-customers-list",
                type: 'GET',
                data: {
                    selected_buyer_id: selected_buyer_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                        var html = '<option value="">SELECT A CUSTOMER</option>';
                        /** [ customers list selectpicker value populating ] */
                        $.each(data.customers_list, function (key, item) {
                            html += '<option value="' + key + '">' + item + '</option>';
                        });
                        $('#customer_filter').html(html);
                        $('#customer_filter').selectpicker('refresh');
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#customer_filter').html('<option value="">SELECT A CUSTOMER</option>');
            $('#customer_filter').selectpicker('refresh');
            $('#style_no_filter').html('<option value="">SELECT A ERP ID</option>');
            $('#style_no_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}
	
	
function getJobcardIDList(evt)
{
    try {
        let selected_customer_id = evt.value;
        let selected_buyer_id    = $('#buyer_filter option:selected').val();
        if (selected_customer_id != '') 
        {
            $.ajax({
                url: base_url + "/print-manager/get-style-list",
                type: 'GET',
                data: {
                    selected_customer_id: selected_customer_id,
                    selected_buyer_id:selected_buyer_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                    	console.log(data)
                        var html = '<option value="">SELECT A ERP ID</option>';
                        /** [ jobcard id list selectpicker value populating ] */
                        $.each(data.jobcard_id_list, function (key, item) {
                            html += '<option value="' + key + '">' + item + '</option>';
                        });
                        $('#style_no_filter').html(html);
                        $('#style_no_filter').selectpicker('refresh');
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#style_no_filter').html('<option value="">SELECT A ERP ID</option>');
            $('#style_no_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}
 $('#printmanager_filter_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
            e.preventDefault();
            getErpOrderList();
        }
    })

function getErpOrderList()
{
    try {
        let selected_buyer_id    = $('#buyer_filter option:selected').val();
        let selected_customer_id = $('#customer_filter option:selected').val();
        let selected_jobcard_id  = $('#style_no_filter option:selected').val();
        if (selected_customer_id != '') 
        {
            $.ajax({
                url: base_url + "/print-manager/get-erporder-list",
                type: 'GET',
                data: {
                    selected_customer_id: selected_customer_id,
                    selected_buyer_id:selected_buyer_id,
                    erp_id:selected_jobcard_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                    	console.log(data)
                        var i=1;
						var html="";
						$.each(data.erp_orders_list, function (key, item) {
							console.log(item)
							if(item.status == 2)
							{
								var status='label-danger';
							}
							else
							{
								var status='label-info';
							}
							var partial_status = item.partial_status == 0 ? "NO":"YES"; 
							$ref_name = item.reference_name.replace(" ","") 

							if(item.buyer=="orchestra")
							{
								if(item.order_qnty_table_name == "orchestra_hang_tag_orders_qty")
								{
									html += `<tr id="`+item.id+`">
										<td>`+i+`</td>
										<td>`+item.style_no+`</td>
										<td>`+item.buyer_po_number+`</td>
										<td>`+item.zone+`</td>
										<td>`+item.reference_name+`</td>
										<td>`+partial_status +`</td>
										<td><span class="label `+status+`">`+item.status_name+`</span></td>
										<td>`+item.created_date+`</td>
										<td><object style="opacity:0;width:0px;height:0px" id="orchestra_modal_front`+i+`" class="center-block tesco_svg`+i+`" data="`+item.frontPath+`" type="image/svg+xml"></object>
										    <object style="opacity:0;width:0px;height:0px" id="orchestra_modal_back`+i+`" class="center-block tesco_svg`+i+`" data="`+item.backPath+`" type="image/svg+xml"></object>
										    <a class="print_download" href="javascript:void(0)" onclick="DownloadArtwork('`+item.reference_name+`','`+i+`','`+item.template_file+`','`+item.style_no+`','`+item.buyer_po_number+`','`+item.erp_order_id+`','`+item.order_type+`','`+item.created_at+`','print_orchestra','hang_tag','`+item.template_id+`')" ><i class="fa fa-edit"  title="Download Print" data-original-title="Download Print"></i></a>
										</td>
										</tr>`;
								}

							else if(item.order_qnty_table_name == "orchestra_wash_care_label_orders_qty")
								{
									html += `<tr id="`+item.id+`">
										<td>`+i+`</td>
										<td>`+item.style_no+`</td>
										<td>`+item.buyer_po_number+`</td>
										<td>`+item.zone+`</td>
										<td>`+item.reference_name+`</td>
										<td>`+partial_status +`</td>
										<td><span class="label `+status+`">`+item.status_name+`</span></td>
										<td>`+item.created_date+`</td>
										<td><object style="opacity:0;width:0px;height:0px" id="washcare_front`+i+`" class="center-block tesco_svg`+i+`" data="`+item.frontPath+`" type="image/svg+xml"></object>
										    <object style="opacity:0;width:0px;height:0px" id="washcare_back`+i+`" class="center-block tesco_svg`+i+`" data="`+item.backPath+`" type="image/svg+xml"></object>
										    <object style="opacity:0;width:0px;height:0px" id="washcare_middle`+i+`" class="center-block tesco_svg`+i+`" data="`+item.middlePath+`" type="image/svg+xml"></object>
										    <a class="print_download" href="javascript:void(0)" onclick="DownloadArtwork('`+item.reference_name+`','`+i+`','`+item.template_file+`','`+item.style_no+`','`+item.buyer_po_number+`','`+item.erp_order_id+`','`+item.order_type+`','`+item.created_at+`','print_orchestra','wash_care','`+item.template_id+`')" ><i class="fa fa-edit"  title="Download Print" data-original-title="Download Print"></i></a>
										</td>
										</tr>`;
								}
								else{

									html += `<tr id="`+item.id+`">
										<td>`+i+`</td>
										<td>`+item.style_no+`</td>
										<td>`+item.buyer_po_number+`</td>
										<td>`+item.zone+`</td>
										<td>`+item.reference_name+`</td>
										<td>`+partial_status +`</td>
										<td><span class="label `+status+`">`+item.status_name+`</span></td>
										<td>`+item.created_date+`</td>
										<td><object style="opacity:0;width:0px;height:0px" id="orchestra_svg_modal`+i+`" class="center-block tesco_svg`+i+`" data="`+item.template_file+`" type="image/svg+xml"></object><a class="print_download" href="javascript:void(0)" onclick="DownloadArtwork('`+item.reference_name+`','`+i+`','`+item.template_file+`','`+item.style_no+`','`+item.buyer_po_number+`','`+item.erp_order_id+`','`+item.order_type+`','`+item.created_at+`','print_orchestra','stickers','`+item.template_id+`')" ><i class="fa fa-edit"  title="Download Print" data-original-title="Download Print"></i></a>
										</td>
										</tr>`;
								}
							}
                            else{

                            html += `<tr id="`+item.id+`">
										<td>`+i+`</td>
										<td>`+item.style_no+`</td>
										<td>`+item.buyer_po_number+`</td>
										<td>`+item.zone+`</td>
										<td>`+item.reference_name+`</td>
										<td>`+partial_status +`</td>
										<td><span class="label `+status+`">`+item.status_name+`</span></td>
										<td>`+item.created_date+`</td>
										<td><object style="opacity:0;width:0px;height:0px" id="tesco_svg_modal`+i+`" class="center-block tesco_svg`+i+`" data="`+item.template_file+`" type="image/svg+xml"></object><a class="print_download" href="javascript:void(0)" onclick="DownloadArtwork('`+$ref_name+`','`+i+`','`+item.template_file+`','`+item.style_no+`','`+item.buyer_po_number+`','`+item.erp_order_id+`','`+item.order_type+`','`+item.created_at+`','print','stickers','`+item.template_id+`')" ><i class="fa fa-edit"  title="Download Print" data-original-title="Download Print"></i></a><a style="margin-left: 10px;" class="proof_download" href="javascript:void(0)" onclick="DownloadArtwork('`+$ref_name+`','`+i+`','`+item.template_file+`','`+item.style_no+`','`+item.buyer_po_number+`','`+item.erp_order_id+`','`+item.order_type+`','`+item.created_at+`','proof','stickers','`+item.template_id+`')"><i class="fa fa-edit"  title="Download Proof" data-original-title="Download Proof"></i></a>	</td>
										</tr>`;
                            }
							i++;
                        });
                        $('#jobcard_list_table_body').html(html);           
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#style_no_filter').html('<option value="">SELECT A JOBCARD ID</option>');
            $('#style_no_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}
	
</script>

@endsection