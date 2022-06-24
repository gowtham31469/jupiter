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
			
			if (data.orchestra_standard_carton_sticker_orders[0].ocn_number == null) {
                $ocn_number = "";
            } else {
                $ocn_number = data.orchestra_standard_carton_sticker_orders[0].ocn_number;
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
			$ocn_number = "";
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
			
			if (data.orchestra_solid_carton_sticker_orders[0].ocn_number == null) {
                $ocn_number = "";
            } else {
                $ocn_number = data.orchestra_solid_carton_sticker_orders[0].ocn_number;
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
			$ocn_number = "";
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
        var onclick = "orders('"+user_type+"')";
    }
    else
    {
        var onclick = "orderStatus('"+user_type+"')";
    }


    if(user_type == "admin")
    {
       var artwork_button = '<button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_print_button" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","admin")>Generate Proof</button><button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_proof_button" onclick=proofDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","admin")>Generate Print</button>';

       var carton_artwork_button = '<button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_print_button" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","admin")>Generate Proof</button><button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_proof_button" onclick=proofDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","admin")>Generate Print</button>';
    }
    else
    {
        var artwork_button = '<button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_print_button" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","customer")>Download</button>';

        var carton_artwork_button = '<button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_print_button" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'","'+po+'","customer")>Download</button>';
    }
    
    $('.top-content').append('<form id="solid_carton_form"><h3><i class="ion-ios-browsers-outline"></i> Sticker Order Details for UVC ' + uvc + ' and PO ' + po + '</h3><hr/><h5><div style="margin-bottom: 10px;"><label style="color:red" class="age_group_error hidden">Please Select Age Group</label></div><span class="sticker-details">Supplier: <strong><span id="supplier">' + data.orchestra_parcel_details[0].SupplierName + '</span></strong> </span> <span class="sticker-details">Style: <strong><span id="style">' + data.orchestra_parcel_details[0].NomApple + '</span></strong></span><span class="sticker-details">Color: <strong><span id="color">' + data.orchestra_parcel_details[0].colour_Code + '</span></strong></span><span class="sticker-details" style="display: inline-flex; white-space: nowrap;">Age Group: <strong><span id="ageGroup">  <select class="form-control" '+edit_disable+' style="margin-top: -9px; margin-left: 10px;" name="carlist" id="age" form="carform">' + option + '</select></span></strong> </span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>' + head_qty + '</h4><div class="table-responsive"><table class="table table-condensed table-bordered table-hover" id="solidform">' + thead + '<tbody id="solidformtbody">' + tr + '</tbody><tfoot><tr class="strong"><td colspan="3" class="text-right">Total</td><td>' + itemtotal + '</td>' + ttotal + '</tr></tfoot></table><div class="bottom-bar"><div class="modal-footer"> <button onclick="'+onclick+'" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button> <button type="submit" id="save_sticker_button" class="btn btn-xs btn-success '+save_hide_button+'"><i class="fa fa-floppy-o"></i>Save</button><button type="button" class="btn btn-success  pull-right btn-xs" data-toggle="modal" id="'+po_file_upload_button+'" style="'+update_disabel_button+'" >Update PO</button><button type="button" id="cancel_sticker_button" onclick=cancelStickerDiv(`'+user_type+'`) class="btn btn-xs hidden btn-danger '+cancel_hide_button+'"><i class="fa fa-floppy-o"></i>Cancel</button></div></div></form></div></div><div class="col-md-4"><h4>' + head_qty1 + '</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" disabled value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" onkeypress="return checkNumbersOnly(event)" value="' + $width + '" class="form-control input-sm" id="width" '+edit_disable+' placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false" '+edit_disable+' placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume">' + $parcelVolume + '</span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6" style="width: 130px;"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left:-135px;">/</span> <div class="col-md-6" style="width: 130px;"><input ' + disabled + ' data-toggle="modal" '+edit_disable+' value="' + $no_of_carton + '" id="needed_modal" data-backdrop="static" data-keyboard="false" onkeypress="return checkNumbersOnly(event)" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div><div class="form-group"> <label>OCN Number</label><div class="row"><div class="col-md-6"><input type="text" '+edit_disable+' value="'+$ocn_number+'" id="ocn_number"  placeholder="OCN Number" class="form-control input-sm" placeholder=""></div></div></div>' + standard_div + ' </div></div>');
    $('#solid_carton_form');

    $('.main-content').html('<input type="hidden" value="' + data.std_carton_id + '" id="std_carton_id"><input type="hidden" value="' + data.std_poly_id + '" id="std_poly_id"><input type="hidden" value="' + data.carton_id + '" id="carton_id"><input type="hidden" value="' + data.poly_id + '" id="poly_id"><input type="hidden" value="' + data.carton_template_id + '" id="carton_template_id"><input type="hidden" value="' + data.polybag_template_id + '" id="polybag_template_id"><input type="hidden" value="' + uvc + '" id="uvc_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].Season_Code + '" id="season_code"><input type="hidden" value="' + data.orchestra_parcel_details[0].Color_Code + '" id="color_code"><input type="hidden" value="' + po + '" id="po"><hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right" style="cursor:pointer">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px; border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:575px;height:378;border: 2px #000 solid;" class="center-block" data="' + data.carton_template + '" type="image/svg+xml"></object></div><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div><div class="modal-footer">'+carton_artwork_button+'</div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" style="cursor:pointer">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:500px;height:330px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object> </div><div class="modal-footer">'+artwork_button+'</div></div></div></div> </div> </div> </div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

    $('#solid_carton_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSolidStickers(user_type)
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

		if(data.orchestra_standard_carton_sticker_orders.length > 0)
		{
			if(data.orchestra_standard_carton_sticker_orders[0].po_status == "PO Uploaded")
			{
				$('#po-file-upload-button-standard').remove()
			}
			else
			{
				$('#po-file-upload-button-standard').addClass('hidden')
			}
		}
		
	}
	else{
		if(data.orchestra_solid_poly_bag_sticker_orders.length > 0)
		{
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

function SolidCartonStickersOs(data,user_type)
{
	var tr = "";
	$qu = 0;
	
	if(user_type == "customer")
	{
		var enable_button = "";
		var custom_url = "customer";
	}
	else
	{
		var enable_button = '<button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_solid_carton_sticker_orders","'+ data.orchestra_solid_carton_sticker_orders[0].id +'")>Enable Edit</button>';
		var custom_url = "admin";
	}
    if (data.orchestra_solid_carton_sticker_orders != "") {
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
		
        $template_id = data.orchestra_solid_carton_sticker_orders[0].template_id;
        $exist_id = data.orchestra_solid_carton_sticker_orders[0].id;
        $solid_carton_count=data.orchestra_solid_carton_sticker_orders[0].final_qty;
        $ocn_number=data.orchestra_solid_carton_sticker_orders[0].ocn_number;
        
        var str=data.orchestra_solid_carton_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_solid_carton_sticker_orders[0].group_id;
		var edit_disable = 'disabled="disabled"';
    } else {
     	$height = "";
     	$width = 395;
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $solid_carton_count="";
		$status="";
		$ocn_number="";
        $status_text="";
        $save_button_class="";
		$age =0;
		var edit_disable = '';
    }
		var tpolytot = 0;
        var tcarttot = 0;
        var ttots = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_carton_sticker_orders != "") {
                $carton_count = data.orchestra_solid_carton_sticker_orders_qty[$qu].quantity;
            } else {
                $carton_count = "";
            }

            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Quantity + '</td><td class="text-center"><div class="form-group has-feedback has-success"><input '+edit_disable+' min="'+data.carton_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)"  required type="number" value="' + $carton_count + '" class="form-control tot_qty_table_carton input-sm"><div class="help-block with-errors"></div></div></td></tr>';
			if($carton_count != "")
			{
				tcarttot += parseInt($carton_count);
			}
			ttots += parseInt(value.Quantity)
            $qu++;
        });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.edit-orch-order-panel').append('<form id="solid_cartonform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" '+edit_disable+' class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label">Height</label><div class="input-group"> <input type="text" '+edit_disable+' value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div><div class="form-group"><label>Width</label><div class="input-group"><input type="text" '+edit_disable+' onkeypress="return checkNumbersOnly(event)" value="' + $width + '" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div><div class="form-group"><label>OCN Number</label><input type="text" '+edit_disable+' value="' + $ocn_number + '" class="form-control input-sm" id="ocn_number" placeholder="OCN Number"> </div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Solid Carton Stickers</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="solid_carton_table"><thead><tr><th>UVC_TCO</th><th>Size</th><th>Barcode</th><th>Item Qty</th><th>Parcel Sticker Qty</th></tr></thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots + '</td><td class="text-center tot_qty_tablecarton_fin">' + tcarttot + '</td></tr></tbody></table></div> <span id="error_msg_alert" style="color:red;"></span>'+enable_button+'<button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" disabled="disabled" id="save_solid_carton_button" style="margin-left:10px">Update</button> <button class="btn btn-warning pull-right input-sm edit_label_order_btn" id="edit_label_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrcStickerOrder(`solid_cartonform`)" type="button">Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="solid_cartonclose">Close</button></div> </div>  </form>');
	$('#age').val($age);
	
	barcode_cartonOs('svg2', data)
	
	//$('.left').removeClass('disabled')
    $('#solid_cartonform .tot_qty_table_carton').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#solid_cartonform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_carton').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table_carton').val());
            }
        })
        $('#solid_cartonform .tot_qty_tablecarton_fin').text(tot_qty_table_carton_final);
    });
	
    $(".backdrop, #solid_cartonclose").click(function () {
        $("#solid_cartonform .left-panelos").removeClass('active');
        $("#solid_cartonform .right-panelos").removeClass('active');
        $("#solid_cartonform .backdrop").removeClass('active');
        $('#solid_cartonform .scalable').removeClass('active');
    })

	
    $("#modal_height").keyup(function () {
        if(this.value)
		{
			var num = 600 * $('#width').val() * this.value
			var val = num / (1000 * 1000 * 1000)
			$('#parcelVolume').val(val)
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
	$("#width").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("width").innerHTML = this.value;
    });
    $('#solid_cartonform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setsolid_cartonStatusOs(custom_url)
        }
    })
}

