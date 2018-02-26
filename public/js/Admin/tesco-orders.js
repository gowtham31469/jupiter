/**
 * This Function is used to show tesco orders page and template selection.
 * @author Praveen
 * @return View
 */
var globalOrderType = 0;
function tescoOrders() {
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.top-content').html('');
    $('.template-error').html('');
    /** [ Remove local storage values ] */
    localStorage.removeItem("templates");
    localStorage.removeItem("zone");
    localStorage.removeItem("template type");
    localStorage.removeItem("data");
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
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
                $('.main-content').html('<div class="container-fluid"> <div class="row"> <div class="col-md-4"> <div class="card"> <h4>Create Order</h4> <hr/> <form id="create_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"> <div class="form-group has-feedback"> <label for="order_type" class="form_label">Select Order Type</label> <select class="form-control order_type selectpicker" name="order_type" id="order_type" required=""> <option value="">Select Order Type</option> <option value="1">PO Upload</option> <option value="2">Manual Entry</option> </select> <div class="help-block with-errors"></div></div><div class="form-group"> <label for="template" class="form_label">Select Template</label> <input class="search-input form-control" placeholder="Search for Awesomeness"> <div class="template-error"></div></div><div id="jstree"></div><div class="form-group has-feedback"> <div class="po-upload hidden"> <div class="form-group files" id="single_file "> <label>Upload UK PO</label> <input type="file" name="purchase_order" id="po_upload" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/> <div class="help-block with-errors"></div></div></div><div class="ce-po-upload hidden"> <div class="form-group files" id="multiple_file1"> <label>Upload CE-ORMS Sheet</label> <input type="file" name="ce_orms_purchase_order" id="ce_orms" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/> <div class="help-block with-errors"></div></div><div class="form-group files" id="multiple_file2"> <label>Upload CE-RETEK Sheet</label> <input type="file" name="ce_retek_purchase_order" id="ce_retek" class="form-control" accept="application/pdf" onchange="ValidateSingleInput(this);" required/> <div class="help-block with-errors"></div></div></div></div><button class="btn btn-primary btn-block ">Create Order</button> </form> </div></div><div class="col-md-8 "> <div class="card " style="height:calc(100vh - 100px); "> <h4>Template Preview</h4> <hr/> <div class="row "> <div class="loader "> <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="templateDiv "><img style="height:150px;object-fit:cover;margin-top: 122px; " src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png "></div><div class="descriptionDiv "></div></div></div></div></div></div>');

                /** [ Search  functionality foe jstree nodes ] */
                $(".search-input").keyup(function () {
                    var searchString = $(this).val();
                    $('#jstree').jstree('search', searchString);
                });

                /** [ Load selectpicker ] */
                $('.selectpicker').selectpicker();

                /** [ Hide/Show PO upload div, PO number search based on order type selected ] */
                $(".order_type").change(function () {
                    globalOrderType = ($(".order_type option:selected").val() === '1') ? 1 : 0;
                    var currentNode = $('#jstree').jstree(true).get_selected('full',true);
                    var zone        = (currentNode.length > 0) ? currentNode[0].original.zone : false;

                    if(zone === 'UK' && globalOrderType === 1) {
                        $(".po-upload").removeClass("hidden");
                        $('#po_upload').prop('required', true);
                        $(".ce-po-upload").addClass("hidden");
                        $('#ce_orms').prop('required', false);
                        $('#ce_retek').prop('required', false);
                    } else if(zone === 'CE' && globalOrderType === 1) {
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
                                        "parent_id": 3,
                                        "type":"ratio",
                                        "zone": "CE"
                                        }, {
                                        "id": "8,9",
                                        "text": "TCL 4 & TSL 3",
                                        "parent_id": 3,
                                        "type":"single",
                                        "zone": "CE"
                                        }, {
                                        "id": "10",
                                        "text": "THL 1",
                                        "parent_id": 3,
                                        "type":"ratio",
                                        "zone": "CE"
                                        }, {
                                        "id": "11",
                                        "text": "THL 3",
                                        "parent_id": 3,
                                        "type":"single",
                                        "zone": "CE"
                                        }]
                                    }, {
                                    "id": 4,
                                    "text": "UK",
                                    "parent_id": 2,
                                    "children": [{
                                        "id": "12,13,14",
                                        "text": "TCL 1 , TCL 2 & TSL 1",
                                        "parent_id": 4,
                                        "type":"ratio",
                                        "zone": "UK"
                                        }, {
                                        "id": "15,16",
                                        "text": "TCL 4 & TSL 3",
                                        "parent_id": 4,
                                        "type":"single",
                                        "zone": "UK"
                                        }, {
                                        "id": "17",
                                        "text": "THL 1",
                                        "parent_id": 4,
                                        "type":"ratio",
                                        "zone": "UK"
                                        }, {
                                        "id": "18",
                                        "text": "THL 3",
                                        "parent_id": 4,
                                        "type":"single",
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
                }).bind('before.jstree', function(event, data){ 
                    /** [ Make only leaf-nodes selectable ] */
                    if(data.instance.is_leaf(data.args[0])){ 
                       return false; 
                    } 
                })

                /** [ Get tempalte preview based on template selection ]  */
                $('#jstree').on('activate_node.jstree', function (e, data) {
                    $('.template-error').html('')
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
                            },
                            success: function (data) {
                                if (data.length > 0) {
                                    $('.templateDiv').html('');
                                    $.each(data, function (index, value) {
                                        if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
                                            $('.templateDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>' + value[0].template_name + '</p><label>Reference Number : </label><p>' + value[0].ref_no + '</p><label>Company Name : </label><p>' + value[0].company_name + '</p></div></div>')

                                        } else {
                                            $('.templateDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><label>Template Name : </label><p>' + value[0].template_name + '</p><label>Reference Number : </label><p>' + value[0].ref_no + '</p><label>Company Name : </label><p>' + value[0].company_name + '</p></div></div>')

                                        }
                                    });
                                } else {
                                    $('.templateDiv').html('<img style="height:150px;object-fit:cover;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')

                                    $('.descriptionDiv').html('<b>Template Not Found</b>');
                                    $('.descriptionDiv').css('margin-top', '0px')
                                    $('.templateDiv').css('margin-top', '140px')
                                }
                                $('.templateDiv').css('width', '100%')

                            },
                            error: function (data) {

                            },
                            complete: function () {
                                $(".loader").hide();
                            }
                        })
                    }
                });

                /** [ To get selected leaf node ] */
                var template_id = 0;
                var type;
                var zone;
                $('#jstree').on('activate_node.jstree', function (e, data) {
                    if (data.instance.is_leaf(data.node) != '') 
                    {
                        template_id = data.node.id;

                        /** [ add values into local storage ] */
                        localStorage.setItem("templates", data.node.id);
                        localStorage.setItem("zone", data.node.original.zone);
                        localStorage.setItem("template type", data.node.original.type);
                        localStorage.setItem("template_group", data.node.text.trim());
                        if(data.node.original.zone === 'UK' && globalOrderType === 1) {
                            $(".po-upload").removeClass("hidden");
                            $('#po_upload').prop('required', true);
                            $(".ce-po-upload").addClass("hidden");
                            $('#ce_orms').prop('required', false);
                            $('#ce_retek').prop('required', false);
                        } else if(data.node.original.zone === 'CE' && globalOrderType === 1) {
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

                    }
                    else 
                    {
                        e.preventDefault();

                        /** [ Check if leaf node( template ) selected or not ] **/
                        if (template_id != 0) 
                        {
                            $('.template-error').html('');
                            //Check for the order type is PO Upload
                            //If so, create a formdata for uploading the file.
                            //invoke uploadPurchaseOrder method with the form data.
                            //If not, go with manual upload by invoking createOrders() method.
                            if($('#order_type').val() == 1) {
                                var formData = new FormData($(this)[0]);
                                formData.append('type', localStorage.getItem("template type"));
                                formData.append('zone', localStorage.getItem("zone"));
                                formData.append('template_name', localStorage.getItem("template_group"));
                                uploadPurchaseOrder(formData);
                            } else {
                                createOrders();
                            }
                            
                        }
                        else
                        {
                            /**  [ Show validation error message ] **/
                            $('.template-error').html('<span style="color:#a94442">Please select a template in the list.</span>');
                        }
                    }
                })

        },
        error: function (data) {

        },
        complete: function () {
            
            $(".loader").hide();
        }
     })
    } catch(e) {
        /** [ error statements ] */
        console.log("Error:"+e)
    }
}

