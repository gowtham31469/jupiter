	function dynamicTable() {
        var customer_id       = $("#customer option:selected").val();
        var html = "";
        $.ajax({
            url: "/erp/erp-po-list",
            type: 'GET',
            data: {
                customer_id:customer_id
            },
            // tryCount: 0,
            // retryLimit: 2,
            dataType: "json",
            // beforeSend: function () {
            //     $(".loader").show();
            // },
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
                        // console.log($(this ).val())
                      showOrderList(this)                           
                    });
                }
                else{
                    $('.po-list').addClass('hidden')
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
            // beforeSend: function () {
            //     $(".loader").show();
            // },
            success: function (data) {
                // console.log(data)
                $('.order-details').html('');
                var tr = "";
                var qnty = 0;
                var amnt = 0;
                var qnty = 0;
                if(data.orderlist.length != 0)
                {
                    $(data.orderlist).each(function(index, value)
                    {
                        tr += '<tr class="trClass" id="'+value.id+'">';
                        tr += '<td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td>'+value.id+'</td><td class="qnty">'+value.order_quantity+'</td><td><div class="col-xs-6"><input type="text" value="'+value.order_quantity+'" class="form-control delivery-qnty" onkeyup="updateAmount(this)"></div></td><td class="rate">2</td><td class="amnt">'+value.order_quantity * value.rate+'</td>';
                        tr += '</tr>';
                        qnty += value.order_quantity;
                        amnt += (value.order_quantity * value.rate);

                    });

                        tr+= '<tr><td colspan="9">Total</td><td>'+qnty+'</td><td></td><td></td><td>'+amnt+'</td></tr>';

                    $('.order-details').html('<form id="frm-orders"><table class="table text-center"><thead><tr><th>Order No</th><th>PO No</th><th>Style</th><th>OCN No</th><th>Item</th><th>Reference</th><th>Size</th><th>HSN No</th><th>Tax</th><th>Order Quantity</th><th>Quantity</th><th>Rate</th><th>Amount</th></tr></thead><tbody>'+tr+'</tbody></table></form>');
                        
                    var checkboxes = $('.dynamic-table td input[type="checkbox"]');
                    if(checkboxes.filter(':checked').length >0){
                        $('.order-details').removeClass('hidden')
                    }
                    else{
                        $('.order-details').addClass('hidden')
                    }
                }
                else{
                    $('.order-details').addClass('hidden')
                }
            },
            error: function (errorXHR, errorStatus, errorThrown) {
               
            },
            complete: function () {
            
            }
        })
    }

    function updateAmount(obj) {
         var amnt  = 0 ;
        console.log($(obj).val(),$(obj).closest('tr').find('.qnty').text())
        if($(obj).val() <= parseInt($(obj).closest('tr').find('.qnty').text())){
            amnt  = parseInt($(obj).val()) * parseInt($(obj).closest('tr').find('.rate').text());
            $(obj).closest('tr').find('.amnt').html(amnt)
        }
        else{
            amnt  = parseInt($(obj).closest('tr').find('.qnty').text()) * parseInt($(obj).closest('tr').find('.rate').text());
            $(obj).closest('tr').find('.amnt').html(amnt)
            $(obj).val('')
        }        
    }