function SolidPolyBagStickersOs(data,user_type)
{
	var tr = "";
	$qu = 0;
	
	if(user_type == "customer")
	{
		var enable_button = "";
		var custom_url = "customer";
	}
	else
	{
		var enable_button = '<button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_solid_poly_bag_sticker_orders","'+ data.orchestra_solid_poly_bag_sticker_orders[0].id +'")>Enable Edit</button>'
		var custom_url = "admin";
	}

    if (data.orchestra_solid_poly_bag_sticker_orders != "") {
        if (data.orchestra_solid_poly_bag_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_solid_poly_bag_sticker_orders[0].height;
		}
		if($height)
		{
			var num = 600 * 395 * $height
			var val = num / (1000 * 1000 * 1000)
			$parcelVolume = val;
		}
		else
		{
			$parcelVolume = "";
		}
        $template_id = data.orchestra_solid_poly_bag_sticker_orders[0].template_id;
        $exist_id = data.orchestra_solid_poly_bag_sticker_orders[0].id;
        $solid_poly_bag_count=data.orchestra_solid_poly_bag_sticker_orders[0].final_qty;
        $ocn_number=data.orchestra_solid_poly_bag_sticker_orders[0].ocn_number;
        
        var str=data.orchestra_solid_poly_bag_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_solid_poly_bag_sticker_orders[0].group_id;
		var edit_disable = 'disabled="disabled"';
    } else {
     	$height = "";
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $solid_poly_bag_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$age =0;
		var edit_disable = '';
		$ocn_number = "";
    }
		var tpolytot = 0;
        var tcarttot = 0;
        var ttots = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_poly_bag_sticker_orders != "") {
                $poly_count = data.orchestra_solid_poly_bag_sticker_orders_qty[$qu].quantity;
            } else {
                $poly_count = "";
            }

            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Quantity + '</td><td class="text-center"><div class="form-group has-feedback has-success"><input  min="'+data.polybag_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)" '+edit_disable+' required type="number" value="' + $poly_count + '" class="form-control tot_qty_table_poly input-sm"><div class="help-block with-errors"></div></div></td></tr>';
			if($poly_count != "")
			{
				tcarttot += parseInt($poly_count);
			}
			ttots += parseInt(value.Quantity)
            $qu++;
        });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.edit-orch-order-panel').append('<form id="solid_poly_bagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select '+edit_disable+'data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div><div class="form-group"><label>OCN Number</label><input type="text" '+edit_disable+' value="' + $ocn_number + '" class="form-control input-sm" id="ocn_number" placeholder="OCN Number"> </div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label hidden">Height</label><div class="input-group hidden"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.polybag_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Solid Poly Bag Stickers</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="solid_poly_bag_table"><thead><tr><th>UVC_TCO</th><th>Size</th><th>Barcode</th><th>Item Qty</th><th>Polybag Sticker Qty</th></tr></thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots + '</td><td class="text-center tot_qty_tablepoly_fin">' + tcarttot + '</td></tr></tbody></table></div> <span id="error_msg_alert" style="color:red;"></span>'+enable_button+'<button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" disabled="disabled" type="submit" id="save_solid_poly_bag_button" style="margin-left:10px">Update</button> <button class="btn btn-warning pull-right input-sm edit_label_order_btn" id="edit_label_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrcStickerOrder(`solid_poly_bagform`)" type="button">Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="solid_poly_bagclose">Close</button></div> </div>  </form>');
	$('#age').val($age);
	
	barcode_polybagOs('svg2', data)
	
	//$('.left').removeClass('disabled')
    $('#solid_poly_bagform .tot_qty_table_poly').keyup(function () {
        var tot_qty_table_poly_final = 0;
        $('#solid_poly_bagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_poly').val() != undefined) {
                tot_qty_table_poly_final += parseInt($(this).find('.tot_qty_table_poly').val());
            }
        })
        $('#solid_poly_bagform .tot_qty_tablepoly_fin').text(tot_qty_table_poly_final);
    });
	
    $(".backdrop, #solid_poly_bagclose").click(function () {
        $("#solid_poly_bagform .left-panelos").removeClass('active');
        $("#solid_poly_bagform .right-panelos").removeClass('active');
        $("#solid_poly_bagform .backdrop").removeClass('active');
        $('#solid_poly_bagform .scalable').removeClass('active');
    })
	
	$('#age').change(function () {
        var mySVG1 = document.getElementById("svg2");
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


    $('#solid_poly_bagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setsolid_poly_bagStatusOs(custom_url)
        }
    })
}

