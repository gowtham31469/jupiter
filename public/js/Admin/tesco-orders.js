/**
 * This Function is used to show tesco orders page and template selection.
 * @author Praveenss
 * @return View
 */

function tescoOrders() {
    $('.main-content').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    try {
        /** ajax call to load tesco order page **/
        $.ajax({
        url: base_url + "/admin/tesco/order-management/orders",
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
                $('.main-content').html('<div class="container-fluid"> <div class="row"> <div class="col-md-4"> <div class="card" > <h4>Create Order</h4> <hr/> <form id="create_order_form" data-toggle="validator" role="form"> <div class="form-group has-feedback"><label for="order_type" class="form_label">Search / Select Purchase Order</label><select class="form-control selectpicker" title="Search / Select PO" data-live-search="true" name="Search PO" required=""><option value="1">PO12345</option><option value="2">PO098765</option></select> <div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="order_type" class="form_label">Order type</label><select class="form-control order_type selectpicker" name="order_type" id="order_type" required=""><option value="">Select Order Type</option><option value="1">PO Upload</option><option value="2">Manual Entry</option></select> <div class="help-block with-errors"></div></div><div class="form-group"><label for="template" class="form_label">Select Template</label><input class="search-input form-control" placeholder="Search for Awesomeness"> </div><div id="jstree"></div><div class="form-group "> <div class="po-upload hidden"> <div class="form-group files" id="single_file "><label>Upload PO</label><input class="form-control " multiple=" " type="file "></div></div></div><button class="btn btn-primary btn-block ">Create Order</button></form></div></div><div class="col-md-8 "> <div class="card " style="height:calc(100vh - 100px); "> <h4>Template Preview</h4> <hr/> <div class="row "> <div class="loader "></div><div class="viewDiv "><img style="height:150px;object-fit:cover;margin-top: 122px; " src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png "></div><div class="descriptionDiv "></div></div></div></div></div></div>')

                $(".search-input").keyup(function () {
                    var searchString = $(this).val();
                    $('#jstree').jstree('search', searchString);
                });
                $('.selectpicker').selectpicker();
                $(".order_type").change(function () {
                    var selectedType = $(".order_type option:selected").val();
                    if (selectedType === '1') {
                        $(".po-upload").removeClass("hidden");
                    } else {
                        $(".po-upload").addClass("hidden");
                    }

                });
                $('.descriptionDiv').addClass('hidden')
                $('#jstree').jstree({
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
                    "plugins": ["types", "search", "conditionalselect"],
                    conditionalselect: function (node) {
                        return this.is_leaf(node);
                    }
                }).on('loaded.jstree', function () {
                    $('#jstree').jstree('open_all');
                }).bind("refresh.jstree", function (e, data) {
                    $(this).jstree("open_all");
                });


                $('#jstree').on('activate_node.jstree', function (e, data) {
                    if (data.instance.is_leaf(data.node)) {
                        var template_id = data.node.id;
                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: base_url + "/admin/tesco/template-management/template-view",
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
                                    $('.viewDiv').html('');
                                    $.each(data, function (index, value) {
                                        if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
                                            $('.viewDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>' + value[0].template_name + '</p><label>Reference Number : </label><p>' + value[0].ref_no + '</p><label>Company Name : </label><p>' + value[0].company_name + '</p></div></div>')

                                        } else {
                                            $('.viewDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>' + value[0].template_name + '</p><label>Reference Number : </label><p>' + value[0].ref_no + '</p><label>Company Name : </label><p>' + value[0].company_name + '</p></div></div>')

                                        }
                                    });
                                } else {
                                    $('.viewDiv').html('<img style="height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')

                                    $('.descriptionDiv').html('<b>Template Not Found</b>');
                                    $('.descriptionDiv').css('margin-top', '0px')
                                    $('.viewDiv').css('margin-top', '140px')
                                }
                                $('.viewDiv').css('width', '100%')

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
                /** bootstrap validator for form **/
                $('#create_order_form').validator().on('submit', function (e) {
                    if (e.isDefaultPrevented()) {

                    } else {
                        e.preventDefault();
                        createOrders();
                    }
                })

        },
        error: function (data) {

        },
        complete: function () {
            
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
     })
    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}
/**
 * This Function is used to show create tesco orders page.
 * @author Praveenss
 * @return View
 */

function createOrders() {
    try {
        $('.top-content').html('<span class="pull-right">Please Note : If any select list does not contain required information, please contact Administrator</span><h3 class="content-heading">Order details for:TGAM_121232</h3>');
        $('.main-content').html(' <hr> <div class="col-md-3"> <div class="form-group"> <label for="name">PO Number</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">UK Style Ref.</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">CE Style Ref.</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">Season Code</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">Short Description</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">EQOS Site Code</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <div class="form-group"> <label for="name">Manufactured Date</label> <input type="text" class="form-control" id="" placeholder="Enter PO. Number"> </div></div><div class="col-md-3"> <label for="name">Department</label><select class="selectpicker"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select></div> <div class="clearfix"></div><div class="col-md-3"><label>Brand</label><select class="selectpicker"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select></div><div class="col-md-3"><label>Supplier Code & Name</label><select class="selectpicker"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select></div><div class="col-md-3"><label>RFID Compliant</label><select class="selectpicker"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select></div><div class="col-md-3"><label>Tag At Source</label><select class="selectpicker"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select></div><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4><p><button class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button></p></div></div> <div class="table-responsive"><table class="table table-bordered text-center"> <thead> <tr> <th rowspan="2">Line No.</th> <th rowspan="2">TPND</th> <th rowspan="2">ITF Barcode</th> <th rowspan="2">Units per carton</th> <th colspan="3">TCL1</th> <th colspan="3">TCL2</th> <th colspan="3">TSL1</th> <th rowspan="2">Actions</th> </tr><tr><th>Select</th><th>Count</th><th>Event</th><th>Select</th><th>Count</th><th>Event</th><th>Select</th><th>Count</th><th>Event</th></tr></thead> <tbody> <tr> <td>1</td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><a href="#"><i class="fa fa-trash filterbtn options fa-button" title="" ></i></a></td></tr><tr> <td>2</td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><a href="#"><i class="fa fa-trash filterbtn options fa-button" title="" ></i></a></td></tr><tr> <td>3</td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><div class="form-group"> <input type="text" class="form-control input-sm"> </div></td><td><div class="checkbox"> <label> <input type="checkbox"> </label> </div></td><td><a href="#"><i class="fa fa-trash filterbtn options fa-button" title="" ></i></a></td></tr></tbody> </table></div><hr><h4>Order Preview</h4><div class="row"> <div class="col-md-4"> <div class="card add-card"> <div class="card-title"> <h5><b>TCL 1</b></h5> </div><div class="row"> <div class="col-md-8 col-md-offset-2"> <div class="img_artwork"> <img src="/images/wcl_default.png" class="img-responsive center-block"> </div></div></div></div></div><div class="col-md-4"> <div class="card add-card"> <div class="card-title"> <h5><b>TCL 1</b></h5> </div><div class="row"> <div class="col-md-8 col-md-offset-2"> <div class="img_artwork"> <img src="/images/wcl_default.png" class="img-responsive center-block"> </div></div></div></div></div><div class="col-md-4"> <div class="card add-card"> <div class="card-title"> <h5><b>TCL 1</b></h5> </div><div class="row"> <div class="col-md-8 col-md-offset-2"> <div class="img_artwork"> <img src="/images/wcl_default.png" class="img-responsive center-block"> </div></div></div></div></div></div><div class="bottom-bar"><div class="modal-footer"><span class="pagename pull-left">Please save or submit changes before closing page.</span><button class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Cancel</button> <button type="button" class="btn btn-xs btn-success" onclick="save_label_orders()"><i class="ion-ios-download"></i>Submit</button><button class="btn btn-warning btn-xs"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button></div></div>');
        $(".card").css("margin-bottom", "80px");
        $('.selectpicker').selectpicker();

        
    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}



