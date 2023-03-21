//Global Variable
var td_row = "";

/**
 * [method append data to orchestraStatusTable ]
 * @param  {[type]} data [description]
 * @param  {[type]} path [description]
 * @return {[type]}      [description]
 */
function orchestraStatusTable(data, path) {
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn) {
        if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uvc" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty" && key != "color_code"  && key != "created_at" && key != "template_file") {
            if (key == "TemplateName") {
                th += "<th style='text-align: center;'>Template Name</th>";
            } else if (key == "BuyerPONo") {
                th += "<th style='text-align: center;'>BuyerPO No</th>";
            } else if (key == "ArtworkType") {
                th += "<th style='display:none'>Artwork Type</th>";
            } else if (key == "Artwork Status") {
                //New changes start
                th += "<th>Order Status</th><th>Artwork</th><th>Artwork Status</th>";
                //New changes end
            } else if (key == "SupplierName") {
                th += "<th style='text-align: center;'>Supplier Name</th>";
            } else if (key == "OrderDate") {
                th += "<th style='text-align: center;'>Order Date</th>";
            } else if (key == "DeliveryDate") {
                th += "<th style='text-align: center;'>Delivery Date</th>";
            } else {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }
        }

    }
    $.each(data, function (index, value) {
        var action_status = false;
        tr += '<tr class="trClass" id="' + value.tableName + '_' + value.tableID + '">';
        for (var key in columnsIn) {
            if (key != "id") {

                if (key == "PO Number" && value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                    var po_file = value['po_file'];
                    let customer_po_number = value[key] === null ? ' - ' : value[key];
                    //New changes start
                    tr += '<td class="text-center">'+customer_po_number+'</td>';
                    //New changes end
                } else if (key == "OrderDate") {
                    if (value['is_order_count_empty'] == 0) {
                        var orderDate = value[key];
                        tr += '<td align="center">' + orderDate + '</td>';
                    } else {
                        tr += '<td align="center"> - </td>';
                    }


                } else if (key == "PI Status") {
                    //New changes start
                    var str = value[key];

                    if (value['pi_required'] == 1 && value['is_order_count_empty'] == 0) {
                        if (str === 'PI Approved') {
                            action_status = true;
                            status = str.replace(/ /g, "");
                            status_text = '<span class="pi_class label label-' + status + '">' + value[key] + '</span>';
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '","'+value.BuyerPONo+'")>'+status_text+'</a></td>';	
                        }
                        else if (str === 'PI Rejected') {
                            status = str.replace(/ /g, "");
                            status_text = '<span class="pi_class label label-' + status + '">' + value[key] + '</span>';
                            tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '","'+value.BuyerPONo+'")>'+status_text+'</a></td>';	
                        } else {
                            if (value['Artwork Status'] === 'Order Placed' || value['Artwork Status'] === 'Order Approved') {
                                status_text = '<span class="pi_class label label-PIApprovalPending"> PI Approval Pending </span>';
                                tr += '<td align="center"><a class="pi_generation" href="javascript:void(0)" onclick=genarateOrchetraPI("' + value.tableID + '","' + value.tableName + '","' + value.UVC + '","' + action_status + '","'+value.BuyerPONo+'")>'+status_text+'</a></td>';	
                            } else {
                                tr += '<td align="center"><span class="pi_class label label-default"> PI Pending </span></td>';
                            }
                        }
                    } else {
                        tr += '<td align="center"> - </td>';
                    }
                    //New changes end
                } else if (key == "Artwork Status") {
                    if (value['is_order_count_empty'] == 0) {
                        if(value[key] == "Buyer Approval Pending")
                        {
                            var str = value[key];
                            $status = str.replace(/ /g, "");
    
                            tr += '<td align="center"><span class="label label-OrderPlaced">Order Placed</span></td>';
                        }
                        else
                        {
                            var str = value[key];
                            $status = str.replace(/ /g, "");
    
                            tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                        }
                        
                    } else {
                        tr += '<td align="center"><span class="label label-CreateOrder">Create Order</span></td>';
                    }

                    var temp_file = value.template_file.split(',');
                    
                    if(value.ArtworkType == "Solid Carton Stickers")
                    {
                        var print_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",0,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg3","os","'+value.tableID+'","'+value.tableName+'","print")'

                        var proof_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",0,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg3","os","'+value.tableID+'","'+value.tableName+'","proof")'
                    }
                    else if(value.ArtworkType == "Solid Poly Bag Stickers")
                    {
                        var print_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",0,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg4","os","'+value.tableID+'","'+value.tableName+'","print")'

                        var proof_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",0,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg4","os","'+value.tableID+'","'+value.tableName+'","proof")'
                    }
                    else if(value.ArtworkType == "Standard Carton Stickers")
                    {
                        var print_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",1,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg3","os","'+value.tableID+'","'+value.tableName+'","print")'

                        var proof_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",1,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg3","os","'+value.tableID+'","'+value.tableName+'","proof")'
                    }
                    else if(value.ArtworkType == "Standard Poly Bag Stickers")
                    {
                        var print_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",1,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg4","os","'+value.tableID+'","'+value.tableName+'","print")'

                        var proof_button = 'printDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'",1,"'+value.UVC+'","'+value.BuyerPONo+'","admin","svg4","os","'+value.tableID+'","'+value.tableName+'","proof")'
                    }
                    else if(value.ArtworkType == "WashCareLabel")
                    {
                        var print_button = 'printWashDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_back_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_middle_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_static_modal"+'","'+value.UVC+'","'+value.BuyerPONo+'","admin","'+value.tableID+'","'+value.tableName+'","print","os","")'
                        

                        var proof_button = 'printWashDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_back_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_middle_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_static_modal"+'","'+value.UVC+'","'+value.BuyerPONo+'","admin","'+value.tableID+'","'+value.tableName+'","proof","os","")'
                    }
                    else if(value.ArtworkType == "Hang Tag")
                    {
                        var print_button = 'printHangDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_back_modal"+'","'+value.UVC+'","'+value.BuyerPONo+'","admin","'+value.tableID+'","'+value.tableName+'","print")'
                        

                        var proof_button = 'printHangDivOs("'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_modal"+'","'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+"_back_modal"+'","'+value.UVC+'","'+value.BuyerPONo+'","admin","'+value.tableID+'","'+value.tableName+'","proof")'
                    }

                    //New changes start
                    if((value.ArtworkType == "Special Tag") || (value.ArtworkType == "Main Label") || (value.ArtworkType == "Size Label") || (value.ArtworkType == "Heat Transfer"))
                    {
                        tr += '<td> - </td>'
                    }
                    else if(value.ArtworkType == "WashCareLabel")
                    {
                        tr += '<td align="center"><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_modal" class="center-block tesco_svg" data="' + temp_file[0]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_back_modal" class="center-block tesco_svg" data="' + temp_file[1]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_middle_modal" class="center-block tesco_svg" data="' + temp_file[2]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_static_modal" class="center-block tesco_svg" data="' + temp_file[3]+'" type="image/svg+xml"></object><button data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value['Artwork Status'] + '" class="" href="javascript:void(0)" onclick='+print_button+'><span style="cursor:pointer" class="fa fa-download"></span></button></td>';
                    }
                    else if(value.ArtworkType == "Hang Tag")
                    {
                        tr += '<td align="center"><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_modal" class="center-block tesco_svg" data="' + temp_file[0]+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_back_modal" class="center-block tesco_svg" data="' + temp_file[1]+'" type="image/svg+xml"></object><button data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value['Artwork Status'] + '" class="" href="javascript:void(0)" onclick='+print_button+'><span style="cursor:pointer" class="fa fa-download"></span></button></td>';
                    }
                    else
                    {
                        tr += '<td align="center"><object style="opacity:0;width:0px;height:0px" id="'+value.TemplateName.replace(/ /g, "")+"_"+value.template_id+'_modal" class="center-block tesco_svg" data="' + temp_file[0]+'" type="image/svg+xml"></object><button data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value['Artwork Status'] + '" class="" href="javascript:void(0)" onclick='+print_button+'><span style="cursor:pointer" class="fa fa-download"></span></button></td>';
                    }
                    

                    if (value['Artwork Status'] == "Order Approved") {
                        var str = value[key];
                        $status = str.replace(/ /g, "");

                        tr += '<td align="center"><span class="label label-OrderApproved">Artwork Approved</span></td>';
                    } else if (value['Artwork Status'] == "Order Placed"){
                        tr += '<td align="center"><span class="label label-POPending">Buyer Approval Pending</span></td>';
                    } else if (value['Artwork Status'] == "Buyer Approval Pending"){
                        tr += '<td align="center"><span class="label label-POPending">Buyer Approval Pending</span></td>';
                    }else {
                        tr += '<td align="center"> - </td>';
                    }

                    //New changes end

                } else if (key == "PO Status") {
                    var str = value[key];
                    var po_file = value['po_file'];
                    var status_text = " - "
                    if (value['po_required'] == 1 && value['is_order_count_empty'] == 0) {
                        if (value['PO Status'] === 'PO Uploaded') {
                            status = str.replace(/ /g, "");
                            status_text = '<span class="po_class label label-' + status + '">' + value['PO Status'] + '</span>';
                            tr += '<td align="center" class="po_status"><a href="javascript:void(0)" class="po-style" onclick=orchestraPOUploaded(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '","'+value.UVC+'","'+value['PO Number']+'","admin") > '+status_text+' </a></td>';
                        } else {
                            if (value['Artwork Status'] === 'Order Placed' || value['Artwork Status'] === 'Order Approved' || value['Artwork Status'] === 'Buyer Approval Pending') {
                                status_text = '<span class="po_class label label-POPending"> PO Pending </span>';
                                tr += '<td align="center" class="po_status"><a href="javascript:void(0)" class="po-style" onclick=orchestraPOUpload(this,"' + value.tableID + '","' + po_file + '","' + value.tableName + '","'+value.UVC+'","admin") > '+status_text+' </a></td>';
                            } else {
                                tr += '<td align="center class="po_status"><span class="po_class label label-default"> PO Pending </span></td>';
                            }
                        }
                    } else {
                        tr += '<td align="center class="po_status"> - </td>';
                    }

                    //tr += '<td align="center class="po_status">' + status_text + '</td>';

                } else if (key == "UVC") {
                    var order_flag = value.order_flag;
                    var span_style = '';
                    if (order_flag == "reOrder") {
                        span_style = '<span title="Additional Quantity" class="batch-additional">A</span>'
                    }
                    if (order_flag == "repeatOrder") {
                        span_style = '<span title="Repeat Order" class="batch-reorder">R</span>'
                    }
                    tr += '<td align="center" data-id="' + value.tableID + '" data-artwork="' + value.ArtworkType + '" data-season="' + value.Season + '" data-uvc="' + value.UVC + '" data-table="' + value.tableName + '" data-buyer="' + value.BuyerPONo + '" data-color_code="' + value.color_code + '" data-template="' + value.TemplateName.replace(/ /g, "") + '"><a class="" href="javascript:void(0)" onclick="updateArtworkOrc(this)">' + span_style + '' + value.UVC + '</a></td>';
                } else if (key == "TemplateName") {
					if(value["color_code"] != "" && value["color_code"] != null)
					{
						tr += '<td align="center"> '+ value.color_code +' '+value.TemplateName+' </td>';
					}
					else
					{
						tr += '<td align="center"> '+value.TemplateName+' </td>';
					}
                } else if (key == "Print Proof") {

                    if((value.ArtworkType == "Special Tag") || (value.ArtworkType == "Main Label") || (value.ArtworkType == "Size Label") || (value.ArtworkType == "Heat Transfer"))
                    {
                        tr += '<td> - </td>'
                    }
                    else
                    {
                        //New changes start
                        tr += '<td align="center"><button data-id="' + value.tableID + '" data-table1="' + value.tableName + '" data-template="' + value.TemplateName.replace(/ /g, "") + '" data-orderDate="' + value.OrderDate + '" data-template_id="' + value.template_id + '" data-order_status="' + value['Artwork Status'] + '" class="" href="javascript:void(0)" onclick='+proof_button+'><span style="cursor:pointer" class="fa fa-download"></span></button></td>';
                        //New changes end
                    }
                } else if (key == "Actions") {
                    //New changes start
                    var po_file = value['po_file'];
                    let customer_po_number = value['PO Number'] === null ? '' : value['PO Number'];
                    tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" onkeypress="return AvoidSpace(event)" name="orchestra-po" id="orchestra-po-' + value.tableID + '" class="form-control input-sm orchestra-po" placeholder="Enter PO" maxlength="11" value="' + customer_po_number + '"><span class="input-group-btn po-span"><button type="submit" class="btn btn-sm btn-default orchestra-po-submit" onclick=orchestraSetPO(this,"' + value.tableID + '","' + value.tableName + '","' + po_file + '")><span class="fa fa-check"></span></button></span></div></div></td>';
                    //New changes end
                } else if (key == "ArtworkType") {
					tr += '<td style="display:none">' + value[key] + '</td>';
                } else if (key == "DeliveryDate") {

                    if (value['is_order_count_empty'] == 0) {
                        if (value[key] == null) {
                            tr += '<td><span style=display:none;>' + value.created_at + '</span><input class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px; border-color: rgb(244, 67, 54);" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '")  readonly></td>';
                        } else {

                            tr += '<td><span style=display:none;>' + value.created_at + '</span><input value="' + value[key] + '"class="form-control input-sm datepicker_recurring_start" data-id="' + value.delivery_day_count + '" style="width: 107px;" placeholder="Delivery Date" onchange=updateDeliveryDate(this,"' + value.tableID + '","' + value.tableName + '") readonly></td>';
                        }
                    } else {
                        tr += '<td><span style=display:none;>' + value.created_at + '</span> - </td>';
                    }
                } else if (key != "tableName" && key != "tableID" && key != "po_file" && key != "pi_required" && key != "po_required" && key != "uk_style_ref" && key != "ce_style_ref" && key != "template_id" && key != "customer_id" && key != "order_flag" && key != "delivery_day_count" && key != "is_order_count_empty" && key != "color_code" && key != "created_at" && key != "template_file") {
                    if (value[key] == "" || value[key] == null || value[key] == "null") {
                        tr += '<td> - </td>';
                    } else {
                        tr += '<td>' + value[key] + '</td>';
                    }
                } 
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-orchestra-orders" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


