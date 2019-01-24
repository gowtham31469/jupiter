/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function openmodaltesco(modal, i,template_file,style_no,buyer_no,id) {
	$('#modaldiv').html('  <div class="modal fade" id="' + modal + '" role="dialog">\
                                             <div class="modal-dialog">\
                                               <div class="modal-content">\
                                                 <div class="modal-header">\
                                                   <button type="button" class="close" data-dismiss="modal">&times;</button>\
                                                   <h4 class="modal-title">' + modal + '</h4>\
                                                 </div>\
                                                 <div class="modal-body">\
                                                   <object style="border: 2px #333 solid;" id="tesco_svg_modal' + i + '" class="center-block tesco_svg' + i + '" data="' + template_file + '" type="image/svg+xml"></object>\
                                                 </div>\
                                                 <div class="modal-footer">\
                                                   <button type="button" class="btn btn-primary print-btn" onclick=printDivTesco("tesco_svg_modal' + i + '","' + modal + '") style="display:none;" id="proof_' + modal + '_btn">Generate Proof</button><button type="button" class="btn btn-primary print-btn" onclick=GenerateDivTesco("tesco_svg_modal' + i + '","' + modal + '") style="display:none;" id="print_' + modal + '_btn">Generate Print</button>\
                                                   <div id="svgdiv" style="position:absolute;visibility:hidden">\
                                                     <svg id="ean-13"></svg>\
                                                   </div>\
                                                 </div>\
                                               </div>\
                                             </div>\
                                          </div>')
	$('#' + modal).modal("show");
	var data = JSON.parse(localStorage.getItem("data"));

	var zone = localStorage.getItem("zone");
	var type = localStorage.getItem("template type");
	/**  [ Dynamically change all svg templates ] */
	var mySVG = document.getElementById("tesco_svg_modal" + i);
	//var mySVG = document.getElementById(id);
	var svgDoc;
	//mySVG.addEventListener("load", function () {

	$('#' + modal).on('shown.bs.modal', function () {
		svgDoc = mySVG.contentDocument;
		genarateArtwork("tesco_svg_modal" + i, modal,style_no,buyer_no,id);
});

	//}, false);

}

function genarateArtwork(data, name,style_no,buyer_no,id) {

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name2=name;
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
	
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/erp/job-manager/get_order_details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id
				},
				beforeSend: function() {

				},
				success: function(data) {
					
					
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = $(this).find('.ce_single_size').val();
			} else {
				var size = $(this).find('.uk_single_size').val();
			}

			var description = $('#tesco_description').val()
			if ((name == "TSL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
								/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;

								
								if (str.length > 24) {
									if (/\S/.test(str.charAt(24))) {
										//No Space
										if (str.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").innerHTML = str.toUpperCase();
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = '';
							} else if (description != '') {
								if (description.length > 24) {
									if (/\S/.test(description.charAt(24))) {
										//No Space
										if (description.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						
							if (description.length > 24) {
								if (/\S/.test(description.charAt(24))) {
									//No Space
									if (description.indexOf(" ") == "-1") {
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									} else {
										svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
									}

								} else {
									//Space
									svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
									svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
								}
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
							} else {
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
								svgDoc.getElementById("description1").innerHTML = "";
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
							}
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);
					var svg = encodeURI(str)
					svgarr.push(svg)

				}
			} else {
				var description = $('#tesco_description').val()
				if (description.length > 24) {
					if (/\S/.test(description.charAt(24))) {
						//No Space
						if (description.indexOf(" ") == "-1") {
							svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
						} else {
							svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
						}

					} else {
						//Space
						svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
						svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
					}
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
				} else {
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					svgDoc.getElementById("description1").innerHTML = "";
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
				}
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);
				var svg = encodeURI(str)
				svgarr.push(svg)
			}
			ajax_count++;
		})
					if (ajax_count == data.order_details.length) {
						var svg_fin = svgarr.join(',_');
						PopupTescoSmall(svg_fin, name);
					}
				},
				error: function(data) {

				},
				complete: function() {

				}
			})

		
		


	} else {
		var svgarr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/erp/job-manager/get_order_details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id
				},
				beforeSend: function() {

				},
				success: function(data) {
					$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.ce_ratio_cpc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.ce_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.uk_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.uk_ratio_upc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.uk_ratio_tpnd').val();
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = $(this).find('.ce_single_size').val();
			} else {
				var size = $(this).find('.uk_single_size').val();
			}

			var description = $('#tesco_description').val()
			if ((name == "TCL4-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)

				}
			} else {
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
			}




			ajax_count++;
		})
			if (ajax_count == data.order_details.length) {
				var svg_fin = svgarr.join(',_');
				PopupTesco(svg_fin, name);
			}
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
		
		

	}


}


/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTesco(data, name) {

	//	var s = new XMLSerializer();
	//	var d = data;
	//	var str = s.serializeToString(d);
	//	var svg = encodeURI(str)
	//var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			//window.location.href=data;
			//			success = true;
			//			url = data;

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoSmall(data, name) {

	var url = "";
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}