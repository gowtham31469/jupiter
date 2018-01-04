var washcare = 0;
var mainlabel = 0;
var composition_percentage_global = 0;
var add_page_composition=0;
var main_label={};
var wash_label={};
var wash_composition={};

function orders() {
    $('.main-content').html('<div class="loader"></div>');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            if(buyer == "orchestra")
            {
                var newObj = data.sticker_orders.concat(data.label_orders);
                $('.main-content').html(table(newObj));
                $('#datatable-example-2').DataTable({
                    "pageLength": 25
                });
                $("#datatable-example-2_filter input").addClass('form-control input-sm');
                $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                $('#datatable-example-2_length select').addClass('form-control input-sm');
            }
            else
            {
                $('.main-content').html('<div class="container-fluid"><div class="row col-md-4"><div class="card"><h4>Create Order</h4><hr/><form id = "create_order_form" data-toggle = "validator" role = "form"><div class="form-group has-feedback"><label for="order_type" class="form_label">Order type</label><select class="form-control order_type" name="order_type" id="order_type" required=""><option value="">Select Order Type</option><option value="1">PO Upload</option><option value="2">Manual Entry</option></select><div class="help-block with-errors"></div></div><br><div class="form-group"><label for="template" class="form_label">Select Template</label><input class="search-input form-control" placeholder="Search for Awesomeness"><div id="jstree""></div></div><div class="form-group"><div class="po-upload"><div class="form-group files hidden" id="single_file"><label>Upload PO<sub>(Single File upload)</sub></label><input class="form-control" multiple="" type="file"></div><div class="form-group files hidden" id="multiple_file"><label>Upload PO<sub>(Multiple File upload)</sub></label><input class="form-control" multiple="" type="file"></div></div></div><button class="btn btn-primary btn-block">Create Order</button></form><br/></div></div><div class="col-md-8"><div class="card" style="height:calc(100vh - 100px);"><h4>Template Preview</h4><hr/><div class="row"><div class="loader"></div><div class="viewDiv"><img style="height:150px;object-fit:cover;margin-top: 122px;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png"></div><div class="descriptionDiv"></div></div></div></div></div></div>')

	                $(".search-input").keyup(function() {
	                var searchString = $(this).val();
	                    $('#jstree').jstree('search', searchString);
	                });
	                $(".order_type").change(function(){
			            var selectedType = $(".order_type option:selected").val();
			            if (selectedType === '1') 
			            {
			            	$("#single_file").removeClass("hidden");
			            	$("#multiple_file").removeClass("hidden");
			            }
			            else
			            {
			            	$("#single_file").addClass("hidden");
			            	$("#multiple_file").addClass("hidden");
			            }
			            
			        });
	                $('.descriptionDiv').addClass('hidden')
	                $('#jstree').jstree({
	                    'core': {
	                        'data': [{
	                            "id": 1,
	                            "text": "Stickers",
	                            "parent_id": 0,
	                            "children": [{
	                                "id": 2,
	                                "text": "Carton & Polybag Stickers",
	                                "parent_id": 1,
	                                "children": [{
	                                    "id": 3,
	                                    "text": "CE",
	                                    "parent_id": 2,
	                                    "children": [{
	                                        "id": "5,6,7",
	                                        "text": "TCL 1 , TCL 2 & TSL 1",
	                                        "parent_id": 3
	                                    }, {
	                                        "id": "8,9",
	                                        "text": "TCL 4 & TSL 3",
	                                        "parent_id": 3
	                                    }, {
	                                        "id": "10,11",
	                                        "text": "THL 1 & THL 3",
	                                        "parent_id": 3
	                                    }]
	                                }, {
	                                    "id": 4,
	                                    "text": "UK",
	                                    "parent_id": 2,
	                                    "children": [{
	                                        "id": "12,13,14",
	                                        "text": "TCL 1 , TCL 2 & TSL 1",
	                                        "parent_id": 4
	                                    }, {
	                                        "id": "15,16",
	                                        "text": "TCL 4 & TSL 3",
	                                        "parent_id": 4
	                                    }, {
	                                        "id": "17,18",
	                                        "text": "THL 1 & THL 3",
	                                        "parent_id": 4
	                                    }]
	                                }]
	                            }]
	                        }]
	                    },
	                    "types": {
	                        "default": {
	                            "icon": "fa fa-folder"
	                        }
	                    },
	                    "plugins": ["types", "search","conditionalselect"],
                        conditionalselect : function (node) { return this.is_leaf(node); }
	                }).on('loaded.jstree', function () {
                        $('#jstree').jstree('open_all');
                    }).bind("refresh.jstree", function (e, data) 
                        { $(this).jstree("open_all");});
	                

	                $('#jstree').on('activate_node.jstree', function (e, data) {
	                    if (data.instance.is_leaf(data.node)) {
	                        var template_id = data.node.id;
	                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
	                        $.ajax({
	                            url: base_url + "/admin/" + buyer + "/template-management/template-view",
	                            type: "Post",
	                            dataType: "json",
	                            data: {
	                                template_id: template_id,
	                                _token: csrf_token
	                            },
	                            beforeSend: function () {
	                                $(".loader").show();
	                                $(".loader").css('margin-top', '12%');
	                            },
	                            success: function (data) {
	                            	if (data.length > 0) 
	                            	{
	                            		$('.viewDiv').html('');
	                            		$.each(data, function (index, value) {
	                                    if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") 
	                                    {
	                                        $('.viewDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>'+value[0].template_name+'</p><label>Reference Number : </label><p>'+value[0].ref_no+'</p><label>Company Name : </label><p>'+value[0].company_name+'</p></div></div>')

	                                    } 
	                                    else 
	                                    {
	                                        $('.viewDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>'+value[0].template_name+'</p><label>Reference Number : </label><p>'+value[0].ref_no+'</p><label>Company Name : </label><p>'+value[0].company_name+'</p></div></div>')

	                                    }
	                                 });
	                            	}
	                            	else
	                            	{
	                            		$('.viewDiv').html('<img style="height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')

	                                        $('.descriptionDiv').html('<b>Template Not Found</b>');
	                                        $('.descriptionDiv').css('margin-top', '0px')
	                                        $('.viewDiv').css('margin-top', '140px')
	                            	}
	                                $('.viewDiv').css('width', '100%')
                                
                            },
                            error: function (data) {

                            },
                            complete: function () {
                                $(".loader").hide();
                                $(".loader").css('margin-top', '0%');
                            }
                        })
                    }
                });
            }
            $('#create_order_form').validator().on('submit', function (e) {
	         if (e.isDefaultPrevented())
	         {

	         }
	         else
	         {
	            e.preventDefault();
	         }
	       })

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function orderStatus()
{
    $('.main-content').html('<div class="loader"></div>');
    var buyer = localStorage.getItem("status_buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/order-status",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            if(buyer == "orchestra")
            {
                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders)));
                $('.main-content').html(table(newObj));
                $('#datatable-example-2').DataTable({
                    "pageLength": 25
                });
                $("#datatable-example-2_filter input").addClass('form-control input-sm');
                $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                $('#datatable-example-2_length select').addClass('form-control input-sm');
            }
            else
            {
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

function table(data) {
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for (var key in columnsIn) {
        if (key != "id") {
            if (key == "SupplierName" || key == "Style_Name") {
                th += "<th>" + key + "</th>";
            } else {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }

        }
    }
    $.each(data, function (index, value) {
        tr += '<tr id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id") {
                if (key == "Type") {
                    if (value[key] == "Null") {
                        var Isstd = "-";
                    } else {
                        var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    }
                    tr += '<td align="center">' + Isstd + '</td>';

                } else if (key == "Disney") {
                    if (value[key] == "Null") {
                        var Disney = "-";
                    } else {
                        var Disney = value[key] == "0" ? "No" : "Yes";
                    }

                    tr += '<td align="center">' + Disney + '</td>';
                } else if (key == "UVC") {
                    tr += '<td align="center"><a href="#" onclick=order_details("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
                } else if (key == "Status") {
                    tr += '<td align="center"><span class="label label-' + value[key] + '">' + value[key] + '</span></td>';
                }else {
                    if (key == "SupplierName" || key == "Style_Name") {
                        tr += '<td>' + value[key] + '</td>';
                    } else {
                        tr += '<td align="center">' + value[key] + '</td>';
                    }

                }
            }
        }
        tr += '</tr>';
    })
    return '<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';

}

function order_details(uvc, po) {
    if (po != "-") {
        sticker_order_details(uvc, po)
    } else {
        label_order_details(uvc)
    }
}

