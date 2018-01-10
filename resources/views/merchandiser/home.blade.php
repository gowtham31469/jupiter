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
    	<div class="loader"></div>
        <table class="table table-striped table-hover table-bordered" id="datatable-example-2">
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
                    <td>{{ $order->OrderDate }}</td>
                    <td>{{ $order->DeliveryDate }}</td>
                    @if($order->Status == "Pending")
                    <td><span class="label label-Pending">{{  $order->Status }}</span></td>
                    @else
                    <td><span class="label label-Approved">Approved</span></td>
                    @endif
                    <td><a class="view-wcl" href="#"><span><i class="fa fa-eye fa-button" title="view"></i> View</span></a></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <div class="left-panel col-md-12">
        <div class="div-max">
            <div class="col-md-7">
                <h4>Washcare Label</h4>
                <hr>
                 <div class="section-1 sections">
                    <div class="sample-artwork" style="text-align:center"><img style="height: calc(100vh - 150px); box-sizing: border-box;" src="../../images/wcl_default.png"></div>
                </div>
            </div>
            <div class="col-md-5">
                <h4>Quantity Information</h4>
                <hr>
               <div class="quantity"></div>
                <div class="merchandiser-area">
                {!! Form::open(['id' => 'add_merchandiser_comment_form','data-toggle' => 'validator','role' => 'form']) !!}
                {!! Form::hidden('wcl_id',$value = null, array('id'=>'wcl_id','class' => 'form-control input-sm')) !!}
                        <div class="form-group has-feedback">
                          {!! Form::label('comment', 'Comment', ['class' => 'form_label']) !!}
                          {!! Form::textarea('comment',$value = '', array('class' => 'form-control ','placeholder' => 'Enter your comments here','required','style'=>'resize:none;','rows'=>10,'cols'=>50)) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="buttons pull-right" style="margin-top:10px;">
			                {!! Form::submit('Approve', ['class' => 'btn btn-primary submitbutton','name' => 'approve'] ) !!}
			                {!! Form::submit('Reject', ['class' => 'btn btn-warning submitbutton','name' => 'reject'] ) !!}
			                {!! Form::button('Close', ['class' => 'btn btn-danger remove'] ) !!}
			            </div>
               {!! Form::close() !!}
                </div>
            </div>
       
        </div>
    </div>
    @section('jqueryIncludes')
    <script src="{{ asset('js/Merchandiser/merchandiser.js') }}"></script>
    <script>
    	/** get edit buyer details **/
       $(document).on('click','.view-wcl',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           viewWcl(id);
       });
        
        
        $('.remove').click(function(){
             $('.left-panel').removeClass('active');
        })
        /** Add comment form validation **/
      $('#add_merchandiser_comment_form').validator().on('submit', function (e) {
	        if (e.isDefaultPrevented())
	        {

	        }
	        else
	        {
	        	e.preventDefault();
	        	var buttonpressed;
			    $('.submitbutton').click(function() {
			          buttonpressed = $(this).attr('name')
			          if (buttonpressed === "approve") 
			          {
			          	approve()
			          }
			          else
			          {
			          	reject()
			          }
			    })
	        }
      })
      function approve()
      {
      	 try{
         var comment    = $('#comment').val();
         var id         = $('#wcl_id').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');
        
         $.ajax({
             url: base_url + "/merchandiser/set-wcl-merchandiser-approve-comment",
             method: 'POST',
             data: {_token:csrf_token,comment:comment,id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Approved Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('.left-panel').removeClass('active');
                  }
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {
                       $(".loader").hide();
                       $(".loader").css('margin-top','0%');
             }
		         })
		     } catch (e) {
		     console.log("Error:"+e)
		   }
      }
      function reject()
      {
      	 try{
         var comment    = $('#comment').val();
         var id         = $('#wcl_id').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');
        
         $.ajax({
             url: base_url + "/merchandiser/set-wcl-merchandiser-reject-comment",
             method: 'POST',
             data: {_token:csrf_token,comment:comment,id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Rejected Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('.left-panel').removeClass('active');
                  }
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {
                       $(".loader").hide();
                       $(".loader").css('margin-top','0%');
             }
		         })
		     } catch (e) {
		     console.log("Error:"+e)
		   }
      }
      </script>
    @endsection