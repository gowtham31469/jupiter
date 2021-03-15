
/**  
 * Validation for numeric values *
 * @param    evt 
 * @return {Boolean}
 * @author <Sumanth>
 */
 function isNumber(evt) {
  try{
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
        {
            return false;
        }
          return true;
    } catch (e) {
     console.log("Error:"+e)
   }
  }

function template_list() {
	var buyer = localStorage.getItem("buyer");
	
	$.ajax({
		url: base_url + "/admin/template-management/template-hierarchy",
		type: "Post",
		dataType: "json",
		data: {
			_token: csrf_token,
			buyer:buyer
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
						'data': data
					},
					"types": {
						"default": {
							"icon": "fa fa-folder"
						}
					},
					"plugins": ["types", "search"]
				}).on('loaded.jstree', function () {
					$('#tree1').jstree('open_all');
				}).bind("refresh.jstree", function (e, data) 
					{ $(this).jstree("open_all");
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
											$.each(companydata.tax, function (index2, value2) {
												tax += "<option value=" + value2.tax + ">" + value2.tax + "</option>";
											});
											var material = "";
											$.each(companydata.material, function (index3, value3) {
												material += "<option value=" + value3.id + ">" + value3.material_name_employee + "</option>";
											});

											var name_in_reports = "";
											$.each(companydata.name_in_reports, function (index4, value4) {
												
												name_in_reports += "<option value=" + index4 + ">" + value4 + "</option>";
											});

											$('.viewDiv').html('');
											$.each(data, function (index, value) {
												temp_id = value[0].id;
												var gl_text = value[0].gl_text === null?'':value[0].gl_text;
												var color = value[0].color === null?'':value[0].color;
												var hsn_code = value[0].hsn_code === null?'':value[0].hsn_code;

												$('.viewDiv').append('<div class="col-md-12"><div class="row"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object></div><div class="row" style="margin-top:15px;"><form role="form" id = "update_tesco_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_' + temp_id + '">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="' + value[0].template_name + '" name="ref_' + temp_id + '" id="ref_' + temp_id + '" required disabled> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_' + temp_id + '">Company Name :</label> <select class="form-control input-sm " name="company_' + temp_id + '" id="company_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a company</option>' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_' + temp_id + '">Taxation Value (%):</label> <select class="form-control input-sm " name="tax_' + temp_id + '" id="tax_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a taxation value</option>' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_' + temp_id + '">Material :</label> <select class="form-control input-sm " name="material_' + temp_id + '" id="material_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a material</option>'+ material +'</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_text_' + temp_id + '">GL Text :</label> <input class="form-control input-sm" placeholder="GL Text" required type="text" value="' + gl_text + '" name="gl_text_' + temp_id + '" id="gl_text_' + temp_id + '" maxlength = "25"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_' + temp_id + '">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="number" min="1" value="' + value[0].gl_code + '" name="gl_code_' + temp_id + '" id="gl_code_' + temp_id + '" maxlength = "15" onkeypress = "return isNumber(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_' + temp_id + '">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="' + color + '" name="color_' + temp_id + '" id="color_' + temp_id + '" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_' + temp_id + '">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="number" min="1" value="' + hsn_code + '" name="hsn_code_' + temp_id + '" id="hsn_code_' + temp_id + '" maxlength = "10" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_' + temp_id + '">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="number" min="1" value="' + value[0].dimension_height + '" name="dimension_height_' + temp_id + '" id="dimension_height_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_' + temp_id + '">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="number" min="1" value="' + value[0].dimension_width + '" name="dimension_width_' + temp_id + '" id="dimension_width_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <label class="pull-left" for=name_in_reports_' + temp_id + '">Name in reports:</label> <select class="form-control input-sm " name="name_in_reports_' + temp_id + '" id="name_in_reports_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a name</option>'+ name_in_reports +'</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="' + temp_id + '" >Save</button><button class="btn btn-primary btn-sm " type="button" onclick="showMoreDetails(' + temp_id + ')"> + More Details</button> </div><div class="clearfix"></div></form></div></div></div></div></div>')
												
												//$('#'+temp_id).prop('disabled',false)
												$('#company_' + temp_id).val(value[0].company_id)
												$('#tax_' + temp_id).val(value[0].tax)
												$('#material_' + temp_id).val(value[0].material_id)
												$('#name_in_reports_' + temp_id).val(value[0].name_in_reports)
												
												if(value[0].tax == 0)
												{
													$('#tax_'+value[0].id).val("")
												}
												
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

			} else if (buyer == "orchestra")  {
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
				
				/*The showModal method is a temporary method created and used to update the path of 
				 the templates in the heirarchy */
					//showModal(data.node.id)	
					
					if (data.instance.is_leaf(data.node)) {
						var template_id = data.node.id;
						
						$.ajax({
							url: base_url + "/admin/" + buyer + "/template-management/template-list-view",
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
								if (data.length > 0) {
									$.ajax({
									url: base_url + "/admin/orchestra/template-management/get-company-list",
									type: "get",
									dataType: "json",
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company  = "";
											var temp_id  = data[0].id;
											var gl_text  = data[0].gl_text === null?'':data[0].gl_text;
											var color    = data[0].color === null?'':data[0].color;
											var hsn_code = data[0].hsn_code === null?'':data[0].hsn_code;
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index2, value2) {
												tax += "<option value=" + value2.tax + ">" + value2.tax + "</option>";
											});
											var material = "";
											$.each(companydata.material, function (index3, value3) {
												material += "<option value=" + value3.id + ">" + value3.material_name_employee + "</option>";
											});

											var name_in_reports = "";
											$.each(companydata.name_in_reports, function (index4, value4) {
												
												name_in_reports += "<option value=" + index4 + ">" + value4 + "</option>";
											});

											if (data.length > 0) {
												if (data[0].template_file != "" && data[0].template_file != null) {
													
													if( data[0].template_file.substr(data[0].template_file.length - 3) == "svg")
													{
														if (data.length > 1) {
														$('.viewDiv').html('<div class="row">');
														$.each(data, function (index, value) {
															$('.viewDiv').append('<object style="display:inline-block" id="svg2" class="center-block svg2" data="' + value.template_file + '" type="image/svg+xml"></object>')
														});
														$('.viewDiv').append('</div>');
													} else {
														$('.viewDiv').html('<div class="row"><object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object></div>')
													}
													}
													else
													{
														$('.viewDiv').html('<img style="    width: 100px;margin-left: 50%;object-fit:cover" src="' + data[0].template_file + '" class="img-responsive" alt="Template File">');
													}
													

													$('.descriptionDiv').html('<div class="col-md-12"><div class="row" style="margin-top:15px;"><form role="form" id = "update_orch_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_' + temp_id + '">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="' + data[0].template_name + '" name="ref_' + temp_id + '" id="ref_' + temp_id + '" required disabled> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_' + temp_id + '">Company Name :</label> <select class="form-control input-sm " name="company_' + temp_id + '" id="company_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a company</option>' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_' + temp_id + '">Taxation Value (%):</label> <select class="form-control input-sm " name="tax_' + temp_id + '" id="tax_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a taxation value</option>' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_' + temp_id + '">Material :</label> <select class="form-control input-sm " name="material_' + temp_id + '" id="material_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a material</option>'+ material +'</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_text_' + temp_id + '">GL Text :</label> <input class="form-control input-sm" placeholder="GL Text" required type="text" value="' + gl_text + '" name="gl_text_' + temp_id + '" id="gl_text_' + temp_id + '" maxlength = "25"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_' + temp_id + '">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="number" min="1" value="' + data[0].gl_code + '" name="gl_code_' + temp_id + '" id="gl_code_' + temp_id + '" maxlength = "15" onkeypress = "return isNumber(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_' + temp_id + '">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="' + color + '" name="color_' + temp_id + '" id="color_' + temp_id + '" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_' + temp_id + '">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="number" min="1" value="' + hsn_code + '" name="hsn_code_' + temp_id + '" id="hsn_code_' + temp_id + '" maxlength = "10" > <div class="help-block with-errors"></div></div> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_' + temp_id + '">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="number" min="1" value="' + data[0].dimension_height + '" name="dimension_height_' + temp_id + '" id="dimension_height_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_' + temp_id + '">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="number" min="1" value="' + data[0].dimension_width + '" name="dimension_width_' + temp_id + '" id="dimension_width_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <label class="pull-left" for=name_in_reports_' + temp_id + '">Name in reports:</label> <select class="form-control input-sm " name="name_in_reports_' + temp_id + '" id="name_in_reports_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a name</option>'+ name_in_reports +'</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="' + temp_id + '" >Save</button></div><div class="clearfix"></div></form></div></div></div></div></div>')
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
											$('#'+temp_id).removeClass('disabled');		
												} else {
													$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')

													$('.descriptionDiv').html('<b>Template Not Found</b>');
													$('.descriptionDiv').css('margin-top', '0px')
													$('.viewDiv').css('margin-top', '140px')
												}
											} else {
												$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
												$('.descriptionDiv').html('<b>Template Not Found</b>');
												$('.descriptionDiv').css('margin-top', '0px')
												$('.viewDiv').css('margin-top', '140px')
											}
											$('#company_' + data[0].id).val(data[0].company_id)
											$('#tax_' + temp_id).val(data[0].tax)
											$('#material_' + temp_id).val(data[0].material_id)
											$('#name_in_reports_' + temp_id).val(data[0].name_in_reports)
											if(data[0].tax == 0)
											{
												$('#tax_'+data[0].id).val("")
											}
										}
									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {}
								})
								}
								else {
									$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
												$('.descriptionDiv').html('<b>Template Not Found</b>');
												$('.descriptionDiv').css('margin-top', '0px')
												$('.viewDiv').css('margin-top', '140px')
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
				});
			}
			else  {
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
						
						$.ajax({
							url: base_url + "/admin/template-management/template-view",
							type: "Post",
							dataType: "json",
							data: {
								template_id: template_id,
								_token: csrf_token,
								buyer:buyer
							},
							beforeSend: function () {
								$(".loader").show();
								$(".loader").css('margin-top', '12%');
							},
							success: function (data) {
								if(data.length > 0)
								{
									$.ajax({
									url: base_url + "/admin/template-management/get-company-list",
									type: "get",
									dataType: "json",
									data: {
										buyer:buyer
									},
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company  = "";
											var temp_id  = data[0].id;
											var gl_text  = data[0].gl_text === null?'':data[0].gl_text;
											var color    = data[0].color === null?'':data[0].color;
											var hsn_code = data[0].hsn_code === null?'':data[0].hsn_code;
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index2, value2) {
												tax += "<option value=" + value2.tax + ">" + value2.tax + "</option>";
											});
											var material = "";
											$.each(companydata.material, function (index3, value3) {
												material += "<option value=" + value3.id + ">" + value3.material_name_employee + "</option>";
											});

											var name_in_reports = "";
											$.each(companydata.name_in_reports, function (index4, value4) {
												
												name_in_reports += "<option value=" + index4 + ">" + value4 + "</option>";
											});

											if (data.length > 0) {
												if (data[0].template_file == "" || data[0].template_file == null) {
													$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
													$('.descriptionDiv').html('<b>Template Not Found</b>');
													$('.descriptionDiv').css('margin-top', '0px')
													$('.viewDiv').css('margin-top', '140px')
												} else {
													
													if (data.length > 1) {
														$('.viewDiv').html("");
														$.each(data, function (index, value) {
															$('.viewDiv').append('<div class="row"><object style="display:inline-block" id="svg2" class="center-block svg2" data="' + value.template_file + '" type="image/svg+xml"></object></div>')
														});
													} else {
														$('.viewDiv').html('<div class="row"><object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object></div>')
													}
												}
													$('.descriptionDiv').html('<div class="col-md-12"><div class="row" style="margin-top:15px;"><form role="form" id = "update_orch_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_' + temp_id + '">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="' + data[0].template_name + '" name="ref_' + temp_id + '" id="ref_' + temp_id + '" required disabled> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_' + temp_id + '">Company Name :</label> <select class="form-control input-sm " name="company_' + temp_id + '" id="company_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a company</option>' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_' + temp_id + '">Taxation Value (%):</label> <select class="form-control input-sm " name="tax_' + temp_id + '" id="tax_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a taxation value</option>' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_' + temp_id + '">Material :</label> <select class="form-control input-sm " name="material_' + temp_id + '" id="material_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a material</option>'+ material +'</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_text_' + temp_id + '">GL Text :</label> <input class="form-control input-sm" placeholder="GL Text" required type="text" value="' + gl_text + '" name="gl_text_' + temp_id + '" id="gl_text_' + temp_id + '" maxlength = "25"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_' + temp_id + '">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="number" min="1" value="' + data[0].gl_code + '" name="gl_code_' + temp_id + '" id="gl_code_' + temp_id + '" maxlength = "15" onkeypress = "return isNumber(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_' + temp_id + '">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="' + color + '" name="color_' + temp_id + '" id="color_' + temp_id + '" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_' + temp_id + '">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="number" min="1" value="' + hsn_code + '" name="hsn_code_' + temp_id + '" id="hsn_code_' + temp_id + '" maxlength = "10" > <div class="help-block with-errors"></div></div> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_' + temp_id + '">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="number" min="1" value="' + data[0].dimension_height + '" name="dimension_height_' + temp_id + '" id="dimension_height_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_' + temp_id + '">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="number" min="1" value="' + data[0].dimension_width + '" name="dimension_width_' + temp_id + '" id="dimension_width_' + temp_id + '" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"><label class="pull-left" for=name_in_reports_' + temp_id + '">Name in reports:</label> <select class="form-control input-sm " name="name_in_reports_' + temp_id + '" id="name_in_reports_' + temp_id + '" style="width: 100%;" required=""><option value="">Select a name</option>'+ name_in_reports +'</select> <div class="help-block with-errors"></div> </div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="' + temp_id + '" >Save</button></div><div class="clearfix"></div></form></div></div></div></div></div>')
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
												$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
												$('.descriptionDiv').html('<b>Template Not Found</b>');
												$('.descriptionDiv').css('margin-top', '0px')
												$('.viewDiv').css('margin-top', '140px')
											}
											$('#company_' + data[0].id).val(data[0].company_id)
											$('#tax_' + temp_id).val(data[0].tax)
											$('#material_' + temp_id).val(data[0].material_id)
											$('#name_in_reports_' + temp_id).val(data[0].name_in_reports)
											if(data[0].tax == 0)
											{
												$('#tax_'+data[0].id).val("")
											}
										}
									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {}
								})
								}
								else
								{
									$.ajax({
									url: base_url + "/admin/template-management/get-company-list",
									type: "get",
									dataType: "json",
									data: {
										buyer:buyer
									},
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company  = "";
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index2, value2) {
												tax += "<option value=" + value2.tax + ">" + value2.tax + "</option>";
											});
											var material = "";
											$.each(companydata.material, function (index3, value3) {
												material += "<option value=" + value3.id + ">" + value3.material_name_employee + "</option>";
											});

											var name_in_reports = "";
											$.each(companydata.name_in_reports, function (index4, value4) {
												
												name_in_reports += "<option value=" + index4 + ">" + value4 + "</option>";
											});

//											alert()
									$('.descriptionDiv').html('<div class="col-md-12"><div class="row" style="margin-top:15px;"><form role="form" id = "add_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_new">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="" name="ref_new" id="ref_new" required> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_new">Company Name :</label> <select class="form-control input-sm " name="company_new" id="company_new" style="width: 100%;" required=""><option value="">Select a company</option>' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_new">Taxation Value (%):</label> <select class="form-control input-sm " name="tax_new" id="tax_new" style="width: 100%;" required=""><option value="">Select a taxation value</option>' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_new">Material :</label> <select class="form-control input-sm " name="material_new" id="material_new" style="width: 100%;" required=""><option value="">Select a material</option>'+ material +'</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_text_new">GL Text :</label> <input class="form-control input-sm" placeholder="GL Text" required type="text" value="" name="gl_text_new" id="gl_text_new" maxlength = "25"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_new">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="number" min="1" value="" name="gl_code_new" id="gl_code_new" maxlength = "15" onkeypress = "return isNumber(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_new">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="" name="color_new" id="color_new" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_new">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="number" min="1" value="" name="hsn_code_new" id="hsn_code_new" maxlength = "10" > <div class="help-block with-errors"></div></div> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_new">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="number" min="1" value="" name="dimension_height_new" id="dimension_height_new" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_new">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="number" min="1" value="" name="dimension_width_new" id="dimension_width_new" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <label class="pull-left" for=name_in_reports_new">Name in reports:</label> <select class="form-control input-sm " name="name_in_reports_new" id="name_in_reports_new" style="width: 100%;" required=""><option value="">Select a name</option>'+ name_in_reports +'</select> <div class="help-block with-errors"></div> </div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="new" >Add</button></div><div class="clearfix"></div></form></div></div></div></div></div>')
									$('.descriptionDiv').css('margin-top', '20px')
									$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
									$('.descriptionDiv').css('margin-top', '0px')
									$('.viewDiv').css('margin-top', '0px')
									/* Template details edit form */
									$('#add_template_data_form').validator().on('submit', function (e) {
										if (e.isDefaultPrevented())
										{

										}
										else
										{
										   e.preventDefault();
										   addTemplate(template_id);
										}
								  })
										
											}

									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {}
								})
									
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
					else
					{
						var parent = data.instance.get_parent(data.node);
						if(parent != "#")
						{
								var template_id = data.node.id;
								$.ajax({
									url: base_url + "/admin/template-management/get-company-list",
									type: "get",
									dataType: "json",
									data: {
										buyer:buyer
									},
									beforeSend: function () {
										$(".loader").show();
									},
									statusCode: {
										200: function (companydata) {
											var company  = "";
											$.each(companydata.company, function (index1, value1) {
												company += "<option value=" + value1.id + ">" + value1.name + "</option>";
											});
											var tax = "";
											$.each(companydata.tax, function (index2, value2) {
												tax += "<option value=" + value2.tax + ">" + value2.tax + "</option>";
											});
											var material = "";
											$.each(companydata.material, function (index3, value3) {
												material += "<option value=" + value3.id + ">" + value3.material_name_employee + "</option>";
											});

											var name_in_reports = "";
											$.each(companydata.name_in_reports, function (index4, value4) {
												
												name_in_reports += "<option value=" + index4 + ">" + value4 + "</option>";
											});
//											alert()
									$('.descriptionDiv').html('<div class="col-md-12"><div class="row" style="margin-top:15px;"><form role="form" id = "add_template_data_form" data-toggle= "validator"> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="ref_new">Reference No :</label> <input class="form-control input-sm " placeholder="Enter Reference No" type="text" value="" name="ref_new" id="ref_new" required> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="company_new">Company Name :</label> <select class="form-control input-sm " name="company_new" id="company_new" style="width: 100%;" required=""><option value="">Select a company</option>' + company + '</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="tax_new">Taxation Value (%):</label> <select class="form-control input-sm " name="tax_new" id="tax_new" style="width: 100%;" required=""><option value="">Select a taxation value</option>' + tax + '</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="material_new">Material :</label> <select class="form-control input-sm " name="material_new" id="material_new" style="width: 100%;" required=""><option value="">Select a material</option>'+ material +'</select> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_text_new">GL Text :</label> <input class="form-control input-sm" placeholder="GL Text" required type="text" value="" name="gl_text_new" id="gl_text_new" maxlength = "25"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=gl_code_new">GL Code :</label> <input class="form-control input-sm" placeholder="GL Code" required type="number" min="1" value="" name="gl_code_new" id="gl_code_new" maxlength = "15" onkeypress = "return isNumber(event)"> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="color_new">Color :</label> <input class="form-control input-sm " placeholder="Color" required type="text" value="" name="color_new" id="color_new" maxlength = "10"> <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for="hsn_code_new">HSN Code :</label> <input class="form-control input-sm" placeholder="HSN Code" required type="number" min="1" value="" name="hsn_code_new" id="hsn_code_new" maxlength = "10" > <div class="help-block with-errors"></div></div> <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_height_new">Height (mm):</label> <input class="form-control input-sm" placeholder="Height in mm" required type="number" min="1" value="" name="dimension_height_new" id="dimension_height_new" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback"> <label class="pull-left" for=dimension_width_new">Width (mm):</label> <input class="form-control input-sm" placeholder="Width in mm" required type="number" min="1" value="" name="dimension_width_new" id="dimension_width_new" maxlength = "5" > <div class="help-block with-errors"></div></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <label class="pull-left" for=name_in_reports_new">Name in reports:</label> <select class="form-control input-sm " name="name_in_reports_new" id="name_in_reports_new" style="width: 100%;" required=""><option value="">Select a name</option>'+ name_in_reports +'</select> <div class="help-block with-errors"></div></div><div class="clearfix"></div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> </div><div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4"> <button class="btn btn-primary btn-sm pull-right" type="submit" id="new" >Add</button></div><div class="clearfix"></div></form></div></div></div></div></div>')
									$('.descriptionDiv').css('margin-top', '20px')
									$('.viewDiv').css('margin-top', '0px')
									/* Template details edit form */
									$('#add_template_data_form').validator().on('submit', function (e) {
										if (e.isDefaultPrevented())
										{

										}
										else
										{
										   e.preventDefault();
										   addTemplate(template_id);
										}
								  })
										
											}

									},
									error: function (errorXHR, errorStatus, errorThrown) {},
									complete: function () {$(".loader").hide();}
								})
						}
						else
						{
							$('.descriptionDiv').html('');
							$('.viewDiv').html('');
						}
					
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
	var glcode = $('#gl_code_' + temp_id).val();
	var color = $('#color_' + temp_id).val();
	var material = $('#material_' + temp_id).val();
	var height = $('#dimension_height_' + temp_id).val();
	var width = $('#dimension_width_' + temp_id).val();
	var buyer = localStorage.getItem("buyer");
	var gltext = $('#gl_text_' + temp_id).val();
	var name_in_reports = $('#name_in_reports_' + temp_id).val();
	
	$.ajax({
		url: base_url + "/admin/template-management/save-taxation",
		type: "POST",
		dataType: "json",
		data: {
			template_id: temp_id,
			tax: tax,
			ref: ref,
			company: company,
			hsncode:hsncode,
			glcode:glcode,
			color:color,
			material_id:material,
			height:height,
			width:width,
			_token: csrf_token,
			gltext:gltext,
			buyer:buyer,
			name_in_reports:name_in_reports
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

function addTemplate(template_id) {
	var tax = $('#tax_new').val();
	var ref = $('#ref_new').val();
	var company = $('#company_new').val();
	var hsncode = $('#hsn_code_new').val();
	var glcode = $('#gl_code_new').val();
	var color = $('#color_new').val();
	var material = $('#material_new').val();
	var height = $('#dimension_height_new').val();
	var width = $('#dimension_width_new').val();
	var buyer = localStorage.getItem("buyer");
	var gltext = $('#gl_text_new').val();
	var name_in_reports = $('#name_in_reports_new').val();
	
	$.ajax({
		url: base_url + "/admin/template-management/add-template",
		type: "POST",
		dataType: "json",
		data: {
			tax: tax,
			ref: ref,
			company: company,
			hsncode:hsncode,
			glcode:glcode,
			color:color,
			material_id:material,
			height:height,
			width:width,
			template_id:template_id,
			_token: csrf_token,
			gltext:gltext,
			buyer:buyer,
			name_in_reports:name_in_reports
		},
		beforeSend: function () {
			$(".loader").show();
			$(".loader").css('margin-top', '12%');
		},
		success: function (data) {
			//$('.snackbar_span').html('Added Successfully');
			//showSnackbar();
			location.reload();
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
					
                	$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]' style='resize: vertical;min-height:10vh;' required>"+data.data[key].notes+"</textarea></div>");
           		});
			}
			else{
				$('.featuresDiv').append("<div class='form-group'><textarea class='form-control input-sm' name='edit_details[]' placeholder='Enter details' style='resize: vertical;min-height:10vh;'required></textarea></div>");
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

