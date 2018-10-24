@extends('layouts.Erp.app')
<style>
.action-button-view
{
	border: 1px #333 solid;
	background-color: #fff;
	border-radius: 2px;
	padding: 3px;
	margin-right: 10px;
}
	.red-icon{
  color: grey;
  float: right;
}
.active{
    color: #dc3545 !important;
}
.active-border{
	border-color: #dc3545 !important;
}
.bad{
  background-color: #dc3545;
  color:#fff;
}
.cur-po{
	cursor:pointer;
	color: grey;
    float: right;
}
.cur-po-comp{
	cursor:pointer;
	color: grey;
}
.btn-loader {
  border: 8px solid #f3f3f3 !important;
  border-radius: 50% !important;
  border-top: 8px solid #3498db !important;
  width: 50px !important;
  height: 50px !important;
  -webkit-animation: spin 2s linear infinite !important; /* Safari */
  animation: spin 2s linear infinite !important;
}
/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@section('navbar') 

<ul class="nav navbar-nav dynamic-menu">
    <li><a  href="#" class="active">Po Verify</a></li>
</ul>

@endsection
@section('content')
<div class="col-sm-12 col-md-12 main">
	 <div class="main-content">
        
		 
		  <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                  <div class="col" id="customer-table">
                    <table id="po-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>ERP ID</th>
                          <th>Customer name</th>
                          <th>PO Number</th>
							<th>Status</th>
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
							  @if($vals->status == 2)
								<td><span class="label label-danger">{{$vals->status_name}}</span></td>
							  @else
								<td><span class="label label-info">{{$vals->status_name}}</span></td>
						      @endif
							<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick="view_order_details('{{ $vals->id }}','{{ $vals->po_no }}','{{ $vals->buyer_id }}','{{ $vals->customer_id }}','{{ $vals->po_file }}')"><i class="fa fa-eye action-button-view" data-placement="top" title="" data-original-title="view Details"></i></a></td>
                          </tr> 
						  @endforeach
                         </tbody>
                     </table>
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
			  <a href="" download onclick="change_po_updated_status()" class="po_file" style="float:right;margin-right:12px"><i class="fa fa-download" data-placement="top" title="" data-original-title="Download Pdf"></i> Download Pdf</a>
			  <h4 class="modal-title" id="myModalLabel">Order Details</h4>
		  </div>

		  <div class="modal-body buyer-selection-modal-body">
			  <label class="label new-po-upload label-info hidden">New po has been uploaded before verify please download the latest pdf</label>
			  <input type="hidden" id="erp_order_id">
			  <table id="order-details-table" class="table table-bordered table-responsive" style="width:100%;margin-top: 15px;">
                      <thead>
                        <tr>
                          <th>Sl. No </th>
                          <th>ERP ID</th>
							  <th>PO Number <i class="po_number_check cur-po fa fa-times"></i></th>
                          <th>Style No <i class="style_no_check cur-po fa fa-times"></i></th>
							<th>Reference <i class="reference_check cur-po fa fa-times"></i></th>
						  <th>Size</th>
                          <th>Quantity <i class="qty_check cur-po fa fa-times"></i></th>
						   <th>Price <i class="price_check cur-po fa fa-times"></i></th>
							<th>Status</th>
						
                        </tr>
                      </thead>
                      <tbody>
						 
                      </tbody>
                     </table>
			 <div class="border-div" style="border: 1px #333 solid;border-color:grey;border-radius: 5px; padding: 5px;width: 110px;"><i class="po_complete_check cur-po-comp fa fa-times"></i><label class="po-complete-label" style="margin-left:10px;color:grey">PO Complete</label></div>
			  <label>Remark : </label>
			  <textarea style="resize:vertical" rows=5 id="comment_box" class="form-control comment_box"></textarea>
		  </div>

		  <div class="modal-footer buyer-selection-modal-footer">
				  {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
			  	  <button class="btn btn-primary btn-submit btn-sm" data-dismiss="modal" onclick="submit()">Submit</button>
		  </div>

	   </div>
  </div>
</div>
@endsection
@section('jqueryIncludes')
 <script>
	 var base_url = "<?php echo  url('/') ?>";
 	 var table =   $('#po-list-table').DataTable();
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
	 
	 function submit()
	 {
		 var i=0;
		 var j=0;
		 
		 if($('.po_number_check').hasClass('active'))
		 {
			 var po = true;
			 i++;
		 }
		 else
		 {
			 var po = false;
		 }
		 if($('.style_no_check').hasClass('active'))
		 {
			 var style_no = true;
			 i++;
		 }
		 else
		 {
			 var style_no = false;
		 }
		 if($('.reference_check').hasClass('active'))
		 {
			 var reference = true;
			 i++;
		 }
		 else
		 {
			 var reference = false;
		 }
		 if($('.qty_check').hasClass('active'))
		 {
			var qty = true;
			 i++;
		 }
		 else
		 {
			 var qty = false;
		 }
		 if($('.price_check').hasClass('active'))
		 {
			 var price = true;
			 i++;
		 }
		 else
		 {
			 var price = false;
		 }
		 if($('.po_complete_check').hasClass('active'))
		 {
			 var po_complete = true;
			 i++;
			 j++;
		 }
		 else
		 {
			 var po_complete = false;
		 }
		 if($('#comment_box').val() != null && $('#comment_box').val() != "")
		 {
			 var comment = true;
			 var commenttext	 = $('#comment_box').val();
			 i++;
		 }
		 else
		 {
			 var comment = false;
			 var commenttext = $('#comment_box').val();
		 }
		 //$('.btn-submit').addClass('btn-loader')
		 
		 var csrf_token = $('meta[name="csrf-token"]').attr('content');
		 var erp_order_id = $('#erp_order_id').val();
		$.ajax({
			url: base_url + "/erp/po-manager/submit-po-verify",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				po:po,
				style_no:style_no,
				reference:reference,
				qty:qty,
				price:price,
				po_complete:po_complete,
				comment:comment,
				commenttext:commenttext,
				count_i:i,
				count_j:j,
				erp_order_id:erp_order_id
			},
			beforeSend: function () {

			},
			success: function (data) {
				 $('#orderDetailsModal').modal("hide")
			     $('.snackbar_span').html('Po Verified successfully ...!');
				 showSnackbar();
				 location.reload()
			},
			error: function (data) {

			},
			complete: function () {

			}
		})
		 
	 }
	 
	/** [showSnackbar function] */
	function showSnackbar() {
		try {
			// Get the snackbar DIV
			var x = document.getElementById("snackbar")

			// Add the "show" class to DIV
			x.className = "show";

			// After 3 seconds, remove the show class from DIV
			setTimeout(function () {
				x.className = x.className.replace("show", "");
			}, 3000);
		} catch (e) {
			console.log("Error:" + e)
		}
	}
	 
	 function view_order_details(id,po_no,buyer_id,customer_id,po_file)
	 {
		 
		$('#erp_order_id').val(id)
		$('.po_file').attr('href',po_file)
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		
		$.ajax({
			url: base_url + "/erp/po-manager/order-details",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				id:id,
				buyer_id:buyer_id,
				customer_id:customer_id
			},
			beforeSend: function () {

			},
			success: function (data) {
				$('#order-details-table tbody').html("");
				
				var i=1;
				
				$(data.orderlist).each(function (key, index) {
					if(index.status == 2)
					{
						var label = 'label label-danger';
					}
					else
					{
						var label = 'label label-info';
					}
					$('#order-details-table tbody').append(`
						<tr id="`+index.id+`">
                          	<td class="">`+ i +`</td>
							  <td class="erp_td">`+index.erp_order_id+`</td>
							  <td class="po_number_td">`+po_no+`</td>
							  <td class="style_no_td">`+index.style_no+`</td>
							  <td class="reference_td">`+index.template_name+`</td>
							  <td class="size_td">`+index.size+`</td>
							  <td class="qty_td">`+index.order_quantity+`</td>
							  <td class="price_td">`+index.price+`</td>
							  <td><span class="`+label+`">`+index.status_name+`</span></td>
                          </tr>`)
					i++;
				})
				
				if(data.orders[0].po_updated_status == 1)
				{
					$('.new-po-upload').removeClass('hidden')
				}
				
				
				
			},
			error: function (data) {

			},
			complete: function () {

			}
		})
		 $('.fa-times').removeClass('active')
		 $('.border-div').removeClass('active-border') 
		 $('.po-complete-label').removeClass('active')
		 
		 $('#orderDetailsModal').modal("show")
	 }
	 
	 function change_po_updated_status()
	 {
		 var id = $('#erp_order_id').val()
		 $('.new-po-upload').addClass('hidden')
		 var csrf_token = $('meta[name="csrf-token"]').attr('content');
		
		 $.ajax({
			url: base_url + "/erp/po-manager/change_po_updated_status",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				id:id,
			},
			beforeSend: function () {

			},
			success: function (data) {
				
				
			},
			error: function (data) {

			},
			complete: function () {

			}
		 })
	 }
	 
</script>

@endsection