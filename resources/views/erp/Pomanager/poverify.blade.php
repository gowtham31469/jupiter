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
	<li><a href="" class="active">Po Verify</a></li>
	<li><a href="{{ route('po-orders-list') }}">Orders List</a></li>
</ul>

@endsection @section('content')
<div class="col-sm-12 col-md-12 main">
	<div class="company-nav-div hidden">
		<a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
	</div>
	<div class="main-content">


		<div class="loader">
			<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
		</div>
		<div class="col" id="customer-table">
			<input type="hidden" value="{{ session()->get('company') }}" id="company_id">
			<input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
			<table id="po-list-table" class="table table-hover table-striped table-bordered table-responsive">
				<thead>
					<tr>
						<th>Sl. No</th>
						<th>ERP ID</th>
						<th>Customer Name</th>
						<th>PO Number</th>
						<th>Partial</th>
						<th>Status</th>
						<th>Created date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach($orders as $vals)

					<tr id="{{$vals->id}}">
						<td class="">{{ $loop->iteration }}</td>
						<td>{{$vals->id}}</td>
						<td>{{$vals->company_name}}</td>
						<td>{{$vals->po_no}}</td>
						<td>{{$vals->partial_status == 0 ? "NO":"YES"}}</td>
						@if($vals->status == 2)
						<td><span class="label label-danger">{{$vals->status_name}}</span></td>
						@else
						<td><span class="label label-info">{{$vals->status_name}}</span></td>
						@endif
						<td>{{$vals->po_created_date}}</td>
						<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick="view_order_details('{{ $vals->id }}','{{ $vals->po_no }}','{{ $vals->buyer_id }}','{{ $vals->customer_id }}','{{ $vals->po_file }}')"><i class="fa fa-eye action-button-view"  title="" data-original-title="view Details"></i></a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

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

