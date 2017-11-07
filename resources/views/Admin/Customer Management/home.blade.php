@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">Customer Management</li>
                  </ol>
                <h3><i class="ion-ios-browsers-outline"></i>Customer Management</h3>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#" onClick="window.location.reload()"><i class="ion-grid"></i>Dashboard</a></li>
                    <li class="suppliers-list-view"><a class="" href="#" onclick="customersListView()"><i class="ion-ios-pricetag"></i>Customers</a></li>
                </ul>
                <hr/>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart" width="100px" height="100px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart1" width="100px" height="100px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <h4 class="text-center">Total Orders</h4>
                            <canvas id="myChart2" width="100px" height="100px"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <h4 class="text-center">Tabel Data</h4>
                            <div class="table-responsive">
                                <table class="table table-striped text-center">

                                    <thead>
                                        <tr>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Payment Date</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Product1</td>
                                            <td>23/11/2013</td>
                                            <td><label class="label label-warning">Pending</label></td>
                                        </tr>

                                        <tr>
                                            <td>Product2</td>
                                            <td>10/11/2013</td>
                                            <td><label class="label label-success">Delivered</label></td>
                                        </tr>

                                        <tr>
                                            <td>Product3</td>
                                            <td>20/10/2013</td>
                                            <td><label class="label label-info">In Call to confirm</label></td>
                                        </tr>

                                        <tr>
                                            <td>Product4</td>
                                            <td>20/10/2013</td>
                                            <td><label class="label label-danger">Declined</label></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card"></div>
                    </div>
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
                                  {!! Form::text('tin_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'TIN Number','required')) !!}
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
                                {!! Form::label('login_email', 'Username', ['class' => 'form_label']) !!}
                                <div class="input-group">
                                  <span class="input-group-addon" id="basic-addon1">@</span>
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
                                  {!! Form::text('password',$value = '', array('class' => 'form-control input-sm js-copytextarea','placeholder' => 'Password','required')) !!}
                                  <span id="generatePwd" class="input-group-addon" style="background-color:#18bc9c;border-right: white;border-style: solid;color:white;">Generate Password</span>
                                  <span class="input-group-addon js-textareacopybtn clipboard" style="background-color:#2c3e50;color:white;"  data-clipboard-target="#password">Copy</span>
                                </div>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-md-6 ">
                                <div id="addcontact">
                                    <div id="contact_1" class="addcontact">
                                        <button type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></button>
                                        <div id="contact1" class="panel">
                                          <div class="form-group has-feedback">
                                              {!! Form::label('contact_person1', 'Contact Person', ['class' => 'form_label']) !!}
                                              {!! Form::text('contact_person1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Person Name','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                        <div class="form-group has-feedback">
                                          {!! Form::label('contact_phone1', 'Contact Phone No', ['class' => 'form_label']) !!}
                                          <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">+91</span>
                                            {!! Form::text('contact_phone1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Phone No','data-minlength' => '10','data-maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                         </div>
                                         <div class="help-block with-errors"></div>
                                        </div>
                                          <div class="form-group has-feedback">
                                            {!! Form::label('contact_email1', 'Contact Email', ['class' => 'form_label']) !!}
                                            <div class="input-group">
                                              <span class="input-group-addon" id="basic-addon1">@</span>
                                              {!! Form::email('contact_email1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Contact Email','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-check">
                                            <label class="form-check-label">
                                              <input class="form-check-input" type="checkbox" value="" name="default">
                                              Make as default contact person
                                            </label>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <button type="button" onclick="add(this,event)" data-id="1" class='btn btn-primary'><i class="ion ion-plus"></i> Contact Person</button>
                                </div>
                              </div>
                              <div class="col-md-6" style="border-left: 1px solid #ecf0f1;">
                                <div id="addbilladdress">
                                    <div id="billaddress_1" class="addbilladdress">
                                        <button type="button" class="accordion button-bottom"><h4>Billing Address 1</h4></button>
                                        <div id="billaddress1" class="panel">
                                          <div class="form-group has-feedback">
                                            {!! Form::label('address1', 'Address', ['class' => 'form_label']) !!}
                                            {!! Form::textarea('address1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                              {!! Form::label('country1', 'Country', ['class' => 'form_label']) !!}
                                              {!! Form::text('country1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Country','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                              {!! Form::label('telephone1', 'Telephone', ['class' => 'form_label']) !!}
                                              {!! Form::text('telephone1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Telephone','required','data-minlength' => '10','data-maxlength' => '10','onkeypress' => 'return isNumber(event)')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                              {!! Form::label('fax1', 'Fax', ['class' => 'form_label']) !!}
                                              {!! Form::text('fax1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Fax','required','onkeypress' => 'return isNumber(event)')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                              <div class="help-block with-errors"></div>
                                          </div>
                                          <div class="form-group has-feedback">
                                            {!! Form::label('address_email1', 'Email', ['class' => 'form_label']) !!}
                                            <div class="input-group">
                                              <span class="input-group-addon" id="basic-addon1">@</span>
                                              {!! Form::email('address_email1',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                                              <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <button type="button" onclick="addAddress(this,event)" data-id="1" class='btn btn-primary'><i class="ion ion-plus"></i> Billing Address</button>
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
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script>
    $( document ).ready(function() {
      $('#add_customer_form').trigger("reset");
      $('#customer_result').hide();
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
      /** submit add supplier form **/
      $('#add_customer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           addSupplier();
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
      $(':checkbox').on('change',function(){
       var th = $(this), name = th.prop('name');
       if(th.is(':checked')){
           $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
        }
      });
    //   $(':checkbox').on('change', function() {
    //
    //   $(':checkbox').not(this).prop('checked', false);
    //
    // });
    </script>
    @endsection
