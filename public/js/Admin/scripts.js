function label_orders()
{
    $('.main-content').html('<div class="loader"></div>');
    $( ".sub_modules a" ).removeClass( "active" )
    $('.label_orders a').addClass("active")
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/label-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
             $('.main-content').html(table(data));
             $('#datatable-example-2').DataTable();
             $("#datatable-example-2_filter input").addClass('form-control input-sm');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
             $('#datatable-example-2_length select').addClass('form-control input-sm');
            $('.breadcrumb').html('<li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Order Management</a></li><li  class="active">Label Orders</li>')
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function back_label_orders(buyer,buyer_id)
{

    $('.main-content').html('<div class="loader"></div>');
    $( ".sub_modules a" ).removeClass( "active" )
    $('.label_orders a').addClass("active")
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/back-label-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
            var option="<option></option>";
            $.each(data.buyer, function (index, value) {
                option+="<option value="+value.id+">"+value.buyer_name+"</option>";
            });
             $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Order Management</a></li><li class="active">Label Orders</li></ol><form class="navbar-form pull-right" style="margin-top:0px;"><label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label><select class="js-example-responsive buyer-select">'+option+'</select></form><h3><i class="ion-ios-browsers-outline"></i>Order Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a></div><ul class="sub-nav-list"><li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li><li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li><li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li></ul><hr/></div>')
             $('.main-content').html(table(data.orders));
             $('#datatable-example-2').DataTable();
             $("#datatable-example-2_filter input").addClass('form-control input-sm');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
             $('#datatable-example-2_length select').addClass('form-control input-sm');
             $('.label_orders a').addClass("active")
             $('.buyer-select').val(buyer_id)
             $('.buyer-select').select2({
                  placeholder: "Buyer Listing",
                  allowClear: true
             });
            $('.buyer-select').change(function() {
                  if($(this).val() != "")
                  {
                       sticker_orders()
                  }
                  else
                  {
                      location.reload()
                  }
            });
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}
function back_sticker_orders(buyer,buyer_id)
{

    $('.main-content').html('<div class="loader"></div>');
    $( ".sub_modules a" ).removeClass( "active" )
    $('.sticker_orders a').addClass("active")
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/back-sticker-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
            var option="<option></option>";
            $.each(data.buyer, function (index, value) {
                option+="<option value="+value.id+">"+value.buyer_name+"</option>";
            });
             $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Order Management</a></li><li class="active">Sticker Orders</li></ol><form class="navbar-form pull-right" style="margin-top:0px;"><label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label><select class="js-example-responsive buyer-select">'+option+'</select></form><h3><i class="ion-ios-browsers-outline"></i>Order Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a></div><ul class="sub-nav-list"><li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li><li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li><li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li></ul><hr/></div>')
             $('.main-content').html(table(data.orders));
             $('#datatable-example-2').DataTable();
             $("#datatable-example-2_filter input").addClass('form-control input-sm');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
             $('#datatable-example-2_length select').addClass('form-control input-sm');
             $('.sticker_orders a').addClass("active")
             $('.buyer-select').val(buyer_id)
             $('.buyer-select').select2({
                  placeholder: "Buyer Listing",
                  allowClear: true
             });
            $('.buyer-select').change(function() {
                  if($(this).val() != "")
                  {
                       sticker_orders()
                  }
                  else
                  {
                      location.reload()
                  }
            });
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function sticker_orders()
{
    $('.main-sub-menu').removeClass('hidden');
    $( ".sub_modules a" ).removeClass( "active" )
     $('.sticker_orders a').addClass("active")
    $('.main-content').html('<div class="loader"></div>');
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/sticker-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
             $('.main-content').html(table(data));
             $('#datatable-example-2').DataTable();
             $("#datatable-example-2_filter input").addClass('form-control input-sm');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
              $('#datatable-example-2_length select').addClass('form-control input-sm');
             $('.breadcrumb').html('<li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Order Management</a></li><li class="active">Sticker Orders</li>')
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function orchestra_create_template_view(obj)
{
    $('.main').html(jumbotron('Create Template','Template Management'));
}

function jumbotron(name,main_menu)
{
    return '<div class="loader"></div><div class="jumbotron jumbotron-1"><div class="container-fluid"><div class="row"><div class="col"><ol class="breadcrumb icon-angle-right"><li><a href="#">Dashboard</a></li><li class="active">'+name+'</li></ol></div></div><div class="row"><div class="col"><a class="btn btn-info btn-rounded btn-icon" href="#"><i class="fa fa-plus"></i><span class="btn-text">Create Order</span></a><h4>'+main_menu+'</h4><h5>'+name+'</h5></div></div></div></div>';
}
function table(data)
{
    var th = "";
    var tr = "";
    var columnsIn = data[0];
    for(var key in columnsIn){
         if(key != "id")
         {
            th += "<th>"+key+"</th>";
         }
    }
    $.each(data, function (index, value) {
        tr += '<tr id="'+value.id+'">';
        for(var key in columnsIn){
            if(key != "id")
            {
                if(key == "Type")
                {
                    var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    tr += '<td>'+Isstd+'</td>';
                }
                else if(key == "Disney")
                {
                    var Disney = value[key] == "0" ? "No" : "Yes";
                    tr += '<td>'+Disney+'</td>';
                }
                else if(key == "UVC")
                {
                    tr += '<td><a href="#" onclick=order_details("'+value[key]+'","'+value.BuyerPONo+'")>'+value[key]+'</a></td>';
                }
                else
                {
                    tr += '<td>'+value[key]+'</td>';
                }
            }
        }
        tr += '</tr>';
    })
    return'<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>'+th+'</tr></thead><tbody>'+tr+'</tbody></table></div></div></div></div></div></div>';

}
function order_details(uvc,po)
{
   if($('.sub-nav-list').find('.active').text() == "Sticker Orders") 
   {
       sticker_order_details(uvc,po)
   }
    else
    {
        label_order_details(uvc)
    }
    
}
function sticker_order_details(uvc,po)
{
    $('.main-content').html('<div class="loader"></div>');
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var buyer_id=$('.buyer-select').val()
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/sticker-order-details",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token,po:po},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
            orchestra_sticker_order_details(buyer,buyer_id,uvc,data,po)
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function orchestra_sticker_order_details(buyer,buyer_id,uvc,data,po)
{
    var tr="";
    if(data.template_name == "Standard")
    {
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
         tr+='<tr><td>'+value.Size+'</td><td>'+data.orchestra_parcel_details[0].ParcelBarCode+'</td><td>'+value.Quantity+'</td><td><input type="number" value="0" class="form-control input-sm"><td><input type="number" value="0" class="form-control input-sm"></td></tr>';
           
     });
         var head_qty="Quantity for Standard Stickers";
        var head_qty1="Standard Parcel/Carton Sticker";
    }
    else
    {
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
         tr+='<tr><td>'+value.Size+'</td><td>'+value.BarcodeForPricesTags+'</td><td>'+value.Quantity+'</td><td><input type="number" value="0" class="form-control input-sm"><td><input type="number" value="0" class="form-control input-sm"></td></tr>';
     });
        var head_qty="Quantity for Solid Size Stickers";
        var head_qty1="Solid Size Parcel/Carton Sticker";
    }
     
     var option="<option>Age Group</option>";
            $.each(data.orchestra_age_group, function (index, value) {
                option+="<option value="+value.id+" style='background-color:"+value.color+"'>"+value.age_group_name+"</option>";
     });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='#'>Order Management</a></li><li><a href='#' onclick=back_sticker_orders('"+buyer+"','"+buyer_id+"')>Sticker Orders</a></li><li class='active'>Sticker Order Details for UVC "+uvc+" and PO "+po+"</li></ol></div>")
    
      $('.top-content').append('  <h3><i class="ion-ios-browsers-outline"></i>Sticker Order Details for UVC 0F0006 and PO '+po+'</h3><hr/><h5><span class="sticker-details">Supplier: <strong><span id="supplier">'+data.orchestra_parcel_details[0].SupplierName+'</span></strong> </span> <span class="sticker-details">Edition: <strong><span id="edition">'+data.orchestra_parcel_details[0].EditionNumber+'</span></strong> </span><span class="sticker-details">Version: <strong><span id="version">'+data.orchestra_parcel_details[0].VersionNumber+'</span></strong></span><span class="sticker-details">Style: <strong><span id="style">'+data.orchestra_parcel_details[0].NomApple+'</span></strong></span><span class="sticker-details">Color: <strong><span id="color">'+data.orchestra_parcel_quantity_details[0].Color+'</span></strong></span><span class="sticker-details">Age Group: <strong><span id="ageGroup">  <select name="carlist" id="age" form="carform">'+option+'</select></span></strong> </span><span class="sticker-details">Delivery Date: <strong><span id="dDate"> <input type="date" ></span></strong></span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>'+head_qty+'</h4><div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty<br/>incl. wastage</th><th>Parcel Sticker Qty<br/>(in sets)</th></tr></thead><tbody>'+tr+'<tr class="strong"><td class="text-right" colspan="2">Total</td><td>'+data.orchestra_parcel_details[0].Quantity+'</td><td class="text-center">0</td><td class="text-center">0</td></tr></tbody></table></div></div><div class="col-md-4"><h4>'+head_qty1+'</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" value="600" class="form-control input-sm" placeholder="Length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" value="395" class="form-control input-sm" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume">asdd</span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left: -5px;">/</span> <div class="col-md-6" style="margin-top: -34px;"><input disabled="disabled" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div> </div></div>');

    
          $('.main-content').html('<hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><img src="'+base_url+'/images/sticker-sample.png"></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg2" style="width:580px;height:500px" class="center-block" data="'+data.carton_template+'" type="image/svg+xml"></object></div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary">Save</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><img src="'+base_url+'/images/polysticker-sample.png"></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg1" class="center-block" style="width:580px;height:500px" data="'+data.carton_template+'" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary">Save</button></div></div></div></div> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer">  <button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save</button></div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Information</h4></div><div class="modal-body">Use this column only if needed</div></div>   ')
    
      var mySVG = document.getElementById("svg2");
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                              var svgItem = svgDoc.getElementById("uvc_tco");
                               var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
            JsBarcode("#ean-13", code, { format: "ean13"});
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

            svgDoc.getElementById("barcode1").setAttribute('xlink:href', img.src);
            svgDoc.getElementById("barcode2").setAttribute('xlink:href', img.src);
                             
                             
           }, false);
    
            $( "#modal_close" ).click(function() {
                $('#modal_height').removeAttr('data-target')
            });
    
            $('#age').change(function() {
                $('#age_modal').modal("show");
            });
}