/**
 * [orchestraSetPO for updating orchestra customer po]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
const orchestraSetPO = (obj, id, tableName, po_file) => {
	$(obj).attr('disabled','disabled')
    $('.orchestra-po').css('border-color', '');
    var po_number = $(obj).closest('tr').find('.orchestra-po').val();
    
    var buyer_id = localStorage.getItem("buyer_id");
    if (po_number != "") {
        $.ajax({
            url: base_url + "/admin/orchestra/order-management/set-po-number",
            type: "Post",
            dataType: "json",
            data: {
                _token: csrf_token,
                po_number: po_number,
                tableName: tableName,
                id: id,
                buyer_id: buyer_id
            },
            beforeSend: function () {
                $(".loader").show();
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.message);
                    $ponumber = $(obj).closest('tr').find('.orchestra-po').val();
                    if ($(obj).closest('tr').find('.po_class').text() == "PO Pending" && po_file != "null") {
                        $(obj).closest('tr').find('.po_class').html("PO Uploaded")
                        $(obj).closest('tr').find('.po_class').removeClass("label-POPending").addClass('label-POUploaded');
                    }
                    showSnackbar();
                    if (po_number == "") {
                        $(obj).closest('tr').find('.orchestra-po').attr('disabled');
                        $(obj).closest('tr').find('.orchestra-po').attr('placeholder', "Enter PO");
                    }
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                },
                403: function (data) {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                },
                400: function () {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
					setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
                }
            },
            error: function (data) {},
            complete: function () {
                $(".loader").hide();
            }
        })
    } else {
        $(obj).closest('tr').find('.orchestra-po').focus();
        $(obj).closest('tr').find('.orchestra-po').css('border-color', '#F44336');
        $('.snackbar_span').html('Please enter a valid PO number');
        showSnackbar();
		setTimeout(function(){ $(obj).removeAttr('disabled') }, 3000);
    }
}

$(".update-po-file").click(function () {
    $('#po_upload').removeClass('hidden')
    $('.upload-po').removeClass('hidden')
});


function AvoidSpace(event) {
    var k = event ? event.which : window.event.keyCode;
    if (k == 32) return false;
}

function updateArtworkOrc(obj) {

    var id = $(obj).parent().attr('data-id');
    var table_name = $(obj).parent().attr('data-table');
    var TemplateName = $(obj).parent().attr('data-template');
    var season = $(obj).parent().attr('data-season');
    var uvc = $(obj).parent().attr('data-uvc');
    var artwork = $(obj).parent().attr('data-artwork');
    var color_code = $(obj).parent().attr('data-color_code');
    var buyer = $(obj).parent().attr('data-buyer');
	var at = artwork.replace(/ /g, '');
    $.ajax({
        url: base_url + "/admin/orchestra/edit-artwork",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            id: id,
            uvc: uvc,
            season: season,
            color_code: color_code,
            buyer: buyer,
			at:at
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode: {
            /** [Show response status based on HTTP status code] */
            200: function (data) {
				$('.edit-orch-order-panel').html('<input type="hidden" value="' + data.order_details[0].UVC + '" id="uvc_id"><input type="hidden" value="' + data.order_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.order_details[0].Season_Code + '" id="season_code"><input type="hidden" value="'+data.customers[0].id+'" id="customer_id">');
				if(at == "HangTag")
				{
					hangtagmodalOs(data)
					
					if (data.hang_erp_details != null) {
							if (data.hang_erp_details.status_name !== 'Hold For JC Queue' && data.hang_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablehang').prop("disabled", true);
								$('.extra_percentagehang').prop("disabled", true);
								$('#save_hang_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.hang_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_hang_tag_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panel-heat').addClass('active');
                    $('.right-panel-heat').addClass('active');
                    $('.left-panel-heat').show();
					
				}
				else if(at == "SpecialTag")
				{
					specialtagsOs(data)
					
					if (data.special_erp_details != null) {
							if (data.special_erp_details.status_name !== 'Hold For JC Queue' && data.special_erp_details.status_name !== 'Moved To ERP') {
						    	$('.special_qty').prop("disabled", true);
								$('#save_special_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.special_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_special_tag_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "MainLabel")
				{
					mainlabelsOs(data)
					
					if (data.main_erp_details != null) {
							if (data.main_erp_details.status_name !== 'Hold For JC Queue' && data.main_erp_details.status_name !== 'Moved To ERP') {
								$('.main_label_qty').prop("disabled", true);
								$('#save_main_label_button').remove()
								$('#edit_orc_ml_order_btn').remove()
								$('#toggle_orch_edit_permission').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.main_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_main_label_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "WashCareLabel")
				{
					washcarelabelsOs(data)
                    
					$('.edit-orch-order-panel').append('<input type="hidden" value="' + data.orchestra_wash_care_label_orders[0].po + '" id="po_select">');
					if (data.wash_erp_details != null) {
							if (data.wash_erp_details.status_name !== 'Hold For JC Queue' && data.wash_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablewcl').prop("disabled", true);
								$('.extra_percentagewash').prop("disabled", true);
								$('#save_wash_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.wash_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_wash_care_label_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panel-heat').addClass('active');
                    $('.right-panel-heat').addClass('active');
                    $('.left-panel-heat').show();
				}
				else if(at == "SizeLabel")
				{
					sizelabelsOs(data)
					
					if (data.size_erp_details != null) {
							if (data.size_erp_details.status_name !== 'Hold For JC Queue' && data.size_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tablesize').prop("disabled", true);
								$('.extra_percentagesize').prop("disabled", true);
								$('#save_size_label_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.size_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_size_label_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "HeatTransfer")
				{
					heatTransferOs(data)
					
					if (data.heat_erp_details != null) {
							if (data.heat_erp_details.status_name !== 'Hold For JC Queue' && data.heat_erp_details.status_name !== 'Moved To ERP') {
								$('.tot_qty_tableheat').prop("disabled", true);
								$('.extra_percentageheat').prop("disabled", true);
								$('#save_heat_transfer_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.heat_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_heat_transfer_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "SolidCartonStickers")
				{
					SolidCartonStickersOs(data,"admin")
					
					if (data.solid_carton_erp_details != null) {
							if (data.solid_carton_erp_details.status_name !== 'Hold For JC Queue' && data.solid_carton_erp_details.status_name !== 'Moved To ERP') {
                                $('.tot_qty_table_carton').prop("disabled", true);
                                $('#age').prop("disabled", true);
                                $('#modal_height').prop("disabled", true);
                                $('#width').prop("disabled", true);
                                $('#save_solid_carton_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.solid_carton_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_solid_carton_sticker_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "SolidPolyBagStickers")
				{
					SolidPolyBagStickersOs(data,"admin")
					
					if (data.solid_poly_erp_details != null) {
							if (data.solid_poly_erp_details.status_name !== 'Hold For JC Queue' && data.solid_poly_erp_details.status_name !== 'Moved To ERP') {
                                $('.tot_qty_table_poly').prop("disabled", true);
                                $('#age').prop("disabled", true);
                                $('#save_solid_poly_bag_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.solid_poly_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_solid_poly_bag_sticker_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }

                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "StandardCartonStickers")
				{
					StandardCartonStickersOs(data,"admin")
					
					if (data.standard_carton_erp_details != null) {
							if (data.standard_carton_erp_details.status_name !== 'Hold For JC Queue' && data.standard_carton_erp_details.status_name !== 'Moved To ERP') {
                                $('.standard_carton_qty').prop("disabled", true);
                                $('#age').prop("disabled", true);
                                $('#modal_height').prop("disabled", true);
                                $('#width').prop("disabled", true);
                                $('#needed_modal').prop("disabled", true);
                                $('#total_polybag').prop("disabled", true);

                                $('#save_standard_carton_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.standard_carton_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_standard_carton_sticker_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }
                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}
				else if(at == "StandardPolyBagStickers")
				{
					StandardPolyBagStickersOs(data,"admin")
					
					if (data.standard_poly_erp_details != null) {
							if (data.standard_poly_erp_details.status_name !== 'Hold For JC Queue' && data.standard_poly_erp_details.status_name !== 'Moved To ERP') {
                                $('.standard_poly_bag_qty').prop("disabled", true);
                                $('#age').prop("disabled", true);
                                $('#save_standard_poly_bag_button').remove()
								$('#error_msg_alert').html('ERP Order Status : <strong>' + data.standard_poly_erp_details.status_name + '</strong>. You are not allowed to edit this order.')
							}
					}

                    if (data.orchestra_standard_poly_bag_sticker_orders[0].edit_permission === 0) {
                        $('#toggle_orch_edit_permission').addClass('btn-success');
                        $('#toggle_orch_edit_permission').removeClass('btn-danger');
                        $('#toggle_orch_edit_permission').text('Enable Edit');
                    } else {
                        $('#toggle_orch_edit_permission').addClass('btn-danger');
                        $('#toggle_orch_edit_permission').removeClass('btn-success');
                        $('#toggle_orch_edit_permission').text('Disable Edit');
                    }
                    $('.left-panelos').addClass('active');
                    $('.right-panelos').addClass('active');
                    $('.left-panelos').show();
				}

				
            },
            400: function () {
                $('.snackbar_span').html('Something went wrong!');
                showSnackbar();
            }
        },
        error: function (data) {
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })

    

}

/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */

function barcode_cartonOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = 600;
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		svgDoc.getElementById("volume").innerHTML = parseFloat($('#parcelVolume').val()).toFixed(3);
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;

		
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			jsbarcodesolidcartonAOs(code, 'barcode1', svgDoc)
			jsbarcodesolidcartonBOs(code, 'barcode2', svgDoc)

	}, false);

}


/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_polybagOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");

            svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
            
            if($('#age option:selected').val() != 6)
            {
                svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
                svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
                svgDoc.getElementById("pmm").innerHTML = "";
            }
            else
            {
                svgDoc.getElementById("round").style.fill = "#FFFFFF"
                svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
                svgDoc.getElementById("pmm").innerHTML = "PMM";
            }
            
			
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			jsbarcodesolidpolyOs(code, 'barcode', svgDoc)
		
	}, false);

}

/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */
function barcode_std_cartonOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = 600;
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		svgDoc.getElementById("volume").innerHTML = parseFloat($('#parcelVolume').val()).toFixed(3);
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
			jsbarcodestdcartonAOs(code, 'barcode1', svgDoc)
			jsbarcodestdcartonBOs(code, 'barcode2', svgDoc)
			svgDoc.getElementById("total_polybags_per_carton").innerHTML = $('#total_polybag').val();
			svgDoc.getElementById("no_of_cartons").innerHTML = $('#needed_modal').val();
	}, false);
}
/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_std_polybagOs(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");

		svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;

		$td_col = "";
		$td_size = "";
		$total = 0;
		var index_arr=[];
		$('#standardpolybagform tbody tr').each(function () {

		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if ($(this).find('.size').text() != "") {
					index_arr.push($(this).find('.size').text())
					$td_size += "<td>" + $(this).find('.size').text() + "</td>";
					$td_col += "<td>" + $(this).find('.qty').text() + "</td>";
					$total += parseInt($(this).find('.qty').text());
			}
		}



		})
        $tbl = '<tr><td>Size:</td>' + $td_size + '<td>Total</td></tr><tr> <td>'+data.orchestra_parcel_details[0].colour_Code+'</td>' + $td_col + '<td>' + $total + '</td></tr>';
        
        if($('#age option:selected').val() != 6)
        {
            svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
            svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
            svgDoc.getElementById("pmm").innerHTML = "";
        }
        else
        {
            svgDoc.getElementById("round").style.fill = "#FFFFFF"
            svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
            svgDoc.getElementById("pmm").innerHTML = "PMM";
        }

		
		var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		svgDoc.getElementById("cartontable").innerHTML = $tbl;
		jsbarcodestdpolyOs(code, 'barcode', svgDoc)
		
		
	}, false);

}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonAOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 347,
		fontSize: 50
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonBOs(code, id, svgDoc) {	
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.5,
		height: 345,
		fontSize: 50
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidpolyOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.7,
		height: 370,
		fontSize: 50
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '55.50px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonAOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 355,
		fontSize: 50
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '59.80px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonBOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.43,
		height: 349,
		fontSize: 50
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '55px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdpolyOs(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width:6.595,
		height: 350,
		fontSize: 60
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '64px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '12px');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}

function hangtagmodalOs(data) {
    var order_details = data.order_details;
    var hang_tag_orders = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (hang_tag_orders != "") {
       

        $template_front_id = hang_tag_orders[0].template_front_id;
        $exist_id = hang_tag_orders[0].id;
        
        var str=hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="";
		$save_button_class="";
		$ocn_number_hang = hang_tag_orders[0].ocn_number;
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
		$ocn_number_hang="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (hang_tag_orders != "") {

            $hang_tag_count = hang_tag_orders[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)" style="margin: 0 auto; width: 95px;"  type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors" style="font-size:10px"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (hang_tag_orders != "") {
            $hang_cm = hang_tag_orders[0].cm;
			$hang_in = hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		//<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm onesizecm" value="'+$hang_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>
		var sizebox='';
	}
	else
	{
		var sizebox="";
	}
    
    if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    $('.edit-orch-order-panel').append(`
                                    <form id="hangtagform" data-toggle="validator"><input type="hidden" value="` + $exist_id + `" id="exist_id">
                                    <div class="left-panel-heat col-md-7">
                                        <div class="div-max">
                                            <div class="col-md-5">
                                                <h4>Options</h4>
                                                <hr>
                                                <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                                        Number</label><input type="text" value="`+ $ocn_number_hang +`" id="ocn_number_hang" required
                                                        value="" class="form-control input-sm" placeholder="OCN Number">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                                        class="form_label">Select Style</label><label id="hang_template_error" class="hidden"
                                                        style="color:red;display: inherit;">Please Select the Template</label>
                                                    <div><input type="text" id="tree1_q" value="" class="form-control input-sm"
                                                            placeholder="Search for Awesomeness"> </div>
                                                    <div class="scroll" id="tree1"
                                                        style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div>
                                                    <div class="help-block with-errors"></div>
                                                </div>`+sizebox+`
                                            </div>
                                            <div class="col-md-7 hang-card">
                                                <h4>ArtWork</h4><span class="label label-`+$status+`"
                                                    style="top:13px;right:15px;padding:3px 10px;position: absolute;">`+$status_text+`</span>
                                                <hr>
                                                <div class="section-1 sections">
                                                    <label style="margin-top: -40px;position: absolute;margin-right: 120px;right: 0;">Hang Tag Print Date : `+data.order_details[0].start_print_date+`</label>
                                                    <div class="sample-artwork" style="text-align:center;margin-right: 190px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right-panel-heat col-md-5">
                                        <div class="div-max scroll">
                                            <h4>Quantity Info for Hang Tag</h4>
                                            <hr>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table">
                                                    <thead>
                                                        <tr>
                                                            <th>UVC TCO#</th>
                                                            <th>Size</th>
                                                            <th>Barcode</th>
                                                            <th>Qty</th>
                                                            <th style="display: flex;justify-content: center;"><label style="float: left; line-height: 2">Extra %:</label> <input class="form-control input-sm extra_percentagehang" type="text" onkeypress="return checkNumbersOnly(event)" style="width:60px; float: left; margin-left: 5px;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>` + tr + `<tr class="strong">
                                                            <td class="text-right" colspan="3">Total:</td>
                                                            <td>` + ttots_sum + `</td>
                                                            <td class="text-center tot_qty_tablehang_fin">` + ttots + `</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div><span id="error_msg_alert" style="color:red;"></span><button
                                                class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                                                id="save_hang_button" style="margin-left:10px">Submit</button> <button
                                                class="btn btn-warning  pull-right input-sm" id="edit_orc_ht_order_btn"
                                                style="width: 55px;margin-left: 10px;" onclick="editOrchestraHTOrder()"
                                                type="button">Edit</button><button class="btn btn-success  pull-right input-sm"
                                                id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button"
                                                onclick=toggleOrchArtworkEditPermission("orchestra_hang_tag_orders","`+
                                                hang_tag_orders[0].id +`")>Enable Edit</button><button
                                                class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button>
                                        </div>
                                    </div>
                                </form>
                                `);


//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
            }
        })
        $('#hangtagform .tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
    });
	
	 $('.extra_percentagehang').keyup(function () {
		
        
        var tot_qty_tablehang_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablehang').val(tot_qty);
                if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                    tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablehang').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text("0");
        }
		$('#hangtagform').validator('validate')

    })
	
    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel-heat").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panel-heat").removeClass('active');
        $("#hangtagform .right-panel-heat").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    })

    $('#tree1').jstree({
        'core': {
            'data': data.treefinalData
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
    });
    $('#expand').bind("click", function () {
        $('#tree1').jstree("open_all");
    });
    $('#collapse').bind("click", function () {
        $('#tree1').jstree("close_all");
    });
    var to = false;
    $('#tree1_q').keyup(function () {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function () {
            var v = $('#tree1_q').val();
            $('#tree1').jstree(true).search(v);
        }, 250);
    });
    //Show Template when tree loads 
    $("#tree1").on("loaded.jstree", function (e, data) {
		$('#tree1').jstree(true).select_node($template_front_id);
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')

                    var xhr = new XMLHttpRequest();
                    xhr.open('HEAD', data.treeviewData[0].template_file.replaceAll("svg", 'png'), false);
                    xhr.send();

                    if (xhr.status == "404") {
                        $('#hangtagform').find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                        $('#hangtagform').find('.sample-artwork').css('margin-right','0px')
                        $('#hangtagform').find('.sample-artwork').css('margin-top','230px')
                    }
                    else
                    {
                        $('#hangtagform').find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file.replaceAll("svg", 'png') + '" style="height: 450px;"><img src="' + data.treeviewData[1].template_file.replaceAll("svg", 'png') + '" style="position: absolute;display:inline;height: 450px;">')
                        $('#hangtagform').find('.sample-artwork').css('margin-right','190px')
                        $('#hangtagform').find('.sample-artwork').css('margin-top','85px')
                    }
					
					if (hang_tag_orders != "") {
                        $('#tree1 >ul > li').each( function() {
                            disable( 'tree1',this.id );        
                        })
                        $('.extra_percentagehang').attr('disabled','disabled')
                        $('.tot_qty_tablehang').attr('disabled','disabled')
                        $('#ocn_number_hang').attr('disabled','disabled')
                    }
                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });
    //Show Template when Click tree node 
    $('#tree1').on('activate_node.jstree', function (e, data) {
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')

                    var xhr = new XMLHttpRequest();
                    xhr.open('HEAD', data.treeviewData[0].template_file.replaceAll("svg", 'png'), false);
                    xhr.send();

                    if (xhr.status == "404") {
                        $('#hangtagform').find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                        $('#hangtagform').find('.sample-artwork').css('margin-right','0px')
                        $('#hangtagform').find('.sample-artwork').css('margin-top','230px')
                    } else {
                        $('#hangtagform').find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file.replaceAll("svg", 'png') + '" style="height: 450px;"><img src="' + data.treeviewData[1].template_file.replaceAll("svg", 'png') + '" style="position: absolute;display:inline;height: 450px;">')
                        $('#hangtagform').find('.sample-artwork').css('margin-right','190px')
                        $('#hangtagform').find('.sample-artwork').css('margin-top','85px')
                    }

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });
	
    $('#hangtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setHangTagStatus(hang_tag_orders[0].color_code,"admin","os")
        }
    })


    selectpicker()
    $('.template-select.bootstrap-select').on('changed.bs.select', function () {
        if ($('.template-select option:selected').val() != "") {
            $('.sample-artwork').html('<object id="svg1" data="' + $('.template-select option:selected').val() + '" style="width:233px;height:500px" type="image/svg+xml"></object>')
            var mySVG = document.getElementById("svg1");
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc = mySVG.contentDocument;
                var pri = data.order_details[0].PrixEurope.split(",")

                if (pri.length == 1) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = data.order_details[0].PrixEurope;
                    svgDoc.getElementById("price2").innerHTML = "";
                } else if (pri.length == 2) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = pri[0];
                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                }
                var pri1 = data.order_details[0].PrixClubEurope.toString().split(",")
                if (pri1.length == 1) {
                    svgDoc.getElementById("club_price1").innerHTML = data.order_details[0].PrixClubEurope;
                    svgDoc.getElementById("club_price2").innerHTML = "";
                } else if (pri1.length == 2) {
                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                }

            }, false);
            $('.sample-artwork').css('background', '#fff');
        } else {
            $('.sample-artwork').html('');
            $('.sample-artwork').css('background', '#eee');
        }

    });
    $("#hangtagform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_ht_order_btn").prop("disabled", false);
    $("#hangclose").prop("disabled", false);
}

