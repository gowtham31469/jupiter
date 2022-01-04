
/**
 * Make any string as camelCase
 * @param  {[type]} str [description]
 * @return {[type]}     [description]
 */
function camelize(str) {
	return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function (match, index) {
		if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
		return index == 0 ? match.toLowerCase() : match.toUpperCase();
	});
}

/**
 * This Function is used to show tesco orders page and template selection.
 * @author Praveen
 * @return View
 */
var globalOrderType = 0;
var barcode_checks = 0;
var isSubmitting = false;
var globalParserZone = 0;
const itf_barcode_stickers  = ['TCL 1', 'TCL 2', 'TSL 1', 'THL 1', 'TCL 5'];
const ean_barcode_stickers  = ['TCL 4', 'TSL 3', 'THL 3'];
const ean_itf_barcode_stickers  = ['TCL 4', 'TSL 3', 'TSL 2'];
function tescoOrders(callback) {
	if(localStorage.getItem("buyer") == "orchestra") 
	{
			$('#financialYear').selectpicker('hide')
	}
	else
	{
			$('#financialYear').selectpicker('show')
	}
	$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
	$('.top-content').html('');
	$('.template-error').html('');
	/** [ Remove local storage values ] */
	localStorage.removeItem("templates");
	localStorage.removeItem("zone");
	localStorage.removeItem("template type");
	localStorage.removeItem("data");
	localStorage.removeItem("moq_values");
	localStorage.removeItem("template_id");
	localStorage.removeItem("template_group");
	localStorage.removeItem("customer_id");

	
	try {
		/** [ ajax call to load tesco order page ] **/
		$.ajax({
			url: base_url + "/admin/tesco/order-management/orders",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token,
				year:fiscal_year
			},
			tryCount: 0,
			retryLimit: 2,
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				var newObj1 = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders.concat(data.tesco_washcare_orders.concat(data.tesco_tcl5_orders.concat(data.tesco_tsl2_orders)))))))));
				
				newObj1.sort(function(obj1, obj2) {
					var date1 =obj1.created_at;
					var date2 =obj2.created_at;
					if (date1 > date2) return -1;
					if (date1 < date2) return 1;
					return 0;
				});
				
				var CEUniqueNames = [];
				var UKUniqueNames = [];
				var CEPOCollection = [];
				var UKPOCollection = [];
				var JointUniqueNames = [];
				var JointPOCollection = [];
				var artwork_collection = {};
				var JointOrderIds = [];
				for (i = 0; i < newObj1.length; i++) {
					if (newObj1[i].zone === 'CE' && newObj1[i].ce_style_ref !== null) {
						if (CEUniqueNames.indexOf(newObj1[i].ce_style_ref) === -1) {
							CEPOCollection.push(newObj1[i].po_number);
							CEUniqueNames.push(newObj1[i].ce_style_ref);
							artwork_collection[newObj1[i].ce_style_ref] = {};
							artwork_collection[newObj1[i].ce_style_ref][newObj1[i].po_number] = newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);
						} else {
							if (CEPOCollection.indexOf(newObj1[i].po_number) === -1) {
								CEPOCollection.push(newObj1[i].po_number);
								artwork_collection[newObj1[i].ce_style_ref][newObj1[i].po_number] = newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);
							} else {
								var artwork_group = artwork_collection[newObj1[i].ce_style_ref][newObj1[i].po_number];
								artwork_collection[newObj1[i].ce_style_ref][newObj1[i].po_number] = artwork_group + ',' + newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);
							}
						}

					} else if (newObj1[i].zone === 'UK' && newObj1[i].uk_style_ref !== null) {
						if (UKUniqueNames.indexOf(newObj1[i].uk_style_ref) === -1) {
							UKPOCollection.push(newObj1[i].po_number);
							UKUniqueNames.push(newObj1[i].uk_style_ref);
							artwork_collection[newObj1[i].uk_style_ref] = {};
							artwork_collection[newObj1[i].uk_style_ref][newObj1[i].po_number] = newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);
						} else {
							if (UKPOCollection.indexOf(newObj1[i].po_number) === -1) {
								UKPOCollection.push(newObj1[i].po_number);
								artwork_collection[newObj1[i].uk_style_ref][newObj1[i].po_number] = newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);

							} else {
								var artwork_group = artwork_collection[newObj1[i].uk_style_ref][newObj1[i].po_number];
								artwork_collection[newObj1[i].uk_style_ref][newObj1[i].po_number] = artwork_group + ',' + newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status);
							}

						}
					} else if (newObj1[i].zone === 'joint' && newObj1[i].uk_style_ref !== null && newObj1[i].ce_style_ref !== null) {
						
							JointPOCollection.push(newObj1[i].po_number);
							JointUniqueNames.push(newObj1[i].uk_style_ref+'/'+newObj1[i].ce_style_ref);
							JointOrderIds.push(newObj1[i].id);
							artwork_collection[newObj1[i].id] = {};
							artwork_collection[newObj1[i].id][newObj1[i].po_number] = newObj1[i].template_id + '__' + newObj1[i].TemplateName + '__' + camelize(newObj1[i].order_status)+ '__' + newObj1[i].id;
						
					}

				}

				var options = "<option value=''>Search Style Number</option>";
				if (CEUniqueNames.length > 0) {
					options += "<optgroup label='CE'>";
					for (i = 0; i < CEUniqueNames.length; i++) {
						options += "<option data-zone='ce'>" + CEUniqueNames[i] + "</option>";
					}
					options += "</optgroup>";
				}
				if (UKUniqueNames.length > 0) {
					options += "<optgroup label='UK'>";
					for (i = 0; i < UKUniqueNames.length; i++) {
						options += "<option data-zone='uk'>" + UKUniqueNames[i] + "</option>";
					}
					options += "</optgroup>";
				}

				if (JointUniqueNames.length > 0) {
					options += "<optgroup label='Joint'>";
					for (i = 0; i < JointUniqueNames.length; i++) {
						options += "<option data-zone='joint' data-orderid ='"+JointOrderIds[i]+"'>" + JointUniqueNames[i] + "</option>";
					}
					options += "</optgroup>";
				}
				
				// tesco care size chart dropdown
				size_options = `<option value=''>Select Size Chart</option>`;
				$.each(data.tesco_size_department, function(i, item){
					size_options += `<option value=` + i + `>` + item + `</option>`;
				});

				$('.main-content').html(`<div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <h4>Create Order</h4>
                                                        <hr/>
                                                        <ul class="nav nav-tabs">
                                                            <li  id="viewOrdertab" class="nav-item active"> <a class="nav-link active" data-toggle="tab" href="#viewOrder" role="tab">View Order</a> </li>
                                                            <li  id="newOrdertab" class="nav-item"> <a class="nav-link" data-toggle="tab" href="#newOrder" role="tab">New Order</a> </li>
 <li class="nav-item"> <a class="nav-link" data-toggle="tab" id="reOrdertab" href="#reOrder" role="tab">Re Order</a> </li>
                                                        </ul>
                                                        <div class="tab-content ">
                                                            <div class="tab-pane fade in active" id="viewOrder" role="tabpanel">
                                                                <div class="pad-top">
                                                                    <form id="view_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                                                        <div class="form-group has-feedback">
                                                                            <label for="style_num" class="form_label">Select Style Number</label>
                                                                            <select class="form-control po_number selectpicker" name="po_number" id="po_number" required data-live-search="true">` + options + `</select>
                                                                            <div class="help-block with-errors"></div>
                                                                        </div>
                                                                        <div class="form-group has-feedback artwork_list hidden">
                                                                            <label for="order_type" class="form_label">Select Artwork</label>
                                                                            <select class="form-control selectpicker" name="artwork_list" id="artwork_list" required data-live-search="true" onchange="loadTemplatePreview()"></select>
                                                                            <div class="help-block with-errors"></div>
                                                                        </div>
                                                                        <button class="btn btn-primary btn-block ">View Order</button>
                                                                    </form>
                                                                </div>
                                                            </div>
															<div class="tab-pane fade" id="reOrder" role="tabpanel">
                                                                <div class="pad-top">
                                                                    <form id="re_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                                                    <div class="reorder-content">
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="newOrder" role="tabpanel">
                                                                <div class="pad-top">
                                                                    <form id="create_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                                                        <div class="form-group has-feedback">
                                                                            <label for="order_type" class="form_label" style="margin-right: 5px;">Select Order Type</label>
                                                                              <div>
                                                                                <label><input type="radio" class="order_type" name="radio" value="1" required="" style="margin-right: 5px;">PO Upload
                                                                                </label>

                                                                                <label><input type="radio" class="order_type" name="radio" value="2" required="" style="margin-right: 5px;"> Manual Entry
                                                                                </label>
                                                                              </div>
                                                                            <div class="help-block with-errors"></div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="template" class="form_label">Select Template</label>
                                                                            <input class="search-input form-control" placeholder="Search for Awesomeness">
                                                                            <div class="template-error"></div>
                                                                        </div>
                                                                        <div id="jstree"></div>
																		<div class="form-group has-feedback hidden" id="tesco_care_parser_type_div">
																		<label for="pdf_parser_type" class="form_label" style="margin-right: 5px;">Select PO Zone</label>
																		<div>
																			<label><input type="radio" class="pdf_parser_type" name="zone" value="UK" style="margin-right: 5px;">UK</label>
																			<label><input type="radio" class="pdf_parser_type" name="zone" value="CE" style="margin-right: 5px;">CE</label>
																			<label><input type="radio" class="pdf_parser_type" name="zone" value="BOTH" style="margin-right: 5px;">Both</label>
																		</div>
																		<div class="help-block with-errors"></div>
																		</div>
                                                                        <div class="form-group has-feedback">
                                                                            <div class="po-upload hidden">
                                                                                <div class="form-group files" id="single_file ">
                                                                                    <label>Upload UK PO</label>
                                                                                    <input type="file" name="purchase_order" id="po_upload" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/>
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ce-po-upload hidden">
                                                                                <div class="form-group files" id="multiple_file1">
                                                                                    <label>Upload CE-ORMS Sheet</label>
                                                                                    <input type="file" name="ce_orms_purchase_order" id="ce_orms" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/>
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                                <div class="form-group files" id="multiple_file2">
                                                                                    <label>Upload CE-RETEK Sheet</label>
                                                                                    <input type="file" name="ce_retek_purchase_order" id="ce_retek" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/>
                                                                                    <div class="help-block with-errors"></div>
                                                                                </div>
                                                                            </div>
																		</div>

																			<div class="form-group has-feedback hidden" id="tesco_care_size_chart_div">
																				<label for="tesco_care_size_chart">Size Chart</label>
																				<select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_size_chart" name="tesco_care_size_chart" required="" >
																				`+size_options+`
																				</select>	
																				<div class="help-block with-errors"></div>
																			</div>
																			<div class="form-group has-feedback hidden" id="tesco_care_size_category_div">
																				
																			</div>

																			
                                                                        <button class="btn btn-primary btn-block ">Create Order</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 ">
                                                    <div class="card " style="height:calc(100vh - 100px); overflow:auto">
                                                        <h4 id="head_template">Template Preview</h4>
                                                        <hr/>
                                                        <div class="row ">
                                                            <div class="loader ">
                                                                <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
                                                            </div>
															<div class="templateDiv ">
															<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>
															</div>
                                                            <div class="descriptionDiv "></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
				
				$('#reOrdertab').click(function () {
					$('.templateDiv').html('<div style="margin-top:150px" class="how_it_works_reorder text-center"><h4>How it works ?</h4><p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p></div><div style="padding-top:0px;overflow-y: scroll;max-height: 450px;" class="list_div list_div_reorder scroll hidden"><ul class="list-group"></ul></div>')
					$('#head_template').html("Re Order")
					$('#style_number').val("Select Style Number");
					$('#style_number').selectpicker('refresh');
					$('#uvc_number').val("Select Style Number");
				$('#uvc_number').selectpicker('refresh');
				});
				
				$('#newOrdertab').click(function () {
					$('.templateDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>')
					$('#head_template').html("Template Preview")
				});
				
				$('#viewOrdertab').click(function () {
					$('.templateDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>')
					$('#head_template').html("Template Preview")
				});
				
				$(".search-input").keyup(function () {
					var searchString = $(this).val();
					$('#jstree').jstree('search', searchString);
				});

				$('body').on('change', '#po_number', function () {
					let selected_value = $(this).find(':selected').data('zone') === 'joint' ?$(this).find(':selected').data('orderid'):this.value;
					loadArtworkNames(artwork_collection[selected_value]);
				});

				/** [ Load selectpicker ] */
				$('.selectpicker').selectpicker("refresh");

				/** [ Hide/Show PO upload div, PO number search based on order type selected ] */
				$('input[type="radio"].order_type').click(function () {
					globalOrderType = ($(this).attr("value") === '1') ? 1 : 0;
					var currentNode = $('#jstree').jstree(true).get_selected('full', true);
					var zone = (currentNode.length > 0) ? currentNode[0].original.zone : false;
					var temp_type = currentNode[0].original.template_type;

					if (zone === 'UK' && globalOrderType === 1) {
						$(".po-upload").removeClass("hidden");
						$('#po_upload').prop('required', true);
						$(".ce-po-upload").addClass("hidden");
						$('#ce_orms').prop('required', false);
						$('#ce_retek').prop('required', false);
					} else if (zone === 'CE' && globalOrderType === 1) {
						$(".po-upload").addClass("hidden");
						$('#po_upload').prop('required', false);
						$(".ce-po-upload").removeClass("hidden");
						$('#ce_orms').prop('required', true);
						$('#ce_retek').prop('required', true);
					} else {
						$(".po-upload").addClass("hidden");
						$('#po_upload').prop('required', false);
						$(".ce-po-upload").addClass("hidden");
						$('#ce_orms').prop('required', false);
						$('#ce_retek').prop('required', false);
					}

					// Label Parser
					if(globalOrderType === 1 && temp_type === 'tesco_care_labels') { // Show only for PO upload
						$("#tesco_care_parser_type_div").removeClass("hidden");
						$('input[type="radio"].pdf_parser_type').prop('required', true);
						if($('input[type="radio"].pdf_parser_type').is(':checked')){
							var parserPOzone =  $('input[type="radio"].pdf_parser_type:checked').val();
							if (parserPOzone === 'UK') {
								$(".po-upload").removeClass("hidden");
								$('#po_upload').prop('required', true);
								$(".ce-po-upload").addClass("hidden");
								$('#ce_orms').prop('required', false);
								$('#ce_retek').prop('required', false);
							} else{
								$(".po-upload").addClass("hidden");
								$('#po_upload').prop('required', false);
								$(".ce-po-upload").removeClass("hidden");
								$('#ce_orms').prop('required', true);
								$('#ce_retek').prop('required', true);
							} 
						}
					} else if(globalOrderType === 0 && temp_type === 'tesco_care_labels') {
						$("#tesco_care_parser_type_div").addClass("hidden");
						$('input[type="radio"].pdf_parser_type').prop('required', false);
						$('input[type="radio"].pdf_parser_type').prop('checked', false);
					}
				});


				/** [ Hide/Show PO upload div for tesco care labels ] */
				$('input[type="radio"].pdf_parser_type').click(function () {
					var parserPOzone =  $(this).attr("value");
					var currentNode = $('#jstree').jstree(true).get_selected('full', true);
					var zone = (currentNode.length > 0) ? currentNode[0].original.zone : false;
					var temp_type = currentNode[0].original.template_type;

					if (parserPOzone === 'UK' && globalOrderType === 1 && temp_type === 'tesco_care_labels') {
						$(".po-upload").removeClass("hidden");
						$('#po_upload').prop('required', true);
						$(".ce-po-upload").addClass("hidden");
						$('#ce_orms').prop('required', false);
						$('#ce_retek').prop('required', false);
					} else if ((parserPOzone === 'CE' || parserPOzone === 'BOTH') && globalOrderType === 1 && temp_type === 'tesco_care_labels') {
						$(".po-upload").addClass("hidden");
						$('#po_upload').prop('required', false);
						$(".ce-po-upload").removeClass("hidden");
						$('#ce_orms').prop('required', true);
						$('#ce_retek').prop('required', true);
					} else {
						$(".po-upload").addClass("hidden");
						$('#po_upload').prop('required', false);
						$(".ce-po-upload").addClass("hidden");
						$('#ce_orms').prop('required', false);
						$('#ce_retek').prop('required', false);
					}
				});

				/** [ Show tesco templates hierarchy ] */
				$('.descriptionDiv').addClass('hidden')
				$('#jstree').jstree({
					'core': {
						'data': [{
								"id": "1",
								"text": "Stickers",
								"parent_id": "0",
								"children": [{
									"id": "2",
									"text": "Carton & Polybag Stickers",
									"parent_id": "1",
									"children": [{
										"id": "3",
										"text": "CE",
										"parent_id": "2",
										"children": [{
											"id": "26,27,21",
											"text": "TCL 1 (QR), TCL 2 (QR) & TSL 1",
											"parent_id": "3",
											"type": "ratio",
											"zone": "CE",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "28,23,39",
											"text": "TCL 4 (QR), TSL 3 & TSL 2",
											"parent_id": "3",
											"type": "single",
											"zone": "CE",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "24",
											"text": "THL 1 (QR)",
											"parent_id": "3",
											"type": "ratio",
											"zone": "CE",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "25",
											"text": "THL 3 (QR)",
											"parent_id": "3",
											"type": "single",
											"zone": "CE",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "38",
											"text": "TCL 5 (QR)",
											"parent_id": "3",
											"type": "single",
											"zone": "CE",
											"template_type": "tesco_carton_stickers"
										}]
									}, {
										"id": "4",
										"text": "UK",
										"parent_id": "2",
										"children": [{
											"id": "29,30,14",
											"text": "TCL 1 , TCL 2 & TSL 1",
											"parent_id": "4",
											"type": "ratio",
											"zone": "UK",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "31,16,40",
											"text": "TCL 4, TSL 3 & TSL 2",
											"parent_id": "4",
											"type": "single",
											"zone": "UK",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "17",
											"text": "THL 1",
											"parent_id": "4",
											"type": "ratio",
											"zone": "UK",
											"template_type": "tesco_carton_stickers"
										}, {
											"id": "18",
											"text": "THL 3",
											"parent_id": "4",
											"type": "single",
											"zone": "UK",
											"template_type": "tesco_carton_stickers"
										}]
									}]
								}]
							},
							{
								"id": "32",
								"text": "Labels",
								"parent_id": "0",
								"children": [{
									"id": "33",
									"text": "Printed Labels",
									"parent_id": "32",
									"children": [{
											"id": "34",
											"text": "GCL25W/M12723G",
											"parent_id": "33",
											"template_type": "tesco_care_labels"
										}, {
											"id": "35",
											"text": "GCL25W/M13220G",
											"parent_id": "33",
											"template_type": "tesco_care_labels"
										},
										{
											"id": "36",
											"text": "GCL25W/M12723G",
											"parent_id": "33",
											"template_type": "tesco_care_labels"
										},
										{
											"id": "37",
											"text": "GCL25W/M13220G",
											"parent_id": "33",
											"template_type": "tesco_care_labels"
										},
									]
								},{
									"id": "41",
									"text": "Swing Tickets",
									"parent_id": "32",
									"children": [{
											"id": "42",
											"text": "CE Non Rfid Swing Ticket",
											"parent_id": "41",
											"children": [{
												"id": "43",
												"text": "FFC/01/M13020C - CE F&F CORE NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											}, {
												"id": "44",
												"text": "FFK/01/M13021C - CE F&F KIDS, SCHOOL & KIDS DRESS UP NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "45",
												"text": "FFC/01/M13022C - CE F&F CORE SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "46",
												"text": "FFB/01/M13023C - CE FRED & FLO NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "47",
												"text": "GE/01/M13114C - Opt 1 - Core Ladieswear & Menswear (excluding Socks & Gifting)",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "48",
												"text": "GE/01/M13114C - Opt 2 - Kids, School and Kids Dress Up (excluding Socks & Gifting)",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "49",
												"text": "GE/01/M13114C - Opt 3 - Socks & Gifting (Adults, Kids & Baby)",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "50",
												"text": "GE/01/M13114C - Opt 4 - Fred & Flo",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "51",
												"text": "FFK/01/M13163C - CE F&F KIDS SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "52",
												"text": "FFB/01/M13233C - CE FRED & FLO SOCKS & GIFTING NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											},{
												"id": "53",
												"text": "FFC/01/M13304C - CE F&F CORE CASHMERE LARGE NON-RFID BRAND TICKET INC. BOP",
												"parent_id": "42",
												"template_type": "tesco_swing_tickets"
											}
										]
										},
									]
								}]
							}
						]
					},
					"types": {
						"default": {
							"icon": "fa fa-folder"
						}
					},
					"plugins": ["types", "search", "conditionalselect"],
					conditionalselect: function (node) {
						return this.is_leaf(node);
					}
				}).on('loaded.jstree', function () {
					/** [ Show expanded jstree ]  */
					$('#jstree').jstree('open_all');
				}).bind("refresh.jstree", function (e, data) {
					/** [ Show expanded jstree ]  */
					$(this).jstree("open_all");
				}).bind('before.jstree', function (event, data) {
					/** [ Make only leaf-nodes selectable ] */
					if (data.instance.is_leaf(data.args[0])) {
						return false;
					}
				})

				/** [ Get tempalte preview based on template selection ]  */
				$('#jstree').on('activate_node.jstree', function (e, data) {
					$('.template-error').html('')
					if (data.instance.is_leaf(data.node)) {
						var template_id = data.node.id;

					}
				});

				/** [ To get selected leaf node ] */
				var template_id = 0;
				var type;
				var zone;
				$('#jstree').on('activate_node.jstree', function (e, data) {
					if (data.instance.is_leaf(data.node) != '') {
						template_id = data.node.id;
						template_type = data.node.original.template_type;
						$.ajax({
							url: base_url + "/admin/tesco/template-management/template-view",
							type: "Post",
							dataType: "json",
							data: {
								template_id: template_id,
								_token: csrf_token
							},
							tryCount: 0,
							retryLimit: 2,
							beforeSend: function () {
								$(".loader").show();
							},
							success: function (data) {
								if (data.length > 0) {
									$('.templateDiv').html('');
									$.each(data, function (index, value) {
										if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
											$('.templateDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">' + value[0].template_name + '</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">' + value[0].ref_no + '</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">' + value[0].company_name + '</td></tr></tbody></table></div></div>')

										} else {
											//console.log("type" + value[0].type);
											if(value[0].type == "Labels")
											{
												//alert()
												var object_ref = '<div class="col-md-2"><img src="/images/FRONT.png" style="height: 800px;"></div><div class="col-md-2"><img style="margin-left: 150px;height: 800px;" src="/images/BACK.png"></div>';
											}
											else
											{
												var object_ref = '<object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object>';
											}
											$('.templateDiv').append('<div class="col-md-4">'+object_ref+'<div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">' + value[0].template_name + '</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">' + value[0].ref_no + '</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">' + value[0].company_name + '</td></tr></tbody></table></div></div>')

										}
									});
								} else {
									$('.templateDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>')

									$('.descriptionDiv').html('<b>Template Not Found</b>');
									$('.descriptionDiv').css('margin-top', '0px')
									$('.templateDiv').css('margin-top', '140px')
								}
								$('.templateDiv').css('width', '100%')

							},
							error: function (errorXHR, errorStatus, errorThrown) {
								this.tryCount++;
								if (this.tryCount <= this.retryLimit) {
									$.ajax(this);
									return;
								}
							},
							complete: function () {
								$(".loader").hide();
							}
						})
						/** [ add values into local storage ] */
						localStorage.setItem("templates", data.node.id);
						localStorage.setItem("zone", data.node.original.zone);
						localStorage.setItem("template type", data.node.original.type);
						localStorage.setItem("template_group", data.node.text.trim());					
						if (data.node.original.zone === 'UK' && globalOrderType === 1) {
							$(".po-upload").removeClass("hidden");
							$('#po_upload').prop('required', true);
							$(".ce-po-upload").addClass("hidden");
							$('#ce_orms').prop('required', false);
							$('#ce_retek').prop('required', false);
						} else if (data.node.original.zone === 'CE' && globalOrderType === 1) {
							$(".po-upload").addClass("hidden");
							$('#po_upload').prop('required', false);
							$(".ce-po-upload").removeClass("hidden");
							$('#ce_orms').prop('required', true);
							$('#ce_retek').prop('required', true);
						} else {
							$(".po-upload").addClass("hidden");
							$('#po_upload').prop('required', false);
							$(".ce-po-upload").addClass("hidden");
							$('#ce_orms').prop('required', false);
							$('#ce_retek').prop('required', false);
						}

						// toggle size chart select box for template_type = tesco_care_labels
						if($.inArray(template_type, ['tesco_care_labels', 'tesco_swing_tickets']) != -1){
							$("#tesco_care_size_chart_div").removeClass("hidden");
							$('#tesco_care_size_chart').prop('required', true);
							$('#tesco_care_size_chart').val('');
							// Label Parser
							if(globalOrderType === 1) { // Show only for PO upload
								$("#tesco_care_parser_type_div").removeClass("hidden");
								$('input[type="radio"].pdf_parser_type').prop('required', true);
								if($('input[type="radio"].pdf_parser_type').is(':checked')){
									var parserPOzone =  $('input[type="radio"].pdf_parser_type:checked').val();
									if (parserPOzone === 'UK') {
										$(".po-upload").removeClass("hidden");
										$('#po_upload').prop('required', true);
										$(".ce-po-upload").addClass("hidden");
										$('#ce_orms').prop('required', false);
										$('#ce_retek').prop('required', false);
									} else{
										$(".po-upload").addClass("hidden");
										$('#po_upload').prop('required', false);
										$(".ce-po-upload").removeClass("hidden");
										$('#ce_orms').prop('required', true);
										$('#ce_retek').prop('required', true);
									} 
								}
							}

						} else {
							$("#tesco_care_size_chart_div").addClass("hidden");
							$('#tesco_care_size_category_div').html('');
							$('#tesco_care_size_chart').prop('required', false);
							$('#tesco_care_size_category').prop('required', false);
							$('#tesco_care_size_chart').val('');
							// Label Parser
							if(globalOrderType === 1){
								$("#tesco_care_parser_type_div").addClass("hidden");
								$('input[type="radio"].pdf_parser_type').prop('checked', false);
								$('input[type="radio"].pdf_parser_type').prop('required', false);
							}

						}
						$(".selectpicker").selectpicker("refresh");
					}
				});

				$('#tesco_care_size_chart').on('change', (e) => {
					var valueSelected  = $(e.target).val();
					if($('#jstree').jstree(true).get_selected('full', true)[0].original.template_type === 'tesco_care_labels') {
						getSecondarySizeCategory(valueSelected, 'admin')
					}
					
				});

				/** [ bootstrap validator for create tesco order form ] **/
				$('#create_order_form').validator().on('submit', function (e) {
					if (e.isDefaultPrevented()) {

					} else {
						e.preventDefault();

						/** [ Check if leaf node( template ) selected or not ] **/
						if (template_id != 0) {
							$('.template-error').html('');
							//Check for the order type is PO Upload
							//If so, create a formdata for uploading the file.
							//invoke uploadPurchaseOrder method with the form data.
							//If not, go with manual upload by invoking createOrders() method.
							if ($('input[type="radio"].order_type:checked').val() == 1) {
								var formData = new FormData();
								var purchase_order = $('#po_upload').prop('files')[0];
								var ce_orms_purchase_order = $('#ce_orms')[0].files[0];
								var ce_retek_purchase_order = $('#ce_retek')[0].files[0];
								formData.append('purchase_order', purchase_order);
								formData.append('ce_orms_purchase_order', ce_orms_purchase_order);
								formData.append('ce_retek_purchase_order', ce_retek_purchase_order);
								formData.append('type', localStorage.getItem("template type"));
								formData.append('template_name', localStorage.getItem("template_group").replace(" with QR", ""));
								if(template_type === 'tesco_care_labels') {
									var poZone =  $('input[type="radio"].pdf_parser_type:checked').val();
									poZone = poZone == 'BOTH'?'CE':poZone;
									formData.append('zone', poZone);
									var size_id = $('#tesco_care_size_chart').val();
									var size_category = [];
									var size_category_text = [];

									/** [ Create array of multi selected categories ] */
									$.each($("#tesco_care_size_category option:selected"), function() {
										size_category.push($(this).val());
										// size_category_text.push($(this).text());
									});

									/** [ Create array of multiple categories ] */
									$.each($("#tesco_care_size_category option"), function() {
										size_category_text.push($(this).text());
									});
									uploadPurchaseOrderForLabels(formData, poZone, role="admin", size_id, size_category, size_category_text,template_id);
								} else {
									formData.append('zone', localStorage.getItem("zone"));
									uploadPurchaseOrder(formData, localStorage.getItem("zone"));
								}
							} else {
								switch (template_type) {
									case 'tesco_carton_stickers':
										createOrders();
										break;
									case 'tesco_care_labels':
										renderTescoCareOrders(role="admin", template_id);
										break;
									case 'tesco_swing_tickets':
										renderTescoSwingTicketOrders(role="admin", template_id);
										break;
								
									default:
										break;
								}	
							}
							
								localStorage.setItem("back_flag","create");
								localStorage.setItem("back_template_id",$('#jstree').jstree("get_selected"));
								localStorage.setItem("back_zone",localStorage.getItem("zone"));
								var order_type_back =$('input[name=radio]:checked').val();
								localStorage.removeItem("artwork_back");
								localStorage.setItem("order_type_back",order_type_back);
								localStorage.removeItem("style_number_back");
								localStorage.removeItem("po_num_back");

						} else {
							/**  [ Show validation error message ] **/
							$('.template-error').html('<span style="color:#F44336">Please select a template in the list.</span>');
						}
						
					}
				});

				/** [ bootstrap validator for create tesco order form ] **/
				$('#view_order_form').validator().on('submit', function (e) {
					if (e.isDefaultPrevented()) {

					} else {
						e.preventDefault();
						var po_num = $('#artwork_list').val();
						var style_number = $('#po_number').val();
						var artwork = $('#artwork_list').find(':selected').text();
						var zone = $('#po_number').find(':selected').data('zone');
						var orderid = $('#artwork_list').find(':selected').data('orderid') != undefined?$('#artwork_list').find(':selected').data('orderid'):0;
						localStorage.setItem('style_number_back',style_number)
						localStorage.setItem('artwork_back',artwork)
						localStorage.setItem('po_num_back',po_num)
						localStorage.setItem("back_flag","view");
						viewOrder(po_num, artwork, style_number, zone, orderid);
					}
				})
				callback();
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {

				$(".loader").hide();
				
			}
		})
	} catch (e) {
		/** [ error statements ] */
		console.log("Error:" + e)
	}
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0) == ' ') {
		c = c.substring(1);
	}
	if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
	}
	}
	return "";
}

/**
 * This Function is used to show create tesco orders page.
 * @author Praveen
 * @return View
 */

function createOrders(parsedObj = false, oType = false) {
	try {
		/* [ Get values from local storage ] */
		var template_id = localStorage.getItem("templates");
		var zone = localStorage.getItem("zone");
		var type = localStorage.getItem("template type");
		$('li.year-nav-menu').addClass('hidden');
		$('li.buyer-nav-menu').addClass('hidden');

		var word = localStorage.getItem("templates").length === 1 ? 'Artwork' : 'Artworks';
		$('.top-content').html('<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;"><h4><strong>Create Order For TESCO-' + zone + ' ' + localStorage.getItem("template_group") + '</strong></h4></div>');

		$.ajax({
			url: base_url + "/admin/tesco/templates-details",
			type: "get",
			dataType: "json",
			data: {
				template_id: template_id
			},
			tryCount: 0,
			retryLimit: 2,
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				localStorage.setItem("data", JSON.stringify(data.templates));
			
				const stickersList = data.templates.map(template => template.template_name);

				if (zone === "CE") {
					/** Buyer PO number length , for CE 7 digits and for UK 9 digits*/
					var po_number_component = `<div class="col-md-3">
													<div class="form-group has-feedback">
														<label for="tesco_po_number">PO Number</label>
														<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()" maxlength="7">
														<div class="help-block with-errors"></div>
													</div>
												</div>`;
					if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
						var barcode_title = `<th rowspan="2">ITF Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_1" onkeyup="dynamicSVGTemplates()" name="ce_ratio_itf_1" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>`;
						
					} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
						var barcode_title = `<th rowspan="2">EAN Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_1" name="ce_single_ean_1" onkeyup="dynamicSVGTemplates()" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>`;	
					} else {
						var barcode_title = `<th rowspan="2">ITF Barcode</th><th rowspan="2">EAN Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_1" onkeyup="dynamicSVGTemplates()" name="ce_ratio_itf_1" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>
												<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_1" name="ce_single_ean_1" onkeyup="dynamicSVGTemplates()" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>`;
					}
					
				} else {

					var po_number_component = `<div class="col-md-3">
													<div class="form-group has-feedback">
														<label for="tesco_po_number">PO Number</label>
														<input type="text" class="form-control" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()" maxlength="9">
														<div class="help-block with-errors"></div>
													</div>
												</div>`;
					if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
						var barcode_title = `<th rowspan="2">ITF Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_1" name="uk_ratio_itf_1" onkeyup="dynamicSVGTemplates()" placeholder="Enter ITF Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>`;
						
					} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
						var barcode_title = `<th rowspan="2">EAN Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" id="uk_single_ean_1" name="uk_single_ean_1" placeholder="Enter EAN Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>	
												</td>`;	
					} else {
						var barcode_title = `<th rowspan="2">ITF Barcode</th><th rowspan="2">EAN Barcode</th>`;
						var barcode_component = `<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_1" name="uk_ratio_itf_1" onkeyup="dynamicSVGTemplates()" placeholder="Enter ITF Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>
												<td>
													<div class="form-group has-feedback">
														<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" id="uk_single_ean_1" name="uk_single_ean_1" placeholder="Enter EAN Barcode">
														<div class="help-block with-errors"></div>
														<div class="barcode-error"></div>
													</div>
												</td>`;
					}
				}

				var html = '<div class="loader">\
                                    <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>\
                                </div>\
								<span id="result"></span>\
                                <br>\
                                <form method="POST" id="create_tesco_order_form" data-toggle="validator" role="form" novalidate="true"><input type="hidden" id="qr_flag" value="' + data.templates[0].is_qrcode_required + '">\
								'+po_number_component+'\
								<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_uk_style">UK Style Ref.</label>\
								<input type="text" class="form-control" name="tesco_uk_style" id="tesco_uk_style" placeholder="Enter UK Style Ref." required="" onkeyup="dynamicSVGTemplates()" maxlength="8">\
								<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_ce_style">CE Style Ref.</label>\
								<input type="text" class="form-control" name="tesco_ce_style" id="tesco_ce_style" placeholder="Enter CE Style Ref." required="" onkeyup="dynamicSVGTemplates()" maxlength="8">\
								<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_season_code">Season Code</label>\
								<input type="text" class="form-control" data-required-error="Mandatory Field." name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" maxlength="6">\
								<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_description">Short Description</label>\
								<input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="45" onkeyup="dynamicSVGTemplates()">\
								<input type="hidden" id="tesco_full_description" value="">\
								<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_eqos">EQOS Site Code</label>\
								<input type="text" class="form-control" name="tesco_eqos" id="tesco_eqos" required="" placeholder="Enter EQOS Site Code" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" maxlength="7">\
								<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_manufacture_date">Manufactured Date</label>\
								<div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1">\
									<input value="" id="tesco_manufacture_date" name="tesco_manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date" onchange="dynamicSVGTemplates()">\
									<div class="input-group-addon">\
									<span class="glyphicon glyphicon-th"> </span >\
								</div >\
							</div >\
							<div class="help-block with-errors"></div>\
							</div>\
						</div>\
						<div class="col-md-3">\
							<div class="form-group has-feedback">\
								<label for="tesco_department">Department</label>\
								<select class="selectpicker" data-live-search="true" id="tesco_department" name="tesco_department" required="" onchange="dynamicSVGTemplates()">\
									<option value="">Select Department</option>';

				/** [ Show dynamic department selectbox ] */
				$.each(data.tesco_department, function (index, item) {
					html += '<option value="' + item.id + '">' + item.department + '</option>';
				});

				html += '</select>\
                             </div>\
                             </div>\
                             <div class="clearfix"></div>\
                             <div class="col-md-3"> \
                               <div class="form-group has-feedback">\
                                 <label for="tesco_brand">Brand</label> \
                                   <select class="selectpicker" data-live-search="true" id="tesco_brand" name="tesco_brand" onchange="dynamicSVGTemplates()" required="">\
                                     <option value="">Select Brand</option>';

				/** [ Show dynamic brand selectbox ] */
				$.each(data.tesco_brand, function (index, item) {
					html += '<option value="' + item.id + '">' + item.brand + '</option>';
				});

				html += '</select>\
                             </div>\
                             </div>\
                             <div class="col-md-3">\
                              <div class="form-group has-feedback">\
                               <label for="tesco_supplier">Customer</label> \
                                <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="">\
                                  <option value="">Select Customer</option>';

				/** [ Show dynamic supplier selectbox ] */
				$.each(data.tesco_suppliers, function (index, item) {
					html += '<option value="' + item.id + '">' + item.supplier_name + '</option>';
				});

				html += '</select>\
                            </div>\
                            </div>\
                            <div class="col-md-3">\
                                <div class="form-group has-feedback">\
                                    <label for="tesco_ocn">OCN Code</label>\
                                    <input type="text" class="form-control" name="tesco_ocn" id="tesco_ocn" required="" placeholder="Enter OCN Code" onkeypress="return isNumber(event)" maxlength="15">\
                                    <div class="help-block with-errors"></div>\
                                </div>\
                            </div>\
                            <div class="col-md-3">\
                                <div class="form-group has-feedback">\
                                    <label for="tesco_rfid">RFID Compliant</label>\
                                    <select class="selectpicker" id="tesco_rfid" name="tesco_rfid" required="" onchange="dynamicSVGTemplates()">\
                                        <option value="">Select RFID Compliant</option>\
                                        <option value="yes">Yes</option>\
                                        <option value="no">No</option>\
                                    </select>\
                                </div>\
                            </div>\
                            <div class="col-md-3">\
                                <div class="form-group has-feedback">\
                                    <label for="tesco_tag_at_source">Tag At Source</label>\
                                    <select class="selectpicker" id="tesco_tag_at_source" name="tesco_tag_at_source" required="" onchange="dynamicSVGTemplates()">\
                                        <option value="">Select Tag At Source</option>\
                                        <option value="yes">Yes</option>\
                                        <option value="no">No</option>\
                                    </select>\
                                </div>\
                            </div>';
				if (zone === "UK") {
					/**  [ Dynamic quantity table for UK ratio templates ] */
					if (type === 'ratio') {
						html += '<div class="qty-table-div">\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <hr>\
                                            <h4>Order Quantity</h4>\
                                            <p>\
                                                <button type="button" onclick="addUKRatio(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                            </p>\
                                        </div>\
                                    </div>\
                                    <div class="table-responsive qty-table">\
                                        <table class="table table-bordered text-center">\
                                            <thead>\
                                                <tr>\
                                                    <th rowspan="2">Line No.</th>\
                                                    <th rowspan="2">TPND</th>\
                                                    '+barcode_title+'\
                                                    <th rowspan="2">Units Per Carton</th>';
						$.each(data.templates, function (i, item) {
							html += '<th colspan="1">' + item.template_name + '</th>';
						});
						html += '<th rowspan="2">Actions</th>\
                                     </tr>\
                                     <tr>';
						$.each(data.templates, function (i, item) {
							html += '<th>Quantity</th>';
						});

						html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_1" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." name="uk_ratio_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()" maxlength="9">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                '+barcode_component+'\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_1" name="uk_ratio_upc_1" placeholder="Enter Units Per Carton" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';

						$.each(data.templates, function (i, item) {
							html += '<td>\
                                            <div class="form-group has-feedback quantity-feedback">\
                                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '"  id="uk_ratio_count_' + item.template_name.replace(' ', '') + '_1" name="uk_ratio_count_' + item.template_name.replace(' ', '') + '_1" max = "999999" data-max-error= "Max 6 digits allowed" placeholder="Enter Quantity"  data-lastid="" data-artwork="'+ item.template_name+'" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </td>';
						});
						html += '<td></td>\
                                        </tr>\
                                        </tbody>\
                                        <div style="display: none;color:#F44336" class="quantity-feedback-err-msg pull-right">Please enter atleast one artwork quantity(&gt;0).</div>\
                                        </table>\
                                        </div>\
                                        <hr>\
                                        </div>';

					} else {
						/**  [ Dynamic quantity table for UK single templates ] */
						html += '<div class="qty-table-div">\
                                        <div class="row">\
                                            <div class="col-md-12">\
                                                <hr>\
                                                <h4>Order Quantity</h4>\
                                                <p>\
                                                    <button type="button" onclick="addUKSingle(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="table-responsive qty-table">\
                                            <table class="table table-bordered text-center">\
                                                <thead>\
                                                    <tr>\
                                                        <th rowspan="2">Line No.</th>\
                                                        <th rowspan="2">TPND</th>\
                                                        '+barcode_title+'\
                                                        <th rowspan="2">Units Per Case</th>\
                                                        <th rowspan="2">Size</th>';
						$.each(data.templates, function (i, item) {
							html += '<th colspan="1">' + item.template_name + '</th>';
						});
						html += '<th rowspan="2">Actions</th>\
                                      </tr>\
                                      <tr>';
						$.each(data.templates, function (i, item) {
							html += '<th>Quantity</th>';
						});
						html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_1" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." name="uk_single_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()" maxlength="9">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
												'+barcode_component+'\
                                                <td class="case_size_td">\
                                                    <div class="form-group has-feedback">\
                                                        <input type="number" class="form-control input-sm uk_single_case_size" required="" onkeyup="dynamicSVGTemplates()" onkeypress="return clearQuantity(this)" id="uk_single_case_size_1" name="uk_single_case_size_1" placeholder="Enter Units Per Case" onkeyup="dynamicSVGTemplates()" max="999999" data-max-error="Max 6 digits allowed">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_1" name="uk_single_size_1" onchange="dynamicSVGTemplates()" maxlength="9">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';
						$.each(data.templates, function (i, item) {
							html += '<td>\
                                            <div class="form-group has-feedback quantity-feedback">\
                                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_single_count_' + item.template_name.replace(' ', '') + '_1" name="uk_single_count_' + item.template_name.replace(' ', '') + '_1" max = "999999" data-max-error= "Max 6 digits allowed" placeholder="Enter Quantity" data-artwork="'+ item.template_name+'" data-lastid="" onfocus="return checkMOQ(this)" onkeypress="return validateMOQ(this)"  onkeyup="return validateCaseSizeQty(this)">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </td>';
						});
						html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       <div style="display: none;color:#F44336" class="quantity-feedback-err-msg pull-right">Please enter atleast one artwork quantity(&gt;0).</div>\
                                       </table>\
                                       </div>\
                                       <hr></div>';

					}
				} else {
					/**  [ Dynamic quantity table for CE ratio templates ] */
					if (type === 'ratio') {
						html += '<div class="qty-table-div">\
                                        <div class="row">\
                                            <div class="col-md-12">\
                                                <hr>\
                                                <h4>Order Quantity</h4>\
                                                <p>\
                                                    <button type="button" onclick="addCERatio(this,event)" data-id="1" class="btn btn-default add-row-btn" ><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="table-responsive qty-table">\
                                            <table class="table table-bordered text-center">\
                                                <thead>\
                                                    <tr>\
                                                        <th rowspan="2">Line No.</th>\
                                                        <th rowspan="2">ORMS TPN</th>\
														'+barcode_title+'\
                                                        <th rowspan="2">No Of Pieces In Ratio</th>';
						$.each(data.templates, function (i, item) {
							html += '<th colspan="1">' + item.template_name + '</th>';
						});
						html += '<th rowspan="2">Actions</th>\
                                      </tr><tr>';
						$.each(data.templates, function (i, item) {
							html += '<th>Quantity</th>';
						});
						html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_1" data-minlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." name="ce_ratio_orms_tpn_1" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                '+barcode_component+'\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_1" name="ce_ratio_cpc_1" onkeypress="return isNumber(event)" placeholder="Enter No Of Pieces In Ratio" onkeyup="dynamicSVGTemplates()" maxlength="5">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';
						$.each(data.templates, function (i, item) {
							html += '<td>\
                                        <div class="form-group has-feedback quantity-feedback"> \
                                        <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_ratio_count_' + item.template_name.replace(' ', '') + '_1" name="ce_ratio_count_' + item.template_name.replace(' ', '') + '_1" max = "999999" data-max-error= "Max 6 digits allowed" placeholder="Enter Quantity" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
                                        <div class="help-block with-errors"></div>\
                                        </div>\
                                        </td>';
						});
						html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       <div style="display: none;color:#F44336" class="quantity-feedback-err-msg pull-right">Please enter atleast one artwork quantity(&gt;0).</div>\
                                       </table>\
                                       <hr></div>';

					} else {
						/**  [ Dynamic quantity table for CE single templates ] */
						html += '<div class="qty-table-div">\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <hr>\
                                            <h4>Order Quantity</h4>\
                                            <p>\
                                                <button type="button" onclick="addCESingle(this,event)" data-id="1" class="btn btn-default add-row-btn" ><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                            </p>\
                                        </div>\
                                    </div>\
                                    <div class="table-responsive qty-table">\
                                        <table class="table table-bordered text-center">\
                                            <thead>\
                                                <tr>\
                                                    <th rowspan="2">Line No.</th>\
                                                    <th rowspan="2">ORMS TPN</th>\
													'+barcode_title+'\
                                                    <th rowspan="2">Units Per Case</th>\
                                                    <th rowspan="2">Size</th>';
						$.each(data.templates, function (i, item) {
							html += '<th colspan="1">' + item.template_name + '</th>';
						});
						html += '<th rowspan="2">Actions</th>\
                                      </tr>\
                                      <tr>';
						$.each(data.templates, function (i, item) {
							html += '<th>Quantity</th>';
						});
						html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_1" data-minlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits."  name="ce_single_orms_tpn_1" required="" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13" >\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
												'+barcode_component+'\
												<td class="case_size_td">\
                                                    <div class="form-group has-feedback">\
                                                        <input type="number" class="form-control input-sm ce_single_case_size" onkeypress="return clearQuantity(this)" id="ce_single_case_size_1" name="ce_single_case_size_1" required="" placeholder="Enter Units Per Case" onkeyup="dynamicSVGTemplates()" max="999999" data-max-error="Max 6 digits allowed">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_1" name="ce_single_size_1" required="" placeholder="Enter Size" onchange="dynamicSVGTemplates()" maxlength="9">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';
						$.each(data.templates, function (i, item) {
							html += '<td>\
                                        <div class="form-group has-feedback quantity-feedback"> \
                                        <input type="number" data-tname="' + item.template_name + '" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_single_count_' + item.template_name.replace(' ', '') + '_1" name="ce_single_count_' + item.template_name.replace(' ', '') + '_1" placeholder="Enter Quantity" max = "999999" data-max-error= "Max 6 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeypress="return validateMOQ(this)" data-artwork="'+ item.template_name+'"   onkeyup="return validateCaseSizeQty(this)">\
                                        <div class="help-block with-errors"></div>\
                                        </div>\
                                        </td>';
						});
						html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       <div style="display: none;color:#F44336" class="quantity-feedback-err-msg pull-right">Please enter atleast one artwork quantity(&gt;0).</div>\
                                       </table>\
                                       </div>\
                                       <hr></div>';

					}
				}

				html += '<div class="invisible-div"></div><h4>Order Preview</h4><div class="row">';
				$.each(data.templates, function (i, item) {
					if(data.templates.length > 1)
					{
						if(item.template_name != "TSL 2")
						{
							if(data.templates.length == i+1)
							{
								var style="width:226px";
							}
							else
							{
								var style="";
							}
						}
						else
						{
							var style="";
						}
						
					}
					else
					{
						var style="";
					}
					
					html += '<div class="col-md-4 ">\
                                  <div class="card add-card">\
                                    <div class="card-title">\
                                       <p class="pull-right tesco_artwork_svg" onclick=openmodaltesco("' + item.template_name.replace(' ', '') + '","' + i + '") style="cursor:pointer">Enlarge</p>\
                                       <h5><b>' + item.template_name + '</b></h5>\
                                    </div>\
                                    <div class="row">\
                                       <div class="col-md-8 col-md-offset-2" style="'+style+'">\
                                         <div class="tesco_artwork"> \
                                           <object style="border: 2px #333 solid;" id="tesco_svg' + i + '" class="center-block tesco_svg' + i + '" data="' + item.template_file + '" type="image/svg+xml"></object>\
                                           <div class="modal fade" id="' + item.template_name.replace(' ', '') + '" role="dialog">\
                                             <div class="modal-dialog">\
                                               <div class="modal-content">\
                                                 <div class="modal-header">\
                                                   <button type="button" class="close" data-dismiss="modal">&times;</button>\
                                                   <h4 class="modal-title">' + item.template_name + '</h4>\
                                                 </div>\
                                                 <div class="modal-body">\
                                                   <object style="border: 2px #333 solid;" id="tesco_svg_modal' + i + '" class="center-block tesco_svg' + i + '" data="' + item.template_file + '" type="image/svg+xml"></object>\
                                                 </div>\
                                                 <div class="modal-footer">\
                                                   <button type="button" class="btn btn-primary print-btn" onclick=printDivTesco("tesco_svg_modal' + i + '","' + item.template_name.replace(' ', '') + '",this) style="display:none;" id="proof_' + item.template_name.replace(' ', '') + '_btn">Generate Proof</button><button type="button" class="btn btn-primary print-btn" onclick=GenerateDivTesco("tesco_svg_modal' + i + '","' + item.template_name.replace(' ', '') + '",this) style="display:none;" id="print_' + item.template_name.replace(' ', '') + '_btn">Generate Print</button>\
                                                   <div id="svgdiv" style="position:absolute;visibility:hidden">\
                                                     <svg id="ean-13"></svg>\
												   </div>\
                                                 </div>\
                                               </div>\
                                             </div>\
                                          </div>\
                                         </div>\
                                      </div>\
                                    </div>\
                                  </div>\
                                </div>';
				});
				html += '</div>\
                                <div class="bottom-bar">\
                                    <div class="modal-footer">\
                                     <span class="pagename pull-left hidden-xs">Please save or submit changes before closing page.</span>\
                                     <button class="btn btn-xs btn-success save-btn" type="button" onclick="saveTescoOrder()" data-insert="true"><i class="fa fa-edit" aria-hidden="true"></i> Save & Continue</button>\
                                        <button class="btn btn-xs btn-warning submit-btn" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>\
										<button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork("print")" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Print</button>\
										<button class="btn btn-xs btn-default create-download-artwork" onclick = "create_download_artwork("proof")" type="button" style="display: none;"><i class="fa fa-download" aria-hidden="true"></i> Download Artwork</button>\
										<button class="btn btn-xs btn-danger cancel-btn" type="button" data-toggle="modal" data-target="#cancelOrderModal" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>\
                                        <button class="btn btn-xs btn-danger reload-btn" type="button" onclick = "reload_back()""><i class="fa fa-refresh" aria-hidden="true"></i> Reload</button>\
                                    </div>\
                                </div>\
                                </div>\
                                </div>';
				 if(parsedObj != false)
					 {
				if(parsedObj[Object.keys(parsedObj)[0]].order_status == "Order Placed")
				{
					$('.cancel-btn').show();
				}
					 }
				$('.main-content').html(html).selectpicker('refresh');
				/** @type {Date} [Manufactured date Datepicker format & validation] */
				var date = new Date();
				var today = new Date(date.getFullYear(), date.getMonth());
				$('#datePicker1').datepicker({
					autoclose: true,
					minViewMode: 1,
					format: 'MM,yyyy',
				})
				/** [ Get data from localstorage ] */
				var data = JSON.parse(localStorage.getItem("data"));
				/** [ Dynamic tesco suppliers preview and Get MOQ value based on supplier selection ] */
				$('#tesco_supplier').on('change',function(){ 
					$.each(data, function (i, item) {
						dynamicTescoSuppliers("tesco_svg" + i);
					});
		        });
				/** [ bootstrap validator for create tesco order form ] **/
				$('#create_tesco_order_form').validator().on('submit', function (e) {
					if (e.isDefaultPrevented()) {

					} else {
						e.preventDefault();
						setTescoOrder();
					}
				})
				$(".card").css("margin-bottom", "80px");
				$('.selectpicker').selectpicker();

				/** [ show tooltip ] */
				$('.delete-row').tooltip('show')
				$('.delete-row').tooltip('toggle')
				if (parsedObj) {
					if (oType) {
						mapStoredValues(parsedObj);
					} else {
						renderParsedValues(parsedObj);
					}
					setTimeout(function () {
						dynamicSVGTemplates();
					}, 500);
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
				/** [ To make top menu active ] */
				$(function () {
					var url = window.location;
					$('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
					$('.dynamic-menu a').filter(function () {
						return this.href == url;
					}).parent('li').addClass('current-page').find('a').addClass('active');
				});
				$(".loader").hide();
			}
		})

	} catch (e) {
		/** [ error statements ] */
		console.log("Error:" + e)
	}
}