function StandardCartonStickersOs(data,user_type)
{
	var tr = "";
	$qu = 0;
	
	if(user_type == "customer")
	{
		var enable_button = "";
		var custom_url = "customer";
	}
	else
	{
		var enable_button = '<button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_standard_carton_sticker_orders","'+ data.orchestra_standard_carton_sticker_orders[0].id +'")>Enable Edit</button>'
		var custom_url = "admin";
	}

    if (data.orchestra_standard_carton_sticker_orders != "") {
        if (data.orchestra_standard_carton_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_standard_carton_sticker_orders[0].height;
		}
		
		if (data.orchestra_standard_carton_sticker_orders[0].height == null) {
			$width = "";
		} else {
			$width = data.orchestra_standard_carton_sticker_orders[0].width;
		}
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
        $template_id = data.orchestra_standard_carton_sticker_orders[0].template_id;
        $exist_id = data.orchestra_standard_carton_sticker_orders[0].id;
        $standard_carton_count=data.orchestra_standard_carton_sticker_orders_qty[0].quantity;
        $ocn_number=data.orchestra_standard_carton_sticker_orders[0].ocn_number;
		
		if(data.orchestra_standard_carton_sticker_orders[0].total_polybag != "" && data.orchestra_standard_carton_sticker_orders[0].total_polybag != null)
		{
			$total_polybag = data.orchestra_standard_carton_sticker_orders[0].total_polybag;
		}
		else
		{
			$total_polybag = "";
		}
		
		if(data.orchestra_standard_carton_sticker_orders[0].no_of_carton != "" && data.orchestra_standard_carton_sticker_orders[0].no_of_carton != null)
		{
			$no_of_carton = data.orchestra_standard_carton_sticker_orders[0].no_of_carton;
		}
		else
		{
			$no_of_carton = "";
		}
		
        
        var str=data.orchestra_standard_carton_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_standard_carton_sticker_orders[0].group_id;
		var edit_disable = 'disabled="disabled"';
    } else {
     	$height = "";
     	$width = 395;
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $total_polybag = "";
        $standard_carton_count="";
        $status="";
		$no_of_carton = "";
        $status_text="";
        $save_button_class="";
		$age =0;
		$ocn_number= "";
		var edit_disable = '';
    }
	var tr = "";
	var ttots = 0;
	var thead = '<thead><tr><th>UVC_TCO	</th><th>Size</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th><th>Ratio</th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + data.orchestra_parcel_details[0].ParcelBarCode + '</td><td class="qty">' + value.Sum + '</td></tr>';
			ttots += parseInt(value.Sum)
     });
	
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
    $('.edit-orch-order-panel').append('<form id="standard_cartonform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select data-live-search="true" class="form-control input-sm age_group" id="age" '+edit_disable+' required>' + option + '</select><div class="help-block with-errors"></div></div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label">Height</label><div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" '+edit_disable+' id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div><div class="form-group"><label>Width</label><div class="input-group"><input type="text" onkeypress="return checkNumbersOnly(event)" '+edit_disable+' value="' + $width + '" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6" style="width: 130px;"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left:-135px;">/</span> <div class="col-md-6" style="width: 130px;"><input data-toggle="modal" data-target=".needed_modal" '+edit_disable+' value="' + $no_of_carton + '" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div><div class="form-group"><label>Total Polybags per carton: </label><div class="form-group has-feedback has-success"><input id="total_polybag" '+edit_disable+' value="' + $total_polybag + '" onkeypress="return checkNumbersOnly(event)" type="text" placeholder="Total Polybags per carton" class="form-control input-sm"><div class="help-block with-errors"></div></div></div><div class="form-group"><label>OCN Number</label><input type="text" '+edit_disable+' value="' + $ocn_number + '" class="form-control input-sm" id="ocn_number" placeholder="OCN Number"> </div> </div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"> <div class="div-max"> <h4>Quantity Info for Standard Carton Stickers</h4> <hr><div class="table-responsive hidden"><table class="table table-striped table-hover table-condensed qty-table-modal" id="standard_carton_table">'+thead+'<tbody>' + tr + '</tbody></table></div><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input class="form-control input-sm standard_carton_qty" '+edit_disable+' value="' + $standard_carton_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span>'+enable_button+'<button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" disabled="disabled" id="save_standard_carton_button" style="margin-left:10px">Update</button> <button class="btn btn-warning pull-right input-sm edit_label_order_btn" id="edit_label_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrcStickerOrder(`standard_cartonform`)" type="button">Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="standard_cartonclose">Close</button>  </div></div> </div>  </form>');
	$('#age').val($age);
	
	barcode_std_cartonOs('svg2', data)
	
    $(".backdrop, #standard_cartonclose").click(function () {
        $("#standard_cartonform .left-panelos").removeClass('active');
        $("#standard_cartonform .right-panelos").removeClass('active');
        $("#standard_cartonform .backdrop").removeClass('active');
        $('#standard_cartonform .scalable').removeClass('active');
    })

	
    $("#modal_height").keyup(function () {
        if(this.value)
		{
			var num = 600 * $('#width').val() * this.value
			var val = num / (1000 * 1000 * 1000)
			$('#parcelVolume').val(val)
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
    $('#standard_cartonform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setstandard_cartonStatusOs(custom_url)
        }
    })
}

function StandardPolyBagStickersOs(data,user_type)
{
	var tr = "";
	$qu = 0;
	
	if(user_type == "customer")
	{
		var enable_button = "";
		var custom_url = "customer";
	}
	else
	{
		var enable_button = '<button class="btn btn-success  pull-right input-sm" id="toggle_orch_edit_permission" style="width: 82px;margin-left: 10px;" type="button" onclick =toggleOrchArtworkEditPermission("orchestra_standard_poly_bag_sticker_orders","'+ data.orchestra_standard_poly_bag_sticker_orders[0].id +'")>Enable Edit</button>'
		var custom_url = "admin";
	}
	
    if (data.orchestra_standard_poly_bag_sticker_orders != "") {
        if (data.orchestra_standard_poly_bag_sticker_orders[0].height == null) {
			$height = "";
		} else {
			$height = data.orchestra_standard_poly_bag_sticker_orders[0].height;
		}
		if($height)
		{
			var num = 600 * 395 * $height
			var val = num / (1000 * 1000 * 1000)
			$parcelVolume = val;
		}
		else
		{
			$parcelVolume = "";
		}
        $template_id = data.orchestra_standard_poly_bag_sticker_orders[0].template_id;
        $ocn_number=data.orchestra_standard_poly_bag_sticker_orders[0].ocn_number;
        $exist_id = data.orchestra_standard_poly_bag_sticker_orders[0].id;
        $standard_poly_bag_count=data.orchestra_standard_poly_bag_sticker_orders_qty[0].quantity;
        
        var str=data.orchestra_standard_poly_bag_sticker_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="";
		$age = data.orchestra_standard_poly_bag_sticker_orders[0].group_id;
		var edit_disable = 'disabled="disabled"';
    } else {
     	$height = "";
		$parcelVolume = "";
        $template_id = "";
        $exist_id = "";
        $standard_poly_bag_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$age =0;
		var edit_disable = '';
		$ocn_number="";
    }
		var tpolytot = 0;
        var tcarttot = 0;
       var ttots = 0;
	var thead = '<thead><tr><th>UVC_TCO	</th><th>Size</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th><th>Ratio</th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + data.orchestra_parcel_details[0].ParcelBarCode + '</td><td class="qty">' + value.Sum + '</td></tr>';
			ttots += parseInt(value.Sum)
     });
    
    var option = "";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
	
	$('.edit-orch-order-panel').append('<form id="standard_poly_bagform" data-toggle= "validator"><div class="left-panelos col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4"> <h4>Options</h4> <hr><div class="scroll"> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Age Group</label><select '+edit_disable+' data-live-search="true" class="form-control input-sm age_group" id="age" required>' + option + '</select><div class="help-block with-errors"></div></div><div class="form-group"><label>OCN Number</label><input type="text" '+edit_disable+' value="' + $ocn_number + '" class="form-control input-sm" id="ocn_number" placeholder="OCN Number"> </div> <input type="hidden" value="'+$parcelVolume+'" id="parcelVolume"><label for="edit_merchandiser_buyer" class="form_label hidden">Height</label><div class="input-group hidden"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"><object id="svg2" style="width: 527px;height: 347px;border:2px solid" class="center-block svg2" data="' + data.polybag_template + '" type="image/svg+xml"></object><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div></div></div></div></div></div><div class="right-panelos col-md-4"><div class="table-responsive hidden"> <table class="table table-condensed table-bordered table-hover" id="standardpolybagform">' + thead + '<tbody id="standardpolybagformtbody">' + tr + '</tbody></table></div><div class="div-max"> <h4>Quantity Info for Standard Poly Bag Stickers</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Final order qty</label><input '+edit_disable+' class="form-control input-sm standard_poly_bag_qty" value="' + $standard_poly_bag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required><div class="help-block with-errors"></div></div><span id="error_msg_alert" style="color:red;"></span>'+enable_button+'<button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" disabled="disabled" id="save_standard_poly_bag_button" style="margin-left:10px">Update</button> <button class="btn btn-warning pull-right input-sm edit_label_order_btn" id="edit_label_order_btn" style="width: 55px;margin-left: 10px;" onclick="editOrcStickerOrder(`standard_poly_bagform`)" type="button">Edit</button><button class="btn btn-default pull-right input-sm" type="button" id="standard_poly_bagclose">Close</button>  </div> </div> </div>  </form>');
	$('#age').val($age);
	
	barcode_std_polybagOs('svg2', data)
	
    $(".backdrop, #standard_poly_bagclose").click(function () {
        $("#standard_poly_bagform .left-panelos").removeClass('active');
        $("#standard_poly_bagform .right-panelos").removeClass('active');
        $("#standard_poly_bagform .backdrop").removeClass('active');
        $('#standard_poly_bagform .scalable').removeClass('active');
    })

	$('#age').change(function () {
        var mySVG1 = document.getElementById("svg2");
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

    $('#standard_poly_bagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setstandard_poly_bagStatusOs(custom_url)
        }
    })
}

