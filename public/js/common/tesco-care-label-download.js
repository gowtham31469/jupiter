function downloadArtworkLabels(data, name,style_no,buyer_no,id,order_type,obj,art_temp_id,table_name_print,art_type,buyer_type,name2,order_page,i,isEdit)
{
    var a = document.getElementById(data);
    var svgDoc = a.contentDocument;

    if(buyer_type == "admin")
    {
        var buyer_url = base_url + "/admin/tesco/get-order-details";
    }
    else if(buyer_type == "customer")
    {
        console.log(data)
        svgDoc.getElementById("watermark").innerHTML = "JGA";
        var buyer_url = base_url + "/customer/tesco/get-order-details";
    }
    else if(buyer_type == "erp")
    {
        var buyer_url =  base_url + "/erp/job-manager/get-order-details";
    }
    
    if(order_page == "order_status")
    {
        var a_back = document.getElementById(name2+"_"+art_temp_id+"_back_modal");
        var svgDoc_back = a_back.contentDocument;
    
        var a_back_text = document.getElementById(name2+"_"+art_temp_id+"_back_text_modal");
        var svgDoc_back_text = a_back_text.contentDocument;
    
        var a_front_text = document.getElementById(name2+"_"+art_temp_id+"_front_modal");
        var svgDoc_front_text = a_front_text.contentDocument;
    }
    else if(order_page == "print_manager")
    {
        var a_back = document.getElementById('tesco_svg_back_modal'+i);
        var svgDoc_back = a_back.contentDocument;

        var a_back_text = document.getElementById('tesco_svg_back_text_modal'+i);
        var svgDoc_back_text = a_back_text.contentDocument;

        var a_front_text = document.getElementById('tesco_svg_front_modal'+i);
        var svgDoc_front_text = a_front_text.contentDocument;
    }
    else if(order_page == "job_manager")
    {
        var a_back = document.getElementById('tesco_svg_back_modal'+i);
        var svgDoc_back = a_back.contentDocument;

        var a_back_text = document.getElementById('tesco_svg_back_text_modal'+i);
        var svgDoc_back_text = a_back_text.contentDocument;

        var a_front_text = document.getElementById('tesco_svg_front_modal'+i);
        var svgDoc_front_text = a_front_text.contentDocument;
    }else if(order_page == "job_card_manager")
    {
        var a_back = document.getElementById('tesco_svg_back_modal'+i);
        var svgDoc_back = a_back.contentDocument;

        var a_back_text = document.getElementById('tesco_svg_back_text_modal'+i);
        var svgDoc_back_text = a_back_text.contentDocument;

        var a_front_text = document.getElementById('tesco_svg_front_modal'+i);
        var svgDoc_front_text = a_front_text.contentDocument;
    }
    else
    {
        var a_back = document.getElementById('tesco_svg_back_modal');
        var svgDoc_back = a_back.contentDocument;

        var a_back_text = document.getElementById('tesco_svg_back_text_modal');
        var svgDoc_back_text = a_back_text.contentDocument;

        var a_front_text = document.getElementById('tesco_svg_front_modal');
        var svgDoc_front_text = a_front_text.contentDocument;
    }
    svgDoc.getElementById("reference_number").innerHTML = name2;
            
    var svgarr = [];
    var svgarr_back = [];
    var svgarr_back_text = [];
    var svgarr_front = [];
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
            ref_name: name2,
            style_no: style_no,
            buyer_no: buyer_no,
            order_type:order_type
        },
        beforeSend: function() {

        },
        success: function(data) {
            
            var svgRoot_back =svgDoc_back.documentElement;
            var svgRoot_back_text =svgDoc_back_text.documentElement;
            var svgRoot_front_text =svgDoc_front_text.documentElement;

            if(name2 != "GCLW25/M11364G" && name2 != "GCLW25/M11367G" && name2 != "GCLW25/M11375G" && name2 != "GCLW25/M11374G" && name2 != "GCLW25/M11372G")
            {
                if(data.order_details[0].leather_suede_code != "" && data.order_details[0].leather_suede_code != null)
                {
                    svgDoc.getElementById("leather_suede_code").innerHTML = data.order_details[0].leather_suede_code;
                }
                else
                {
                    svgDoc.getElementById("leather_suede_code").innerHTML = "";
                }
            }

            $(data.order_details).each(function (key, val) {
                svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;

                if(name2 == "GCLW25/M11364G")
                {
                        if(val.uk_ce_mark == "yes")
                        {
                            svgDoc_front_text.getElementById("uk_ce_mark").removeAttribute("visibility", "hidden");
                        }
                        else
                        {
                            svgDoc_front_text.getElementById("uk_ce_mark").setAttribute("visibility", "hidden");
                        }

                    if(val.flammability_id != null && val.flammability_id != "null" && val.flammability_id != "")
                    {
                        svgDoc.getElementById("low_flammable_1").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("low_flammable_2").removeAttribute("visibility", "hidden");
                    }
                    else
                    {
                        svgDoc.getElementById("low_flammable_1").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("low_flammable_2").setAttribute("visibility", "hidden");
                    }
                } else if(name2 == "GCLW20/M11473G")
                {
                    if(val.uk_ce_mark == "yes")
                    {
                        svgDoc_back.getElementById("uk_ce_mark").removeAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 244) scale(0.65 1)");

                    }
                    else
                    {
                        svgDoc_back.getElementById("uk_ce_mark").setAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 222.55) scale(0.65 1)");
                    }
                } 
                else if(name2 == "GCLW20/M11474G")
                {
                    if(val.uk_ce_mark == "yes")
                    {
                        svgDoc_back.getElementById("uk_ce_mark").removeAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 244) scale(0.65 1)");

                    }
                    else
                    {
                        svgDoc_back.getElementById("uk_ce_mark").setAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 222.55) scale(0.65 1)");
                    }
                }
                else if(name2 == "GCLW25/M11374G")
                {
                    if(val.uk_ce_mark == "yes")
                    {
                        svgDoc_front_text.getElementById("uk_ce_mark").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_1").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_2").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_3").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_4").removeAttribute("visibility", "hidden");
                    }
                    else
                    {
                        svgDoc_front_text.getElementById("uk_ce_mark").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_1").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_2").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_3").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("uk_ce_text_4").setAttribute("visibility", "hidden");
                    }
                } else if(name2 == "GCLW25/M11372G")
                {
                    if(val.uk_ce_mark == "yes")
                    {
                        svgDoc_back.getElementById("uk_ce_mark").removeAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 242) scale(0.65 1)");

                    }
                    else
                    {
                        svgDoc_back.getElementById("uk_ce_mark").setAttribute("visibility", "hidden");

                        svgDoc_back_text.getElementById("wash_text_2").setAttribute('transform', "translate(5.5 222.55) scale(0.65 1)");
                    }

                    if(val.flammability_id != null && val.flammability_id != "null" && val.flammability_id != "")
                    {
                        svgDoc.getElementById("low_flammable_1").removeAttribute("visibility", "hidden");
                        svgDoc.getElementById("low_flammable_2").removeAttribute("visibility", "hidden");
                    }
                    else
                    {
                        svgDoc.getElementById("low_flammable_1").setAttribute("visibility", "hidden");
                        svgDoc.getElementById("low_flammable_2").setAttribute("visibility", "hidden");
                    }
                }

                if(name2 != "GCLW25/M11364G" && name2 != "GCLW25/M11367G" && name2 != "GCLW25/M11375G" && name2 != "GCLW25/M11374G" && name2 != "GCLW25/M11372G" && name2 != "GCLW20/M11473G" && name2 != "GCLW20/M11474G")
                {

                    svgDoc.getElementById("woolmark_batch_code").innerHTML = val.woolmark_batch_code;

                    if(val.woolmark_logo_id == null || val.woolmark_logo_id == "" || val.woolmark_logo_id == "null")
                    {
                        svgDoc_front_text.getElementById("woolmark_logo_text").setAttribute("visibility", "hidden");
                    }
                    else
                    {
                        svgDoc_front_text.getElementById("woolmark_logo_text").removeAttribute("visibility", "hidden");
                    }
                }
                
                if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                {
                    svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                }
                else
                {
                    if(name2 == "GCLW25/M11364G" || name2 == "GCLW25/M11372G")
                    {
                        svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 121.2519)");
                        svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 126.2514)");
                        svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 131.2509)");
                    }
                    else if(name2 == "GCLW25/M11363G" || name2 == "GCLW25/M11368G" || name2 == "GCLB25/M11410G")
                    {
                        svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 124.1455)");
                        svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 128.6455)");
                        svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 133.1455)");
                    }
                    else if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                    {
                        svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 131.8876)");
                        svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 136.8871)");
                        svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 141.8866)");
                    }
                    else if(name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
                    {
                        svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1, 0, 0, 1, 28, 124.8013)");
                        svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1, 0, 0, 1, 28, 129.8008)");
                        svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1, 0, 0, 1, 28, 134.8004)");
                    }
                    else if(name2 == "GCLW25/M11374G")
                    {
                        svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.59858, 128.8781)");
                        svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 133.3781)");
                        svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1, 0, 0, 1, 35.5985, 137.8781)");
                    }
                    
                    svgDoc.getElementById("batch_code").innerHTML = "";

                }

                if(val.internal_reference != "" && val.internal_reference != null)
                {
                    svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;

                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                    {

                        if(val.bs_en_declaration == 0)
                        {
                            
                            svgDoc.getElementById("bs_en").setAttribute("visibility", "hidden");
                        }
                        else
                        {
                            svgDoc.getElementById("bs_en").removeAttribute("visibility", "hidden");
                        }

                        if(val.baby_sleeping_bag_symbol == 0)
                        {
                            svgDoc_front_text.getElementById("tog_image").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("tog_value_1_").setAttribute("visibility", "hidden");
                            if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                            {
                                    svgDoc.getElementById("baby_text_1").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_2").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_3").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_4").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_5").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_6").setAttribute("visibility", "hidden");
                            }
                        }
                        else
                        {
                            svgDoc_front_text.getElementById("tog_image").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("tog_value_1_").removeAttribute("visibility", "hidden");
                            if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                            {
                                svgDoc.getElementById("baby_text_1").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_2").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_3").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_4").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_5").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_6").removeAttribute("visibility", "hidden");
                            }
                        }
                    }

                    if(name2 == "GCLW25/M11363G" || name2 == "GCLW25/M11378G" || name2 == "GCLB25/M11410G")
                    {
                        svgDoc.getElementById("reference_number").setAttribute('transform', "matrix(1 0 0 1 6.5 159.6377)");
                        svgDoc.getElementById("packaging_reference_number").setAttribute('transform', "matrix(1 0 0 1 6.5 164.1377)");
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1 0 0 1 6.5 168.6377)");
                    }
                    else if(name2 == "GCLW25/M11364G" || name2 == "GCLW25/M11372G")
                    {
                        svgDoc.getElementById("reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6681 159.351)");
                        svgDoc.getElementById("packaging_reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6681 164.3505)");
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1 0 0 1 5.6681 169.35)");
                    }
                    else if(name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11367G")
                    {
                        svgDoc.getElementById("reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6714 169.9872)");
                        svgDoc.getElementById("packaging_reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6714 174.9867)");
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1 0 0 1 5.6714 179.9862)");
                    }
                    else if(name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
                    {
                        svgDoc.getElementById("reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6723 163)");
                        svgDoc.getElementById("packaging_reference_number").setAttribute('transform', "matrix(1 0 0 1 5.6723 169)");
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1 0 0 1 5.6723 175)");
                    }
                    else if(name2 == "GCLW25/M11374G")
                    {
                        svgDoc.getElementById("reference_number").setAttribute('transform', "matrix(1 0 0 1 6.5 171.4099)");
                        svgDoc.getElementById("packaging_reference_number").setAttribute('transform', "matrix(1 0 0 1 6.5 176.4094)");
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1 0 0 1 6.5 181.4089)");
                    }
                }
                else
                {
                    svgDoc.getElementById("packaging_reference_number").innerHTML = "";

                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                    {
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.5, 174.7462)");

                        if(val.bs_en_declaration == 0)
                        {
                            
                            svgDoc.getElementById("bs_en").setAttribute("visibility", "hidden");
                        }
                        else
                        {
                            svgDoc.getElementById("bs_en").removeAttribute("visibility", "hidden");
                        }

                        if(val.baby_sleeping_bag_symbol == 0)
                        {
                            svgDoc_front_text.getElementById("tog_image").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("tog_value_1_").setAttribute("visibility", "hidden");
                            if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                            {
                                    svgDoc.getElementById("baby_text_1").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_2").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_3").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_4").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_5").setAttribute("visibility", "hidden");
                                    svgDoc.getElementById("baby_text_6").setAttribute("visibility", "hidden");
                            }
                        }
                        else
                        {
                            svgDoc_front_text.getElementById("tog_image").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("tog_value_1_").removeAttribute("visibility", "hidden");
                            if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G")
                            {
                                svgDoc.getElementById("baby_text_1").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_2").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_3").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_4").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_5").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("baby_text_6").removeAttribute("visibility", "hidden");
                            }
                        }
                    }
                    else  if(name2 == "GCLW20/M11473G")
                    {
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.5, 166)");
                    }
                    else  if(name2 == "GCLW20/M11474G")
                    {
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.5, 166)");
                    }
                    else  if(name2 == "GCLW25/M11374G")
                    {
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.5, 177)");
                    }
                    else
                    {
                        svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.5, 164.7462)");
                    }
                    
                }
                
                if(val.tog != "" && val.tog != null)
                {
                    svgDoc.getElementById("tog_value_1_").innerHTML = "TOG "+val.tog_value;
                }
                else
                {
                    //console.log("hai")
                    svgDoc.getElementById("tog_value_1_").innerHTML = "";
                }


                var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                var selected_secondary_sizes_text = "";
                console.log(selected_secondary_sizes)
                $size_y=0;
                $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                    if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                    {
                        selected_secondary_sizes_text += '<tspan text-anchor="middle" x="10" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                        $size_y += 5;
                    }
                })
                svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                


                svgDoc.getElementById("uk_ean_barcode").innerHTML =  "UK EAN: " + val.uk_ean;
                svgDoc.getElementById("ce_ean_barcode").innerHTML =  "CE EAN: " + val.ce_ean;

                if(val.potps_id == null)
                {
                    svgDoc.getElementById("part_of_two_piece_set").setAttribute("visibility", "hidden");
                    // svgDoc.getElementById("uk_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 302.3271)");
                    // svgDoc.getElementById("ce_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 312.2111)");
                    // svgDoc.getElementById("top_line").setAttribute('y1', "294.16");
                    // svgDoc.getElementById("top_line").setAttribute('y2', "294.16");
                    // svgDoc.getElementById("bottom_line").setAttribute('y1', "315.63");
                    // svgDoc.getElementById("bottom_line").setAttribute('y2', "315.63");



                    if(name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
                    {
                        svgDoc_back_text.getElementById("wyprod_text").setAttribute('transform', "translate(27 13.5)");
                    }
                    else
                    {
                        svgDoc_back_text.getElementById("wyprod_text").setAttribute('transform', "translate(34 13.5)");
                    }
                    
                    
                }
                else
                {
                    svgDoc.getElementById("part_of_two_piece_set").removeAttribute("visibility", "hidden");
                    svgDoc.getElementById("part_of_two_piece_set").innerHTML = val.part_name;

                    if(name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
                    {
                        svgDoc_back_text.getElementById("wyprod_text").setAttribute('transform', "translate(27 6)");
                    }
                    else
                    {
                        svgDoc_back_text.getElementById("wyprod_text").setAttribute('transform', "translate(34 6)");
                    }

                }
                
                if(val.uk_size == "tiny baby")
                {

                    setVisibilityForSize(svgDoc);
                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","262","48.2","262","35","269")
                    }
                    else if(name2 == "GCLW25/M11372G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","253","48.2","253","35","260")
                    }
                    else if(name2 == "GCLW25/M11378G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","252","48.2","252","35","259")
                    }
                    else if(name2 == "GCLW20/M11473G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLW20/M11474G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLB25/M11410G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","250","48.2","250","35","257")
                    }
                    else
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","245","48.2","245","35","252")
                    }

                    var polish = "tiny baby"
                    var czech = data.tesco_tiny[0].czech
                    var slovak = data.tesco_tiny[0].slovak
                    var hungarian = data.tesco_tiny[0].hungarian

                    setTranslations(svgDoc,polish, czech, slovak, hungarian)
                }
                else if(val.uk_size.toLowerCase() == "newborn")
                {
                    setVisibilityForSize(svgDoc);
                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","262","48.2","262","35","269")
                    }
                    else if(name2 == "GCLW25/M11372G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","253","48.2","253","35","260")
                    }
                    else if(name2 == "GCLW25/M11378G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","252","48.2","252","35","259")
                    }
                    else if(name2 == "GCLW20/M11473G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLW20/M11474G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLB25/M11410G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","250","48.2","250","35","257")
                    }
                    else
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","245","48.2","245","35","252")
                    }

                    var polish = "newborn"
                    var czech = data.tesco_nb[0].czech
                    var slovak = data.tesco_nb[0].slovak
                    var hungarian = data.tesco_nb[0].hungarian

                    setTranslations(svgDoc,polish, czech, slovak, hungarian)
                }
                else if(val.uk_size == "up to 1M")
                {
                    setVisibilityForSize(svgDoc);
                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","262","48.2","262","35","269")
                    }
                    else if(name2 == "GCLW25/M11372G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","253","48.2","253","35","260")
                    }
                    else if(name2 == "GCLW25/M11378G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","252","48.2","252","35","259")
                    }
                    else if(name2 == "GCLW20/M11473G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLW20/M11474G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLB25/M11410G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","250","48.2","250","35","257")
                    }
                    else
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","245","48.2","245","35","252")
                    }

                    var polish = "up to 1m"
                    var czech = data.tesco_up1[0].czech
                    var slovak = data.tesco_up1[0].slovak
                    var hungarian = data.tesco_up1[0].hungarian

                    setTranslations(svgDoc,polish, czech, slovak, hungarian)
                }
                else if(val.uk_size == "up to 3M")
                {
                    setVisibilityForSize(svgDoc);
                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","262","48.2","262","35","269")
                    }
                    else if(name2 == "GCLW25/M11372G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","253","48.2","253","35","260")
                    }
                    else if(name2 == "GCLW25/M11378G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","252","48.2","252","35","259")
                    }
                    else if(name2 == "GCLW20/M11473G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLW20/M11474G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","255","38","255","28","262")
                    }
                    else if(name2 == "GCLB25/M11410G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","250","48.2","250","35","257")
                    }
                    else
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","245","48.2","245","35","252")
                    }

                    var polish = "up to 3m"
                    var czech = data.tesco_up3[0].czech
                    var slovak = data.tesco_up3[0].slovak
                    var hungarian = data.tesco_up3[0].hungarian

                    setTranslations(svgDoc,polish, czech, slovak, hungarian)
                }
                else if(val.uk_size == "One size")
                {
                    setVisibilityForSize(svgDoc);
                    
                    if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","21","272","46","272","35","280")
                    }
                    else if(name2 == "GCLW25/M11372G")
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","23.2","263","48.2","263","35","271")
                    }
                    else if(name2 == "GCLW20/M11473G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","262","38","262","28","269")
                    }
                    else if(name2 == "GCLW20/M11474G")
                    {
                        setTransformations(svgDoc,"0.75","0.70","17.5","262","38","262","28","269")
                    }
                    else
                    {
                        setTransformations(svgDoc,"1.0039","0.8105","21","257.061","46","257.061","35","264.061")
                    }
                    
                    
                    var polish = "one size"
                    var czech = data.tesco_onesize[0].czech
                    var slovak = data.tesco_onesize[0].slovak
                    var hungarian = data.tesco_onesize[0].hungarian

                    setTranslations(svgDoc,polish, czech, slovak, hungarian)
                }
                else
                {
                    if(val.uk_size == "3-6M" || val.uk_size == "6-9M" || val.uk_size == "9-12M")
                    {

                        svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                        svgDoc.getElementById("ce_size").innerHTML = val.ce_size;

                        if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                        {
                            setMYTransformations(svgDoc,"15.12","266","45.42","266","19.4656","272","51.9983","272","1.0148")
                        }
                        else if(name2 == "GCLW25/M11378G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.4656","260","51.9983","260","1.0148")
                        }
                        else if(name2 == "GCLW25/M11372G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.2656","260","51.7983","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11473G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11474G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else
                        {
                            setMYTransformations(svgDoc,"15.12","250","45.42","250","19.4656","256","51.9983","256","1.0148")
                        }

                    }
                    else if(val.uk_size == "12-18M" || val.uk_size == "18-24M")
                    {
                        svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                        svgDoc.getElementById("ce_size").innerHTML = val.ce_size;

                        if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                        {
                            setMYTransformations(svgDoc,"15.12","266","45.42","266","19.2656","272","51.7983","272","1.0148")
                        }
                        else if(name2 == "GCLW25/M11378G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.4656","260","51.9983","260","1.0148")
                        }
                        else if(name2 == "GCLW25/M11372G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.2656","260","51.7983","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11473G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11474G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else
                        {
                            setMYTransformations(svgDoc,"15.12","250","45.42","250","19.2656","256","51.7983","256","1.0148")
                        }

                        

                    }
                    else if(val.uk_size == "1-2Y" || val.uk_size == "2-3Y" || val.uk_size == "3-4Y" || val.uk_size == "4-5Y" || val.uk_size == "5-6Y" || val.uk_size == "6-7Y" || val.uk_size == "7-8Y" || val.uk_size == "8-9Y" || val.uk_size == "9-10Y")
                    {

                        svgDoc.getElementById("uk_size").innerHTML = val.uk_size.replaceAll("Y", ' years');
                        svgDoc.getElementById("ce_size").innerHTML = val.ce_size.replaceAll("Y", ' years');
                        if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                        {
                            setMYTransformations(svgDoc,"15.12","266","45.42","266","19.8656","272","52.3656","272","0.9105")
                        }
                        else if(name2 == "GCLW25/M11378G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.4656","260","51.9983","260","1.0148")
                        }
                        else if(name2 == "GCLW25/M11372G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.2656","260","51.7983","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11473G")
                        {
                            setMYTransformations(svgDoc,"12","254","36","254","15","260","41.5","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11474G")
                        {
                            setMYTransformations(svgDoc,"12","254","36","254","15","260","41.5","260","1.0148")
                        }
                        else
                        {
                            setMYTransformations(svgDoc,"15.12","250","45.42","250","19.8656","256","52.3656","256","0.9105")
                        }

                    }
                    else if(val.uk_size == "10-11Y" || val.uk_size == "11-12Y" || val.uk_size == "12-13Y" || val.uk_size == "13-14Y" || val.uk_size == "14-15Y" || val.uk_size == "15-16Y")
                    {
                        svgDoc.getElementById("uk_size").innerHTML = val.uk_size.replaceAll("Y", ' years');
                        svgDoc.getElementById("ce_size").innerHTML = val.ce_size.replaceAll("Y", ' years');

                        if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                        {
                            setMYTransformations(svgDoc,"15.12","266","45.42","266","19.8656","272","52.3656","272","0.8255")
                        }
                        else if(name2 == "GCLW25/M11378G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.4656","260","51.9983","260","1.0148")
                        }
                        else if(name2 == "GCLW25/M11372G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.2656","260","51.7983","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11473G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11474G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else
                        {
                            setMYTransformations(svgDoc,"15.12","250","45.42","250","19.8656","256","52.3656","256","0.8255")
                        }

                    }
                    else
                    {
                        svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                        svgDoc.getElementById("ce_size").innerHTML = val.ce_size;

                        if(name2 == "GCLW25/M11367G" || name2 == "GCLW25/M11375G" || name2 == "GCLW25/M11374G")
                        {
                            setMYTransformations(svgDoc,"16.5","266","45.42","266","19.9656","272","51","272","1.0148")
                        }
                        else if(name2 == "GCLW25/M11378G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.4656","260","51.9983","260","1.0148")
                        }
                        else if(name2 == "GCLW25/M11372G")
                        {
                            setMYTransformations(svgDoc,"15.12","254","45.42","254","19.2656","260","51.7983","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11473G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else if(name2 == "GCLW20/M11474G")
                        {
                            setMYTransformations(svgDoc,"10","254","36","254","13","260","41.5","260","1.0148")
                        }
                        else
                        {
                            setMYTransformations(svgDoc,"16.5","250","45.42","250","19.9656","255","51","255","1.0148")
                        }
                    }
                }
                svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;

                if(name2 == "GCLW25/M11364G" || name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
                {
                    svgDoc.getElementById("made_in_czech").innerHTML = country_of_origin_text[0].czech;
                    svgDoc.getElementById("made_in_slovak").innerHTML = country_of_origin_text[0].slovak;
                }
                
                var svgRoot = svgDoc.documentElement;
                var s = new XMLSerializer();
                var d = svgRoot;
                var str = s.serializeToString(d);
                var svg = encodeURI(str)
                svgarr.push(svg)
                ajax_count++;
            })

            if(name2 != "GCLW25/M11364G" && name2 != "GCLW20/M11473G" && name2 != "GCLW20/M11474G")
            {
                var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan   x="2" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2" y="11">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="1.8" y="14.94">'+country_of_origin_text[0].hungarian+'</tspan>';
            } 
            else
            {
                var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan   x="2" y="5.94">'+country_of_origin_text[0].hungarian+'</tspan>';
            }

            svgDoc_back_text.getElementById("part_text").innerHTML = '<tspan   x="2" y="1.2">'+data.order_details[0].part_czech+'</tspan><tspan   x="2" y="6">'+data.order_details[0].part_slovak+'</tspan><tspan  x="1.8" y="10.94">'+data.order_details[0].part_hungarian+'</tspan>';

            var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
            var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
            var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
            var degree =data.order_details[0].degree

            var en_care_instructions = "";
            if(additional_care_text_1 != "" && additional_care_text_1 != null)
            {
                en_care_instructions += toTitleCase(additional_care_text_1[0].english)
            }
            if(additional_care_text_2 != "" && additional_care_text_2 != null)
            {
                en_care_instructions += " / " + toTitleCase(additional_care_text_2[0].english)
            }
            if(additional_care_text_3 != "" && additional_care_text_3 != null)
            {
                en_care_instructions += " / " + toTitleCase(additional_care_text_3[0].english)
            }
            
            if(degree >= 30)
            {
                en_care_instructions += " / " + toTitleCase(data.degree_text[0].english)
            }
            
            
            let sample = en_care_instructions.split('AT');

            if(sample.length > 1)
            {
                $('#splitdiv').html('<div id="breaking_words" style="inline-size: 210px;overflow-wrap: break-word;whitespace:pre;">'+sample[0]+'\xa0\xa0\xa0\xa0\xa0AT '+sample[1]+'</div>'); 
            }
            else
            {
                $('#splitdiv').html('<div id="breaking_words" style="inline-size: 210px;overflow-wrap: break-word;whitespace:pre;">'+sample[0]+'</div>'); 
            }

            $chunk_str = chunksplit("breaking_words")
            var chunks='';
            $y=0;
            $line_length=0;
            $ind=1;
            var isFirstLine = true;
            $($chunk_str).each(function(index,chunk_val){
                if(chunk_val != "AT 30º")
                {
                    chunk_val = chunk_val.replaceAll('\xa0','')
                }

                if(chunk_val.charAt(0) == "/")
                {
                    var firstOccuranceIndex = chunk_val.search(/\//) + 1;

                    var resultStr = chunk_val.substr(0, firstOccuranceIndex) + chunk_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 

                    var new_chunk_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$y+'" >/</tspan><tspan>')
                    chunks += '<tspan>'+new_chunk_val+'</tspan>';
                }
                else
                {
                    var new_chunk_val =chunk_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan>')
                    chunks += '<tspan x="0" y="'+$y+'">'+new_chunk_val+'</tspan>';
                }
                if($chunk_str.length == $ind)
                {
                    $y+=8;
                }
                else
                {
                    $y+=6;
                }
                $ind++;
                $line_length++;
                isFirstLine = false;
                
            })

            var en_garment_component = "";
            var cz_garment_component = "";
            var sk_garment_component = "";
            var hu_garment_component = "";
            $(data.component).each(function(index,comp_val){
                
                var garment_component_text = JSON.parse(comp_val.garment_component_text)
                if(index != 0)
                {
                    en_garment_component += " / " +toTitleCase(garment_component_text[0].english);
                    cz_garment_component += " / " +toTitleCase(garment_component_text[0].czech);
                    sk_garment_component += " / " +toTitleCase(garment_component_text[0].slovak);
                    hu_garment_component += " / " +toTitleCase(garment_component_text[0].hungarian);
                }
                else
                {
                    en_garment_component += toTitleCase(garment_component_text[0].english);
                    cz_garment_component += toTitleCase(garment_component_text[0].czech);
                    sk_garment_component += toTitleCase(garment_component_text[0].slovak);
                    hu_garment_component += toTitleCase(garment_component_text[0].hungarian);
                }

                var isFirstEntryComp = true;
                
                $(comp_val.fibre).each(function(index,fibre_val){
                    var fibre_text = JSON.parse(fibre_val.fibre_composition_text)

                    var separator = isFirstEntryComp ? ": " : " ";

                    en_garment_component += separator + fibre_val.fibre_composition_percentage+ "% " + toTitleCase(fibre_text[0].english);

                    cz_garment_component += separator + fibre_val.fibre_composition_percentage+ "% " + toTitleCase(fibre_text[0].czech);

                    sk_garment_component += separator + fibre_val.fibre_composition_percentage+ "% " + toTitleCase(fibre_text[0].slovak);

                    hu_garment_component += separator + fibre_val.fibre_composition_percentage+ "% " + toTitleCase(fibre_text[0].hungarian);

                    isFirstEntryComp = false;
                });
            })

            var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
            var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
            var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
            var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
            

            if(exclusive_trims_text != "" && exclusive_trims_text != null)
            {
                en_garment_component += " / " + toTitleCase(exclusive_trims_text[0].english)
                cz_garment_component += " / " + toTitleCase(exclusive_trims_text[0].czech)
                sk_garment_component += " / " + toTitleCase(exclusive_trims_text[0].slovak)
                hu_garment_component += " / " + toTitleCase(exclusive_trims_text[0].hungarian)
            }

            if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
            {
                en_garment_component += " / " + toTitleCase(contains_non_textile_part_text[0].english)
                cz_garment_component += " / " + toTitleCase(contains_non_textile_part_text[0].czech)
                sk_garment_component += " / " + toTitleCase(contains_non_textile_part_text[0].slovak)
                hu_garment_component += " / " + toTitleCase(contains_non_textile_part_text[0].hungarian)
            }

            if(kids_warning_text != "" && kids_warning_text != null)
            {
                en_garment_component += " / " + toTitleCase(kids_warning_text[0].english)
                cz_garment_component += " / " + toTitleCase(kids_warning_text[0].czech)
                sk_garment_component += " / " + toTitleCase(kids_warning_text[0].slovak)
                hu_garment_component += " / " + toTitleCase(kids_warning_text[0].hungarian)
            }

            if(additional_care_text_1 != "" && additional_care_text_1 != null)
            {
                cz_garment_component += " / " + toTitleCase(additional_care_text_1[0].czech)
                sk_garment_component += " / " + toTitleCase(additional_care_text_1[0].slovak)
                hu_garment_component += " / " + toTitleCase(additional_care_text_1[0].hungarian)
            }
            if(additional_care_text_2 != "" && additional_care_text_2 != null)
            {
                cz_garment_component += " / " + toTitleCase(additional_care_text_2[0].czech)
                sk_garment_component += " / " + toTitleCase(additional_care_text_2[0].slovak)
                hu_garment_component += " / " + toTitleCase(additional_care_text_2[0].hungarian)
            }
            if(additional_care_text_3 != "" && additional_care_text_3 != null)
            {
                cz_garment_component += " / " + toTitleCase(additional_care_text_3[0].czech)
                sk_garment_component += " / " + toTitleCase(additional_care_text_3[0].slovak)
                hu_garment_component += " / " + toTitleCase(additional_care_text_3[0].hungarian)
            }

            if(degree >= 30)
            {
                cz_garment_component += " / " + toTitleCase(data.degree_text[0].czech)
                sk_garment_component += " / " + toTitleCase(data.degree_text[0].slovak)
                hu_garment_component += " / " + toTitleCase(data.degree_text[0].hungarian)
            }

            if(part_of_set_text != "" && part_of_set_text != null)
            {
                en_garment_component += " / " + toTitleCase(part_of_set_text[0].english)
                cz_garment_component += " / " + toTitleCase(part_of_set_text[0].czech)
                sk_garment_component += " / " + toTitleCase(part_of_set_text[0].slovak)
                hu_garment_component += " / " + toTitleCase(part_of_set_text[0].hungarian)
            }

            if(name2 == "GCLW20/M11473G" || name2 == "GCLW20/M11474G")
            {
                $garment_str = chunkSubstrpercentage(en_garment_component, 28);
                $cz_garment_str = chunkSubstrpercentage(cz_garment_component, 28);
                $sk_garment_str = chunkSubstrpercentage(sk_garment_component, 28);
                $hu_garment_str = chunkSubstrpercentage(hu_garment_component, 28);
            }
            else
            {
                $garment_str = chunkSubstrpercentage(en_garment_component, 28);
                $cz_garment_str = chunkSubstrpercentage(cz_garment_component, 28);
                $sk_garment_str = chunkSubstrpercentage(sk_garment_component, 28);
                $hu_garment_str = chunkSubstrpercentage(hu_garment_component, 28);
            }

            

            chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

            $ind_en=1;
            $z=0;
            $($garment_str).each(function(index,garment_val){
                if(garment_val.charAt(0) == "/")
                {
                    var firstOccuranceIndex = garment_val.search(/\//) + 1;

                    var resultStr = garment_val.substr(0, firstOccuranceIndex) + garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 

                    var new_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$y+'" >/</tspan><tspan>')
                    chunks += '<tspan>'+new_garment_val+'</tspan>';
                }
                else
                {
                    var new_garment_val =garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan>')
                    chunks += '<tspan x="9" y="'+$y+'">'+new_garment_val+'</tspan>';
                }

                if($garment_str.length == $ind_en)
                {
                    $y+=8;
                }
                else
                {
                    $y+=6;
                }
                $ind_en++;
                $line_length++;
            })

            // chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                var new_chunks = '';
            // $($pl_garment_str).each(function(index,pl_garment_val){
            //     if($y == 96)
            //     {
            //         $z=0;
            //     }

            //     if($y > 96)
            //     {
            //         new_chunks += '<tspan x="9" y="'+$z+'">'+pl_garment_val+'</tspan>';
            //     }
            //     else
            //     {
            //         chunks += '<tspan x="9" y="'+$y+'">'+pl_garment_val+'</tspan>';   
            //     }
                
            //     $y+=6;
            //     $z+=6;
            // })

            $limited_length = 0;

            if(name2 == "GCLW25/M11363G")
            {
                $limited_length = 15;
            }
            else if(name2 == "GCLW25/M11364G")
            {
                $limited_length = 16;
            }
            else if(name2 == "GCLW25/M11367G")
            {
                $limited_length = 17;
            }
            else if(name2 == "GCLW25/M11378G")
            {
                $limited_length = 15;
            }
            else if(name2 == "GCLW25/M11372G")
            {
                $limited_length = 15;
            }
            else if(name2 == "GCLW25/M11374G")
            {
                $limited_length = 17;
            }
            else if(name2 == "GCLW25/M11375G")
            {
                $limited_length = 17;
            }
            else if(name2 == "GCLB25/M11410G")
            {
                $limited_length = 15;
            }
            else
            {
                $limited_length = 19;
            }

            if($line_length > $limited_length)
            {
                new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
            }
            else
            {
                chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
            }
            $ind_cz=1;
            $($cz_garment_str).each(function(index,cz_garment_val){

                var isAllUpperCase = cz_garment_val === cz_garment_val.toUpperCase();

                // Set scaling factor based on whether the text is all uppercase

                var scaleFactor = isAllUpperCase ? '0.9' : '1'; // Adjust scale factor as needed
                
                if($line_length == $limited_length)
                {
                    $z=0;
                }

                if($line_length > $limited_length)
                {
                    if(cz_garment_val.charAt(0) == "/")
                    {
                        var firstOccuranceIndex = cz_garment_val.search(/\//) + 1;

                        var resultStr = cz_garment_val.substr(0, firstOccuranceIndex) + cz_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 

                        var new_cz_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$z+'" >/</tspan><tspan transform="scale(' + scaleFactor + ' 1)">')
                        new_chunks += '<tspan transform="scale(' + scaleFactor + ' 1)">'+new_cz_garment_val+'</tspan>';
                    }
                    else
                    {
                        var new_cz_garment_val =cz_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactor + ' 1)">')
                        new_chunks += '<tspan x="9" y="'+$z+'" transform="scale(' + scaleFactor + ' 1)">'+new_cz_garment_val+'</tspan>';
                    }
                    //new_chunks += '<tspan x="9" y="'+$z+'">'+cz_garment_val+'</tspan>';
                }
                else
                {
                    if(cz_garment_val.charAt(0) == "/")
                    {
                        var firstOccuranceIndex = cz_garment_val.search(/\//) + 1;

                        var resultStr = cz_garment_val.substr(0, firstOccuranceIndex) + cz_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactor + ' 1)">'); 

                        var new_cz_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$y+'" >/</tspan><tspan transform="scale(' + scaleFactor + ' 1)">')
                        chunks += '<tspan transform="scale(' + scaleFactor + ' 1)">'+new_cz_garment_val+'</tspan>'; 
                    }
                    else
                    {
                        var new_cz_garment_val =cz_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan>')
                        chunks += '<tspan x="9" y="'+$y+'" transform="scale(' + scaleFactor + ' 1)">'+new_cz_garment_val+'</tspan>'; 
                    }
                    //chunks += '<tspan x="9" y="'+$y+'">'+cz_garment_val+'</tspan>';   
                }

                if($cz_garment_str.length == $ind_cz)
                {
                    $y+=8;
                    $z+=8;
                }
                else
                {
                    $y+=6;
                    $z+=6;
                }
                $ind_cz++;
                $line_length++;
            })

            if($line_length > $limited_length)
            {
                new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
            }
            else
            {
                if($line_length == $limited_length)
                {
                    new_chunks += '<tspan x="0" y="6">SK</tspan>';
                }
                else
                {
                    chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                }
            }
            $ind_sk=1;
            $($sk_garment_str).each(function(index_str_en,sk_garment_val){
                console.log("$line_length 1" + $line_length)

                var isAllUpperCaseSk = sk_garment_val === sk_garment_val.toUpperCase();

                // Set scaling factor based on whether the text is all uppercase

                var scaleFactorSk = isAllUpperCaseSk ? '0.9' : '1'; // Adjust scale factor as needed

                if((index_str_en == 0 && $line_length ==$limited_length))
                {
                    $z=6;
                }
                else if($line_length == $limited_length)
                {
                    $z=0;
                }

                if($line_length > $limited_length)
                {
                    if(sk_garment_val.charAt(0) == "/")
                    {
                        var firstOccuranceIndex = sk_garment_val.search(/\//) + 1;

                        var resultStr = sk_garment_val.substr(0, firstOccuranceIndex) + sk_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 

                        var new_sk_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$z+'" >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                        new_chunks += '<tspan transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>';
                    }
                    else
                    {
                        var new_sk_garment_val =sk_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                        new_chunks += '<tspan x="9" y="'+$z+'" transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>';
                    }
                    //new_chunks += '<tspan x="9" y="'+$z+'">'+sk_garment_val+'</tspan>';
                }
                else
                {
                    if((index_str_en == 0 && $line_length ==$limited_length))
                    {
                        if(sk_garment_val.charAt(0) == "/")
                        {
                            var firstOccuranceIndex = sk_garment_val.search(/\//) + 1;
    
                            var resultStr = sk_garment_val.substr(0, firstOccuranceIndex) + sk_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 
    
                            var new_sk_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$z+'" >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                            new_chunks += '<tspan transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            var new_sk_garment_val =sk_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                            new_chunks += '<tspan x="9" y="'+$z+'" transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>';
                        }
                    }
                    else
                    {
                        if(sk_garment_val.charAt(0) == "/")
                        {
                            var firstOccuranceIndex = sk_garment_val.search(/\//) + 1;
    
                            var resultStr = sk_garment_val.substr(0, firstOccuranceIndex) + sk_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 
    
                            var new_sk_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$y+'" >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                            chunks += '<tspan transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>'; 
                        }
                        else
                        {
                            var new_sk_garment_val =sk_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorSk + ' 1)">')
                            chunks += '<tspan x="9" y="'+$y+'" transform="scale(' + scaleFactorSk + ' 1)">'+new_sk_garment_val+'</tspan>'; 
                        }
                    }
                    
                    //chunks += '<tspan x="9" y="'+$y+'">'+sk_garment_val+'</tspan>';   
                }
                
                if($sk_garment_str.length == $ind_sk)
                {
                    $y+=8;
                    $z+=8;
                }
                else
                {
                    $y+=6;
                    $z+=6;
                }
                $ind_sk++;
                $line_length++;
            })

            if($line_length > $limited_length)
            {
                new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
            }
            else
            {
                if($line_length == $limited_length)
                {
                    new_chunks += '<tspan x="0" y="6">HU</tspan>';
                }
                else
                {
                    chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                }
                
            }

            $($hu_garment_str).each(function(index_str_hu,hu_garment_val){

                var isAllUpperCaseHu = hu_garment_val === hu_garment_val.toUpperCase();

                // Set scaling factor based on whether the text is all uppercase

                var scaleFactorHu = isAllUpperCaseHu ? '0.9' : '1'; // Adjust scale factor as needed
                
                if((index_str_hu == 0 && $line_length ==$limited_length) || ($hu_garment_str.length == 5 && $line_length == $limited_length))
                {
                    $z=6;
                }
                else if($line_length == $limited_length)
                {
                    $z=0;
                }

                if($line_length > $limited_length)
                {
                    if(hu_garment_val.charAt(0) == "/")
                    {
                        var firstOccuranceIndex = hu_garment_val.search(/\//) + 1;

                        var resultStr = hu_garment_val.substr(0, firstOccuranceIndex) + hu_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 


                        var new_hu_garment_val = resultStr.replace("/", '</tspan><tspan x="9" id="hai" y="'+$z+'" >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                        new_chunks += '<tspan transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>';

                        console.log(new_hu_garment_val)
                        console.log(new_chunks)
                    }
                    else
                    {
                        var new_hu_garment_val =hu_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                        new_chunks += '<tspan x="9" y="'+$z+'" transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>';
                    }
                    //new_chunks += '<tspan x="9" y="'+$z+'">'+hu_garment_val+'</tspan>';
                }
                else
                {
                    if((index_str_hu == 0 && $line_length ==$limited_length) || ($hu_garment_str.length == 5 && $line_length == $limited_length))
                    {
                        if(hu_garment_val.charAt(0) == "/")
                        {
                            var firstOccuranceIndex = hu_garment_val.search(/\//) + 1;
    
                            var resultStr = hu_garment_val.substr(0, firstOccuranceIndex) + hu_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 
    
    
                            var new_hu_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$z+'" >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                            new_chunks += '<tspan transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            var new_hu_garment_val =hu_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                            new_chunks += '<tspan x="9" y="'+$z+'" transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>';
                        }
                    }
                    else
                    {
                        if(hu_garment_val.charAt(0) == "/")
                        {
                            var firstOccuranceIndex = hu_garment_val.search(/\//) + 1;

                            var resultStr = hu_garment_val.substr(0, firstOccuranceIndex) + hu_garment_val.slice(firstOccuranceIndex).replace(/\//g, '</tspan><tspan >/</tspan><tspan>'); 

                            var new_hu_garment_val = resultStr.replace("/", '</tspan><tspan x="9" y="'+$y+'" >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                            chunks += '<tspan transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>'; 
                        }
                        else
                        {
                            var new_hu_garment_val =hu_garment_val.replaceAll("/", '</tspan><tspan >/</tspan><tspan transform="scale(' + scaleFactorHu + ' 1)">')
                            chunks += '<tspan x="9" y="'+$y+'" transform="scale(' + scaleFactorHu + ' 1)">'+new_hu_garment_val+'</tspan>'; 
                        }
                    }
                    
                    //chunks += '<tspan x="9" y="'+$y+'">'+hu_garment_val+'</tspan>';   
                }
                
                $y+=6;
                $z+=6;
                $line_length++;
            })
            
            svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
            svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


            
            // console.log(en_care_instructions)
            // console.log(en_care_instructions)

            var s_front = new XMLSerializer();
            var d_front = svgRoot_front_text;
            var str_front = s_front.serializeToString(d_front);
            var svg_front = encodeURI(str_front)
            svgarr_front.push(svg_front)
            
            var s_back = new XMLSerializer();
            var d_back = svgRoot_back;
            var str_back = s_back.serializeToString(d_back);
            var svg_back = encodeURI(str_back)
            svgarr_back.push(svg_back)

            var s_back_text = new XMLSerializer();
            var d_back_text = svgRoot_back_text;
            var str_back_text = s_back_text.serializeToString(d_back_text);
            var svg_back_text = encodeURI(str_back_text)
            svgarr_back_text.push(svg_back_text)
            
            if (ajax_count == data.order_details.length) {
                setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
                var svg_fin = svgarr.join(',_');
                var svg_fin_back = svgarr_back.join(',_');
                var svg_fin_back_text = svgarr_back_text.join(',_');
                var svg_fin_front = svgarr_front.join(',_');

                if(order_page == "job_card_manager")
                {
                    PopupTescoLabelsPrieview(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,isEdit,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso,data.order_details[0].white_wash_astm,data.order_details[0].white_wash_iso,data.order_details[0].white_whit_astm,data.order_details[0].white_whit_iso,data.order_details[0].white_dry_astm,data.order_details[0].white_dry_iso,data.order_details[0].white_iron_astm,data.order_details[0].white_iron_iso,data.order_details[0].white_clean_astm,data.order_details[0].white_clean_iso,data.order_details[0].tesco_size_department,data.order_details[0].woolmark_logo_file,data.order_details[0].white_woolmark_logo_file,data.order_details[0].woolmark_logo_id);
                }
                else
                {
                    if(art_type == "print")
                    {
                        PopupTescoLabelsGeneratePrintManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso,data.order_details[0].white_wash_astm,data.order_details[0].white_wash_iso,data.order_details[0].white_whit_astm,data.order_details[0].white_whit_iso,data.order_details[0].white_dry_astm,data.order_details[0].white_dry_iso,data.order_details[0].white_iron_astm,data.order_details[0].white_iron_iso,data.order_details[0].white_clean_astm,data.order_details[0].white_clean_iso,data.order_details[0].tesco_size_department,data.order_details[0].woolmark_logo_file,data.order_details[0].white_woolmark_logo_file,data.order_details[0].woolmark_logo_id);
                    }
                    else 
                    {
                        PopupTescoLabelsProofManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso,data.order_details[0].white_wash_astm,data.order_details[0].white_wash_iso,data.order_details[0].white_whit_astm,data.order_details[0].white_whit_iso,data.order_details[0].white_dry_astm,data.order_details[0].white_dry_iso,data.order_details[0].white_iron_astm,data.order_details[0].white_iron_iso,data.order_details[0].white_clean_astm,data.order_details[0].white_clean_iso,data.order_details[0].tesco_size_department,data.order_details[0].woolmark_logo_file,data.order_details[0].white_woolmark_logo_file,data.order_details[0].woolmark_logo_id);
                    }
                }
                
            }
        
        },
        error: function(data) {

        },
        complete: function() {

        }
    });
        
}


function setVisibilityForSize(svgDoc) {

    svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
    svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
    svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
    svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

    svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
    svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
    svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

}

function setMYTransformations(svgDoc,ukx,uky,eurx,eury,uk_sizex,uk_sizey,ce_sizex,ce_sizey,xtrans) {

    svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
    svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
    svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
    svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");

    svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
    svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
    svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
    svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");

    svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, "+ukx+", "+uky+")");

    svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, "+eurx+", "+eury+")");

    svgDoc.getElementById("uk_size").setAttribute('transform', "matrix("+xtrans+", 0, 0, 1, "+uk_sizex+", "+uk_sizey+")");

    svgDoc.getElementById("ce_size").setAttribute('transform', "matrix("+xtrans+", 0, 0, 1, "+ce_sizex+", "+ce_sizey+")");

}

