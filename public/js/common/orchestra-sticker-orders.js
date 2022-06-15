function orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po,user_type) {
   
    var tr = "";

    if (data.template_name == "Standard") {
		 var update_disable=0;
        var disabled = '';
        var ttotal = '';
		var itemtotal = 0;
        var thead = '<thead><tr><th>UVC_TCO	</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th><th>Size</th><th>Ratio</th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="barcode">' + data.orchestra_parcel_details[0].ParcelBarCode + '</td><td class="size">' + value.Size + '</td><td class="qty">' + value.Sum + '</td><input type="hidden" class="size_break" value="'+value.Size_Breakdown+'"></tr>';
			itemtotal += value.Sum ;

        });



        if (data.orchestra_standard_poly_bag_sticker_orders != "") {
            var edit_disable = 'disabled="disabled"';
           
            if (data.orchestra_standard_poly_bag_sticker_orders[0].height == null) {
                $height = "";
            } else {
                $height = data.orchestra_standard_poly_bag_sticker_orders[0].height;
            }
			
			if (data.orchestra_standard_carton_sticker_orders[0].width == null) {
                $width = "";
            } else {
                $width = data.orchestra_standard_carton_sticker_orders[0].width;
            }
			
            $age = data.orchestra_standard_poly_bag_sticker_orders[0].group_id;
			
			if($height)
			{
				var num = 600 * $width * $height
				var val = num / (1000 * 1000 * 1000)
				$parcelVolume = val;
			}
			else
			{
				$parcelVolume = "";
			}
			
			
			if(data.orchestra_standard_poly_bag_sticker_orders[0].no_of_carton != "" && data.orchestra_standard_poly_bag_sticker_orders[0].no_of_carton != null)
			{
				$no_of_carton = data.orchestra_standard_poly_bag_sticker_orders[0].no_of_carton;
			}
			else
			{
				$no_of_carton = "";
			}
            
			
			if(data.orchestra_standard_poly_bag_sticker_orders[0].total_polybag != null && data.orchestra_standard_poly_bag_sticker_orders[0].total_polybag != "")
			{
				$total_polybag = data.orchestra_standard_poly_bag_sticker_orders[0].total_polybag;
			}
			else
			{
				$total_polybag = "";
			}
            
            $final_order_qty_sets = data.orchestra_standard_poly_bag_sticker_orders[0].final_order_qty_sets;
            $order_qty = data.orchestra_standard_poly_bag_sticker_orders[0].order_qty;
            $final_order_qty = data.orchestra_standard_poly_bag_sticker_orders[0].final_order_qty;
			update_disable=1;	
        } else {
            
            var edit_disable = '';
            $height = "";
			$width = 395;
			$parcelVolume = "";
            $age = 0;
            $no_of_carton = "";
            $total_polybag = "";
            $final_order_qty_sets = "";
            $order_qty = itemtotal;
            $final_order_qty = "";
        }
		
		if (data.orchestra_standard_poly_bag_sticker_orders != "") {
			update_disable=1;
		}

	
        var head_qty = "Quantity for Standard Stickers";
        var head_qty1 = "Standard Parcel/Carton Sticker";
        var standard_div = '<input type="hidden" id="carton_hidden" value="standard"><div class="form-group"><label>Total Polybags per carton: </label><div class="form-group has-feedback has-success"><input id="total_polybag" '+edit_disable+' value="' + $total_polybag + '" onkeypress="return checkNumbersOnly(event)" type="text" placeholder="Total Polybags per carton" class="form-control input-sm"><div class="help-block with-errors"></div></div></div> <div class="form-group"><label>Final Order Qty including Wastage: </label><div class="form-group has-feedback has-success"><input '+edit_disable+' required min="'+data.polybag_moq+'" id="final_order_qty_sets" value="' + $final_order_qty_sets + '" type="number" onkeypress="return checkNumbersOnly(event)" placeholder="" class="form-control input-sm"><div class="help-block with-errors"></div></div></div><hr><h4>Standard Polybag Sticker</h4><div class="form-group"><label>Order Quantity</label><input id="order_qty" '+edit_disable+' value="' + $order_qty + '" onkeypress="return checkNumbersOnly(event)" type="text" class="form-control input-sm"></div><div class="form-group"><label>Final Order Qty including Wastage</label><div class="form-group has-feedback has-success"><input '+edit_disable+' required min="'+data.carton_moq+'" id="final_order_qty" value="' + $final_order_qty + '" onkeypress="return checkNumbersOnly(event)" type="number" class="form-control input-sm"><div class="help-block with-errors"></div></div></div>';
		var po_file_upload_button="po-file-upload-button-standard";
		if(update_disable == 0)
		{
			var update_disabel_button = "display:none";
			var save_hide_button = "";
			var carton_print_hidden = "hidden";
			var polybag_print_hidden = "hidden";
			var cancel_hide_button = "hidden";
		}
		else
		{
			var update_disabel_button = "";
			var save_hide_button = "hidden";
			var carton_print_hidden = "";
			var polybag_print_hidden = "";
			var cancel_hide_button = "";
		}
		//alert(save_hide_button)
    } else {
		var update_disable=0;
        $no_of_carton = "";
        var disabled = 'disabled="disabled"';
        var tpolytot = 0;
        var tcarttot = 0;
		var po_file_upload_button="po-file-upload-button-solid";
        var itemtotal=0;
        
        if (data.orchestra_solid_carton_sticker_orders != "") {
           
            var edit_disable = 'disabled="disabled"';
            if (data.orchestra_solid_carton_sticker_orders[0].height == null) {
                $height = "";
            } else {
                $height = data.orchestra_solid_carton_sticker_orders[0].height;
            }
			
			
			if (data.orchestra_solid_carton_sticker_orders[0].width == null) {
                $width = "";
            } else {
                $width = data.orchestra_solid_carton_sticker_orders[0].width;
            }
            $age = data.orchestra_solid_poly_bag_sticker_orders[0].group_id;
			
			if($height)
			{
				var num = 600 * $width * $height
				var val = num / (1000 * 1000 * 1000)
				$parcelVolume = val;
			}
			else
			{
				$parcelVolume = "";
			}
			
			update_disable=1;
			$save_hide_button="hidden";
			var cancel_hide_button = "";

        } else {
            
            var edit_disable = '';
            $height = "";
			$width = 395;
            $parcelVolume = "";
            $age = 0;
			$save_hide_button="";
			var cancel_hide_button = "hidden";
        }
		if (data.orchestra_solid_poly_bag_sticker_orders != "") {
			update_disable=1;
		}
		
		
        var thead = '<thead><tr><th>UVC_TCO	</th><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty</th><th>Parcel Sticker Qty</th></tr></thead>';
        $qu = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_carton_sticker_orders != "") {
                $carton_count = data.orchestra_solid_carton_sticker_orders[0].qty[$qu].quantity;
            } else {
                $carton_count = "";
            }

            if (data.orchestra_solid_poly_bag_sticker_orders != "") {
                $poly_count = data.orchestra_solid_poly_bag_sticker_orders[0].qty[$qu].quantity;
            } else {
                $poly_count = "";
            }

            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Sum + '</td><td><input type="hidden" class="SKU_Code" value="' + value.SKU_Code + '"><div class="form-group has-feedback has-success"><input style="margin-bottom: 0px;" type="number" '+edit_disable+' required min="'+data.polybag_moq+'" onkeypress="return checkorcMOQ(this)" value="' + $poly_count + '" class="form-control tot_qty_table_poly input-sm"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback has-success"><input  min="'+data.carton_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)"  required type="number" '+edit_disable+' value="' + $carton_count + '" class="form-control tot_qty_table_carton input-sm"><div class="help-block with-errors"></div></div></td><input type="hidden" class="size_break" value="'+value.Size_Breakdown+'"></tr>';
            if($poly_count != "")
			{
				 tpolytot += parseInt($poly_count);
			}
			if($carton_count != "")
			{
				tcarttot += parseInt($carton_count);
			}
			itemtotal += value.Sum ;
            $qu++;
        });

        var ttotal = '<td class="text-center final_solid_poly_qty">' + tpolytot + '</td><td class="text-center final_solid_carton_qty">' + tcarttot + '</td>';

        

        var head_qty = "Quantity for Solid Size Stickers";
        var head_qty1 = "Solid Size Parcel/Carton Sticker";
        var standard_div = '<input type="hidden" id="carton_hidden" value="solid">';
		if(update_disable == 0)
		{
			var update_disabel_button = "display:none";
			var save_hide_button = "";
			var carton_print_hidden = "hidden";
			var polybag_print_hidden = "hidden";
		}
		else
		{
			var update_disabel_button = "";
			var save_hide_button = "hidden";
			var carton_print_hidden = "";
			var polybag_print_hidden = "";
		}
    }

    var option = "<option>Age Group</option>";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
    
    if(localStorage.getItem("page_navigation") == "orders")
    {
        var onclick = "orders()";
    }
    else
    {
        var onclick = "orderStatus('"+user_type+"')";
    }


    if(user_type == "admin")
    {
       var artwork_button = '<button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_print_button" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Generate Proof</button><button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_proof_button" onclick=proofDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Generate Print</button>';

       var carton_artwork_button = '<button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_print_button" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Generate Proof</button><button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_proof_button" onclick=proofDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Generate Print</button>';
    }
    else
    {
        var artwork_button = '<button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_print_button" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Download</button>';

        var carton_artwork_button = '<button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_print_button" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'")>Download</button>';
    }
    
    $('.top-content').append('<form id="solid_carton_form"><h3><i class="ion-ios-browsers-outline"></i> Sticker Order Details for UVC ' + uvc + ' and PO ' + po + '</h3><hr/><h5><div style="margin-bottom: 10px;"><label style="color:red" class="age_group_error hidden">Please Select Age Group</label></div><span class="sticker-details">Supplier: <strong><span id="supplier">' + data.orchestra_parcel_details[0].SupplierName + '</span></strong> </span> <span class="sticker-details">Style: <strong><span id="style">' + data.orchestra_parcel_details[0].NomApple + '</span></strong></span><span class="sticker-details">Color: <strong><span id="color">' + data.orchestra_parcel_details[0].colour_Code + '</span></strong></span><span class="sticker-details" style="display: inline-flex; white-space: nowrap;">Age Group: <strong><span id="ageGroup">  <select class="form-control" '+edit_disable+' style="margin-top: -9px; margin-left: 10px;" name="carlist" id="age" form="carform">' + option + '</select></span></strong> </span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>' + head_qty + '</h4><div class="table-responsive"><table class="table table-condensed table-bordered table-hover" id="solidform">' + thead + '<tbody id="solidformtbody">' + tr + '</tbody><tfoot><tr class="strong"><td colspan="3" class="text-right">Total</td><td>' + itemtotal + '</td>' + ttotal + '</tr></tfoot></table><div class="bottom-bar"><div class="modal-footer"> <button onclick="'+onclick+'" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button> <button type="submit" id="save_sticker_button" class="btn btn-xs btn-success '+save_hide_button+'"><i class="fa fa-floppy-o"></i>Save</button><button type="button" class="btn btn-success  pull-right btn-xs" data-toggle="modal" id="'+po_file_upload_button+'" style="'+update_disabel_button+'" >Update PO</button><button type="button" id="cancel_sticker_button" onclick=cancelStickerDiv() class="btn btn-xs hidden btn-danger '+cancel_hide_button+'"><i class="fa fa-floppy-o"></i>Cancel</button></div></div></form></div></div><div class="col-md-4"><h4>' + head_qty1 + '</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" disabled value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" onkeypress="return checkNumbersOnly(event)" value="' + $width + '" class="form-control input-sm" id="width" '+edit_disable+' placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" '+edit_disable+' placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume">' + $parcelVolume + '</span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6" style="width: 130px;"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left:-135px;">/</span> <div class="col-md-6" style="width: 130px;"><input ' + disabled + ' data-toggle="modal" '+edit_disable+' value="' + $no_of_carton + '" id="needed_modal" data-backdrop="static" data-keyboard="false" onkeypress="return checkNumbersOnly(event)" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div>' + standard_div + ' </div></div>');
    $('#solid_carton_form');

    $('.main-content').html('<input type="hidden" value="' + data.std_carton_id + '" id="std_carton_id"><input type="hidden" value="' + data.std_poly_id + '" id="std_poly_id"><input type="hidden" value="' + data.carton_id + '" id="carton_id"><input type="hidden" value="' + data.poly_id + '" id="poly_id"><input type="hidden" value="' + data.carton_template_id + '" id="carton_template_id"><input type="hidden" value="' + data.polybag_template_id + '" id="polybag_template_id"><input type="hidden" value="' + uvc + '" id="uvc_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].Season_Code + '" id="season_code"><input type="hidden" value="' + data.orchestra_parcel_details[0].Color_Code + '" id="color_code"><input type="hidden" value="' + po + '" id="po"><hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right" style="cursor:pointer">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px; border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:575px;height:378;border: 2px #000 solid;" class="center-block" data="' + data.carton_template + '" type="image/svg+xml"></object></div><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div><div class="modal-footer">'+carton_artwork_button+'</div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" style="cursor:pointer">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:500px;height:330px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object> </div><div class="modal-footer">'+artwork_button+'</div></div></div></div> </div> </div> </div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

    $('#solid_carton_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSolidStickers()
        }
    })
	
	
	$('.bs-example-modal-lg2').on('shown.bs.modal', function (e) {
	  	var mySVG1 = document.getElementById("svg4");
		svgDoc1 = mySVG1.contentDocument;
		if($('#age option:selected').val() != 6)
		{
			svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
			svgDoc1.getElementById("pmm").setAttribute('visibility','hidden');
			svgDoc1.getElementById("pmm").innerHTML = "";
		}
		else
		{
			svgDoc1.getElementById("round").style.fill = "#FFFFFF"
			svgDoc1.getElementById("pmm").removeAttribute('visibility','hidden');
			svgDoc1.getElementById("pmm").innerHTML = "PMM";
		}
        
	})

	
	$('#po-file-upload-button-solid').click(function () {

		$('#new_po_div').addClass('hidden')
		$('#map_po_div').addClass('hidden')
		$('#exist_artwork_div').removeClass('hidden')
		$('#update_new_po').addClass('hidden')
		$('#new_po1').removeClass('hidden')
		$('#map_po').removeClass('hidden')
		$('#new_po_no').val("")
		$('#new_combined_delivery_date').val("")
		document.getElementById("new_po_file").value=null; 
		$('#new_po_error').addClass('hidden')
		$('#new_po_file_error').addClass('hidden')

		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/get-existing-solid-sticker-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val(),
				po : $('#po').val()
			},
			beforeSend: function () {

			},
			success: function (data) {

				var order_date = data.orchestra_solid_carton_sticker_orders[0].created_at
				var today = new Date(order_date);
				$('#datePicker3').datepicker({
					autoclose: true,
					startDate: today,
					format: 'yyyy-mm-dd',
				})

				$('#po-file-upload-div').modal('show')
				$('#po-file-upload-div').find('#exist_artwork_div ul').html("");
				var newObj = data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders);
				var coun = 0;
				var options = "<option>Select PO</option>";
				$(newObj).each(function (index1, value1) {
					
					if (value1.po_number != "" && value1.po_number != null) {
						options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
						$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="check-list">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
						coun++;
					}
					else
					{
						$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
					}
					
				});
				
				
				
				//Start bootstrap checkbox 
				
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})
				
				//End bootstrap checkbox 
				
                $('#selectpo').html('<label>Select PO</label><select id="select_po" style="width:96%" class="form-control">' + options + '</select>')
                $('#map_po').addClass('hidden');
                $('#new_po1').addClass('hidden');
                $('#new_po_div').removeClass('hidden');
                $('#update_new_po').removeClass('hidden');
				
				$('#update_new_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#new_po_no').val() != "" && $('#new_po_file').val() != "") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var new_po_file = $('#new_po_file').prop('files')[0];
						var new_po_no = $('#new_po_no').val();
						var combined_delivery_date = $('#new_combined_delivery_date').val();
						
						var form_data = new FormData();
						form_data.append('id', aa);
						form_data.append('po_file', new_po_file);
						form_data.append('combined_delivery_date', combined_delivery_date);
						form_data.append('po', new_po_no);
						form_data.append('_token', csrf_token);
						$.ajax({
							url: base_url + "/"+user_type+"/" + buyer + "/order-management/update-label-orders-po",
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							success: function (data) {
								//location.reload()
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function () {
								console.log('somethig is wrong, please try later');
							}

						});
					} else if ($('#new_po_no').val() == "" && $('#new_po_file').val() != ""){
						$('#new_po_error').removeClass('hidden')
						$('#new_po_file_error').addClass('hidden')
					} else if ($('#new_po_no').val() != "" && $('#new_po_file').val() == ""){
						$('#new_po_file_error').removeClass('hidden')
						$('#new_po_error').addClass('hidden')
					} else if ($('#new_po_no').val() == "" && $('#new_po_file').val() == ""){
						$('#new_po_error').removeClass('hidden')
						$('#new_po_file_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}


				});

				$('#update_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#select_po').val() != "Select PO") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						
						$.ajax({
							url: base_url + "/"+user_type+"/" + buyer + "/order-management/map-label-orders-po",
							type: "Post",
							dataType: "json",
							data: {
								_token: csrf_token,
								id: aa,
								po: $('#select_po').val(),
								po_file:$('#select_po option:selected').attr('data-id')
							},
							beforeSend: function () {

							},
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function (data) {

							},
							complete: function () {
								$(".loader").hide();
							}
						})
					} else {
						$('#select_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#new_po1').click(function () {
					

					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_new_po').removeClass('hidden')
					$('#update_po').addClass('hidden')
						$('#new_po_div').removeClass('hidden')
						$('#map_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#map_po').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_po').removeClass('hidden')
					$('#update_new_po').addClass('hidden')
						$('#map_po_div').removeClass('hidden')
						$('#new_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
						$('#check_error').addClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}



				});
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});
	
	
	$('#po-file-upload-button-standard').click(function () {

		$('#new_po_div').addClass('hidden')
		$('#map_po_div').addClass('hidden')
		$('#exist_artwork_div').removeClass('hidden')
		$('#update_new_po').addClass('hidden')
		$('#new_po1').removeClass('hidden')
		$('#map_po').removeClass('hidden')
		$('#new_po_no').val("")
		$('#new_combined_delivery_date').val("")
		document.getElementById("new_po_file").value=null; 
		$('#new_po_error').addClass('hidden')
		$('#new_po_file_error').addClass('hidden')

		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/get-existing-standard-sticker-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val(),
				po : $('#po').val()
			},
			beforeSend: function () {

			},
			success: function (data) {

				var order_date = data.orchestra_standard_carton_sticker_orders[0].created_at
				var today = new Date(order_date);
				$('#datePicker3').datepicker({
					autoclose: true,
					startDate: today,
					format: 'yyyy-mm-dd',
				})

				$('#po-file-upload-div').modal('show')
				$('#po-file-upload-div').find('#exist_artwork_div ul').html("");
				var newObj = data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders);
				var coun = 0;
				var options = "<option>Select PO</option>";
				$(newObj).each(function (index1, value1) {
					
					if (value1.po_number != "" && value1.po_number != null) {
						options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
						$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="check-list">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
						coun++;
					}
					else
					{
						$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
					}
					
				});
				
				
				
				//Start bootstrap checkbox 
				
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})
				
				//End bootstrap checkbox 
				
                $('#selectpo').html('<label>Select PO</label><select id="select_po" style="width:96%" class="form-control">' + options + '</select>')
                $('#map_po').addClass('hidden');
                $('#new_po1').addClass('hidden');
                $('#new_po_div').removeClass('hidden');
                $('#update_new_po').removeClass('hidden');
				
				$('#update_new_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#new_po_no').val() != "" && $('#new_po_file').val() != "") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var new_po_file = $('#new_po_file').prop('files')[0];
						var new_po_no = $('#new_po_no').val();
						var combined_delivery_date = $('#new_combined_delivery_date').val();
						
						var form_data = new FormData();
						form_data.append('id', aa);
						form_data.append('po_file', new_po_file);
						form_data.append('combined_delivery_date', combined_delivery_date);
						form_data.append('po', new_po_no);
						form_data.append('_token', csrf_token);
						$.ajax({
							url: base_url + "/"+user_type+"/" + buyer + "/order-management/update-label-orders-po",
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function () {
								console.log('somethig is wrong, please try later');
							}

						});
					} else if ($('#new_po_no').val() == "" && $('#new_po_file').val() != ""){
						$('#new_po_error').removeClass('hidden')
						$('#new_po_file_error').addClass('hidden')
					} else if ($('#new_po_no').val() != "" && $('#new_po_file').val() == ""){
						$('#new_po_file_error').removeClass('hidden')
						$('#new_po_error').addClass('hidden')
					} else if ($('#new_po_no').val() == "" && $('#new_po_file').val() == ""){
						$('#new_po_error').removeClass('hidden')
						$('#new_po_file_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}


				});

				$('#update_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#select_po').val() != "Select PO") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						
						$.ajax({
							url: base_url + "/"+user_type+"/" + buyer + "/order-management/map-label-orders-po",
							type: "Post",
							dataType: "json",
							data: {
								_token: csrf_token,
								id: aa,
								po: $('#select_po').val(),
								po_file:$('#select_po option:selected').attr('data-id')
							},
							beforeSend: function () {

							},
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function (data) {

							},
							complete: function () {
								$(".loader").hide();
							}
						})
					} else {
						$('#select_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#new_po1').click(function () {
					

					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_new_po').removeClass('hidden')
					$('#update_po').addClass('hidden')
						$('#new_po_div').removeClass('hidden')
						$('#map_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#map_po').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_po').removeClass('hidden')
					$('#update_new_po').addClass('hidden')
						$('#map_po_div').removeClass('hidden')
						$('#new_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
						$('#check_error').addClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}



				});
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});


    $('.tot_qty_table_poly').keyup(function () {
        var tot_qty_table_poly_final = 0;
        $('#solidform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_poly').val() != undefined && $(this).find('.tot_qty_table_poly').val() != "") {
                tot_qty_table_poly_final += parseInt($(this).find('.tot_qty_table_poly').val());
            }

        })
        $('.final_solid_poly_qty').text(tot_qty_table_poly_final);
    });

    $('.tot_qty_table_carton').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#solidform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_carton').val() != undefined && $(this).find('.tot_qty_table_carton').val() != "") {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table_carton').val());
            }
        })
        $('.final_solid_carton_qty').text(tot_qty_table_carton_final);
    });

    console.log(user_type)

    if(user_type == "admin")
    {
        barcode_carton('svg2', data,"")
        barcode_carton('svg3', data,"")
        barcode_polybag('svg1', data,"")
        barcode_polybag('svg4', data,"")
    }
    else
    {
        barcode_carton('svg2', data,"")
        barcode_carton('svg3', data,"JGA")
        barcode_polybag('svg1', data,"")
        barcode_polybag('svg4', data,"JGA")
    }
    

	$("#modal_height").one('focus', function(){
		$('.bs-example-modal-sm').modal("show")
	});

	$("#needed_modal").one('focus', function(){
		$('.needed_modal').modal("show")
	});

	
    $("#modal_height").keyup(function () {
		if(this.value)
		{
			var num = $('#length').val() * $('#width').val() * this.value
			var val = num / (1000 * 1000 * 1000)
			$('#parcelVolume').text(val)
		}
		else
		{
			var val = ""
		}
        
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("volume").innerHTML = val;
        svgDoc1.getElementById("height").innerHTML = this.value;
    });  
	$("#total_polybag").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("total_polybags_per_carton").innerHTML = this.value;
    });
	$("#width").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("width").innerHTML = this.value;
    });
	$("#needed_modal").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("no_of_cartons").innerHTML = this.value;
    });
    $("#modal_close").click(function () {
        $('#modal_height').removeAttr('data-target')
    });
    $(".modal_close1").click(function () {
        $('#needed_modal').removeAttr('data-target')
    });
    if ($age != 0) {
        $('#age').val($age);
        var mySVG1 = document.getElementById("svg1");
        var svgDoc1;
        mySVG1.addEventListener("load", function () {
			svgDoc1 = mySVG1.contentDocument;
			
			if($('#age option:selected').val() != 6)
			{
				svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
				svgDoc1.getElementById("pmm").setAttribute('visibility','hidden');
				svgDoc1.getElementById("pmm").innerHTML = "";
			}
			else
			{
				svgDoc1.getElementById("round").style.fill = "#FFFFFF"
				svgDoc1.getElementById("pmm").removeAttribute('visibility','hidden');
				svgDoc1.getElementById("pmm").innerHTML = "PMM";
			}

        }, false);

        var mySVG = document.getElementById("svg4");
        var svgDoc;
        mySVG.addEventListener("load", function () {
			svgDoc = mySVG.contentDocument;
			
			if($('#age option:selected').val() != 6)
			{
				svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
				svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
				svgDoc1.getElementById("pmm").innerHTML = "";
			}
			else
			{
				svgDoc.getElementById("round").style.fill = "#FFFFFF"
				svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
				svgDoc1.getElementById("pmm").innerHTML = "PMM";
			}

            
        }, false);

    }


    $('#age').change(function () {
        if($('#age option:selected').val() != "Age Group")
		{
            $('#age_modal').modal("show");
        }
        
        var mySVG1 = document.getElementById("svg1");
        svgDoc1 = mySVG1.contentDocument;
        if($('#age option:selected').val() != 6)
		{
			svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
			svgDoc1.getElementById("pmm").setAttribute('visibility','hidden');
            svgDoc1.getElementById("pmm").innerHTML = "";
		}
		else
		{
			svgDoc1.getElementById("round").style.fill = "#FFFFFF"
			svgDoc1.getElementById("pmm").removeAttribute('visibility','hidden');
            svgDoc1.getElementById("pmm").innerHTML = "PMM";
		}
	});
	

	if (data.template_name == "Standard") {

		if(data.orchestra_standard_carton_sticker_orders[0].po_status == "PO Uploaded")
		{
			$('#po-file-upload-button-standard').remove()
		}
		else
		{
			$('#po-file-upload-button-standard').addClass('hidden')
		}
	}
	else{

		if(data.orchestra_solid_poly_bag_sticker_orders[0].po_status == "PO Uploaded")
		{
			$('#po-file-upload-button-solid').remove()
		}
		else
		{
			$('#po-file-upload-button-solid').addClass('hidden')
		}
	}

}



