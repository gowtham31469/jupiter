{{-- Add Tesco Care Additional Instruction --}}
<div class="modal fade" id="addTescoCareInstructionModal" tabindex="-1" role="dialog" aria-labelledby="TescoCareInstructionLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoCareInstructionLabel">Add Care Additional Instruction</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-care-instruction-form', 'data-toggle' => 'validator', 'role' => 'form', 'onsubmit' => 'return false;']) !!}
            <div class="modal-body">
                <div id="care_instruction_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('care_instruction_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('care_instruction_english',$value = '', array('class' => 'form-control input-sm','placeholder' => 'instruction in english','required')) !!}
                            <div class="help-block with-errors text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('care_instruction_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('care_instruction_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'instruction in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('care_instruction_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('care_instruction_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'instruction in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('care_instruction_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('care_instruction_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'instruction in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('care_instruction_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('care_instruction_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'instruction in hungarian','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
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

{{-- Edit Tesco Care Additional Instruction Modal --}}
<div class="modal fade" id="editTescoCareInstructionModal" tabindex="-1" role="dialog" aria-labelledby="editTescoCareInstructionLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoCareInstructionLabel">Edit Care Additional Instruction</h4>
            </div>
            {!! Form::open(['id' => 'edit-tesco-care-instruction-form', 'data-toggle' => 'validator', 'role' => 'form', 'onsubmit' => 'return false;']) !!}
            <div class="modal-body">
                <div id="care_instruction_update_error"></div>
                {!! Form::hidden('edit_tesco_care_instruction_id',$value = null, array('id'=>'edit_tesco_care_instruction_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_care_instruction_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_care_instruction_english',$value = '', array('id' => 'edit_care_instruction_english', 'class' => 'form-control input-sm','placeholder' => 'instruction in english','required')) !!}
                            <div class="help-block with-errors text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_care_instruction_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_care_instruction_polish',$value = '', array('id' => 'edit_care_instruction_polish', 'class' => 'form-control input-sm','placeholder' => 'instruction in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_care_instruction_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_care_instruction_czech',$value = '', array('id' => 'edit_care_instruction_czech', 'class' => 'form-control input-sm','placeholder' => 'instruction in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_care_instruction_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_care_instruction_slovak',$value = '', array('id' => 'edit_care_instruction_slovak', 'class' => 'form-control input-sm','placeholder' => 'instruction in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_care_instruction_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_care_instruction_hungarian',$value = '', array('id' => 'edit_care_instruction_hungarian', 'class' => 'form-control input-sm','placeholder' => 'instruction in hungarian','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<style>
.required-field:after {
    content: " *";
    color: red;
}
.form_label.required-field {
    color: red;
}
.is-invalid {
    border-color: red !important;
}
</style>

<script type="text/javascript">
// Wait for jQuery to be defined
(function checkJQuery() {
    if (typeof jQuery !== 'undefined') {
        // jQuery is loaded, initialize our code
        jQuery(document).ready(function($) {
            // Initialize form validation
            function validateForm(formId) {
                var isValid = true;
                $(formId + ' [required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        // Clear the help-block text to remove the default required message
                        $(this).closest('.form-group').find('.help-block').html('');
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.form-group').find('.help-block').html('');
                    }
                });
                return isValid;
            }

            // Reset form and validation state
            function resetForm(formId) {
                $(formId)[0].reset();
                $(formId + ' .is-invalid').removeClass('is-invalid');
                $(formId + ' .help-block').html('');
                $(formId + ' .alert').remove();
            }

            // Add form submission
            $('#add-tesco-care-instruction-form').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (validateForm('#add-tesco-care-instruction-form')) {
                    addTescoCareInstruction();
                }
                return false;
            });

            // Add button click handler
            $('.btn-primary[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var form = $(this).closest('form');
                if (form.attr('id') === 'add-tesco-care-instruction-form') {
                    if (validateForm('#add-tesco-care-instruction-form')) {
                        addTescoCareInstruction();
                    }
                } else if (form.attr('id') === 'edit-tesco-care-instruction-form') {
                    if (validateForm('#edit-tesco-care-instruction-form')) {
                        updateTescoCareInstruction();
                    }
                }
                return false;
            });

            // Edit form submission
            $('#edit-tesco-care-instruction-form').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (validateForm('#edit-tesco-care-instruction-form')) {
                    updateTescoCareInstruction();
                }
                return false;
            });

            // Reset forms when modals are closed
            $('#addTescoCareInstructionModal').on('hidden.bs.modal', function() {
                resetForm('#add-tesco-care-instruction-form');
            });

            $('#editTescoCareInstructionModal').on('hidden.bs.modal', function() {
                resetForm('#edit-tesco-care-instruction-form');
            });

            // Enable/Disable buttons
            $(document).on('click', '#enableTescoCareInstruction', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').attr('id');
                enableTescoCareInstruction(id, csrf_token);
            });

            $(document).on('click', '#disableTescoCareInstruction', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').attr('id');
                disableTescoCareInstruction(id, csrf_token);
            });

            // Edit button
            $(document).on('click', '.edit-tesco-care-instruction', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').attr('id');
                getTescoCareInstructionById(id);
            });

            // Remove HTML5 validation messages
            $('form').on('invalid', 'input, select, textarea', function(e) {
                e.preventDefault();
                $(this).addClass('is-invalid');
            });

            // Add input validation handlers
            $('input[required]').on('input', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid');
                    $(this).closest('.form-group').find('.help-block').html('');
                }
            });
        });
    } else {
        // jQuery not loaded yet, check again in 100ms
        setTimeout(checkJQuery, 100);
    }
})();
</script> 