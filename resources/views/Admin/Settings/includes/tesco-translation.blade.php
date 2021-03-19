{{-- Add Tesco Translation --}}
<div class="modal fade" id="addTescoTranslationModal" tabindex="-1" role="dialog" aria-labelledby="TescoTranslationLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoTranslationLabel"> Translation </h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-translation-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="translation_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('translation_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('translation_english',$value = '', array('class' => 'form-control input-sm','placeholder' => 'english translation','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('translation_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('translation_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'polish translation','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('translation_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('translation_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'czech translation','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('translation_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('translation_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'slovak translation','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('translation_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('translation_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'hungarian translation','required')) !!}
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


{{-- Edit Tesco Translation Modal --}}
<div class="modal fade" id="editTescoTranslationModal" tabindex="-1" role="dialog" aria-labelledby="editTescoTranslationLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoTranslationLabel">Edit Translation</h4>
            </div>
            {!! Form::open(['id' => 'edit-tesco-translation-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="translation_update_error"></div>
                {!! Form::hidden('edit_tesco_translation_id',$value = null, array('id'=>'edit_tesco_translation_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_translation_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_translation_english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Translation in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_translation_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_translation_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Translation in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_translation_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_translation_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Translation in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_translation_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_translation_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Translation in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_translation_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_translation_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Translation in hungarian','required')) !!}
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