/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function openmodaltesco(modal, i,template_file) {
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
		dynamicSVGTemplatescard("tesco_svg_modal" + i, modal.replace('', ' '));
	});

	//}, false);

}

/**
 * [ dynamicSVGTemplatescard function for creating dynamic card svg file ]
 * @author <praveen@wizroots.com>
 */
function dynamicSVGTemplatescard(id, modal) {

	var zone = localStorage.getItem("zone");
	var type = localStorage.getItem("template type");
	/**  [ Dynamically change all svg templates ] */
	var mySVG = document.getElementById(id);
	var svgDoc = mySVG.contentDocument;
	var buyer_id  = localStorage.getItem("buyer_id");
	var templates = localStorage.getItem("templates");
	/** [ separate conditions for TSL1,TSL3 and other stickers ] */
	if (modal === "TSL 1" || modal === "TSL 3") {

		if (zone === "CE") {
			/**  [ if CE ratio template ] */
			if (type === "ratio") {
				var tpnd = $('#ce_ratio_orms_tpn_1').val();
				var description = $('#tesco_description').val();
				var ce_ratio_itf = $('#ce_ratio_itf_1').val();

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
						svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
						svgDoc.getElementById("description1").innerHTML = "";
					}

				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
				/** [ Dynamically change svg CE ratio tpnd number ] */
				if (tpnd != '') {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + tpnd;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}


				/** [ Dynamically change svg barcode ] */
				if (ce_ratio_itf.length === 14) {
					var barcodecheck = checkjsbarcodeitf(ce_ratio_itf);
					if (barcodecheck == "false") {
						barcode_checks++;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode_number").innerHTML = '';
						/**  [ Show validation error message ] **/
						$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						jsbarcodetescotsl(ce_ratio_itf, 'barcode', svgDoc)
						svgDoc.getElementById("barcode_number").innerHTML = ce_ratio_itf;
					}
				} else {
					barcode_checks = 0;
					$('.barcode-error').html('');
					svgDoc.getElementById("barcode").innerHTML = '';
					svgDoc.getElementById("barcode_number").innerHTML = '';
				}
			} else {
				/**  [ if CE single template ] */
				var orms_tpn = $('#ce_single_orms_tpn_1').val();
				var description = $('#tesco_description').val();

				var ce_single_ean = $('#ce_single_ean_1').val();

				/** [ Dynamically change svg CE single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}

				if (modal === "TSL 3") {

					/** [ Dynamically change svg barcode ] */
					if (ce_single_ean.length === 13) {
						var barcodecheck = checkjsbarcodeean(ce_single_ean);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetescotslean(ce_single_ean, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = ce_single_ean;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode").innerHTML = '';
						svgDoc.getElementById("barcode_number").innerHTML = '';
					}
				}
				var size = $('#ce_single_size_1').val();
				/** if size is not empty, Get equivalent ce size for entered size (single templates) */
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
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
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
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
				} else {
					/** [ Dynamically change svg short description ] */
					//dsf efsf dsf fdsfs fdsffdsf f fds  fsdf 
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
				}
			}
		} else {
			/**  [ if UK ratio template ] */
			if (type === "ratio") {
				var tpnd = $('#uk_ratio_tpnd_1').val();
				var description = $('#tesco_description').val();
				var uk_ratio_itf = $('#uk_ratio_itf_1').val();

				/** [ Dynamically change svg UK ratio tpnd number ] */
				if (tpnd != '') {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + tpnd;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
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
				/** [ Dynamically change svg barcode ] */
				if (uk_ratio_itf.length === 14) {
					var barcodecheck = checkjsbarcodeitf(uk_ratio_itf);
					if (barcodecheck == "false") {
						barcode_checks++;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode_number").innerHTML = '';
						/**  [ Show validation error message ] **/
						$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						jsbarcodetescotsl(uk_ratio_itf, 'barcode', svgDoc)
						svgDoc.getElementById("barcode_number").innerHTML = uk_ratio_itf;
					}

				} else {
					barcode_checks = 0;
					$('.barcode-error').html('');
					svgDoc.getElementById("barcode_number").innerHTML = '';
					svgDoc.getElementById("barcode").innerHTML = '';
				}
			} else {
				/**  [ if UK single template ] */
				var orms_tpn = $('#uk_single_tpnd_1').val();
				var description = $('#tesco_description').val();
				var uk_single_ean = $('#uk_single_ean_1').val();
				/** [ Dynamically change svg UK single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				if (modal === "TSL 3") {

					/** [ Dynamically change svg barcode ] */
					if (uk_single_ean.length === 13) {
						var barcodecheck = checkjsbarcodeean(uk_single_ean);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetescotslean(uk_single_ean, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = uk_single_ean;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode_number").innerHTML = '';
						svgDoc.getElementById("barcode").innerHTML = '';
					}
				}
				var size = $('#uk_single_size_1').val();
				/** if size is not empty, Get equivalent ce size for entered size (single templates) */
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
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
									svgDoc.getElementById("description1").innerHTML = "";
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = "";
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
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
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
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
				}
			}
		}
	} else {
		var po_number = $('#tesco_po_number').val();
		var uk_style = $('#tesco_uk_style').val();
		var ce_style = $('#tesco_ce_style').val();
		var tesco_eqos = $('#tesco_eqos').val();
		var department = $('#tesco_department option:selected').text();
		var brand = $('#tesco_brand option:selected').text();
		var rfid = $('#tesco_rfid option:selected').text();
		var tag_at_source = $('#tesco_tag_at_source option:selected').text();
		var season_code = $('#tesco_season_code').val();
		var manufacture_date = $('#tesco_manufacture_date').val();
		var supplier = $('#tesco_supplier option:selected').text();

		if (po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (ce_style != '') {
			svgDoc.getElementById("ce_style").innerHTML = ce_style.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (uk_style != '') {
			svgDoc.getElementById("uk_style").innerHTML = uk_style.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (tesco_eqos != '') {
			svgDoc.getElementById("eqos").innerHTML = tesco_eqos.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		/** [ Dynamically change  svg department ] */
		if ($('#tesco_department option:selected').val() != '') {
			/** [Give font-size for department] */
			if (department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if ($('#tesco_brand option:selected').val() != '') {
			/** [Give font-size for brand] */
			if (brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
		/** [ Dynamically svg RFID ] */
		if ($('#tesco_rfid option:selected').val() != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if ($('#tesco_tag_at_source option:selected').val() != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
		 /** [ Dynamically change  svg supplier name ] */
		if ($('#tesco_supplier option:selected').val() != '') {
		    svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
		if (zone === "CE") {
			/**  [ if CE ratio template ] */
			if (type === "ratio") {
				var pieces_in_ratio = $('#ce_ratio_cpc_1').val();
				var tpnd = $('#ce_ratio_orms_tpn_1').val();
				var ce_ratio_itf = $('#ce_ratio_itf_1').val();
				var description = $('#tesco_description').val();
				/** [ Dynamically change svg CE pieces in ratio ] */
				if (pieces_in_ratio != '') {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
				} else {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
				}
				/** [ Dynamically change svg CE ratio tpnd number ] */
				if (tpnd != '') {
					svgDoc.getElementById("tpnd").innerHTML = tpnd;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				if (modal === "TCL 1" || modal === "THL 1") {

					/** [ Dynamically change svg barcode ] */
					if (ce_ratio_itf != '' && ce_ratio_itf.length === 14) {
						var barcodecheck = checkjsbarcodeitf(ce_ratio_itf);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = ce_ratio_itf;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode").innerHTML = '';
						svgDoc.getElementById("barcode_number").innerHTML = '';
					}
				}
				/** [ Dynamically change svg short description ] */
				if (description != '') {
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
			} else {
				/**  [ if CE single template ] */
				var orms_tpn = $('#ce_single_orms_tpn_1').val();
				var case_size = $('#ce_single_case_size_1').val();
				var ce_single_ean = $('#ce_single_ean_1').val();
				var description = $('#tesco_description').val();
				/** [ Dynamically change svg CE single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				/** [ Dynamically change svg CE single case size ] */
				if (case_size != '') {
					svgDoc.getElementById("unit_per_case").innerHTML = case_size;
				} else {
					svgDoc.getElementById("unit_per_case").innerHTML = '';
				}
				if (modal === "THL 3") {
					/** [ Dynamically change svg barcode ] */
					if (ce_single_ean != '' && ce_single_ean.length === 13) {
						var barcodecheck = checkjsbarcodeean(ce_single_ean);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = ce_single_ean;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode").innerHTML = '';
						svgDoc.getElementById("barcode_number").innerHTML = '';
					}
				}
				var size = $('#ce_single_size_1').val();
				/** if size is not empty, Get equivalent ce size for entered size (single templates) */
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
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
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
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
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
				}

			}
		} else {
			/**  [ if UK ratio template ] */
			if (type === "ratio") {
				var pieces_in_ratio = $('#uk_ratio_upc_1').val();
				var tpnd = $('#uk_ratio_tpnd_1').val();
				var uk_ratio_itf = $('#uk_ratio_itf_1').val();
				var description = $('#tesco_description').val();

				/** [ Dynamically change svg UK pieces in ratio ] */
				if (pieces_in_ratio != '') {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
				} else {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
				}
				/** [ Dynamically change svg UK ratio tpnd number ] */
				if (tpnd != '') {
					svgDoc.getElementById("tpnd").innerHTML = tpnd;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				if (modal === "TCL 1" || modal === "THL 1") {
					/** [ Dynamically change svg barcode ] */
					if (uk_ratio_itf != '' && uk_ratio_itf.length === 14) {
						var barcodecheck = checkjsbarcodeitf(uk_ratio_itf);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = uk_ratio_itf;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode_number").innerHTML = '';
						svgDoc.getElementById("barcode").innerHTML = '';
					}
				}
				/** [ Dynamically change svg short description ] */
				if (description != '') {
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
			} else {
				/**  [ if UK single template ] */
				var orms_tpn = $('#uk_single_tpnd_1').val();
				var case_size = $('#uk_single_case_size_1').val();
				var uk_single_ean = $('#uk_single_ean_1').val();
				var description = $('#tesco_description').val();
				/** [ Dynamically change svg Uk single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				/** [ Dynamically change svg Uk single case size ] */
				if (case_size != '') {
					svgDoc.getElementById("unit_per_case").innerHTML = case_size;
				} else {
					svgDoc.getElementById("unit_per_case").innerHTML = '';
				}

				if (modal === "THL 3") {
					/** [ Dynamically change svg barcode ] */
					if (uk_single_ean != '' && uk_single_ean.length === 13) {
						var barcodecheck = checkjsbarcodeean(uk_single_ean);
						if (barcodecheck == "false") {
							barcode_checks++;
							$('.barcode-error').html('');
							svgDoc.getElementById("barcode_number").innerHTML = '';
							/**  [ Show validation error message ] **/
							$('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
						} else {
							barcode_checks = 0;
							$('.barcode-error').html('');
							jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
							svgDoc.getElementById("barcode_number").innerHTML = uk_single_ean;
						}
					} else {
						barcode_checks = 0;
						$('.barcode-error').html('');
						svgDoc.getElementById("barcode_number").innerHTML = '';
						svgDoc.getElementById("barcode").innerHTML = '';
					}
				}
				var size = $('#uk_single_size_1').val();
				/** if size is not empty, Get equivalent ce size for entered size (single templates) */
				if (size != '') {
					$.ajax({
						url: base_url + "/admin/tesco/get-tsl-size",
						type: 'GET',
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
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
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
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
				}
			}
		}
	}
}
function genarateArtwork(data, name) {
	console.log(data);
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
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

		if (ajax_count == document.getElementById("table_row").rows.length) {
			var svg_fin = svgarr.join(',_');
			PopupTescoSmall(svg_fin, name);
		}


	} else {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
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

		if (ajax_count == document.getElementById("table_row").rows.length) {
			var svg_fin = svgarr.join(',_');
			PopupTesco(svg_fin, name);
		}

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