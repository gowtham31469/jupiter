/**
 * This Function is used to Show Reorder Page
 *
 * @return View
 */
function reOrderTesco()
{
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/reorder",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token
		},
		beforeSend: function () {
			
		},
		success: function (data) {
			var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));
			var uniqueNames = [];
			console.log(newObj)
				for(i = 0; i< newObj.length; i++){
					if(newObj[i].is_order_count_empty == 0)
					{
						if(newObj[i].zone == "CE")
						{
							if(uniqueNames.indexOf(newObj[i].ce_style_ref) === -1){
									uniqueNames.push(newObj[i].ce_style_ref);    
							}   
						}
						else
						{
							if(uniqueNames.indexOf(newObj[i].uk_style_ref) === -1){
									uniqueNames.push(newObj[i].uk_style_ref);   
							}   
						}
					}
				}
				var options="<option>Select Style Number</option>";
				for(i = 0; i< uniqueNames.length; i++){    
					 options+="<option>"+uniqueNames[i]+"</option>";
				}
				
				var newObjrepeatOrder = data.tesco_thl3_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_tsl3_orders))
				
			
				var uniqueNamesRepeatOrder = [];
				for(i = 0; i< newObjrepeatOrder.length; i++){
					if(newObj[i].is_order_count_empty == 0)
					{
						if(newObjrepeatOrder[i].zone == "CE")
						{
							if(uniqueNamesRepeatOrder.indexOf(newObjrepeatOrder[i].ce_style_ref) === -1){
								uniqueNamesRepeatOrder.push(newObjrepeatOrder[i].ce_style_ref);        
							}   
						}
						else
						{
							if(uniqueNamesRepeatOrder.indexOf(newObjrepeatOrder[i].uk_style_ref) === -1){
								uniqueNamesRepeatOrder.push(newObjrepeatOrder[i].uk_style_ref);        
							}   
						}
					}
					     
				}
				var optionsRepeatOrder="<option>Select Style Number</option>";
				for(i = 0; i< uniqueNamesRepeatOrder.length; i++){    
					 optionsRepeatOrder+="<option>"+uniqueNamesRepeatOrder[i]+"</option>";
				}
			
			$('.main-content').html('<ul class="nav nav-tabs"> <li class="nav-item active"> <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">Clone Order</a> </li><li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#panel2" role="tab">Repeat Order</a> </li></ul><div class="tab-content "> <div class="tab-pane fade in active" id="panel1" role="tabpanel"><div class="container pad-top"><div class="col-md-10 col-md-offset-1"><div class="image-container text-center"> <img src="'+base_url+'/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Clone Order</h4></div><form><div class="form-group select_div"> <select class="form-control" id="uvc_number" data-live-search="true" onchange=reordertescoartworks()>'+options+'</select></div></form><div class="how_it_works_reorder text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div class="list_div list_div_reorder"><ul class="list-group"></ul></div></div></div> </div><div class="tab-pane fade" id="panel2" role="tabpanel"><div class="container pad-top"><div class="col-md-10 col-md-offset-1 "><div class="image-container text-center"> <img src="'+base_url+'/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Repeat Order</h4></div><form><div class="form-group select_div"> <select class="form-control" id="style_number" data-live-search="true" onchange=repeatordertescoartworks()>'+optionsRepeatOrder+'</select></div></form><div class="how_it_works_repeat text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div class="list_div list_div_repeat"><ul class="list-group"></ul></div></div></div></div></div>')
			$('#uvc_number,#style_number').selectpicker();
		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
			$(".loader").css('margin-top', '0%');
		}
	})
}

/**
 * This Function is used to Show the existing artworks for selected style number.
 *
 * @return View
 */

