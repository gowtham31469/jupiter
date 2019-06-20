function genarateTescoPI(id,tableName,pi_status,style_no,template_id,buyer_po,template_name,order_flag)
{
    $('#uvc_number').text(style_no)
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var tr = "";
    $.ajax({
        url: base_url + "/customer/tesco/order-management/show-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            tableName: tableName,
            buyer_id :buyer_id,
            pi_status:pi_status,
            style_no:style_no,
            template_id:template_id,
            buyer_po:buyer_po,
            id:id,
            template_name:template_name,
            order_flag:order_flag
        },
        beforeSend: function (){
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
                    $('.advnc').text(data.data.bank_details.payment_terms)
                    $('.freight').text(data.data.bank_details.freight_terms)
                    $('.pi_number').text(data.data.company_details.pi_number)
				    var createdate = data.data.company_details.pi_date.replace(/-/g,'/')
                    $('.pi-date').text(createdate)
                    if (data.data.company_details.rev_number!=0)
                    {
                       $('.rev').removeClass('hidden')
                       $('.rev_number').text(data.data.company_details.rev_number) 
                    }
                    else{
                      $('.rev').addClass('hidden')
                    }
                    

                    $('#company_details').html('<strong style="font-size:11px;">'+data.data.company_details.name+'</strong><br/>'+data.data.company_details.address+'<br/>PH : '+data.data.company_details.office_phone+', '+data.data.company_details.phone+'<br/>GST : '+data.data.company_details.gstin)
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'</br> GSTIN :'+data.data.bank_details.gstin_number+ '</p>')
                    $('#ship_to').html('<h4>Shipping Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.ship_to.address+'</p>')
                    $('#bank_details').html(' <strong>BANK DETAILS: </strong><table class="table table-condensed" style="width:50%;"><tr><td>Bank</td><td>'+data.data.company_details.bank+'</td></tr><tr><td>A/C No</td><td>'+data.data.company_details.account_no+'</td></tr><tr><td>A/C NAME</td><td>'+data.data.company_details.account_holder+'</td></tr><tr><td>IFSC CODE</td><td>'+data.data.company_details.ifsc_code+'</td></tr></table>')
               
               
                    var i = 0;
                    var totalqnty = 0;
                    var subtotal = 0;
                    var tax = 0 ;
                    var tr = '';
                    
                    if (data.data.bank_details.template_type == "single")
                    {
                        tr += '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S.No.</th><th>Style</th><th>Po.No.</th><th>Ref</th><th class="size-pi">Size</th><th>Quantity</th><th>Unit Cost</th><th>Tax</th><th>Total</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';    
                    }
                    else
                    {
                        tr += '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S.No.</th><th>Style</th><th>Po.No.</th><th>Ref</th><th>Quantity</th><th>Unit Cost</th><th>Tax</th><th>Total</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';
                    }        
                     
               
                    if(data.data.item_details.length != 0 && data.data.price_list.length != 0)
                    {
                        var noPrice = 0;
                        var noVol = 0 ;
                        var taxamnt = 0;
                        var amnt = 0;
                        var totaltax = 0;
                        var totalcgst = 0;
                        var totalsgst = 0;
                        var totaligst = 0
                        var billamnt = 0;
                        var amount = 0;
                        // tax = data.data.item_details[0].tax;
                        $.each(data.data.item_details, function (index, value)
                        {
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
                            if(unit_price === 0){
                                noPrice++;
                            }
                            taxamnt = (value.quantity*unit_price)*(tax/100);
                            amnt =  (value.quantity*unit_price) + taxamnt ;
                            if (data.data.bank_details.template_type == "single")
                            {
                                size = value.size ;
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.style+'</td><td>'+value.po_no+'</td><td>'+value.ref_no+'</td><td>'+size+'</td><td>'+value.quantity+'</td><td>'+unit_price+'</td><td>'+tax+'</td><td>'+amnt.toFixed(2)+'</td></tr>'
                            }
                            else
                            {
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.style+'</td><td>'+value.po_no+'</td><td>'+value.ref_no+'</td><td>'+value.quantity+'</td><td>'+unit_price+'</td><td>'+tax+'</td><td>'+amnt.toFixed(2)+'</td></tr>'
                            }
                            totaltax+=parseInt(tax);
                            if (data.data.bank_details.place_of_supply==1){
                                totalcgst+=((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                                totalsgst+=((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                            }
                            else if (data.data.bank_details.place_of_supply==2){
                                totaligst   += ((parseInt(tax))/100)*(value.quantity*unit_price);
                            }
                            else{
                               // amount +=(totaltax/100)* (value.quantity*unit_price);
                               amount +=(parseInt(tax)/100)* (value.quantity*unit_price);
                               
                            }
                            totalqnty+=value.quantity;
                            subtotal+=(value.quantity*unit_price);
                            billamnt+=amnt;
                        });

                        if(noPrice < 1) {

                            tr += '</tbody></table></div><hr><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4><hr><h4 class="text-right"> Sub-Total : &#x20b9; '+subtotal.toFixed(2)+' </h4><hr/><div class="text-right">'
                            if (data.data.bank_details.place_of_supply==1)
                            {
                              tr += '<h4> CGST : &#x20b9; (<span>'+(totalcgst).toFixed(2)+'</span>)</h4><h4> SGST : &#x20b9; (<span>'+(totalcgst).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9; '+billamnt.toFixed(2)+' </h4><p  style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                            }
                            else if (data.data.bank_details.place_of_supply==2)
                            {
                                tr += '<h4> IGST : &#x20b9; (<span>'+totaligst.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9; '+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+' </p></div><hr /></div>'
                            }
                            else
                            {
                                 tr += '<h4> Taxes : &#x20b9; (<span>'+amount.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : &#x20b9;'+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                            }
                            $('#item_details').html(tr)
                            if (pi_status=="true")
                            {
                                $('.pi_actions').html('<button class="btn btn-default btn-xs downloadPi" id="downloadPi" onclick=downloadPi("'+template_id+'","'+id+'","'+tableName+'","tesco")>Download</button><button type="button" class="btn btn-default btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                            }
                            else
                            {
                                $('.pi_actions').html('<button class="btn btn-success btn-xs" onclick=tescoApprovePI("'+template_id+'","'+id+'","'+tableName+'","'+billamnt+'")>Approve</button><button class="btn btn-danger btn-xs" onclick=tescoRejectPI("'+id+'","'+tableName+'","tesco")>Reject</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                            }
                            var logo = base_url+data.data.company_details.logo
                            $('#company-logo').attr('src',logo);
                            $('#profoma-invoice').modal("show");
                           
                        } else {
                            $('.snackbar_span').html('Price details should be within a range !!');
                            showSnackbar();
                        }
                        
                    }
                    else
                    {
                        $('.snackbar_span').html('Price Details are Missing !!');
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
        error: function (data)
        {

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
function tescoApprovePI(template_id,id,table_name,billamnt)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    $.ajax({
        url: base_url + "/customer/tesco/approve-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            tableName: table_name,
            buyer_id:buyer_id,
            id:id,
            template_id:template_id,
            billamnt:billamnt
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Approved Successfully');
               $('#snackbar').css('background-color','#00b6ff');
               $('#profoma-invoice').modal("hide");
               $('#'+table_name+'_'+id+' .pi_class').removeClass().addClass('pi_class label label-PIApproved').text('PI Approved');
               var attrbs = $('#'+table_name+'_'+id+' .pi_generation').attr('onclick');
               // var attrbs = attrbs.replace("false", "true");
               $('#'+table_name+'_'+id+' .pi_generation').attr('onclick', attrbs);
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
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })

}
/**
 * method to call the PI rejection functionality
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @return {[type]}            [description]
 */
function tescoRejectPI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    $.ajax({
        url: base_url + "/customer/tesco/reject-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id:buyer_id,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('PI Rejected!');
               $('#profoma-invoice').modal("hide")
               $('#'+table_name+'_'+id+' .pi_class').removeClass().addClass('pi_class label label-PIRejected').text('PI Rejected');
               showSnackbar();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#profoma-invoice').modal("hide")
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function Rs(amount) {   
    var words = new Array();
    words[0] = 'Zero';
    words[1] = 'One';
    words[2] = 'Two';
    words[3] = 'Three';
    words[4] = 'Four';
    words[5] = 'Five';
    words[6] = 'Six';
    words[7] = 'Seven';
    words[8] = 'Eight';
    words[9] = 'Nine';
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
        console.log(nums[1])
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

function downloadPi(template_id,id,table_name){

    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var amntText = $('#bilamnt').text();
    $.ajax({
        url: base_url + "/customer/tesco/download-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            tableName: table_name,
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
                var resp = JSON.stringify(data);
                    console.log( base_url+data.responseText)
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
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}