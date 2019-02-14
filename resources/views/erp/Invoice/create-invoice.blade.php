@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')
<style>
    .navbar-nav > li > a {
    line-height: 12px !important;
    }
  /*  .invoice-modal{
        width: 1100px;
    }*/
    .nopadding {
       padding: 0 !important;
       margin: 0 !important;
    }
    .table-fixed {
      width: 100%;
      /*background-color: #f3f3f3;*/
    }
    .table-fixed tbody {
      height: 130px;
      overflow-y: auto;
      width: 100%;
    }
    .table-fixed thead, .table-fixed tbody {
      display: block;
    }

    table.table-bordered th:last-child, table.table-bordered td:last-child {
        border-right-width: 1px !important; 
    }

    .delivery-at-link a:hover {
      background-color: blue !important;
    }

    /* Style the active class (and buttons on mouse-over) */
/*    .delivery-card.active {
      border: solid;
      border-color: #273339;
    }*/
/*    .delivery-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
        transition: transform 0.7s;
        }
    .delivery-card.active:hover {
        transform: scale(1.00);
        transition: transform 0.7s;
        
    }*/
   
</style>
<ul class="nav navbar-nav dynamic-menu erp-dynamic-menu">
<li><a href="{{ url('/erp/invoice') }}" class="">Invoice List</a></li>
<li><a href="{{ url('/erp/create-invoice') }}" class="active">Create Invoice</a></li>
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
     <input type="hidden" value="{{ session()->get('company') }}" id="company_id">
     <input type="hidden" value="{{ session()->get('company_name') }}" id="company_name">
        <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
        <div class="col-md-12">
            <!-- <div class="card"> -->
               <form id="invoice_form">
               <h4 class="text-center">Invoice Details</h4>
                <div class="col-xs-2">
                    <label for="invoice-no" style="padding-top: 12px;margin-left: -12px;">No</label>
                </div>
                <div class="col-xs-2">
                    <input type="text" class="form-control" name="invoice-no" id="invoice-no" style="margin-left: -145px;width: 91px;" value="{{$invID}}">
                </div>
                <div class="row">
                    <div class="col-xs-2">
                    <label for="invoice-date" style="padding-top: 8px;margin-left: -240px;">Date</label>
                   
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control invoice-date" id="invoice-date" name="invoice-date" style="margin-left: -412px;width: 136px;;" name="invoice-date" id="invoice-date" value ="{{ now('Asia/Kolkata')->format('d-m-Y') }}" readonly="">
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-2">
                    <label for="customer" style="padding-top: 12px;">Customer</label>     
                </div>
                <div class="col-xs-3" style="margin-left:-135px;padding-top: 5px;">
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" id="customer" name="customer" required="" onchange="dynamicTable()">
                        <option value="">Select Customers</option> 
                        @foreach($customers as $vals)
                            <option title="{{$vals->company_name}}" value="{{$vals->id}}">{{$vals->company_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="row po-list hidden">
                <div class="col-xs-2">
                    <label for="erp-orders" style="padding-top: 12px;">Orders</label>
                    
                </div>
                <!-- <div class="panel"> -->
                <div class="dynamic-table pull-left" style="margin-left:-120px; width:285px;margin-top: 6px;border-radius: 5px;">
                </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="order-heading hidden"> 
                        <u><strong style="margin-left: 16px;">ORDER DETAILS :</strong></u>
                    </div>                     
                    
                    <div class="table-responsive order-details hidden">
                    
                    </div> 
                    <div class="col-md-7 remarks hidden">
                        <label>Delivery Address</label>
                        <div class="row delivery-address">
                       <!--  <div class="col-md-6">
                           
                        </div> -->
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="orders-tax">
                            
                        </div>
                        <div class="extra-charges">
                            
                        </div>
                    </div>
                </div>                    
            </form>
        </div>
        
    </div>
</div>
<div class="modal fade" id="erp-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg invoice-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div style="margin-left: 5px" class="invoice_actions pull-right">

                    </div>
                    <h5><strong>Invoice for <span id="erp_id"></span></strong></h5>

                </div>
                <div class="modal-body" id="invoice">
                  <div class="container-fluid ">
                  <div class="row">
                            <div class="col-md-12">
                               <div class="row text-center color-invoice">
                                    <h4><strong><u>Invoice</u></strong></h4>
                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4" id="company_details">

                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4 pull-right" style="padding-left: 50px;">
                                <img id="company-logo" style="height: 62px;margin-top: 34px;" src="" class="img-responsive">

                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5">

                            </div>
                        </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4" id="bill_to">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 50px; margin-top:6px;">
                                    <span><p><strong>TAX INVOICE </strong></p></span>
                                    <strong>Date</strong>
                                    <span style="padding-left: 37px;"> : <span style="padding-left: 6px;" class="invoice-date"></span>
                                    </span><br>
                                    <strong>Invoice No </strong>
                                    <span style="padding-left: 2px;"> : </span>
                                    <span style="padding-left: 4px;" class="invoice_number"></span>
                                    <p><strong>Disp thru</strong>          
                                    <span style="padding-left: 10px;"> : </span>
                                    <span style="padding-left: 4px;" class="disp_thru" id="disp_thru"></span></p>  

                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5">

                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <strong style="margin-left: 14px;">ORDER DETAILS :</strong>
                                <div class="col-md-12" id="item_details">
                                    
                                </div>
                            </div>

                            <div class="row">
                                 <div class="col-md-12">
                                    <div class="col-md-4 nopadding remarks hidden">
                                        <label>Credit Terms</label><span style="padding-left: 8px;">:</span><span class="credit-val" id="credit-val" style="padding-left: 20px;">Data</span>
                                        </br>
                                        <label>E-Way Bill No</label><span style="padding-left: 6px;">:</span><span class="eway-val" style="padding-left: 20px;"> - </span>
                                        </br>
                                        
                                    </div>
                                    <div class="col-md-3 nopadding">
                                    </div>
                                    <div class="col-md-5 nopadding">
                                        <div class="preview-orders-tax"></div>
                                        <div class="preview-extra-charges"></div>
                                    </div>
                                    <div>
                                        <strong>AMOUNT IN WORDS</strong>
                                        <p class="amnt-in-words"></p>
                                    </div>
                                    <div>
                                        <!-- <strong>Delivery At</strong>
                                        <p class="delivery-at"></p> -->
                                        <label>Delivery At</label><span style="padding-left: 8px;">:</span><span class="delivery-details" style="padding-left: 20px;"></span>
                                    </div>
                                    <div class="pull-right" style="padding-right: 55px;">
                                        <span>For Jupiter Garments Accessories</span>
                                    </div>
                                 </div>
                            </div>
                            </br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3 nopadding">
                                     <span>Recievers Signature</span>
                                    </div>
                                    <div class="col-md-3 nopadding">
                                        <span>Prepared By</span>
                                    </div>
                                    <div class="col-md-3 nopadding">
                                        <span>Verified By</span>
                                    </div>
                                    <div class="col-md-3 nopadding">
                                        <span>Authorized Signatory</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    
                </div>
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
                          <img src="{{$company->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
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
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('js/Erp/invoice.js') }}"></script>

<script>    
$(document).ready(function(){
$('body').on('focus',".invoice-date", function(){
    
    $(this).datepicker({
        autoclose: true,
        format:'dd-mm-yyyy'
    });
});

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