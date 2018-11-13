    var place_of_supply = 0;
    var order_data = '';
    var uniqueTax = [];
    var tax_amnt = {};
    var sub_amnt = 0;
    var result = '';
    var ship_to = '';
    var bill_to = '';
    var company_details = '';

    function dynamicTable() {
        var customer_id  = $("#customer option:selected").val();
        var html = "";
        $.ajax({
            url: "/erp/erp-po-list",
            type: 'GET',
            data: {
                customer_id:customer_id
            },
            dataType: "json",

            success: function (data) {
                if(data.po_list.length != 0)
                {
                    $(data.po_list).each(function(index, value)
                    {
                        html += '<tr><td><input name="po_select" value="'+value.id +'" id="'+value.id +'" class="po-select" type="checkbox" /></td><td>'+value.erp_id +'</td><td>'+value.po_no+'</td><td>'+value.buyer_name+'</td></tr>';
                    });
                    $('.dynamic-table').html('<form id="po-list-form"><table class="table"><thead><tr><th></th><th class="text-center">ERP ID</th><th class="text-center">PO Number</th><th class="text-center">Buyer</th></tr></thead><tbody>'+html+'</tbody></table></form>');
                    $('.po-list').removeClass('hidden')
                    $('.dynamic-table tbody').on('change', 'input[type="checkbox"]', function(){
                    $('.po-select').not(this).prop('checked', false);  
                        showOrderList(this)                           
                    });
                }
                else{
                    $('.snackbar_span').html("No Orders found for this Customer !");
                    showSnackbar();    
                    $('.po-list').addClass('hidden')
                    $('.order-heading').addClass('hidden')
                    $('.order-details').addClass('hidden')
                    $('.remarks').addClass('hidden')
                    $('.create-invoice').addClass('hidden')
                    
                    
                }                
            },
            error: function (errorXHR, errorStatus, errorThrown) {
               
            },
            complete: function () {
                
            }
        })	    
	}
    function showOrderList(obj) {
        var erp_id = $(obj).val()
        var table  = $(obj).attr('data-id')
        $.ajax({
            url: "/erp/erp-order-list",
            type: 'GET',
            data: {
                erp_id:erp_id
            },
            tryCount: 0,
            retryLimit: 2,
            dataType: "json",

            success: function (data) {

                if(data.orderlist.length != 0)
                {                       
                    order_data = data.orderlist;
                    ship_to =  data.ship_to;
                    bill_to = data.bill_to;
                    company_details = data.company_details;
                    createInvoice(order_data,order_data)                                       
                }
                else{
                    $('.order-details').addClass('hidden')
                    $('.remarks').addClass('hidden')
                    $('.extra-charges').addClass('hidden')
                    $('.orders-tax').addClass('hidden')
                }
            },
            error: function (errorXHR, errorStatus, errorThrown) {
               
            },
            complete: function () {
            
            }
        })
    }
    function createInvoice(orderData,taxData){
        
        $('.order-details').html('');
        var tr = "";
        var qnty = 0;
        var pendQnty = 0;
        var amnt = 0;
        var tax_tr = 0;
        var totalcgst = 0;
        var totalsgst = 0;
        var totaligst = 0;
        var totaltax = 0;

        taxDivision(taxData)
        
        place_of_supply = orderData[0].place_of_supply;                 
        $(orderData).each(function(index, value)
        {            
            var size = (value.size == null)?'-':value.size ; 
            tr += '<tr class="trClass" id="'+value.id+'">';
            tr += '<td><input name="order_select" value="'+value.id +'" id="'+value.id +'" class="order-select" type="checkbox" checked/></td><td>'+value.id+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_number+'</td><td>'+value.id+'</td><td>'+value.reference_name+'</td><td>'+size+'</td><td>'+value.hsn_code+'</td><td class="tax-td">'+value.tax+'</td><td class="qnty">'+value.order_quantity+'</td><td class="pending-qnty">'+value.pending_qnty+'</td><td style="width: 116px;"><input type="text" value="" class="form-control delivery-qnty" required data-id="'+value.id+'" min="1" onkeyup="updateAmount.call(this,'+uniqueTax+')"></td><td class="rate">'+value.rate+'</td><td class="amnt">'+value.pending_qnty * value.rate+'</td>';
            tr += '</tr>';
            qnty += value.order_quantity;
            pendQnty += value.pending_qnty;
            amnt += (value.pending_qnty*value.rate);

        });

        tr+= '<tr><td colspan="10"><strong>Total</strong></td><td class="qnty-sum">'+qnty+'</td><td class="balnc-qnty" id="balnc-qnty">'+pendQnty+'</td><td class="total-qnty" id="total-qnty">'+quantityTotal()+'</td><td></td><td class="total-amnt">'+amnt+'</td></tr>';


        $('.order-details').html('<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table text-center table-bordered order-details" id="order-details"><thead><tr><th></th><th>Order No</th><th>PO No</th><th>Style</th><th>OCN No</th><th>Item</th><th>Reference</th><th>Size</th><th>HSN No</th><th>Tax (%)</th><th>Order Quantity</th><th>Pending Quantity</th><th>Delivered Quantity</th><th>Rate (₹)</th><th>Amount (₹)</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12"><tbody>'+tr+'</tbody></table></div></div>');

        updateTaxAmount(tax_amnt)

        var gndtotal = parseFloat($('.tax-total').text())+ parseFloat($('.total-amnt').text());
        $('.extra-charges').html('<table class="table table-borderedtext-center extra-charges" style="margin-top: -22px;"><thead><tr><th colspan="2">Description</th><th>Amount (₹)</th></tr></thead><tbody><tr><td colspan="2"><div style="width: 65%"><select class="charges" id="charges1" name="charges1"><option>Freight</option><option>Minimum charges</option><option>Job Work Charges</option></select></div></td><td style="width: 116px;"><input type="text" value="" min="0" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><div style="width: 65%"><select  id="charges2" name="charges2" class="charges"><option value="1">Freight</option><option value="2">Minimum charges</option><option value="3">Job Work Charges</option></select></div></td><td style="width: 116px;"><input type="text" min="0" value="" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><div style="width: 65%"><select class="charges" id="charges3" name="charges3" ><option value="1">Freight</option><option value="2">Minimum charges</option><option value="3">Job Work Charges</option></select></div></td><td style="width: 116px;"><input type="text" min="0" value="" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><strong>SubTotal</strong></td><td class="sub-extra">0</td></tr><tr></tr><tr><td colspan="2"><strong>Grand Total</strong></td><td class="grand-total">'+gndtotal.toFixed(3)+'</td></tr><tr></tr></tbody></table><div class="create-invoice"><button type="button" class="btn btn-primary create-invoice pull-right" style="margin-top: -10px;" id="create-invoice">Create Invoice</button></div>')

            $('#create-invoice').on('click',function(){
                previewInvoice(orderData)
            })
        $('.order-details tbody').on('change', 'input[type="checkbox"]', function(){
            updateInvoice(this)                           
        });

        var checkboxes = $('.dynamic-table td input[type="checkbox"]');
        $(document).find('.charges').selectpicker();                  

        if(checkboxes.filter(':checked').length >0){
            $('.order-heading').removeClass('hidden')
            $('.order-details').removeClass('hidden')
            $('.remarks').removeClass('hidden')
            $('.create-invoice').removeClass('hidden')
            $('.orders-tax').removeClass('hidden')
            $('.extra-charges').removeClass('hidden')
        }
        else{
            
            $('.order-heading').addClass('hidden')
            $('.order-details').addClass('hidden')
            $('.remarks').addClass('hidden')
            $('.create-invoice').addClass('hidden')
            $('.orders-tax').addClass('hidden')
            $('.extra-charges').addClass('hidden')
        }
    }

    function taxDivision(orderData){
        var taxIndexes = [] ;
        var taxlist = orderData.map(a => a.tax);
        $.each(taxlist, function(i, el){
            if($.inArray(el, uniqueTax) === -1) 
             uniqueTax.push(el);
        }); 
        $(uniqueTax).each(function(key, vals){
            var sub_amnt = 0;
            taxIndexes[vals] = $.map(orderData, function(obj, index) {
                if(obj.tax == vals) {
                    sub_amnt += obj.pending_qnty*obj.rate;
                }
            }) 
            tax_amnt[vals] = sub_amnt;                                     
        });
        return tax_amnt;
    }

    function previewTaxDiv(newData){
        var taxIndexes = [] ;
        var taxlist = newData.map(a => a.tax);
        $.each(taxlist, function(i, el){
            if($.inArray(el, uniqueTax) === -1) 
             uniqueTax.push(el);
        }); 
        $(uniqueTax).each(function(key, vals){
            var sub_amnt = 0;
            taxIndexes[vals] = $.map(newData, function(obj, index) {
                if(obj.tax == vals) {
                    sub_amnt += obj.delvry_qnty*obj.rate;
                }
            }) 
            tax_amnt[vals] = sub_amnt;                                     
        });
        return tax_amnt;
    }

    function updateAmount() {
        // var flg = 0;
        var tax_array= [].slice.call(arguments);
        var amnt  = 0 ;
        var result = [];
        var taxsum = {};
        var arr = [];
        var pendingQnty = 0
        
        if($(this).val() <= parseInt($(this).closest('tr').find('.pending-qnty').text())){
            amnt  = parseInt($(this).val()) * $(this).closest('tr').find('.rate').text();
            $(this).closest('tr').find('.amnt').html(amnt)
            pendingQnty = $(this).closest('tr').find('.pending-qnty').text() - $(this).val()
            $(this).closest('tr').find('.pending-qnty').html(pendingQnty)
        }
        else{
            amnt  = $(this).closest('tr').find('.qnty').text() * $(this).closest('tr').find('.rate').text();
            $(this).closest('tr').find('.amnt').html(amnt)
            $(this).val('')
        } 
        // $(this).closest('tr').find('.order-select').attr("checked", "checked");   

        $('.total-amnt').html(totalAmount())
        $('#total-qnty').html(quantityTotal()) 
        $('.tax-total').html(taxSubTotal()) 
        $('#balnc-qnty').html(balncTotal())
        $(tax_array).each(function(key, vals){
            $('.order-details table tbody tr').each(function(){
                if(parseInt($(this).find($(".tax-td")).text().split(" ")[0]) == vals){           
                    val=taxsum[vals] == undefined ? 0 : taxsum[vals];
                    val+= $(this).find($(".amnt")).text() == "" ? 0 :parseInt($(this).find($(".amnt")).text())
                    taxsum[vals] = val;
                   
                }
            });
        });
        updateTaxAmount(taxsum)
        updateTotal()
    }
    function totalAmount(){
        var result = [];

        $('.order-details tr').each(function(){
            $('td', this).each(function(index, val){
                if(!result[index]) result[index] = 0;
              result[index] += parseFloat($(val).text());
            });
        }); 
        return result[14];
    }
    function balncTotal(){
       var pendingQnty = [];
        $('.order-details tr').each(function(){
            $('td', this).each(function(index, val){
                if(!pendingQnty[index]) pendingQnty[index] = 0;
              pendingQnty[index] += parseFloat($(val).text());
            });
        });
        return pendingQnty[11] ;
    }
    function quantityTotal(){
        
        var sum=0;
        $('.delivery-qnty').each(function(i, obj){
           if($.isNumeric(this.value)) {
            $(this).css('border-color', '');
                sum += parseFloat(this.value);
              }
        })
        return sum;
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
    function previewTaxTotal(){
        var taxamnt = [];
        $('.preview_orders_tax tr').each(function(){
            $('td', this).each(function(index, val){
                if(index == 2){
                if(!taxamnt[index]) taxamnt[index] = 0;
                taxamnt[index] += parseFloat($(val).text());
                // console.log($(val).text())
            }
            });
        });
        return taxamnt[2] ;
    }
    


    function updateTaxAmount(tax_amnt){

        var tax_tr = "";       
        var tax_table = $('.orders-tax').html('<table class="table text-center table-bordered orders_tax" style="margin-top: -23px;;"><thead><tr><th>Tax</th><th>Base value (₹)</th><th>Amount (₹)</th></tr></thead><tbody class="tax-tabl"></tbody></table>')
        Object.keys(tax_amnt).forEach(function(key) {
          if (place_of_supply==1){
                var trClass = tax_amnt[key]!=0?"":"hidden"
                // if (tax_amnt[key]!=0) {
                    tax_tr = '<tr class="'+trClass+'"><td>CGST ('+(key/2)+'% )</td><td class="cgst_'+key+'">'+tax_amnt[key]+'</td><td>'+(((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr><tr class="'+trClass+'"><td>SGST ('+(key/2)+'% )</td><td class="sgst_'+key+'">'+tax_amnt[key]+'</td><td>'+ (((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
                // }
                // else{
                //     tax_tr = "";
                // }
            }
            
            else if (place_of_supply==2){
                var trClass = tax_amnt[key]!=0?"":"hidden"
                // if (tax_amnt[key]!=0) {                
                    tax_tr = '<tr class="'+trClass+'"><td>IGST ('+key+'% )</td><td class="igst_'+key+'">'+tax_amnt[key]+'</td><td>'+((key/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
                // }
                // else{
                //     tax_tr = "";
                // }
            }
            else{
                tax_tr = '<tr><td>Taxes ('+key+'% )</td><td>'+tax_amnt[key]+'</td><td>'+ ((key/100)*tax_amnt[key] ).toFixed(3)+'</td></tr>'
            }
            $('.tax-tabl').append(tax_tr);
        });
        $tT = $.isNumeric(taxSubTotal())?taxSubTotal().toFixed(3):0;
        $('.tax-tabl').append('<tr><td colspan="2"><strong>SubTotal</strong></td><td class="tax-total">'+ $tT+'</td></tr><tr></tr>')
    }

    function updateTotal(){
        var result = 0;
        $('.extra-amnt').each(function(i, obj){
            if($.isNumeric(this.value)) {
                result += (this.value) == "" ? 0 :parseFloat(this.value)
            }
        })
        $('.sub-extra').html(result)
        var grandTotal = parseFloat($('.tax-total').text())+ parseFloat($('.total-amnt').text()) + parseFloat($('.sub-extra').text());
        $('.grand-total').html(grandTotal.toFixed(3))        
    }

    function previewInvoice(data){
        var selected = [];
        var tr = "";
        var qnty = 0;
        var amnt = 0;
        var tax_tr = 0;
        var totalcgst = 0;
        var totalsgst = 0;
        var totaligst = 0;
        var totaltax = 0;
        var pending_qnty = 0;
        var arr = [].concat(data);
        $('.delivery-qnty').css('border-color', '');
        var delvryQnty = {};
        var count = 0 ;
        console.log(company_details)
        $('#company-logo').attr('src',company_details.logo);

        // $('#company_details').html('<strong style="font-size:11px;">'+company_details.name+'</strong><br/>'+company_details.address+'<br/>PH : '+company_details.office_phone+', '+company_details.phone+'<br/>GST : '+company_details.gstin)
        $('#company_details').html('<h4>'+company_details.name+'</h4>'+company_details.address+'<p>PH : '+company_details.office_phone+', '+company_details.phone+'</br> GST : '+company_details.gstin+'</p>')

        $('#bill_to').html('<h4>Billing Address:</h4>'+data[0].company_name+'<p>'+bill_to.address+'</br> GSTN :'+data[0].gstin_number+ '</p>')
        $('#ship_to').html('<h4>Shipping Address:</h4>'+data[0].company_name+'<p>'+ship_to.address+'</p>')

        $('.order-details input[type=checkbox]').each(function () {
            if ($(this).prop("checked") == false) {
                selected.push($(this).attr('id'));           
            }
        });
        count = selected.length;
        $('.delivery-qnty').each(function(i, obj){
            if($.isNumeric(this.value)) {             
               delvryQnty[$(this).data("id")] = parseFloat(this.value);
            }
            else if($(this).closest('tr').find('.order-select').prop("checked") == true){
                $(this).closest('tr').find('.delivery-qnty').focus();
                $(this).closest('tr').find('.delivery-qnty').css('border-color', '#F44336');
                $('.snackbar_span').html("Please provide delivered quantity details");
                showSnackbar();
            }
           
        })
        if (jQuery.isEmptyObject(delvryQnty) || Object.keys(delvryQnty).length<(count)) {
           $('.snackbar_span').html("Please provide delivered quantity details");
           showSnackbar();
        }
        else{
            for(var i=0; i<selected.length;i++){
               var removeIndex = arr.map(function(item) { return item.id; }).indexOf(parseInt(selected[i]));
               var data = arr.splice(removeIndex, 1);
            }
            $('#erp_id').text(arr[0].erp_id)
            $('.invoice-date').text($('#invoice-date').val())
            $('.invoice_number').text($('#invoice-no').val())
            
            
            var pendingQnty = 0  ;
            $(arr).each(function(index, value)
            {            
                var size = (value.size == null)?'-':value.size ;
                var delQnty = 0
                for (var k in delvryQnty) {
                    if (delvryQnty.hasOwnProperty(k)) {
                       if (k==value.id)
                        delQnty = delvryQnty[k]
                    }
                }
                value.delvry_qnty = delQnty;
                pending_qnty = value.pending_qnty - delQnty
                tr += '<tr class="trClass" id="'+value.id+'">';
                tr += '<td>'+value.id+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_number+'</td><td>'+value.id+'</td><td>'+value.reference_name+'</td><td>'+size+'</td><td>'+value.hsn_code+'</td><td class="tax-td">'+value.tax+' </td><td class="qnty">'+value.order_quantity+'</td><td class="pending-qnty">'+pending_qnty+'</td><td style="width: 116px;">'+delQnty+'</td><td class="rate">'+value.rate+'</td><td class="amnt">'+delQnty * value.rate+'</td>';
                tr += '</tr>';

                qnty += value.order_quantity;
                pendingQnty += pending_qnty
                amnt += (delQnty*value.rate);

            });
            tr+= '<tr><td colspan="9"><strong>Total</strong></td><td class="preview-qnty-sum"></td><td class="preview-balnc-qnty" id="preview-balnc-qnty"></td><td class="preview-total-qnty" id="preview-total-qnty"></td><td></td><td class="preview-total-amnt"></td></tr>';
            $('#item_details').html('<table class="table table-bordered table-condensed order-details" id="preview-order-details" style="width:100%"><thead><tr><th>Order No</th><th>PO No</th><th>Style</th><th>OCN</th><th>Item</th><th>Ref</th><th>Size</th><th>HSN</th><th>Tax (%)</th><th>Order Qty</th><th>Pending Qty</th><th>Del Qty</th><th>Rate (₹)</th><th>Amount (₹)</th></tr></thead><tbody>'+tr+'</tbody></table>');
            var tax_tr = "";       
            var tax_table = $('.preview-orders-tax').html('<table class="table table-bordered text-center preview_orders_tax" style="margin-top: -23px;"><thead><tr><th>Tax (%)</th><th>Base value (₹)</th><th>Amount (₹)</th></tr></thead><tbody class="preview-tax-tabl"></tbody></table>')

            $('.preview-total-qnty').text($('.total-qnty').text())
            $('.preview-balnc-qnty').text($('.balnc-qnty').text())
            $('.preview-total-amnt').text($('.total-amnt').text())
            $('.preview-qnty-sum').text($('.qnty-sum').text())
            var taxTotal = 0;

            tax_amnt = previewTaxDiv(arr)
            Object.keys(tax_amnt).forEach(function(key) {
            
                if (place_of_supply==1){
                    if (tax_amnt[key]!=0) {
                        tax_tr = '<tr><td>CGST ('+(key/2)+')</td><td class="cgst_'+key+'">'+tax_amnt[key]+'</td><td>'+(((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr><tr><td>SGST ('+(key/2)+')</td><td class="sgst_'+key+'">'+tax_amnt[key]+'</td><td>'+ (((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'    
                    }
                    else{
                        tax_tr = ""; 
                    }
                }
                else if (place_of_supply==2){
                    if (tax_amnt[key]!=0) {
                      tax_tr = '<tr><td>IGST ('+key+')</td><td class="igst_'+key+'">'+tax_amnt[key]+'</td><td>'+((key/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
                    }
                    else{
                        tax_tr = ""; 
                    }
                }
                else{
                    tax_tr = '<tr><td>Taxes ('+key+')</td><td>'+tax_amnt[key]+'</td><td>'+ ((key/100)*tax_amnt[key] ).toFixed(3)+'</td></tr>'
                }
                $('.preview-tax-tabl').append(tax_tr);
            });
            
            $('.preview-tax-tabl').append('<tr><td colspan="2"><strong>SubTotal</strong></td><td><strong class="preview-tax-total"></strong></td></tr><tr></tr>')
            $('.preview-tax-total').text($('.tax-total').text())
           
            var trclass1 = $( "#charges1" ).val()==""?"hidden":'';
            var trclass2 = $( "#charges2" ).val()==""?"hidden":'';
            var trclass3 = $( "#charges3" ).val()==""?"hidden":'';
            
            var tableHide = trclass1 && trclass2 && trclass3 =="hidden" ?"hidden":''
            
            $('.preview-extra-charges').html('<table class="table table-bordered text-center preview-extra-charges style="margin-top: -22px;;"><thead class="'+tableHide+'""><tr><th colspan="2">Description</th><th>Amount (₹)</th></tr></thead><tbody><tr class="'+trclass1+'"><td colspan="2">'+$( "#charges1" ).val()+'</td><td style="width: 116px;" colspan="2"></td></tr><tr class="'+trclass2+'"><td colspan="2">'+$( "#charges2" ).val()+'</div></td><td style="width: 116px;"></td></tr><tr class="'+trclass3+'"><td colspan="2">'+$( "#charges3" ).val()+'</td><td style="width: 116px;"></td></tr><tr><td colspan="2"><strong>SubTotal</strong></td><td><strong class="preview-sub-extra"></strong></td></tr><tr></tr><tr><td colspan="2"><strong>Grand Total</strong></td><td><strong class="preview-grand-total"></strong></td></tr><tr></tr></tbody></table>')
                

            $('.invoice_actions').html('<button class="btn btn-default btn-xs downloadIn" id="downloadIn" onclick="downloadInvoice()" >View as PDF</button><button class="btn btn-success btn-xs" id="save-invoice">Save & Send</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
            $('#save-invoice').on('click',function(){
                saveInvoice(arr)
            })
            $('#erp-invoice').modal("show")
        }
    }
    function downloadInvoice(){

        var myOffscreenEl = document.getElementById("invoice")
            useWidth      = myOffscreenEl.style.width,
            useHeight     = myOffscreenEl.style.height;
            myOffscreenEl.style.backgroundColor = 'white';
        html2pdf(myOffscreenEl, {
            margin      :  10,
            filename    :  'Invoice.pdf',
            image       :  { type: 'jpeg', quality: 0.98 },
            html2canvas :  { dpi: 300, letterRendering: true },
            jsPDF       :  { unit: 'pt', format: 'a4', orientation: 'portrait' }
        });
       
       // dataPDF.save('storage/app/public/invoices/invoice.pdf', 'F');

    }

    function updateInvoice(obj){
        var val = $(obj).val();
        var i = order_data.length;        
        var arr = [].concat(order_data);
        var selected = [];
        $('.order-details input[type=checkbox]').each(function () {
            var amt =0;
            if ($(this).prop("checked") == false) {
              selected.push($(this).attr('id'));
              $(this).closest('tr').find('.amnt').text(0);
              $(this).closest('tr').find('.delivery-qnty').removeAttr('required');
              $(this).closest('tr').find('.delivery-qnty').css('border-color', '');              
            }
            else{
               amt = $(this).closest('tr').find('.pending-qnty').text()*$(this).closest('tr').find('.rate').text()
                $(this).closest('tr').find('.amnt').text(amt);
                $(this).closest('tr').find('.delivery-qnty').attr('required');
            }
        });
        for(var i=0; i<selected.length;i++){
           var removeIndex = arr.map(function(item) { return item.id; }).indexOf(parseInt(selected[i]));
           var data = arr.splice(removeIndex, 1);
        }
        var tax_amnt = taxDivision(arr)        
        updateTaxAmount(tax_amnt)
        $('.total-amnt').html(totalAmount())
        updateTotal()       
        // console.log(arr,"final")
    }

    function saveInvoice(finalData){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var myOffscreenEl = document.getElementById("invoice");
        var htmlData =  myOffscreenEl.innerHTML;
        //     useWidth      = myOffscreenEl.style.width,
        //     useHeight     = myOffscreenEl.style.height;
        // myOffscreenEl.style.backgroundColor = 'white';
        // html2pdf(myOffscreenEl, {
        //     margin      :  10,
        //     filename    :  'Invoice.pdf',
        //     image       :  { type: 'jpeg', quality: 0.98 },
        //     html2canvas :  { dpi: 300, letterRendering: true },
        //     jsPDF       :  { unit: 'pt', format: 'a4', orientation: 'portrait' }
        // });
        $.ajax({
            url: "/erp/save-invoice",
            type: 'POST',
            data: {
                _token: csrf_token,
                finalData:finalData,
                htmlData:htmlData
            },
            tryCount: 0,
            retryLimit: 2,
            dataType: "json",

            success: function (data) {
                $('.snackbar_span').html("Invoice Send Successfully !");
                showSnackbar();                
                $('#erp-invoice').modal("hide")
                // setTimeout(function(){window.location = 'invoice';}, 3000); 

            },
            error: function (errorXHR, errorStatus, errorThrown) {
               
            },
            complete: function () {
            
            }
        })
    }
