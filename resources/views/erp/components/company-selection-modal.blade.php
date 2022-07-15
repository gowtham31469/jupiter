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
					<div class="card buyers-card elevation-2dp active-buyer" data-name="{{$company->name}}" data-value="{{$company->company}}" id="{{ strtolower( str_replace(' ','',$company->name)) }}">
						<img src="{{$company->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
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