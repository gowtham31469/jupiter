function tescoOrderStatus()
{
	$('.main-content').html('<div class="loader"></div>');
    $('.top-content').html('');
    $('.template-error').html('');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/tesco-orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               var path = base_url + "/admin/tesco/order-management/order-status";
               var newObj = data.data;
               $('.main-content').append(tescoStatusTable(newObj,path));
               $('.top-content').html("")
               $('.snackbar_span').html('Tesco Orders');
               $('#snackbar').css('background-color','#1abb9c');
               $('#profoma-invoice').modal("hide")
               showSnackbar();             
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               $('#snackbar').css('background-color','#F44336');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}

function tescoStatusTable(data,path){
   console.log(data[0]);
    var th = "";
    var tr = "";
    var columnsIn = data[0];

    for (var key in columnsIn) {
        if (key != "id") {
            if (key == "SupplierName" || key == "Style_Name") {
                th += "<th>" + key + "</th>";
            } else if ((key != "pi_required")&&(key != "tableName")&&(key != "template_id")&&(key !="qntytable")) {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }

        }
    }
    $.each(data, function (index, value) {

        var action_status = false;
        tr += '<tr class="trClass" id="' + value.id + '">';
                  
        tr += '</tr>';
    })
    return '<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}