/**
 * This Function is used Print Stickers.
 *  
 * @return Pdf
 */

function printDivTesco(data, name, obj) {
	$(obj).prop('disabled', true);
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			var svgRoot = svgDoc.documentElement;

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmall(svg_fin, name,zone);
		}


	} else if (name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {

			if($(this).find('.count_TSL3').val() > 0)
			{

			var svgRoot = svgDoc.documentElement;

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			}
			ajax_count++;
		})

		if (ajax_count == document.getElementById("table_row").rows.length) {
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmall(svg_fin, name,zone);
		}


	} else if (name == "TSL2-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			if($(this).find('.count_TSL2').val() > 0)
			{

				var svgRoot = svgDoc.documentElement;

				if ((name == "TSL1-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
				} else if ((name == "TSL2-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				} else if ((name == "TSL1-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
				} else if ((name == "TSL2-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				}
	
	
	
				if ((name == "TSL3-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_single_ean').val()
					} else {
						var barcode_change = $(this).find('.uk_single_ean').val()
					}
					jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				} else if ((name == "TSL2-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
					jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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
	
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
				if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
					var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			}

			ajax_count++;
		})

		if (ajax_count == document.getElementById("table_row").rows.length) {
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmall(svg_fin, name,zone);
		}


	} else {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.ce_ratio_cpc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_ratio_orms_tpn').val();
				
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_ratio_orms_tpn').val().substr($(this).find('.ce_ratio_orms_tpn').val().length - 3);
				}	
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "TCL5-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.ce_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_single_orms_tpn').val();
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_single_orms_tpn').val().substr($(this).find('.ce_single_orms_tpn').val().length - 3);
				}	
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
			} else if ((name == "TCL1-sticker") || (name == "TCL5-sticker") || (name == "THL1-sticker")) {

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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TCL4-sticker") || (name == "TCL5-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
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
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

                                            svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
                                            
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
                                        else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

					                        svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
                                        else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

                                            svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
                                            
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
                                        else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

					                        svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
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
							if($('#qr_flag').val() == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name === "THL3-sticker")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

									}
									else if(name === "TCL5-sticker")
									{
										svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
										svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
										svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
										svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

										svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
										svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
										svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
										svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
										
										svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
										svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
										
									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name === "THL3-sticker")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

									}
									else if(name === "TCL5-sticker")
									{
										svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
										svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
										svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
										svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

										svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
										svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
										svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
										svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

										svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
										svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
										
									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
									}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
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
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTesco(svg_fin, name,zone);
		}

	}


}

