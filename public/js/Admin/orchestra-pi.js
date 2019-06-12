/**
 * call the method for getting all the details for profoma invoice generation
 * @param  {[type]} uvc         [description]
 * @param  {[type]} po          [description]
 * @param  {[type]} template_id [description]
 * @param  {[type]} table_name  [description]
 * @return profoma invoice
 */

function genarateOrchetraPI(id,table_name,uvc,pi_status)
{
    $('#uvc_number').text(uvc)
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var tr = ""
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
                    $('.snackbar_span').html('Something went wrong');
                    showSnackbar();
               }
               else
               {
                    $('#company_details').html('<h3><strong>'+data.data.company_details.name+'</strong></h3>'+data.data.company_details.address+'<br/>'+data.data.company_details.phone+'<br/><strong>GSTIN :'+data.data.company_details.phone+'</strong>')
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'</br> GSTIN :'+data.data.bank_details.gstin_number+ '</p>')
                    $('#ship_to').html('<h4>Shipping Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.ship_to.address+'</p>')
                    $('#bank_details').html(' <strong> Bank Details: </strong><table class="table table-condensed" style="width:50%;"><tr><td>Bank</td><td>'+data.data.bank_details.bank+'</td></tr><tr><td>A/C TYPE</td><td>OD</td></tr><tr><td>A/C NUMBER</td><td>'+data.data.bank_details.account_no+'</td></tr><tr><td>ACCOUNT NAME</td><td>'+data.data.bank_details.account_holder+'</td></tr><tr><td>ADDRESS</td><td>'+data.data.bank_details.branch+'</td></tr><tr><td>RTGS & IFSC</td><td>'+data.data.bank_details.ifsc_code+'</td></tr></table>')
               
               
                    var i = 0;
                    var totalqnty = 0;
                    var subtotal = 0;
                    var tax = 0 ;
                               
                    var tr = '<div class="col-lg-12 col-md-12 col-sm-12"><div class="table-responsive"><table class="table table-bordered table-condensed"><thead><tr><th>S. No.</th><th>Material</th><th>Size</th><th>Style</th><th>Quantity</th><th>Unit Cost (₹)</th><th>Total (₹)</th></tr></thead><div class="col-lg-12 col-md-12 col-sm-12">';
               
                    if (data.data.item_details.length!=0)
                    {
                        tax = data.data.item_details[0].tax;
                        $.each(data.data.item_details, function (index, value)
                        {
                            tr += '<tr><td>'+ ++i +'</td><td>'+value.material_name+'</td><td>'+value.size+'</td><td>'+value.style+'</td><td>'+value.quantity+'</td><td>'+value.unit_price+'</td><td>'+value.quantity*value.unit_price+'</td></tr>'
                            totalqnty+=value.quantity;
                            subtotal+=value.quantity*value.unit_price;

                        });
                        var taxamnt= subtotal*(tax/100);
                        var billamnt = subtotal+taxamnt;
                        tr += '</tbody></table></div><hr><div><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4></div><hr><div><h4 class="text-right"> Sub-Total : ₹ '+subtotal.toFixed(2)+' </h4></div><hr/><div class="text-right">'
                        if (data.data.bank_details.place_of_supply==1)
                        {
                           tr += '<h4> CGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4><h4> SGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+RsPaise(billamnt.toFixed(2))+' only</p></div><hr /></div>'
                        }
                        else if (data.data.bank_details.place_of_supply==2)
                        {
                            tr += '<h4> IGST : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+RsPaise(billamnt.toFixed(2))+' only</p></div><hr /></div>'
                        }
                        else
                        {
                            tr += '<h4> Taxes : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹'+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+RsPaise(billamnt.toFixed(2))+' only</p></div><hr /></div>'
                        }
                        $('#item_details').html(tr)
                        if (pi_status=="true")
                        {
                        	$('.pi_actions').html('<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                        else
                        {
                        	$('.pi_actions').html('<button class="btn btn-success btn-xs" onclick=approveOrchestraPI("'+id+'","'+table_name+'")>Approve</button><button class="btn btn-danger btn-xs" onclick=rejectOrchestraPI("'+id+'","'+table_name+'")>Reject</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                       var logo = base_url+data.data.company_details.logo
                        $('#company-logo').attr('src',logo);
                       $('#profoma-invoice').modal("show")
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