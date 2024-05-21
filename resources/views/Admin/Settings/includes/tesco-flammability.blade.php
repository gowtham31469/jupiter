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
                            {!! Form::label('flammability_text', 'Flammability Text', ['class' => 'form_label']) !!}
                            {!! Form::text('flammability_text',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Flammability text','required','id'=>'flammability_text')) !!}
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
                            {!! Form::label('edit_flammability_text', 'Flammability Text', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_flammability_text',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Flammability Text','required','id' => 'edit_flammability_text')) !!}
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