/**
 * Method to parse the xml string object.
 * Invoked inside uploadPurchaseOrder() method.
 * @param  {string} xml Data obtained from uploadPurchaseOrder() method
 */
function parseXML(xml) {
    try {
        // Check for Purchase Order Type
        var po_type = '';
        $(xml).find('page[id="2"] textbox[id="0"] textline:first text').each(function() {
            po_type += $(this).text();
        });
        po_type = $.trim(po_type);
        switch(po_type) {
            case 'SINGLE': parseSingleXML(xml);
                break;
            case 'RATIO': parseRatioXML(xml);
                break;
            case 'RATIO PACK': parseRatioPackXML(xml);
                break;
            default: console.log("Failed to identify Purchase Order Type!! Exiting..")
                break;
        }

    } catch(e) {
        // error statements
        console.log("Error:"+e);
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
        var po_string = ''; 
        $(xml).find('page[id="1"] textbox[id="1"] text').each(function() {
            po_string += $(this).text();
        });
        singleObj.purchaseOrder = $.trim(po_string);

    //Style Number
        var style_number = ''; 
        $(xml).find('page[id="2"] textbox[id="1"] text').each(function() {
            style_number += $(this).text();
        });
        singleObj.styleNumber = $.trim(style_number);
    //Season Code
        var season_code = ''; 
        $(xml).find('page[id="2"] textbox[id="4"] text').each(function(){ 
            season_code += $(this).text();
        });
        singleObj.seasonCode = $.trim(season_code);

    //Short Description
        var short_desc = ''; 
        $(xml).find('page[id="2"] textbox[id="2"] textline:nth-child(2) text').each(function() {
            short_desc = ($(this).text() === '') ? short_desc += ' ' : short_desc += $(this).text();
        });
        singleObj.shortDescription = $.trim(short_desc);



    console.log(singleObj);
}