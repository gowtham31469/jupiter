/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function openmodaltesco(modal, i,template_file,style_no,buyer_no,id,order_type) {
	
		genarateArtwork("tesco_svg_modal" + i, modal,style_no,buyer_no,id,order_type);

}

function genarateArtwork(data, name,style_no,buyer_no,id,order_type) {

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
					id: id,
					order_type:order_type
				},
				beforeSend: function() {

				},
				success: function(data) {
					
					
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			var zone=val.zone;

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TSL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
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
				var description =val.short_description.replace(/&/g,'&#038;');
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
						var zone=val.zone;
			var svgRoot = svgDoc.documentElement;
						
		if (val.supplier_name != '') {
		    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
						
		if (val.po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (val.ce_style_ref != '') {
			svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (val.uk_style_ref != '') {
			svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (val.eqos_site_code != '') {
			svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		
					
						/** [ Dynamically change  svg department ] */
		if (val.department != '') {
			/** [Give font-size for department] */
			if (val.department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if (val.brand != '') {
			/** [Give font-size for brand] */
			if (val.brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
						
										
		var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
		var rfid = val.rfid_compliant == 0 ? "no" : "yes";
						
		/** [ Dynamically svg RFID ] */
		if (rfid != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (tag_at_source != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (val.season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (val.manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
						
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TCL4-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
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
		url: base_url + "/erp/job-manager/tesco/print-div-tesco",
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
		url: base_url + "/erp/job-manager/tesco/print-div-tesco-small",
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
	

	
	//svg.removeAttribute('version');
	//svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 ,0 ,463 ,222');
	
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
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetescoean(code, id, svgDoc) {
	//		format: "ean13",
	//		width: 3.45,
	//		height: 125,
	//		fontSize: 35,
	//		flat: true,
	//		displayValue: false
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
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0, 0, 393 ,190');

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

	
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 ,0 ,393 ,187');
	
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
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 0 231 109');
	
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