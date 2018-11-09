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
    if ($("#approved-order-list input[class='row-checkbox']:not(:checked)").length > 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		showSnackbar();
    }
    else
    {
        $('#jobcardCreationModalCenter').modal('show');
    }
}