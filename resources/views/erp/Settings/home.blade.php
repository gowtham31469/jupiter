@extends('layouts.Erp.app')
@section('navbar')

@endsection @section('content')
<div class="col-sm-12 col-md-12 main">
	<div class="main-content">
		<div class="erp-sidebar-main style">
          <h4>Settings</h4>
          <ul class="erp-sidebar meta-data-list">
            <li id="1" onclick="dispatchMode()"><a class="li-setting dispatchmodeli active">Dispatch Mode</a></li>
            <li id="2" onclick="machines()"><a class="li-setting machineli">Machine</a></li>
            <li id="3" onclick="teethNo()"><a class="li-setting teethnoli">Teeth Number</a></li>
            <li id="4" onclick="finishType()"><a class="li-setting finishtypeli">Finish Type</a></li>
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




{{-- Add Machine Modal --}}
<div class="modal fade" id="addERPMachineModal" tabindex="-1" role="dialog" aria-labelledby="addERPMachineModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addERPMachineModalLabel">Add Machine</h4>
            </div>
            {!! Form::open(['id' => 'add_machine_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="machine_result"></div>
                    <div class="form-group has-feedback">
                        {!! Form::label('machine', 'Machine', ['class' => 'form_label']) !!}
                        {!! Form::text('machine',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Machine','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('machine_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('machine_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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
{{-- Edit Machine Modal --}}
<div class="modal fade" id="editERPMachineModal" tabindex="-1" role="dialog" aria-labelledby="editERPMachineModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editERPMachineModalLabel">Edit Machine</h4>
            </div>
            {!! Form::open(['id' => 'edit_machine_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="edit_machine_result"></div>
                {!! Form::hidden('edit_machine_id',$value = null, array('id'=>'edit_machine_id','class' => 'form-control input-sm')) !!}
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_machine', 'Machine', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_machine',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Machine','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_machine_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_machine_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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



{{-- Add Teeth Number Modal --}}
<div class="modal fade" id="addERPTeethNoModal" tabindex="-1" role="dialog" aria-labelledby="addERPTeethNoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addERPTeethNoModalLabel">Add Teeth Number</h4>
            </div>
            {!! Form::open(['id' => 'add_teethno_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="teethno_result"></div>
                    <div class="form-group has-feedback">
                        {!! Form::label('teethno', 'Teeth Number', ['class' => 'form_label']) !!}
                        {!! Form::text('teethno',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Teeth Number','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('teethno_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('teethno_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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
{{-- Edit Teeth Number Modal --}}
<div class="modal fade" id="editERPTeethNoModal" tabindex="-1" role="dialog" aria-labelledby="editERPTeethNoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editERPTeethNoModalLabel">Edit Teeth Number</h4>
            </div>
            {!! Form::open(['id' => 'edit_teethno_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="edit_teethno_result"></div>
                {!! Form::hidden('edit_teethno_id',$value = null, array('id'=>'edit_teethno_id','class' => 'form-control input-sm')) !!}
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_teethno', 'Teeth Number', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_teethno',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Teeth Number','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_teethno_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_teethno_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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



{{-- Add Finish Type Modal --}}
<div class="modal fade" id="addERPFinishTypeModal" tabindex="-1" role="dialog" aria-labelledby="addERPFinishTypeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addERPFinishTypeModalLabel">Add Finish Type</h4>
            </div>
            {!! Form::open(['id' => 'add_finishtype_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="finishtype_result"></div>
                    <div class="form-group has-feedback">
                        {!! Form::label('finishtype', 'Finish Type', ['class' => 'form_label']) !!}
                        {!! Form::text('finishtype',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Finish Type','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('finishtype_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('finishtype_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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
{{-- Edit Finish Type Modal --}}
<div class="modal fade" id="editERPFinishTypeModal" tabindex="-1" role="dialog" aria-labelledby="editERPFinishTypeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editERPFinishTypeModalLabel">Edit Finish Type</h4>
            </div>
            {!! Form::open(['id' => 'edit_finishtype_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                <div id="edit_finishtype_result"></div>
                {!! Form::hidden('edit_finishtype_id',$value = null, array('id'=>'edit_finishtype_id','class' => 'form-control input-sm')) !!}
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_finishtype', 'Finish Type', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_finishtype',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Finish Type','required')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_finishtype_desc', 'Description', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_finishtype_desc',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required')) !!}
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
        
        
        
        
       /** Add Machine form validation **/
        $('#add_machine_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
                e.preventDefault();
                /* call addDispatchMode function from erp-settings.js */
                addMachine();
            }
        });
        
        /** Add Teeth Number validation **/
        $('#add_teethno_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
                e.preventDefault();
                /* call addDispatchMode function from erp-settings.js */
                addTeethNo();
            }
        }); 
        
        /** Add Finish Type validation **/
        $('#add_finishtype_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
                e.preventDefault();
                /* call addDispatchMode function from erp-settings.js */
                addFinishType();
            }
        });

        /** get edit machine details **/
        $(document).on('click','.edit-machine',function(e){
            var id = $(this).closest('tr').attr('id');
            e.preventDefault();
            /* call getMachineById function from erp-settings.js */
            getMachineById(id);
        });
        
         /** get edit teeth no details **/
        $(document).on('click','.edit-teethno',function(e){
            var id = $(this).closest('tr').attr('id');
            e.preventDefault();
            /* call getTeethNoById function from erp-settings.js */
            getTeethNoById(id);
        });
        
         /** get edit finish type details **/
        $(document).on('click','.edit-finishtype',function(e){
            var id = $(this).closest('tr').attr('id');
            e.preventDefault();
            /* call getFinishTypeById function from erp-settings.js */
            getFinishTypeById(id);
        });

        /** submit edit machine form **/
        $('#edit_machine_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
            e.preventDefault();
            /* call editDispatchMode function from erp-settings.js */
            editMachine();
            }
        });
        
          /** submit edit teeth no form **/
        $('#edit_teethno_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
            e.preventDefault();
            /* call editDispatchMode function from erp-settings.js */
            editTeethNo();
            }
        });
        
          /** submit edit finish type form **/
        $('#edit_finishtype_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented())
            {

            }
            else
            {
            e.preventDefault();
            /* call editDispatchMode function from erp-settings.js */
            editFinishType();
            }
        });

        /** To Enable  dispatch_mode  on click **/
        $(document).on('click', '#make_machine_enabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeDispatchModeEnabled function from erp-settings.js*/
            makeMachineEnabled(id)
        });

        /** To disable dispatch_mode  on click **/
        $(document).on('click', '#make_machine_disabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeDispatchModeDisabled function from erp-settings.js*/
            makeMachineDisabled(id)
        });
        
            /** To Enable  teeth no  on click **/
        $(document).on('click', '#make_teethno_enabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeTeethNoEnabled function from erp-settings.js*/
            makeTeethNoEnabled(id)
        });
        
         /** To disable teeth no  on click **/
        $(document).on('click', '#make_teethno_disabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeTeethNoDisabled function from erp-settings.js*/
            makeTeethNoDisabled(id)
        });
        
         /** To Enable  finish type  on click **/
        $(document).on('click', '#make_finishtype_enabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeFinishTypeEnabled function from erp-settings.js*/
            makeFinishTypeEnabled(id)
        });

       
        /** To disable finish type  on click **/
        $(document).on('click', '#make_finishtype_disabled', function(e) {
            var id = $(this).closest('tr').attr('id');
            /* call makeFinishTypeDisabled function from erp-settings.js*/
            makeFinishTypeDisabled(id)
        });
	});

</script>

@endsection