function setTransformations(svgDoc,transform_x,transform_xx,stx,sty,stx1,sty1,stx2,sty2) {
    svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix("+transform_x+", 0, 0, 1, "+stx+", "+sty+")");
    svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix("+transform_xx+", 0, 0, 1, "+stx1+", "+sty1+")");
    svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix("+transform_xx+", 0, 0, 1, "+stx2+", "+sty2+")");
}

function setTranslations(svgDoc,polish, czech, slovak, hungarian) {

    svgDoc.getElementById("small_translation1").innerHTML = polish;
    svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
    svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;

}

function chunksplit(breaking_id)
{
    let divs = document.querySelectorAll('#'+breaking_id);
            let wordBreak = [];
            for (let div of divs) {
                
                const words = div.innerText.split(" ");
                div.innerText = words[0];
                let beforeBreak = words[0];
                let height = div.offsetHeight;

                for (let i = 1; i < words.length; i++) {
                    div.innerText += ' ' + words[i];
                    if (div.offsetHeight > height) {
                        height = div.offsetHeight;
                        wordBreak.push(beforeBreak);
                        beforeBreak = words[i];
                    } else {
                        beforeBreak += ' ' + words[i];
                    }
                }
                
                wordBreak.push(beforeBreak);
               return wordBreak;
            }
}

function chunkSubstrpercentage(str,n) {
    let arr = str.split(' ');
    let result=[]
    let subStr=arr[0]
    for(let i = 1; i < arr.length; i++){
        let word = arr[i]
        if(subStr.length + word.length + 1 <= n){
            subStr = subStr + ' ' + word
            console.log("subStr" + subStr)
        }
        else{
        	if(subStr.slice(-1) == "%")
            {
           	   const withoutLastChunk = subStr.slice(0, subStr.lastIndexOf(" "));
               result.push(withoutLastChunk);
               
			   const withLastChunk = subStr.replace(withoutLastChunk,"")
               subStr = withLastChunk.trim() + ' ' + word
               
            }
            else
            {
              result.push(subStr);
              subStr = word
            }
            
        }
    }
    if(subStr.length){result.push(subStr)}
    return result
}