function reordertescoartworks()
{
	var uvc=$('#uvc_number').val();
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
	if(uvc != "Select Style Number")
	{
		$('.how_it_works_reorder').html("");
		$('.list_div_reorder').html('<ul class="list-group"></ul>');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/reorder-artwork",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				style:uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));
				var i=1;
				$(newObj).each(function(key,index){
					
					if(index.is_order_count_empty == 0)
					{
						   if(index.zone == "CE")
							{
								var span='<span class="label labels">'+index.zone+'</span><span id="list-li-reorder'+i+'"></span>';
							}
							else
							{
								var span='<span class="label sticker">'+index.zone+'</span><span id="list-li-reorder'+i+'"></span>';
							}
								$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="'+base_url+'/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-'+uvc+'</p></div><div class="col-md-2"><p class="po">PO-'+index.po_number+'</p></div><div class="col-md-2"><p class="name">'+index.TemplateName+'</p></div><div class="col-md-2">'+span+' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("'+index.type+'","'+index.id+'","'+index.TemplateName.replace(' ', '')+'","'+index.zone+'","'+index.po_number+'","list-li-reorder'+i+'")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
					i++;
					}
				})
				
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
				$(".loader").css('margin-top', '0%');
			}
		})
	}
	else
	{
		$('.how_it_works_reorder').html('<h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>');
		$('.list_div_reorder').html('<ul class="list-group"></ul>');
	}
}
/**
 * This Function is used to Show the existing artworks for selected style number.
 *
 * @return View
 */

function repeatordertescoartworks()
{
	var uvc=$('#style_number').val();
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
	if(uvc != "Select Style Number")
	{
		$('.how_it_works_repeat').html("");
		$('.list_div_repeat').html('<ul class="list-group"></ul>');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/repeatorder-artwork",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				style:uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				var newObj = data.tesco_thl3_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_tsl3_orders))
				
				
				var i=1;
				$(newObj).each(function(key,index){
					if(index.is_order_count_empty == 0)
					{
						if(index.zone == "CE")
						{
							var span='<span class="label labels">'+index.zone+'</span><span id="list-li-repeatorder'+i+'"></span>';
						}
						else
						{
							var span='<span class="label sticker">'+index.zone+'</span><span id="list-li-repeatorder'+i+'"></span>';
						}
						$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="'+base_url+'/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-'+uvc+'</p></div><div class="col-md-2"><p class="po">PO-'+index.po_number+'</p></div><div class="col-md-2"><p class="name">'+index.TemplateName+'</p></div><div class="col-md-2">'+span+' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("'+index.type+'","'+index.id+'","'+index.TemplateName.replace(' ', '')+'","'+index.zone+'","'+index.po_number+'","list-li-repeatorder'+i+'")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_repeat ul').show('slow')
						i++;
					}
				})
				
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
				$(".loader").css('margin-top', '0%');
			}
		})
	}
	else
	{
		$('.how_it_works_repeat').html('<h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>');
		$('.list_div_repeat').html('<ul class="list-group"></ul>');
	}
}

