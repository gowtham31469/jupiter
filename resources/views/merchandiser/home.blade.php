@extends('layouts.merchandiser.app') @section('content')
<style>
.left-panel.active {
    background: rgba(0, 0, 0, 0.56);
    margin-right: 0% !important;
    transition: 0.5s ease all;
}
</style>
<div class="col-sm-12 col-md-12 main">
    <div class="main-content">
        <table class="table table-striped table-hover table-bordered" id="datatable-example-2" style="font-size:12px;">
            <thead>
                <tr>
                    <th>UVC</th>
                    <th>Artwork</th>
                    <th>Supplier Name</th>
                    <th>Merchandiser Name</th>
                    <th>Season</th>
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr id="{{$order->id}}">
                    <td>{{ $order->UVC }}</td>
                    <td>{{ $order->Artwork }}</td>
                    <td>{{ $order->SupplierName }}</td>
                    <td>{{ $order->merchandiser_name }}</td>
                    <td>{{ $order->Season }}</td>
					@if($order->OrderDate != "")
                        <td>{{ date('d-m-Y', strtotime($order->OrderDate)) }}</td>
                    @else
                        <td>-</td>
                    @endif
					@if($order->DeliveryDate != "")
                        <td>{{ date('d-m-Y', strtotime($order->DeliveryDate)) }}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>
					@php
						$temp = explode(",",$order->template_file);
					@endphp

					<object style="opacity:0;width:0px;height:0px" id="{{ $order->id }}_template_front_modal" class="center-block tesco_svg" data="{{ $temp [0] }}" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="{{ $order->id }}_template_back_modal" class="center-block tesco_svg" data="{{ $temp [1] }}" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="{{ $order->id }}_template_middle_modal" class="center-block tesco_svg" data="{{ $temp [2] }}" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="{{ $order->id }}_template_static_modal" class="center-block tesco_svg" data="{{ $temp [3] }}" type="image/svg+xml"></object>

					<div id="svgdiv" style="visibility:hidden;position: absolute;">
						<svg id="ean-13"></svg>
					</div>

					<span class="label label-{{ str_replace(' ','',$order->Status)}}">{{  $order->Status }}</span></td>
                    <td><a href="#" class="view-wcl" data-toggle="tooltip" title="View"><i class="fa fa-eye fa-button filterbtn options" aria-hidden="true"></i></a><a href="#" class="download-wcl" onclick='printWashDivOs("template_front_modal","template_back_modal","template_middle_modal","template_static_modal","{{ $order->UVC }}","","merchandiser","{{ $order->id }}","orchestra_wash_care_label_orders","print","os","","")' data-toggle="tooltip" title="Download"><i class="fa fa-download fa-button filterbtn options" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    {{-- While clicking view , Show left panel --}}

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Quantity Information</h4>
      </div>
      {!! Form::open(['id' => 'add_merchandiser_comment_form','data-toggle' => 'validator','role' => 'form']) !!}
			<div class="modal-body">
				<div class="quantity"></div>
					{!! Form::hidden('wcl_id',$value = null, array('id'=>'wcl_id','class' => 'form-control input-sm')) !!}
					<div class="form-group has-feedback">
						{!! Form::label('comment', 'Comment', ['class' => 'form_label']) !!}
						{!! Form::textarea('comment',$value = '', array('class' => 'form-control ','placeholder' => 'Enter your comments here','required','style'=>'resize:none;','rows'=>5,'cols'=>50)) !!}
						<div class="help-block with-errors"></div>
					</div>
			</div>
      <div class="modal-footer" style="padding-bottom: 0px !important;">
	  		{!! Form::button('Close', ['class' => 'btn btn-default input-sm remove'] ) !!}
			{!! Form::submit('Approve', ['class' => 'btn btn-primary input-sm submitbutton','name' => 'approve'] ) !!}
			{!! Form::submit('Reject', ['class' => 'btn btn-danger input-sm submitbutton','name' => 'reject'] ) !!}
      </div>
	{!! Form::close() !!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    <!-- <div class="left-panel col-md-12" style="display: none;">
    	<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="div-max">
            <div class="col-md-7">
                <h4>Washcare Label</h4>
                <hr>
                 <div class="section-1 sections">
                    <div class="sample-artwork scroll" style="text-align:center;height: calc(100vh - 120px);overflow-y: scroll;"></div>
                </div>
            </div>
            <div class="col-md-5 scroll" style="overflow-y: scroll;height: 100%;">
                <h4>Quantity Information</h4>
                <hr>
               <div class="quantity"></div>
                <div class="merchandiser-area">
                {!! Form::open(['id' => 'add_merchandiser_comment_form','data-toggle' => 'validator','role' => 'form']) !!}
                {!! Form::hidden('wcl_id',$value = null, array('id'=>'wcl_id','class' => 'form-control input-sm')) !!}
                        <div class="form-group has-feedback">
                          {!! Form::label('comment', 'Comment', ['class' => 'form_label']) !!}
                          {!! Form::textarea('comment',$value = '', array('class' => 'form-control ','placeholder' => 'Enter your comments here','required','style'=>'resize:none;','rows'=>5,'cols'=>50)) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="buttons pull-right" style="margin-top:10px;">
			                {!! Form::button('Close', ['class' => 'btn btn-default input-sm remove'] ) !!}
			                {!! Form::submit('Approve', ['class' => 'btn btn-primary input-sm submitbutton','name' => 'approve'] ) !!}
			                {!! Form::submit('Reject', ['class' => 'btn btn-danger input-sm submitbutton','name' => 'reject'] ) !!}
			            </div>
               {!! Form::close() !!}
                </div>
            </div>
       
        </div>
    </div> -->

    @section('jqueryIncludes')
    <script src="{{ asset('js/Merchandiser/merchandiser.js') }}"></script>
    <script src="{{ asset('js/common/orchestra-care-label-download.js') }}"></script>
    <script>
	$( document ).ready(function() {
		$('#add_merchandiser_comment_form').trigger("reset");
		$("#datatable-example-2_filter input").addClass('form-control input-sm');
	    $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
	    $('#datatable-example-2_length select').addClass('form-control input-sm');
	    $('.selectpicker').selectpicker();
		$('[data-toggle="tooltip"]').tooltip(); 
	});

	/** get edit buyer details **/
   $(document).on('click','.view-wcl',function(e){
	$("[data-toggle='tooltip']").tooltip('hide');
   	   $('#myModal').modal('show')
       var id = $(this).closest('tr').attr('id');
       e.preventDefault();
       /* call viewWcl function from /Merchandiser/merchandiser.js */
       viewWcl(id);
   });
        
    /** Remove panel */
    $('.remove').click(function(){
		$('#myModal').modal('hide')
         $('#add_merchandiser_comment_form').trigger("reset");
    })

	/** Get button name approve/reject */
	var buttonpressed;
	$('.submitbutton').click(function() {
	      buttonpressed = $(this).attr('name')
	      
	})

    /** Add comment form validation **/
    $('#add_merchandiser_comment_form').validator().on('submit', function (e) {
    if (e.isDefaultPrevented())
    {

    }
    else
    {
    	e.preventDefault();
         if (buttonpressed === "approve") 
          {
          	/* call approve function from /Merchandiser/merchandiser.js */
          	approve()
          }
          else
          {
          	/* call reject function from /Merchandiser/merchandiser.js */
          	reject()
          }
    }
  })

  /** Filter funtionality */
  var $tableSel = $('#datatable-example-2');
	$tableSel.DataTable({
		"pageLength": 25,
		initComplete: function() {
			$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
			$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
			$('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectSeasonCode"> <option disabled selected>Select Season Code</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

			/*For Custom Filters*/
			this.api().columns().every(function(i) {
				var column = this;
				if (i === 0 || i === 2 || i === 4) {
					var select;
					switch (i) {
						case 0:
							select = $('#uvcNumber')
							break;
						case 2:
							select = $('#supplierName')
							break;
						case 4:
							select = $('#selectSeasonCode')
							break;
					}
					select.on('change', function() {
						var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);

						column
							.search(val ? '^' + val + '$' : '', true, false)
							.draw();
					});
					select.append('<option value=""> </option>');
					column.data().unique().sort().each(function(d, j) {
						cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
						select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
					});
				}
			});
		}
	});

	/** Reset filter */
	$("#reset").on("click", function() {
		$('#uvcNumber').val('').change();
		$('#supplierName').val('').change();
		$('#selectSeasonCode').val('').change();
		$.fn.dataTableExt.afnFiltering.length = 0;
		$tableSel.dataTable().fnDraw();
		$('input[name="daterange"]').val('Filter by Order Date');
		$('input[name="daterange"]').datepicker("refresh");
		$('.selectpicker').selectpicker('val', 1);
		$('.selectpicker').selectpicker('refresh');

	});

	$('input[name="daterange"]').daterangepicker({

	},
	function(start, end, label) {

		$.fn.dataTableExt.afnFiltering.length = 0;
		$tableSel.dataTable().fnDraw();
		filterByDate(5, start, end); // We call our filter function
		$tableSel.dataTable().fnDraw();
		
	});

	$('input[name="daterange"]').val('Filter by Order Date');

	/*Clear the filter. Unlike normal filters in Datatables,
	custom filters need to be removed from the afnFiltering array.*/
	$('.cancelBtn').on('click', function(e) {
		e.preventDefault();
		$.fn.dataTableExt.afnFiltering.length = 0;
		$tableSel.dataTable().fnDraw();
	});

	function showFilter() {
	$('.filter-window').toggle();
	}

	/* Our main filter function
	 * We pass the column location, the start date, and the end date
	 */
	var filterByDate = function(column, startDate, endDate) {
	/*Custom filter syntax requires pushing the new filter to the global filter array*/
	$.fn.dataTableExt.afnFiltering.push(
		function(oSettings, aData, iDataIndex) {
			var columnDate = aData[column].toString().split("-").reverse().join("-");
			var rowDate = normalizeDate(columnDate),
				start   = normalizeDate(startDate),
				end     = normalizeDate(endDate);

			/*If our date from the row is between the start and end*/
			if (start <= rowDate && rowDate <= end) {
				return true;
			} else if (rowDate >= start && end === '' && start !== '') {
				return true;
			} else if (rowDate <= end && start === '' && end !== '') {
				return true;
			} else {
				return false;
			}
		}
	);
	};
	
	/*converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220*/
	var normalizeDate = function(dateString) {
		var date = new Date(dateString);
		var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
		return normalized;
	}
	/** filter functionality ends */
      </script>
    @endsection