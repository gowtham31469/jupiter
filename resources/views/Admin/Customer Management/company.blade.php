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
                          <th>Email</th>
                          <th>Company</th>
                          <th>Role</th>
						  <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($customers as $value)
                          <tr id="{{$value->id}}">
                          	<td>{{ $loop->iteration }}</td>
							  <td>{{$value->name}}</td>
							  <td>{{$value->email}}</td>
							  <td>{{$value->company_name}}</td>
							  <td>{{$value->role_name}}</td>
							   @if($value->status === 1)
                              <td><span class="label label-success">Active</span></td>
                            @else
                              <td><span class="label label-danger">Inactive</span></td>
                            @endif
                            @if($value->status === 1)
                              <td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>
                            @else
                              <td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a>
                            @endif
                                 <a href="#">
                                  <i class="fa fa-pencil filterbtn options edit-customer fa-button" title="Edit" data-placement="left"></i>
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
                        {!! Form::label('customer_add_title', 'Select Title', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_add_title',(['' => '-- Select Title --','Mr' => 'Mr','Mrs' => 'Mrs']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_add_title','required')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
						
                          <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('customer_add_name', 'Customer Name', ['class' => 'form_label']) !!}
                          {!! Form::text('customer_add_name',$value = '', array('class' => 'form-control input-sm','id' => 'customer_add_name','placeholder' => 'Customer Name','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('customer_add_email', 'Email', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                        {!! Form::email('customer_add_email',$value = '', array('class' => 'form-control input-sm ','id' => 'customer_add_email','placeholder' => 'example@gmail.com','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
						<div class="col-md-6 edit_forwarder_div">
                      <div class="form-group has-feedback">
                        {!! Form::label('customer_add_company', 'Select Company', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_add_company',(['' => '-- Select Company --']+ $company),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_add_company','required')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
				  <div class="col-md-6 customer_add_buyer_div" style="display:none;">
                    <div class="form-group has-feedback">
                      {!! Form::label('customer_add_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                        {{ Form::select('customer_add_buyer',$buyers,'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'customer_add_buyer','required')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                        {!! Form::label('customer_add_role', 'Select Role', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_add_role',(['' => '-- Select Role --']+ $roles),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_add_role','required')) }}
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
                        {!! Form::label('customer_title', 'Select Title', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_title',(['' => '-- Select Title --','Mr' => 'Mr','Mrs' => 'Mrs']),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_title','required')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
				
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                          {!! Form::label('customer_name', 'Customer Name', ['class' => 'form_label']) !!}
                          {!! Form::text('customer_name',$value = '', array('class' => 'form-control input-sm','id' => 'customer_name','placeholder' => 'Customer Name','required')) !!}
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group has-feedback">
                      {!! Form::label('customer_email', 'Email', ['class' => 'form_label']) !!}
                      <div class="input-group">
                        <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                        {!! Form::email('customer_email',$value = '', array('class' => 'form-control input-sm ','id' => 'customer_email','placeholder' => 'example@gmail.com','required','onkeyup' => '$(this).val($(this).val().toLowerCase());')) !!}
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
				 <div class="col-md-6 edit_forwarder_div">
                      <div class="form-group has-feedback">
                        {!! Form::label('customer_company', 'Select Company', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_company',(['' => '-- Select Company --']+ $company),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_company','required')) }}
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
				  <div class="col-md-6 customer_buyer_div">
                    <div class="form-group has-feedback">
                      {!! Form::label('customer_buyer', 'Select Buyer', ['class' => 'form_label']) !!}
                        {{ Form::select('customer_buyer',$buyers,'',array('class' => 'form-control input-sm selectpicker','data-actions-box' => 'true', 'data-width' => '100%','multiple' =>'true','data-live-search'=>'true','multiple title'=>'Choose one of the following...','id' => 'customer_buyer','required')) }}
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group has-feedback">
                        {!! Form::label('customer_role', 'Select Role', ['class' => 'form_label']) !!}
                          {{ Form::select('customer_role',(['' => '-- Select Role --']+ $roles),'',array('class' => 'form-control input-sm selectpicker','data-width' => '100%','id' => 'customer_role','required','disabled')) }}
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
    <script src="{{ asset('js/Admin/customer_profile.js') }}"></script>
    <script>
    $( document ).ready(function() {
      /* Show logo on left side */
      $('.logo').html('Customers')
      $('#add_customer_form').trigger("reset");
      var table =   $('#customers-list-table').DataTable();
		
	  table.order( [ 5, 'asc' ] ).draw();
		
	  $("#customers-list-table_filter input").addClass('form-control');
	  $('.dataTables_length select').addClass('form-control');
	  $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
    });
	
	/** Generate random password for customer **/
    $("#generate_pwd").validator().click(function(){
      $("#password").val( randomString(10, 'aA#!'));
      $('#add_customer_form').validator('validate');
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
		
		 $(function () {
                    var url = window.location;
                    $('.dynamic-menu a[href="' + url.toString().replace("#","") + '"]').parent('li').addClass('current-page');
                    $('.dynamic-menu a').filter(function () {
                        return this.href == url.toString().replace("#","");
                    }).parent('li').addClass('current-page').find('a').addClass('active');
          });
		
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
		 $('#customer_add_company').change(function(){
			  if($("#customer_add_company option:selected").text() == "-- Select Company --")
			  {
				  $('.customer_add_buyer_div').hide();
				  $('#customer_add_buyer').val('0').selectpicker('refresh');
				  $('#customer_add_buyer').prop('required',false);
			  } 
			  else 
			  {
				  $('#customer_add_buyer').html('')
				  $("#customer_add_role option[value='1']").show();
				    var csrf_token           =  $('meta[name="csrf-token"]').attr('content');
				    var customer_add_company =  $('#customer_add_company').val();
					$.ajax({
						url: base_url + "/admin/customer-management/customer-company-buyer",
						method: 'POST',
						data: {_token:csrf_token,customer_add_company:customer_add_company},
						tryCount : 0,
						retryLimit : 2,
						beforeSend: function () {
						},
						statusCode: {

							 /** [ Show response status based on HTTP status code ] */
							 200: function(data) {
								if(data.role.length != 0)
								{
									$("#customer_add_role option[value='1']").hide();
								}
								 $(data.customer_list).each(function(key,value){
									$('#customer_add_buyer').append('<option value='+value.buyer_id+'>'+value.buyer_name+'</option>')
								 });
								 $('#customer_add_buyer').selectpicker('refresh');
								 $('#customer_add_role').selectpicker('refresh');
							 }
						},
						error: function(errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						 },
						complete: function () {

						}
					})
				  
				  $('.customer_add_buyer_div').show();
				  $('#customer_add_buyer').prop('required',true);
			  } 
        });
		
		 $('#customer_company').change(function(){
			  if($("#customer_company option:selected").text() == "-- Select Company --")
			  {
				  $('.customer_buyer_div').hide();
				  $('#customer_buyer').val('0').selectpicker('refresh');
				  $('#customer_buyer').prop('required',false);
			  } 
			  else 
			  {
				  $('#customer_buyer').html('')
				    var csrf_token           =  $('meta[name="csrf-token"]').attr('content');
				    var customer_add_company =  $('#customer_company').val();
					$.ajax({
						url: base_url + "/admin/customer-management/customer-company-buyer",
						method: 'POST',
						data: {_token:csrf_token,customer_add_company:customer_add_company},
						tryCount : 0,
						retryLimit : 2,
						beforeSend: function () {
						},
						statusCode: {

							 /** [ Show response status based on HTTP status code ] */
							 200: function(data) {
								 $(data.customer_list).each(function(key,value){
									$('#customer_buyer').append('<option value='+value.buyer_id+'>'+value.buyer_name+'</option>')
								 });
								 $('#customer_buyer').selectpicker('refresh');
							 }
						},
						error: function(errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						 },
						complete: function () {

						}
					})
				  
				  $('.customer_buyer_div').show();
				  $('#customer_buyer').prop('required',true);
			  } 
        });
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    /** To refresh modal and form data**/
    $('.modal').on('hidden.bs.modal', function (e) {
      $('#add_customer_form').trigger('reset');
      $('#customer_result').hide();
      $('#customer_add_role').val('0').selectpicker('refresh');
      $('#customer_add_company').val('0').selectpicker('refresh');
      $('#customer_add_buyer').val('0').selectpicker('refresh');
      $('#customer_add_title').val('0').selectpicker('refresh');
     })
    </script>
    @endsection
