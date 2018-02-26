/**
 * Method to parse the xml string object.
 * Invoked inside uploadPurchaseOrder() method.
 * @param  {string} xml Data obtained from uploadPurchaseOrder() method
 */
function parseXML(data, callback) {
   // try {
        //Check for Purchase Order Type
        var parsedObj;
        var po_type = data.type;
        switch(po_type) {
            case 'single': parsedObj = parseSingleType(data);
                break;
            case 'ratio': parsedObj = parseRatioType(data);
                break;
            default: console.log("Failed to identify Purchase Order Type!! Exiting..");
                break;
        }
        callback(parsedObj);
    // } catch(e) {
    //     // error statements
    //     console.log("Error:"+e);
    // }
}

/**
 * Method to parse data for SINGLE type.
 * @param  {json} data string to be parsed.
 * @return {object}     Object array with all the required fields.
 */
function parseSingleType(data) {
    try{
        var singleObj = {};
        //*********************************************************************************************************
        //*********************************************************************************************************
        //********************************************* SUPPLIER SECTION    ***************************************
        //********************************************* PARSE THE JSON TABULA *************************************
        //*********************************************************************************************************
        //*********************************************************************************************************
        //*********************************************************************************************************
        if (data.details.hasOwnProperty("supplier")) {
            var supplier = JSON.parse(data.details.supplier);
            supplier = supplier[0].data.slice(-3);
            //Supplier Code
            supplier_code = supplier[0][0].text;
            supplier_code = supplier_code.split(':');
            if(supplier_code[0] === 'Supplier No. ') {
                singleObj.supplierCode = supplier_code[1].trim();
                //Supplier Name
                singleObj.supplierName = supplier[2][0].text.trim();
            }
        }
        
        //*********************************************************************************************************
        //*********************************************************************************************************
        //********************************************* ORDER INFORMATION SECTION *********************************
        //********************************************* PARSE THE JSON TABULA *************************************
        //*********************************************************************************************************
        //*********************************************************************************************************
        //*********************************************************************************************************

        var info = JSON.parse(data.details.info);
        $.each(info[0].data, function(ind, ele) {
            var row_length = ele.length;
            switch(ind) {
                case 0:
                    // singleObj.purchaseOrder = (row_length === 5) ? ele[4].text : ele[5].text;
                    singleObj.purchaseOrder = data.po_number;
                    break;
                case 1:
                    singleObj.type = ele[0].text;
                    break;
                case 2:
                    var season = ele[2].text.split(" ");
                    singleObj.seasonCode = season[0];
                    break;
                case 3:
                    var style = ele[0].text.split(":");
                    var tab = JSON.parse(data.details.table);
                    var size = tab[0].data[1][3].text;
                    singleObj.styleNumber =  style[1].trim();
                    singleObj.shortDescription =  ele[2].text.replace(size, '').trim();
                    break;
            }
        });

        //*********************************************************************************************************
        //*********************************************************************************************************
        //********************************************* ORDER QUANTITY SECTION ************************************
        //********************************************* PARSE THE JSON TABULA *************************************
        //*********************************************************************************************************
        //*********************************************************************************************************
        //*********************************************************************************************************
        singleObj.orderQuantity = {};
        var header = [];
        var values = [];
        var tabula = JSON.parse(data.details.table);
        tabula[0].data.pop(); //Remove the total quantity row from the object
        $.each(tabula[0].data, function(index,element) {
            var row = {};
            element.pop();//Remove the extra space element from the array
            $.each(element, function(i, val) {
                if(index === 0) {
                    header.push(val.text.replace(/ /g,"_"));
                } else {
                    row[header[i]] = val.text;  
                }
            });
            if(index !== 0) {
               values.push(row); 
            } 
        });
        singleObj.orderQuantity = values;
        return singleObj;
        
    } catch(e) {
        console.log("Error:"+e);
    }
}

/**
 * Method to parse data for RATIO PACK A type.
 * @param  {json} json string to be parsed.
 * @return {object}     Object array with all the required fields.
 */
