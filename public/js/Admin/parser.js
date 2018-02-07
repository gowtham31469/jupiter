/**
 * Method to parse the xml string object.
 * Invoked inside uploadPurchaseOrder() method.
 * @param  {string} xml Data obtained from uploadPurchaseOrder() method
 */
function parseXML(xml) {
    try {
        var pic = $(xml).find('#1').text();
        console.log(pic);

    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}