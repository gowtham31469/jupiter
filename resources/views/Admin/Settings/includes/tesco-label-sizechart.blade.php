{{-- Add Tesco Label Size --}}
<div class="modal fade" id="addTescoLabelSizeChartModal" tabindex="-1" role="dialog" aria-labelledby="TescoLabelSizeChartLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="TescoLabelSizeChartLabel">Add Label Size</h4>
            </div>
            {!! Form::open(['id' => 'add-tesco-labelsize-chart-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="labelsize_error"></div>
                <div class="row">
                    <div class="col-md-12 secondary-departments">
                        <div class="form-group has-feedback">
                            {!! Form::label('size_department', 'Department', ['class' => 'form_label']) !!}
                            <select id="size_department" name="size_department" class="form-control input-sm" required="True"></select>
                         <div class="help-block with-errors"></div>
                       </div>
                    </div>
                    <div class="col-md-12 secondary-categories">
                        {{-- appends checkbox on modal show --}}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('labelsize_dept_title', 'Title', ['class' => 'form_label']) !!}
                            {!! Form::text('labelsize_dept_title',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Department Title','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('labelsize_uksize', 'UK Size', ['class' => 'form_label']) !!}
                            {!! Form::text('labelsize_uksize',$value = '', array('class' => 'form-control input-sm','placeholder' => 'UK Size','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('labelsize_cesize', 'CE Size', ['class' => 'form_label']) !!}
                            {!! Form::text('labelsize_cesize',$value = '', array('class' => 'form-control input-sm','placeholder' => 'CE Size','required')) !!}
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

{{-- Edit Tesco Label Size --}}
<div class="modal fade" id="editTescoPrimarySizeModal" tabindex="-1" role="dialog" aria-labelledby="editTescoPrimarySizeLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="editTescoPrimarySizeLabel">Edit PrimarySize</h4>
            </div>
            {!! Form::open(['id' => 'edit-tesco-labelsize-chart-form','data-toggle' => 'validator','role' => 'form']) !!}
            <div class="modal-body">
                <div id="edit_labelsize_error"></div>
                {!! Form::hidden('edit_tesco_primary_id',$value = null, array('id'=>'edit_tesco_primary_id','class' => 'form-control input-sm')) !!}
                <div class="row">
                    <div class="col-md-12 edit-secondary-departments">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_size_department', 'Department', ['class' => 'form_label']) !!}
                            {{-- <select id="edit_size_department" name="edit_size_department" class="form-control input-sm" required="True"></select> --}}

                            {!! Form::select('edit_size_department', $departments,null,['class' => 'form-control input-sm edit_size_department','required','id'=>'edit_size_department'])!!}

                         <div class="help-block with-errors"></div>
                       </div>
                    </div>
                    <div class="col-md-12 edit-econdary-categories">

                        @foreach($categories as $id => $value)
                            <div class="form-check form-check-inline col-md-2"><input class="form-check-input" type="checkbox" value="" data-id="{{$id}}" id="category_{{$value}}" data-name="Height"><label class="form-check-label" for="height">{{$value}}</label></div>
                        @endforeach

                        {{-- appends checkbox on modal show --}}
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_labelsize_dept_title', 'Title', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_labelsize_dept_title ',$value = '', array('class' => 'form-control input-sm edit_labelsize_dept_title','placeholder' => 'Department Title','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_labelsize_uksize', 'UK Size', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_labelsize_uksize',$value = '', array('class' => 'form-control input-sm edit_labelsize_uksize','placeholder' => 'UK Size','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            {!! Form::label('edit_labelsize_cesize', 'CE Size', ['class' => 'form_label']) !!}
                            {!! Form::text('edit_labelsize_cesize',$value = '', array('class' => 'form-control input-sm edit_labelsize_cesize','placeholder' => 'CE Size','required')) !!}
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="selected_fields"></div>
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