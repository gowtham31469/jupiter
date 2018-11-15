/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

/** [showSnackbar function] */
    function showSnackbar() {
    try {
        // Get the snackbar DIV
        var x = document.getElementById("snackbar")

        // Add the "show" class to DIV
        x.className = "show";

        // After 3 seconds, remove the show class from DIV
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    } catch (e) {
        console.log("Error:" + e)
    }
}
function showFilter() {
    $('.filter-window').toggle();
}
/**
 * Job card generation 
 * @author <Praveen> [praveen@wizroots.com]
 */
function generateJobcard(){
    if ($("#approved-order-list input[class='row-checkbox']:checked").length === 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		showSnackbar();
    }
    else
    {
        orders = [];
        /** [ Create array of orders selected ] */
        $('.row-checkbox').each(function (i, item) {
            if ($(this).is(':checked')) {
                orders[i]      = $(this).closest('tr').attr('id');
            }
        });

        $.ajax({
            url:base_url + "/erp/get-jobcard-order-details",
            dataType:"json",
            method:"get",
            data:{orders:orders},
            tryCount : 0,
            retryLimit : 2,
            beforeSend: function () {
              $(".loader").show();
            },
            success: function(data){
                console.log(data)
                /** [ Backdrop for bootstrap modal ] */
                $('#jobcardCreationModalCenter').modal({
                     backdrop: 'static',
                     keyboard: false
                })

                /** [ Validate edit_merchandiser_form form using bootstrap validator ] */
                $('#create_jobcard').validator('validate');
                $('#jobcardCreationModalCenter').modal('show');
            },
            error: function(errorXHR, errorStatus, errorThrown) {
               this.tryCount++;
               if (this.tryCount <= this.retryLimit) {
                   $.ajax(this);
                   return;
               }
            },
            complete: function () {
                $(".loader").hide();
            }
        })
        
    }
}