
function downloadArtworkTickets(data, name,style_no,buyer_no,id,order_type,obj,art_temp_id,table_name_print,art_type,buyer_type) {

    if(buyer_type == "admin")
    {
        var buyer_url = base_url + "/admin/tesco/get-order-details";
        var name2=name;
        var a_back = document.getElementById(name2+"_"+art_temp_id+"_back_modal");
    }
    else if(buyer_type == "customer")
    {
        var buyer_url = base_url + "/customer/tesco/get-order-details";
        var name2=name;
        var a_back = document.getElementById(name2+"_"+art_temp_id+"_back_modal");
    }
    else if(buyer_type == "erp")
    {
        var buyer_url = base_url + "/erp/job-manager/get-order-details";
        if(name == "GE/01/M13114C-Opt1")	
        {	
            var name2=name.replace(/-Opt1/g, " - Opt 1");	
            var name_modal=name;	
        }	
        else if(name == "GE/01/M13114C-Opt2")	
        {	
            var name2=name.replace(/-Opt2/g, " - Opt 2");	
            var name_modal=name;	
        }	
        else if(name == "GE/01/M13114C-Opt3")	
        {	
            var name2=name.replace(/-Opt3/g, " - Opt 3");	
            var name_modal=name;	
        }	
        else if(name == "GE/01/M13114C-Opt4")	
        {	
            var name2=name.replace(/-Opt4/g, " - Opt 4");	
            var name_modal=name;	
        }	
        else	
        {	
            var name2=name;	
            var name_modal=name;	
        }
        var a_back = document.getElementById(name_modal+"_"+art_temp_id+"_back_modal");
    }

    $(obj).prop('disabled', true);

	var a = document.getElementById(data);
    var svgDoc = a.contentDocument;
    
    var name = name + "-sticker";
    
    
    var svgDoc_back = a_back.contentDocument;

    var svgarr = [];
    var svgarr_back = [];
    var wee_symbol = [];
    var ce_symbol = [];
    var size_category_arr = [];
    var size_comp_arr = [];
    var product_desc_arr = [];
    ajax_count=0;
    $.ajax({
        url: buyer_url,
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            name: name,
            id: id,
            table_name_print,
            style_no: style_no,	
			buyer_no: buyer_no,	
			order_type:order_type,	
			ref_name: name2
        },
        beforeSend: function() {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function(data) {

            var sticker_name = name.trim();

            $(data.order_details).each(function (key, val) {
                //console.log(name)
                
                if(sticker_name == "FFK/01/M13021C-sticker")
                {
                    var temp_data = M13021C(data,svgDoc_back,val)
                }
                else if(sticker_name == "FFC/01/M13022C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13022C(data,svgDoc_back,val)
                }
                else if(sticker_name == "FFK/01/M13163C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13163C(data,svgDoc_back,val)
                }
                else if(sticker_name == "FFC/01/M13020C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13020C(data,svgDoc_back,val)
                }
                else if(sticker_name == "FFB/01/M13233C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13233C(data,svgDoc_back,val)
                }
                else if(sticker_name == "FFB/01/M13023C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13021C(data,svgDoc_back,val)
                }
                else if(sticker_name == "GE/01/M13114C-Opt1-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13020C(data,svgDoc_back,val)
                }
                else if(sticker_name == "GE/01/M13114C-Opt2-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13114COpt2(data,svgDoc_back,val)
                }
                else if(sticker_name == "GE/01/M13114C-Opt3-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13114COpt3(data,svgDoc_back,val)
                }
                else if(sticker_name == "GE/01/M13114C-Opt4-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13114COpt4(data,svgDoc_back,val)

                    if(val.is_ce_mark_required == true)
                    {
                        svgDoc_back.getElementById("ce_mark").removeAttribute('visibility','hidden')
                    }
                    else
                    {
                        svgDoc_back.getElementById("ce_mark").setAttribute('visibility','hidden')
                    }
                }
                else if(sticker_name == "FFC/01/M13304C-sticker")
                {
                    //console.log(svgDoc_back)
                    var temp_data = M13304C(data,svgDoc_back,val)
                }
                

                
                //console.log(temp_data)

                var svgRoot_back =svgDoc_back.documentElement;
                var s_back = new XMLSerializer();
                var d_back = svgRoot_back;
                var str_back = s_back.serializeToString(d_back);
                var svg_back = encodeURI(str_back)
                svgarr_back.push(svg_back)
                size_category_arr.push($size_category_str)
                wee_symbol.push(val.wee_symbol_file)
                ce_symbol.push(val.is_ce_mark_required)
                size_comp_arr.push(temp_data[0])
                product_desc_arr.push(temp_data[1])
                ajax_count++;
            });


            if(sticker_name == "FFC/01/M13304C-sticker")
            {
                var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
                var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
                var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
                var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;
    
                svgDoc.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
                if(sl_price2  != "")
                {
                    svgDoc.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
                }
                else
                {
                    svgDoc.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
                }
                svgDoc.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

                if(data.order_details[0].is_machine_washable == true)
                {
                    svgDoc.getElementById("machine_logo").removeAttribute('visibility','hidden')
                    svgDoc.getElementById("machine_washable").innerHTML =data.order_details[0].tesco_translation[0].english.toUpperCase();
                }
                else
                {
                    svgDoc.getElementById("machine_logo").setAttribute('visibility','hidden')
                    svgDoc.getElementById("machine_washable").innerHTML =""
                }
                var mm_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)

                if(data.order_details[0].product_benefit_id != null)
                {
                    svgDoc.getElementById("product_benefit").innerHTML = mm_benefit_json[0].english
                }
                else
                {
                    svgDoc.getElementById("product_benefit").innerHTML = ""
                }

                
            }
            else
            {
                if(data.order_details[0].mm_product_benefit_id == null && data.order_details[0].product_benefit_id == null)
                {
                    svgDoc.getElementById("made_mindfully_logo").setAttribute('visibility','hidden')
                    svgDoc.getElementById("mm_benefits").innerHTML = "";
                }
                else
                {
                    if(data.order_details[0].mm_product_benefit_id != null)
                    {
                        if(sticker_name == "FFC/01/M13022C-sticker" || sticker_name == "FFC/01/M13020C-sticker" || sticker_name == "GE/01/M13114C-Opt1-sticker" || sticker_name == "GE/01/M13114C-Opt2-sticker" || sticker_name == "GE/01/M13114C-Opt3-sticker" || sticker_name == "GE/01/M13114C-Opt4-sticker")
                        {
                            svgDoc.getElementById("lycra").setAttribute('visibility','hidden')
                            svgDoc.getElementById("lycra_extra_life").setAttribute('visibility','hidden')
                        }
                        var mm_benefit_json = JSON.parse(data.order_details[0].mm_product_benefit_text)
        
                        $mm_benefit_str = chunkSubstr(mm_benefit_json[0].czech, 36).split(/\r?\n/);
                        $y_ben = 0;
                        var mm_json_text = "";
                        $($mm_benefit_str).each(function(index,mm_benefit_val){
                            mm_json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+mm_benefit_val+'</tspan>';
                            $y_ben += 7.8;
                        })
        
                        $sl_mm_benefit_str = chunkSubstr(mm_benefit_json[0].slovak, 36).split(/\r?\n/);
                        $($sl_mm_benefit_str).each(function(index,sl_mm_benefit_val){
                            mm_json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+sl_mm_benefit_val+'</tspan>';
                            $y_ben += 7.8;
                        })
        
                        $hu_mm_benefit_str = chunkSubstr(mm_benefit_json[0].hungarian, 36).split(/\r?\n/);
                        $($hu_mm_benefit_str).each(function(index,hu_mm_benefit_val){
                            mm_json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+hu_mm_benefit_val+'</tspan>';
                            $y_ben += 7.8;
                        })
                        svgDoc.getElementById("mm_benefits").innerHTML = mm_json_text;
        
                        
                    }
                    else
                    {
        
                        if(data.order_details[0].is_lycra == true)
                        {
                            svgDoc.getElementById("made_mindfully_logo").setAttribute('visibility','hidden')
                            svgDoc.getElementById("mm_benefits").innerHTML = "";
                            var product_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)
        
                            if(sticker_name == "FFC/01/M13022C-sticker" || sticker_name == "FFC/01/M13020C-sticker" || sticker_name == "GE/01/M13114C-Opt1-sticker" || sticker_name == "GE/01/M13114C-Opt2-sticker" || sticker_name == "GE/01/M13114C-Opt3-sticker" || sticker_name == "GE/01/M13114C-Opt4-sticker")
                            {
                                if(product_benefit_json[0].english == "Lycra")
                                {
                                    svgDoc.getElementById("lycra").removeAttribute('visibility','hidden')
                                    svgDoc.getElementById("lycra_extra_life").setAttribute('visibility','hidden')
                                }
                                else if(product_benefit_json[0].english == "Lycra Extra Life")
                                {
                                    svgDoc.getElementById("lycra").setAttribute('visibility','hidden')
                                    svgDoc.getElementById("lycra_extra_life").removeAttribute('visibility','hidden')
                                }
                            }
                            
                        }
                        else
                        {
                            if(sticker_name == "FFC/01/M13022C-sticker" || sticker_name == "FFC/01/M13020C-sticker" || sticker_name == "GE/01/M13114C-Opt1-sticker" || sticker_name == "GE/01/M13114C-Opt2-sticker" || sticker_name == "GE/01/M13114C-Opt3-sticker" || sticker_name == "GE/01/M13114C-Opt4-sticker")
                            {
                                svgDoc.getElementById("lycra").setAttribute('visibility','hidden')
                                svgDoc.getElementById("lycra_extra_life").setAttribute('visibility','hidden')
                            }
                            svgDoc.getElementById("made_mindfully_logo").setAttribute('visibility','hidden')
                            var product_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)
            
                            $benefit_str = chunkSubstr(product_benefit_json[0].czech, 36).split(/\r?\n/);
                            $y_ben = 0;
                            var json_text = "";
                            $($benefit_str).each(function(index,benefit_val){
                                json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+benefit_val.toLowerCase()+'</tspan>';
                                $y_ben += 7.8;
                            })
            
                            $sl_benefit_str = chunkSubstr(product_benefit_json[0].slovak, 36).split(/\r?\n/);
                            $($sl_benefit_str).each(function(index,sl_benefit_val){
                                json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+sl_benefit_val.toLowerCase()+'</tspan>';
                                $y_ben += 7.8;
                            })
            
                            $hu_benefit_str = chunkSubstr(product_benefit_json[0].hungarian, 36).split(/\r?\n/);
                            $($hu_benefit_str).each(function(index,hu_benefit_val){
                                json_text += '<tspan x="0" y="'+$y_ben+'" style="fill:#8A8B92; font-family:FandFSans-Light; font-size:6.5px;">'+hu_benefit_val.toLowerCase()+'</tspan>';
                                $y_ben += 7.8;
                            })
                            console.log(svgDoc)
                            svgDoc.getElementById("mm_benefits").innerHTML = json_text;
                        }
                        
                    }
                }
                
    
                //Front Svg Price Details
    
                var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
                var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
                var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
                var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;
    
                svgDoc.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
                
                if(sl_price2  != "")
                {
                    svgDoc.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
                }
                else
                {
                    svgDoc.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
                }
                svgDoc.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';
            }
            
            
            var svgRoot = svgDoc.documentElement;
            var s = new XMLSerializer();
            var d = svgRoot;
            var str = s.serializeToString(d);
            var svg = encodeURI(str)
            svgarr.push(svg)

            
            if (ajax_count == data.order_details.length) {
                setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
                var svg_fin = svgarr.join(',_');
                var svg_fin_back = svgarr_back.join(',_');
                var size_category_fin = size_category_arr.join(',_');
                var wee_symbol_fin = wee_symbol.join(',_');
                var ce_symbol_fin = ce_symbol.join(',_');
                var size_comp_fin = size_comp_arr.join(',_');
                var product_desc_fin = product_desc_arr.join(',_');

                if(art_type == "print")
                {
                    PopupTescoTicketsPrintManager(svg_fin,svg_fin_back,data.order_details[0].po_number,data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,size_category_fin,size_comp_fin,product_desc_fin,wee_symbol_fin,ce_symbol_fin,data.order_details[0].woolmark_logo_file);
                }
                else
                {
                    PopupTescoTicketsProofManager(svg_fin,svg_fin_back,data.order_details[0].po_number,data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,size_category_fin,size_comp_fin,product_desc_fin,wee_symbol_fin,ce_symbol_fin,data.order_details[0].woolmark_logo_file,data.order_details[0].tesco_size_department);
                }
                
            }

        },
        error: function(data) {

        },
        complete: function() {

        }
    });

}



