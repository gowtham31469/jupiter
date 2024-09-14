function printWashDivOs(front,back,middle,static_final,uvc,po,user_type,tableID,tableName,print_type,page_name,index_wcl_count,ref_name)
{

    if(page_name == "co")
    {
        tableID = $('#exist_id'+index_wcl_count).val()
    }

    if(user_type == "admin")
    {
        var buyer_url = base_url + "/admin/orchestra/get-order-details";
        var wash_data = {
                            _token: csrf_token,
                            table_id: tableID,
                            table_name: tableName,
                            ref_name: ref_name,
                            style_no: uvc,
                            buyer:"orchestra",
                            buyer_no: po
                        }
        var size_url = base_url + "/"+user_type+"/order-management/get-tsl-size";
    }
    else if(user_type == "customer")
    {
        var buyer_url = base_url + "/customer/orchestra/get-order-details";
        var wash_data = {
                            _token: csrf_token,
                            table_id: tableID,
                            table_name: tableName,
                            ref_name: ref_name,
                            style_no: uvc,
                            buyer:"orchestra",
                            buyer_no: po
                        }
        var size_url = base_url + "/"+user_type+"/order-management/get-tsl-size";
    }
    else if(user_type == "erp")
    {
        var buyer_url = base_url + "/erp/job-manager/get-order-details";
        var wash_data = {
                            _token: csrf_token,
                            id: tableID,
                            table_name: tableName,
                            ref_name: ref_name,
                            style_no: uvc,
                            buyer:"orchestra",
                            buyer_no: po
                        }
        var size_url = base_url + "/"+user_type+"/job-manager/tesco/get-tsl-size";
    }
    else if(user_type == "merchandiser")
    {
        var buyer_url = base_url + "/merchandiser/orchestra/get-order-details";

        front = tableID+"_"+front;
        back = tableID+"_"+back;
        middle = tableID+"_"+middle;
        static_final = tableID+"_"+static_final;
        var wash_data = {
                            _token: csrf_token,
                            table_id: tableID,
                            table_name: tableName,
                            ref_name: ref_name,
                            style_no: uvc,
                            buyer:"orchestra",
                            buyer_no: po
                        }
        var size_url = base_url + "/"+user_type+"/order-management/get-tsl-size";
    }

    var a1 = document.getElementById(front);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;

    var a1_back = document.getElementById(back);
    var svgDoc1_back = a1_back.contentDocument;
    var svgRoot1_back = svgDoc1_back.documentElement;

	var svgarr = [];
	var svgarr_back = [];
	var i=0;
	var ajax=0;

    $.ajax({
        url: buyer_url,
        type: "Post",
        dataType: "json",
        data: wash_data,
        beforeSend: function() {

        },
        success: function(data) {
            if(user_type == "erp")
            {
                var product_family = data.order_details.product_family;
                var orchestra_wash_care_label_orders = data.order_details.orchestra_wash_care_label_orders;
                var activity_label = data.order_details.orchestra_wash_care_label_orders[0].activity_label;
                var created_at = data.order_details.orchestra_wash_care_label_orders[0].created_at;
            }
            else
            {
                var product_family = data.product_family;
                var orchestra_wash_care_label_orders = data.orchestra_wash_care_label_orders;
                var activity_label = data.orchestra_wash_care_label_orders[0].activity_label;
                var created_at = data.orchestra_wash_care_label_orders[0].created_at;
            }

            $(orchestra_wash_care_label_orders).each(function(index,value){

                var size_txt = value.size;
                var uvc_tco = value.uvc_tco;
                var size = value.size.split(" ")

                if (size[1] == "ANS") {
                    var size1 = "years";
                } else {
                    var size1 = "months";
                }
                
                var code = value.barcode;

                $.ajax({
                    url: size_url,
                    type: 'GET',
                    async: false,
                    data: {
                        size: size_txt
                    },
                    tryCount: 0,
                    retryLimit: 2,
                    success: function (data) {
                        //console.log(data)
                        if(size[0] == "TAILLE")
                        {
                            svgDoc1.getElementById("size1").innerHTML = size_txt;
                            svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";

                            svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
                            svgDoc1_back.getElementById("size2").innerHTML = size_txt;
                        }
                        else
                        {
                            if(data.size_chart.length > 0 )
                            {
                                svgDoc1.getElementById("size_in_fr_en1").innerHTML = data.size_chart[0].size+" cm ";
                                svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = data.size_chart[0].size + "cm";
                            }
                            else
                            {
                                svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
                                svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
                            }

                            svgDoc1.getElementById("size1").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
                            svgDoc1_back.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
                        }
                        
                        svgDoc1.getElementById("parcel_code1").innerHTML = uvc_tco;
                        svgDoc1_back.getElementById("parcel_code2").innerHTML = uvc_tco;

                        svgDoc1.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("astm_symbol1").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("astm_symbol2").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("astm_symbol3").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("astm_symbol4").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                        svgDoc1.getElementById("astm_symbol5").setAttribute('xlink:href', "");

                       
                        svgDoc1_back.getElementById("season_code").innerHTML = orchestra_wash_care_label_orders[0].season_code

                        if(activity_label == "PUERICULTURE")
                        {
                            svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -40 380) rotate(270)');
                        }
                        else
                        {
                            svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -40 400) rotate(270)');
                        }
                        
                        jsbarcodewash(code, 'barcode', svgDoc1_back)


                        var s_back = new XMLSerializer();
                        var d_back = svgRoot1_back;
                        var str_back = s_back.serializeToString(d_back);
                        var svg_back = encodeURI(str_back)
                        svgarr_back.push(svg_back)
                        


                        var s = new XMLSerializer();
                        var d = svgRoot1;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax++;
                    },
                    error: function (errorXHR, errorStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {

                    }
                })
                
                i++;	

            })

            var washing_select = orchestra_wash_care_label_orders[0].washing_id;
            var whitening_select = orchestra_wash_care_label_orders[0].whitening_id;
            var dryer_select = orchestra_wash_care_label_orders[0].dryer_id;
            var ironing_select = orchestra_wash_care_label_orders[0].ironing_id;
            var cleaning_select = orchestra_wash_care_label_orders[0].cleaning_id;

            var made_in_id = orchestra_wash_care_label_orders[0].made_in_id;
            var care_instruction_id = orchestra_wash_care_label_orders[0].care_instruction_id;


            var washcomp=[];
            $(orchestra_wash_care_label_orders[0].parts_comp).each(function (index_part,value_part) {
                var washcomp1=[];
                $(orchestra_wash_care_label_orders[0].care_composition).each(function(index_comp,value_comp){
                    if(value_part.parts_id == value_comp.parts_id)
                    {
                        washcomp1.push(value_comp.parts_id +"_"+ value_comp.english +"_"+ value_comp.percentage);
                    }
                });
                washcomp.push(washcomp1)
            })


            var wash_finals = JSON.stringify(washcomp);



            var a1_middle = document.getElementById(middle);
            var svgDoc1_middle = a1_middle.contentDocument;
            var svgRoot1_middle = svgDoc1_middle.documentElement;
            var svg_fin = svgarr.join(',_');
            var svg_fin_back = svgarr_back.join(',_');
            
            if(i == ajax)
            {
                var product_image = product_family.length > 0 ? product_family[0].image : "";
                PopupWashos(svg_fin,svgRoot1_back,svgRoot1_middle, tableID,user_type,svg_fin_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,made_in_id,care_instruction_id,wash_finals,product_image,activity_label,created_at,po,uvc,ref_name);
            }
        },
        error: function(data) {

        },
        complete: function() {

        }
    });
}