/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function extraQuantityTesco(type,id,name,zone,po_number,liid)
{
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/extra-quantity",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token,
			type:type,
			id:id
		},
		beforeSend: function () {
			
		},
		success: function (data) {
			
			if((name == "TCL1" && zone=="CE") ||(name == "TCL2" && zone=="CE") || (name == "TSL1" && zone=="CE") || (name == "THL1" && zone=="CE"))
			{
				var qty="Cases per carton";
				var barcode_tr="ITF Barcode";
				var tpnd_tr="ORMS TPND";
				var th_tr="";
			}
			else if((name == "TCL4" && zone=="CE") || (name == "TSL3" && zone=="CE") || (name == "THL3" && zone=="CE"))
			{
				var qty="Case Size";
				var barcode_tr="EAN Barcode";
				var tpnd_tr="ORMS TPND";
				var th_tr="<th>Size</th>";
			}
			else if((name == "TCL4" && zone=="UK") || (name == "TSL3" && zone=="UK") || (name == "THL3" && zone=="UK"))
			{
				var qty="Case Size";
				var barcode_tr="EAN Barcode";
				var tpnd_tr="TPND";
				var th_tr="<th>Size</th>";
			}
			else if((name == "TCL1" && zone=="UK") ||(name == "TCL2" && zone=="UK") || (name == "TSL1" && zone=="UK") || (name == "THL1" && zone=="UK"))
			{
				var qty="Units per carton";
				var barcode_tr="ITF Barcode";
				var tpnd_tr="TPND";
				var th_tr="";
			}
			
			var tr="";
			$(data.qty).each(function(key,index){
				
					if((name == "TCL1" && zone=="CE") ||(name == "TCL2" && zone=="CE") || (name == "TSL1" && zone=="CE") || (name == "THL1" && zone=="CE"))
					{
						var unit_qty = index.cases_per_carton;
						var barcode_td=index.itf_barcode;
						var tpnd_td=index.orms_tpn;
						var th_td="";
					}
					else if((name == "TCL4" && zone=="CE") || (name == "TSL3" && zone=="CE") || (name == "THL3" && zone=="CE"))
					{
						var unit_qty = index.case_size;
						var barcode_td=index.ean_barcode;
						var tpnd_td=index.orms_tpn;
						var th_td="<td>"+index.size+"</td>";
					}
					else if((name == "TCL4" && zone=="UK") || (name == "TSL3" && zone=="UK") || (name == "THL3" && zone=="UK"))
					{
						var unit_qty = index.case_size;
						var barcode_td=index.ean_barcode;
						var tpnd_td=index.tpnd;
						var th_td="<td>"+index.size+"</td>";
					}
					else if((name == "TCL1" && zone=="UK") ||(name == "TCL2" && zone=="UK") || (name == "TSL1" && zone=="UK") || (name == "THL1" && zone=="UK"))
					{
						var unit_qty = index.units_per_carton;
						var barcode_td=index.itf_barcode;
						var tpnd_td=index.tpnd;
						var th_td="";
					}

				 tr += '<tr><td class="tpnd">' + tpnd_td + '</td><td class="barcode">' + barcode_td + '</td><td class="size">' + unit_qty + '</td>'+th_td+'<td> <div class="form-group has-feedback has-success"><input maxlength="4" onkeypress="return checkNumbersOnly(event)" type="text" class="form-control  input-sm tot_qty_table" value="" required placeholder="Quantity"><div class="help-block with-errors"></div></div></td></tr>';
			});
			$('.quantity').html('<form id="qty_table"><table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px"> <thead> <tr> <th>'+tpnd_tr+'</th><th>'+barcode_tr+'</th> <th>'+qty+'</th> '+th_tr+'<th>Quantity</th> </tr></thead> <tbody style="font-size: 11px">'+tr+'</tbody></table><div class="po_div"></div><button style="margin-left: 10px;" class="btn btn-primary submitbutton pull-right">Submit</button><button type="button" class="btn btn-danger remove pull-right">Close</button><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></form>')
			
			var item = $('.nav-item.active').find('a').text();
			if(item == "Repeat Order")
			{
				if((name == "TSL1") ||(name == "TSL3"))
				{
					$('.po_div').append('<div class="form-group has-feedback has-success"><input type="text" maxlength="9" class="form-control  input-sm purchase_order_number" style="display:none" onkeyup="repeatorderpo()" value="'+po_number+'" placeholder="PO Number"> <label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label><div class="help-block with-errors"></div>')
				}
				else
				{
					$('.po_div').append('<div class="form-group has-feedback has-success"><input maxlength="9" type="text" class="form-control  input-sm purchase_order_number" onkeyup="repeatorderpo()" value="" required placeholder="PO Number"> <label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label><div class="help-block with-errors"></div>')
				}
				
			}
				
				
			
			$('.remove').click(function(){
				 $('.left-panel').removeClass('active');
			})
			
			$('.artwork_name').text(name + " Artwork");
			$('.sample-artwork').html('<input type="hidden" id="po_number" value="'+data.template[0].po_number+'"><input type="hidden" id="uk_style" value="'+data.template[0].uk_style_ref+'"><input type="hidden" id="ce_style" value="'+data.template[0].ce_style_ref+'"><input type="hidden" id="template_name" value="'+name+'"><input type="hidden" id="zone" value="'+zone+'"><object style="display:inline;height: calc( 100vh - 250px );border: 1px solid #333;" id="reorder_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
			contentdocument(data,name,zone)
			
			$('#qty_table').validator().on('submit', function (e) {
				if (e.isDefaultPrevented()) {

				} else {
					e.preventDefault();
					setTescoReorder(type,id,liid)
				}
			})

		},
		error: function (data) {

		},
		complete: function () {
			$('.left-panel').show();
	 		$('.left-panel').addClass('active');
		}
	})
}