editOrchestraHTOrder = () => {
    $("#hangtagform :input").prop("disabled", false);
    $("#save_hang_button").removeClass('disabled');
    $('#tree1 >ul > li').each( function() {	
        enable( "tree1",this.id );        	
    })
}

function specialtagsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_special_tag_orders != "") {

      
        $template_id = data.orchestra_special_tag_orders[0].template_id;
        $exist_id = data.orchestra_special_tag_orders[0].id;
        
        var str=data.orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
        $ocn_number_special = data.orchestra_special_tag_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $ocn_number_special = "";
    }
    var st="";
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$special_tag_count=data.orchestra_special_tag_orders[0].quantity;
	
    $('.edit-orch-order-panel').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_special" required value="'+$ocn_number_special+'" class="form-control input-sm" placeholder="OCN Number"><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="top:13px;right:15px;padding:3px 10px;position: absolute;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Special Tag Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Submit</button><button class="btn btn-warning  pull-right input-sm" id="edit_orc_st_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrchestraSTOrder()" type="button">Edit</button><button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_special_tag_orders","'+ data.orchestra_special_tag_orders[0].id +'")>Enable Edit</button> <button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  </form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#specialtagform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmespecial").click(function () {
        // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panelos").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panelos").removeClass('active');
        $("#specialtagform .right-panelos").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    })



    treestructurespecialOs(data.treeviewspecial, "tree2", $template_id);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus(data.orchestra_special_tag_orders[0].color_code,"admin","os")
        }
    })
    $("#specialtagform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_st_order_btn").prop("disabled", false);
    $("#specialclose").prop("disabled", false);
}