function sticker_order_details(uvc, po) {
    $('.main-content').html('<div class="loader"></div>');
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/sticker-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po: po
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po)
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po) {
    var tr = "";
    if (data.template_name == "Standard") {
        var disabled = '';
        var ttotal = '';
        var thead = '<thead><tr><th>Size</th><th>Ratio</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th></tr></thead>';
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
            tr += '<tr><td class="size">' + value.Size + '</td><td class="qty">' + value.Quantity + '</td><td>' + data.orchestra_parcel_details[0].ParcelBarCode + '</td></tr>';

        });
        var head_qty = "Quantity for Standard Stickers";
        var head_qty1 = "Standard Parcel/Carton Sticker";
        var standard_div = '<div class="form-group"><label>Total Polybags per carton: </label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" placeholder="Polybag count" class="form-control input-sm"></div> <div class="form-group"><label>Final Order Qty including Wastage: </label><div class="input-group"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Set of 2 Pcs." class="form-control input-sm"><span class="input-group-addon">sets</span></div></div><hr><h4>Standard Polybag Sticker</h4><div class="form-group"><label>Order Quantity</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div><div class="form-group"><label>Final Order Qty including Wastage</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div>';
    } else {
        var disabled = 'disabled="disabled"';
        var ttotal = '<td class="text-center">0</td><td class="text-center">0</td>';
        var thead = '<thead><tr><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty<br/>incl. wastage</th><th>Parcel Sticker Qty<br/>(in sets)</th></tr></thead>';
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
            tr += '<tr><td class="size">' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Quantity + '</td><td><div class="form-group has-feedback has-success"><input type="number" required min=1 value="0" class="form-control tot_qty_table_poly input-sm"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback has-success"><input  min=1 required type="number" value="0" class="form-control tot_qty_table_carton input-sm"><div class="help-block with-errors"></div></div></td></tr>';
        });
        
        
        var head_qty = "Quantity for Solid Size Stickers";
        var head_qty1 = "Solid Size Parcel/Carton Sticker";
        var standard_div = '';
    }

    var option = "<option>Age Group</option>";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color="+ value.color +" value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='#'>Order Management</a></li><li><a href='#' onclick=back_sticker_orders('" + buyer + "','" + buyer_id + "')>Sticker Orders</a></li><li class='active'>Sticker Order Details for UVC " + uvc + " and PO " + po + "</li></ol></div>")

    $('.top-content').append('<form id="solid_carton_form"><h3><i class="ion-ios-browsers-outline"></i>Sticker Order Details for UVC ' + uvc + ' and PO ' + po + '</h3><hr/><h5><span class="sticker-details">Supplier: <strong><span id="supplier">' + data.orchestra_parcel_details[0].SupplierName + '</span></strong> </span> <span class="sticker-details">Edition: <strong><span id="edition">' + data.orchestra_parcel_details[0].EditionNumber + '</span></strong> </span><span class="sticker-details">Version: <strong><span id="version">' + data.orchestra_parcel_details[0].VersionNumber + '</span></strong></span><span class="sticker-details">Style: <strong><span id="style">' + data.orchestra_parcel_details[0].NomApple + '</span></strong></span><span class="sticker-details">Color: <strong><span id="color">' + data.orchestra_parcel_quantity_details[0].Color + '</span></strong></span><span class="sticker-details">Age Group: <strong><span id="ageGroup">  <select name="carlist" id="age" form="carform">' + option + '</select></span></strong> </span><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"><input id="solid_delivery_date" type="text" class="form-control" required=""><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>' + head_qty + '</h4><div class="table-responsive"><table class="table table-bordered table-hover" id="solidform">' + thead + '<tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="2">Total</td><td>' + data.orchestra_parcel_details[0].Quantity + '</td>' + ttotal + '</tr></tbody></table><div class="bottom-bar"><div class="modal-footer">  <button type="submit" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save</button></div></div></form></div></div><div class="col-md-4"><h4>' + head_qty1 + '</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" value="395" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume"></span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left: -5px;">/</span> <div class="col-md-6" style="margin-top: -34px;"><input ' + disabled + ' data-toggle="modal" data-target=".needed_modal" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div>' + standard_div + ' </div></div>');

    $('.main-content').html('<input type="hidden" value="'+data.carton_template_id+'" id="carton_template_id"><input type="hidden" value="'+data.polybag_template_id+'" id="polybag_template_id"><input type="hidden" value="'+uvc+'" id="uvc_id"><input type="hidden" value="'+po+'" id="po"><hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right" style="cursor:pointer">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px; border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:575px;height:378;border: 2px #000 solid;" class="center-block" data="' + data.carton_template + '" type="image/svg+xml"></object></div><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ')>Print</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" style="cursor:pointer">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:500px;height:330px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ')>Print</button></div></div></div></div> </div> </div> </div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

        $('#solid_carton_form').validator().on('submit', function (e) {
             if (e.isDefaultPrevented())
             {

             }
             else
             {
                e.preventDefault();
                setSolidStickers()
             }
        })
    
    barcode_carton('svg2', data)
    barcode_carton('svg3', data)
    barcode_polybag('svg1', data)
    barcode_polybag('svg4', data)
    $("#modal_height").keyup(function () {
        var num = $('#length').val() * $('#width').val() * this.value
        var val = num / (1000 * 1000 * 1000)
        $('#parcelVolume').text(val)
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("volume").innerHTML = val;
        svgDoc1.getElementById("height").innerHTML = this.value;
    });
    $("#modal_close").click(function () {
        $('#modal_height').removeAttr('data-target')
    });
    $(".modal_close1").click(function () {
        $('#needed_modal').removeAttr('data-target')
    });

    $('#age').change(function () {
        $('#age_modal').modal("show");
        var mySVG1 = document.getElementById("svg1");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("round").style.fill = $('.age option:selected').data('cofreet');
    });


}

function printDiv(data, isstd) {
    if (data == "svg4" && isstd == "0") {
        var a = document.getElementById(data);
        var svgDoc = a.contentDocument;
        var svgRoot = svgDoc.documentElement;
        Popupsmall(svgRoot);
    }
    else if (data == "svg4" && isstd == "1") {
        var a = document.getElementById(data);
        var svgDoc = a.contentDocument;
        var svgRoot = svgDoc.documentElement;
        PopupTable(svgRoot);
    }
    else {
        var a = document.getElementById(data);
        var svgDoc = a.contentDocument;
        var svgRoot = svgDoc.documentElement;
        Popup(svgRoot);
    }

}

function Popup(data) {
//    var s = new XMLSerializer();
//    var d = data;
//    var str = s.serializeToString(d);
//    var mywindow = window.open('', 'new div', 'class=printmedia');
//    mywindow.document.write('<html><head><title></title>');
//    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
//    mywindow.document.write('</head><body><div id="printmedia" style="width:150mm;height:100mm">');
//    mywindow.document.write(str);
//    mywindow.document.write('</div>');
//    mywindow.print();
//    mywindow.close();
//    return true;
    var s = new XMLSerializer();
    var d = data;
    var str = s.serializeToString(d);
    var svg = encodeURI(str)
    var success = false;
    var url="";
   // window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: base_url + "/admin/order-management/print-pdf",
        type: "post",
        data: {
            _token: csrf_token,
            svg: svg
        },
       async:false,
        success: function (data) {
            //window.location.href=data;
            success = true;
            url=data;
  //         window.open('http://google.com')
//            var a = document.createElement('a');
//            a.href = data;
//            a.download = "stickers.pdf";
//            document.body.appendChild(a);
//            a.click();
//            document.body.removeChild(a);
        },
        error: function (data) {

        },
    })
    if(success){ //AND THIS CHANGED
      window.open(url)
    }
}
function PopupTable(data) {
    var s = new XMLSerializer();
    var d = data;
    var str = s.serializeToString(d);
    var svg = encodeURI(str)
    var success = false;
    var url="";
    var size=[];
    var qty=[];
   // window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
    
      $('#solidform tbody tr').each(function(){
        size.push($(this).find('.size').text()); 
        qty.push($(this).find('.qty').text()); 
    })
    var sizefin=size.join(',')
    var qtyfin=qty.join(',')
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: base_url + "/admin/order-management/print-pdf-table",
        type: "post",
        data: {
            _token: csrf_token,
            svg: svg,
            size:sizefin,
            qty:qtyfin
        },
       async:false,
        success: function (data) {
            success = true;
            url=data;
        },
        error: function (data) {

        },
    })
    if(success){ //AND THIS CHANGED
      window.open(url)
    }
}

function Popupsmall(data) {
//    var s = new XMLSerializer();
//    var d = data;
//    var str = s.serializeToString(d);
//    var mywindow = window.open('', 'new div', 'class=printmedia');
//    mywindow.document.write('<html><head><title></title>');
//    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
//    mywindow.document.write('</head><body><div id="printmedia" style="border:1px solid #000;width:100mm;height:70mm">');
//    mywindow.document.write(str);
//    mywindow.document.write('</div>');
//    mywindow.print();
//    mywindow.close();
//    return true;
    
    
    var s = new XMLSerializer();
    var d = data;
    var str = s.serializeToString(d);
    var svg = encodeURI(str)
    var success = false;
    var url="";
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: base_url + "/admin/order-management/print-pdf-small",
        type: "post",
        data: {
            _token: csrf_token,
            svg: svg
        },
       async:false,
        success: function (data) {
            success = true;
            url=data;
        },
        error: function (data) {

        },
    })
    if(success){ //AND THIS CHANGED
      window.open(url)
    }
}

function barcode_polybag(id, data) {
    var mySVG = document.getElementById(id);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;
        var svgItem = svgDoc.getElementById("uvc_tco");

        svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
        //svgDoc.getElementById("round").style.fill = $('#age').val();

        if (data.template_name == "Standard") {
            
            $td_col="";
            $td_size="";
            $total=0;
            $('#solidform tbody tr').each(function(){
                if($(this).find('.size').text() != "")
                {
                    $td_size+="<td>"+$(this).find('.size').text()+"</td>";
                    $td_col+="<td>"+$(this).find('.qty').text()+"</td>";
                     $total+=parseInt($(this).find('.qty').text());
                }
                
               
            })
             $tbl = '<tr><td>Size:</td>'+$td_size+'<td>Total</td></tr><tr> <td>ERCU</td>'+$td_col+'<td>'+$total+'</td></tr>';
            
            var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
            svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
            svgDoc.getElementById("cartontable").innerHTML = $tbl;
        } else {
            var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
            svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_quantity_details[0].Size;
            svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_quantity_details[0].Color;

        }
        //jsbarcode(code, 'barcode', svgDoc)
    }, false);

}

