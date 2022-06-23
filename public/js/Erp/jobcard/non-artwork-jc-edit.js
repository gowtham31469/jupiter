/**
 * Get customers list based on buyer selection
 * @param buyer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

const getNATCustomersList = (evt) => {

    let selected_buyer_id = evt.value;
    if (selected_buyer_id != '') 
    {
        $.ajax({
            type: "post",
            url: base_url + "/erp/non-artwork-job-manager/get-customers-filter",
            data: {
                'buyer_id': selected_buyer_id,
                _token: csrf_token
            },
            dataType: "json",
            beforeSend:  () => {
                $(".loader").show();
            },
            statusCode: {
                200: function(data) {
                    var html = '<option value="">SELECT A CUSTOMER</option>';
                    /** [ customers list selectpicker value populating ] */
                    $.each(data.customers, function (key, item) {
                        html += '<option value="' + item.id + '">' + item.company_name + '</option>';
                    });
                    $('#customer_filter').html(html);
                    $('#customer_filter').selectpicker('refresh');
                },
                400: function() {
                    $('.snackbar_span').html("Parameter is missing !!!");
                    showSnackbar();
                },
            },
            error: function (errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function () {
                $(".loader").hide();
            }
        });
    }
    else
    {
        $('#customer_filter').html('<option value="">SELECT A CUSTOMER</option>');
        $('#customer_filter').selectpicker('refresh');
        $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
        $('#jobcard_id_filter').selectpicker('refresh');
    }
    
}

/**
 * Get jobcard id list based on buyer and customer selection
 * @param buyer_id, customer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
 function getNatJobcardIDList(evt)
 {
     try {
         let selected_customer_id = evt.value;
         let selected_buyer_id    = $('#buyer_filter option:selected').val();
         if (selected_customer_id != '') 
         {
             $.ajax({
                 url: base_url + "/erp/non-artwork-job-manager/get-nat-jobcard-ids",
                 type: 'GET',
                 data: {
                     selected_customer_id: selected_customer_id,
                     selected_buyer_id:selected_buyer_id
                 },
                 dataType: "json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 statusCode: {
                     200: function(data) {
                         var html = '<option value="">SELECT A JOBCARD ID</option>';
                         /** [ jobcard id list selectpicker value populating ] */
                         $.each(data.jobcard_id_list, function (key, item) {
                             html += '<option value="' + key + '">' + item + '</option>';
                         });
                         $('#jobcard_id_filter').html(html);
                         $('#jobcard_id_filter').selectpicker('refresh');
                     },
                     400: function() {
                         $('.snackbar_span').html("Parameter is missing !!!");
                         showSnackbar();
                     },
                },
                 error: function (errorXHR, errorStatus, errorThrown) {
                     console.log(errorThrown);
                 },
                 complete: function () {
                     $(".loader").hide();
                 }
             })
         }
         else
         {
             $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
             $('#jobcard_id_filter').selectpicker('refresh');
         }
     } catch (e) {
         console.log("Error:"+e)
     }
 }