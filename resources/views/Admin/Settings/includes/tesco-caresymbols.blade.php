{{-- Tesco add care symbols --}}
<div class="modal fade" id="addTescoSymbolsModal" tabindex="-1" role="dialog" aria-labelledby="tescoSymbolsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tescoSymbolsLabel">Add Symbols</h4>
            </div>
            {!! Form::open(['id' => 'add_tesco_symbols_form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div class="hidden-error"></div>
                <div class="form-group has-feedback">
                    {!! Form::label('care_degree', 'Degree', ['class' => 'form_label']) !!}
                    {{ Form::text('care_degree', null,  ['class' => 'form-control', 'required']) }}
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('symbol_category', 'Category', ['class' => 'form_label']) !!}
                    {{ Form::select('symbol_category', $category, '', ['class' => 'form-control input-sm','required']) }}
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('iso_symbol', 'ISO Symbol (*.svg only)', ['class' => 'form_label']) !!}
                    <div class="row">
                        <div class="col-md-2"> <img width="30px" id="add_iso_symbol" src="/not-available.png"
                                alt="symbol"></div>
                        <div class="col-md-10"> {{ Form::file('iso_symbol', ['required', 'accept' => 'image/svg']) }}
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('astms_symbol', 'Astms Symbol (*.svg only)', ['class' => 'form_label']) !!}
                    <div class="row">
                        <div class="col-md-2"><img width="30px" id="add_astms_symbol" src="/not-available.png"
                                alt="symbol"></div>
                        <div class="col-md-10">{{ Form::file('astms_symbol', ['required', 'accept' => 'image/svg']) }}
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('care_instruction', 'Care Instruction', ['class' => 'form_label']) !!}
                    {{ Form::textarea('care_instruction', null,  ['class' => 'form-control', 'maxlength' => '255', 'minlength' => '1', 'style' => 'resize: none;', 'rows' => '5','required']) }}
                    <div class="help-block with-errors"></div>
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




<div class="modal fade" id="editTescoSymbolsModal" tabindex="-1" role="dialog" aria-labelledby="tescoSymbolsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="tescoSymbolsLabel">Add Symbols</h4>
            </div>
            {!! Form::open(['id' => 'edit_tesco_symbols_form','data-toggle' => 'validator','role' => 'form']) !!}
            <input type="hidden" id="edit_tesco_symbol_id">
            <div class="modal-body">
                <div class="hidden-error"></div>
                <div class="form-group has-feedback">
                    {!! Form::label('edit_care_degree', 'Degree', ['class' => 'form_label']) !!}
                    {{ Form::text('edit_care_degree', null,  ['class' => 'form-control', 'required']) }}
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('edit_symbol_category', 'Category', ['class' => 'form_label']) !!}
                    {{ Form::select('edit_symbol_category', $category, '', ['class' => 'form-control input-sm','required']) }}
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('edit_iso_symbol', 'ISO Symbol (*.svg only)', ['class' => 'form_label']) !!}
                    <div class="row">
                        <div class="col-md-2"> <img width="30px" id="edit_iso_symbol" src="/not-available.png"
                                alt="symbol"></div>
                        <div class="col-md-8"> {{ Form::file('edit_iso_symbol', ['required', 'accept' => 'image/svg']) }}
                        </div>
                        <div class="col-md-2">
                            <img id="iso_image" style="width:30px;height:30px">
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('edit_astms_symbol', 'Astms Symbol (*.svg only)', ['class' => 'form_label']) !!}
                    <div class="row">
                        <div class="col-md-2"><img width="30px" id="edit_astms_symbol" src="/not-available.png"
                                alt="symbol"></div>
                        <div class="col-md-8">{{ Form::file('edit_astms_symbol', ['required', 'accept' => 'image/svg']) }}
                        </div>
                        <div class="col-md-2">
                            <img id="astm_image" style="width:30px;height:30px">
                        </div>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                    {!! Form::label('edit_care_instruction', 'Care Instruction', ['class' => 'form_label']) !!}
                    {{ Form::textarea('edit_care_instruction', null,  ['class' => 'form-control', 'maxlength' => '255', 'minlength' => '1', 'style' => 'resize: none;', 'rows' => '5','required']) }}
                    <div class="help-block with-errors"></div>
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