function parseRatioType(data) {
    var ratioObj = {};
    //********************************************* SUPPLIER INFO SECTION *********************************
    //********************************************* PARSE THE JSON TABULA *************************************
    if (data.details.hasOwnProperty("supplier")) {
        var supplier = JSON.parse(data.details.supplier);
        supplier = supplier[0].data.slice(-3);
        console.log(supplier);
        //Supplier Code
        supplier_code = supplier[0][0].text;
        supplier_code = supplier_code.split(':');
        if(supplier_code[0] === 'Supplier No. ') {
            ratioObj.supplierCode = supplier_code[1].trim();
            //Supplier Name
            ratioObj.supplierName = supplier[2][0].text.trim();
        }      
    }
    //********************************************* ORDER INFORMATION SECTION *********************************
    //********************************************* PARSE THE JSON TABULA *************************************

    var info = JSON.parse(data.details.info);
    $.each(info[0].data, function(ind, ele) {
        var row_length = ele.length;
        switch(ind) {
            case 0:
                var new_element = removeEmptyValues(ele);
                if(new_element.length < 2) {
                    var po = new_element[0].text;
                    po = po.split(":");
                    ratioObj.purchaseOrder = po[1].trim();
                } else {
                    ratioObj.purchaseOrder = new_element[1].text.trim();
                }
                break;
            case 1:
                ratioObj.type = ele[0].text;
                break;
            case 2:
                var new_element = removeEmptyValues(ele);
                var product = new_element[1].text.split(":");
                var season = product[1].trim().split(" ");
                ratioObj.seasonCode = season[0];
                break;
            case 3:
                ratioObj.styleNumber =  ele[1].text.trim();
                break;
            case 4:
                ele[1].text = ele[1].text.replace('12/18', '').trim();
                var short_desc = ele[1].text.split('  ');
                ratioObj.shortDescription = (short_desc.length === 1) ? ele[1].text.trim() : ele[1].text.replace(short_desc[1], '').trim();
        }
    });


    //********************************************* ORDER QUANTITY SECTION ************************************
    //********************************************* PARSE THE JSON TABULA *************************************
    ratioObj.orderQuantity = {};
    var tabula = [];
    var packA = JSON.parse(data.details.table);
    tabula.push(packA[0].data[0]);
    if (data.details.hasOwnProperty("ratioPackB")) {
        var packB = JSON.parse(data.details.ratioPackB);
        tabula.push(packB);
    }
    var values = [];
    $.each(tabula, function(index,element) {
        var row = {};
        $.each(element, function(i, val) {
            switch(i) {
                case 2 : row.unitsPerCarton =  val.text;
                break;

                case 4 : row.barCode =  val.text;
                break;

                case 5 : row.tpnd = val.text;
                break;
            }
        });
        values.push(row);
        
    });
    ratioObj.orderQuantity = values;
    return ratioObj;
    
}

/**
 * Method to remove the empty spaces from the object
 * @param  {array} element element array with empty values
 * @return {array} element element array without empty values
 */
function removeEmptyValues(element) {
    element = $.grep(element, function (el, i) {
        if (el.text === '') { // or whatever
            return false;
        }
        return true; // keep the element in the array
    });
    return element;
}

/**
 * Method to render the parsed objects to the input field
 * @param  {[type]} parsedObj [description]
 * @return {[type]}           [description]
 */
function renderParsedValues(parsedObj) {
    console.log(parsedObj);
    var zone        = localStorage.getItem("zone").toLowerCase();
    var type        = localStorage.getItem("template type");
    $('#tesco_po_number').val(parsedObj.purchaseOrder);
    if(zone === 'ce') {
        $('#tesco_ce_style').val(parsedObj.styleNumber);
     } else {
        $('#tesco_uk_style').val(parsedObj.styleNumber);
     }
    $('#tesco_eqos').val(parsedObj.supplierCode);
    $('#tesco_season_code').val(parsedObj.seasonCode);
    $('#tesco_description').val(parsedObj.shortDescription);


    if(type === 'single') {
        $.each(parsedObj.orderQuantity, function(i, j) {
            var index = i + 1;
            $('#'+zone+'_single_tpnd_'+index).val(j.TPND);
            $('#'+zone+'_single_ean_'+index).val(j.EAN_Barcode);
            $('#'+zone+'_single_case_size_'+index).val(j.Case_Size);
            $('#'+zone+'_single_size_'+index).val(j.Size);  
            if(parsedObj.orderQuantity.length !== (i+1)) {
                $('.add-row-btn').trigger('click');
            }
        });  
    } else if(type === 'ratio') {
        $.each(parsedObj.orderQuantity, function(i, j) {
            var index = i + 1;
            $('#'+zone+'_ratio_tpnd_'+index).val(j.tpnd);
            $('#'+zone+'_ratio_itf_'+index).val(j.barCode);
            $('#'+zone+'_ratio_upc_'+index).val(j.unitsPerCarton);
            if(parsedObj.orderQuantity.length !== (i+1)) {
                $('.add-row-btn').trigger('click');
            }
        }); 
    }

    dynamicSVGTemplates();
}

var _validFileExtensions = [".pdf"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                $('.snackbar_span').html("Sorry!! You seem to have uploaded an invalid file format. Please upload a PDF file.");
                $('#snackbar').css('background-color','#F44336');
                showSnackbar();
                $("#"+oInput.id).val(null);
                return false;
            }
        }
    }
    return true;
}

/**
 * Method to parse xml for SINGLE type.
 * @param  {xml} xml string to be parsed.
 * @return {object}     Object array with all the required fields.
 */
function parseSingleXml(data) {
    var singleObj = {};
    var xml = data.info;
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

        return singleObj;
}