function PopupWashos(data,data_back,data_middle,table_id,user_type,data_fin_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,made_in_id,care_instruction_id,compo_select,pro_family_image,activity_label,created_at,po,uvc,ref_name) {
	
    
    var s = new XMLSerializer();
	
	var d_back = data_back;
    var str_back = s.serializeToString(d_back);
    var svg_back = encodeURI(str_back)
	
	var d_middle = data_middle;
    var str_middle = s.serializeToString(d_middle);
	var svg_middle = encodeURI(str_middle)
	
	var id = table_id
	
    var success = false;
    var url = "";

    if(user_type == "erp")
    {
        var download_url = base_url + "/"+user_type+"/job-manager/orchestra/print-wash-pdf";
    }
    else
    {
        var download_url = base_url + "/"+user_type+"/order-management/print-wash-pdf";
    }
    
    $.ajax({
        url: download_url,
        type: "post",
        data: {
            _token: csrf_token,
			made_in_select:made_in_id,
			id:id,
            care_instruction_select_fin:care_instruction_id,
            composition:compo_select,
            svg: data,
            svg_back: svg_back,
            svg_middle: svg_middle,
            data_fin_back:data_fin_back,
            washing_select:washing_select,
            whitening_select:whitening_select,
            dryer_select:dryer_select,
            ironing_select:ironing_select,
            cleaning_select:cleaning_select,
            pro_family_image:pro_family_image,
            activity_label:activity_label,
            created_at:created_at,
            buyer_no:po,
            style_no:uvc,
            ref_name:ref_name
        },
        async: false,
        success: function (data) {
            success = true;
            if(user_type == "erp")
            {
                url = data.file_path;
            }
            else
            {
                url = data;
            }
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}

function jsbarcodewash(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '50px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '92.2px');
    svg.setAttribute('height', '133.5px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    svg.getElementsByTagName('text')[2].setAttribute('letter-spacing', '5px')
    svg.getElementsByTagName('text')[4].setAttribute('letter-spacing', '5px')
    svg.getElementsByTagName('text')[0].setAttribute('x', '20')
    svg.getElementsByTagName('text')[0].setAttribute('y', '350')
    svg.getElementsByTagName('text')[2].setAttribute('y', '350')
    svg.getElementsByTagName('text')[4].setAttribute('y', '350')

    console.log(svg)


    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}