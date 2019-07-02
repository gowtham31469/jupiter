@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
                <div class="action-group pull-right sub-menu-button">
                  <a href="#" data-target="#addBuyerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                    <i class="fa fa-plus"></i> Add Buyer</a>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                    <div class="col" id="buyer-table">
                    <table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>Buyer Name</th>
                          <th>Buyer Email</th>
                          <th>Buyer Phone</th>
                          <th>Buyer Address</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($buyers as $value)
                        <tr id="{{$value->id}}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->buyer_name }}</td>
                          <td>{{ $value->buyer_email }}</td>
                          <td>{{ $value->buyer_phone }}</td>
                          <td>{{ $value->buyer_address }}</td>
                          @if($value->is_active === 1)
                            <td><span class="label label-success">Active</span></td>
                          @else
                            <td><span class="label label-danger">Inactive</span></td>
                          @endif
                          @if($value->is_active === 1)
                            <td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>
                          @else
                            <td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a>
                          @endif
                            <a href="#"><i class="fa fa-pencil fa-button edit-buyer filterbtn options" title="Edit" data-placement="left"></i></a>
                          </td>
                         </tr>
                         @endforeach
                         </tbody>
                     </table>
                  </div>
              </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>

    {{-- Add buyer modal --}}
     <div class="modal fade" id="addBuyerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Buyer</h4>
                </div>
                 @foreach($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                {!! Form::open(['id' => 'add_buyer_form','data-toggle' => 'validator','role' => 'form','enctype' => 'multipart/form-data']) !!}
                    <div class="modal-body">
                      <div id="buyer_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('buyer_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required','data-minlength' => '3','maxlength' => '40', 'style' => 'text-transform: capitalize;')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_email', 'Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                            {!! Form::email('buyer_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_phone', 'Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                            {!! Form::text('buyer_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('buyer_address',$value = '', array('class' => 'form-control ','placeholder' => 'Address','required','style'=>'resize:none; text-transform: capitalize;','rows'=>3)) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                         <div class="form-group has-feedback">
                             <label for="image">Buyer Logo</label> <span class="control-fileupload"><input type="file" id="image" name="image" class="po-file-upload form-control center-block" required="" accept="image/*" onchange="loadFile(event)"> </span>
                             <img id="output"/>
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

    {{-- Edit buyer modal --}}
    <div class="modal fade" id="editBuyerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Buyer</h4>
               </div>
               {!! Form::open(['id' => 'edit_buyer_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                     <div id="edit_buyer_result"></div>
                     {{-- hidden id for edit functionality --}}
                     {!! Form::hidden('buyer_edit_id',$value = null, array('id'=>'buyer_edit_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_name', 'Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_buyer_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required','data-minlength' => '3','maxlength' => '40', 'style' => 'text-transform: capitalize;')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_email', 'Email', ['class' => 'form_label']) !!}
                         <div class="input-group">
                           <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                           {!! Form::email('edit_buyer_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                         </div>
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_phone', 'Phone', ['class' => 'form_label']) !!}
                         <div class="input-group">
                           <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                           {!! Form::text('edit_buyer_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                        </div>
                        <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_address', 'Address', ['class' => 'form_label']) !!}
                         {!! Form::textarea('edit_buyer_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none; text-transform: capitalize;','rows'=>3)) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('image_name', 'Buyer Logo Preview :', ['class' => 'control-label']) !!}
                         <img id="image_name"/>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_image', 'Change Logo', ['class' => 'control-label']) !!}
                         {!! Form::file('edit_image',['onchange' => 'loadEditFile(event)','accept' => 'image/*']) !!}
                         <div class="help-block with-errors"></div>
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

    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/buyers.js') }}"></script>
   <script type="text/javascript">
       $( document ).ready(function() {
         /* Show logo on left side*/
         $('.logo').html('Buyers & Merchandisers')
         $('#buyers-list-table').DataTable();
         $("#buyers-list-table_filter input").addClass('form-control input-sm');
         $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
         $('#buyers-list-table_length select').addClass('form-control input-sm');
         $('#add_buyer_form').trigger("reset");
         $('#edit_buyer_form').trigger('reset');
         $('#buyer_result').hide();
         $('#edit_buyer_result').hide();
         $('.edit-buyer').tooltip('show')
         $('.edit-buyer').tooltip('toggle')
         $('.status').tooltip('show')
         $('.status').tooltip('toggle')
         $('#output').attr('src', '');
         $('#output').attr('style', '');
         $("#edit_image").val(null);
       });
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       /** Add buyer bootstrap form validation **/
        $('#add_buyer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addBuyer function from buyers.js*/
           addBuyer();
        }
      })

      /** To refresh modal while hidden **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_buyer_form').trigger("reset");
        $('#buyer_result').hide();
        $('#edit_buyer_result').hide();
        $('#output').attr('src', '');
        $('#output').attr('style', '');
        $("#edit_image").val(null);
       })

       /** Get edit buyer details on click**/
       $(document).on('click','.edit-buyer',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getBuyer function from buyers.js*/
           getBuyer(id);
       });

       /** Submit edit buyer form **/
       $('#edit_buyer_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call getBuyer function from buyers.js*/
            editBuyer();
         }
       })
       /** To activate Buyers on click **/
       $(document).on('click', '#make_active', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeBuyerActive function from buyers.js*/
           makeBuyerActive(id,csrf_token)
       });

       /** To deactivate Buyers on click **/
       $(document).on('click', '#make_inactive', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeBuyerInactive function from buyers.js*/
           makeBuyerInactive(id,csrf_token)
       });
    </script>
    <script>
      // preview buyer logo
      var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#output').css({"height": "100px","width": "140px","margin-top": "16px"});
      };
      var loadEditFile = function(event) {
        var output = document.getElementById('image_name');
        output.src = URL.createObjectURL(event.target.files[0]);
        $('#image_name').css({"height": "100px","width": "140px","margin-top": "16px"});
      };
    </script>
    @endsection
