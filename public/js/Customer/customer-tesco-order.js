/**
 * method to check the input value is a number or not
 * @param  input data
 * @return {Boolean}
 */
function isNumber(evt) {
    try {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    } catch (e) {
        console.log("Error:" + e)
    }
}

/**  
  * Validation for numeric values with dot
  * @param   evt 
  * @return {Boolean} 
  * @author <praveen>    
  */
 function isNumberKey(evt){
  try{
       var charCode = (evt.which) ? evt.which : event.keyCode

       if (charCode == 46)
       {
           var inputValue = $("#inputfield").val()
           if (inputValue.indexOf('.') < 1)
           {
               return true;
           }
           return false;
       }
       /** [ Checking key stroke values for numeric values ] */
       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
       {
           return false;
       }
       return true;
    } catch (e) {
     console.log("Error:"+e)
   }
  }

/**
 * This Function is used to show tesco orders page and template selection.
 * @author Praveen
 * @return View
 */
var globalOrderType = 0;
var barcode_checks = 0;
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
        url: base_url + "/customer/tesco/order-management/orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            
                var newObj = data.tesco_tcl1_orders.concat(data.tesco_tcl2_orders.concat(data.tesco_tcl4_orders.concat(data.tesco_thl1_orders.concat(data.tesco_thl3_orders.concat(data.tesco_tsl1_orders.concat(data.tesco_tsl3_orders))))));
                var uniqueNames = [];
                var artwork_collection = {};
                for(i = 0; i< newObj.length; i++) {
                    if(uniqueNames.indexOf(newObj[i].po_number) === -1) {
                        uniqueNames.push(newObj[i].po_number); 
                        artwork_collection[newObj[i].po_number] = newObj[i].template_id;     
                    } else {
                        var artwork_group = artwork_collection[newObj[i].po_number];
                        artwork_collection[newObj[i].po_number] = artwork_group+','+newObj[i].template_id;
                    }   
                }
                var options="<option value=''>Search Purchase Number</option>";
                for(i = 0; i< uniqueNames.length; i++){    
                     options+="<option data-tempid='"+artwork_collection[uniqueNames[i]]+"'>"+uniqueNames[i]+"</option>";
                }
                $('.main-content').html(`<div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <h4>Create Order</h4>
                                                        <hr/>
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item active"> <a class="nav-link active" data-toggle="tab" href="#viewOrder" role="tab">View Order</a> </li>
                                                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#newOrder" role="tab">New Order</a> </li>
                                                        </ul>
                                                        <div class="tab-content ">
                                                            <div class="tab-pane fade in active" id="viewOrder" role="tabpanel">
                                                                <div class="pad-top">
                                                                    <form id="view_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                                                        <div class="form-group has-feedback">
                                                                            <label for="order_type" class="form_label">Select PO Number</label>
                                                                            <select class="form-control po_number selectpicker" name="po_number" id="po_number" required data-live-search="true" onchange=loadTemplatePreview()>`+options+`</select>
                                                                            <div class="help-block with-errors"></div>
                                                                        </div>
                                                                        <button class="btn btn-primary btn-block ">View Order</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="newOrder" role="tabpanel">
                                                                <div class="pad-top">
                                                                    <form id="create_order_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">
                                                                        <div class="form-group has-feedback">
                                                                            <label for="order_type" class="form_label">Select Order Type</label>
                                                                            <select class="form-control order_type selectpicker" name="order_type" id="order_type" required="">
                                                                                <option value="">Select Order Type</option>
                                                                                <option value="1">PO Upload</option>
                                                                                <option value="2">Manual Entry</option>
                                                                            </select>
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
                                                    <div class="card " style="height:calc(100vh - 100px); ">
                                                        <h4>Template Preview</h4>
                                                        <hr/>
                                                        <div class="row ">
                                                            <div class="loader ">
                                                                <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>
                                                            </div>
                                                            <div class="templateDiv "><img style="height:150px;object-fit:cover;margin-top: 122px; " src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png "></div>
                                                            <div class="descriptionDiv "></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
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
                        $.ajax({
                            url: base_url + "/customer/tesco/template-view",
                            type: "Post",
                            dataType: "json",
                            data: {
                                template_id: template_id,
                                _token: csrf_token
                            },
                            tryCount : 0,
                            retryLimit : 2,
                            beforeSend: function () {
                                $(".loader").show();
                            },
                            success: function (data) {
                                if (data.length > 0) {
                                    $('.templateDiv').html('');
                                    $.each(data, function (index, value) {
                                        if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
                                            $('.templateDiv').append('<div class="col-md-4">\
                                                <object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object>\
                                                <div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;">\
                                                <label>Template Name : </label>\
                                                <p>' + value[0].template_name + '</p>\
                                                <label>Reference Number : </label>\
                                                <p>' + value[0].ref_no + '</p>\
                                                <label>Company Name : </label>\
                                                <p>' + value[0].company_name + '</p>\
                                                </div>\
                                                </div>')

                                        } else {
                                            $('.templateDiv').append('<div class="col-md-4">\
                                                <object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object>\
                                                <div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;">\
                                                <label>Template Name : </label>\
                                                <p>' + value[0].template_name + '</p>\
                                                <label>Reference Number : </label>\
                                                <p>' + value[0].ref_no + '</p>\
                                                <label>Company Name : </label>\
                                                <p>' + value[0].company_name + '</p>\
                                                </div>\
                                                </div>')

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
                            error: function(errorXHR, errorStatus, errorThrown) {
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
                                uploadPurchaseOrder(formData, localStorage.getItem("zone"));
                            } else {
                                createOrders();
                            }
                            
                        }
                        else
                        {
                            /**  [ Show validation error message ] **/
                            $('.template-error').html('<span style="color:#F44336">Please select a template in the list.</span>');
                        }
                    }
                });

                /** [ bootstrap validator for create tesco order form ] **/
                $('#view_order_form').validator().on('submit', function (e) {
                    if (e.isDefaultPrevented()) {

                    }
                    else {
                        e.preventDefault();
                        var po_num = $('#po_number').val();
                        viewOrder(po_num);  
                    }
                })

        },
        error: function(errorXHR, errorStatus, errorThrown) {
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

function createOrders(parsedObj = false, oType = false) {
    try {
        /* [ Get values from local storage ] */
        var template_id = localStorage.getItem("templates");
        var zone        = localStorage.getItem("zone");
        var type        = localStorage.getItem("template type");
        var word        = localStorage.getItem("templates").length === 1?'Artwork':'Artworks';
        $('.top-content').html('<div class="page-header" style="margin: 10px 0 20px;border-bottom: 2px solid #eee;"><h4><strong>Create Order For TESCO-'+zone+' '+localStorage.getItem("template_group")+'</strong></h4></div>');

         $.ajax({
                 url: base_url + "/customer/tesco/templates-details",
                 type: "get",
                 dataType:"json",
                 data: {template_id:template_id},
                 tryCount : 0,
                 retryLimit : 2,
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                     localStorage.setItem("data", JSON.stringify(data.templates));
                     var html = '<div class="loader">\
                                    <div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div>\
                                </div>\
                                <br>\
                                <form method="POST" id="create_tesco_order_form" data-toggle="validator" role="form" novalidate="true">\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_po_number">PO Number</label>\
                                            <input type="text" class="form-control" name="tesco_po_number" id="tesco_po_number" required="" placeholder="Enter PO. Number" onkeyup="dynamicSVGTemplates()" maxlength="9">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
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
                                            <input type="text" class="form-control" name="tesco_season_code" id="tesco_season_code" required="" placeholder="Enter Season Code" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <div class="form-group has-feedback">\
                                            <label for="tesco_description">Short Description</label>\
                                            <input type="text" class="form-control" name="tesco_description" id="tesco_description" required="" placeholder="Enter Description" maxlength="24" onkeyup="dynamicSVGTemplates()">\
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
                      $.each(data.tesco_department, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.department + '</option>';
                      });

                     html +='</select>\
                             </div>\
                             </div>\
                             <div class="clearfix"></div>\
                             <div class="col-md-3"> \
                               <div class="form-group has-feedback">\
                                 <label for="tesco_brand">Brand</label> \
                                   <select class="selectpicker" data-live-search="true" id="tesco_brand" name="tesco_brand" onchange="dynamicSVGTemplates()" required="">\
                                     <option value="">Select Brand</option>';

                       /** [ Show dynamic brand selectbox ] */
                       $.each(data.tesco_brand, function(index, item) {
                        html += '<option value="'+ item.id +'">' + item.brand + '</option>';
                       });  

                     html +='</select>\
                             </div>\
                             </div>\
                             <div class="col-md-3">\
                              <div class="form-group has-feedback">\
                               <label for="tesco_supplier">Supplier</label> \
                                <select class="selectpicker" data-live-search="true" id="tesco_supplier" name="tesco_supplier" required="" onchange="dynamicSVGTemplates()">';

                       /** [ Show dynamic supplier selectbox ] */
                       $.each(data.tesco_suppliers, function(index, item) {
                        html += '<option value="'+ item.id +'">'+ item.company_name + '</option>';
                        });  

                     html +='</select>\
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
                     if(zone === "UK")
                     {
                        /**  [ Dynamic quantity table for UK ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div">\
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
                                                    <th rowspan="2">ITF Barcode</th>\
                                                    <th rowspan="2">Units per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th>\
                                     </tr>\
                                     <tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });

                             html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_1" name="uk_ratio_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()" maxlength="9">\
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

                              $.each(data.templates, function(i, item){
                               html += '<td>\
                                            <div class="form-group has-feedback">\
                                                <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="uk_ratio_count_'+item.template_name.replace(' ', '')+'_1" maxlength="4" placeholder="Enter Count"  data-lastid="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </td>';
                             });
                              html += '<td></td>\
                                        </tr>\
                                        </tbody>\
                                        </table>\
                                        </div>\
                                        <hr>\
                                        </div>';

                        }
                        else
                        {
                            /**  [ Dynamic quantity table for UK single templates ] */
                            html +='<div class="qty-table-div">\
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
                                                        <th rowspan="2">EAN Barcode</th>\
                                                        <th rowspan="2">Case Size</th>\
                                                        <th rowspan="2">Size</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th>\
                                      </tr>\
                                      <tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_1" name="uk_single_tpnd_1" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()" maxlength="9">\
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
                              $.each(data.templates, function(i, item){
                               html += '<td>\
                                            <div class="form-group has-feedback">\
                                                <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_1" name="uk_single_count_'+item.template_name.replace(' ', '')+'_1" maxlength="4" placeholder="Enter Count"  data-lastid="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </td>';
                             });
                              html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       </table>\
                                       </div>\
                                       <hr></div>';

                        }
                     }
                     else
                     {  
                        /**  [ Dynamic quantity table for CE ratio templates ] */
                        if(type === 'ratio')
                        {
                            html +='<div class="qty-table-div">\
                                        <div class="row">\
                                            <div class="col-md-12">\
                                                <hr>\
                                                <h4>Order Quantity</h4>\
                                                <p>\
                                                    <button type="button" onclick="addCERatio(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
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
                                                        <th rowspan="2">Cases per carton</th>';
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th>\
                                      </tr><tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_1" name="ce_ratio_orms_tpn_1" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13">\
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
                              $.each(data.templates, function(i, item){
                               html += '<td>\
                                        <div class="form-group has-feedback"> \
                                        <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" name="ce_ratio_count_'+item.template_name.replace(' ', '')+'_1" maxlength="4" placeholder="Enter Count" data-lastid="">\
                                        <div class="help-block with-errors"></div>\
                                        </div>\
                                        </td>';
                             });
                              html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       </table>\
                                       </div>\
                                       <hr></div>';

                        }
                        else
                        {
                            /**  [ Dynamic quantity table for CE single templates ] */
                            html +='<div class="qty-table-div">\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <hr>\
                                            <h4>Order Quantity</h4>\
                                            <p>\
                                                <button type="button" onclick="addCESingle(this,event)" data-id="1" class="btn btn-default add-row-btn"><i class="fa fa-plus" aria-hidden="true"></i> Add Row</button>\
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
                             $.each(data.templates, function(i, item){
                               html += '<th colspan="1">'+item.template_name+'</th>';
                             });
                             html += '<th rowspan="2">Actions</th>\
                                      </tr>\
                                      <tr>';
                             $.each(data.templates, function(i, item){
                               html += '<th>Count</th>';
                             });
                             html += '</tr>\
                                        </thead>\
                                        <tbody id="table_row">\
                                            <tr>\
                                                <td class="slno">1</td>\
                                                <td>\
                                                    <div class="form-group has-feedback">\
                                                        <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_1" name="ce_single_orms_tpn_1" required="" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13">\
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
                              $.each(data.templates, function(i, item){
                               html += '<td>\
                                        <div class="form-group has-feedback"> \
                                        <input type="text" data-tname="'+item.template_name+'" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_single_count_'+item.template_name.replace(' ', '')+'_1" name="ce_single_count_'+item.template_name.replace(' ', '')+'_1" placeholder="Enter Count" maxlength="4" data-lastid="">\
                                        <div class="help-block with-errors"></div>\
                                        </div>\
                                        </td>';
                             });
                              html += '<td></td>\
                                       </tr>\
                                       </tbody>\
                                       </table>\
                                       </div>\
                                       <hr></div>';

                        }
                     }
                    
                      html += '<div class="invisible-div"></div><h4>Order Preview</h4><div class="row">';
                      $.each(data.templates, function(i, item){
                       html += '<div class="col-md-4">\
                                  <div class="card add-card">\
                                    <div class="card-title">\
                                       <p class="pull-right" onclick=openmodaltesco("'+item.template_name.replace(' ', '')+'","'+i+'") style="cursor:pointer">Click to view</p>\
                                       <h5><b>'+item.template_name+'</b></h5>\
                                    </div>\
                                    <div class="row">\
                                       <div class="col-md-8 col-md-offset-2">\
                                         <div class="tesco_artwork"> \
                                           <object style="border: 2px #333 solid;" id="tesco_svg'+i+'" class="center-block tesco_svg'+i+'" data="' + item.template_file + '" type="image/svg+xml"></object>\
                                           <div class="modal fade" id="'+item.template_name.replace(' ', '')+'" role="dialog">\
                                             <div class="modal-dialog">\
                                               <div class="modal-content">\
                                                 <div class="modal-header">\
                                                   <button type="button" class="close" data-dismiss="modal">&times;</button>\
                                                   <h4 class="modal-title">'+item.template_name+'</h4>\
                                                 </div>\
                                                 <div class="modal-body">\
                                                   <object style="border: 2px #333 solid;" id="tesco_svg_modal'+i+'" class="center-block tesco_svg'+i+'" data="' + item.template_file + '" type="image/svg+xml"></object>\
                                                 </div>\
                                                 <div class="modal-footer">\
                                                   <button type="button" class="btn btn-primary" onclick=printDivTesco("tesco_svg_modal'+i+'","'+item.template_name.replace(' ', '')+'")>Print</button>\
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
                                     <button class="btn btn-xs btn-success save-btn" type="button" onclick="saveTescoOrder()" data-insert="true"><i class="fa fa-edit" aria-hidden="true"></i> Save</button>\
                                        <button class="btn btn-xs btn-warning submit-btn" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>\
                                        <button class="btn btn-xs btn-danger" type="button" onclick="location.reload()"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>\
                                    </div>\
                                </div>\
                                </div>\
                                </div>';

                      $('.main-content').html(html).selectpicker('refresh');
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
                            if(oType) {
                                mapStoredValues(parsedObj);
                            } else {
                                renderParsedValues(parsedObj);
                            }
                            setTimeout(function(){  dynamicSVGTemplates(); }, 500);
                        }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
	//var success = false;
	var url = "";
	// window.open(base_url + "/customer/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/customer/tesco/print-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
        tryCount : 0,
        retryLimit : 2,
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
		error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
        },
	})
//	if (success) { //AND THIS CHANGED
//		window.open(url)
//	}
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
	//var success = false;
	var url = "";
	// window.open(base_url + "/customer/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/customer/tesco/print-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
        tryCount : 0,
        retryLimit : 2,
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
		error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
        },
	})
//	if (success) { //AND THIS CHANGED
//		window.open(url)
//	}
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
         var html = '<tr>\
                        <td class="slno">'+val+'</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_tpnd" required="" onkeypress="return isNumber(event)" id="uk_single_tpnd_'+val+'" name="uk_single_tpnd_'+val+'" required="" placeholder="Enter TPND" maxlength="9">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_ean" required="" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." id="uk_single_ean_'+val+'" name="uk_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_case_size" required="" onkeypress="return isNumber(event)" id="uk_single_case_size_'+val+'" name="uk_single_case_size_'+val+'" required="" placeholder="Enter Case Size" maxlength="4">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_single_size" required="" placeholder="Enter Size" id="uk_single_size_'+val+'" name="uk_single_size_'+val+'">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
             $.each(data, function(i, item){
               html += '<td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="uk_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count" maxlength="4" data-lastid="">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
             });
          html += '<td>\
                    <a href="#"><i class="fa fa-trash filterbtn options fa-button delete-row" title="Delete" data-placement="left" onclick="removeRow(this,event)"></i></a>\
                   </td>\
                   </tr>';
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
         var html = '<tr>\
                        <td class="slno">'+val+'</td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_tpnd" required="" onkeypress="return isNumber(event)" id="uk_ratio_tpnd_'+val+'" name="uk_ratio_tpnd_'+val+'" placeholder="Enter TPND" onkeyup="dynamicSVGTemplates()" maxlength="9">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_itf" required="" onkeypress="return isNumber(event)" id="uk_ratio_itf_'+val+'" name="uk_ratio_itf_'+val+'" onkeyup="dynamicSVGTemplates()" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." placeholder="Enter ITF Barcode">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>\
                        <td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm uk_ratio_upc" required="" onkeypress="return isNumber(event)" id="uk_ratio_upc_'+val+'" name="uk_ratio_upc_'+val+'" placeholder="Enter Units Per Carton" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                                <div class="help-block with-errors"></div>\
                            </div>\
                        </td>';
             $.each(data, function(i, item){
               html += '<td>\
                            <div class="form-group has-feedback">\
                                <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="uk_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="uk_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count" maxlength="4" data-lastid="">\
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
function  uploadPurchaseOrder(formData, zone) {
    try {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         $.ajax({
                 url: base_url + "/customer/tesco/po-upload",
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
                        if(zone === 'UK') {
                            //Call the parse method which resides on parser.js
                            parseUKPurchaseOrder(data, createOrders);
                        } else {
                            //Call the parse method which resides on parser.js
                            parseCEPurchaseOrder(data, createOrders);
                        }
                              
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
                 error: function(errorXHR, errorStatus, errorThrown) {
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
     var html = '<tr>\
                    <td class="slno">'+val+'</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_orms_tpn" onkeypress="return isNumber(event)" id="ce_single_orms_tpn_'+val+'" name="ce_single_orms_tpn_'+val+'" required="" placeholder="Enter ORMS TPN" maxlength="13">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_ean" onkeypress="return isNumber(event)" data-minlength="13" maxlength="13" data-required-error="Mandatory Field." data-minlength-error="Enter 13 digits." id="ce_single_ean_'+val+'" name="ce_single_ean_'+val+'" required="" placeholder="Enter EAN Barcode">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_case_size" onkeypress="return isNumber(event)" id="ce_single_case_size_'+val+'" name="ce_single_case_size_'+val+'" required="" placeholder="Enter Case Size" maxlength="4">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_single_size" id="ce_single_size_'+val+'" name="ce_single_size_'+val+'" required="" placeholder="Enter Size">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
         $.each(data, function(i, item){
           html += '<td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" data-tname="'+item.template_name+'" id="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="ce_single_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count" maxlength="4" data-lastid="">\
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
     var html = '<tr>\
                    <td class="slno">'+val+'</td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_orms_tpn" required="" id="ce_ratio_orms_tpn_'+val+'" name="ce_ratio_orms_tpn_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter ORMS TPN" onkeyup="dynamicSVGTemplates()" maxlength="13">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_itf" data-minlength="14" maxlength="14" data-required-error="Mandatory Field." data-minlength-error="Enter 14 digits." required="" id="ce_ratio_itf_'+val+'" name="ce_ratio_itf_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter ITF Barcode">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm ce_ratio_cpc" required="" id="ce_ratio_cpc_'+val+'" name="ce_ratio_cpc_'+val+'" onkeypress="return isNumber(event)" placeholder="Enter Cases Per Carton" onkeyup="dynamicSVGTemplates()" maxlength="4">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';
         $.each(data, function(i, item){
           html += '<td>\
                        <div class="form-group has-feedback">\
                            <input type="text" class="form-control input-sm count_'+item.template_name.replace(' ', '')+'" onkeypress="return isNumber(event)" id="ce_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" name="ce_ratio_count_'+item.template_name.replace(' ', '')+'_'+val+'" placeholder="Enter Count" maxlength="4" data-lastid="">\
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
                    var barcodecheck = checkjsbarcodeitf(ce_ratio_itf);
                    if(barcodecheck == "false")
                    {
                        barcode_checks++;
                        $('.barcode-error').html('');
                        /**  [ Show validation error message ] **/
                        $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                    }
                    else
                    {
                        barcode_checks = 0;
                        $('.barcode-error').html('');
                        jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
                    }
				  }
				  else
				  {
                     barcode_checks = 0;
                     $('.barcode-error').html('');
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
                        var barcodecheck = checkjsbarcodeean(ce_single_ean);
                        if(barcodecheck == "false")
                        {
                            barcode_checks++;
                            $('.barcode-error').html('');
                            /**  [ Show validation error message ] **/
                            $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                        }
                        else
                        {
                            barcode_checks = 0;
                            $('.barcode-error').html('');
                            jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
                        }
					  }
					  else
					  {
                         barcode_checks = 0;
                         $('.barcode-error').html('');
						 svgDoc.getElementById("barcode").innerHTML = '';
					  }
				 }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     tryCount : 0,
                     retryLimit : 2,
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
                     error: function(errorXHR, errorStatus, errorThrown) {
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
                    var barcodecheck = checkjsbarcodeitf(uk_ratio_itf);
                    if(barcodecheck == "false")
                    {
                        barcode_checks++;
                        $('.barcode-error').html('');
                        /**  [ Show validation error message ] **/
                        $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                    }
                    else
                    {
                        barcode_checks = 0;
                        $('.barcode-error').html('');
                        jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
                    }
					
				  }
				  else
				  {
                     barcode_checks = 0;
                     $('.barcode-error').html('');
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
                        var barcodecheck = checkjsbarcodeean(uk_single_ean);
                        if(barcodecheck == "false")
                        {
                            barcode_checks++;
                            $('.barcode-error').html('');
                            /**  [ Show validation error message ] **/
                            $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                        }
                        else
                        {
                            barcode_checks = 0;
                            $('.barcode-error').html('');
                            jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
                        }
					  }
					  else
					  {
                         barcode_checks = 0;
                         $('.barcode-error').html('');
						 svgDoc.getElementById("barcode").innerHTML = '';
					  }
				 }
                 var size  = $('#uk_single_size_1').val();
                 /** if size is not empty, Get equivalent uk size for entered size (single templates)*/
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     tryCount : 0,
                     retryLimit : 2,
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
                     error: function(errorXHR, errorStatus, errorThrown) {
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
              /** [Give font-size for department] */
              if(department.length < 17)
              {
                svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
              }
              else
              {
                svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
              }
              /** [ Dynamically change svg department] */
              svgDoc.getElementById("department").innerHTML = department.toUpperCase().replace('&', '&amp;');
           }  
           else
           { 
              svgDoc.getElementById("department").innerHTML = '';
           }
           /** [ Dynamically change svg brand ] */
           if($('#tesco_brand option:selected').val() != '')
           {
              /** [Give font-size for brand] */
              if(brand.length < 17)
              {
                svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
              }
              else
              {
                svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
              }
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
                            var barcodecheck = checkjsbarcodeitf(ce_ratio_itf);
                            if(barcodecheck == "false")
                            {
                                barcode_checks++;
                                $('.barcode-error').html('');
                                /**  [ Show validation error message ] **/
                                $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                            }
                            else
                            {
                                barcode_checks = 0;
                                $('.barcode-error').html('');
                                jsbarcodetesco(ce_ratio_itf, 'barcode', svgDoc)
                            }
                       }
                       else
                       { 
                         barcode_checks = 0;
                         $('.barcode-error').html('');
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
                            var barcodecheck = checkjsbarcodeean(ce_single_ean);
                            if(barcodecheck == "false")
                            {
                                barcode_checks++;
                                $('.barcode-error').html('');
                                /**  [ Show validation error message ] **/
                                $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                            }
                            else
                            {
                                barcode_checks = 0;
                                $('.barcode-error').html('');
                                jsbarcodetescoean(ce_single_ean, 'barcode', svgDoc)
                            }
					   }
					   else
					   { 
                          barcode_checks = 0;
                          $('.barcode-error').html('');
                          svgDoc.getElementById("barcode").innerHTML = '';
					   }
			     }
                 var size  = $('#ce_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     tryCount : 0,
                     retryLimit : 2,
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
                     error: function(errorXHR, errorStatus, errorThrown) {
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
                            var barcodecheck = checkjsbarcodeitf(uk_ratio_itf);
                            if(barcodecheck == "false")
                            {
                                barcode_checks++;
                                $('.barcode-error').html('');
                                /**  [ Show validation error message ] **/
                                $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                            }
                            else
                            {
                                barcode_checks = 0;
                                $('.barcode-error').html('');
                                jsbarcodetesco(uk_ratio_itf, 'barcode', svgDoc)
                            }
                       }
                       else
                       {   
                           barcode_checks = 0;
                           $('.barcode-error').html('');
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
                            var barcodecheck = checkjsbarcodeean(uk_single_ean);
                            if(barcodecheck == "false")
                            {
                                barcode_checks++;
                                $('.barcode-error').html('');
                                /**  [ Show validation error message ] **/
                                $('.barcode-error').html('<span style="color:#F44336">Enter a valid barcode.</span>');
                            }
                            else
                            {
                                barcode_checks = 0;
                                $('.barcode-error').html('');
                                jsbarcodetescoean(uk_single_ean, 'barcode', svgDoc)
                            }
					   }
					   else
					   { 
                           barcode_checks = 0;
                           $('.barcode-error').html('');
                           svgDoc.getElementById("barcode").innerHTML = '';
					   }
			     }
                 var size  = $('#uk_single_size_1').val();
                 /** if size is not empty, Get equivalent ce size for entered size (single templates) */
                 if(size != '')
                 {
                    $.ajax({
                     url: base_url + "/customer/tesco/get-tsl-size",
                     type: 'GET',
                     data: {size:size,zone:zone},
                     tryCount : 0,
                     retryLimit : 2,
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
                     error: function(errorXHR, errorStatus, errorThrown) {
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
 * [setTescoOrder for storing tesco manual orders(Submit order)]
 * @author <praveen>
 */
function setTescoOrder()
{
    try {
        var zone       =  localStorage.getItem("zone");
        var type       =  localStorage.getItem("template type");
        var data       =  JSON.parse(localStorage.getItem("data"));
        
        var count_TCL1 = [];
        var count_TCL2 = [];
        var count_TSL1 = [];
        var count_TCL4 = [];
        var count_TSL3 = [];
        var count_THL3 = [];
        var count_THL1 = [];
        var msg        = '';

        if(zone === "CE")
        {
            if(type === "ratio")
            {
                if(data[0].template_name === 'THL 1')
                {
                    /** [ Create array of ce ratio thl1 count ] */
                    $('.count_THL1').each(function(i, item)
                    {
                        count_THL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });

                    /** [ Create single array of atrworks, for validation ] */
                    var empty_count = 0;
                    var l           = count_THL1.length;
                    for(var i= 0; i< l; i++){
                        if(count_THL1[i] === 0 || count_THL1[i] === '0' || count_THL1[i] === '00' || count_THL1[i] === '000' || count_THL1[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_THL1 = count_THL1.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                             $('#countMissingModal').modal({
                                 backdrop: 'static',
                                 keyboard: false
                             })
                             $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if (empty_count > 0)
                            {
                                msg = '<li style="margin-bottom: 10px;"><strong>THL 1</strong></li>';
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeCERatioTHL1(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store CE ratio THL1 template */
                                storeCERatioTHL1();
                            }
                        }
                    }
                }
                else
                {
                    /** [ Create array of ce ratio tcl1 count ] */
                    $('.count_TCL1').each(function(i, item)
                    {
                        count_TCL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of ce ratio tcl2 count ] */
                    $('.count_TCL2').each(function(i, item)
                    {
                        count_TCL2[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of ce ratio tsl1 count ] */
                    $('.count_TSL1').each(function(i, item)
                    {
                        count_TSL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var templates_array = [];
                    templates_array     = count_TCL1.concat(count_TCL2).concat(count_TSL1);
                    var empty_count     = 0;
                    var l               = templates_array.length;
                    for(var i= 0; i< l; i++){
                        if(templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** Check If artwork count array contains atleast one '0' OR empty value **/
                    if (count_TCL1.includes('0') || count_TCL1.includes('00') || count_TCL1.includes('000') || count_TCL1.includes('0000') || count_TCL1.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 1</strong></li>';
                    }
                    if (count_TCL2.includes('0') || count_TCL2.includes('00') || count_TCL2.includes('000') || count_TCL2.includes('0000') || count_TCL2.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 2</strong></li>';
                    }
                    if (count_TSL1.includes('0') || count_TSL1.includes('00') || count_TSL1.includes('000') || count_TSL1.includes('0000') || count_TSL1.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TSL 1</strong></li>';
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_TCL1 = count_TCL1.join(',');
                    count_TCL2 = count_TCL2.join(',');
                    count_TSL1 = count_TSL1.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                             $('#countMissingModal').modal({
                                 backdrop: 'static',
                                 keyboard: false
                             })
                             $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if(empty_count > 0)
                            {
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeCERatioTCL1TCL2TSL1(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store CE ratio TCL1, TCL2, TSL1 template */
                                storeCERatioTCL1TCL2TSL1();
                            }
                        }
                    }
                    
                }
                
            }
            else
            {
               if(data[0].template_name === 'THL 3')
                {
                    /** [ Create array of ce single thl3 count ] */
                    $('.count_THL3').each(function(i, item)
                    {
                        count_THL3[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var empty_count = 0;
                    var l           = count_THL3.length;
                    for(var i= 0; i< l; i++){
                        if(count_THL3[i] === 0 || count_THL3[i] === '0' || count_THL3[i] === '00' || count_THL3[i] === '000' || count_THL3[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_THL3 = count_THL3.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                            $('#countMissingModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            })
                            $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if (empty_count > 0)
                            {
                                msg += '<li style="margin-bottom: 10px;"><strong>THL 3</strong></li>';
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeCESingleTHL3(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store CE single THL3 template */
                                storeCESingleTHL3();
                            }
                        }
                    }
                    
                }
                else
                {
                    /** [ Create array of ce single tcl4 count ] */
                    $('.count_TCL4').each(function(i, item)
                    {
                        count_TCL4[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of ce single tsl3 count ] */
                    $('.count_TSL3').each(function(i, item)
                    {
                        count_TSL3[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var templates_array = [];
                    templates_array     = count_TCL4.concat(count_TSL3);
                    var empty_count     = 0;
                    var l               = templates_array.length;
                    for(var i= 0; i< l; i++){
                        if(templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** Check If artwork count array contains atleast one '0' OR empty value **/
                    if (count_TCL4.includes('0') || count_TCL4.includes('00') || count_TCL4.includes('000') || count_TCL4.includes('0000') || count_TCL4.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 4</strong></li>';
                    }
                    if (count_TSL3.includes('0') || count_TSL3.includes('00') || count_TSL3.includes('000') || count_TSL3.includes('0000') || count_TSL3.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TSL 3</strong></li>';
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_TSL3 = count_TSL3.join(',');
                    count_TCL4 = count_TCL4.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                             $('#countMissingModal').modal({
                                 backdrop: 'static',
                                 keyboard: false
                             })
                             $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/

                            if(empty_count > 0)
                            {
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeCESingleTCL4TSL3(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store CE single TCL4, TSL3 template */
                                 storeCESingleTCL4TSL3();
                            }
                        }
                    }
                    
                }
            }
        }
        else
        {
            if(type === "ratio")
            {
                if(data[0].template_name === 'THL 1')
                {
                    /** [ Create array of uk ratio thl1 count ] */
                    $('.count_THL1').each(function(i, item)
                    {
                        count_THL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var empty_count = 0;
                    var l           = count_THL1.length;
                    for(var i= 0; i< l; i++){
                        if(count_THL1[i] === 0 || count_THL1[i] === '0' || count_THL1[i] === '00' || count_THL1[i] === '000' || count_THL1[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_THL1 = count_THL1.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                            $('#countMissingModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            })
                            $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if (empty_count > 0)
                            {
                                msg += '<li style="margin-bottom: 10px;"><strong>THL 1</strong></li>';
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeUKRatioTHL1(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store UK ratio THL1 template */
                                storeUKRatioTHL1();
                            }
                        }
                    }
                    
                }
                else
                {
                    /** [ Create array of uk ratio tcl1 count ] */
                    $('.count_TCL1').each(function(i, item)
                    {
                        count_TCL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of uk ratio tcl2 count ] */
                    $('.count_TCL2').each(function(i, item)
                    {
                        count_TCL2[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of uk ratio tsl1 count ] */
                    $('.count_TSL1').each(function(i, item)
                    {
                        count_TSL1[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var templates_array = [];
                    templates_array     = count_TCL1.concat(count_TCL2).concat(count_TSL1);
                    var empty_count     = 0;
                    var l               = templates_array.length;
                    for(var i= 0; i< l; i++){
                        if(templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** Check If artwork count array contains atleast one '0' OR empty value **/
                    if (count_TCL1.includes('0') || count_TCL1.includes('00') || count_TCL1.includes('000') || count_TCL1.includes('0000') || count_TCL1.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 1</strong></li>';
                    }
                    if (count_TCL2.includes('0') || count_TCL2.includes('00') || count_TCL2.includes('000') || count_TCL2.includes('0000') || count_TCL2.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 2</strong></li>';
                    }
                    if (count_TSL1.includes('0') || count_TSL1.includes('00') || count_TSL1.includes('000') || count_TSL1.includes('0000') || count_TSL1.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TSL 1</strong></li>';
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_TCL1 = count_TCL1.join(',');
                    count_TCL2 = count_TCL2.join(',');
                    count_TSL1 = count_TSL1.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                            $('#countMissingModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            })
                            $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if(empty_count > 0)
                            {
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeUKRatioTCL1TCL2TSL1(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store UK ratio TCL1, TCL2, TSL1 template */
                                storeUKRatioTCL1TCL2TSL1();
                            }
                        }
                    }
                    
                }
                
            }
            else
            {
               if(data[0].template_name === 'THL 3')
                {
                    /** [ Create array of uk single thl3 count ] */
                    $('.count_THL3').each(function(i, item)
                    {
                        count_THL3[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var empty_count = 0;
                    var l           = count_THL3.length;
                    for(var i= 0; i< l; i++){
                        if(count_THL3[i] === 0 || count_THL3[i] === '0' || count_THL3[i] === '00' || count_THL3[i] === '000' || count_THL3[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_THL3 = count_THL3.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                            $('#countMissingModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            })
                            $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
                            if (empty_count > 0)
                            {
                                msg += '<li style="margin-bottom: 10px;"><strong>THL 3</strong></li>';
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeUKSingleTHL3(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store UK single THL3 template */
                                storeUKSingleTHL3();
                            }
                        }
                    }
                    
                }
                else
                {
                    /** [ Create array of uk single tcl4 count ] */
                    $('.count_TCL4').each(function(i, item)
                    {
                        count_TCL4[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    

                    /** [ Create array of uk single tsl3 count ] */
                    $('.count_TSL3').each(function(i, item)
                    {
                        count_TSL3[i]    = $(item).val() === ''?0:$(item).val();
                        
                    });
                    
                    /** [ Create single array of atrworks, for validation ] */
                    var templates_array = [];
                    templates_array     = count_TCL4.concat(count_TSL3);
                    var empty_count     = 0;
                    var l               = templates_array.length;
                    for(var i= 0; i< l; i++){
                        if(templates_array[i] === 0 || templates_array[i] === '0' || templates_array[i] === '00' || templates_array[i] === '000' || templates_array[i] === '0000'){
                            empty_count += 1;
                        }
                    }

                    /** Check If artwork count array contains atleast one '0' OR empty value **/
                    if (count_TCL4.includes('0') || count_TCL4.includes('00') || count_TCL4.includes('000') || count_TCL4.includes('0000') || count_TCL4.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TCL 4</strong></li>';
                    }
                    if (count_TSL3.includes('0') || count_TSL3.includes('00') || count_TSL3.includes('000') || count_TSL3.includes('0000') || count_TSL3.includes(0))
                    {
                       msg += '<li style="margin-bottom: 10px;"><strong>TSL 3</strong></li>';
                    }

                    /** [ Join artworks count array using ' , '] */
                    count_TCL4 = count_TCL4.join(',');
                    count_TSL3 = count_TSL3.join(',');

                    /** [Check if valid barcode entered] */
                    if(barcode_checks === 0)
                    {
                        /** [ Check if count of empty quantity in the array is equal to array length ] */
                        if(empty_count === l)
                        {
                            /** [ Backdrop for bootstrap modal ] */
                             $('#countMissingModal').modal({
                                 backdrop: 'static',
                                 keyboard: false
                             })
                             $('#countMissingModal').modal('show');
                        }
                        else 
                        {
                            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/

                            if(empty_count > 0)
                            {
                                $('#body_msg').html(msg);
                                /** [ Backdrop for bootstrap modal ] */
                                 $('#confirmOrder').modal({
                                      backdrop: 'static',
                                      keyboard: false
                                 })
                                 $('#confirm_btn').attr('onclick', 'storeUKSingleTCL4TSL3(this);');
                                 $('#confirmOrder').modal('show');
                            }
                            else
                            {
                                /** Function to store UK single TCL4, TSL3 template */
                                 storeUKSingleTCL4TSL3();
                            }
                        }
                    }
                    
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
    rfid              =  $("#tesco_rfid option:selected").val() === "yes" ? 1 : 0;
    tag_at_source     =  $("#tesco_tag_at_source option:selected").val() === "yes" ? 1 : 0;
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl1_last_id      = $('#TCL1_id').val();
        var tcl2_last_id      = $('#TCL2_id').val();
        var tsl1_last_id      = $('#TSL1_id').val();

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
            count_TCL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');

        /** [ Create array of ce ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');

        /** [ Create array of ce ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-ce-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data,is_insert:is_insert,tcl1_last_id:tcl1_last_id,tcl2_last_id:tcl2_last_id,tsl1_last_id:tsl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {

                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }
                       
                       /** [ Show single po upload tab ,if order placed for single artwork] */
                       if(length === 1)
                       {
                         $('#combined_tab').hide();
                         /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                               <div class="container-fluid">\
                               <div class="template-error"></div>';
                           var i = 1;
                           $.each(data.last_insertions, function (index, value) { 
                            html+= '<div class="row">\
                                        <div class="col-md-12">\
                                            <div class="form-check form-group has-feedback">\
                                                <label class="form-check-label">\
                                                    <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label>\
                                                    <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="single_delivery_date">Delivery Date</label>\
                                                    <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                        <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                        <div class="input-group-addon"> \
                                                          <span class="glyphicon glyphicon-th"></span >\
                                                        </div >\
                                                    </div >\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       else
                       {
                           /** Buyer Po upload modal for combined po upload modal */
                           var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error1"></div>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <br> <strong>Select Artworks :</strong>';
                           $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox1" type="checkbox" value="'+value+'" name="'+index.replace(' ','')+'" id="'+index.replace(' ','')+'" style="margin-right: 10px;" data-name="'+index.replace(' ','')+'"> '+ index+' </label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>';
                           });
                           html += '</div>\
                                        <hr>\
                                        <div class="row">\
                                            <div class="col-md-12">\
                                                <div class="col-md-6">\
                                                    <div class="form-group has-feedback">\
                                                        <label for="po_number" class="form_label">PO Number</label>\
                                                        <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text">\
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
                           if(data.delivery_date_required === 1)
                           {
                             html +='<div class="form-group has-feedback">\
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
                           }
                           else
                           {
                             html +='<div class="form-group has-feedback">\
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
                                       </div>\
                                    </div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for combined po upload modal tab */
                           $('#combined').html(html);

                           /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error"></div>';
                           var i = 1;
                           $.each(data.last_insertions, function (index, value) { 
                            html+= '<div class="row">\
                                        <div class="col-md-12">\
                                            <div class="form-check form-group has-feedback">\
                                                <label class="form-check-label">\
                                                    <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label>\
                                                    <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="single_delivery_date">Delivery Date</label>\
                                                    <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                        <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                        <div class="input-group-addon"> \
                                                          <span class="glyphicon glyphicon-th"></span >\
                                                        </div >\
                                                    </div >\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div><hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }

                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })
                       $('#poUploadModal').modal('show');

                       /** Update bootstrap validator */
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();
                       

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }
                       $('#datePicker3').datepicker({
                            autoclose: true,
                            startDate: today,
                            format: 'yyyy-mm-dd',
                        })

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                        /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox1:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               var checkbox_clicked = 0;
                               /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Hide validation error, if atleast 2 artwork selected ] */
                                if(checkbox_clicked === 2)
                                {
                                    $('.template-error1').html('');
                                }
                           }
                           else
                           {
                                /** [ Clear all input fields values] */
                                $('input:not(.template-checkbox1)[id="'  + id + '"]').val("");
                                /* [ remove validation error from input field ] */
                                $('#upload_combined_po_form').validator('validate');
                           }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });

                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    $('.template-error1').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })

                        /** [ bootstrap validator for upload combined po form ] **/
                        $('#upload_combined_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                 /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 2 artwork not selected ] */
                                if(checkbox_clicked < 2)
                                {
                                    $('.template-error').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error1').html('<span style="color:#F44336">Please select atleast 2 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error1').html('');
                                    var formData = new FormData();
                                    var delivery_date = $('#combined_delivery_date').val();
                                    var po_number = $('#po_number').val();
                                    var po_file = $('#po_file').prop('files')[0];
                                    /** [ Create array of Templates selected ] */ 
                                    $('.template-checkbox1').each(function(i, item) 
                                    { 
                                        i += 1;
                                        if ($(this).is(':checked')) 
                                        { 
                                          formData.append('template_id_'+i,$(this).val());
                                          formData.append('template_'+i,$(this).data('name'));
                                        } 
                                    }); 
                                   
                                   formData.append('delivery_date',delivery_date);
                                   formData.append('po_number',po_number);
                                   formData.append('po_file',po_file);

                                   uploadCombinedPO(formData);
                                }
                            }

                        })

                        /** Clear combined tab validation errors */
                        $('#single_tab').on('click',function(){
                           $('.template-error1').html('');
                        });

                        /** Clear single tab validation errors */
                        $('#combined_tab').on('click',function(){
                           $('.template-error').html('');
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
 * [storeCERatioTHL1 for storing tesco CE ratio THL1 Templates manual orders]
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
        var is_insert         = $('.save-btn').data('insert');
        var thl1_last_id      = $('#THL1_id').val();

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
            count_THL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL1 = count_THL1.join(',');
        
        $.ajax({
                 url: base_url + "/customer/tesco/set-ce-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_THL1:count_THL1,templates:data,thl1_last_id:thl1_last_id,is_insert:is_insert
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {

                       $('#combined_tab').hide();
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** Buyer Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                             <div class="container-fluid">\
                               <div class="template-error"></div>';
                       var i = 1;
                       $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group  has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon"> \
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                                <hr>';
                        i++;
                       });
                       html +='</div>\
                                <div class="modal-footer">\
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                </div>\
                               </form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);

                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator */
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }

                        })
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl4_last_id      = $('#TCL4_id').val();
        var tsl3_last_id      = $('#TSL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of ce single tcl4 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');

        /** [ Create array of ce single tsl3 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-ce-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data,is_insert:is_insert,tcl4_last_id:tcl4_last_id,tsl3_last_id:tsl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** [ Show single po upload tab ,if order placed for single artwork] */
                       if(length === 1)
                       {
                         $('#combined_tab').hide()
                         /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data"><div class="container-fluid">\
                                <div class="template-error"></div>';
                           var i = 1;
                          $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon">\
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                   </div>\
                                   <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       else
                       {
                           /** Buyer Po upload modal for combined po upload modal */
                           var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error1"></div>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <br> <strong>Select Artworks :</strong>';
                           $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox1" type="checkbox" value="'+value+'" name="'+index.replace(' ','')+'" id="'+index.replace(' ','')+'" style="margin-right: 10px;" data-name="'+index.replace(' ','')+'"> '+ index+' </label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>';
                           });
                           html += '</div>\
                                    <hr>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label> \
                                                     <span class="control-fileupload">\
                                                       <label for="file">Choose a file :</label> \
                                                        <input type="file" id="po_file" name="po_file" class="po-file-upload form-control center-block" required="" accept="application/pdf">\
                                                     </span>\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">';
                          /** [ Delivery Date mandatory or not for particular customer] */
                           if(data.delivery_date_required === 1)
                           {
                             html +='<div class="form-group has-feedback">\
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
                           }
                           else
                           {
                             html +='<div class="form-group has-feedback">\
                                        <label for="combined_delivery_date">Delivery Date</label>\
                                        <div class="input-group date" data-provide="datepicker" id="datePicker3">\
                                            <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
                                            <div class="input-group-addon"> \
                                              <span class="glyphicon glyphicon-th"></span>\
                                            </div>\
                                        </div>\
                                        <div class="help-block with-errors"></div>\
                                    </div>\
                                    </div>';
                           }
                           html += '     </div>\
                                        </div>\
                                       </div>\
                                     </div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for combined po upload modal tab */
                           $('#combined').html(html);

                           /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                                    <div class="container-fluid">\
                                        <div class="template-error"></div>';
                           var i = 1;
                          $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon">\
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                  </div>\
                                  <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator */
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }
                       $('#datePicker3').datepicker({
                            autoclose: true,
                            startDate: today,
                            format: 'yyyy-mm-dd',
                        })

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                        /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox1:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               var checkbox_clicked = 0;
                               /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Hide validation error, if atleast 2 artwork selected ] */
                                if(checkbox_clicked === 2)
                                {
                                    $('.template-error1').html('');
                                }
                           }
                           else
                           {
                                /** [ Clear all input fields values] */
                                $('input:not(.template-checkbox1)[id="'  + id + '"]').val("");
                                /* [ remove validation error from input field ] */
                                $('#upload_combined_po_form').validator('validate');
                           }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    $('.template-error1').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })

                        /** [ bootstrap validator for upload combined po form ] **/
                        $('#upload_combined_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                 /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 2 artwork not selected ] */
                                if(checkbox_clicked < 2)
                                {
                                    $('.template-error').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error1').html('<span style="color:#F44336">Please select atleast 2 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error1').html('');
                                    var formData = new FormData();
                                    var delivery_date = $('#combined_delivery_date').val();
                                    var po_number = $('#po_number').val();
                                    var po_file = $('#po_file').prop('files')[0];
                                    /** [ Create array of Templates selected ] */ 
                                    $('.template-checkbox1').each(function(i, item) 
                                    { 
                                        i += 1;
                                        if ($(this).is(':checked')) 
                                        { 
                                          formData.append('template_id_'+i,$(this).val());
                                          formData.append('template_'+i,$(this).data('name'));
                                        } 
                                    }); 
                                   
                                   formData.append('delivery_date',delivery_date);
                                   formData.append('po_number',po_number);
                                   formData.append('po_file',po_file);

                                   uploadCombinedPO(formData);
                                }
                            }
                        })

                        /** Clear combined tab validation errors */
                        $('#single_tab').on('click',function(){
                           $('.template-error1').html('');
                        });

                        /** Clear single tab validation errors */
                        $('#combined_tab').on('click',function(){
                           $('.template-error').html('');
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
        var is_insert         = $('.save-btn').data('insert');
        var thl3_last_id      = $('#THL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of ce single thl3 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL3 = count_THL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-ce-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data,is_insert:is_insert,thl3_last_id:thl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('#combined_tab').hide();
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }
                       /** Buyer Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                             <div class="container-fluid">\
                               <div class="template-error"></div>';
                       var i = 1;
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row">\
                                <div class="col-md-12">\
                                    <div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="po_number" class="form_label">PO Number</label>\
                                            <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="po_file">Purchase Order File</label>\
                                            <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="single_delivery_date">Delivery Date</label>\
                                            <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                <div class="input-group-addon"> \
                                                  <span class="glyphicon glyphicon-th"> </span >\
                                              </div >\
                                          </div >\
                                          <div class="help-block with-errors"></div>\
                                      </div>\
                                  </div>\
                              </div>\
                            </div>\
                            <hr>';
                       i++;
                       });
                       html +='</div>\
                                <div class="modal-footer">\
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                </div>\
                                </form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);

                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator  */
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl1_last_id      = $('#TCL1_id').val();
        var tcl2_last_id      = $('#TCL2_id').val();
        var tsl1_last_id      = $('#TSL1_id').val();


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
            count_TCL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');

        /** [ Create array of uk ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');

        /** [ Create array of uk ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-uk-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data,is_insert:is_insert,tcl1_last_id:tcl1_last_id,tcl2_last_id:tcl2_last_id,tsl1_last_id:tsl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** [ Show single po upload tab ,if order placed for single artwork] */
                       if(length === 1)
                       {
                         $('#combined_tab').hide();
                         /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                               <div class="container-fluid">\
                               <div class="template-error"></div>';
                           var i = 1;
                           $.each(data.last_insertions, function (index, value) { 
                            html+= '<div class="row">\
                                        <div class="col-md-12">\
                                            <div class="form-check form-group has-feedback">\
                                                <label class="form-check-label">\
                                                    <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label>\
                                                    <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="single_delivery_date">Delivery Date</label>\
                                                    <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                        <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                        <div class="input-group-addon"> \
                                                          <span class="glyphicon glyphicon-th"></span >\
                                                        </div >\
                                                    </div >\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       else
                       {
                           /** Buyer Po upload modal for combined po upload modal */
                           var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error1"></div>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <br> <strong>Select Artworks :</strong>';
                           $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox1" type="checkbox" value="'+value+'" name="'+index.replace(' ','')+'" id="'+index.replace(' ','')+'" style="margin-right: 10px;" data-name="'+index.replace(' ','')+'"> '+ index+' </label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>';
                           });
                           html += '</div>\
                                        <hr>\
                                        <div class="row">\
                                            <div class="col-md-12">\
                                                <div class="col-md-6">\
                                                    <div class="form-group has-feedback">\
                                                        <label for="po_number" class="form_label">PO Number</label>\
                                                        <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text">\
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
                           if(data.delivery_date_required === 1)
                           {
                             html +='<div class="form-group has-feedback">\
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
                           }
                           else
                           {
                             html +='<div class="form-group has-feedback">\
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
                                       </div>\
                                    </div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for combined po upload modal tab */
                           $('#combined').html(html);

                           /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error"></div>';
                           var i = 1;
                           $.each(data.last_insertions, function (index, value) { 
                            html+= '<div class="row">\
                                        <div class="col-md-12">\
                                            <div class="form-check form-group has-feedback">\
                                                <label class="form-check-label">\
                                                    <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label>\
                                                    <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="single_delivery_date">Delivery Date</label>\
                                                    <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                        <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                        <div class="input-group-addon"> \
                                                          <span class="glyphicon glyphicon-th"></span >\
                                                        </div >\
                                                    </div >\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div><hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator  */
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }
                       $('#datePicker3').datepicker({
                            autoclose: true,
                            startDate: today,
                            format: 'yyyy-mm-dd',
                       })

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                        /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox1:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               var checkbox_clicked = 0;
                               /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Hide validation error, if atleast 2 artwork selected ] */
                                if(checkbox_clicked === 2)
                                {
                                    $('.template-error1').html('');
                                }
                           }
                           else
                           {
                                /** [ Clear all input fields values] */
                                $('input:not(.template-checkbox1)[id="'  + id + '"]').val("");
                                /* [ remove validation error from input field ] */
                                $('#upload_combined_po_form').validator('validate');
                           }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    $('.template-error1').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })

                        /** [ bootstrap validator for upload combined po form ] **/
                        $('#upload_combined_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                 /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 2 artwork not selected ] */
                                if(checkbox_clicked < 2)
                                {
                                    $('.template-error').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error1').html('<span style="color:#F44336">Please select atleast 2 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error1').html('');
                                    var formData = new FormData();
                                    var delivery_date = $('#combined_delivery_date').val();
                                    var po_number = $('#po_number').val();
                                    var po_file = $('#po_file').prop('files')[0];
                                    /** [ Create array of Templates selected ] */ 
                                    $('.template-checkbox1').each(function(i, item) 
                                    { 
                                        i += 1;
                                        if ($(this).is(':checked')) 
                                        { 
                                          formData.append('template_id_'+i,$(this).val());
                                          formData.append('template_'+i,$(this).data('name'));
                                        } 
                                    }); 
                                   
                                   formData.append('delivery_date',delivery_date);
                                   formData.append('po_number',po_number);
                                   formData.append('po_file',po_file);

                                   uploadCombinedPO(formData);
                                }
                            }
                        })

                        /** Clear combined tab validation errors */
                        $('#single_tab').on('click',function(){
                           $('.template-error1').html('');
                        });

                        /** Clear single tab validation errors */
                        $('#combined_tab').on('click',function(){
                           $('.template-error').html('');
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
 * [storeUKRatioTHL1 for storing tesco UK ratio THL1 Templates manual orders]
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
        var is_insert         = $('.save-btn').data('insert');
        var thl1_last_id      = $('#THL1_id').val();

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

        /** [ Create array of uk ratio thl1 count ] */
        $('.count_THL1').each(function(i, item)
        {
            count_THL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL1 = count_THL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-uk-ratio-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_THL1:count_THL1,templates:data,is_insert:is_insert,thl1_last_id:thl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('#combined_tab').hide();
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** Buyer Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                             <div class="container-fluid">\
                               <div class="template-error"></div>';
                       var i = 1;
                       $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group  has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon"> \
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                                <hr>';
                        i++;
                       });
                       html +='</div>\
                                <div class="modal-footer">\
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                </div>\
                                </form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);

                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator  */
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl4_last_id      = $('#TCL4_id').val();
        var tsl3_last_id      = $('#TSL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of uk single tcl2 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');

        /** [ Create array of uk single tsl1 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-uk-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data,is_insert:is_insert,tcl4_last_id:tcl4_last_id,tsl3_last_id:tsl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** [ Show single po upload tab ,if order placed for single artwork] */
                       if(length === 1)
                       {
                         $('#combined_tab').hide()
                         /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data"><div class="container-fluid">\
                                <div class="template-error"></div>';
                           var i = 1;
                          $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon">\
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                   </div>\
                                   <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       else
                       {
                           /** Buyer Po upload modal for combined po upload modal */
                           var html = '<form id="upload_combined_po_form" data-toggle="validator" role="form" method="post" enctype="multipart/form-data">\
                                <div class="container-fluid">\
                                    <div class="template-error1"></div>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <br> <strong>Select Artworks :</strong>';
                           $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox1" type="checkbox" value="'+value+'" name="'+index.replace(' ','')+'" id="'+index.replace(' ','')+'" style="margin-right: 10px;" data-name="'+index.replace(' ','')+'"> '+ index+' </label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>';
                           });
                           html += '</div>\
                                    <hr>\
                                    <div class="row">\
                                        <div class="col-md-12">\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_number" class="form_label">PO Number</label>\
                                                    <input class="form-control input-sm " placeholder="PO Number" required="" name="po_number" value="" id="po_number" type="text">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">\
                                                <div class="form-group has-feedback">\
                                                    <label for="po_file">Purchase Order File</label> \
                                                     <span class="control-fileupload">\
                                                       <label for="file">Choose a file :</label> \
                                                        <input type="file" id="po_file" name="po_file" class="po-file-upload form-control center-block" required="" accept="application/pdf">\
                                                     </span>\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-6">';
                          /** [ Delivery Date mandatory or not for particular customer] */
                           if(data.delivery_date_required === 1)
                           {
                             html +='<div class="form-group has-feedback">\
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
                           }
                           else
                           {
                             html +='<div class="form-group has-feedback">\
                                        <label for="combined_delivery_date">Delivery Date</label>\
                                        <div class="input-group date" data-provide="datepicker" id="datePicker3">\
                                            <input value="" id="combined_delivery_date" name="combined_delivery_date" type="text" class="form-control center-block" placeholder="Select Delivery Date">\
                                            <div class="input-group-addon"> \
                                              <span class="glyphicon glyphicon-th"></span>\
                                            </div>\
                                        </div>\
                                        <div class="help-block with-errors"></div>\
                                    </div>\
                                    </div>';
                           }
                           html += '     </div>\
                                        </div>\
                                       </div>\
                                     </div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for combined po upload modal tab */
                           $('#combined').html(html);

                           /** Buyer Po upload modal for single po upload modal */
                           var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                                    <div class="container-fluid">\
                                        <div class="template-error"></div>';
                           var i = 1;
                          $.each(data.last_insertions, function (index, value) { 
                           html+= '<div class="row">\
                                    <div class="col-md-12">\
                                        <div class="form-check form-group has-feedback">\
                                            <label class="form-check-label">\
                                                <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_number" class="form_label">PO Number</label>\
                                                <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="po_file">Purchase Order File</label>\
                                                <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-6">\
                                            <div class="form-group has-feedback">\
                                                <label for="single_delivery_date">Delivery Date</label>\
                                                <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                    <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                    <div class="input-group-addon">\
                                                      <span class="glyphicon glyphicon-th"> </span >\
                                                    </div >\
                                                </div >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                  </div>\
                                  <hr>';
                           i++;
                           });
                           html +='</div>\
                                    <div class="modal-footer">\
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                        <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                    </div>\
                                    </form>';
                           /** Dynamic content for single po upload modal tab */
                           $('#single').html(html);
                       }
                       
                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator  */
                       $('#upload_combined_po_form').validator('update');
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }
                       $('#datePicker3').datepicker({
                            autoclose: true,
                            startDate: today,
                            format: 'yyyy-mm-dd',
                        })

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });

                        /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox1:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               var checkbox_clicked = 0;
                               /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Hide validation error, if atleast 2 artwork selected ] */
                                if(checkbox_clicked === 2)
                                {
                                    $('.template-error1').html('');
                                }
                           }
                           else
                           {
                                /** [ Clear all input fields values] */
                                $('input:not(.template-checkbox1)[id="'  + id + '"]').val("");
                                /* [ remove validation error from input field ] */
                                $('#upload_combined_po_form').validator('validate');
                           }
                        });

                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    $('.template-error1').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })

                        /** [ bootstrap validator for upload combined po form ] **/
                        $('#upload_combined_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                 /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox1').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 2 artwork not selected ] */
                                if(checkbox_clicked < 2)
                                {
                                    $('.template-error').html('');
                                    /**  [ Show validation error message ] **/
                                    $('.template-error1').html('<span style="color:#F44336">Please select atleast 2 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error1').html('');
                                    var formData = new FormData();
                                    var delivery_date = $('#combined_delivery_date').val();
                                    var po_number = $('#po_number').val();
                                    var po_file = $('#po_file').prop('files')[0];
                                    /** [ Create array of Templates selected ] */ 
                                    $('.template-checkbox1').each(function(i, item) 
                                    { 
                                        i += 1;
                                        if ($(this).is(':checked')) 
                                        { 
                                          formData.append('template_id_'+i,$(this).val());
                                          formData.append('template_'+i,$(this).data('name'));
                                        } 
                                    }); 
                                   
                                   formData.append('delivery_date',delivery_date);
                                   formData.append('po_number',po_number);
                                   formData.append('po_file',po_file);

                                   uploadCombinedPO(formData);
                                }
                            }
                        })

                        /** Clear combined tab validation errors */
                        $('#single_tab').on('click',function(){
                           $('.template-error1').html('');
                        });

                        /** Clear single tab validation errors */
                        $('#combined_tab').on('click',function(){
                           $('.template-error').html('');
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
        var is_insert         = $('.save-btn').data('insert');
        var thl3_last_id      = $('#THL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of uk single tcl2 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL3 = count_THL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/set-uk-single-template-orders",
                 type: 'POST',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data,is_insert:is_insert,thl3_last_id:thl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                       $('#combined_tab').hide();
                       var order_date = data.ordered_date.date;
                       delivery_date_req = data.delivery_date_required;
                       var length = Object.keys(data.last_insertions).length;
                       
                       /** Show snackbar if order placed **/
                       if(length > 0)
                       {
                         $('.snackbar_span').html('Your order has been placed successfully');
                         showSnackbar();
                       }

                       /** [ Show order already exists alertbox] */
                       if(data.already_exists.length === 0)
                       {
                         $('#order-exists-alert').html('');
                       }
                       else
                       {
                           var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                                <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                                 <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                                   <span aria-hidden="true">×</span>\
                                                 </button>\
                                                <p>Order already exists for following artworks,</p>\
                                               <ol>';
                            $.each(data.already_exists, function (index, value) { 
                                html_content += '<li>'+value+'</li>';
                             });   
                            html_content += '</ol>\
                                            </div>';

                         $('#order-exists-alert').html(html_content);

                         /** [ Show only alertbox if data already exits ] */
                         if(length === 0)
                         {
                            $('.po-modal-content').remove();
                            $('.close-btn').attr('data-dismiss','modal')
                         }
                         else
                         {
                            $('.snackbar_span').html('Your order has been placed successfully');
                            showSnackbar();
                         }
                       }

                       /** Buyer Po upload modal for single po upload modal */
                       var html = '<form id="upload_single_po_form" data-toggle="validator" role="form" enctype="multipart/form-data">\
                             <div class="container-fluid">\
                               <div class="template-error"></div>';
                       var i = 1;
                      $.each(data.last_insertions, function (index, value) { 
                       html+= '<div class="row">\
                                <div class="col-md-12">\
                                    <div class="form-check form-group has-feedback">\
                                        <label class="form-check-label">\
                                            <input class="form-check-input template-checkbox" type="checkbox" value="'+value+'" name="template_id_'+i+'" id="artwork_'+i+'" data-name="'+index.replace(' ','')+'" style="margin-right: 10px;">'+ index+'</label>\
                                        <div class="help-block with-errors"></div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="po_number" class="form_label">PO Number</label>\
                                            <input class="form-control input-sm po-number" placeholder="PO Number" name="po_number_'+i+'" value="" id="artwork_'+i+'" type="text" disabled="">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="po_file">Purchase Order File</label>\
                                            <input type="file" id="artwork_'+i+'" name="po_file_'+i+'" class="po-file-upload form-control center-block" disabled="" accept="application/pdf">\
                                            <div class="help-block with-errors"></div>\
                                        </div>\
                                    </div>\
                                    <div class="col-md-6">\
                                        <div class="form-group has-feedback">\
                                            <label for="single_delivery_date">Delivery Date</label>\
                                            <div class="input-group date" data-provide="datepicker" id="delivery_date_'+i+'">\
                                                <input value="" id="artwork_'+i+'" name="delivery_date_'+i+'" type="text" class="form-control center-block single_delivery_date" placeholder="Select Delivery Date" disabled="">\
                                                <div class="input-group-addon"> \
                                                  <span class="glyphicon glyphicon-th"> </span >\
                                              </div >\
                                          </div >\
                                          <div class="help-block with-errors"></div>\
                                      </div>\
                                  </div>\
                              </div>\
                            </div>\
                            <hr>';
                       i++;
                       });
                       html +='</div>\
                                <div class="modal-footer">\
                                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Upload Later</button>\
                                    <button class="btn btn-primary btn-sm" type="submit">Upload</button>\
                                </div>\
                                </form>';
                       /** Dynamic content for single po upload modal tab */
                       $('#single').html(html);

                       /** [ Backdrop for bootstrap modal ] */
                         $('#poUploadModal').modal({
                              backdrop: 'static',
                              keyboard: false
                         })

                       $('#poUploadModal').modal('show');

                       /** Update bootstrap 1000hz validator  */
                       $('#upload_single_po_form').validator('update');

                       /** remove save & submit button */
                       $('.submit-btn').remove();
                       $('.save-btn').remove();

                       /** @type {Date} [Delivery date Datepicker format & validation] */
                       var today = new Date(order_date);
                       for (var i=1; i <= length; i++) 
                       {
                           $('#delivery_date_'+i).datepicker({
                             autoclose: true,
                             startDate: today,
                             format: 'yyyy-mm-dd',
                            })
                       }

                       /* [ Toggle required field validation in for fields [single po upload form validation] ] */
                        $('.template-checkbox:checkbox').on('change',function(){
                           var th = $(this), id = th.prop('id');
                           if(th.is(':checked'))
                           {
                               $('input[id="'  + id + '"]').prop('disabled',false) 
                               $('.template-error').html('');
                               /* [ Check if delivery date is mandatory for this customer [ Dynamically swap mandatory validation ] ] */
                               if(delivery_date_req === 1)
                               {
                                 $('input[id="'  + id + '"]').prop('required',true)
                               }
                               else
                               {
                                 $('input:not(.single_delivery_date)[id="'  + id + '"]').prop('required',true)
                               }
                            }
                            else
                            {
                                /** [ Make all input fields disabled, except checkboxes ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').prop('disabled',true) 
                                /** [ Make all input fields empty ] */
                                $('input:not(.template-checkbox)[id="'  + id + '"]').val("");
                                /* [ remove required from input field ] */
                                $('input[id="'  + id + '"]').prop('required',false) 
                                /* [ remove validation error from input field ] */
                                $('#upload_single_po_form').validator('validate');
                            }
                        });
                       /** [ bootstrap validator for upload single po form ] **/
                        $('#upload_single_po_form').validator().on('submit', function (e) {
                            if (e.isDefaultPrevented()) 
                            {
                                
                            }
                            else 
                            {
                                e.preventDefault();
                                var checkbox_clicked = 0;
                                /** [ Get count of checkboxes checked ] */
                                $('.template-checkbox').each(function(i, item)
                                {
                                  if ($(this).is(':checked'))
                                    {
                                      checkbox_clicked++;
                                    }
                                    
                                });
                                /** [ Show validation error, if atleast 1 artwork not selected ] */
                                if(checkbox_clicked === 0)
                                {
                                    /**  [ Show validation error message ] **/
                                    $('.template-error').html('<span style="color:#F44336">Please select atleast 1 artwork.</span>');
                                }
                                else
                                {
                                    $('.template-error').html('')
                                    var formData = new FormData($(this)[0]);
                                    var templates = [];
                                    /** [ Append templates selected to formdata ] */
                                    $('.template-checkbox').each(function(i, item)
                                    {
                                        i += 1;
                                        if ($(this).is(':checked'))
                                        {
                                          formData.append('template_'+i,$(this).data('name'));
                                        }
                                    });
                                    
                                    uploadSinglePO(formData);
                                }
                            }
                        })
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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

/**
* Function for uploading single po files
* @author    <praveen>
*/
function uploadSinglePO(formdata) {
try{
    $.ajax({
             url: base_url + "/customer/tesco/upload-tesco-single-po-file",
             type: 'POST',
             headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
             data: formdata,
             tryCount : 0,
             retryLimit : 2,
             dataType:'JSON',
             async: true,
             cache: false,
             contentType: false,
             processData: false,
             statusCode:
             {
                200: function(data) {
                    $('.snackbar_span').html('PO File upload successfully');
                    showSnackbar();
                    $('#poUploadModal').modal('hide');
                },
                400: function() {
                    $('.snackbar_span').html("Something went wrong !. Try again");
                    showSnackbar();
                }
             },
             error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
             },
             complete: function () {
             }
         })
  } catch (e) {
   console.log("Error:"+e)
 }
}

/**
* Function for uploading combined po files
* @author    <praveen>
*/
function uploadCombinedPO(formdata) {
try{

    $.ajax({
             url: base_url + "/customer/tesco/upload-tesco-combined-po-file",
             type: 'POST',
             headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
             data: formdata,
             tryCount : 0,
             retryLimit : 2,
             dataType:'JSON',
             async: true,
             cache: false,
             contentType: false,
             processData: false,
             statusCode:
             {
                200: function(data) {
                    $('.snackbar_span').html('PO File upload successfully');
                    showSnackbar();
                    $('#poUploadModal').modal('hide');  
                },
                400: function() {
                    $('.snackbar_span').html("Something went wrong !. Try again");
                    showSnackbar();
                }
             },
             error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
             },
             complete: function () {
             }
         })
  } catch (e) {
   console.log("Error:"+e)
 }
}

/**
 * This Function is used to check ITF barcode is valid or not.
 * 
 * @return boolean
 */
function checkjsbarcodeitf(code) {
    var return_value="";
    JsBarcode("#ean-13", code, {
        format: "ITF14",
        width: 2.50,
        height: 100,
        fontSize: 30,
        flat:true,
        valid:function(valid){
            if(valid == false)
            {
                return_value="false";
            }
            else
            {
                return_value="true";
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
    var return_value="";
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 3.45,
        height: 125,
        fontSize: 35,
        flat:true,
        valid:function(valid){
            if(valid == false)
            {
                return_value="false";
            }
            else
            {
                return_value="true";
            }
        }
    });
    return return_value;
}

/**
 * [saveTescoOrder for drafting tesco manual orders(Save order)]
 * @author <praveen>
 */
function saveTescoOrder()
{
    try {
        var zone       =  localStorage.getItem("zone");
        var type       =  localStorage.getItem("template type");
        var data       =  JSON.parse(localStorage.getItem("data"));
        var po_number  =  $('#tesco_po_number').val();
        
        if((po_number != '') && (po_number != 'null') && (po_number != 'Null') && (po_number != 'NULL'))
        {
            if(zone === "CE")
            {
                if(type === "ratio")
                {
                    if(data[0].template_name === 'THL 1')
                    {
                        /** Function to save CE ratio THL1 template */
                        saveCERatioTHL1();
                    }
                    else
                    {
                        /** Function to save CE ratio TCL1, TCL2, TSL1 template */
                        saveCERatioTCL1TCL2TSL1();
                    }
                    
                }
                else
                {
                   if(data[0].template_name === 'THL 3')
                    {
                        /** Function to save CE single THL3 template */
                        saveCESingleTHL3();
                    }
                    else
                    {
                        /** Function to save CE single TCL4, TSL3 template */
                        saveCESingleTCL4TSL3();
                    }
                }
            }
            else
            {
                if(type === "ratio")
                {
                    if(data[0].template_name === 'THL 1')
                    {
                        /** Function to save UK ratio THL1 template */
                        saveUKRatioTHL1();
                    }
                    else
                    {
                        /** Function to save UK ratio TCL1, TCL2, TSL1 template */
                        saveUKRatioTCL1TCL2TSL1();
                    }
                    
                }
                else
                {
                   if(data[0].template_name === 'THL 3')
                    {
                        /** Function to save UK single THL3 template */
                        saveUKSingleTHL3();
                    }
                    else
                    {
                        /** Function to save UK single TCL4, TSL3 template */
                        saveUKSingleTCL4TSL3();
                    }
                }
            }
        }
        else
        {
           $('.snackbar_span').html('Please Enter a Valid PO Number !!');
           $('#snackbar').css('background-color','#F44336');
           showSnackbar();
        }
        
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}

/**
 * [saveCERatioTCL1TCL2TSL1 for storing tesco CE ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function saveCERatioTCL1TCL2TSL1()
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl1_last_id      = $('#TCL1_id').val();
        var tcl2_last_id      = $('#TCL2_id').val();
        var tsl1_last_id      = $('#TSL1_id').val();

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
            count_TCL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');

        /** [ Create array of ce ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');

        /** [ Create array of ce ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-ce-ratio-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data,is_insert:is_insert,tcl1_last_id:tcl1_last_id,tcl2_last_id:tcl2_last_id,tsl1_last_id:tsl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}

/**
 * [saveCERatioTHL1 for storing tesco CE ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function saveCERatioTHL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var orms_tpn          = [];
        var itf_code          = [];
        var cases_per_carton  = [];
        var count_THL1        = [];
        var is_insert         = $('.save-btn').data('insert');
        var thl1_last_id      = $('#THL1_id').val();

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

        /** [ Create array of ce ratio thl1 count ] */
        $('.count_THL1').each(function(i, item)
        {
            count_THL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL1 = count_THL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-ce-ratio-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,itf_code:itf_code,cases_per_carton:cases_per_carton,count_THL1:count_THL1,templates:data,is_insert:is_insert,thl1_last_id:thl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}

/**
 * [saveCESingleTCL4TSL3 for storing tesco CE single TCL4 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function saveCESingleTCL4TSL3()
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl4_last_id      = $('#TCL4_id').val();
        var tsl3_last_id      = $('#TSL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of ce single tcl4 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');

        /** [ Create array of ce single tsl3 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-ce-single-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data,is_insert:is_insert,tcl4_last_id:tcl4_last_id,tsl3_last_id:tsl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.error("Error:"+e)
    }
}

/**
 * [saveCESingleTHL3 for storing tesco CE single THL3 Template manual orders]
 * @author <praveen>
 */
function saveCESingleTHL3()
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
        var is_insert         = $('.save-btn').data('insert');
        var thl3_last_id      = $('#THL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of ce single thl3 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL3 = count_THL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-ce-single-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,orms_tpn:orms_tpn,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data,is_insert:is_insert,thl3_last_id:thl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}

/**
 * [saveUKRatioTCL1TCL2TSL1 for storing tesco UK ratio TCL1,TCL2 AND TSL1 Templates manual orders]
 * @author <praveen>
 */
function saveUKRatioTCL1TCL2TSL1()
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl1_last_id      = $('#TCL1_id').val();
        var tcl2_last_id      = $('#TCL2_id').val();
        var tsl1_last_id      = $('#TSL1_id').val();


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
            count_TCL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL1 = count_TCL1.join(',');

        /** [ Create array of uk ratio tcl2 count ] */
        $('.count_TCL2').each(function(i, item)
        {
            count_TCL2[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL2 = count_TCL2.join(',');

        /** [ Create array of uk ratio tsl1 count ] */
        $('.count_TSL1').each(function(i, item)
        {
            count_TSL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL1 = count_TSL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-uk-ratio-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_TCL1:count_TCL1,count_TCL2:count_TCL2,count_TSL1:count_TSL1,templates:data,is_insert:is_insert,tcl1_last_id:tcl1_last_id,tcl2_last_id:tcl2_last_id,tsl1_last_id:tsl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}

/**
 * [saveUKRatioTHL1 for storing tesco UK ratio THL1 Templates manual orders]
 * @author <praveen>
 */
function saveUKRatioTHL1()
{
    try {
        /** Calling tescoOrderCommonData() function for getting common data(independent of zone and template type) */
        tescoOrderCommonData();
        /** @type {Array} [ initializing empty arrays ] */
        var tpnd              = [];
        var itf_code          = [];
        var units_per_carton  = [];
        var count_THL1        = [];
        var is_insert         = $('.save-btn').data('insert');
        var thl1_last_id      = $('#THL1_id').val();

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

        /** [ Create array of uk ratio thl1 count ] */
        $('.count_THL1').each(function(i, item)
        {
            count_THL1[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL1 = count_THL1.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-uk-ratio-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,itf_code:itf_code,units_per_carton:units_per_carton,count_THL1:count_THL1,templates:data,is_insert:is_insert,thl1_last_id:thl1_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}

/**
 * [saveUKSingleTCL4TSL3 for storing tesco UK single TCL3 AND TSL3 Templates manual orders]
 * @author <praveen>
 */
function saveUKSingleTCL4TSL3()
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
        var is_insert         = $('.save-btn').data('insert');
        var tcl4_last_id      = $('#TCL4_id').val();
        var tsl3_last_id      = $('#TSL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of uk single tcl2 count ] */
        $('.count_TCL4').each(function(i, item)
        {
            count_TCL4[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TCL4 = count_TCL4.join(',');

        /** [ Create array of uk single tsl1 count ] */
        $('.count_TSL3').each(function(i, item)
        {
            count_TSL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_TSL3 = count_TSL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-uk-single-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_TCL4:count_TCL4,count_TSL3:count_TSL3,templates:data,is_insert:is_insert,tcl4_last_id:tcl4_last_id,tsl3_last_id:tsl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}

/**
 * [saveUKSingleTHL3 for storing tesco UK single THL3 Template manual orders]
 * @author <praveen>
 */
function saveUKSingleTHL3()
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
        var is_insert         = $('.save-btn').data('insert');
        var thl3_last_id      = $('#THL3_id').val();

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
            size[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        size = size.join(',');

        /** [ Create array of uk single tcl2 count ] */
        $('.count_THL3').each(function(i, item)
        {
            count_THL3[i]    = $(item).val() === ''? 0 : $(item).val();
            
        });
        count_THL3 = count_THL3.join(',');

        $.ajax({
                 url: base_url + "/customer/tesco/save-uk-single-template-orders",
                 type: 'PUT',
                 data: {
                     _token: csrf_token,po_number:po_number,uk_style:uk_style,ce_style:ce_style,season_code:season_code,description:description,eqos:eqos,manufacture_date:manufacture_date,department:department,brand:brand,supplier:supplier,rfid:rfid,tag_at_source:tag_at_source,zone:zone,type:type,tpnd:tpnd,ean_code:ean_code,case_size:case_size,size:size,count_THL3:count_THL3,templates:data,is_insert:is_insert,thl3_last_id:thl3_last_id
                 },
                 tryCount : 0,
                 retryLimit : 2,
                 dataType:"json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 success: function (data) {
                    /** [ Show order already exists alertbox] */
                   if(data.already_exists.length > 0)
                   {
                      $('.po-modal-content').remove();
                      $('.close-btn').attr('data-dismiss','modal')
                       var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                                            <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                                             <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                               <span aria-hidden="true">×</span>\
                                             </button>\
                                            <p>Order already exists for following artworks,</p>\
                                           <ol>';
                        $.each(data.already_exists, function (index, value) { 
                            html_content += '<li>'+value+'</li>';
                         });   
                        html_content += '</ol>\
                                        </div>';

                     $('#order-exists-alert').html(html_content);
                     $('#poUploadModal').modal('show');
                    }
                    else
                    {
                        $('.snackbar_span').html('Saved Successfully');
                        showSnackbar();

                        $('.save-btn').data('insert','false');

                        var div_html='';
                        /** [ store last updated id as hidden input , for updating] */
                        $.each(data.last_insertions, function (index, value) {
                            div_html += '<input id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                        });
                        $('.invisible-div').html(div_html);
                    }
                 },
                 error: function(errorXHR, errorStatus, errorThrown) {
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
    } catch(e) {
        /** [error statements] */
        console.log("Error:"+e)
    }
}

/**
* Function for loading the template preview
*/
function loadTemplatePreview() {
    var template_id = $('#po_number').find(':selected').data('tempid');
    var csrf_token  = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/tesco/template-view",
        type: "Post",
        dataType: "json",
        data: {
            template_id: template_id,
            _token: csrf_token
        },
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            if (data.length > 0) {
                $('.templateDiv').html('');
                $.each(data, function (index, value) {
                    if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
                        $('.templateDiv').append('<div class="col-md-4">\
                            <object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object>\
                            <div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;">\
                            <label>Template Name : </label>\
                            <p>' + value[0].template_name + '</p>\
                            <label>Reference Number : </label>\
                            <p>' + value[0].ref_no + '</p>\
                            <label>Company Name : </label>\
                            <p>' + value[0].company_name + '</p>\
                            </div>\
                            </div>')

                    } else {
                        $('.templateDiv').append('<div class="col-md-4">\
                            <object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object>\
                            <div style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;">\
                            <label>Template Name : </label>\
                            <p>' + value[0].template_name + '</p>\
                            <label>Reference Number : </label>\
                            <p>' + value[0].ref_no + '</p>\
                            <label>Company Name : </label>\
                            <p>' + value[0].company_name + '</p>\
                            </div>\
                            </div>')

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
        error: function(errorXHR, errorStatus, errorThrown) {
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
}
/**
 * AJAX method to fetch the order details.
 * @return {[type]} [description]
 */
function viewOrder(pNumber) {
    try {
        $.ajax({
            url: base_url + "/customer/tesco/view-order",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'po_number':pNumber},
            tryCount: 0,
            retryLimit: 2,
            dataType: 'JSON',
            statusCode: {
                200: function(data) {
                    var node_ids = [];
                    var zone = [];
                    var type = [];
                    var group = [];
                    var nodes;
                    var template_zone;
                    var template_type;
                    $.each(data.result, function(index, object) {
                        if( object && object !== "null" && object !== "undefined" ) {
                            node_ids.push(object.template_id);
                            zone.push(object.zone);
                            type.push(object.template_type);
                            group.push(object.TemplateName);
                        }
                    });
                    if(node_ids.length > 0) {
                        nodes = node_ids.toString();
                    }
                    if(zone.length > 0 && $.unique(zone).length === 1) {
                        template_zone = zone[0];
                    }
                    if(type.length > 0 && $.unique(type).length === 1) {
                        template_type = type[0];
                    }
                    group = group.toString();                  
                    localStorage.setItem("templates", nodes);
                    localStorage.setItem("zone", template_zone);
                    localStorage.setItem("template type", template_type);
                    localStorage.setItem("template_group", group);
                    createOrders(data.result, "viewOrder");
                },
                400: function() {
                    $('.snackbar_span').html("Sorry!! Couldn't process your request. Please try again later.");
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {}
        })
    } catch (e) {
        console.error("Error:" + e)
    }
}

/**
 * Function to render the stored order details to the create order page.
 * @param  {object} oDetails Order Details object 
 */
function mapStoredValues(oDetails) {
    var artwork_num = oDetails.length;
    var count = 0;
    var artwork_status = '';
    var last_insertions = [];
    var header = 'Create Order For TESCO - '+localStorage.getItem('zone')+' - '+localStorage.getItem('template_group');
    $('.page-header h4 strong').text(header);
    $('.save-btn').data('insert','false');
    $.each(oDetails, function(ind,ele) {
        if(count === 0) {
            $('#tesco_po_number').val(ele.po_number);
            $('#tesco_ce_style').val(ele.ce_style_ref);
            $('#tesco_uk_style').val(ele.uk_style_ref);
            $('#tesco_season_code').val(ele.season_code);
            $('#tesco_description').val(ele.short_description);
            $('#tesco_eqos').val(ele.eqos_site_code);
            $('#tesco_manufacture_date').val(ele.manufacture_date);
            $('#tesco_department').val(ele.tesco_department_id);
            $('#tesco_brand').val(ele.tesco_brand_id);
            $('#tesco_supplier').val(ele.tesco_supplier_id);
            $('#tesco_rfid').val(ele.rfid_compliant === 1  ? 'yes' : 'no');
            $('#tesco_tag_at_source').val(ele.tag_at_source === 1  ? 'yes' : 'no');

            if(ele.template_type === 'single') {
                $.each(ele.order_quantity, function(i, j) {
                    var index = i + 1;
                    var zone = ele.zone.toLowerCase();
                    if(zone === 'ce') {
                        $('#ce_single_orms_tpn_'+index).val(j.orms_tpn);
                    } else {
                        $('#uk_single_tpnd_'+index).val(j.tpnd);
                    }
                    
                    $('#'+zone+'_single_ean_'+index).val(j.ean_barcode);
                    $('#'+zone+'_single_case_size_'+index).val(j.case_size);
                    $('#'+zone+'_single_size_'+index).val(j.size);  
                    if(ele.order_quantity.length !== (i+1)) {
                        $('.add-row-btn').trigger('click');
                    }
                });  
            } else if(ele.template_type === 'ratio') {
                $.each(ele.order_quantity, function(i, j) {
                    var index = i + 1;
                    var zone = ele.zone.toLowerCase();
                    $('#'+zone+'_ratio_itf_'+index).val(j.itf_barcode);
                    if(zone === 'ce') {
                        $('#'+zone+'_ratio_orms_tpn_'+index).val(j.tpnd);
                        $('#'+zone+'_ratio_cpc_'+index).val(j.cases_per_carton);
                    } else {
                        $('#'+zone+'_ratio_tpnd_'+index).val(j.tpnd);
                        $('#'+zone+'_ratio_upc_'+index).val(j.units_per_carton);
                    }
                    if(ele.order_quantity.length !== (i+1)) {
                        $('.add-row-btn').trigger('click');
                    }
                }); 
            }
        }

        $.each(ele.order_quantity, function(loop, qty) {
            $('#'+ele.zone.toLowerCase()+'_'+ele.template_type+'_count_'+ele.TemplateName.replace(/ /g,"")+'_'+(loop+1)).val(qty.count);
        });
        count++;
        artwork_status = ele.artwork_status;
        last_insertions[ele.TemplateName.replace(/ /g,"")+'_id'] = ele.id;

    });
    if(artwork_status === 'Saved') {
        var div_html='';
        /** [ store last updated id as hidden input , for updating] */
        for (var k in last_insertions){
            if (last_insertions.hasOwnProperty(k)) {
                 div_html += '<input id="'+k+'" name="'+k+'" type="hidden" value="'+last_insertions[k]+'">';
            }
        }
        $('.invisible-div').html(div_html);

    } else {
        $('#create_tesco_order_form input').attr('readonly', 'readonly');
        $('.selectpicker').prop('disabled', true);
        $('#tesco_manufacture_date').prop('disabled',true);
        $("#datePicker1").datepicker("disabled");
        $('.delete-row, .add-row-btn, .submit-btn, .save-btn').remove();
    }
    $(".selectpicker").selectpicker('refresh');
}
