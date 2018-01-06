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
                        {!! Form::submit('Add Age Group', ['class' => 'btn btn-primary'] ) !!}
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
                       {!! Form::submit('Delete Age Group', ['class' => 'btn btn-primary'] ) !!}
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
                        <div class="form-group has-feedback">
                          {!! Form::label('english', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('french', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('spanish', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('spanish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('portuguese', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('portuguese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('italian', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('italian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('dutch', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('dutch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('german', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('german',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('russian', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('russian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('chinese', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('chinese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('greek', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('greek',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('turkish', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('turkish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Country Name', ['class' => 'btn btn-primary'] ) !!}
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
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_spanish', 'Spanish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_spanish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in spanish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_portuguese', 'Portuguese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_portuguese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in portuguese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_italian', 'Italian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_italian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in italian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_dutch', 'Dutch', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_dutch',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in dutch','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_german', 'German', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_german',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in german','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_russian', 'Russian', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_russian',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in russian','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_chinese', 'Chinese', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_chinese',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in chinese','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_greek', 'Greek', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_greek',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in greek','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_turkish', 'Turkish', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_turkish',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country name in turkish','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Edit Country Name', ['class' => 'btn btn-primary'] ) !!}
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
                       {!! Form::submit('Delete Country Name', ['class' => 'btn btn-primary'] ) !!}
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
                        <div class="form-group has-feedback">
                          {!! Form::label('code', 'Product Family Code', ['class' => 'form_label']) !!}
                          {!! Form::text('code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product Family Code','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('english_pf', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('english_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('french_pf', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('french_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('arabic_pf', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('arabic_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Product Family', ['class' => 'btn btn-primary'] ) !!}
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
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_code', 'Product Family Code', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product Family Code','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_english_pf', 'English', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_english_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in english','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_french_pf', 'French', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_french_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in french','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_arabic_pf', 'Arabic', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_arabic_pf',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Product family in arabic','required')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Edit Product Family', ['class' => 'btn btn-primary'] ) !!}
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
                       {!! Form::submit('Delete Product Family', ['class' => 'btn btn-primary'] ) !!}
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
        /** To refresh modal **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#add_orchestra_garments_form').trigger("reset");
        $('#add_orchestra_agegroup_form').trigger("reset");
        $('#add_orchestra_country_form').trigger("reset");
        $('#add_orchestra_productfamily_form').trigger("reset");
       })
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
             addOrchestraAgegroup();
          }
        })
        /** get edit orchestra age group details **/
       $(document).on('click','.edit-orchestra-age-group',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
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
             addOrchestraCountries();
          }
        })
        /** get edit orchestra country details **/
       $(document).on('click','.edit-orchestra-country',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
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
             addOrchestraProductFamily();
          }
        })
        /** get edit orchestra productfamily details **/
       $(document).on('click','.edit-orchestra-productfamily',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
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
            deleteOrchestraProductFamily(id);
         }
       })
    });
    </script>

    @endsection