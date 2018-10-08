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
			$('.main-content').html('<div class="row"><div class="col-md-4"><button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand">Expand</button> <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse">Collapse</button><div class="search-tree"> <input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="card js-card" id="style-4" style="height:calc(100vh - 110px); overflow-y:scroll; position:relative;margin-bottom: 15px;"><div class="jaytree force-overflow" id="tree1"> </div></div></div><div class="col-md-8"><div class="card preview-tag" style="height:calc(100vh - 110px);"><div class="loader"></div><div class="viewDiv"> </div><div class="descriptionDiv">  </div></div> </div> </div>')

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
									"children": [{
										"id": "5,6,7",
										"text": "TCL 1 , TCL 2 & TSL 1",
										"parent_id": 3
                                    }, {
										"id": "8,9",
										"text": "TCL 4 & TSL 3",
										"parent_id": 3
                                    }, {
										"id": "10,11",
										"text": "THL 1 & THL 3",
										"parent_id": 3
                                    }]
                                }, {
									"id": 4,
									"text": "UK",
									"parent_id": 2,
									"children": [{
										"id": "12,13,14",
										"text": "TCL 1 , TCL 2 & TSL 1",
										"parent_id": 4
                                    }, {
										"id": "15,16",
										"text": "TCL 4 & TSL 3",
										"parent_id": 4
                                    }, {
										"id": "17,18",
										"text": "THL 1 & THL 3",
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
												if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
													$('.viewDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Reference No :</td><td class="pull-left"><div class="input-group"><input disabled class="form-control input-sm " placeholder="Enter Reference No" required type="text" value="' + value[0].template_name + '" name="name_' + value[0].id + '" step="0.01" id="name_' + value[0].id + '" style="width: 70px;"></div></td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left"><div class="input-group"><select class="form-control input-sm "  name="company_' + value[0].id + '" id="company_' + value[0].id + '" style="width: 70px;">' + company + '</select></div></td></tr><tr><td class="pull-left">Taxation : </td><td class="pull-left"><div class="input-group"><select class="form-control input-sm "  name="tax_' + value[0].id + '" id="tax_' + value[0].id + '" style="width: 70px;">' + tax + '</select></div></td><td class="pull-left">SAP Ref : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="SAP Ref. No" required type="text" value="' + value[0].sap_ref + '" name="sap_ref' + value[0].id + '" id="sap_ref' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">GL Code : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="GL Code" required type="text" value="' + value[0].gl_code + '" name="gl_code_' + value[0].id + '" id="gl_code_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">HSN Code : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="HSN Code" required type="text" value="' + value[0].hsn_code + '" name="hsn_code_' + value[0].id + '" id="hsn_code_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Color : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Color" required type="text" value="' + value[0].color + '" name="color_' + value[0].id + '" id="color_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Material : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Material" required type="text" value="' + value[0].material + '" name="material_' + value[0].id + '" id="material_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Height : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Height" required type="text" value="' + value[0].dimension_height + '" name="dimension_height_' + value[0].id + '" id="dimension_height_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Width : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Width" required type="text" value="' + value[0].dimension_width + '" name="dimension_width_' + value[0].id + '" id="dimension_width_' + value[0].id + '" style="width: 70px;"></div></td></tr></tbody></table><button class="btn btn-secondary btn-sm pull-right" type="button" id="' + value[0].id + '" onclick="saveTescoTax(this.id)">Save</button><button class="btn btn-secondary btn-sm pull-left" type="button" onclick="showMoreDetails(' + value[0].id + ')">More Details</button></div></div>')

												} else {
													$('.viewDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Reference No :</td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Enter Reference No" disabled type="text" value="' + value[0].template_name + '" name="name_' + value[0].id + '" step="0.01" id="name_' + value[0].id + '" style="width: 70px;"></div></td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left"><div class="input-group"><select class="form-control input-sm "  name="company_' + value[0].id + '" id="company_' + value[0].id + '" style="width: 70px;">' + company + '</select></div></td></tr><tr><td class="pull-left">Taxation : </td><td class="pull-left"><div class="input-group"><select class="form-control input-sm "  name="tax_' + value[0].id + '" id="tax_' + value[0].id + '" style="width: 70px;">' + tax + '</select></div></td><td class="pull-left">SAP Ref : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="SAP Ref. No" required type="text" value="' + value[0].sap_ref + '" name="sap_ref' + value[0].id + '" id="sap_ref' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">GL Code : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="GL Code" required type="text" value="' + value[0].gl_code + '" name="gl_code_' + value[0].id + '" id="gl_code_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">HSN Code : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="HSN Code" required type="text" value="' + value[0].hsn_code + '" name="hsn_code_' + value[0].id + '" id="hsn_code_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Color : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Color" required type="text" value="' + value[0].color + '" name="color_' + value[0].id + '" id="color_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Material : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Material" required type="text" value="' + value[0].material + '" name="material_' + value[0].id + '" id="material_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Height : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Height" required type="text" value="' + value[0].dimension_height + '" name="dimension_height_' + value[0].id + '" id="dimension_height_' + value[0].id + '" style="width: 70px;"></div></td><td class="pull-left">Width : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Width" required type="text" value="' + value[0].dimension_width + '" name="dimension_width_' + value[0].id + '" id="dimension_width_' + value[0].id + '" style="width: 70px;"></div></td></tr></tbody></table><button class="btn btn-secondary btn-sm pull-right" type="button" id="' + value[0].id + '" onclick="saveTescoTax(this.id)">Save</button><button class="btn btn-secondary btn-sm pull-left" type="button" onclick="showMoreDetails(' + value[0].id + ')">More Details</button></div></div>')
												}
												$('#company_' + value[0].id).val(value[0].company_id)
											});
											$('.viewDiv').css('width', '100%')
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
									url: base_url + "/admin/tesco/template-management/get-company",
									type: "get",
									dataType: "json",
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company = "";
											$.each(companydata, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											console.log(data);
											if (data.length > 0) {
												if (data[0].template_file != "") {
													if (data.length > 1) {
														$('.viewDiv').html("");
														$.each(data, function (index, value) {
															$('.viewDiv').append('<object style="display:inline-block" id="svg2" class="center-block svg2" data="' + value.template_file + '" type="image/svg+xml"></object>')
														});
													} else {
														$('.viewDiv').html('<object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
													}
													$('.descriptionDiv').html('<div class="col-md-4"><label>Template Name : </label><input class="form-control center-block input-sm " placeholder="Enter Template Name" required type="text" disabled value="' + data[0].template_name + '" name="template_name_' + data[0].id + '" id="template_name_' + data[0].id + '" style="width: 120px;"><label>Reference Number : </label><input class="form-control center-block input-sm " placeholder="Enter Reference Number" required type="text" value="' + data[0].ref_no + '" name="ref_' + data[0].id + '" id="ref_' + data[0].id + '" style="width: 120px;"></div><div class="col-md-4"><label>Company Name : </label><select class="form-control center-block input-sm "  name="company_' + data[0].id + '" id="company_' + data[0].id + '" style="width: 150px;">' + company + '</select><label>Tax : </label><input class="form-control center-block input-sm " placeholder="Enter Tax" required type="number" value="' + data[0].tax + '" name="tax_' + data[0].id + '" step="0.01" id="tax_' + data[0].id + '" style="width: 120px;"></div><div class="col-md-4"><label>Inside Tamilnadu: </label><input class="form-control center-block input-sm " placeholder="Inside Tamilnadu" required type="text" value="' + data[0].inside_tamilnadu + '" name="inside_tamilnadu_' + data[0].id + '" id="inside_tamilnadu_' + data[0].id + '" style="width: 120px;"><label>Outside Tamilnadu: </label><input class="form-control center-block input-sm " placeholder="Outside Tamilnadu" required type="text" value="' + data[0].outside_tamilnadu + '" name="outside_tamilnadu_' + data[0].id + '" id="outside_tamilnadu_' + data[0].id + '" style="width: 120px;"></div><button class="btn btn-secondary btn-sm pull-right" type="button" id="' + data[0].id + '" onclick="saveOrchestraTax(this.id)">Save</button>')
													$('.descriptionDiv').css('margin-top', '20px')
													$('.viewDiv').css('margin-top', '0px')

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
	var name = $('#name_' + temp_id).val();
	var company = $('#company_' + temp_id).val();
	var inside_tamilnadu = $('#inside_tamilnadu_' + temp_id).val();
	var outside_tamilnadu = $('#outside_tamilnadu_' + temp_id).val();
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
			name: name,
			company: company,
			inside_tamilnadu: inside_tamilnadu,
			outside_tamilnadu: outside_tamilnadu,
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
	var name = $('#name_' + temp_id).val();
	var company = $('#company_' + temp_id).val();
	var inside_tamilnadu = $('#inside_tamilnadu_' + temp_id).val();
	var outside_tamilnadu = $('#outside_tamilnadu_' + temp_id).val();
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
			name: name,
			company: company,
			inside_tamilnadu: inside_tamilnadu,
			outside_tamilnadu: outside_tamilnadu,
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
					
                	$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]'>"+data.data[key].notes+"</textarea></div>");
           		});
			}
			else{
				$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]' placeholder='Enter details'></textarea></div>");
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
		console.log(id)
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
