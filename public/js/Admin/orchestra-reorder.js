/**
 * This Function is used to Show Reorder Page
 *
 * @return View
 */
function reOrder()
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
			
			var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders)))))));
			var uniqueNames = [];
				for(i = 0; i< newObj.length; i++){    
					if(uniqueNames.indexOf(newObj[i].uvc) === -1){
						uniqueNames.push(newObj[i].uvc);        
					}        
				}
				var options="<option>Select UVC Number</option>";
				for(i = 0; i< uniqueNames.length; i++){    
					 options+="<option>"+uniqueNames[i]+"</option>";
				}
			$('.main-content').html('<div class="container pad-top"><div class="col-md-8 col-md-offset-2 "><div class="image-container text-center"> <img src="'+base_url+'/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Reorder / Additional Quantity</h4></div><form><div class="form-group select_div"> <select class="form-control" id="uvc_number" onchange=reorderartworks()>'+options+' </select></div></form><div class="how_it_works text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div class="list_div"><ul class="list-group"></ul></div></div></div>')
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
 * This Function is used to Show the existing artworks for selected uvc number.
 *
 * @return View
 */
function reorderartworks()
{
	
	var uvc=$('#uvc_number').val();
	var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
	if(uvc != "Select UVC Number")
	{
		$('.how_it_works').html("");
		$('.list_div').html('<ul class="list-group"></ul>');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/reorder-artwork",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				uvc:uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders)))))));
				$(newObj).each(function(key,index){
					if(index.Labeltype == "Label")
					{
						var span='<span class="label labels">'+index.Labeltype+'</span>';
					}
					else
					{
						var span='<span class="label sticker">'+index.Labeltype+'</span>';
					}

					if(index.TemplateType=="Main and Size Labels" || index.TemplateType=="Wash Care Labels")
					{
						var name=index.TemplateName+' - '+index.TemplateType;
					}
					else
					{
					   var name=index.TemplateName;
					}
						$('<li class="list-group-item"><div class="row"><div class="col-md-1"> <img src="'+base_url+'/images/price-tag.png" alt="Tag"></div><div class="col-md-2"><p class="uvc">UVC-'+uvc+'</p></div><div class="col-md-4"><p class="name">'+name+'</p></div><div class="col-md-2">'+span+' </div><div class="col-md-3"> <button class="btn btn-block btn-ghost" onclick=extraQuantity("'+index.type+'","'+index.id+'")>Extra Quantity</button></div></div></li>').appendTo('.list_div ul').show('slow')
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
		$('.how_it_works').html('<h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>');
		$('.list_div').html('<ul class="list-group"></ul>');
	}
	
}

/**
 * This Function is used to get the artwork and quantity information.
 *
 * @return View
 */

function extraQuantity(type,id)
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
			if(type == "orchestra_hang_tag_orders")
			{
				reorderHang(data);
				LabelQty(data)
			}
			else if(type == "orchestra_special_tag_orders")
			{
				reorderSpecial(data);
				specialTagQty(data)
			}
			else if(type == "orchestra_main_label_orders")
			{
				reorderMain(data);
				LabelQty(data)
			}
			else if(type == "orchestra_wash_care_label_orders")
			{
				reorderWash(data);
				LabelQty(data)
			}
			else if(type == "orchestra_solid_carton_sticker_orders")
			{
				reorderSolidCarton(data);
				SolidStickerQty(data,"Parcel")
			}
			else if(type == "orchestra_solid_poly_bag_sticker_orders")
			{
				reorderSolidPoly(data);
				SolidStickerQty(data,"Polybag")
			}
			else if(type == "orchestra_standard_carton_sticker_orders")
			{
				reorderStdCarton(data);
				StdStickerQty(data)
			}
			else if(type == "orchestra_standard_poly_bag_sticker_orders")
			{
				reorderStdPoly(data);
				StdStickerQty(data)
			}
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
 * This Function is used to Show the label quantity information.
 *
 * @return View
 */

function LabelQty(data)
{
	var tr="";
	$(data.qty).each(function(key,index){
		 tr += '<tr><td class="uvc_tco">' + index.uvc_tco + '</td><td class="size">' + index.size + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkNumbersOnly(event)" type="text" class="form-control  input-sm tot_qty_table" value="" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
	});
	$('.quantity').html('<table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px"> <thead> <tr> <th>UVC TCO#</th> <th>Size in ANS</th> <th><input type="text" class="form-control input-sm extra_percentage" onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th> </tr></thead> <tbody style="font-size: 11px">'+tr+'</tbody></table>')
}

/**
 * This Function is used to Show the Special tag quantity information.
 *
 * @return View
 */

function specialTagQty(data)
{
	$('.quantity').html('<div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm special_qty" value="0" onkeypress="return checkNumbersOnly(event)" placeholder="Special Tag Count" type="text" required=""><div class="help-block with-errors"></div></div>');
}

/**
 * This Function is used to Show the Solid Sticker quantity information.
 *
 * @return View
 */

function SolidStickerQty(data,name)
{
	if(name == "Parcel")
	{
		var qty = "Parcel Stickers Qty";
		var th= "Parcel Sticker Qty (in sets)";
	}
	else
	{
		var qty = "Polybag Stickers Qty";
		var th= "Polybag Sticker Qty incl. wastage";
	}
	var tr="";
	$(data.qty).each(function(key,index){
		 tr += '<tr><td class="size">' + index.size + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkNumbersOnly(event)" type="text" class="form-control  input-sm tot_qty_table" value="" required placeholder="'+qty+'"><div class="help-block with-errors"></div></div></td></tr>';
	});
	$('.quantity').html('<table class="table table-bordered table-responsive qty-table-modal" style="font-size:11px"> <thead> <tr> <th>Size</th> <th>'+th+'</th> </tr></thead> <tbody style="font-size: 11px">'+tr+'</tbody></table>')
}

/**
 * This Function is used to Show the Standard Sticker quantity information.
 *
 * @return View
 */

function StdStickerQty(data)
{
	
}

/**
 * This Function is used to Show the Hang tag template.
 *
 * @return View
 */

function reorderHang(data)
{
	$('.artwork_name').text("Special Tag");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 125px );" id="washcare_svg" class="center-block" data="'+data.template_front[0].template_file+'" type="image/svg+xml"></object><object style="padding-left:15px;display:inline;height: calc( 100vh - 125px );" id="washcare_svg" class="center-block" data="'+data.template_back[0].template_file+'" type="image/svg+xml"></object>');
}

/**
 * This Function is used to Show the Special tag template.
 *
 * @return View
 */

function reorderSpecial(data)
{
	$('.artwork_name').text("Special Tag");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 500px );" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}

/**
 * This Function is used to Show the Main and Size Labels template.
 *
 * @return View
 */

function reorderMain(data)
{
	$('.artwork_name').text("Main and Size Labels");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 500px );" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}

/**
 * This Function is used to Show the Wash care label template.
 *
 * @return View
 */

function reorderWash(data)
{
	$('.artwork_name').text("Wash Care Label");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 125px );" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}
/**
 * This Function is used to Show the Wash care label template.
 *
 * @return View
 */

function reorderSolidCarton(data)
{
	$('.artwork_name').text("Wash Care Label");
	$('.sample-artwork').html('<object style="height: calc( 100vh - 250px );border: 1px solid #333;display:inline;" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}
/**
 * This Function is used to Show the Wash care label template.
 *
 * @return View
 */

function reorderSolidPoly(data)
{
	$('.artwork_name').text("Wash Care Label");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 250px );border: 1px solid #333;" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}
/**
 * This Function is used to Show the Wash care label template.
 *
 * @return View
 */

function reorderStdCarton(data)
{
	$('.artwork_name').text("Wash Care Label");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 250px );border: 1px solid #333;" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}
/**
 * This Function is used to Show the Wash care label template.
 *
 * @return View
 */

function reorderStdPoly(data)
{
	$('.artwork_name').text("Wash Care Label");
	$('.sample-artwork').html('<object style="display:inline;height: calc( 100vh - 250px );border: 1px solid #333;" id="washcare_svg" class="center-block" data="'+data.template_file[0].template_file+'" type="image/svg+xml"></object>');
}