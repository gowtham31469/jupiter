/** 
 * Tesco Swing tickets JS 
 * @author Praveen
 * 
 */

// Method to render tesco care label orders page
renderTescoSwingTicketOrders = (role, template_id, callback=null) => {
    var user_role = role.toLowerCase();
    var size_id = $('#tesco_care_size_chart').val();
    

    $('.main-content').load('/' + user_role + '/tesco/swing-ticket-orders/'+size_id, ( response, status, xhr ) => {
        $(".selectpicker").selectpicker("refresh");
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');
    }); 
}