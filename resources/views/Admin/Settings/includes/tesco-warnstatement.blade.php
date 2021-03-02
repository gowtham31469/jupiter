{{-- Add Tesco KidsStatement --}}
<div class="modal fade" id="addTescoKidsStatementModal" tabindex="-1" role="dialog" aria-labelledby="TescoKidsStatementLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoKidsStatementLabel">Add Warning Statement</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-kids-statement-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="kids_stmt_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('kids_stmt_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('kids_stmt_english',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('kids_stmt_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('kids_stmt_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('kids_stmt_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('kids_stmt_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('kids_stmt_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('kids_stmt_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('kids_stmt_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('kids_stmt_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in hungarian','required')) !!}
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

{{-- Edit Tesco KidsStatement Modal --}}
<div class="modal fade" id="editTescoKidsStatementModal" tabindex="-1" role="dialog" aria-labelledby="editTescoKidsStatementLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoKidsStatementLabel">Edit Fabric Name</h4>
            </div>
            {!! Form::open(['id' => 'edit-tesco-kids-statement-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="kids_stmt_update_error"></div>
                {!! Form::hidden('edit_tesco_kids_stmt_id',$value = null, array('id'=>'edit_tesco_kids_stmt_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_kids_stmt_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_kids_stmt_english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fabric name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_kids_stmt_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_kids_stmt_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_kids_stmt_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_kids_stmt_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_kids_stmt_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_kids_stmt_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_kids_stmt_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_kids_stmt_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in hungarian','required')) !!}
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