{{-- Order Details modal --}}
<div class="modal fade bd-example-modal-lg" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg buyer-selection-modal-dialog" role="document">
		<div class="modal-content buyer-selection-modal-content" style="color: #333;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Order Details</h4>
			</div>

			<div class="modal-body buyer-selection-modal-body scroll">
				<label class="label new-po-upload label-info hidden">New po has been uploaded before verify please download the latest pdf</label>
				<label class="label label-danger label-required-error hidden">Please check the checkbox</label>
				<input type="hidden" id="erp_order_id">
				<table id="order-details-table" class="table table-striped table-responsive">
					<thead>
						<tr>
							<th>Sl. No </th>
							<th>ERP ID</th>
							<th>PO Number</th>
							<th>Style No</th>
							<th>Reference</th>
							<th>Size</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Status</th>

						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
				<hr>
				<!--			 <div class="border-div" style="border: 1px #333 solid;border-color:grey;border-radius: 5px; padding: 5px;width: 110px;"><i class="po_complete_check cur-po-comp fa fa-times"></i><label class="po-complete-label" style="margin-left:10px;color:grey">PO Complete</label></div>-->


				<div class="col-md-12 confirmation-container">
					<div class="col-md-5">
						<div class="row">
						<div class="col-md-4"></div>
						<div class="first-padding col-md-4 text-center"> OK</div>
						<div class="first-padding odd-padding col-md-4 text-center">Not OK</div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">PO Number</div>
						<div class="col-md-4 text-center"> 
						<input type="radio" name='po-number-radio' value="ok">
						</div>
						<div class="col-md-4 text-center">
						<input type="radio" name='po-number-radio' value="not-ok">
						</div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">Style Number</div>
						<div class="col-md-4 text-center"> 
						<input type="radio" name='style-number-radio'  value="ok">
						</div>
						<div class="col-md-4 text-center">
						<input type="radio" name='style-number-radio'  value="not-ok">
						</div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">Reference Number</div>
						<div class="col-md-4 text-center"><input type="radio" name='ref-number-radio'  value="ok"></div>
						<div class="col-md-4 text-center"><input type="radio" name='ref-number-radio'  value="not-ok"></div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">Quantity</div>
						<div class="col-md-4 text-center"> <input type="radio" name='qty-radio' value="ok"></div>
						<div class="col-md-4 text-center"><input type="radio" name='qty-radio'  value="not-ok"></div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">Price</div>
						<div class="col-md-4 text-center"><input type="radio" name='price-radio' value="ok"></div>
						<div class="col-md-4 text-center"><input type="radio" name='price-radio' value="not-ok"></div>
						</div>

						<div class="row">
						<div class="col-md-4 first-padding">PO Complete</div>
						<div class="col-md-4 text-center"><input type="radio" name='po-complete-radio'  value="ok"></div>
						<div class="col-md-4 text-center"><input type="radio" name='po-complete-radio' value="not-ok"></div>
						</div>

					</div>
					<div class="col-md-4">
						<label class="first-padding remark-align">Remark : </label>
						<textarea style="resize:vertical" rows=5 id="comment_box" class="form-control comment_box"></textarea>
					</div>
				</div>

			</div>

			<div class="modal-footer buyer-selection-modal-footer">
				
				{!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
				<button class="btn btn-primary btn-submit btn-sm" onclick="submit()" type="button" id="submit_button">Submit</button>
				<a href="" download onclick="change_po_updated_status()" class="po_file btn btn-default pull-left"><i class="fa fa-download" title="" data-original-title="Download Pdf"></i> Download Pdf</a>
			</div>

		</div>
	</div>
</div>

<div class="zoom hidden">
<a class="zoom-fab zoom-btn-large" id="zoomBtn"><i class="fa fa-bars"></i></a>
<ul class="zoom-menu">
<li data-toggle="tooltip" data-placement="bottom" title="Hold Order: {{$hold_orders}}" class="zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out">
<i class="fa fa-edit"></i>
</li>
<li data-toggle="tooltip" data-placement="bottom" title="New Order: {{$new_orders}}" class="zoom-fab zoom-btn-sm zoom-btn-report scale-transition scale-out">
<i class="fa fa-bell"></i>
</li>
</ul>
</div>
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

	function submit() {
		var i = 0;
		var j = 0;
		var error_msg = 0;

		if ($('input[name=po-number-radio]:checked').val() == "not-ok") {
			var po = true;
			i++;
			error_msg++;
		} else if ($('input[name=po-number-radio]:checked').val() == "ok") {
			var po = false;
			error_msg++;
		}
		if ($('input[name=style-number-radio]:checked').val() == "not-ok") {
			var style_no = true;
			i++;
			error_msg++;
		} else if ($('input[name=style-number-radio]:checked').val() == "ok") {
			var style_no = false;
			error_msg++;
		}
		if ($('input[name=ref-number-radio]:checked').val() == "not-ok") {
			var reference = true;
			i++;
			error_msg++;
		} else if ($('input[name=ref-number-radio]:checked').val() == "ok") {
			var reference = false;
			error_msg++;
		}
		if ($('input[name=qty-radio]:checked').val() == "not-ok") {
			var qty = true;
			i++;
			error_msg++;
		} else if ($('input[name=qty-radio]:checked').val() == "ok") {
			var qty = false;
			error_msg++;
		}
		if ($('input[name=price-radio]:checked').val() == "not-ok") {
			var price = true;
			i++;
			error_msg++;
		} else if ($('input[name=price-radio]:checked').val() == "ok") {
			var price = false;
			error_msg++;
		}
		if ($('input[name=po-complete-radio]:checked').val() == "not-ok") {
			var po_complete = true;
			i++;
			j++;
			error_msg++;
		} else if ($('input[name=po-complete-radio]:checked').val() == "ok") {
			var po_complete = false;
			error_msg++;
		}
		if ($('#comment_box').val() != null && $('#comment_box').val() != "") {
			var comment = true;
			var commenttext = $('#comment_box').val();
			i++;
		} else {
			var comment = false;
			var commenttext = $('#comment_box').val();
		}
		if (error_msg < 6) {
			$('.label-required-error').removeClass('hidden')
		} else {
			$('.label-required-error').addClass('hidden')
			$('.btn-submit').prop("disabled", true);
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			var erp_order_id = $('#erp_order_id').val();
			$.ajax({
				url: base_url + "/erp/po-manager/submit-po-verify",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					po: po,
					style_no: style_no,
					reference: reference,
					qty: qty,
					price: price,
					po_complete: po_complete,
					comment: comment,
					commenttext: commenttext,
					count_i: i,
					count_j: j,
					erp_order_id: erp_order_id
				},
				beforeSend: function() {

				},
				success: function(data) {
					$('#orderDetailsModal').modal("hide")
					$('.snackbar_span').html('Po Verified successfully ...!');
					showSnackbar();
					location.reload()
					$('#orderDetailsModal').modal("hide")
				},
				error: function(data) {

				},
				complete: function() {

				}
			})
		}


	}

	/** [showSnackbar function] */
	function showSnackbar() {
		try {
			// Get the snackbar DIV
			var x = document.getElementById("snackbar")

			// Add the "show" class to DIV
			x.className = "show";

			// After 3 seconds, remove the show class from DIV
			setTimeout(function() {
				x.className = x.className.replace("show", "");
			}, 3000);
		} catch (e) {
			console.log("Error:" + e)
		}
	}

	function view_order_details(id, po_no, buyer_id, customer_id, po_file) {

		$('#erp_order_id').val(id)
		$('.po_file').attr('href', po_file)
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: base_url + "/erp/po-manager/order-details",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				id: id,
				buyer_id: buyer_id,
				customer_id: customer_id
			},
			beforeSend: function() {

			},
			success: function(data) {
				$('#submit_button').prop("disabled", true);
				$('#order-details-table tbody').html("");

				var i = 1;

				$(data.orderlist).each(function(key, index) {
					if (index.status == 2 || index.status == 4) {
						var label = 'label label-danger';
					}else if (index.status == 3){
						var label = 'label label-success';
					}else {
						var label = 'label label-info';
					}
					
					if(index.size != null)
					{
						var size = index.size;
					}
					else
					{
						var size = "-";
					}
					$('#order-details-table tbody').append(`
						<tr id="` + index.id + `">
                          	<td class="">` + i + `</td>
							  <td class="erp_td">` + index.erp_order_id + `</td>
							  <td class="po_number_td">` + index.buyer_po_number + `</td>
							  <td class="style_no_td">` + index.style_no + `</td>
							  <td class="reference_td">` + index.template_name + `</td>
							  <td class="size_td">` + size + `</td>
							  <td class="qty_td">` + index.order_quantity + `</td>
							  <td class="price_td">` + index.price + `</td>
							  <td><span class="` + label + `">` + index.status_name + `</span></td>
                          </tr>`)
					i++;
				})

				if (data.orders[0].po_updated_status == 1) {
					$('.new-po-upload').removeClass('hidden')
				}



			},
			error: function(data) {

			},
			complete: function() {

			}
		})
		$('.fa-times').removeClass('active')
		$('.border-div').removeClass('active-border')
		$('.po-complete-label').removeClass('active')
		$('.label-required-error').addClass('hidden')
		$('input:radio[name=po-number-radio]').prop('checked', false);
		$('input:radio[name=style-number-radio]').prop('checked', false);
		$('input:radio[name=ref-number-radio]').prop('checked', false);
		$('input:radio[name=qty-radio]').prop('checked', false);
		$('input:radio[name=price-radio]').prop('checked', false);
		$('input:radio[name=po-complete-radio]').prop('checked', false);
		$('#orderDetailsModal').modal("show")
		$('.btn-submit').prop("disabled", false);
	}

	$('input[type="radio"]').click(function(){
		if ($('input[type=radio]:checked').length == 6)
		{
		 	$('#submit_button').prop("disabled", false);
		}
	});
	
	function change_po_updated_status() {
		var id = $('#erp_order_id').val()
		$('.new-po-upload').addClass('hidden')
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: base_url + "/erp/po-manager/change_po_updated_status",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				id: id,
			},
			beforeSend: function() {

			},
			success: function(data) {


			},
			error: function(data) {

			},
			complete: function() {

			}
		})
	}
</script>

@endsection