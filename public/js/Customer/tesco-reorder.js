/**
 * This Function is used to Show Reorder Page
 *
 * @return View
 */
function reOrderTesco() {
	//$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	var buyer = localStorage.getItem("buyer");
	
	$.ajax({
		url: base_url + "/customer/tesco/reorder",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token
		},
		beforeSend: function () {

		},
		success: function (data) {
			var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));

			var newObj1 = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders.concat(data.tesco_washcare_orders)))))));

			var uniqueNames = [];

			newObj1.sort(function (obj1, obj2) {
				var date1 = obj1.created_at;
				var date2 = obj2.created_at;
				if (date1 > date2) return -1;
				if (date1 < date2) return 1;
				return 0;
			});
			for (i = 0; i < newObj1.length; i++) {
				//if (newObj1[i].is_order_count_empty == 0) {
					if(newObj1[i].type == "stickers")
					{
						if (newObj1[i].zone == "CE") {
							if (uniqueNames.indexOf(newObj1[i].ce_style_ref) === -1) {
								uniqueNames.push(newObj1[i].ce_style_ref);
							}
						} else {
							if (uniqueNames.indexOf(newObj1[i].uk_style_ref) === -1) {
								uniqueNames.push(newObj1[i].uk_style_ref);
							}
						}
					}
					else
					{
						if(uniqueNames.indexOf(newObj1[i].uk_style_ref+"/"+newObj1[i].ce_style_ref) === -1){
							uniqueNames.push(newObj1[i].uk_style_ref+"/"+newObj1[i].ce_style_ref);
						}
					}
					
				//}
			}
			var options = "<option>Select Style Number</option>";
			for (i = 0; i < uniqueNames.length; i++) {
				options += "<option>" + uniqueNames[i] + "</option>";
			}

			var newObjrepeatOrder = data.tesco_thl3_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_tsl3_orders))
			var newObjrepeatOrder1 = data.tesco_thl3_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_tsl3_orders.concat(data.tesco_washcare_orders)))

			var uniqueNamesRepeatOrder = [];

			newObjrepeatOrder1.sort(function (obj1, obj2) {
				var date1 = obj1.created_at;
				var date2 = obj2.created_at;
				if (date1 > date2) return -1;
				if (date1 < date2) return 1;
				return 0;
			});
			for (i = 0; i < newObjrepeatOrder1.length; i++) {
				//if (newObjrepeatOrder1[i].is_order_count_empty == 0) {
					if(newObjrepeatOrder1[i].type == "stickers")
					{
						if (newObjrepeatOrder1[i].zone == "CE") {
							if (uniqueNamesRepeatOrder.indexOf(newObjrepeatOrder1[i].ce_style_ref) === -1) {
								uniqueNamesRepeatOrder.push(newObjrepeatOrder1[i].ce_style_ref);
							}
						} else {
							if (uniqueNamesRepeatOrder.indexOf(newObjrepeatOrder1[i].uk_style_ref) === -1) {
								uniqueNamesRepeatOrder.push(newObjrepeatOrder1[i].uk_style_ref);
							}
						}
					}
					else
					{
						if(uniqueNamesRepeatOrder.indexOf(newObjrepeatOrder1[i].uk_style_ref+"/"+newObjrepeatOrder1[i].ce_style_ref) === -1){
							uniqueNamesRepeatOrder.push(newObjrepeatOrder1[i].uk_style_ref+"/"+newObjrepeatOrder1[i].ce_style_ref);        
						}   
					}
					
				//}

			}
			var optionsRepeatOrder = "<option>Select Style Number</option>";
			for (i = 0; i < uniqueNamesRepeatOrder.length; i++) {
				optionsRepeatOrder += "<option>" + uniqueNamesRepeatOrder[i] + "</option>";
			}

			$('.reorder-content').html('<ul class="nav nav-tabs nav-tabs-item"> <li class="nav-item li-item active"> <a class="nav-link  li-a active" id="clone_hide" data-toggle="tab" href="#panel1" role="tab">Clone Order</a> </li><li class="nav-item li-item"> <a class="nav-link li-a" data-toggle="tab" href="#panel2" id="repeat_hide" role="tab">Repeat Order</a> </li></ul><div class="tab-content "> <div class="tab-pane fade in active" id="panel1" role="tabpanel"><div><div><div style="padding-top: 15px;" class="image-container text-center"> <img style="width:50px;height:50px" src="' + base_url + '/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Clone Order</h4></div><form><div class="form-group select_div"> <select class="form-control" id="uvc_number" data-live-search="true" onchange=reordertescoartworks()>' + options + '</select></div></form></div></div> </div><div class="tab-pane fade" id="panel2" role="tabpanel"><div><div><div style="padding-top: 15px;" class="image-container text-center"> <img  style="width:50px;height:50px" src="' + base_url + '/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Repeat Order</h4></div><form><div class="form-group select_div"> <select class="form-control" id="style_number" data-live-search="true" onchange=repeatordertescoartworks()>' + optionsRepeatOrder + '</select></div></form><div class="list_div list_div_repeat"><ul class="list-group"></ul></div></div></div></div></div>')
			$('#uvc_number,#style_number').selectpicker();

			$('#clone_hide').click(function () {
				$('.templateDiv').html('<div style="margin-top:150px" class="how_it_works_reorder text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div style="padding-top:0px;overflow-y: scroll;max-height: 450px;" class="list_div list_div_reorder scroll hidden"><ul class="list-group"></ul></div>')
				$('#head_template').html("Re Order")
				$('#uvc_number').val("Select Style Number");
				$('#uvc_number').selectpicker('refresh');
			});
			$('#repeat_hide').click(function () {
				$('.templateDiv').html('<div style="margin-top:150px" class="how_it_works_reorder text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div style="padding-top:0px;overflow-y: scroll;max-height: 450px;" class="list_div list_div_reorder scroll hidden"><ul class="list-group"></ul></div>')
				$('#head_template').html("Re Order")
				$('#style_number').val("Select Style Number");
				$('#style_number').selectpicker('refresh');
			});
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

