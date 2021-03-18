/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */


    function renderTescoCareOrders() {
        $('.main-content').load('/admin/tesco/care-label-orders');
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');

    }

    function submitTescoCareOrders() {
        $('#create_tesco_care_order_form').validator('validate');
        
        /** [ bootstrap validator for create tesco order form ] **/
        $('#create_tesco_care_order_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {

            } else {
                e.preventDefault();
                alert();
            }
        });
    }