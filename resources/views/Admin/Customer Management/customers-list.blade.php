@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                <li>
                  <a href="/admin/customer-management">Dashboard</a>
                </li>
                <li class="active">Customers</li>
                </ol>
              </div>
                <div class="main-sub-menu">
                  <div class="action-group pull-right sub-menu-button">
                    <a href="#" data-target="#addCustomerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Customer</a>
                  </div>
                </div>
               <div class="clearfix"></div>
                <div class="main-content">
                  <div class="loader"></div>
                  <div class="col" id="customer-table">
                    <table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Company Name</th>
                          <th>Factory Code</th>
                          <th>Default Contact</th>
                          <th>Courier Choice</th>
                          <th>Account Number</th>
                          <th>TIN Number</th>
                          <th>GSTIN Number</th>
                          <th>PAN Number</th>
                          <th>Billing Address</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($customers as $value)
                        <tr id="{{$value->id}}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $value->company_name }}</td>
                          <td>{{ $value->factory_code }}</td>
                          <td>
                            <dl>
                              <dt>Contact Person:</dt>
                              <dd>{{ $value->contact_person }}</dd>
                              <dt>Contact Email:</dt>
                              <dd>{{ $value->contact_email }}</dd>
                              <dt>Contact Phone:</dt>
                              <dd>{{ $value->phone_no }}</dd>
                            </dl>
                          </td>
                          <td>{{ $value->courier_choice }}</td>
                          <td>{{ $value->account_no }}</td>
                          <td>{{ $value->tin_number }}</td>
                          <td>{{ $value->gstin_number }}</td>
                          <td>{{ $value->pan_number }}</td>
                          <td>
                            <dl>
                              <dt>Address:</dt>
                              <dd>{{ $value->address }},{{ $value->country }}</dd>
                              <dt>Email:</dt>
                              <dd>{{ $value->email }}</dd>
                              <dt>Phone:</dt>
                              <dd>{{ $value->telephone }}</dd>
                              <dt>Fax:</dt>
                              <dd>{{ $value->fax }}</dd>
                            </dl>
                          </td>
                          @if($value->is_active === 1)
                            <td><span class="badge badge-success">Active</span></td>
                          @else
                            <td><span class="badge badge-danger">Inactive</span></td>
                          @endif
                          @if($value->is_active === 1)
                            <td><a href="#"><i class="fa fa-times fa-lg fa-button" aria-hidden="true" title="Make Inactive" id="make_inactive"></i></a>
                          @else
                            <td><a href="#"><i class="fa fa-check fa-lg fa-button" aria-hidden="true" title="Make Active" id="make_active"></i></a>
                          @endif
                               <a href="#">
                                <i class="fa fa-pencil fa-lg edit-customer fa-button" title="Edit"></i>
                               </a>
                               <a href="#">
                                <i class="fa fa-trash fa-lg fa-button" data-userid="{{$value->id}}" title="Delete" data-toggle="modal" data-target="#deleteCustomerModal" ></i>
                               </a>
                             </td>
                         </tr>
                         @endforeach
                         </tbody>
                     </table>
                  </div>
              </div>
            </div>
          </div>
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
                                    {!! Form::text('factory_code',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Factory Code','required')) !!}
                                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    {!! Form::label('company_name', 'Company Name', ['class' => 'form_label']) !!}
                                    {!! Form::text('company_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Company Name','required')) !!}
                                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('courier_choice', 'Courier Choice', ['class' => 'form_label']) !!}
                                  {!! Form::text('courier_choice',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Courier Choice','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('account_no', 'Account Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('account_no',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Account Number','required','onkeypress' => 'return isNumber(event)')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('tin_number', 'TIN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('tin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TIN Number','required','maxlength' => '9','data-minlength' => '9')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('gstin_number', 'GSTIN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('gstin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GSTIN Number','required','maxlength' => '15','data-minlength' => '15')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('pan_number', 'PAN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('pan_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PAN Number','required','data-minlength' => '10','maxlength' => '10')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                {!! Form::label('login_email', 'Email', ['class' => 'form_label']) !!}
                                <div class="input-group">
                                  <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                  {!! Form::email('login_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'example@gmail.com','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                </div>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                {!! Form::label('password', 'Password', ['class' => ' form_label']) !!}
                                <div class="input-group">
                                  {!! Form::text('password',$value = '', array('class' => 'form-control js-copytextarea','placeholder' => 'Password','required','data-minlength' => '6')) !!}
                                  <span id="generatePwd" class="input-group-addon input-sm" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                                  <span class="input-group-addon js-textareacopybtn clipboard input-sm" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#password">Copy</span>
                                </div>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            </div>
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
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('contact_phone0', 'Contact Phone No', ['class' => 'form_label']) !!}
                                          <div class="input-group">
                                            <span class="input-group-addon input-sm" id="basic-addon0">+91</span>
                                            {!! Form::text('contact_phone0',$value = '', array('class' => 'form-control input-sm contact_phone','placeholder' => 'Contact Phone No','data-minlength' => '10','data-maxlength' => '10','required','maxlength' => '10','onkeypress' => 'return isNumber(event)')) !!}
                                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                         </div>
                                         <div class="help-block with-errors"></div>
                                        </div>
                                          <div class="form-group has-feedback">
                                            {!! Form::label('contact_email0', 'Contact Email', ['class' => 'form_label']) !!}
                                            <div class="input-group">
                                              <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                              {!! Form::email('contact_email0',$value = '', array('class' => 'form-control input-sm contact_email','placeholder' => 'Contact Email','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-check">
                                            <label class="form-check-label">
                                              <input class="form-check-input default_contact" type="checkbox" value="" name="default" id="default_contact0">
                                              Make as default contact person
                                            </label>
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
                                        <div type="button" class="accordion button-bottom"><h4>Billing Address 1</h4></div>
                                        <div id="billaddress0" class="panel">
                                          <div class="form-group has-feedback">
                                            {!! Form::label('address0', 'Address', ['class' => 'form_label']) !!}
                                            {!! Form::textarea('address0',$value = '', array('class' => 'form-control address','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                              {!! Form::label('country0', 'Country', ['class' => 'form_label']) !!}
                                              {!! Form::text('country0',$value = '', array('class' => 'form-control input-sm country','placeholder' => 'Country','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                            {!! Form::label('telephone0', 'Phone', ['class' => 'form_label']) !!}
                                            <div class="input-group ">
                                            <span class="input-group-addon input-sm" id="basic-addon1">+91</span>
                                              {!! Form::text('telephone0',$value = '', array('class' => 'form-control input-sm telephone','placeholder' => 'Phone','required','data-minlength' => '10','maxlength' => '10','onkeypress' => 'return isNumber(event)')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            </div>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                              {!! Form::label('fax0', 'Fax', ['class' => 'form_label']) !!}
                                              {!! Form::text('fax0',$value = '', array('class' => 'form-control input-sm fax','placeholder' => 'Fax','required','onkeypress' => 'return isNumber(event)')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                            {!! Form::label('address_email0', 'Email', ['class' => 'form_label']) !!}
                                            <div class="input-group">
                                              <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                              {!! Form::email('address_email0',$value = '', array('class' => 'form-control input-sm address_email','placeholder' => 'Email','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-check">
                                            <label class="form-check-label">
                                              <input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address0">
                                              Make as default billing address
                                            </label>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <button type="button" onclick="addAddress(this,event)" data-id="0" class='btn btn-primary'><i class="ion ion-plus"></i> Billing Address</button>
                                </div>
                              </div>
                            </div>
                         </div>
                            <div class="modal-footer">
                                {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                                {!! Form::submit('Add Customer', ['class' => 'btn btn-primary'] ) !!}
                            </div>
                           {!! Form::close() !!}
                    </div>
                </div>
              </div>
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
                                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    {!! Form::label('edit_company_name', 'Company Name', ['class' => 'form_label']) !!}
                                    {!! Form::text('edit_company_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Company Name','required')) !!}
                                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('edit_courier_choice', 'Courier Choice', ['class' => 'form_label']) !!}
                                  {!! Form::text('edit_courier_choice',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Courier Choice','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('edit_account_no', 'Account Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('edit_account_no',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Account Number','required','onkeypress' => 'return isNumber(event)')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('edit_tin_number', 'TIN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('edit_tin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TIN Number','required','maxlength' => '9','data-minlength' => '9')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('edit_gstin_number', 'GSTIN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('edit_gstin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GSTIN Number','required','maxlength' => '15','data-minlength' => '15')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                  {!! Form::label('edit_pan_number', 'PAN Number', ['class' => 'form_label']) !!}
                                  {!! Form::text('edit_pan_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'PAN Number','required','data-minlength' => '10','maxlength' => '10')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group has-feedback">
                                {!! Form::label('edit_login_email', 'Email', ['class' => 'form_label']) !!}
                                <div class="input-group">
                                  <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                                  {!! Form::email('edit_login_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'example@gmail.com','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                </div>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            </div>
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
                                    <button type="button" id="edit_address" onclick="editAddAddress(this,event)" data-id="1" class='btn btn-primary'><i class="ion ion-plus"></i> Billing Address</button>
                                </div>
                              </div>
                            </div>
                         </div>
                            <div class="modal-footer">
                                {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                                {!! Form::submit('Edit Customer', ['class' => 'btn btn-primary'] ) !!}
                            </div>
                           {!! Form::close() !!}
                    </div>
                </div>
              </div>
    <div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title" id="myModalLabel">Delete Customer</h4>
             </div>
             {!! Form::open(['id' => 'delete_customer_form','data-toggle' => 'validator','role' => 'form']) !!}
                 <div class="modal-body">
                   <div id="delete_buyer_result"></div>
                     <div class="form-group has-feedback">
                       <p>You are about to delete customer. Do you want to proceed?</p>
                        {!! Form::hidden('id',$value = null, array('id'=>'delete_customer_id','class' => 'form-control input-sm')) !!}
                       <div class="help-block with-errors"></div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                     {!! Form::submit('Delete Customer', ['class' => 'btn btn-primary'] ) !!}
                 </div>
            {!! Form::close() !!}
         </div>
     </div>
 </div>
 <style type="text/css">
   .easy-autocomplete-container {
    z-index: 9;

    }
 </style>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script>
    $( document ).ready(function() {
        
      $('.logo').html('Customers')
      $('#add_customer_form').trigger("reset");
      $('#customer_result').hide();
      $('#customer_edit_result').hide();
      $('#customers-list-table').DataTable();
      $("#customers-list-table_filter input").addClass('form-control');
      $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
            autopopulateCountry(0)
    });
    /** restrict special characters **/
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
    /** To refresh modal **/
    $('.modal').on('hidden.bs.modal', function (e) {
      $('#add_customer_form').trigger('reset');
      $('#customer_result').hide();
     })
    /** generate password **/
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
      $("#password").val( randomString(10, 'aA#!'));
      $('#add_customer_form').validator('validate');
      })
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
      /** submit add customer form **/
      $('#add_customer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           addCustomer();
        }
      })

      var acc = document.getElementsByClassName("accordion");
      var i;
      for (i = 0; i < acc.length; i++) {
          acc[i].onclick = function() {
              this.classList.toggle("active");
              var panel = this.nextElementSibling;
              if (panel.style.display === "block") {
                  panel.style.display = "none";
              } else {
                  panel.style.display = "block";
              }
          }
      }
    // to activate customers
    $(document).on('click', '#make_active', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        makeCustomerActive(id,csrf_token)
    });
    // to deactivate customers
    $(document).on('click', '#make_inactive', function(e) {
        var id = $(this).closest('tr').attr('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        makeCustomerInactive(id,csrf_token)
    });
    // get edit customer details
       $(document).on('click','.edit-customer',function(e){
           var id = $(this).closest('tr').attr('id');
           e.preventDefault();
           getCustomer(id);
       });
    /** submit edit customer form **/
      $('#edit_customer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           editCustomer();
        }
      })
    $('#deleteCustomerModal').on('show.bs.modal', function (e) {
           var config = $(e.relatedTarget).data('userid');
           $('#delete_customer_id').attr('value', config);
       });
       /** submit delete buyer form **/
       $('#delete_customer_form').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            var id   = $('#delete_customer_id').val();
            deleteCustomer(id);
         }
       })
    </script>
    @endsection
