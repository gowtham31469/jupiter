function printWashDivOs(front,back,middle,static,uvc,po,user_type,page_flag,tableID,tableName,print_flag)
{
    var a1 = document.getElementById(front);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;

    var a1_back = document.getElementById(back);
    var svgDoc1_back = a1_back.contentDocument;
    var svgRoot1_back = svgDoc1_back.documentElement;

	var index_arr=[];
	var svgarr = [];
	var svgarr_back = [];
	var i=0;
	var ajax=0;

    $.ajax({
        url: base_url + "/admin/orchestra/get-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_id: tableID,
            table_name: tableName,
            style_no: uvc,
            buyer_no: po
        },
        beforeSend: function() {

        },
        success: function(data) {

            var product_family = data.product_family;
            var orchestra_wash_care_label_orders = data.orchestra_wash_care_label_orders;

            $(data.orchestra_wash_care_label_orders).each(function(index,value){

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
                    url: base_url + "/"+user_type+"/order-management/get-tsl-size",
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
                            //svgDoc1.getElementById("size2").innerHTML = size_txt;
                            svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
                            //svgDoc1.getElementById("size_in_fr_en2").innerHTML = "";

                            svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
                            svgDoc1_back.getElementById("size2").innerHTML = size_txt;
                        }
                        else
                        {
                            if(data.size_chart.length > 0 )
                            {
                                svgDoc1.getElementById("size_in_fr_en1").innerHTML = data.size_chart[0].size+" cm ";
                                //svgDoc1.getElementById("size_in_fr_en2").innerHTML = data.size_chart[0].size+" cm ";
                                svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = data.size_chart[0].size + "cm";
                            }
                            else
                            {
                                svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
                                svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
                                //svgDoc1.getElementById("size_in_fr_en2").innerHTML = "";
                            }

                            svgDoc1.getElementById("size1").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
                            svgDoc1_back.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
                            //svgDoc1.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
                        }
                        
                        svgDoc1.getElementById("parcel_code1").innerHTML = uvc_tco;
                        svgDoc1_back.getElementById("parcel_code2").innerHTML = uvc_tco;
                        //svgDoc1.getElementById("parcel_code2").innerHTML = uvc_tco;

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


                        svgDoc1_back.getElementById("product_family").innerHTML = product_family.length > 0 ? product_family[0].arabic : "";
                        svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -37.5 154) rotate(270)');
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

            var washing_select = data.orchestra_wash_care_label_orders[0].washing_id;
            var whitening_select = data.orchestra_wash_care_label_orders[0].whitening_id;
            var dryer_select = data.orchestra_wash_care_label_orders[0].dryer_id;
            var ironing_select = data.orchestra_wash_care_label_orders[0].ironing_id;
            var cleaning_select = data.orchestra_wash_care_label_orders[0].cleaning_id;

            var made_in_id = data.orchestra_wash_care_label_orders[0].made_in_id;
            var care_instruction_id = data.orchestra_wash_care_label_orders[0].care_instruction_id;


            var washcomp=[];
            $(data.orchestra_wash_care_label_orders[0].parts_comp).each(function (index_part,value_part) {
                var washcomp1=[];
                $(data.orchestra_wash_care_label_orders[0].care_composition).each(function(index_comp,value_comp){
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
                PopupWashos(svg_fin,svgRoot1_back,svgRoot1_middle, tableID,svgDoc1,user_type,svg_fin_back,svgDoc1_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,made_in_id,care_instruction_id,wash_finals);
            }
        },
        error: function(data) {

        },
        complete: function() {

        }
    });
}


function PopupWashos(data,data_back,data_middle,table_id,svgDoc1,user_type,data_fin_back,svgDoc1_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,made_in_id,care_instruction_id,compo_select) {
	
    
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
    var made_in_select = "";
    
    
    var care_instruction_select_fin = "";
    var wash_finals ="";
    
    $.ajax({
        url: base_url + "/"+user_type+"/order-management/print-wash-pdf",
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
            
        },
        async: false,
        success: function (data) {
            success = true;
            url = data;
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}