/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */

function barcode_carton(id, data,watermark) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("watermark").innerHTML = watermark;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = $('#length').val();
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		if($('#parcelVolume').text())
		{
			svgDoc.getElementById("volume").innerHTML = parseFloat($('#parcelVolume').text()).toFixed(3);
		}
		else
		{
			svgDoc.getElementById("volume").innerHTML = ""
		}
		
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;

		if (data.template_name == "Standard") {
			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
			jsbarcodestdcartonA(code, 'barcode1', svgDoc)
			jsbarcodestdcartonB(code, 'barcode2', svgDoc)
			svgDoc.getElementById("total_polybags_per_carton").innerHTML = $('#total_polybag').val();
			svgDoc.getElementById("standardcolor").style.fill = "#fbfb08"
			svgDoc.getElementById("no_of_cartons").innerHTML = $('#needed_modal').val();
		} else {
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			if(data.orchestra_parcel_details[0].Size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-11" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-28" dy="9" text-anchor="middle">UNIQUE</tspan>';
		    }
			else
			{
				svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			}
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			jsbarcodesolidcartonA(code, 'barcode1', svgDoc)
			jsbarcodesolidcartonB(code, 'barcode2', svgDoc)
		}

		
		


	}, false);

}


/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_polybag(id, data,watermark) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("watermark").innerHTML = watermark;
		var svgItem = svgDoc.getElementById("uvc_tco");
