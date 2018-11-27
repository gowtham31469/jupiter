/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

/** [showSnackbar function] */
    function showSnackbar() {
    try {
        // Get the snackbar DIV
        var x = document.getElementById("snackbar")

        // Add the "show" class to DIV
        x.className = "show";

        // After 3 seconds, remove the show class from DIV
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    } catch (e) {
        console.log("Error:" + e)
    }
}
function showFilter() {
    $('.filter-window').toggle();
}
/**
 * Job card generation 
 * @author <Praveen> [praveen@wizroots.com]
 */
function generateJobcard(){
    if ($("#approved-order-list input[class='row-checkbox']:checked").length === 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		showSnackbar();
    }
    else
    {
        orders = [];
        /** [ Create array of orders selected ] */
        $('.row-checkbox').each(function (i, item) {
            if ($(this).is(':checked')) {
                orders.push($(this).closest('tr').attr('id')); 
            }
        });
        orders   = orders.join(',');
        $.ajax({
            url:base_url + "/erp/get-jobcard-order-details",
            dataType:"json",
            method:"get",
            data:{orders:orders},
            tryCount : 0,
            retryLimit : 2,
            beforeSend: function () {
              $(".loader").show();
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function(data) {
                    
                    if(data.error === false)
                    {
                        if(data.template_data.company)
                        {
                            $('#company-name').text(data.template_data.company.company)
                            $('#company-address').text(data.template_data.company.company_address)
                            $('#company-logo').attr('src', data.template_data.company.company_logo);
                            $('#company-name').attr('data-company-id', data.template_data.company.company_id)
                        }

                        var workorder_id = data.template_data.company.company === 'Jupiter Garments Accessories'?'JGA-JC-':'SP-JC-';
                        $('#work_order').text( workorder_id+data.template_data.workorder_id);

                        $('#today-date').text( data.template_data.today_date);

                        data.template_data.article.length > 0?$('#article').text(data.template_data.article):'';

                        $.each(data.template_data.material, function(key, item){
                            $('#material').text(item)
                            $('#material').attr('data-material-id', key)
                        });

                        if(data.template_data.customer)
                        {
                            $('#customer').text(data.template_data.customer.customer_name)
                            $('#customer').attr('data-cutomer-id', data.template_data.customer.cutomer_id)
                        }

                        if(data.template_data.buyer)
                        {
                            $('#buyer').text(data.template_data.buyer.buyer_name)
                            $('#buyer').attr('data-buyer-id', data.template_data.buyer.buyer_id)
                        }

                        if(data.template_data.style_array.length === 1)
                        {
                            $('#style_no').text(data.template_data.style_array)
                        }
                        else
                        {
                            $('#style_no').text('')
                        }

                        if(data.template_data.refnum_array.length === 1)
                        {
                            $('#reference').text(data.template_data.refnum_array)
                        }
                        else
                        {
                            $('#reference').text('')
                        }
                        
                        if(data.template_data.ordertype_array.length === 1)
                        {
                           if(data.template_data.ordertype_array[0] === 'newOrder')
                           {
                             $('#order_type').text('Initial')
                           }
                           else if(data.template_data.ordertype_array[0] ===  'reOrder')
                           {
                             $('#order_type').text('ReOrder')
                           }
                           else if(data.template_data.ordertype_array[0] === 'repeatOrder')
                           {
                             $('#order_type').text('RepeatOrder')
                           }
                           else
                           {
                             $('#order_type').text('')
                           }
                        }
                        else 
                        {
                            $('#order_type').text('')
                        } 
                        
                        $('#order_date').text(data.template_data.order_date);  

                        if(data.template_data.length.length === 1)
                        {
                            $('#length').text(data.template_data.length)
                        }
                        else
                        {
                            $('#length').text('')
                        } 

                        if(data.template_data.width.length === 1)
                        {
                            $('#width').text(data.template_data.width)
                        }
                        else
                        {
                            $('#width').text('')
                        } 

                        if(data.template_data.color.length === 1)
                        {
                            $('#print_color').text(data.template_data.color)
                        }
                        else
                        {
                            $('#print_color').text('')
                        }

                        /** Show ratio templates dynamic table */
                        var ratio_table_html = '';
                        if(data.template_data.ratio)
                        {
                            $.each(data.template_data.ratio, function(key, template){
                                var total = 0;
                                ratio_table_html += '<table class="table table-bordered table-responsive"  style="font-size: 12px;" id="ratio-list-table">\
                                                        <thead>\
                                                            <tr>\
                                                                <th>REFNUM</th>\
                                                                <th>PONUM</th>\
                                                                <th>IDNUM</th>\
                                                                <th>OCN</th>\
                                                                <th>QNTY</th>\
                                                                <th>P.QNTY</th>\
                                                            </tr>\
                                                        </thead>\
                                                        <tbody>';
                                                        template.sort(function(a, b){
                                                            return a.qty_table_id - b.qty_table_id;
                                                        });
                                                        $.each(template, function(index, row){
                                                            total += row.order_quantity;
                                                            ratio_table_html += '<tr id='+row.erp_order_details_id+'>\
                                                            <td>'+row.reference_name+' '+row.ratio_pack_type+'</td>\
                                                            <td>'+row.buyer_po_number+'</td>\
                                                            <td>'+row.erp_id+'</td>\
                                                            <td>'+row.ocn_number+'</td>\
                                                            <td>'+row.order_quantity+'</td>\
                                                            <td></td>\
                                                            </tr>';
                                                        });
                                                        ratio_table_html += '<tr>\
                                                            <th>Total</th>\
                                                            <td></td>\
                                                            <td></td>\
                                                            <td></td>\
                                                            <td><strong>'+total+'</strong></td>\
                                                            <td></td>\
                                                        </tr>\
                                                    </tbody>\
                                                </table>';
                            });
                            $('.ratio-list-table-div').html(ratio_table_html);
                        }

                        /** Show single templates dynamic table */
                        var single_table_html = '';
                        if(data.template_data.single)
                        {
                            $.each(data.template_data.single, function(key, template){
                                var total = 0;
                                single_table_html += '<table class="table table-bordered table-responsive"  style="font-size: 12px;" id="single-list-table">\
                                                    <tbody>\
                                                        <tr>\
                                                            <th>ID Num</th>\
                                                            <td colspan="2">1000</td>\
                                                            <td colspan="2">1000</td>\
                                                            <td colspan="2">1000</td>\
                                                            <td colspan="5" rowspan="3">REFNUM  : TCL4</td>\
                                                        </tr>\
                                                        <tr>\
                                                            <th>B. PO NUM</th>\
                                                            <td colspan="2">460-12345</td>\
                                                            <td colspan="2">460-12346</td>\
                                                            <td colspan="2">460-12347</td>\
                                                        </tr>\
                                                        <tr>\
                                                            <th>OCN Num</th>\
                                                            <td colspan="2">1</td>\
                                                            <td colspan="2">2</td>\
                                                            <td colspan="2">3</td>\
                                                        </tr>\
                                                        <tr>\
                                                        <tr>\
                                                            <th>SIZE /Price</th>\
                                                            <th>O.QNT</th>\
                                                            <th>P.QNT</th>\
                                                            <th>O.QNT</th>\
                                                            <th>P.QNT</th>\
                                                            <th>O.QNT</th>\
                                                            <th>P.QNT</th>\
                                                            <th>TOTAL</th>\
                                                            <th>EXCESS</th>\
                                                            <th>MTRS</th>\
                                                            <th>Stock</th>\
                                                        </tr>\
                                                        <tr>\
                                                            <td>2-3</td>\
                                                            <td>1000</td>\
                                                            <td></td>\
                                                            <td>1000</td>\
                                                            <td></td>\
                                                            <td>1000</td>\
                                                            <td></td>\
                                                            <td>3000</td>\
                                                            <td>150</td>\
                                                            <td></td>\
                                                            <td></td>\
                                                        </tr>\
                                                        <tr>\
                                                            <th>TOTAL</th>\
                                                            <td>25000</td>\
                                                            <td></td>\
                                                            <td>25000</td>\
                                                            <td></td>\
                                                            <td>25000</td>\
                                                            <td></td>\
                                                            <td>75000</td>\
                                                            <td>3000</td>\
                                                            <td>0</td>\
                                                            <td>0</td>\
                                                        </tr>\
                                                    </tbody>\
                                                </table>';
                            });
                            $('.single-list-table-div').html(single_table_html);
                        }
                        
                    }
                    else
                    {
                        $('.snackbar_span').html("Something went wrong");
                        showSnackbar();
                    }
                       
                    
                    /** [ Backdrop for bootstrap modal ] */
                    $('#jobcardCreationModalCenter').modal({
                        backdrop: 'static',
                        keyboard: false
                    })

                    /** [ Validate edit_merchandiser_form form using bootstrap validator ] */
                    $('#create_jobcard').validator('validate');
                    $('#jobcardCreationModalCenter').modal('show');
                },
                406: function(data) {
                     $('.snackbar_span').html(data.responseJSON.message);
                     showSnackbar();
                },
                404: function(data) {
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
               },
                400: function() {
                    $('.snackbar_span').html("Something went wrong");
                    showSnackbar();
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
                $(".loader").hide();
            }
        })
        
    }
}

/**
 * Multi checkbox selection
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function selectOrders(event)
{
    /** Table checkbox click functionality	**/
    var checkbox = $("input[class='row-checkbox']");
    var customer_name = $(event).data('customer-name');
    var buyer_name = $(event).data('buyer-name');
    var ischecked= $(event).is(':checked');
    var length= $(checkbox).filter(':checked').length;

    if(ischecked)
    {
        /** change table row background color	**/
        $(event).closest('tr').addClass("highlight_row");
        /** Make checkboxes disabled(having different customer and buyer names) **/
        $(checkbox).not("[data-customer-name='"+customer_name+"']").attr("disabled", "true");
        $(checkbox).not("[data-buyer-name='"+buyer_name+"']").attr("disabled", "true");

    }
    else
    {
        /** change table row background color	**/
        $(event).closest('tr').removeClass("highlight_row");
        /** Make checkboxes enabled iff all checkboxes unchecked **/
        if(length === 0)
         $(checkbox).not("[data-customer-name='"+customer_name+"']").removeAttr("disabled");
         $(checkbox).not("[data-buyer-name='"+buyer_name+"']").removeAttr("disabled");

    }
}