function printDivTescoSet(data, name,set_po_number,set_style) {
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	var nameset = set_po_number+'-'+set_style+'-'+name + "-sticker";
	if (name == "TSL1-sticker") {
		
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			
			
			var svgRoot = svgDoc.documentElement;
			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmallSet(svg_fin, nameset);
		}


	} else if (name == "TSL3-sticker") {
		
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			
			if($(this).find('.count_TSL3').val() > 0)
			{

				var svgRoot = svgDoc.documentElement;
				if ((name == "TSL1-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
				} else if ((name == "TSL2-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				} else if ((name == "TSL1-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
				} else if ((name == "TSL2-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				}
	
	
	
				if ((name == "TSL3-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_single_ean').val()
					} else {
						var barcode_change = $(this).find('.uk_single_ean').val()
					}
					jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				} else if ((name == "TSL2-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
					jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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
	
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
				if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
					var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			}

			
			ajax_count++;
		})

		if (ajax_count == document.getElementById("table_row").rows.length) {
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmallSet(svg_fin, nameset);
		}


	} else if (name == "TSL2-sticker") {
		
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			
			if($(this).find('.count_TSL2').val() > 0)
			{

			var svgRoot = svgDoc.documentElement;
			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			}
			
			ajax_count++;
		})

		if (ajax_count == document.getElementById("table_row").rows.length) {
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSmallSet(svg_fin, nameset);
		}


	} else {
		var svgarr = [];
		var ajax_count = 0;
		$('#table_row tr').each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.ce_ratio_cpc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_ratio_orms_tpn').val();
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_ratio_orms_tpn').val().substr($(this).find('.ce_ratio_orms_tpn').val().length - 3);
				}	
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.ce_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_single_orms_tpn').val();
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_single_orms_tpn').val().substr($(this).find('.ce_single_orms_tpn').val().length - 3);
				}	
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
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
							if($('#qr_flag').val() == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
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
			setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
			var svg_fin = svgarr.join(',_');
			PopupTescoSet(svg_fin, nameset);
		}

	}


}
/**

Generate Print Tesco

*/
function GenerateDivTesco(data, name, obj) {
	$(obj).prop('disabled', true);
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker") {
		var svgarr = [];
        var barcode_final = [];
		var ajax_count = 0;
		var name_count=0;
		$('#table_row tr').each(function (key, val) {
			var svgRoot = svgDoc.documentElement;

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
			} else if ((name == "TSL2-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
			} else if ((name == "TSL2-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				//jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl3",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:barcode_change,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
								var svg_fin = svgarr.join(',_');
								var bar_fin = barcode_final.join(',_');
								PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");

				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TSL2-sticker")) {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
		//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:barcode_change,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
								var svg_fin = svgarr.join(',_');
								var bar_fin = barcode_final.join(',_');
								PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			} else {
				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
		//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:barcode_change,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
								var svg_fin = svgarr.join(',_');
								var bar_fin = barcode_final.join(',_');
								PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = $(this).find('.ce_single_size').val();
			} else {
				var size = $(this).find('.uk_single_size').val();
			}

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
                            barcode_final.push(barcode_change)
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
					barcode_final.push(barcode_change)

				}
			} else {
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
				barcode_final.push(barcode_change)
			}
			
			name_count++;
		})

		

	} else if (name == "TSL3-sticker") {
		var svgarr = [];
        var barcode_final = [];
		var ajax_count = 0;
		var name_count=0;
		$('#table_row tr').each(function (key, val) {

			if($(this).find('.count_TSL3').val() > 0)
			{

			
				var svgRoot = svgDoc.documentElement;

				if ((name == "TSL1-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
				} else if ((name == "TSL2-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				} else if ((name == "TSL1-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
				} else if ((name == "TSL2-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				}
	
	
	
				if ((name == "TSL3-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_single_ean').val()
					} else {
						var barcode_change = $(this).find('.uk_single_ean').val()
					}
					//jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl3",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 1,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								ajax_count++;

								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL3').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
	
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				} else if ((name == "TSL2-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
			//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl1",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 2,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								
								ajax_count++;
								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL3').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
					
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
	
				} else {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
			//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl1",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 2,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								
								ajax_count++;
								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL3').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
					
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
	
				}
	
				if (zone == "CE") {
					var size = $(this).find('.ce_single_size').val();
				} else {
					var size = $(this).find('.uk_single_size').val();
				}
	
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
				if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
								barcode_final.push(barcode_change)
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
						barcode_final.push(barcode_change)
	
					}
				} else {
					var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
					barcode_final.push(barcode_change)
				}
			}
			
			name_count++;
		})

		

	} else if (name == "TSL2-sticker") {
		var svgarr = [];
        var barcode_final = [];
		var ajax_count = 0;
		var name_count=0;
		$('#table_row tr').each(function (key, val) {


			if($(this).find('.count_TSL2').val() > 0)
			{

				var svgRoot = svgDoc.documentElement;

				if ((name == "TSL1-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_ratio_orms_tpn').val();
				} else if ((name == "TSL2-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "CE")) {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + $(this).find('.ce_single_orms_tpn').val();
				} else if ((name == "TSL3-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				} else if ((name == "TSL1-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_ratio_tpnd').val();
				} else if ((name == "TSL2-sticker" && zone == "UK")) {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + $(this).find('.uk_single_tpnd').val();
				}
	
	
	
				if ((name == "TSL3-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_single_ean').val()
					} else {
						var barcode_change = $(this).find('.uk_single_ean').val()
					}
					//jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl3",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 1,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								ajax_count++;
								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL2').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
	
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				} else if ((name == "TSL2-sticker")) {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
			//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl1",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 2,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								
								ajax_count++;
								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL2').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
					
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
	
				} else {
					if (zone == "CE") {
						var barcode_change = $(this).find('.ce_ratio_itf').val()
					} else {
						var barcode_change = $(this).find('.uk_ratio_itf').val()
					}
			//		jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
					
					
		
					$.ajax({
							url: base_url + "/admin/tesco/generatebarcodetsl1",
							type: 'post',
							data: {
								barcode: barcode_change,
								type: 2,
								name:barcode_change,
								_token: csrf_token,
							},
							success: function (data) {
								
								ajax_count++;
								var split_count = 0;
								$('#table_row tr').each(function (key, val) {
									if($(this).find('.count_TSL2').val() > 0)
									{
										split_count++;
									}
								})
								if (ajax_count == split_count) {
									setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
									var svg_fin = svgarr.join(',_');
									var bar_fin = barcode_final.join(',_');
									PopupTescoGenerateSmall(svg_fin, bar_fin, name,svgDoc);
								}
							},
							complete: function () {
	
							}
						})
					//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
					
					svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
					
					svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
	
				}
	
				if (zone == "CE") {
					var size = $(this).find('.ce_single_size').val();
				} else {
					var size = $(this).find('.uk_single_size').val();
				}
	
				var description = $('#tesco_description').val().replace(/&/g,'&#038;')
				if ((name == "TSL3-sticker") || (name == "TSL2-sticker")) {
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
								barcode_final.push(barcode_change)
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
						barcode_final.push(barcode_change)
	
					}
				} else {
					var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
                    barcode_final.push(barcode_change)
				}
			}
			
			
			name_count++;
		})

		

	} else {
		var svgarr = [];
		var ajax_count = 0;
		var name_count=0;
		var qr_tpndarr = [];
		var qr_tpnd = "";
		$('#table_row tr').each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.ce_ratio_cpc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_ratio_orms_tpn').val();

				qr_tpnd =  $(this).find('.ce_ratio_orms_tpn').val();
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_ratio_orms_tpn').val().substr($(this).find('.ce_ratio_orms_tpn').val().length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "TCL5-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.ce_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.ce_single_orms_tpn').val();
				qr_tpnd = $(this).find('.ce_single_orms_tpn').val();
				if($('#qr_flag').val() == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = $(this).find('.ce_single_orms_tpn').val().substr($(this).find('.ce_single_orms_tpn').val().length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = $(this).find('.uk_single_case_size').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.uk_single_tpnd').val();
				qr_tpnd = $(this).find('.uk_single_tpnd').val();
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = $(this).find('.uk_ratio_upc').val();

				svgDoc.getElementById("tpnd").innerHTML = $(this).find('.uk_ratio_tpnd').val();
				qr_tpnd = $(this).find('.uk_ratio_tpnd').val();
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_single_ean').val()
				} else {
					var barcode_change = $(this).find('.uk_single_ean').val()
				}
				//jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeeanbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:barcode_change,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
								var svg_fin = svgarr.join(',_');
								var qr_tpnd_fin = qr_tpndarr.join(',_');
								PopupTescoGenerate(svg_fin, name,svgDoc,qr_tpnd_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				
			} else if ((name == "TCL1-sticker") || (name == "TCL5-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				//jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeitfbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:barcode_change,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
								var svg_fin = svgarr.join(',_');
								var qr_tpnd_fin = qr_tpndarr.join(',_');
								PopupTescoGenerate(svg_fin, name,svgDoc,qr_tpnd_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = $(this).find('.ce_single_size').val();
			} else {
				var size = $(this).find('.uk_single_size').val();
			}

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
			if ((name == "TCL4-sticker") || (name == "TCL5-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
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
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

                                            svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
                                            
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
                                        else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

					                        svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

                                            svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
                                            
                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name === "THL3-sticker")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
                                        else if(name === "TCL5-sticker")
										{
                                            svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
                                            svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
                                            svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
                                            svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

					                        svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
                                            svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
                                            svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
                                            svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

                                            svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
                                            svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
                                            svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
                                            svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
                                            svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
                                            svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
                                            svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
							qr_tpndarr.push(qr_tpnd)
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
							if($('#qr_flag').val() == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name === "THL3-sticker")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

									}
									else if(name === "TCL5-sticker")
									{
										svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 58)')
										svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
										svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 87)')
										svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 98)')

										svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 58)')
										svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
										svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 87)')
										svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 98)')
										
										svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 110)");
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 122)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
										svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 110)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 122)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
										
									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name === "THL3-sticker")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

									}
									else if(name === "TCL5-sticker")
									{
										svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 55)')
										svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 69)')
										svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
										svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

										svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 55)')
										svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 69)')
										svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
										svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')

										svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 102)");
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 134)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 144)");
										svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 102)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 134)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 144)");
										
									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
									}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
						} else {
							svgDoc.getElementById("description").innerHTML = '';
						}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)
					qr_tpndarr.push(qr_tpnd)

				}
			} else {
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
				qr_tpndarr.push(qr_tpnd)
			}


			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				ajax_count++;
			}
			name_count++;
		})
		
		
		
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				if (ajax_count == document.getElementById("table_row").rows.length) {
					setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
					var svg_fin = svgarr.join(',_');
					var qr_tpnd_fin = qr_tpndarr.join(',_');
					PopupTescoGenerate(svg_fin, name,svgDoc,qr_tpnd_fin);
				}

			}

		
	}


}

