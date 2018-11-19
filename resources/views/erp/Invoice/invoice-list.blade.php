@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')
<style>
    .navbar-nav > li > a {
    line-height: 12px !important;
    }
</style>
<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
<li><a href="{{ url('/erp/invoice') }}" class="active">Invoice List</a></li>
    <li><a href="{{ url('/erp/create-invoice') }}" class="">Create Invoice</a></li>
</ul>

@endsection
@section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="main-sub-menu">
        <div class="company-nav-div hidden">
            <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Company</a>
        </div>
      </div>
     <div class="clearfix"></div>
    <div class="main-content">


        <div class="loader">
            <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
        </div>
        <div class="col" id="customer-table">
            <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
            <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
            <table id="invoice-list-table" class="table table-hover table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Sl. No</th>
                        <th>Invoice ID</th>
                        <th>ERP ID</th>
                        <th>Customer name</th>
                        <th>PO Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                {{$i=0}}
                @foreach($invoices as $vals)
                    <tr id="{{$vals->id}}">
                        <td>{{++$i}}</td>
                        <td>{{$vals->invoice_id}}</td>
                        <td>{{$vals->erp_id}}</td>
                        <td>{{$vals->company_name}}</td>
                        <td>{{$vals->po_no}}</td>
                        <td align="center"><a class="pi_generation" href="{{$vals->invoice_file}}" data-id="{{$vals->invoice_file}}"><i class="fa fa-eye action-button-view" data-placement="top" title="" data-original-title="view Details"></i></a></td>
                    </tr>
                @endforeach    
                </tbody>
            </table>
        </div>

    </div>
</div>
    <!-- company selection Modal -->
<div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
     <div class="modal-dialog buyer-selection-modal-dialog" role="document">
         <div class="modal-content buyer-selection-modal-content">
                <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Company</h4>
                 </div>
                 <div class="modal-body buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                      <strong>Warning!</strong> Select a Company to proceed.
                    </div>
                    @foreach($companies as $company)
                     <a class="buyer-card-link" style="cursor: pointer;">
                         <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$company->name}}"  data-value ="{{$company->company}}" id="{{ strtolower( str_replace(' ','',$company->name)) }}">
                          {{ $company->name }}
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
{{-- Show snackbar html code --}}
<div id="snackbar"><span class="snackbar_span"></span></div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/erp') }}";
</script>
@endif 
@section('jqueryIncludes')
<script src="{{ asset('js/Erp/invoice.js') }}"></script>
<script>  
$(document).ready(function(){    
    var table = $('#invoice-list-table').DataTable();
/** [ Make companies card active ] */
    $('div.buyers-card').click(function(){
    
      $('div.buyers-card').removeClass("active");
      $(this).addClass("active");
      $('#hidden-buyer-div').hide();

      /* Save selected buyer into localdb */
      if ($('.buyers-card.active-buyer.active').length != 0) 
      {
          var company_id = $('.buyers-card.active-buyer.active').data("value"); 
          var company_name = $('.buyers-card.active-buyer.active').attr("id");
          $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
          $('#buyerSelectionModalCenter').modal('show');
          
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/erp/po-manager/set_company_session",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    company_id: company_id,
                    company_name:company_name
                },
                beforeSend: function() {
                    location.reload();

                },
                success: function(data) {
                },
                error: function(data) {

                },
                complete: function() {

                }
            })
      }
      
    });
    
    /** [ Show already selected(active) buyer on clicking buyer menu] */
    $('.company-nav-menu').on('click', function() {

        $("#buyerSelectionModalCenter").modal("show");
    });

    if($('#company_name').val() != null && $('#company_name').val() != "")
    {
        $('#'+$('#company_name').val()).addClass('active');
    }
    else
    {
        $('div.buyers-card').first().addClass("active");
    }
   
    $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
    
    $('.company-nav-menu').html($('.company-nav-div').html());
})
</script>
 @endsection