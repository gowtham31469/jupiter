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
             $("#datatable-example-2_filter input").addClass('form-control');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
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
             $('.top-content').html('<form class="navbar-form pull-right" style="margin-top:0px;"><label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label><select class="js-example-responsive buyer-select">'+option+'</select></form><h3><i class="ion-ios-browsers-outline"></i>Order Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a></div><ul class="sub-nav-list"><li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li><li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li><li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li></ul><hr/></div>')
             $('.main-content').html(table(data.orders));
             $('#datatable-example-2').DataTable();
             $("#datatable-example-2_filter input").addClass('form-control');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
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
             $("#datatable-example-2_filter input").addClass('form-control');
             $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
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
                    tr += '<td><a href="#" onclick=order_details("'+value[key]+'")>'+value[key]+'</a></td>';
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
function order_details(uvc)
{
    var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    var buyer_id=$('.buyer-select').val()
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/"+buyer+"/order-management/order-details",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token,uvc:uvc},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
            orchestra_label_order_details(buyer,buyer_id,uvc,data)
            hangtagmodal();
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}
function hangtagmodal()
{
    $('.top-content').append('<div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Hang Tag</h4></div><div class="modal-body"><div class="container-fluid"><div class="col-md-4 h100"> <div class="card-title"><h5>Options</h5></div><br/><form><div class="form-group"><label>State</label><select class="form-control"><option>Applicable</option><option>Not Applicable</option> </select> </div><div class="form-group"><label>Delivery Date</label><input type="date" class="form-control" placeholder="Date"></div><div class="form-group"><label>Style</label> <select class="form-control"><option>Baby boy</option></select></div></form> </div> <div class="col-md-4"><div class="card-title"><h5>Front</h5></div><br/><div class="sample-artwork"></div></div><div class="col-md-4"><div class="card-title"><h5>Back</h5></div><br/><div id="svg_box"></div></div></div></div> <div class="modal-footer"><button type="button" class="btn btn-primary">Save</button> </div> </div> </div></div><div class="bottom-bar"><div class="modal-footer"><button type="button" class="btn btn-xs btn-success"><i class="ion-ios-download"></i>Save All Items</button><button type="button" class="btn btn-xs btn-info"><i class="ion-android-print"></i>Print Labels and Tags</button></div></div>');
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
      $('.main-content').html('<hr/><h4>Artworks</h4><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right">Click to edit</p> <h5>Hangtag</h5></div><div class="img_artwork"><img src="assets/images/hangtag_sample.png"></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Specialtag</h5></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Washcare label</h5></div></div></div><div class="col-md-4"><div class="card"><div class="card-title"><h5>Main & Size Label</h5></div></div></div><div class="col-md-4"><div class="card add-card"><p class="text-center add-label"><i class="ion-plus-round"></i><br/>Add Washcare - Main & Size label</p></div></div>');
     
}

function users_list_view()
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

        },
        success: function (data) {
          $('.add-users-div').removeClass('hidden');
          $('.add-roles-div').addClass('hidden');

          var table = '<div class="col main"><div class="page"><div class="container-fluid"><div class="row"><div class="col"><div class="widget"><div class="col" id="user-table"><table id="users-list-table" class="table table-hover table-striped table-bordered" style="width:100%"><thead><tr><th>Id</th><th>Name</th><th>Role</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.users, function(i, item){
            table += '<tr id="'+item.id+'"><td>'+item.user_id+'</td><td>'+item.name+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.email+'</td><td>'+item.phone+'</td><td>'+item.address+'</td>';
            if(item.user_role_id != 1)
            {
              table += '<td><a href="#"><i class="ion-edit" title="Edit" ></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.user_id+'" title="Delete" data-toggle="modal" data-target="#deleteVendor" ></i></a></td>'
            }
            else
            {
              table += '<td></td>';
            }
              table += '</tr>';
          });
             table += '</tbody></table></div></div></div></div></div></div></div>';
             $('.main-content').html(table);
             $('#users-list-table').DataTable();

        },
        error: function (data) {

        },
        complete: function () {

        }
    })
}
function roles_list_view()
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

        },
        success: function (data) {
          $('.add-roles-div').removeClass('hidden');
          $('.add-users-div').addClass('hidden');
          var table = '<div class="col main"><div class="page"><div class="container-fluid"><div class="row"><div class="col"><div class="widget"><div class="col" id="user-table"><table id="roles-list-table" class="table table-hover table-striped table-bordered" style="width:100%"><thead><tr><th>Id</th><th>Role Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>';
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

          table += '</tbody></table></div></div></div></div></div></div></div>';
          $('.main-content').html(table);
          $('#roles-list-table').DataTable();
        },
        error: function (data) {

        },
        complete: function () {

        }
    })
}
function add_user()
{
    var user_id    = $('#user_id').val();
    var name       = $('#name').val();
    var email      = $('#email').val();
    var password   = $('#password').val();
    var phone      = $('#phone').val();
    var address    = $('#address').val();
    var user_role  =  $('#user_role option:selected').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    // var form_data = new FormData();
    // form_data.append('user_id', user_id);
    // form_data.append('name', name);
    // form_data.append('email', email);
    // form_data.append('password', password);
    // form_data.append('phone', phone);
    // form_data.append('address', address);
    // form_data.append('user_role', user_role);
    // form_data.append('_token', csrf_token);
    $.ajax({
        url: base_url + "/admin/user-management/add-jga-user",
        type: "POST",
        dataType:"json",
        processData: false,
        contentType: false,
        cache: false,
        data: {_token:csrf_token,name:name,email:email,password:password,phone:phone,address:address,user_role:user_role},
        beforeSend: function () {

        },
        success: function (data) {
          console.log(data)
        },
        error: function (data) {

        },
        complete: function () {

        }
    })
}
