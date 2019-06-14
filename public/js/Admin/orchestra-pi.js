/**
 * call the method for getting all the details for profoma invoice generation
 * @param  {[type]} uvc         [description]
 * @param  {[type]} po          [description]
 * @param  {[type]} template_id [description]
 * @param  {[type]} table_name  [description]
 * @return profoma invoice
 */

const genarateOrchetraPI = (id,table_name,uvc,pi_status) => 
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/show-proforma-invoice",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            buyer_id : buyer_id,
            id:id,
            uvc:uvc
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
                    $('.advnc').text(data.data.bank_details.payment_terms)
                    $('.freight').text(data.data.bank_details.freight_terms)
                    $('.pi_number').text(data.data.company_details.pi_number)
                    $('.pi-date').text(moment(data.data.company_details.pi_date).format('DD/MM/YYYY'))
                    if (data.data.company_details.rev_number!=0)
                    {
                       $('.rev').removeClass('hidden')
                       $('.rev_number').text(data.data.company_details.rev_number) 
                    }
                    else{
                      $('.rev').addClass('hidden')
                    }

                    $('#company_details').html('<strong style="font-size:11px;">'+data.data.company_details.name+'</strong><br/>'+data.data.company_details.address+'<br/>PH : '+data.data.company_details.office_phone+', '+data.data.company_details.phone+'<br/>GSTIN : '+data.data.company_details.gstin)
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'</br> GSTIN :'+data.data.bank_details.gstin_number+ '</p>')
                    $('#ship_to').html('<h4>Shipping Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.ship_to.address+'</p>')
                    $('#bank_details').html(' <strong>BANK DETAILS: </strong><table class="table table-condensed" style="width:50%;"><tr><td>BANK</td><td>'+data.data.company_details.bank+'</td></tr><tr><td>A/C No</td><td>'+data.data.company_details.account_no+'</td></tr><tr><td>A/C NAME</td><td>'+data.data.company_details.account_holder+'</td></tr><tr><td>IFSC CODE</td><td>'+data.data.company_details.ifsc_code+'</td></tr></table>')

                    var i = 0;
                    var totalqnty = 0;
                    var subtotal = 0;
                   
                    var tr = '';
                    if (data.data.bank_details.template_type == "single")
                    {
                        tr += '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S.No.</th><th>Style</th><th>Po.No.</th><th>Ref</th><th class="size-pi">Size</th><th>Quantity</th><th>Unit Cost</th><th>Tax</th><th>Total</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';    
                    }
                    else
                    {
                        tr += '<table class="table table-bordered table-condensed"><thead><tr><th>S.No.</th><th>Style</th><th>Po.No.</th><th>Ref</th><th>Quantity</th><th>Unit Cost</th><th>Tax</th><th>Total</th></tr></thead>';
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
                        var totaligst = 0;
                        var billamnt = 0;
                        var amount = 0;
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
                                    else{
                                        noVol++;
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
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.style+'</td><td>'+value.po_no+'</td><td>'+value.ref_no+'</td><td>'+size+'</td><td>'+value.quantity+'</td><td>'+unit_price+'</td><td>'+tax+'</td><td style="text-align: right;">'+amnt.toFixed(2)+'</td></tr>'
                            }
                            else
                            {
                                tr += '<tr><td>'+ ++i +'</td><td>'+value.style+'</td><td>'+value.po_no+'</td><td>'+value.ref_no+'</td><td>'+value.quantity+'</td><td>'+unit_price+'</td><td>'+tax+'</td><td style="text-align: right;">'+amnt.toFixed(2)+'</td></tr>'
                            }
                            
                            totaltax+=parseInt(tax);
                            if (data.data.bank_details.place_of_supply==1){
                                totalcgst+=((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                                totalsgst+=((parseInt(tax)/2)/100)*(value.quantity*unit_price);
                            }
                            else if (data.data.bank_details.place_of_supply==2){
                                totaligst+= ((parseInt(tax))/100)*(value.quantity*unit_price);
                            }
                            else{
                               // amount +=(totaltax/100)* (value.quantity*unit_price);
                               amount +=(parseInt(tax)/100)* (value.quantity*unit_price);
                            }
                            totalqnty+=value.quantity;
                            subtotal+=(value.quantity*unit_price);
                            billamnt+=amnt;
                        });
                        if(noPrice <1) {
                            // var taxamnt = subtotal*(tax/100);
                            // var billamnt = subtotal+taxamnt;
                            tr += '</tbody></table></div><hr><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4><hr><h4 class="text-right"> Sub-Total : ₹ '+subtotal.toFixed(2)+' </h4><hr/><div class="text-right">'
                            if (data.data.bank_details.place_of_supply==1)
                            {
                               tr += '<h4> CGST : (<span>'+(totalcgst).toFixed(2)+'</span>)</h4><h4> SGST : (<span>'+(totalcgst).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p  style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
                            }
                            else if (data.data.bank_details.place_of_supply==2)
                            {
                                tr += '<h4> IGST : (<span>'+totaligst.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize" id="bilamnt">'+RsPaise(billamnt.toFixed(2))+' </p></div><hr /></div>'
                            }
                            else
                            {
                                tr += '<h4> Taxes : (<span>'+amount.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹'+billamnt.toFixed(2)+' </h4><p style="text-transform: capitalize">'+RsPaise(billamnt.toFixed(2))+'  </p></div><hr /></div>'
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
                            $('#profoma-invoice').modal("show")

                        } else {
                            $('.snackbar_span').html('Price Details are Missing !!');
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
function approveOrchestraPI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/approve-profoma-invoice",
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
               $('.snackbar_span').html('PI Approved Successfully');
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               showSnackbar();             
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
function rejectOrchestraPI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/reject-profoma-invoice",
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
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               showSnackbar();             
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