function reordertescoartworks() {
	$('.how_it_works_reorder').css('margin-top', '0px')
	var uvc = $('#uvc_number').val();
	var buyer = localStorage.getItem("buyer");
	
	if (uvc != "Select Style Number") {
		$('.how_it_works_reorder').html("");
		$('.list_div_reorder').html('<ul class="list-group"></ul>');
		$.ajax({
			url: base_url + "/customer/tesco/reorder-artwork",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				style: uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders.concat(data.tesco_washcare_orders)))))));
				var i = 1;
				$(newObj).each(function (key, index) {

					//if (index.is_order_count_empty == 0) {
						if(index.type == "tesco_washcare_orders")
						{
							var span = '<span class="label labels">-</span><span id="list-li-reorder' + i + '"></span>';
							$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityLabelTesco("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-reorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
							$('.list_div_reorder').removeClass("hidden")
						}
						else
						{
							if (index.zone == "CE") {
								var span = '<span class="label labels">' + index.zone + '</span><span id="list-li-reorder' + i + '"></span>';
								if (index.ce_style_ref == uvc) {
									$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-reorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
									$('.list_div_reorder').removeClass("hidden")
								}
							} else {
								var span = '<span class="label sticker">' + index.zone + '</span><span id="list-li-reorder' + i + '"></span>';
								if (index.uk_style_ref == uvc) {
									$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-reorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
									$('.list_div_reorder').removeClass("hidden")
								}
							}
						}
						
						i++;

					//}

				})

			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
				$(".loader").css('margin-top', '0%');
			}
		})
	} else {
		$('.how_it_works_reorder').html('<h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>');
		$('.list_div_reorder').html('<ul class="list-group"></ul>');
		$('.list_div_reorder').addClass("hidden")
		$('.how_it_works_reorder').css('margin-top', '150px')
	}
}
/**
 * This Function is used to Show the existing artworks for selected style number.
 *
 * @return View
 */

function repeatordertescoartworks() {
	$('.how_it_works_reorder').css('margin-top', '0px')
	var uvc = $('#style_number').val();
	var buyer = localStorage.getItem("buyer");
	
	if (uvc != "Select Style Number") {
		$('.how_it_works_reorder').html("");
		$('.list_div_reorder').html('<ul class="list-group"></ul>');
		$.ajax({
			url: base_url + "/customer/tesco/repeatorder-artwork",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				style: uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				var newObj = data.tesco_thl3_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_tsl3_orders.concat(data.tesco_washcare_orders)))


				var i = 1;
				$(newObj).each(function (key, index) {
					//if (index.is_order_count_empty == 0) {
						if(index.type == "tesco_washcare_orders")
						{
							//alert()
							var span = '<span class="label labels">-</span><span id="list-li-repeatorder' + i + '">';
							$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityLabelTescoRepeatOrderCustomer("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-repeatorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
							$('.list_div_reorder').removeClass("hidden")
						}
						else
						{
							if (index.zone == "CE") {
								var span = '<span class="label labels">' + index.zone + '</span><span id="list-li-repeatorder' + i + '">';
								if (index.ce_style_ref == uvc) {
									$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-repeatorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
									$('.list_div_reorder').removeClass("hidden")
								}
							} else {
								var span = '<span class="label sticker">' + index.zone + '</span><span id="list-li-repeatorder' + i + '">';
								if (index.uk_style_ref == uvc) {
									$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + index.po_number + '</p></div><div class="col-md-2"><p class="name">' + index.TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("' + index.type + '","' + index.id + '","' + index.TemplateName.replace(' ', '') + '","' + index.zone + '","' + index.po_number + '","list-li-repeatorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')
									$('.list_div_reorder').removeClass("hidden")
								}
							}
						}
						
						i++;
					//}

				})

			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
				$(".loader").css('margin-top', '0%');
			}
		})
	} else {
		$('.how_it_works_repeat').html('<h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>');
		$('.list_div_repeat').html('<ul class="list-group"></ul>');

		$('.list_div_reorder').addClass("hidden")
		$('.how_it_works_reorder').css('margin-top', '150px')
	}
}


// function extraQuantityTesco_repeatOrder(type, id, name, zone, po_number, liid) 
// {
// 	$.ajax({
// 		type : 'post',
// 		url : '{{URL::to('triggerNotification')}}',
// 		data: {
			
// 		},

// 		success:function(data)
// 		{
			
// 		}
// 	});
// }



