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
