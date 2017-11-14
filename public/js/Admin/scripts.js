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
    //$( ".sub_modules a" ).removeClass( "active" )
     //$('.template_list a').addClass("active")
    var buyer = localStorage.getItem("template_buyer");
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
            
           $('#tree1').jstree({ 
                    'core' : {
                        'data' : data
                    },
                    "types" : {
                          "default" : {
                            "icon" : "fa fa-folder"
                          }
                    },
                    "plugins" : [ "types","search" ]
            });
            $('#expand').bind("click", function () {
                $('#tree1').jstree("open_all");
            });
            $('#collapse').bind("click", function () {
                $('#tree1').jstree("close_all");
            });
//            .bind("loaded.jstree", function (event, data) {
//                    $(this).jstree("open_all");
//                    
//            })
            //$('.js-card').niceScroll()
              var to = false;
              $('#tree1_q').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                  var v = $('#tree1_q').val();
                  $('#tree1').jstree(true).search(v);
                }, 250);
              });
            
            $('#tree1').on('activate_node.jstree', function(e, data) {
                  if(data.instance.is_leaf(data.node)) {
                       var template_id = data.node.id;
                       var csrf_token = $('meta[name="csrf-token"]').attr('content');
                       $.ajax({
                            url: base_url + "/admin/"+buyer+"/template-management/template-view",
                            type: "Post",
                            dataType:"json",
                            data: {template_id:template_id,_token:csrf_token},
                            beforeSend: function () {
                                $(".loader").show();
                                $(".loader").css('margin-top','12%');
                            },
                            success: function (data) {
                                if(data.length > 0)
                                {
                                     $('.viewDiv').html('<object id="svg2" class="center-block svg2" data="'+data[0].template_file+'" type="image/svg+xml"></object>')   
                                     $('.descriptionDiv').html('<h4>Template Details</h4><p class="delta"><label>Name :<span> '+data[0].template_name+'</span></label></p><p class="delta"><label>Reference Number : <span> '+data[0].ref_no+'</span></label></p><p class="delta"><label>Company Name : <span>'+data[0].company_name+'</span></label></p>')
                                     $('.descriptionDiv').css('margin-top','20px')
                                    $('.viewDiv').css('margin-top','0px')
                                }
                                else
                                {
                                     $('.viewDiv').html('<img style="width:160px;height:150px;object-fit:cover;margin-left: 118px;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')   
                                     $('.descriptionDiv').html('<b>Template Not Found</b>');
                                    $('.descriptionDiv').css('margin-top','0px')
                                    $('.viewDiv').css('margin-top','140px')
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
            });

            
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
                             var Isstd = "-";
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
                             var Disney = "-";
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

          $('.main-content').html('<hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px" class="center-block svg2" data="'+data.carton_template+'" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:580px;height:500px" class="center-block" data="'+data.carton_template+'" type="image/svg+xml"></object></div><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary" onclick=printDiv("svg3")>Print</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px" data="'+data.polybag_template+'" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:580px;height:500px" data="'+data.polybag_template+'" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary" onclick=printDiv("svg4")>Print</button></div></div></div></div> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer">  <button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save</button></div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

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
function printDiv(data)
{
    var a = document.getElementById(data);
    var svgDoc = a.contentDocument;
    var svgRoot  = svgDoc.documentElement;
    Popup(svgRoot);
}

function Popup(data)
{
    var s = new XMLSerializer();
    var d = data;
    var str = s.serializeToString(d);
    var mywindow = window.open('', 'new div', 'height=400,width=600');
    mywindow.document.write('<html><head><title></title>');
    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
    mywindow.document.write('</head><body >');
    mywindow.document.write(str);
    mywindow.print();
    mywindow.close();
    return true;
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
            var instruction_option="<option>Select Option</option>";
            var parts_option="<option>Select Option</option>";
            var composition_option="<option>Select Option</option>";

             $.each(data.orchestra_countries, function (index, value) {
                        made_option+="<option  data-french='"+value.french+"' data-english='"+value.english+"' data-spanish='"+value.spanish+"' data-portuguese='"+value.portuguese+"' data-italian='"+value.italian+"' data-dutch='"+value.dutch+"' data-german='"+value.german+"' data-russian='"+value.russian+"' data-chinese='"+value.chinese+"' data-arabic='"+value.arabic+"' data-greek='"+value.greek+"' data-turkish='"+value.turkish+"' value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_instruction, function (index, value) {
                        instruction_option+="<option data-french='"+value.french+"' data-english='"+value.english+"' data-spanish='"+value.spanish+"' data-portuguese='"+value.portuguese+"' data-italian='"+value.italian+"' data-dutch='"+value.dutch+"' data-german='"+value.german+"' data-russian='"+value.russian+"' data-chinese='"+value.chinese+"' data-arabic='"+value.arabic+"' data-greek='"+value.greek+"' data-turkish='"+value.turkish+"' value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_parts, function (index, value) {
                        parts_option+="<option data-french='"+value.french+"' data-english='"+value.english+"' data-spanish='"+value.spanish+"' data-portuguese='"+value.portuguese+"' data-italian='"+value.italian+"' data-dutch='"+value.dutch+"' data-german='"+value.german+"' data-russian='"+value.russian+"' data-chinese='"+value.chinese+"' data-arabic='"+value.arabic+"' data-greek='"+value.greek+"' data-turkish='"+value.turkish+"' value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_composition, function (index, value) {
                        composition_option+="<option data-french='"+value.french+"' data-english='"+value.english+"' data-spanish='"+value.spanish+"' data-portuguese='"+value.portuguese+"' data-italian='"+value.italian+"' data-dutch='"+value.dutch+"' data-german='"+value.german+"' data-russian='"+value.russian+"' data-chinese='"+value.chinese+"' data-arabic='"+value.arabic+"' data-greek='"+value.greek+"' data-turkish='"+value.turkish+"' value="+value.id+">"+value.english+"</option>";
                    });
             $.each(data.orchestra_care_symbols, function (index, value) {
                 if(value.category_id == 1)
                     {
                          washing_option+="<option data-content='<img src="+value.cofreet_symbols+"> &nbsp; "+value.care_instruction+"' value="+value.cofreet_symbols+"  data-cofreet="+value.cofreet_symbols+"  data-astm="+value.astms_symbol+">"+value.care_instruction+"</option>";
                     }
                 else if(value.category_id == 2)
                     {
                          whitening_option+="<option data-content='<img src="+value.cofreet_symbols+"> &nbsp; "+value.care_instruction+"' data-cofreet="+value.cofreet_symbols+"  data-astm="+value.astms_symbol+"  value="+value.cofreet_symbols+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 3)
                     {
                          dryer_option+="<option data-content='<img src="+value.cofreet_symbols+"> &nbsp; "+value.care_instruction+"' value="+value.cofreet_symbols+"  data-cofreet="+value.cofreet_symbols+"  data-astm="+value.astms_symbol+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 4)
                     {
                          ironing_option+="<option data-content='<img src="+value.cofreet_symbols+"> &nbsp; "+value.care_instruction+"' value="+value.cofreet_symbols+"  data-cofreet="+value.cofreet_symbols+"  data-astm="+value.astms_symbol+">"+value.care_instruction+"</option>";
                     }
                  else if(value.category_id == 5)
                     {
                          cleaning_option+="<option data-content='<img src="+value.cofreet_symbols+"> &nbsp; "+value.care_instruction+"' value="+value.cofreet_symbols+"  data-cofreet="+value.cofreet_symbols+"  data-astm="+value.astms_symbol+">"+value.care_instruction+"</option>";
                     }

            });
    
    
    var tr="";
     $.each(data.order_details, function (index, value) {
         tr+='<tr><td>'+value.UVC_TCO+'</td><td>'+value.Size+'</td><td>'+value.BarcodeForPricesTags+'</td><td class="sum">'+value.Sum+'</td><td><input type="text" class="form-control input-sm tot_qty_table" placeholder="WCL Count"></td></tr>';
     });
    
             $('.top-content').append('<form id="washcarelabelform"><div class="left-panel col-md-9"> <div class="div-max"> <div class="col-md-3 "> <h4>Options</h4> <hr> <div class="section-1 sections"> <h5>State</h5> <select class="selectpicker" data-live-search="true" required> <option>Select Option</option><option>Applicable</option> <option>Not Applicable</option> </select> </div> <div class="section-2 sections"> <h5>Deliver Date</h5> <div class="input-group date" data-provide="datepicker" id="datePicker1"> <input type="text" class="form-control" required> <div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </div> </div> </div> <div class="section-3 sections"> <h5>Washing</h5> <select  data-live-search="true" class="selectpicker washing_select" required>'+washing_option+'</select> </div> <div class="section-4 sections"> <h5>Whitening</h5> <select class="selectpicker whitening_select"  data-live-search="true">'+whitening_option+'</select> </div> <div class="section-5 sections"> <h5>Dryer</h5> <select class="selectpicker dryer_select"  data-live-search="true">'+dryer_option+'</select> </div> <div class="section-6 sections"> <h5>Ironing</h5> <select class="selectpicker ironing_select"  data-live-search="true"> '+ironing_option+'</select> </div><div class="section-6 sections"> <h5>Cleaning</h5> <select class="selectpicker cleaning_select"  data-live-search="true"> '+cleaning_option+'</select> </div> </div> <div class="col-md-6"> <h4>Art Work</h4> <hr> <div class="section-1 sections scroll"> <object id="washcare_svg1" style=width:100%" data="/storage/app/template/orchestra_wcl.svg" type="image/svg+xml"></object> </div> </div> <div class="col-md-3 "> <h4>Options</h4> <hr>  <div class="section-1 sections"> <h5>Made In</h5> <select class="selectpicker made_in_select"  data-live-search="true"> '+made_option+' </select> </div><div class="section-2 sections"> <h5>Care Instructions</h5> <select class="selectpicker care_instruction_select" multiple  data-live-search="true"> '+instruction_option+' </select> </div> <div class="section-3 sections"> <h5>Parts</h5> <select class="selectpicker parts_select"  data-live-search="true"> '+parts_option+'  </select> </div> <div class="section-1 sections"> <h5>Compositions</h5> <select class="selectpicker composition_select"  data-live-search="true">  '+composition_option+'  </select> </div> <h5>Percentage</h5> <input type="text" class="form-control input-sm composition_percent">  <button class="btn btn-info pull-right input-sm" onclick="add_to_list()">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty"></tbody></table></div> </div> </div> <div class="right-panel col-md-3"> <div class="div-max"> <h4>Quantity Info for WashCare Label</h4> <hr> <table class="table table-striped table-hover table-condensed qty-table-modal"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty.</th><th>Qty<br/> <label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentage" style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>'+tr+'<tr class="strong"><td class="text-right" colspan="3">Total:</td><td>'+data.total_qty+'</td><td class="text-center">0</td></tr></tbody></table> <button class="btn btn-info pull-right input-sm" type="submit" id="left">Preview & Save</button> <button class="btn btn-danger pull-right input-sm" id="close">Close</button> </div> </div>  <div class="backdrop"></div></form>');
    
           $('#washcarelabelform').validator()
    
            $('.extra_percentage').keyup(function(){
                if($(this).val() != "")
                    {
                        var per = $(this).val();
                        $('.qty-table-modal tbody tr').each(function(){
                            var sum = $(this).find('.sum').text();
                            var per_qty = ( parseInt(sum) /  100) * parseInt(per);
                            var tot_qty = parseInt(sum)+parseInt(per_qty);
                            $(this).find('.tot_qty_table').val(tot_qty);
                        })
                    }
                else
                    {
                         $('.qty-table-modal tbody tr').each(function(){
                            $(this).find('.tot_qty_table').val("");
                        })
                    }
                
            })
    
             $('.selectpicker').selectpicker({
                       style: 'btn-default',
                       size: 4
             });

            $("#clickme").click(function(){
                $(".backdrop").toggleClass('active');
                $(".right-panel").toggleClass('active');
                $('.scalable').toggleClass('active');
                $(".left-panel").toggleClass('active');
            }) 

            $("#left").click(function(){


            })
            $(".backdrop, #close").click(function(){
                $(".left-panel").removeClass('active');
                $(".right-panel").removeClass('active');
                $(".backdrop").removeClass('active');
                $('.scalable').removeClass('active');
            })
  
    
    $('.washing_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.washing_select option:selected').val() != "")
                            {
                                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href',$('.washing_select option:selected').val());
                                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href',$('.washing_select option:selected').data('astm'));
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href',"");
                            }


                        });
                      $('.whitening_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.whitening_select option:selected').val() != "")
                            {
                             svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href',$('.whitening_select option:selected').val());
                            svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href',$('.whitening_select option:selected').data('astm'));
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href',"");
                            }
                        });
                         $('.dryer_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.dryer_select option:selected').val() != "")
                            {
                             svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href',$('.dryer_select option:selected').val());
                            svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href',$('.dryer_select option:selected').data('astm'));
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href',"");
                            }

                        });
                        $('.ironing_select.bootstrap-select').change(function() {
                            if($('.ironing_select option:selected').val() != "")
                            {
                              svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href',$('.ironing_select option:selected').val());
                            svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href',$('.ironing_select option:selected').data('astm'));
                            }
                             else
                            {
                                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href',"");
                            }

                        });
                        $('.cleaning_select.bootstrap-select').change(function() {
                            if($('.cleaning_select option:selected').val() != "")
                            {
                              svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href',$('.cleaning_select option:selected').val()); svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href',$('.cleaning_select option:selected').data('astm'));
                            }
                            else
                            {
                                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href',""); svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href',"");
                            }

                        });
                        $('.made_in_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.made_in_select option:selected').val() != "")
                            {
                                
                              var text_for_mad_in = '<tspan x="0" y="0" class="st3 st6">' +$('select.made_in_select').find(':selected').data('french') +"-"+ $('select.made_in_select').find(':selected').data('english') +"-"+ $('select.made_in_select').find(':selected').data('spanish') +"-"+ $('select.made_in_select').find(':selected').data('portuguese') +"-"+ $('select.made_in_select').find(':selected').data('italian') +"-"+ $('select.made_in_select').find(':selected').data('dutch') +"-"+ $('select.made_in_select').find(':selected').data('german') +"-"+ 
                              $('select.made_in_select').find(':selected').data('russian') +"-"+ 
                              $('select.made_in_select').find(':selected').data('chinese') +"-"+ 
                              $('select.made_in_select').find(':selected').data('arabic') +"-"+ 
                              $('select.made_in_select').find(':selected').data('greek') +"-"+ 
                              $('select.made_in_select').find(':selected').data('turkish')+'</tspan>';
                              svgDoc.getElementById("text_for_mad_in").innerHTML=text_for_mad_in;
                              
                            }
                            else
                            {
                                
                            }


                        });

                        $('.care_instruction_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.care_instruction_select option:selected').val() != "")
                            {
                                svgDoc.getElementById("text_care_instruction").innerHTML="";
                                $('.care_instruction_select option:selected').each(function(){
                                    var value ='<tspan>' +$(this).data('french') +"-"+ $(this).data('english') +"-"+ $(this).data('spanish') +"-"+ $(this).data('portuguese') +"-"+ $(this).data('italian') +"-"+ $(this).data('dutch') +"-"+ $(this).data('german') +"-"+ 
                              $(this).data('russian') +"-"+ 
                              $(this).data('chinese') +"-"+ 
                              $(this).data('arabic') +"-"+ 
                              $(this).data('greek') +"-"+ 
                              $(this).data('turkish')+'</tspan>';
                                    svgDoc.getElementById("text_care_instruction").innerHTML+=value;
                                });
                              
                              
                            }
                            else
                            {
                                
                            }


                        });      
                        $('.parts_select.bootstrap-select').on('changed.bs.select', function() {
                            if($('.parts_select option:selected').val() != "")
                            {
                                
                            }
                            else
                            {
                                
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
                             svgDoc.getElementById("product_family").innerHTML=data.product_family[0].arabic;
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

                                svgDoc.getElementById("barcode").setAttribute('xlink:href', img.src);


           }, false);
}
function add_to_list()
{

    if($('.composition_select option:selected').val() != "" && $('.composition_percent').val() != "")
    {
        $('#composition_table_qty').append("<tr><td>"+$('.composition_select option:selected').text()+"</td><td>"+$('.composition_percent').val()+"</td></tr>")
    }
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
    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='/admin/order-management/'>Orders</a></li><li><a href='#'>Order List</a></li><li class='active'>Order Detail for UVC "+uvc+"</li></ol></div>")
      $('.top-content').append('<h3 class="content-heading">Order Detail for UVC '+uvc+'</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">'+barcode+'  </span></h5><hr/><h4>Artworks & Quantity</h4>');

      $('.main-content').html('<hr/><h4>Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="'+base_url+'/images/hangtag_sample.png"></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" id="clickme">Click to edit</p><h5>Washcare label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><div class="card-title"><h5>Main & Size Label</h5></div></div></div><div id="add_modal"></div><div class="col-md-4" id="washcare_label_card"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Washcare Label</p></div></div><div class="col-md-4" id="main_label_card"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Main & Size Label</p></div></div>');


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
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/customer-management/customers-list",
          type: "Post",
          dataType:"json",
          data: {_token:csrf_token},
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
          },
          success: function (data) {
            $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/customer-management">Dashboard</a></li><li class="active">Customers</li></ol>');
            var table = '<div class="loader"></div><div class="col" id="customer-table"><table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Company Name</th><th>Factory Code</th><th>Default Contact</th><th>Courier Choice</th><th>Account Number</th><th>TIN Number</th><th>GSTIN Number</th><th>PAN Number</th><th>Billing Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.customers, function(i, item){
             var index = i+1;
             table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.company_name+'</td><td>'+item.factory_code+'</td><td><dl><dt>Contact Person:</dt><dd>'+item.contact_person+'</dd><dt>Contact Email:</dt><dd>'+item.contact_email+'</dd><dt>Contact Phone:</dt><dd>'+item.phone_no+'</dd></dl></td><td>'+item.courier_choice+'</td><td>'+item.account_no+'</td><td>'+item.tin_number+'</td><td>'+item.gstin_number+'</td><td>'+item.pan_number+'</td><td><dl><dt>Address:</dt><dd>'+item.address+','+item.country+'</dd><dt>Email:</dt><dd>'+item.email+'</dd><dt>Phone:</dt><dd>'+item.telephone+'</dd><dt>Fax:</dt><dd>'+item.fax+'</dd></dl></td>';
               if(item.is_active === 1)
               {
                table += '<td><span class="badge badge-success">Active</span></td>';
               }
               else
               {
                table += '<td><span class="badge badge-danger">Inactive</span></td>';
               }
               if(item.is_active === 1)
               {
                table += '<td><a href="#"><i class="fa fa-times fa-lg" aria-hidden="true" title="Make Inactive" id="make_inactive"></i></a>';
               }
               else
               {
                table += '<td><a href="#"><i class="fa fa-check fa-lg" aria-hidden="true" title="Make Active" id="make_active"></i></a>';
               }
               table += '<a href="#"><i class="fa fa-pencil fa-lg edit-customer" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteCustomerModal" ></i></a></td></tr>';
            });
            table += '</tbody></table></div>';
            $('.main-content').html(table);
            $('#customers-list-table').DataTable();
            $("#customers-list-table_filter input").addClass('form-control');
            $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
            autopopulateCountry(0)
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
      var factory_code    =  $('#factory_code').val();
      var company_name    =  $('#company_name').val();
      var courier_choice  =  $('#courier_choice').val();
      var account_no      =  $('#account_no').val();
      var tin_number      =  $('#tin_number').val();
      var gstin_number    =  $('#gstin_number').val();
      var pan_number      =  $('#pan_number').val();
      var login_email     =  $('#login_email').val();
      var password        =  $('#password').val();
      var csrf_token      =  $('meta[name="csrf-token"]').attr('content');

      var contact_person  = [];
      var contact_phone   = [];
      var contact_email   = [];
      var default_contact = [];

      var address         = [];
      var country         = [];
      var telephone       = [];
      var fax             = [];
      var address_email   = [];
      var default_address = [];

      $('.contact_person').each(function(i, item)
      {
          contact_person[i]    = $(item).val();
          
      });
      $('.contact_phone').each(function(i, item)
      {
          contact_phone[i]    = $(item).val();
          
      });
      $('.contact_email').each(function(i, item)
      {
          contact_email[i]    = $(item).val();
          
      });
      $('.default_contact').each(function(i, item)
      {
          if ($(this).is(':checked'))
          {
            default_contact[i]    = 1;
          }
          else
          {
            default_contact[i]    = 0;
          }
          
      });
      $('.address').each(function(i, item)
      {
          address[i]    = $(item).val();
          
      });
      $('.country').each(function(i, item)
      {
          country[i]    = $(item).val();
          
      });
      $('.telephone').each(function(i, item)
      {
          telephone[i]    = $(item).val();
          
      });
      $('.fax').each(function(i, item)
      {
          fax[i]    = $(item).val();
          
      });
      $('.address_email').each(function(i, item)
      {
          address_email[i]    = $(item).val();
          
      });
      $('.default_address').each(function(i, item)
      {
        if ($(this).is(':checked'))
          {
            default_address[i]    = 1;
          }
          else
          {
            default_address[i]    = 0;
          }
          
      });
                
      contact_person   = contact_person.join(',');
      contact_phone    = contact_phone.join(',');
      contact_email    = contact_email.join(',');
      default_contact  = default_contact.join(',');
      address          = address.join('|');
      country          = country.join(',');
      telephone        = telephone.join(',');
      fax              = fax.join(',');
      address_email    = address_email.join(',');
      default_address  = default_address.join(',');

      $.ajax({
          url: base_url + "/admin/customer-management/add-customer",
          method: 'POST',
          data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,p:password,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address},
          beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
          },
          statusCode: {
               200: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html('Customer Added Successfully')
                    $("#customer_result").addClass("alert alert-success");
                    $('#addCustomerModal').modal('hide');
                    customersListView();
               },
               400: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html("Something went wrong!!")
                    $("#customer_result").addClass("alert alert-dander");
               },
               409: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html('Email already registered.')
                    $("#customer_result").addClass("alert alert-warning");
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
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/buyers-list",
           type: "get",
           dataType:"json",
           data: {_token:csrf_token},
           beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
           },
           success: function (data) {
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/buyer-management">Dashboard</a></li><li class="active">Buyers</li></ol>');
               var table = '<div class="loader"></div><div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.buyers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td><td><a href="#"><i class="fa fa-pencil fa-lg edit-buyer" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" ></i></a></td></tr>';
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
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/merchandiser-list",
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
                 var appenddata1 = "<option value = ''>-- Select Customers --</option>";
                   $.each(data.customers, function (key, value) {

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
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li class="active">Merchandisers</li></ol>');
               var table = '<div class="loader"></div><div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Supplier Name</th><th>Buyer Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.merchandisers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.company_name+'</td><td>'+item.buyer_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td><a href="#"><i class="fa fa-pencil fa-lg edit-merchandiser" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteMerchandiserModal" ></i></a></td></tr>';
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
                 var appenddata1 = "<option value = ''>-- Select Customers --</option>";
                   $.each(data.customers, function (key, value) {

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

     function addContact(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addcontact').append('<div id="contact_' + val + '" class="addcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="contact' + val + '" class="panel"><div class="form-group has-feedback"><label for="contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm contact_person" placeholder="Contact Person Name" required="" name="contact_person' + val + '" value="" id="contact_person' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="contact_phone' + val + '" value="" id="contact_phone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm contact_email" placeholder="Contact Email" required="" name="contact_email' + val + '" value="" id="contact_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_contact" type="checkbox" value="" name="default" id="default_contact' + val + '"> Make as default contact person</label></div></div></div>')
         $('#add_customer_form').validator('update');
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }

     function removeContact(rem) {
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
         $('#add_customer_form').validator('update');
     }

     function addAddress(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addbilladdress').append('<div id="billaddress_' + val + '" class="addbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="billaddress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="address' + val + '" cols="50" id="address' + val + '"></textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="country' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="country' + val + '" value="" id="country' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="telephone' + val + '" value="" id="telephone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="fax' + val + '" value="" id="fax' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="address_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address' + val + '"> Make as default billing address</label></div></div></div>')
         $('#add_customer_form').validator('update');
         autopopulateCountry(val);
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
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
         $('#add_customer_form').validator('update');
     }
/** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends**/
    function autopopulateCountry(id)
     {
            var options = {
            url: base_url + "/countries.json",

            getValue: "name",
            adjustWidth: false,
            template: {
              type: "description",
              fields: {
                description: "code"
              }
            },
            list: {
                showAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },

                hideAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },
                maxNumberOfElements: 10,
                 match: {
                  enabled: true
                },

                onClickEvent: function() {
                  var value = $("#country" + id).getSelectedItemData().code;
                  $("#country" + id).val(value).trigger("change");
                }
            }
          };
       $("#country" + id).easyAutocomplete(options);
     }
     function editAutopopulateCountry(id)
     {
          var options = {
          url: base_url + "/countries.json",

          getValue: "name",
          adjustWidth: false,
          template: {
            type: "description",
            fields: {
              description: "code"
            }
          },
           
            list: {
                showAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },

                hideAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },
                maxNumberOfElements: 10,
                 match: {
                  enabled: true
                },

                onClickEvent: function() {
                  var value = $("#edit_country" + id).getSelectedItemData().code;
                  $("#edit_country" + id).val(value).trigger("change");
                }
           }
          };
       $("#edit_country" + id).easyAutocomplete(options);
     }
    function makeCustomerActive(id,csrf_token)
     {
      $.ajax({
           url:base_url + "/admin/customer-management/make-customer-active",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     customersListView();
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }

    function makeCustomerInactive(id,csrf_token)
     {
      $.ajax({
           url:base_url + "/admin/customer-management/make-customer-inactive",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     customersListView();
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }

     var isSubmitting = false;
     function getCustomer(id)
     {
       if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        var a = []
        $('#editContact').html('');
        $('#editbilladdress').html('');

       $.ajax({
           url:base_url + "/admin/customer-management/get-customers",
           dataType:"json",
           method:"get",
           data:{id:id},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           success: function(data){
               isSubmitting = false;
               $('#customer_edit_id').val(id);
               $.each(data.customers, function (key, value) {
                $('#edit_factory_code').val(value.factory_code);
                $('#edit_company_name').val(value.company_name);
                $('#edit_courier_choice').val(value.courier_choice);
                $('#edit_account_no').val(value.account_no);
                $('#edit_tin_number').val(value.tin_number);
                $('#edit_gstin_number').val(value.gstin_number);
                $('#edit_pan_number').val(value.pan_number);
                $('#edit_login_email').val(value.email);
               });

               $.each(data.address,function(key,value){
                 key += 1;
                  var address_html = '<div edit="editbilladdress_' + key + '" class="editbilladdress">';
                  if(key != 1)
                  {
                    address_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i>';
                  }
                  address_html += '<div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + key + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + key + '" class="form_label">Address</label><textarea class="form-control edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + key + '" cols="50" id="edit_address' + key + '">'+value.address+'</textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + key + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + key + '" value="'+value.country+'" id="edit_country' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + key + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="editbasic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + key + '" value="'+value.telephone+'" id="edit_telephone' + key + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + key + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="edit_fax' + key + '" value="'+value.fax+'" id="edit_fax' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + key + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + key + '" value="'+value.email+'" id="edit_address_email' + key + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + key + '"> Make as default billing address</label></div></div></div>';
                    
                    $('#editbilladdress').append(address_html);

                    editAutopopulateCountry(key);
                    if(value.default_address === 1)
                    {
                      $('#edit_default_address'+ key).prop('checked', true);
                    }
                    else
                    {
                      $('#edit_default_address'+ key).prop('checked', false);
                    }

                     var j = 1;
                     $('#editbilladdress .editbilladdress').each(function(key, val) {
                         $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
                         a.push($(this).attr('id'));
                         j++;
                     })
                     var b = []
                     var b = a.join(',');
                     $('#hidden').val(b);
                     $(':checkbox').on('change',function(){
                       var th = $(this), name = th.prop('name');
                       if(th.is(':checked')){
                           $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
                        }
                  });
                $('#edit_address').attr("data-id", key);
               });

               $.each(data.contacts,function(key,value){
                 key += 1;
                  var contact_html = '<div id="edit_contact_' + key + '" class="editcontact">';
                  if(key != 1)
                  {
                    contact_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i>';
                  }
                  contact_html += '<div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact' + key + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + key + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + key + '" value="' + value.contact_person_name + '" id="edit_contact_person' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + key + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + key + '" value="' + value.contact_person_phone + '" id="edit_contact_phone' + key + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + key + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + key + '" value="' + value.contact_person_email + '" id="edit_contact_email' + key + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + key + '"> Make as default contact person</label></div></div></div>';
                    
                    $('#editContact').append(contact_html);

                    editAutopopulateCountry(key);
                    if(value.default_contact_person === 1)
                    {
                      $('#edit_default_contact'+ key).prop('checked', true);
                    }
                    else
                    {
                      $('#edit_default_contact'+ key).prop('checked', false);
                    }

                     var j = 1;
                     $('#editContact .editcontact').each(function(key, val) {
                         $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
                         a.push($(this).attr('id'));
                         j++;
                     })
                     var b = []
                     var b = a.join(',');
                     $('#hidden').val(b);
                     $(':checkbox').on('change',function(){
                       var th = $(this), name = th.prop('name');
                       if(th.is(':checked')){
                           $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
                        }
                  });
                $('#edit_contact').attr("data-id", key);
               });
               $('#editCustomerModal').modal({
                    backdrop: 'static',
                    keyboard: false
               })
               $('#edit_customer_form').validator('update');
               $('#editCustomerModal').modal('show');
           },
           complete: function () {
            $('#edit_customer_form').validator('validate');
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins(for editing)**/
     function editAddContact(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editContact').append('<div id="edit_contact_' + val + '" class="editcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact_person' + val + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + val + '" value="" id="edit_contact_person' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + val + '" value="" id="edit_contact_phone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + val + '" value="" id="edit_contact_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + val + '"> Make as default contact person</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#editContact .editcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }

     function editRemoveContact(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#editContact .editcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#edit_customer_form').validator('update');
     }
     function editAddAddress(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editbilladdress').append('<div id="editbilladdress_' + val + '" class="editbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + val + '" class="form_label">Address</label><textarea class="form-control  edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + val + '" cols="50" id="edit_address' + val + '"></textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + val + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + val + '" value="" id="edit_country' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + val + '" value="" id="edit_telephone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="edit_fax' + val + '" value="" id="edit_fax' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="editbasic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + val + '" value="" id="edit_address_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + val + '"> Make as default billing address</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         editAutopopulateCountry(val);
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }

     function editRemoveAddress(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#edit_customer_form').validator('update');
     }
    /** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends(for editing)**/

    function editCustomer()
  {
      var id              =  $('#customer_edit_id').val();
      var factory_code    =  $('#edit_factory_code').val();
      var company_name    =  $('#edit_company_name').val();
      var courier_choice  =  $('#edit_courier_choice').val();
      var account_no      =  $('#edit_account_no').val();
      var tin_number      =  $('#edit_tin_number').val();
      var gstin_number    =  $('#edit_gstin_number').val();
      var pan_number      =  $('#edit_pan_number').val();
      var login_email     =  $('#edit_login_email').val();
      var csrf_token      =  $('meta[name="csrf-token"]').attr('content');

      var contact_person  = [];
      var contact_phone   = [];
      var contact_email   = [];
      var default_contact = [];

      var address         = [];
      var country         = [];
      var telephone       = [];
      var fax             = [];
      var address_email   = [];
      var default_address = [];

      $('.edit_contact_person').each(function(i, item)
      {
          contact_person[i]    = $(item).val();
          
      });
      $('.edit_contact_phone').each(function(i, item)
      {
          contact_phone[i]    = $(item).val();
          
      });
      $('.edit_contact_email').each(function(i, item)
      {
          contact_email[i]    = $(item).val();
          
      });
      $('.edit_default_contact').each(function(i, item)
      {
          if ($(this).is(':checked'))
          {
            default_contact[i]    = 1;
          }
          else
          {
            default_contact[i]    = 0;
          }
          
      });
      $('.edit_address').each(function(i, item)
      {
          address[i]    = $(item).val();
          
      });
      $('.edit_country').each(function(i, item)
      {
          country[i]    = $(item).val();
          
      });
      $('.edit_telephone').each(function(i, item)
      {
          telephone[i]    = $(item).val();
          
      });
      $('.edit_fax').each(function(i, item)
      {
          fax[i]    = $(item).val();
          
      });
      $('.edit_address_email').each(function(i, item)
      {
          address_email[i]    = $(item).val();
          
      });
      $('.edit_default_address').each(function(i, item)
      {
        if ($(this).is(':checked'))
          {
            default_address[i]    = 1;
          }
          else
          {
            default_address[i]    = 0;
          }
          
      });
                
      contact_person   = contact_person.join(',');
      contact_phone    = contact_phone.join(',');
      contact_email    = contact_email.join(',');
      default_contact  = default_contact.join(',');
      address          = address.join('|');
      country          = country.join(',');
      telephone        = telephone.join(',');
      fax              = fax.join(',');
      address_email    = address_email.join(',');
      default_address  = default_address.join(',');

      $.ajax({
          url: base_url + "/admin/customer-management/update-customer",
          method: 'PUT',
          data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address,id:id},
          beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
          },
          statusCode: {
               200: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html('Customer Updated Successfully')
                    $("#customer_upadte_result").addClass("alert alert-success");
                    $('#editCustomerModal').modal('hide');
                    customersListView();
               },
               400: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html("Something went wrong!!")
                    $("#customer_upadte_result").addClass("alert alert-dander");
               },
               409: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html('Email already registered.')
                    $("#customer_upadte_result").addClass("alert alert-warning");
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
   function deleteCustomer(id)
   {
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/customer-management/delete-customer",
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
                   $('#delete_result').html('Customer deleted Successfully')
                   $("#delete_result").addClass("alert alert-success");
                   $('#deleteCustomerModal').modal('hide');
                   customersListView();
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
     function getBuyersMerchandisers()
     {
      $.ajax({
                 url: base_url + "/admin/buyer-management/get-buyers",
                 method: 'get',
                 success: function (data) {
                   var appenddata = "<option value = ''>-- Select Buyers --</option>";
                     $.each(data.buyers, function (key, value) {

                     appenddata += "<option value = '"+value.id+"'>" +value.buyer_name+" </option>";
                     });
                 $('#merchandiser_buyer').html(appenddata);
                 var appenddata1 = "<option value = ''>-- Select Customers --</option>";
                   $.each(data.customers, function (key, value) {

                   appenddata1 += "<option value = '"+value.id+"'>" +value.company_name+" </option>";
                   });
               $('#merchandiser_supplier').html(appenddata1);
                 },
                 error: function (data) {

                 },
                 complete: function () {
                 }
             })
     }