@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('navbar')
<style>
    .navbar-nav > li > a {
    line-height: 12px !important;
    }
 /*   .inv-details-modal {
      width: 90%;
      height: 100%;
      margin-left: 60px;*/
      /*padding: 0;*/
 /*     margin-top: 40px;
    }

    .inv-content {
      height: auto;
      min-height: auto;
      border-radius: 6px;
    }*/
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
                @php $i=0; @endphp
                @foreach($invoices as $vals)
                    <tr id="{{$vals->id}}">
                        <td>{{++$i}}</td>
                        <td class="inv-id">{{$vals->invoice_id}}</td>
                        <td>{{$vals->erp_id}}</td>
                        <td>{{$vals->company_name}}</td>
                        <td>{{$vals->po_no}}</td>
                        @if($vals->invoice_file=="" || $vals->invoice_file=="null")
                          <td align="center"><a href="javascript:void(0)" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil filterbtn options edit-user fa-button" title="Link E-way No" data-placement="left"></i></a></td>
                        @else
                            <td align="center"><a class="pi_generation" target="_blank" href="javascript:void(0)" data-id="{{$vals->invoice_file}}"  onclick="showInvoice(this)"><i class="fa fa-print action-button-view" data-placement="top" title="View & Print" data-original-title="view Details"></i></a></td>
                        @endif
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
 <div class="modal fade bd-example-modal-lg" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog inv-details-modal modal-lg" role="document">
    <div class="modal-content inv-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
          <div class="pull-right invoice_actions">
         <!--  {!! Form::open(['id' => 'edit_invoice_form', 'data-toggle' => 'validator', 'role' => 'form']) !!}
              {!! Form::button('View as PDF', ['class' => 'btn btn-default btn-xs downloadIn','id'=> 'downloadIn','data-dismiss' => 'modal'] ) !!}
              {!! Form::button('Save & Send Invoice', ['class' => 'btn btn-primary btn-xs update-invoice'] ) !!}
              {!! Form::label('edit_error_label', 'Select Templates *', ['class' => 'form_label hidden edit_error_label', 'style' => 'color:red;']) !!}          
              {!! Form::button('Close', ['class' => 'btn btn-default btn-xs','data-dismiss' => 'modal'] ) !!} -->
          </div>
          <h4 class="modal-title" id="editModalLabel">Link E-Way No.</h4>
      </div>
      <input type="hidden" name="hiddenid" id="hiddenId">

      <div class="modal-body" id="invoice">        
         <div class="container-fluid inv_details">
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
                <div class="col-lg-4 col-md-4 col-sm-4 pull-right" id="ship_to" style="padding-left: 50px; margin-top: 35px;">
                    <strong>Date</strong>
                    <span style="padding-left: 37px;"> : <span style="padding-left: 6px;" class="inv_date" id="inv_date"></span>
                    </span><br>
                    <strong>Invoice No </strong>
                    <span style="padding-left: 3px;"> : </span>
                    <span style="padding-left: 4px;" class="inv_no" id="inv_no" data-id=""></span>
                    <p><strong>Disp thru</strong>          
                    <span style="padding-left: 10px;"> : </span>
                    <span style="padding-left: 4px;" class="disp_thru" id="disp_thru"></span></p>  
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5">
                </div>
            </div>       
        <hr />
        <strong>ORDER DETAILS :</strong>
        <div class="order_details" id="order_details">

        </div>
        <div class="row">
          <div class="col-md-4">
             
          </div>
          <div class="col-md-4">
                      
          </div>
          <div class="col-md-4 inv-tax">

          </div>
        </div>
        <div class="row">
           <div class="col-md-4">
              <div>
                <label>Credit Terms</label><span style="padding-left: 8px;">:</span><span class="credit-val" id="credit-val" style="padding-left: 20px;">Data</span>
              </div>
              <div class="form-group has-feedback">
                  {!! Form::label('eway_no', 'E-Way Bill No.', ['class' => 'form_label']) !!}
                  {!! Form::text('eway_no',$value = '', array('class' => 'form-control input-sm','required')) !!}
              </div>
              <div>
                 <strong>AMOUNT IN WORDS</strong>
                  <p class="amnt-in-words"></p> 
              </div>
              <div>
                  <label>Delivery At</label><span style="padding-left: 8px;">:</span>
                  <span class="delivery-at" style="padding-left: 20px;"></span>
              </div>
          </div>
          <div class="col-md-4">
                   
          </div>
          <div class="col-md-4 inv-extra-charges">

          </div>
        </div>

        <div class="row">
           <div class="col-md-4">
         
          </div>
          <div class="col-md-4">
                   
          </div>
          <div class="col-md-4">
              <span style="padding-left: 10px;">For Jupiter Garments Accessories</span>
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
      <div class="modal-footer invoice_actions">
     <!--    {!! Form::button('Save & Send Invoice', ['class' => 'btn btn-primary btn-xs update-invoice',] ) !!}
        {!! Form::label('edit_error_label', 'Select Templates *', ['class' => 'form_label hidden edit_error_label', 'style' => 'color:red;']) !!}          
        {!! Form::button('Close', ['class' => 'btn btn-default btn-xs','data-dismiss' => 'modal'] ) !!} -->
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
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
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


