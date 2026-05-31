{{-- Add Part of Set Modal --}}
<div class="modal fade" id="addPartOfSetModal" tabindex="-1" role="dialog" aria-labelledby="TescoPartOfSetLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoPartOfSetLabel">Add Part of Set</h4>
            </div>
            {!! Form::open(['id' => 'add-part-of-set-form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
            <div class="modal-body">
                <div id="part_of_set_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('part_of_set_name', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('part_of_set_name', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in English', 'required', 'id' => 'part_of_set_name']) !!}
                            <div class="help-block with-errors text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('part_of_set_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('part_of_set_polish', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Polish', 'id' => 'part_of_set_polish']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('part_of_set_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('part_of_set_czech', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Czech', 'id' => 'part_of_set_czech']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('part_of_set_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('part_of_set_slovak', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Slovak', 'id' => 'part_of_set_slovak']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('part_of_set_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('part_of_set_hungarian', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Hungarian', 'id' => 'part_of_set_hungarian']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

{{-- Edit Part of Set Modal --}}
<div class="modal fade" id="editPartOfSetModal" tabindex="-1" role="dialog" aria-labelledby="editTescoPartOfSetLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoPartOfSetLabel">Edit Part of Set</h4>
            </div>
            {!! Form::open(['id' => 'edit-part-of-set-form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
            <div class="modal-body">
                <div id="part_of_set_update_error"></div>
                {!! Form::hidden('edit_part_of_set_id', null, ['id' => 'edit_part_of_set_id', 'class' => 'form-control input-sm']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_part_of_set_name', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_part_of_set_name', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in English', 'required', 'id' => 'edit_part_of_set_name']) !!}
                            <div class="help-block with-errors text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_part_of_set_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_part_of_set_polish', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Polish', 'id' => 'edit_part_of_set_polish']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_part_of_set_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_part_of_set_czech', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Czech', 'id' => 'edit_part_of_set_czech']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_part_of_set_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_part_of_set_slovak', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Slovak', 'id' => 'edit_part_of_set_slovak']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_part_of_set_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_part_of_set_hungarian', '', ['class' => 'form-control input-sm', 'placeholder' => 'Text in Hungarian', 'id' => 'edit_part_of_set_hungarian']) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default btn-sm', 'data-dismiss' => 'modal']) !!}
                {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
