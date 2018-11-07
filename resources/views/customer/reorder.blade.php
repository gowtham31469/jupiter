@extends('layouts.Customer.app') @if (Auth::guard("web_customer")->user()) @section('content')	
<style>
	.pad-top {
		padding-top: 100px;
	}
	
	.image-container img {
		object-fit: cover;
		width: 100px;
		height: 100px;
	}
	
	.image-container h4 {
		font-weight: bold;
	}
	
	.how_it_works_repeat h4,.how_it_works_reorder h4 {
		padding-top: 30px;
		font-weight: bold;
		text-align: center;
	}
	
	.how_it_works_repeat p,.how_it_works_reorder p {
		color: rgba(0, 0, 0, 0.28);
	}
	
	.select_div {
		padding-top: 10px;
		margin: 0 auto;
		width: 350px;
	}
	
	.select_div select {
		border: 1px solid #f7f7f7;
		box-shadow: -1px 2px 4px 1px #0000000f;
		outline: none;
	}
	
	.select_div select:focus {
		border-color: rgba(255, 255, 255, 0);
		outline: 0;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(255, 255, 255, 0);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(255, 255, 255, 0);
	}
	
	.pad-top {
		padding-top: 100px;
	}
	
	.image-container img {
		object-fit: cover;
		width: 100px;
		height: 100px;
	}
	
	.image-container h4 {
		font-weight: bold;
	}
	
	.list_div {
		padding-top: 50px;
	}
	
	.list-group img {
		width: 30px;
		height: 30px;
	}
	
	.select_div {
		padding-top: 10px;
		margin: 0 auto;
		width: 350px;
	}
	
	.select_div select {
		border: 1px solid #f7f7f7;
		box-shadow: -1px 2px 4px 1px #0000000f;
		outline: none;
	}
	
	.select_div select:focus {
		border-color: rgba(255, 255, 255, 0);
		outline: 0;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(255, 255, 255, 0);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(255, 255, 255, 0);
	}
	
	.list_div li {
		border: 1px solid #f7f7f7;
		box-shadow: -1px 2px 4px 1px #0000000f;
		outline: none;
	}
	
	.btn-ghost {
		background-color: #fff;
		border: 1px solid #818080;
		color: #818080;
		padding: 4px 22px;
		border-radius: 3px;
		transition: 0.3s ease;
		font-size: 10px;
	}
	
	.btn-ghost:hover {
		background-color: #333;
		color: #fff;
	}
	
	.list_div p {
		margin: 5px 0px 5px 0px;
	}
	
	.list_div p.uvc {
		font-weight: bold;
	}
	
	.list_div p.name {
		font-weight: 400;
		color: #666;
		font-size: 13px;
	}
	
	.list_div span.label {
		vertical-align: bottom;
	}
	
	.sticker {
		border-radius: 10px;
		background-color: #1A237E;
		padding: 3px 15px;
	}
	
	.labels {
		border-radius: 10px;
		background-color: #00BCD4;
		padding: 3px 15px;
	}
	
	.left-panel.active {
		background: rgba(0, 0, 0, 0.56);
		margin-right: 0% !important;
		transition: 0.5s ease all;
	}
	
	.nav-tabs>li {
		float: none;
		display: inline-block;
		zoom: 1;
	}
	
	.nav-tabs {
		text-align: center;
		border-bottom: 1px solid transparent;
	}
	
	.nav-tabs>li.active>a,
	.nav-tabs>li.active>a:focus,
	.nav-tabs>li.active>a:hover {
		border: 2px solid transparent;
		border-bottom: 2px solid;
		color: #273238;
	}
	
	.nav-tabs>li>a:hover {
		border-color:  rgba(39, 50, 56, 0.66);
		background-color: transparent;
		border: 2px solid transparent;
		border-bottom: 2px solid;
	}
</style>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<div class="col-sm-12 col-md-12 main">
	<div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>

	<div class="main-content card">
		<div class="select-buyer">
		</div>
	</div>
</div>
<div class="left-panel col-md-12" style="display: none;">
	<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
	<div class="div-max">
		<div class="col-md-7">
			<h4 class="artwork_name"></h4>
			<hr>
			<div class="section-1 sections">
				<div class="sample-artwork scroll" style="text-align:center;height: calc(100vh - 120px);overflow-y: scroll;"></div>
			</div>
		</div>
		<div class="col-md-5 scroll" style="overflow-y: scroll;height: 100%;">
			<h4>Quantity Information</h4>
			<hr>

			<div class="merchandiser-area">

				<div class="quantity"></div>
				<div class="buttons pull-right" style="margin-top:10px;">

				</div>
			</div>
		</div>

	</div>
