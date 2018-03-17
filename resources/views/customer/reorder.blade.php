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
		<div class="buyer-div hidden">
			<select class="js-example-responsive buyer-select show-tick show-menu-arrow" data-live-search="true">
                <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option title="Selected Buyer : {{$buyer->buyer_name}}" value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="main-content card">
		<div class="select-buyer">
			<img src="../../images/select-buyer.png" class="img-responsive">
		</div>
	</div>
</div>
<div class="left-panel col-md-12" style="display: none;">
	<div class="loader"></div>
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
	$('.logo').html('ReOrder');
	$('.select-buyer').click(function() {
		$('.select-buyer').addClass('hidden');
	})
	$('.buyer-nav-select').html($('.buyer-div').html())
	if (localStorage.getItem("reorder_buyer") === null) {
		$('.main-content').removeClass('card')

	} else if (localStorage.getItem("reorder_buyer") === 'orchestra') {
		$('.main-content').addClass('card')
		reOrder();
	} else {
		$('.main-content').addClass('card')
		reOrderTesco();
	}
	$('.buyer-select').val(localStorage.getItem("reorder_buyer_id"))
	$('.buyer-nav-select .buyer-select').selectpicker({
		style: 'btn-default',
		size: 4
	});
	$('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
		if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
			localStorage.setItem("reorder_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
			localStorage.setItem("reorder_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
			if (localStorage.getItem("reorder_buyer") === 'orchestra') {
				$('.main-content').addClass('card')
				reOrder();
			} else {
				$('.main-content').addClass('card')
				reOrderTesco();
			}
		} else {
			localStorage.removeItem("reorder_buyer");
			localStorage.removeItem("reorder_buyer_id");
			$('.main-content').removeClass('card')
			location.reload()
			
		}
	});
</script>

@endsection