function showInvoice(obj){
 var invoice_link = base_url+$(obj).attr('data-id')
 $('.pi_generation').attr("href",invoice_link);
}
  /* Get User Details */
  $(function() {
    $(document).on("click", ".edit-user", function() {
      $('#editModal').modal('show');
      var id = $(this).closest('tr').attr('id')
      var inv_id = $(this).closest('tr').find('.inv-id').text()
     
      $.ajax({
        url: base_url + "/erp/get-invoice",
        type: "get",
        data: {
          id: id
        },
        beforeSend: function() {
          $(".loader").show();
        },
        success: function(data) {
          var tr = '';
          var totalAmnt = 0;

          $('#inv_no').text(data.inv_details[0].invoice_id)
          $('#inv_no').attr('data-id',id);
          $('#customer_name').val(data.inv_details[0].company_name)
          $('#inv_date').text(data.inv_details[0].inv_date)
          $('#company-logo').attr('src',data.company_details.logo);
          $('#disp_thru').text(data.inv_details[0].delvry_thru);
          $('#credit-val').text(data.inv_details[0].credit_limit);
          $('#company_details').html('<h4>'+data.company_details.name+'</h4>'+data.company_details.address+'<p>PH : '+data.company_details.office_phone+', '+data.company_details.phone+'</br> <strong>GSTIN : '+data.company_details.gstin+'</strong></p>')

          $('#bill_to').html('<p>Buyer</p><h4>'+data.inv_details[0].company_name+'</h4><p>'+data.bill_to.address+'</br> GSTIN :'+data.inv_details[0].gstin_number+ '</p>')
          var itemName ="";
          $(data.inv_details).each(function(index, value)
          {   
              var size = (value.size == "null")?'-':value.size ;
              
              // tr += '<tr><td>'+value.erp_id+'</td><td>'+value.buyer_name+'</td><td>'+value.order_no+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_no+'</td><td>'+value.reference_no+'</td><td>'+value.size+'</td><td>'+value.tax+'</td><td>'+value.delivered_qnty+'</td><td>'+value.rate+'</td><td>'+value.delivered_qnty * value.rate+'</td></tr>';

              if (itemName==value.item_name) {
                    tr += '<tr class="trClass" id="'+value.id+'">';
                    tr += '<td>'+value.erp_id+'</td><td>'+value.buyer_name+'</td><td>'+value.order_no+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_no+'</td><td>'+value.custom_ref_name+'</td><td>'+size+'</td><td>'+value.tax+'</td><td>'+value.delivered_qnty+'</td><td>'+value.rate+'</td><td>'+(value.delivered_qnty * value.rate).toFixed(2)+'</td>';
                    tr += '</tr>';
                }
                else{
                    var hsnCode = value.hsn_code==null?'-':value.hsn_code;
                    tr += '<tr><td></td><td colspan="13"><strong style="float: left;">ITEM: '+value.item_name+'  HSNCode: '+hsnCode+'</strong></td></tr>';
                    tr += '<td>'+value.erp_id+'</td><td>'+value.buyer_name+'</td><td>'+value.order_no+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_no+'</td><td>'+value.custom_ref_name+'</td><td>'+size+'</td><td>'+value.tax+'</td><td>'+value.delivered_qnty+'</td><td>'+value.rate+'</td><td style="border-width:1px !important;">'+(value.delivered_qnty * value.rate).toFixed(2)+'</td>';
                    tr += '</tr>';
                } 
                totalAmnt+= value.delivered_qnty * value.rate;
          });

          tr+= '<tr><td colspan="11"><strong>Total</strong></td><td style="border-width:1px !important;"><strong>'+totalAmnt.toFixed(2)+'</strong></td></tr>';
          $('#order_details').html('<table class="table table-bordered table-condensed preview-inv-details" id="preview-inv-details" style="width:100%"><thead><tr><th>ERP ID</th><th>Buyer</th><th>Order No</th><th>PO No</th><th>Style</th><th>OCN</th><th>Ref</th><th>Size</th><th>Tax (%)</th><th>Quantity</th><th>Rate (₹)</th><th style="border-width:1px !important;">Amount (₹)</th></tr></thead><tbody>'+tr+'</tbody></table>');

          var tax_amnt = taxDivision(data.inv_details)
          var tax_tr = "";  
          var place_of_supply = data.inv_details[0].place_of_supply     
          var tax_table = $('.inv-tax').html('<table class="table text-center table-bordered orders_tax" style="margin-top: -23px;;"><thead><tr><th>Tax</th><th style="padding-left: 2px;">Base value (₹)</th><th>Amount (₹)</th></tr></thead><tbody class="tax-tabl"></tbody></table>')
          Object.keys(tax_amnt).forEach(function(key) {
            if (place_of_supply==1){
                  var trClass = tax_amnt[key]!=0?"":"hidden"
                  tax_tr = '<tr class="'+trClass+'"><td>CGST('+(key/2)+'%)</td><td class="cgst_'+key+'">'+tax_amnt[key]+'</td><td>'+(((key/2)/100)*tax_amnt[key]).toFixed(2)+'</td></tr><tr class="'+trClass+'"><td>SGST('+(key/2)+'%)</td><td class="sgst_'+key+'">'+tax_amnt[key]+'</td><td>'+ (((key/2)/100)*tax_amnt[key]).toFixed(2)+'</td></tr>'
              }
              else if (place_of_supply==2){
                  var trClass = tax_amnt[key]!=0?"":"hidden"
                  // if (tax_amnt[key]!=0) {                
                      tax_tr = '<tr class="'+trClass+'"><td>IGST('+key+'%)</td><td class="igst_'+key+'">'+tax_amnt[key]+'</td><td>'+((key/100)*tax_amnt[key]).toFixed(2)+'</td></tr>'
              }
              else{
                  tax_tr = '<tr><td>Taxes('+key+'%)</td><td>'+tax_amnt[key]+'</td><td>'+ ((key/100)*tax_amnt[key] ).toFixed(2)+'</td></tr>'
              }

              $('.tax-tabl').append(tax_tr);
              
          });
          $tT = $.isNumeric(taxSubTotal())?taxSubTotal().toFixed(2):0;
              $('.tax-tabl').append('<tr><td colspan="2"><strong>SubTotal</strong></td><td class="tax-total"><strong>'+ $tT+'</strong></td></tr><tr></tr>')
          var taxTr = '';
          var exAmnt = 0 ;
          $(data.extra_charges).each(function(index, value)
          {
              taxTr += '<tr><td colspan="2">'+value.ext_chrg_name  +'</td><td>'+parseFloat(value.amount).toFixed(2)+'</td></tr>';
              exAmnt+=parseFloat(value.amount);
          });

          var roundOff = data.inv_details[0].round_off_amnt ==null?0:data.inv_details[0].round_off_amnt;
          var rndClass = roundOff==0?"hidden":''
          $('.inv-extra-charges').html('<table class="table table-bordered text-center inv-extra-charges" style="margin-top: -22px;"><thead><tr><th colspan="2">Description</th><th>Amount (₹)</th></tr></thead><tbody>'+taxTr+'<tr><td colspan="2"><strong>SubTotal</strong></td><td><strong class="preview-sub-extra">'+parseFloat(exAmnt).toFixed(2)+'</strong></td></tr><tr class="'+rndClass+'"><td colspan="2"><strong>Round Off</strong></td><td><strong id="preview-round-off">'+parseFloat(roundOff).toFixed(2)+'</strong></td></tr><tr></tr><tr><td colspan="2" style="border-width: 1px !important;"><strong>Grand Total</strong></td><td class="grand-total" style="border-width: 1px !important;"><strong>'+parseFloat(data.inv_details[0].grand_total).toFixed(2)+'</strong></td></tr><tr></tr></tbody></table>')
          $('.amnt-in-words').text(RsPaise($('.grand-total').text()))

          $('.delivery-at').html('<p>'+data.inv_details[0].address+'</p><p>'+data.inv_details[0].email+'</p><p>'+data.inv_details[0].pincode+'</p><p>'+data.inv_details[0].telephone+'</p>')
          $('.invoice_actions').html('<button data-dismiss="modal" class=" btn btn-default btn-xs">Close</button><button class="btn btn-primary btn-xs" onclick="editInvoice()">Save & Send</button><button class="btn btn-default btn-xs downloadIn" id="downloadIn" onclick="downloadInvoice()">View as PDF</button>')
        },
       
        complete: function() {
          $(".loader").hide();
          $('#editModal').modal('show');
        }
      });
    });
  });

    function taxDivision(orderData){
        var taxIndexes = [] ;
        var tax_amnt  = {};
        var taxlist = orderData.map(a => a.tax);
        $.each(taxlist, function(i, el){
            if($.inArray(el, uniqueTax) === -1) 
             uniqueTax.push(el);
        }); 
        $(uniqueTax).each(function(key, vals){
            var sub_amnt = 0;
            taxIndexes[vals] = $.map(orderData, function(obj, index) {
                if(obj.tax == vals) {
                    sub_amnt += obj.delivered_qnty*obj.rate;
                }
            }) 
            tax_amnt[vals] = sub_amnt;                                     
        });
        return tax_amnt;
    }
    function taxSubTotal(){
        var taxamnt = [];
        $('.orders_tax tr').each(function(){
            $('td', this).each(function(index, val){
                if(!taxamnt[index]) taxamnt[index] = 0;
                taxamnt[index] += ($(val).text()==0)?0:parseFloat($(val).text());
            });
        });
        return taxamnt[2] ;
    }
    // $('#downloadIn').on('click',function(){
    //   downloadInvoice()
    // });

     $('#edit_invoice_form').validator().on('click', function (e) {
      if (e.isDefaultPrevented())
      {

      }
      else
      {
         e.preventDefault();
         /* call editCustomer function from customer.js*/
         editInvoice();
      }
    })

     function editInvoice(){
        var eway_no = $('#eway_no').val();
        var inv_id = $('#inv_no').data('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var amntInwords = $('.amnt-in-words').text()
        if (eway_no=="") {
            $('.snackbar_span').html("Please link E-Way Bill No");
            showSnackbar();
        }else{
             $.ajax({
                url: base_url + "/erp/update-invoice",
                type: "post",
                data: {
                    _token: csrf_token,
                    inv_id: inv_id,
                    eway_no:eway_no,
                    amntInwords:amntInwords
                },
                beforeSend: function() {
                    // location.reload();
                },
                statusCode: {
                      200: function(data) {
                          var resp = JSON.stringify(data);
                          console.log( base_url+data)
                          $('.snackbar_span').html("Successfully send Invoice to the Customer !");
                            showSnackbar();
                        setTimeout(function(){window.location = 'invoice';}, 1000);
                      },
                      400: function() {
                          $('.snackbar_span').html("Parameter is missing !!!");
                          showSnackbar();
                      },
                 },
                // success: function(data) {
                 
                //   $('.snackbar_span').html("Successfully send Invoice to the Customer !");
                //   showSnackbar();
                 
                //    console.log(base_url+data)
                //   window.open(
                //   base_url+data,
                //   '_blank' // <- This is what makes it open in a new window.
                //   );

                //   // setTimeout(function(){window.location = 'invoice';}, 1000); 
                // },
                error: function(data) {

                },
                complete: function() {

                }
            })
        }
           
     }


    function downloadInvoice(){
      var eway_no = $('#eway_no').val();
        var inv_id = $('#inv_no').data('id');
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var amntInwords = $('.amnt-in-words').text()
        if (eway_no=="") {
            $('.snackbar_span').html("Please link E-Way Bill No");
            showSnackbar();
        }else{
             $.ajax({
                url: base_url + "/erp/download-invoice",
                type: "post",
                data: {
                    _token: csrf_token,
                    inv_id: inv_id,
                    eway_no:eway_no,
                    amntInwords:amntInwords
                },
                beforeSend: function() {
                    // location.reload();
                },
                statusCode: {
                      200: function(data) {
                          var resp = JSON.stringify(data);
                          console.log( base_url+data)
                          $('.snackbar_span').html("Successfully send Invoice to the Customer !");
                            showSnackbar();
                        window.open(
                           base_url+$.trim(data),
                          '_blank' // <- This is what makes it open in a new window.
                        );
                        
                      },
                      400: function() {
                          $('.snackbar_span').html("Parameter is missing !!!");
                          showSnackbar();
                      },
                 },
                // success: function(data) {
                 
                //   $('.snackbar_span').html("Successfully send Invoice to the Customer !");
                //   showSnackbar();
                 
                //    console.log(base_url+data)
                //   window.open(
                //   base_url+data,
                //   '_blank' // <- This is what makes it open in a new window.
                //   );

                //   // setTimeout(function(){window.location = 'invoice';}, 1000); 
                // },
                error: function(data) {

                },
                complete: function() {

                }
            })
        }
    }

</script>
 @endsection