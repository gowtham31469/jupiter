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
tfoot {
     display: table-header-group;
}
</style>
@section('navbar')

<ul class="nav navbar-nav dynamic-menu">
	<li><a href="#" class="active">Job Manager</a></li>
</ul>

@endsection @section('content')
<div class="col-sm-12 col-md-12 main">
	  <div class="company-nav-div">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
        </div>
	<div class="main-content">


		<div class="loader">
			<div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
		</div>
		<div class="col" id="customer-table">
			<input type="hidden" value="{{ session()->get('company') }}" id="company_id">
			<input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
			<table id="po-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
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
				<tfoot>
					<tr>
						<th>Sl. No</th>
						<th>ERP ID</th>
						<th>Customer name</th>
						<th>PO Number</th>
						<th>Style Number</th>
						<th>Reference Number</th>
						<th>Delivery Date</th>
					</tr>
				</tfoot>
				<tbody>
					@foreach($orders as $vals)

					<tr id="{{$vals->id}}">
						<td class="">{{ $loop->iteration }}</td>
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

{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>

@endsection @section('jqueryIncludes')
<script>
$(document).ready(function() {
	var base_url = "<?php echo  url('/') ?>";
    $('#po-list-table').DataTable( {
		/** Table column filter functionality **/
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( "<option value='"+d+"'>"+d+"</option>" )
                } );
            } );
        }
    } );
	$("#po-list-table_filter input[type=search]").addClass('form-control');
	$('.dataTables_length select').addClass('form-control');
	$("#po-list-table_filter input[type=search]").attr('placeholder', 'Data Search');
} );
	/** [ Make buyers card active ] */
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
	//alert($('#company_name').val())
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

</script>

@endsection