//alert(data.orchestra_parcel_details[0].ParcelCode);
		
		//svgDoc.getElementById("round").style.fill = $('#age').val();

		if (data.template_name == "Standard") {
			svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;

			$td_col = "";
			$td_size = "";
			$total = 0;
			var index_arr=[];
			$('#solidform tbody tr').each(function () {
				
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						$td_size += "<td>" + $(this).find('.size').text() + "</td>";
						$td_col += "<td>" + $(this).find('.size_break').val() + "</td>";
						$total += parseInt($(this).find('.size_break').val());
				}
			}
				


			})
			if(data.orchestra_parcel_details[0].colour_Code.length  > 6)
			{
				var fon_siz = "style='font-size:6px'";
			}
			else
			{
				var fon_siz = "style='font-size: 8px;'";
			}
			$tbl = '<tr '+fon_siz+'><td>Size:</td>' + $td_size + '<td>Total</td></tr><tr '+fon_siz+'> <td>'+data.orchestra_parcel_details[0].colour_Code+'</td>' + $td_col + '<td>' + $total + '</td></tr>';

			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
			svgDoc.getElementById("cartontable").innerHTML = $tbl;
			jsbarcodestdpoly(code, 'barcode', svgDoc)
		} else {
			svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			if(data.orchestra_parcel_details[0].Size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
				svgDoc.getElementById("size").style.fontSize = "15px"
				svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 174.625 28.7874)');
				svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 175.625 45.3324)');
		    }
			else
			{
				svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			}
			svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			jsbarcodesolidpoly(code, 'barcode', svgDoc)

		}
		
	}, false);

}