function barcode_carton(id, data) {
    console.log(data);
    var mySVG = document.getElementById(id);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;
        var svgItem = svgDoc.getElementById("uvc_tco");
        svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
        svgDoc.getElementById("length").innerHTML = $('#length').val();
        svgDoc.getElementById("width").innerHTML = $('#width').val();
        svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
        svgDoc.getElementById("volume").innerHTML = $('#parcelVolume').text();
        svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
        svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
        svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_quantity_details[0].Color;

        if (data.template_name == "Standard") {
            var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
        } else {
            var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
            svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_quantity_details[0].Size;
        }

        jsbarcode(code, 'barcode1', svgDoc)
        jsbarcode(code, 'barcode2', svgDoc)


    }, false);

}

function label_order_details(uvc) {
    $('.main-content').html('<div class="loader"></div>');
    // var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    // var buyer_id=$('.buyer-select').val()
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/label-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            uvc: uvc
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            localStorage.setItem("label_order_data",JSON.stringify(data));
            orchestra_label_order_details(buyer, buyer_id, uvc, data)
            hangtagmodal(data);
            specialtags(data);
            if(data.orchestra_main_label_orders.length > 0)
            {
                 $.each(data.orchestra_main_label_orders, function (index, value) {
                     mainlabel+=1;
                     if(index == 0)
                     {
                         mainlabels(data,"");
                     }
                     else
                     {
                          $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".main_label_' + index + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-main" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickmemain'+index+'>Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p> <h5><b class="mainlabelname'+index+'">' + value.template_name + '</b></h5></div><div class="img_artwork"><img src="' + base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div>')
                          mainlabels(data,index);
                     }
                 });
                mainlabel-=1;
            }
            else
            {
                mainlabels(data,"");
            }
            if(data.orchestra_wash_care_label_orders.length > 0)
            {
                 $.each(data.orchestra_wash_care_label_orders, function (index, value) {
                    
                     washcare+=1;
                     if(index == 0)
                     {
                         washcarelabels(data,"");
                     }
                     else
                     {
                          $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".washcare_label_' + index + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-wash" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme'+index+'>Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p> <h5><b class="washlabelname'+index+'">' + value.template_name + '</b></h5></div><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div>')
                          washcarelabels(data,index);
                     }
                 });
                washcare-=1;
            }
            else
            {
                washcarelabels(data,"");
            }

            
          //  washcarelabels(data,"");

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function washcarelabels(data,dataid) {
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
        made_option += "<option data-id='" + value.id + "'  data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
        instruction_option += "<option data-id='" + value.id + "' data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
        if (value.category_id == 1) {
            washing_option += "<option data-id='"+value.id+"' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option data-id='"+value.id+"' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option data-id='"+value.id+"' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option data-id='"+value.id+"' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option data-id='"+value.id+"' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        }

    });


    var tr = "";
    $qu=0;
        if(data.orchestra_wash_care_label_orders != "")
        {
             if(dataid != "")
              {
                  dataid1=dataid;
              }
              else
              {
                  dataid1=0;
              }
              if(data.orchestra_wash_care_label_orders.length > dataid1)
              {
                   $de_date=data.orchestra_wash_care_label_orders[dataid1].delivery_date
                  
                   $de_date1 =  $de_date.split('-');
                   $de_date_fin = $de_date1[1] + "/" + $de_date1[2] + "/" +$de_date1[0];
                  
                    $delivery_date=$de_date_fin;
                    $exist_id=data.orchestra_wash_care_label_orders[dataid1].id;
              }
              else
              {
                    $delivery_date="";
                    $exist_id="";
              }
            
        }
        else
        {
            $delivery_date="";
            $exist_id="";
        }
    $.each(data.order_details, function (index, value) {
        if(data.orchestra_wash_care_label_orders != "")
        {
          if(dataid != "")
          {
              dataid1=dataid;
          }
          else
          {
              dataid1=0;
          }
              if(data.orchestra_wash_care_label_orders.length > dataid1)
              {
                  $wash_label_count=data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
              }
              else
              {
                   $wash_label_count="";
              }
          
        }
        else
        {
            $wash_label_count="";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" value="'+$wash_label_count+'" required placeholder="WCL Count"><div class="help-block with-errors"></div></div></td></tr>';
        $qu++;
    });
    $('.top-content').append('<form id="washcarelabelform'+dataid+'" data-toggle= "validator"><input type="hidden" value="'+$exist_id+'" id="exist_id'+dataid+'"><div class="left-panel col-md-9"> <div class="div-max"> <div class="col-md-3 "> <h4>Options</h4> <hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select State</label><select data-live-search="true" class="form-control selectpicker input-sm" required> <option value="">Select Option</option><option>Applicable</option> <option>Not Applicable</option> </select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Delivery Date</label> <div class="input-group date" data-provide="datepicker" id="datePicker1"> <input type="text" class="form-control" value="'+$delivery_date+'" id="wash_delivery_date'+dataid+'" required><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </div></div> <div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select data-live-search="true" class="form-control selectpicker input-sm washing_select'+dataid+'" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select data-live-search="true" class="form-control selectpicker input-sm whitening_select'+dataid+'" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select data-live-search="true" class="form-control selectpicker input-sm dryer_select'+dataid+'" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select data-live-search="true" class="form-control selectpicker input-sm ironing_select'+dataid+'" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select data-live-search="true" class="form-control selectpicker input-sm cleaning_select'+dataid+'" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div> </div> <div class="col-md-6"> <h4>Art Work</h4> <hr> <div class="section-1 sections scroll"> <object id="washcare_svg'+dataid+'" style=width:100%" data="' + data.orchestra_wash_template + '" type="image/svg+xml"></object> <input type="hidden" value="' + data.orchestra_wash_template_id + '" id="wash_label_id"></div> </div> <div class="col-md-3 "> <h4>Options</h4> <hr> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select data-live-search="true" class="form-control selectpicker input-sm made_in_select'+dataid+'" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select data-live-search="true" class="form-control selectpicker input-sm care_instruction_select'+dataid+'" required multiple>' + instruction_option + '</select><div class="help-block with-errors"></div></div> <div class="section-3 sections"> <h5>Parts</h5> <select class="selectpicker parts_select'+dataid+'"  data-live-search="true"> ' + parts_option + '  </select> </div> <div class="section-1 sections"> <h5>Compositions</h5> <select class="selectpicker composition_select'+dataid+'"  data-live-search="true">  ' + composition_option + '  </select> </div> <h5>Percentage</h5> <input type="number" min="1" max="100" class="form-control input-sm composition_percent'+dataid+'">  <button type="button" class="btn btn-info pull-right input-sm add_to_list'+dataid+'" onclick="add_to_list('+dataid+')">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty'+dataid+'"></tbody></table><button class="btn btn-info pull-right input-sm add_to_page'+dataid+'"  type="button" disabled onclick="add_to_page('+dataid+')">Add To Page</button> <table class="table table-striped table-hover table-condensed"><thead><tr><th>Composition Name</th><th>Actions</th></tr></thead><tbody id="composition_remove_qty'+dataid+'"></tbody></table></div> </div> </div> <div class="right-panel col-md-3"> <div class="div-max scroll"> <h4>Quantity Info for <span class="washlabelnameqty'+dataid+'"> WashCare Label</span></h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close" type="button">Close</button> </div> </div>  <div class="backdrop"></div></form>');

    $('#washcarelabelform'+dataid).validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            setWashCareLabelStatus(dataid)
         }
       })

    $('.extra_percentage').keyup(function () {
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_table').val(tot_qty);
            })
        } else {
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_table').val("");
            })
        }

    })

    selectpicker()

    $('.date').datepicker();
    $("#clickme"+dataid).click(function () {
        $('.washlabelnameqty'+dataid).text($('.washlabelname'+dataid).text())
        $("#washcarelabelform"+dataid+" .backdrop").toggleClass('active');
        $("#washcarelabelform"+dataid+" .right-panel").toggleClass('active');
        $('#washcarelabelform'+dataid+' .scalable').toggleClass('active');
        $("#washcarelabelform"+dataid+" .left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $("#washcarelabelform"+dataid+" .left-panel").removeClass('active');
        $("#washcarelabelform"+dataid+" .right-panel").removeClass('active');
        $("#washcarelabelform"+dataid+" .backdrop").removeClass('active');
        $('#washcarelabelform'+dataid+' .scalable').removeClass('active');
    })

    var mySVG = document.getElementById("washcare_svg"+dataid);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;

        //$('.washing_select'+dataid+' option[data-id="1"]').prop('selected', true)
        
        if(data.orchestra_wash_care_label_orders != "")
        {
             if(dataid != "")
              {
                  dataid1=dataid;
              }
              else
              {
                  dataid1=0;
              }
              
              if(data.orchestra_wash_care_label_orders.length > dataid1)
              {
                  $('.made_in_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].washing_id); 
                  var text_for_mad_in = '<p>' + $('select.made_in_select'+dataid).find(':selected').data('french') + " - " + $('select.made_in_select'+dataid).find(':selected').data('english') + " - " + $('select.made_in_select'+dataid).find(':selected').data('spanish') + " - " + $('select.made_in_select'+dataid).find(':selected').data('portuguese') + " - " + $('select.made_in_select'+dataid).find(':selected').data('italian') + " - " + $('select.made_in_select'+dataid).find(':selected').data('dutch') + " - " + $('select.made_in_select'+dataid).find(':selected').data('german') + " - " +
                  $('select.made_in_select'+dataid).find(':selected').data('russian') + " - " +
                  $('select.made_in_select'+dataid).find(':selected').data('chinese') + " - " +
                  $('select.made_in_select'+dataid).find(':selected').data('arabic') + " - " +
                  $('select.made_in_select'+dataid).find(':selected').data('greek') + " - " +
                  $('select.made_in_select'+dataid).find(':selected').data('turkish') + '</p>';
                  svgDoc.getElementById("country").innerHTML = text_for_mad_in;
                    $('.care_instruction_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].care_instruction);
                    svgDoc.getElementById("text_care_instruction").innerHTML = "";
                    $('.care_instruction_select'+dataid+' option:selected').each(function () {
                        var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                            $(this).data('russian') + "-" +
                            $(this).data('chinese') + "-" +
                            $(this).data('arabic') + "-" +
                            $(this).data('greek') + "-" +
                            $(this).data('turkish') + '</p>';
                        svgDoc.getElementById("text_care_instruction").innerHTML += value;
                    });
                  
                  $('.washing_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].washing_id); 
                  svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select'+dataid+' option:selected').data('cofreet'));
                  svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select'+dataid+' option:selected').data('astm'));
                  $('.whitening_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].whitening_id);
                  svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select'+dataid+' option:selected').data('cofreet'));
                  svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select'+dataid+' option:selected').data('astm'));
                  $('.dryer_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].dryer_id);
                  svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select'+dataid+' option:selected').data('cofreet'));
                  svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select'+dataid+' option:selected').data('astm'));
                  $('.ironing_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].ironing_id);
                  svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select'+dataid+' option:selected').data('cofreet'));
                  svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select'+dataid+' option:selected').data('astm'));
                  $('.cleaning_select'+dataid).selectpicker('val',data.orchestra_wash_care_label_orders[dataid1].cleaning_id);
                  svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select'+dataid+' option:selected').data('cofreet'));
                  svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select'+dataid+' option:selected').data('astm'));
                  
                  var page_id=0;
                   $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index, value) {
                       if(value.page_id != page_id)
                       {
                           add_page_composition+=1;
                           $('#composition_remove_qty'+dataid).append('<tr id="'+add_page_composition+'"><td>Composition '+add_page_composition+'</td><td><a style="cursor:pointer" onclick="deleteComposition(this,'+dataid+')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
                           if(value.parts_english != null)
                           {
                               svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_'+add_page_composition+'" id="composition_'+add_page_composition+'">'+value.parts_french + "-" + value.parts_english + "-" + value.parts_spanish + "-" + value.parts_portuguese + "-" + value.parts_italian + "-" + value.parts_dutch + "-" + value.parts_german + "-" + value.parts_russian + "-" + value.parts_chinese + "-" + value.parts_arabic + "-" + value.parts_greek + "-" + value.parts_turkish + '</p>';
                           }
                             
                       }
                       
                     svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_'+add_page_composition+'" id="composition_'+add_page_composition+'">'+value.percentage+"% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german + "-" + value.russian + "-" + value.chinese + "-" + value.arabic + "-" + value.greek + "-" + value.turkish + '</p>';
                       
                       page_id=value.page_id;
                       
                   });  
              }
            
        }
        
        
        $('.washing_select'+dataid+'.bootstrap-select').on('changed.bs.select', function () {
           // alert()
            if ($('.washing_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select'+dataid+' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select'+dataid+' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select'+dataid+'.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select'+dataid+' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select'+dataid+' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select'+dataid+'.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select'+dataid+' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select'+dataid+' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select'+dataid+'.bootstrap-select').change(function () {
            if ($('.ironing_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select'+dataid+' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select'+dataid+' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select'+dataid+'.bootstrap-select').change(function () {
            if ($('.cleaning_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select'+dataid+' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select'+dataid+' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select'+dataid+'.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select'+dataid+' option:selected').val() != "") {

                var text_for_mad_in = '<p>' + $('select.made_in_select'+dataid).find(':selected').data('french') + " - " + $('select.made_in_select'+dataid).find(':selected').data('english') + " - " + $('select.made_in_select'+dataid).find(':selected').data('spanish') + " - " + $('select.made_in_select'+dataid).find(':selected').data('portuguese') + " - " + $('select.made_in_select'+dataid).find(':selected').data('italian') + " - " + $('select.made_in_select'+dataid).find(':selected').data('dutch') + " - " + $('select.made_in_select'+dataid).find(':selected').data('german') + " - " +
                    $('select.made_in_select'+dataid).find(':selected').data('russian') + " - " +
                    $('select.made_in_select'+dataid).find(':selected').data('chinese') + " - " +
                    $('select.made_in_select'+dataid).find(':selected').data('arabic') + " - " +
                    $('select.made_in_select'+dataid).find(':selected').data('greek') + " - " +
                    $('select.made_in_select'+dataid).find(':selected').data('turkish') + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select'+dataid+'.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select'+dataid+' option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select'+dataid+' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('russian') + "-" +
                        $(this).data('chinese') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + "-" +
                        $(this).data('turkish') + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });


        var size = data.order_details[0].Size.split(" ")
        if (size[1] == "ANS") {
            var size1 = "years";
        } else {
            var size1 = "months";
        }
        svgDoc.getElementById("parcel_code1").innerHTML = data.order_details[0].UVC_TCO;
        svgDoc.getElementById("product_family").innerHTML = data.product_family[0].arabic;
        svgDoc.getElementById("parcel_code2").innerHTML = data.order_details[0].UVC_TCO;
        svgDoc.getElementById("size_in_fr_en1").innerHTML = data.order_details[0].cm + "cm / " + data.order_details[0].in + "in";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = data.order_details[0].cm + "cm / " + data.order_details[0].in + "in";
        svgDoc.getElementById("size1").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
        svgDoc.getElementById("size2").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;


        var code = data.order_details[0].BarcodeForPricesTags.toString();
        jsbarcode(code, 'barcode', svgDoc)

    }, false);
}

function add_to_list(dataid) {
    if(dataid === undefined)
    {
      dataid="";
    }
    else
    {
        dataid=dataid;
    }

    if ($('.composition_select'+dataid+' option:selected').val() != "Select Option" && $('.composition_percent'+dataid).val() != "") {
       
        composition_percentage_global += parseInt($('.composition_percent'+dataid).val());
         
        if (composition_percentage_global <= "100") {
            
            composition_percentage_global = composition_percentage_global;
            $('#composition_table_qty'+dataid).append("<tr><td class='comp'>" + $('.composition_select'+dataid+' option:selected').text() + "</td><td class='percent'>" + parseInt($('.composition_percent'+dataid).val()) + "</td></tr>")
        } else {
            composition_percentage_global -= parseInt($('.composition_percent'+dataid).val());
        }

        $('.parts_select'+dataid).prop('disabled', 'true')
    }
    if (composition_percentage_global == "100") {
        $('.composition_select'+dataid).prop('disabled', 'true')
        $('.composition_percent'+dataid).prop('disabled', 'true')
        $('.add_to_list'+dataid).prop('disabled', 'true')
        $('.add_to_page'+dataid).removeAttr('disabled')
    }

}

function add_to_page(dataid) {
    add_page_composition+=1;
    
    if(dataid === undefined)
    {
      dataid="";
    }
    else
    {
        dataid=dataid;
    }
    composition_percentage_global = 0;
    var mySVG = document.getElementById("washcare_svg"+dataid);
    var svgDoc = mySVG.contentDocument;
    var a = [];
    var com = [];
    $('#composition_table_qty'+dataid+' tr').each(function () {
        var comp = $(this).find('.comp').text();
        var percent = $(this).find('.percent').text();
        var parts = $('.parts_select'+dataid+' option:selected').val()
        a.push(comp)
        com.push(parts+"_"+comp+"_"+percent)
    })
   if(localStorage.getItem('wash_composition') != null)
       {
            var str = localStorage.getItem('wash_composition');
            var n = str.includes('wash_composition'+dataid);
            if(n == false)
            {
               wash_composition['wash_composition'+dataid]= {};
            }
       }
    else
        {
            wash_composition['wash_composition'+dataid]= {};
        }
    
    wash_composition['wash_composition'+dataid][add_page_composition]=com;
     var wash_comp = JSON.stringify(wash_composition);
    localStorage.setItem('wash_composition',wash_comp);
    
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer = localStorage.getItem("buyer");
    if ($('.parts_select'+dataid+' option:selected').val() != "Select Option") {
        svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_'+add_page_composition+'">' + $('select.parts_select'+dataid).find(':selected').data('french') + "-" + $('select.parts_select'+dataid).find(':selected').data('english') + "-" + $('select.parts_select'+dataid).find(':selected').data('spanish') + "-" + $('select.parts_select'+dataid).find(':selected').data('portuguese') + "-" + $('select.parts_select'+dataid).find(':selected').data('italian') + "-" + $('select.parts_select'+dataid).find(':selected').data('dutch') + "-" + $('select.parts_select'+dataid).find(':selected').data('german') + "-" + $('select.parts_select'+dataid).find(':selected').data('russian') + "-" + $('select.parts_select'+dataid).find(':selected').data('chinese') + "-" + $('select.parts_select'+dataid).find(':selected').data('arabic') + "-" + $('select.parts_select'+dataid).find(':selected').data('greek') + "-" + $('select.parts_select'+dataid).find(':selected').data('turkish') + '</p>';
    }
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/compositions-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            lang: a
        },
        beforeSend: function () {},
        success: function (data) {

            $.each(data, function (index, value) {
                $('#composition_table_qty'+dataid+' tr').each(function () {
                    var percent=$(this).find('.percent').text();
                    if ($(this).find('.comp').text() == value.english) {
                        svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_'+add_page_composition+'" id="composition_'+add_page_composition+'">'+ percent+"% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german + "-" + value.russian + "-" + value.chinese + "-" + value.arabic + "-" + value.greek + "-" + value.turkish + '</p>';
                    }
                })
            });
            $('#composition_remove_qty'+dataid).append('<tr id="'+add_page_composition+'"><td>Composition '+add_page_composition+'</td><td><a style="cursor:pointer" onclick="deleteComposition(this,'+dataid+')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
            
            $(".add_to_list"+dataid).prop("disabled", false);
            $('.composition_select'+dataid).prop('disabled', false)
            $('.composition_percent'+dataid).prop('disabled', false)
            $('#composition_table_qty'+dataid).html("")
            $('.parts_select'+dataid).prop('disabled', false)
            $('.add_to_page'+dataid).attr('disabled', 'disabled')
            $('.composition_percent'+dataid).val("");
            $(".composition_select"+dataid).val('');
            $('.composition_select'+dataid).selectpicker('refresh');
            $(".parts_select"+dataid).val('');
            $('.parts_select'+dataid).selectpicker('refresh');

        },
        error: function (data) {

        },
        complete: function () {}
    })


}
function deleteComposition(obj,dataid)
{
   
   // add_page_composition-=1;
    if(dataid === undefined)
    {
      dataid="";
    }
    else
    {
        dataid=dataid;
    }
    $(obj).parent().parent().remove();
    var classname= $(obj).parent().parent().attr('id');
    var mySVG = document.getElementById("washcare_svg"+dataid);
    var svgDoc = mySVG.contentDocument;
    var list =svgDoc.getElementsByClassName("composition_"+classname);
     for(var i = list.length - 1; 0 <= i; i--)
     {
         list[i].remove();
     }
     var wash = localStorage.getItem('wash_composition');
    var wash_fin = JSON.parse(wash);
    alert(add_page_composition)
    delete wash_fin['wash_composition'+dataid][classname];
    delete wash_composition['wash_composition'+dataid][classname];
    var wash_final = JSON.stringify(wash_fin);
    localStorage.setItem('wash_composition',wash_final);
}

function mainlabels(data,dataid) {
    
    var tr = "";
    $qu=0;
        if(data.orchestra_main_label_orders != "")
        {
             if(dataid != "")
              {
                  dataid1=dataid;
              }
              else
              {
                  dataid1=0;
              }
              if(data.orchestra_main_label_orders.length > dataid1)
              {
                   $de_date=data.orchestra_main_label_orders[dataid1].delivery_date
                  
                   $de_date1 =  $de_date.split('-');
                   $de_date_fin = $de_date1[1] + "/" + $de_date1[2] + "/" +$de_date1[0];
                  
                    $delivery_date=$de_date_fin;
                    $template_id=data.orchestra_main_label_orders[dataid1].template_id;
                    $exist_id=data.orchestra_main_label_orders[dataid1].id;
              }
              else
              {
                    $delivery_date="";
                    $template_id="";
                    $exist_id="";
              }
            
        }
        else
        {
            $delivery_date="";
            $template_id="";
            $exist_id="";
        }
    $.each(data.order_details, function (index, value) {
        if(data.orchestra_main_label_orders != "")
        {
          if(dataid != "")
          {
              dataid1=dataid;
          }
          else
          {
              dataid1=0;
          }
              if(data.orchestra_main_label_orders.length > dataid1)
              {
                  $main_label_count=data.orchestra_main_label_orders[dataid1]['qty'][$qu].quantity;
              }
              else
              {
                   $main_label_count="";
              }
          
        }
        else
        {
            $main_label_count="";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" value="'+$main_label_count+'" required placeholder="Main & Size Count"><div class="help-block with-errors"></div></div></td></tr>';
        $qu++;
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="mainlabelsform'+dataid+'" data-toggle= "validator"><input type="hidden" value="'+$exist_id+'" id="exist_id'+dataid+'"><div class="left-panel col-md-9"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker"id="datePicker1"><input type="text" value="'+$delivery_date+'" id="main_delivery_date'+dataid+'" class="form-control" required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_template_error'+dataid+'" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree3'+dataid+'_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree3'+dataid+'" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><hr> <div class="section-1 sections scroll"><div class="loader"></div><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max scroll"> <h4>Quantity Info for <span class="mainlabelnameqty'+dataid+'">Main & Size Labels</span></h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');
    
    $("#clickmemain"+dataid).click(function () {
       // $('.sample-artwork').html("")
        //alert($('.mainlabelname'+dataid).text())
        $('.mainlabelnameqty'+dataid).text($('.mainlabelname'+dataid).text())
        $("#mainlabelsform"+dataid+" .backdrop").toggleClass('active');
        $("#mainlabelsform"+dataid+" .right-panel").toggleClass('active');
        $('#mainlabelsform'+dataid+' .scalable').toggleClass('active');
        $("#mainlabelsform"+dataid+" .left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $("#mainlabelsform"+dataid+" .left-panel").removeClass('active');
        $("#mainlabelsform"+dataid+" .right-panel").removeClass('active');
        $("#mainlabelsform"+dataid+" .backdrop").removeClass('active');
        $('#mainlabelsform'+dataid+' .scalable').removeClass('active');
    })
    selectpicker()
    treestructure(data.treeviewmain, "tree3"+dataid,$template_id);
    $('#mainlabelsform'+dataid).validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            setMainLabelStatus(dataid)
         }
       })
}

function specialtags(data) {
    var tr = "";
    $qu=0;
     if(data.orchestra_special_tag_orders != "")
        {
          
              $de_date=data.orchestra_special_tag_orders[0].delivery_date;
                  
                   $de_date1 =  $de_date.split('-');
                   $de_date_fin = $de_date1[1] + "/" + $de_date1[2] + "/" +$de_date1[0];
                  
                    $delivery_date=$de_date_fin;
            $template_id=data.orchestra_special_tag_orders[0].template_id;
             $exist_id=data.orchestra_special_tag_orders[0].id;
        }
        else
        {
            $delivery_date="";
             $template_id="";
            $exist_id="";
        }
    $.each(data.order_details, function (index, value) {
         if(data.orchestra_special_tag_orders != "")
        {
          
            $special_tag_count=data.orchestra_special_tag_orders_qty[$qu].quantity;
        }
        else
        {
            $special_tag_count="";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" value="'+$special_tag_count+'" class="form-control input-sm tot_qty_table" required placeholder="Special Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        $qu++;
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panel col-md-9"id="HandTag"><input type="hidden" value="'+$exist_id+'" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker" ><input id="special_delivery_date"  type="text" value="'+$delivery_date+'" class="form-control"required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><hr> <div class="section-1 sections scroll"><div class="loader"></div><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max scroll"> <h4>Quantity Info for Special Tag</h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="specialclose">Close</button> </div> </div>  <div class="backdrop"></div></form>');
    
    $("#clickmespecial").click(function () {
       // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panel").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panel").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panel").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    })



   treestructure(data.treeviewspecial, "tree2",$template_id);

    $('#specialtagform').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            setSpecialTagStatus()
         }
       })
}

function hangtagmodal(data) {
    var order_details = data.order_details;
    var tr = "";
    $qu=0;
    if(data.orchestra_hang_tag_orders != "")
        {
            $de_date=data.orchestra_hang_tag_orders[0].delivery_date;
                  
                   $de_date1 =  $de_date.split('-');
                   $de_date_fin = $de_date1[1] + "/" + $de_date1[2] + "/" +$de_date1[0];
                  
                    $delivery_date=$de_date_fin;
          
            $template_front_id=data.orchestra_hang_tag_orders[0].template_front_id;
            $exist_id=data.orchestra_hang_tag_orders[0].id;
        }
        else
        {
            $delivery_date="";
            $template_front_id="";
            $exist_id="";
        }
    $.each(data.order_details, function (index, value) {
        if(data.orchestra_hang_tag_orders != "")
        {
          
            $hang_tag_count=data.orchestra_hang_tag_orders_qty[$qu].quantity;
        }
        else
        {
            $hang_tag_count="";
        }
        tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control  input-sm tot_qty_table" value="'+$hang_tag_count+'" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        $qu++;
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><input type="hidden" value="'+$exist_id+'" id="exist_id"><div class="left-panel col-md-9"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker"id="datePicker1"><input value="'+$delivery_date+'" id="hang_delivery_date" type="text"class="form-control"required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div></div><div class="col-md-4"><h4>Front</h4><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections"><label style="    margin-left: 108px;">Hang Tag Print Date : '+data.order_details[0].HangTag_Print_Date+'</label><input type="hidden" value="' + data.orchestra_template_id + '" id="hang_tag_id"><object class="center-block" id="svg2" style="height:486px" data="' + data.orchestra_template + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table></div> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" type="button" id="hangclose">Close</button> </div> </div>  <div class="backdrop"></div></form>');
    
    $("#clickmehang").click(function () {
       // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panel").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panel").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
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
        "plugins": ["types", "search"]
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
    
    $("#tree1").on("loaded.jstree", function(e, data){
        $('#tree1').jstree(true).select_node($template_front_id);
         var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        //alert()
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                    $(".loader").css('margin-top', '12%');
                },
                success: function (data) {
                    if (data.length > 0) {
                        $('#'+ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('#'+ids).find('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
                    }
                    var mySVG = document.getElementById("svg_hang_front");
                    var svgDoc;
                    mySVG.addEventListener("load", function () {
                        svgDoc = mySVG.contentDocument;
                        var pri = order_details[0].PrixEurope.split(".")

                        if (pri.length == 1) {
                            var svgItem = svgDoc.getElementById("price1");
                            svgItem.innerHTML  = order_details[0].PrixEurope;
                            svgDoc.getElementById("price2").innerHTML = "";
                        } else if (pri.length == 2) {
                            var svgItem = svgDoc.getElementById("price1");
                            svgItem.innerHTML  = pri[0];
                            svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                        }
                        var pri1 = order_details[0].PrixClubEurope.toString().split(".")
                        if (pri1.length == 1) {
                            svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                            svgDoc.getElementById("club_price2").innerHTML = "";
                        } else if (pri1.length == 2) {
                            svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                            svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                        }

                    }, false);



                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                    $(".loader").css('margin-top', '0%');
                }
            })
        }
    });

    $('#tree1').on('activate_node.jstree', function (e, data) {
       var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        //alert()
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                    $(".loader").css('margin-top', '12%');
                },
                success: function (data) {
                    if (data.length > 0) {
                        $('#'+ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('#'+ids).find('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
                    }
                    var mySVG = document.getElementById("svg_hang_front");
                    var svgDoc;
                    mySVG.addEventListener("load", function () {
                        svgDoc = mySVG.contentDocument;
                        var pri = order_details[0].PrixEurope.split(".")

                        if (pri.length == 1) {
                            var svgItem = svgDoc.getElementById("price1");
                            svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                            svgDoc.getElementById("price2").innerHTML = "";
                        } else if (pri.length == 2) {
                            var svgItem = svgDoc.getElementById("price1");
                            svgItem.innerHTML = innerHTML = pri[0];
                            svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                        }
                        var pri1 = order_details[0].PrixClubEurope.toString().split(".")
                        if (pri1.length == 1) {
                            svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                            svgDoc.getElementById("club_price2").innerHTML = "";
                        } else if (pri1.length == 2) {
                            svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                            svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                        }

                    }, false);



                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                    $(".loader").css('margin-top', '0%');
                }
            })
        }
    });

    $('#hangtagform').validator().on('submit', function (e) {
         if (e.isDefaultPrevented())
         {

         }
         else
         {
            e.preventDefault();
            setHangTagStatus()
         }
       })

    var mySVG = document.getElementById("svg2");
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;
        var svgItem = svgDoc.getElementById("uvc_tco");
        svgDoc.getElementById("algeria_price").innerHTML = data.order_details[0].Prix_Vente_ALGERIE != null ? data.order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",") : "";
        svgDoc.getElementById("algeria_club_price").innerHTML = data.order_details[0].Prix_Club_ALGERIE != null ? data.order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") : "";
        svgDoc.getElementById("saudi_price").innerHTML = data.order_details[0].Prix_Vente_Arabie_Saoudite != null ? data.order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",") : "";
        svgDoc.getElementById("morocco_price").innerHTML = data.order_details[0].Prix_Vente_Maroc != null ? data.order_details[0].Prix_Vente_Maroc.toString().replace(".", ",") : "";
        svgDoc.getElementById("swiss_price").innerHTML = data.order_details[0].Prix_Vente_Suisse != null ? data.order_details[0].Prix_Vente_Suisse.toString().replace(".", ",") : "";
        svgDoc.getElementById("canada_price").innerHTML = data.order_details[0].Prix_Vente_Canada != null ? data.order_details[0].Prix_Vente_Canada.toString().replace(".", ",") : "";
        svgDoc.getElementById("turkey_price").innerHTML = data.order_details[0].Prix_Vente_Turquie != null ? data.order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
        svgDoc.getElementById("us_price").innerHTML = data.order_details[0].Prix_Vente_USA != null ? data.order_details[0].Prix_Vente_USA.toString().replace(".", ",") : "";
        svgDoc.getElementById("us_club_price").innerHTML = data.order_details[0].Prix_Club_USA != null ? data.order_details[0].Prix_Club_USA.toString().replace(".", ",") : "";
        svgDoc.getElementById("morris_price").innerHTML = data.order_details[0].Prix_Vente_Ile_Maurice != null ? data.order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",") : "";
        svgDoc.getElementById("morris_club_price").innerHTML = data.order_details[0].Prix_Club_Ile_Maurice != null ? data.order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",") : "";
        svgDoc.getElementById("morroco_club_price").innerHTML = data.order_details[0].Prix_Club_Maroc != null ? data.order_details[0].Prix_Club_Maroc.toString().replace(".", ",") : "";
        svgDoc.getElementById("swiss_club_price").innerHTML = data.order_details[0].Prix_Club_Suisse != null ? data.order_details[0].Prix_Club_Suisse.toString().replace(".", ",") : "";
        svgDoc.getElementById("canada_club_price").innerHTML = data.order_details[0].Prix_Club_Canada != null ? data.order_details[0].Prix_Club_Canada.toString().replace(".", ",") : "";
        svgDoc.getElementById("turkey_club_price").innerHTML = data.order_details[0].Prix_Club_Turquie != null ? data.order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
        svgItem.innerHTML = data.order_details[0].UVC_TCO;
        var size = data.order_details[0].Size.split(" ")
        svgDoc.getElementById("theme").innerHTML = data.order_details[0].Theme;
        svgDoc.getElementById("size").innerHTML = size[0];
        svgDoc.getElementById("size_list").innerHTML = data.order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-");
        svgDoc.getElementById("cm").innerHTML = data.order_details[0].cm + "cm";
        svgDoc.getElementById("in").innerHTML = data.order_details[0].in;
        if (size[1] == "ANS") {
            svgDoc.getElementById("size_french").innerHTML = size[1];
            svgDoc.getElementById("size_english").innerHTML = "years";

        } else {
            svgDoc.getElementById("size_french").innerHTML = size[1];
            svgDoc.getElementById("size_english").innerHTML = "months";
        }
        var code = data.order_details[0].BarcodeForPricesTags.toString();
        jsbarcode(code, "bhat", svgDoc)


    }, false);
    selectpicker()
    $('.template-select.bootstrap-select').on('changed.bs.select', function () {
        if ($('.template-select option:selected').val() != "") {
            $('.sample-artwork').html('<object id="svg1" data="' + $('.template-select option:selected').val() + '" style="width:233px;height:500px" type="image/svg+xml"></object>')
            var mySVG = document.getElementById("svg1");
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc = mySVG.contentDocument;
                var pri = data.order_details[0].PrixEurope.split(".")

                if (pri.length == 1) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = data.order_details[0].PrixEurope;
                    svgDoc.getElementById("price2").innerHTML = "";
                } else if (pri.length == 2) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = pri[0];
                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                }
                var pri1 = data.order_details[0].PrixClubEurope.toString().split(".")
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
}

function orchestra_label_order_details(buyer, buyer_id, uvc, data) {
    var tr = "";
    var barcode = "";
    $.each(data.order_details, function (index, value) {
        tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td>' + value.Sum + '</td><td><input type="text" class="form-control input-sm" placeholder="Hangtag Count"><td><input type="text" class="form-control input-sm" placeholder="Specialtag Count"><td><input type="text" class="form-control input-sm" placeholder="Main & Size label Count"></td><td><input type="text" class="form-control input-sm" placeholder="WCL Count"></td></tr>';
    });
    $.each(data.barcode, function (index, value) {
        barcode += value.BuyerPONo + " | ";
    });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='/admin/order-management/'>Orders</a></li><li><a href='#'>Order List</a></li><li class='active'>Order Detail for UVC " + uvc + "</li></ol></div>")
    $('.top-content').append('<h3 class="content-heading">Order Detail for UVC ' + uvc + '</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">' + barcode + '  </span></h5>');

    
     $('.main-content').html('<input type="hidden" value="'+data.order_details[0].UVC+'" id="uvc_id"><hr/><h4>Quantity And Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">View &amp; Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p> <h5><b>Hangtag</b></h5></div><div class="row"><div class="col-md-6"><div class="img_artwork"><img src="' + base_url + '/images/ht_default.png" class="img-responsive"></div></div><div class="col-md-6"><div class="text_artwork"><table class="table table-condensed "><thead><tr><th>UVC TCO</th><th>Size ANS</th><th>Barcode</th><th>Qty</th></tr></thead><tbody><tr><td>0F008\123</td><td>2</td><td>3534444391471</td><td>2</td><tr><td>0F008\123</td><td>2</td><td>3534444391471</td><td>2</td><tr><td>0F008\123</td><td>2</td><td>3534444391471</td><td>2</td><tr><td>0F008\123</td><td>2</td><td>3534444391471</td><td>2</td><tr><td>0F008\123</td><td>2</td><td>3534444391471</td><td>2</td></tbody></table></div></div></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Specialtag</b></h5></div><div class="img_artwork"><img src="' +
        base_url + '/images/st_default.png" class="img-responsive center-block"></div></div></div><div class="col-md-4"><div class="card add-card" style="height: 280px;" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickme" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="washlabelname">Washcare Label</b></h5></div><div class="img_artwork"><img src="' +
        base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="mainlabelname">Main & Size Label</b></h5> <div class="img_artwork"><img src="' +
        base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div></div><div id="add_modal"></div><div class="col-md-4"><div style="height: 280px;" class="card add-card"><h5><b>Washcare label</b></h5><label style="color:red" class="washnameerror hidden">Please fill this field</label><input type="text" placeholder="Enter Name" class="form-control input-sm washcarename"><h5 class="text-right add-label" style="padding: 10px 0px;cursor: pointer;"  id="washcare_label_card" >Add Washcare Label <i class="fa fa-plus" aria-hidden="true"></i></h5></div></div><div class="col-md-4" ><div class="card add-card" style="height: 280px;"><h5><b>Main and Size Label</b></h5><label class="mainnameerror hidden" style="color:red" >Please fill this field</label><input type="text" placeholder="Enter Name" class="form-control input-sm mainname"><h5 class="text-right add-label" id="main_label_card" style="padding: 10px 0px;cursor: pointer;">Add Main & Size Label <i class="fa fa-plus" aria-hidden="true"></i></h5></div></div><div class="bottom-bar"><div class="modal-footer">  <button type="button" class="btn btn-xs btn-success" onclick="save_label_orders()"><i class="ion-ios-download"></i>Save</button></div></div>');

    
//    $('.main-content').html('<hr/><h4>Quantity And Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">View & Edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="' +
//        base_url + '/images/ht_default.png" class="img-responsive center-block"><p class="text-center">No Image preview</p></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">View & Edit</p><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickme" style="cursor: pointer;">View & Edit</p><h5>Washcare Label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">View & Edit</p><h5>Main & Size Label</h5></div></div></div><div id="add_modal"></div><div class="col-md-4"><div class="card add-card"><label style="color:red" class="washnameerror hidden">Please fill this field</label><input type="text" class="form-control input-sm washcarename"><p class="text-center add-label" style="padding: 53px 0px;cursor: pointer;"  id="washcare_label_card" >Add Washcare Label</p></div></div><div class="col-md-4" ><div class="card add-card"><label class="mainnameerror hidden" style="color:red" >Please fill this field</label><input type="text" class="form-control input-sm mainname"><p class="text-center add-label" id="main_label_card" style="padding: 53px 0px;cursor: pointer;">Add Main & Size Label</p></div></div>');

    $.fn.capitalize = function () {
        $.each(this, function () {
            this.value = this.value.replace(/\b[a-z]/gi, function ($0) {
                return $0.toUpperCase();
            });
            this.value = this.value.replace(/@([a-z])([^.]*\.[a-z])/gi, function ($0, $1) {
                console.info(arguments);
                return '@' + $0.toUpperCase() + $1.toLowerCase();
            });

        });
    }

    $("input").keyup(function () {
        $(this).capitalize();
    }).capitalize();

    $('#washcare_label_card').on('click', function (e) {

        if ($('.washcarename').val() != "") {
            washcare += 1;
            if (washcare <= 5) {
                    $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".washcare_label_' + washcare + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-wash" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme'+washcare+' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname'+washcare+'">' + $('.washcarename').val() + ' label</b></h5></div><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div>')   
            }
            $('.delete-wash').on('click', function (e) {
                $('#washcarelabelform'+washcare).remove()
                washcare -= 1;
                $(this).parent().parent().parent().remove();
                

            });
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data),washcare);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
   $('.extra_percentage').keyup(function () {
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_table').val(tot_qty);
            })
        } else {
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_table').val("");
            })
        }

    })

    });
    $('#main_label_card').on('click', function (e) {
        if ($('.mainname').val() != "") {
            mainlabel += 1;
            if (mainlabel <= 5) {
                $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".main_label_' + mainlabel + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-main" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickmemain'+mainlabel+'>Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p> <h5><b class="mainlabelname'+mainlabel+'">' + $('.mainname').val() + '  Label</b></h5></div><div class="img_artwork"><img src="' + base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div>')
                var data = localStorage.getItem("label_order_data");
                mainlabels(JSON.parse(data),mainlabel);
            }
            $('.delete-main').on('click', function (e) {
                $('#mainlabelsform'+mainlabel).remove()
                mainlabel -= 1;
                $(this).parent().parent().parent().remove();

            });
            $('.mainnameerror').addClass('hidden')
        } else {
            if (mainlabel < 5) {
                $('.mainname').val("")
                $('.mainnameerror').removeClass('hidden')
            }
        }
            $('.extra_percentage').keyup(function () {
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_table').val(tot_qty);
            })
        } else {
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_table').val("");
            })
        }

    })

    });


}

