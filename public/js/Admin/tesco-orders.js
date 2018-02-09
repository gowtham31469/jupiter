/**
 * This Function is used to show tesco orders page and template selection.
 * @author Praveen
 * @return View
 */

function tescoOrders() {
    $('.main-content').html('<div class="loader"></div>');
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
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
                $('.main-content').html('<div class="container-fluid"> <div class="row"> <div class="col-md-4"> <div class="card" > <h4>Create Order</h4> <hr/> <form id="create_order_form" data-toggle="validator" role="form"  method="post" enctype="multipart/form-data"><div class="form-group has-feedback"><label for="order_type" class="form_label">Order type</label><select class="form-control order_type selectpicker" name="order_type" id="order_type" required=""><option value="">Select Order Type</option><option value="1">PO Upload</option><option value="2">Manual Entry</option></select> <div class="help-block with-errors"></div></div> <div class="form-group"><label for="template" class="form_label">Select Template</label><input class="search-input form-control" placeholder="Search for Awesomeness"><div class="template-error"></div></div><div id="jstree"></div><div class="form-group has-feedback"> <div class="po-upload hidden"> <div class="form-group files" id="single_file "><label>Upload PO</label><input type="file" name="purchase_order" id="po_upload" required/><div class="help-block with-errors"></div></div></div></div><button class="btn btn-primary btn-block ">Create Order</button></form></div></div><div class="col-md-8 "> <div class="card " style="height:calc(100vh - 100px); "> <h4>Template Preview</h4> <hr/> <div class="row "> <div class="loader "></div><div class="viewDiv "><img style="height:150px;object-fit:cover;margin-top: 122px; " src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png "></div><div class="descriptionDiv "></div></div></div></div></div></div>');

                /** [ Search  functionality foe jstree nodes ] */
                $(".search-input").keyup(function () {
                    var searchString = $(this).val();
                    $('#jstree').jstree('search', searchString);
                });

                /** [ Load selectpicker ] */
                $('.selectpicker').selectpicker();

                /** [ Hide/Show PO upload div, PO number search based on order type selected ] */
                $(".order_type").change(function () {
                    var selectedType = $(".order_type option:selected").val();
                    if (selectedType === '1') {
                        $(".po-upload").removeClass("hidden");
                        //$(".po-number-search").show();
                        $('#po_upload').prop('required',true)
                    } else {
                        $(".po-upload").addClass("hidden");
                        //$(".po-number-search").hide();
                        $('#po_upload').prop('required',false)
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
            $(".loader").css('margin-top', '0%');
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

function createOrders() {
    try {
        /* [ Get values from local storage ] */
        var template_id = localStorage.getItem("templates");
        var zone        = localStorage.getItem("zone");
        var type        = localStorage.getItem("template type");

        $('.top-content').html('<span class="pull-right">Please Note : If any select list does not contain required information, please contact Administrator</span><h3 class="content-heading" id="random_order_id">Order details for : JGAM_1517'+Math.floor(Math.random() * 1000000000)+'</h3>');

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
                     var html = '<ul class="nav nav-tabs"><li class="active"><a data-toggle="tab" href="#stickers">Stickers</a></li><li><a data-toggle="tab" href="#labels">Labels</a></li></ul><div class="tab-content"><div id="stickers" class="tab-pane fade in active"><br><form method="POST" id="create_tesco_order_form" data-toggle="validator" role="form" novalidate="true"><div class="col-md-3"><div class="form-group has-feedback"><label for="po_number">PO Number</label> <input type="text" class="form-control" name="po_number" id="po_number" required=""  placeholder="Enter PO. Number"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="uk_style">UK Style Ref.</label> <input type="text" class="form-control" name="uk_style" id="uk_style"  placeholder="Enter UK Style Ref." required=""><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="ce_style">CE Style Ref.</label> <input type="text" class="form-control" name="ce_style" id="ce_style" placeholder="Enter CE Style Ref." required=""><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="season_code">Season Code</label> <input type="text" class="form-control" name="season_code" id="season_code" required=""  placeholder="Enter Season Code"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="description">Short Description</label> <input type="text" class="form-control" name="description" id="description" required=""  placeholder="Enter Description"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="eqos">EQOS Site Code</label> <input type="text" class="form-control" name="eqos" id="eqos" required=""  placeholder="Enter EQOS Site Code"><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"> <label for="manufacture_date">Manufactured Date</label><div class="input-group manufacture_date date" data-provide="datepicker" id="datePicker1"> <input value="" id="manufacture_date" name="manufacture_date" type="text" class="form-control" required="" placeholder="Select Manufactured Date"> <div class="input-group-addon" > <span class="glyphicon glyphicon-th" > </span > </div > </div ><div class="help-block with-errors"></div></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="department">Department</label><select class="selectpicker" data-live-search="true" id="department" name="department" required="" ><option value="">Select Department</option>';

                      /** [ Show dynamic department selectbox ] */
                      $.each(data.tesco_department, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.department + '</option>';
                      });

                     html +='</select></div></div><div class="clearfix"></div><div class="col-md-3"> <div class="form-group has-feedback"><label for="brand">Brand</label> <select class="selectpicker" data-live-search="true" id="brand" name="brand" required="" ><option value="">Select Brand</option>';

                       /** [ Show dynamic brand selectbox ] */
                       $.each(data.tesco_brand, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.brand + '</option>';
                       });  

                     html +='</select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="supplier">Supplier</label> <select class="selectpicker" data-live-search="true" id="supplier" name="supplier" required="" ><option value="">Select Supplier</option>';

                       /** [ Show dynamic supplier selectbox ] */
                       $.each(data.tesco_suppliers, function(index, item) {
                        html += '<option value="'+ item.id +'">'+ item.supplier_code +' : '+ item.supplier_name + '</option>';
                        });  

                     html +='</select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="rfid">RFID Compliant</label> <select class="selectpicker" id="rfid" name="rfid" required=""><option value="">Select RFID Compliant</option><option value="yes">Yes</option><option value="no">No</option> </select></div></div><div class="col-md-3"><div class="form-group has-feedback"><label for="tag_at_source">Tag At Source</label> <select class="selectpicker" id="tag_at_source" name="tag_at_source" required="" ><option value="">Select Tag At Source</option><option value="yes">Yes</option><option value="no">No</option> </select></div></div>';
                     if(zone === "UK")
                     {
                        /**  [ Dynamic quantity table for UK ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">TPND</th><th rowspan="2">ITF Barcode</th><th rowspan="2">Units per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '</tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr></thead><tbody><tr><td>1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd" name="uk_ratio_tpnd" placeholder="Enter TPND"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf" name="uk_ratio_itf" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc" name="uk_ratio_upc" placeholder="Enter Units Per Carton"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" onkeypress="return isNumber(event)" id="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '</tr></tbody></table></div><hr></div>';

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
                             html += '</tr></thead><tbody id="table_row"><tr><td class="slno">1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_1" name="uk_single_tpnd_1" required="" placeholder="Enter TPND"> <div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_ean" required="" onkeypress="return isNumber(event)" id="uk_single_ean_1" name="uk_single_ean_1" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeypress="return isNumber(event)" id="uk_single_case_size_1" name="uk_single_case_size_1" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_1" name="uk_single_size_1"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_1" name="uk_single_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                     }
                     else
                     {  
                        /**  [ Dynamic quantity table for CE ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div"><div class="row"><div class="col-md-12"><hr><h4>Order Quantity</h4></div></div><div class="table-responsive qty-table"><table class="table table-bordered text-center"><thead><tr><th rowspan="2">Line No.</th><th rowspan="2">ORMS TPN</th><th rowspan="2">ITF Barcode</th><th rowspan="2">Cases per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '</tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr></thead><tbody><tr><td>1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" id="ce_ratio_orms_tpn" name="ce_ratio_orms_tpn" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" id="ce_ratio_itf" name="ce_ratio_itf" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" required="" id="ce_ratio_cpc" name="ce_ratio_cpc" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" onkeypress="return isNumber(event)" id="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '</tr></tbody></table></div><hr></div>';

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
                             html += '</tr></thead><tbody id="table_row"><tr class="slno"><td>1</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_1" name="ce_single_orms_tpn_1" required="" placeholder="Enter ORMS TPN"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_1" name="ce_single_ean_1" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_1" name="ce_single_case_size_1" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_1" name="ce_single_size_1" required="" placeholder="Enter Size"><div class="help-block with-errors"></div></div></td>';
                              $.each(data.templates, function(i, item){
                               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_count" onkeypress="return isNumber(event)" id="ce_single_count_'+item.template_name.replace(' ', '')+'_1" name="ce_single_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
                             });
                              html += '<td></td></tr></tbody></table></div><hr></div>';

                        }
                     }
                    
                      html += '<h4>Order Preview</h4><div class="row">';
                      $.each(data.templates, function(i, item){
                       html += '<div class="col-md-4"><div class="card add-card"><div class="card-title"><h5><b>'+item.template_name+'</b></h5></div><div class="row"><div class="col-md-8 col-md-offset-2"><div class="img_artwork"> <object style="width:50%;border: 2px #333 solid;" id="svg2" class="center-block svg2" data="' + item.template_file + '" type="image/svg+xml"></object></div></div></div></div></div>';
                      });
                      

                      html += '</div><div class="bottom-bar"><div class="modal-footer"><span class="pagename pull-left">Please save or submit changes before closing page.</span><button class="btn btn-xs btn-warning" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button><button class="btn btn-xs btn-danger" type="button" onclick="location.reload()"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button></div></div></div><div id="labels" class="tab-pane fade"> <hr>Coming soon </div></div></div></div>';
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

                      /** [ bootstrap validator for create tesco order form ] **/
                        $('#create_tesco_order_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) {

                            }
                            else 
                            {
                                e.preventDefault();
                                alert('valid')
                            }
                        })
                        $(".card").css("margin-bottom", "80px");
                        $('.selectpicker').selectpicker();

                        /** [ show tooltip ] */
                        $('.delete-row').tooltip('show')
                        $('.delete-row').tooltip('toggle')
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
 *  addUKSingle function for adding dynamic table row for UK single templates 
 * @author <praveen>
 * 
 */
function addUKSingle(rem,e,templates) {
try{
         e.preventDefault(); 
         /** [ Get template details from localstorage ] */
         var data = JSON.parse(localStorage.getItem("data"));
         var a = [] 
         /** [ Get data-id of add button ] */
         var id = $(rem).attr("data-id"); 
         var val = parseInt(id) + 1; 
         var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_'+val+'" name="uk_single_tpnd_'+val+'" required=""  placeholder="Enter TPND"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_ean" required="" onkeypress="return isNumber(event)" id="uk_single_ean_'+val+'" name="uk_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeypress="return isNumber(event)" id="uk_single_case_size_'+val+'" name="uk_single_case_size_'+val+'" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_'+val+'" name="uk_single_size_'+val+'"><div class="help-block with-errors"></div></div></td>';
             $.each(data, function(i, item){
               html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
             });
          html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this)"></i></a></td></tr>';
         $('#table_row').append(html);

         /** [ Refresh boostrap form-validator ]  */
         $('#create_tesco_order_form').validator('update'); 

          /** [ Update data-id of add button ] */
         $(rem).attr("data-id", val); 

         /** [ Call setSerialNumber function ] */
         setSerialNumber()

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
                 async: true,
                 cache: false,
                 contentType: false,
                 processData: false,
                 beforeSend: function () {
                     $(".loader").show();
                     $(".loader").css('margin-top','12%');
                 },
                 success: function (data) {
                    //Call the parse method which resides on parser.js
                     parseXML(data);
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
                      $(".loader").css('margin-top','0%');
                 }
             })
        
    } catch(e) {
        // error statements
        console.log("Error:"+e)
    }
}