/**
 * This Function is used to show create tesco orders page.
 * @author Praveen
 * @return View
 */

function createOrders(parsedObj = false) {
    try {
        /* [ Get values from local storage ] */
        var template_id = localStorage.getItem("templates");
        var zone        = localStorage.getItem("zone");
        var type        = localStorage.getItem("template type");
        var random_order_id = 'JGAM_1517'+Math.floor(Math.random() * 1000000000);
        $('.top-content').html('<span class="pull-right">Please Note : If any select list does not contain required information, please contact Administrator</span>');
//        <h3 class="content-heading" >Order details for : '+random_order_id+'</h3>


         $.ajax({
                 url: base_url + "/admin/tesco/templates-details",
                 type: "get",
                 dataType:"json",
                 data: {template_id:template_id},
                 beforeSend: function () {
                     $(".loader").show();
                     $(".loader").css('margin-top','12%');
                 },
                 success: function (data) {
                     localStorage.setItem("data", JSON.stringify(data.templates));
                     var html = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><br><form method="POST" id="create_tesco_order_form" data-toggle="validator" role="form" novalidate="true"><div class="col-md-3"><div class="form-group has-feedback"><label for="tesco_po_number">PO Number</label> <input type="text" class="form-control" name="tesco_po_number" id="tesco_po_number" required=""  placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_uk_style">UK Style Ref.</label> <input type="text" class="form-control" name="tesco_uk_style" id="tesco_uk_style"  placeholder="Enter UK Style Ref." required="" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_ce_style">CE Style Ref.</label> <input type="text" class="form-control" name="tesco_ce_style" id="tesco_ce_style" placeholder="Enter CE Style Ref." required="" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_season_code">Season Code</label> <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required=""  placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_description">Short Description</label> <input type="text" class="form-control" name="tesco_description" id="tesco_description" required=""  placeholder="Enter Description"  maxlength="24" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_eqos">EQOS Site Code</label> <input type="text" class="form-control" name="tesco_eqos" id="tesco_eqos" required=""  placeholder="Enter EQOS Site Code" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="tesco_manufacture_date">Manufactured Date</label><div class="input-group tesco_manufacture_date date" data-provide="datepicker" id="datePicker1"> <input value="" id="tesco_manufacture_date" name="tesco_manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date" onchange="dynamicSVGTemplates()"> <div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span > </div > </div ><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="tesco_department">Department</label><select class="selectpicker" data-live-search="true" id="tesco_department" name="tesco_department" required="" onchange="dynamicSVGTemplates()"><option value="">Select Department</option>';

                      /** [ Show dynamic department selectbox ] */
                      $.each(data.tesco_department, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.department + '</option>';
                      });

                     html +='</select></div></div><div class="clearfix"></div><div class="col-md-3"> <div class="form-group has-feedback"><label for="tesco_brand">Brand</label> <select class="selectpicker" data-live-search="true" id="tesco_brand" name="tesco_brand" onchange="dynamicSVGTemplates()" required=""><option value="">Select Brand</option>';

                       /** [ Show dynamic brand selectbox ] */
                       $.each(data.tesco_brand, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.brand + '</option>';
                       });  

                     html +='</select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="tesco_supplier">Supplier</label> <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicSVGTemplates()"><option value="">Select Supplier</option>';

                       /** [ Show dynamic supplier selectbox ] */
                       $.each(data.tesco_suppliers, function(index, item) {
                        html += '<option value="'+ item.id +'">'+ item.company_name + '</option>';
                        });  

                     html +='</select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="tesco_rfid">RFID Compliant</label> <select class="selectpicker" id="tesco_rfid" name="tesco_rfid" required="" onchange="dynamicSVGTemplates()"><option value="">Select RFID Compliant</option><option value="yes">Yes</option><option value="no">No</option> </select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="tesco_tag_at_source">Tag At Source</label> <select class="selectpicker" id="tesco_tag_at_source" name="tesco_tag_at_source" required="" onchange="dynamicSVGTemplates()"><option value="">Select Tag At Source</option><option value="yes">Yes</option><option value="no">No</option> </select></div></div>';
                     if(zone === "UK")
                     {
                        /**  [ Dynamic quantity table for UK ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4><p> <button type="button" onclick="addUKRatio(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button></p></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">TPND</th><th rowspan="2">ITF Barcode</th><th rowspan="2">Units per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th></tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });

                             html += '</tr></thead><tbody id="table_row"><tr><td class="slno">1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_1" name="uk_ratio_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_itf" data-minlength="14" maxlength="14" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_1" name="uk_ratio_itf_1" onkeyup="dynamicSVGTemplates()" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_1" name="uk_ratio_upc_1" placeholder="Enter Units Per Carton" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';

                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                        else
                        {
                            /**  [ Dynamic quantity table for UK single templates ] */
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4><p> <button type="button" onclick="addUKSingle(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button></p></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">TPND</th><th rowspan="2">EAN Barcode</th><th rowspan="2">Case Size</th><th rowspan="2">Size</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th></tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr></thead><tbody id="table_row"><tr><td class="slno">1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_1" name="uk_single_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()"> <div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_ean" data-minlength="13" maxlength="13" required="" onkeypress="return isNumber(event)" onkeyup="dynamicSVGTemplates()" id="uk_single_ean_1" name="uk_single_ean_1" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeyup="dynamicSVGTemplates()" onkeypress="return isNumber(event)" id="uk_single_case_size_1" name="uk_single_case_size_1" placeholder="Enter Case Size" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_1" name="uk_single_size_1" onchange="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_1" name="uk_single_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                     }
                     else
                     {  
                        /**  [ Dynamic quantity table for CE ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4><p> <button type="button" onclick="addCERatio(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button></p></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">ORMS TPN</th><th rowspan="2">ITF Barcode</th><th rowspan="2">Cases per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th></tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr></thead><tbody id="table_row"><tr><td class="slno">1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_1" name="ce_ratio_orms_tpn_1" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_itf" required="" data-minlength="14" maxlength="14" id="ce_ratio_itf_1" onkeyup="dynamicSVGTemplates()" name="ce_ratio_itf_1" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_1" name="ce_ratio_cpc_1" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                        else
                        {
                            /**  [ Dynamic quantity table for CE single templates ] */
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4><p> <button type="button" onclick="addCESingle(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button></p></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">ORMS TPN</th><th rowspan="2">EAN Barcode</th><th rowspan="2">Case Size</th><th rowspan="2">Size</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th></tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr></thead><tbody id="table_row"><tr><td class="slno">1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_1" name="ce_single_orms_tpn_1" required="" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_1" name="ce_single_ean_1" onkeyup="dynamicSVGTemplates()" required="" data-minlength="13" maxlength="13" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_1" name="ce_single_case_size_1" required="" placeholder="Enter Case Size" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_1" name="ce_single_size_1" required="" placeholder="Enter Size" onchange="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" data-tname="'+item.template_name+'" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_single_count_'+item.template_name.replace(' ', '')+'_1" name="ce_single_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                     }
                    
                      html += '<h4>Order Preview</h4><div class="row">';
                      $.each(data.templates, function(i, item){
                       html += '<div class="col-md-4"><div class="card add-card"><div class="card-title"><p class="pull-right" onclick=openmodaltesco("'+item.template_name.replace(' ', '')+'","'+i+'") style="cursor:pointer">Click to view</p><h5><b>'+item.template_name+'</b></h5></div><div class="row"><div class="col-md-8 col-md-offset-2"><div class="tesco_artwork"> <object style="border: 2px #333 solid;" id="tesco_svg'+i+'" class="center-block tesco_svg'+i+'" data="' + item.template_file + '" type="image/svg+xml"></object> <div class="modal fade" id="'+item.template_name.replace(' ', '')+'" role="dialog"> <div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">'+item.template_name+'</h4></div><div class="modal-body"> <object style="border: 2px #333 solid;" id="tesco_svg_modal'+i+'" class="center-block tesco_svg'+i+'" data="' + item.template_file + '" type="image/svg+xml"></object></div><div class="modal-footer"><button type="button" class="btn btn-primary" onclick=printDivTesco("tesco_svg_modal'+i+'","'+item.template_name.replace(' ', '')+'")>Print</button><div id="svgdiv" style="position:absolute;visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div></div></div></div></div></div>';
                      });
                      html += '</div><div class="bottom-bar"><div class="modal-footer"><span class="pagename pull-left hidden-xs">Please submit changes before closing page.</span><button class="btn btn-xs btn-warning submit-btn" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button><button class="btn btn-xs btn-danger" type="button" onclick="location.reload()"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button></div></div></div></div>';

                      $('.main-content').html(html).selectpicker('refresh');

                      /* [ Toggle required field validation in quantity table ] */
                      $(':checkbox').on('change',function(){
                       var th = $(this), id = th.prop('id');
                       if(th.is(':checked'))
                       {
                           /* [ add required for input field ] */
                           $('input[id="'  + id + '"]').prop('required',true) 
                        }
                        else
                        {
                            /* [ remove required from input field ] */
                            $('input[id="'  + id + '"]').prop('required',false) 
                            /* [ remove validation error from input field ] */
                            $('input[id="'  + id + '"]').siblings('').html('')
                            $('input[id="'  + id + '"]').parent().removeClass('has-error has-danger');
                        }
                      });
                      /** @type {Date} [Manufactured date Datepicker format & validation] */
                      var date = new Date();
                      var today = new Date(date.getFullYear(), date.getMonth());
                       $('#datePicker1').datepicker({
                            autoclose: true,
                            minViewMode: 1,
                            format: 'MM,yyyy',
                            startDate: today,
                        })
                      /** [ bootstrap validator for create tesco order form ] **/
                        $('#create_tesco_order_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) {

                            }
                            else 
                            {
                                e.preventDefault();
                                setTescoOrder();
                            }
                        })
                        $(".card").css("margin-bottom", "80px");
                        $('.selectpicker').selectpicker();

                        /** [ show tooltip ] */
                        $('.delete-row').tooltip('show')
                        $('.delete-row').tooltip('toggle')
                        if(parsedObj) {
                            renderParsedValues(parsedObj);
                            setTimeout(function(){  dynamicSVGTemplates(); }, 500);
                        }
                 },
                 error: function (data) {
                    console.log(data);
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
                      $(".loader").css('margin-top','0%');
                 }
             })
        
    } catch(e) {
        /** [ error statements ] */
        console.log("Error:"+e)
    }
}