function M13304C(data,svgDoc_back,val)
{
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')

    

    

    if(val.woolmark_logo_id != null)
    {

        var product_desc_text = JSON.parse(val.product_desc_text)
        var product_desc_translation ="";
        var product_desc_eng ="";
        $(product_desc_text).each(function (keys, vals) {

            product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6px;">'+vals.english+'</tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="10" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="15" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        })
        svgDoc_back.getElementById("prod_trans_align").innerHTML = product_desc_translation;
        
        


        //Machine Washable
        if(val.is_machine_washable == true)
        {
            svgDoc_back.getElementById("machine_logo_align").removeAttribute('visibility','hidden')
            var machine = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:7px;">'+val.tesco_translation[0].english+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].czech+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].slovak+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].hungarian+'</tspan>';
    
            svgDoc_back.getElementById("machine_washable_trans_align").innerHTML = machine;


            if(val.product_benefit_id != null)
            {
                var mm_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)
                
                var product_benefit_trans = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6px;">'+mm_benefit_json[0].english+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].czech+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].slovak+'</tspan><tspan x="0" y="18" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].hungarian+'</tspan>';
                svgDoc_back.getElementById("product_benefit_trans_align").innerHTML = product_benefit_trans;
            }
            else
            {
                svgDoc_back.getElementById("product_benefit_trans_align").innerHTML = "";
            }


            svgDoc_back.getElementById("prod_trans").innerHTML = "";
            svgDoc_back.getElementById("product_benefit_trans").innerHTML = "";
            svgDoc_back.getElementById("size_list_translation").innerHTML = "";  
            svgDoc_back.getElementById("size_list").innerHTML = "";     
            svgDoc_back.getElementById("machine_logo").setAttribute('visibility','hidden')
            svgDoc_back.getElementById("machine_washable_trans").innerHTML = "";

            if(val.ce_size_name == "tiny baby")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_tiny[0].slovak +" • "+ data.tesco_tiny[0].hungarian+'</tspan>';
                
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";       
            }
            else if(val.ce_size_name.toLowerCase() == "newborn")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_nb[0].slovak +" • "+ data.tesco_nb[0].hungarian+'</tspan>';
                
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";
                
            }
            else if(val.ce_size_name == "up to 1M")
            {
        
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_up1[0].slovak +" • "+ data.tesco_up1[0].hungarian+'</tspan>';
                
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";
                
            }
            else if(val.ce_size_name == "up to 3M")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_up3[0].slovak +" • "+ data.tesco_up3[0].hungarian+'</tspan>';
                
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";
        
            } 
            else if(val.ce_size_name == "One size")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_onesize[0].slovak +" • "+ data.tesco_onesize[0].hungarian+'</tspan>';
                
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";
        
            }
            else
            {
                svgDoc_back.getElementById("size_list_align").innerHTML = val.ce_size_name;
                svgDoc_back.getElementById("size_list_translation_align").innerHTML = "";
            }
        }
        else
        {
            svgDoc_back.getElementById("machine_logo_align").setAttribute('visibility','hidden')
            svgDoc_back.getElementById("machine_washable_trans_align").innerHTML = "";


            if(val.product_benefit_id != null)
            {
                var mm_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)
            
                var product_benefit_trans = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:7px;">'+mm_benefit_json[0].english+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].czech+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].slovak+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].hungarian+'</tspan>';
                svgDoc_back.getElementById("product_benefit_trans").innerHTML = product_benefit_trans;

                svgDoc_back.getElementById("product_benefit_trans_align").innerHTML = "";


                svgDoc_back.getElementById("size_list_translation_align").innerHTML = "";  
                svgDoc_back.getElementById("size_list_align").innerHTML = "";  

                svgDoc_back.getElementById("prod_trans_align").setAttribute('transform', "matrix(1, 0, 0, 1, 55.0881, 60.9614)");


                if(val.ce_size_name == "tiny baby")
                {
                    var size_list_translation = data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech +" • "+ data.tesco_tiny[0].slovak +" • "+ data.tesco_tiny[0].hungarian;
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list").innerHTML = "";       
                }
                else if(val.ce_size_name.toLowerCase() == "newborn")
                {
                    var size_list_translation = data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech +" • "+ data.tesco_nb[0].slovak +" • "+ data.tesco_nb[0].hungarian;
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list").innerHTML = "";
                    
                }
                else if(val.ce_size_name == "up to 1M")
                {
            
                    var size_list_translation = data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech +" • "+ data.tesco_up1[0].slovak +" • "+ data.tesco_up1[0].hungarian;
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list").innerHTML = "";
                    
                }
                else if(val.ce_size_name == "up to 3M")
                {
                    var size_list_translation = data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech +" • "+ data.tesco_up3[0].slovak +" • "+ data.tesco_up3[0].hungarian;
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list").innerHTML = "";
            
                } 
                else if(val.ce_size_name == "One size")
                {
                    var size_list_translation = data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech +" • "+ data.tesco_onesize[0].slovak +" • "+ data.tesco_onesize[0].hungarian;
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list").innerHTML = "";
            
                }
                else
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
                    svgDoc_back.getElementById("size_list_translation").innerHTML = "";
                }

            }
            else
            {

                if(val.ce_size_name == "tiny baby")
                {
                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_tiny[0].slovak +" • "+ data.tesco_tiny[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list_align").innerHTML = "";       
                }
                else if(val.ce_size_name.toLowerCase() == "newborn")
                {
                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_nb[0].slovak +" • "+ data.tesco_nb[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list_align").innerHTML = "";
                    
                }
                else if(val.ce_size_name == "up to 1M")
                {
            
                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_up1[0].slovak +" • "+ data.tesco_up1[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list_align").innerHTML = "";
                    
                }
                else if(val.ce_size_name == "up to 3M")
                {
                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_up3[0].slovak +" • "+ data.tesco_up3[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list_align").innerHTML = "";
            
                } 
                else if(val.ce_size_name == "One size")
                {
                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:6px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:6px;"> • '+ data.tesco_onesize[0].slovak +" • "+ data.tesco_onesize[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = size_list_translation;  
                    svgDoc_back.getElementById("size_list_align").innerHTML = "";
            
                }
                else
                {
                    svgDoc_back.getElementById("size_list_align").innerHTML = val.ce_size_name;
                    svgDoc_back.getElementById("size_list_translation_align").innerHTML = "";
                }
                
                svgDoc_back.getElementById("product_benefit_trans_align").innerHTML = "";

                svgDoc_back.getElementById("prod_trans_align").setAttribute('transform', "matrix(1, 0, 0, 1, 55.0881, 33.9614)");

                svgDoc_back.getElementById("size_list").innerHTML = "";
                svgDoc_back.getElementById("size_list_translation").innerHTML = "";
            }


            svgDoc_back.getElementById("prod_trans").innerHTML = "";
            //svgDoc_back.getElementById("product_benefit_trans").innerHTML = "";
            //svgDoc_back.getElementById("size_list_translation").innerHTML = "";  
            //svgDoc_back.getElementById("size_list").innerHTML = "";     
            svgDoc_back.getElementById("machine_logo").setAttribute('visibility','hidden')
            svgDoc_back.getElementById("machine_washable_trans").innerHTML = "";
        }


        

    }
    else
    {

        var product_desc_text = JSON.parse(val.product_desc_text)
        var product_desc_translation ="";
        var product_desc_eng ="";
        $(product_desc_text).each(function (keys, vals) {

            product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6px;">'+vals.english+'</tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="10" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="15" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        })
        svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;

        if(val.ce_size_name == "tiny baby")
        {
            var size_list_translation = data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech +" • "+ data.tesco_tiny[0].slovak +" • "+ data.tesco_tiny[0].hungarian;
            
            svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
            svgDoc_back.getElementById("size_list").innerHTML = "";       
        }
        else if(val.ce_size_name.toLowerCase() == "newborn")
        {
            var size_list_translation = data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech +" • "+ data.tesco_nb[0].slovak +" • "+ data.tesco_nb[0].hungarian;
            
            svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
            svgDoc_back.getElementById("size_list").innerHTML = "";
            
        }
        else if(val.ce_size_name == "up to 1M")
        {
    
            var size_list_translation = data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech +" • "+ data.tesco_up1[0].slovak +" • "+ data.tesco_up1[0].hungarian;
            
            svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
            svgDoc_back.getElementById("size_list").innerHTML = "";
            
        }
        else if(val.ce_size_name == "up to 3M")
        {
            var size_list_translation = data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech +" • "+ data.tesco_up3[0].slovak +" • "+ data.tesco_up3[0].hungarian;
            
            svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
            svgDoc_back.getElementById("size_list").innerHTML = "";
    
        } 
        else if(val.ce_size_name == "One size")
        {
            var size_list_translation = data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech +" • "+ data.tesco_onesize[0].slovak +" • "+ data.tesco_onesize[0].hungarian;
            
            svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
            svgDoc_back.getElementById("size_list").innerHTML = "";
    
        }
        else
        {
            svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
            svgDoc_back.getElementById("size_list_translation").innerHTML = "";
        }
        
        //Machine Washable
        if(val.is_machine_washable == true)
        {
            svgDoc_back.getElementById("machine_logo").removeAttribute('visibility','hidden')
            var machine = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:7px;">'+val.tesco_translation[0].english+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].czech+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].slovak+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:5px;">'+val.tesco_translation[0].hungarian+'</tspan>';
    
            svgDoc_back.getElementById("machine_washable_trans").innerHTML = machine;

            svgDoc_back.getElementById("product_benefit_trans").setAttribute('transform', "matrix(1, 0, 0, 1, 52.0743, 100.5881)");
        }
        else
        {
            svgDoc_back.getElementById("machine_logo").setAttribute('visibility','hidden')
            svgDoc_back.getElementById("machine_washable_trans").innerHTML = "";

            svgDoc_back.getElementById("prod_trans_align").setAttribute('transform', "matrix(1, 0, 0, 1, 55.0881, 33.9614)");

            svgDoc_back.getElementById("product_benefit_trans").setAttribute('transform', "matrix(1, 0, 0, 1, 107.4243, 100.5881)");
        }
    
        if(val.product_benefit_id != null)
        {
            var mm_benefit_json = JSON.parse(data.order_details[0].product_benefit_text)
            
            var product_benefit_trans = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:7px;">'+mm_benefit_json[0].english+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].czech+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].slovak+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:5px;">'+mm_benefit_json[0].hungarian+'</tspan>';
            svgDoc_back.getElementById("product_benefit_trans").innerHTML = product_benefit_trans;
        }
        else
        {
            svgDoc_back.getElementById("product_benefit_trans").innerHTML = "";
        }


        svgDoc_back.getElementById("machine_logo_align").setAttribute('visibility','hidden')
        svgDoc_back.getElementById("prod_trans_align").innerHTML = "";
    }

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';

    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';
    

    

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketsean(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13114COpt3(data,svgDoc_back,val)
{

    if(val.is_lycra == true)
    {
        svgDoc_back.getElementById("lycra_text").removeAttribute("visibility", "hidden");
    }
    else
    {
        svgDoc_back.getElementById("lycra_text").setAttribute('visibility','hidden');
    }
    
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 3/g, "")+' - '+month_ca+' • M2 • '+val.season_code+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.uk_style_ref+' • '+optionID+'</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.twenty_four_char_desc+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;');

    var country_of_origin_text = JSON.parse(val.country_of_origin_text);

    var english_coo = country_of_origin_text[0].english;
    var czech_coo = country_of_origin_text[0].czech;
    var slovak_coo = country_of_origin_text[0].slovak;
    var hungarian_coo = country_of_origin_text[0].hungarian;

    var country_of_origin = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+english_coo+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+czech_coo+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:4.5px;">'+slovak_coo+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+hungarian_coo+'</tspan>'

    svgDoc_back.getElementById("made_in").innerHTML = country_of_origin;

    var product_desc_text = JSON.parse(val.product_desc_text)
    var product_desc_translation ="";
    var product_desc_eng ="";
    $(product_desc_text).each(function (keys, vals) {

        product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        product_desc_eng = vals.english;
        
    })
    svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;

    if(val.ce_size_name == "tiny baby")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";      
        
		svgDoc_back.getElementById("size_list_right_align1").innerHTML = "";
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = ""; 
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";
        
		svgDoc_back.getElementById("size_list_right_align1").innerHTML = "";
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 1M")
    {

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

		svgDoc_back.getElementById("size_list_right_align1").innerHTML = "";
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 3M")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

		svgDoc_back.getElementById("size_list_right_align1").innerHTML = "";
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = "";

    } 
    else if(val.ce_size_name == "One size")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_onesize[0].slovak+" • "+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

		svgDoc_back.getElementById("size_list_right_align1").innerHTML = "";
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = "";

    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
        svgDoc_back.getElementById("size_list_translation").innerHTML = "";


        if(val.ce_size_name.toString().slice(-1) == "M")
        {
            if(val.ce_size_name == "0-3M")
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">months  · </tspan><tspan x="17.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_under[0].czech+'  · </tspan><tspan x="35" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_under[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "M")
            {
                var size_list_right_align1 = "";
                var size_list_right_align2 = "";
            }
            else
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">months  · </tspan><tspan x="17.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_over[0].czech+'  · </tspan><tspan x="35" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_over[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_month_over[0].hungarian+'</tspan>';
            }
        }
        else if(val.ce_size_name.toString().slice(-1) == "Y")
        {
            if(val.ce_size_name == "1- 1½Y")
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">years  · </tspan><tspan x="14.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_und[0].czech+' · </tspan><tspan x="26" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_und[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_und[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "1-2Y")
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">years  · </tspan><tspan x="14.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].czech+'  · </tspan><tspan x="27" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "2-3Y")
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">years  · </tspan><tspan x="14.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].czech+'  · </tspan><tspan x="27" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "3-4Y")
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">years  · </tspan><tspan x="14.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].czech+'  · </tspan><tspan x="27" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else
            {
                var size_list_right_align1 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">years  · </tspan><tspan x="14.69" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_over[0].czech+'  · </tspan><tspan x="24" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_over[0].slovak+'</tspan>';

                var size_list_right_align2 = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">· </tspan><tspan x="2.21" y="0" style="font-family:FandFSans-Regular; font-size:4.3px;">'+val.tesco_year_over[0].hungarian+'</tspan>';
            }
        }
        else
        {
            var size_list_right_align1 = "";
            var size_list_right_align2 = "";
        }

        svgDoc_back.getElementById("size_list_right_align1").innerHTML = size_list_right_align1;
        svgDoc_back.getElementById("size_list_right_align2").innerHTML = size_list_right_align2;
    }

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketseanhoriz(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13233C(data,svgDoc_back,val)
{
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name+' - '+month_ca+' • M2 • '+val.season_code+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.uk_style_ref+' • '+optionID+'</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.twenty_four_char_desc+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')

    var country_of_origin_text = JSON.parse(val.country_of_origin_text);

    var english_coo = country_of_origin_text[0].english;
    var czech_coo = country_of_origin_text[0].czech;
    var slovak_coo = country_of_origin_text[0].slovak;
    var hungarian_coo = country_of_origin_text[0].hungarian;

    var country_of_origin = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+english_coo+' / '+czech_coo+' / </tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+slovak_coo+' /  '+hungarian_coo+'</tspan>'

    svgDoc_back.getElementById("made_in").innerHTML = country_of_origin;

    var product_desc_text = JSON.parse(val.product_desc_text)
    var product_desc_translation ="";
    var product_desc_eng ="";
    $(product_desc_text).each(function (keys, vals) {

        product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        product_desc_eng = vals.english;
        
    })
    svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;

    if(val.ce_size_name == "tiny baby")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";     
        
        svgDoc_back.getElementById("size_list_right_align").innerHTML = "";
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        svgDoc_back.getElementById("size_list_right_align").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 1M")
    {

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        svgDoc_back.getElementById("size_list_right_align").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 3M")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        svgDoc_back.getElementById("size_list_right_align").innerHTML = "";

    } 
    else if(val.ce_size_name == "One size")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_onesize[0].slovak+" • "+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        svgDoc_back.getElementById("size_list_right_align").innerHTML = "";

    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
        svgDoc_back.getElementById("size_list_translation").innerHTML = "";


        if(val.ce_size_name.toString().slice(-1) == "M")
        {
            if(val.ce_size_name == "0-3M")
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_under[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_under[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "M")
            {
                var size_list_right_align1 = "";
                var size_list_right_align2 = "";
            }
            else
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_over[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_over[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_month_over[0].hungarian+'</tspan>';
            }
        }
        else if(val.ce_size_name.toString().slice(-1) == "Y")
        {
            if(val.ce_size_name == "1- 1½Y")
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_und[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_und[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_und[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "1-2Y")
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "2-3Y")
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "3-4Y")
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else
            {
                var size_list_right_align = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years</tspan><tspan x="16.76" y="0" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:8.25;">	</tspan><tspan x="26.3" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_over[0].czech+' </tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_over[0].slovak+'</tspan><tspan x="22.19" y="5" style="font-family:FandFSans-Regular; font-size:5px; letter-spacing:2.06;">	</tspan><tspan x="26.3" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+ val.tesco_year_over[0].hungarian+'</tspan>';
            }
        }
        else
        {
            var size_list_right_align1 = "";
            var size_list_right_align2 = "";
        }

        svgDoc_back.getElementById("size_list_right_align").innerHTML = size_list_right_align;
    }

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketseanhoriz(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13114COpt1(data,svgDoc_back,val)
{
    if(val.is_lycra == true)
    {
        svgDoc_back.getElementById("lycra_text").removeAttribute("visibility", "hidden");
    }
    else
    {
        svgDoc_back.getElementById("lycra_text").setAttribute('visibility','hidden');
    }
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 1/g, "")+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')


    var product_desc_text = JSON.parse(val.product_desc_text)
    var product_desc_translation ="";
    var product_desc_eng ="";
    $(product_desc_text).each(function (keys, vals) {

        product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        product_desc_eng = vals.english;
        
    })
    svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;


    if(val.ce_size_name == "tiny baby")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";     
        
        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.8413)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.8413)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }
        
    }
    else if(val.ce_size_name == "up to 1M")
    {

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.8413)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }
        
    }
    else if(val.ce_size_name == "up to 3M")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.8413)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }

    } 
    else if(val.ce_size_name == "One size")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_onesize[0].slovak+" • "+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.8413)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }

    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
        svgDoc_back.getElementById("size_list_translation").innerHTML = "";

        if(val.tesco_secondary_size_chart.length == 2)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 52)");
        }
        else if(val.tesco_secondary_size_chart.length == 3)
        {
            svgDoc_back.getElementById("size_category_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.8413)");
        }
    }


    var sec_size = "";
    var sec_size_y =0;
    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
         sec_size += '<tspan x="0" y="'+sec_size_y+'" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.secondary_size+'</tspan>';
         sec_size_y+=6;
    });

    svgDoc_back.getElementById("size_category_translation").innerHTML = sec_size;

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketsean(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13020C(data,svgDoc_back,val)
{
    if(val.is_lycra == true)
    {
        svgDoc_back.getElementById("lycra_text").removeAttribute("visibility", "hidden");
    }
    else
    {
        svgDoc_back.getElementById("lycra_text").setAttribute('visibility','hidden');
    }
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 1/g, "")+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')


    // var product_desc_text = JSON.parse(val.product_desc_text)
    // var product_desc_translation ="";
    // var product_desc_eng ="";
    // $(product_desc_text).each(function (keys, vals) {

    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="5" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="10" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

    //     product_desc_eng = vals.english;
        
    // })
    // svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    // svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;


    if(val.ce_size_name == "tiny baby")
    {
        svgDoc_back.getElementById("size_list").innerHTML = "";

        var size_category_translation = "";

        var size_comp = [];

        $(val.tesco_secondary_size_chart).each(function (keys, vals) {
            var sec_size = vals.secondary_size.split(':')
            if(keys !=0 )
            {
                size_category_translation += " " +vals.english+" "
            }
            else
            {
                size_category_translation += vals.english+" "
            }
            size_category_translation += toTitleCase(vals.czech)+"•"
            size_category_translation += toTitleCase(vals.slovak)+"•"
            size_category_translation += toTitleCase(vals.hungarian)+": "
            size_category_translation += sec_size[1].replaceAll(/\s/g,'')

            size_comp.push(sec_size[1].replaceAll(/\s/g,''))
            
        })
        
        $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);

        if(val.tesco_secondary_size_chart.length > 0)
        {
            var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

            svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
        }
        else
        {
            svgDoc_back.getElementById("size_bold").innerHTML = "";
        }

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;         

        if($size_category_str.length <= 3)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 38)");
        }
        else if($size_category_str.length == 4)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
        else if($size_category_str.length == 5)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");

        }
    
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        svgDoc_back.getElementById("size_list").innerHTML = "";

        var size_category_translation = "";

        var size_comp = [];

        $(val.tesco_secondary_size_chart).each(function (keys, vals) {
            var sec_size = vals.secondary_size.split(':')
            if(keys !=0 )
            {
                size_category_translation += " " +vals.english+" "
            }
            else
            {
                size_category_translation += vals.english+" "
            }
            size_category_translation += toTitleCase(vals.czech)+"•"
            size_category_translation += toTitleCase(vals.slovak)+"•"
            size_category_translation += toTitleCase(vals.hungarian)+": "
            size_category_translation += sec_size[1].replaceAll(/\s/g,'')
            size_comp.push(sec_size[1].replaceAll(/\s/g,''))
            
        })
        
        $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
        
        if(val.tesco_secondary_size_chart.length > 0)
        {
            var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

            svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
        }
        else
        {
            svgDoc_back.getElementById("size_bold").innerHTML = "";
        }

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        

        if($size_category_str.length <= 3)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 38)");
        }
        else if($size_category_str.length == 4)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
        else if($size_category_str.length == 5)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
    }
    else if(val.ce_size_name == "up to 1M")
    {
        svgDoc_back.getElementById("size_list").innerHTML = "";

        var size_category_translation = "";

        var size_comp = [];

        $(val.tesco_secondary_size_chart).each(function (keys, vals) {
            var sec_size = vals.secondary_size.split(':')
            if(keys !=0 )
            {
                size_category_translation += " " +vals.english+" "
            }
            else
            {
                size_category_translation += vals.english+" "
            }
            size_category_translation += toTitleCase(vals.czech)+"•"
            size_category_translation += toTitleCase(vals.slovak)+"•"
            size_category_translation += toTitleCase(vals.hungarian)+": "
            size_category_translation += sec_size[1].replaceAll(/\s/g,'')
            size_comp.push(sec_size[1].replaceAll(/\s/g,''))
            
        })
        
        $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
        
        if(val.tesco_secondary_size_chart.length > 0)
        {
            var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

            svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
        }
        else
        {
            svgDoc_back.getElementById("size_bold").innerHTML = "";
        }

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        
        if($size_category_str.length <= 3)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 38)");
        }
        else if($size_category_str.length == 4)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
        else if($size_category_str.length == 5)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
    }
    else if(val.ce_size_name == "up to 3M")
    {
        svgDoc_back.getElementById("size_list").innerHTML = "";

        var size_category_translation = "";

        var size_comp = [];

        $(val.tesco_secondary_size_chart).each(function (keys, vals) {
            var sec_size = vals.secondary_size.split(':')
            if(keys !=0 )
            {
                size_category_translation += " " +vals.english+" "
            }
            else
            {
                size_category_translation += vals.english+" "
            }
            size_category_translation += toTitleCase(vals.czech)+"•"
            size_category_translation += toTitleCase(vals.slovak)+"•"
            size_category_translation += toTitleCase(vals.hungarian)+": "
            size_category_translation += sec_size[1].replaceAll(/\s/g,'')
            size_comp.push(sec_size[1].replaceAll(/\s/g,''))
            
        })
        
        $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
        

        if(val.tesco_secondary_size_chart.length > 0)
        {
            var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

            svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
        }
        else
        {
            svgDoc_back.getElementById("size_bold").innerHTML = "";
        }

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  


        if($size_category_str.length <= 3)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 38)");
        }
        else if($size_category_str.length == 4)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
        else if($size_category_str.length == 5)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 54)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 35)");
        }
    } 
    else if(val.ce_size_name == "One size")
    {
        svgDoc_back.getElementById("size_list").innerHTML = "";

        $size_category_str = "null";

        var size_comp = [];


        svgDoc_back.getElementById("size_bold").innerHTML = ""

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6.5px;">'+data.tesco_onesize[0].english+'</tspan><tspan x="0" y="8" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="14" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].slovak+'</tspan><tspan x="0" y="20" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 40)");
    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

        var size_category_translation = "";

        var size_comp = [];

        $(val.tesco_secondary_size_chart).each(function (keys, vals) {
            var sec_size = vals.secondary_size.split(':')
            if(keys !=0 )
            {
                size_category_translation += " " +vals.english+" "
            }
            else
            {
                size_category_translation += vals.english+" "
            }
            size_category_translation += toTitleCase(vals.czech)+"•"
            size_category_translation += toTitleCase(vals.slovak)+"•"
            size_category_translation += toTitleCase(vals.hungarian)+": "
            size_category_translation += sec_size[1].replaceAll(/\s/g,'')
            size_comp.push(sec_size[1].replaceAll(/\s/g,''))
            
        })
        
        $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
        


        if(val.tesco_secondary_size_chart.length > 0)
        {
            var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

            svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
        }
        else
        {
            svgDoc_back.getElementById("size_bold").innerHTML = "";
        }


        if(val.ce_size_name.toString().slice(-1) == "M")
        {
            if(val.ce_size_name == "0-3M")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_under[0].czech+" • "+ val.tesco_month_under[0].slovak+" • "+ val.tesco_month_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "M")
            {
                var size_list_translation = "";
            }
            else
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_over[0].czech+" • "+ val.tesco_month_over[0].slovak+" • "+ val.tesco_month_over[0].hungarian+'</tspan>';
            }
        }
        else if(val.ce_size_name.toString().slice(-1) == "Y")
        {
            if(val.ce_size_name == "1- 1½Y")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_und[0].czech+" • "+ val.tesco_year_und[0].slovak+" • "+ val.tesco_year_und[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "1-2Y")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "2-3Y")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else if(val.ce_size_name == "3-4Y")
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
            }
            else
            {
                var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_over[0].czech+" • "+ val.tesco_year_over[0].slovak+" • "+ val.tesco_year_over[0].hungarian+'</tspan>';
            }
        }
        else
        {
            var size_list_translation = "";
        }
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  


        if($size_category_str.length <= 3)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48)");
        }
        else if($size_category_str.length == 4)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 57)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 45)");

            svgDoc_back.getElementById("size_list").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 40)");
        }
        else if($size_category_str.length == 5)
        {
            svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 57)");

            svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46)");
        }
    }


    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    //$size_category_str = null;

    var product_desc_text = val.product_desc_text;

    //var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketseansmall(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13163C(data,svgDoc_back,val)
{
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name+' - '+month_ca+' • M2 • '+val.season_code+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.uk_style_ref+' • '+optionID+'</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.twenty_four_char_desc+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')

    var country_of_origin_text = JSON.parse(val.country_of_origin_text);

    var english_coo = country_of_origin_text[0].english;
    var czech_coo = country_of_origin_text[0].czech;
    var slovak_coo = country_of_origin_text[0].slovak;
    var hungarian_coo = country_of_origin_text[0].hungarian;

    var country_of_origin = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+english_coo+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+czech_coo+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:4.5px;">'+slovak_coo+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+hungarian_coo+'</tspan>'

    svgDoc_back.getElementById("made_in").innerHTML = country_of_origin;


    var product_desc_text = JSON.parse(val.product_desc_text)
    var product_desc_translation ="";
    var product_desc_eng ="";
    $(product_desc_text).each(function (keys, vals) {

        product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        product_desc_eng = vals.english;
        
    })
    svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;


    if(val.ce_size_name == "tiny baby")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";       
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 1M")
    {

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 3M")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

    } 
    else if(val.ce_size_name == "One size")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_onesize[0].slovak+" • "+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
        svgDoc_back.getElementById("size_list_translation").innerHTML = "";
    }

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketseanhoriz(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13022C(data,svgDoc_back,val)
{
    if(val.is_lycra == true)
    {
        svgDoc_back.getElementById("lycra_text").removeAttribute("visibility", "hidden");
    }
    else
    {
        svgDoc_back.getElementById("lycra_text").setAttribute('visibility','hidden');
    }
    var created_at = new Date(val.created_at);

    var shortYear = created_at.getFullYear(); 
    // Add this line
    var twoDigitYear = shortYear.toString().substr(-2);

    var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

    var optionID = addOptionIdHyphen(val.ce_style_ref);

    var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name+' - '+month_ca+' • M2 • '+val.season_code+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.uk_style_ref+' • '+optionID+'</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.twenty_four_char_desc+'</tspan>';

    svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')

    var country_of_origin_text = JSON.parse(val.country_of_origin_text);

    var english_coo = country_of_origin_text[0].english;
    var czech_coo = country_of_origin_text[0].czech;
    var slovak_coo = country_of_origin_text[0].slovak;
    var hungarian_coo = country_of_origin_text[0].hungarian;

    var country_of_origin = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+english_coo+'</tspan><tspan x="0" y="5.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+czech_coo+'</tspan><tspan x="0" y="11" style="font-family:FandFSans-Regular; font-size:4.5px;">'+slovak_coo+'</tspan><tspan x="0" y="16.5" style="font-family:FandFSans-Regular; font-size:4.5px;">'+hungarian_coo+'</tspan>'

    svgDoc_back.getElementById("made_in").innerHTML = country_of_origin;


    var product_desc_text = JSON.parse(val.product_desc_text)
    var product_desc_translation ="";
    var product_desc_eng ="";
    $(product_desc_text).each(function (keys, vals) {

        product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.czech+'</tspan><tspan x="0" y="6" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.slovak+'</tspan><tspan x="0" y="12" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.hungarian+'</tspan>';

        product_desc_eng = vals.english;
        
    })
    svgDoc_back.getElementById("prod_english").innerHTML = product_desc_eng;
    svgDoc_back.getElementById("prod_trans").innerHTML = product_desc_translation;


    if(val.ce_size_name == "tiny baby")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";       
    }
    else if(val.ce_size_name.toLowerCase() == "newborn")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 1M")
    {

        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";
        
    }
    else if(val.ce_size_name == "up to 3M")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

    } 
    else if(val.ce_size_name == "One size")
    {
        var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].english +" • "+ data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_onesize[0].slovak+" • "+data.tesco_onesize[0].hungarian+'</tspan>';
        
        svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
        svgDoc_back.getElementById("size_list").innerHTML = "";

    }
    else
    {
        svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;
        svgDoc_back.getElementById("size_list_translation").innerHTML = "";
    }

    //Back Svg Price Details
    var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
    var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
    var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
    var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

    svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
    if(sl_price2  != "")
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
    }
    else
    {
        svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
    }
    svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

    $size_category_str = null;

    var product_desc_text = val.product_desc_text;

    var size_comp = null;


    var barcode_change = val.ean_barcode;

    jsbarcodetescoticketseanhoriz(barcode_change, 'barcode', svgDoc_back);

    return [
        size_comp,
        product_desc_text
       ];
}

