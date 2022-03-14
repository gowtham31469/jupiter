{{-- Add Product Benefits --}}
<div class="modal fade" id="addProductBenefitsModal" tabindex="-1" role="dialog" aria-labelledby="TescobenefitsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescobenefitsLabel">Add Product Benefits</h4>
            </div>
            {!! Form::open(['id' => 'add-product-benefits-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="benefits_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in english','required','id'=>'benefits_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in polish','required','id'=>'benefits_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in czech','required','id'=>'benefits_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in slovak','required','id'=>'benefits_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'benefits name in hungarian','required','id'=>'benefits_hungarian')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('template_id', 'Select Template', ['class' => 'form_label']) !!}
                            {{ Form::select('template_id', $templates, '', ['class' => 'form-control input-sm','required','id'=>'template_id']) }}
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

{{-- Edit Product Benefits Modal --}}
<div class="modal fade" id="editProductBenefitsModal" tabindex="-1" role="dialog" aria-labelledby="editTescobenefitsModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescobenefitsLabel">Edit Product Benefits</h4>
            </div>
            {!! Form::open(['id' => 'edit-product-benefits-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="product_benefits_error"></div>
                {!! Form::hidden('edit_tesco_product_benefits_id',$value = null,
                array('id'=>'edit_tesco_product_benefits_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'benefits name in english','required','id' => 'benefits_edit_english')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in polish','required','id' => 'benefits_edit_polish')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in czech','required','id' => 'benefits_edit_czech')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'benefits name in slovak','required','id' => 'benefits_edit_slovak')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('benefits_edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('benefits_edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'benefits name in hungarian','required','id' => 'benefits_edit_hungarian')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('template_id', 'Select Template', ['class' => 'form_label']) !!}
                            {{ Form::select('template_id', $templates, '', ['class' => 'form-control input-sm','required','id'=>'edit_template_id']) }}
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

