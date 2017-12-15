//this is the start
var washcare = 0;
var mainlabel = 0;
var composition_percentage_global = 0;

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
                $('.main-content').html('<div class="container-fluid"><div class="row"><div class="col-md-4"><div class="card"><h4>Create Order For UK</h4><hr/><form><label>Order type</label><select class="form-control"><option>PO Upload</option><option>Manual Entry</option></select><br/><label>Select Template</label><div><input class="search-input form-control" placeholder="Search for Awesomeness"></div><div id="jstree"></div></form><label>Upload PO</label><div class="row"><h5>Multiple File upload</h5><div class="col-md-6"></div><div class="col-md-6"></div></div><div class="row"><h5>Single File upload</h5><div class="col-md-12"></div></div><br/><button class="btn btn-primary btn-block">Create Order</button></div></div><div class="col-md-8"><div class="card"><h4>Sample Template for reference</h4><hr/><div class="row"><div class="col-md-6"><img src="../../images/img1.png" class="img-responsive center-block pad-top"></div><div class="col-md-6"><img src="../../images/img1.png" class="img-responsive center-block pad-top"></div><div class="clearfix"></div><div class="col-md-6"><img src="../../images/img1.png" class="img-responsive center-block pad-top"></div><div class="col-md-6"><img src="../../images/img1.png" class="img-responsive center-block pad-top"></div></div></div></div></div></div>')

                $(".search-input").keyup(function() {
                var searchString = $(this).val();
                    console.log(searchString);
                    $('#jstree').jstree('search', searchString);
                });

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
                        "plugins": ["types", "search"]
                    });
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
                } else {
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
            tr += '<tr><td>' + value.Size + '</td><td>' + value.Quantity + '</td><td>' + data.orchestra_parcel_details[0].ParcelBarCode + '</td></tr>';

        });
        var head_qty = "Quantity for Standard Stickers";
        var head_qty1 = "Standard Parcel/Carton Sticker";
        var standard_div = '<div class="form-group"><label>Total Polybags per carton: </label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" placeholder="Polybag count" class="form-control input-sm"></div> <div class="form-group"><label>Final Order Qty including Wastage: </label><div class="input-group"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Set of 2 Pcs." class="form-control input-sm"><span class="input-group-addon">sets</span></div></div><hr><h4>Standard Polybag Sticker</h4><div class="form-group"><label>Order Quantity</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div><div class="form-group"><label>Final Order Qty including Wastage</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div>';
    } else {
        var disabled = 'disabled="disabled"';
        var ttotal = '<td class="text-center">0</td><td class="text-center">0</td>';
        var thead = '<thead><tr><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty<br/>incl. wastage</th><th>Parcel Sticker Qty<br/>(in sets)</th></tr></thead>';
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
            tr += '<tr><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td>' + value.Quantity + '</td><td><input type="number" value="0" class="form-control input-sm"><td><input type="number" value="0" class="form-control input-sm"></td></tr>';
        });
        var head_qty = "Quantity for Solid Size Stickers";
        var head_qty1 = "Solid Size Parcel/Carton Sticker";
        var standard_div = '';
    }

    var option = "<option>Age Group</option>";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option value=" + value.color + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='#'>Order Management</a></li><li><a href='#' onclick=back_sticker_orders('" + buyer + "','" + buyer_id + "')>Sticker Orders</a></li><li class='active'>Sticker Order Details for UVC " + uvc + " and PO " + po + "</li></ol></div>")

    $('.top-content').append('  <h3><i class="ion-ios-browsers-outline"></i>Sticker Order Details for UVC ' + uvc + ' and PO ' + po + '</h3><hr/><h5><span class="sticker-details">Supplier: <strong><span id="supplier">' + data.orchestra_parcel_details[0].SupplierName + '</span></strong> </span> <span class="sticker-details">Edition: <strong><span id="edition">' + data.orchestra_parcel_details[0].EditionNumber + '</span></strong> </span><span class="sticker-details">Version: <strong><span id="version">' + data.orchestra_parcel_details[0].VersionNumber + '</span></strong></span><span class="sticker-details">Style: <strong><span id="style">' + data.orchestra_parcel_details[0].NomApple + '</span></strong></span><span class="sticker-details">Color: <strong><span id="color">' + data.orchestra_parcel_quantity_details[0].Color + '</span></strong></span><span class="sticker-details">Age Group: <strong><span id="ageGroup">  <select name="carlist" id="age" form="carform">' + option + '</select></span></strong> </span><span class="sticker-details">Delivery Date: <strong><span id="dDate"> <input type="date" ></span></strong></span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>' + head_qty + '</h4><div class="table-responsive"><table class="table table-bordered table-hover">' + thead + '<tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="2">Total</td><td>' + data.orchestra_parcel_details[0].Quantity + '</td>' + ttotal + '</tr></tbody></table></div></div><div class="col-md-4"><h4>' + head_qty1 + '</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" value="395" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume"></span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left: -5px;">/</span> <div class="col-md-6" style="margin-top: -34px;"><input ' + disabled + ' data-toggle="modal" data-target=".needed_modal" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div>' + standard_div + ' </div></div>');

    $('.main-content').html('<hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right" style="cursor:pointer">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:575px;height:378;border: 2px #000 solid;" class="center-block" data="' + data.carton_template + '" type="image/svg+xml"></object></div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ')>Print</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" style="cursor:pointer">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px" data="' + data.polybag_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:580px;height:500px" data="' + data.polybag_template + '" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ')>Print</button></div></div></div></div> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer">  <button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save</button></div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

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
        svgDoc1.getElementById("round").style.fill = $(this).val();

    });


}

