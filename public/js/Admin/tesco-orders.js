
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
function tescoOrders(callback) {
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

	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	try {
		/** [ ajax call to load tesco order page ] **/
		$.ajax({
			url: base_url + "/admin/tesco/order-management/orders",
			type: "Post",
			dataType: "json",
			data: {
				_token: csrf_token
			},
			tryCount: 0,
			retryLimit: 2,
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));
				
				var newObj1 = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));
				
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
				var artwork_collection = {};
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
					}

				}

				var options = "<option value=''>Search Style Number</option>";
				if (CEUniqueNames.length > 0) {
					options += "<optgroup label='CE'>";
					for (i = 0; i < CEUniqueNames.length; i++) {
						options += "<option>" + CEUniqueNames[i] + "</option>";
					}
					options += "</optgroup>";
				}
				if (UKUniqueNames.length > 0) {
					options += "<optgroup label='UK'>";
					for (i = 0; i < UKUniqueNames.length; i++) {
						options += "<option>" + UKUniqueNames[i] + "</option>";
					}
					options += "</optgroup>";
				}

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
					loadArtworkNames(artwork_collection[this.value]);
				});

				/** [ Load selectpicker ] */
				$('.selectpicker').selectpicker();

				/** [ Hide/Show PO upload div, PO number search based on order type selected ] */
				$('input[type="radio"].order_type').click(function () {
					globalOrderType = ($(this).attr("value") === '1') ? 1 : 0;
					var currentNode = $('#jstree').jstree(true).get_selected('full', true);
					var zone = (currentNode.length > 0) ? currentNode[0].original.zone : false;

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
										"id": "5,6,7",
										"text": "TCL 1 , TCL 2 & TSL 1",
										"parent_id": "3",
										"type": "ratio",
										"zone": "CE"
                                        }, {
										"id": "8,9",
										"text": "TCL 4 & TSL 3",
										"parent_id": "3",
										"type": "single",
										"zone": "CE"
                                        }, {
										"id": "10",
										"text": "THL 1",
										"parent_id": "3",
										"type": "ratio",
										"zone": "CE"
                                        }, {
										"id": "11",
										"text": "THL 3",
										"parent_id": "3",
										"type": "single",
										"zone": "CE"
                                        }]
                                    }, {
									"id": "4",
									"text": "UK",
									"parent_id": "2",
									"children": [{
										"id": "12,13,14",
										"text": "TCL 1 , TCL 2 & TSL 1",
										"parent_id": "4",
										"type": "ratio",
										"zone": "UK"
                                        }, {
										"id": "15,16",
										"text": "TCL 4 & TSL 3",
										"parent_id": "4",
										"type": "single",
										"zone": "UK"
                                        }, {
										"id": "17",
										"text": "THL 1",
										"parent_id": "4",
										"type": "ratio",
										"zone": "UK"
                                        }, {
										"id": "18",
										"text": "THL 3",
										"parent_id": "4",
										"type": "single",
										"zone": "UK"
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
											$('.templateDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">' + value[0].template_name + '</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">' + value[0].ref_no + '</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">' + value[0].company_name + '</td></tr></tbody></table></div></div>')

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
								var ce_orms_purchase_order = $('#ce_orms')[0].files;
								var ce_retek_purchase_order = $('#ce_retek')[0].files;
								formData.append('purchase_order', purchase_order);
								formData.append('ce_orms_purchase_order', ce_orms_purchase_order);
								formData.append('ce_retek_purchase_order', ce_retek_purchase_order);
								formData.append('type', localStorage.getItem("template type"));
								formData.append('zone', localStorage.getItem("zone"));
								formData.append('template_name', localStorage.getItem("template_group"));
								uploadPurchaseOrder(formData, localStorage.getItem("zone"));
							} else {
								createOrders();
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
						localStorage.setItem('style_number_back',style_number)
						localStorage.setItem('artwork_back',artwork)
						localStorage.setItem('po_num_back',po_num)
						localStorage.setItem("back_flag","view");
						viewOrder(po_num, artwork);
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
				var html = '<div class="loader">\
                                    <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>\
                                </div>\
                                <br>\
                                <form method="POST" id="create_tesco_order_form" data-toggle="validator" role="form" novalidate="true">';
				         /** Buyer PO number length , for CE 7 digits and for UK 9 digits*/
						if (zone === "CE") {
							  html += '<div class="col-md-3">\
																<div class="form-group has-feedback">\
																	<label for="tesco_po_number">PO Number</label>\
																	<input type="text" class="form-control" data-minlength="7" data-required-error="Mandatory Field." data-minlength-error="Enter 7 digits." name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()" maxlength="7">\
																	<div class="help-block with-errors"></div>\
																</div>\
															</div>';
							}
							else
							{
							  	html += '<div class="col-md-3">\
																<div class="form-group has-feedback">\
																	<label for="tesco_po_number">PO Number</label>\
																	<input type="text" class="form-control" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()" maxlength="9">\
																	<div class="help-block with-errors"></div>\
																</div>\
															</div>';
								}
                                  
                                     html += '<div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_uk_style">UK Style Ref.</label>\
                                            <input type="text" class="form-control" name="tesco_uk_style" id="tesco_uk_style" placeholder="Enter UK Style Ref." required="" onkeyup="dynamicSVGTemplates()" maxlength="10">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_ce_style">CE Style Ref.</label>\
                                            <input type="text" class="form-control" name="tesco_ce_style" id="tesco_ce_style" placeholder="Enter CE Style Ref." required="" onkeyup="dynamicSVGTemplates()" maxlength="10">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_season_code">Season Code</label>\
                                            <input type="text" class="form-control" data-minlength="4" data-required-error="Mandatory Field." data-minlength-error="Enter 4 digits." name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_description">Short Description</label>\
                                            <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="45" onkeyup="dynamicSVGTemplates()">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_eqos">EQOS Site Code</label>\
                                            <input type="text" class="form-control" name="tesco_eqos" id="tesco_eqos" required="" placeholder="Enter EQOS Site Code" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" maxlength="10">\
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
                                                <button type="button" onclick="addUKRatio(this,event)" data-id="1" class="btn btn-default add-row-btn" title="Select a supplier" data-placement="right"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                            </p>\
                                        </div>\
                                    </div>\
                                    <div class="table-responsive qty-table">\
                                        <table class="table table-bordered text-center">\
                                            <thead>\
                                                <tr>\
                                                    <th rowspan="2">Line No.</th>\
                                                    <th rowspan="2">TPND</th>\
                                                    <th rowspan="2">ITF Barcode</th>\
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
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_1" name="uk_ratio_itf_1" onkeyup="dynamicSVGTemplates()" placeholder="Enter ITF Barcode">\
                                                        <div class="help-block with-errors"></div>\
                                                        <div class="barcode-error"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_1" name="uk_ratio_upc_1" placeholder="Enter Units Per Carton" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';

						$.each(data.templates, function (i, item) {
							html += '<td>\
                                            <div class="form-group has-feedback quantity-feedback">\
                                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '"  id="uk_ratio_count_' + item.template_name.replace(' ', '') + '_1" name="uk_ratio_count_' + item.template_name.replace(' ', '') + '_1" max = "9999", data-max-error= "Max 4 digits allowed" placeholder="Enter Quantity"  data-lastid="" data-artwork="'+ item.template_name+'" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)">\
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
                                                    <button type="button" onclick="addUKSingle(this,event)" data-id="1" class="btn btn-default add-row-btn" title="Select a supplier" data-placement="right"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="table-responsive qty-table">\
                                            <table class="table table-bordered text-center">\
                                                <thead>\
                                                    <tr>\
                                                        <th rowspan="2">Line No.</th>\
                                                        <th rowspan="2">TPND</th>\
                                                        <th rowspan="2">EAN Barcode</th>\
                                                        <th rowspan="2">Case Size</th>\
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
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." required="" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" id="uk_single_ean_1" name="uk_single_ean_1" placeholder="Enter EAN Barcode">\
                                                        <div class="help-block with-errors"></div>\
                                                        <div class="barcode-error"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeyup="dynamicSVGTemplates()" onkeypress="return isNumber(event)" id="uk_single_case_size_1" name="uk_single_case_size_1" placeholder="Enter Case Size" onkeyup="dynamicSVGTemplates()" maxlength="4">\
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
                                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_single_count_' + item.template_name.replace(' ', '') + '_1" name="uk_single_count_' + item.template_name.replace(' ', '') + '_1" max = "9999", data-max-error= "Max 4 digits allowed" placeholder="Enter Quantity" data-artwork="'+ item.template_name+'" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)">\
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
                                                    <button type="button" onclick="addCERatio(this,event)" data-id="1" class="btn btn-default add-row-btn" title="Select a supplier" data-placement="right"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                                </p>\
                                            </div>\
                                        </div>\
                                        <div class="table-responsive qty-table">\
                                            <table class="table table-bordered text-center">\
                                                <thead>\
                                                    <tr>\
                                                        <th rowspan="2">Line No.</th>\
                                                        <th rowspan="2">ORMS TPN</th>\
                                                        <th rowspan="2">ITF Barcode</th>\
                                                        <th rowspan="2">Cases Per Carton</th>';
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
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." id="ce_ratio_itf_1" onkeyup="dynamicSVGTemplates()" name="ce_ratio_itf_1" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">\
                                                        <div class="help-block with-errors"></div>\
                                                        <div class="barcode-error"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_1" name="ce_ratio_cpc_1" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton" onkeyup="dynamicSVGTemplates()" maxlength="5">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>';
						$.each(data.templates, function (i, item) {
							html += '<td>\
                                        <div class="form-group has-feedback quantity-feedback"> \
                                        <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_ratio_count_' + item.template_name.replace(' ', '') + '_1" name="ce_ratio_count_' + item.template_name.replace(' ', '') + '_1" max = "9999", data-max-error= "Max 4 digits allowed" placeholder="Enter Quantity" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
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
                                                <button type="button" onclick="addCESingle(this,event)" data-id="1" class="btn btn-default add-row-btn" title="Select a supplier" data-placement="right"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
                                            </p>\
                                        </div>\
                                    </div>\
                                    <div class="table-responsive qty-table">\
                                        <table class="table table-bordered text-center">\
                                            <thead>\
                                                <tr>\
                                                    <th rowspan="2">Line No.</th>\
                                                    <th rowspan="2">ORMS TPN</th>\
                                                    <th rowspan="2">EAN Barcode</th>\
                                                    <th rowspan="2">Case Size</th>\
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
                                                        <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_1" data-minlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits."  name="ce_single_orms_tpn_1" required="" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_1" name="ce_single_ean_1" onkeyup="dynamicSVGTemplates()" required="" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." placeholder="Enter EAN Barcode">\
                                                        <div class="help-block with-errors"></div>\
                                                        <div class="barcode-error"></div>\
                                                    </div>\
                                                </td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_1" name="ce_single_case_size_1" required="" placeholder="Enter Case Size" onkeyup="dynamicSVGTemplates()" maxlength="6">\
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
                                        <input type="number" data-tname="' + item.template_name + '" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_single_count_' + item.template_name.replace(' ', '') + '_1" name="ce_single_count_' + item.template_name.replace(' ', '') + '_1" placeholder="Enter Quantity" max = "9999", data-max-error= "Max 4 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)" data-artwork="'+ item.template_name+'">\
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
                                                   <button type="button" class="btn btn-primary print-btn" onclick=printDivTesco("tesco_svg_modal' + i + '","' + item.template_name.replace(' ', '') + '") style="display:none;" id="proof_' + item.template_name.replace(' ', '') + '_btn">Generate Proof</button><button type="button" class="btn btn-primary print-btn" onclick=GenerateDivTesco("tesco_svg_modal' + i + '","' + item.template_name.replace(' ', '') + '") style="display:none;" id="print_' + item.template_name.replace(' ', '') + '_btn">Generate Print</button>\
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
                                     <button class="btn btn-xs btn-success approve-btn" type="button" onclick="approveTescoOrder()" data-insert="true" style="display:none;"><i class="fa fa-check" aria-hidden="true" ></i> Approve</button>\
                                        <button class="btn btn-xs btn-warning submit-btn" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>\
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
					$('.approve-btn').show();
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

function printDivTesco(data, name) {
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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

function printDivTescoSet(data, name,set_po_number,set_style) {
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	var nameset = set_po_number+'-'+set_style+'-'+name + "-sticker";
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

			var description = $('#tesco_description').val().replace(/&/g,'&#038;')
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
			PopupTescoSet(svg_fin, nameset);
		}

	}


}
/**

Generate Print Tesco

*/
function GenerateDivTesco(data, name) {
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var name_count=0;
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
				//jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				var csrf_token = $('meta[name="csrf-token"]').attr('content');
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl3",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmall(svg_fin, name,svgDoc);
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
				
				var csrf_token = $('meta[name="csrf-token"]').attr('content');
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmall(svg_fin, name,svgDoc);
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
			
			name_count++;
		})

		

	} else {
		var svgarr = [];
		var ajax_count = 0;
		var name_count=0;
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
				//jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				var csrf_token = $('meta[name="csrf-token"]').attr('content');
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeeanbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerate(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
				
			} else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = $(this).find('.ce_ratio_itf').val()
				} else {
					var barcode_change = $(this).find('.uk_ratio_itf').val()
				}
				//jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
				var csrf_token = $('meta[name="csrf-token"]').attr('content');
	
				$.ajax({
						url: base_url + "/admin/tesco/generatebarcodeitfbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == document.getElementById("table_row").rows.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerate(svg_fin, name,svgDoc);
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


			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				ajax_count++;
			}
			name_count++;
		})
		
		
		
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				if (ajax_count == document.getElementById("table_row").rows.length) {
					var svg_fin = svgarr.join(',_');
					PopupTescoGenerate(svg_fin, name,svgDoc);
				}

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
function PopupTescoSet(data, name) {

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

function PopupTescoGenerate(data, name,svgDoc) {

	var url = "";
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tesco",
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
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoGenerateSmall(data, name,svgDoc) {

	var url = "";
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/generate-div-tesco-small",
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
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PdfboxGenerateSmall(data, name,svgDoc) {

	var url = "";
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
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
		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		var html = '<tr>\
                        <td class="slno">' + val + '</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_' + val + '" name="uk_single_tpnd_' + val + '" required="" placeholder="Enter TPND" data-minlength="9" maxlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits.">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_ean" required="" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." id="uk_single_ean_' + val + '" name="uk_single_ean_' + val + '" required="" placeholder="Enter EAN Barcode" onkeyup="return validateEanBarcode(this)">\
                                <div class="barcode-error-ean"></div>\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeypress="return isNumber(event)" id="uk_single_case_size_' + val + '" name="uk_single_case_size_' + val + '" required="" placeholder="Enter Case Size" maxlength="4">\
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
                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="uk_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "9999", data-max-error= "Max 4 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
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
		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		var html = '<tr>\
                        <td class="slno">' + val + '</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_' + val + '" name="uk_ratio_tpnd_' + val + '" placeholder="Enter TPND" maxlength="9" data-minlength="9" data-required-error="Mandatory Field." data-minlength-error="Enter 9 digits." >\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_itf" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_' + val + '" name="uk_ratio_itf_' + val + '" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." placeholder="Enter ITF Barcode" onkeyup="return validateItfBarcode(this)">\
                                 <div class="barcode-error-itf"></div>\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_' + val + '" name="uk_ratio_upc_' + val + '" placeholder="Enter Units Per Carton" maxlength="4">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                            <div class="form-group has-feedback quantity-feedback">\
                                <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="uk_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="uk_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "9999", data-max-error= "Max 4 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
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
		// $.ajaxSetup({
		// 	headers: {
		// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 	}
		// });
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
		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		var html = '<tr>\
                    <td class="slno">' + val + '</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_' + val + '" name="ce_single_orms_tpn_' + val + '" required="" placeholder="Enter ORMS TPN" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." >\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." id="ce_single_ean_' + val + '" name="ce_single_ean_' + val + '" required="" placeholder="Enter EAN Barcode" onkeyup="return validateEanBarcode(this)">\
                            <div class="barcode-error-ean"></div>\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_' + val + '" name="ce_single_case_size_' + val + '" required="" placeholder="Enter Case Size" maxlength="4">\
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
                            <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" data-tname="' + item.template_name + '" id="ce_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="ce_single_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "9999", data-max-error= "Max 4 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
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
		var a = []
		/** [ Get data-id of add button ] */
		var id = $(rem).attr("data-id");
		var val = parseInt(id) + 1;
		var html = '<tr>\
                    <td class="slno">' + val + '</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_' + val + '" name="ce_ratio_orms_tpn_' + val + '" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." >\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_itf" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." required="" id="ce_ratio_itf_' + val + '" name="ce_ratio_itf_' + val + '" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode" onkeyup="return validateItfBarcode(this)">\
                            <div class="barcode-error-itf"></div>\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_' + val + '" name="ce_ratio_cpc_' + val + '" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton" maxlength="4">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
		$.each(data, function (i, item) {
			html += '<td>\
                        <div class="form-group has-feedback quantity-feedback">\
                            <input type="number" class="form-control input-sm quantity-field count_' + item.template_name.replace(' ', '') + '" id="ce_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" name="ce_ratio_count_' + item.template_name.replace(' ', '') + '_' + val + '" placeholder="Enter Quantity" max = "9999", data-max-error= "Max 4 digits allowed" data-lastid="" onfocus="return checkMOQ(this)" onkeyup="return validateMOQ(this)"  data-artwork="'+ item.template_name+'">\
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
	if (item.template_name === "TSL 1" || item.template_name === "TSL 3") {

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
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
			} else {
				/**  [ if CE single template ] */
				var orms_tpn = $('#ce_single_orms_tpn_1').val();
				var case_size = $('#ce_single_case_size_1').val();
				var ce_single_ean = $('#ce_single_ean_1').val();
				var description = $('#tesco_description').val().replace(/&/g,'&#038;');
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
				/** [ Dynamically change svg Uk single case size ] */
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
				if (data[0].template_name === 'THL 3') {
					/** [ Create array of ce single thl3 count ] */
					$('.count_THL3').each(function (i, item) {
						count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var empty_count = 0;
					var l = count_THL3.length;
					for (var i = 0; i < l; i++) {
						if (count_THL3[i] === 0 || count_THL3[i] === '0' || count_THL3[i] === '00' || count_THL3[i] === '000' || count_THL3[i] === '0000') {
							empty_count += 1;
						}
					}

					/** [ Join artworks count array using ' , '] */
					count_THL3 = count_THL3.join(',');

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
								msg += '<li style="margin-bottom: 10px;"><strong>THL 3</strong></li>';
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeCESingleTHL3(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store CE single THL3 template */
								storeCESingleTHL3();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_ce_style').val();
								printDivTescoSet('tesco_svg0','THL3',set_po_number,set_style)
							}
						}
					}

				} else {
					/** [ Create array of ce single tcl4 count ] */
					$('.count_TCL4').each(function (i, item) {
						count_TCL4[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of ce single tsl3 count ] */
					$('.count_TSL3').each(function (i, item) {
						count_TSL3[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var templates_array = [];
					templates_array = count_TCL4.concat(count_TSL3);
					var empty_count = 0;
					var l = templates_array.length;
					for (var i = 0; i < l; i++) {
						if (templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000') {
							empty_count += 1;
						}
					}

					/** Check If artwork count array contains atleast one '0' OR empty value **/
					if (count_TCL4.includes('0') || count_TCL4.includes('00') || count_TCL4.includes('000') || count_TCL4.includes('0000') || count_TCL4.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 4</strong></li>';
					}
					if (count_TSL3.includes('0') || count_TSL3.includes('00') || count_TSL3.includes('000') || count_TSL3.includes('0000') || count_TSL3.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TSL 3</strong></li>';
					}

					/** [ Join artworks count array using ' , '] */
					count_TSL3 = count_TSL3.join(',');
					count_TCL4 = count_TCL4.join(',');

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
								$('#confirm_btn').attr('onclick', 'storeCESingleTCL4TSL3(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store CE single TCL4, TSL3 template */
								storeCESingleTCL4TSL3();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_ce_style').val();
								printDivTescoSet('tesco_svg0','TCL4',set_po_number,set_style)
								printDivTescoSet('tesco_svg1','TSL3',set_po_number,set_style)
							}
						}
					}

				}
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
				if (data[0].template_name === 'THL 3') {
					/** [ Create array of uk single thl3 count ] */
					$('.count_THL3').each(function (i, item) {
						count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create single array of atrworks, for validation ] */
					var empty_count = 0;
					var l = count_THL3.length;
					for (var i = 0; i < l; i++) {
						if (count_THL3[i] === 0 || count_THL3[i] === '0' || count_THL3[i] === '00' || count_THL3[i] === '000' || count_THL3[i] === '0000') {
							empty_count += 1;
						}
					}

					/** [ Join artworks count array using ' , '] */
					count_THL3 = count_THL3.join(',');

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
								msg += '<li style="margin-bottom: 10px;"><strong>THL 3</strong></li>';
								$('#body_msg').html(msg);
								/** [ Backdrop for bootstrap modal ] */
								$('#confirmOrder').modal({
									backdrop: 'static',
									keyboard: false
								})
								$('#confirm_btn').attr('onclick', 'storeUKSingleTHL3(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store UK single THL3 template */
								storeUKSingleTHL3();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_uk_style').val();
								printDivTescoSet('tesco_svg0','THL3',set_po_number,set_style)
							}
						}
					}

				} else {
					/** [ Create array of uk single tcl4 count ] */
					$('.count_TCL4').each(function (i, item) {
						count_TCL4[i] = $(item).val() === '' ? 0 : $(item).val();

					});


					/** [ Create array of uk single tsl3 count ] */
					$('.count_TSL3').each(function (i, item) {
						count_TSL3[i] = $(item).val() === '' ? 0 : $(item).val();

					});

					/** [ Create single array of atrworks, for validation ] */
					var templates_array = [];
					templates_array = count_TCL4.concat(count_TSL3);
					var empty_count = 0;
					var l = templates_array.length;
					for (var i = 0; i < l; i++) {
						if (templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000') {
							empty_count += 1;
						}
					}

					/** Check If artwork count array contains atleast one '0' OR empty value **/
					if (count_TCL4.includes('0') || count_TCL4.includes('00') || count_TCL4.includes('000') || count_TCL4.includes('0000') || count_TCL4.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TCL 4</strong></li>';
					}
					if (count_TSL3.includes('0') || count_TSL3.includes('00') || count_TSL3.includes('000') || count_TSL3.includes('0000') || count_TSL3.includes(0)) {
						msg += '<li style="margin-bottom: 10px;"><strong>TSL 3</strong></li>';
					}

					/** [ Join artworks count array using ' , '] */
					count_TCL4 = count_TCL4.join(',');
					count_TSL3 = count_TSL3.join(',');

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
								$('#confirm_btn').attr('onclick', 'storeUKSingleTCL4TSL3(this);');
								$('#confirmOrder').modal('show');
							} else {
								/** Function to store UK single TCL4, TSL3 template */
								storeUKSingleTCL4TSL3();
								var set_po_number 		 = $('#tesco_po_number').val();
								var set_style 		 = $('#tesco_uk_style').val();
								printDivTescoSet('tesco_svg0','TCL4',set_po_number,set_style)
								printDivTescoSet('tesco_svg1','TSL3',set_po_number,set_style)
							}
						}
					}

				}
			}
		}

	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * [ tescoOrderCommonData  function for getting tesco order common values ( Independent of zone and template type ) .Creating global variables ]
 * @return {[type]} [ Common data ]
 * @author <praveen>
 */
function tescoOrderCommonData() {
	po_number 		 = $('#tesco_po_number').val();
	uk_style 		 = $('#tesco_uk_style').val();
	ce_style 		 = $('#tesco_ce_style').val();
	season_code 	 = $('#tesco_season_code').val();
	description      = $('#tesco_description').val();
	eqos             = $('#tesco_eqos').val();
	manufacture_date = $('#tesco_manufacture_date').val();
	csrf_token 		 = $('meta[name="csrf-token"]').attr('content');
	department  	 = $("#tesco_department option:selected").val();
	brand 			 = $("#tesco_brand option:selected").val();
	supplier 		 = $("#tesco_supplier option:selected").val();
	rfid 			 = $("#tesco_rfid option:selected").val() === "yes" ? 1 : 0;
	tag_at_source    = $("#tesco_tag_at_source option:selected").val() === "yes" ? 1 : 0;
	zone			 = localStorage.getItem("zone");
	type             = localStorage.getItem("template type");
	data             = JSON.parse(localStorage.getItem("data"));
	ocn_no           = $('#tesco_ocn').val();
}

/**
 * [storeCERatioTCL1TCL2TSL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function storeCERatioTCL1TCL2TSL1() {
	try {
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

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
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

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
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {

				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
 * [storeCESingleTCL4TSL3 for storing tesco CE single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function storeCESingleTCL4TSL3() {
	try {
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of ce single orms tpn ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single case size ] */
		$('.ce_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of ce single tcl1 count ] */
		$('.ce_single_size').each(function (i, item) {
			size[i] = $(item).val() === '' ? 0 : $(item).val();

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
			url: base_url + "/admin/tesco/set-ce-single-template-orders",
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
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_TCL4: count_TCL4,
				count_TSL3: count_TSL3,
				templates: data,
				is_insert: is_insert,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
 * [storeCESingleTHL3 for storing tesco CE single THL3 Template manual orders]
 * @author <praveen>
 */
function storeCESingleTHL3() {
	try {
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_THL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl3_last_id = $('#THL3_id').val();

		/** [ Create array of ce single orms tpn ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single case size ] */
		$('.ce_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of ce single tcl1 count ] */
		$('.ce_single_size').each(function (i, item) {
			size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		size = size.join(',');

		/** [ Create array of ce single thl3 count ] */
		$('.count_THL3').each(function (i, item) {
			count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL3 = count_THL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-ce-single-template-orders",
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
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_THL3: count_THL3,
				templates: data,
				is_insert: is_insert,
				thl3_last_id: thl3_last_id,
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
 * [storeUKSingleTCL4TSL3 for storing tesco UK single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function storeUKSingleTCL4TSL3() {
	try {
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of uk single case size ] */
		$('.uk_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of uk single tcl1 count ] */
		$('.uk_single_size').each(function (i, item) {
			size[i] = $(item).val() === '' ? 0 : $(item).val();

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
			url: base_url + "/admin/tesco/set-uk-single-template-orders",
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
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_TCL4: count_TCL4,
				count_TSL3: count_TSL3,
				templates: data,
				is_insert: is_insert,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
 * [storeUKSingleTHL3 for storing tesco UK single THL3 Template manual orders]
 * @author <praveen>
 */
function storeUKSingleTHL3() {
	try {
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of uk single case size ] */
		$('.uk_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val();

		});
		case_size = case_size.join(',');

		/** [ Create array of uk single tcl1 count ] */
		$('.uk_single_size').each(function (i, item) {
			size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		size = size.join(',');

		/** [ Create array of uk single tcl2 count ] */
		$('.count_THL3').each(function (i, item) {
			count_THL3[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		count_THL3 = count_THL3.join(',');

		$.ajax({
			url: base_url + "/admin/tesco/set-uk-single-template-orders",
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
				ean_code: ean_code,
				case_size: case_size,
				size: size,
				count_THL3: count_THL3,
				templates: data,
				is_insert: is_insert,
				thl3_last_id: thl3_last_id,
				ocn_no: ocn_no
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				/** Call  afterOrderSubmitted() */
				var stl_no =  (zone=="CE")? ce_style : uk_style;
				afterOrderSubmitted(data,po_number,stl_no)
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
function afterOrderSubmitted(data,po_number,stl_no) {
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
	    /** Show approve button */
		$('.approve-btn').show();
		$('.cancel-btn').show();
		$('.print-btn').show();
		var div_html = '';
		/** [ store last updated id as hidden input , for approve/cancel order functionality] */
		$.each(data.last_insertions, function (index, value) {
			div_html += '<input id="' + index.replace(' ', '') + '_id" name="' + index.replace(' ', '') + '_id" type="hidden" value="' + value + '">';

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
	                     <div class="input-group date" data-provide="datepicker" id="datePicker3">\
	                         <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
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
	                     <div class="input-group date" data-provide="datepicker" id="datePicker3">\
	                         <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
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
			$('#datePicker3').datepicker({
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
				var delivery_date = $('#combined_delivery_date').val();
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
			statusCode: {
				200: function (data) {
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
		var zone = localStorage.getItem("zone");
		var type = localStorage.getItem("template type");
		var data = JSON.parse(localStorage.getItem("data"));
		var po_number = $('#tesco_po_number').val();
		/** [ Switch style number based on zone ] */
		if (zone === 'UK') {
			var style = $('#tesco_uk_style').val();
		} else {
			var style = $('#tesco_ce_style').val();
		}
		/** Check if PO Number null or not */
		if ((po_number != '') && (po_number != 'null') && (po_number != 'Null') && (po_number != 'NULL')) {
			/** Check if Style number null or not */
			if ((style != '') && (style != 'null') && (style != 'Null') && (style != 'NULL')) {
				if (zone === "CE") {
					if (type === "ratio") {
						if (data[0].template_name === 'THL 1') {
							/** Function to save CE ratio THL1 template */
							saveCERatioTHL1();
						} else {
							/** Function to save CE ratio TCL1, TCL2, TSL1 template */
							saveCERatioTCL1TCL2TSL1();
						}

					} else {
						if (data[0].template_name === 'THL 3') {
							/** Function to save CE single THL3 template */
							saveCESingleTHL3();
						} else {
							/** Function to save CE single TCL4, TSL3 template */
							saveCESingleTCL4TSL3();
						}
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
						if (data[0].template_name === 'THL 3') {
							/** Function to save UK single THL3 template */
							saveUKSingleTHL3();
						} else {
							/** Function to save UK single TCL4, TSL3 template */
							saveUKSingleTCL4TSL3();
						}
					}
				}
			} else {
				$('.snackbar_span').html('Please Enter ' + zone + ' Style Number !!');
				showSnackbar();
			}
		} else {
			$('.snackbar_span').html('Please Enter PO Number !!');
			showSnackbar();
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var itf_code = [];
		var cases_per_carton = [];
		var count_THL1 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl1_last_id = $('#THL1_id').val();

		/** [ Create array of ce ratio orms tpn ] */
		$('.ce_ratio_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce ratio itf barcode ] */
		$('.ce_ratio_itf').each(function (i, item) {
			itf_code[i] = $(item).val();

		});
		itf_code = itf_code.join(',');

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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of ce single orms tpn ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single case size ] */
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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
		/** @type {Array} [ initializing empty arrays ] */
		var orms_tpn = [];
		var ean_code = [];
		var case_size = [];
		var size = [];
		var count_THL3 = [];
		var is_insert = $('.save-btn').data('insert');
		var thl3_last_id = $('#THL3_id').val();

		/** [ Create array of ce single orms tpn ] */
		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

		});
		orms_tpn = orms_tpn.join(',');

		/** [ Create array of ce single ean barcode ] */
		$('.ce_single_ean').each(function (i, item) {
			ean_code[i] = $(item).val();

		});
		ean_code = ean_code.join(',');

		/** [ Create array of ce single case size ] */
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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of uk single case size ] */
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
				ocn_no: ocn_no
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
		/** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
		tescoOrderCommonData();
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

		/** [ Create array of uk single case size ] */
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
				ocn_no: ocn_no
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
			div_html += '<input id="' + index.replace(' ', '') + '_id" name="' + index.replace(' ', '') + '_id" type="hidden" value="' + value + '">';
		});
		$('.invisible-div').html(div_html);
	}
}
/**
 * [Approve tesco orders]
 * @author <praveen>
 */
function approveTescoOrder() {
	try {
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();
		var tcl4_last_id = $('#TCL4_id').val();
		var tsl3_last_id = $('#TSL3_id').val();
		var thl1_last_id = $('#THL1_id').val();
		var thl3_last_id = $('#THL3_id').val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: base_url + "/admin/tesco/approve-tesco-order",
			type: 'PUT',
			data: {
				_token: csrf_token,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				thl1_last_id: thl1_last_id,
				thl3_last_id: thl3_last_id
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('.snackbar_span').html('Order Approved Successfully');
				showSnackbar();
				$('.approve-btn').remove();
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
 * [Validation for dynamic ean barcode fields]
 * @author <praveen>
 */
function validateEanBarcode(obj)
{
	var barcode_checks = 0;
	/** [ Dynamically change svg barcode ] */
	if (obj.value.length === 13) {
		var barcodecheck = checkjsbarcodeean(obj.value);
		if (barcodecheck == "false") {
			barcode_checks++;
			$(obj).next('div').html('');
			/**  [ Show validation error message ] **/
			$(obj).next('div').html('<span style="color:#F44336">Enter a valid barcode.</span>');
			$('.submit-btn').prop('disabled', true);
		} else {
			barcode_checks = 0;
			$(obj).next('div').html('');
			$('.submit-btn').prop('disabled', false);
		}
	} else {
		barcode_checks = 0;
		$(obj).next('div').html('');
		$('.submit-btn').prop('disabled', false);
	}
}	

/**
 * [Validation for dynamic itf barcode fields]
 * @author <praveen>
 */
function validateItfBarcode(obj)
{
	var barcode_checks = 0;
	/** [ Dynamically change svg barcode ] */
	if (obj.value.length === 14) {
		var barcodecheck = checkjsbarcodeitf(obj.value);
		if (barcodecheck == "false") {
			barcode_checks++;
			$(obj).next('div').html('');
			/**  [ Show validation error message ] **/
			$(obj).next('div').html('<span style="color:#F44336">Enter a valid barcode.</span>');
			$('.submit-btn').prop('disabled', true);
		} else {
			barcode_checks = 0;
			$(obj).next('div').html('');
			$('.submit-btn').prop('disabled', false);
		}
	} else {
		barcode_checks = 0;
		$(obj).next('div').html('');
		$('.submit-btn').prop('disabled', false);
	}
}	
/**
 * [cancel tesco orders]
 * @author <praveen>
 */
function cancelTescoOrder() {
	try {
		var tcl1_last_id = $('#TCL1_id').val();
		var tcl2_last_id = $('#TCL2_id').val();
		var tsl1_last_id = $('#TSL1_id').val();
		var tcl4_last_id = $('#TCL4_id').val();
		var tsl3_last_id = $('#TSL3_id').val();
		var thl1_last_id = $('#THL1_id').val();
		var thl3_last_id = $('#THL3_id').val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: base_url + "/admin/tesco/cancel-tesco-order",
			type: 'PUT',
			data: {
				_token: csrf_token,
				tcl1_last_id: tcl1_last_id,
				tcl2_last_id: tcl2_last_id,
				tsl1_last_id: tsl1_last_id,
				tcl4_last_id: tcl4_last_id,
				tsl3_last_id: tsl3_last_id,
				thl1_last_id: thl1_last_id,
				thl3_last_id: thl3_last_id
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
				$('.approve-btn').remove();
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
			$(obj).data("min-error","Quantity Should be >= "+moq); 
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
								$('.count_' + item.template_name.replace(' ', '') ).data("min-error","Quantity Should be >= "+moq); 
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
