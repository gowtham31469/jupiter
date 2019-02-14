@extends('layouts.Erp.app')
@section('navbar')

@endsection @section('content')
<div class="col-sm-12 col-md-12 main">
	<div class="main-content">
		<div class="erp-sidebar-main style">
          <h4>Settings</h4>
          <ul class="erp-sidebar meta-data-list">
            <li id="1" onclick="dispatchMode()"><a class="active">Dispatch Mode</a></li>
            <li id="2" onclick="machine()"><a>Machine</a></li>
            <li id="3" onclick="teethNo()"><a>Teeth Number</a></li>
            <li id="4"  onclick="finishType()"><a>Finish Type</a></li>
          </ul>
        </div>
        <div class="content">
        </div>
	</div>
</div>

{{-- Snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>
@endsection @section('jqueryIncludes')
<script>
	$(document).ready(function() {
	
		
	});

</script>

@endsection