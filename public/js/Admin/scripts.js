function orchestra_label_orders(obj)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/label-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $("#fakeloader").fadeIn();
        },
        success: function (data) {
             $('.main').html(jumbotron('Label Orders',data));
             $('#datatable-example-2').DataTable();
        },
        error: function (data) {

        },
        complete: function () {
            $("#fakeloader").fadeOut();
        }
    })
}

function orchestra_sticker_orders(obj)
{
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/sticker-orders",
        type: "Post",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $("#fakeloader").fadeIn();
        },
        success: function (data) {
             $('.main').html(jumbotron('Sticker Orders',data));
             $('#datatable-example-2').DataTable();
        },
        error: function (data) {

        },
        complete: function () {
            $("#fakeloader").fadeOut();
        }
    })
}
function jumbotron(name,data)
{
    return '<div class="jumbotron jumbotron-1"><div class="container-fluid"><div class="row"><div class="col"><ol class="breadcrumb icon-angle-right"><li><a href="#">Dashboard</a></li><li class="active">'+name+'</li></ol></div></div><div class="row"><div class="col"><a class="btn btn-info btn-rounded btn-icon" href="#"><i class="fa fa-plus"></i><span class="btn-text">Create Order</span></a><h4>Order Management</h4><h5>'+name+'</h5></div></div></div></div><div class="page"><div class="row"><div class="col"><div class="widget"><div class="row"><div class="col">'+table(data)+'</div></div></div></div></div></div>';
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
    return'<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>'+th+'</tr></thead><tbody>'+tr+'</tbody></table>';
   
}