@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
      {{-- Show select buyer image as default page --}}
        <div class="select-buyer">
        </div>
        <div class="tesco-sidebar sidebar style-4" >
          <h4>Metadata</h4>
          <ul class="customer-select meta-data-list-1">
            <li id="5"  onclick="taxCode()" ><a class="active">Tax Code</a></li>
            <li id="6"  onclick="tescoMaterialMasters()"><a>Material Master</a></li>
            <li id="7"  onclick="tescoErrorReportEmail()"><a>Error Report Email</a></li>
          </ul>
        </div>
        <div class="content">
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
                          {!! Form::text('tax',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax In %','required','onkeypress' => 'return isNumber(event)')) !!}
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
                          {!! Form::text('edit_tax',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Tax In %','required','onkeypress' => 'return isNumber(event)')) !!}
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
    {{-- Add Raw Material Master Modal --}}
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

    {{-- Edit Raw material Modal --}}
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
	  taxCode();
 
      /** To refresh modal while hidden **/
      $('.modal').on('hidden.bs.modal', function (e) {
        $('#taxcode_result').hide();
        $('#edit_taxcode_result').hide();
        $('#tesco_material_result').hide();
        $('#edit_tesco_material_result').hide();
        $('#tesco_error_report_email_result').hide();
        $('#edit_tesco_error_report_email_result').hide();
       })

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
           
           /* call makeMaterialEnabled function from tesco-settings.js*/
           makeMaterialEnabled(id,csrf_token)
       });

       /** To disable Material  on click **/
       $(document).on('click', '#make_material_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           
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
           
           /* call makeErrorReportEmailEnabled function from tesco-settings.js*/
           makeErrorReportEmailEnabled(id,csrf_token)
       });

       /** To disable Tesco error report email  on click **/
       $(document).on('click', '#make_error_report_email_disabled', function(e) {
           var id = $(this).closest('tr').attr('id');
           
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
	});
    </script>
  
    @endsection