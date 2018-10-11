/**  
 * Validation for numeric values with dot
 * @param   evt 
 * @return {Boolean} 
 * @author <praveen>    
 */
function isNumberKey(evt) {
	try {
		var charCode = (evt.which) ? evt.which : event.keyCode

		if (charCode == 46) {
			var inputValue = $("#inputfield").val()
			if (inputValue.indexOf('.') < 1) {
				return true;
			}
			return false;
		}
		/** [ Checking key stroke values for numeric values ] */
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	} catch (e) {
		console.log("Error:" + e)
	}
}

function template_list() {
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/template-management/template-list",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
			$(".loader").css('margin-top', '12%');
		},
		success: function (data) {
			$('.main-content').html('<div class="row"><div class="col-md-4"><button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand">Expand</button> <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse">Collapse</button><div class="search-tree"> <input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="card js-card" id="style-4" style="height:calc(100vh - 110px); overflow-y:auto; position:relative;margin-bottom: 15px;"><div class="jaytree force-overflow" id="tree1"> </div></div></div><div class="col-md-8 col-sm-12 col-xs-12" style="margin-top: 70px;"><div class="card text-white preview-tag" style="height:calc(100vh - 110px);overflow-y: auto;"> <div class="card-body text-center justify-content-center"> <div class="loader"></div><div class="viewDiv" style="width:100%;"> </div><div class="descriptionDiv"> </div></div></div></div></div>')

			if (buyer == "tesco") {
				$('.descriptionDiv').addClass('hidden')
				$('#tree1').jstree({
                    'core': {
                        'data': [{
                            "id": 1,
                            "text": "Stickers",
                            "parent_id": 0,
                            "children": [{
                                "id": 2,
                                "text": "Carton & Polybag Stickers",
                                "parent_id": 1,
                                "children": [{
                                    "id": 3,
                                    "text": "CE",
                                    "parent_id": 2,
                                    "children": [
                                    {
                                        "id": "5",
                                        "text": "TCL 1",
                                        "parent_id": 3
                                    },{
                                        "id": "6",
                                        "text": "TCL 2",
                                        "parent_id": 3
                                    },{
                                        "id": "7",
                                        "text": "TSL 1",
                                        "parent_id": 3
                                    }, {
                                        "id": "8",
                                        "text": "TCL 4",
                                        "parent_id": 3
                                    }, {
                                        "id": "9",
                                        "text": "TSL 3",
                                        "parent_id": 3
                                    }, {
                                        "id": "10",
                                        "text": "THL 1",
                                        "parent_id": 3
                                    },{
                                        "id": "11",
                                        "text": "THL 3",
                                        "parent_id": 3
                                    }]
                                }, {
                                    "id": 4,
                                    "text": "UK",
                                    "parent_id": 2,
                                    "children": [
                                    {
                                        "id": "12",
                                        "text": "TCL 1",
                                        "parent_id": 4
                                    },{
                                        "id": "13",
                                        "text": "TCL 2",
                                        "parent_id": 4
                                    },{
                                        "id": "14",
                                        "text": "TSL 1",
                                        "parent_id": 4
                                    }, {
                                        "id": "15",
                                        "text": "TCL 4",
                                        "parent_id": 4
                                    }, {
                                        "id": "16",
                                        "text": "TSL 3",
                                        "parent_id": 4
                                    }, {
                                        "id": "17",
                                        "text": "THL 1",
                                        "parent_id": 4
                                    },{
                                        "id": "18",
                                        "text": "THL 3",
                                        "parent_id": 4
                                    }]
                                }]
                            }]
                        }]
					},
					"types": {
						"default": {
							"icon": "fa fa-folder"
						}
					},
					"plugins": ["types", "search"]
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

				$('#tree1').on('activate_node.jstree', function (e, data) {
					if (data.instance.is_leaf(data.node)) {
						var template_id = data.node.id;
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							url: base_url + "/admin/" + buyer + "/template-management/template-view",
							type: "Post",
							dataType: "json",
							data: {
								template_id: template_id,
								_token: csrf_token
							},
							beforeSend: function () {
								$(".loader").show();
								$(".loader").css('margin-top', '12%');
							},
							success: function (data) {
								$.ajax({
									url: base_url + "/admin/tesco/template-management/get-company",
									type: "get",
									dataType: "json",
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company = "";
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index1, value1) {
												tax += "<option value=" + value1.id + ">" + value1.tax_type + "("+value1.tax_code+")</option>";
											});
											$('.viewDiv').html('');
											$.each(data, function (index, value) {
												temp_id = value[0].id;
												$('.viewDiv').append('<div class="col-md-12"><div class="row"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object></div><div class="row" style="margin-top:15px;"><form role="form" id = "update_tesco_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_' + temp_id + '">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="' + value[0].template_name + '" name="ref_' + temp_id + '" id="ref_' + temp_id + '" required disabled> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_' + temp_id + '">Company Name</label> <select class="form-control input-sm " name="company_' + temp_id + '" id="company_' + temp_id + '" style="width: 100%;">' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_' + temp_id + '">Taxation :</label> <select class="form-control input-sm " name="tax_' + temp_id + '" id="tax_' + temp_id + '" style="width: 100%;">' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_' + temp_id + '">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="text" value="' + value[0].hsn_code + '" name="hsn_code_' + temp_id + '" id="hsn_code_' + temp_id + '" maxlength = "4" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="sap_ref_' + temp_id + '">SAP Ref No:</label> <input class="form-control input-sm" placeholder="SAP Ref. No" required type="text" value="' + value[0].sap_ref + '" name="sap_ref_' + temp_id + '" id="sap_ref_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_' + temp_id + '">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="text" value="' + value[0].gl_code + '" name="gl_code_' + temp_id + '" id="gl_code_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_' + temp_id + '">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="' + value[0].color + '" name="color_' + temp_id + '" id="color_' + temp_id + '" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_' + temp_id + '">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="text" value="' + value[0].dimension_height + '" name="dimension_height_' + temp_id + '" id="dimension_height_' + temp_id + '" maxlength = "5" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_' + temp_id + '">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="text" value="' + value[0].dimension_width + '" name="dimension_width_' + temp_id + '" id="dimension_width_' + temp_id + '" maxlength = "5" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_' + temp_id + '">Material :</label> <input class="form-control input-sm" placeholder="Material" required type="text" value="' + value[0].material + '" name="material_' + temp_id + '" id="material_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="' + temp_id + '" >Save</button><button class="btn btn-primary btn-sm " type="button" onclick="showMoreDetails(' + temp_id + ')"> + More Details</button> </div><div class="clearfix"></div></form></div></div></div></div></div>')
												$('#company_' + temp_id).val(value[0].company_id)
												$('#tax_' + temp_id).val(value[0].tax_code_id)
											});

										    /* Template details edit form */
											$('#update_tesco_template_data_form').validator().on('submit', function (e) {
										        if (e.isDefaultPrevented())
										        {

										        }
										        else
										        {
										           e.preventDefault();
										           saveTescoTax(temp_id);
										        }
										      })
										}

									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {}
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
				});

			} else {
				$('#tree1').jstree({
					'core': {
						'data': data
					},
					"types": {
						"default": {
							"icon": "fa fa-folder"
						}
					},
					"plugins": ["types", "search"]
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

				$('#tree1').on('activate_node.jstree', function (e, data) {
					if (data.instance.is_leaf(data.node)) {
						var template_id = data.node.id;
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							url: base_url + "/admin/" + buyer + "/template-management/template-view",
							type: "Post",
							dataType: "json",
							data: {
								template_id: template_id,
								_token: csrf_token
							},
							beforeSend: function () {
								$(".loader").show();
								$(".loader").css('margin-top', '12%');
							},
							success: function (data) {
								$.ajax({
									url: base_url + "/admin/orchestra/template-management/get-company-list",
									type: "get",
									dataType: "json",
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company = "";
											var temp_id = data[0].id;
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index1, value1) {
												tax += "<option value=" + value1.id + ">" + value1.tax_type + "("+value1.tax_code+")</option>";
											});
											if (data.length > 0) {
												if (data[0].template_file != "") {
													if (data.length > 1) {
														$('.viewDiv').html("");
														$.each(data, function (index, value) {
															$('.viewDiv').append('<div class="row"><object style="display:inline-block" id="svg2" class="center-block svg2" data="' + value.template_file + '" type="image/svg+xml"></object></div>')
														});
													} else {
														$('.viewDiv').html('<div class="row"><object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object></div>')
													}

													$('.descriptionDiv').html('<div class="col-md-12"><div class="row" style="margin-top:15px;"><form role="form" id = "update_orch_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_' + temp_id + '">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="' + data[0].template_name + '" name="ref_' + temp_id + '" id="ref_' + temp_id + '" required disabled> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_' + temp_id + '">Company Name</label> <select class="form-control input-sm " name="company_' + temp_id + '" id="company_' + temp_id + '" style="width: 100%;">' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_' + temp_id + '">Taxation :</label> <select class="form-control input-sm " name="tax_' + temp_id + '" id="tax_' + temp_id + '" style="width: 100%;">' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_' + temp_id + '">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="text" value="' + data[0].hsn_code + '" name="hsn_code_' + temp_id + '" id="hsn_code_' + temp_id + '" maxlength = "4" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="sap_ref_' + temp_id + '">SAP Ref No:</label> <input class="form-control input-sm" placeholder="SAP Ref. No" required type="text" value="' + data[0].sap_ref + '" name="sap_ref_' + temp_id + '" id="sap_ref_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_' + temp_id + '">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="text" value="' + data[0].gl_code + '" name="gl_code_' + temp_id + '" id="gl_code_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_' + temp_id + '">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="' + data[0].color + '" name="color_' + temp_id + '" id="color_' + temp_id + '" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_' + temp_id + '">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="text" value="' + data[0].dimension_height + '" name="dimension_height_' + temp_id + '" id="dimension_height_' + temp_id + '" maxlength = "5" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_' + temp_id + '">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="text" value="' + data[0].dimension_width + '" name="dimension_width_' + temp_id + '" id="dimension_width_' + temp_id + '" maxlength = "5" onkeypress = "return isNumberKey(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_' + temp_id + '">Material :</label> <input class="form-control input-sm" placeholder="Material" required type="text" value="' + data[0].material + '" name="material_' + temp_id + '" id="material_' + temp_id + '" maxlength = "20"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="' + temp_id + '" >Save</button></div><div class="clearfix"></div></form></div></div></div></div></div>')
													$('.descriptionDiv').css('margin-top', '20px')
													$('.viewDiv').css('margin-top', '0px')
													/* Template details edit form */
														$('#update_orch_template_data_form').validator().on('submit', function (e) {
													        if (e.isDefaultPrevented())
													        {

													        }
													        else
													        {
													           e.preventDefault();
													           saveTescoTax(temp_id);
													        }
												      })

												} else {
													$('.viewDiv').html('<img style="height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')

													$('.descriptionDiv').html('<b>Template Not Found</b>');
													$('.descriptionDiv').css('margin-top', '0px')
													$('.viewDiv').css('margin-top', '140px')
												}
											} else {
												$('.viewDiv').html('<img style="height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
												$('.descriptionDiv').html('<b>Template Not Found</b>');
												$('.descriptionDiv').css('margin-top', '0px')
												$('.viewDiv').css('margin-top', '140px')
											}
											$('#company_' + data[0].id).val(data[0].company_id)
											$('#tax_' + temp_id).val(data[0].tax_code_id)

										}
									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {}
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
				});
			}
		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
			$(".loader").css('margin-top', '0%');
		}
	})
}

function saveTescoTax(temp_id) {
	var tax = $('#tax_' + temp_id).val();
	var ref = $('#ref_' + temp_id).val();
	var company = $('#company_' + temp_id).val();
	var hsncode = $('#hsn_code_' + temp_id).val();
	var sapcode = $('#sap_ref_' + temp_id).val();
	var glcode = $('#gl_code_' + temp_id).val();
	var color = $('#color_' + temp_id).val();
	var material = $('#material_' + temp_id).val();
	var height = $('#dimension_height_' + temp_id).val();
	var width = $('#dimension_width_' + temp_id).val();
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/template-management/save-taxation",
		type: "POST",
		dataType: "json",
		data: {
			template_id: temp_id,
			tax: tax,
			ref: ref,
			company: company,
			hsncode:hsncode,
			sapcode:sapcode,
			glcode:glcode,
			color:color,
			material:material,
			height:height,
			width:width,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
			$(".loader").css('margin-top', '12%');
		},
		success: function (data) {
			$('.snackbar_span').html('Saved Successfully');
			showSnackbar();
		},
		error: function (data) {
			$('.snackbar_span').html('Unable to save. Try again!!');
			showSnackbar();
		},
		complete: function () {
			$(".loader").hide();
			$(".loader").css('margin-top', '0%');
		}
	});
}
function saveOrchestraTax(temp_id) {
	var tax = $('#tax_' + temp_id).val();
	var ref = $('#ref_' + temp_id).val();
	var company = $('#company_' + temp_id).val();
	var hsncode = $('#hsn_code_' + temp_id).val();
	var sapcode = $('#sap_ref_' + temp_id).val();
	var glcode = $('#gl_code_' + temp_id).val();
	var color = $('#color_' + temp_id).val();
	var material = $('#material_' + temp_id).val();
	var height = $('#dimension_height_' + temp_id).val();
	var width = $('#dimension_width_' + temp_id).val();
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/template-management/save-taxation",
		type: "POST",
		dataType: "json",
		data: {
			template_id: temp_id,
			tax: tax,
			ref: ref,
			company: company,
			hsncode:hsncode,
			sapcode:sapcode,
			glcode:glcode,
			color:color,
			material:material,
			height:height,
			width:width,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
			$(".loader").css('margin-top', '12%');
		},
		success: function (data) {
			$('.snackbar_span').html('Saved Successfully');
			showSnackbar();
		},
		error: function (data) {
			$('.snackbar_span').html('Unable to save. Try again!!');
			showSnackbar();
		},
		complete: function () {
			$(".loader").hide();
			$(".loader").css('margin-top', '0%');
		}
	});
}

	function showMoreDetails(id)
	{
		$('#template_id').val(id);
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
		url: base_url + "/admin/" + buyer + "/template-details",
		type: "GET",
		dataType: "json",
		data: {
			template_id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
			$(".loader").css('margin-top', '12%');
		},
		success: function (data) {
			$('.featuresDiv').html('')
			if (data.data.length > 0) {
				$(data.data).each(function (key, index) {
					
                	$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]' style='resize: vertical;min-height:10vh;'>"+data.data[key].notes+"</textarea></div>");
           		});
			}
			else{
				$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]' placeholder='Enter details' style='resize: vertical;min-height:10vh;'></textarea></div>");
			}
			
		},
		error: function (data) {
			$('.snackbar_span').html('Something went wrong!!');
			showSnackbar();
		},
		complete: function () {
			$(".loader").hide();
			$(".loader").css('margin-top', '0%');
		}
	});

		$('#moreDetails').modal("show");
		$('#moreDetails').removeData('.modal');
	}

/**
 * To show the snackbar
 */
function showSnackbar() {
	// Get the snackbar DIV
	var x = document.getElementById("snackbar");
	// Add the "show" class to DIV
	x.className = "show";
	// After 3 seconds, remove the show class from DIV
	setTimeout(function () {
		x.className = x.className.replace("show", "");
	}, 3000);
}
