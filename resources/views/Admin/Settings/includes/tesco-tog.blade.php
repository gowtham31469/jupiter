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
                            {!! Form::label('tog_value', 'Tog Value', ['class' => 'form_label']) !!}
                            {!! Form::text('tog_value',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Tog value','required','id'=>'tog_value')) !!}
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
                            {!! Form::label('edit_tog_value', 'Tog Value', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_tog_value',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Tog value','required','id' => 'edit_tog_value')) !!}
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