/**
  * Removes row from order quantity table
  *
  * @author <praveen>
  */
function removeRow(rem) {
try{
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
 *  addCESingle function for adding dynamic table row for CE single templates 
 * @author <praveen>
 * 
 */
function addCESingle(rem,e,templates) {
try{
     e.preventDefault(); 
     /** [ Get template details from localstorage ] */
     var data = JSON.parse(localStorage.getItem("data"));
     var a = [] 
     /** [ Get data-id of add button ] */
     var id = $(rem).attr("data-id"); 
     var val = parseInt(id) + 1; 
     var html = '<tr><td class="slno">'+val+'</td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_'+val+'" name="ce_single_orms_tpn_'+val+'" required="" placeholder="Enter ORMS TPN"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" id="ce_single_ean_'+val+'" name="ce_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_'+val+'" name="ce_single_case_size_'+val+'" required="" placeholder="Enter Case Size"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_'+val+'" name="ce_single_size_'+val+'" required="" placeholder="Enter Size"><div class="help-block with-errors"></div></div></td>';
         $.each(data, function(i, item){
           html += '<td><div class="form-group has-feedback"> <input type="text" class="form-control input-sm ce_single_count" onkeypress="return isNumber(event)" id="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count"><div class="help-block with-errors"></div></div></td>';
         });
      html += '<td><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this)"></i></a></td></tr>';
     $('#table_row').append(html);

     /** [ Refresh boostrap form-validator ]  */
     $('#create_tesco_order_form').validator('update'); 

      /** [ Update data-id of add button ] */
     $(rem).attr("data-id", val); 

     /** [ Call setSerialNumber function ] */
     setSerialNumber()

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
         
   } catch (e) {
    /** [error statements] */
   console.log("Error:"+e)
 }
}
