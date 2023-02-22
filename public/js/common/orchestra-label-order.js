var wash_care_count = 1;
function label_order_details(uvc,user_type,season_code) {
    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
    var buyer = localStorage.getItem("buyer");
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/label-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            uvc: uvc,
            season_code:season_code
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            if(data.customers.length > 0)
			{
                OrderDetailsCardView(uvc, data)
                hangtagmodal(data,uvc)
                heattransfermodal(data)
                specialtagmodal(data)
                mainlabelmodal(data)
                sizelabelmodal(data)
            }
            else
            {
                $('.snackbar_span').html('Company Details Missing, Please Contact Administrator.');
                showSnackbar();
            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

function OrderDetailsCardView(uvc, data)
{
    var buyer_po = "";
    var hang_tag_orders = data.orchestra_hang_tag_orders;
    var orchestra_heat_transfer_orders = data.orchestra_heat_transfer_orders;
	
	$.each(data.buyer_po, function (index,value) {
			buyer_po += value.BuyerPONo + " | ";
	});

	$('.top-content').append('<div class="top-content-new-div"><h3 class="content-heading">Order Detail for UVC ' + uvc + '</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">' + buyer_po + '  </span></h5></div>');


    var merchandisersoption = "<option value=''>Select Option</option>";

	$.each(data.merchandisers, function (index, value) {
		merchandisersoption += "<option value=" + value.id + ">" + value.merchandiser_name + "</option>";
	});

    if (hang_tag_orders != "") {
		var str = hang_tag_orders[0].status;
		$hang_status = str.replace(/ /g, "");
		$hang_status_text = hang_tag_orders[0].status;
	} else {
		$hang_status = "";
		$hang_status_text = "";
	}

    if (orchestra_heat_transfer_orders != "") {
		var str = orchestra_heat_transfer_orders[0].status;
		$heat_transfer_status = str.replace(/ /g, "");
		$heat_transfer_status_text = orchestra_heat_transfer_orders[0].status;
		update_disable=1;
	} else {
		$heat_transfer_status = "";
		$heat_transfer_status_text = "";
	}

    if (data.orchestra_special_tag_orders != "") {
		var str = data.orchestra_special_tag_orders[0].status;
		$special_status = str.replace(/ /g, "");
		$special_status_text = data.orchestra_special_tag_orders[0].status;
		update_disable=1;
	} else {
		$special_status = "";
		$special_status_text = "";
	}

    if (data.orchestra_main_label_orders != "") {
		var str = data.orchestra_main_label_orders[0].status;
		$main_label_status = str.replace(/ /g, "");
		$main_label_status_text = data.orchestra_main_label_orders[0].status;
		update_disable=1;
	} else {
		$main_label_status = "";
		$main_label_status_text = "";
	}
    
	if (data.orchestra_size_label_orders != "") {
		var str = data.orchestra_size_label_orders[0].status;
		$size_label_status = str.replace(/ /g, "");
		$size_label_status_text = data.orchestra_size_label_orders[0].status;
		update_disable=1;
	} else {
		$size_label_status = "";
		$size_label_status_text = "";
	}

    var wcl_grid = ""

    $(data.color_wcl).each(function (index1, value1) {
        wcl_grid += `<div class="artwork-wrapper">
        <div class="wcl-card" data-color="`+value1.Color_Code+`" id="newWashCareLabel_`+value1.Color_Code+`">
        <svg xmlns="http://www.w3.org/2000/svg" width="60.124" height="60.124" viewBox="0 0 60.124 60.124"><path d="M60.367,30.3H37.82V7.758a3.758,3.758,0,1,0-7.516,0V30.3H7.758a3.758,3.758,0,1,0,0,7.516H30.3V60.367a3.758,3.758,0,1,0,7.516,0V37.82H60.367a3.758,3.758,0,1,0,0-7.516Z" transform="translate(-4 -4)" fill="#4e4e4e" opacity="0.148"/></svg>
        <p>Add wash care label</p>
        <p>`+value1.Color_Code+`</p>
        </div>  
        </div>`;
    });

    $(data.orchestra_wash_care_label_orders).each(function (index1, value1) {
        var str = value1.status;
        $wcl_status=str.replace(/ /g, "");
        $wcl_status_text=str;

        wcl_grid += `<div class="artwork-wrapper">
        <div class="artwork-card" data-color="`+value1.color_code+`" id="oldWashCareLabel_`+value1.color_code+`">
            <span class="label label-` + $wcl_status + ` label-status">` + $wcl_status_text + `</span>
            <img src="` + base_url + `/images/wcl_default.png" class="img-responsive center-block">
        </div>  
        <h3>Wash Care Label</h3>
        <p id="clickmewcl_`+wash_care_count+`" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>`;
        washcarelabelmodal(value1, wash_care_count,value1.color_code,data.order_details[0].Activity_Label)
        wash_care_count++;        
    });
    
    
	$('.main-content').html(`
    
        <input type="hidden" value="` + data.order_details[0].UVC + `" id="uvc_id"><input type="hidden" value="` + data.order_details[0].Theme + `" id="theme_label"><input type="hidden" value="` + data.order_details[0].id + `" id="parcel_id"><input type="hidden" value="` + data.order_details[0].Season_Code + `" id="season_code"><input type="hidden" value="` + data.customers[0].customer_id + `" id="customer_id">

        <h4 class="section-title">Artworks</h4>

        <div class="artworks-grid">
        <div class="artwork-wrapper">
        <div class="artwork-card hang-card">
        <span class="label label-` + $hang_status + ` label-status">` + $hang_status_text + `</span>
        <img src="` + base_url + `/images/ht_default.png" class="img-responsive center-block">
        </div>
        <h3>Hangtag</h3>
        <p id="clickmehang" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card heat_transfer-card">
        <span class="label label-` + $heat_transfer_status + ` label-status">` + $heat_transfer_status_text + `</span>
        <img src="` + base_url + `/images/HT.jpg" class="img-responsive center-block">
        </div>
        <h3>Heat Transfer</h3>
        <p id="clickmeheat_transfer" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card special-card">
        <span class="label label-` + $special_status + ` label-status">` + $special_status_text + `</span>
        <img src="` + base_url + `/images/st_default.png" class="img-responsive center-block">
        </div>
        <h3>Special Tag</h3>
        <p id="clickmespecial" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card main_label-card">
        <span class="label label-` + $main_label_status + ` label-status">` + $main_label_status_text + `</span>
        <img src="` + base_url + `/images/ML.png" class="img-responsive center-block">
        </div>
        <h3>Main Label</h3>
        <p id="clickmemain_label" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card size_label-card">
        <span class="label label-` + $size_label_status + ` label-status">` + $size_label_status_text + `</span>
        <img src="` + base_url + `/images/SL.png" class="img-responsive center-block">
        </div>
        <h3>Size Label</h3>
        <p id="clickmesize_label" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        </div>
        </div>

        <h4 class="section-title">Wash care labels</h4>
        <div class="row">
        <div class="col-md-3">
        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Merchandiser</label><select data-live-search="true" class="form-control selectpicker input-sm merchandisers" required>` + merchandisersoption + `</select><div class="help-block with-errors"></div><label class="hidden merchandiser_error" style="color:red">Please Select Merchandiser</label></div>
        </div>
        </div>
    
        <div class="artworks-grid" style="align-items: start; display: flex;">
        `+wcl_grid+`
        <div class="wcl_orders_card"></div>
        </div>
    `);

    selectpicker()

    $('.wcl-card').click(function() {
        if( $('.merchandisers option:selected').val() != "")
        {
            var color_code = $(this).data('color')
            $('.wcl_orders_card').append(`
                    <div class="artwork-wrapper">
                        <div class="artwork-card" data-color="`+color_code+`" id="newWashCareLabel_`+color_code+`">
                            <span class="label label-status"></span>
                            <img src="` + base_url + `/images/wcl_default.png" class="img-responsive center-block">
                        </div>
                        <h3>Wash Care Label</h3>
                        <p id="clickmewcl_`+wash_care_count+`" style="cursor: pointer;">View &amp; Edit <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                    </div>
            `)
            wash_care_count++;
        }
        else
        {
            $('.snackbar_span').html('Please Select Merchandiser');
            showSnackbar();
        }
        
    });
}

function hangtagmodal(data,uvc) {
    var order_details = data.order_details;
    var hang_tag_orders = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (hang_tag_orders != "") {
       

        $template_front_id = hang_tag_orders[0].template_front_id;
        $exist_id = hang_tag_orders[0].id;
        
        var str=hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="";
		$save_button_class="hidden";
		$ocn_number_hang = hang_tag_orders[0].ocn_number;
        var onclick = `onclick=printHangDivOs("svg_hang_front","svg2","`+uvc+`","","admin","`+hang_tag_orders[0].id+`","orchestra_hang_tag_orders","proof")`;
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
		$ocn_number_hang="";
        var onclick = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(order_details, function (index, value) {
        if (hang_tag_orders != "") {
            $hang_tag_count = hang_tag_orders[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 85px;" type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (hang_tag_orders != "") {
            $hang_cm = hang_tag_orders[0].cm;
			$hang_in = hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		var sizebox='';
	}
	else
	{
		var sizebox="";
	}
    
    if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    $('.top-content').append(`
        <form id="hangtagform" data-toggle="validator"><input type="hidden" value="` + $exist_id + `" id="exist_id">
            <div class="sample-front"></div>
            <div class="sample-back"></div>
            <div class="left-panelorc-hang col-md-4">
                <div class="div-max">
                    <div class="col-md-12">
                        <h4>Options</h4>
                        <hr>
                        <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                Number</label><input type="text" value="`+ $ocn_number_hang +`" id="ocn_number_hang" value=""
                                class="form-control input-sm" placeholder="OCN Number"></div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Style</label><label id="hang_template_error" class="hidden"
                                style="color:red;display: inherit;">Please Select the Template</label>
                                <div style="margin-top: -45px;">
                                    <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand">Expand</button>
                                    <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse">Collapse</button>
                                </div>
                            <div><input type="text" id="tree1_q" value="" class="form-control input-sm"
                                    placeholder="Search for Awesomeness"> </div>
                            <div class="scroll" id="tree1"
                                style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div>
                            <div class="help-block with-errors"></div>
                        </div>`+sizebox+`
                    </div>
                </div>
            </div>
            <div class="right-panel-hang col-md-8">
                <div class="div-max scroll">
                    <h4 style="display:inline">Quantity Info for Hang Tag</h4>
                    <span class="label label-` + $status + `" style="margin-left:10px;">` + $status_text + `</span>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table">
                            <thead>
                                <tr>
                                    <th>UVC TCO#</th>
                                    <th>Size</th>
                                    <th>Barcode</th>
                                    <th>Qty</th>
                                    <th style="display: flex;justify-content: center;"><label style="float: left; line-height: 2">Extra %:</label> <input class="form-control input-sm extra_percentagehang" type="text" onkeypress="return checkNumbersOnly(event)" style="width:60px; float: left; margin-left: 5px;"></th>
                                </tr>
                            </thead>
                            <tbody>` + tr + `<tr class="strong">
                                    <td class="text-right" colspan="3">Total:</td>
                                    <td>` + ttots_sum + `</td>
                                    <td class="text-center tot_qty_tablehang_fin">` + ttots + `</td>
                                </tr>
                            </tbody>
                        </table>
                    </div><button type="button" class="btn btn-danger hidden input-sm `+$print_button_class+`"
                        id="cancel_hang_button" style="float: right;margin-left:10px" onclick=cancelHangDiv()>Cancel</button>
                    <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                        id="save_hang_button" style="margin-left:10px">Save</button> <button
                        class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button><button
                        type="button" class="btn btn-primary `+$print_button_class+`" id="print_hang_button"
                        `+onclick+`>Print</button>
                </div>
            </div>
            <div class="backdrop"></div>
        </form>
    `);



//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined && $(this).find('.tot_qty_tablehang').val() != "") {
                tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
            }
        })
        $('#hangtagform .tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
    });
	
	 $('.extra_percentagehang').keyup(function () {
		
        
        var tot_qty_tablehang_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablehang').val(tot_qty);
                if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                    tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablehang').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text("0");
        }

		$('#hangtagform').validator('validate')

    })
	
    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel-hang").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panelorc-hang").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panelorc-hang").removeClass('active');
        $("#hangtagform .right-panel-hang").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    })

    $('#tree1').jstree({
        'core': {
            'data': data.treefinalData
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
    });
    $('#expand').bind("click", function () {
        $('#tree1').jstree("open_all");
    });
    $('#collapse').bind("click", function () {
        $('#tree1').jstree("close_all");
    });
    var to = false;
    $('#tree1_q').keyup(function () {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function () {
            var v = $('#tree1_q').val();
            $('#tree1').jstree(true).search(v);
        }, 250);
    });

    $("#tree1").on("loaded.jstree", function (e, data) {
		$('#tree1').jstree(true).select_node($template_front_id);
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#hangtagform').find('.sample-front').html('<object id="svg_hang_front" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#hangtagform').find('.sample-back').html('<object id="svg2" style="opacity:0;width:0px;height:0px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;position: absolute;"><svg id="ean-13"></svg></div>')
                    } 
					
					if (hang_tag_orders != "") {
                        $('#tree1 >ul > li').each( function() {
                            disable( 'tree1',this.id );        
                        })
                        $('.extra_percentagehang').attr('disabled','disabled')
                        $('.tot_qty_tablehang').attr('disabled','disabled')
                        $('#ocn_number_hang').attr('disabled','disabled')
                    }

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });


    $('#tree1').on('activate_node.jstree', function (e, data) {
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            $('#hang_template_error').addClass('hidden')
            
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#hangtagform').find('.sample-front').html('<object id="svg_hang_front" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#hangtagform').find('.sample-back').html('<object id="svg2" style="opacity:0;width:0px;height:0px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;position: absolute;"><svg id="ean-13"></svg></div>')
                    } 
                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });

    $('#hangtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setHangTagStatus(data.color_wcl)
        }
    })
}