function label_order_details(uvc)
{
    $('.main-content').html('<div class="loader"></div>');
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var buyer_id=$('.buyer-select').val()
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/label-order-details",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token,uvc:uvc},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
            orchestra_label_order_details(buyer,buyer_id,uvc,data)
            hangtagmodal(data);
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}
function hangtagmodal(data)
{
            var option="<option></option>";
            $.each(data.orchestra_template_front, function (index, value) {
                option+="<option value="+value.template_file+">"+value.template_name+"</option>";
            });
             $('.top-content').append('<div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Hang Tag</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-4 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div><div class="form-group"><label>Style</label> <select class="form-control template-select">'+option+'</select></div></form> </div> <div class="col-md-4"><div class="card-title"><h5>Front</h5></div><br/><div class="sample-artwork"></div></div><div class="col-md-4"><div class="card-title"><h5>Back</h5></div><br/><div class="front-side"><object id="svg2" style="width:427px;height:500px" data="'+data.orchestra_template+'" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div><div id="svg_box"></div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer"><button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save All Items</button><button type="button" class="btn btn-xs btn-info"><i class="ion-android-print"></i>Print Labels and Tags</button></div></div>');
    
             var mySVG = document.getElementById("svg2");
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                              var svgItem = svgDoc.getElementById("uvc_tco");
                              svgDoc.getElementById("algeria_price").innerHTML=data.order_details[0].Prix_Vente_ALGERIE!= null ?data.order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",") : "";
                              svgDoc.getElementById("algeria_club_price").innerHTML=data.order_details[0].Prix_Club_ALGERIE!= null ?data.order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") : ""; svgDoc.getElementById("saudi_price").innerHTML=data.order_details[0].Prix_Vente_Arabie_Saoudite!= null ?data.order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",") : ""; svgDoc.getElementById("morocco_price").innerHTML=data.order_details[0].Prix_Vente_Maroc!= null ?data.order_details[0].Prix_Vente_Maroc.toString().replace(".", ",") : ""; svgDoc.getElementById("swiss_price").innerHTML=data.order_details[0].Prix_Vente_Suisse!= null ?data.order_details[0].Prix_Vente_Suisse.toString().replace(".", ",") : ""; svgDoc.getElementById("canada_price").innerHTML=data.order_details[0].Prix_Vente_Canada!= null ?data.order_details[0].Prix_Vente_Canada.toString().replace(".", ",") : "";
                              svgDoc.getElementById("turkey_price").innerHTML=data.order_details[0].Prix_Vente_Turquie!= null ?data.order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                              svgDoc.getElementById("us_price").innerHTML=data.order_details[0].Prix_Vente_USA!= null ?data.order_details[0].Prix_Vente_USA.toString().replace(".", ",") : "";
                              svgDoc.getElementById("us_club_price").innerHTML=data.order_details[0].Prix_Club_USA!= null ?data.order_details[0].Prix_Club_USA.toString().replace(".", ",") : "";
                              svgDoc.getElementById("morris_price").innerHTML=data.order_details[0].Prix_Vente_Ile_Maurice!= null ?data.order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",") : "";
                              svgDoc.getElementById("morris_club_price").innerHTML=data.order_details[0].Prix_Club_Ile_Maurice!= null ?data.order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",") : "";
                              svgDoc.getElementById("morroco_club_price").innerHTML=data.order_details[0].Prix_Club_Maroc!= null ?data.order_details[0].Prix_Club_Maroc.toString().replace(".", ",") : "";
                              svgDoc.getElementById("swiss_club_price").innerHTML=data.order_details[0].Prix_Club_Suisse!= null ?data.order_details[0].Prix_Club_Suisse.toString().replace(".", ",") : "";
                              svgDoc.getElementById("canada_club_price").innerHTML=data.order_details[0].Prix_Club_Canada!= null ?data.order_details[0].Prix_Club_Canada.toString().replace(".", ",") : "";
                              svgDoc.getElementById("turkey_club_price").innerHTML=data.order_details[0].Prix_Club_Turquie!= null ?data.order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                              svgItem.innerHTML = data.order_details[0].UVC_TCO;
                              var size = data.order_details[0].Size.split(" ")
                              svgDoc.getElementById("theme").innerHTML=data.order_details[0].Theme;
                              svgDoc.getElementById("size").innerHTML=size[0];
                              svgDoc.getElementById("size_list").innerHTML=data.order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-");
                             svgDoc.getElementById("cm").innerHTML=data.order_details[0].cm+"cm";
                             svgDoc.getElementById("in").innerHTML=data.order_details[0].in;
                             if(size[1] == "ANS")
                                 {
                                      svgDoc.getElementById("size_french").innerHTML=size[1];
                                      svgDoc.getElementById("size_english").innerHTML="years";
                                     
                                 }
                             else
                                 {
                                     svgDoc.getElementById("size_french").innerHTML=size[1];
                                     svgDoc.getElementById("size_english").innerHTML="months";
                                 }
                               var code = data.order_details[0].BarcodeForPricesTags.toString();
            JsBarcode("#ean-13", code, { format: "ean13"});
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

            svgDoc.getElementById("bhat").setAttribute('xlink:href', img.src);
                             
                             
           }, false);
             $('.template-select').select2({
                placeholder: "Select Template",
                allowClear: true
             });
             $('.template-select').change(function() {
                  if($(this).val() != "")
                  {
                         $('.sample-artwork').html('<object id="svg1" data="'+$(this).val()+'" style="width:233px;height:500px" type="image/svg+xml"></object>')
                         var mySVG = document.getElementById("svg1");
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                             var pri = data.order_details[0].PrixEurope.split(".")
                             
                             if(pri.length == 1)
                             {
                                 var svgItem = svgDoc.getElementById("price1");
                                 svgItem.innerHTML = innerHTML=data.order_details[0].PrixEurope;
                                 svgDoc.getElementById("price2").innerHTML ="";
                             }
                             else if(pri.length == 2)
                             {
                                 var svgItem = svgDoc.getElementById("price1");
                                 svgItem.innerHTML = innerHTML=pri[0];
                                 svgDoc.getElementById("price2").innerHTML =","+pri[1];
                             }
                             var pri1 = data.order_details[0].PrixClubEurope.toString().split(".")
                             if(pri1.length == 1)
                             {
                                 svgDoc.getElementById("club_price1").innerHTML =data.order_details[0].PrixClubEurope;
                                 svgDoc.getElementById("club_price2").innerHTML ="";
                             }
                             else if(pri1.length == 2)
                             {
                                  svgDoc.getElementById("club_price1").innerHTML =pri1[0];
                                  svgDoc.getElementById("club_price2").innerHTML =","+pri1[1];
                             }
                              
                         }, false);
                      $('.sample-artwork').css('background','#fff');
                  }
                  else
                  {
                     $('.sample-artwork').html('');
                      $('.sample-artwork').css('background','#eee');
                  }

              });


}
function orchestra_label_order_details(buyer,buyer_id,uvc,data)
{
    var tr="";
    var barcode="";
     $.each(data.order_details, function (index, value) {
         tr+='<tr><td>'+value.UVC_TCO+'</td><td>'+value.Size+'</td><td>'+value.BarcodeForPricesTags+'</td><td>'+value.Sum+'</td><td><input type="text" class="form-control input-sm" placeholder="Hangtag Count"><td><input type="text" class="form-control input-sm" placeholder="Specialtag Count"><td><input type="text" class="form-control input-sm" placeholder="Main & Size label Count"></td><td><input type="text" class="form-control input-sm" placeholder="WCL Count"></td></tr>';
     });
     $.each(data.barcode, function (index, value) {
         barcode+=value.BuyerPONo+" | ";
     });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='#'>Order Management</a></li><li><a href='#' onclick=back_label_orders('"+buyer+"','"+buyer_id+"')>Label Orders</a></li><li class='active'>Order Detail for UVC "+uvc+"</li></ol></div>")
      $('.top-content').append('<h3><i class="ion-ios-browsers-outline"></i>Order Detail for UVC '+uvc+'</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">'+barcode+'  </span></h5><hr/><h4>Quantity</h4><table class="table table-bordered"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th class="mand">Hang Tags<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm" style="width:60px; float: left; margin-left: 5px;"></th><th>Special Tags<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm" style="width:60px; float: left; margin-left: 5px;"></th><th>Main & Size Label<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm" style="width:60px; float: left; margin-left: 5px;"></th><th>WCL<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>'+tr+'<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>'+data.total_qty+'</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">'+data.total_qty+'</td></tr></tbody></table>');
      $('.main-content').html('<hr/><h4>Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="'+base_url+'/images/hangtag_sample.png"></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Washcare label</h5></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Main & Size Label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Washcare - Main & Size label</p></div></div>');

}