function printDiv(data, isstd) {
    if (data == "svg4" && isstd == "0") {
        var a = document.getElementById(data);
        var svgDoc = a.contentDocument;
        var svgRoot = svgDoc.documentElement;
        Popupsmall(svgRoot);
    } else {
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

function Popupsmall(data) {
    var s = new XMLSerializer();
    var d = data;
    var str = s.serializeToString(d);
    var mywindow = window.open('', 'new div', 'class=printmedia');
    mywindow.document.write('<html><head><title></title>');
    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
    mywindow.document.write('</head><body><div id="printmedia" style="border:1px solid #000;width:100mm;height:70mm">');
    mywindow.document.write(str);
    mywindow.document.write('</div>');
    mywindow.print();
    mywindow.close();
    return true;
}

function barcode_polybag(id, data) {
    var mySVG = document.getElementById(id);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;
        var svgItem = svgDoc.getElementById("uvc_tco");

        svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
        svgDoc.getElementById("round").style.fill = $('#age').val();

        if (data.template_name == "Standard") {
            var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
            svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
        } else {
            var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
            svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_quantity_details[0].Size;
            svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_quantity_details[0].Color;

        }
        jsbarcode(code, 'barcode', svgDoc)
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
            orchestra_label_order_details(buyer, buyer_id, uvc, data)
            hangtagmodal(data);
            specialtags(data);
            mainlabels(data);
            washcarelabels(data);

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function washcarelabels(data) {
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
        made_option += "<option  data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
        instruction_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-russian='" + value.russian + "' data-chinese='" + value.chinese + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' data-turkish='" + value.turkish + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
        if (value.category_id == 1) {
            washing_option += "<option data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.cofreet_symbols + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.cofreet_symbols + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.cofreet_symbols + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.cofreet_symbols + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.cofreet_symbols + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        }

    });


    var tr = "";
    $.each(data.order_details, function (index, value) {
        tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" required placeholder="WCL Count"><div class="help-block with-errors"></div></div></td></tr>';
    });
    $('.top-content').append('<form id="washcarelabelform" data-toggle= "validator"><div class="left-panel col-md-9"> <div class="div-max"> <div class="col-md-3 "> <h4>Options</h4> <hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select State</label><select data-live-search="true" class="form-control selectpicker input-sm" required> <option value="">Select Option</option><option>Applicable</option> <option>Not Applicable</option> </select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Delivery Date</label> <div class="input-group date" data-provide="datepicker" id="datePicker1"> <input type="text" class="form-control" required><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </div></div> <div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select data-live-search="true" class="form-control selectpicker input-sm washing_select" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select data-live-search="true" class="form-control selectpicker input-sm whitening_select" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select data-live-search="true" class="form-control selectpicker input-sm dryer_select" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select data-live-search="true" class="form-control selectpicker input-sm ironing_select" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select data-live-search="true" class="form-control selectpicker input-sm cleaning_select" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div> </div> <div class="col-md-6"> <h4>Art Work</h4> <hr> <div class="section-1 sections scroll"> <object id="washcare_svg1" style=width:100%" data="/storage/app/template/orchestra_wcl.svg" type="image/svg+xml"></object> </div> </div> <div class="col-md-3 "> <h4>Options</h4> <hr> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select data-live-search="true" class="form-control selectpicker input-sm made_in_select" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select data-live-search="true" class="form-control selectpicker input-sm care_instruction_select" required multiple>' + instruction_option + '</select><div class="help-block with-errors"></div></div> <div class="section-3 sections"> <h5>Parts</h5> <select class="selectpicker parts_select"  data-live-search="true"> ' + parts_option + '  </select> </div> <div class="section-1 sections"> <h5>Compositions</h5> <select class="selectpicker composition_select"  data-live-search="true">  ' + composition_option + '  </select> </div> <h5>Percentage</h5> <input type="number" min="1" max="100" class="form-control input-sm composition_percent">  <button class="btn btn-info pull-right input-sm add_to_list" onclick="add_to_list()">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty"></tbody></table><button class="btn btn-info pull-right input-sm add_to_page" disabled onclick="add_to_page()">Add To Page</button> </div> </div> </div> <div class="right-panel col-md-3"> <div class="div-max"> <h4>Quantity Info for WashCare Label</h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');

    $('#washcarelabelform').validator()

    $('.extra_percentage').keyup(function () {
        if ($(this).val() != "") {
            var per = $(this).val();
            $('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_table').val(tot_qty);
            })
        } else {
            $('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_table').val("");
            })
        }

    })

    selectpicker()

    $('.date').datepicker();
    $("#clickme").click(function () {
        $(".backdrop").toggleClass('active');
        $(".right-panel").toggleClass('active');
        $('.scalable').toggleClass('active');
        $(".left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $(".left-panel").removeClass('active');
        $(".right-panel").removeClass('active');
        $(".backdrop").removeClass('active');
        $('.scalable').removeClass('active');
    })

    var mySVG = document.getElementById("washcare_svg1");
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;


        $('.washing_select.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select option:selected').val());
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select option:selected').val());
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select option:selected').val());
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select.bootstrap-select').change(function () {
            if ($('.ironing_select option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select option:selected').val());
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select.bootstrap-select').change(function () {
            if ($('.cleaning_select option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select option:selected').val());
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select option:selected').val() != "") {

                var text_for_mad_in = '<p>' + $('select.made_in_select').find(':selected').data('french') + " - " + $('select.made_in_select').find(':selected').data('english') + " - " + $('select.made_in_select').find(':selected').data('spanish') + " - " + $('select.made_in_select').find(':selected').data('portuguese') + " - " + $('select.made_in_select').find(':selected').data('italian') + " - " + $('select.made_in_select').find(':selected').data('dutch') + " - " + $('select.made_in_select').find(':selected').data('german') + " - " +
                    $('select.made_in_select').find(':selected').data('russian') + " - " +
                    $('select.made_in_select').find(':selected').data('chinese') + " - " +
                    $('select.made_in_select').find(':selected').data('arabic') + " - " +
                    $('select.made_in_select').find(':selected').data('greek') + " - " +
                    $('select.made_in_select').find(':selected').data('turkish') + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select option:selected').each(function () {
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

