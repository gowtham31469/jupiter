/**
 * Method to parse the xml string object.
 * Invoked inside uploadPurchaseOrder() method.
 * @param  {string} xml Data obtained from uploadPurchaseOrder() method
 */
function parseXML(xml) {
    try {
        // var type = localStorage.getItem("template type");
        var type = 'single';
        switch(type) {
            case 'single': parseSingleXML(xml);
                break;
            case 'ratio': parseRatioXML(xml);
                break;
            case 'ratio pack': parseRatioPackXML(xml);
                break;
        }

    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}

/**
 * Method to parse xml for SINGLE type.
 * @param  {xml} xml string to be parsed.
 * @return {object}     Object array with all the required fields.
 */
function parseSingleXML(xml) {
    var singleObj = {};
    //PO Number
        var po_string = $(xml).find('#1').text();
        var po = (po_string.length > 15) ? po_string.substring(15) : 'NA';
        singleObj.purchaseOrder = po;
    //
    console.log(singleObj);
}