/**
 * This Function is used to Update Solid Carton Stickers.
 * 
 * @return void
 */

function setsolid_cartonStatusOs(custom_url){
	var tot_qty_table_poly = [];
		var tot_qty_carton = [];
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		
		$('#solid_cartonform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_carton').val() != undefined) {
				tot_qty_carton.push($(this).find('.tot_qty_table_carton').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		tot_qty_carton_fin = tot_qty_carton.join(',');
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');

		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+custom_url+"/" + buyer + "/order-management/set_solid_stickers_os",
			type: "Post",
			data: {
				tot_qty_carton: tot_qty_carton_fin,
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				uvc: $('#uvc_id').val(),
				height: $('#modal_height').val(),
				ocn_number: $('#ocn_number').val(),
				width: $('#width').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				carton_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#solid_cartonform .left-panelos").removeClass('active');
				$("#solid_cartonform .right-panelos").removeClass('active');
				$("#solid_cartonform .backdrop").removeClass('active');
				$('#solid_cartonform .scalable').removeClass('active');
				$('.snackbar_span').html('Order updated Successfully');
				showSnackbar();
				location.reload()
			},
			error: function (data) {

			}
		})
}

/**
 * This Function is used to Update Solid Poly Bag Stickers.
 * 
 * @return void
 */

function setsolid_poly_bagStatusOs(custom_url){
	var tot_qty_table_poly = [];
		var tot_qty_poly = [];
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		
		$('#solid_poly_bagform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		tot_qty_poly_fin = tot_qty_poly.join(',');
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+custom_url+"/" + buyer + "/order-management/set_solid_poly_bag_stickers_os",
			type: "Post",
			data: {
				tot_qty_poly: tot_qty_poly_fin,
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				uvc: $('#uvc_id').val(),
				height: $('#modal_height').val(),
				ocn_number: $('#ocn_number').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				poly_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#solid_poly_bagform .left-panelos").removeClass('active');
				$("#solid_poly_bagform .right-panelos").removeClass('active');
				$("#solid_poly_bagform .backdrop").removeClass('active');
				$('#solid_poly_bagform .scalable').removeClass('active');
				$('.snackbar_span').html('Order updated Successfully');
				showSnackbar();
				location.reload()
			},
			error: function (data) {

			}
		})
}