/**
 * This Function is used Print Stickers.
 *  
 * @return Pdf
 */

function printDivTesco(data,name) {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		var name=name+"-sticker";
	    if(name == "TSL1-sticker" || name == "TSL3-sticker")
		{
			PopupTescoSmall(svgRoot,name);
		}
		else
		{
			PopupTesco(svgRoot,name);
		}
	
		
}

/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTesco(data,name) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}
/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoSmall(data,name) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/tesco/print-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 *  addUKSingle function for adding dynamic table row for UK single templates 
 * @author <praveen>
 * 
 */
function addUKSingle(rem,e) {
try{
         e.preventDefault(); 
         /** [ Get template details from localstorage ] */
         var data = JSON.parse(localStorage.getItem("data"));
         var a = [] 
         /** [ Get data-id of add button ] */
         var id = $(rem).attr("data-id"); 
         var val = parseInt(id) + 1; 
         var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_'+val+'" name="uk_single_tpnd_'+val+'" required=""  placeholder="Enter TPND"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_ean" required="" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" id="uk_single_ean_'+val+'" name="uk_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeypress="return isNumber(event)" id="uk_single_case_size_'+val+'" name="uk_single_case_size_'+val+'" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_'+val+'" name="uk_single_size_'+val+'"><div class="help-block with-errors"></div></div></td>';
             $.each(data, function(i, item){
               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
             });
          html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a></td></tr>';
         $('#table_row').append(html);

         /** [ Refresh boostrap form-validator ]  */
         $('#create_tesco_order_form').validator('update'); 

          /** [ Update data-id of add button ] */
         $(rem).attr("data-id", val); 

         /** [ Call setSerialNumber function ] */
         setSerialNumber()
         
   } catch (e) {
    /** [error statements] */
   console.log("Error:"+e)
 }
}
/**
 *  addUKRatio function for adding dynamic table row for UK ratio templates 
 * @author <praveen>
 * 
 */