function M13114COpt4(data,svgDoc_back,val)
{

                // svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                // svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;

                var created_at = new Date(val.created_at);

                var shortYear = created_at.getFullYear(); 
                // Add this line
                var twoDigitYear = shortYear.toString().substr(-2);

                var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

                var optionID = addOptionIdHyphen(val.ce_style_ref);

                var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 4/g, "")+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

                svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')



                if(val.ce_size_name == "tiny baby")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')

                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;

                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+" • "+ data.tesco_tiny[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;         
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");           
                    

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                    
                }
                else if(val.ce_size_name.toLowerCase() == "newborn")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+" • "+ data.tesco_nb[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 1M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+" • "+ data.tesco_up1[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 3M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+" • "+ data.tesco_up3[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                } 
                else if(val.ce_size_name == "One size")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = "";

                    $size_category_str = "null";

                    var size_comp = [];

                    //svgDoc_back.getElementById("size_category_translation").innerHTML = "";

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;

                    svgDoc_back.getElementById("size_bold").innerHTML = ""


                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 78)");


                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6.5px;">'+data.tesco_onesize[0].english+'</tspan><tspan x="0" y="8" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="14" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].slovak+'</tspan><tspan x="0" y="20" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 40)");
                }
                else
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }


                    if(val.ce_size_name.toString().slice(-1) == "M")
                    {
                        if(val.ce_size_name == "0-3M")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_under[0].czech+" • "+ val.tesco_month_under[0].slovak+" • "+ val.tesco_month_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "M")
                        {
                            var size_list_translation = "";
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_over[0].czech+" • "+ val.tesco_month_over[0].slovak+" • "+ val.tesco_month_over[0].hungarian+'</tspan>';
                        }
                    }
                    else if(val.ce_size_name.toString().slice(-1) == "Y")
                    {
                        if(val.ce_size_name == "1- 1½Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_und[0].czech+" • "+ val.tesco_year_und[0].slovak+" • "+ val.tesco_year_und[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "1-2Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "2-3Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "3-4Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_over[0].czech+" • "+ val.tesco_year_over[0].slovak+" • "+ val.tesco_year_over[0].hungarian+'</tspan>';
                        }
                    }
                    else
                    {
                        var size_list_translation = "";
                    }
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.013)");
                    }
                }

                //Back Svg Price Details
                var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
                var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
                var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
                var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

                svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
                if(sl_price2  != "")
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
                }
                else
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
                }
                svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

                var barcode_change = val.ean_barcode;

                jsbarcodetescoticketsean(barcode_change, 'barcode', svgDoc_back);


                return [
                    size_comp,
                    product_desc_text
                   ];
}

