@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
	<div class="col-sm-12 col-md-12 main">
		<div class="top-content">
			<div class="buyer-nav-div hidden">
			   <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
			</div>
			{{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
			<div class="clearfix"></div>
		</div>
		<div class="main-content">
			<div class="select-buyer">

			</div>
		</div>

	</div>

@endsection @else
<script type="text/javascript">
	window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes') 
<script src="{{ asset('js/Admin/order.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-reorder.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-orders.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-pi.js') }}"></script>
<script src="{{ asset('js/Admin/parser.js') }}"></script>
<script src="{{ asset('js/Admin/tesco-view-orders.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script>
	nonArtworkOrders();
</script>
@endsection
