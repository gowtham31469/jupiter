{{-- Add Tesco fabrics --}}
<div class="modal fade" id="addTescoFabricsModal" tabindex="-1" role="dialog" aria-labelledby="TescoFabricsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoFabricsLabel">Add Fabric Name</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-fabric-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="fabric_error"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('fabric_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('fabric_english',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('fabric_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('fabric_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('fabric_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('fabric_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('fabric_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('fabric_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('fabric_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('fabric_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'fabric name in hungarian','required')) !!}
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


{{-- Edit Tesco Fabric Modal --}}
<div class="modal fade" id="editTescoFabricModal" tabindex="-1" role="dialog" aria-labelledby="editTescoFabricLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoFabricLabel">Edit Fabric Name</h4>
            </div>
            {!! Form::open(['id' => 'edit-tesco-fabric-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="fabric_update_error"></div>
                {!! Form::hidden('edit_tesco_fabric_id',$value = null, array('id'=>'edit_tesco_fabric_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_fabric_english', 'English', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_fabric_english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fabric name in english','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_fabric_polish', 'Polish', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_fabric_polish',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in polish','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_fabric_czech', 'Czech', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_fabric_czech',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in czech','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_fabric_slovak', 'Slovak', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_fabric_slovak',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in slovak','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_fabric_hungarian', 'Hungarian', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_fabric_hungarian',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Fabric name in hungarian','required')) !!}
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