function add_to_list() {

    if ($('.composition_select option:selected').val() != "Select Option" && $('.composition_percent').val() != "") {
        composition_percentage_global += parseInt($('.composition_percent').val());
        if (composition_percentage_global <= "100") {
            composition_percentage_global = composition_percentage_global;
            $('#composition_table_qty').append("<tr><td class='comp'>" + $('.composition_select option:selected').text() + "</td><td>" + parseInt($('.composition_percent').val()) + "</td></tr>")
        } else {
            composition_percentage_global -= parseInt($('.composition_percent').val());
        }

        $('.parts_select').prop('disabled', 'true')
    }
    if (composition_percentage_global == "100") {
        $('.composition_select').prop('disabled', 'true')
        $('.composition_percent').prop('disabled', 'true')
        $('.add_to_list').prop('disabled', 'true')
        $('.add_to_page').removeAttr('disabled')
    }

}

function add_to_page() {

    composition_percentage_global = 0;
    var mySVG = document.getElementById("washcare_svg1");
    var svgDoc = mySVG.contentDocument;
    var a = [];
    $('#composition_table_qty tr').each(function () {
        var comp = $(this).find('.comp').text();
        a.push(comp)
    })
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer = localStorage.getItem("buyer");
    if ($('.parts_select option:selected').val() != "Select Option") {
        svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph">' + $('select.parts_select').find(':selected').data('french') + "-" + $('select.parts_select').find(':selected').data('english') + "-" + $('select.parts_select').find(':selected').data('spanish') + "-" + $('select.parts_select').find(':selected').data('portuguese') + "-" + $('select.parts_select').find(':selected').data('italian') + "-" + $('select.parts_select').find(':selected').data('dutch') + "-" + $('select.parts_select').find(':selected').data('german') + "-" + $('select.parts_select').find(':selected').data('russian') + "-" + $('select.parts_select').find(':selected').data('chinese') + "-" + $('select.parts_select').find(':selected').data('arabic') + "-" + $('select.parts_select').find(':selected').data('greek') + "-" + $('select.parts_select').find(':selected').data('turkish') + '</p>';
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
                $('#composition_table_qty tr').each(function () {
                    if ($(this).find('.comp').text() == value.english) {
                        svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph">' + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german + "-" + value.russian + "-" + value.chinese + "-" + value.arabic + "-" + value.greek + "-" + value.turkish + '</p>';
                    }
                })
            });

            $(".add_to_list").prop("disabled", false);
            $('.composition_select').prop('disabled', false)
            $('.composition_percent').prop('disabled', false)
            $('#composition_table_qty').html("")
            $('.parts_select').prop('disabled', false)
            $('.add_to_page').attr('disabled', 'disabled')
            $('.composition_percent').val("");
            $(".composition_select").val('');
            $('.composition_select').selectpicker('refresh');
            $(".parts_select").val('');
            $('.parts_select').selectpicker('refresh');

        },
        error: function (data) {

        },
        complete: function () {}
    })


}

