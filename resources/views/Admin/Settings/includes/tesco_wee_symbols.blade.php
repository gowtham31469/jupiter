{{-- Add addWeeSymbolModal --}}
   <div class="modal fade" id="addWeeSymbolModal" tabindex="-1" role="dialog" aria-labelledby="addTescoBrandsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoBrandsLabel">Add Wee Symbol</h4>
                </div>
                {!! Form::open(['id' => 'add_wee_symbol_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="tesco_wee_symbol_result"></div>
                      <div class="hidden-error"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('wee_symbol_text', 'Wee Symbol Text', ['class' => 'form_label']) !!}
                          {!! Form::text('wee_symbol_text',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Wee Symbol Text','required', 'maxlength' => '25')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('wee_symbol', 'Wee Symbol (*.svg only)', ['class' => 'form_label']) !!}
                            <div class="row">
                                <div class="col-md-2"> <img width="30px" id="add_iso_symbol" src="/not-available.png"
                                    alt="symbol"></div>
                                    <div class="col-md-10"> {{ Form::file('wee_symbol', ['required', 'accept' => 'image/svg']) }}
                                    </div>
                                </div>
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
    

{{-- Edit WeeSymbolModal --}}
   <div class="modal fade" id="editWeeSymbolModal" tabindex="-1" role="dialog" aria-labelledby="addTescoBrandsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoBrandsLabel">Edit Wee Symbol</h4>
                </div>
                {!! Form::open(['id' => 'edit_wee_symbol_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="tesco_wee_symbol_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_wee_symbol_text', 'Wee Symbol Text', ['class' => 'form_label']) !!}

                            {!! Form::hidden('edit_wee_symbol_id',$value = null,
                            array('id'=>'edit_wee_symbol_id','class' => 'form-control input-sm')) !!}
                          {!! Form::text('edit_wee_symbol_text',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Wee Symbol Text','required', 'maxlength' => '25','id'=>'edit_wee_symbol_text')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_wee_symbol', 'Wee Symbol (*.svg only)', ['class' => 'form_label']) !!}
                            <div class="row">
                                <div class="col-md-2"> <img width="30px" id="edit_wee_symbol" src="/not-available.png"
                                    alt="symbol"></div>
                                    <div class="col-md-8"> {{ Form::file('edit_wee_symbol', ['required', 'accept' => 'image/svg']) }}
                                    </div>
                                    <div class="col-md-2">
                                        <img id="wee_symbol_image" style="width:30px;height:30px">
                                    </div>
                                </div>
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