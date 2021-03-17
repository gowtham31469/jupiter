/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
function createTescoCareOrders() {
    $('.main-content').load('/admin/tesco/care-label-orders');
    $('li.year-nav-menu').addClass('hidden');
    $('li.buyer-nav-menu').addClass('hidden');
    console.log("PKDDD");
}