function nonArtworkOrders()
{
	$.ajax({
		url: base_url + "/admin/order-management/non-artwork-orders",
		type: "get",
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

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
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTesco(data, name,zone) {
	var tesco_po_number = $('#tesco_po_number').val();
	if(zone == "CE")
	{
		var style = $('#tesco_ce_style').val();
	}
	else
	{
		var style = $('#tesco_uk_style').val();
	}
	
	
	if(name == "TCL1-sticker")
	{
		var id = $('#TCL1_id').val();
		var db = "tesco_tcl1_orders";
	}
	else if(name == "TCL2-sticker")
	{
		var id = $('#TCL2_id').val();
		var db = "tesco_tcl2_orders";
	}
	else if(name == "TCL4-sticker")
	{
		var id = $('#TCL4_id').val();
		var db = "tesco_tcl4_orders";
	}
	else if(name == "TCL5-sticker")
	{
		var id = $('#TCL5_id').val();
		var db = "tesco_tcl5_orders";
	}
	else if(name == "THL1-sticker")
	{
		var id = $('#THL1_id').val();
		var db = "tesco_thl1_orders";
	}
	else if(name == "THL3-sticker")
	{
		var id = $('#THL3_id').val();
		var db = "tesco_thl3_orders";
	}
	var tcl1_id = $('#TCL1_id').val();
	var tcl2_id = $('#TCL2_id').val();
	var tcl4_id = $('#TCL4_id').val();
	var tcl5_id = $('#TCL5_id').val();
	var thl1_id = $('#THL1_id').val();
	var thl3_id = $('#THL3_id').val();
	var qr_flag = $('#qr_flag').val();
	var tesco_description = $('#tesco_description').val();
	//	var s = new XMLSerializer();
	//	var d = data;
	//	var str = s.serializeToString(d);
	//	var svg = encodeURI(str)
	//var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			tcl1_id: tcl1_id,
			tcl2_id: tcl2_id,
			tcl4_id: tcl4_id,
			tcl5_id: tcl5_id,
			thl1_id: thl1_id,
			thl3_id: thl3_id,
			qr_flag: qr_flag,
			name: name,
			zone:zone,
			style:style,
			tesco_po_number:tesco_po_number,
			tesco_description:tesco_description,
			id:id,
			db:db
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

function PopupTescoSmall(data, name,zone) {
	if(name == "TSL1-sticker")
	{
		var id = $('#TSL1_id').val();
		var db = "tesco_tsl1_orders";
	}
	else if(name == "TSL3-sticker")
	{
		var id = $('#TSL3_id').val();
		var db = "tesco_tsl3_orders";
	}
	else if(name == "TSL2-sticker")
	{
		var id = $('#TSL2_id').val();
		var db = "tesco_tsl2_orders";
	}
	var tesco_po_number = $('#tesco_po_number').val();
	if(zone == "CE")
	{
		var style = $('#tesco_ce_style').val();
	}
	else
	{
		var style = $('#tesco_uk_style').val();
	}
	
	
	var tesco_description = $('#tesco_description').val();
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			zone:zone,
			style:style,
			tesco_po_number:tesco_po_number,
			tesco_description:tesco_description,
			id:id,
			db:db
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
function PopupTescoSet(data, name) {

	//	var s = new XMLSerializer();
	//	var d = data;
	//	var str = s.serializeToString(d);
	//	var svg = encodeURI(str)
	//var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
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

//			var element = document.createElement('a');
//			element.setAttribute('href', data);
//			element.setAttribute('download', name);
//
//			element.style.display = 'none';
//			document.body.appendChild(element);
//
//			element.click();
//
//			document.body.removeChild(element);

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

function PopupTescoSmallSet(data, name) {

	var url = "";
	
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

//			var element = document.createElement('a');
//			element.setAttribute('href', data);
//			element.setAttribute('download', name);
//
//			element.style.display = 'none';
//			document.body.appendChild(element);
//
//			element.click();
//
//			document.body.removeChild(element);

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
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTescoGenerate(data, name,svgDoc,qr_tpnd_fin) {
	var tcl1_id = $('#TCL1_id').val();
	var tcl2_id = $('#TCL2_id').val();
	var tcl4_id = $('#TCL4_id').val();
	var tcl5_id = $('#TCL5_id').val();
	var thl1_id = $('#THL1_id').val();
	var thl3_id = $('#THL3_id').val();
	var qr_flag = $('#qr_flag').val();
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			tcl1_id: tcl1_id,
			tcl2_id: tcl2_id,
			tcl4_id: tcl4_id,
			tcl5_id: tcl5_id,
			thl1_id: thl1_id,
			thl3_id: thl3_id,
			qr_flag: qr_flag,
			name: name,
			qr_tpnd_fin:qr_tpnd_fin
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
			
			svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

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

function PopupTescoGenerateSmall(data,bar_fin, name,svgDoc) {

	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
            bar_fin:bar_fin,
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
			
			svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

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

function PdfboxGenerateSmall(data, name,svgDoc) {

	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/pdfbox-div-tesco-small",
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
			
			svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

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
 *  addUKSingle function for adding dynamic table row for UK single templates 
 * @author <praveen>
 * 
 */
function addUKSingle(rem, e) {
	try {
		e.preventDefault();
		/** [ Get template details from localstorage ] */
		var data = JSON.parse(localStorage.getItem("data"));
		const stickersList = data.map(template => template.template_name);

		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		
		if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_` + val + `" name="uk_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
			
		} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="return validateEanBarcode(this)" id="uk_single_ean_` + val + `" name="uk_single_ean_` + val + `" placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>	
									</td>`;	
		} else {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_` + val + `" name="uk_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>
									<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="return validateEanBarcode(this)" id="uk_single_ean_` + val + `" name="uk_single_ean_` + val + `" placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>	
									</td>`;
		}

		var html = '<tr>\
                        <td class="slno">' + val + '</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_' + val + '" name="uk_single_tpnd_' + val + '" required="" placeholder="Enter TPND" data-minlength="9" maxlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits.">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        '+barcode_component+'\
                        <td class="case_size_td">\
                            <div class="form-group has-feedback">\
                                <input type="number" class="form-control input-sm uk_single_case_size" required="" onkeypress="return clearQuantity(this)" id="uk_single_case_size_' + val + '" name="uk_single_case_size_' + val + '" required="" placeholder="Enter Units Per Case" max="999999" data-max-error="Max 6 digits allowed">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_' + val + '" name="uk_single_size_' + val + '">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                            <div class="form-group has-feedback quantity-feedback">\
                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="uk_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "999999" data-max-error= "Max 6 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeypress="return validateMOQ(this)"  data-artwork="'+ item.template_name+'"   onkeyup="return validateCaseSizeQty(this)">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
		});
		html += '<td>\
                    <a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a>\
                   </td>\
                   </tr>';
		$('#table_row').append(html);

		/** [ show tooltip ] */
		$('.delete-row').tooltip('show')
		$('.delete-row').tooltip('toggle')

		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

		/** [ Update data-id of add button ] */
		$(rem).attr("data-id", val);

		/** [ Call setSerialNumber function ] */
		setSerialNumber()
		

	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}
/**
 *  addUKRatio function for adding dynamic table row for UK ratio templates 
 * @author <praveen>
 * 
 */
function addUKRatio(rem, e) {
	try {
		e.preventDefault();
		/** [ Get template details from localstorage ] */
		var data = JSON.parse(localStorage.getItem("data"));
		const stickersList = data.map(template => template.template_name);

		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		
		if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_` + val + `" name="uk_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
			
		} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="return validateEanBarcode(this)" id="uk_single_ean_` + val + `" name="uk_single_ean_` + val + `" placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>	
									</td>`;	
		} else {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_` + val + `" name="uk_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>
									<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="return validateEanBarcode(this)" id="uk_single_ean_` + val + `" name="uk_single_ean_` + val + `" placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
		}
		
		var html = '<tr>\
                        <td class="slno">' + val + '</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_' + val + '" name="uk_ratio_tpnd_' + val + '" placeholder="Enter TPND" maxlength="9" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." >\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        '+barcode_component+'\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_' + val + '" name="uk_ratio_upc_' + val + '" placeholder="Enter Units Per Carton" maxlength="4">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                            <div class="form-group has-feedback quantity-feedback">\
                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="uk_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "999999" data-max-error= "Max 6 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
		});
		html += '<td>\
                     <a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i>\
                     </a>\
                   </td>\
                   </tr>';
		$('#table_row').append(html);

		/** [ show tooltip ] */
		$('.delete-row').tooltip('show')
		$('.delete-row').tooltip('toggle')

		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

		/** [ Update data-id of add button ] */
		$(rem).attr("data-id", val);

		/** [ Call setSerialNumber function ] */
		setSerialNumber()
		

	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}
/**
 * Tesco Create Order by PO upload Method.
 * Get the template type and zone from the form along with PDF.
 * File to be uploaded for parsing.
 * Render the the order form with autofilled fields which are parsed from the customer PDF.
 * @return {xml} XML from the parser. 
 */
function uploadPurchaseOrder(formData, zone) {
	try {
		$.ajax({
			url: base_url + "/admin/tesco/po-upload",
			type: 'POST',
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			data: formData,
			dataType: 'JSON',
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$(".loader").show();
			},
			statusCode: {
				200: function (data) {
					if (zone === 'UK') {
						//Call the parse method which resides on parser.js
						parseUKPurchaseOrder(data, createOrders);
					} else {
						//Call the parse method which resides on parser.js
						parseCEPurchaseOrder(data, createOrders);
					}

				},
				422: function (data) {
					$('.snackbar_span').html("File Mismatch!! Please check the file.");
					showSnackbar();
				},
				400: function () {
					$('.snackbar_span').html("Sorry!! You seem to have uploaded an invalid file. Please check the file or Try Manual Mode");
					showSnackbar();
				}
			},
			error: function (errorXHR, errorStatus, errorThrown) {},
			complete: function () {
				/** [To make top menu active] */
				$(function () {
					var url = window.location;
					$('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
					$('.dynamic-menu a').filter(function () {
						return this.href == url;
					}).parent('li').addClass('current-page').find('a').addClass('active');
				});
				$(".loader").hide();
			}
		})

	} catch (e) {
		// error statements
		console.log("Error:" + e)
	}
}

/**
 *  addCESingle function for adding dynamic table row for CE single templates 
 * @author <praveen>
 * 
 */
function addCESingle(rem, e) {
	try {
		e.preventDefault();
		/** [ Get template details from localstorage ] */
		var data = JSON.parse(localStorage.getItem("data"));
		const stickersList = data.map(template => template.template_name);

		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;

		if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" name="ce_ratio_itf_` + val + `" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
			
		} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_` + val + `" name="ce_single_ean_` + val + `" onkeyup="return validateEanBarcode(this)" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;	
		} else {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" name="ce_ratio_itf_` + val + `" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>
									<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_` + val + `" name="ce_single_ean_` + val + `" onkeyup="return validateEanBarcode(this)" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
		}
		
		var html = '<tr>\
                    <td class="slno">' + val + '</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_' + val + '" name="ce_single_orms_tpn_' + val + '" required="" placeholder="Enter ORMS TPN" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." >\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    '+barcode_component+'\
                    <td class="case_size_td">\
                        <div class="form-group has-feedback">\
                            <input type="number" class="form-control input-sm ce_single_case_size" onkeypress="return clearQuantity(this)" id="ce_single_case_size_' + val + '" name="ce_single_case_size_' + val + '" required="" placeholder="Enter Units Per Case" max="999999" data-max-error="Max 6 digits allowed">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_' + val + '" name="ce_single_size_' + val + '" required="" placeholder="Enter Size">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                        <div class="form-group has-feedback quantity-feedback">\
                            <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" data-tname="' + item.template_name + '" id="ce_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="ce_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "999999" data-max-error= "Max 6 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeypress="return validateMOQ(this)"  data-artwork="'+ item.template_name+'"   onkeyup="return validateCaseSizeQty(this)">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
		});
		html += '<td>\
                 <a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i>\
                 </a>\
               </td>\
               </tr>';
		$('#table_row').append(html);

		/** [ show tooltip ] */
		$('.delete-row').tooltip('show')
		$('.delete-row').tooltip('toggle')

		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

		/** [ Update data-id of add button ] */
		$(rem).attr("data-id", val);

		/** [ Call setSerialNumber function ] */
		setSerialNumber()
		
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}
/**
 *  addCERatio function for adding dynamic table row for CE Ratio templates 
 * @author <praveen>
 * 
 */
function addCERatio(rem, e) {
	try {
		e.preventDefault();
		/** [ Get template details from localstorage ] */
		var data = JSON.parse(localStorage.getItem("data"));
		const stickersList = data.map(template => template.template_name);
		
		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;

		if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" name="ce_ratio_itf_` + val + `" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
			
		} else if($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_` + val + `" name="ce_single_ean_` + val + `" onkeyup="return validateEanBarcode(this)" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;	
		} else {
			var barcode_component = `<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_` + val + `" onkeyup="return validateItfBarcode(this)" name="ce_ratio_itf_` + val + `" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>
									<td>
										<div class="form-group has-feedback">
											<input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_` + val + `" name="ce_single_ean_` + val + `" onkeyup="return validateEanBarcode(this)" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">
											<div class="help-block with-errors"></div>
											<div class="barcode-error"></div>
										</div>
									</td>`;
		}

		var html = '<tr>\
                    <td class="slno">' + val + '</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_' + val + '" name="ce_ratio_orms_tpn_' + val + '" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." >\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    '+barcode_component+'\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_' + val + '" name="ce_ratio_cpc_' + val + '" onkeypress="return isNumber(event)" placeholder="Enter No Of Pieces In Ratio" maxlength="4">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                        <div class="form-group has-feedback quantity-feedback">\
                            <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="ce_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "999999" data-max-error= "Max 6 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
		});
		html += '<td>\
                <a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i>\
                </a>\
               </td>\
               </tr>';
		$('#table_row').append(html);

		/** [ show tooltip ] */
		$('.delete-row').tooltip('show')
		$('.delete-row').tooltip('toggle')

		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

		/** [ Update data-id of add button ] */
		$(rem).attr("data-id", val);

		/** [ Call setSerialNumber function ] */
		setSerialNumber()
		
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}
/**
 * Removes row from order quantity table
 *
 * @author <praveen>
 */
function removeRow(rem, event) {
	try {

		event.preventDefault();
		/** [ Remove Row from table ] */
		$(rem).parent().parent().parent().remove();

		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

		/** [ Call setSerialNumber function ] */
		setSerialNumber()

	} catch (e) {
		/** [ error statements ] */
		console.log("Error:" + e)
	}
}
/**
 * Generate dynamic serial number for quantity table
 *
 * @author <praveen>
 */
function setSerialNumber() {
	var j = 1;

	/** [ Dynamically generate slno ] */
	$('#table_row tr').each(function (key, val) {
		$(this).find(".slno").text(j)
		j++;
	})
}
/**
 * Validate quantity moq custom validation code
 * @author <praveen>
 */
function validateMOQ(obj) {
 try {
		var quantity_array = [];
		/** [ Get template details from localstorage ] */
	    var temp_data = JSON.parse(localStorage.getItem("data"));
		/** [create array of all quantities , for validation] */
		$.each(temp_data, function (i, item) {
			var temp_name = item.template_name.replace(' ', '');
			$('.count_' +temp_name ).each(function (i, item) {
				quantity = $(item).val() === '' ? 0 : $(item).val();
				quantity_array.push(quantity);
			});
		});
		if ($(obj).val() != '' && $(obj).val() > 0 ) {
			/** hide quantity missing error msgs */
			$('.quantity-feedback').removeClass('has-error has-danger');
			$('.quantity-feedback-err-msg').css('display', 'none');
			$('.quantity-field').prop('required', false);
			$('.quantity-field').parent().removeClass('has-error')
			$('.help-block').html('');
			
		} else {
			if(quantity_array.every( (val, i, arr) => val === 0 ) === false)
			{
				/** hide quantity missing error msgs */
				$('.quantity-feedback').removeClass('has-error has-danger');
				$('.quantity-feedback-err-msg').css('display', 'none');
				$('.quantity-field').prop('required', false);
				$('.quantity-field').parent().removeClass('has-error')
				$('.help-block').html('');
			}
			else
			{
				/** All quantity value fields empty - Show custom validation message and functionality  */
				$('.quantity-feedback').addClass('has-error has-danger');
				$('.quantity-feedback-err-msg').css('display', 'block');
				$('.quantity-field').prop('required', true);
			}
		}
		/** [ Refresh boostrap form-validator ]  */
		$('#create_tesco_order_form').validator('update');

	} catch (e) {
		/** [ error statements ] */
		console.log("Error:" + e)
	}
}
/**
 * [ dynamicSVGTemplates function for creating dynamic svg file ]
 * @author <praveen@wizroots.com>
 */
function dynamicSVGTemplates() {
	/** [ Get data from localstorage ] */
	var data = JSON.parse(localStorage.getItem("data"));
	try{
		
		$.each(data, function (i, item) {
			dynamicSVGTemplatescard("tesco_svg" + i, item);
			
		});
	
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}
/**
 * [ dynamicSVGTemplatescard function for creating dynamic card svg file ]
 * @author <praveen@wizroots.com>
 */
function dynamicSVGTemplatescard(id, item) {

	var zone = localStorage.getItem("zone");
	var type = localStorage.getItem("template type");
	/**  [ Dynamically change all svg templates ] */
	var mySVG = document.getElementById(id);
	var svgDoc = mySVG.contentDocument;
	var buyer_id  = localStorage.getItem("buyer_id");
	var templates = localStorage.getItem("templates");
	/** [ separate conditions for TSL1,TSL3 and other stickers ] */
	if (item.template_name === "TSL 1" || item.template_name === "TSL 2" || item.template_name === "TSL 3") {

		if (zone === "CE") {
			/**  [ if CE ratio template ] */
			if (type === "ratio") {
				var tpnd = $('#ce_ratio_orms_tpn_1').val();
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');

				var ce_single_ean = $('#ce_single_ean_1').val();
				var ce_ratio_itf = $('#ce_ratio_itf_1').val();

				/** [ Dynamically change svg CE single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}

				if (item.template_name === "TSL 3") {

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
				else if (item.template_name === "TSL 2") {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
				var uk_single_ean = $('#uk_single_ean_1').val();
				var uk_ratio_itf = $('#uk_ratio_itf_1').val();
				/** [ Dynamically change svg UK single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = "TPND: " + orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				if (item.template_name === "TSL 3") {

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
				else if (item.template_name === "TSL 2") {
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
			svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace(/&/g, '&amp;');
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
			svgDoc.getElementById("brand").innerHTML = brand.toUpperCase().replace(/&/g, '&amp;');
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
			svgDoc.getElementById("season_code").innerHTML = season_code.toUpperCase().trim();
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
		    svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace(/&/g, '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
		if (zone === "CE") {
			/**  [ if CE ratio template ] */
			if (type === "ratio") {
				var pieces_in_ratio = $('#ce_ratio_cpc_1').val();
				var tpnd = $('#ce_ratio_orms_tpn_1').val();
				var ce_ratio_itf = $('#ce_ratio_itf_1').val();
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
				/** [ Dynamically change svg CE pieces in ratio ] */
				if (pieces_in_ratio != '') {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
				} else {
					svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
				}
				/** [ Dynamically change svg CE ratio tpnd number ] */
				if (tpnd != '') {
					svgDoc.getElementById("tpnd").innerHTML = tpnd;
					if($('#qr_flag').val() == 1)
					{
						svgDoc.getElementById("tpnd_last_digit").innerHTML = tpnd.substr(tpnd.length - 3);
					}		
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				if (item.template_name === "TCL 1" || item.template_name === "THL 1") {

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
					if($('#qr_flag').val() == 1)
					{
						if(description.length <= 23)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (item.template_name === "TCL 1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (item.template_name === "TCL 2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(item.template_name === "THL 1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = wordWrap(description, 23);
							svgDoc.getElementById("description").innerHTML = str;
							if (item.template_name === "TCL 1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (item.template_name === "TCL 2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(item.template_name === "THL 1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
							}
						}
					}
					else
					{
						/** [ Dynamically change svg short description ] */
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					}					
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
			} else {
				/**  [ if CE single template ] */
				var orms_tpn = $('#ce_single_orms_tpn_1').val();
				var case_size = $('#ce_single_case_size_1').val();
				var ce_single_ean = $('#ce_single_ean_1').val();
				var ce_ratio_itf = $('#ce_ratio_itf_1').val();
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
				/** [ Dynamically change svg CE single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
					if($('#qr_flag').val() == 1)
					{
						svgDoc.getElementById("tpnd_last_digit").innerHTML = orms_tpn.substr(orms_tpn.length - 3);
					}
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				/** [ Dynamically change svg CE single Units Per Case ] */
				if (case_size != '') {
					svgDoc.getElementById("unit_per_case").innerHTML = case_size;
				} else {
					svgDoc.getElementById("unit_per_case").innerHTML = '';
				}
				if (item.template_name === "THL 3") {
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
				else if (item.template_name === "TCL 5") {
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
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										
										if(item.template_name === "THL 3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else if(item.template_name === "TCL 5")
										{
											svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 56)')
											svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
											svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 85)')
											svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 96)')
		
											svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 56)')
											svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
											svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 85)')
											svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 96)')
		
											svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 107)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 119)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 130)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 140)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 107)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 119)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 130)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 140)");
																			
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
										
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										if(item.template_name === "THL 3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else if(item.template_name === "TCL 5")
										{
											console.log("2")
											svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 52)')
											svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 70)')
											svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
											svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')
		
											svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 52)')
											svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 70)')
											svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
											svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')
											
											svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 103)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 126)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 103)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 126)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
										
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if($('#qr_flag').val() == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(item.template_name === "THL 3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else if(item.template_name === "TCL 5")
										{
											svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 56)')
											svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
											svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 85)')
											svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 96)')
		
											svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 56)')
											svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
											svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 85)')
											svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 96)')
		
											svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 107)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 119)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 130)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 140)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 107)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 119)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 130)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 140)");
																			
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = wordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(item.template_name === "THL 3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else if(item.template_name === "TCL 5")
										{
											console.log("2")
											svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 52)')
											svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 70)')
											svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
											svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')
		
											svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 52)')
											svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 70)')
											svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
											svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')
											
											svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 103)");
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 126)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
											svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 103)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 126)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
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
						if($('#qr_flag').val() == 1)
						{
							var desc_size = description.toUpperCase();
							if(desc_size.length <= 22)
							{
								svgDoc.getElementById("description").innerHTML = desc_size;
								if(item.template_name === "THL 3")
								{
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

								}
								else if(item.template_name === "TCL 5")
								{
									svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 56)')
									svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 74)')
									svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 85)')
									svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 96)')

									svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 56)')
									svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 74)')
									svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 85)')
									svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 96)')

									svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 107)");
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 119)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 130)");
									svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 140)");
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 107)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 119)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 130)");
									svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 140)");
																	
								}
								else
								{
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
									svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 150)");
									svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
								}
							}
							else
							{
								str = wordWrap(desc_size, 22);
								svgDoc.getElementById("description").innerHTML = str;

								if(item.template_name === "THL 3")
								{
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

								}
								else if(item.template_name === "TCL 5")
								{
									console.log("2")
									svgDoc.getElementById("orms_tpnd_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 52)')
									svgDoc.getElementById("orms_po_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 70)')
									svgDoc.getElementById("uk_style_text").setAttribute('transform','matrix(1, 0, 0, 1, 5.4283, 81)')
									svgDoc.getElementById("ce_style_text").setAttribute('transform','matrix(0.8259, 0, 0, 1, 5.4282, 92)')

									svgDoc.getElementById("tpnd").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 52)')
									svgDoc.getElementById("po_number").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 70)')
									svgDoc.getElementById("uk_style").setAttribute('transform','matrix(0.9848, 0, 0, 1, 91.1543, 81)')
									svgDoc.getElementById("ce_style").setAttribute('transform','matrix(1, 0, 0, 1, 91.1544, 92)')
									
									svgDoc.getElementById("short_text").setAttribute('transform', "matrix(0.8616, 0, 0, 1, 5.4287, 103)");
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 126)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
									svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 103)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 126)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 136)");
									svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 146)");
									
								}
								else
								{
									svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
									svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
									svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
									svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
									svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.84, 0, 0, 1, 91.1544, 163)");
									svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
								}
							}
						}
						else
						{
							/** [ Dynamically change svg short description ] */
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
						}					
					} 
					else {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');

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
				if (item.template_name === "TCL 1" || item.template_name === "THL 1") {
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
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
				/** [ Dynamically change svg Uk single tpnd number ] */
				if (orms_tpn != '') {
					svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
				} else {
					svgDoc.getElementById("tpnd").innerHTML = '';
				}
				/** [ Dynamically change svg Uk single Units Per Case ] */
				if (case_size != '') {
					svgDoc.getElementById("unit_per_case").innerHTML = case_size;
				} else {
					svgDoc.getElementById("unit_per_case").innerHTML = '';
				}

				if (item.template_name === "THL 3") {
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

/**
 * [setTescoOrder for storing tesco manual orders(Submit order)]
 * @author <praveen>
 */
function setTescoOrder() {
	try {
		var zone = localStorage.getItem("zone");
		var type = localStorage.getItem("template type");
		var data = JSON.parse(localStorage.getItem("data"));

		var count_TCL1 = [];
		var count_TCL2 = [];
		var count_TSL1 = [];
		var count_TCL4 = [];
		var count_TSL3 = [];
		var count_THL3 = [];
		var count_THL1 = [];
		var msg        = '';

		if (zone === "CE") {
			if (type === "ratio") {
				if (data[0].template_name === 'THL 1') {
					/** [ Create array of ce ratio thl1 count ] */
					$('.count_THL1').each(function (i, item) {
						count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var empty_count = 0;
					var l = count_THL1.length;
					for (var i = 0; i < l; i++) {
						if (count_THL1[i] === 0 || count_THL1[i] === '0' || count_THL1[i] === '00' || count_THL1[i] === '000' || count_THL1[i] === '0000') {
							empty_count += 1;
						}
					}

					/** [ Join artworks count array using ' , '] */
					count_THL1 = count_THL1.join(',');

					/** [Check if valid barcode entered] */
					if (barcode_checks === 0) {
						/** [ Check if count of empty quantity in the array is equal to array length ] */
						if (empty_count === l) {
							/** All quantity value fields empty - Show custom validation message and functionality  */
							$('.quantity-feedback').addClass('has-error has-danger');
							$('.quantity-feedback-err-msg').css('display', 'block');
							$('.quantity-field').prop('required', true)
						} else {
							/** All quantity value fields empty - hide custom validation message and functionality  */
							$('.quantity-feedback-err-msg').css('display', 'none');
							$('.quantity-field').prop('required', false)
							/** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
							if (empty_count > 0) {
								msg = '<li style="margin-bottom: 10px;"><strong>THL 1</strong></li>';
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeCERatioTHL1(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store CE ratio THL1 template */
								storeCERatioTHL1();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_ce_style').val();
								printDivTescoSet('tesco_svg0','THL1',set_po_number,set_style)
							}
						}
					}
				} else {
					/** [ Create array of ce ratio tcl1 count ] */
					$('.count_TCL1').each(function (i, item) {
						count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of ce ratio tcl2 count ] */
					$('.count_TCL2').each(function (i, item) {
						count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of ce ratio tsl1 count ] */
					$('.count_TSL1').each(function (i, item) {
						count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var templates_array = [];
					templates_array = count_TCL1.concat(count_TCL2).concat(count_TSL1);
					var empty_count = 0;
					var l = templates_array.length;
					for (var i = 0; i < l; i++) {
						if (templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000') {
							empty_count += 1;
						}
					}

					/** Check If artwork count array contains atleast one '0' OR empty value **/
					if (count_TCL1.includes('0') || count_TCL1.includes('00') || count_TCL1.includes('000') || count_TCL1.includes('0000') || count_TCL1.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 1</strong></li>';
					}
					if (count_TCL2.includes('0') || count_TCL2.includes('00') || count_TCL2.includes('000') || count_TCL2.includes('0000') || count_TCL2.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 2</strong></li>';
					}
					if (count_TSL1.includes('0') || count_TSL1.includes('00') || count_TSL1.includes('000') || count_TSL1.includes('0000') || count_TSL1.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TSL 1</strong></li>';
					}

					/** [ Join artworks count array using ' , '] */
					count_TCL1 = count_TCL1.join(',');
					count_TCL2 = count_TCL2.join(',');
					count_TSL1 = count_TSL1.join(',');

					/** [Check if valid barcode entered] */
					if (barcode_checks === 0) {
						/** [ Check if count of empty quantity in the array is equal to array length ] */
						if (empty_count === l) {
							/** All quantity value fields empty - Show custom validation message and functionality  */
							$('.quantity-feedback').addClass('has-error has-danger');
							$('.quantity-feedback-err-msg').css('display', 'block');
							$('.quantity-field').prop('required', true)
						} else {
							/** All quantity value fields empty - hide custom validation message and functionality  */
							$('.quantity-feedback-err-msg').css('display', 'none');
							$('.quantity-field').prop('required', false)
							/** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
							if (empty_count > 0) {
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeCERatioTCL1TCL2TSL1(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store CE ratio TCL1, TCL2, TSL1 template */
								storeCERatioTCL1TCL2TSL1();
								
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_ce_style').val();
								printDivTescoSet('tesco_svg0','TCL1',set_po_number,set_style)
								printDivTescoSet('tesco_svg1','TCl2',set_po_number,set_style)
								printDivTescoSet('tesco_svg2','TSL1',set_po_number,set_style)
							}
						}
					}

				}

			} else {
				storeSingleSticker('admin'); 
			}
		} else {
			if (type === "ratio") {
				if (data[0].template_name === 'THL 1') {
					/** [ Create array of uk ratio thl1 count ] */
					$('.count_THL1').each(function (i, item) {
						count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var empty_count = 0;
					var l = count_THL1.length;
					for (var i = 0; i < l; i++) {
						if (count_THL1[i] === 0 || count_THL1[i] === '0' || count_THL1[i] === '00' || count_THL1[i] === '000' || count_THL1[i] === '0000') {
							empty_count += 1;
						}
					}

					/** [ Join artworks count array using ' , '] */
					count_THL1 = count_THL1.join(',');

					/** [Check if valid barcode entered] */
					if (barcode_checks === 0) {
						/** [ Check if count of empty quantity in the array is equal to array length ] */
						if (empty_count === l) {
							/** All quantity value fields empty - Show custom validation message and functionality  */
							$('.quantity-feedback').addClass('has-error has-danger');
							$('.quantity-feedback-err-msg').css('display', 'block');
							$('.quantity-field').prop('required', true)
						} else {
							/** All quantity value fields empty - hide custom validation message and functionality  */
							$('.quantity-feedback-err-msg').css('display', 'none');
							$('.quantity-field').prop('required', false)
							/** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
							if (empty_count > 0) {
								msg += '<li style="margin-bottom: 10px;"><strong>THL 1</strong></li>';
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeUKRatioTHL1(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store UK ratio THL1 template */
								storeUKRatioTHL1();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_uk_style').val();
								printDivTescoSet('tesco_svg0','THL1',set_po_number,set_style)
							}
						}
					}

				} else {
					/** [ Create array of uk ratio tcl1 count ] */
					$('.count_TCL1').each(function (i, item) {
						count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of uk ratio tcl2 count ] */
					$('.count_TCL2').each(function (i, item) {
						count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of uk ratio tsl1 count ] */
					$('.count_TSL1').each(function (i, item) {
						count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var templates_array = [];
					templates_array = count_TCL1.concat(count_TCL2).concat(count_TSL1);
					var empty_count = 0;
					var l = templates_array.length;
					for (var i = 0; i < l; i++) {
						if (templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000') {
							empty_count += 1;
						}
					}

					/** Check If artwork count array contains atleast one '0' OR empty value **/
					if (count_TCL1.includes('0') || count_TCL1.includes('00') || count_TCL1.includes('000') || count_TCL1.includes('0000') || count_TCL1.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 1</strong></li>';
					}
					if (count_TCL2.includes('0') || count_TCL2.includes('00') || count_TCL2.includes('000') || count_TCL2.includes('0000') || count_TCL2.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 2</strong></li>';
					}
					if (count_TSL1.includes('0') || count_TSL1.includes('00') || count_TSL1.includes('000') || count_TSL1.includes('0000') || count_TSL1.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TSL 1</strong></li>';
					}

					/** [ Join artworks count array using ' , '] */
					count_TCL1 = count_TCL1.join(',');
					count_TCL2 = count_TCL2.join(',');
					count_TSL1 = count_TSL1.join(',');

					/** [Check if valid barcode entered] */
					if (barcode_checks === 0) {
						/** [ Check if count of empty quantity in the array is equal to array length ] */
						if (empty_count === l) {
							/** All quantity value fields empty - Show custom validation message and functionality  */
							$('.quantity-feedback').addClass('has-error has-danger');
							$('.quantity-feedback-err-msg').css('display', 'block');
							$('.quantity-field').prop('required', true)
						} else {
							/** All quantity value fields empty - hide custom validation message and functionality  */
							$('.quantity-feedback-err-msg').css('display', 'none');
							$('.quantity-field').prop('required', false)
							/** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
							if (empty_count > 0) {
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeUKRatioTCL1TCL2TSL1(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store UK ratio TCL1, TCL2, TSL1 template */
								storeUKRatioTCL1TCL2TSL1();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_uk_style').val();
								printDivTescoSet('tesco_svg0','TCL1',set_po_number,set_style)
								printDivTescoSet('tesco_svg1','TCL2',set_po_number,set_style)
								printDivTescoSet('tesco_svg2','TSL1',set_po_number,set_style)
							}
						}
					}

				}

			} else {
				storeSingleSticker('admin'); 
			}
		}

	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [ tescoAdminOrderCommonData  function for getting tesco order common values ( Independent of zone and template type ) .Creating global variables ]
 * @return {[type]} [ Common data ]
 * @author <praveen>
 */
function tescoAdminOrderCommonData() {
	po_number 		 = $('#tesco_po_number').val();
	uk_style 		 = $('#tesco_uk_style').val();
	ce_style 		 = $('#tesco_ce_style').val();
	season_code 	 = $('#tesco_season_code').val();
	description      = $('#tesco_description').val();
	eqos             = $('#tesco_eqos').val();
	manufacture_date = $('#tesco_manufacture_date').val();
	department  	 = $("#tesco_department option:selected").val();
	brand 			 = $("#tesco_brand option:selected").val();
	supplier 		 = $("#tesco_supplier option:selected").val();
	rfid 			 = $("#tesco_rfid option:selected").val() === "yes" ? 1 : 0;
	tag_at_source    = $("#tesco_tag_at_source option:selected").val() === "yes" ? 1 : 0;
	zone			 = localStorage.getItem("zone");
	type             = localStorage.getItem("template type");
	data             = JSON.parse(localStorage.getItem("data"));
	ocn_no           = $('#tesco_ocn').val();
	full_description = $('#tesco_full_description').val();
	created_by       = '';
}

/**
 * [storeCERatioTCL1TCL2TSL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function storeCERatioTCL1TCL2TSL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_TCL1 = [];
		var count_TCL2 = [];
		var count_TSL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();
		var qr_code = [];
		var itf_code_array = [];

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();
            itf_code_array.push($(item).val()); 
		});
		itf_code = itf_code.join(',');

		/** [ Create array of ce ratio orms tpn and ce ratio qr code ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();
			 
			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+itf_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce ratio case per carton ] */
		$('.ce_ratio_cpc').each(function (i, item) {
			cases_per_carton[i] = $(item).val();

		});
		cases_per_carton = cases_per_carton.join(',');

		/** [ Create array of ce ratio tcl1 count ] */
		$('.count_TCL1').each(function (i, item) {
			count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL1 = count_TCL1.join(',');

		/** [ Create array of ce ratio tcl2 count ] */
		$('.count_TCL2').each(function (i, item) {
			count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL2 = count_TCL2.join(',');

		/** [ Create array of ce ratio tsl1 count ] */
		$('.count_TSL1').each(function (i, item) {
			count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL1 = count_TSL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-ce-ratio-template-orders",
			type: 'POST',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				itf_code: itf_code,
				cases_per_carton: cases_per_carton,
				count_TCL1: count_TCL1,
				count_TCL2: count_TCL2,
				count_TSL1: count_TSL1,
				templates: data,
				is_insert: is_insert,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
			},
			success: function (data) {
				/** Call  afterAdminOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterAdminOrderSubmitted(data,po_number,stl_no)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [storeCERatioTHL1 for storing tesco CE ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function storeCERatioTHL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();
		var qr_code = [];
		var itf_code_array = [];

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();
            itf_code_array.push($(item).val()); 
		});
		itf_code = itf_code.join(',');

		/** [ Create array of ce ratio orms tpn and ce ratio qr code ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();
			 
			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+itf_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce ratio case per carton ] */
		$('.ce_ratio_cpc').each(function (i, item) {
			cases_per_carton[i] = $(item).val();

		});
		cases_per_carton = cases_per_carton.join(',');

		/** [ Create array of ce ratio tcl1 count ] */
		$('.count_THL1').each(function (i, item) {
			count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL1 = count_THL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-ce-ratio-template-orders",
			type: 'POST',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				itf_code: itf_code,
				cases_per_carton: cases_per_carton,
				count_THL1: count_THL1,
				templates: data,
				thl1_last_id: thl1_last_id,
				is_insert: is_insert,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
			},
			success: function (data) {

				/** Call  afterAdminOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterAdminOrderSubmitted(data,po_number,stl_no)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}


/**
 * [storeUKRatioTCL1TCL2TSL1 for storing tesco UK ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function storeUKRatioTCL1TCL2TSL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var itf_code = [];
		var units_per_carton = [];
		var count_TCL1 = [];
		var count_TCL2 = [];
		var count_TSL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();


		/** [ Create array of uk ratio orms tpn ] */
		$('.uk_ratio_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk ratio itf barcode ] */
		$('.uk_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

		/** [ Create array of uk ratio units per carton ] */
		$('.uk_ratio_upc').each(function (i, item) {
			units_per_carton[i] = $(item).val();

		});
		units_per_carton = units_per_carton.join(',');

		/** [ Create array of uk ratio tcl1 count ] */
		$('.count_TCL1').each(function (i, item) {
			count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL1 = count_TCL1.join(',');

		/** [ Create array of uk ratio tcl2 count ] */
		$('.count_TCL2').each(function (i, item) {
			count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL2 = count_TCL2.join(',');

		/** [ Create array of uk ratio tsl1 count ] */
		$('.count_TSL1').each(function (i, item) {
			count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL1 = count_TSL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-uk-ratio-template-orders",
			type: 'POST',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				itf_code: itf_code,
				units_per_carton: units_per_carton,
				count_TCL1: count_TCL1,
				count_TCL2: count_TCL2,
				count_TSL1: count_TSL1,
				templates: data,
				is_insert: is_insert,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
			},
			success: function (data) {
				/** Call  afterAdminOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterAdminOrderSubmitted(data,po_number,stl_no)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * [storeUKRatioTHL1 for storing tesco UK ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function storeUKRatioTHL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var itf_code = [];
		var units_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();

		/** [ Create array of uk ratio orms tpn ] */
		$('.uk_ratio_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk ratio itf barcode ] */
		$('.uk_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

		/** [ Create array of uk ratio units per carton ] */
		$('.uk_ratio_upc').each(function (i, item) {
			units_per_carton[i] = $(item).val();

		});
		units_per_carton = units_per_carton.join(',');

		/** [ Create array of uk ratio thl1 count ] */
		$('.count_THL1').each(function (i, item) {
			count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL1 = count_THL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-uk-ratio-template-orders",
			type: 'POST',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				itf_code: itf_code,
				units_per_carton: units_per_carton,
				count_THL1: count_THL1,
				templates: data,
				is_insert: is_insert,
				thl1_last_id: thl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
			},
			success: function (data) {
				/** Call  afterAdminOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterAdminOrderSubmitted(data,po_number,stl_no)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * showSnackbar function
 * @author    <praveen>
 */
function showSnackbar() {
	try {
		/** [ Get the snackbar DIV ] */
		var x = document.getElementById("snackbar")

		/** [ Add the "show" class to DIV ] */
		x.className = "show";

		/** [  After 3 seconds, remove the show class from DIV ] */
		setTimeout(function () {
			x.className = x.className.replace("show", "");
		}, 3000);
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**
 * After submitting the order callback function.
 * @author    <praveen>
 */
function afterAdminOrderSubmitted(data,po_number,stl_no) {
	var order_date = data.ordered_date.date;
	var delivery_date_req = data.delivery_date_required;
	var last_insertions_length = Object.keys(data.last_insertions).length;

	// Handle po upload modal button issue after page reloading
	$('.po-submit-btn').text('Save');
	$('.po-submit-btn').attr('data-dismiss', '')
	$('.upload-later').show();
	/** Show snackbar if order placed **/
	if (last_insertions_length > 0) {
		/** Make form as readonly */
		$('#create_tesco_order_form input').prop('disabled', 'true');
		$('.selectpicker').prop('disabled', true);
		$('#tesco_manufacture_date').prop('disabled',true);
		$("#datePicker1").datepicker("disabled");
		$('.delete-row, .add-row-btn, .submit-btn, .save-btn').remove();
		/** remove save & submit button */
		$('.submit-btn').remove();
		$('.save-btn').remove();
		$('.cancel-btn').show();
		$('.print-btn').show();
		var div_html = '';
		/** [ store last updated id as hidden input , for approve/cancel order functionality] */
		$.each(data.last_insertions, function (index, value) {
			div_html += '<input class="table-id-hidden" id="' + index.replace(' ', '') + '_id" name="' + index.replace(' ', '') + '_id" type="hidden" value="' + value + '">';

		});
		$('.invisible-div').html(div_html);
		$('.snackbar_span').html('Your order has been placed successfully');
		showSnackbar();

		// check if po upload is required or not.
		if(data.po_required === 1)
		{ 
			/** Buyer Po upload modal for combined po upload modal */
			var html = '<div class="container-fluid">\
	                 <div class="template-error1"></div>\
	                     <div class="col-md-12">\
	                         <br> <strong>Select Artworks :</strong>';
			$.each(data.last_insertions, function (index, value) {
				html += '<div class="row" id="' + index.replace(' ', '') + '_row">\
				          <div class="col-md-4">\
				           <div class="form-check form-group has-feedback">\
	                        <label class="form-check-label">\
	                         <input class="form-check-input template-checkbox1" type="checkbox" value="' + value + '" name="' + index.replace(' ', '') + '" id="' + index.replace(' ', '') + '" style="margin-right: 10px;" data-name="' + index.replace(' ', '') + '"> ' + index + ' </label>\
	                     <div class="help-block with-errors"></div>\
	                 </div></div>\
	                 <div class="col-md-8 '+index.replace(' ', '')+'-po-div po-number-div" style="display:none; margin-top: 5px;">\
	                 <strong><span id="'+index.replace(' ', '')+'-uploaded-po"></span></strong>\
	                 <i aria-hidden="true" style="color: green;margin-left: 9px;font-size: 15px;" class="fa fa-check"></i>\
	                 </div></div>';
			});
			html += '</div>\
	                     <div class="row">\
	                         <div class="col-md-12 upload-po-form">\
	                             <div class="col-md-6">\
	                                 <div class="form-group has-feedback">\
	                                     <label for="po_number" class="form_label">PO Number</label>\
	                                     <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text" maxlength="11">\
	                                     <div class="help-block with-errors"></div>\
	                                 </div>\
	                             </div>\
	                             <div class="col-md-6">\
	                                 <div class="form-group has-feedback">\
	                                     <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="po_file" name="po_file" class="po-file-upload form-control center-block" required="" accept="application/pdf"> </span>\
	                                     <div class="help-block with-errors"></div>\
	                                 </div>\
	                             </div>\
	                             <div class="col-md-6">';
			/** [ Delivery Date mandatory or not for particular customer] */
			if (delivery_date_req === 1) {
				html += '<div class="form-group has-feedback">\
	                     <label for="combined_delivery_date">Delivery Date</label>\
	                     <div class="input-group date" data-provide="datepicker" id="delivery_datepicker">\
	                         <input value="" id="combined_delivery_date" required="" name="combined-delivery-date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
	                         <div class="input-group-addon">\
	                            <span class="glyphicon glyphicon-th"></span >\
	                         </div >\
	                     </div >\
	                     <div class="help-block with-errors"></div>\
	                 </div>\
	                 </div>';
			} else {
				html += '<div class="form-group has-feedback">\
	                     <label for="combined_delivery_date">Delivery Date</label>\
	                     <div class="input-group date" data-provide="datepicker" id="delivery_datepicker">\
	                         <input value="" id="combined_delivery_date" name="combined-delivery-date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
	                         <div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span>\
	                         </div>\
	                     </div>\
	                     <div class="help-block with-errors"></div>\
	                 </div>\
	                 </div>';
			}
			html += '        </div>\
	                       </div>\
	                    </div>';
			/** Dynamic content for po upload modal tab */
			$('#po-upload-modal-content').html(html);
			/** [ Backdrop for bootstrap modal ] */
			$('#poUploadModal').modal({
				backdrop: 'static',
				keyboard: false
			})
			$('#poUploadModal').modal('show');
			
			/** @type {Date} [Delivery date Datepicker format & validation] */
			var today = new Date(order_date);
			$('#delivery_datepicker').datepicker({
				autoclose: true,
				startDate: today,
				format: 'yyyy-mm-dd',
			})

			/* [ Toggle required field validation in for fields ] */
			$('.template-checkbox1:checkbox').on('change', function () {
				var th = $(this),
					id = th.prop('id');
				if (th.is(':checked')) {
					var checkbox_clicked = 0;
					/** [ Get count of checkboxes checked ] */
					$('.template-checkbox1').each(function (i, item) {
						if ($(this).is(':checked')) {
							checkbox_clicked++;
						}

					});
					/** [ Hide validation error, if atleast 1 artwork selected ] */
					if (checkbox_clicked === 1) {
						$('.template-error1').html('');
					}
				} else {
					/** [ Clear all input fields values] */
					$('input:not(.template-checkbox1)[id="' + id + '"]').val("");
					/* [ remove validation error from input field ] */
					$('#upload_combined_po_form').validator('validate');
				}
			});
		}
		else if(data.pi_required === 1 && data.po_required != 1 )
		{
			var template_id = '';
			var tabl_name = "";
			var order_flag = "newOrder";
			var pi_status  = false ;
			var template_name = Object.keys(data.last_insertions)[0].replace(/ /g, "");
			var row_id = data.last_insertions[Object.keys(data.last_insertions)[0]];
			var temp_details = JSON.parse(localStorage.getItem("data"));
			$.each(temp_details, function (i, item) {
	            if(temp_details[i].template_name == Object.keys(data.last_insertions)[0]){
	          	  	template_id = temp_details[i].id;
	         	}
	        });
			switch(template_name){
				case 'TCL1' : tabl_name = "tesco_tcl1_orders";
								break;
				case 'TCL2' : tabl_name = "tesco_tcl2_orders";
								break;
				case 'TSL1' : tabl_name = "tesco_tsl1_orders";
								break;
				case 'TCL4' : tabl_name = "tesco_tcl4_orders";
								break;
				case 'TSL3' : tabl_name = "tesco_tsl3_orders";
								break;
				case 'THL1' : tabl_name = "tesco_thl1_orders";
								break;
				case 'THL3' : tabl_name = "tesco_thl3_orders";
								break;
				case 'TCL5' : tabl_name = "tesco_tcl5_orders";
								break;
				case 'TSL2' : tabl_name = "tesco_tsl2_orders";
								break;
			}
			genarateTescoPI(row_id,tabl_name,pi_status,stl_no,template_id,po_number,template_name,order_flag)
		}	
		$.each(data.zero_quantity, function (key, item) {
			/** remove zero quantity templates from po upload modal */
			$("#"+ item.replace(' ', '') + '_row').remove();

			/** [ Remove print and proof genration button if quantity is 0 ] */
			$('#print_' + item.replace(' ', '') + '_btn').hide();
			$('#proof_' + item.replace(' ', '') + '_btn').hide();
		});
	}

	/** [ Show order already exists alertbox] */
	if (data.already_exists.length === 0) {
		$('.po-modal-content').show();
		$('#order-exists-alert').html('');
	} else {
		var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                             <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                              <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                <span aria-hidden="true">×</span>\
                              </button>\
                             <p>Order already exists for following artworks,</p>\
                            <ol>';
		$.each(data.already_exists, function (index, value) {
			html_content += '<li>' + value + '</li>';
		});
		html_content += '</ol>\
                         </div>';

		$('#order-exists-alert').html(html_content);

		/** [ Show only alertbox if data already exists ] */
		if (last_insertions_length === 0) {
			$('.po-modal-content').hide();
			$('.close-btn').attr('data-dismiss', 'modal')
		}
		$('#poUploadModal').modal('show');
			/** [ Backdrop for bootstrap modal ] */
		$('#poUploadModal').modal({
			backdrop: 'static',
			keyboard: false
		})
	}

	/** Update bootstrap validator */
	$('#upload_combined_po_form').validator('update');

	/** [ bootstrap validator for upload combined po form ] **/
	$('#upload_combined_po_form').validator().on('submit', function (e) {
		if (e.isDefaultPrevented()) {

		} else {
			e.preventDefault();
			var checkbox_clicked = 0;
			/** [ Get count of checkboxes checked ] */
			$('.template-checkbox1').each(function (i, item) {
				if ($(this).is(':checked')) {
					checkbox_clicked++;
				}

			});
			/** [ Show validation error, if atleast 1 artwork not selected ] */
			if (checkbox_clicked < 1) {
				/**  [ Show validation error message ] **/
				$('.template-error1').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
			} else {
				$('.template-error1').html('');
				var templateName = [];
				var formData = new FormData();
				var delivery_date = $('input[name="combined-delivery-date"]').val();
				var po_number = $('#po_number').val();
				var po_file = $('#po_file').prop('files')[0];
				template_id = [];
				templates = [];
				/** [ Create array of Templates selected ] */
				$('.template-checkbox1').each(function (i, item) {
					if ($(this).is(':checked')) {
						template_id[i]    = $(this).val();
						templates[i]      = $(this).data('name');
						templateName.push($(this).data('name'));
					}
				});

				formData.append('template_id', template_id);
				formData.append('templates', templates);
				formData.append('delivery_date', delivery_date);
				formData.append('po_number', po_number);
				formData.append('po_file', po_file);

				uploadCombinedPO(formData, templateName);
			}
		}

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
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function openmodaltesco(modal, i) {
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
		dynamicSVGTemplatescard("tesco_svg_modal" + i, data[i]);
	});

	//}, false);

}

/**
 * Function for uploading combined po files
 * @author    <praveen>
 */
function uploadCombinedPO(formdata, templateName) {
	try {
		$.ajax({
			url: base_url + "/admin/tesco/upload-tesco-combined-po-file",
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: formdata,
			tryCount: 0,
			retryLimit: 2,
			dataType: 'JSON',
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function () {
				$('.po-submit-btn').prop('disabled', true);
			},
			statusCode: {
				200: function (data) {
					// enable submit button after po upload
					$('.po-submit-btn').prop('disabled', false);
					
					/** [ Disable already uploaded templates checkbox] */
					$.each(templateName, function (index, value) {
						$('input[type="checkbox"]#' + value).prop('checked', false)
						$('input[type="checkbox"]#' + value).prop('disabled', true)
						$('.'+ value+'-po-div').show();
						$('#'+ value+'-uploaded-po').text("PO Number : " + data.po_number);
					});

					/** [ Rename button and hide form fields if Po uploaded for all templates ] */
					if($('.template-checkbox1').filter(':not(:disabled)').length === 0)
					{
						$('.po-submit-btn').text('Place Order');
						$('.po-submit-btn').attr('data-dismiss', 'modal')
						$('.upload-later').hide();
						$('.upload-po-form').hide();
					}
					/** [ Show success alert] */
					$("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
						$("#success-alert").slideUp(500);
					});
					/** Reset po upload form**/
					$('#upload_combined_po_form').trigger('reset');
				},
				400: function () {
					$('.snackbar_span').html("Something went wrong !. Try again");
					showSnackbar();
				}
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {}
		})
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**
 * This Function is used to check ITF barcode is valid or not.
 * 
 * @return boolean
 */
function checkjsbarcodeitf(code) {
	var return_value = "";
	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 2.50,
		height: 100,
		fontSize: 30,
		flat: true,
		valid: function (valid) {
			if (valid == false) {
				return_value = "false";
			} else {
				return_value = "true";
			}
		}
	});
	return return_value;
}

/**
 * This Function is used to check EAN barcode is valid or not.
 * 
 * @return boolean
 */
function checkjsbarcodeean(code) {
	var return_value = "";
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.45,
		height: 125,
		fontSize: 35,
		flat: true,
		valid: function (valid) {
			if (valid == false) {
				return_value = "false";
			} else {
				return_value = "true";
			}
		}
	});
	return return_value;
}

/**
 * [saveTescoOrder for drafting tesco manual orders(Save order)]
 * @author <praveen>
 */
function saveTescoOrder() {
	try {
		var zone = localStorage.getItem("zone").toLowerCase();
		var type = localStorage.getItem("template type");
		var data = JSON.parse(localStorage.getItem("data"));
		var po_number = $('#tesco_po_number').val();
		var style = $('#tesco_'+zone+'_style').val();
		
		/** Check if PO Number null or not */
		if ((po_number === '' ) || (po_number.toLowerCase() === 'null')) {
			$('.snackbar_span').html('Please Enter PO Number !!');
			showSnackbar();

			return false;
		}

		if ((style === '') || (style.toLowerCase() === 'null')) {
			$('.snackbar_span').html('Please Enter ' + zone.toUpperCase() + ' Style Number !!');
			showSnackbar();
			return false;
		}

		if (zone === "ce") {
			if (type === "ratio") {
				if (data[0].template_name === 'THL 1') {
					/** Function to save CE ratio THL1 template */
					saveCERatioTHL1();
				} else {
					/** Function to save CE ratio TCL1, TCL2, TSL1 template */
					saveCERatioTCL1TCL2TSL1();
				}

			} else {
				saveSingleStickers('admin');
			}
		} else {
			if (type === "ratio") {
				if (data[0].template_name === 'THL 1') {
					/** Function to save UK ratio THL1 template */
					saveUKRatioTHL1();
				} else {
					/** Function to save UK ratio TCL1, TCL2, TSL1 template */
					saveUKRatioTCL1TCL2TSL1();
				}

			} else {
				saveSingleStickers('admin');
			}
		}

	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [saveCERatioTCL1TCL2TSL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function saveCERatioTCL1TCL2TSL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_TCL1 = [];
		var count_TCL2 = [];
		var count_TSL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();
		var qr_code = [];
		var itf_code_array = [];

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();
			itf_code_array.push($(item).val());

		});
		itf_code = itf_code.join(',');

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+itf_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce ratio case per carton ] */
		$('.ce_ratio_cpc').each(function (i, item) {
			cases_per_carton[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		cases_per_carton = cases_per_carton.join(',');

		/** [ Create array of ce ratio tcl1 count ] */
		$('.count_TCL1').each(function (i, item) {
			count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL1 = count_TCL1.join(',');

		/** [ Create array of ce ratio tcl2 count ] */
		$('.count_TCL2').each(function (i, item) {
			count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL2 = count_TCL2.join(',');

		/** [ Create array of ce ratio tsl1 count ] */
		$('.count_TSL1').each(function (i, item) {
			count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL1 = count_TSL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-ce-ratio-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				itf_code: itf_code,
				cases_per_carton: cases_per_carton,
				count_TCL1: count_TCL1,
				count_TCL2: count_TCL2,
				count_TSL1: count_TSL1,
				templates: data,
				is_insert: is_insert,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)

			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [saveCERatioTHL1 for storing tesco CE ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function saveCERatioTHL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();
		var qr_code = [];
		var itf_code_array = [];

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();
			itf_code_array.push($(item).val());

		});
		itf_code = itf_code.join(',');

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+itf_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce ratio case per carton ] */
		$('.ce_ratio_cpc').each(function (i, item) {
			cases_per_carton[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		cases_per_carton = cases_per_carton.join(',');

		/** [ Create array of ce ratio thl1 count ] */
		$('.count_THL1').each(function (i, item) {
			count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL1 = count_THL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-ce-ratio-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				itf_code: itf_code,
				cases_per_carton: cases_per_carton,
				count_THL1: count_THL1,
				templates: data,
				is_insert: is_insert,
				thl1_last_id: thl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [saveCESingleTCL4TSL3 for storing tesco CE single TCL4 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function saveCESingleTCL4TSL3() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_TCL4 = [];
		var count_TSL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl4_last_id = $('#TCL4_id').val();
		var tsl3_last_id = $('#TSL3_id').val();
		var qr_code = [];
		var ean_code_array = [];

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();
			ean_code_array.push($(item).val());

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single orms tpn and qr code ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+ean_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce single Units Per Case ] */
		$('.ce_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of ce single tcl1 size ] */
		$('.ce_single_size').each(function (i, item) {
			size[i] = $(item).val();

		});
		size = size.join(',');

		/** [ Create array of ce single tcl4 count ] */
		$('.count_TCL4').each(function (i, item) {
			count_TCL4[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL4 = count_TCL4.join(',');

		/** [ Create array of ce single tsl3 count ] */
		$('.count_TSL3').each(function (i, item) {
			count_TSL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL3 = count_TSL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-ce-single-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_TCL4: count_TCL4,
				count_TSL3: count_TSL3,
				templates: data,
				is_insert: is_insert,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [saveCESingleTHL3 for storing tesco CE single THL3 Template manual orders]
 * @author <praveen>
 */
function saveCESingleTHL3() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_THL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl3_last_id = $('#THL3_id').val();
		var qr_code = [];
		var ean_code_array = [];

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();
			ean_code_array.push($(item).val());

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single orms tpn and qr code ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+ean_code_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		/** [ Create array of ce single Units Per Case ] */
		$('.ce_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of ce single tcl1 size ] */
		$('.ce_single_size').each(function (i, item) {
			size[i] = $(item).val();

		});
		size = size.join(',');

		/** [ Create array of ce single thl3 count ] */
		$('.count_THL3').each(function (i, item) {
			count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL3 = count_THL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-ce-single-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_THL3: count_THL3,
				templates: data,
				is_insert: is_insert,
				thl3_last_id: thl3_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * [saveUKRatioTCL1TCL2TSL1 for storing tesco UK ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function saveUKRatioTCL1TCL2TSL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var itf_code = [];
		var units_per_carton = [];
		var count_TCL1 = [];
		var count_TCL2 = [];
		var count_TSL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();


		/** [ Create array of uk ratio orms tpn ] */
		$('.uk_ratio_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk ratio itf barcode ] */
		$('.uk_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

		/** [ Create array of uk ratio units per carton ] */
		$('.uk_ratio_upc').each(function (i, item) {
			units_per_carton[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		units_per_carton = units_per_carton.join(',');

		/** [ Create array of uk ratio tcl1 count ] */
		$('.count_TCL1').each(function (i, item) {
			count_TCL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL1 = count_TCL1.join(',');

		/** [ Create array of uk ratio tcl2 count ] */
		$('.count_TCL2').each(function (i, item) {
			count_TCL2[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL2 = count_TCL2.join(',');

		/** [ Create array of uk ratio tsl1 count ] */
		$('.count_TSL1').each(function (i, item) {
			count_TSL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL1 = count_TSL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-uk-ratio-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				itf_code: itf_code,
				units_per_carton: units_per_carton,
				count_TCL1: count_TCL1,
				count_TCL2: count_TCL2,
				count_TSL1: count_TSL1,
				templates: data,
				is_insert: is_insert,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * [saveUKRatioTHL1 for storing tesco UK ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function saveUKRatioTHL1() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var itf_code = [];
		var units_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();

		/** [ Create array of uk ratio orms tpn ] */
		$('.uk_ratio_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk ratio itf barcode ] */
		$('.uk_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

		/** [ Create array of uk ratio units per carton ] */
		$('.uk_ratio_upc').each(function (i, item) {
			units_per_carton[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		units_per_carton = units_per_carton.join(',');

		/** [ Create array of uk ratio thl1 count ] */
		$('.count_THL1').each(function (i, item) {
			count_THL1[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL1 = count_THL1.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-uk-ratio-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				itf_code: itf_code,
				units_per_carton: units_per_carton,
				count_THL1: count_THL1,
				templates: data,
				is_insert: is_insert,
				thl1_last_id: thl1_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * [saveUKSingleTCL4TSL3 for storing tesco UK single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function saveUKSingleTCL4TSL3() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_TCL4 = [];
		var count_TSL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var tcl4_last_id = $('#TCL4_id').val();
		var tsl3_last_id = $('#TSL3_id').val();

		/** [ Create array of uk single orms tpn ] */
		$('.uk_single_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk single ean barcode ] */
		$('.uk_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of uk single Units Per Case ] */
		$('.uk_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of uk single tcl1 size ] */
		$('.uk_single_size').each(function (i, item) {
			size[i] = $(item).val();

		});
		size = size.join(',');

		/** [ Create array of uk single tcl2 count ] */
		$('.count_TCL4').each(function (i, item) {
			count_TCL4[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TCL4 = count_TCL4.join(',');

		/** [ Create array of uk single tsl1 count ] */
		$('.count_TSL3').each(function (i, item) {
			count_TSL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_TSL3 = count_TSL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-uk-single-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_TCL4: count_TCL4,
				count_TSL3: count_TSL3,
				templates: data,
				is_insert: is_insert,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * [saveUKSingleTHL3 for storing tesco UK single THL3 Template manual orders]
 * @author <praveen>
 */
function saveUKSingleTHL3() {
	try {
		/** Calling tescoAdminOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoAdminOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var tpnd = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_THL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl3_last_id = $('#THL3_id').val();

		/** [ Create array of uk single orms tpn ] */
		$('.uk_single_tpnd').each(function (i, item) {
			tpnd[i] = $(item).val();

		});
		tpnd = tpnd.join(',');

		/** [ Create array of uk single ean barcode ] */
		$('.uk_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of uk single Units Per Case ] */
		$('.uk_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of uk single tcl1 size ] */
		$('.uk_single_size').each(function (i, item) {
			size[i] = $(item).val();

		});
		size = size.join(',');

		/** [ Create array of uk single tcl2 count ] */
		$('.count_THL3').each(function (i, item) {
			count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL3 = count_THL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/save-uk-single-template-orders",
			type: 'PUT',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				tpnd: tpnd,
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_THL3: count_THL3,
				templates: data,
				is_insert: is_insert,
				thl3_last_id: thl3_last_id,
				ocn_no: ocn_no,
				full_description:full_description
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSaved() */
				afterOrderSaved(data)
			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.log("Error:" + e)
	}
}

/**
 * After order drafted/Saved callback function
 * @author <praveen>
 */
function afterOrderSaved(data) {
	/** [ Show order already exists alertbox] */
	if (data.already_exists.length > 0) {
		var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                             <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                              <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">\
                                <span aria-hidden="true">×</span>\
                              </button>\
                             <p>Order already exists for following artworks,</p>\
                            <ol>';
		$.each(data.already_exists, function (index, value) {
			html_content += '<li>' + value + '</li>';
		});
		html_content += '</ol>\
                         </div>';

		$('#order-exists-msg').html(html_content);
		$('#warningModal').modal('show');
	} else {
		$('.snackbar_span').html('Saved Successfully');
		showSnackbar();

		$('.save-btn').data('insert', 'false');

		var div_html = '';
		/** [ store last updated id as hidden input , for updating] */
		$.each(data.last_insertions, function (index, value) {
			div_html += '<input class="table-id-hidden" id="' + index.replace(' ', '') + '_id" name="' + index.replace(' ', '') + '_id" type="hidden" value="' + value + '">';
		});
		$('.invisible-div').html(div_html);
	}
}

/**
 * [Validation for dynamic ean barcode fields]
 * @author <praveen>
 */
 function validateEanBarcode(obj) {
 	/** [ Dynamically change svg barcode ] */
 	if (obj.value.length === 13) {
 		var barcodecheck = checkjsbarcodeean(obj.value);
 		if (barcodecheck == "false") {
 			/**  [ Show validation error message ] **/
 			obj.value = '';
 			$(obj).parent('div').addClass('has-error has-danger');
 			$(obj).next('div').html('<ul class="list-unstyled"><li>Enter a valid barcode.</li></ul>');
 		} else {
 			$(obj).parent('div').removeClass('has-error has-danger');
 			$(obj).next('div').html('');
 		}
 	} else {
 		$(obj).parent('div').removeClass('has-error has-danger');
 		$(obj).next('div').html('');
 	}
 }
 
 /**
  * [Validation for dynamic itf barcode fields]
  * @author <praveen>
  */
 function validateItfBarcode(obj) {
 	/** [ Dynamically change svg barcode ] */
 	if (obj.value.length === 14) {
 		var barcodecheck = checkjsbarcodeitf(obj.value);
 		if (barcodecheck == "false") {
 			obj.value = '';
 			/**  [ Show validation error message ] **/
 			$(obj).parent('div').addClass('has-error has-danger');
 			$(obj).next('div').html('<ul class="list-unstyled"><li>Enter a valid barcode.</li></ul>');
 		} else {
 			$(obj).parent('div').removeClass('has-error has-danger');
 			$(obj).next('div').html('');
 		}
 	} else {
 		$(obj).parent('div').removeClass('has-error has-danger');
 		$(obj).next('div').html('');
 	}
 }
 
/**
 * [cancel tesco orders]
 * @author <praveen>
 */
function cancelTescoOrder() {
	try {
		var array_list   = [];
		$('.table-id-hidden').each(function (i, table_id) {
			my_object    = new Object();
			var table_names = table_id.id.split("_");
			my_object.table = table_names[0];
			my_object.table_id = table_id.value;
			array_list.push(my_object);
		});
		

		$.ajax({
			url: base_url + "/admin/tesco/cancel-tesco-order",
			type: 'PUT',
			data: {
				_token: csrf_token,
				data_array:array_list
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('.snackbar_span').html('Order Cancelled Successfully');
				showSnackbar();

				$('#cancelOrderModal').modal('hide');
				$('.cancel-btn').remove();

			},
			error: function (errorXHR, errorStatus, errorThrown) {
				this.tryCount++;
				if (this.tryCount <= this.retryLimit) {
					$.ajax(this);
					return;
				}
			},
			complete: function () {
				$(".loader").hide();
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
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}


/**
 * [MOQ validation function]
 * @author <praveen>
 */
function checkMOQ(obj) {
	try {
		var temp_name = $(obj).data('artwork');
		var moq_array = JSON.parse(localStorage.getItem("moq_values"));

		if(moq_array != null)
		{
			moq_array.forEach(function(i,j){
				moq = i[temp_name];
			})
			$(obj).attr("min",moq); 
			$(obj).data("min-error","Qty should be >= "+moq); 
		}
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [Tesco supplier dynamic preview function]
 * @author <praveen>
 */
function dynamicTescoSuppliers(id)
{
	/**  [ Dynamically change all svg templates ] */
	var mySVG = document.getElementById(id);
	var svgDoc = mySVG.contentDocument;
	var supplier = $('#tesco_supplier option:selected').text();
	var templates = localStorage.getItem("templates");
	var buyer_id  = localStorage.getItem("buyer_id");
	var supplier_id = $('#tesco_supplier option:selected').val();
	/** [ Get template details from localstorage ] */
	var temp_data = JSON.parse(localStorage.getItem("data"));
	/** [ Dynamically change  svg supplier name ] */
		if (supplier_id != '') {
			/*
			 * Get MOQ value based on supplier value.
			 */
			if(isSubmitting) 
	        {
	          return;
	        }
	        isSubmitting = true;
		    $.ajax({
			       url:base_url + "/admin/tesco/get-moq-value",
			       dataType:"json",
			       method:"get",
			       data:{supplier:supplier_id,buyer_id:buyer_id,templates:templates},
		           tryCount : 0,
		           retryLimit : 2,
		           beforeSend: function () {
		             $(".loader").show();
		           },
		           success: function(data){
					     if(svgDoc.getElementById("supplier"))
		                 {
		                   svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace(/&/g, '&amp;');
		                 }
		           	     /** Store moq values for validation */
		                 localStorage.setItem("moq_values", JSON.stringify(data.moq));
	                 	 
		                 /** hide quantity missing error msgs */
						 $('.quantity-feedback').removeClass('has-error has-danger');
						 $('.quantity-feedback-err-msg').css('display', 'none');
						 $('.quantity-field').prop('required', false);
						 $('.quantity-field').parent().removeClass('has-error')
						 $('.help-block').html('');
		                 /** [ Dynamic moq value assignment on supplier change ] */
						 var moq_array = JSON.parse(localStorage.getItem("moq_values"));
						 $.each(temp_data, function (i, item) {
							var temp_name = item.template_name;
							if(moq_array != null)
							{
								moq_array.forEach(function(i,j){
									moq = i[temp_name];
								})
								$('.count_' + item.template_name.replace(' ', '') ).attr("min",moq); 
								$('.count_' + item.template_name.replace(' ', '') ).data("min-error","Qty should be >= "+moq); 
							}
						});
						 
		           },
		           error: function(errorXHR, errorStatus, errorThrown) {
		                this.tryCount++;
		                if (this.tryCount <= this.retryLimit) {
		                    $.ajax(this);
		                    return;
		                }
		            },
		           complete: function () {
	                   isSubmitting = false;
		               $(".loader").hide();
		           }
		       })
		} else {
				localStorage.removeItem("moq_values");
				if(svgDoc.getElementById("supplier"))
				{
					svgDoc.getElementById("supplier").innerHTML = "";
				}				
				/** hide quantity missing error msgs */
				$('.quantity-feedback').removeClass('has-error has-danger');
				$('.quantity-feedback-err-msg').css('display', 'none');
				$('.quantity-field').prop('required', false);
				$('.quantity-field').parent().removeClass('has-error')
				$('.help-block').html('');
				/** [ Remove MOQ values] */
				$.each(temp_data, function (i, item) {
					$('.count_' + item.template_name.replace(' ', '') ).removeAttr("min"); 
					$('.count_' + item.template_name.replace(' ', '') ).data("min-error",""); 
				});
		}
}

function reload_back()
{
	$('li.year-nav-menu').removeClass('hidden');
	$('li.buyer-nav-menu').removeClass('hidden');
	var back_flag = localStorage.getItem("back_flag");
	if(back_flag == "view")
	{
		tescoOrders(selectStyleNumber)
	}
	else
	{
		tescoOrders(selectNewOrder)
	}
}
function selectStyleNumber()
{
	var style_number = localStorage.getItem('style_number_back')
	var artwork = localStorage.getItem('artwork_back')
	var po_num = localStorage.getItem('po_num_back')
	var po_num = localStorage.getItem('po_num_back')
	
	$('#po_number').val(style_number).trigger('change');
	$('#artwork_list').val(po_num);
	$('#po_number').selectpicker('refresh');
	$('#artwork_list').selectpicker('refresh');
	reOrderTesco()
}
function selectNewOrder()
{
	var order_type_back = localStorage.getItem('order_type_back')
	var back_template_id = localStorage.getItem('back_template_id')
	var back_zone = localStorage.getItem('back_zone')
	var $radios = $('input:radio[name=radio]');
    if($radios.is(':checked') === false) {
        $radios.filter('[value='+order_type_back+']').prop('checked', true);
    }
	$('#newOrdertab').addClass('active')
	$('#viewOrdertab').removeClass('active')
	$('#newOrder').addClass('active in')
	$('#viewOrder').removeClass('active')
	
	$("#jstree").on("loaded.jstree", function(){
		$("#jstree li[id='" + back_template_id + "'] a").click();
	});
	
	if (back_zone === 'UK' && order_type_back === "1") {
		$(".po-upload").removeClass("hidden");
		$('#po_upload').prop('required', true);
		$(".ce-po-upload").addClass("hidden");
		$('#ce_orms').prop('required', false);
		$('#ce_retek').prop('required', false);
	} else if (back_zone === 'CE' && order_type_back === "1") {

		$(".po-upload").addClass("hidden");
		$('#po_upload').prop('required', false);
		$(".ce-po-upload").removeClass("hidden");
		$('#ce_orms').prop('required', true);
		$('#ce_retek').prop('required', true);
	} else {
		$(".po-upload").addClass("hidden");
		$('#po_upload').prop('required', false);
		$(".ce-po-upload").addClass("hidden");
		$('#ce_orms').prop('required', false);
		$('#ce_retek').prop('required', false);
	}
	reOrderTesco()
}

function wordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'  x='0' y='0'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }
        if (str.length < maxWidth)
            done = true;
    } while (!done);
    return res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;' x='0' y='12'>"+str+"</tspan>";
}
function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}


function create_download_artwork(print_type)
{
	var a = document.getElementById('tesco_svg_modal');
    var svgDoc = a.contentDocument;

	var a_back = document.getElementById('tesco_svg_back_modal');
    var svgDoc_back = a_back.contentDocument;

    var a_back_text = document.getElementById('tesco_svg_back_text_modal');
    var svgDoc_back_text = a_back_text.contentDocument;

    var a_front_text = document.getElementById('tesco_svg_front_modal');
    var svgDoc_front_text = a_front_text.contentDocument;
    
            var svgarr = [];
            var svgarr_back = [];
            var svgarr_back_text = [];
            var svgarr_front = [];
            ajax_count=0;
            $.ajax({
				url: base_url + "/admin/tesco/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: $('#hidden_name').val(),
                    id: $('#hidden_id').val(),
                    table_name_print:"tesco_washcare_orders"
                },
				beforeSend: function() {

				},
				success: function(data) {
                    
                    var svgRoot_back =svgDoc_back.documentElement;
                    var svgRoot_back_text =svgDoc_back_text.documentElement;
                    var svgRoot_front_text =svgDoc_front_text.documentElement;

                    if(data.order_details[0].bs_en_declaration == 0)
                    {
                        console.log("hai")
                        svgDoc_front_text.getElementById("sleeping_bag_name").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].baby_sleeping_bag_symbol == 0)
                    {
                        svgDoc_front_text.getElementById("tog_image").setAttribute('style', "visibility:hidden");
                    }

                    // if(data.order_details[0].tog == "" || data.order_details[0].tog == null)
                    // {
                    //     svgDoc_front_text.getElementById("tog_name").setAttribute('style', "visibility:hidden");
                    // }

                    $(data.order_details).each(function (key, val) {
                        svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                        svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                        svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;
                        if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                        {
                            svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                        }
                        else
                        {
                            //alert()
                            svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 103)");
                            svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 110)");
                            svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 117)");
                            svgDoc.getElementById("batch_code").innerHTML = "";
                        }

                        if(val.internal_reference != "" && val.internal_reference != null)
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;
                        }
                        else
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = "";
                            svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 4.8456, 160.7462)");
                            
                        }
                        
                        if(val.tog != "" && val.tog != null)
                        {
                            svgDoc.getElementById("tog_value_1_").innerHTML = "TOG "+val.tog
                        }
                        else
                        {
                            //console.log("hai")
                            svgDoc.getElementById("tog_value_1_").innerHTML = "";
                        }


                        var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                        var selected_secondary_sizes_text = "";
                        console.log(selected_secondary_sizes)
                        $size_y=0;
                        $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                            if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                            {
                                selected_secondary_sizes_text += '<tspan text-anchor="middle" x="10" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                                $size_y += 6;
                            }
                        })
                        svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                        


                        svgDoc.getElementById("uk_ean_barcode").innerHTML =  "UK EAN: " + val.uk_ean;
						svgDoc.getElementById("ce_ean_barcode").innerHTML =  "CE EAN: " + val.ce_ean;
						
						if(val.potps_id == null)	
                        {	
                            svgDoc.getElementById("part_of_two_piece_set").setAttribute("visibility", "hidden");	
                            svgDoc.getElementById("uk_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 302.3271)");	
                            svgDoc.getElementById("ce_ean_barcode").setAttribute('transform', "matrix(0.6664 0 0 1 36.1605 312.2111)");	
                            svgDoc.getElementById("top_line").setAttribute('y1', "294.16");	
                            svgDoc.getElementById("top_line").setAttribute('y2', "294.16");	
                            svgDoc.getElementById("bottom_line").setAttribute('y1', "315.63");	
                            svgDoc.getElementById("bottom_line").setAttribute('y2', "315.63");	
                        }	
                        else	
                        {	
                            svgDoc.getElementById("part_of_two_piece_set").removeAttribute("visibility", "hidden");	
                        }
                        
                        if(val.uk_size == "tiny baby")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");



                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 22.5, 257.061)");
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 36.4, 257.0148)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 10.65, 264.3518)");

                            //svgDoc.getElementById("small_english").innerHTML = "tiny baby";

                            var polish = "tiny baby"
                            var czech = data.tesco_tiny[0].czech
                            var slovak = data.tesco_tiny[0].slovak
                            var hungarian = data.tesco_tiny[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "Newborn")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "Newborn";

                            var polish = "newborn"
                            var czech = data.tesco_nb[0].czech
                            var slovak = data.tesco_nb[0].slovak
                            var hungarian = data.tesco_nb[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation1").style.letterSpacing = "-0.2px";
                            //svgDoc.getElementById("small_translation_1").style.letterSpacing = "-0.2px";
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 30.7 257.061)");
                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 17.1806, 257.061)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 9.9, 264.3518)");
                            
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 1M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 21.5, 257.061)");
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 35, 257.0148)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 4.25, 264.3518)");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 1M";
                            
                            var polish = "up to 1m"
                            var czech = data.tesco_up1[0].czech
                            var slovak = data.tesco_up1[0].slovak
                            var hungarian = data.tesco_up1[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.05px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 3M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");


                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 21.6, 257.061)");
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 35.1, 257.0148)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 3.1, 264.3518)");

                            //svgDoc.getElementById("small_english").innerHTML = "up to 3M";
                            
                            var polish = "up to 3m"
                            var czech = data.tesco_up3[0].czech
                            var slovak = data.tesco_up3[0].slovak
                            var hungarian = data.tesco_up3[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation2").style.letterSpacing = "-0.05px";
                            svgDoc.getElementById("small_translation_1").innerHTML = " • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =" • "+slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "One size")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation_1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            //svgDoc.getElementById("small_english").innerHTML = "One size";
                            
                            var polish = "one size"
                            var czech = data.tesco_onesize[0].czech
                            var slovak = data.tesco_onesize[0].slovak
                            var hungarian = data.tesco_onesize[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish;
                            //svgDoc.getElementById("small_translation1").style.letterSpacing = "-0.2px";
                            //svgDoc.getElementById("small_translation_1").style.letterSpacing = "-0.2px";
                            svgDoc.getElementById("small_translation1").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 18.8, 257.061)");
                            svgDoc.getElementById("small_translation_1").setAttribute('transform', "matrix(0.8605, 0, 0, 1, 31.3, 257.061)");
                            svgDoc.getElementById("small_translation2").setAttribute('transform', "matrix(0.8605, 0, 0, 1, 7.3, 264.061)");
                            
                            svgDoc.getElementById("small_translation_1").innerHTML = "  • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML ="• "+slovak+ " • "+hungarian;
                        }
                        else
                        {
                            if(val.uk_size == "3-6M")
                            {
                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.4656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.9983, 259.591)");
                            }
                            else if(val.uk_size == "6-9M")
                            {

                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.4656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.9983, 259.591)");
                            }
                            else if(val.uk_size == "9-12M")
                            {

                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.4656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.9983, 259.591)");
                            }
                            else if(val.uk_size == "12-18M")
                            {

                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.2656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.7983, 259.591)");
                            }
                            else if(val.uk_size == "18-24M")
                            {

                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.2656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.7983, 259.591)");
                            }
                            else if(val.uk_size == "1-2 years" || val.uk_size == "2-3 years" || val.uk_size == "3-4 years" || val.uk_size == "4-5 years" || val.uk_size == "5-6 years" || val.uk_size == "6-7 years" || val.uk_size == "7-8 years" || val.uk_size == "8-9 years" || val.uk_size == "9-10 years")
                            {
                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(0.9105, 0, 0, 1, 19.8656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(0.9105, 0, 0, 1, 52.3656, 259.591)");
                            }
                            else if(val.uk_size == "10-11 years" || val.uk_size == "11-12 years" || val.uk_size == "12-13 years" || val.uk_size == "13-14 years" || val.uk_size == "14-15 years" || val.uk_size == "15-16 years")
                            {
                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(0.8255, 0, 0, 1, 19.8656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(0.8255, 0, 0, 1, 52.3656, 259.591)");
                            }
                            else
                            {
                                svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                                svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                                svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                                svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                                svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
    
                                svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                                svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                                svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 19.4656, 259.591)");
    
                                svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 51.9983, 259.591)");
                            }
                            // svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");
    
                            //     svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("small_translation_1").setAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                            //     svgDoc.getElementById("uk_size").innerHTML = "9-10 years";
                            //     svgDoc.getElementById("ce_size").innerHTML = "9-10 years";
    
                            //     svgDoc.getElementById("uk").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 15.12, 252.641)");
    
                            //     svgDoc.getElementById("eur").setAttribute('transform', "matrix(1.0148, 0, 0, 1, 45.42, 252.6402)");
    
                            //     svgDoc.getElementById("uk_size").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 19.8656, 259.591)");
    
                            //     svgDoc.getElementById("ce_size").setAttribute('transform', "matrix(0.8105, 0, 0, 1, 52.3656, 259.591)");
                        }
                        svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                        var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                        svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;
                        var svgRoot = svgDoc.documentElement;
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax_count++;
                    })

                    var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                    svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan   x="2" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2" y="12.88">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="1.8" y="18.82">'+country_of_origin_text[0].hungarian+'</tspan>';

                    // svgDoc_back.getElementById("wash_iso").setAttribute('xlink:href',data.order_details[0].wash_iso);
                    // svgDoc_back.getElementById("clean_iso").setAttribute('xlink:href',data.order_details[0].clean_iso);
                    // svgDoc_back.getElementById("whit_iso").setAttribute('xlink:href',data.order_details[0].whit_iso);
                    // svgDoc_back.getElementById("iron_iso").setAttribute('xlink:href',data.order_details[0].iron_iso);
                    // svgDoc_back.getElementById("dry_iso").setAttribute('xlink:href',data.order_details[0].dry_iso);
                    // svgDoc_back.getElementById("wash_astm").setAttribute('xlink:href',data.order_details[0].wash_astm);
                    // svgDoc_back.getElementById("dry_astm").setAttribute('xlink:href',data.order_details[0].dry_astm);
                    // svgDoc_back.getElementById("whit_astm").setAttribute('xlink:href',data.order_details[0].whit_astm);
                    // svgDoc_back.getElementById("iron_astm").setAttribute('xlink:href',data.order_details[0].iron_astm);
                    // svgDoc_back.getElementById("clean_astm").setAttribute('xlink:href',data.order_details[0].clean_astm);

                    var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
                    var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
                    var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
                    var degree =data.order_details[0].degree

                    var en_care_instructions = "";
                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        en_care_instructions += additional_care_text_1[0].english
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        en_care_instructions += "/" + additional_care_text_2[0].english
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        en_care_instructions += "/" + additional_care_text_3[0].english
                    }
                    
                    if(degree >= 30)
                    {
                        en_care_instructions += "/" + data.degree_text[0].english
                    }
                    
                    $chunk_str = chunkSubstr(en_care_instructions, 28).split(/\r?\n/);

                    //console.log($chunk_str);
                    var chunks='<tspan  x="0" y="0">EN</tspan>';
                    $y=0;
                    $ind=1;
                    console.log("gy:"+$chunk_str.length)
                    $($chunk_str).each(function(index,chunk_val){
                        if(chunk_val.charAt(0) == "/")	
                        {	
                            var new_chunk_val = chunk_val.replaceAll("/", '</tspan><tspan x="12" y="'+$y+'" style="stroke-width:1">/</tspan><tspan>')	
                            chunks += '<tspan>'+new_chunk_val+'</tspan>';	
                        }	
                        else	
                        {	
                            var new_chunk_val =chunk_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                            chunks += '<tspan x="12" y="'+$y+'">'+new_chunk_val+'</tspan>';	
                        }	
                        //chunks += '<tspan x="12" y="'+$y+'">'+chunk_val+'</tspan>';
                        if($chunk_str.length == $ind)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind++;
                        
                    })

                    var en_garment_component = "";
                    var pl_garment_component = "";
                    var cz_garment_component = "";
                    var sk_garment_component = "";
                    var hu_garment_component = "";
                    $(data.component).each(function(index,comp_val){
                        
                        var garment_component_text = JSON.parse(comp_val.garment_component_text)
                        if(index != 0)
                        {
                            en_garment_component += "/" +garment_component_text[0].english;
                            pl_garment_component += "/" +garment_component_text[0].polish;
                            cz_garment_component += "/" +garment_component_text[0].czech;
                            sk_garment_component += "/" +garment_component_text[0].slovak;
                            hu_garment_component += "/" +garment_component_text[0].hungarian;
                        }
                        else
                        {
                            en_garment_component += garment_component_text[0].english;
                            pl_garment_component += garment_component_text[0].polish;
                            cz_garment_component += garment_component_text[0].czech;
                            sk_garment_component += garment_component_text[0].slovak;
                            hu_garment_component += garment_component_text[0].hungarian;
                        }
                        
                        $(comp_val.fibre).each(function(index,fibre_val){
                            var fibre_text = JSON.parse(fibre_val.fibre_composition_text)
                            en_garment_component += "/" + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].english;

                            pl_garment_component += "/" + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].polish;

                            cz_garment_component += "/" + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].czech;

                            sk_garment_component += "/" + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].slovak;

                            hu_garment_component += "/" + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].hungarian;
                        });
                    })

                    var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
                    var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
                    var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
                    var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
                    

                    if(exclusive_trims_text != "" && exclusive_trims_text != null)
                    {
                        en_garment_component += "/" + exclusive_trims_text[0].english
                        pl_garment_component += "/" + exclusive_trims_text[0].polish
                        cz_garment_component += "/" + exclusive_trims_text[0].czech
                        sk_garment_component += "/" + exclusive_trims_text[0].slovak
                        hu_garment_component += "/" + exclusive_trims_text[0].hungarian
                    }

                    if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
                    {
                        en_garment_component += "/" + contains_non_textile_part_text[0].english
                        pl_garment_component += "/" + contains_non_textile_part_text[0].polish
                        cz_garment_component += "/" + contains_non_textile_part_text[0].czech
                        sk_garment_component += "/" + contains_non_textile_part_text[0].slovak
                        hu_garment_component += "/" + contains_non_textile_part_text[0].hungarian
                    }

                    if(kids_warning_text != "" && kids_warning_text != null)
                    {
                        en_garment_component += "/" + kids_warning_text[0].english
                        pl_garment_component += "/" + kids_warning_text[0].polish
                        cz_garment_component += "/" + kids_warning_text[0].czech
                        sk_garment_component += "/" + kids_warning_text[0].slovak
                        hu_garment_component += "/" + kids_warning_text[0].hungarian
                    }

                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        console.log("Polish : "+additional_care_text_1[0].polish)
                        pl_garment_component += "/" + additional_care_text_1[0].polish
                        cz_garment_component += "/" + additional_care_text_1[0].czech
                        sk_garment_component += "/" + additional_care_text_1[0].slovak
                        hu_garment_component += "/" + additional_care_text_1[0].hungarian
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        pl_garment_component += "/" + additional_care_text_2[0].polish
                        cz_garment_component += "/" + additional_care_text_2[0].czech
                        sk_garment_component += "/" + additional_care_text_2[0].slovak
                        hu_garment_component += "/" + additional_care_text_2[0].hungarian
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        pl_garment_component += "/" + additional_care_text_3[0].polish
                        cz_garment_component += "/" + additional_care_text_3[0].czech
                        sk_garment_component += "/" + additional_care_text_3[0].slovak
                        hu_garment_component += "/" + additional_care_text_3[0].hungarian
                    }

                    if(degree >= 30)
                    {
                        pl_garment_component += "/" + data.degree_text[0].polish
                        cz_garment_component += "/" + data.degree_text[0].czech
                        sk_garment_component += "/" + data.degree_text[0].slovak
                        hu_garment_component += "/" + data.degree_text[0].hungarian
					}
					
					if(part_of_set_text != "" && part_of_set_text != null)
                    {
                        en_garment_component += "/" + part_of_set_text[0].english
                        pl_garment_component += "/" + part_of_set_text[0].polish
                        cz_garment_component += "/" + part_of_set_text[0].czech
                        sk_garment_component += "/" + part_of_set_text[0].slovak
                        hu_garment_component += "/" + part_of_set_text[0].hungarian
                    }

                    $garment_str = chunkSubstr(en_garment_component, 28).split(/\r?\n/);
                    $pl_garment_str = chunkSubstr(pl_garment_component, 28).split(/\r?\n/);
                    $cz_garment_str = chunkSubstr(cz_garment_component, 28).split(/\r?\n/);
                    $sk_garment_str = chunkSubstr(sk_garment_component, 28).split(/\r?\n/);
                    $hu_garment_str = chunkSubstr(hu_garment_component, 28).split(/\r?\n/);

                    chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

                    $ind_en=1;
                    $z=0;
                    $($garment_str).each(function(index,garment_val){
                        if(garment_val.charAt(0) == "/")	
                        {	
                            var new_garment_val = garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$y+'" style="stroke-width:1">/</tspan><tspan>')	
                            chunks += '<tspan>'+new_garment_val+'</tspan>';	
                        }	
                        else	
                        {	
                            var new_garment_val =garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                            chunks += '<tspan x="12" y="'+$y+'">'+new_garment_val+'</tspan>';	
                        }	
                        //chunks += '<tspan x="12" y="'+$y+'">'+garment_val+'</tspan>';
                        if($garment_str.length == $ind_en)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind_en++;
                    })

                    // chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                     var new_chunks = '';
                    // $($pl_garment_str).each(function(index,pl_garment_val){
                    //     if($y == 96)
                    //     {
                    //         $z=0;
                    //     }

                    //     if($y > 96)
                    //     {
                    //         new_chunks += '<tspan x="12" y="'+$z+'">'+pl_garment_val+'</tspan>';
                    //     }
                    //     else
                    //     {
                    //         chunks += '<tspan x="12" y="'+$y+'">'+pl_garment_val+'</tspan>';   
                    //     }
                        
                    //     $y+=6;
                    //     $z+=6;
                    // })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
                    }

                    $($cz_garment_str).each(function(index,cz_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            if(cz_garment_val.charAt(0) == "/")	
                            {	
                                var new_cz_garment_val = cz_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$z+'" style="stroke-width:1">/</tspan><tspan>')	
                                new_chunks += '<tspan>'+new_cz_garment_val+'</tspan>';	
                            }	
                            else	
                            {	
                                var new_cz_garment_val =cz_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                new_chunks += '<tspan x="12" y="'+$z+'">'+new_cz_garment_val+'</tspan>';	
                            }	
                            //new_chunks += '<tspan x="12" y="'+$z+'">'+cz_garment_val+'</tspan>';
                        }
                        else
                        {
                            if(cz_garment_val.charAt(0) == "/")	
                            {	
                                var new_cz_garment_val = cz_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$y+'" style="stroke-width:1">/</tspan><tspan>')	
                                chunks += '<tspan>'+new_cz_garment_val+'</tspan>'; 	
                            }	
                            else	
                            {	
                                var new_cz_garment_val =cz_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                chunks += '<tspan x="12" y="'+$y+'">'+new_cz_garment_val+'</tspan>'; 	
                            }	
                            //chunks += '<tspan x="12" y="'+$y+'">'+cz_garment_val+'</tspan>';      
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                    }

                    $($sk_garment_str).each(function(index,sk_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            if(sk_garment_val.charAt(0) == "/")	
                            {	
                                var new_sk_garment_val = sk_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$z+'" style="stroke-width:1">/</tspan><tspan>')	
                                new_chunks += '<tspan>'+new_sk_garment_val+'</tspan>';	
                            }	
                            else	
                            {	
                                var new_sk_garment_val =sk_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                new_chunks += '<tspan x="12" y="'+$z+'">'+new_sk_garment_val+'</tspan>';	
                            }	
                            //new_chunks += '<tspan x="12" y="'+$z+'">'+sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            if(sk_garment_val.charAt(0) == "/")	
                            {	
                                var new_sk_garment_val = sk_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$y+'" style="stroke-width:1">/</tspan><tspan>')	
                                chunks += '<tspan>'+new_sk_garment_val+'</tspan>'; 	
                            }	
                            else	
                            {	
                                var new_sk_garment_val =sk_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                chunks += '<tspan x="12" y="'+$y+'">'+new_sk_garment_val+'</tspan>'; 	
                            }	
                            //chunks += '<tspan x="12" y="'+$y+'">'+sk_garment_val+'</tspan>';      
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                    }

                    $($hu_garment_str).each(function(index,hu_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
							if(hu_garment_val.charAt(0) == "/")	
                            {	
                                var new_hu_garment_val = hu_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$z+'" style="stroke-width:1">/</tspan><tspan>')	
                                new_chunks += '<tspan>'+new_hu_garment_val+'</tspan>';	
                            }	
                            else	
                            {	
                                var new_hu_garment_val =hu_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                new_chunks += '<tspan x="12" y="'+$z+'">'+new_hu_garment_val+'</tspan>';	
                            }	
                            //new_chunks += '<tspan x="12" y="'+$z+'">'+hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            if(hu_garment_val.charAt(0) == "/")	
                            {	
                                var new_hu_garment_val = hu_garment_val.replaceAll("/", '</tspan><tspan x="12" y="'+$y+'" style="stroke-width:1">/</tspan><tspan>')	
                                chunks += '<tspan>'+new_hu_garment_val+'</tspan>'; 	
                            }	
                            else	
                            {	
                                var new_hu_garment_val =hu_garment_val.replaceAll("/", '</tspan><tspan style="stroke-width:1"> / </tspan><tspan>')	
                                chunks += '<tspan x="12" y="'+$y+'">'+new_hu_garment_val+'</tspan>'; 	
                            }	
                            //chunks += '<tspan x="12" y="'+$y+'">'+hu_garment_val+'</tspan>';      
                        }
                        
                        $y+=6;
                        $z+=6;
                    })
                    
                    svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
                    svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


                    
                    // console.log(en_care_instructions)
                    // console.log(en_care_instructions)

                    var s_front = new XMLSerializer();
                    var d_front = svgRoot_front_text;
                    var str_front = s_front.serializeToString(d_front);
                    var svg_front = encodeURI(str_front)
                    svgarr_front.push(svg_front)
                    
                    var s_back = new XMLSerializer();
                    var d_back = svgRoot_back;
                    var str_back = s_back.serializeToString(d_back);
                    var svg_back = encodeURI(str_back)
                    svgarr_back.push(svg_back)

                    var s_back_text = new XMLSerializer();
                    var d_back_text = svgRoot_back_text;
                    var str_back_text = s_back_text.serializeToString(d_back_text);
                    var svg_back_text = encodeURI(str_back_text)
                    svgarr_back_text.push(svg_back_text)
                   
                    if (ajax_count == data.order_details.length) {
						setTimeout(function() {     $(obj).prop('disabled', false); }, 5000);
                        var svg_fin = svgarr.join(',_');
                        var svg_fin_back = svgarr_back.join(',_');
                        var svg_fin_back_text = svgarr_back_text.join(',_');
						var svg_fin_front = svgarr_front.join(',_');
						
						if(print_type == "print")
						{
							PopupTescoLabelsGeneratePrintManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
						}
						else
						{
							PopupTescoLabelsProofManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
						}
                        
                    }
				
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
}


function PopupTescoLabelsGeneratePrintManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
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
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

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

function PopupTescoLabelsProofManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/admin/tesco/proof-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
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
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

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

function chunkSubstr(str, maxWidth) {
    var newLineStr = "\n"; done = false; res = '';
    while (str.length > maxWidth) {                 
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + [str.slice(0, i), newLineStr].join('');
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res += [str.slice(0, maxWidth), newLineStr].join('');
            str = str.slice(maxWidth);
        }

    }

    return res + str;
}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}

