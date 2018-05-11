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
                                $('.viewDiv').html('');
                                $.each(data, function (index, value) {
                                    if (value[0].id == "3" || value[0].id == "5" || value[0].id == "10" || value[0].id == "12") {
                                        $('.viewDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">'+value[0].template_name+'</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">'+value[0].ref_no+'</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">'+value[0].company_name+'</td></tr><tr><td class="pull-left">Taxation : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Enter Tax" required type="number" value="'+value[0].tax+'" name="tax_'+value[0].id+'" step="0.01" id="tax_'+value[0].id+'" style="width: 80px;"> <span class="input-group-btn"><button class="btn btn-secondary btn-sm" type="button" id="'+value[0].id+'" onclick="saveTescoTax(this.id)">Save</button></span></div></td></tr></tbody></table></div></div>')

                                    } else {
                                        $('.viewDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">'+value[0].template_name+'</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">'+value[0].ref_no+'</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">'+value[0].company_name+'</td></tr><tr><td class="pull-left">Taxation : </td><td class="pull-left"><div class="input-group"><input class="form-control input-sm " placeholder="Enter Tax" required type="number" value="'+value[0].tax+'" name="tax_'+value[0].id+'" id="tax_'+value[0].id+'" step="0.01" style="width: 80px;"><span class="input-group-btn"><button class="btn btn-secondary btn-sm" type="button" id="'+value[0].id+'" onclick="saveTescoTax(this.id)">Save</button></span></div></td></tr></tbody></table></div></div>')

                                    }
                                });
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
                                console.log(data);
                                console.log("DAsdsadsadasdsa");
                                if (data.length > 0) {
                                    if (data[0].template_file != "") {
                                        if (data.length > 1) {
                                            $('.viewDiv').html("");
                                            $.each(data, function (index, value) {
                                                $('.viewDiv').append('<object style="display:inline-block" id="svg2" class="center-block svg2" data="' + value.template_file + '" type="image/svg+xml"></object>')
                                            });
                                            
                                        }
                                        else
                                        {
                                            $('.viewDiv').html('<object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                                        }
                                        $('.descriptionDiv').html('<label>Template Name : </label><p>' + data[0].template_name + '</p><label>Reference Number : </label><p>' + data[0].ref_no + '</p><label>Company Name : </label><p>' + data[0].company_name + '</p>')
                                        $('.descriptionDiv').css('margin-top', '20px')
                                        $('.viewDiv').css('margin-top', '0px')

                                    }
                                    else
                                    {
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
    var tax = $('#tax_'+temp_id).val();
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/template-management/save-taxation",
        type: "POST",
        dataType: "json",
        data: {
            template_id: temp_id,
            tax : tax,
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

/**
 * To show the snackbar
 */
function showSnackbar() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");
    // Add the "show" class to DIV
    x.className = "show";
    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}