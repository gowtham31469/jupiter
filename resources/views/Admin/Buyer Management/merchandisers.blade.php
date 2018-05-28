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
                  <a href="#" data-target="#addMerchandiserModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i>Add Merchandiser</a>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                    <div class="col" id="merchandisers-table">
                      <table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                        <thead>
                          <tr>
                            <th>Sl. No</th>
                            <th>Merchandiser Name</th>
                            <th>Merchandiser Email</th>
                            <th>Merchandiser Phone</th>
                            <th>Merchandiser Address</th>
                            <th>Buyer Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($merchandisers as $value)
                        <tr id="{{$value->id}}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->merchandiser_name }}</td>
                          <td>{{ $value->email }}</td>
                          <td>{{ $value->merchandiser_phone }}</td>
                          <td>{{ $value->merchandiser_address }}</td>
                          <td>{{ $value->buyer_name }}</td>
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
                            <a href="#"><i class="fa fa-pencil filterbtn options fa-button edit-merchandiser" title="Edit" data-placement="left"></i></a>
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

    {{-- Add merchandiser modal --}}
   <div class="modal fade" id="addMerchandiserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Add Merchandiser</h4>
              </div>
              {!! Form::open(['id' => 'add_merchandiser_form','data-toggle' => 'validator','role' => 'form']) !!}
                  <div class="modal-body">
                    <div id="add_merchandiser_result"></div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_name', 'Name', ['class' => 'form_label']) !!}
                        {!! Form::text('merchandiser_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_email', 'Email', ['class' => 'form_label']) !!}
                        <div class="input-group">
                          <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                          {!! Form::email('merchandiser_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_password', 'Password', ['class' => ' form_label']) !!}
                        <div class="input-group">
                          {!! Form::text('merchandiser_password',$value = '', array('class' => 'form-control input-sm js-copytextarea','placeholder' => 'Password','required','minlength' => '6')) !!}
                          <span id="generatePwd" class="input-group-addon input-sm" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                          <span class="input-group-addon js-textareacopybtn clipboard input-sm" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#merchandiser_password">Copy</span>
                        </div>
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_phone', 'Phone', ['class' => 'form_label']) !!}
                        <div class="input-group">
                          <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                          {!! Form::text('merchandiser_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                       </div>
                       <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_address', 'Address', ['class' => 'form_label']) !!}
                        {!! Form::textarea('merchandiser_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group has-feedback">
                        {!! Form::label('merchandiser_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                        {{  Form::select('merchandiser_buyer',(['' => '-- Select Buyer --']+ $buyers),'',array('class' => 'form-control input-sm','id'=>'merchandiser_buyer','required')) }}
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
  
  {{-- Edit merchandiser modal --}}
  <div class="modal fade" id="editMerchandiserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Edit Merchandiser</h4>
             </div>
             {!! Form::open(['id' => 'edit_merchandiser_form','data-toggle' => 'validator','role' => 'form']) !!}
                 <div class="modal-body">
                  {{-- hidden id for edit functionality --}}
                   {!! Form::hidden('merchandiser_edit_id',$value = null, array('id'=>'merchandiser_edit_id','class' => 'form-control input-sm')) !!}
                   <div id="edit_merchandiser_result"></div>
                     <div class="form-group has-feedback">
                       {!! Form::label('edit_merchandiser_name', 'Name', ['class' => 'form_label']) !!}
                       {!! Form::text('edit_merchandiser_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('edit_merchandiser_email', 'Email', ['class' => 'form_label']) !!}
                       <div class="input-group">
                         <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                         {!! Form::email('edit_merchandiser_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                       </div>
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('edit_merchandiser_phone', 'Phone', ['class' => 'form_label']) !!}
                       <div class="input-group">
                         <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                         {!! Form::text('edit_merchandiser_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                       {!! Form::label('edit_merchandiser_address', 'Address', ['class' => 'form_label']) !!}
                       {!! Form::textarea('edit_merchandiser_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                     <div class="form-group has-feedback">
                        {!! Form::label('edit_merchandiser_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                        {{  Form::select('merchandiser_buyer',(['' => '-- Select Buyer --']+ $buyers),'',array('class' => 'form-control input-sm','id'=>'edit_merchandiser_buyer','required')) }}
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
        /* Show logo on left side */
         $('.logo').html('Buyers & Merchandisers')
         $('#merchandisers-list-table').DataTable();
         $("#merchandisers-list-table_filter input").addClass('form-control input-sm');
         $("#merchandisers-list-table_filter input").attr('placeholder', 'Data Search');
         $('#merchandisers-list-table_length select').addClass('form-control input-sm');
         $('#add_merchandiser_form').trigger('reset');
         $('#edit_merchandiser_form').trigger('reset');
         $('#add_merchandiser_result').hide();
         $('#edit_merchandiser_result').hide();
         $('.edit-merchandiser').tooltip('show')
         $('.edit-merchandiser').tooltip('toggle')
         $('.status').tooltip('show')
         $('.status').tooltip('toggle')
         
       });
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
        
      /** To refresh modal while hidden**/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_buyer_form').trigger("reset");
        $('#add_merchandiser_form').trigger('reset');
        $('#buyer_result').hide();
        $('#edit_buyer_result').hide();
        $('#merchandiser_result').hide();
        $('#add_merchandiser_result').hide();
        $('#edit_merchandiser_result').hide();
       })
       /** generate random password **/
       $("#generatePwd").validator().click(function(){
       function randomString(length, chars)
       {
         var mask = '';
         if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
         if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
         if (chars.indexOf('#') > -1) mask += '0123456789';
         if (chars.indexOf('!') > -1) mask += '!@#$%&*()+=?';
         var result = '';
         for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
         return result;
       }
         $("#merchandiser_password").val( randomString(10, 'aA#!'));
         $('#add_merchandiser_form').validator('validate');
         })
         /** Copy random password to clipboard **/
         $( document ).ready(function() {
           var clipboard = new Clipboard('.clipboard');
           clipboard.on('success', function(e) {
             $(e.trigger).text("Copied!");
             e.clearSelection();
             setTimeout(function() {
               $(e.trigger).text("Copy");
             }, 2500);
           });
         });
         /* To validate add merchandiser form*/
         $('#add_merchandiser_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call addMerchandiser function from buyers.js*/
            addMerchandiser();
         }
       })
       /** get merchandiser details for editing  on click**/
       $(document).on('click','.edit-merchandiser',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getMerchandiser function from buyers.js*/
           getMerchandiser(id);
       });
       /** submit edit merchandiser form **/
       $('#edit_merchandiser_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editMerchandiser function from buyers.js*/
            editMerchandiser();
         }
       })
       /** To activate Merchandisers on click **/
       $(document).on('click', '#make_active', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeMerchandiserActive function from buyers.js*/
           makeMerchandiserActive(id,csrf_token)
       });

       /** To deactivate Merchandisers on click **/
       $(document).on('click', '#make_inactive', function(e) {
           var id = $(this).closest('tr').attr('id');
           /* call makeMerchandiserInactive function from buyers.js*/
           makeMerchandiserInactive(id,csrf_token)
       });
    </script>
    @endsection