/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function extraQuantityTesco(type, id, name, zone, po_number, liid) {
	var buyer = localStorage.getItem("buyer");
	
	$.ajax({
		url: base_url + "/customer/tesco/extra-quantity",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token,
			type: type,
			id: id
		},
		beforeSend: function () {

		},
		success: function (data) {

			if ((name == "TCL1" && zone == "CE") || (name == "TCL2" && zone == "CE") || (name == "TSL1" && zone == "CE") || (name == "THL1" && zone == "CE")) {
				var qty = "No Of Pieces In Ratio";
				var barcode_tr = "ITF Barcode";
				var tpnd_tr = "ORMS TPND";
				var th_tr = "";
			} else if ((name == "TCL4" && zone == "CE") || (name == "TSL3" && zone == "CE") || (name == "THL3" && zone == "CE")) {
				var qty = "Units Per Case";
				var barcode_tr = "EAN Barcode";
				var tpnd_tr = "ORMS TPND";
				var th_tr = "<th>Size</th>";
			} else if ((name == "TCL4" && zone == "UK") || (name == "TSL3" && zone == "UK") || (name == "THL3" && zone == "UK")) {
				var qty = "Units Per Case";
				var barcode_tr = "EAN Barcode";
				var tpnd_tr = "TPND";
				var th_tr = "<th>Size</th>";
			} else if ((name == "TCL1" && zone == "UK") || (name == "TCL2" && zone == "UK") || (name == "TSL1" && zone == "UK") || (name == "THL1" && zone == "UK")) {
				var qty = "Units Per Carton";
				var barcode_tr = "ITF Barcode";
				var tpnd_tr = "TPND";
				var th_tr = "";
			}

			var tr = "";
			$(data.qty).each(function (key, index) {

				if ((name == "TCL1" && zone == "CE") || (name == "TCL2" && zone == "CE") || (name == "TSL1" && zone == "CE") || (name == "THL1" && zone == "CE")) {
					var unit_qty = index.cases_per_carton;
					var barcode_td = index.itf_barcode;
					var tpnd_td = index.orms_tpn;
					var th_td = "";
				} else if ((name == "TCL4" && zone == "CE") || (name == "TSL3" && zone == "CE") || (name == "THL3" && zone == "CE")) {
					var unit_qty = index.case_size;
					var barcode_td = index.ean_barcode;
					var tpnd_td = index.orms_tpn;
					var th_td = "<td>" + index.size + "</td>";
				} else if ((name == "TCL4" && zone == "UK") || (name == "TSL3" && zone == "UK") || (name == "THL3" && zone == "UK")) {
					var unit_qty = index.case_size;
					var barcode_td = index.ean_barcode;
					var tpnd_td = index.tpnd;
					var th_td = "<td>" + index.size + "</td>";
				} else if ((name == "TCL1" && zone == "UK") || (name == "TCL2" && zone == "UK") || (name == "TSL1" && zone == "UK") || (name == "THL1" && zone == "UK")) {
					var unit_qty = index.units_per_carton;
					var barcode_td = index.itf_barcode;
					var tpnd_td = index.tpnd;
					var th_td = "";
				}
				
				if(data.moq.length > 0)
				{
					var moq = data.moq[0].moq;
				}
				else
				{
					var moq = 1;
				}

				tr += '<tr><td class="tpnd">' + tpnd_td + '</td><td class="barcode">' + barcode_td + '</td><td class="size">' + unit_qty + '</td>' + th_td + '<td> <div class="form-group has-feedback has-success"><input onInput="checkLength(6,this)" type="number"  class="form-control  input-sm tot_qty_table" value="" required placeholder="Quantity" data-min-error="Quantity Should be >= '+moq+'."><div class="help-block with-errors"></div></div></td></tr>';
			});
			$('.quantity').html('<form id="qty_table"><table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px"> <thead> <tr> <th>' + tpnd_tr + '</th><th>' + barcode_tr + '</th> <th>' + qty + '</th> ' + th_tr + '<th>Quantity</th> </tr></thead> <tbody style="font-size: 11px">' + tr + '</tbody></table><div class="po_div"></div><button style="margin-left: 10px;" class="btn btn-primary submitbutton pull-right">Submit</button><button type="button" class="btn btn-danger remove pull-right">Close</button><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></form>')

			var item = $('.li-item.active').find('a').text();
			if (item == "Repeat Order") {
				if ((name == "TSL1") || (name == "TSL3")) {
					$('.po_div').append('<div class="form-group has-feedback has-success"><input type="text" maxlength="9" class="form-control  input-sm purchase_order_number" style="display:none" onkeyup="repeatorderpo()" value="' + po_number + '" placeholder="PO Number"><label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label><div class="help-block with-errors"></div>')
				} else {
					if (zone == "CE") {
						$('.po_div').append('<div class="form-group has-feedback has-success"><input data-minlength="7" maxlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits."  type="text" class="form-control  input-sm purchase_order_number" onkeyup="repeatorderpo()" value="" required placeholder="PO Number"> <label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label><div class="help-block with-errors"></div></div><div class="form-group has-feedback"> <label for="tesco_manufacture_date">OCN Number</label><input  type="text" class="form-control" onkeypress="return isNumber(event)"  name="tesco_ocn" id="tesco_ocn" required="" placeholder="Enter OCN Code" maxlength="15"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"> <label for="tesco_manufacture_date">Manufactured Date</label><div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1"><input  id="tesco_manufacture_date" name="tesco_manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date" onchange="repeatordermanufacturedate()"><div class="input-group-addon"><span class="glyphicon glyphicon-th"> </span > </div ></div ><div class="help-block with-errors"></div> </div>')
					} else {
						$('.po_div').append('<div class="form-group has-feedback has-success"><input data-minlength="9" maxlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits."  type="text" class="form-control  input-sm purchase_order_number" onkeyup="repeatorderpo()" value="" required placeholder="PO Number"> <label style="color:red" id="po_already_error" class="hidden">PO Number already exists</label><div class="help-block with-errors"></div></div><div class="form-group has-feedback">  <label for="tesco_manufacture_date">OCN Number</label><input type="text" class="form-control" onkeypress="return isNumber(event)" name="tesco_ocn" id="tesco_ocn" required="" placeholder="Enter OCN Code" maxlength="15"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"> <label for="tesco_manufacture_date">Manufactured Date</label><div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1"><input id="tesco_manufacture_date" name="tesco_manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date" onchange="repeatordermanufacturedate()"><div class="input-group-addon"><span class="glyphicon glyphicon-th"> </span > </div ></div ><div class="help-block with-errors"></div> </div>')
					}
				}

			}

			$('#datePicker1').datepicker({
				autoclose: true,
				minViewMode: 1,
				format: 'MM,yyyy',
			})

			$('.remove').click(function () {
				$('.left-panel').removeClass('active');
			})

			$('.artwork_name').text(name + " Artwork");
			$('.sample-artwork').html('<input type="hidden" id="po_number" value="'+data.template[0].po_number+'"><input type="hidden" id="uk_style" value="'+data.template[0].uk_style_ref+'"><input type="hidden" id="ce_style" value="'+data.template[0].ce_style_ref+'"><input type="hidden" id="template_name" value="'+name+'"><input type="hidden" id="zone" value="'+zone+'"><object style="display:inline;height: calc( 100vh - 250px );border: 1px solid #333;" id="reorder_svg" class="center-block" data="' + data.template_file[0].template_file + '" type="image/svg+xml"></object>');
			contentdocument(data, name, zone)

			$('#qty_table').validator().on('submit', function (e) {
				if (e.isDefaultPrevented()) {

				} else {
					e.preventDefault();
					setTescoReorder(type, id, liid)
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


function extraQuantityLabelTescoRepeatOrderCustomer(type,id,name,zone,po_number,liid)
{
	// alert('')
	var buyer = localStorage.getItem("buyer");
    
	$.ajax({
		url: base_url + "/customer/tesco/order-management/extra-quantity-label-tesco-repeatOrder",
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
			// alert('rt')
			// console.log(data.composition.length)
			console.log(data)
			$("#primary_size_id").val(data.orders[0]['primary_size_id'])
			localStorage.removeItem("templates");
			localStorage.setItem("templates", data.orders[0]['template_id']);

			var quantity_html = `<div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>UK</th>
                                                <th>EUR</th>
                                                <th>Secondary Size</th>
                                                <th>UK EAN</th>
                                                <th>CE EAN</th>
                                                <th>Quantity <small>(Units)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>`;

                size_array = [];
                console.log(data.tesco_sizelists)
                $.each(data.tesco_sizelists, function (indexInArray, valueOfElement) {
                    quantity_html += `<tr>
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.uk_size + `</td>
											<td >` + valueOfElement.ce_size + `</td>  <td >`;
					var check_size = jQuery.grep(data.qty, function (data_qty) { return data_qty.size_id == valueOfElement.id })

					if(check_size != "")
					{
						var ce_ean_check = check_size[0].ce_ean
						var uk_ean_check = check_size[0].uk_ean
					}
					else
					{
						var ce_ean_check = ""
						var uk_ean_check = ""
					}

                    secondary_sizes = [];

                    for (var index = 0; index < data.size_category_text.length; index++) {

                        var category_value = '-';
                        var size_id = null;

                        $.each(valueOfElement.sec_size_chart, function (index_1, category) {

                            if ($.trim(data.size_category_text[index]) === $.trim(category['category_name'])) {

                                category_value = category['secondary_size'];
                                size_id = category['id'];
                                return false;

                            }

                        });

                        quantity_html += `<strong class="text-justify">` + $.trim(data.size_category_text[index]) + `</strong> : ` + category_value + `<br>`;

                        size_value = category_value === '-' ? null : category_value;

                        secondary_sizes.push({
                            id: size_id,
                            secondary_size: $.trim(data.size_category_text[index]),
                            value: size_value
                        });

                    }

                    quantity_html += `</td> <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" id="uk_ean_` + valueOfElement.id + `" name="uk_ean[]" value="`+uk_ean_check+`" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)"><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" id="ce_ean_` + valueOfElement.id + `" name="ce_ean[]" value="`+ce_ean_check+`" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)"><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control tot_qty_table_label_reorder input-sm label-quantity-field" placeholder="Quantity" id="quantity_` + valueOfElement.id + `" name="quantity[]"><div class="help-block with-errors"></div></div></td>
                                            <input type='hidden' id='secondary_size_data_` + valueOfElement.id + `' name='secondary_size_data[]' value='` + JSON.stringify(secondary_sizes) + `'>
                                        </tr>`;
                });
                quantity_html += `</tbody>
                                        </table>
                                        </div>`;

			$('.quantity_care_label_repeatorder').html('<form id="qty_table_care_label_reorder">'+quantity_html+'<div class="po_div"></div></div></form>')


			$("#tesco_care_po").val(data.orders[0]['po_number'])
			$("#tesco_care_eqos").val(data.orders[0]['eqos_site_code'])
			$("#tesco_care_fabric_code").val(data.orders[0]['fabric_batch_code'])
			$("#tesco_care_leather_id").val(data.orders[0]['leather_suede_code'])
			$("#tesco_care_coo").val(data.orders[0]['country_of_origin_id'])
			$("#tesco_care_supplier").val(data.orders[0]['tesco_supplier_id'])
			$("#tesco_care_uk_style").val(data.orders[0]['uk_style_ref'])
			$("#tesco_care_ce_style").val(data.orders[0]['ce_style_ref'])
			$("#tesco_care_additional_instruction_1").val(data.orders[0]['additional_care_id_1'])
			$("#tesco_care_additional_instruction_2").val(data.orders[0]['additional_care_id_2'])
			$("#tesco_care_additional_instruction_3").val(data.orders[0]['additional_care_id_3'])
			$("#tesco_care_kids_warning").val(data.orders[0]['kids_warning_id'])
			$("#tesco_care_wash_instruction").val(data.orders[0]['wash_symbol_id'])
			$("#tesco_care_bleach_instruction").val(data.orders[0]['bleach_symbol_id'])
			$("#tesco_care_dry_instruction").val(data.orders[0]['dry_symbol_id'])
			$("#tesco_care_iron_instruction").val(data.orders[0]['ironing_symbol_id'])
			$("#tesco_care_dry_clean_instruction").val(data.orders[0]['dryclean_symbol_id'])

			if(data.orders[0]['include_btn_sew_space'])
			{
				$("#tesco_care_button_space").prop('checked',true)
			}
			else
			{
				$("#tesco_care_button_space").prop('checked',false)
			}

			if(data.orders[0]['exclusive_trims'])
			{
				$("#tesco_care_exclusive_trim").prop('checked',true)
			}
			else
			{
				$("#tesco_care_exclusive_trim").prop('checked',false)
			}

			if(data.orders[0]['contains_non_textile_part'])
			{
				$("#tesco_care_non_textile_part").prop('checked',true)
			}
			else
			{
				$("#tesco_care_non_textile_part").prop('checked',false)
			}

			if(data.orders[0]['part_of_set'])
			{
				$("#tesco_care_part_of_set").prop('checked',true)
			}
			else
			{
				$("#tesco_care_part_of_set").prop('checked',false)
			}

			

			for (var counter = 0; counter < data.composition.length; counter++) 
			{
				// console.log($("#tesco_care_garment_component_"+counter).val(data.composition[0]['garment_components'][counter].garment_component_id))
				$temp = data.composition[counter]
				$num = counter+1
				$("#tesco_care_garment_component_"+$num).val($temp['garment_component_id'])
				$("#fiber_total_percentage_"+$num).val($temp['total_composition_percentage'])
				$fibres = data.composition[counter]['fibers'] 	

				for (var looper = 0; looper < $fibres.length; looper++)
				{
					$num2 = looper+1
					$("#tesco_care_fiber_"+$num+"_"+$num2).val($fibres[looper]['fibre_composition_id'])
					$("#fiber_individual_percentage_"+$num+"_"+$num2).val($fibres[looper]['fibre_composition_percentage'])
					
				}
			}	

			$('.selectpicker').selectpicker('refresh');



			var split_template = data.template_file[0].template_file.split(',')

			$('.sample_artwork_care_label_reorder').html('<img src="/images/FRONT.png"><img src="/images/BACK.png">')

			$('.artwork_name_care_label_reorder').text(data.template_file[0].template_name+" Quantity Information")

			$('.remove_care_label_reorder').click(function(){
				$('.care_label_repeatorder').removeClass('active');
		   })

		   $('#qty_table_care_label_reorder').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {

			} else {
				e.preventDefault();
				setTescoCareLabelReorder(type,id,liid)
			}
		})

		},
		error: function (data) {

		},
		complete: function () {
			$('.care_label_repeatorder').show();
	 		$('.care_label_repeatorder').addClass('active');
		}
	})
}

/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function extraQuantityLabelTesco(type,id,name,zone,po_number,liid)
{

	var buyer = localStorage.getItem("buyer");
    
	$.ajax({
		url: base_url + "/customer/tesco/extra-quantity-label-tesco",
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
			
			var quantity_html = `<div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>UK</th>
                                                <th>EUR</th>
                                                <th>Secondary Size</th>
                                                <th>UK EAN</th>
                                                <th>CE EAN</th>
                                                <th>Quantity <small>(Units)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>`;

                size_array = [];
                $.each(data.tesco_sizelists, function (indexInArray, valueOfElement) {
                    quantity_html += `<tr>
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.uk_size + `</td>
											<td >` + valueOfElement.ce_size + `</td>  <td >`;
					var check_size = jQuery.grep(data.qty, function (data_qty) { return data_qty.size_id == valueOfElement.id })

					if(check_size != "")
					{
						var ce_ean_check = check_size[0].ce_ean
						var uk_ean_check = check_size[0].uk_ean
					}
					else
					{
						var ce_ean_check = ""
						var uk_ean_check = ""
					}

                    secondary_sizes = [];

                    for (var index = 0; index < data.size_category_text.length; index++) {

                        var category_value = '-';
                        var size_id = null;

                        $.each(valueOfElement.sec_size_chart, function (index_1, category) {

                            if ($.trim(data.size_category_text[index]) === $.trim(category['category_name'])) {

                                category_value = category['secondary_size'];
                                size_id = category['id'];
                                return false;

                            }

                        });

                        quantity_html += `<strong class="text-justify">` + $.trim(data.size_category_text[index]) + `</strong> : ` + category_value + `<br>`;

                        size_value = category_value === '-' ? null : category_value;

                        secondary_sizes.push({
                            id: size_id,
                            secondary_size: $.trim(data.size_category_text[index]),
                            value: size_value
                        });

                    }

                    quantity_html += `</td> <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" id="uk_ean_` + valueOfElement.id + `" name="uk_ean[]" value="`+uk_ean_check+`" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)"><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" id="ce_ean_` + valueOfElement.id + `" name="ce_ean[]" value="`+ce_ean_check+`" type="text" data-minlength="13" maxlength="13" data-minlength-error="Enter 13 digits." onkeypress="return isNumber(event)"><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control tot_qty_table_label_reorder input-sm label-quantity-field" placeholder="Quantity" id="quantity_` + valueOfElement.id + `" name="quantity[]"><div class="help-block with-errors"></div></div></td>
                                            <input type='hidden' id='secondary_size_data_` + valueOfElement.id + `' name='secondary_size_data[]' value='` + JSON.stringify(secondary_sizes) + `'>
                                        </tr>`;
                });
                quantity_html += `</tbody>
                                        </table>
                                        </div>`;

			$('.quantity_care_label_reorder').html('<form id="qty_table_care_label_reorder">'+quantity_html+'<div class="po_div"></div><button style="margin-left: 10px;" class="btn btn-primary submitbutton pull-right">Submit</button><button type="button" class="btn btn-danger remove_care_label_reorder pull-right">Close</button><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></form>')

			var split_template = data.template_file[0].template_file.split(',')

			$('.sample_artwork_care_label_reorder').html('<img src="/images/FRONT.png"><img src="/images/BACK.png">')

			$('.artwork_name_care_label_reorder').text(data.template_file[0].template_name)

			$('.remove_care_label_reorder').click(function(){
				$('.care_label_reorder').removeClass('active');
		   })

		   $('#qty_table_care_label_reorder').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {

			} else {
				e.preventDefault();
				setTescoCareLabelReorder(type,id,liid)
			}
		})

		},
		error: function (data) {

		},
		complete: function () {
			$('.care_label_reorder').show();
	 		$('.care_label_reorder').addClass('active');
		}
	})
}


function setTescoCareLabelReorder(type,id,liid)
{
	$('.submitbutton').attr('disabled','disabled')
	var item = $('.li-item.active').find('a').text();
	var count=[];
	var val_count=0;
	 $('.tot_qty_table_label_reorder').each(function(i, item)
        {
            count[i]    = $(item).val();
		 	val_count	+= parseInt($(item).val());
        });
        count = count.join(',');
		
	if(item == "Repeat Order")
	{
		
	}
	else
	{
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/customer/tesco/set-tesco-reorder",
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
				$('.care_label_reorder').removeClass('active');
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

function checkLength(len, ele) {
	var fieldLength = ele.value.length;
	if (fieldLength <= len) {
		return true;
	} else {
		var str = ele.value;
		str = str.substring(0, str.length - 1);
		ele.value = str;
	}
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
function repeatorderpo() {
	var mySVG = document.getElementById("reorder_svg");
	var svgDoc;
	var po = $('.purchase_order_number').val();
	svgDoc1.getElementById("po_number").innerHTML = po;
}

/**
 * This Function is used to render manufacture date in artwork.
 *
 * @return View
 */
function repeatordermanufacturedate() {
	var mySVG = document.getElementById("reorder_svg");
	var svgDoc;
	var md = $('#tesco_manufacture_date').val();
	svgDoc1.getElementById("manufactured_date").innerHTML = md.toUpperCase();
}

/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function contentdocument(data, name, zone) {

	if ((name == "TCL1" && zone == "CE") || (name == "TCL2" && zone == "CE") || (name == "TSL1" && zone == "CE") || (name == "THL1" && zone == "CE")) {
		var pieces_in_ratio = data.qty[0].cases_per_carton;
		var tpnd_td = data.qty[0].orms_tpn;
	} else if ((name == "TCL4" && zone == "CE") || (name == "TSL3" && zone == "CE") || (name == "THL3" && zone == "CE")) {
		var pieces_in_ratio = data.qty[0].case_size;
		var tpnd_td = data.qty[0].orms_tpn;
	} else if ((name == "TCL4" && zone == "UK") || (name == "TSL3" && zone == "UK") || (name == "THL3" && zone == "UK")) {
		var pieces_in_ratio = data.qty[0].case_size;
		var tpnd_td = data.qty[0].tpnd;
	} else if ((name == "TCL1" && zone == "UK") || (name == "TCL2" && zone == "UK") || (name == "TSL1" && zone == "UK") || (name == "THL1" && zone == "UK")) {
		var pieces_in_ratio = data.qty[0].units_per_carton;
		var tpnd_td = data.qty[0].tpnd;
	}
	var mySVG = document.getElementById("reorder_svg");
	var svgDoc;
	mySVG.addEventListener("load", function () {
		if (name == "TSL3") {
			svgDoc1 = mySVG.contentDocument;
			var description = data.template[0].short_description.replace(/&/g,'&#038;');
			var size = data.qty[0].size;
			$.ajax({
				url: base_url + "/customer/tesco/get-tsl-size",
				type: 'GET',
				data: {
					size: size,
					zone: zone
				},
				success: function (datas) {
					/** [ Dynamically change svg short description ] */
					if (description != '' && datas.size_chart.length != 0) {
						svgDoc1.getElementById("description").innerHTML = (description.toUpperCase()) + ' ' + datas.size_chart[0].size;
					} else if (datas.size_chart.length != 0) {
						svgDoc1.getElementById("description").innerHTML = datas.size_chart[0].size;
					} else if (description != '') {
						svgDoc1.getElementById("description").innerHTML = description.toUpperCase();
					} else {
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
			jsbarcodetescotslean(data.qty[0].ean_barcode, 'barcode', svgDoc1)
			svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].ean_barcode;
		} else if (name == "TSL1") {
			svgDoc1 = mySVG.contentDocument;
			svgDoc1.getElementById("description").innerHTML = data.template[0].short_description.replace(/&/g,'&#038;');
			svgDoc1.getElementById("tpnd").innerHTML = tpnd_td;
			jsbarcodetescotsl(data.qty[0].itf_barcode, 'barcode', svgDoc1)
			svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].itf_barcode;
		} else {
			var rfid = data.template[0].rfid_compliant;
			var tag = data.template[0].tag_at_source;
			var rfid_yes = rfid == 1 ? "Yes" : "No";
			var tag_yes = tag == 1 ? "Yes" : "No";
			var brand = data.template[0].brand;
			svgDoc1 = mySVG.contentDocument;
			svgDoc1.getElementById("season_code").innerHTML = data.template[0].season_code;
			svgDoc1.getElementById("department").innerHTML = data.template[0].department;

			var str = data.template[0].department.trim();
			var justify_len = str.length;
			if (justify_len < 17) {
				svgDoc1.getElementById("department").style.fontSize = "11px";
			} else {
				svgDoc1.getElementById("department").style.fontSize = "9px";
			}

			svgDoc1.getElementById("rfid").innerHTML = rfid_yes;
			svgDoc1.getElementById("tag_at_source").innerHTML = tag_yes;
			svgDoc1.getElementById("tpnd").innerHTML = tpnd_td;
			
			if(data.tesco_item_master[0].is_qrcode_required == 1)
			{
				svgDoc1.getElementById("tpnd_last_digit").innerHTML = tpnd_td.substr(tpnd_td.length - 3);
			}
			
			svgDoc1.getElementById("po_number").innerHTML = data.template[0].po_number;
			svgDoc1.getElementById("uk_style").innerHTML = data.template[0].uk_style_ref;
			svgDoc1.getElementById("ce_style").innerHTML = data.template[0].ce_style_ref;
			svgDoc1.getElementById("description").innerHTML = data.template[0].short_description.replace(/&/g,'&#038;');
			var description = data.template[0].short_description.replace(/&/g,'&#038;');
			if(data.tesco_item_master[0].is_qrcode_required == 1)
								{
			if(description.length <= 20)
						{
							svgDoc1.getElementById("description").innerHTML = description.toUpperCase();
							if (name === "TCL1")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name === "TCL2")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name === "THL1")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = oswordWrap(description, 23);
							svgDoc1.getElementById("description").innerHTML = str;
							if (name === "TCL1")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name === "TCL2")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
								svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name === "THL1")
							{
								svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
							}
						}
								}
			svgDoc1.getElementById("eqos").innerHTML = data.template[0].eqos_site_code;
			svgDoc1.getElementById("supplier").innerHTML = data.template[0].supplier_name;
			svgDoc1.getElementById("manufactured_date").innerHTML = data.template[0].manufacture_date.toUpperCase();
			
			if (name == "TCL4" || name == "THL3") {
				var size = data.qty[0].size;
				$.ajax({
					url: base_url + "/customer/tesco/get-tsl-size",
					type: 'GET',
					data: {
						size: size,
						zone: zone
					},
					success: function (datas) {
						/** [ Dynamically change svg short description ] */
						 if (description != '' && datas.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + datas.size_chart[0].size;
								if(data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc1.getElementById("description").innerHTML = desc_size;
										if(name === "THL3")
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc1.getElementById("description").innerHTML = str;
										
										if(name === "THL3")
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
											svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc1.getElementById("description").innerHTML = desc_size;
								}
							} else if (datas.size_chart.length != 0) {
								svgDoc1.getElementById("description").innerHTML = datas.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if(data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc1.getElementById("description").innerHTML = desc_size;
										if(name === "THL3")
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc1.getElementById("description").innerHTML = str;
										
										if(name === "THL3")
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc1.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc1.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc1.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc1.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc1.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 163)");
											svgDoc1.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc1.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
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
			} else {
				svgDoc1.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
			}



			svgDoc1.getElementById("brand").innerHTML = brand.toUpperCase().replace('&', '&amp;');

			var str = brand.trim();
			var justify_len = str.length;
			if (justify_len < 15) {
				svgDoc1.getElementById("brand").style.fontSize = "11px";
			} else {
				svgDoc1.getElementById("brand").style.fontSize = "9px";
			}

			if (name == "TSL3" || name == "THL3") {
				jsbarcodetescoean(data.qty[0].ean_barcode, 'barcode', svgDoc1)
				svgDoc1.getElementById("barcode_number").innerHTML = data.qty[0].ean_barcode;
			} else if (name == "TCL1" || name == "TSL1" || name == "THL1") {
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
		width: 3.28148,
		height: 202,
		fontSize: 30,
		flat: true,
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

	svg.setAttribute('width', '408.900px');
	svg.setAttribute('height', '328.02px');

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
		width: 3.9260,
		height: 170,
		fontSize: 30,
		flat: true,
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

	svg.setAttribute('width', '412.25px');
	svg.setAttribute('height', '378.02px');
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

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetescotslean(code, id, svgDoc) {
	//		format: "ean13",
	//		width: 3.45,
	//		height: 125,
	//		fontSize: 35,
	//		flat: true,
	//		displayValue: false
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.9260,
		height: 167,
		fontSize: 30,
		flat: true,
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

	svg.setAttribute('width', '419.075px');
	svg.setAttribute('height', '275.02px');

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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode 
 */


function jsbarcodetescotsl(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 3.28148,
		height: 199,
		fontSize: 30,
		flat: true,
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

	svg.setAttribute('width', '415.100px');
	svg.setAttribute('height', '328.02px');
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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to set tesco reorder.
 * 
 * @return view
 */
function setTescoReorder(type, id, liid) {
	$('.submitbutton').attr('disabled','disabled')
	var item = $('.li-item.active').find('a').text();
	var po = $('.purchase_order_number').val();
	var count = [];
	var val_count = 0;
	$('.tot_qty_table').each(function (i, item) {
		count[i] = $(item).val();
		val_count += parseInt($(item).val());
	});
	count = count.join(',');
	


	if (item == "Repeat Order") {
		var uvc = $('#style_number').val();
		var buyer = localStorage.getItem("buyer");
		
		var po_number = $('.purchase_order_number').val();
		var uk_style = $('#uk_style').val()
		var ce_style = $('#ce_style').val()
		var template_name = $('#template_name').val()
		var zone = $('#zone').val()
		var tesco_ocn = $('#tesco_ocn').val()
		var tesco_manufacture_date = $('#tesco_manufacture_date').val()
		$.ajax({
			 url: base_url + "/customer/tesco/edit-check-exist-po-repeat",
			 type: 'post',
			 data: { _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,zone:zone,template_name:template_name},
			 success: function (data) {
				if($.trim(data) == "true" || template_name == "TSL3")
				{
					$.ajax({
						url: base_url + "/customer/tesco/set-tesco-repeat-order",
						type: "Post",
						dataType: "json",
						data: {
							_token: csrf_token,
							type: type,
							id: id,
							po: po,
							manufacture_date: tesco_manufacture_date,
							ocn: tesco_ocn,
							counts: count
						},
						beforeSend: function () {

						},
						success: function (data) {

							$('#' + liid).append('<span class="label sticker" style="position: absolute;margin-left: -100px;margin-top: 5px;">' + val_count + '</span><img src="/images/tick.png" alt="Tag" style="position: absolute;margin-left: 20px;margin-top: -2px;">');

							var i = 1;
							$(".list-group li").each(function (key, index) {
								i++;
							})

							$('.left-panel').removeClass('active');
							if (data[0].zone == "CE") {
								var span = '<span class="label labels">' + data[0].zone + '</span><span id="list-li-repeatorder' + i + '">';
							} else {
								var span = '<span class="label sticker">' + data[0].zone + '</span><span id="list-li-repeatorder' + i + '">';
							}
							$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="' + base_url + '/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">Style-' + uvc + '</p></div><div class="col-md-2"><p class="po">PO-' + data[0].po_number + '</p></div><div class="col-md-2"><p class="name">' + data[0].TemplateName + '</p></div><div class="col-md-2">' + span + ' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantityTesco("' + data[0].type + '","' + data[0].id + '","' + data[0].TemplateName.replace(' ', '') + '","' + data[0].zone + '","' + data[0].po_number + '","list-li-repeatorder' + i + '")>ADDITIONAL QUANTITY</button></div></div></li>').appendTo('.list_div_reorder ul').show('slow')

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
					$('.submitbutton').removeAttr('disabled')
					$('#po_already_error').removeClass('hidden')
				}
			 },
			 error: function (data) {
				console.log(data);
			 },
			 complete: function () {

			 }
		})




	} else {
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/customer/tesco/set-tesco-reorder",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				type: type,
				id: id,
				counts: count
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

function oswordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'  x='0' y='0'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }
        if (str.length < maxWidth)
            done = true;
    } while (!done);
    return res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;' x='0' y='12'>"+str+"</tspan>";
}
function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}