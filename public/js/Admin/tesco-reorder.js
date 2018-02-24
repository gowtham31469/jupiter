/**
 * This Function is used to Show Reorder Page
 *
 * @return View
 */
function reOrderTesco()
{
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	var buyer = localStorage.getItem("reorder_buyer");
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
				for(i = 0; i< newObj.length; i++){    
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
				var options="<option>Select Style Number</option>";
				for(i = 0; i< uniqueNames.length; i++){    
					 options+="<option>"+uniqueNames[i]+"</option>";
				}
			$('.main-content').html('<ul class="nav nav-tabs nav-justified"> <li class="nav-item active"> <a class="nav-link active" data-toggle="tab" href="#panel1" role="tab">Reorder / Additional Quantity</a> </li><li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#panel2" role="tab">Repeat Order</a> </li></ul><div class="tab-content card"> <div class="tab-pane fade in show active" id="panel1" role="tabpanel"><div class="container pad-top"><div class="col-md-8 col-md-offset-2 "><div class="image-container text-center"> <img src="'+base_url+'/images/repeat.png" alt="Repeat" class="img-rounded"><h4>Reorder / Additional Quantity</h4></div><form><div class="form-group select_div"> <select class="form-control" id="uvc_number" onchange=reordertescoartworks()>'+options+'</select></div></form><div class="how_it_works text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div class="list_div"><ul class="list-group"></ul></div></div></div> </div><div class="tab-pane fade" id="panel2" role="tabpanel"></div></div>')
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
	var buyer = localStorage.getItem("reorder_buyer");
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
				var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_polybag_sticker_orders)))))));
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