/**
 * This Function is used to Update Standard Carton Stickers.
 * 
 * @return void
 */

function setstandard_cartonStatusOs(custom_url){
	
		var size = [];
		var barcode = [];
		var uvc_tco = [];

		$('#standard_cartonform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
	
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+custom_url+"/" + buyer + "/order-management/set_standard_carton_stickers_os",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				carton_id: $('#exist_id').val(),
				height: $('#modal_height').val(),
				ocn_number: $('#ocn_number').val(),
				width: $('#width').val(),
				total_polybag: $('#total_polybag').val(),
				final_order_qty_sets: $('.standard_carton_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				age: $('#age').val(),
				no_of_carton: $('#needed_modal').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#standard_cartonform .left-panelos").removeClass('active');
				$("#standard_cartonform .right-panelos").removeClass('active');
				$("#standard_cartonform .backdrop").removeClass('active');
				$('#standard_cartonform .scalable').removeClass('active');
				$('.snackbar_span').html('Order updated Successfully');
				showSnackbar();
				location.reload()
			},
			error: function (data) {

			}
		})
}

/**
 * This Function is used to Update Standard Poly Bag Stickers.
 * 
 * @return void
 */

function setstandard_poly_bagStatusOs(custom_url){
	var size = [];
		var barcode = [];
		var uvc_tco = [];

		$('#standard_poly_bagform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());

		})
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
	var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+custom_url+"/" + buyer + "/order-management/set_standard_poly_bag_stickers_os",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				height: $('#modal_height').val(),
				ocn_number: $('#ocn_number').val(),
				final_order_qty_sets: $('.standard_poly_bag_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				age: $('#age').val(),
				poly_id: $('#exist_id').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$("#standard_poly_bagform .left-panelos").removeClass('active');
				$("#standard_poly_bagform .right-panelos").removeClass('active');
				$("#standard_poly_bagform .backdrop").removeClass('active');
				$('#standard_poly_bagform .scalable').removeClass('active');
				$('.snackbar_span').html('Order updated Successfully');
				showSnackbar();
				location.reload()
			},
			error: function (data) {

			}
		})
}

/**
 * This Function is used to Show all the list of orders like label orders and sticker orders.
 *
 * @return View
 */

function orders(user_type) {
	if(localStorage.getItem("season_code_arr") != null && localStorage.getItem("season_code_arr") != "")
	{
		seasonCodeOrders(user_type,localStorage.getItem("season_code_arr"))
	}
	seasonCodeFilter(user_type,localStorage.getItem("season_code_arr"))	
}

/**
 * This Function is used to Show all the list of orders like label orders and sticker orders.
 *
 * @return View
 */

function seasonCodeOrders(user_type,season_code)
{
	if(localStorage.getItem("buyer") == "orchestra") 
	{
			$('#financialYear').selectpicker('hide')
			$('.season_code-nav-menu').css('display','block')
	}
	else
	{
			$('#financialYear').selectpicker('show')
			$('.season_code-nav-menu').css('display','none')
	}
	$('li.buyer-nav-menu').removeClass('hidden');
	$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	
	$.ajax({
		url: base_url + "/"+user_type+"/orchestra/order-management/orders",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token,
			season_code:JSON.parse(season_code)
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
				var path = base_url + "/"+user_type+"/orchestra/order-management/orders"
				var newObj = data.sticker_orders.concat(data.label_orders);
				var search_string= localStorage.getItem("search_string");
				$('.main-content').html(table(newObj,path,user_type)); 
				$('.top-content').html("");
				if(search_string != null)
				{
					  $('#datatable-example-2').DataTable({
						  "pageLength": 25,
						  "oSearch": { "sSearch": search_string }
					  });
				}
				else
				{
					$('#datatable-example-2').DataTable({
						"pageLength": 25
					});
				}
			  
				$("#datatable-example-2_filter input").addClass('form-control input-sm');
				$("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
				$('#datatable-example-2_length select').addClass('form-control input-sm');

		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
		}
	})
}

/**
 * This Function is used to Show all the list of season code.
 *
 * @return View
 */

function seasonCodeFilter(user_type,season_code_ls)
{
	if(localStorage.getItem("buyer") == "orchestra") 
	{
			$('#financialYear').selectpicker('hide')
	}
	else
	{
			$('#financialYear').selectpicker('show')
	}
	$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	
	$.ajax({
		url: base_url + "/"+user_type+"/orchestra/order-management/season_code_filter",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {

				var options_sc = ""

				$(data).each(function(index,value){
					if(season_code_ls != null)
					{
						if(season_code_ls.includes(value.Season_Code) == true)
						{
							options_sc += "<option data-id='"+value.Season_Code+"' selected>" + value.Season_Code + "</option>";
						}
						else
						{
							options_sc += "<option data-id='"+value.Season_Code+"'>" + value.Season_Code + "</option>";
						}
					}
					else
					{
						options_sc += "<option data-id='"+value.Season_Code+"'>" + value.Season_Code + "</option>";
					}
					
				})

				$('.season_code-nav-menu').html('<div class="form-group"><label style="color:white;margin-right:8px;font-weight:bold;margin-top:14px">Season Code : </label><select data-usertype="'+user_type+'" multiple id="select_season_code" class="selectpicker custom-width-select-season form-control" data-actions-box="true">' + options_sc + '</select></div>')
				$('#select_season_code').selectpicker()

				if(season_code_ls == null)
				{
					$('.main-content').html('<div class="row text-center" style="padding-top:15%"><div class="col-md-6 col-md-offset-3"><img style="width:200px;height:200px" src="/images/undraw_no_data_re_kwbl.svg" /><h3 style="opacity: 0.3;">No season code selected.</h3><p>please <a href="#" onclick="open_season_select(this)">select season code</a> to continue.</p></div></div>');
				}
		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
		}
	})
}

function open_season_select(obj)
{
	$('#select_season_code').selectpicker('toggle')
}


$(document).on('change', '#select_season_code', function(e) {
	if($(this).val() != "")
	{
		localStorage.setItem('season_code_arr', JSON.stringify($(this).val()));
	}
	else
	{
		localStorage.removeItem('season_code_arr');
	}
	orders($(this).data('usertype'))
})

/**
 * This Function is used to create table.
 * Generic Table View For all Lists
 * @return View
 */