editOrchestraSTOrder = () => {
    $("#specialtagform :input").prop("disabled", false);
    $("#save_special_button").removeClass('disabled');
    $('#tree2 >ul > li').each( function() {
        enable( 'tree2',this.id );        
    }) 
}


function treestructurespecialOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }
                    
                    $('#'+tree+' >ul > li').each( function() {	
                        disable( tree,this.id );        	
                    })	

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
function heatTransferOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_heat_transfer_orders != "") {

      
        $template_id = data.orchestra_heat_transfer_orders[0].template_id;
        $exist_id = data.orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=data.orchestra_heat_transfer_orders[0].final_qty;
        
        var str=data.orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
        $ocn_number_heat = data.orchestra_heat_transfer_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $ocn_number_heat = "";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = data.orchestra_heat_transfer_orders[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });

    $('.edit-orch-order-panel').append('<form id="heat_transferform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_heat" required value="'+$ocn_number_heat+'" class="form-control input-sm" placeholder="OCN Number"><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="heat_transfer_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeht_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeht" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="top:13px;right:15px;padding:3px 10px;position: absolute;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentageheat" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tableheat_fin">' + ttots + '</td></tr></tbody></table></div><span id="error_msg_alert" style="color:red;"></span> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_heat_transfer_button" style="margin-left:10px">Submit</button> <button class="btn btn-warning  pull-right input-sm" id="edit_orc_he_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrchestraHEOrder()" type="button">Edit</button><button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_heat_transfer_orders","'+ data.orchestra_heat_transfer_orders[0].id +'")>Enable Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button></div> </div>  </form>');

	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
            }
        })
        $('#heat_transferform .tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
    });
	
	 $('.extra_percentageheat').keyup(function () {
		
        
        var tot_qty_tableheat_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tableheat').val(tot_qty);
                if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                    tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tableheat').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text("0");
        }

    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panelos").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panelos").removeClass('active');
        $("#heat_transferform .right-panelos").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    })



    treestructureheat_transferOs(data.treeviewheat_transfer, "treeht", $template_id);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.orchestra_heat_transfer_orders[0].color_code,"admin","os")
        }
    })
    $("#heat_transferform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_he_order_btn").prop("disabled", false);
    $("#heat_transferclose").prop("disabled", false);
}

