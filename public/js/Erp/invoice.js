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
                        html += '<tr><td><input name="select_all" value="'+value.id +'" id="'+value.id +'" class="po-select" type="checkbox" /></td><td>'+value.id +'</td><td>'+value.po_no+'</td><td>'+value.buyer_name+'</td></tr>';
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
                $('.order-details').html('');
                var tr = "";
                var qnty = 0;
                var amnt = 0;
                var tax_tr = 0;
                var totalcgst = 0;
                var totalsgst = 0;
                var totaligst = 0;
                var totaltax = 0;

                if(data.orderlist.length != 0)
                {   
                    var result = '';
                    var taxIndexes = [] ;
                    var taxlist = data.orderlist.map(a => a.tax);
                    var uniqueTax = [];
                    var tax_amnt = {};
                    var sub_amnt = 0;

                    $.each(taxlist, function(i, el){
                        if($.inArray(el, uniqueTax) === -1) 
                         uniqueTax.push(el);
                    }); 
                    $(uniqueTax).each(function(key, vals){
                        var sub_amnt = 0;
                        taxIndexes[vals] = $.map(data.orderlist, function(obj, index) {
                            if(obj.tax == vals) {
                                sub_amnt += obj.order_quantity*obj.rate;
                            }
                        }) 
                        tax_amnt[vals] = sub_amnt;                                     
                    });
                              
                    $(data.orderlist).each(function(index, value)
                    {
                        var size = (value.size == null)?'-':value.size ; 
                        console.log(uniqueTax)
                        tr += '<tr class="trClass" id="'+value.id+'">';
                        tr += '<td><input name="select_all" value="'+value.id +'" id="'+value.id +'" class="order-select" type="checkbox" /></td><td>'+value.id+'</td><td>'+value.po_no+'</td><td>'+value.style_no+'</td><td>'+value.ocn_number+'</td><td>'+value.id+'</td><td>'+value.refenece_name+'</td><td>'+size+'</td><td>'+value.hsn_code+'</td><td>'+value.tax+' %</td><td class="qnty">'+value.order_quantity+'</td><td style="width: 116px;"><input type="text" value="" class="form-control delivery-qnty" onkeyup="updateAmount.call(this,'+uniqueTax+')"></td><td class="rate">'+value.rate+'</td><td class="amnt">'+value.order_quantity * value.rate+'</td>';
                        tr += '</tr>';
                        qnty += value.order_quantity;
                        amnt += (value.order_quantity*value.rate);

                    });

                        tr+= '<tr><td colspan="10"><strong>Total</strong></td><td>'+qnty+'</td><td class="total-qnty" id="total-qnty">'+quantityTotal()+'</td><td></td><td class="total-amnt">'+amnt+'</td></tr>';


                    $('.order-details').html('<form id="frm-orders"><table class="table text-center"><thead><tr><th></th><th>Order No</th><th>PO No</th><th>Style</th><th>OCN No</th><th>Item</th><th>Reference</th><th>Size</th><th>HSN No</th><th>Tax</th><th>Order Quantity</th><th>Delivered Quantity</th><th>Rate</th><th>Amount</th></tr></thead><tbody>'+tr+'</tbody></table></form>');

                    var tax_table = $('.orders-tax').html('<form id="orders-tax"><table class="table text-center" style="margin-left: 31px;margin-top: -18px;"><thead><tr><th>Tax</th><th>Base value</th><th>Amount</th></tr></thead><tbody class="tax-tabl"></tbody></table></form>')

                    Object.keys(tax_amnt).forEach(function(key) {

                      if (data.orderlist[0].place_of_supply==1){
                        tax_tr = '<tr><td>CGST ('+(key/2)+'% )</td><td>'+tax_amnt[key]+'</td><td>'+(((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr><tr><td>SGST ('+(key/2)+'% )</td><td>'+tax_amnt[key]+'</td><td>'+ (((key/2)/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
                        }
                        else if (data.orderlist[0].place_of_supply==2){
                            
                            tax_tr = '<tr><td>IGST ('+key+'% )</td><td>'+tax_amnt[key]+'</td><td>'+((key/100)*tax_amnt[key]).toFixed(3)+'</td></tr>'
                        }
                        else{
                            tax_tr = '<tr><td>Taxes ('+key+'% )</td><td>'+tax_amnt[key]+'</td><td>'+ ((key/100)*tax_amnt[key] ).toFixed(3)+'</td></tr>'
                        }
                        $('.tax-tabl').append(tax_tr);
                    });

                    $('.tax-tabl').append('<tr><td colspan="2"><strong>SubTotal</strong></td><td class="tax-total">'+ taxSubTotal() +'</td></tr><tr></tr>')                

                    $('.extra-charges').html('<form id="extra-charges"><table class="table text-center" style="margin-left: 31px;margin-top: -18px;"><thead><tr><th>Description</th><th>Tax</th><th>Amount</th></tr></thead><tbody><tr><td><div style="width: 65%"><select class="charges" id="charges" name="charges" drequired=""><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td>1212</td><td>1212</td></tr><tr><td><div style="width: 65%"><select  id="charges" name="charges" class="charges" required=""><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td>1212</td><td>1212</td></tr><tr><td><div style="width: 65%"><select class="charges" id="charges" name="charges" drequired=""><option value="">Freight</option><option value="">Minimum charges</option><option value="">Job Work Charges</option></select></div></td><td>1212</td><td>1212</td></tr><tr><td colspan="2"><strong>SubTotal</strong></td><td>434</td></tr><tr></tr><tr><td colspan="2"><strong>Grand Total</strong></td><td>434</td></tr><tr></tr></tbody></table></form>')

                    var checkboxes = $('.dynamic-table td input[type="checkbox"]');
                    $(document).find('.charges').selectpicker();                  

                    if(checkboxes.filter(':checked').length >0){
                        $('.order-heading').removeClass('hidden')
                        $('.order-details').removeClass('hidden')
                        $('.remarks').removeClass('hidden')
                    }
                    else{
                        
                        $('.order-heading').addClass('hidden')
                        $('.order-details').addClass('hidden')
                        $('.remarks').addClass('hidden')
                    }
                }
                else{
                    $('.order-details').addClass('hidden')
                    $('.remarks').addClass('hidden')
                }
            },
            error: function (errorXHR, errorStatus, errorThrown) {
               
            },
            complete: function () {
            
            }
        })
    }

    function updateAmount(obj) {     
        var tax_array= [].slice.call(arguments);
        console.log(tax_array)
        var amnt  = 0 ;
        if($(obj).val() <= parseInt($(obj).closest('tr').find('.qnty').text())){

            amnt  = parseInt($(obj).val()) * $(obj).closest('tr').find('.rate').text();
            $(obj).closest('tr').find('.amnt').html(amnt)
        }
        else{
            amnt  = $(obj).closest('tr').find('.qnty').text() * $(obj).closest('tr').find('.rate').text();
            $(obj).closest('tr').find('.amnt').html(amnt)
            $(obj).val('')
        }        
        $('.total-amnt').html(totalAmount())
        $('#total-qnty').html(quantityTotal()) 
        $('.tax-total').html(taxSubTotal()) 
        updateTaxAmount(uniqueTax)
    }
    function totalAmount(){
        var result = [];

        $('.order-details tr').each(function(){
            $('td', this).each(function(index, val){
                if(!result[index]) result[index] = 0;
              result[index] += parseFloat($(val).text());
            });
        }); 
        return result[13];
    }

    function quantityTotal(){
        var sum=0;
        $('.delivery-qnty').each(function(i, obj){
           if($.isNumeric(this.value)) {
                sum += parseFloat(this.value);
              }
        })
        return sum;
    }   
    function taxSubTotal()
    {
        var tax_amnt = [];
        $('.orders-tax tr').each(function(){
            $('td', this).each(function(index, val){
                if(!tax_amnt[index]) tax_amnt[index] = 0;
              tax_amnt[index] += parseFloat($(val).text());
            });
        });
        return tax_amnt[2] ;
    }

    function updateTaxAmount(uniqueTax){
        console.log(uniqueTax)
        // var taxIndexes = [] ;
        // $(uniqueTax).each(function(key, vals){
        //     var sub_amnt = 0;
        //     taxIndexes[vals] = $.map(data.orderlist, function(obj, index) {
        //         if(obj.tax == vals) {
        //             sub_amnt += obj.order_quantity*obj.rate;
        //         }
        //     }) 
        //     tax_amnt[vals] = sub_amnt;                                     
        // });
    }