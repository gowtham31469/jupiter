@extends('layouts.Erp.app')
<style>
	.highlight_row {
		background: #eee;
	}

	.edit-ratio-list-table tr,
	.edit-ratio-list-table th,
	.edit-ratio-list-table td,
	.edit-single-list-table tr,
	.edit-single-list-table th,
	.edit-single-list-table td {
		border-right-width: 1px !important;
		border-bottom-width: 1px !important;
		border-left-width: 1px !important;
		border-top-width: 1px !important;
		border-left: 1px solid #DDD !important;
		border-top: 1px solid #DDD !important;
		border-right: 1px solid #DDD !important;
		border-bottom: 1px solid #DDD !important;
	}
</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
	<li><a href="{{ route('non-artwork-jobcard-manager') }}">Create Non Artwork Jobcard</a></li>
	<li><a href="{{ route('nat-jobcards-list') }}"  class="active">Non Artwork Jobcards List</a></li>
</ul>

@endsection

@section('content')
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
					{!! Form::open(['id' => 'nat_jobcard_list_filter_form','data-toggle' => 'validator','role' => 'form']) !!}
					<h5 style="margin-left:15px;"><strong>Non Artwork Jobcard Filter</strong></h5>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('buyer_filter', (['' => 'SELECT A BUYER']+ $buyers), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'buyer_filter', 'required', 'onchange' => 'getNATCustomersList(this)')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('customer_filter', (['' => 'SELECT A CUSTOMER']), '', array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true', 'id' => 'customer_filter', 'required', 'onchange' => 'getNatJobcardIDList(this)')) }}
							<div class="help-block with-errors"></div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-4">
						<div class="form-group has-feedback">
							{{ Form::select('jobcard_id_filter',(['' => 'SELECT A JOBCARD ID']),'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','data-live-search'=>'true','id' => 'jobcard_id_filter','required')) }}
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
								<th>Jobcard ID</th>
								<th>Customer Name</th>
								<th>Buyer Name</th>
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
</div>

@include('erp.components.company-selection-modal')

<!-- Job card creation Modal -->
@include('erp.Non-Artwork-Jobmanager.create-jobcard-modal')

{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection 
@section('jqueryIncludes')
<script>
	$(document).ready(function() {
		// disable mousewheel on a input number field when in focus
		// (to prevent Cromium browsers change the value when scrolling)

		$(document).on("wheel", "input[type=number]", function(e) {
			$(this).blur();
		});
		/** [ Toggle bootstrap tooltip ] */
		$('.edit-jobcard').tooltip('show')
		$('.edit-jobcard').tooltip('toggle')

		/** [ Refresh  bootstrap selectpicker and form ] */
		$('#jobcard_filter_form').trigger("reset");
		$('#buyer_filter').selectpicker('refresh');


		/** [ Make companies card active ] */
		$('div.buyers-card').click(function() {

			$('div.buyers-card').removeClass("active");
			$(this).addClass("active");
			$('#hidden-buyer-div').hide();

			/* Save selected company into localdb */
			if ($('.buyers-card.active-buyer.active').length != 0) {
				var company_id = $('.buyers-card.active-buyer.active').data("value");
				var company_name = $('.buyers-card.active-buyer.active').attr("id");
				$('.selected-buyer').text('Selected Company : ' + $('.buyers-card.active-buyer.active').data("name"))
				$('#buyerSelectionModalCenter').modal('hide');

				$.ajax({
					url: base_url + "/erp/set-erp-company-session",
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

		/** [ Show already selected(active) buyer on clicking company menu] */
		$('.company-nav-menu').on('click', function() {
			$("#buyerSelectionModalCenter").modal("show");
		});

		if ($('#company_name').val() != null && $('#company_name').val() != "") {
			$('#' + $('#company_name').val()).addClass('active');
		} else {
			$('div.buyers-card').first().addClass("active");
		}

		$('.selected-buyer').text('Selected Company : ' + $('.buyers-card.active-buyer.active').data("name"))

		$('.company-nav-menu').html($('.company-nav-div').html());

	});
</script>
@endsection