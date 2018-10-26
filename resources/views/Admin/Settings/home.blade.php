@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-nav-div">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
      {{-- Show select buyer image as default page --}}
        <div class="select-buyer">
        </div>
          <div class="sidebar style-4 orchestra-sidebar" style="display: none;">
            <h4>Orchestra Metadata</h4>
            <ul class="customer-select meta-data-list">
              <li id="1" onclick="orchestraSettings()"><a class="active">Garments</a></li>
              <li id="2" onclick="ageGroup()"><a>Age Group</a></li>
              <li id="3" onclick="countries()"><a>Countries</a></li>
              <li id="4" onclick="productFamily()"><a>Product Family</a></li>
              <li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li>
              <li id="6" onclick="compositions()"><a>Compositions</a></li>
              <li id="7" onclick="parts()"><a>Parts</a></li>
              <li id="8"  onclick="sizeChart()"><a>Size Chart</a></li>
              <li id="9"  onclick="orchestraSymbols()"><a>Symbols</a></li>
            </ul>
          </div>
        <div class="tesco-sidebar sidebar style-4" style="display: none;">
          <h4>Tesco Metadata</h4>
          <ul class="customer-select meta-data-list-1">
            <li id="1" onclick="tescoBrands()"><a class="active">Brands</a></li>
            <li id="2" onclick="tescoDepartment()"><a>Departments</a></li>
            <li id="3" onclick="tescoSuppliers()"><a>Customers</a></li>
            <li id="4"  onclick="tescoSizeChart()"><a>Size Chart</a></li>
            <li id="5"  onclick="taxCode()"><a>Tax Code</a></li>
            <li id="6"  onclick="tescoMaterialMasters()"><a>Material Master</a></li>
            <li id="7"  onclick="tescoErrorReportEmail()"><a>Error Report Email</a></li>
          </ul>
        </div>
        <div class="content">
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                       {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                       {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                    {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
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
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::button('Update', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

<!-- care_symbols -->

{{-- Add Orchestra Symbols Modal --}}
<div class="modal fade" id="addOrchestraSymbolsModal" tabindex="-1" role="dialog" aria-labelledby="orchestraSymbolsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="orchestraSymbolsLabel">Add Symbols</h4>
            </div>
            {!! Form::open(['id' => 'add_orchestra_symbols_form','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
     
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('symbol_category', 'Category', ['class' => 'form_label']) !!}
                         {{ Form::select('symbol_category', $category, '', ['class' => 'form-control input-sm','required']) }}
                      <div class="help-block with-errors"></div>
                    </div>
                    
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('cofreet_symbol', 'Cofreet Symbol', ['class' => 'form_label']) !!}
                         <div class="row">
                         <div class="col-md-2"> <img width="30px" id="add_cofreet_symbol" src="" alt="Null"></div>
                         <div class="col-md-10"> {{ Form::file('cofreet_symbol', ['required', 'accept' => 'image/png']) }}</div>
                         </div>
                      
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('astms_symbol', 'Astms Symbol', ['class' => 'form_label']) !!}
                         <div class="row">
                         <div class="col-md-2"><img width="30px" id="add_astms_symbol" src="" alt="Null"></div>
                         <div class="col-md-10">{{ Form::file('astms_symbol', ['required', 'accept' => 'image/png']) }}</div>
                         </div>
                         
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('care_instruction', 'Care Instruction', ['class' => 'form_label']) !!}
                         
                         {{ Form::textarea('care_instruction', null,  ['class' => 'form-control', 'maxlength' => '255', 'minlength' => '1', 'style' => 'resize: none;', 'rows' => '5','required']) }}
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

{{-- Edit Symbol --}}

{{-- <div class="modal fade" id="editOrchestraSymbolsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Symbol</h4>
               </div>
               {!! Form::open(['id' => 'edit_orchestra_symbols_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                     {!! Form::hidden('edit_orchestra_symbols_id',$value = null, array('id'=>'edit_orchestra_symbols_id','class' => 'form-control input-sm')) !!}
                     <div class="form-group has-feedback">
                     
                         {!! Form::label('edit_symbol_category', 'Category', ['class' => 'form_label']) !!}
                         {{ Form::select('edit_symbol_category', $category, '', ['class' => 'form-control input-sm','required']) }}
                      <div class="help-block with-errors"></div>
                    </div>
                    
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('edit_cofreet_symbol', 'Cofreet Symbol', ['class' => 'form_label']) !!}
                        
                         <div class="row">
                         <div class="col-md-2"> <img width="30px" id="cofreet_image" src="" alt="Null"></div>
                         <div class="hidden hidden_edit_cofreet_symbol">
                         <div class="col-md-1"><span class="glyphicon glyphicon-arrow-right"></span></div>
                         <div class="col-md-1"><img width="30px" id="add_edit_cofreet_symbol" src="" alt="Null"></div>
                         </div>
                         <div class="col-md-8"> {{ Form::file('edit_cofreet_symbol', ['accept' => 'image/png']) }}</div>
                         </div>
                        
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('edit_astms_symbol', 'Astms Symbol', ['class' => 'form_label']) !!}
                         <div class="row">
                         <div class="col-md-2"><img width="30px" id="astms_image" src="" alt="Null"></div>
                         <div class="hidden hidden_edit_astms_symbol">
                         <div class="col-md-1"><span class="glyphicon glyphicon-arrow-right"></span></div>
                         <div class="col-md-1"><img width="30px" id="add_edit_astms_symbol" src="" alt="Null"></div>
                         </div>
                         <div class="col-md-8">{{ Form::file('edit_astms_symbol', ['accept' => 'image/png']) }}</div>
                         </div>
                         
                         
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback">
                     
                         {!! Form::label('edit_care_instruction', 'Care Instruction', ['class' => 'form_label']) !!}
                         {{ Form::textarea('edit_care_instruction', null,  ['class' => 'form-control', 'maxlength' => '255', 'minlength' => '1', 'style' => 'resize: none;', 'rows' => '5','required']) }}
                      <div class="help-block with-errors"></div>
                    </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                       {!! Form::button('Update', ['class' => 'btn btn-primary','type' => 'submit'] ) !!}
                   </div>
              {!! Form::close() !!}
           
       </div>
   </div>
   </div> --}}

<!-- Tesco Modals -->

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
                      <div id="tesco_brand_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('brand_name', 'Brand Name', ['class' => 'form_label']) !!}
                          {!! Form::text('brand_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Brand Name','required', 'maxlength' => '25')) !!}
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
    
    {{-- Edit Tesco Brands Modal --}}
    <div class="modal fade" id="editTescoBrandsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Brand</h4>
               </div>
               {!! Form::open(['id' => 'edit_tesco_brands_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                    <div id="edit_tesco_brand_result"></div>
                     {!! Form::hidden('edit_tesco_brands_id',$value = null, array('id'=>'edit_tesco_brands_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_brand_name', 'Brand Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_brand_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Brand Name','required', 'maxlength' => '25')) !!}
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

{{-- Department --}}

   {{-- Add Tesco Department Modal --}}
   <div class="modal fade" id="addTescoDepartmentsModal" tabindex="-1" role="dialog" aria-labelledby="addTescoDepartmentLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoDepartmentLabel">Add Department</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_departments_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="tesco_department_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('department_name', 'Department Name', ['class' => 'form_label']) !!}
                          {!! Form::text('department_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Department Name','required', 'maxlength' => '25')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('pantone', 'Pantone', ['class' => 'form_label']) !!}
                          {!! Form::text('pantone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Pantone','required', 'maxlength' => '10')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('rbg', 'RBG', ['class' => 'form_label']) !!}
                          {!! Form::text('rbg',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'RBG','required', 'maxlength' => '10')) !!}
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

    {{-- Edit Tesco Department Modal --}}
    <div class="modal fade" id="editTescoDepartmentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Department</h4>
               </div>
               {!! Form::open(['id' => 'edit_tesco_departments_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                    <div id="edit_tesco_department_result"></div>
                     {!! Form::hidden('edit_tesco_departments_id',$value = null, array('id'=>'edit_tesco_departments_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_department_name', 'Department Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_department_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Department Name','required', 'maxlength' => '25')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_pantone_name', 'Pantone Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_pantone_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Pantone Name','required', 'maxlength' => '10')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_rbg_name', 'RBG Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_rbg_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'RBG Name','required', 'maxlength' => '10')) !!}
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

{{-- Suppliers  --}}

   {{-- Add Tesco Supplier Modal --}}
   <div class="modal fade" id="addTescoSuppliersModal" tabindex="-1" role="dialog" aria-labelledby="addTescoSupplierLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoSupplierLabel">Add Customer</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_suppliers_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="supplier_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('supplier_name', 'Customer Name', ['class' => 'form_label']) !!}
                          {!! Form::text('supplier_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Customer Name','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('supplier_code', 'Customer Code', ['class' => 'form_label']) !!}
                          {!! Form::text('supplier_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Customer Code','required','onkeypress' => 'return isNumberKey(event)', 'maxlength' => '15')) !!}
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

    {{-- Edit Tesco Supplier Modal --}}
    <div class="modal fade" id="editTescoSuppliersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Customer</h4>
               </div>
               {!! Form::open(['id' => 'edit_tesco_suppliers_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body">
                    <div id="edit_supplier_result"></div>
                     {!! Form::hidden('edit_tesco_suppliers_id',$value = null, array('id'=>'edit_tesco_suppliers_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_supplier_name', 'Customer Name', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_supplier_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Customer Name','required', 'maxlength' => '50')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_supplier_code', 'Customer Code', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_supplier_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Customer Code','required', 'maxlength' => '15')) !!}
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

   {{-- Add Tesco Size Chart Modal --}}
   <div class="modal fade" id="addTescoSizeChartsModal" tabindex="-1" role="dialog" aria-labelledby="addTescoSizeChartLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoSizeChartLabel">Add Size Chart</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_size_charts_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="tesco_sizechart_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('po_size', 'PO Size', ['class' => 'form_label']) !!}
                          {!! Form::text('po_size',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PO Size','required', 'maxlength' => '10')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('uk', 'UK', ['class' => 'form_label']) !!}
                          {!! Form::text('uk',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'UK','required', 'maxlength' => '10')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('ce', 'CE', ['class' => 'form_label']) !!}
                          {!! Form::text('ce',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'CE','required', 'maxlength' => '10')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Tesco Size Chart Modal --}}
    <div class="modal fade" id="editTescoSizeChartsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Size Chart</h4>
               </div>
               {!! Form::open(['id' => 'edit_tesco_size_charts_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body"> 
                    <div id="edit_tesco_sizechart_result"></div>
                     {!! Form::hidden('edit_tesco_size_charts_id',$value = null, array('id'=>'edit_tesco_size_charts_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_po_size', 'PO Size', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_po_size',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PO Size','required', 'maxlength' => '10')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_uk', 'UK', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_uk',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'UK','required', 'maxlength' => '10')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       <div class="form-group has-feedback">
                         {!! Form::label('edit_ce', 'CE', ['class' => 'form_label']) !!}
                         {!! Form::text('edit_ce',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'CE','required', 'maxlength' => '10')) !!}
                         <div class="help-block with-errors"></div>
                       </div>
                       
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Update', ['class' => 'btn btn-primary btn-sm'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>

   {{-- Add  Tax Code Modal --}}
   <div class="modal fade" id="addTaxCodesModal" tabindex="-1" role="dialog" aria-labelledby="addTaxCodeLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTaxCodeLabel">Add Tax Code</h4>
                </div>
                {!! Form::open(['id' => 'add_tax_codes_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="taxcode_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('tax_type', 'Tax Type', ['class' => 'form_label']) !!}
                          {!! Form::text('tax_type',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax Type','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('tax', 'Tax (%)', ['class' => 'form_label']) !!}
                          {!! Form::text('tax',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax In %','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('tax_code', 'Tax Code', ['class' => 'form_label']) !!}
                          {!! Form::text('tax_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax Code','required', 'maxlength' => '5')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Tesco Tax Code Modal --}}
   <div class="modal fade" id="editTaxCodesModal" tabindex="-1" role="dialog" aria-labelledby="editTaxCodeLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editTaxCodeLabel">Edit Tax Code</h4>
                </div>
                {!! Form::open(['id' => 'edit_tax_codes_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="edit_taxcode_result"></div>
                        {!! Form::hidden('edit_tax_code_id',$value = null, array('id'=>'edit_tax_code_id','class' => 'form-control input-sm')) !!}
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_tax_type', 'Tax Type', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_tax_type',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax Type','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_tax', 'Tax (%)', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_tax',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax In %','required','onkeypress' => 'return isNumberKey(event)')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_tax_code', 'Tax Code', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_tax_code',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax Code','required', 'maxlength' => '5')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Add Tesco Material Master Modal --}}
   <div class="modal fade" id="addTescoMaterialModal" tabindex="-1" role="dialog" aria-labelledby="addTescoMaterialLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoMaterialLabel">Add Material</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_material_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="tesco_material_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('material_name_employee', 'Material Name For Employee', ['class' => 'form_label']) !!}
                          {!! Form::text('material_name_employee',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Employee','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('material_name_production', 'Material Name For Production', ['class' => 'form_label']) !!}
                          {!! Form::text('material_name_production',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Production','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('material_name_purchase', 'Material Name For Purchase', ['class' => 'form_label']) !!}
                          {!! Form::text('material_name_purchase',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Purchase','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{-- Edit Tesco material Modal --}}
    <div class="modal fade" id="editTescoMaterialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4 class="modal-title" id="myModalLabel">Edit Material</h4>
               </div>
               {!! Form::open(['id' => 'edit_tesco_material_form','data-toggle' => 'validator','role' => 'form']) !!}
                   <div class="modal-body"> 
                    <div id="edit_tesco_material_result"></div>
                     {!! Form::hidden('edit_tesco_material_id',$value = null, array('id'=>'edit_tesco_material_id','class' => 'form-control input-sm')) !!}
                       <div class="form-group has-feedback">
                          {!! Form::label('edit_material_name_employee', 'Material Name For Employee', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_material_name_employee',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Employee','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_material_name_production', 'Material Name For Production', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_material_name_production',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Production','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_material_name_purchase', 'Material Name For Purchase', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_material_name_purchase',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Material Name For Purchase','required', 'maxlength' => '50')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                       {!! Form::submit('Update', ['class' => 'btn btn-primary btn-sm'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
    {{-- Add Tesco error report email Modal --}}
   <div class="modal fade" id="addTescoErrorReportEmailModal" tabindex="-1" role="dialog" aria-labelledby="addTescoErrorReportEmailLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addTescoErrorReportEmailLabel">Add Error Report Email</h4>
                </div>
                {!! Form::open(['id' => 'add_tesco_error_report_email_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="tesco_error_report_email_result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('company', 'Select a Company', ['class' => 'form_label']) !!}
                          {{  Form::select('company',(['' => '-- Select a Company --']+ $company),'',array('class' => 'form-control input-sm','id'=>'company','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('error_case', 'Select an Error Case', ['class' => 'form_label']) !!}
                          {{  Form::select('error_case',(['' => '-- Select an Error Case --','Wrong Price' => 'Wrong Price','Wrong Quantity' => 'Wrong Quantity','Wrong Reference' => 'Wrong Reference','Wrong Style' => 'Wrong Style','Partial PO' => 'Partial PO','Others' => 'Others']),'',array('class' => 'form-control input-sm','id'=>'error_case','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('contact_person', 'Contact Person Name', ['class' => 'form_label']) !!}
                          {!! Form::text('contact_person',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Person Name','maxlength' => '25')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('error_email', 'Contact Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                            {!! Form::email('error_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());',  'maxlength' => '50')) !!}
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Edit Tesco error report email Modal --}}
   <div class="modal fade" id="editTescoErrorReportEmailModal" tabindex="-1" role="dialog" aria-labelledby="editTescoErrorReportEmailLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editTescoErrorReportEmailLabel">Edit Error Report Email</h4>
                </div>
                {!! Form::open(['id' => 'edit_tesco_error_report_email_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                        <div id="edit_tesco_error_report_email_result"></div>
                        {!! Form::hidden('edit_tesco_error_report_email_id',$value = null, array('id'=>'edit_tesco_error_report_email_id','class' => 'form-control input-sm')) !!}
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_company', 'Select a Company', ['class' => 'form_label']) !!}
                          {{  Form::select('edit_company',(['' => '-- Select a Company --']+ $company),'',array('class' => 'form-control input-sm','id'=>'edit_company','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_error_case', 'Select an Error Case', ['class' => 'form_label']) !!}
                          {{  Form::select('edit_error_case',(['' => '-- Select an Error Case --','Wrong Price' => 'Wrong Price','Wrong Quantity' => 'Wrong Quantity','Wrong Reference' => 'Wrong Reference','Wrong Style' => 'Wrong Style','Partial PO' => 'Partial PO','Others' => 'Others']),'',array('class' => 'form-control input-sm','id'=>'edit_error_case','required')) }}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_contact_person', 'Contact Person Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_contact_person',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Person Name','maxlength' => '25')) !!}
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('edit_error_email', 'Contact Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                            {!! Form::email('edit_error_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());',  'maxlength' => '50')) !!}
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Update', ['class' => 'btn btn-primary btn-sm'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
   {{-- Show snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>
    <!-- Buyer selection Modal -->
    <div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog buyer-selection-modal-dialog" role="document">
             <div class="modal-content buyer-selection-modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Buyer</h4>
                     </div>
                     <div class="modal-body buyer-selection-modal-body">
                        <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                          <strong>Warning!</strong> Select a buyer to proceed.
                        </div>
                        @foreach($buyers as $buyer)
                         <a class="buyer-card-link" style="cursor: pointer;">
                             <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$buyer->buyer_name}}"  data-value ="{{$buyer->id}}" id="{{ strtolower($buyer->buyer_name) }}">
                               <img src="{{$buyer->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
                             </div>
                         </a>
                        @endforeach
                     </div>
                     <div class="modal-footer buyer-selection-modal-footer">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                     </div>
                {!! Form::close() !!}
             </div>
         </div>
     </div>
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
        /* Show logo on left side */
        $('.logo').html('Settings')

       /** [ Make buyers card active ] */
       $('div.buyers-card').click(function(){
         $('div.buyers-card').removeClass("active");
         $(this).addClass("active");
         $('#hidden-buyer-div').hide();

         /* Save selected buyer into localdb */
         if ($('.buyers-card.active-buyer.active').length != 0) 
         {
             $('#hidden-buyer-div').hide();
             localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
             localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
             $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
             $('#buyerSelectionModalCenter').modal('hide');
             var buyer = localStorage.getItem("buyer");
              /* call orchestraSettings function from orchestra-settings.js*/
              /**  [ Show orchestraSettings page based on buyer selection ] */
             if(buyer === 'orchestra')
              {
                $('.meta-data-list li a').removeClass("active");
                $('.meta-data-list li:first a').addClass("active");
                   orchestraSettings();

              }
              else
              {
                $('.meta-data-list-1 li a').removeClass("active");
                $('.meta-data-list-1 li:first a').addClass("active");
                tescoBrands();
              } 
         } else {
             localStorage.removeItem("buyer");
             localStorage.removeItem("buyer_id");
             $('#hidden-buyer-div').show();
         }
       });

       /** [ Show already selected(active) buyer on clicking buyer menu] */
       $('.buyer-nav-menu').on('click', function() {
           if (localStorage.getItem("buyer") === null) 
           {
              $("#buyerSelectionModalCenter").modal("show");
              
           } 
           else 
           {
               $('#'+localStorage.getItem("buyer")).addClass('active');
               $("#buyerSelectionModalCenter").modal("show");
           }
       });

       /** [If only 1 buyer , auto select that buyer] */
       var buyer_count = $('#invisible__count_id').val();
       if(buyer_count <= 1) 
       {
           $('#hidden-buyer-div').hide();
           $('div.buyers-card').addClass("active");
           localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
           localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
           $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
       }   
       
       /** add buyer selection menu into navbar */
       $('.buyer-nav-menu').html($('.buyer-nav-div').html());

       /** Load selected buyer from local-storage on pageload */
       if (localStorage.getItem("buyer") === null) 
       {
           $(document).ready(function() {
              $("#buyerSelectionModalCenter").modal("show");
           })
       } 
       else 
       {
           $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
           var buyer = localStorage.getItem("buyer");
            /* call orchestraSettings function from orchestra-settings.js*/
            /**  [ Show orchestraSettings page based on buyer selection ] */
           if(buyer === 'orchestra')
            {
              $('.meta-data-list li a').removeClass("active");
              $('.meta-data-list li:first a').addClass("active");
                 orchestraSettings();

            }
            else
            {
              $('.meta-data-list-1 li a').removeClass("active");
              $('.meta-data-list-1 li:first a').addClass("active");
              tescoBrands();
            } 
       }

       /** To refresh modal while hidden **/
       $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
         $('div.buyers-card').removeClass("active");
         if (localStorage.getItem("buyer") === null) 
         {
             $(document).ready(function() {
                $("#buyerSelectionModalCenter").modal("show");
             })
         } 
         else 
         {
             $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
             var buyer = localStorage.getItem("buyer");
                /* call orchestraSettings function from orchestra-settings.js*/
                /**  [ Show orchestraSettings page based on buyer selection ] */
               if(buyer === 'orchestra')
                {
                  $('.meta-data-list li a').removeClass("active");
                  $('.meta-data-list li:first a').addClass("active");
                     orchestraSettings();

                }
                else
                {
                  $('.meta-data-list-1 li a').removeClass("active");
                  $('.meta-data-list-1 li:first a').addClass("active");
                  tescoBrands();
                } 
         }
        }) 
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
        $('#add_tesco_brands_form').trigger("reset");
        $('#add_tesco_departments_form').trigger("reset");
        $('#add_tesco_suppliers_form').trigger("reset");
        $('#add_tesco_size_charts_form').trigger("reset");
        $('#add_tesco_tax_codes_form').trigger("reset");
        $('#add_tesco_material_form').trigger("reset");
        $('#add_tesco_error_report_email_form').trigger("reset");
        $('#edit_orchestra_symbols_form').trigger("reset");
        $('#add_orchestra_symbols_form').trigger("reset");
        $('#supplier_result').hide();
        $('#edit_supplier_result').hide();
        $('#tesco_sizechart_result').hide();
        $('#edit_tesco_sizechart_result').hide();
        $('#tesco_department_result').hide();
        $('#edit_tesco_department_result').hide();
        $('#tesco_brand_result').hide();
        $('#edit_tesco_brand_result').hide();
        $('#taxcode_result').hide();
        $('#edit_taxcode_result').hide();
        $('#tesco_material_result').hide();
        $('#edit_tesco_material_result').hide();
        $('#tesco_error_report_email_result').hide();
        $('#edit_tesco_error_report_email_result').hide();
       })

      /** Add orchestra garment form validation **/
      $('#add_orchestra_garments_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addOrchestraGarments function from orchestra-settings.js*/
           addOrchestraGarments();
        }
      })

      /** get edit orchestra garment details **/
       $(document).on('click','.edit-orchestra-garments',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraGarmentById function from orchestra-settings.js*/
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
            /* call editOrchestraGarments function from orchestra-settings.js*/
            editOrchestraGarments();
         }
       })
       /** To Enable  Garment  on click **/
       $(document).on('click', '#make_garment_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeGarmentEnabled function from orchestra-settings.js*/
           makeGarmentEnabled(id,csrf_token)
       });

       /** To disable Garment  on click **/
       $(document).on('click', '#make_garment_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeGarmentDisabled function from orchestra-settings.js*/
           makeGarmentDisabled(id,csrf_token)
       });
       /** Add orchestra age group form validation **/
        $('#add_orchestra_agegroup_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraAgegroup function from orchestra-settings.js*/
             addOrchestraAgegroup();
          }
        })

       /** get edit orchestra age group details **/
       $(document).on('click','.edit-orchestra-age-group',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
            /* call getOrchestraAgeGroupById function from orchestra-settings.js*/
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
            /* call editOrchestraAgeGroup function from orchestra-settings.js*/
            editOrchestraAgeGroup();
         }
       })
       /** To Enable  age group  on click **/
       $(document).on('click', '#make_age_group_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeAgeGroupEnabled function from orchestra-settings.js*/
           makeAgeGroupEnabled(id,csrf_token)
       });

       /** To disable age group  on click **/
       $(document).on('click', '#make_age_group_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeAgeGroupDisabled function from orchestra-settings.js*/
           makeAgeGroupDisabled(id,csrf_token)
       });
       /** Add orchestra country form validation **/
        $('#add_orchestra_country_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraCountries function from orchestra-settings.js*/
             addOrchestraCountries();
          }
        })

        /** get edit orchestra country details **/
       $(document).on('click','.edit-orchestra-country',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraCountryById function from orchestra-settings.js*/
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
            /* call editOrchestraCountry function from orchestra-settings.js*/
            editOrchestraCountry();
         }
       })
       /** To Enable  country  on click **/
       $(document).on('click', '#make_country_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeCountryEnabled function from orchestra-settings.js*/
           makeCountryEnabled(id,csrf_token)
       });

       /** To disable country  on click **/
       $(document).on('click', '#make_country_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeCountryDisabled function from orchestra-settings.js*/
           makeCountryDisabled(id,csrf_token)
       });
       /** Add orchestra productfamily form validation **/
        $('#add_orchestra_productfamily_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraProductFamily function from orchestra-settings.js*/
             addOrchestraProductFamily();
          }
        })

        /** get edit orchestra productfamily details **/
       $(document).on('click','.edit-orchestra-productfamily',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraProductFamilyById function from orchestra-settings.js*/
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
            /* call editOrchestraProductFamily function from orchestra-settings.js*/
            editOrchestraProductFamily();
         }
       })
       /** To Enable  productfamily  on click **/
       $(document).on('click', '#make_productfamily_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeProductFamilyEnabled function from orchestra-settings.js*/
           makeProductFamilyEnabled(id,csrf_token)
       });

       /** To disable productfamily  on click **/
       $(document).on('click', '#make_productfamily_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeProductFamilyDisabled function from orchestra-settings.js*/
           makeProductFamilyDisabled(id,csrf_token)
       });
       /** Add orchestra special instruction form validation **/
        $('#add_orchestra_special_instruction_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraSpecialInstruction function from orchestra-settings.js*/
             addOrchestraSpecialInstruction();
          }
        })

        /** get edit orchestra special instruction details **/
       $(document).on('click','.edit-orchestra-special-instruction',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraSpecialInstructionById function from orchestra-settings.js*/
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
            /* call editOrchestraSpecialInstruction function from orchestra-settings.js*/
            editOrchestraSpecialInstruction();
         }
       })
       /** To Enable  special instruction  on click **/
       $(document).on('click', '#make_special_instruction_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSpecialInstructionEnabled function from orchestra-settings.js*/
           makeSpecialInstructionEnabled(id,csrf_token)
       });

       /** To disable special instruction  on click **/
       $(document).on('click', '#make_special_instruction_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSpecialInstructionDisabled function from orchestra-settings.js*/
           makeSpecialInstructionDisabled(id,csrf_token)
       });
       /** Add orchestra composition form validation **/
        $('#add_orchestra_composition_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraComposition function from orchestra-settings.js*/
             addOrchestraComposition();
          }
        })

        /** get edit orchestra composition details **/
       $(document).on('click','.edit-orchestra-composition',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraCompositionById function from orchestra-settings.js*/
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
            /* call editOrchestraComposition function from orchestra-settings.js*/
            editOrchestraComposition();
         }
       })
       /** To Enable  composition  on click **/
       $(document).on('click', '#make_composition_list_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeCompositionEnabled function from orchestra-settings.js*/
           makeCompositionEnabled(id,csrf_token)
       });

       /** To disable composition  on click **/
       $(document).on('click', '#make_composition_list_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeCompositionDisabled function from orchestra-settings.js*/
           makeCompositionDisabled(id,csrf_token)
       });
       /** Add orchestra part form validation **/
        $('#add_orchestra_part_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraPart function from orchestra-settings.js*/
             addOrchestraPart();
          }
        })

        /** get edit orchestra part details **/
       $(document).on('click','.edit-orchestra-part',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraPartById function from orchestra-settings.js*/
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
            /* call editOrchestraPart function from orchestra-settings.js*/
            editOrchestraPart();
         }
       })
       /** To Enable  part  on click **/
       $(document).on('click', '#make_part_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makePartEnabled function from orchestra-settings.js*/
           makePartEnabled(id,csrf_token)
       });

       /** To disable part  on click **/
       $(document).on('click', '#make_part_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makePartDisabled function from orchestra-settings.js*/
           makePartDisabled(id,csrf_token)
       });
       /** Add orchestra size chart form validation **/
        $('#add_orchestra_size_chart_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addOrchestraSizeChart function from orchestra-settings.js*/
             addOrchestraSizeChart();
          }
        })

        /** get edit orchestra size chart details **/
       $(document).on('click','.edit-orchestra-size-chart',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getOrchestraSizeChartById function from orchestra-settings.js*/
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
            /* call editOrchestraSizeChart function from orchestra-settings.js*/
            editOrchestraSizeChart();
         }
       })
       /** To Enable  size chart  on click **/
       $(document).on('click', '#make_size_chart_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeOrchestraSizeChartEnabled function from orchestra-settings.js*/
           makeOrchestraSizeChartEnabled(id,csrf_token)
       });

       /** To disable size chart  on click **/
       $(document).on('click', '#make_size_chart_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeOrchestraSizeChartDisabled function from orchestra-settings.js*/
           makeOrchestraSizeChartDisabled(id,csrf_token)
       });
          /** Add orchestra symbols form validation **/
         $('#add_orchestra_symbols_form').validator().on('submit', function (e) {
           if (e.isDefaultPrevented())
           {

           }
           else
           {
              e.preventDefault();
              var cofreet_file = $('#cofreet_symbol')[0].files[0];
              var astms_file = $('#astms_symbol')[0].files[0];
              var symbol_category = $('#symbol_category option:selected').val();
              var care_instruction = $('#care_instruction').val();
              
              /* call addOrchestraSymbols function from orchestra-settings.js*/
              addOrchestraSymbols(cofreet_file, astms_file, symbol_category, care_instruction);
           }
         })
       });

       /** Edit orchestra symbols form validation **/
       $('#edit_orchestra_symbols_form').validator().on('submit', function (e) {
           if (e.isDefaultPrevented())
           {

           }
           else
           {
              e.preventDefault();
              var cofreet_file = $('#edit_cofreet_symbol')[0].files[0];
              var astms_file = $('#edit_astms_symbol')[0].files[0];
              if(astms_file === undefined){
               astms_file = "";
              }
              if(cofreet_file === undefined){
               cofreet_file = "";
              }
              var symbol_category = $('#edit_symbol_category option:selected').val();
              var care_instruction = $('#edit_care_instruction').val();
              var id = $('#edit_orchestra_symbols_id').val();

              /* call editOrchestraSymbols function from orchestra-settings.js*/
              editOrchestraSymbols(id, cofreet_file, astms_file, symbol_category, care_instruction);
           }
         });
       /** To Enable  symbols  on click **/
       $(document).on('click', '#make_symbols_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSymbolEnabled function from orchestra-settings.js*/
           makeSymbolEnabled(id,csrf_token)
       });

       /** To disable symbols  on click **/
       $(document).on('click', '#make_symbols_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSymbolDisabled function from orchestra-settings.js*/
           makeSymbolDisabled(id,csrf_token)
       });
       
       /** Add Tesco Brand form validation **/
       $('#add_tesco_brands_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addTescoBrands function from tesco-settings.js*/
             addTescoBrands();
          }
        })

        /** get edit orchestra garment details **/
       $(document).on('click','.edit-tesco-brands',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTescoBrandById function from tesco-settings.js*/
           getTescoBrandById(id);
       });

       /** submit edit orchestra garment form **/
       $('#edit_tesco_brands_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoBrands function from tesco-settings.js*/
            editTescoBrands();
         }
       })

    /** To Enable  Brand  on click **/
    $(document).on('click', '#make_brand_enabled', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        /* call makeBrandEnabled function from tesco-settings.js*/
        makeBrandEnabled(id,csrf_token)
    });

    /** To disable Brand  on click **/
    $(document).on('click', '#make_brand_disabled', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        /* call makeBrandDisabled function from tesco-settings.js*/
        makeBrandDisabled(id,csrf_token)
    });

    /** Add Tesco Department form validation **/
    $('#add_tesco_departments_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addTescoDepartments function from  tesco-settings.js*/
             addTescoDepartments();
          }
        })
        /** get edit Tesco Department details **/
        $(document).on('click','.edit-tesco-departments',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTescoDepartmentsById function from settings.js*/
           getTescoDepartmentsById(id);
       });
        
       /** submit edit tesco departments form **/
       $('#edit_tesco_departments_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoDepartments function from tesco-settings.js*/
            editTescoDepartments();
         }
       })
       /** To Enable  Department  on click **/
       $(document).on('click', '#make_department_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeDepartmentEnabled function from tesco-settings.js*/
           makeDepartmentEnabled(id,csrf_token)
       });

       /** To disable Department  on click **/
       $(document).on('click', '#make_department_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeDepartmentDisabled function from tesco-settings.js*/
           makeDepartmentDisabled(id,csrf_token)
       });
       /** Add Tesco Supplier form validation **/
       $('#add_tesco_suppliers_form').validator().on('submit', function (e) {
          if (e.isDefaultPrevented())
          {

          }
          else
          {
             e.preventDefault();
             /* call addTescoSuppliers function from  tesco-settings.js*/
             addTescoSuppliers();
          }
        })
       
         /** get edit Tesco Supplier details **/
       $(document).on('click','.edit-tesco-suppliers',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTescoBrandById function from tesco-settings.js*/
           getTescoSuppliersById(id);
       });

       /** submit edit Tesco Supplier form **/
       $('#edit_tesco_suppliers_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoBrands function from tesco-settings.js*/
            editTescoSuppliers();
         }
       })

    /** To Enable  Supplier  on click **/
    $(document).on('click', '#make_supplier_enabled', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        /* call makeSupplierEnabled function from tesco-settings.js*/
        makeSupplierEnabled(id,csrf_token)
    });

    /** To disable Supplier  on click **/
    $(document).on('click', '#make_supplier_disabled', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        /* call makeSupplierDisabled function from tesco-settings.js*/
        makeSupplierDisabled(id,csrf_token)
    });
     /** Add Tesco Size chart form validation **/
     $('#add_tesco_size_charts_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         /* call addTescoSize charts function from  tesco-settings.js*/
         addTescoSizeCharts();
      }
    })
       
         /** get edit Tesco Size chart details **/
       $(document).on('click','.edit-tesco-sizecharts',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTescoSizechartById function from settings.js*/
           getTescoSizeChartsById(id);
       });

       /** submit edit Tesco Size chart form **/
       $('#edit_tesco_size_charts_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoSizecharts function from tesco-settings.js*/
            editTescoSizeChart();
         }
       })
       /** To Enable  SizeChart  on click **/
       $(document).on('click', '#make_sizechart_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSizeChartEnabled function from tesco-settings.js*/
           makeSizeChartEnabled(id,csrf_token)
       });

       /** To disable SizeChart  on click **/
       $(document).on('click', '#make_sizechart_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeSizeChartDisabled function from tesco-settings.js*/
           makeSizeChartDisabled(id,csrf_token)
       });

     /** Add  Tax Code form validation **/
     $('#add_tax_codes_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addTaxCodes function from  tesco-settings.js*/
           addTaxCodes();
        }
      })
       
         /** get edit  tax code details **/
       $(document).on('click','.edit-taxcodes',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTaxCodesById function from tesco-settings.js*/
           getTaxCodesById(id);
       });

       /** submit edit  tax code form **/
       $('#edit_tax_codes_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTaxCodes function from tesco-settings.js*/
            editTaxCode();
         }
       })

     /** Add Tesco material form validation **/
     $('#add_tesco_material_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addTescoMaterial function from  tesco-settings.js*/
           addTescoMaterial();
        }
      })
       
       /** get edit Tesco material details **/
       $(document).on('click','.edit-tesco-material',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           /* call getTescoMaterialById function from tesco-settings.js*/
           getTescoMaterialById(id);
       });

       /** submit edit Tesco material form **/
       $('#edit_tesco_material_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoMaterial function from tesco-settings.js*/
            editTescoMaterial();
         }
       })
       /** To Enable  Material  on click **/
       $(document).on('click', '#make_material_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeMaterialEnabled function from tesco-settings.js*/
           makeMaterialEnabled(id,csrf_token)
       });

       /** To disable Material  on click **/
       $(document).on('click', '#make_material_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeMaterialDisabled function from tesco-settings.js*/
           makeMaterialDisabled(id,csrf_token)
       });
     /** Add Tesco error report emails form validation **/
     $('#add_tesco_error_report_email_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           /* call addTescoErrorReportEmail function from  tesco-settings.js*/
           addTescoErrorReportEmail();
        }
      })
     /** get edit Tesco error report email details **/
     $(document).on('click','.edit-tesco-error-report-email',function(e){
         var id = $(this).closest('tr').attr('id');
         e.preventDefault();
         /* call getTescoErrorReportEmailById function from tesco-settings.js*/
         getTescoErrorReportEmailById(id);
     });
      /** submit edit Tesco error report email form **/
       $('#edit_tesco_error_report_email_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            /* call editTescoErrorReportEmail function from tesco-settings.js*/
            editTescoErrorReportEmail();
         }
       }) 
      /** To Enable  Tesco error report email  on click **/
       $(document).on('click', '#make_error_report_email_enabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeErrorReportEmailEnabled function from tesco-settings.js*/
           makeErrorReportEmailEnabled(id,csrf_token)
       });

       /** To disable Tesco error report email  on click **/
       $(document).on('click', '#make_error_report_email_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           /* call makeErrorReportEmailDisabled function from tesco-settings.js*/
           makeErrorReportEmailDisabled(id,csrf_token)
       });
      /** [ Make sidebar active ] */
       $('.meta-data-list li a').click(function(){
        $('.meta-data-list li a').removeClass("active");
        $(this).addClass("active");
      });
        $('.meta-data-list-1 li a').click(function(){
        $('.meta-data-list-1 li a').removeClass("active");
        $(this).addClass("active");
      });
    </script>
  
    @endsection