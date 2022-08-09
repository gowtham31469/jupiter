{{-- Add addWoolmArkModal --}}
   <div class="modal fade" id="addWoolmArkModal" tabindex="-1" role="dialog" aria-labelledby="addTescoBrandsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoBrandsLabel">Add Woolmark Logo</h4>
                </div>
                {!! Form::open(['id' => 'add_woolmark_logo_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="tesco_wool_mark_logo_result"></div>
                      <div class="hidden-error"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('woolmark_logo_text', 'Woolmark Log Text', ['class' => 'form_label']) !!}
                          {!! Form::text('woolmark_logo_text',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Woolmark Log Text','required', 'maxlength' => '25')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('woolmark_symbol', 'Woolmark Logo (*.svg only)', ['class' => 'form_label']) !!}
                            <div class="row">
                                <div class="col-md-2"> <img width="30px" id="add_woolmark_symbol" src="/not-available.png"
                                    alt="symbol"></div>
                                    <div class="col-md-10"> {{ Form::file('woolmark_symbol', ['required', 'accept' => 'image/svg+xml']) }}
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
    

{{-- Edit editWoolmarkModal --}}
   <div class="modal fade" id="editWoolmarkModal" tabindex="-1" role="dialog" aria-labelledby="addTescoBrandsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoBrandsLabel">Edit Woolmark Logo</h4>
                </div>
                {!! Form::open(['id' => 'edit_woolmark_logo_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="tesco_wool_mark_logo_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_woolmark_logo_text', 'Woolmark Logo', ['class' => 'form_label']) !!}

                            {!! Form::hidden('edit_woolmark_logo_id',$value = null,
                            array('id'=>'edit_woolmark_logo_id','class' => 'form-control input-sm')) !!}
                          {!! Form::text('edit_woolmark_logo_text',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Woolmark Logo Text','required', 'maxlength' => '25','id'=>'edit_woolmark_logo_text')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_woolmark_logo', 'Wee Symbol (*.svg only)', ['class' => 'form_label']) !!}
                            <div class="row">
                                <div class="col-md-2"> <img width="30px" id="edit_woolmark_logo_image" src="/not-available.png"
                                    alt="symbol"></div>
                                    <div class="col-md-8"> {{ Form::file('edit_woolmark_logo', ['required', 'accept' => 'image/svg+xml']) }}
                                    </div>
                                    <div class="col-md-2">
                                        <img id="woolmark_logo_image" style="width:30px;height:30px">
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