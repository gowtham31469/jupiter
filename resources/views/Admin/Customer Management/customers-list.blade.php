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
                    <a href="#" data-target="#addCustomerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Customer</a>
                  </div>
                </div>
               <div class="clearfix"></div>
                <div class="main-content">
                  <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                  <div class="col" id="customer-table">
                    <table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>Name</th>
                          <th></th>
                          <th>Default Contact</th>
                          <th>Billing Address</th>
                          <th>Shipping Address</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($customers as $value)
                          <tr id="{{$value->id}}">
                            <td rowspan="3">{{ $loop->iteration }}</td>
                            <td rowspan="3"><a href="#" title="view more" class="view-more" data-placement="left"  data-backdrop="static" data-keyboard="false">{{ $value->company_name }}</a></td>
                            <td><i class="fa fa-user" aria-hidden="true"></i></td>
                            <td>{{ $value->contact_person }}</td>
                            <td >
                              @if($value->bill_address != '-')
                                  <dd>{{ $value->bill_address }},{{ $value->bill_country }}-{{ $value->pincode }}</dd>
                                @else
                                  <dd>-</dd>
                                @endif
                            </td>
                            <td >
                              @if($value->ship_address != '-')
                                <dd>{{ $value->ship_address }},{{ $value->ship_country }}-{{ $value->pincode }}</dd>
                              @else
                                <dd>-</dd>
                              @endif
                            </td>
                            @if($value->is_active === 1)
                              <td rowspan="3"><span class="label label-success">Active</span></td>
                            @else
                              <td rowspan="3"><span class="label label-danger">Inactive</span></td>
                            @endif
                            @if($value->is_active === 1)
                              <td rowspan="3"><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>
                            @else
                              <td rowspan="3"><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a>
                            @endif
                                 <a href="#">
                                  <i class="fa fa-pencil filterbtn options edit-customer fa-button" title="Edit" data-placement="left"></i>
                                 </a>
                               </td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-envelope" aria-hidden="true"></i></td>
                            <td>{{ $value->contact_email }}</td>
                            <td>{{ $value->bill_email }}</td>
                            <td>{{ $value->ship_email }}</td>
                          </tr>
                          <tr>
                            <td><i class="fa fa-phone" aria-hidden="true"></i></td>
                            <td>{{ $value->phone_no }}</td>
                            <td>{{ $value->bill_telephone }}</td>
                            <td>{{ $value->ship_telephone }}</td>
                          </tr>
                         @endforeach
                         </tbody>
                     </table>
                  </div>
              </div>
            </div>
          </div>

    {{-- Show snackbar html code --}}
     <div id="snackbar"><span class="snackbar_span"></span></div>

    {{-- Add customer modal --}}
  <div class="modal fade bd-example-modal-lg" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
              </div>
              {!! Form::open(['id' => 'add_customer_form','data-toggle' => 'validator','role' => 'form']) !!}
                  <div class="modal-body">
                  <div id="customer_result"></div>
                    <div class="row">
                          <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('factory_code', 'Factory Code', ['class' => 'form_label']) !!}
                                  {!! Form::text('factory_code',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Factory Code','required','pattern'=>'[a-z0-9]+')) !!}
                                  <div class="help-block with-errors"></div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('short_name', 'Short Name', ['class' => 'form_label']) !!}
                                  {!! Form::text('short_name',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Short Name','required')) !!}
                                  <div class="help-block with-errors"></div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('company_name', 'Company Name', ['class' => 'form_label']) !!}
                                  {!! Form::text('company_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Company Name','required')) !!}
                                  <div class="help-block with-errors"></div>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                {!! Form::label('courier_choice', 'Courier Choice', ['class' => 'form_label']) !!}
                                {!! Form::text('courier_choice',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Courier Choice')) !!}
                                <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                {!! Form::label('tin_number', 'TIN Number', ['class' => 'form_label']) !!}
                                {!! Form::text('tin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TIN Number','required','maxlength' => '9','data-minlength' => '9')) !!}
                                <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                {!! Form::label('gstin_number', 'GSTIN Number', ['class' => 'form_label']) !!}
                                {!! Form::text('gstin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GSTIN Number','required','maxlength' => '15','data-minlength' => '15')) !!}
                                <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                                {!! Form::label('pan_number', 'PAN Number', ['class' => 'form_label']) !!}
                                {!! Form::text('pan_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PAN Number','required','data-minlength' => '10','maxlength' => '10')) !!}
                                <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('login_email', 'Email', ['class' => 'form_label']) !!}
                              <div class="input-group">
                                <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                {!! Form::email('login_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'example@gmail.com','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                              </div>
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                                {{ Form::select('buyer',$buyers,'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'buyer','required')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6 customers-metadata" style="display: none;">
                            <div class="form-group has-feedback">
                              {!! Form::label('customers_metadata', 'Select Customer From Metadata', ['class' => 'form_label']) !!}
                                {{ Form::select('customers_metadata',$tesco_customers,'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'customers_metadata')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('payment_terms', 'Select Payment Terms', ['class' => 'form_label']) !!}
                                {{ Form::select('payment_terms',(['' => '-- Select payment terms --']+ $payment_terms),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'payment_terms','required')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('place_of_supply', 'Select Place Of Supply', ['class' => 'form_label']) !!}
                                {{ Form::select('place_of_supply',(['' => '-- Select place of supply --']+ $place_of_supply),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'place_of_supply','required')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('freight', 'Select Freight Type', ['class' => 'form_label']) !!}
                                {{ Form::select('freight',(['' => '-- Select freight type --']+ $freight),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'freight','required')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6 forwarder_div">
                            <div class="form-group has-feedback">
                              {!! Form::label('forwarder', 'Select Nominated Forwarder', ['class' => 'form_label']) !!}
                                {{ Form::select('forwarder',(['' => '-- Select a forwarder --']+ $forwarder),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'forwarder')) }}
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback">
                              {!! Form::label('password', 'Password', ['class' => ' form_label']) !!}
                              <div class="input-group">
                                {!! Form::text('password',$value = '', array('class' => 'form-control js-copytextarea','placeholder' => 'Password','required','minlength' => '6')) !!}
                                <span id="generate_pwd" class="input-group-addon input-sm" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                                <span class="input-group-addon js-textareacopybtn clipboard input-sm" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#password">Copy</span>
                              </div>
                              <div class="help-block with-errors"></div>
                            </div>
                          </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-check form-group has-feedback">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="checkbox" value="" name="pi_required" id="pi_required">
                                  Proforma Invoice(PI) Required ? 
                                </label>
                                <div class="help-block with-errors"></div>
                              </div>
                              <div class="form-check form-group has-feedback po-required-div"  style="display: none;">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="checkbox" value="" name="po_required" id="po_required">
                                  Purchase Order(PO) Required ? 
                                </label>
                                <div class="help-block with-errors"></div>
                              </div>
                              <div class="form-check form-group has-feedback">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="checkbox" value="" name="delivery_date_required" id="delivery_date_required">
                                  Make Delivery Date Mandatory 
                                </label>
                                <div class="help-block with-errors"></div>
                              </div>
                              <div class="form-group has-feedback form-inline">
                                {!! Form::label('day_count', 'Deliver order after &nbsp;') !!}
                                {!! Form::text('day_count',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Days','required','style' => 'width: 60px;','maxlength' => '3','onkeypress' => 'return isNumber(event)')) !!}
                                {!! Form::label('day_count', '&nbsp;Days.') !!}  
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                          </div>
                        <h4><strong>Contact Information</strong></h4>
                          <hr>
                          <div class="row">
                            <div class="col-md-6 ">
                              <div id="addcontact">
                                  <div id="contact_0" class="addcontact">
                                      <div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div>
                                      <div id="contact0" class="panel">
                                        <div class="form-group has-feedback">
                                            {!! Form::label('contact_person0', 'Contact Person', ['class' => 'form_label']) !!}
                                            {!! Form::text('contact_person0',$value = '', array('class' => 'form-control input-sm contact_person','placeholder' => 'Contact Person Name','required')) !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                      <div class="form-group has-feedback">
                                        {!! Form::label('contact_phone0', 'Contact Phone No', ['class' => 'form_label']) !!}
                                        <div class="input-group">
                                          <span class="input-group-addon input-sm" id="basic-addon0">+91</span>
                                          {!! Form::text('contact_phone0',$value = '', array('class' => 'form-control input-sm contact_phone','placeholder' => 'Contact Phone No','data-minlength' => '10','data-maxlength' => '10','required','maxlength' => '10','onkeypress' => 'return isNumber(event)')) !!}
                                       </div>
                                       <div class="help-block with-errors"></div>
                                      </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('contact_email0', 'Contact Email', ['class' => 'form_label']) !!}
                                          <div class="input-group">
                                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                            {!! Form::email('contact_email0',$value = '', array('class' => 'form-control input-sm contact_email','placeholder' => 'Contact Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                                          </div>
                                          <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-check form-group has-feedback">
                                          <label class="form-check-label">
                                            <input class="form-check-input default_contact" type="checkbox" value="" name="default" id="default_contact0">
                                            Make as default contact person
                                          </label>
                                          <div class="help-block with-errors"></div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                  <button type="button" onclick="addContact(this,event)" data-id="0" class='btn btn-primary'><i class="ion ion-plus"></i> Contact Person</button>
                              </div>
                            </div>
                            <div class="col-md-6" style="border-left: 1px solid #ecf0f1;">
                              <div id="addbilladdress">
                                  <div id="billaddress_0" class="addbilladdress">
                                      <div type="button" class="accordion button-bottom"><h4>Address 1</h4></div>
                                      <div id="billaddress0" class="panel">
                                        <div class="form-group has-feedback">
                                          {!! Form::label('address0', 'Address', ['class' => 'form_label']) !!}
                                          {!! Form::textarea('address0',$value = '', array('class' => 'form-control address','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3,'onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                                          <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('pincode0', 'Pincode', ['class' => 'form_label']) !!}
                                            {!! Form::text('pincode0',$value = '', array('class' => 'form-control input-sm pincode','placeholder' => 'Pincode','required','data-minlength' => '6','maxlength' => '6','onkeypress' => 'return isNumber(event)')) !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            {!! Form::label('country0', 'Country', ['class' => 'form_label']) !!}
                                            {!! Form::text('country0',$value = '', array('class' => 'form-control input-sm country','placeholder' => 'Country','required')) !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('telephone0', 'Phone', ['class' => 'form_label']) !!}
                                          <div class="input-group ">
                                          <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                                            {!! Form::text('telephone0',$value = '', array('class' => 'form-control input-sm telephone','placeholder' => 'Phone','required','data-minlength' => '10','maxlength' => '10','onkeypress' => 'return isNumber(event)')) !!}
                                          </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group has-feedback">
                                            {!! Form::label('fax0', 'Fax', ['class' => 'form_label']) !!}
                                            {!! Form::text('fax0',$value = '', array('class' => 'form-control input-sm fax','placeholder' => 'Fax','onkeypress' => 'return isNumber(event)')) !!}
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('address_email0', 'Email', ['class' => 'form_label']) !!}
                                          <div class="input-group">
                                            <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                            {!! Form::email('address_email0',$value = '', array('class' => 'form-control input-sm address_email','placeholder' => 'Email','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                                          </div>
                                          <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-check form-group has-feedback" style="margin-top: 25px;">
                                          <label class="form-check-label">
                                            <input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address0">
                                            Make as default billing address
                                          </label>
                                          <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="form-check form-group has-feedback">
                                          <label class="form-check-label">
                                            <input class="form-check-input shipping_address" type="checkbox" value="" name="defaultShip1" id="shipping_address0">
                                            Make as default shipping address
                                          </label>
                                          <div class="help-block with-errors"></div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                  <button type="button" onclick="addAddress(this,event)" data-id="0" class='btn btn-primary'><i class="ion ion-plus"></i> Address</button>
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

{{-- Edit customer modal  --}}
<div class="modal fade bd-example-modal-lg" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Customer</h4>
      </div>
      {!! Form::open(['id' => 'edit_customer_form','data-toggle' => 'validator','role' => 'form']) !!}
      {!! Form::hidden('customer_edit_id',$value = null, array('id'=>'customer_edit_id','class' => 'form-control input-sm')) !!}
          <div class="modal-body">
          <div id="customer_edit_result"></div>
            <div class="row">
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('edit_factory_code', 'Factory Code', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_factory_code',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Factory Code','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('edit_short_name', 'Short Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_short_name',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Short Name','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('edit_company_name', 'Company Name', ['class' => 'form_label']) !!}
                          {!! Form::text('edit_company_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Company Name','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_courier_choice', 'Courier Choice', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_courier_choice',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Courier Choice')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_tin_number', 'TIN Number', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_tin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TIN Number','required','maxlength' => '9','data-minlength' => '9')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_gstin_number', 'GSTIN Number', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_gstin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GSTIN Number','required','maxlength' => '15','data-minlength' => '15')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                        {!! Form::label('edit_pan_number', 'PAN Number', ['class' => 'form_label']) !!}
                        {!! Form::text('edit_pan_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PAN Number','required','data-minlength' => '10','maxlength' => '10')) !!}
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_login_email', 'Email', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                        {!! Form::email('edit_login_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'example@gmail.com','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                        {{ Form::select('edit_buyer',$buyers,'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'edit_buyer','required')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6 edit-customers-metadata" style="display:none;">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_customers_metadata', 'Select Customer From Metadata', ['class' => 'form_label']) !!}
                        {{ Form::select('edit_customers_metadata',(['' => 'Choose one of the following...']),'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'edit_customers_metadata')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_payment_terms', 'Select Payment Terms', ['class' => 'form_label']) !!}
                        {{ Form::select('edit_payment_terms',(['' => '-- Select payment terms --']+ $payment_terms),'',array('class' => 'form-control input-sm selectpicker', 'data-width' => '100%','id' => 'edit_payment_terms','required')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_place_of_supply', 'Select Place Of Supply', ['class' => 'form_label']) !!}
                        {{ Form::select('edit_place_of_supply',(['' => '-- Select place of supply --']+ $place_of_supply),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_place_of_supply','required')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                        {!! Form::label('edit_freight', 'Select Freight Type', ['class' => 'form_label']) !!}
                          {{ Form::select('edit_freight',(['' => '-- Select freight type --']+ $freight),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_freight','required')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                    <div class="col-md-6 edit_forwarder_div">
                      <div class="form-group has-feedback">
                        {!! Form::label('edit_forwarder', 'Select Nominated Forwarder', ['class' => 'form_label']) !!}
                          {{ Form::select('edit_forwarder',(['' => '-- Select a forwarder --']+ $forwarder),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'edit_forwarder')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                  <div class="col-md-6 edit_pwd hidden">
                    <div class="form-group has-feedback">
                      {!! Form::label('edit_password', 'Password', ['class' => ' form_label']) !!}
                      <div class="input-group">
                        {!! Form::text('edit_password',$value = '', array('class' => 'form-control js-copytextarea','placeholder' => 'Password')) !!}
                        <span id="generate_edit_pwd" class="input-group-addon input-sm" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                        <span class="input-group-addon js-textareacopybtn clipboard input-sm" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#edit_password">Copy</span>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                    <div class="form-check form-group has-feedback">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="" name="edit_pi_required" id="edit_pi_required">
                        Proforma Invoice(PI) Required ? 
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-check form-group has-feedback edit-po-required-div"  style="display: none;">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="" name="edit_po_required" id="edit_po_required">
                        Purchase Order(PO) Required ? 
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-check form-group has-feedback">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="" name="edit_delivery_date_required" id="edit_delivery_date_required">
                        Make Delivery Date Mandatory 
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback form-inline">
                      {!! Form::label('edit_day_count', 'Deliver order after &nbsp;') !!}
                      {!! Form::text('edit_day_count',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Days','required','style' => 'width: 60px;','maxlength' => '3','onkeypress' => 'return isNumber(event)')) !!}
                      {!! Form::label('edit_day_count', '&nbsp;Days.') !!}  
                        <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  </div>
                  <h4><strong>Contact Information</strong></h4>
                  <hr>
              <div class="row">
                <div class="col-md-6 ">
                  <div id="editContact">
                      
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-group">
                      <button type="button" id="edit_contact" onclick="editAddContact(this,event)" data-id="1" class='btn btn-primary'><i class="ion ion-plus"></i> Contact Person</button>
                  </div>
                </div>
                <div class="col-md-6" style="border-left: 1px solid #ecf0f1;">
                  <div id="editbilladdress">
                      
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-group">
                      <button type="button" id="edit_address" onclick="editAddAddress(this,event)" data-id="1" class='btn btn-primary'><i class="ion ion-plus"></i> Address</button>
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
    {{-- view more modal --}}
    <div class="modal fade" id="viewMoreModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
                   <div class="modal-body">
                        <div class="panel panel-info">
                          <div class="panel-heading">
                            <h3 class="panel-title " id="customer-name"></h3>
                          </div>
                          <div class="panel-body">
                            <div class="row">
                              <div class=" col-md-12 col-lg-12 "> 
                                <table class="table table-user-information">
                                  <tbody>
                                     <tr>
                                      <td class="pull-left">Customer Short Name :</td>
                                      <td id="short-name"></td>
                                    </tr>
                                    <tr>
                                      <td class="pull-left">Factory Code :</td>
                                      <td id="factory-code"></td>
                                    </tr>
                                    <tr>
                                      <td class="pull-left">Email :</td>
                                      <td id="email"></td>
                                    </tr>
                                    <tr>
                                       <td class="pull-left">Buyer Name :</td>
                                       <td id="buyer-name"></td>
                                     </tr>
                                    <tr>
                                      <td class="pull-left">Courier Choice :</td>
                                      <td id="courier-choice"></td>
                                    </tr>
                                     <tr>
                                       <td class="pull-left">PAN Number :</td>
                                       <td id="pan-number"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">GSTIN Number :</td>
                                       <td id="gstin-number"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">TIN Number :</td>
                                       <td id="tin-number"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Payment Terms :</td>
                                       <td id="payment-terms"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Place Of Supply :</td>
                                       <td id="place-of-supply"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Freight Type :</td>
                                       <td id="freight-type"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Nominated Forwarder :</td>
                                       <td id="nominated-forwarder"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Is Delivery Date Mandatory ?</td>
                                       <td id="delivery-date-required"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Proforma Invoice(PI) Required ?</td>
                                       <td id="pi-required"></td>
                                     </tr>
                                     <tr>
                                       <td class="pull-left">Purchase Order(PO) Required ?</td>
                                       <td id="po-required"></td>
                                     </tr>
                                   
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                   </div>
                   <div class="modal-footer">
                       {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                   </div>
              {!! Form::close() !!}
           </div>
       </div>
   </div>
 <style type="text/css">
   .easy-autocomplete-container {
    z-index: 9;

    }
    table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
    border-right-width: 1px;
}
 </style>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/customer.js') }}"></script>
    <script>
    $( document ).ready(function() {
      /* Show logo on left side */
      $('.logo').html('Customers')
      $('#add_customer_form').trigger("reset");
      $('#customer_result').hide();
      $('#customer_edit_result').hide();
      $('#customers-list-table').DataTable();
      $("#customers-list-table_filter input").addClass('form-control');
      $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
      /* Refresh auto populate country functionality */
      autopopulateCountry(0)
      $('#payment_terms').val('0').selectpicker('refresh');
      $('#edit_payment_terms').val('0').selectpicker('refresh');
      $('#place_of_supply').val('0').selectpicker('refresh');
      $('#edit_place_of_supply').val('0').selectpicker('refresh');
      $('#edit_buyer').val('0').selectpicker('refresh');
      $('#buyer').val('0').selectpicker('refresh');
      $('#edit_customers_metadata').val('0').selectpicker('refresh');
      $('#customers_metadata').val('0').selectpicker('refresh');
      $('.edit-customer').tooltip('show')
      $('.edit-customer').tooltip('toggle')
      $('.status').tooltip('show')
      $('.status').tooltip('toggle')
      $('.view-more').tooltip('show')
      $('.view-more').tooltip('toggle')
      $('.dataTables_length select').addClass('form-control');
      $('div.freight_charge_div').hide()
      $('.forwarder_div').hide(); 
      $('#customers_metadata').prop('required',false)
    });
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    /** restrict special characters (except @, ., _ **/
    $('input').on('input', function() {
      var c = this.selectionStart,
          r = /[^a-z0-9@._ ]/gi,
          v = $(this).val();
      if(r.test(v))
      {
        $(this).val(v.replace(r, ''));
        c--;
      }
      this.setSelectionRange(c, c);
    });

    /** To refresh modal and form data**/
    $('.modal').on('hidden.bs.modal', function (e) {
      $('#add_customer_form').trigger('reset');
      $('#customer_result').hide();
      $('#edit_buyer').val('0').selectpicker('refresh');
      $('#buyer').val('0').selectpicker('refresh');
      $('#payment_terms').val('0').selectpicker('refresh');
      $('#edit_payment_terms').val('0').selectpicker('refresh');
      $('#place_of_supply').val('0').selectpicker('refresh');
      $('#edit_place_of_supply').val('0').selectpicker('refresh');
      $('.forwarder_div').hide();
      $('#edit_customers_metadata').val('0').selectpicker('refresh');
      $('#customers_metadata').val('0').selectpicker('refresh');
      $('#customers_metadata').prop('required',false)
     })

    /** Generate random password for customer **/
    $("#generate_pwd").validator().click(function(){
      $("#password").val( randomString(10, 'aA#!'));
      $('#add_customer_form').validator('validate');
    })

    /** generate random password while editing customer **/
    $("#generate_edit_pwd").validator().click(function(){
      $("#edit_password").val( randomString(10, 'aA#!'));
      $('#edit_customer_form').validator('validate');
    })

    /** function to generate random strings */
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

    /** Copy password to clipboard **/
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

    /** submit add customer form and check validation **/
    $('#add_customer_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         /* call addCustomer function from customer.js*/
         addCustomer();
      }
    })

    /** To activate customers on click **/
    $(document).on('click', '#make_active', function(e) {
        var id = $(this).closest('tr').attr('id');
        /* call makeCustomerActive function from customer.js*/
        makeCustomerActive(id,csrf_token)
    });

    /** To deactivate customers on click **/
    $(document).on('click', '#make_inactive', function(e) {
        var id = $(this).closest('tr').attr('id');
        /* call makeCustomerInactive function from customer.js*/
        makeCustomerInactive(id,csrf_token)
    });

    /** Get edit customer details on click and populate details on edit form **/
     $(document).on('click','.edit-customer',function(e){
         var id = $(this).closest('tr').attr('id');
         e.preventDefault();
         /* call getCustomer function from customer.js*/
         getCustomer(id);
     });

    /** submit edit customer form and check validation **/
    $('#edit_customer_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         /* call editCustomer function from customer.js*/
         editCustomer();
      }
    })

     /** [ To hide/show PO required checkbox based on PI required checkbox begins] */
       $(document).ready(function($) {
        // While adding customer
        $('#pi_required').click(function() {
           if($('#pi_required').is(':checked'))
           {
            $('div.po-required-div').show();
            /** [ uncheck po required checkbox on page load ] */
            $('input:checkbox[name=po_required]').prop('checked',false);
           }
           else
           {
            $('div.po-required-div').hide();
            /** [ uncheck po required checkbox while hidden ] */
            $('input:checkbox[name=po_required]').prop('checked',false);
           }
        });

        // While editing customer
        $('#edit_pi_required').click(function() {
           if($('#edit_pi_required').is(':checked'))
           {
            $('div.edit-po-required-div').show();
            /** [ uncheck po required checkbox on page load ] */
            $('input:checkbox[name=edit_po_required]').prop('checked',false);
           }
           else
           {
            $('div.edit-po-required-div').hide();
            /** [ uncheck po required checkbox while hidden ] */
            $('input:checkbox[name=edit_po_required]').prop('checked',false);
           }
        });
        /** [ To hide/show Nominated forwarder dropdown ( Based on freight type selected :Nominated Forwarded) ] */
          $('#freight').change(function(){
              if($('#freight option:selected').text() === "Nominated Forwarded") {
                  $('.forwarder_div').show(); 
                  $('#forwarder').prop('required',true);
              } else {
                  $('.forwarder_div').hide(); 
                  $('#forwarder').prop('required',false);
              } 
        });
          $('#edit_freight').change(function(){
              if($('#edit_freight option:selected').text() === "Nominated Forwarded") {
                  $('.edit_forwarder_div').show(); 
                  $('#edit_forwarder').prop('required',true);
              } else {
                  $('.edit_forwarder_div').hide(); 
                  $('#edit_forwarder').prop('required',false);
              } 
        });
        var buyers = [];
        var customers;
        /** [ To toggle suppliers dropdown mandatory field validation( Based on buyer select) ] */
        $('#buyer').change(function(){
          /** [Push selected buyers into a array] */
          $.each($("#buyer option:selected"), function(){
            buyers.push($(this).text());
          });
          /** [Check if array contains "tesco", then hide/show tesco suppliers dropdown] */
          if(jQuery.inArray("Tesco", buyers) !== -1)
          {
              $('.customers-metadata').show();
              $('#customers_metadata').prop('required',true);
          } 
          else 
          {
              $('.customers-metadata').hide();
              $('#customers_metadata').val('0').selectpicker('refresh');
              $('#customers_metadata').prop('required',false);
          } 
          buyers = [];
        });
        $('#edit_buyer').change(function(){
          /** [Push selected buyers into a array] */
          $.each($("#edit_buyer option:selected"), function(){
            buyers.push($(this).text());
          });
           /** [Check if array contains "tesco", then hide/show tesco suppliers dropdown] */
          if(jQuery.inArray("Tesco", buyers) !== -1)
          {
              $('.edit-customers-metadata').show();
              $('#edit_customers_metadata').prop('required',true);
          } 
          else 
          {
              $('.edit-customers-metadata').hide();
              $('#edit_customers_metadata').val('0').selectpicker('refresh');
              $('#edit_customers_metadata').prop('required',false);
          } 
          buyers = [];
        });
     }); 
     /** Get customer details on click and populate details on modal **/
     $(document).on('click','.view-more',function(e){
         var id = $(this).closest('tr').attr('id');
         e.preventDefault();
         /* call getCustomerDetails function from customer.js*/
         getCustomerDetails(id);
     });
    </script>
    @endsection