editOrchestraHEOrder = () => {
    $("#heat_transferform :input").prop("disabled", false);
    $("#save_heat_transfer_button").removeClass('disabled');
    $('#treeht >ul > li').each( function() {
        enable( 'treeht',this.id );        
    }) 
}

function treestructureheat_transferOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }
                    
                    $('#'+tree+' >ul > li').each( function() {	
                        disable( tree,this.id );        	
                    })	

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
function sizelabelsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_size_label_orders != "") {

      
        $template_id = data.orchestra_size_label_orders[0].template_id;
        $exist_id = data.orchestra_size_label_orders[0].id;
        $size_label_count=data.orchestra_size_label_orders[0].final_qty;
        
        var str=data.orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
        $ocn_number_size = data.orchestra_size_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $ocn_number_size = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_size_label_orders != "") {
            $size_label_count = data.orchestra_size_label_orders[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });

    $('.edit-orch-order-panel').append('<form id="size_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_size" required value="'+$ocn_number_size+'" class="form-control input-sm" placeholder="OCN Number"><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="size_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treesl_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treesl" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="top:13px;right:15px;padding:3px 10px;position: absolute;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Size Label</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="size_label_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagesize" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablesize_fin">' + ttots + '</td></tr></tbody></table></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_size_label_button" style="margin-left:10px">Submit</button> <button class="btn btn-warning  pull-right input-sm" id="edit_orc_sl_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrchestraSLOrder()" type="button">Edit</button><button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_size_label_orders","'+ data.orchestra_size_label_orders[0].id +'")>Enable Edit</button> <button class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button></div> </div>  </form>');

	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
            }
        })
        $('#size_labelform .tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
    });

	 $('.extra_percentagesize').keyup(function () {
		
        
        var tot_qty_tablesize_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablesize').val(tot_qty);
                if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                    tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablesize').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text("0");
        }

    })
	
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panelos").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panelos").removeClass('active');
        $("#size_labelform .right-panelos").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    })



    treestructuresize_labelOs(data.treeviewsize_label, "treesl", $template_id,data.orchestra_size_label_orders);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus(data.orchestra_size_label_orders[0].color_code,"admin","os")
        }
    })
    $("#size_labelform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_sl_order_btn").prop("disabled", false);
    $("#size_labelclose").prop("disabled", false);
}

