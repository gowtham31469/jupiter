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