/**
 * This Function is used to restrict alpha numeric and special characters.
 * 
 * @return boolean
 */

function checkNumbersOnly(e) {

	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

/**
 * This Function is used to render po in artwork.
 *
 * @return View
 */
function repeatorderpo()
{
	var mySVG = document.getElementById("reorder_svg");
	var svgDoc;
    var po=$('.purchase_order_number').val();
    svgDoc1.getElementById("po_number").innerHTML = po;
}

/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function contentdocument(data,name,zone)
{
	
	if((name == "TCL1" && zone=="CE") ||(name == "TCL2" && zone=="CE") || (name == "TSL1" && zone=="CE") || (name == "THL1" && zone=="CE"))
	{
		var pieces_in_ratio=data.qty[0].cases_per_carton;
		var tpnd_td=data.qty[0].orms_tpn;
	}
	else if((name == "TCL4" && zone=="CE") || (name == "TSL3" && zone=="CE") || (name == "THL3" && zone=="CE"))
	{
		var pieces_in_ratio=data.qty[0].case_size;
		var tpnd_td=data.qty[0].orms_tpn;
	}
	else if((name == "TCL4" && zone=="UK") || (name == "TSL3" && zone=="UK") || (name == "THL3" && zone=="UK"))
	{
		var pieces_in_ratio=data.qty[0].case_size;
		var tpnd_td=data.qty[0].tpnd;
	}
	else if((name == "TCL1" && zone=="UK") ||(name == "TCL2" && zone=="UK") || (name == "TSL1" && zone=="UK") || (name == "THL1" && zone=="UK"))
	{
		var pieces_in_ratio=data.qty[0].units_per_carton;
		var tpnd_td=data.qty[0].tpnd;
	}
	var mySVG = document.getElementById("reorder_svg");
	var svgDoc;
	mySVG.addEventListener("load", function () {
		if(name == "TSL3")
		{
		    svgDoc1 = mySVG.contentDocument;
			var description = data.template[0].short_description;
			var size=data.qty[0].size;
			$.ajax({
                     url: base_url + "/admin/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (datas) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && datas.size_chart.length != 0)
                           {
                              svgDoc1.getElementById("description").innerHTML = (description.toUpperCase())+' '+datas.size_chart[0].size;
                           }
                           else if(datas.size_chart.length != 0)
                           {
                              svgDoc1.getElementById("description").innerHTML = datas.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc1.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc1.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (datas) {
                        console.log(datas);
                     },
                     complete: function () {
                          /** [To make top menu active] */
                          $(function () {
                              var url = window.location;
                              $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                              $('.dynamic-menu a').filter(function () {
                                  return this.href == url;
                              }).parent('li').addClass('current-page').find('a').addClass('active');
                          });
                     }
                 })
			
		   svgDoc1.getElementById("tpnd").innerHTML = tpnd_td;
		   jsbarcodetescoean(data.qty[0].ean_barcode, 'barcode', svgDoc1)
		   svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].ean_barcode;
		}
		else if(name == "TSL1")
		{
		   svgDoc1 = mySVG.contentDocument;
		   svgDoc1.getElementById("description").innerHTML = data.template[0].short_description;
		   svgDoc1.getElementById("tpnd").innerHTML = tpnd_td;
		   jsbarcodetesco(data.qty[0].itf_barcode, 'barcode', svgDoc1)
		   svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].itf_barcode;
		}
		else
		{
		    var rfid = data.template[0].rfid_compliant;
			var tag = data.template[0].tag_at_source;
			var rfid_yes = rfid == 1 ? "Yes":"No";
			var tag_yes = tag == 1 ? "Yes":"No";
			var brand=data.template[0].brand;
			svgDoc1 = mySVG.contentDocument;
			svgDoc1.getElementById("season_code").innerHTML = data.template[0].season_code;
			svgDoc1.getElementById("department").innerHTML =data.template[0].department;
			
			 var str =data.template[0].department.trim();
			   var justify_len = str.length;
			   if(justify_len < 17)
			   {
				   svgDoc1.getElementById("department").style.fontSize = "11px";
			   }
			   else
			   {
				  svgDoc1.getElementById("department").style.fontSize = "9px"; 
			   }
			
			svgDoc1.getElementById("rfid").innerHTML =rfid_yes;
			svgDoc1.getElementById("tag_at_source").innerHTML =tag_yes;
			svgDoc1.getElementById("tpnd").innerHTML = tpnd_td;
			svgDoc1.getElementById("po_number").innerHTML = data.template[0].po_number;
			svgDoc1.getElementById("uk_style").innerHTML = data.template[0].uk_style_ref;
			svgDoc1.getElementById("ce_style").innerHTML = data.template[0].ce_style_ref;
			svgDoc1.getElementById("description").innerHTML = data.template[0].short_description;
			svgDoc1.getElementById("eqos").innerHTML = data.template[0].eqos_site_code;
			svgDoc1.getElementById("supplier").innerHTML = data.template[0].supplier_name;
			svgDoc1.getElementById("manufactured_date").innerHTML = data.template[0].manufacture_date;
			var description = data.template[0].short_description;
			if(name == "TCL4" || name == "THL3" )
			{
				
				var size=data.qty[0].size;
				$.ajax({
						 url: base_url + "/admin/tesco/get-tsl-size",
						 type: 'GET',
						 data: {size:size,zone:zone},
						 success: function (datas) {
							   /** [ Dynamically change svg short description ] */
	                           if(description != '' && datas.size_chart.length != 0)
	                           {
	                              svgDoc1.getElementById("description").innerHTML = (description.toUpperCase())+' '+datas.size_chart[0].size;
	                           }
	                           else if(datas.size_chart.length != 0)
	                           {
	                              svgDoc1.getElementById("description").innerHTML = datas.size_chart[0].size; 
	                           }
	                           else if(description != '')
	                           {
	                              svgDoc1.getElementById("description").innerHTML = description.toUpperCase();
	                           }
	                           else
	                           {
	                              svgDoc1.getElementById("description").innerHTML = '';
	                           }
						 },
						 error: function (datas) {
							console.log(datas);
						 },
						 complete: function () {
							  /** [To make top menu active] */
							  $(function () {
								  var url = window.location;
								  $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
								  $('.dynamic-menu a').filter(function () {
									  return this.href == url;
								  }).parent('li').addClass('current-page').find('a').addClass('active');
							  });
						 }
					 })
				svgDoc1.getElementById("unit_per_case").innerHTML = pieces_in_ratio;
			}
			else
			{
				svgDoc1.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
			}
			
			
			
			svgDoc1.getElementById("brand").innerHTML =brand.toUpperCase().replace('&', '&amp;');
			
			 var str =brand.trim();
		     var justify_len = str.length;
		     if(justify_len < 15)
		     {
			     svgDoc1.getElementById("brand").style.fontSize = "11px";
		     }
		     else
		     {
			    svgDoc1.getElementById("brand").style.fontSize = "9px"; 
		     }

			if(name == "TSL3" || name == "THL3" )
			{
				jsbarcodetescoean(data.qty[0].ean_barcode, 'barcode', svgDoc1)
				svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].ean_barcode;
			}
			else if(name == "TCL1" || name == "TSL1"|| name == "THL1" )
			{
				jsbarcodetesco(data.qty[0].itf_barcode, 'barcode', svgDoc1)
				svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].itf_barcode;
			}
		}
		
		
	}, false);
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetesco(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 2.50,
		height: 100,
		fontSize: 30,
		flat:true,
		displayValue: false
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
	svg.setAttribute('font-size', '30px');
	svg.setAttribute('font-family', 'frankgothcnbt');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

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
	console.log(img.src);

	//remove temporary canvas
	canvasTmp.remove();
	
	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetescoean(code, id, svgDoc) {

	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.45,
		height: 125,
		fontSize: 35,
		flat:true,
		displayValue: false
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
	svg.setAttribute('font-family', 'frankgothcnbt');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

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
	console.log(img.src);

	//remove temporary canvas
	canvasTmp.remove();
	
	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to set tesco reorder.
 * 
 * @return view
 */
function setTescoReorder(type,id,liid)
{
	
	var item = $('.nav-item.active').find('a').text();
	var po=$('.purchase_order_number').val();
	var count=[];
	var val_count=0;
	 $('.tot_qty_table').each(function(i, item)
        {
            count[i]    = $(item).val();
		 	val_count	+= parseInt($(item).val());
        });
        count = count.join(',');
		$('#'+liid).append('<span class="label sticker" style="position: absolute;margin-left: 15px;margin-top: 5px;">'+val_count+'</span><img src="http://atlas-content-cdn.pixelsquid.com/stock-images/check-mark-z074ln3-600.jpg" alt="Tag" style="position: absolute;margin-left: 60px;margin-top: -2px;">');
	if(item == "Repeat Order")
	{
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		var po_number=$('.purchase_order_number').val();
		var uk_style=$('#uk_style').val()
		var ce_style=$('#ce_style').val()
		var template_name=$('#template_name').val()
		var zone=$('#zone').val()
		
		$.ajax({
			 url: base_url + "/admin/tesco/edit-check-exist-po-repeat",
			 type: 'post',
			 data: { _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,zone:zone,template_name:template_name},
			 success: function (data) {
				if(data == "true")
				{
					$.ajax({
						url: base_url + "/admin/" + buyer + "/order-management/set-tesco-repeat-order",
						type: "Post",
						dataType: "json",
						data: {
							_token: csrf_token,
							type:type,
							id:id,
							po:po,
							counts:count
						},
						beforeSend: function () {

						},
						success: function (data) {
							$('.left-panel').removeClass('active');
							$('.snackbar_span').html('Repeat Order Created Successfully ...!');
							showSnackbar();
						},
						error: function (data) {

						},
						complete: function () {

						}
					})
				}
				else
				{
					$('#po_already_error').removeClass('hidden')
				}
			 },
			 error: function (data) {
				console.log(data);
			 },
			 complete: function () {

			 }
		})
		
		
	}
	else
	{
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set-tesco-reorder",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				type:type,
				id:id,
				counts:count
			},
			beforeSend: function () {

			},
			success: function (data) {
				$('.left-panel').removeClass('active');
				$('.snackbar_span').html('Reorder Created Successfully ...!');
				showSnackbar();
			},
			error: function (data) {

			},
			complete: function () {

			}
		})
	}
	
}
/** [showSnackbar function] */
function showSnackbar() {
	try {
		// Get the snackbar DIV
		var x = document.getElementById("snackbar")

		// Add the "show" class to DIV
		x.className = "show";

		// After 3 seconds, remove the show class from DIV
		setTimeout(function () {
			x.className = x.className.replace("show", "");
		}, 3000);
	} catch (e) {
		console.log("Error:" + e)
	}
}