function addUKRatio(rem,e) {
try{
         e.preventDefault(); 
         /** [ Get template details from localstorage ] */
         var data = JSON.parse(localStorage.getItem("data"));
         var a = [] 
         /** [ Get data-id of add button ] */
         var id = $(rem).attr("data-id"); 
         var val = parseInt(id) + 1; 
         var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_'+val+'" name="uk_ratio_tpnd_'+val+'" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_itf" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_'+val+'" name="uk_ratio_itf_'+val+'" onkeyup="dynamicSVGTemplates()" data-minlength="14" maxlength="14" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_'+val+'" name="uk_ratio_upc_'+val+'" placeholder="Enter Units Per Carton" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
             $.each(data, function(i, item){
               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="uk_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
             });
          html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a></td></tr>';
         $('#table_row').append(html);

         /** [ Refresh boostrap form-validator ]  */
         $('#create_tesco_order_form').validator('update'); 

          /** [ Update data-id of add button ] */
         $(rem).attr("data-id", val); 

         /** [ Call setSerialNumber function ] */
         setSerialNumber()
         
   } catch (e) {
    /** [error statements] */
   console.log("Error:"+e)
 }
}
/**
 * Tesco Create Order by PO upload Method.
 * Get the template type and zone from the form along with PDF.
 * File to be uploaded for parsing.
 * Render the the order form with autofilled fields which are parsed from the customer PDF.
 * @return {xml} XML from the parser. 
 */