function heattransfermodal(data) {

    var order_details = data.order_details;
    var orchestra_heat_transfer_orders = data.orchestra_heat_transfer_orders;

    var tr = "";
    $qu = 0;
    if (orchestra_heat_transfer_orders != "") {

      
        $template_id = orchestra_heat_transfer_orders[0].template_id;
        $exist_id = orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=orchestra_heat_transfer_orders[0].final_qty;
        
        var str=orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
		$cancel_button_class="";
		$ocn_number_heat = orchestra_heat_transfer_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$cancel_button_class="hidden";
		$ocn_number_heat = "";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(order_details, function (index, value) {
        if (orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = orchestra_heat_transfer_orders[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input type="number" style="margin: 0 auto; width: 85px;" class="form-control input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    $('.top-content').append(`
                <form id="heat_transferform" data-toggle="validator">
                <div class="left-panel-heat col-md-7" id="HandTag"><input type="hidden" value="` + $exist_id + `" id="exist_id">
                    <div class="div-max">
                        <div class="col-md-6 ">
                            <h4>Options</h4>
                            <hr>
                            <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                    Number</label><input type="text" id="ocn_number_heat" value="`+$ocn_number_heat+`"
                                    class="form-control input-sm" placeholder="OCN Number"></div>
                            <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                    class="form_label">Select Style</label><label id="heat_transfer_template_error"
                                    style="color:red;display: inherit;" class="hidden">Please select the template</label>
                                <div style="margin-top: -45px;">
                                    <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_heat">Expand</button>
                                    <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse_heat">Collapse</button>
                                </div>
                                <div><input type="text" id="treeht_q" value="" class="form-control input-sm"
                                        placeholder="Search for Awesomeness"> </div>
                                <div class="scroll" id="treeht"
                                    style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>ArtWork</h4><span class="label label-`+$status+`"
                                style="position: absolute;top: 30px;left: 15px;">`+$status_text+`</span>
                            <hr>
                            <div class="section-1 sections scroll">
                                <div class="sample-artwork" style="text-align:center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-panel-heat col-md-5">
                    <div class="div-max scroll">
                        <h4>Quantity Info for Heat Transfer</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table">
                                <thead>
                                    <tr>
                                        <th>UVC TCO#</th>
                                        <th>Size</th>
                                        <th>Barcode</th>
                                        <th>Qty</th>
                                        <th style="display: flex;justify-content: center;><label style="float: left; line-height: 2">Extra %:</label> <input type="text"
                                                class="form-control input-sm extra_percentageheat"
                                                onkeypress="return checkNumbersOnly(event)"
                                                style="width:60px; float: left; margin-left: 5px;"></th>
                                    </tr>
                                </thead>
                                <tbody>` + tr + `<tr class="strong">
                                        <td class="text-right" colspan="3">Total:</td>
                                        <td>` + ttots_sum + `</td>
                                        <td class="text-center tot_qty_tableheat_fin">` + ttots + `</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                            id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button type="button"
                            class="btn btn-danger hidden input-sm `+$cancel_button_class+`" id="cancel_heat_button"
                            style="float: right;margin-left:10px" onclick=cancelHeatDiv()>Cancel</button><button
                            class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button>
                    </div>
                </div>
                <div class="backdrop"></div>
            </form>
    `);

	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined  && $(this).find('.tot_qty_tableheat').val() != "") {
                tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
            }
        })
        $('#heat_transferform .tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
    });
	
	 $('.extra_percentageheat').keyup(function () {
		
        
        var tot_qty_tableheat_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tableheat').val(tot_qty);
                if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                    tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tableheat').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text("0");
        }
        $('#heat_transferform').validator('validate')
    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panel-heat").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panel-heat").removeClass('active');
        $("#heat_transferform .right-panel-heat").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    })

    treestructureheat_transfer(data.treeviewheat_transfer, "treeht", $template_id,orchestra_heat_transfer_orders);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.color_wcl)
        }
    })
}

function specialtagmodal(data) {

    var orchestra_special_tag_orders = data.orchestra_special_tag_orders;

    $qu = 0;
    if (orchestra_special_tag_orders != "") {

      
        $template_id = orchestra_special_tag_orders[0].template_id;
        $exist_id = orchestra_special_tag_orders[0].id;
        
        var str=orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_special = orchestra_special_tag_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_special = "";
    }
    var st="";
	$c=0;

	$.each(data.color_wcl, function (index, value) {
		if (orchestra_special_tag_orders != "") {
			$special_tag_count=orchestra_special_tag_orders[$c].quantity;
		}else{
			 $special_tag_count="";
		}
		
        st += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" placeholder="Special Tag Count" type="number" required>';
		$c++;
    });
    
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_special" value="'+$ocn_number_special+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label> <div style="margin-top: -45px;"><button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_special">Expand</button><button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse_special">Collapse</button></div> <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+st+'<div class="help-block with-errors" style="margin-bottom: 20px;"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Save</button> <button type="button" class="btn hidden input-sm btn-danger '+$cancel_button_class+'" id="cancel_special_button" style="float: right;margin-left: 10px;" onclick=cancelSpecialDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_tablespecial').keyup(function () {
        var tot_qty_tablespecial_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablespecial').val() != undefined && $(this).find('.tot_qty_tablespecial').val() != "") {
                tot_qty_tablespecial_carton_final += parseInt($(this).find('.tot_qty_tablespecial').val());
            }
        })
        $('#specialtagform .tot_qty_tablespecial_fin').text(tot_qty_tablespecial_carton_final);
    });

    $("#clickmespecial").click(function () {
        // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panel").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panelorc").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    })

    treestructurespecial(data.treeviewspecial, "tree2", $template_id,orchestra_special_tag_orders);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus(data.color_wcl)
        }
    })
}

function mainlabelmodal(data) {

    var orchestra_main_label_orders = data.orchestra_main_label_orders;
    
    $qu = 0;
    if (orchestra_main_label_orders != "") {

      
        $template_id = orchestra_main_label_orders[0].template_id;
        $exist_id = orchestra_main_label_orders[0].id;
        
        var str=orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_main = orchestra_main_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_main = "";
    }
    
	var ml="";
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (orchestra_main_label_orders != "") {
			$main_label_count=orchestra_main_label_orders[$c].quantity;
		}else{
			 $main_label_count="";
		}
		
        ml += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" placeholder="Main Label Count" type="number" required>';
		$c++;
    });

    var button_tree = '<button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_main">Expand</button><button type="button" style="float:right;background-color:#263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="collapse_main">Collapse</button>';
	

    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5"><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_main" value="'+$ocn_number_main+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label> <div style="margin-top: -45px;"> '+button_tree+' </div> <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4><label style="color:#ff9800">This is only to place main label orders. Size label orders you want to place seperately</label> <hr> <div class="scroll"><div class="form-group has-feedback has-success" style="margin-bottom: 20px;">'+ml+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Save</button> <button type="button" class="btn btn-danger hidden input-sm '+$cancel_button_class+'" id="cancel_main_button" style="float: right;margin-left: 10px;" onclick=cancelMainDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');

	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined && $(this).find('.tot_qty_table').val() != "") {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#main_labelform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmemain_label").click(function () {
        // $('.sample-artwork').html("")
        $("#main_labelform .backdrop").toggleClass('active');
        $("#main_labelform .right-panel").toggleClass('active');
        $('#main_labelform .scalable').toggleClass('active');
        $("#main_labelform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #main_labelclose").click(function () {
        $("#main_labelform .left-panelorc").removeClass('active');
        $("#main_labelform .right-panel").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    })

    treestructuremain_label(data.treeviewmain_label, "treeml", $template_id,orchestra_main_label_orders);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus(data.color_wcl)
        }
    })
}

function sizelabelmodal(data) {

    var orchestra_size_label_orders = data.orchestra_size_label_orders;

    var tr = "";
    $qu = 0;
    if (orchestra_size_label_orders != "") {

      
        $template_id = orchestra_size_label_orders[0].template_id;
        $exist_id = orchestra_size_label_orders[0].id;
        $size_label_count=orchestra_size_label_orders[0].final_qty;
        
        var str=orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_size = orchestra_size_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_size = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (orchestra_size_label_orders != "") {

            $size_label_count = orchestra_size_label_orders[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 85px;" type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    $('.top-content').append(`<form id="size_labelform" data-toggle="validator">
    <div class="left-panel-heat col-md-7" id="HandTag"><input type="hidden" value="` + $exist_id + `" id="exist_id">
        <div class="div-max">
            <div class="col-md-5 ">
                <h4>Options</h4>
                <hr>
                <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                        Number</label><input type="text" id="ocn_number_size" value="`+$ocn_number_size+`"
                        class="form-control input-sm" placeholder="OCN Number"></div>
                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                        class="form_label">Select Style</label><label id="size_label_template_error"
                        style="color:red;display: inherit;" class="hidden">Please select the template</label>
                        
                        <div style="margin-top: -45px;">
                            <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_size">Expand</button>
                            <button type="button" style="float:right;background-color:#263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="collapse_size">Collapse</button>
                        </div>

                    <div><input type="text" id="treesl_q" value="" class="form-control input-sm"
                            placeholder="Search for Awesomeness"> </div>
                    <div class="scroll" id="treesl"
                        style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-7">
                <h4>ArtWork</h4><span class="label label-`+$status+`"
                    style="position: absolute;top: 30px;left: 15px;">`+$status_text+`</span>
                <hr>
                <div class="section-1 sections scroll">
                    <div class="sample-artwork" style="text-align:center"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-panel-heat col-md-5">
        <div class="div-max scroll">
            <h4>Quantity Info for Size Label</h4>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="size_label_table">
                    <thead>
                        <tr>
                            <th>UVC TCO#</th>
                            <th>Size</th>
                            <th>Barcode</th>
                            <th>Qty</th>
                            <th style="display: flex;justify-content: center;><label style="float: left; line-height: 2">Extra %:</label> <input type="text"
                                    class="form-control input-sm extra_percentagesize"
                                    onkeypress="return checkNumbersOnly(event)"
                                    style="width:60px; float: left; margin-left: 5px;"></th>
                        </tr>
                    </thead>
                    <tbody>` + tr + `<tr class="strong">
                            <td class="text-right" colspan="3">Total:</td>
                            <td>` + ttots_sum + `</td>
                            <td class="text-center tot_qty_tablesize_fin">` + ttots + `</td>
                        </tr>
                    </tbody>
                </table>
            </div> <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                id="save_size_label_button" style="margin-left:10px">Save</button> <button type="button"
                class="btn input-sm hidden btn-danger `+$cancel_button_class+`" id="cancel_size_button"
                style="float: right;margin-left: 10px;" onclick=cancelSizeDiv()>Cancel</button><button
                class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button>
        </div>
    </div>
    <div class="backdrop"></div>
</form>`);

	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined && $(this).find('.tot_qty_tablesize').val() != "") {
                tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
            }
        })
        $('#size_labelform .tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
    });

	 $('.extra_percentagesize').keyup(function () {
		
        
        var tot_qty_tablesize_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablesize').val(tot_qty);
                if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                    tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablesize').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text("0");
        }

        $('#size_labelform').validator('validate')
    })
	
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panel-heat").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panel-heat").removeClass('active');
        $("#size_labelform .right-panel-heat").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    })

    treestructuresize_label(data.treeviewsize_label, "treesl", $template_id,orchestra_size_label_orders);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus(data.color_wcl)
        }
    })
}

function washcarelabelmodal(data,index_wcl_count,color_code,activity_label) {

    var tr = "";
    $qu = 0;
    if (data != "") {
        $template_id = data.template_id;
        $exist_id = data.id;
        $wcl_label_count=data.final_qty;
        
        var str=data.status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $comment = data.merchandiser_comment;
        $save_button_class="hidden";
        $print_button_class = "";
        $cancel_button_class="";
		$ocn_number_wcl = data.ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $wcl_label_count="";
        $status="";
        $status_text="";
        $comment = "";
        $save_button_class="";
        $print_button_class = "hidden";
        $cancel_button_class="hidden";
		$ocn_number_wcl = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if(color_code == value.Color_Code)
		{
            if (data != "") {
                $wcl_label_count = data.qty[$qu].quantity;
            } else {
                $wcl_label_count = "";
            }
            tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="color_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 85px;" type="number" class="form-control  input-sm tot_qty_tablewcl" data-required-error="Required" value="' + $wcl_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>'; 
            ttots += parseInt($wcl_label_count);
            ttots_sum += parseInt(value.Sum);
            $qu++;
        }
    });
	
	if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }

    $('.top-content').append(`
            <form id="washcarelabelform` + index_wcl_count + `" data-toggle="validator"><input type="hidden" value="` + $exist_id + `"
                id="exist_id` + index_wcl_count + `"><input type="hidden" value="` + color_code + `" id="color_id` + index_wcl_count + `">
                <div class="left-panel-heat col-md-7">
                <div class="div-max">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            </div>
            <div class="right-panel-heat col-md-5">
                <div class="div-max scroll">
                    <hr>
                    <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_`+index_wcl_count+`" style="font-size:11px">
                        <thead>
                            <tr>
                                <th>UVC TCO#</th>
                                <th>Size in ANS</th>
                                <th>Barcode</th>
                                <th>Qty</th>
                                <th><input type="text" class="form-control input-sm extra_percentagewash" onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 11px">` + tr + `
                        <tfoot>
                            <tr class="strong">
                                <td class="text-right" colspan="3">Total:</td>
                                <td>` + ttots_sum + `</td>
                                <td class="text-center tot_qty_tablewash_fin` + index_wcl_count + `">` + ttots + `</td>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                    <hr>`+ $comment +` <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`"
                        type="submit" id="save_wash_button_`+index_wcl_count+`" style="margin-left:10px">Save</button> <button
                        type="button" class="btn input-sm btn-danger `+$print_button_class+`" id="cancel_wash_button_`+index_wcl_count+`"
                        style="float: right;margin-left: 10px;"
                        onclick=cancelWashDiv("`+index_wcl_count+`","admin")>Cancel</button><button
                        class="btn btn-default pull-right input-sm" id="wcl_labelclose_`+index_wcl_count+`" type="button">Close</button> <button
                        type="button" class="btn btn-primary `+$print_button_class+`" id="print_wash_button_`+index_wcl_count+`"
                        onclick=printWashDiv("washcare_svg_front`+index_wcl_count+`","washcare_svg_back`+index_wcl_count+`","washcare_svg_middle`+index_wcl_count+`","`+index_wcl_count+`","admin","`+activity_label+`")>Print</button>
                </div>
            </div>
            <div class="backdrop"></div>
        </form>
    `);

    $("#clickmewcl_"+index_wcl_count).click(function () {
        $("#washcarelabelform"+index_wcl_count+" .backdrop").toggleClass('active');
        $("#washcarelabelform"+index_wcl_count+" .right-panel-heat").toggleClass('active');
        $("#washcarelabelform"+index_wcl_count+" .scalable").toggleClass('active');
        $("#washcarelabelform"+index_wcl_count+" .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #wcl_labelclose_"+index_wcl_count).click(function () {
        $("#washcarelabelform"+index_wcl_count+" .left-panel-heat").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .right-panel-heat").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .backdrop").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .scalable").removeClass('active');
    })
}

function selectpicker() {
	$('.selectpicker').selectpicker({
		style: 'btn-default',
		size: 4
	});

}

function printHangDivOs(front,back,uvc,po,user_type,tableID,tableName,art_type) {

	var a = document.getElementById(front);
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(back);
	var svgDoc1 = a1.contentDocument;
	
	var svgRoot1 = svgDoc1.documentElement;


    if(user_type == "admin")
    {
        var buyer_url = base_url + "/admin/orchestra/get-order-details";
    }
    else if(user_type == "customer")
    {
        var buyer_url = base_url + "/customer/orchestra/get-order-details";
    }
    else if(user_type == "erp")
    {
        var buyer_url = base_url + "/erp/job-manager/get-order-details";
    }
	
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

    $.ajax({
        url: buyer_url,
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

            var disney = data.orchestra_hang_tag_orders[0].Licence_Code;

            var node = data.orchestra_hang_tag_orders[0].template_name;

            svgDoc1.getElementById("theme").innerHTML = data.orchestra_hang_tag_orders[0].theme_reference;

            $(data.orchestra_hang_tag_orders).each(function(index,value){
                var size_txt = value.size;
                var uvc_tco = value.uvc_tco;
                var size = size_txt.split(" ")

                if (size[1] == "ANS") {
                    if(size_txt.split(' ')[0] == 1)
                    {
                        var size1 = "YEAR";
                    }
                    else
                    {
                        var size1 = "YEARS";
                    }
                    
                } else {
                    if(size_txt.split(' ')[0] == 1)
                    {
                        var size1 = "MONTH";
                    }
                    else
                    {
                        var size1 = "MONTHS";
                    }
                }
                
                var code = value.barcode;
                
                $.ajax({
                    url: base_url + "/admin/order-management/get-tsl-size",
                    type: 'GET',
                    async: false,
                    data: {
                        size: size_txt,
                        uvc_tco:uvc_tco
                    },
                    tryCount: 0,
                    retryLimit: 2,
                    success: function (data) {
                        //console.log(data)
                        if(size[0] == "TAILLE")
                        {
                            svgDoc1.getElementById("size").innerHTML = "";
                            svgDoc1.getElementById("size_french").innerHTML = "";
                            svgDoc1.getElementById("size_english").innerHTML = "";
                            svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
                            svgDoc1.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
                            svgDoc1.getElementById("cm").innerHTML = "ONE SIZE";
                            svgDoc1.getElementById("cm").setAttribute('font-size', '6px');
                            
                            svgDoc1.getElementById("tu_one_size").removeAttribute("visibility");
                            svgDoc.getElementById("size").innerHTML = "";
                            svgDoc.getElementById("size_french").innerHTML = "";
                            svgDoc.getElementById("size_english").innerHTML = "";
                            svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
                            svgDoc.getElementById("one_size").removeAttribute("visibility");
                        }
                        else
                        {
                            if(data.size_chart.length > 0)
                            {
                                svgDoc1.getElementById("cm").innerHTML =  data.size_chart[0].size+" CM";
                            }
                            else
                            {
                                svgDoc1.getElementById("cm").innerHTML = "";
                            }

                            svgDoc1.getElementById("size").innerHTML = size[0];
                            svgDoc1.getElementById("size_french").innerHTML = size[1];
                            svgDoc1.getElementById("size_english").innerHTML = size1;
                            svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;svgDoc1.getElementById("cm").setAttribute('font-size', '7.7px');
                            
                            svgDoc.getElementById("size").innerHTML = size[0];
                            //console.log(size[0].length)
                            if(size[0].length == 1)
                            {
                                svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 14 297.5785)');
                                svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 291.033)');
                                svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 297.5544)');
                                
                            }
                            else
                            {
                                svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 13 297.5785)');
                                svgDoc1.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 92 36.4142)');
                                svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 30 291.033)');
                                svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 30 297.5544)');
                            }
                            svgDoc.getElementById("size_french").innerHTML = size[1];
                            svgDoc.getElementById("size_english").innerHTML = size1;
                        }
                        
                        
                        if(data.opqt.length > 0)
                        {
                            $(data.opqt).each(function(index,value){

                            switch(value.Currency)
                            {
                                    case "EUR":
                                    var svg_price =	value.Price.split(',');
                                    var svg_club_price = value.Club_Price.split(',');
                                    svgDoc.getElementById("price1").innerHTML = svg_price[0];
                                    svgDoc.getElementById("price2").innerHTML = ","+svg_price[1];
                                    svgDoc.getElementById("club_price1").innerHTML = svg_club_price[0];
                                    svgDoc.getElementById("club_price2").innerHTML = ","+ 	svg_club_price[1];
                                    break;
                                    case "CAD":
                                    // svgDoc1.getElementById("canada_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("canada_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "CHF":
                                    svgDoc1.getElementById("swiss_price").innerHTML = value.Price;
                                    svgDoc1.getElementById("swiss_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "DZD":
                                    // svgDoc1.getElementById("algeria_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("algeria_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "MAD":
                                    svgDoc1.getElementById("morocco_price").innerHTML = value.Price;
                                    svgDoc1.getElementById("morroco_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "MUR":
                                    // svgDoc1.getElementById("morris_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("morris_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "SAR":
                                    // svgDoc1.getElementById("saudi_club_price").innerHTML = value.Price;
                                    break;
                                    case "USD":
                                    // svgDoc1.getElementById("us_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("us_club_price").innerHTML = value.Club_Price;
                                    break;
                                    default:
                                    console.log("default");
                                    break;
                                }
                            })
                        }
                        else
                        {
                            svgDoc.getElementById("price1").innerHTML = "";
                            svgDoc.getElementById("price2").innerHTML = "";
                            svgDoc.getElementById("club_price1").innerHTML = "";
                            svgDoc.getElementById("club_price2").innerHTML = "";
                            // svgDoc1.getElementById("canada_price").innerHTML = "";
                            // svgDoc1.getElementById("canada_club_price").innerHTML = "";
                            svgDoc1.getElementById("swiss_price").innerHTML = "";
                            svgDoc1.getElementById("swiss_club_price").innerHTML = "";
                            // svgDoc1.getElementById("algeria_price").innerHTML = "";
                            // svgDoc1.getElementById("algeria_club_price").innerHTML = "";
                            svgDoc1.getElementById("morocco_price").innerHTML = "";
                            svgDoc1.getElementById("morroco_club_price").innerHTML = "";
                            // svgDoc1.getElementById("morris_price").innerHTML = "";
                            // svgDoc1.getElementById("morris_club_price").innerHTML = "";
                            // svgDoc1.getElementById("saudi_club_price").innerHTML = "";
                            // svgDoc1.getElementById("us_price").innerHTML = "";
                            // svgDoc1.getElementById("us_club_price").innerHTML = "";
                        }
        
                        //alert(node)
                        if(disney != "DI")
                        {
                            jsbarcode_hang(code, "barcode1", svgDoc1)
                            svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 27 45)');
                        }
                        else
                        {
                            
                           jsbarcode_hang_disney(code, "barcode1", svgDoc1)
                            if(node == "COD2")
                            {
                                   svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 -5 -15)');
                            }
                            else if(node == "DBB1")
                            {
                                svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -20)');
                                //svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 92.096 47.0822)');
                            }
                            else
                            {
                                svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -15)');
                            }
                        }


                        var s = new XMLSerializer();
                        var d = svgRoot1;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        
                        var s_front = new XMLSerializer();
                        var d_front = svgRoot;
                        var str_front = s_front.serializeToString(d_front);
                        var svg_front = encodeURI(str_front)
                        svgarr_front.push(svg_front)
                        
                        ajax++;
                    },
                    error: function (errorXHR, errorStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {

                    }
                })
                
            i++;	
            });

            var svg_fin = svgarr.join(',_');
            var svg_fin_front = svgarr_front.join(',_');
            
            if(i == ajax)
            {
                PopupHangOs(svg_fin_front, svg_fin,svgDoc1,disney,node,uvc);
            }
        },
        error: function(data) {

        },
        complete: function() {

        }
    });	
	
}

