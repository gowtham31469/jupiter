{{-- Add Tesco Care Additional Instruction --}}
<div class="modal fade" id="addTescoCareInstructionModal" tabindex="-1" role="dialog" aria-labelledby="TescoCareInstructionLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoCareInstructionLabel">Add Care Additional Instruction</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-care-instruction-form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
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
            {!! Form::open(['id' => 'edit-tesco-care-instruction-form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
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