function mainlabels(data) {
    var tr = "";
    $.each(data.order_details, function (index, value) {
        tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" required placeholder="Main & Size Count"><div class="help-block with-errors"></div></div></td></tr>';
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="mainlabelsform" data-toggle= "validator"><div class="left-panel col-md-9"id="HandTag"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker"id="datePicker1"><input type="text"class="form-control"required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label>  <div><input type="text" id="tree3_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree3" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><hr> <div class="section-1 sections scroll"><div class="loader"></div><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max"> <h4>Quantity Info for Main & Size Labels</h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');

    $("#clickmemain").click(function () {
        $("#mainlabelsform .backdrop").toggleClass('active');
        $("#mainlabelsform .right-panel").toggleClass('active');
        $('#mainlabelsform .scalable').toggleClass('active');
        $("#mainlabelsform .left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $("#mainlabelsform.left-panel").removeClass('active');
        $("#mainlabelsform.right-panel").removeClass('active');
        $("#mainlabelsform.backdrop").removeClass('active');
        $('#mainlabelsform.scalable').removeClass('active');
    })

    treestructure(data.treeviewmain, "tree3");
    $('#mainlabelsform').validator()
}

function specialtags(data) {
    var tr = "";
    $.each(data.order_details, function (index, value) {
        tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panel col-md-9"id="HandTag"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker"id="datePicker1"><input type="text"class="form-control"required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><hr> <div class="section-1 sections scroll"><div class="loader"></div><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max"> <h4>Quantity Info for Hang Tag</h4> <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');

    $("#clickmespecial").click(function () {
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panel").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $("#specialtagform.left-panel").removeClass('active');
        $("#specialtagform.right-panel").removeClass('active');
        $("#specialtagform.backdrop").removeClass('active');
        $('#specialtagform.scalable').removeClass('active');
    })



    treestructure(data.treeviewspecial, "tree2");

    $('#specialtagform').validator()
}

function hangtagmodal(data) {
    var order_details = data.order_details;
    var tr = "";
    $.each(data.order_details, function (index, value) {
        tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input type="text" class="form-control input-sm tot_qty_table" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
    });
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><div class="left-panel col-md-9"id="HandTag"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select State</label><select data-live-search="true"class="form-control selectpicker input-sm"required><option value="">Select Option</option><option>Applicable</option><option>Not Applicable</option></select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Delivery Date</label><div class="input-group date"data-provide="datepicker"id="datePicker1"><input type="text"class="form-control"required><div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div></div><div class="col-md-4"><h4>Front</h4><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections"><object class="center-block" id="svg2" style="height:486px" data="' + data.orchestra_template + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panel col-md-3"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>' + data.total_qty + '</td><td class="text-center">0</td></tr></tbody></table></div> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');

    $("#clickmehang").click(function () {
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panel").toggleClass('active');
    })

    $(".backdrop, #close").click(function () {
        $("#hangtagform.left-panel").removeClass('active');
        $("#hangtagform.right-panel").removeClass('active');
        $("#hangtagform.backdrop").removeClass('active');
        $('#hangtagform.scalable').removeClass('active');
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

    $('#tree1').on('activate_node.jstree', function (e, data) {
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
                        $('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
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

    $('#hangtagform').validator()
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

    
     $('.main-content').html('<hr/><h4>Quantity And Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">Click to edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p> <h5><b>Hangtag</b></h5></div><div class="img_artwork"><img src="' +
        base_url + '/images/ht_default.png" class="img-responsive center-block"></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">Click to edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Specialtag</b></h5></div><div class="img_artwork"><img src="' +
        base_url + '/images/st_default.png" class="img-responsive center-block"></div></div></div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickme" style="cursor: pointer;">Click to edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Washcare Label</b></h5></div><div class="img_artwork"><img src="' +
        base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">Click to edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5>Main & Size Label</h5> <div class="img_artwork"><img src="' +
        base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div></div><div id="add_modal"></div><div class="col-md-4"><div class="card add-card"><h5><b>Washcare label</b></h5><label style="color:red" class="washnameerror hidden">Please fill this field</label><input type="text" placeholder="Enter Name" class="form-control input-sm washcarename"><h5 class="text-right add-label" style="padding: 10px 0px;cursor: pointer;"  id="washcare_label_card" >Add Washcare Label <i class="fa fa-plus" aria-hidden="true"></i></h5></div></div><div class="col-md-4" ><div class="card add-card"><h5><b>Main and Size Label</b></h5><label class="mainnameerror hidden" style="color:red" >Please fill this field</label><input type="text" placeholder="Enter Name" class="form-control input-sm mainname"><h5 class="text-right add-label" id="main_label_card" style="padding: 10px 0px;cursor: pointer;">Add Main & Size Label <i class="fa fa-plus" aria-hidden="true"></i></h5></div></div>');

    
//    $('.main-content').html('<hr/><h4>Quantity And Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">Click to edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="' +
//        base_url + '/images/ht_default.png" class="img-responsive center-block"><p class="text-center">No Image preview</p></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">Click to edit</p><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickme" style="cursor: pointer;">Click to edit</p><h5>Washcare Label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">Click to edit</p><h5>Main & Size Label</h5></div></div></div><div id="add_modal"></div><div class="col-md-4"><div class="card add-card"><label style="color:red" class="washnameerror hidden">Please fill this field</label><input type="text" class="form-control input-sm washcarename"><p class="text-center add-label" style="padding: 53px 0px;cursor: pointer;"  id="washcare_label_card" >Add Washcare Label</p></div></div><div class="col-md-4" ><div class="card add-card"><label class="mainnameerror hidden" style="color:red" >Please fill this field</label><input type="text" class="form-control input-sm mainname"><p class="text-center add-label" id="main_label_card" style="padding: 53px 0px;cursor: pointer;">Add Main & Size Label</p></div></div>');

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
                    $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".washcare_label_' + washcare + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-wash" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px">Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b>' + $('.washcarename').val() + ' label</b></h5></div><div class="img_artwork"></div></div></div>')   
            }
            $('.delete-wash').on('click', function (e) {
                washcare -= 1;
                $(this).parent().parent().parent().remove();

            });
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }

    });
    $('#main_label_card').on('click', function (e) {
        if ($('.mainname').val() != "") {
            mainlabel += 1;
            if (mainlabel <= 5) {
                $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".main_label_' + mainlabel + '" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right delete-main" style="cursor: pointer;">Click to delete</p><p class="pull-right" style="cursor: pointer;margin-right:5px">Click to edit</p> <h5>' + $('.mainname').val() + '  Label</h5></div><div class="img_artwork"></div></div></div>')
            }
            $('.delete-main').on('click', function (e) {
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

    });


}

function selectpicker() {
    $('.selectpicker').selectpicker({
        style: 'btn-default',
        size: 4
    });

}

function treestructure(data, tree) {

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

    $('#' + tree).on('activate_node.jstree', function (e, data) {
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
                        $('.sample-artwork').html('<object id="svg2" style="height: 370px;" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                    } else {
                        $('.sample-artwork').html('<img style="width:160px;height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
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
        font: 'Calibri, Candara, Segoe,"Segoe UI", Optima, Arial, sans-serif'
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');

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