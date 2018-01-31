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
      {{-- Show select buyer image as default page --}}
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
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
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
                       {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
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
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
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
                      <div class="row">
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('age_group_name', 'Age Group Name', ['class' => 'form_label']) !!}
                          {!! Form::text('age_group_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age Group Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('color_name', 'Color Name', ['class' => 'form_label']) !!}
                          {!! Form::text('color_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Color Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        </div>
                    </div>
                     </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
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
                     <div class="row">
                      <div class="col-md-6">
                       <div class="form-group has-feedback">
                          {!! Form::label('edit_age_group_name', 'Age Group Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_age_group_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age Group Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_color_name', 'Color Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_color_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Color Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Delete Orchestra age group Modal --}}
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
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra countries Modal --}}
    <div class="modal fade" id="addOrchestraCountryModal" tabindex="-1" role="dialog" aria-labelledby="orchestraCountryLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraCountryLabel">Add Country Name</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_country_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('english', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('french', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('spanish', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('spanish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('portuguese', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('portuguese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('italian', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('italian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('dutch', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('dutch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('german', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('german',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('russian', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('russian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('chinese', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('chinese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('greek', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('greek',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('turkish', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('turkish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra countries Modal --}}
    <div class="modal fade" id="editOrchestraCountryModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraCountryLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraCountryLabel">Edit Country Name</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_country_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_country_id',$value = null, array('id'=>'edit_orchestra_country_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_spanish', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_spanish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_portuguese', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_portuguese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_italian', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_italian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_dutch', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_dutch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_german', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_german',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_russian', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_russian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_chinese', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_chinese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_greek', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_greek',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_turkish', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_turkish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

   {{-- Delete Orchestra country Modal --}}
   <div class="modal fade" id="deleteOrchestraCountryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Country Name</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_country_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra country name. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_country_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra product family Modal --}}
    <div class="modal fade" id="addOrchestraProductFamilyModal" tabindex="-1" role="dialog" aria-labelledby="orchestraProductFamilyLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraProductFamilyLabel">Add Product Family</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_productfamily_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                       <div class="col-md-6"> 
                        <div class="form-group has-feedback">
                          {!! Form::label('code', 'Product Family Code', ['class' => 'form_label']) !!}
                          {!! Form::text('code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product Family Code','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('english_pf', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('french_pf', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic_pf', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra product family Modal --}}
    <div class="modal fade" id="editOrchestraProductFamilyModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraProductFamilyLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraProductFamilyLabel">Edit Product Family</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_productfamily_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_productfamily_id',$value = null, array('id'=>'edit_orchestra_productfamily_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_code', 'Product Family Code', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product Family Code','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english_pf', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french_pf', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic_pf', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Delete Orchestra productfamily Modal --}}
   <div class="modal fade" id="deleteOrchestraProductFamilyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Product Family</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_productfamily_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra product family. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_productfamily_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra special instructions Modal --}}
    <div class="modal fade bd-example-modal-lg" id="addOrchestraSpecialInstructionModal" tabindex="-1" role="dialog" aria-labelledby="orchestraSpecialInstructionLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraSpecialInstructionLabel">Add Special Instruction</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_special_instruction_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('english_si', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('french_si', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('spanish_si', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('spanish_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('portuguese_si', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('portuguese_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('italian_si', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('italian_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('dutch_si', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('dutch_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('german_si', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('german_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('russian_si', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('russian_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('chinese_si', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('chinese_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic_si', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('greek_si', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('greek_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('turkish_si', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('turkish_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra special instructions Modal --}}
    <div class="modal fade bd-example-modal-lg" id="editOrchestraSpecialInstructionModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraSpecialInstructionLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraSpecialInstructionLabel">Edit Special Instruction</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_special_instruction_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_special_instruction_id',$value = null, array('id'=>'edit_orchestra_special_instruction_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english_si', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french_si', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_spanish_si', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_spanish_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_portuguese_si', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_portuguese_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_italian_si', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_italian_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_dutch_si', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_dutch_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_german_si', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_german_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_russian_si', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_russian_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_chinese_si', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_chinese_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic_si', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_greek_si', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_greek_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_turkish_si', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_turkish_si',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Special Instruction in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Delete Orchestra special instruction Modal --}}
   <div class="modal fade" id="deleteOrchestraSpecialInstructionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Special Instruction</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_special_instruction_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra special instruction. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_special_instruction_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra compositions Modal --}}
    <div class="modal fade" id="addOrchestraCompositionModal" tabindex="-1" role="dialog" aria-labelledby="orchestraCompositionLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraCompositionLabel">Add Composition</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_composition_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('english_co', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('french_co', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('spanish_co', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('spanish_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('portuguese_co', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('portuguese_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('italian_co', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('italian_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('dutch_co', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('dutch_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('german_co', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('german_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('russian_co', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('russian_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('chinese_co', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('chinese_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic_co', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('greek_co', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('greek_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('turkish_co', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('turkish_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra composition Modal --}}
    <div class="modal fade" id="editOrchestraCompositionModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraCompositionLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraCompositionLabel">Edit Composition</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_composition_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_composition_id',$value = null, array('id'=>'edit_orchestra_composition_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english_co', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french_co', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_spanish_co', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_spanish_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_portuguese_co', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_portuguese_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_italian_co', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_italian_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_dutch_co', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_dutch_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_german_co', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_german_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_russian_co', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_russian_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_chinese_co', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_chinese_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic_co', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_greek_co', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_greek_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_turkish_co', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_turkish_co',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Composition in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Delete Orchestra composition Modal --}}
   <div class="modal fade" id="deleteOrchestraCompositionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Composition</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_composition_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra composition. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_composition_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra parts Modal --}}
    <div class="modal fade" id="addOrchestraPartModal" tabindex="-1" role="dialog" aria-labelledby="orchestraPartLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraPartLabel">Add Part</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_part_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('english_pa', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('french_pa', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('spanish_pa', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('spanish_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('portuguese_pa', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('portuguese_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('italian_pa', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('italian_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('dutch_pa', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('dutch_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('german_pa', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('german_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('russian_pa', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('russian_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('chinese_pa', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('chinese_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic_pa', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('greek_pa', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('greek_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('turkish_pa', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('turkish_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra Part Modal --}}
    <div class="modal fade" id="editOrchestraPartModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraPartLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraPartLabel">Edit Part</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_part_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_part_id',$value = null, array('id'=>'edit_orchestra_part_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english_pa', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french_pa', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_spanish_pa', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_spanish_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_portuguese_pa', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_portuguese_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_italian_pa', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_italian_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_dutch_pa', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_dutch_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_german_pa', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_german_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_russian_pa', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_russian_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_chinese_pa', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_chinese_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic_pa', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_greek_pa', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_greek_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_turkish_pa', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_turkish_pa',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Part in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Delete Orchestra Part Modal --}}
   <div class="modal fade" id="deleteOrchestraPartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Part</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_part_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra part. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_part_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Orchestra size chart Modal --}}
    <div class="modal fade" id="addOrchestraSizeChartModal" tabindex="-1" role="dialog" aria-labelledby="orchestraSizeChartLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="orchestraSizeChartLabel">Add Size Chart</h4>
                </div>
                {!! Form::open(['id' => 'add_orchestra_size_chart_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('age', 'Age', ['class' => 'form_label']) !!}
                          {!! Form::text('age',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('cm', 'Centimeters', ['class' => 'form_label']) !!}
                          {!! Form::text('cm',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Centimeters','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('inch', 'Inches', ['class' => 'form_label']) !!}
                          {!! Form::text('inch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Inches','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Orchestra size chart Modal --}}
    <div class="modal fade" id="editOrchestraSizeChartModal" tabindex="-1" role="dialog" aria-labelledby="editOrchestraSizeChartLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editOrchestraSizeChartLabel">Edit Size Chart</h4>
                </div>
                {!! Form::open(['id' => 'edit_orchestra_size_chart_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      {!! Form::hidden('edit_orchestra_size_chart_id',$value = null, array('id'=>'edit_orchestra_size_chart_id','class' => 'form-control input-sm')) !!}
                      <div class="row">
                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_age', 'Age', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_age',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Age','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_cm', 'Centimeters', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_cm',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Centimeters','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_inch', 'Inches', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_inch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Inches','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Delete Orchestra Part Modal --}}
   <div class="modal fade" id="deleteOrchestraSizeChartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Delete Size Chart</h4>
               </div>
               {!! Form::open(['id' => 'delete_orchestra_size_chart_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                       <div class="form-group has-feedback">
                         <p>You are about to delete orchestra size chart. Do you want to proceed?</p>
                          {!! Form::hidden('id',$value = null, array('id'=>'delete_orchestra_size_chart_id','class' => 'form-control input-sm')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Delete', ['class' => 'btn btn-primary'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add Tesco Brand Modal --}}
   <div class="modal fade" id="addTescoBrandsModal" tabindex="-1" role="dialog" aria-labelledby="addTescoBrandsLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoBrandsLabel">Add Brand</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_brands_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div class="form-group has-feedback">
                          {!! Form::label('brand_name', 'Brand Name', ['class' => 'form_label']) !!}
                          {!! Form::text('brand_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Brand Name','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
   {{-- Show snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>

    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/orchestra-settings.js') }}"></script>
    <script src="{{ asset('js/Admin/tesco-settings.js') }}"></script>
    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).ready(function() {
        $('.select-buyer').click(function() {
            $('.select-buyer').addClass('hidden');
        })
        /* Show logo on left side */
        $('.logo').html('Settings')
        $('.buyer-nav-select').html($('.buyer-div').html())
         
        // Check if buyer selected or not
        /* call settings function from settings.js*/
        /**  [ Show settings page based on buyer selection ] */
        if (localStorage.getItem("buyer") === null) {

        }else if(localStorage.getItem("buyer") === 'orchestra')
        {
            orchestraSettings();
        }
        else
        {
            tescoSettings();
        }

        $('.buyer-select').val(localStorage.getItem("buyer_id"))
        $('.buyer-nav-select .buyer-select').selectpicker({
            style: 'btn-default',
            size: 4
        });
        // Store selected buyer into localstorage
        $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
            if ($('.buyer-nav-select .buyer-select option:selected').val() != "") 
            {
                localStorage.setItem("buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                localStorage.setItem("buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                var buyer = $(this).find("option:selected").text().toLowerCase();
                /* call settings function from settings.js*/
                /**  [ Show settings page based on buyer selection ] */
               if(buyer === 'orchestra')
                {
                     orchestraSettings();
                }
                else
                {
                    tescoSettings();
                } 
            } 
            else 
            {
                localStorage.removeItem("buyer");
                localStorage.removeItem("buyer_id");
                location.reload()
            }
        });

      /** To refresh modal while hidden **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_orchestra_garments_form').trigger("reset");
        $('#add_orchestra_agegroup_form').trigger("reset");
        $('#add_orchestra_country_form').trigger("reset");
        $('#add_orchestra_productfamily_form').trigger("reset");
        $('#add_orchestra_special_instruction_form').trigger("reset");
        $('#add_orchestra_composition_form').trigger("reset");
        $('#add_orchestra_part_form').trigger("reset");
        $('#add_orchestra_size_chart_form').trigger("reset");
       })

      /** Add orchestra garment form validation **/
      $('#add_orchestra_garments_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addOrchestraGarments function from settings.js*/
           addOrchestraGarments();
        }
      })

      /** get edit orchestra garment details **/
       $(document).on('click','.edit-orchestra-garments',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraGarmentById function from settings.js*/
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
            /* call editOrchestraGarments function from settings.js*/
            editOrchestraGarments();
         }
       })

       /** assign id for delete garment modal **/
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
            /* call deleteOrchestraGarment function from settings.js*/
            deleteOrchestraGarment(id);
         }
       })

       /** Add orchestra age group form validation **/
        $('#add_orchestra_agegroup_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraAgegroup function from settings.js*/
             addOrchestraAgegroup();
          }
        })

       /** get edit orchestra age group details **/
       $(document).on('click','.edit-orchestra-age-group',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
            /* call getOrchestraAgeGroupById function from settings.js*/
           getOrchestraAgeGroupById(id);
       });

       /** submit edit orchestra age group form **/
       $('#edit_orchestra_agegroup_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraAgeGroup function from settings.js*/
            editOrchestraAgeGroup();
         }
       })

       /** assign id for delete age group modal **/
       $('#deleteOrchestraAgeGroupModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_agegroup_id').attr('value', config);
       });

       /** submit delete orchestra age group form **/
       $('#delete_orchestra_agegroup_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_agegroup_id').val();
            /* call deleteOrchestraAgeGroup function from settings.js*/
            deleteOrchestraAgeGroup(id);
         }
       })

       /** Add orchestra country form validation **/
        $('#add_orchestra_country_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraCountries function from settings.js*/
             addOrchestraCountries();
          }
        })

        /** get edit orchestra country details **/
       $(document).on('click','.edit-orchestra-country',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraCountryById function from settings.js*/
           getOrchestraCountryById(id);
       });

       /** submit edit orchestra country form **/
       $('#edit_orchestra_country_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraCountry function from settings.js*/
            editOrchestraCountry();
         }
       })

       /** assign id for delete country modal **/
       $('#deleteOrchestraCountryModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_country_id').attr('value', config);
       });

       /** submit delete orchestra country form **/
       $('#delete_orchestra_country_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_country_id').val();
            /* call deleteOrchestraCountry function from settings.js*/
            deleteOrchestraCountry(id);
         }
       })

       /** Add orchestra productfamily form validation **/
        $('#add_orchestra_productfamily_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraProductFamily function from settings.js*/
             addOrchestraProductFamily();
          }
        })

        /** get edit orchestra productfamily details **/
       $(document).on('click','.edit-orchestra-productfamily',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraProductFamilyById function from settings.js*/
           getOrchestraProductFamilyById(id);
       });

       /** submit edit orchestra productfamily form **/
       $('#edit_orchestra_productfamily_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraProductFamily function from settings.js*/
            editOrchestraProductFamily();
         }
       })

       /** assign id for delete productfamily modal **/
       $('#deleteOrchestraProductFamilyModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_productfamily_id').attr('value', config);
       });

       /** submit delete orchestra productfamily form **/
       $('#delete_orchestra_productfamily_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_productfamily_id').val();
            /* call deleteOrchestraProductFamily function from settings.js*/
            deleteOrchestraProductFamily(id);
         }
       })

       /** Add orchestra special instruction form validation **/
        $('#add_orchestra_special_instruction_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraSpecialInstruction function from settings.js*/
             addOrchestraSpecialInstruction();
          }
        })

        /** get edit orchestra special instruction details **/
       $(document).on('click','.edit-orchestra-special-instruction',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraSpecialInstructionById function from settings.js*/
           getOrchestraSpecialInstructionById(id);
       });

        /** submit edit orchestra special instruction form **/
       $('#edit_orchestra_special_instruction_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraSpecialInstruction function from settings.js*/
            editOrchestraSpecialInstruction();
         }
       })

       /** assign id for delete special instruction modal **/
       $('#deleteOrchestraSpecialInstructionModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_special_instruction_id').attr('value', config);
       });

       /** submit delete orchestra special instruction form **/
       $('#delete_orchestra_special_instruction_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_special_instruction_id').val();
            /* call deleteOrchestraSpecialInstruction function from settings.js*/
            deleteOrchestraSpecialInstruction(id);
         }
       })

       /** Add orchestra composition form validation **/
        $('#add_orchestra_composition_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraComposition function from settings.js*/
             addOrchestraComposition();
          }
        })

        /** get edit orchestra composition details **/
       $(document).on('click','.edit-orchestra-composition',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraCompositionById function from settings.js*/
           getOrchestraCompositionById(id);
       });

        /** submit edit orchestra composition form **/
       $('#edit_orchestra_composition_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraComposition function from settings.js*/
            editOrchestraComposition();
         }
       })

       /** assign id for delete composition modal **/
       $('#deleteOrchestraCompositionModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_composition_id').attr('value', config);
       });

       /** submit delete orchestra composition form **/
       $('#delete_orchestra_composition_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_composition_id').val();
            /* call deleteOrchestraComposition function from settings.js*/
            deleteOrchestraComposition(id);
         }
       })

       /** Add orchestra part form validation **/
        $('#add_orchestra_part_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraPart function from settings.js*/
             addOrchestraPart();
          }
        })

        /** get edit orchestra part details **/
       $(document).on('click','.edit-orchestra-part',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraPartById function from settings.js*/
           getOrchestraPartById(id);
       });

        /** submit edit orchestra part form **/
       $('#edit_orchestra_part_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraPart function from settings.js*/
            editOrchestraPart();
         }
       })

       /** assign id for delete Part modal **/
       $('#deleteOrchestraPartModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_part_id').attr('value', config);
       });

       /** submit delete orchestra part form **/
       $('#delete_orchestra_part_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_part_id').val();
            /* call deleteOrchestraPart function from settings.js*/
            deleteOrchestraPart(id);
         }
       })

       /** Add orchestra size chart form validation **/
        $('#add_orchestra_size_chart_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraSizeChart function from settings.js*/
             addOrchestraSizeChart();
          }
        })

        /** get edit orchestra size chart details **/
       $(document).on('click','.edit-orchestra-size-chart',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraSizeChartById function from settings.js*/
           getOrchestraSizeChartById(id);
       });

        /** submit edit orchestra size chart form **/
       $('#edit_orchestra_size_chart_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editOrchestraSizeChart function from settings.js*/
            editOrchestraSizeChart();
         }
       })

       /** assign id for delete size chart modal **/
       $('#deleteOrchestraSizeChartModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_orchestra_size_chart_id').attr('value', config);
       });

       /** submit delete orchestra size chart form **/
       $('#delete_orchestra_size_chart_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_orchestra_size_chart_id').val();
            /* call deleteOrchestraSizeChart function from settings.js*/
            deleteOrchestraSizeChart(id);
         }
       })

    });
    </script>

    @endsection