function M13114COpt2(data,svgDoc_back,val)
{

                // svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                // svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;

                var created_at = new Date(val.created_at);

                var shortYear = created_at.getFullYear(); 
                // Add this line
                var twoDigitYear = shortYear.toString().substr(-2);

                var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

                var optionID = addOptionIdHyphen(val.ce_style_ref);

                var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 2/g, "")+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

                svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')



                if(val.ce_size_name == "tiny baby")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')

                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;

                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+" • "+ data.tesco_tiny[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;         
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");           
                    

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                    
                }
                else if(val.ce_size_name.toLowerCase() == "newborn")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+" • "+ data.tesco_nb[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 1M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+" • "+ data.tesco_up1[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 3M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+" • "+ data.tesco_up3[0].slovak+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                } 
                else if(val.ce_size_name == "One size")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = "";

                    $size_category_str = "null";

                    var size_comp = [];

                    //svgDoc_back.getElementById("size_category_translation").innerHTML = "";

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;

                    svgDoc_back.getElementById("size_bold").innerHTML = ""


                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 78)");


                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6.5px;">'+data.tesco_onesize[0].english+'</tspan><tspan x="0" y="8" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="14" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].slovak+'</tspan><tspan x="0" y="20" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 40)");
                }
                else
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }


                    if(val.ce_size_name.toString().slice(-1) == "M")
                    {
                        if(val.ce_size_name == "0-3M")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_under[0].czech+" • "+ val.tesco_month_under[0].slovak+" • "+ val.tesco_month_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "M")
                        {
                            var size_list_translation = "";
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_over[0].czech+" • "+ val.tesco_month_over[0].slovak+" • "+ val.tesco_month_over[0].hungarian+'</tspan>';
                        }
                    }
                    else if(val.ce_size_name.toString().slice(-1) == "Y")
                    {
                        if(val.ce_size_name == "1- 1½Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_und[0].czech+" • "+ val.tesco_year_und[0].slovak+" • "+ val.tesco_year_und[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "1-2Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "2-3Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "3-4Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_over[0].czech+" • "+ val.tesco_year_over[0].slovak+" • "+ val.tesco_year_over[0].hungarian+'</tspan>';
                        }
                    }
                    else
                    {
                        var size_list_translation = "";
                    }
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.013)");
                    }
                }

                //Back Svg Price Details
                var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
                var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
                var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
                var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

                svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
                if(sl_price2  != "")
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
                }
                else
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
                }
                svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

                var barcode_change = val.ean_barcode;

                jsbarcodetescoticketsean(barcode_change, 'barcode', svgDoc_back);


                return [
                    size_comp,
                    product_desc_text
                   ];
}