function usersListView()
{
    $('.main-content').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $(".sub-nav-list li a").removeClass("active");
    $('.users-list-view a').addClass("active");
    $('#add_user_form').trigger("reset");
    $.ajax({
        url: base_url + "/admin/user-management/users",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Users</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addUserModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Users</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>User Management Dashboard</a></li><li class="users-list-view"><a class="active" href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="users-list-table" class="table table-hover table-striped table-bordered" style="width:100%"><thead><tr><th>Id</th><th>Name</th><th>Role</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.users, function(i, item){
            table += '<tr id="'+item.id+'"><td>'+item.user_id+'</td><td>'+item.name+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.email+'</td><td>'+item.phone+'</td><td>'+item.address+'</td>';
            if(item.user_role_id != 1)
            {
              table += '<td><a href="#"><i class="ion-edit edit-user" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete"  data-target="#deleteUserModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"></i></a></td>'
            }
            else
            {
              table += '<td></td>';
            }
              table += '</tr>';
          });
             table += '</tbody></table></div>';
             $('.main-content').html(table);
             $('#users-list-table').DataTable();
             $("#users-list-table_filter input").addClass('form-control');
             $("#users-list-table_filter input").attr('placeholder', 'Data Search');

        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
}
function rolesListView()
{
    $('.main-content').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $(".sub-nav-list li a").removeClass("active");
    $('.roles-list-view a').addClass("active");
    $('#add_user_form').trigger("reset");
    $.ajax({
        url: base_url + "/admin/user-management/roles",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Roles</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addRoleModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Roles</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>User Management Dashboard</a></li><li class="users-list-view"><a href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="active" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="roles-list-table" class="table table-hover table-striped table-bordered" style="width:100%"><thead><tr><th>Id</th><th>Role Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.roles, function(i, item){
           table += '<tr id="'+item.id+'"><td>'+item.role_id+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.user_role_description+'</td>';
           if(item.id != 1)
           {
             table += '<td><a href="#"><i class="ion-edit" title="Edit" ></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteVendor" ></i></a></td>'
           }
           else
           {
             table += '<td></td>';
           }
           table += '</tr>';
          });

          table += '</tbody></table></div>';
          $('.main-content').html(table);
          $('#roles-list-table').DataTable();
          $("#roles-list-table_filter input").addClass('form-control');
          $("#roles-list-table_filter input").attr('placeholder', 'Data Search');
        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
}
function addUser()
{
    var user_id    = $('#user_id').val();
    var name       = $('#name').val();
    var email      = $('#email').val();
    var phone      = $('#phone').val();
    var address    = $('#address').val();
    var p          = $('#password').val();
    var user_role  =  $('#user_role option:selected').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: base_url + "/admin/user-management/add-jga-user",
        method: 'POST',
        data: {_token:csrf_token,user_id:user_id,name:name,email:email,phone:phone,address:address,user_role:user_role,p:p},
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        statusCode: {
             200: function() {
                  $('#result').html('User Added Successfully')
                  $("#result").addClass("alert alert-success");
                  $('#addUserModal').modal('hide');
                  usersListView();
             },
             400: function() {
               $('#result').html("Something went wrong!!")
               $("#result").addClass("alert alert-dander");
             },
             409: function() {
                  $('#result').html('Email already registered.')
                  $("#result").addClass("alert alert-warning");
             }
        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
 }

 function getUser(id)
 {
   $.ajax({
       url:base_url + "/admin/user-management/get-user-details",
       dataType:"json",
       method:"get",
       data:{id:id},
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       success: function(data){
           $('#user_edit_id').val(id);
           $('#edit_user_id').val(data.user[0].user_id);
           $('#edit_name').val(data.user[0].name);
           $('#edit_email').val(data.user[0].email);
           $('#edit_phone').val(data.user[0].phone);
           $('#edit_address').val(data.user[0].address);
           $('#edit_user_role').val(data.user[0].user_role_id);
           $('#editUserModal').modal({
                backdrop: 'static',
                keyboard: false
           })
           $('#edit_user_form').validator('validate');
           $('#editUserModal').modal('show');
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function editUser()
 {
   var id         = $('#user_edit_id').val();
   var name       = $('#edit_name').val();
   var email      = $('#edit_email').val();
   var phone      = $('#edit_phone').val();
   var address    = $('#edit_address').val();
   var user_role  =  $('#edit_user_role option:selected').val();
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
       url:base_url + "/admin/user-management/update-user-details",
       dataType:"json",
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       method:"put",
       data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address,user_role:user_role},
       statusCode: {
            200: function() {
                 $('#edit_result').html('User Updated Successfully')
                 $("#edit_result").addClass("alert alert-success");
                 $('#editUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#edit_result').html("Something went wrong!!")
              $("#edit_result").addClass("alert alert-dander");
            },
            409: function() {
                 $('#edit_result').html('Email already registered.')
                 $("#edit_result").addClass("alert alert-warning");
            }
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function deleteUser(id)
 {
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
       url:base_url + "/admin/user-management/delete-user",
       dataType:"json",
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       method:"delete",
       data:{_token:csrf_token,id:id},
       statusCode: {
            200: function() {
                 $('#delete_result').html('User deleted Successfully')
                 $("#delete_result").addClass("alert alert-success");
                 $('#deleteUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#delete_result').html("Something went wrong!!")
              $("#delete_result").addClass("alert alert-dander");
            }
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
