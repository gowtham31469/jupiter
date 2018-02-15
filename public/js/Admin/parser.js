/**
 * Method to parse the xml string object.
 * Invoked inside uploadPurchaseOrder() method.
 * @param  {string} xml Data obtained from uploadPurchaseOrder() method
 */
function parseXML(data) {
    try {
        // Check for Purchase Order Type
        var po_type = '';
        $(data.xml).find('page[id="2"] textbox[id="0"] textline:first text').each(function() {
            po_type += $(this).text();
        });
        // po_type = $.trim(po_type);
        po_type = 'SINGLE';
        switch(po_type) {
            case 'SINGLE': parseSingleXML(data);
                break;
            case 'RATIO': parseRatioXML(data.xml);
                break;
            case 'RATIO PACK': parseRatioPackXML(data.xml);
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
function parseSingleXML(data) {
    var singleObj = {};
    var xml = data.xml;
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************ORDER DETAILS SECTION**************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
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

    //Short Descriptionx
        var short_desc = ''; 
        $(xml).find('page[id="2"] textbox[id="2"] textline:nth-child(2) text').each(function() {
            short_desc = ($(this).text() === '') ? short_desc += ' ' : short_desc += $(this).text();
        });
        singleObj.shortDescription = $.trim(short_desc);

    //Brand
        var brand = ''; 
        $(xml).find('page[id="2"] textbox[id="0"] textline:nth-child(5) text').each(function() {
            brand = ($(this).text() === '') ? brand += ' ' : brand += $(this).text();
        });
        singleObj.brand = $.trim(brand);

    //Supplier Name
        var supplier_name = ''; 
        $(xml).find('page[id="1"] textbox[id="21"] textline:nth-child(2) text').each(function() {
            supplier_name = ($(this).text() === '') ? supplier_name += ' ' : supplier_name += $(this).text();
        });
        singleObj.supplierName = $.trim(supplier_name);

    //Supplier Code
        var supplier_code = ''; 
        $(xml).find('page[id="1"] textbox[id="20"] textline text').each(function() {
            supplier_code = ($(this).text() === '') ? supplier_code += ' ' : supplier_code += $(this).text();
        });
        singleObj.supplierCode = $.trim(supplier_code);

    //Cargo Ready Date
        var cargo_ready_date = ''; 
        $(xml).find('page[id="1"] textbox[id="26"] textline text').each(function() {
            cargo_ready_date = ($(this).text() === '') ? cargo_ready_date += ' ' : cargo_ready_date += $(this).text();
        });
        singleObj.cargoReadyDate = $.trim(cargo_ready_date);
/*
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************ORDER QUANTITY SECTION**************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    singleObj.orderQuantity = {};
    //TPND collection
        var tpnd = [];
        $(xml).find('page[id="2"] textbox[id="12"] textline').each(function() {
            var tpnd_no = '';
            $(this).find('text').each(function() {
                tpnd_no += $(this).text();
            });
            tpnd.push(parseInt(tpnd_no));
        });
        singleObj.orderQuantity.TPND = tpnd;

    //EAN Barcode
        var EAN_barcode = [];
        $(xml).find('page[id="2"] textbox[id="22"] textline').not(':first-child').each(function() {
            var ean_bc = '';
            $(this).find('text').each(function() {
                ean_bc += $(this).text();
            });
            EAN_barcode.push(parseInt(ean_bc));
        });
         singleObj.orderQuantity.EANBarcode = EAN_barcode;

    //Size Collection
        var size = [];
        $(xml).find('page[id="2"] textbox[id="16"] textline').not(':first-child').each(function() {
            var size_chart = '';
            $(this).find('text').each(function() {
                size_chart =  ($(this).text() === '') ? size_chart += ' ' : size_chart += $(this).text();
            });
            size.push($.trim(size_chart));
        });
        singleObj.orderQuantity.size = size;

    //Case Size
        var caseSize = [];
        $(xml).find('page[id="2"] textbox[id="20"] textline').each(function() {
            var case_size = '';
            $(this).find('text').each(function() {
                case_size += $(this).text();
            });
            caseSize.push($.trim(case_size));
        });         
        singleObj.orderQuantity.caseSize = caseSize;

        */
       
    console.log(singleObj);
    //*********************************************************************************************************
    //*********************************************************************************************************
    //********************************************* ORDER QUANTITY SECTION ************************************
    //********************************************* PARSE THE JSON TABULA *************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    //*********************************************************************************************************
    var header = [];
    var values = [];
    var tabula = JSON.parse(data.json);
    tabula[0].data.pop(); //Remove the total quantity row from the object
    $.each(tabula[0].data, function(index,element) {
        var row = {};
        element.pop();//Remove the extra space element from the array
        $.each(element, function(i, val) {
            if(index === 0) {
                header.push(val.text);
            } else {
                row[header[i]] = val.text;  
            }
        });
        if(index !== 0) {
           values.push(row); 
        } 
    });
    console.log(header);
    console.log(values);
    

}