</div>

{{-- Show snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>
<!-- Buyer selection Modal -->
<div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog buyer-selection-modal-dialog" role="document">
         <div class="modal-content buyer-selection-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Buyer</h4>
                 </div>
                 <div class="modal-body buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                      <strong>Warning!</strong> Select a buyer to proceed.
                    </div>
                    @foreach($buyers as $buyer)
                     <a class="buyer-card-link" style="cursor: pointer;">
                         <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$buyer->buyer_name}}"  data-value ="{{$buyer->id}}" id="{{ strtolower($buyer->buyer_name) }}">
                           <img src="{{$buyer->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
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
@endsection @else
<script type="text/javascript">
	window.location = "{{ url('/customer') }}";
</script>
@endif @section('jqueryIncludes')
<script src="{{ asset('js/Customer/orchestra-reorder.js') }}"></script>
<script src="{{ asset('js/Customer/tesco-reorder.js') }}"></script>
<script>
	/** Remove panel */
	$('.remove').click(function() {
		$('.left-panel').removeClass('active');
	})
	$(document).ready(function() {
	    /** [ Make buyers card active ] */
	    $('div.buyers-card').click(function(){
	      $('div.buyers-card').removeClass("active");
	      $(this).addClass("active");
	      $('#hidden-buyer-div').hide();

	       /* Save selected buyer into localdb */
	        if ($('.buyers-card.active-buyer.active').length != 0) 
	        {
	            $('#hidden-buyer-div').hide();
	            localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
	            localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
	            $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
	            $('#buyerSelectionModalCenter').modal('hide');
	            if (localStorage.getItem("buyer") === 'orchestra') {
					$('.main-content').addClass('card')
					reOrder();
				} else {
					$('.main-content').addClass('card')
					reOrderTesco();
				}
	        } else {
	            localStorage.removeItem("buyer");
	            localStorage.removeItem("buyer_id");
	            $('#hidden-buyer-div').show();
	        }
	    });

	    /** [ Show already selected(active) buyer on clicking buyer menu] */
	    $('.buyer-nav-menu').on('click', function() {
	        if (localStorage.getItem("buyer") === null) 
	        {
	           $("#buyerSelectionModalCenter").modal("show");
	           
	        } 
	        else 
	        {
	            $('#'+localStorage.getItem("buyer")).addClass('active');
	            $("#buyerSelectionModalCenter").modal("show");
	        }
	    });

	    /** [If only 1 buyer , auto select that buyer] */
	    var buyer_count = $('#invisible__count_id').val();
	    if(buyer_count <= 1) 
	    {
	        $('#hidden-buyer-div').hide();
	        $('div.buyers-card').addClass("active");
	        localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
	        localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
	        $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
	    }   
	    
	    /** add buyer selection menu into navbar */
	    $('.buyer-nav-menu').html($('.buyer-nav-div').html());

	    /** Load selected buyer from local-storage on pageload */
	    if (localStorage.getItem("buyer") === null) 
	    {
	    	$('.main-content').removeClass('card')
	        $(document).ready(function() {
	           $("#buyerSelectionModalCenter").modal("show");
	        })
	    } 
	    else 
	    {
	        $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
	        if (localStorage.getItem("buyer") === 'orchestra') {
				$('.main-content').addClass('card')
				reOrder();
			} else {
				$('.main-content').addClass('card')
				reOrderTesco();
			}
	    }

	    /** To refresh modal while hidden **/
	    $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
	      $('div.buyers-card').removeClass("active");
	      if (localStorage.getItem("buyer") === null) 
	      {
	      	  $('.main-content').removeClass('card')
	          $(document).ready(function() {
	             $("#buyerSelectionModalCenter").modal("show");
	          })
	      } 
	      else 
	      {
	          $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
	          if (localStorage.getItem("buyer") === 'orchestra') {
  				$('.main-content').addClass('card')
  				reOrder();
  			   } else {
  			   	$('.main-content').addClass('card')
  			   	reOrderTesco();
  			   }
	      }
	     })
	    
	});
</script>

@endsection