	var place_of_supply = 0;
    var order_data = '';
    var taxIndexes = [] ;
    var uniqueTax = [];
    var tax_amnt = {};
    var sub_amnt = 0;
    var result = '';

    function dynamicTable() {
        var customer_id       = $("#customer option:selected").val();
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
                    createInvoice(data,order_data)                                       
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
    function createInvoice(data,taxData){
        orderData = data.orderlist;
        $('.order-details').html('');
        var tr = "";
        var qnty = 0;
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
            tr += '<td><input name="order_select" value="'+value.id +'" id="'+value.id +'" class="order-select" type="checkbox" checked/></td><td>'+value.id+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_number+'</td><td>'+value.id+'</td><td>'+value.reference_name+'</td><td>'+size+'</td><td>'+value.hsn_code+'</td><td class="tax-td">'+value.tax+' %</td><td class="qnty">'+value.order_quantity+'</td><td class="pending-qnty">'+value.order_quantity+'</td><td style="width: 116px;"><input type="text" value="" class="form-control delivery-qnty" required="" data-id="'+value.id+'" min="1" onkeyup="updateAmount.call(this,'+uniqueTax+')"></td><td class="rate">'+value.rate+'</td><td class="amnt">'+value.order_quantity * value.rate+'</td>';
            tr += '</tr>';
            qnty += value.order_quantity;
            amnt += (value.order_quantity*value.rate);

        });

            tr+= '<tr><td colspan="10"><strong>Total</strong></td><td>'+qnty+'</td><td class="balnc-qnty" id="balnc-qnty">'+qnty+'</td><td class="total-qnty" id="total-qnty">'+quantityTotal()+'</td><td></td><td class="total-amnt">'+amnt+'</td></tr>';


        $('.order-details').html('<table class="table text-center order-details" id="order-details"><thead><tr><th></th><th>Order No</th><th>PO No</th><th>Style</th><th>OCN No</th><th>Item</th><th>Reference</th><th>Size</th><th>HSN No</th><th>Tax</th><th>Order Quantity</th><th>Pending Quantity</th><th>Delivered Quantity</th><th>Rate</th><th>Amount</th></tr></thead><tbody>'+tr+'</tbody></table>');

        updateTaxAmount(tax_amnt)

        var gndtotal = parseFloat($('.tax-total').text())+ parseFloat($('.total-amnt').text());
        $('.extra-charges').html('<table class="table text-center extra-charges" style="margin-left: 31px;margin-top: -18px;"><thead><tr><th colspan="2">Description</th><th>Amount</th></tr></thead><tbody><tr><td><div style="width: 65%"><select class="charges" id="charges1" name="charges1"><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td style="width: 116px;" colspan="2"><input type="text" value="" min="0" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><div style="width: 65%"><select  id="charges2" name="charges2" class="charges"><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td style="width: 116px;"><input type="text" min="0" value="" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><div style="width: 65%"><select class="charges" id="charges3" name="charges3" ><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td style="width: 116px;"><input type="text" min="0" value="" class="form-control extra-amnt" onkeyup="updateTotal()"></td></tr><tr><td colspan="2"><strong>SubTotal</strong></td><td class="sub-extra">0</td></tr><tr></tr><tr><td colspan="2"><strong>Grand Total</strong></td><td class="grand-total">'+gndtotal.toFixed(3)+'</td></tr><tr></tr></tbody></table><div class="create-invoice"><button type="button" class="btn btn-primary create-invoice pull-right" style="margin-right: -32px;margin-top: -10px;" id="create-invoice">Create Invoice</button></div>')

            $('#create-invoice').on('click',function(){
                previewInvoice(data)
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

        var taxlist = orderData.map(a => a.tax);
        $.each(taxlist, function(i, el){
            if($.inArray(el, uniqueTax) === -1) 
             uniqueTax.push(el);
        }); 
        $(uniqueTax).each(function(key, vals){
            var sub_amnt = 0;
            taxIndexes[vals] = $.map(orderData, function(obj, index) {
                if(obj.tax == vals) {
                    sub_amnt += obj.order_quantity*obj.rate;
                }
            }) 
            tax_amnt[vals] = sub_amnt;                                     
        });
    }

    function updateAmount() {
        // var flg = 0;
        var tax_array= [].slice.call(arguments);
        var amnt  = 0 ;
        var result = [];
        var taxsum = {};
        var arr = [];
        var pendingQnty = 0
        if($(this).val() <= parseInt($(this).closest('tr').find('.qnty').text())){
            amnt  = parseInt($(this).val()) * $(this).closest('tr').find('.rate').text();
            $(this).closest('tr').find('.amnt').html(amnt)
            pendingQnty = $(this).closest('tr').find('.qnty').text() - $(this).val()
            $(this).closest('tr').find('.pending-qnty').html(pendingQnty)
        }
        else{
            amnt  = $(this).closest('tr').find('.qnty').text() * $(this).closest('tr').find('.rate').text();
            $(this).closest('tr').find('.amnt').html(amnt)
            $(this).val('')
        }        
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
        var tax_amnt = [];
        $('.orders-tax tr').each(function(){
            $('td', this).each(function(index, val){
                if(!tax_amnt[index]) tax_amnt[index] = 0;
              tax_amnt[index] += parseFloat($(val).text());
            });
        });
        return tax_amnt[2] ;
    }

    function updateTaxAmount(tax_amnt){
        var tax_tr = "";
       
        var tax_table = $('.orders-tax').html('<form id="orders-tax"><table class="table text-center" style="margin-left: 31px;margin-top: -18px;"><thead><tr><th>Tax</th><th>Base value</th><th>Amount</th></tr></thead><tbody class="tax-tabl"></tbody></table></form>')
        Object.keys(tax_amnt).forEach(function(key) {
          if (place_of_supply==1){
            tax_tr = '<tr><td>CGST ('+(key/2)+'% )</td><td class="cgst_'+key+'">'+tax_amnt[key]+'</td><td>'+(((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr><tr><td>SGST ('+(key/2)+'% )</td><td class="sgst_'+key+'">'+tax_amnt[key]+'</td><td>'+ (((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
            }
            else if (place_of_supply==2){
                
                tax_tr = '<tr><td>IGST ('+key+'% )</td><td class="igst_'+key+'">'+tax_amnt[key]+'</td><td>'+((key/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
            }
            else{
                tax_tr = '<tr><td>Taxes ('+key+'% )</td><td>'+tax_amnt[key]+'</td><td>'+ ((key/100)*tax_amnt[key] ).toFixed(3)+'</td></tr>'
            }
            $('.tax-tabl').append(tax_tr);
        });
        $('.tax-tabl').append('<tr><td colspan="2"><strong>SubTotal</strong></td><td class="tax-total">'+ taxSubTotal().toFixed(3) +'</td></tr><tr></tr>')
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

    // $('#invoice_form').validator().on('submit', function (e) {
    //     if (e.isDefaultPrevented()) {

    //     } else {
    //         e.preventDefault();
    //         createInvoice();
    //     }
    // })

    function previewInvoice(data){
        $('.delivery-qnty').css('border-color', '');
        var delvryQnty = {};
        var count = 0 ;
        $('.delivery-qnty').each(function(i, obj){
            if($.isNumeric(this.value)) {             
               delvryQnty[$(this).data("id")] = parseFloat(this.value);
            }
            else{
                $(this).closest('tr').find('.delivery-qnty').focus();
                $(this).closest('tr').find('.delivery-qnty').css('border-color', '#F44336');
                $('.snackbar_span').html("Please provide delivered quantity details");
                showSnackbar();
            }
            count = i;
        })
        if (jQuery.isEmptyObject(delvryQnty) || Object.keys(delvryQnty).length<(count+1)) {
           $('.snackbar_span').html("Please provide delivered quantity details");
           showSnackbar();
        }
        else{
            
            $('#erp-invoice').modal("show")
        }
        
    }

    function updateInvoice(obj){
        var val = $(obj).val();
        var i = order_data.length;        
        var arr = [].concat(order_data);
        console.log(order_data,"old");
        var removeIndex = arr.map(function(item) { return item.id; }).indexOf(parseInt(val));
        var data = arr.splice(removeIndex, 1);
        createInvoice(order_data,arr)
        
    }