{{-- Add Product Description --}}
<div class="modal fade" id="addProductDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="TescocomponentsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescocomponentsLabel">Add Product Description</h4>
            </div>
            {!! Form::open(['id' => 'add-product-description-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="components_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'description name in hungarian','required')) !!}
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

{{-- Edit Product Description Modal --}}
<div class="modal fade" id="editProductDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="editTescoComponentsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescocomponentsLabel">Edit Product Description</h4>
            </div>
            {!! Form::open(['id' => 'edit-product-description-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="product_description_error"></div>
                {!! Form::hidden('edit_tesco_product_description_id',$value = null,
                array('id'=>'edit_tesco_product_description_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'description name in english','required','id' => 'desc_edit_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in polish','id' => 'desc_edit_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in czech','required','id' => 'desc_edit_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'description name in slovak','required','id' => 'desc_edit_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('desc_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('desc_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'description name in hungarian','required','id' => 'desc_edit_hungarian')) !!}
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