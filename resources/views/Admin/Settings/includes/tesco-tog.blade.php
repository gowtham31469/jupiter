{{-- Add Tog --}}
<div class="modal fade" id="addTogModal" tabindex="-1" role="dialog" aria-labelledby="TescoTogLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoTogLabel">Add Tog</h4>
            </div>
            {!! Form::open(['id' => 'add-tog-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="tog_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in english','required','id'=>'tog_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in polish','id'=>'tog_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in czech','required','id'=>'tog_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in slovak','required','id'=>'tog_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Tog name in hungarian','required','id'=>'tog_hungarian')) !!}
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

{{-- Edit Tog Modal --}}
<div class="modal fade" id="editTogModal" tabindex="-1" role="dialog" aria-labelledby="editTescoTogModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoTogLabel">Edit Tog</h4>
            </div>
            {!! Form::open(['id' => 'edit-tog-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="tog_error"></div>
                {!! Form::hidden('edit_tesco_tog_id',$value = null,
                array('id'=>'edit_tesco_tog_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Tog name in english','required','id' => 'tog_edit_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in polish','id' => 'tog_edit_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in czech','required','id' => 'tog_edit_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog name in slovak','required','id' => 'tog_edit_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('tog_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Tog name in hungarian','required','id' => 'tog_edit_hungarian')) !!}
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