editOrchestraSLOrder = () => {
    $("#size_labelform :input").prop("disabled", false);
    $("#save_size_label_button").removeClass('disabled');
    $('#treesl >ul > li').each( function() {
        enable( 'treesl',this.id );        
    })    
}


/**
 * This Function is used to invoke Jstree For Size Labels.
 * 
 * @return tree
 */
function treestructuresize_labelOs(data, tree, template_id,orchestra_size_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }

                    $('#'+tree+' >ul > li').each( function() {	
                          disable( tree,this.id );        	
                    })	

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}

function mainlabelsOs(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_main_label_orders != "") {

      
        $template_id = data.orchestra_main_label_orders[0].template_id;
        $exist_id = data.orchestra_main_label_orders[0].id;
        $main_label_count=data.orchestra_main_label_orders[0].quantity;
        
        var str=data.orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
        $ocn_number_main = data.orchestra_main_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $main_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $ocn_number_main = "";
    }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.edit-orch-order-panel').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_main" required value="'+$ocn_number_main+'" class="form-control input-sm" placeholder="OCN Number"><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="top:13px;right:15px;padding:3px 10px;position: absolute;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Submit</button><button class="btn btn-warning  pull-right input-sm" id="edit_orc_ml_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrchestraMLOrder()" type="button">Edit</button> <button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_main_label_orders","'+ data.orchestra_main_label_orders[0].id +'")>Enable Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  </form>');
	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#main_labelform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmemain_label").click(function () {
        // $('.sample-artwork').html("")
        $("#main_labelform .backdrop").toggleClass('active');
        $("#main_labelform .right-panelos").toggleClass('active');
        $('#main_labelform .scalable').toggleClass('active');
        $("#main_labelform .left-panelos").toggleClass('active');
    })

    $(".backdrop, #main_labelclose").click(function () {
        $("#main_labelform .left-panelos").removeClass('active');
        $("#main_labelform .right-panelos").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    })



    treestructuremain_labelOs(data.treeviewmain_label, "treeml", $template_id);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus(data.orchestra_main_label_orders[0].color_code,"admin","os")
        }
    })
    $("#main_labelform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_ml_order_btn").prop("disabled", false);
    $("#main_labelclose").prop("disabled", false);
}

editOrchestraMLOrder = () => {
    $("#main_labelform :input").prop("disabled", false);
    $("#save_main_label_button").removeClass('disabled');
    $('#treeml >ul > li').each( function() {
        enable( 'treeml',this.id );        
    })
}


