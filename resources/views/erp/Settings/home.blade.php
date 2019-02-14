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
            <li id="4" onclick="finishType()"><a>Finish Type</a></li>
          </ul>
        </div>
        <div class="inner-content">
        </div>
	</div>
</div>

{{-- Add Dispatch Mode Modal --}}
<div class="modal fade" id="addERPDispModeModal" tabindex="-1" role="dialog" aria-labelledby="addERPDispModeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addERPDispModeModalLabel">Add Dispatch Mode</h4>
            </div>
            {!! Form::open(['id' => 'add_dispatch_mode_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="dispatch_mode_result"></div>
                    <div class="form-group has-feedback">
                        {!! Form::label('dispatch_mode', 'Dispatch Mode', ['class' => 'form_label']) !!}
                        {!! Form::text('dispatch_mode',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Dispatch Mode','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('dispatch_mode_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('dispatch_mode_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                    {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{{-- Edit Dispatch Mode Modal --}}
<div class="modal fade" id="editERPDispModeModal" tabindex="-1" role="dialog" aria-labelledby="editERPDispModeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editERPDispModeModalLabel">Edit Dispatch Mode</h4>
            </div>
            {!! Form::open(['id' => 'edit_dispatch_mode_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="edit_dispatch_mode_result"></div>
                {!! Form::hidden('edit_dispatch_mode_id',$value = null, array('id'=>'edit_dispatch_mode_id','class' => 'form-control input-sm')) !!}
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_dispatch_mode', 'Dispatch Mode', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_dispatch_mode',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Dispatch Mode','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_dispatch_mode_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_dispatch_mode_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                    {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
        /** call to dispatchMode() on page load **/
        dispatchMode()

        /** Add dispatch mode form validation **/
        $('#add_dispatch_mode_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
                e.preventDefault();
                /* call addDispatchMode function from erp-settings.js */
                addDispatchMode();
            }
        });

        /** get edit dispatch mode details **/
        $(document).on('click','.edit-dispatch-mode',function(e){
            var id = $(this).closest('tr').attr('id');
            e.preventDefault();
            /* call getDispatchModeById function from erp-settings.js */
            getDispatchModeById(id);
        });

        /** submit edit dispatch mode form **/
        $('#edit_dispatch_mode_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
            e.preventDefault();
            /* call editDispatchMode function from erp-settings.js */
            editDispatchMode();
            }
        });

        /** To Enable  dispatch_mode  on click **/
        $(document).on('click', '#make_dispatch_mode_enabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeDispatchModeEnabled function from erp-settings.js*/
            makeDispatchModeEnabled(id)
        });

        /** To disable dispatch_mode  on click **/
        $(document).on('click', '#make_dispatch_mode_disabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeDispatchModeDisabled function from erp-settings.js*/
            makeDispatchModeDisabled(id)
        });
	});

</script>

@endsection