function selectpicker() {
    $('.selectpicker').selectpicker({
        style: 'btn-default',
        size: 4
    });

}

function treestructure(data, tree,template_id) {

    $('#' + tree).jstree({
        'core': {
            'data': data
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search"]
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
   
    $("#"+tree).on("loaded.jstree", function(e, data){
       
        $('#'+tree).jstree(true).select_node(template_id);
         var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        //alert(ids)
        //alert()
        if (template_id != "") {
            var buyer = localStorage.getItem("buyer");
           // var template_id = template_id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
             $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                    $(".loader").css('margin-top', '12%');
                },
                success: function (data) {
                    if (data.length > 0) {
                        $('#'+ids).find('.sample-artwork').html('<object id="svg2" style="height: 370px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('#'+ids).find('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
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
    });

    $('#' + tree).on('activate_node.jstree', function (e, data) {
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                    $(".loader").css('margin-top', '12%');
                },
                success: function (data) {
                    if (data.length > 0) {
                        $('#'+ids).find('.sample-artwork').html('<object id="svg2" style="height: 370px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('#'+ids).find('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
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
    });
}
      

function jsbarcode(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 343,
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
   svg.setAttribute('font-size', '50px');
   svg.setAttribute('font-family', 'calibrib');
    
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
    console.log(img.src);

    //remove temporary canvas
    canvasTmp.remove();

    //Check in browsers
    //
//                var csrf_token = $('meta[name="csrf-token"]').attr('content');
//                        $.ajax({
//                            url: base_url + "/admin/template-management/base64_to_jpeg",
//                            type: "Post",
//                            data: {
//                                src: img.src,
//                                _token: csrf_token
//                            },
//                            beforeSend: function () {
//                                $(".loader").show();
//                                $(".loader").css('margin-top', '12%');
//                            },
//                            success: function (data) {
//                               svgDoc.getElementById(id).setAttribute('xlink:href',data);
//                                
//                            },
//                            error: function (data) {
//                               
//                            }
//                        })
svgDoc.getElementById(id).innerHTML = svg_xml;
}

function setHangTagStatus()
{
    if($('#hangtagform').find('#exist_id').val() != "")
    {
         var exist_id=$('#hangtagform').find('#exist_id').val();
    }
    else
    {
         var exist_id="null";
    }
   
    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    if(template_id != ""){
        localStorage.setItem("hang_tag",exist_id);
        $('#hang_template_error').addClass('hidden')
        $("#hangtagform .left-panel").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    }
    else
    {
        $('#hang_template_error').removeClass('hidden')
    }
  
}
function setSpecialTagStatus()
{
   
    if($('#specialtagform').find('#exist_id').val() != "")
    {
         var exist_id=$('#specialtagform').find('#exist_id').val();
    }
    else
    {
         var exist_id="null";
    }
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    if(template_id != ""){
        localStorage.setItem("special_tag",exist_id);
        $('#special_template_error').addClass('hidden')
        $("#specialtagform .left-panel").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    }
    else
    {
        $('#special_template_error').removeClass('hidden')
    }
  
}
function setMainLabelStatus(dataid)
{
    if($('#mainlabelsform'+dataid).find('#exist_id'+dataid).val() != "")
    {
         main_label['mainlabelsform'+dataid]=$('#mainlabelsform'+dataid).find('#exist_id'+dataid).val();
    }
    else
    {
          main_label['mainlabelsform'+dataid]="null";
    }
  
    var main_lab = JSON.stringify(main_label);
    var template_id = $('#mainlabelsform'+dataid).find('#tree3'+dataid).jstree('get_selected', true);
    if(template_id != ""){
        localStorage.setItem("main_label",main_lab);
        $('#main_template_error'+dataid).addClass('hidden')
        $("#mainlabelsform"+dataid+" .left-panel").removeClass('active');
        $("#mainlabelsform"+dataid+" .right-panel").removeClass('active');
        $("#mainlabelsform"+dataid+" .backdrop").removeClass('active');
        $('#mainlabelsform'+dataid+' .scalable').removeClass('active');
    }
    else
    {
        $('#main_template_error'+dataid).removeClass('hidden')
    }
  
}
function setWashCareLabelStatus(dataid)
{
    if($('#washcarelabelform'+dataid).find('#exist_id'+dataid).val() != "")
    {
         wash_label['washcarelabelform'+dataid]=$('#washcarelabelform'+dataid).find('#exist_id'+dataid).val();
    }
    else
    {
          wash_label['washcarelabelform'+dataid]="null";
    }
  
    var wash_lab = JSON.stringify(wash_label);
   
        localStorage.setItem("wash_label",wash_lab);
        $("#washcarelabelform"+dataid+" .left-panel").removeClass('active');
        $("#washcarelabelform"+dataid+" .right-panel").removeClass('active');
        $("#washcarelabelform"+dataid+" .backdrop").removeClass('active');
        $('#washcarelabelform'+dataid+' .scalable').removeClass('active');
   
  
}
function setHangTag(id)
{
    var delivery_date = $('#hang_delivery_date').val();
    delivery_date =  delivery_date.split('/');
    delivery_date_fin = delivery_date[2] + "-" + delivery_date[0] + "-" +delivery_date[1];
    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    template_front_id = template_id[0].id;
    var template_back_id = $('#hang_tag_id').val();
    var tot_qty_table=[];
    var uvc_tco=[];
    var size=[];
    $('#hangtagform tbody tr').each(function(){
        if($(this).find('.tot_qty_table').val() != undefined)
            {
                  tot_qty_table.push($(this).find('.tot_qty_table').val()) 
                   uvc_tco.push($(this).find('.uvc_tco').text())
                   size.push($(this).find('.size').text())
            }
     
    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
     var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //alert($('#uvc_id').val())
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_hang_tag",
            type: "Post",
            data: {
                delivery_date: delivery_date_fin,
                template_front_id: template_front_id,
                template_back_id: template_back_id,
                tot_qty_table: tot_qty_table_fin,
                id: id,
                uvc_tco: uvc_tco_fin,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top', '12%');
            },
            success: function (data) {
              
            },
            error: function (data) {

            }
        })

}
function setSpecialTag(id)
{
    var delivery_date = $('#special_delivery_date').val();
    delivery_date =  delivery_date.split('/');
    delivery_date_fin = delivery_date[2] + "-" + delivery_date[0] + "-" +delivery_date[1];
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    template_front_id = template_id[0].id;
    var tot_qty_table=[];
    var uvc_tco=[];
    var size=[];
    $('#specialtagform tbody tr').each(function(){
        if($(this).find('.tot_qty_table').val() != undefined)
            {
                  tot_qty_table.push($(this).find('.tot_qty_table').val()) 
                   uvc_tco.push($(this).find('.uvc_tco').text())
                   size.push($(this).find('.size').text())
            }
     
    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
     var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //alert($('#uvc_id').val())
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_special_tag",
            type: "Post",
            data: {
                delivery_date: delivery_date_fin,
                template_front_id: template_front_id,
                id: id,
                tot_qty_table: tot_qty_table_fin,
                uvc_tco: uvc_tco_fin,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top', '12%');
            },
            success: function (data) {
              
            },
            error: function (data) {

            }
        })

}
function setMainLabel(index,dataid,id)
{
    var delivery_date = $('#main_delivery_date'+dataid).val();
    alert(delivery_date)
    delivery_date =  delivery_date.split('/');
    delivery_date_fin = delivery_date[2] + "-" + delivery_date[0] + "-" +delivery_date[1];
    var template_id = $('#'+index).find('#tree3'+dataid).jstree('get_selected', true);
    var template_name = $('#'+index).find('.mainlabelnameqty'+dataid).text();
    template_front_id = template_id[0].id;
    var tot_qty_table=[];
    var uvc_tco=[];
    var size=[];
    $('#'+index+' tbody tr').each(function(){
        if($(this).find('.tot_qty_table').val() != undefined)
            {
                  tot_qty_table.push($(this).find('.tot_qty_table').val()) 
                   uvc_tco.push($(this).find('.uvc_tco').text())
                   size.push($(this).find('.size').text())
            }
     
    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
     var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //alert($('#uvc_id').val())
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_main_label",
            type: "Post",
            data: {
                delivery_date: delivery_date_fin,
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                tot_qty_table: tot_qty_table_fin,
                uvc_tco: uvc_tco_fin,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top', '12%');
            },
            success: function (data) {
              
            },
            error: function (data) {

            }
        })

}
function setWashLabel(index,dataid,id)
{
    var composition = localStorage.getItem('wash_composition');
    
    var wash_fins = JSON.parse(composition);
    var wash_finals = JSON.stringify(wash_fins['wash_composition'+dataid]);
    
    var delivery_date = $('#wash_delivery_date'+dataid).val();
    delivery_date =  delivery_date.split('/');
    delivery_date_fin = delivery_date[2] + "-" + delivery_date[0] + "-" +delivery_date[1];
    var template_front_id = $('#wash_label_id').val();
    var template_name = $('#'+index).find('.washlabelnameqty'+dataid).text();
    var washing_select = $('.washing_select'+dataid+' option:selected').attr('data-id');
    var whitening_select = $('.whitening_select'+dataid+' option:selected').attr('data-id');
    var dryer_select = $('.dryer_select'+dataid+' option:selected').attr('data-id');
    var ironing_select = $('.ironing_select'+dataid+' option:selected').attr('data-id');
    var cleaning_select = $('.cleaning_select'+dataid+' option:selected').attr('data-id');
    var made_in_select = $('.made_in_select'+dataid+' option:selected').attr('data-id');
   // var care_instruction_select = $('.care_instruction_select'+dataid+' option:selected').val();
    
        var care_instruction = $('.care_instruction_select'+dataid+' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function(index, brand){
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');
       console.log(care_instruction_select_fin);
    
    //template_front_id = template_id[0].id;
    var tot_qty_table=[];
    var uvc_tco=[];
    var size=[];
    $('#'+index+' tbody tr').each(function(){
        if($(this).find('.tot_qty_table').val() != undefined)
            {
                  tot_qty_table.push($(this).find('.tot_qty_table').val()) 
                   uvc_tco.push($(this).find('.uvc_tco').text())
                   size.push($(this).find('.size').text())
            }
     
    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
     var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //alert($('#uvc_id').val())
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_wash_label",
            type: "Post",
            data: {
                delivery_date: delivery_date_fin,
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                washing_select: washing_select,
                whitening_select: whitening_select,
                dryer_select: dryer_select,
                ironing_select: ironing_select,
                cleaning_select: cleaning_select, 
                made_in_select: made_in_select,
                care_instruction_select: care_instruction_select_fin,
                composition: wash_finals,
                tot_qty_table: tot_qty_table_fin,
                uvc_tco: uvc_tco_fin,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top', '12%');
            },
            success: function (data) {
              
            },
            error: function (data) {

            }
        })

}
function save_label_orders()
{
    if(localStorage.getItem("hang_tag") != null)
    {
         setHangTag(localStorage.getItem("hang_tag"))
    }
    if(localStorage.getItem("special_tag") != null)
    {
         setSpecialTag(localStorage.getItem("special_tag"))
    }
    if(localStorage.getItem("main_label") != null)
    {
        var main_json = JSON.parse(localStorage.getItem("main_label"));

        $.each(main_json, function (index, value) {
            var data_array = index.split('mainlabelsform');
            setMainLabel(index,data_array[1],value)
        });
        //setMainLabel()
    }
    if(localStorage.getItem("wash_label") != null)
    {
        var wash_json = JSON.parse(localStorage.getItem("wash_label"));

        $.each(wash_json, function (index, value) {
            var data_array = index.split('washcarelabelform');
            setWashLabel(index,data_array[1],value)
        });
        //setMainLabel()
    }
}
function setSolidStickers()
{
    var tot_qty_table_poly=[]; 
    var tot_qty_carton=[];
    
    var delivery_date = $('#solid_delivery_date').val();
    delivery_date =  delivery_date.split('/');
    delivery_date_fin = delivery_date[2] + "-" + delivery_date[0] + "-" +delivery_date[1];
    $('#solidform tbody tr').each(function(){
        if($(this).find('.tot_qty_table_poly').val() != undefined)
            {
                  tot_qty_table_poly.push($(this).find('.tot_qty_table_poly').val()); 
            }    
        if($(this).find('.tot_qty_table_carton').val() != undefined)
            {
                  tot_qty_carton.push($(this).find('.tot_qty_table_carton').val());
            }
     
    })
    tot_qty_table_poly_fin = tot_qty_table_poly.join(','); 
    tot_qty_carton_fin = tot_qty_carton.join(',');
    
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //alert($('#uvc_id').val())
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_solid_stickers",
            type: "Post",
            data: {
                delivery_date: delivery_date_fin,
               // template_front_id: template_front_id,
                tot_qty_poly: tot_qty_table_poly_fin,
                tot_qty_carton: tot_qty_carton_fin,
                uvc: $('#uvc_id').val(),  
                carton_template_id: $('#carton_template_id').val(), 
                polybag_template_id: $('#polybag_template_id').val(), 
                height: $('#modal_height').val(), 
                age: $('#age').val(), 
                po: $('#po').val(),
                //size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top', '12%');
            },
            success: function (data) {
              
            },
            error: function (data) {

            }
        })
}