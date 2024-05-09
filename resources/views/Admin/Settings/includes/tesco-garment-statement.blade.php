{{-- Add Garment Statements --}}
<div class="modal fade" id="addGarmentStatementsModal" tabindex="-1" role="dialog" aria-labelledby="TescoGarmentStatementsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoGarmentStatementsLabel">Add Garment Statements</h4>
            </div>
            {!! Form::open(['id' => 'add-garment-statement-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="garment_statement_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in english','required','id'=>'garment_statement_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in polish','id'=>'garment_statement_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in czech','required','id'=>'garment_statement_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in slovak','required','id'=>'garment_statement_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Garment Statement name in hungarian','required','id'=>'garment_statement_hungarian')) !!}
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

{{-- Edit Garment Statements Modal --}}
<div class="modal fade" id="editGarmentStatementsModal" tabindex="-1" role="dialog" aria-labelledby="editTescoGarmentStatementsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoGarmentStatementsLabel">Edit Garment Statements</h4>
            </div>
            {!! Form::open(['id' => 'edit-garment-statement-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="garment_statement_error"></div>
                {!! Form::hidden('edit_tesco_garment_statement_id',$value = null,
                array('id'=>'edit_tesco_garment_statement_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Garment Statement name in english','required','id' => 'garment_statement_edit_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in polish','id' => 'garment_statement_edit_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in czech','required','id' => 'garment_statement_edit_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Garment Statement name in slovak','required','id' => 'garment_statement_edit_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('garment_statement_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('garment_statement_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Garment Statement name in hungarian','required','id' => 'garment_statement_edit_hungarian')) !!}
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