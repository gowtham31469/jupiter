function orchestra_label_orders(obj)
{
    $('.main').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/label-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $("#fakeloader").fadeIn();
            $(".loader").show();
            $(".loader").css('margin-top','12%');
            $( ".sub_modules a" ).removeClass( "active" )
            $( obj ).addClass( "active" )
        },
        success: function (data) {
             $('.main').html(jumbotron('Label Orders','Order Management'));
             $('.main').append(table(data));
             $('#datatable-example-2').DataTable();
        },
        error: function (data) {

        },
        complete: function () {
            $("#fakeloader").fadeOut();
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
        }
    })
}

function orchestra_sticker_orders()
{
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
                else
                {
                    tr += '<td>'+value[key]+'</td>';
                }
            }
        }
        tr += '</tr>';
    })
    return' <div class="action-group pull-right"><a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a></div><ul class="sub-nav-list"><li><a class="active" href="#"><i class="ion-social-codepen"></i>Sticker Orders</a></li><li><a class="" href="#"><i class="ion-ios-pricetag"></i>Label Orders</a></li></ul><hr/><table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>'+th+'</tr></thead><tbody>'+tr+'</tbody></table></div></div></div></div></div></div>';

}
function jumbotron_without_button(name,main_menu)
{
    return '<div class="loader"></div><div class="jumbotron jumbotron-1"><div class="container-fluid"><div class="row"><div class="col"><ol class="breadcrumb icon-angle-right"><li><a href="#">Dashboard</a></li><li class="active">'+name+'</li></ol></div></div><div class="row"><div class="col"><h4>'+main_menu+'</h4><h5>'+name+'</h5></div></div></div></div>';
}
function add_user_page()
{
    $('.main').html(jumbotron_without_button('Users','User Management'));
    $('.main').append('<div class="page"><div class="row"><div class="col"><div class="widget"><div class="col-12 col-md-6 add-user-form"><div class="widget"><div class="row"><div class="col"><form method="post" id="add_user"><div class="form-group"><label>User ID *</label><input type="text" class="form-control" required></div><div class="form-group"><label>Name *</label><input type="text" class="form-control"  placeholder="Enter name" required></div><div class="form-group"><label>Phone *</label><input type="text" class="form-control"  placeholder="Enter phone" required></div><div class="form-group"><label>Email *</label><input type="email" class="form-control"  placeholder="Enter email" required></div><div class="form-group"><label>Address *</label><textarea class="form-control" rows="6" required></textarea></div><div class="form-group"><label>Password *</label><input type="password" class="form-control" placeholder="Enter password" required></div><div class="form-group"><label class="select-label"><span>Select User Role *</span></label><select class="custom-select form-control"><option value="default">Select User Role</option><option value="1">Tesco</option><option value="2">Orchestra</option></select></div><button type="submit" class="btn btn-primary">Submit</button><button type="button" class="btn btn-primary">Cancel</button></form></div></div></div></div></div></div></div></div>');
}