function  uploadPurchaseOrder(formData) {
    try {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         $.ajax({
                 url: base_url + "/admin/tesco/po-upload",
                 type: 'POST',
                 data: formData,
                 dataType:'JSON',
                 async: true,
                 cache: false,
                 contentType: false,
                 processData: false,
                 beforeSend: function () {
                     $(".loader").show();
                 },       
                 statusCode:
                 {
                    200: function(data) {
                       //Call the parse method which resides on parser.js
                        parseXML(data, createOrders);       
                    },
                    422: function(data) {  
                        $('.snackbar_span').html("File Mismatch!! Please check the file.");
                        $('#snackbar').css('background-color','#F44336');
                        showSnackbar();
                    },
                    400: function() {
                        $('.snackbar_span').html("Sorry!! You seem to have uploaded an invalid file. Please check the file or Try Manual Mode");
                        $('#snackbar').css('background-color','#F44336');
                        showSnackbar();
                    }
                 },
                 error: function (data) {
                    console.log(data);
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
                       $(".loader").hide();
                 }
             })
        
    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}

/**
 *  addCESingle function for adding dynamic table row for CE single templates 
 * @author <praveen>
 * 
 */
function addCESingle(rem,e) {
try{
     e.preventDefault(); 
     /** [ Get template details from localstorage ] */
     var data = JSON.parse(localStorage.getItem("data"));
     var a = [] 
     /** [ Get data-id of add button ] */
     var id = $(rem).attr("data-id"); 
     var val = parseInt(id) + 1; 
     var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_'+val+'" name="ce_single_orms_tpn_'+val+'" required="" placeholder="Enter ORMS TPN"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" id="ce_single_ean_'+val+'" name="ce_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_'+val+'" name="ce_single_case_size_'+val+'" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_'+val+'" name="ce_single_size_'+val+'" required="" placeholder="Enter Size"><div class="help-block with-errors"></div></div></td>';
         $.each(data, function(i, item){
           html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" data-tname="'+item.template_name+'" id="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
         });
      html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a></td></tr>';
     $('#table_row').append(html);

     /** [ Refresh boostrap form-validator ]  */
     $('#create_tesco_order_form').validator('update'); 

      /** [ Update data-id of add button ] */
     $(rem).attr("data-id", val); 

     /** [ Call setSerialNumber function ] */
     setSerialNumber()
   } catch (e) {
    /** [error statements] */
   console.log("Error:"+e)
 }
}
/**
 *  addCERatio function for adding dynamic table row for CE Ratio templates 
 * @author <praveen>
 * 
 */
function addCERatio(rem,e) {
try{
     e.preventDefault(); 
     /** [ Get template details from localstorage ] */
     var data = JSON.parse(localStorage.getItem("data"));
     var a = [] 
     /** [ Get data-id of add button ] */
     var id = $(rem).attr("data-id"); 
     var val = parseInt(id) + 1; 
     var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_'+val+'" name="ce_ratio_orms_tpn_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_itf" data-minlength="14" maxlength="14"  required="" id="ce_ratio_itf_'+val+'" name="ce_ratio_itf_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_'+val+'" name="ce_ratio_cpc_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton" onkeyup="dynamicSVGTemplates()"><div class="help-block with-errors"></div></div></td>';
         $.each(data, function(i, item){
           html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="ce_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
         });
      html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a></td></tr>';
     $('#table_row').append(html);

     /** [ Refresh boostrap form-validator ]  */
     $('#create_tesco_order_form').validator('update'); 

      /** [ Update data-id of add button ] */
     $(rem).attr("data-id", val); 

     /** [ Call setSerialNumber function ] */
     setSerialNumber()
   } catch (e) {
    /** [error statements] */
   console.log("Error:"+e)
 }
}
/**
  * Removes row from order quantity table
  *
  * @author <praveen>
  */
function removeRow(rem,event) {
try{
    
     event.preventDefault();
     /** [ Remove Row from table ] */
     $(rem).parent().parent().parent().remove();

     /** [ Refresh boostrap form-validator ]  */
     $('#create_tesco_order_form').validator('update');

     /** [ Call setSerialNumber function ] */
     setSerialNumber()

  } catch (e) {
    /** [ error statements ] */
   console.log("Error:"+e)
 }
}
/**
  * Generate dynamic serial number for quantity table
  *
  * @author <praveen>
  */
function setSerialNumber()
{
    var j = 1; 

    /** [ Dynamically generate slno ] */
    $('#table_row tr').each(function(key, val) { 
       $(this).find(".slno").text(j)
       j++; 
    })
}
/**
 * [ dynamicSVGTemplates function for creating dynamic svg file ]
 * @author <praveen@wizroots.com>
 */
function dynamicSVGTemplates()
{
	 /** [ Get data from localstorage ] */
	 var data = JSON.parse(localStorage.getItem("data"));
	 try{
		$.each(data, function(i, item){
			dynamicSVGTemplatescard("tesco_svg"+i,item);
			//dynamicSVGTemplatescard("tesco_svg_modal"+i,item);  
		});   
		} catch (e) {
		/** [error statements] */
	       console.error(e);
        }   
}
/**
 * [ dynamicSVGTemplatescard function for creating dynamic card svg file ]
 * @author <praveen@wizroots.com>
 */
function dynamicSVGTemplatescard(id,item)
{
	
    var zone = localStorage.getItem("zone");
    var type = localStorage.getItem("template type");
	/**  [ Dynamically change all svg templates ] */
        var mySVG = document.getElementById(id);
        var svgDoc = mySVG.contentDocument;
         /** [ separate conditions for TSL1,TSL3 and other stickers ] */
        if(item.template_name === "TSL 1" || item.template_name === "TSL 3")
        {
           
           if(zone === "CE")
           {
              /**  [ if CE ratio template ] */
              if(type === "ratio")
              {
                 var tpnd = $('#ce_ratio_orms_tpn_1').val();
                 var description  = $('#tesco_description').val();
				 var ce_ratio_itf  = $('#ce_ratio_itf_1').val();
				 
                 /** [ Dynamically change svg short description ] */
                 if(description != '')
                 {
                    svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                 }
                 else
                 {
                    svgDoc.getElementById("description").innerHTML = '';
                 }
                 /** [ Dynamically change svg CE ratio tpnd number ] */
                 if(tpnd != '')
                 {
                     svgDoc.getElementById("tpnd").innerHTML = tpnd;
                 }
                 else
                 {
                     svgDoc.getElementById("tpnd").innerHTML = '';
                 }
				  
				  
				   /** [ Dynamically change svg barcode ] */
				  if(ce_ratio_itf.length === 14)
				  {
					jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
				  }
				  else
				  {
					 svgDoc.getElementById("barcode").innerHTML = '';
				  }
              }
              else
              {
                 /**  [ if CE single template ] */
                 var orms_tpn = $('#ce_single_orms_tpn_1').val();
                 var description  = $('#tesco_description').val();
				  
				 var ce_single_ean  = $('#ce_single_ean_1').val();
                 
                 /** [ Dynamically change svg CE single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
				  
				 if(item.template_name === "TSL 3")
				 {
					 
					  /** [ Dynamically change svg barcode ] */
					  if(ce_single_ean.length === 13)
					  {
						jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
					  }
					  else
					  {
						 svgDoc.getElementById("barcode").innerHTML = '';
					  }
				 }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/admin/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.log(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }
           }
           else
           {
              /**  [ if UK ratio template ] */
              if(type === "ratio")
              { 
                  var tpnd = $('#uk_ratio_tpnd_1').val();
                  var description  = $('#tesco_description').val();
				  var uk_ratio_itf  = $('#uk_ratio_itf_1').val();
				  
                  /** [ Dynamically change svg UK ratio tpnd number ] */
                  if(tpnd != '')
                  {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                  }
                  else
                  {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                  }
                  /** [ Dynamically change svg short description ] */
                  if(description != '')
                  {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                  }
                  else
                  {
                      svgDoc.getElementById("description").innerHTML = ''; 
                  }
				  /** [ Dynamically change svg barcode ] */
				  if(uk_ratio_itf.length === 14)
				  {
					jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
				  }
				  else
				  {
					 svgDoc.getElementById("barcode").innerHTML = '';
				  }
              }
              else
              {
                 /**  [ if UK single template ] */
                 var orms_tpn = $('#uk_single_tpnd_1').val();
                 var description  = $('#tesco_description').val();
				 var uk_single_ean  = $('#uk_single_ean_1').val();
                 /** [ Dynamically change svg UK single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
				 if(item.template_name === "TSL 3")
				 {
					 
					  /** [ Dynamically change svg barcode ] */
					  if(uk_single_ean.length === 13)
					  {
						jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
					  }
					  else
					  {
						 svgDoc.getElementById("barcode").innerHTML = '';
					  }
				 }
                 var size  = $('#uk_single_size_1').val();
                 /** if size is not empty, Get equivalent uk size for entered size (single templates)*/
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/admin/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }  
           }
        }
        else 
        {
            var po_number        = $('#tesco_po_number').val();
            var uk_style         = $('#tesco_uk_style').val();
            var ce_style         = $('#tesco_ce_style').val();
            var tesco_eqos       = $('#tesco_eqos').val();
            var supplier         = $('#tesco_supplier option:selected').text();
            var department       = $('#tesco_department option:selected').text();
            var brand            = $('#tesco_brand option:selected').text();
            var rfid             = $('#tesco_rfid option:selected').text();
            var tag_at_source    = $('#tesco_tag_at_source option:selected').text();
            var season_code      = $('#tesco_season_code').val();
            var manufacture_date = $('#tesco_manufacture_date').val();
			
			
           if(po_number != '')
           {
              svgDoc.getElementById("po_number").innerHTML = po_number.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("po_number").innerHTML = '';
           }

           /** [ Dynamically change svg CE style reference number ] */
           if(ce_style != '')
           {
              svgDoc.getElementById("ce_style").innerHTML = ce_style.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("ce_style").innerHTML = '';
           }
           /** [ Dynamically change svg UK style reference number ] */
           if(uk_style != '')
           {
              svgDoc.getElementById("uk_style").innerHTML = uk_style.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("uk_style").innerHTML = '';
           }
           /** [ Dynamically change svg EQOS code ] */
           if(tesco_eqos != '')
           {
              svgDoc.getElementById("eqos").innerHTML = tesco_eqos.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("eqos").innerHTML = '';
           }
           /** [ Dynamically change  svg supplier name ] */
           if($('#tesco_supplier option:selected').val() != '')
           {
              svgDoc.getElementById("supplier").innerHTML = supplier.toUpperCase().replace('&', '&amp;');
           }
           else
           {
              svgDoc.getElementById("supplier").innerHTML = '';
           }
           /** [ Dynamically change  svg department ] */
           if($('#tesco_department option:selected').val() != '')
           {
              svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace('&', '&amp;');
           }  
           else
           { 
              svgDoc.getElementById("department").innerHTML = '';
           }
           /** [ Dynamically change svg brand ] */
           if($('#tesco_brand option:selected').val() != '')
           {
              svgDoc.getElementById("brand").innerHTML = brand.toUpperCase().replace('&', '&amp;');
           } 
           else
           {
              svgDoc.getElementById("brand").innerHTML = '';
           }
           /** [ Dynamically svg RFID ] */
           if($('#tesco_rfid option:selected').val() != '')
           {
              svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("rfid").innerHTML = ''; 
           } 
           /** [ Dynamically change svg tag at source ] */
           if($('#tesco_tag_at_source option:selected').val() != '')
           {
              svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("tag_at_source").innerHTML = '';
           }
           /** [ Dynamically change svg tag at source ] */
           if(season_code != '')
           {
              svgDoc.getElementById("season_code").innerHTML = season_code.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("season_code").innerHTML = '';
           }
           /** [ Dynamically change svg manufacture date ] */
           if(manufacture_date != '')
           {
              svgDoc.getElementById("manufactured_date").innerHTML = manufacture_date.toUpperCase();
           }
           else
           {
              svgDoc.getElementById("manufactured_date").innerHTML = '';
           }
           if(zone === "CE")
           {
              /**  [ if CE ratio template ] */
              if(type === "ratio")
              {
                  var pieces_in_ratio = $('#ce_ratio_cpc_1').val();
                  var tpnd = $('#ce_ratio_orms_tpn_1').val();
				  var ce_ratio_itf  = $('#ce_ratio_itf_1').val();
                  var description      = $('#tesco_description').val();
                  /** [ Dynamically change svg CE pieces in ratio ] */
                   if(pieces_in_ratio != '')
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                   }
                   else
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                   }
                   /** [ Dynamically change svg CE ratio tpnd number ] */
                   if(tpnd != '')
                   {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                   } 
                   else
                   {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                   }
				   if(item.template_name === "TCL 1" || item.template_name === "THL 1")
                   {
					 
                     /** [ Dynamically change svg barcode ] */
                       if(ce_ratio_itf !='' && ce_ratio_itf.length === 14)
                       {
                        
                        jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)

                       }
                       else
                       { 
                         svgDoc.getElementById("barcode").innerHTML = '';
                       }
                   }
                   /** [ Dynamically change svg short description ] */
                   if(description != '')
                   {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                   }
                   else
                   {
                      svgDoc.getElementById("description").innerHTML = '';
                   }
              }
              else
              {
                 /**  [ if CE single template ] */
                 var orms_tpn = $('#ce_single_orms_tpn_1').val();
                 var case_size = $('#ce_single_case_size_1').val();
				 var ce_single_ean  = $('#ce_single_ean_1').val();
                 var description      = $('#tesco_description').val();
                 /** [ Dynamically change svg CE single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                 /** [ Dynamically change svg CE single case size ] */
                 if(case_size != '')
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                 }
                 else
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = '';
                 }
				 if(item.template_name === "THL 3")
			     {
					   /** [ Dynamically change svg barcode ] */
					   if(ce_single_ean !='' && ce_single_ean.length === 13)
					   {

						  jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)

					   }
					   else
					   { 
                          svgDoc.getElementById("barcode").innerHTML = '';
					   }
			     }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/admin/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
                 
              }
           }
           else
           {
              /**  [ if UK ratio template ] */
              if(type === "ratio")
              {
                   var pieces_in_ratio = $('#uk_ratio_upc_1').val();
                   var tpnd = $('#uk_ratio_tpnd_1').val();
				   var uk_ratio_itf  = $('#uk_ratio_itf_1').val();
                   var description      = $('#tesco_description').val();

                   /** [ Dynamically change svg UK pieces in ratio ] */
                   if(pieces_in_ratio != '')
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = pieces_in_ratio;
                   }
                   else
                   {
                      svgDoc.getElementById("pieces_in_ratio").innerHTML = '';
                   }
                   /** [ Dynamically change svg UK ratio tpnd number ] */
                   if(tpnd != '')
                   {
                      svgDoc.getElementById("tpnd").innerHTML = tpnd;
                   }
                   else
                   {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                   }
                   if(item.template_name === "TCL 1" || item.template_name === "THL 1")
                   {
                     /** [ Dynamically change svg barcode ] */
                       if(uk_ratio_itf !='' && uk_ratio_itf.length === 14)
                       {
                        
                           jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)

                       }
                       else
                       { 
                           svgDoc.getElementById("barcode").innerHTML = '';
                       }
                   }
                   /** [ Dynamically change svg short description ] */
                   if(description != '')
                   {
                      svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                   }
                   else
                   {
                      svgDoc.getElementById("description").innerHTML = '';
                   }
              }
              else
              {
                 /**  [ if UK single template ] */
                 var orms_tpn = $('#uk_single_tpnd_1').val();
                 var case_size = $('#uk_single_case_size_1').val();
				 var uk_single_ean  = $('#uk_single_ean_1').val();
                 var description      = $('#tesco_description').val();
                 /** [ Dynamically change svg Uk single tpnd number ] */
                 if(orms_tpn != '')
                 {
                      svgDoc.getElementById("tpnd").innerHTML = orms_tpn;
                 }
                 else
                 {
                      svgDoc.getElementById("tpnd").innerHTML = '';
                 }
                 /** [ Dynamically change svg Uk single case size ] */
                 if(case_size != '')
                 {
                      svgDoc.getElementById("unit_per_case").innerHTML = case_size;
                 }
                 else
                 { 
                      svgDoc.getElementById("unit_per_case").innerHTML = '';
                 }
				  
			     if(item.template_name === "THL 3")
			     {
					   /** [ Dynamically change svg barcode ] */
					   if(uk_single_ean !='' && uk_single_ean.length === 13)
					   {

						   jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)

					   }
					   else
					   { 
                           svgDoc.getElementById("barcode").innerHTML = '';
					   }
			     }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/admin/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     success: function (data) {
                           /** [ Dynamically change svg short description ] */
                           if(description != '' && data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = (description.toUpperCase())+' '+data.size_chart[0].size;
                           }
                           else if(data.size_chart.length != 0)
                           {
                              svgDoc.getElementById("description").innerHTML = data.size_chart[0].size; 
                           }
                           else if(description != '')
                           {
                              svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                           }
                           else
                           {
                              svgDoc.getElementById("description").innerHTML = '';
                           }
                     },
                     error: function (data) {
                        console.error(data);
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
                 }
                 else
                 {
                     /** [ Dynamically change svg short description ] */
                     if(description != '')
                     {
                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                     }
                     else
                     {
                        svgDoc.getElementById("description").innerHTML = '';
                     }
                 }
              }  
           }
        } 
}

