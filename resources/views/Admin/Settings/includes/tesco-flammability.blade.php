{{-- Add Flammability --}}
<div class="modal fade" id="addFlammabilityModal" tabindex="-1" role="dialog" aria-labelledby="TescoFlammabilityLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoFlammabilityLabel">Add Flammability</h4>
            </div>
            {!! Form::open(['id' => 'add-flammability-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="flammability_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in english','required','id'=>'flammability_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in polish','id'=>'flammability_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in czech','required','id'=>'flammability_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in slovak','required','id'=>'flammability_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Flammability name in hungarian','required','id'=>'flammability_hungarian')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('template_id', 'Select Template', ['class' => 'form_label']) !!}
                            {{ Form::select('template_id', $templates, '', ['class' => 'form-control input-sm','required','id'=>'template_id_mm']) }}
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

{{-- Edit Flammability Modal --}}
<div class="modal fade" id="editFlammabilityModal" tabindex="-1" role="dialog" aria-labelledby="editTescoFlammabilityModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoFlammabilityLabel">Edit Flammability</h4>
            </div>
            {!! Form::open(['id' => 'edit-flammability-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="flammability_error"></div>
                {!! Form::hidden('edit_tesco_flammability_id',$value = null,
                array('id'=>'edit_tesco_flammability_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Flammability name in english','required','id' => 'flammability_edit_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in polish','id' => 'flammability_edit_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in czech','required','id' => 'flammability_edit_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability name in slovak','required','id' => 'flammability_edit_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('flammability_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Flammability name in hungarian','required','id' => 'flammability_edit_hungarian')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('template_id', 'Select Template', ['class' => 'form_label']) !!}
                            {{ Form::select('template_id', $templates, '', ['class' => 'form-control input-sm','required','id'=>'edit_template_id_mm']) }}
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