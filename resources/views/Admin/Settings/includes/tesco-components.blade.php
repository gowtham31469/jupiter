{{-- Add Tesco components --}}
<div class="modal fade" id="addTescocomponentsModal" tabindex="-1" role="dialog" aria-labelledby="TescocomponentsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescocomponentsLabel">Add Components Name</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-components-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="components_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'components name in hungarian','required')) !!}
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

{{-- Edit Tesco components Modal --}}
<div class="modal fade" id="editTescoComponentsModal" tabindex="-1" role="dialog" aria-labelledby="editTescoComponentsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescocomponentsLabel">Edit Components Name</h4>
            </div>
            {!! Form::open(['id' => 'edit_tesco_components_form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="components_update_error"></div>
                {!! Form::hidden('edit_tesco_components_id',$value = null,
                array('id'=>'edit_tesco_components_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'components name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'components name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('comp_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('comp_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'components name in hungarian','required')) !!}
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