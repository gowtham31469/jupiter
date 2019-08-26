/**
 * call the method for getting all the details for profoma invoice generation
 * @param  {[type]} uvc         
 * @param  {[type]} po          
 * @param  {[type]} table_name  
 * @param  {[type]} buyer_id  
 * @return profoma invoice
 */

const genarateOrchetraPI = (id, table_name, uvc, pi_status, buyer_po) => 
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var buyer_po_no = buyer_po === '-'?null:buyer_po
    $.ajax({
        url: base_url + "/customer/orchestra/order-management/show-orchestra-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id : buyer_id,
            pi_status:pi_status,
            id:id,
            uvc:uvc,
            buyer_po:buyer_po_no
        },
        tryCount: 0,
        retryLimit: 2,
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {              
               if ((jQuery.isEmptyObject(data.data.company_details)) || (jQuery.isEmptyObject(data.data.bank_details)) || (jQuery.isEmptyObject(data.data.ship_to)) || (jQuery.isEmptyObject(data.data.bill_to)))
               {
                    $('.snackbar_span').html("Customer Profile isn't complete");
                    showSnackbar();
               }
               else
               {     
                    $('#uvc_number').text(uvc)                  
                    $('.advnc').text(data.data.bank_details.payment_terms)
                    $('.freight').text(data.data.bank_details.freight_terms)
                    $('.pi_number').text(data.data.company_details.pi_number)
                    $('.pi-date').text(data.data.company_details.pi_date);

                    // check if revision number is 0 or not.
                    if (data.data.company_details.rev_number!=0)
                    {
                       $('.rev').removeClass('hidden')
                       $('.rev_number').text(data.data.company_details.rev_number) 
                    }
                    else{
                      $('.rev').addClass('hidden')
                    }

                    // company details data
                    $('#company_details').html('<h4><strong>'+data.data.company_details.name+'</strong></h4>'+data.data.company_details.address+'<br/>PH : '+data.data.company_details.office_phone+', '+data.data.company_details.phone+'<br/>GSTIN : '+data.data.company_details.gstin)
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'-'+data.data.bill_to.pincode+'</br> GSTIN :'+data.data.bank_details.gstin_number+ '</p>');

                    // ship to details data
                    $('#ship_to').html('<h4>Shipping Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.ship_to.address+'-'+data.data.ship_to.pincode+'</p>')
                    $('#bank_details').html(' <strong>BANK DETAILS: </strong><table class="table table-condensed" style="width:50%;"><tr><td>BANK</td><td>'+data.data.company_details.bank+'</td></tr><tr><td>A/C No</td><td>'+data.data.company_details.account_no+'</td></tr><tr><td>A/C NAME</td><td>'+data.data.company_details.account_holder+'</td></tr><tr><td>IFSC CODE</td><td>'+data.data.company_details.ifsc_code+'</td></tr></table>');

                    // order details table.
                    var i = 0;
                    var totalqnty = 0;
                    var subtotal = 0;
                   
                    var tr = '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S.No.</th><th>UVC</th><th>Po.No.</th><th>Ref</th><th class="size-pi">Size</th><th>Quantity</th><th>Unit Cost</th><th>Tax</th><th>Total</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';    

                    if(data.data.item_details.length != 0 && data.data.price_list.length != 0)
                    {
                        var noPrice = 0;
                        var taxamnt = 0;
                        var amnt = 0;
                        var totalcgst = 0;
                        var totalsgst = 0;
                        var totaligst = 0;
                        var billamnt = 0;
                        var amount = 0;
                        $.each(data.data.item_details, function (index, value)
                        {
                            // set unit price and tax value based on quantity and volume to value
                            var unit_price = 0;
                            var tax = 0;
                            $.each(data.data.price_list, function(i, val){
                                if (value.item_id == val.item_id) {
                                    if(value.quantity <= val.volume_to) {
                                        unit_price = val.price;
                                        tax = val.tax;
                                        return false; 
                                    }
                                }
                            });

                            // if price is 0, (ie, there is no price data available for quantity) increment 'noPrice' by 1
                            if(unit_price === 0){
                                noPrice++;
                            }

                            taxamnt = (value.quantity*unit_price)*(tax/100);
                            amnt    =  (value.quantity*unit_price) + taxamnt ;

                            // table rows
                            size = value.size ;
                            var buyer_po = value.po_no === ('' || null)?'-':value.po_no;
                            tr += '<tr><td>'+ ++i +'</td><td>'+value.uvc+'</td><td>'+buyer_po+'</td><td>'+value.ref_no+'</td><td>'+size+'</td><td>'+value.quantity+'</td><td>'+unit_price+'</td><td>'+tax+'</td><td style="text-align: right;">'+amnt.toFixed(2)+'</td></tr>';
                            
                            
                            if (data.data.bank_details.place_of_supply==1){
                                totalcgst += ((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                                totalsgst += ((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                            }
                            else if (data.data.bank_details.place_of_supply==2){
                                totaligst += ((parseInt(tax))/100)*(value.quantity*unit_price);
                            }
                            else{
                               amount += (parseInt(tax)/100)* (value.quantity*unit_price);
                            }
                            totalqnty += value.quantity;
                            subtotal += (value.quantity*unit_price);
                            billamnt += amnt;
                        });

                        // price details missing
                        if(noPrice < 1) {
                            tr += '</tbody></table></div><hr><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4><hr><h4 class="text-right"> Sub-Total : &#x20b9; '+subtotal.toFixed(2)+' </h4><hr/><div class="text-right">'
                            if (data.data.bank_details.place_of_supply==1)
                            {
                               tr += '<h4> CGST : &#x20b9; (<span>'+(totalcgst).toFixed(2)+'</span>)</h4><h4> SGST : &#x20b9; (<span>'+(totalsgst).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9; '+billamnt.toFixed(2)+' </h4><p  style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                            }
                            else if (data.data.bank_details.place_of_supply==2)
                            {
                                tr += '<h4> IGST : &#x20b9; (<span>'+totaligst.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9; '+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+' </p></div><hr /></div>'
                            }
                            else
                            {
                                tr += '<h4> Taxes : &#x20b9; (<span>'+amount.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9;'+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                            }
                            $('#item_details').html(tr);
                            
                            if (pi_status=="true")
                            {
                                $('.pi_actions').html('<button class="btn btn-default btn-xs downloadPi" id="downloadPi" onclick=orchestradownloadPi("'+data.data.template_id+'","'+id+'","'+table_name+'","orchestra")>Download</button><button type="button" class="btn btn-default btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                            }
                            else
                            {
                                $('.pi_actions').html('<button class="btn btn-success btn-xs" onclick=orchestraApprovePI("'+data.data.template_id+'","'+id+'","'+table_name+'","'+billamnt.toFixed(2)+'")>Approve</button><button class="btn btn-danger btn-xs" onclick=orchestraRejectPI("'+id+'","'+table_name+'","orchestra")>Reject</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                            }
                            var logo = base_url+data.data.company_details.logo
                            $('#company-logo').attr('src',logo);
                            $('#profoma-invoice').modal("show")

                        } else {
                            $('.snackbar_span').html('Price details should be within a range !!');
                            showSnackbar();
                        }
                    }
                    else
                    {
                        $('.snackbar_span').html('Price details should be within a range !!');
                        showSnackbar();
                    }
                } 
            
            },
            400: function(data)
            {
               $('.snackbar_span').html('Something went wrong');
               showSnackbar();
            }
        },
        error: function (errorXHR, errorStatus, errorThrown) {
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
/**
 * method to call the PI approval functionality
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @return {[type]}            [description]
 */
function orchestraApprovePI(template_id,row_id,table_name,amount)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var amntText = $('#bilamnt').text();

    $.ajax({
        url: base_url + "/customer/orchestra/approve-orchestra-pi",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id:buyer_id,
            id:row_id,
            template_id:template_id,
            billamnt:amount,
            amntText:amntText
        },
        tryCount: 0,
        retryLimit: 2,
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Approved Successfully');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
               location.reload();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (errorXHR, errorStatus, errorThrown) {
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
/**
 * method to call the PI rejection functionality
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @return {[type]}            [description]
 */
function orchestraRejectPI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/orchestra/reject-orchestra-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            id:id
        },
        tryCount: 0,
        retryLimit: 2,
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Rejected!');
               $('#profoma-invoice').modal("hide")
               showSnackbar();    
               location.reload();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (errorXHR, errorStatus, errorThrown) {
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


/**
 * Generate and download PI PDF file
 * @param {*} template_id 
 * @param {*} id 
 * @param {*} table_name 
 */
function orchestradownloadPi(template_id,id,table_name){

    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var amntText = $('#bilamnt').text();
    $.ajax({
        url: base_url + "/customer/orchestra/download-orchestra-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id:buyer_id,
            template_id:template_id,
            id:id,
            amntText:amntText
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        { 
            200: function(data)
            {
                $('.snackbar_span').html("Successfully send Invoice to the Customer !");
                showSnackbar();
                window.open(
                    base_url+$.trim(data.responseText),
                    '_blank' // <- This is what makes it open in a new window.
                );            
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (data){
            
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

/**
 * Price formating : Numeric values to words
 * @param {*} amount 
 */
function Rs(amount) {   
    var words = new Array();
    words[0]  = 'Zero';
    words[1]  = 'One';
    words[2]  = 'Two';
    words[3]  = 'Three';
    words[4]  = 'Four';
    words[5]  = 'Five';
    words[6]  = 'Six';
    words[7]  = 'Seven';
    words[8]  = 'Eight';
    words[9]  = 'Nine';
    words[10] = 'Ten';
    words[11] = 'Eleven';
    words[12] = 'Twelve';
    words[13] = 'Thirteen';
    words[14] = 'Fourteen';
    words[15] = 'Fifteen';
    words[16] = 'Sixteen';
    words[17] = 'Seventeen';
    words[18] = 'Eighteen';
    words[19] = 'Nineteen';
    words[20] = 'Twenty';
    words[30] = 'Thirty';
    words[40] = 'Forty';
    words[50] = 'Fifty';
    words[60] = 'Sixty';
    words[70] = 'Seventy';
    words[80] = 'Eighty';
    words[90] = 'Ninety';
    var op;
    amount = amount.toString();
    var atemp = amount.split(".");
    var number = atemp[0].split(",").join("");
    var n_length = number.length;
    var words_string = "";
    if (n_length <= 9) {
        var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
        var received_n_array = new Array();
        for (var i = 0; i < n_length; i++) {
            received_n_array[i] = number.substr(i, 1);
        }
        for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
            n_array[i] = received_n_array[j];
        }
        for (var i = 0, j = 1; i < 9; i++, j++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                if (n_array[i] == 1) {
                    n_array[j] = 10 + parseInt(n_array[j]);
                    n_array[i] = 0;
                }
            }
        }
        value = "";
        for (var i = 0; i < 9; i++) {
            if (i == 0 || i == 2 || i == 4 || i == 7) {
                value = n_array[i] * 10;
            } else {
                value = n_array[i];
            }
            if (value != 0) {
                words_string += words[value] + " ";
            }
            if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Crores ";
            }
            if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Lakhs ";
            }
            if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                words_string += "Thousand ";
            }
            if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                words_string += "Hundred and ";
            } else if (i == 6 && value != 0) {
                words_string += "Hundred ";
            }
        }
        words_string = words_string.split("  ").join(" ");
    }
    return words_string;
}

function RsPaise(n) {
    nums = n.toString().split('.')
    var whole = Rs(nums[0])
    if (nums[1] == null) nums[1] = 0;
    if (nums[1].length == 1) nums[1] = nums[1] + '0';
    if (nums[1].length > 2) {
        nums[1] = nums[1].substring(2, length - 1)
    }

    if (nums.length == 2) {
        if (nums[0] <= 9) {
            nums[0] = nums[0] * 10
        } else {
            nums[0] = nums[0]
        };
        var fraction = Rs(nums[1])
        if (whole == '' && fraction == '') {
            op = 'Zero only';
        }
        if (whole == '' && fraction != '') {
            op = fraction + 'paise only';
        }
        if (whole != '' && fraction == '') {
            op = 'Rupees ' + whole + ' only';
        }
        if (whole != '' && fraction != '') {
            op = 'Rupees ' + whole + 'and  ' + fraction + 'paise only';
        }
        return (op)
    }
}