/**
 * [setTescoOrder for storing tesco manual orders]
 * @author <praveen>
 */
function setTescoOrder()
{
    try {
        var zone   =  localStorage.getItem("zone");
        var type   =  localStorage.getItem("template type");
        var data   =  JSON.parse(localStorage.getItem("data"));

        if(zone === "CE")
        {
            if(type === "ratio")
            {
                if(data[0].template_name === 'THL 1')
                {
                    /** Function to store CE ratio THL1 template */
                    storeCERatioTHL1();
                }
                else
                {
                    /** Function to store CE ratio TCL1, TCL2, TSL1 template */
                    storeCERatioTCL1TCL2TSL1();
                }
                
            }
            else
            {
               if(data[0].template_name === 'THL 3')
                {
                    /** Function to store CE single THL3 template */
                    storeCESingleTHL3();
                }
                else
                {
                    /** Function to store CE single TCL4, TSL3 template */
                    storeCESingleTCL4TSL3();
                }
            }
        }
        else
        {
            if(type === "ratio")
            {
                if(data[0].template_name === 'THL 1')
                {
                    /** Function to store UK ratio THL1 template */
                    storeUKRatioTHL1();
                }
                else
                {
                    /** Function to store UK ratio TCL1, TCL2, TSL1 template */
                    storeUKRatioTCL1TCL2TSL1();
                }
                
            }
            else
            {
               if(data[0].template_name === 'THL 3')
                {
                    /** Function to store UK single THL3 template */
                    storeUKSingleTHL3();
                }
                else
                {
                    /** Function to store UK single TCL4, TSL3 template */
                    storeUKSingleTCL4TSL3();
                }
            }
        }
        
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}

/**
 * [ tescoOrderCommonData  function for getting tesco order common values ( Independent of zone and template type ) .Creating global variables ]
 * @return {[type]} [ Common data ]
 * @author <praveen>
 */
function tescoOrderCommonData()
{
    po_number         =  $('#tesco_po_number').val();
    uk_style          =  $('#tesco_uk_style').val();
    ce_style          =  $('#tesco_ce_style').val();
    season_code       =  $('#tesco_season_code').val();
    description       =  $('#tesco_description').val();
    eqos              =  $('#tesco_eqos').val();
    manufacture_date  =  $('#tesco_manufacture_date').val();
    csrf_token        =  $('meta[name="csrf-token"]').attr('content');
    department        =  $("#tesco_department option:selected").val();
    brand             =  $("#tesco_brand option:selected").val();
    supplier          =  $("#tesco_supplier option:selected").val();
    rfid              =  $("#tesco_rfid option:selected").val() === "yes"?1:0;
    tag_at_source     =  $("#tesco_tag_at_source option:selected").val() === "yes"?1:0;
    zone              =  localStorage.getItem("zone");
    type              =  localStorage.getItem("template type");
    data              =  JSON.parse(localStorage.getItem("data"));
    
}
/**
 * [storeCERatioTCL1TCL2TSL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */

function storeCERatioTCL1TCL2TSL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var orms_tpn          = [];
        var itf_code          = [];
        var cases_per_carton  = [];
        var count_TCL1        = [];
        var count_TCL2        = [];
        var count_TSL1        = [];

        /** [ Create array of ce ratio orms tpn ] */
        $('.ce_ratio_orms_tpn').each(function(i, item)
        {
            orms_tpn[i]    = $(item).val();
            
        });
        orms_tpn   = orms_tpn.join(',');
        /** [ Create array of ce ratio itf barcode ] */
        $('.ce_ratio_itf').each(function(i, item)
        {
            itf_code[i]    = $(item).val();
            
        });
        itf_code = itf_code.join(',');
        /** [ Create array of ce ratio case per carton ] */
        $('.ce_ratio_cpc').each(function(i, item)
        {
            cases_per_carton[i]    = $(item).val();
            
        });
        cases_per_carton = cases_per_carton.join(',');
        /** [ Create array of ce ratio tcl1 count ] */
        $('.count_TCL1').each(function(i, item)
        {
            count_TCL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');
        /** [ Create array of ce ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');
        /** [ Create array of ce ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-ce-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.error(data);
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
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}
/**
 * [storeCERatioTHL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */

function storeCERatioTHL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var orms_tpn          = [];
        var itf_code          = [];
        var cases_per_carton  = [];
        var count_THL1        = [];

        /** [ Create array of ce ratio orms tpn ] */
        $('.ce_ratio_orms_tpn').each(function(i, item)
        {
            orms_tpn[i]    = $(item).val();
            
        });
        orms_tpn   = orms_tpn.join(',');
        /** [ Create array of ce ratio itf barcode ] */
        $('.ce_ratio_itf').each(function(i, item)
        {
            itf_code[i]    = $(item).val();
            
        });
        itf_code = itf_code.join(',');
        /** [ Create array of ce ratio case per carton ] */
        $('.ce_ratio_cpc').each(function(i, item)
        {
            cases_per_carton[i]    = $(item).val();
            
        });
        cases_per_carton = cases_per_carton.join(',');
        /** [ Create array of ce ratio tcl1 count ] */
        $('.count_THL1').each(function(i, item)
        {
            count_THL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_THL1 = count_THL1.join(',');
        
        $.ajax({
                 url: base_url + "/admin/tesco/set-ce-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_THL1:count_THL1,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeCESingleTCL4TSL3 for storing tesco CE single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */

function storeCESingleTCL4TSL3()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var orms_tpn          = [];
        var ean_code          = [];
        var case_size         = [];
        var size              = [];
        var count_TCL4        = [];
        var count_TSL3        = [];

        /** [ Create array of ce single orms tpn ] */
        $('.ce_single_orms_tpn').each(function(i, item)
        {
            orms_tpn[i]    = $(item).val();
            
        });
        orms_tpn   = orms_tpn.join(',');
        /** [ Create array of ce single ean barcode ] */
        $('.ce_single_ean').each(function(i, item)
        {
            ean_code[i]    = $(item).val();
            
        });
        ean_code = ean_code.join(',');
        /** [ Create array of ce single case size ] */
        $('.ce_single_case_size').each(function(i, item)
        {
            case_size[i]    = $(item).val();
            
        });
        case_size = case_size.join(',');
        /** [ Create array of ce single tcl1 count ] */
        $('.ce_single_size').each(function(i, item)
        {
            size[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        size = size.join(',');
        /** [ Create array of ce single tcl2 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');
        /** [ Create array of ce single tsl1 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-ce-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeCESingleTHL3 for storing tesco CE single THL3 Template manual orders]
 * @author <praveen>
 */

function storeCESingleTHL3()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var orms_tpn          = [];
        var ean_code          = [];
        var case_size         = [];
        var size              = [];
        var count_THL3        = [];

        /** [ Create array of ce single orms tpn ] */
        $('.ce_single_orms_tpn').each(function(i, item)
        {
            orms_tpn[i]    = $(item).val();
            
        });
        orms_tpn   = orms_tpn.join(',');
        /** [ Create array of ce single ean barcode ] */
        $('.ce_single_ean').each(function(i, item)
        {
            ean_code[i]    = $(item).val();
            
        });
        ean_code = ean_code.join(',');
        /** [ Create array of ce single case size ] */
        $('.ce_single_case_size').each(function(i, item)
        {
            case_size[i]    = $(item).val();
            
        });
        case_size = case_size.join(',');
        /** [ Create array of ce single tcl1 count ] */
        $('.ce_single_size').each(function(i, item)
        {
            size[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        size = size.join(',');
        /** [ Create array of ce single thl3 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_THL3 = count_THL3.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-ce-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeUKRatioTCL1TCL2TSL1 for storing tesco UK ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */

function storeUKRatioTCL1TCL2TSL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var tpnd              = [];
        var itf_code          = [];
        var units_per_carton  = [];
        var count_TCL1        = [];
        var count_TCL2        = [];
        var count_TSL1        = [];

        /** [ Create array of uk ratio orms tpn ] */
        $('.uk_ratio_tpnd').each(function(i, item)
        {
            tpnd[i]    = $(item).val();
            
        });
        tpnd   = tpnd.join(',');
        /** [ Create array of uk ratio itf barcode ] */
        $('.uk_ratio_itf').each(function(i, item)
        {
            itf_code[i]    = $(item).val();
            
        });
        itf_code = itf_code.join(',');
        /** [ Create array of uk ratio units per carton ] */
        $('.uk_ratio_upc').each(function(i, item)
        {
            units_per_carton[i]    = $(item).val();
            
        });
        units_per_carton = units_per_carton.join(',');
        /** [ Create array of uk ratio tcl1 count ] */
        $('.count_TCL1').each(function(i, item)
        {
            count_TCL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');
        /** [ Create array of uk ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');
        /** [ Create array of uk ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-uk-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeUKRatioTHL1 for storing tesco UK ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */

function storeUKRatioTHL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var tpnd              = [];
        var itf_code          = [];
        var units_per_carton  = [];
        var count_THL1        = [];

        /** [ Create array of uk ratio orms tpn ] */
        $('.uk_ratio_tpnd').each(function(i, item)
        {
            tpnd[i]    = $(item).val();
            
        });
        tpnd   = tpnd.join(',');
        /** [ Create array of uk ratio itf barcode ] */
        $('.uk_ratio_itf').each(function(i, item)
        {
            itf_code[i]    = $(item).val();
            
        });
        itf_code = itf_code.join(',');
        /** [ Create array of uk ratio units per carton ] */
        $('.uk_ratio_upc').each(function(i, item)
        {
            units_per_carton[i]    = $(item).val();
            
        });
        units_per_carton = units_per_carton.join(',');
        /** [ Create array of uk ratio tcl1 count ] */
        $('.count_THL1').each(function(i, item)
        {
            count_THL1[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_THL1 = count_THL1.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-uk-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_THL1:count_THL1,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeUKSingleTCL4TSL3 for storing tesco UK single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */

function storeUKSingleTCL4TSL3()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var tpnd          = [];
        var ean_code          = [];
        var case_size         = [];
        var size              = [];
        var count_TCL4        = [];
        var count_TSL3        = [];

        /** [ Create array of uk single orms tpn ] */
        $('.uk_single_tpnd').each(function(i, item)
        {
            tpnd[i]    = $(item).val();
            
        });
        tpnd   = tpnd.join(',');
        /** [ Create array of uk single ean barcode ] */
        $('.uk_single_ean').each(function(i, item)
        {
            ean_code[i]    = $(item).val();
            
        });
        ean_code = ean_code.join(',');
        /** [ Create array of uk single case size ] */
        $('.uk_single_case_size').each(function(i, item)
        {
            case_size[i]    = $(item).val();
            
        });
        case_size = case_size.join(',');
        /** [ Create array of uk single tcl1 count ] */
        $('.uk_single_size').each(function(i, item)
        {
            size[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        size = size.join(',');
        /** [ Create array of uk single tcl2 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');
        /** [ Create array of uk single tsl1 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-uk-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
 * [storeUKSingleTHL3 for storing tesco UK single THL3 Template manual orders]
 * @author <praveen>
 */

function storeUKSingleTHL3()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var tpnd          = [];
        var ean_code          = [];
        var case_size         = [];
        var size              = [];
        var count_THL3        = [];

        /** [ Create array of uk single orms tpn ] */
        $('.uk_single_tpnd').each(function(i, item)
        {
            tpnd[i]    = $(item).val();
            
        });
        tpnd   = tpnd.join(',');
        /** [ Create array of uk single ean barcode ] */
        $('.uk_single_ean').each(function(i, item)
        {
            ean_code[i]    = $(item).val();
            
        });
        ean_code = ean_code.join(',');
        /** [ Create array of uk single case size ] */
        $('.uk_single_case_size').each(function(i, item)
        {
            case_size[i]    = $(item).val();
            
        });
        case_size = case_size.join(',');
        /** [ Create array of uk single tcl1 count ] */
        $('.uk_single_size').each(function(i, item)
        {
            size[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        size = size.join(',');
        /** [ Create array of uk single tcl2 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''?0:$(item).val();
            
        });
        count_THL3 = count_THL3.join(',');
        $.ajax({
                 url: base_url + "/admin/tesco/set-uk-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data
                 },
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('.snackbar_span').html('Your order has been placed successfully');
                       showSnackbar();
                       /** Po upload modal for combined po upload modal */
                       var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid"><div class="row"><div class="col-md-12"> <br> <strong>Select Artworks :</strong>';
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required=""> '+index+' </label><div class="help-block with-errors"></div></div>';
                       });
                      html += '</div><hr><div class="row"><div class="col-md-12"><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div><div class="col-md-6">';
                      /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" required="" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th"></span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="form-group has-feedback"> <label for="combined_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="combined_delivery_date"> <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span></div></div><div class="help-block with-errors"></div></div></div>';
                       }
                       html += '</div></div></div></div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for combined po upload modal tab */
                       $('#combined').html(html);

                       /** Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data"><div class="container-fluid">';

                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row"><div class="col-md-12"><div class="form-check form-group has-feedback"> <label class="form-check-label"> <input class="form-check-input" type="checkbox" value="'+value+'" name="template" id="template" required="">'+index+'</label><div class="help-block with-errors"></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_number" class="form_label">PO Number</label> <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text"><div class="help-block with-errors"></div></div></div><div class="col-md-6"><div class="form-group has-feedback"> <label for="po_file">Purchase Order File</label> <span class="control-fileupload"> <label for="file">Choose a file :</label> <input type="file" id="file" class="po-fileupload form-control center-block" required=""> </span><div class="help-block with-errors"></div></div></div>';
                       /** [ Delivery Date mandatory or not for particular customer] */
                       if(data.delivery_date_required === 1)
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" required="" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       else
                       {
                         html +='<div class="col-md-6"><div class="form-group has-feedback"> <label for="single_delivery_date">Delivery Date</label><div class="input-group date" data-provide="datepicker" id="datePicker1"> <input value="" id="single_delivery_date" name="single_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date"><div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span ></div ></div ><div class="help-block with-errors"></div></div></div>';
                       }
                       html +='</div></div><hr>';
                       });
                       html +='</div><div class="modal-footer"> <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="location.reload()">Upload Later</button> <button class="btn btn-primary btn-sm" type="submit">Upload</button></div></form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');
                       $('.submit-btn').addClass('disabled');
                 },
                 error: function (data) {
                    console.log(data);
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}
/**
* showSnackbar function
* @author    <praveen>
*/
function showSnackbar() {
try{
  /** [ Get the snackbar DIV ] */
  var x = document.getElementById("snackbar")

  /** [ Add the "show" class to DIV ] */
  x.className = "show";

  /** [  After 3 seconds, remove the show class from DIV ] */
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  } catch (e) {
   console.log("Error:"+e)
 }
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodetesco(code, id, svgDoc) {

	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 2.50,
		height: 100,
		fontSize: 30,
		flat:true
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

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg
	console.log(img.src);

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

	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.45,
		height: 125,
		fontSize: 35,
		flat:true
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

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg
	console.log(img.src);

	//remove temporary canvas
	canvasTmp.remove();
	
	svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function openmodaltesco(modal,i)
{
    $('#'+modal).modal("show");
	var data = JSON.parse(localStorage.getItem("data"));
	
	var zone = localStorage.getItem("zone");
    var type = localStorage.getItem("template type");
	/**  [ Dynamically change all svg templates ] */
	var mySVG = document.getElementById("tesco_svg_modal"+i);
	//var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		dynamicSVGTemplatescard("tesco_svg_modal"+i,data[i]);  
	}, false);
	
}