function table(data,path,user_type){
    var th = "";
    var tr = "";
    var columnsIn = data[0];

    for (var key in columnsIn) {
        if (key != "id") {
            if (key == "SupplierName" || key == "Style_Name") {
                th += "<th>" + key + "</th>";
            } else if ((key != "pi_required")&&(key != "tableName")&&(key != "template_id")&&(key !="qntytable")) {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }

        }
    }
    if (path==base_url + "/admin/"+user_type+"/order-management/order-status")
    {
       th += "<th style='text-align: center;'>Actions</th>";
    }
    $.each(data, function (index, value) {

        var action_status = false;
        tr += '<tr class="trClass" id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id") {
                if (key == "Type") {
                    if (value[key] == "Null") {
                        var Isstd = "-";
                    } else {
                        var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    }
                    tr += '<td align="center">' + Isstd + '</td>';

                } else if (key == "Disney") {
                    if (value[key] == "Null") {
                        var Disney = "-";
                    } else {
                        var Disney = value[key] === ("DI" || 1) ? "Yes" : "No";
                    }

                    tr += '<td align="center">' + Disney + '</td>';
                } else if (key == "UVC") {

					if(user_type == "admin")
					{
						if($('.logo').text() == "Orders")
						{
							tr += '<td align="center"><a href="#" onclick=order_details("' + value[key] + '","' + value.BuyerPONo + '",this)>' + value[key] + '</a></td>';
						}
						else
						{
							tr += '<td align="center"><a href="#" onclick=artwork_modal("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
						}
					}
					else
					{
						if($('#status_flag').val() == "Orders")
						{
							tr += '<td align="center"><a href="#" onclick=order_details("' + value[key] + '","' + value.BuyerPONo + '",this)>' + value[key] + '</a></td>';
						}
						else
						{
							tr += '<td align="center"><a href="#" onclick=artwork_modal("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
						}
					}

                } else if (key == "Status") {
                    var str=value[key] ;
                    $status=str.replace(/ /g, "");
                    
                    tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                   
                }else if (key == "PI Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                       if (str=="PI Approved"){
                        	action_status = true;
                        }
                       $status=str.replace(/ /g, "");
                       $status_text='<span class="label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }else if (key == "PO Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $status=str.replace(/ /g, "");
                        $status_text='<span class="label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                } else if (key == "DeliveryDate") {
                    var str=value[key] ;
                    if(str != null && str != "" && str != "00-00-0000")
                    {
                        $status_text=str;
                    }
                    else
                    {
                        
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                } else if (key == "Order Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $order_status=str;
                    }
                    else
                    {
                        
                        $order_status=" - ";
                    }
                    
                    tr += '<td align="center">'+$order_status+'</td>';
                   
                } else {
                    if (key == "SupplierName" || key == "Style_Name"){
                        tr += '<td>' + value[key] + '</td>';
                    }
                    else if ((key != "pi_required")&&(key != "tableName")&&(key != "template_id")&&(key != "PONumber")&&(key !="qntytable")){
                        tr += '<td align="center">' + value[key] + '</td>';
                    }
                }
            }
            if (path==base_url + "/"+user_type+"/orchestra/order-management/order-status")
            { 
                if (key == "PONumber")
                { 
                   if (value[key]==null || value[key]=="")
                   {
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="po" id="po" class="form-control input-sm po" placeholder="Enter PO" maxlength="11"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default po-submit" onclick=setPO(this,"'+value.id+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                   }
                   else
                   {
                        
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="po" id="po" class="form-control input-sm po" value="'+value[key]+'"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default po-submit" onclick=setPO(this,"'+value.id+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                   }
                      
                }
                if (key == "pi_required")
                {    
                    if (value[key]==1)
                    {    
                       tr += '<td align="center"><a href="javascript:void(0)" onclick=genarateOrchetraPI("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+action_status+'","'+value.BuyerPONo+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" onclick=showPOUpload("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+value.qntytable+'")></i></a></td>';
                    }
                    else
                    {
                        tr += '<td align="center"><a href="javascript:void(0)" style="margin-left:36px" onclick=showPOUpload("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+value.qntytable+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order"></i></a></td>';
                    }   
                }
            }
        }
              
        tr += '</tr>';
    })
    return '<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}


/**
 * This Function is used to get sticker order details.
 *  
 * @return View
 */

function sticker_order_details(uvc, po, user_type) {

    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
   
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
	$('.season_code-nav-menu').css('display','none')
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/sticker-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po: po,
            uvc: uvc
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			if(data.customer_active == "active")
			{
				 $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
				orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po,user_type)
			}
			else
			{
				$('.snackbar_span').html('Company Details Missing.Please Contact Administrator...!');
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

/**
 * This Function is used Print Stickers.
 *  
 * @return Pdf
 */

function printDiv(data, isstd,uvc,po, user_type) {
	if (data == "svg4" && isstd == "0") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-PS";
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		var tu_size = $('#solidform tbody tr').find('.size').text()
		$('#solidform tbody tr').each(function (key, val) {
			
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						if(user_type == "customer")
						{
							svgDoc.getElementById("watermark").innerHTML = "JGA";
						}
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						svgDoc.getElementById("size").innerHTML = size;
						if(size == "NAISSANCE")
						{
							svgDoc.getElementById("size").style.fontSize = "16px";
						}
						else if(size == "PREMATURE")
						{
							svgDoc.getElementById("size").style.fontSize = "15.5px";
						}
						svgDoc.getElementById("parcel_code").innerHTML = SKU_Code;
						jsbarcodesolidpoly(barcode, 'barcode', svgDoc)
						if(size == "TAILLE UNIQUE")
						{
							svgDoc.getElementById("size").style.fontSize = "14px"
							svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 172.625 28.7874)');
							svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 173.625 45.3324)');
						}
						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
						
				}
			}
			ajax_count++;
			
		});
		
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			Popupsmall(svg_fin, name,svgDoc,uvc,po,svgDoc,tu_size,user_type);
		}
		
		
	} else if (data == "svg4" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		if(user_type == "customer")
		{
			svgDoc.getElementById("watermark").innerHTML = "JGA";
		}
		var name=uvc+"-STD-PS";
		PopupTable(svgRoot,name,uvc,po,svgDoc,user_type);
	} else if (data == "svg3" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		if(user_type == "customer")
		{
			svgDoc.getElementById("watermark").innerHTML = "JGA";
		}
		var name=uvc+"-STD-CS";
		Popup(svgRoot,name,uvc,po,svgDoc,user_type);
	} else {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-CS";
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		var tu_size = $('#solidform tbody tr').find('.size').text()
		$('#solidform tbody tr').each(function (key, val) {
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						if(user_type == "customer")
						{
							svgDoc.getElementById("watermark").innerHTML = "JGA";
						}
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						if(size == "NAISSANCE")
						{
							svgDoc.getElementById("size").style.fontSize = "8px";
						}
						else if(size == "PREMATURE")
						{
							svgDoc.getElementById("size").style.fontSize = "7px";
						}
						svgDoc.getElementById("size").innerHTML = size;
						svgDoc.getElementById("parcelcode").innerHTML = SKU_Code;
						jsbarcodesolidcartonA(barcode, 'barcode1', svgDoc)
						jsbarcodesolidcartonB(barcode, 'barcode2', svgDoc)
						if(size == "TAILLE UNIQUE")
						{
							svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-12" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-14" dy="9" text-anchor="middle">UNIQUE</tspan>';
						}

						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
				}
			}
			ajax_count++;
		});
		
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			PopupArr(svg_fin, name,svgDoc,uvc,po,svgDoc,tu_size,user_type);
		}
	}

}
/**
 * This Function is used Print Stickers.
 *  
 * @return Pdf
 */

