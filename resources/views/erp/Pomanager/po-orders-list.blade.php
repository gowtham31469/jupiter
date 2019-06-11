@extends('layouts.Erp.app') @section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{ route('poManager') }}">Po Verify</a></li>
	<li><a href="" class="active">Orders List</a></li>
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
			<form id="create_order_form" data-toggle="validator" class=" container-fluid" role="form" style='border-bottom: 1px solid #cecece;
    padding-bottom: 10px;
    margin-bottom: 20px;'>
				<label for="order_type" class="form_label row" style="display: block;">Search ERP Id</label>
				<div class="form-group row has-feedback">
					<input type="text" onkeypress="return isNumber(event)" required id="erp_id" class="erp_id form-control col-md-2" style="width: auto; margin-right: 10px;">

					<button class="btn btn-primary btn-block col-md-1" style="width: auto;"><span class="glyphicon glyphicon-search"></span></button>
				</div>
				<div class="help-block with-errors"></div>
			</form>

		</div>

	</div>
	<div class="div-table hidden">

	</div>
	<div class="error-div hidden">
		<p style="font-size:14px;">No Results Found!</p>
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

		$('#create_order_form').validator().on('submit', function(e) {
			if (e.isDefaultPrevented()) {

			} else {
				e.preventDefault();
				SubmitOrders()
			}
		})

		function SubmitOrders() {
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/erp/po-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					erp_id: $('#erp_id').val()
				},
				beforeSend: function() {
					//location.reload();
				},
				success: function(data) {


					$('.div-table').html(`<table id="order-details-table" class="table table-striped table-responsive">
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
					</table>`);
					var i = 1;
					if (data.message != "empty") {
						$('.div-table').removeClass('hidden')
						$(data.orderlist).each(function(key, index) {
							if (index.status == 2 || index.status == 4) {
								var label = 'label label-danger';
							} else if (index.status == 3) {
								var label = 'label label-success';
							} else {
								var label = 'label label-info';
							}

							if (index.size != null) {
								var size = index.size;
							} else {
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

						$('.error-div').addClass('hidden')
						var ref = $('#order-details-table').DataTable();
						$("#order-details-table_filter input[type=search]").addClass('form-control');
						$('.dataTables_length select').addClass('form-control');
						$("#order-details-table_filter input[type=search]").attr('placeholder', 'Data Search');

					} else {
						$('.div-table').addClass('hidden')
						$('.error-div').removeClass('hidden')
					}


				},
				error: function(data) {

				},
				complete: function() {

				}
			})
		}

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


	/**
	 * Validation for numeric values
	 * @param  evt  
	 * @return {Boolean} 
	 * @author <praveen>
	 */
	function isNumber(evt) {
		try {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		} catch (e) {
			console.log("Error:" + e)
		}
	}
</script>
@endsection