function PopupHangOs(svgRoot, svgRoot1,svgDoc1,disney,node,uvc) {
    
        var success = false;
        var url = "";
        
        $.ajax({
            url: base_url + "/admin/order-management/print-hang-pdf",
            type: "post",
            data: {
                _token: csrf_token,
                svg: svgRoot,
                svgRoot: svgRoot1,
                template_name : node,
                uvc : uvc
            },
            async: false,
            success: function (data) {
                if(disney != "DI")
                {
                    svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 50.8835)');
                }
                else
                {	var node = $("#tree1").jstree('get_selected', true)[0].text;
                     if(node == "COD2")
                     {
                         svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 32.6793 139.8835)');
                     }
                     else if(node == "DBB1")
                    {
                        svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 51.8835)');
                    }
                     else
                     {
    
                        svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 57.8835)');
                     }
                }
                
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


/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

 function treestructurespecial(data, tree, template_id,orchestra_special_tag_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand_special').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_special').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					if (orchestra_special_tag_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.special_qty').attr('disabled','disabled')
						$('#ocn_number_special').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Heat Transfer.
 * 
 * @return tree
 */
function treestructureheat_transfer(data, tree, template_id,orchestra_heat_transfer_orders_data) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand_heat').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_heat').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_heat_transfer_orders_data != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentageheat').attr('disabled','disabled')
						$('.tot_qty_tableheat').attr('disabled','disabled')
						$('#ocn_number_heat').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Main Labels.
 * 
 * @return tree
 */
function treestructuremain_label(data, tree, template_id,orchestra_main_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand_main').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_main').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:150px;object-fit:cover">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_main_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.main_label_qty').attr('disabled','disabled')
						$('#ocn_number_main').attr('disabled','disabled')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Size Labels.
 * 
 * @return tree
 */
function treestructuresize_label(data, tree, template_id,orchestra_size_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand_size').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_size').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_size_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentagesize').attr('disabled','disabled')
						$('.tot_qty_tablesize').attr('disabled','disabled')
						$('#ocn_number_size').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}