function M13021C(data,svgDoc_back,val)
{

                // svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                // svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;

                var created_at = new Date(val.created_at);

                var shortYear = created_at.getFullYear(); 
                // Add this line
                var twoDigitYear = shortYear.toString().substr(-2);

                var month_ca = ( '0' + (created_at.getMonth()+1) ).slice( -2 ) +"/"+ twoDigitYear;

                var optionID = addOptionIdHyphen(val.ce_style_ref);

                var short_description = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.twenty_four_char_desc+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:4.5px;">'+val.item_name.replace(/ - Opt 2/g, "")+' - '+month_ca+' • M2</tspan><tspan x="0" y="10.8" style="font-family:FandFSans-Regular; font-size:4.5px;">• '+val.eqos_site_code+' • '+val.season_code+' • '+optionID+'</tspan>';

                svgDoc_back.getElementById("short_description").innerHTML = short_description.replace('&', '&amp;')



                if(val.ce_size_name == "tiny baby")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')

                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_tiny[0].english +" • "+ data.tesco_tiny[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_tiny[0].slovak+" • "+data.tesco_tiny[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;         
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");           
                    

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                    
                }
                else if(val.ce_size_name.toLowerCase() == "newborn")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_nb[0].english +" • "+ data.tesco_nb[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_nb[0].slovak+" • "+data.tesco_nb[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 1M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up1[0].english +" • "+ data.tesco_up1[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up1[0].slovak+" • "+data.tesco_up1[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  
                    
                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                }
                else if(val.ce_size_name == "up to 3M")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }

                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_up3[0].english +" • "+ data.tesco_up3[0].czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+data.tesco_up3[0].slovak+" • "+data.tesco_up3[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 48.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 60.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 46.013)");
                    }
                } 
                else if(val.ce_size_name == "One size")
                {
                    svgDoc_back.getElementById("size_list").innerHTML = "";

                    $size_category_str = "null";

                    var size_comp = [];

                    //svgDoc_back.getElementById("size_category_translation").innerHTML = "";

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;

                    svgDoc_back.getElementById("size_bold").innerHTML = ""


                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 78)");


                    var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Bold;font-weight:700; font-size:6.5px;">'+data.tesco_onesize[0].english+'</tspan><tspan x="0" y="8" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].czech+'</tspan><tspan x="0" y="14" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].slovak+'</tspan><tspan x="0" y="20" style="font-family:FandFSans-Regular; font-size:5px;">'+data.tesco_onesize[0].hungarian+'</tspan>';
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 40)");
                }
                else
                {
                    svgDoc_back.getElementById("size_list").innerHTML = val.ce_size_name;

                    var size_category_translation = "";

                    var size_comp = [];

                    $(val.tesco_secondary_size_chart).each(function (keys, vals) {
                        var sec_size = vals.secondary_size.split(':')
                        if(keys !=0 )
                        {
                            size_category_translation += " " +vals.english+" "
                        }
                        else
                        {
                            size_category_translation += vals.english+" "
                        }
                        size_category_translation += toTitleCase(vals.czech)+"•"
                        size_category_translation += toTitleCase(vals.slovak)+"•"
                        size_category_translation += toTitleCase(vals.hungarian)+": "
                        size_category_translation += sec_size[1].replaceAll(/\s/g,'')
                        size_comp.push(sec_size[1].replaceAll(/\s/g,''))
                        
                    })
                    
                    $size_category_str = chunkSubstr(size_category_translation, 42).split(/\r?\n/);
                    // $y_size = 0;
                    // var size_category_str_text = "";
                    // $($size_category_str).each(function(index,size_category_str_text_val){
                    //     size_category_str_text += '<tspan x="0" y="'+$y_size+'" style="font-family:FandFSans-Regular; font-size:5px;">'+size_category_str_text_val+'</tspan>';
                    //     $y_size += 5.76;
                    // })
                    // console.log(size_category_str_text)
                    // svgDoc_back.getElementById("size_category_translation").innerHTML = size_category_str_text;

                    //Product Description
                    var product_desc_translation = "";
                    var product_desc_text = val.product_desc_text
                    // $(product_desc_text).each(function (keys, vals) {

                    //     product_desc_translation += '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">'+vals.english+" • "+vals.czech+'</tspan><tspan x="0" y="5.4" style="font-family:FandFSans-Regular; font-size:5px;">• '+vals.slovak+" • "+vals.hungarian+'</tspan>';
                        
                    // })
                    
                    // svgDoc_back.getElementById("product_description").innerHTML = product_desc_translation;


                    if(val.tesco_secondary_size_chart.length > 0)
                    {
                        var size_bold = val.tesco_secondary_size_chart[0].secondary_size.split(':')

                        svgDoc_back.getElementById("size_bold").innerHTML = size_bold[1].trim();
                    }
                    else
                    {
                        svgDoc_back.getElementById("size_bold").innerHTML = "";
                    }


                    if(val.ce_size_name.toString().slice(-1) == "M")
                    {
                        if(val.ce_size_name == "0-3M")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_under[0].czech+" • "+ val.tesco_month_under[0].slovak+" • "+ val.tesco_month_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "M")
                        {
                            var size_list_translation = "";
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">months • '+ val.tesco_month_over[0].czech+" • "+ val.tesco_month_over[0].slovak+" • "+ val.tesco_month_over[0].hungarian+'</tspan>';
                        }
                    }
                    else if(val.ce_size_name.toString().slice(-1) == "Y")
                    {
                        if(val.ce_size_name == "1- 1½Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_und[0].czech+" • "+ val.tesco_year_und[0].slovak+" • "+ val.tesco_year_und[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "1-2Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "2-3Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else if(val.ce_size_name == "3-4Y")
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_under[0].czech+" • "+ val.tesco_year_under[0].slovak+" • "+ val.tesco_year_under[0].hungarian+'</tspan>';
                        }
                        else
                        {
                            var size_list_translation = '<tspan x="0" y="0" style="font-family:FandFSans-Regular; font-size:5px;">years • '+ val.tesco_year_over[0].czech+" • "+ val.tesco_year_over[0].slovak+" • "+ val.tesco_year_over[0].hungarian+'</tspan>';
                        }
                    }
                    else
                    {
                        var size_list_translation = "";
                    }
                    
                    svgDoc_back.getElementById("size_list_translation").innerHTML = size_list_translation;  

                    svgDoc_back.getElementById("product_description").setAttribute('transform', "matrix(1, 0, 0, 1.1111, 58.7577, 94.1046)");
                    //svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");

                    if($size_category_str.length <= 3)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 51.013)");
                    }
                    else if($size_category_str.length == 4)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 64)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 50.013)");
                    }
                    else if($size_category_str.length == 5)
                    {
                        svgDoc_back.getElementById("size_bold").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 58.7789)");

                        svgDoc_back.getElementById("size_list_translation").setAttribute('transform', "matrix(1, 0, 0, 1, 58, 47.013)");
                    }
                }

                //Back Svg Price Details
                var sl_price2 = parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)) != 0?parseInt(data.order_details[0].sk_selling_price.toString().slice(-2)):'';
                var sl_price1 = data.order_details[0].sk_selling_price.toString().slice(0,-2).replace('.','');
                var hu_price = data.order_details[0].hu_selling_price.toString().length == 5?data.order_details[0].hu_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].hu_selling_price;
                var cz_price = data.order_details[0].cz_selling_price.toString().length == 4?data.order_details[0].cz_selling_price.toString().replace(/.{3}$/,' $&'):data.order_details[0].cz_selling_price;

                svgDoc_back.getElementById("cz_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+cz_price+' Kč</tspan>';
                if(sl_price2  != "")
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+',</tspan><tspan style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:4.664px;" y="-2.7">'+sl_price2+'</tspan><tspan y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;"> €</tspan>';
                }
                else
                {
                    svgDoc_back.getElementById("sl_price1").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+sl_price1+' €</tspan>';
                }
                svgDoc_back.getElementById("hu_price").innerHTML = '<tspan x="0" y="0" style="fill:#8A8B92; font-family:FandFSans-Regular; font-size:8px;">'+hu_price+' Ft</tspan>';

                var barcode_change = val.ean_barcode;

                jsbarcodetescoticketsean(barcode_change, 'barcode', svgDoc_back);


                return [
                    size_comp,
                    product_desc_text
                   ];
}

