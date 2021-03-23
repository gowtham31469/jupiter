/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
    // Menthod to render tesco care label orders page
    renderTescoCareOrders = () => {
        $('.main-content').load('/admin/tesco/care-label-orders');
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');

    }

    // Menthod to validate and submit tesco care label orders
    submitTescoCareOrders = () => {
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

    // Method to add dynamic garment component
    var addComponent = () => {
        $('.garment-component-div').append($('.component-div-1').html());
    }