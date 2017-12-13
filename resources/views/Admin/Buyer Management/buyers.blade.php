@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                    <li><a href="/admin/buyer-management">Dashboard</a></li>
                    <li class="active">Buyers</li>
                  </ol>
              </div>
              <div class="main-sub-menu">
                <div class="action-group pull-right sub-menu-button">
                  <a href="#" data-target="#addBuyerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false">
                    <i class="ion-person-add"></i> Add Buyer</a>
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"></div>
                    <div class="col" id="buyer-table">
                    <table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Buyer Name</th>
                          <th>Buyer Email</th>
                          <th>Buyer Phone</th>
                          <th>Buyer Address</th>
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
                          <td>
                            <a href="#"><i class="fa fa-pencil fa-lg fa-button edit-buyer" title="Edit"></i></a>
                            <a href="#"><i class="fa fa-trash fa-lg fa-button" data-userid="{{$value->id}}" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" ></i></a>
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
     <div class="modal fade" id="addBuyerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Buyer</h4>
                </div>
                {!! Form::open(['id' => 'add_buyer_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="buyer_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_name', 'Name', ['class' => 'form_label']) !!}
                          {!! Form::text('buyer_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_email', 'Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                            {!! Form::email('buyer_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_phone', 'Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                            {!! Form::text('buyer_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('buyer_address', 'Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('buyer_address',$value = '', array('class' => 'form-control ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Buyer', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
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
                     {!! Form::hidden('buyer_edit_id',$value = null, array('id'=>'buyer_edit_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_name', 'Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_buyer_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                         <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_email', 'Email', ['class' => 'form_label']) !!}
                         <div class="input-group">
                           <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                           {!! Form::email('edit_buyer_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                           <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         </div>
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_phone', 'Phone', ['class' => 'form_label']) !!}
                         <div class="input-group">
                           <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                           {!! Form::text('edit_buyer_phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                           <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                        </div>
                        <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_buyer_address', 'Address', ['class' => 'form_label']) !!}
                         {!! Form::textarea('edit_buyer_address',$value = '', array('class' => 'form-control','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                         <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         <div class="help-block with-errors"></div>
                       </div>

                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Edit Buyer', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
   <div class="modal fade" id="deleteBuyerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Buyer</h4>
               </div>
               {!! Form::open(['id' => 'delete_buyer_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                     <div id="delete_buyer_result"></div>
                       <div class="form-group has-feedback">
                         <p>You are about to delete buyer. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_buyer_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete Buyer', ['class' => 'btn btn-primary'] ) !!}
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
         $('.logo').html('Buyers & Merchandisers')
         $('#buyers-list-table').DataTable();
         $("#buyers-list-table_filter input").addClass('form-control input-sm');
         $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
         $('#buyers-list-table_length select').addClass('form-control input-sm');
         $('#add_buyer_form').trigger("reset");
         $('#edit_buyer_form').trigger('reset');
         $('#buyer_result').hide();
         $('#edit_buyer_result').hide();
         $('#delete_buyer_result').hide();
       });
       /** Add buyer form validation **/
        $('#add_buyer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           addBuyer();
        }
      })
      /** To refresh modal **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_buyer_form').trigger("reset");
        $('#buyer_result').hide();
        $('#edit_buyer_result').hide();
        $('#delete_buyer_result').hide();
       })
       /** get edit buyer details **/
       $(document).on('click','.edit-buyer',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           getBuyer(id);
       });
       /** submit edit buyer form **/
       $('#edit_buyer_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            editBuyer();
         }
       })
       /** assign id for delete modal **/
       $('#deleteBuyerModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_buyer_id').attr('value', config);
       });
       /** submit delete buyer form **/
       $('#delete_buyer_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_buyer_id').val();
            deleteBuyer(id);
         }
       })
    </script>
    @endsection