function proofDiv(data, isstd,uvc,buyer_po, user_type) {
	if (data == "svg4" && isstd == "0") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-PS-"+buyer_po;
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		var tu_size = $('#solidform tbody tr').find('.size').text()
		$('#solidform tbody tr').each(function (key, val) {
			
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						svgDoc.getElementById("size").innerHTML = size;
						if(size == "NAISSANCE")
						{
							svgDoc.getElementById("size").style.fontSize = "16px";
						}
						else if(size == "PREMATURE")
						{
							svgDoc.getElementById("size").style.fontSize = "15.5px";
						}
						svgDoc.getElementById("round").style.fill = "#FFFFFF"
						svgDoc.getElementById("parcel_code").innerHTML = SKU_Code;
						jsbarcodesolidpoly(barcode, 'barcode', svgDoc)
						if(size == "TAILLE UNIQUE")
						{
							svgDoc.getElementById("size").style.fontSize = "14px"
							svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 172.625 28.7874)');
							svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 173.625 45.3324)');
						}
						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
						
				}
			}
			ajax_count++;
			
		});
		var age_colour = $('#age option:selected').data('color');
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			ProofPopupsmall(svg_fin, name,svgDoc,age_colour,tu_size,user_type);
		}
		
		
	} else if (data == "svg4" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		svgDoc.getElementById("round").style.fill = "#FFFFFF"
		var name=uvc+"-STD-PS-"+buyer_po;
		var age_colour = $('#age option:selected').data('color');
		ProofPopupTable(svgRoot,name,svgDoc,age_colour,user_type);
	} else if (data == "svg3" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		svgDoc.getElementById("standardcolor").style.fill = "#FFFFFF"
		var svgRoot = svgDoc.documentElement;
		var name=uvc+"-STD-CS-"+buyer_po;
		ProofPopup(svgRoot,name,svgDoc,user_type);
	} else {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-CS-"+buyer_po;
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		var tu_size = $('#solidform tbody tr').find('.size').text()
		$('#solidform tbody tr').each(function (key, val) {
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						svgDoc.getElementById("size").innerHTML = size;
						if(size == "NAISSANCE")
						{
							svgDoc.getElementById("size").style.fontSize = "8px";
						}
						else if(size == "PREMATURE")
						{
							svgDoc.getElementById("size").style.fontSize = "7px";
						}
						svgDoc.getElementById("parcelcode").innerHTML = SKU_Code;
						jsbarcodesolidcartonA(barcode, 'barcode1', svgDoc)
						jsbarcodesolidcartonB(barcode, 'barcode2', svgDoc)
						if(size == "TAILLE UNIQUE")
						{
							svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-12" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-14" dy="9" text-anchor="middle">UNIQUE</tspan>';
						}
						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
				}
			}
			ajax_count++;
		});
		
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			ProofPopupArr(svg_fin, name,svgDoc,tu_size,user_type);
		}
	}

}

/**
 * This Function is used Cancel Sticker .
 *  
 * @return Boolean Value
 */

function cancelStickerDiv(user_type){
	var carton_id = $('#carton_id').val();
	var poly_id = $('#poly_id').val();

	var std_carton_id = $('#std_carton_id').val();
	var std_poly_id = $('#std_poly_id').val();

	
	var buyer = localStorage.getItem("buyer");
	if ($('#carton_hidden').val() == "standard") {
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/remove_standard_stickers",
			type: "Post",
			data: {
				carton_id: std_carton_id,
				poly_id:std_poly_id,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('#age').val("Age Group")
				$('#modal_height').val("")
				$('#needed_modal').val("")
				$('#total_polybag').val("")
				$('#final_order_qty_sets').val("")
				$('#final_order_qty').val("")
				$('#save_sticker_button').removeClass("hidden")
				$('#po-file-upload-button-standard').addClass("hidden")
				$('#cancel_sticker_button').addClass("hidden")
			},
			error: function (data) {
	
			}
		})
	}
	else{
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/remove_solid_stickers",
			type: "Post",
			data: {
				carton_id: carton_id,
				poly_id:poly_id,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {

				$('#age').val("Age Group")
				$('#modal_height').val("")
				$('.tot_qty_table_poly').val("")
				$('.tot_qty_table_carton').val("")

				$('.final_solid_poly_qty').text("0")
				$('.final_solid_carton_qty').text("0")
				$('#save_sticker_button').removeClass("hidden")
				$('#po-file-upload-button-solid').addClass("hidden")
				$('#cancel_sticker_button').addClass("hidden")
				
			},
			error: function (data) {
	
			}
		})
	}
	
}


