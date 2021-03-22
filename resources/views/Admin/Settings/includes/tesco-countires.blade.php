{{-- Add Tesco Country --}}
<div class="modal fade" id="addTescoCountryModal" tabindex="-1" role="dialog" aria-labelledby="TescoCountryLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoCountryLabel">Add Country Name</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-countries-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="country_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('english',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Country name in hungarian','required')) !!}
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

{{-- Edit Tesco countries Modal --}}
<div class="modal fade" id="editTescoCountryModal" tabindex="-1" role="dialog" aria-labelledby="editTescoCountryLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoCountryLabel">Edit Country Name</h4>
            </div>
            {!! Form::open(['id' => 'edit_tesco_country_form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="country_update_error"></div>
                {!! Form::hidden('edit_tesco_country_id',$value = null,
                array('id'=>'edit_tesco_country_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_english',$value = '', array('class' => 'form-control input-sm
                            ','placeholder' => 'Country name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_polish',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_czech',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_slovak',$value = '', array('class' => 'form-control input-sm','placeholder'
                            => 'Country name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_hungarian',$value = '', array('class' => 'form-control
                            input-sm','placeholder' => 'Country name in hungarian','required')) !!}
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