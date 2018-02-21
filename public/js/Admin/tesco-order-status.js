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
    $.each(data, function (index, value){

        tr += '<tr class="trClass" id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id")
            {
                // if (key =="StyleNumber")
                // {
                //     var zone = value.Zone
                //     if (zone=="CE")
                //     {
                //         tr += '<td align="center">'+value.ce_style_ref+'</td>';
                //     }
                //     else{
                //          tr += '<td align="center">'+value.uk_style_ref+'</td>';
                //     }
                // }
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
                else if (key == "PO Status")
                {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $status=str.replace(/ /g, "");
                        $status_text='<span class="label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }
                else if (key == "Actions")
                {
                   var po_file = value['po_file'];
                   tr += '<td align="center"><a href="#" style="margin-left:36px" onclick=tescoPOUpload(this,"'+value.tableID+'","'+po_file+'","'+value.tableName+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order"></i></a></td>';
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
               $(obj).closest('tr').find('.tesco-po').val(po_number);
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
        $('#po_file').removeClass('hidden')
    }
    else
    {
        $('#po_upload').removeClass('hidden')
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
});