/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function Popup(data,name,uvc,po,svgDoc,user_type) {
	var id = $('#std_carton_id').val()
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/print-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name,
			id : id,
			style_no:uvc,
			buyer_no:po
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
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
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function PopupArr(data,name,svgDoc,uvc,po,svgDoc,tu_size,user_type) {
	var id = $('#carton_id').val()
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/print-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:uvc,
			buyer_no:po
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
			if(tu_size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-11" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-28" dy="9" text-anchor="middle">UNIQUE</tspan>';
		    }
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 * It is used to create dynamic table using tcpdf
 * @return Pdf
 */

function PopupTable(data,name,uvc,po,svgDoc,user_type) {
	var id = $('#std_poly_id').val()
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	var size = [];
	var qty = [];
	var size_break = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	$('#solidform tbody tr').each(function () {
		size.push($(this).find('.size').text());
		qty.push($(this).find('.qty').text());
		size_break.push($(this).find('.size_break').val());
	})
	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var size_breakfin = size_break.join(',')
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/print-pdf-table",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			size_break: size_breakfin,
			name:name,
			color:$('#color_code').val(),
			id : id,
			style_no:uvc,
			buyer_no:po
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

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 100 * 70 stickers
 * @return Pdf
 */

function Popupsmall(data,name,svgDoc,uvc,po,svgDoc,tu_size,user_type) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var id = $('#poly_id').val()
	var success = false;
//	var url = "";
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/print-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:uvc,
			buyer_no:po
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
			if(tu_size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 174.625 28.7874)');
				svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 175.625 45.3324)');
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function ProofPopup(data,name,svgDoc,user_type) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/proof-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
			svgDoc.getElementById("standardcolor").style.fill = "#fbfb08"
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function ProofPopupArr(data,name,svgDoc,tu_size,user_type) {
	
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/proof-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
			if(tu_size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-11" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-28" dy="9" text-anchor="middle">UNIQUE</tspan>';
		    }
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 * It is used to create dynamic table using tcpdf
 * @return Pdf
 */

function ProofPopupTable(data,name,svgDoc,age_colour,user_type) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	var size = [];
	var qty = [];
	var size_break = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	$('#solidform tbody tr').each(function () {
		size.push($(this).find('.size').text());
		qty.push($(this).find('.qty').text());
		size_break.push($(this).find('.size_break').val());
	})
	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var size_breakfin = size_break.join(',')
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/proof-pdf-table",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			size_break: size_breakfin,
			name:name,
			color:$('#color_code').val()
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
			svgDoc.getElementById("round").style.fill = age_colour
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 100 * 70 stickers
 * @return Pdf
 */

function ProofPopupsmall(data,name,svgDoc,age_colour,tu_size,user_type) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	
	$.ajax({
		url: base_url + "/"+user_type+"/order-management/proof-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
			svgDoc.getElementById("round").style.fill = age_colour
			if(tu_size == "TAILLE UNIQUE")
		    {
				svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 174.625 28.7874)');
				svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 175.625 45.3324)');
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonA(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 347,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonA(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 355,
		fontSize: 50
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
	svg.setAttribute('font-size', '59.80px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonB(code, id, svgDoc) {	
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.5,
		height: 345,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonB(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.43,
		height: 349,
		fontSize: 50
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
	svg.setAttribute('font-size', '55px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdpoly(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width:6.595,
		height: 350,
		fontSize: 60
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
	svg.setAttribute('font-size', '64px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '12px');

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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidpoly(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.7,
		height: 370,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.50px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
 * This Function is used to set Solid and Standard Stickers to db.
 * 
 * @return void
 */

function setSolidStickers(user_type) {
	
	if($('#age').val() != "Age Group"){
		$('.age_group_error').addClass('hidden')
	if ($('#carton_hidden').val() == "standard") {
		
		var size = [];
		var barcode = [];
		var uvc_tco = [];
		var size_break = [];

		$('#solidform tbody tr').each(function () {
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());
			size_break.push($(this).find('.size_break').val());
		})
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
		size_break_fin = size_break.join(',');
		
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_standard_stickers",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				carton_template_id: $('#carton_template_id').val(),
				polybag_template_id: $('#polybag_template_id').val(),
				ocn_number: $('#ocn_number').val(),
				height: $('#modal_height').val(),
				width: $('#width').val(),
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				size_break: size_break_fin,
				no_of_carton: $('#no_of_carton').val(),
				total_polybag: $('#total_polybag').val(),
				final_order_qty_sets: $('#final_order_qty_sets').val(),
				order_qty: $('#order_qty').val(),
				final_order_qty: $('#final_order_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				color_code: $('#color_code').val(),
				age: $('#age').val(),
				carton_id: $('#std_carton_id').val(),
				poly_id: $('#std_poly_id').val(),
				no_of_carton: $('#needed_modal').val(),
				po: $('#po').val(),
				//size: size_fin,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('#save_sticker_button').addClass('hidden')
				//$('#po-file-upload-button-standard').css('display','block')
				$('#polybag_print_button').removeClass('hidden')
				$('#carton_print_button').removeClass('hidden')
				if(user_type == "admin")
				{
					$('#polybag_proof_button').removeClass('hidden')
					$('#carton_proof_button').removeClass('hidden')
				}
				//$('#cancel_sticker_button').removeClass('hidden')
				$('#std_carton_id').val(data.carton_id);
				$('#std_poly_id').val(data.poly_id);

				document.getElementById("po-file-upload-button-standard").click();
			},
			error: function (data) {

			}
		})
	} else {
		var tot_qty_table_poly = [];
		var tot_qty_carton = [];
		var size = [];
		var barcode = [];
		var uvc_tco = [];
		var size_break = [];

		
		$('#solidform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_table_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			if ($(this).find('.tot_qty_table_carton').val() != undefined) {
				tot_qty_carton.push($(this).find('.tot_qty_table_carton').val());
			}
			size.push($(this).find('.size').text());
			barcode.push($(this).find('.barcode').text());
			uvc_tco.push($(this).find('.uvc_tco').text());
			size_break.push($(this).find('.size_break').val());

		})
		tot_qty_table_poly_fin = tot_qty_table_poly.join(',');
		tot_qty_carton_fin = tot_qty_carton.join(',');
		size_fin = size.join(',');
		barcode_fin = barcode.join(',');
		uvc_tco_fin = uvc_tco.join(',');
		size_break_fin = size_break.join(',');

		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_solid_stickers",
			type: "Post",
			data: {
				
				// template_front_id: template_front_id,
				tot_qty_poly: tot_qty_table_poly_fin,
				tot_qty_carton: tot_qty_carton_fin,
				size: size_fin,
				barcode: barcode_fin,
				uvc_tco: uvc_tco_fin,
				size_break: size_break_fin,
				uvc: $('#uvc_id').val(),
				carton_template_id: $('#carton_template_id').val(),
				polybag_template_id: $('#polybag_template_id').val(),
				ocn_number: $('#ocn_number').val(),
				height: $('#modal_height').val(),
				width: $('#width').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				color_code: $('#color_code').val(),
				carton_id: $('#carton_id').val(),
				poly_id: $('#poly_id').val(),
				po: $('#po').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('#save_sticker_button').addClass('hidden')
				//$('#po-file-upload-button-solid').css('display','block')
				$('#polybag_print_button').removeClass('hidden')
				$('#carton_print_button').removeClass('hidden')
				if(user_type == "admin")
				{
					$('#polybag_proof_button').removeClass('hidden')
					$('#carton_proof_button').removeClass('hidden')
				}
				//$('#cancel_sticker_button').removeClass('hidden')
				$('#carton_id').val(data.carton_id);
				$('#poly_id').val(data.poly_id);

				document.getElementById("po-file-upload-button-solid").click();
			},
			error: function (data) {

			}
		})
	}
	}
	else{
		$('.snackbar_span').html('Please Select Age Group');
		showSnackbar();
		//$('.age_group_error').removeClass('hidden')
	}

}

editOrcStickerOrder = (options) => {
	//console.log(options)
	$("#"+options+" :input").prop("disabled", false);
	$(".dropdown-toggle").css("background-color", "#fff");
	$('.selectpicker').prop('disabled', false);
	$(".selectpicker").selectpicker("refresh");
	$('#'+options).validator('update');
}