function treestructuremain_labelOs(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }
                    
                    $('#'+tree+' >ul > li').each( function() {	
                        disable( tree,this.id );        	
                    })


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
function washcarelabelsOs(data) {
    var washing_option = "<option value=''>Select Option</option>";
    var whitening_option = "<option value=''>Select Option</option>";
    var dryer_option = "<option value=''>Select Option</option>";
    var ironing_option = "<option value=''>Select Option</option>";
    var cleaning_option = "<option value=''>Select Option</option>";
    var made_option = "<option value=''>Select Option</option>";
    var instruction_option = "";
    var parts_option = "<option>Select Option</option>";
    var composition_option = "<option>Select Option</option>";

    $.each(data.orchestra_countries, function (index, value) {
        made_option += '<option data-id="' + value.id + '"  data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
        if(value.english.length > 31)
		{
			var txtEng = value.english.slice(0,30)+"...";
		}
		else
		{
			var txtEng = value.english;
		}
        instruction_option += '<option data-id="' + value.id + '" data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + txtEng + '</option>';
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option +=  '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
        if(value.care_instruction.length > 15)
		{
			var carEng = value.care_instruction.slice(0,15)+"...";
		}
		else
		{
			var carEng = value.care_instruction;
		}
        if (value.category_id == 1) {
            washing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + carEng + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        }

    });

    var merchandisersoption = "<option value=''>Select Merchandiser</option>";

	$.each(data.merchandisers, function (index, value) {
		merchandisersoption += "<option value=" + value.id + ">" + value.merchandiser_name + "</option>";
	});

    var tr = "";
    $qu = 0;
    var ttots = 0;
    if (data.orchestra_wash_care_label_orders != "") {
        
        if (data.orchestra_wash_care_label_orders.length > 0) {
          
            $exist_id = data.orchestra_wash_care_label_orders[0].id;
            if(data.orchestra_wash_care_label_orders[0].merchandiser_comment != "")
            {
                $comment = "<h4>Comments :</h4><p>"+data.orchestra_wash_care_label_orders[0].merchandiser_comment+"</p><hr>";
            }
            else
            {
                $comment = "";
            }
            
            var str=data.orchestra_wash_care_label_orders[0].status;

			$save_button_class = "";
            $status=str.replace(/ /g, "");
            $status_text=str;
            $wcl_name=data.orchestra_wash_care_label_orders[0].template_name;
			$template_id = data.orchestra_wash_care_label_orders[0].template_id;
		    $ocn_number_wash = data.orchestra_wash_care_label_orders[0].ocn_number;
			
			$print_button_class = "hidden";
        } else {
            
            $exist_id = "";
            $comment = "";
            $status="";
            $status_text="";
            $wcl_name="";
			$template_id = "122";
			$print_button_class = "hidden";
			$save_button_class = "";
            $ocn_number_wash = "";
        }

    } else {
        
        $exist_id = "";
        $comment = "";
        $status="";
        $status_text="";
        $wcl_name="";
		$template_id = "122";
		$print_button_class = "hidden";
		$save_button_class = "";
        $ocn_number_wash = "";
    }

	var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_wash_care_label_orders != "") {
            if (data.orchestra_wash_care_label_orders.length > 0) {
                $wash_label_count = data.orchestra_wash_care_label_orders[0]['qty'][$qu].quantity;
            } else {
                $wash_label_count = "";
            }

        } else {
            $wash_label_count = "";
        }


        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + (value.Size).replace("ANS", '') + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 95px;" onkeypress="return checkorcMOQ(this)" type="number" min="'+data.wash_moq+'" class="form-control input-sm tot_qty_tablewcl" value="' + $wash_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors" style="font-size:10px"></div></div></td></tr>';

        $qu++;
        ttots += parseInt($wash_label_count);
		ttots_sum += parseInt(value.Sum);

    });
	
	
    if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    if(data.treeviewwash_label[0].parent_id != "null")
    {
        var wcl_template = "<option value=''>Select Option</option>";

        $.each(data.treeviewwash_label, function (index, value) {
            wcl_template += '<option value="' + value.id + '">' + value.text + '</option>'
        });

        var wcl_template_div =`<div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                    class="form_label">Select Template</label><select
                                    data-required-error="Required" data-live-search="true"
                                    class="form-control selectpicker input-sm wcl_template" required>` +
                                    wcl_template + `</select>
                                <div class="help-block with-errors"></div>
                            </div>`
    }
    else
    {
        var wcl_template = "<option value=''>Select Option</option>";
        
        $.each(data.treeviewwash_label, function (index, value) {
            wcl_template += '<option value="' + value.id + '" selected>' + value.text + '</option>'
        });

        var wcl_template_div =`<div style="display:none" class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Template</label><select
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm wcl_template" required>` +
                                wcl_template + `</select>
                            <div class="help-block with-errors"></div>
                        </div>`;
    }

    $('.edit-orch-order-panel').append(`
            <form id="washcarelabelform" data-toggle="validator"><input type="hidden" value="` + $exist_id + `"
            id="exist_id">
                <div class="left-panel-heat col-md-7">
                    <div class="div-max">
                        <div class="col-md-4">
                            <h4>Options</h4>
                            <hr>
                            <div class="scroll">
                                <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                        Number</label><input type="text" id="ocn_number_wash" required
                                        value="`+$ocn_number_wash+`" class="form-control input-sm" placeholder="OCN Number">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">WCL Name</label><input type="text" class="form-control input-sm"
                                        required="" placeholder="WCL Name" value="` + $wcl_name + `" id="wcl_name">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Washing</label><select data-live-search="true"
                                        class="form-control selectpicker input-sm washing_select" required>` +
                                        washing_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Whitening</label><select data-live-search="true"
                                        class="form-control selectpicker input-sm whitening_select" required>` +
                                        whitening_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Dryer</label><select data-live-search="true"
                                        class="form-control selectpicker input-sm dryer_select" required>` +
                                        dryer_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Ironing</label><select data-live-search="true"
                                        class="form-control selectpicker input-sm ironing_select" required>` +
                                        ironing_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Cleaning</label><select data-live-search="true"
                                        class="form-control selectpicker input-sm cleaning_select" required>` +
                                        cleaning_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                    class="form_label">Select Made In</label><select data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm made_in_select" required>` +
                                    made_option + `</select>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                            class="form_label">Select Merchandiser</label><select data-live-search="true" class="form-control selectpicker input-sm merchandisers" required>` + merchandisersoption + `</select><div class="help-block with-errors"></div><label class="hidden merchandiser_error" style="color:red">Please Select Merchandiser</label>
                            </div>
                            `+wcl_template_div+`
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4>Care Instructions & Composition</h4>
                            <span class="label right_wcl_status label-` + $status + `" style="top:13px;right:15px;padding:3px 10px;position: absolute;">` + $status_text + `</span>
                            <hr>
                            <div class="scroll">
                                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                        class="form_label">Select Care Instructions</label><select data-live-search="true"
                                        data-done-button="true"
                                        class="form-control selectpicker input-sm care_instruction_select" required
                                        multiple>` + instruction_option + `</select>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-md-6">

                                    <label>Compositions</label> <select class="selectpicker composition_select"
                                        data-live-search="true"> ` + composition_option + ` </select>
                                    <p class="hidden" style="color:red;float:right" id="composition_error_required">
                                        Please Select Composition</p>
                                </div>

                                <div class="col-md-3">
                                <label>Percentage</label> <input type="number" min="1" max="100"
                                    class="form-control input-sm composition_percent">
                                <p class="hidden" style="color:red;text-align:right" id="percentage_error_required">
                                    Please Enter Percentage</p>
                                    </div>
                                    
                                    <div class="col-md-3" style="margin-top: 22px;">
                                    <button type="button"
                                    class="btn  btn-primary pull-right input-sm add_to_list"
                                    onclick="add_to_list()">Add To List</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                <table class="table table-bordered table-striped table-hover table-condensed">
                                    <thead>
                                        <th>Composition</th>
                                        <th>Percentage</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody id="composition_table_qty">
                                        <tr id="no_data">
                                            <td colspan="3">No data Found</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;margin-bottom: 10px;">
                                
                                <button
                                        class="btn  btn-primary pull-right input-sm add_to_composition" type="button"
                                        disabled onclick="add_to_composition()">Add To Composition</button>
                                        <p id="wash_composition_error" class="hidden"
                                        style="color:red;margin-right: 10px;">Please add to composition set</p>
                                <p id="wash_composition_page_error" class="hidden"
                                    style="color:red;text-align: right;margin-right: 10px;">Please Add Composition to Page</p>

                                <div>
                                    <div class="col-md-12">
                                            <hr>
                                            <h4>Compositions :</h4>
                                        <table class="table table-bordered table-striped table-hover table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Composition Name</th>
                                                    <th>Parts</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="composition_remove_qty"></tbody>
                                        </table>
                                        <div>
                                            <p class="hidden composition_error_hidden"
                                                style="color:red;text-align: right;margin-right: 10px;">Please add parts to the composition</p>
                                        </div>
                                        <div><button class="btn  btn-primary pull-right input-sm add_to_page"
                                                style="position:relative;" type="button" onclick="add_parts('')">Add
                                                Parts</button></div>
                                    </div>
                                </div>
                                <div class="parts_none" style="display:none"><select class="form-control">` + parts_option
                                        + `</select></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-panel-heat col-md-5">
                    <div class="div-max scroll">
                        <h4>Quantity Info for <span class="washlabelnameqty"> WashCare Label</span></h4>
                        <hr>
                        <table class="table table-bordered table-striped table-hover table-condensed table-responsive qty-table-modal"
                            id="wash_care_label_table_" style="font-size:11px">
                            <thead>
                                <tr>
                                    <th>UVC TCO#</th>
                                    <th>Size</th>
                                    <th>Barcode</th>
                                    <th>Qty</th>
                                    <th  style="display: flex;justify-content: center;"><label style="float: left; line-height: 2">Extra %:</label><input type="text" class="form-control input-sm extra_percentagewash"
                                            onkeypress="return checkNumbersOnly(event)"
                                            style="width:60px; float: left; margin-left: 5px;"></th>
                                </tr>
                            </thead>
                            <tbody>` + tr + ` <tr class="strong">
                                    <td class="text-right" colspan="3">Total:</td>
                                    <td>` + ttots_sum + `</td>
                                    <td class="text-center tot_qty_tablewash_fin">` + ttots + `</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>`+ $comment +` <span id="error_msg_alert" style="color:red;"></span><button
                            class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                            id="save_wash_button" style="margin-left:10px">Submit</button><button
                            class="btn btn-warning  pull-right input-sm" id="edit_orc_label_order_btn"
                            style="width: 55px;margin-left: 10px;" onclick="editOrchestraLabelOrder()" type="button">Edit</button>
                        <button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission"
                            style="width: 82px;margin-left: 10px;" type="button"
                            onclick=toggleOrchArtworkEditPermission("orchestra_wash_care_label_orders","`+
                            data.orchestra_wash_care_label_orders[0].id +`")>Enable Edit</button><button
                            class="btn btn-default pull-right input-sm" id="close" type="button">Close</button>
                    </div>
                </div>
                <div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div>
                </form>
    `);

    $('#washcarelabelform .tot_qty_tablewcl').keyup(function () {
        var tot_qty_tablewash_carton_final = 0;
        $('#washcarelabelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablewcl').val() != undefined) {
                tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewcl').val());
            }
        })
        $('#washcarelabelform .tot_qty_tablewash_fin').text(tot_qty_tablewash_carton_final);
    });
	
    $('.care_instruction_select').parent().removeClass('has-error')
    $('.help-block').html('')
    $('.extra_percentagewash').keyup(function () {
		
        
        var tot_qty_tablewash_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablewcl').val(tot_qty);
                if ($(this).find('.tot_qty_tablewcl').val() != undefined) {
                    tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewcl').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin').text(tot_qty_tablewash_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablewcl').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin').text("0");
        }

    })

    if (data.orchestra_wash_care_label_orders.length > 0) {

        var care_instruction_select_option = data.orchestra_wash_care_label_orders[0].care_instruction;
        $('.made_in_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].made_in_id);
        $('.washing_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].washing_id);
        $('.whitening_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].whitening_id);
        $('.dryer_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].dryer_id);
        $('.ironing_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].ironing_id);
        $('.cleaning_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].cleaning_id);
        $('.wcl_template').selectpicker('val', data.orchestra_wash_care_label_orders[0].template_id);

        $('.care_instruction_select').selectpicker('val', data.orchestra_wash_care_label_orders[0].care_instruction);
        $('.merchandisers').selectpicker('val', data.orchestra_wash_care_label_orders[0].merchandisers_id);


        localStorage.setItem('care_instruction_select', JSON.stringify(data.orchestra_wash_care_label_orders[0].care_instruction));


        var page_id = 0;
                var s_no =1;
                //var comps="";
                $.each(data.orchestra_wash_care_label_orders[0].composition, function (index, value) {
                    if (value.page_id != page_id) {
                        var comps="";
                        var comps_info="<label style='font-size:12px;color:black'>Compositions : </label>";
                        $.each(data.orchestra_wash_care_label_orders[0].composition, function (index1, value1) {
                            if(value.page_id == value1.page_id)
                            {
                                if(comps != "")
                                {
                                    comps+=","+value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                    comps_info += "<p style='color:black'>"+value1.english+" : "+value1.percentage+" % </p>";
                                }
                                else
                                {
                                    comps+=value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                    comps_info += "<p style='color:black'>"+value1.english+" : "+value1.percentage+" %</p>";
                                }
                                
                            }
                            
                        });
                        
                        add_page_composition += 1;
                        $('#composition_remove_qty').append(`<tr id="` + add_page_composition + `"><td><label id="label_s_no_`+add_page_composition+`">`+ s_no +`</label>. Composition <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-content="`+comps_info+`" style="cursor:pointer"></i>
                        </td><td><input type="hidden" value="`+comps+`" class="compositions_hidden"><select disabled class="form-control select_parts`+add_page_composition+`">` + parts_option + `</select></td><td><a style="cursor:pointer;pointer-events:none" class="pointer_anchor" onclick="deleteComposition(this,'')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>`)
                        
                        if(value.parts_id != "")
                        {
                            $('.select_parts'+add_page_composition).val(value.parts_id)
                        }
                        
                        
                        var washcomp=[];
                        $('#composition_remove_qty tr').each(function () {
                              var washcomp1=[];
                              var value=$(this).find('select').find(':selected').val();
                              var compo =$(this).find('.compositions_hidden').val();
                              var compss = compo.split(',');
                                $(compss).each(function (index1,value1){ 
                                    var english = value1.split('_');
                                    washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                                });
                            washcomp.push(washcomp1)
                        })
                        
                        
                        var wash_finals = JSON.stringify(washcomp);
                        localStorage.setItem('wash_composition_final', wash_finals);
                        
                        s_no++;
                    }

                    page_id = value.page_id;
                });


		    $('[data-toggle="popover"]').popover({
                html: true
            });
                
    }

    selectpicker()

    $("#clickme").click(function () {
        
       if( $('.merchandisers option:selected').val() != "")
       {
            composition_percentage_global = 0;
            $('.composition_select').prop('disabled', false)
            $('.parts_select').prop('disabled', false)
            $(".parts_select").val('');
            $('.parts_select').selectpicker('refresh');
            $(".composition_select").val('');
            $('.composition_select').selectpicker('refresh');
            $(".composition_percent").val('');
            $('.add_to_list').removeAttr('disabled')
            $('#composition_table_qty').html("")
            $('.composition_percent').removeAttr('disabled')
            $("#washcarelabelform" + " .backdrop").toggleClass('active');
            $("#washcarelabelform" + " .right-panelos").toggleClass('active');
            $('#washcarelabelform .scalable').toggleClass('active');
            $("#washcarelabelform" + " .left-panelos").toggleClass('active');
            $('.merchandiser_error').addClass('hidden')
       }
       else
       {
                $('.merchandiser_error').removeClass('hidden')
       }
	   $('.tot_qty_tablewcl').parent().removeClass('has-error').removeClass('has-danger')
        
    })

    $(".backdrop, #close").click(function () {
        $("#washcarelabelform" + " .left-panel-heat").removeClass('active');
        $("#washcarelabelform" + " .right-panel-heat").removeClass('active');
        $("#washcarelabelform" + " .backdrop").removeClass('active');
        $('#washcarelabelform .scalable').removeClass('active');
    })
	
    $("#washcarelabelform :input").prop("disabled", true);
    $("#toggle_orch_edit_permission").prop("disabled", false);
    $("#edit_orc_label_order_btn").prop("disabled", false);
    $("#close").prop("disabled", false);

    $('.selectpicker').selectpicker('refresh')
    
    $('#washcarelabelform').validator('update');


    $('.care_instruction_select.bootstrap-select').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

        var hidden_id = $(this).find('option').eq(clickedIndex).val();
        

        if(care_instruction_select_option.includes(hidden_id) != true)
        {
            care_instruction_select_option.push(hidden_id)
        }
        else
        {
            var index_cisop = care_instruction_select_option.indexOf(hidden_id);
            if (index_cisop !== -1) {
                care_instruction_select_option.splice(index_cisop, 1);
            }
        }
                                            
        localStorage.setItem('care_instruction_select', JSON.stringify(care_instruction_select_option));
        
    });

    $('#washcarelabelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setWashCareLabelStatus("","admin","os")
        }
    })
}

editOrchestraLabelOrder = () => {
    $("#washcarelabelform :input").prop("disabled", false);
    $(".add_to_composition").prop("disabled", true);
    $("#save_wash_button").removeClass('disabled');
    $(".pointer_anchor").css("pointer-events", "auto")
    $('.selectpicker').selectpicker('refresh')
}

function jsbarcodewashOs(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '36.1px');
    svg.setAttribute('height', '77px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}