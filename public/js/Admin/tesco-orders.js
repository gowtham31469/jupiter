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
                $('.main-content').html('<div class="container-fluid"> <div class="row"> <div class="col-md-4"> <div class="card" > <h4>Create Order</h4> <hr/> <form id="create_order_form" data-toggle="validator" role="form"> <div class="form-group has-feedback"><label for="order_type" class="form_label">Search / Select Purchase Order</label><select class="form-control selectpicker" title="Search / Select PO" data-live-search="true" name="Search PO" required=""><option value="1">PO12345</option><option value="2">PO098765</option></select> <div class="help-block with-errors"></div></div><br><div class="form-group has-feedback"><label for="order_type" class="form_label">Order type</label><select class="form-control order_type selectpicker" name="order_type" id="order_type" required=""><option value="">Select Order Type</option><option value="1">PO Upload</option><option value="2">Manual Entry</option></select> <div class="help-block with-errors"></div></div><br><div class="form-group"><label for="template" class="form_label">Select Template</label><input class="search-input form-control" placeholder="Search for Awesomeness"> </div><div id="jstree"></div><div class="form-group "> <div class="po-upload hidden"> <div class="form-group files" id="single_file "><label>Upload PO</label><input class="form-control " multiple=" " type="file "></div></div></div><button class="btn btn-primary btn-block ">Create Order</button></form> <br/> </div></div><div class="col-md-8 "> <div class="card " style="height:calc(100vh - 100px); "> <h4>Template Preview</h4> <hr/> <div class="row "> <div class="loader "></div><div class="viewDiv "><img style="height:150px;object-fit:cover;margin-top: 122px; " src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png "></div><div class="descriptionDiv "></div></div></div></div></div></div>')

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
        $('.main-content').html('<div class="container"><div class="row"><h2><u>Order details for:<span>TGAM_121232</span></u></h2><hr><div class="row input-fields"><div class="col-md-4 col-sm-12"><table><tr><p>PO Number</p></tr><tr><input type="text" /></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>UK Style Reference</p></tr><tr><input type="text" /></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>Global Style Reference</p></tr><tr><input type="text" /></tr></table></div></div><div class="row input-fields"><div class="col-md-4 col-sm-12"><table><tr><p>Season Code</p></tr><tr><input type="text" /></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>Short Description</p></tr><tr><input type="text" /></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>E0OS Site Code</p></tr><tr><input type="text" /></tr></table></div></div><div class="row input-fields"><div class="col-md-4 col-sm-12"><table><tr><p>Brand</p></tr><tr><select><option value="volvo">Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option><option value="audi">Audi</option></select></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>Supplier Code & Name</p></tr><tr><select><option value="volvo">Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option><option value="audi">Audi</option></select></tr></table></div><div class="col-md-4 col-sm-12"><table><tr><p>Department</p></tr><tr><select><option value="volvo">Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option>x<option value="audi">Audi</option></select></tr></table></div></div><div class="row"><div class="col-md-6"><div class="pull-left"><h4>Note:<span class="order-details-notes">This is a sample note.</span></h4></div></div><div class="col-md-6"><div class="pull-right"><h4>RFID Complaint</h4><input type="radio" name="Yes" value="yes" checked> Yes<input type="radio" name="No" value="no"> No</div></div></div></div></div>');
    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}



