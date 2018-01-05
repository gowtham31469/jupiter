@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-div hidden">
            <select class="js-example-responsive buyer-select show-tick show-menu-arrow" data-live-search="true">
                <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option title="Selected Buyer : {{$buyer->buyer_name}}" value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
        <div class="select-buyer">
            <img src="../../images/select-buyer.png" class="img-responsive">
        </div>
    </div>
    {{-- Add Orchestra Garments Modal --}}
    <div class="modal fade" id="addOrchestraGarmentsModal" tabindex="-1" role="dialog" aria-labelledby="orchestraGarmentsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraGarmentsLabel">Add Garment</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_garments_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                          {!! Form::label('garment_name', 'Garment Name', ['class' => 'form_label']) !!}
                          {!! Form::text('garment_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Garment Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Garment', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Edit Orchestra Garments Modal --}}
    <div class="modal fade" id="editOrchestraGarmentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Garment</h4>
               </div>
               {!! Form::open(['id' => 'edit_orchestra_garments_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                     {!! Form::hidden('edit_orchestra_garments_id',$value = null, array('id'=>'edit_orchestra_garments_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_garment_name', 'Garment Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_garment_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Garment Name','required')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Edit Garment', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
   {{-- Delete Orchestra Garments Modal --}}
   <div class="modal fade" id="deleteOrchestraGarmentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Garment</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_garment_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra garment. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_garment_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete Garment', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
   {{-- Add Orchestra Age group Modal --}}
    <div class="modal fade" id="addOrchestraAgeGroupModal" tabindex="-1" role="dialog" aria-labelledby="orchestraAgeGroupLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraAgeGroupLabel">Add Age Group</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_agegroup_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                          {!! Form::label('age_group_name', 'Age Group Name', ['class' => 'form_label']) !!}
                          {!! Form::text('age_group_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age Group Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('color_name', 'Color Name', ['class' => 'form_label']) !!}
                          {!! Form::text('color_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Color Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Garment', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Edit Orchestra age group Modal --}}
     <div class="modal fade" id="editOrchestraAgeGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Age Group</h4>
               </div>
               {!! Form::open(['id' => 'edit_orchestra_agegroup_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                     {!! Form::hidden('edit_orchestra_agegroup_id',$value = null, array('id'=>'edit_orchestra_agegroup_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                          {!! Form::label('edit_age_group_name', 'Age Group Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_age_group_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age Group Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_color_name', 'Color Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_color_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Color Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Edit Age Group', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
   {{-- Delete Orchestra Garments Modal --}}
   <div class="modal fade" id="deleteOrchestraAgeGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Age Group</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_agegroup_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra age group. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_agegroup_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete Age Group', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
    <div id="snackbar"><span class="snackbar_span"></span></div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/settings.js') }}"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).ready(function() {
            $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            $('.logo').html('Settings')
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("buyer") === null) {

            } else {
                settings();
            }
            $('.buyer-select').val(localStorage.getItem("buyer_id"))
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });
            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") 
                {
                    localStorage.setItem("buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                    settings();
                } 
                else 
                {
                    localStorage.removeItem("buyer");
                    localStorage.removeItem("buyer_id");
                    location.reload()
                }
            });
        /** Add orchestra garment form validation **/
        $('#add_orchestra_garments_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             addOrchestraGarments();
          }
        })
      /** To refresh modal **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_orchestra_garments_form').trigger("reset");
        $('#add_orchestra_agegroup_form').trigger("reset");
       })
      /** get edit orchestra garment details **/
       $(document).on('click','.edit-orchestra-garments',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           getOrchestraGarmentById(id);
       });
       /** submit edit orchestra garment form **/
       $('#edit_orchestra_garments_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            editOrchestraGarments();
         }
       })
       /** assign id for delete modal **/
       $('#deleteOrchestraGarmentsModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_garment_id').attr('value', config);
       });
       /** submit delete orchestra garment form **/
       $('#delete_orchestra_garment_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_garment_id').val();
            deleteOrchestraGarment(id);
         }
       })
       /** Add orchestra garment form validation **/
        $('#add_orchestra_agegroup_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             addOrchestraAgegroup();
          }
        })
        /** get edit orchestra garment details **/
       $(document).on('click','.edit-orchestra-age-group',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           getOrchestraAgeGroupById(id);
       });
       /** submit edit orchestra garment form **/
       $('#edit_orchestra_agegroup_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            editOrchestraAgeGroup();
         }
       })
       /** assign id for delete modal **/
       $('#deleteOrchestraAgeGroupModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_agegroup_id').attr('value', config);
       });
       /** submit delete orchestra garment form **/
       $('#delete_orchestra_agegroup_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_agegroup_id').val();
            deleteOrchestraAgeGroup(id);
         }
       })
    });
    </script>

    @endsection