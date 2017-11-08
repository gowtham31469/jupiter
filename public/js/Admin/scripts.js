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
   // $('.main-sub-menu').removeClass('hidden');
   // $( ".sub_modules a" ).removeClass( "active" )
   //  $('.sticker_orders a').addClass("active")
    $('.main-content').html('<div class="loader"></div>');
    var buyer = localStorage.getItem("buyer");
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
            var newObj = data.sticker_orders.concat(data.label_orders);
             $('.main-content').html(table(newObj));
             $('#datatable-example-2').DataTable(
             {
                 "pageLength": 25
             });
             $("#datatable-example-2_filter input").addClass('form-control input-sm');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
              $('#datatable-example-2_length select').addClass('form-control input-sm');
             //$('.breadcrumb').html('<li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Order Management</a></li><li class="active">Sticker Orders</li>')
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function template_list()
{
    $( ".sub_modules a" ).removeClass( "active" )
     $('.template_list a').addClass("active")
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/template-management/template-list",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          /*  var template = '<div class="col-md-4 modules_div"> <div class="row">';
             $.each(data, function (index, value) {
                 template+='<div class="main-menu" id="'+value.id+'"><div class="main-module"> '+value.item_name+' <span class="glyphicon glyphicon-chevron-right" style="margin-top:3px;float: right;"></span> </div><div class="new-div">';
                  $.each(value.second, function (index1, value1) {
                      template+=' <div class="sub-module" id="'+value1.id+'" data-id="'+value.id+'" style="display:none"><span class="glyphicon glyphicon-chevron-right" style="margin-top:3px;float: right;"></span> '+value1.item_name+' </div>';
                       $.each(value1.third, function (index2, value2) {
                           template+='<div class="sub-module2" data-id="'+value1.id+'" style="display:none"><i class="ion-ios-eye pull-right"></i>'+value2.template_name+' </div>';
                        });
                  });
                 template+='</div> </div>';
             });
            template+='</div> </div><div class="col-md-8"><div class="viewDiv hidden"></div><div class="loader"></div></div>';
             $('.main-content').html(template);
             $('.breadcrumb').html('<li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Template Management</a></li><li class="active">Template List</li>')
              $('.main-menu').on('click', function (e) {
                $('.main-module').find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
                $(this).find('span').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
                var dataId = $(this).attr('id')
                $(".sub-module").css('display', 'none');
                $(".sub-module2").css('display', 'none');
                $(".sub-module[data-id='" + dataId + "']").css('display', 'inline-block');
                e.stopPropagation()
                })

                $('.sub-module').on('click', function (e) {
                $('.sub-module').find('span').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right')
                $(this).find('span').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down')
                var dataId = $(this).attr('id')
                $(".sub-module2").css('display', 'none');
                $(".sub-module2[data-id='" + dataId + "']").css('display', 'block');
                e.stopPropagation()
                })
            $('.sub-module2').on('click', function (e) {

                $(".sub-module2").css('display', 'block');
                $(".sub-module2").css('display', 'block');
                e.stopPropagation()
                })*/
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
            th += "<th style='text-align: center;'>"+key+"</th>";
         }
    }
    $.each(data, function (index, value) {
        tr += '<tr id="'+value.id+'">';
        for(var key in columnsIn){
            if(key != "id")
            {
                if(key == "Type")
                {
                     if(value[key] == "Null")
                    {
                             var Isstd = "Null";
                    }
                    else
                    {
                            var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    }
                    tr += '<td align="center">'+Isstd+'</td>';
                        
                }
                else if(key == "Disney")
                {
                    if(value[key] == "Null")
                    {
                             var Disney = "Null";
                    }
                    else
                    {
                         var Disney = value[key] == "0" ? "No" : "Yes";
                    }
                   
                    tr += '<td align="center">'+Disney+'</td>';
                }
                else if(key == "UVC")
                {
                    tr += '<td align="center"><a href="#" onclick=order_details("'+value[key]+'","'+value.BuyerPONo+'")>'+value[key]+'</a></td>';
                }
                else
                {
                    tr += '<td align="center">'+value[key]+'</td>';
                }
            }
        }
        tr += '</tr>';
    })
    return'<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>'+th+'</tr></thead><tbody>'+tr+'</tbody></table></div></div></div></div></div></div>';

}
function order_details(uvc,po)
{
   if(po != "-")
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
    //var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    //var buyer_id=$('.buyer-select').val()
    var buyer =localStorage.getItem("buyer");
    var buyer_id=localStorage.getItem("buyer_id");
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
        var disabled='';
        var ttotal='';
        var thead='<thead><tr><th>Size</th><th>Ratio</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th></tr></thead>';
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
         tr+='<tr><td>'+value.Size+'</td><td>'+value.Quantity+'</td><td>'+data.orchestra_parcel_details[0].ParcelBarCode+'</td></tr>';

     });
         var head_qty="Quantity for Standard Stickers";
        var head_qty1="Standard Parcel/Carton Sticker";
        var standard_div = '<div class="form-group"><label>Total Polybags per carton: </label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" placeholder="Polybag count" class="form-control input-sm"></div> <div class="form-group"><label>Final Order Qty including Wastage: </label><div class="input-group"><input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" placeholder="Set of 2 Pcs." class="form-control input-sm"><span class="input-group-addon">sets</span></div></div><hr><h4>Standard Polybag Sticker</h4><div class="form-group"><label>Order Quantity</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div><div class="form-group"><label>Final Order Qty including Wastage</label><input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control input-sm"></div>';
    }
    else
    {
        var disabled='disabled="disabled"';
        var ttotal='<td class="text-center">0</td><td class="text-center">0</td>';
        var thead='<thead><tr><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty<br/>incl. wastage</th><th>Parcel Sticker Qty<br/>(in sets)</th></tr></thead>';
        $.each(data.orchestra_parcel_quantity_details, function (index, value) {
         tr+='<tr><td>'+value.Size+'</td><td>'+value.BarcodeForPricesTags+'</td><td>'+value.Quantity+'</td><td><input type="number" value="0" class="form-control input-sm"><td><input type="number" value="0" class="form-control input-sm"></td></tr>';
     });
        var head_qty="Quantity for Solid Size Stickers";
        var head_qty1="Solid Size Parcel/Carton Sticker";
         var standard_div = '';
    }

     var option="<option>Age Group</option>";
            $.each(data.orchestra_age_group, function (index, value) {
                option+="<option value="+value.color+" style='background-color:"+value.color+"'>"+value.age_group_name+"</option>";
     });
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='#'>Order Management</a></li><li><a href='#' onclick=back_sticker_orders('"+buyer+"','"+buyer_id+"')>Sticker Orders</a></li><li class='active'>Sticker Order Details for UVC "+uvc+" and PO "+po+"</li></ol></div>")

 $('.top-content').append('  <h3><i class="ion-ios-browsers-outline"></i>Sticker Order Details for UVC 0F0006 and PO '+po+'</h3><hr/><h5><span class="sticker-details">Supplier: <strong><span id="supplier">'+data.orchestra_parcel_details[0].SupplierName+'</span></strong> </span> <span class="sticker-details">Edition: <strong><span id="edition">'+data.orchestra_parcel_details[0].EditionNumber+'</span></strong> </span><span class="sticker-details">Version: <strong><span id="version">'+data.orchestra_parcel_details[0].VersionNumber+'</span></strong></span><span class="sticker-details">Style: <strong><span id="style">'+data.orchestra_parcel_details[0].NomApple+'</span></strong></span><span class="sticker-details">Color: <strong><span id="color">'+data.orchestra_parcel_quantity_details[0].Color+'</span></strong></span><span class="sticker-details">Age Group: <strong><span id="ageGroup">  <select name="carlist" id="age" form="carform">'+option+'</select></span></strong> </span><span class="sticker-details">Delivery Date: <strong><span id="dDate"> <input type="date" ></span></strong></span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>'+head_qty+'</h4><div class="table-responsive"><table class="table table-bordered table-hover">'+thead+'<tbody>'+tr+'<tr class="strong"><td class="text-right" colspan="2">Total</td><td>'+data.orchestra_parcel_details[0].Quantity+'</td>'+ttotal+'</tr></tbody></table></div></div><div class="col-md-4"><h4>'+head_qty1+'</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" value="395" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume"></span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left: -5px;">/</span> <div class="col-md-6" style="margin-top: -34px;"><input '+disabled+' data-toggle="modal" data-target=".needed_modal" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div>'+standard_div+' </div></div>');

          $('.main-content').html('<hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px" class="center-block svg2" data="'+data.carton_template+'" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:580px;height:500px" class="center-block" data="'+data.carton_template+'" type="image/svg+xml"></object></div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary">Save</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px" data="'+data.polybag_template+'" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:580px;height:500px" data="'+data.polybag_template+'" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary">Save</button></div></div></div></div> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer">  <button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save</button></div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

          barcode_carton('svg2',data)
          barcode_carton('svg3',data)
          barcode_polybag('svg1',data)
          barcode_polybag('svg4',data)
           $( "#modal_height" ).keyup(function() {
              var num = $('#length').val() * $('#width').val() * this.value
              var val =num/(1000 * 1000 * 1000)
              $('#parcelVolume').text(val)
               var svgDoc1;
                var mySVG1 = document.getElementById("svg2");
                svgDoc1 = mySVG1.contentDocument;
                svgDoc1.getElementById("volume").innerHTML=val;
               svgDoc1.getElementById("height").innerHTML=this.value;
            });
            $( "#modal_close" ).click(function() {
                $('#modal_height').removeAttr('data-target')
            }); $( ".modal_close1" ).click(function() {
                $('#needed_modal').removeAttr('data-target')
            });

            $('#age').change(function() {
                $('#age_modal').modal("show");
                var mySVG1 = document.getElementById("svg1");
                svgDoc1 = mySVG1.contentDocument;
                svgDoc1.getElementById("round").style.fill=$(this).val();

            });


}
function barcode_polybag(id,data)
{
    var mySVG = document.getElementById(id);
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                              var svgItem = svgDoc.getElementById("uvc_tco");

                                     svgDoc.getElementById("parcel_code").innerHTML=data.orchestra_parcel_details[0].ParcelCode;
                             svgDoc.getElementById("round").style.fill=$('#age').val();

                                if(data.template_name == "Standard")
                                {
                                     var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
                                     svgDoc.getElementById("theme").innerHTML=data.orchestra_parcel_details[0].Theme;
                                }
                                else
                                {
                                     var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
                                    svgDoc.getElementById("size").innerHTML=data.orchestra_parcel_quantity_details[0].Size;
                                     svgDoc.getElementById("color").innerHTML=data.orchestra_parcel_quantity_details[0].Color;

                                }

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

            svgDoc.getElementById("barcode").setAttribute('xlink:href', img.src);


           }, false);

}
function barcode_carton(id,data)
{
    var mySVG = document.getElementById(id);
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                              var svgItem = svgDoc.getElementById("uvc_tco");
                              svgDoc.getElementById("po").innerHTML=data.orchestra_parcel_details[0].BuyerPONo;
                                     svgDoc.getElementById("length").innerHTML=$('#length').val();
                                     svgDoc.getElementById("width").innerHTML=$('#width').val();
                                     svgDoc.getElementById("height").innerHTML=$('#modal_height').val();
                                     svgDoc.getElementById("volume").innerHTML=$('#parcelVolume').text();
                                     svgDoc.getElementById("theme").innerHTML=data.orchestra_parcel_details[0].Theme;
                                     svgDoc.getElementById("parcelcode").innerHTML=data.orchestra_parcel_details[0].ParcelCode;
                                     svgDoc.getElementById("color").innerHTML=data.orchestra_parcel_quantity_details[0].Color;

                                if(data.template_name == "Standard")
                                {
                                     var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
                                }
                                else
                                {
                                     var code = data.orchestra_parcel_quantity_details[0].BarcodeForPricesTags.toString();
                                     svgDoc.getElementById("size").innerHTML=data.orchestra_parcel_quantity_details[0].Size;
                                }

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

}
function label_order_details(uvc)
{
    $('.main-content').html('<div class="loader"></div>');
   // var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
   // var buyer_id=$('.buyer-select').val()
    var buyer =localStorage.getItem("buyer");
    var buyer_id=localStorage.getItem("buyer_id");
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
            washcarelabels(data);
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}
function washcarelabels(data)
{
            var washing_option="<option>Select Option</option>";
            var whitening_option="<option>Select Option</option>";
            var dryer_option="<option>Select Option</option>";
            var ironing_option="<option>Select Option</option>";
            var cleaning_option="<option>Select Option</option>";
            var made_option="<option>Select Option</option>";
            var instruction_option="";
            var parts_option="";
            var composition_option="<option>Select Option</option>";

             $.each(data.orchestra_countries, function (index, value) {
                        made_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_instruction, function (index, value) {
                        instruction_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_parts, function (index, value) {
                        parts_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_composition, function (index, value) {
                        composition_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_symbols, function (index, value) {
                 if(value.category_id == 1)
                     {
                          washing_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                 else if(value.category_id == 2)
                     {
                          whitening_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 3)
                     {
                          dryer_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 4)
                     {
                          ironing_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 5)
                     {
                          cleaning_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }

            });
             $('.top-content').append('<div class="modal wash_care_label_main" artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">WashCare Label</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-3 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div><div class="form-group"><label>Washing</label><select class="form-control washing_select">'+washing_option+'</select> </div><div class="form-group"><label>Whitening</label><select class="form-control whitening_select">'+whitening_option+'</select> </div><div class="form-group"><label>Dryer</label><select class="form-control dryer_select">'+dryer_option+'</select> </div><div class="form-group"><label>Ironing</label><select class="form-control ironing_select">'+ironing_option+'</select> </div><div class="form-group"><label>Cleaning</label><select class="form-control cleaning_select">'+cleaning_option+'</select> </div></form> </div> <div class="col-md-6"><div class="card-title"><h5>Art Work</h5></div><br/><div class="front-side"><object id="washcare_svg1" style="width:550px" data="/storage/app/template/WCL.svg" type="image/svg+xml"></object><object id="washcare_svg2" style="margin-top: 0px;margin-left: -194px;display: inline-block;width: 186.6px;" data="/storage/app/template/WCL2.svg" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div><div id="svg_box"></div></div><div class="col-md-3"><div class="card-title"><h5>Options</h5></div><br/><div class="form-group"><label>Made in</label><select class="form-control made_select">'+made_option+'</select> </div><div class="form-group"><label>Care Instructions</label><select class="form-control instruction_select" multiple>'+instruction_option+'</select> </div><div class="form-group"><label>Parts</label><select class="form-control parts_select" multiple>'+parts_option+'</select> </div><div class="form-group"><label>Compositions</label><select class="form-control composition_select">'+composition_option+'</select> </div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div>');

     $('.instruction_select').select2({
                placeholder: "Select Option",
                allowClear: true
             });

     $('.parts_select').select2({
                placeholder: "Select Option",
                allowClear: true
             });

            $('.select2-dropdown').css('width','270px')

                function formatData (data) {
                  if (!data.id) { return data.text; }
                    console.log(data)
                  var $result= $(
                    '<span><img style="width: 34px;" src="'+data.id+'"/> ' + data.text + '</span>'
                  );
                  return $result;
                };

                $(".washing_select").select2({
                  templateResult: formatData,
                  templateSelection: formatData

                });
    $(".whitening_select").select2({
                  templateResult: formatData,
                  templateSelection: formatData

                });
    $(".dryer_select").select2({
                  templateResult: formatData,
                  templateSelection: formatData

                });$(".ironing_select").select2({
                  templateResult: formatData,
                  templateSelection: formatData

                });
    $(".cleaning_select").select2({
                  templateResult: formatData,
                  templateSelection: formatData

                });

                        $('.washing_select').change(function() {
                            if($(this).val() != "")
                            {
                                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href',$(this).val());
                                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href',$(this).val());
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href',"");
                            }


                        });
                        $('.whitening_select').change(function() {
                            if($(this).val() != "")
                            {
                             svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href',$(this).val());
                            svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href',$(this).val());
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href',"");
                            }
                        });
                        $('.dryer_select').change(function() {
                            if($(this).val() != "")
                            {
                             svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href',$(this).val());
                            svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href',$(this).val());
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href',"");
                            }

                        });
                        $('.ironing_select').change(function() {
                            if($(this).val() != "")
                            {
                              svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href',$(this).val());
                            svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href',$(this).val());
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href',"");
                            }

                        });
                        $('.cleaning_select').change(function() {
                            if($(this).val() != "")
                            {
                              svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href',$(this).val()); svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href',$(this).val());
                            }
                            else
                            {
                                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href',"");
                            }

                        });

                         var mySVG = document.getElementById("washcare_svg1");
                         var svgDoc;
                         mySVG.addEventListener("load",function() {
                              svgDoc = mySVG.contentDocument;
                               var size = data.order_details[0].Size.split(" ")
                               if(size[1] == "ANS")
                               {
                                    var size1="years";
                               }
                               else
                               {
                                    var size1="months";
                               }
                                svgDoc.getElementById("parcel_code1").innerHTML=data.order_details[0].UVC_TCO;
                             svgDoc.getElementById("parcel_code2").innerHTML=data.order_details[0].UVC_TCO;
                             svgDoc.getElementById("size_in_fr_en1").innerHTML=data.order_details[0].cm+"cm / "+ data.order_details[0].in+"in";
                              svgDoc.getElementById("size_in_fr_en2").innerHTML=data.order_details[0].cm+"cm / "+ data.order_details[0].in+"in";
                             svgDoc.getElementById("size1").innerHTML=data.order_details[0].Size.replace(" ","").toLowerCase()+"/"+size1;
                             svgDoc.getElementById("size2").innerHTML=data.order_details[0].Size.replace(" ","").toLowerCase()+"/"+size1;

                               var code = data.order_details[0].BarcodeForPricesTags.toString();
                                JsBarcode("#ean-13", code, {
                                    format: "ean13",
                                    font:"Arial"});
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

                               // svgDoc.getElementById("barcode").setAttribute('xlink:href', img.src);


           }, false);
}
function hangtagmodal(data)
{
            var option="<option></option>";
            var washing_option="<option>Select Option</option>";
            var whitening_option="<option>Select Option</option>";
            var dryer_option="<option>Select Option</option>";
            var ironing_option="<option>Select Option</option>";
            var cleaning_option="<option>Select Option</option>";
            var made_option="<option>Select Option</option>";
            var instruction_option="<option></option>";
            var parts_option="<option></option>";
            var composition_option="<option>Select Option</option>";
            $.each(data.orchestra_template_front, function (index, value) {
                option+="<option value="+value.template_file+">"+value.template_name+"</option>";
            });

             $.each(data.orchestra_countries, function (index, value) {
                        made_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_instruction, function (index, value) {
                        instruction_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_parts, function (index, value) {
                        parts_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_composition, function (index, value) {
                        composition_option+="<option value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_symbols, function (index, value) {
                 if(value.category_id == 1)
                     {
                          washing_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                 else if(value.category_id == 2)
                     {
                          whitening_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 3)
                     {
                          dryer_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 4)
                     {
                          ironing_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 5)
                     {
                          cleaning_option+="<option value="+value.cofreet_symbols+"><img src="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }

            });
             $('.top-content').append('<div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Hang Tag</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-4 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div><div class="form-group"><label>Style</label> <select class="form-control template-select">'+option+'</select></div></form> </div> <div class="col-md-4"><div class="card-title"><h5>Front</h5></div><br/><div class="sample-artwork"></div></div><div class="col-md-4"><div class="card-title"><h5>Back</h5></div><br/><div class="front-side"><object id="svg2" style="width:427px;height:486px" data="'+data.orchestra_template+'" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div><div id="svg_box"></div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer"><button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save All Items</button><button type="button" class="btn btn-xs btn-info"><i class="ion-android-print"></i>Print Labels and Tags</button></div></div>');

     $('.instruction_select').select2({
                placeholder: "Select Option",
                allowClear: true
             });

     $('.parts_select').select2({
                placeholder: "Select Option",
                allowClear: true
             });

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
            JsBarcode("#ean-13", code, {
                format: "ean13",
                font:"Arial"});
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

      $('.main-content').html('<hr/><h4>Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="'+base_url+'/images/hangtag_sample.png"></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to edit</p><h5>Washcare label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><h5>Main & Size Label</h5></div></div></div><div id="add_modal"></div><div class="col-md-4" id="washcare_label_card"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Washcare Label</p></div></div><div class="col-md-4" id="main_label_card"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Main & Size Label</p></div></div>');


     $('#washcare_label_card').on('click', function (e) {
          washcare+=1;
          $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".washcare_label_'+washcare+'" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right">Click to edit</p> <h5>Washcare label</h5></div><div class="img_artwork"></div></div></div><div class="modal washcare_label_'+washcare+' artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Wash Care Label</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-3 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div></form> </div> <div class="col-md-6"><div class="card-title"><h5>Art Work</h5></div><br/><div class="sample-artwork1"></div></div><div class="col-md-3"><div class="card-title"><h5>Options</h5></div><br/><div class="front-side"><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div><div id="svg_box"></div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div>')
     });
     $('#main_label_card').on('click', function (e) {
          mainlabel+=1;
          $('#add_modal').append('<div class="col-md-4" ><div class="card add-card" data-toggle="modal" data-target=".main_label_'+mainlabel+'" data-backdrop="static" data-keyboard="false">    <div class="card-title"><p class="pull-right">Click to edit</p> <h5>Main & Size Label</h5></div><div class="img_artwork"></div></div></div><div class="modal main_label_'+mainlabel+' artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Main Label</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-3 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div></form> </div> <div class="col-md-6"><div class="card-title"><h5>Art Work</h5></div><br/><div class="sample-artwork1"></div></div><div class="col-md-3"><div class="card-title"><h5>Options</h5></div><br/><div class="front-side"><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div><div id="svg_box"></div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div>')
     });

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
        type: "get",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $.ajax({
              url: base_url + "/admin/user-management/get-user-roles",
              method: 'get',
              success: function (data) {
                console.log(data.roles);
                var appenddata = "<option value = ''>-- Select User Role --</option>";
                $.each(data.roles, function (key, value) {

                appenddata += "<option value = '"+value.id+"'>" +value.user_role_name+" </option>";
                });
              $('#user_role').html(appenddata);
              },
              error: function (data) {

              },
              complete: function () {
              }
          })
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Users</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addUserModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Users</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>Dashboard</a></li><li><a class="active" href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="users-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Name</th><th>Role</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead><tbody>';
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
        type: "get",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Roles</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addRoleModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Roles</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>Dashboard</a></li><li><a href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="active" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="roles-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Role Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.roles, function(i, item){
           table += '<tr id="'+item.id+'"><td>'+item.role_id+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.user_role_description+'</td>';
           if(item.id != 1)
           {
             table += '<td><a href="#"><i class="ion-edit edit-user-role" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteUserRoleModal" ></i></a></td>'
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
                  $('#result').removeAttr('style');
                  $('#result').html('User Added Successfully')
                  $("#result").addClass("alert alert-success");
                  $('#addUserModal').modal('hide');
                  rolesListView();
                  usersListView();
             },
             400: function() {
               $('#result').removeAttr('style');
               $('#result').html("Something went wrong!!")
               $("#result").addClass("alert alert-dander");
             },
             409: function() {
                  $('#result').removeAttr('style');
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
 var isSubmitting = false;
 function getUser(id)
 {
   if(isSubmitting) {
        return;
    }
    isSubmitting = true;
   $.ajax({
       url:base_url + "/admin/user-management/get-user-details",
       dataType:"json",
       method:"get",
       data:{id:id},
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
         $.ajax({
             url: base_url + "/admin/user-management/get-user-roles",
             method: 'get',
             success: function (data) {
               var appenddata = "<option value = ''>-- Select User Role --</option>";
                 $.each(data.roles, function (key, value) {

                 appenddata += "<option value = '"+value.id+"'>" +value.user_role_name+" </option>";
                 });
             $('#edit_user_role').html(appenddata);
             },
             error: function (data) {

             },
             complete: function () {
             }
         })
       },
       success: function(data){
           isSubmitting = false;
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
                 $('#edit_result').removeAttr('style');
                 $('#editUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#edit_result').removeAttr('style');
              $('#edit_result').html("Something went wrong!!")
              $("#edit_result").addClass("alert alert-dander");
            },
            409: function() {
                 $('#edit_result').removeAttr('style');
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
                 $('#delete_result').removeAttr('style');
                 $('#delete_result').html('User deleted Successfully')
                 $("#delete_result").addClass("alert alert-success");
                 $('#deleteUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#delete_result').removeAttr('style');
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
$("#age").change(function(){
    alert();
})

function addRole(permissions)
{
    var id   = $('#role_id').val();
    var name = $('#role_name').val();
    var desc = $('#role_description').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/user-management/add-user-role",
        method: 'POST',
        data: {_token:csrf_token,permissions:permissions,role_id:id,role_name:name,role_desc:desc},
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        statusCode: {
             200: function() {
                  $('#role_result').removeAttr('style');
                  $('#role_result').html('User Role Added Successfully')
                  $("#role_result").addClass("alert alert-success");
                  $('#addRoleModal').modal('hide');
                  usersListView();
                  rolesListView();
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
 function getUserRole(id)
 {
   if(isSubmitting) {
        return;
    }
    isSubmitting = true;
   $.ajax({
       url:base_url + "/admin/user-management/get-user-role-details",
       dataType:"json",
       method:"get",
       data:{id:id},
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       success: function(data){
           isSubmitting = false;
           $('#user_edit_role_id').val(id);
           $('#edit_role_id').val(data.user_role[0].role_id);
           $('#edit_role_name').val(data.user_role[0].user_role_name);
           $('#edit_role_description').val(data.user_role[0].user_role_description);

           if (data.error == "false") {
               var parsedData = [];
               data.permissions.forEach(function (index) {
                   parsedData.push(index.module_id);
               })
               $('.role_permissions_div').find('input[type="checkbox"]').each(function () {
                   var permitted_mod = parseInt($(this).val());

                   if ($.inArray(permitted_mod, parsedData) != -1) {
                       $(this).prop('checked', true);
                   } else {
                       $(this).prop('checked', false);
                   }
               })
           } else {

           }
           $('#editRoleModal').modal({
                backdrop: 'static',
                keyboard: false
           })
           $('#edit_role_form').validator('validate');
           $('#editRoleModal').modal('show');
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function updateRole(permissions)
 {
     var id         = $('#user_edit_role_id').val();
     var role_id    = $('#edit_role_id').val();
     var name       = $('#edit_role_name').val();
     var desc       = $('#edit_role_description').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url: base_url + "/admin/user-management/update-user-role",
         dataType:"json",
         method:"put",
         data: {_token:csrf_token,permissions:permissions,role_id:role_id,role_name:name,role_desc:desc,id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         statusCode: {
              200: function() {
                   $('#edit_role_result').removeAttr('style');
                   $('#edit_role_result').html('User Role Updated Successfully')
                   $("#edit_role_result").addClass("alert alert-success");
                   $('#editRoleModal').modal('hide');
                   rolesListView();
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
  function deleteUserRole(id)
  {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:base_url + "/admin/user-management/delete-user-role",
        dataType:"json",
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        method:"delete",
        data:{_token:csrf_token,id:id},
        statusCode: {
             200: function() {
                  $('#delete_user_role_result').removeAttr('style');
                  $('#delete_user_role_result').html('User role deleted Successfully')
                  $("#delete_user_role_result").addClass("alert alert-success");
                  $('#deleteUserRoleModal').modal('hide');
                  rolesListView();
             },
             400: function() {
               $('#delete_user_role_result').html("Something went wrong!!")
               $("#delete_user_role_result").addClass("alert alert-dander");
             }
        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
  }
  function customersListView()
  {
      $('.main-sub-menu').removeClass('hidden');
      $( ".sub_modules a" ).removeClass( "active" )
       $('.sticker_orders a').addClass("active")
      $('.main-content').html('<div class="loader"></div>');
      var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/customer-management/customers",
          type: "Post",
          dataType:"json",
          data: {_token:csrf_token},
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
          },
          success: function (data) {
            $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Customer Management</a></li><li class="active">Customers</li></ol><h3><i class="ion-ios-browsers-outline"></i>Customer Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addCustomerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Customer</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>Dashboard</a></li><li class="customers-list-view"><a class="active" href="#" onclick="customersListView()"><i class="ion-ios-pricetag"></i>Customers</a></li></ul><hr/></div>');
            var table = '<div class="col" id="customer-table"><table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Company Name</th><th>Factory Code</th><th>Contact Person</th><th>Contact Email</th><th>Phone</th><th>Courier Choice</th><th>Account Number</th><th>TIN Number</th><th>PAN Number</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.customers, function(i, item){
             var index = i+1;
             table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.company_name+'</td><td>'+item.factory_code+'</td><td>'+item.contact_person+'</td><td>'+item.contact_email+'</td><td>'+item.phone_no+'</td><td>'+item.courier_choice+'</td><td>'+item.account_no+'</td><td>'+item.tin_number+'</td><td>'+item.pan_number+'</td><td><a href="#"><i class="ion-edit edit-user-role" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteCustomerModal" ></i></a></td></tr>';
            });
            table += '</tbody></table></div>';
            $('.main-content').html(table);
            $('#customers-list-table').DataTable();
            $("#customers-list-table_filter input").addClass('form-control');
            $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
          },
          error: function (data) {

          },
          complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top','0%');
          }
      })
  }
  function addCustomer()
  {
      var factory_code    = $('#factory_code').val();
      var company_name    = $('#company_name').val();
      var contact_person  = $('#contact_person').val();
      var contact_phone   = $('#contact_phone').val();
      var contact_email   = $('#contact_email').val();
      var courier_choice  = $('#courier_choice').val();
      var account_no      =  $('#account_no').val();
      var tin_number      =  $('#tin_number').val();
      var pan_number      =  $('#pan_number').val();
      var login_email     =  $('#login_email').val();
      var address         =  $('#address').val();
      var country         =  $('#country').val();
      var fax             =  $('#pan_number').val();
      var telephone       =  $('#telephone').val();
      var address_email   =  $('#address_email').val();


      var csrf_token = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
          url: base_url + "/admin/user-management/add-jga-user",
          method: 'POST',
          data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,pan_number:pan_number,login_email:login_email,p:p,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email},
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
   function buyers()
   {
      $('.main-sub-menu').removeClass('hidden');
      $( ".sub_modules a" ).removeClass( "active" )
      $('.sticker_orders a').addClass("active")
      $('.main-content').html('<div class="loader"></div>');
      var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/buyers",
           type: "get",
           dataType:"json",
           data: {_token:csrf_token},
           beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
           },
           success: function (data) {
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Buyer Management</a></li><li class="active">Buyers</li></ol><h3><i class="ion-ios-browsers-outline"></i>Buyer Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addBuyerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Buyer</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-social-codepen"></i>Dashboard</a></li><li><a class="active" href="#" onclick="buyers()"><i class="ion-ios-pricetag"></i>Buyers</a></li><li class="roles-list-view"><a class="" href="#" onclick="merchandiser()"><i class="ion-ios-pricetag"></i>Merchandiser</a></li></ul><hr/></div>');
               var table = '<div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.buyers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td><td><a href="#"><i class="ion-edit edit-buyer" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" ></i></a></td></tr>';
               });
               table += '</tbody></table></div>';
               $('.main-content').html(table);
                $('#buyers-list-table').DataTable();
                $("#buyers-list-table_filter input").addClass('form-control input-sm');
                $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
                 $('#buyers-list-table_length select').addClass('form-control input-sm');
           },
           error: function (data) {

           },
           complete: function () {
               $(".loader").hide();
               $(".loader").css('margin-top','0%');
           }
       })
   }
   function addBuyer()
   {
       var name       = $('#buyer_name').val();
       var email      = $('#buyer_email').val();
       var phone      = $('#buyer_phone').val();
       var address    = $('#buyer_address').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');

       $.ajax({
           url: base_url + "/admin/buyer-management/add-jga-buyer",
           method: 'POST',
           data: {_token:csrf_token,name:name,email:email,phone:phone,address:address},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           statusCode: {
                200: function() {
                     $('#buyer_result').removeAttr('style');
                     $('#buyer_result').html('Buyer Added Successfully')
                     $("#buyer_result").addClass("alert alert-success");
                     $('#addBuyerModal').modal('hide');
                     buyers();
                },
                400: function() {
                  $('#buyer_result').removeAttr('style');
                  $('#buyer_result').html("Something went wrong!!")
                  $("#buyer_result").addClass("alert alert-dander");
                },
                409: function() {
                     $('#buyer_result').removeAttr('style');
                     $('#buyer_result').html('Email already registered.')
                     $("#buyer_result").addClass("alert alert-warning");
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
   var isSubmitting = false;
   function getBuyer(id)
   {
     if(isSubmitting) {
          return;
      }
      isSubmitting = true;
     $.ajax({
         url:base_url + "/admin/buyer-management/get-buyer-details",
         dataType:"json",
         method:"get",
         data:{id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         success: function(data){
             isSubmitting = false;
             $('#buyer_edit_id').val(id);
             $('#edit_buyer_name').val(data.buyer[0].buyer_name);
             $('#edit_buyer_email').val(data.buyer[0].buyer_email);
             $('#edit_buyer_phone').val(data.buyer[0].buyer_phone);
             $('#edit_buyer_address').val(data.buyer[0].buyer_address);
             $('#editBuyerModal').modal({
                  backdrop: 'static',
                  keyboard: false
             })
             $('#edit_buyer_form').validator('validate');
             $('#editBuyerModal').modal('show');
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   function editBuyer()
   {
     var id         = $('#buyer_edit_id').val();
     var name       = $('#edit_buyer_name').val();
     var phone      = $('#edit_buyer_phone').val();
     var email      = $('#edit_buyer_email').val();
     var address    = $('#edit_buyer_address').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/buyer-management/update-buyer-details",
         dataType:"json",
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         method:"put",
         data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address},
         statusCode: {
              200: function() {
                   $('#edit_buyer_result').removeAttr('style');
                   $('#edit_buyer_result').html('Buyer Updated Successfully')
                   $("#edit_buyer_result").addClass("alert alert-success");
                   $('#editBuyerModal').modal('hide');
                   buyers();
              },
              400: function() {
                $('#edit_buyer_result').removeAttr('style');
                $('#edit_buyer_result').html("Something went wrong!!")
                $("#edit_buyer_result").addClass("alert alert-dander");
              },
              409: function() {
                   $('#edit_buyer_result').removeAttr('style');
                   $('#edit_buyer_result').html('Email already registered.')
                   $("#edit_buyer_result").addClass("alert alert-warning");
              }
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   function deleteBuyer(id)
   {
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/buyer-management/delete-buyer",
         dataType:"json",
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         method:"delete",
         data:{_token:csrf_token,id:id},
         statusCode: {
              200: function() {
                   $('#delete_buyer_result').removeAttr('style');
                   $('#delete_buyer_result').html('Buyer deleted Successfully')
                   $("#delete_buyer_result").addClass("alert alert-success");
                   $('#deleteBuyerModal').modal('hide');
                   buyers();
              },
              400: function() {
                $('#delete_buyer_result').removeAttr('style');
                $('#delete_buyer_result').html("Something went wrong!!")
                $("#delete_buyer_result").addClass("alert alert-dander");
              }
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   /**  Validation for mobile number **/
   function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
      return true;
    }

    function merchandiser()
    {
      $('.main-sub-menu').removeClass('hidden');
      $( ".sub_modules a" ).removeClass( "active" )
      $('.sticker_orders a').addClass("active")
      $('.main-content').html('<div class="loader"></div>');
      var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/merchandiser",
           type: "get",
           dataType:"json",
           data: {_token:csrf_token},
           beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
               $.ajax({
                   url: base_url + "/admin/buyer-management/get-buyers",
                   method: 'get',
                   success: function (data) {
                     var appenddata = "<option value = ''>-- Select Buyers --</option>";
                       $.each(data.buyers, function (key, value) {

                       appenddata += "<option value = '"+value.id+"'>" +value.buyer_name+" </option>";
                       });
                   $('#merchandiser_buyer').html(appenddata);
                   var appenddata1 = "<option value = ''>-- Select Suppliers --</option>";
                     $.each(data.suppliers, function (key, value) {

                     appenddata1 += "<option value = '"+value.id+"'>" +value.company_name+" </option>";
                     });
                 $('#merchandiser_supplier').html(appenddata1);
                   },
                   error: function (data) {

                   },
                   complete: function () {
                   }
               })
           },
           success: function (data) {
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Buyer Management</a></li><li class="active">Merchandisers</li></ol><h3><i class="ion-ios-browsers-outline"></i>Buyer Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addMerchandiserModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Merchandiser</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-social-codepen"></i>Dashboard</a></li><li><a  href="#" onclick="buyers()"><i class="ion-ios-pricetag"></i>Buyers</a></li><li class="roles-list-view"><a class="active" href="#" onclick="merchandiser()"><i class="ion-ios-pricetag"></i>Merchandisers</a></li></ul><hr/></div>');
               var table = '<div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Supplier Name</th><th>Buyer Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.merchandisers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.company_name+'</td><td>'+item.buyer_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td><a href="#"><i class="ion-edit edit-merchandiser" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteMerchandiserModal" ></i></a></td></tr>';
               });
               table += '</tbody></table></div>';
               $('.main-content').html(table);
                $('#merchandisers-list-table').DataTable();
                $("#merchandisers-list-table_filter input").addClass('form-control input-sm');
                $("#merchandisers-list-table_filter input").attr('placeholder', 'Data Search');
                 $('#merchandisers-list-table_length select').addClass('form-control input-sm');
           },
           error: function (data) {

           },
           complete: function () {
               $(".loader").hide();
               $(".loader").css('margin-top','0%');
           }
       })
    }
    function addMerchandiser()
    {
        var name                   = $('#merchandiser_name').val();
        var email                  = $('#merchandiser_email').val();
        var password               = $('#merchandiser_password').val();
        var phone                  = $('#merchandiser_phone').val();
        var address                = $('#merchandiser_address').val();
        var merchandiser_buyer     = $('#merchandiser_buyer option:selected').val();
        var merchandiser_supplier  = $('#merchandiser_supplier option:selected').val();
        var csrf_token             = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: base_url + "/admin/buyer-management/add-merchandiser",
            method: 'POST',
            data: {_token:csrf_token,name:name,email:email,phone:phone,address:address,p:password,merchandiser_buyer:merchandiser_buyer,merchandiser_supplier:merchandiser_supplier},
            beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
            },
            statusCode: {
                 200: function() {
                      $('#add_merchandiser_result').removeAttr('style');
                      $('#add_merchandiser_result').html('Merchandiser Added Successfully')
                      $("#add_merchandiser_result").addClass("alert alert-success");
                      $('#addMerchandiserModal').modal('hide');
                      merchandiser();
                 },
                 400: function() {
                   $('#add_merchandiser_result').removeAttr('style');
                   $('#add_merchandiser_result').html("Something went wrong!!")
                   $("#add_merchandiser_result").addClass("alert alert-dander");
                 },
                 409: function() {
                      $('#add_merchandiser_result').removeAttr('style');
                      $('#add_merchandiser_result').html('Email already registered.')
                      $("#add_merchandiser_result").addClass("alert alert-warning");
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
     var isSubmitting = false;
     function getMerchandiser(id)
     {
       if(isSubmitting) {
            return;
        }
        isSubmitting = true;
       $.ajax({
           url:base_url + "/admin/buyer-management/get-merchandiser-details",
           dataType:"json",
           method:"get",
           data:{id:id},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
             $.ajax({
                 url: base_url + "/admin/buyer-management/get-buyers",
                 method: 'get',
                 success: function (data) {
                   var appenddata = "<option value = ''>-- Select Buyers --</option>";
                     $.each(data.buyers, function (key, value) {

                     appenddata += "<option value = '"+value.id+"'>" +value.buyer_name+" </option>";
                     });
                 $('#edit_merchandiser_buyer').html(appenddata);
                 var appenddata1 = "<option value = ''>-- Select Suppliers --</option>";
                   $.each(data.suppliers, function (key, value) {

                   appenddata1 += "<option value = '"+value.id+"'>" +value.company_name+" </option>";
                   });
               $('#edit_merchandiser_supplier').html(appenddata1);
                 },
                 error: function (data) {

                 },
                 complete: function () {
                 }
             })
           },
           success: function(data){
               isSubmitting = false;
               $('#merchandiser_edit_id').val(id);
               $('#edit_merchandiser_name').val(data.merchandiser[0].merchandiser_name);
               $('#edit_merchandiser_email').val(data.merchandiser[0].email);
               $('#edit_merchandiser_phone').val(data.merchandiser[0].merchandiser_phone);
               $('#edit_merchandiser_address').val(data.merchandiser[0].merchandiser_address);
               $('#edit_merchandiser_supplier').val(data.merchandiser[0].customer_id);
               $('#edit_merchandiser_buyer').val(data.merchandiser[0].buyer_id);
               $('#editMerchandiserModal').modal({
                    backdrop: 'static',
                    keyboard: false
               })
               $('#edit_merchandiser_form').validator('validate');
               $('#editMerchandiserModal').modal('show');
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     function editMerchandiser()
     {
       var id         = $('#merchandiser_edit_id').val();
       var name       = $('#edit_merchandiser_name').val();
       var phone      = $('#edit_merchandiser_phone').val();
       var email      = $('#edit_merchandiser_email').val();
       var address    = $('#edit_merchandiser_address').val();
       var supplier   = $('#edit_merchandiser_supplier').val();
       var buyer      = $('#edit_merchandiser_buyer').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/buyer-management/update-merchandiser-details",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address,supplier:supplier,buyer:buyer},
           statusCode: {
                200: function() {
                     $('#edit_merchandiser_result').removeAttr('style');
                     $('#edit_merchandiser_result').html('Merchandiser Updated Successfully')
                     $("#edit_merchandiser_result").addClass("alert alert-success");
                     $('#editMerchandiserModal').modal('hide');
                     merchandiser();
                },
                400: function() {
                  $('#edit_merchandiser_result').removeAttr('style');
                  $('#edit_merchandiser_result').html("Something went wrong!!")
                  $("#edit_merchandiser_result").addClass("alert alert-dander");
                },
                409: function() {
                     $('#edit_merchandiser_result').removeAttr('style');
                     $('#edit_merchandiser_result').html('Email already registered.')
                     $("#edit_merchandiser_result").addClass("alert alert-warning");
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     function deleteMerchandiser(id)
     {
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/buyer-management/delete-merchandiser",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('#delete_merchandiser_result').removeAttr('style');
                     $('#delete_merchandiser_result').html('Merchandiser deleted Successfully')
                     $("#delete_merchandiser_result").addClass("alert alert-success");
                     $('#deleteMerchandiserModal').modal('hide');
                     merchandiser();
                },
                400: function() {
                  $('#delete_merchandiser_result').removeAttr('style');
                  $('#delete_merchandiser_result').html("Something went wrong!!")
                  $("#delete_merchandiser_result").addClass("alert alert-dander");
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins**/
     function add(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addcontact').append('<div id="contact_' + val + '" class="addcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 23px 15px;cursor:pointer" onclick="remove(this)" title="Delete"></i><button type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></button><div id="contact' + val + '" class="panel"><div class="form-group has-feedback"><label for="contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm " placeholder="Contact Person Name" required="" name="contact_person' + val + '" value="" id="contact_person' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon" id="basic-addon1">+91</span><input class="form-control input-sm " placeholder="Contact Phone No" data-minlength="10" data-maxlength="10" required="" onkeypress="return isNumber(event)" name="contact_phone' + val + '" value="" id="contact_phone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon" id="basic-addon1">@</span><input class="form-control input-sm " placeholder="Contact Email" required="" name="contact_email' + val + '" value="" id="contact_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" value="" name="default"> Make as default contact person</label></div></div></div>')
         $('#add_customer_form').validator('update');
         var acc = document.getElementsByClassName("accordion");
         var i;
         for (i = 0; i < acc.length; i++) {
             acc[i].onclick = function() {
                 this.classList.toggle("active");
                 var panel = this.nextElementSibling;
                 if (panel.style.display === "block") {
                     panel.style.display = "none";
                 } else {
                     panel.style.display = "block";
                 }
             }
         }
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
     }

     function remove(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
     }
     function addAddress(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addbilladdress').append('<div id="billaddress_' + val + '" class="addbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 23px 15px;cursor:pointer" onclick="removeAddress(this)" title="Delete"></i><button type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></button><div id="billaddress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control input-sm " required="" placeholder="Address" style="resize:none;" rows="3" name="address' + val + '" cols="50" id="address' + val + '"></textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="country' + val + '" class="form_label">Country</label><input class="form-control input-sm " placeholder="Country" required="" name="country' + val + '" value="" id="country' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="telephone' + val + '" class="form_label">Telephone</label><input class="form-control input-sm " placeholder="Telephone" required="" data-minlength="10" data-maxlength="10" onkeypress="return isNumber(event)" name="telephone' + val + '" value="" id="telephone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm " placeholder="Fax" required="" onkeypress="return isNumber(event)" name="fax' + val + '" value="" id="fax' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon" id="basic-addon1">@</span><input class="form-control input-sm " placeholder="Email" required="" name="address_email' + val + '" value="" id="address_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div></div></div>')
         $('#add_customer_form').validator('update');
         var acc = document.getElementsByClassName("accordion");
         var i;
         for (i = 0; i < acc.length; i++) {
             acc[i].onclick = function() {
                 this.classList.toggle("active");
                 var panel = this.nextElementSibling;
                 if (panel.style.display === "block") {
                     panel.style.display = "none";
                 } else {
                     panel.style.display = "block";
                 }
             }
         }
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
     }

     function removeAddress(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
     }
/** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends**/