function jsbarcodetescoticketsean(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 286,
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
    svg.setAttribute('font-family', 'fandfsansb');
    svg.setAttribute('letter-spacing', '8px');

    svg.setAttribute('width', '83.93px');
    svg.setAttribute('height', '58.03px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    svg.getElementsByTagName('text')[2].setAttribute('letter-spacing', '9px')
    svg.getElementsByTagName('text')[4].setAttribute('letter-spacing', '12px')

    svg.getElementsByTagName('text')[0].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[2].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[4].setAttribute('font-family', 'FandFSans-Bold')


    svg.getElementsByTagName('text')[0].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[2].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[4].setAttribute('font-weight', '700')

    svg.getElementsByTagName('text')[0].setAttribute('x', '20')

    svg.getElementsByTagName('text')[0].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[2].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[4].setAttribute('style', 'font-size:55px')

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // img.src - contains base64 URI from svg
    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}

function jsbarcodetescoticketseanhoriz(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 236,
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
    svg.setAttribute('font-family', 'fandfsansb');
    svg.setAttribute('letter-spacing', '8px');

    svg.setAttribute('width', '76.93px');
    svg.setAttribute('height', '58.03px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    svg.getElementsByTagName('text')[2].setAttribute('letter-spacing', '9px')
    svg.getElementsByTagName('text')[4].setAttribute('letter-spacing', '12px')

    svg.getElementsByTagName('text')[0].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[2].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[4].setAttribute('font-family', 'FandFSans-Bold')


    svg.getElementsByTagName('text')[0].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[2].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[4].setAttribute('font-weight', '700')

    svg.getElementsByTagName('text')[0].setAttribute('x', '15')

    svg.getElementsByTagName('text')[0].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[2].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[4].setAttribute('style', 'font-size:55px')

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // img.src - contains base64 URI from svg
    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}

addOptionIdHyphen = (option_id) => {
    var opID = option_id.replace("-","");
    if(option_id.toString().length > 3){
        opID = opID.substring(0,3) + "-" + opID.substring(3);
    }
    return opID;
}

function toTitleCase(str) {
	return str[0].toUpperCase() + str.slice(1)
}



function jsbarcodetescoticketseansmall(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 286,
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
    svg.setAttribute('font-family', 'fandfsansb');
    svg.setAttribute('letter-spacing', '8px');

    svg.setAttribute('width', '79px');
    svg.setAttribute('height', '54px');

    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    svg.getElementsByTagName('text')[2].setAttribute('letter-spacing', '9px')
    svg.getElementsByTagName('text')[4].setAttribute('letter-spacing', '12px')

    svg.getElementsByTagName('text')[0].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[2].setAttribute('font-family', 'FandFSans-Bold')
    svg.getElementsByTagName('text')[4].setAttribute('font-family', 'FandFSans-Bold')


    svg.getElementsByTagName('text')[0].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[2].setAttribute('font-weight', '700')
    svg.getElementsByTagName('text')[4].setAttribute('font-weight', '700')

    svg.getElementsByTagName('text')[0].setAttribute('x', '20')

    svg.getElementsByTagName('text')[0].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[2].setAttribute('style', 'font-size:55px')
    svg.getElementsByTagName('text')[4].setAttribute('style', 'font-size:55px')

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // img.src - contains base64 URI from svg
    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}