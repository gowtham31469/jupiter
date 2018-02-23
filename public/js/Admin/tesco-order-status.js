/**
 * method to show the tesco order status table
 * @return {[type]} [description]
 */
function tescoOrderStatus()
{
	$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.top-content').html('');
    $('.template-error').html('');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/tesco-orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
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
               var path = base_url + "/admin/tesco/order-management/order-status";
               var newObj = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
               $('.main-content').html(tescoStatusTable(newObj,path));
               $('.top-content').html("")
                           
               var $tableSel = $('#datatable-tesco-orders');
                $tableSel.DataTable({
                    "pageLength": 25,
                    initComplete: function () {
                        
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');
                        $("#datatable-tesco-orders_filter input").addClass('form-control input-sm');
		                $("#datatable-tesco-orders_filter input").attr('placeholder', 'Data Search');
		                $('#datatable-tesco-orders_length select').addClass('form-control input-sm');
                    }
                });
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
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
 * [method append data to tescoStatusTable ]
 * @param  {[type]} data [description]
 * @param  {[type]} path [description]
 * @return {[type]}      [description]
 */
function tescoStatusTable(data,path)
{
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn)
    {
        if (key != "tableName" && key!="tableID" && key!="po_file" && key!="pi_required" && key!="uk_style_ref" && key!="ce_style_ref")
        {
        	th += "<th style='text-align: center;'>" + key + "</th>";
        }
    }
    $.each(data, function (index, value)
    {
        var action_status = false;
        tr += '<tr class="trClass" id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id")
            {
                
                if (key =="PO Number") 
                {
                	if (value[key]== "" || value[key]==null)
	                {
	                	tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" placeholder="Enter PO" maxlength="11"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"'+value.tableID+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
	                }
	                else
	                {
                		tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="tesco-po" id="tesco-po" class="form-control input-sm tesco-po" value="'+value[key]+'"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default tesco-po-submit" onclick=tescoSetPO(this,"'+value.tableID+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
	                }
                }
                else if (key == "PI Status")
                {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                       if (str=="PI Approved"){
                            action_status = true;
                        }
                       $status=str.replace(/ /g, "");
                       $status_text='<span class="pi_class label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }
                else if (key == "Artwork Status") {
                    var str=value[key] ;
                    $status=str.replace(/ /g, "");
                    
                    tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                   
                }
                else if (key == "PO Status")
                {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $status=str.replace(/ /g, "");
                        $status_text='<span class="po_class label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }
                else if (key =="StyleNumber")
                {
                    var zone = value.Zone
                    var style_no = "";
                    if (zone=="CE")
                    {
                        style_no = value.ce_style_ref
                        tr += '<td align="center">'+style_no+'</td>';
                    }
                    else
                    {
                        style_no = value.uk_style_ref
                        tr += '<td align="center">'+value.uk_style_ref+'</td>';
                    }
                }
                else if (key == "Actions")
                {
                   var po_file     = value['po_file'];
                   var pi_required = value['pi_required'];
                   if (pi_required==1)
                   {
                        tr += '<td align="center"><a href="javascript:void(0)" onclick=genarateTescoPI(this,"'+value.tableID+'","'+value.tableName+'","'+action_status+'","'+style_no+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" onclick=tescoPOUpload(this,"'+value.tableID+'","'+po_file+'","'+value.tableName+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order"></i></a></td>';
                   }
                   else
                   {
                        tr += '<td align="center"><a href="javascript:void(0)" style="margin-left:36px" onclick=tescoPOUpload(this,"'+value.tableID+'","'+po_file+'","'+value.tableName+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order"></i></a></td>';
                   }
                   
                }
                
                else if (key != "tableName" && key!="tableID" && key!="po_file" && key!="pi_required" && key!="uk_style_ref" && key!="ce_style_ref")
                {
                	if (value[key]== "" || value[key]==null)
                	{
                		tr += '<td> - </td>';
                	}
                	else
                	{	
                		tr += '<td>' + value[key] + '</td>';
                	}
                }                
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-tesco-orders" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}
/**
 * [tescoSetPO description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoSetPO(obj,id,tableName)
{
   $('.tesco-po').css('border-color', '');
   var po_number  = $(obj).closest('tr').find('.tesco-po').val();
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   if (po_number!="")
   {
    $.ajax({
        url: base_url + "/admin/tesco/order-management/set-po-number",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po_number:po_number,
            tableName:tableName,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('#snackbar').css('background-color','#00b6ff');
               $('.snackbar_span').html('Purchase Order Number added Successfully');
               $(obj).closest('tr').find('.tesco-po').val(po_number);
               tescoOrderStatus() 
               showSnackbar();
               if (po_number==""){
                 $(obj).closest('tr').find('.tesco-po').attr('disabled');
                 $(obj).closest('tr').find('.tesco-po').attr('placeholder', "Enter PO");
               }   
                         
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
   }
   else
   {
	   $(obj).closest('tr').find('.tesco-po').focus();
	   $(obj).closest('tr').find('.tesco-po').css('border-color', '#F44336');
	   $('.snackbar_span').html('Please a valid PO Number');
       $('#snackbar').css('background-color','#F44336');
       showSnackbar();
   }
}	
/**
 * [tescoPOUpload description]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
function tescoPOUpload(obj,id,po_file,tableName)
{
    if (po_file!= "null")
    {
        $('#po_upload').addClass('hidden')
        $('.upload-po').addClass('hidden')
        $('#po_file').removeClass('hidden')
    }
    else
    {
        $('#po_upload').removeClass('hidden')
        $('.upload-po').removeClass('hidden')
        $('#po_file').addClass('hidden')
    }
    var po_link = base_url+po_file
    $('#po_link').attr("href",po_link);
    $('#rowID').val(id);
    $('#tableName').val(tableName);
    $('#po-file-upload').modal('show')
}

$(".update-po-file").click(function () {
    $('#po_upload').removeClass('hidden')
    $('.upload-po').removeClass('hidden')
});

/**
 * call the method for getting all the details for profoma invoice generation
 * @return {[type]} [description]
 */
function genarateTescoPI(obj,id,tableName,pi_status,style_no)
{
    $('#uvc_number').text(style_no)
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    var tr = "";
    $.ajax({
        url: base_url + "/admin/tesco/order-management/show-tesco-pi",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: tableName,
            buyer_id :buyer_id,
            id:id
        },
        beforeSend: function (){
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               console.log(data.data)
               if ((jQuery.isEmptyObject(data.data.company_details)) || (jQuery.isEmptyObject(data.data.bank_details)) || (jQuery.isEmptyObject(data.data.ship_to)) || (jQuery.isEmptyObject(data.data.bill_to)))
               {
                    $('.snackbar_span').html('Something went wrong');
                    $('#snackbar').css('background-color','#F44336');
                    showSnackbar();
               }
               else
               {
                    $('#company_details').html('<h3><strong>'+data.data.company_details.name+'</strong></h3>'+data.data.company_details.address+'<br/>'+data.data.company_details.phone+'<br/><strong>GSTN :'+data.data.company_details.phone+'</strong>')
                    $('#bill_to').html('<h4>Billing Address:</h4>'+data.data.bank_details.company_name+'<p>'+data.data.bill_to.address+'</br> GSTN :'+data.data.bank_details.gstin_number+ '</p>')
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
                            if (value.size == "")
                            {
                                size = "-" ;
                            }
                            else
                            {
                                size = value.size ;
                            }
                            tr += '<tr><td>'+ ++i +'</td><td>'+value.material_name+'</td><td>'+size+'</td><td>'+value.style+'</td><td>'+value.quantity+'</td><td>'+value.unit_price+'</td><td>'+value.quantity*value.unit_price+'</td></tr>'
                            totalqnty+=value.quantity;
                            subtotal+=value.quantity*value.unit_price;

                        });
                        var taxamnt= subtotal*(tax/100);
                        var billamnt = subtotal+taxamnt;
                        tr += '</tbody></table></div><hr><div><h4 class="text-right"> Total Quantity : '+totalqnty+' </h4></div><hr><div><h4 class="text-right"> Sub-Total : ₹ '+subtotal.toFixed(2)+' </h4></div><hr/><div class="text-right">'
                        if (data.data.bank_details.place_of_supply==1)
                        {
                           tr += '<h4> CGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4><h4> SGST : '+tax/2+'% (<span>'+(taxamnt/2).toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+numToString(billamnt)+' only</p></div><hr /></div>'
                        }
                        else if (data.data.bank_details.place_of_supply==2)
                        {
                            tr += '<h4> IGST : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹ '+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+numToString(billamnt)+' only</p></div><hr /></div>'
                        }
                        else
                        {
                            tr += '<h4> Taxes : '+tax+'% (<span>'+taxamnt.toFixed(2)+'</span>)</h4></div><hr><div class="text-right"><h4> Bill Amount : ₹'+billamnt.toFixed(2)+' </h4><p class="text-uppercase">'+numToString(billamnt)+' only</p></div><hr /></div>'
                        }
                        $('#item_details').html(tr)
                        if (pi_status=="true")
                        {
                            $('.pi_actions').html('<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                        else
                        {
                            $('.pi_actions').html('<button class="btn btn-success btn-xs" onclick=tescoApprovePI("'+id+'","'+tableName+'","tesco")>Approve</button><button class="btn btn-danger btn-xs" onclick=tescoRejectPI("'+id+'","'+tableName+'","tesco")>Reject</button><button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" aria-label="Close">Close</button>')
                        }
                        var logo = base_url+data.data.company_details.logo
                        $('#company-logo').attr('src',logo);
                        $('#profoma-invoice').modal("show")
                    }
                    else
                    {
                        $('.snackbar_span').html('Price Details are Missing !!');
                        $('#snackbar').css('background-color','#F44336');
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
function tescoApprovePI(id,table_name)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer_id = localStorage.getItem("buyer_id");
    $.ajax({
        url: base_url + "/admin/orchestra/approve-tesco-pi",
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
               $('.snackbar_span').html('PI Approved Successfully');
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               tescoOrderStatus() 
               showSnackbar();

            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
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
        url: base_url + "/admin/orchestra/reject-tesco-pi",
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
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               tescoOrderStatus() 
               showSnackbar();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong!');
               $('#snackbar').css('background-color','#F44336');
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