function price_template_list() {
    var buyer = localStorage.getItem("pricing_buyer");
    var buyer_id = localStorage.getItem("pricing_buyer_id");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:base_url + "/admin/customer-management/get-buyers-customers",
        type:"GET",
        dataType:"json",
        data :{
            _token: csrf_token,
            buyer_id:buyer_id,
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
            if(data.customer_list.length === 0){
                $('.customer-select').html('<li>No Customers</li>');
            } else {
                var list = '';
                $.each(data.customer_list, function(){
                    list += '<li id="'+this.id+'"><a>'+this.company_name+'</a></li>';
                });
                $('.customer-select').html(list);
            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    });

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
            $('.template-block').html('<div class="col-md-4"><div class="search-tree"><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class=" js-card style-4"  style="height:calc(100vh - 60px); overflow-y:scroll; position:relative;margin-bottom: 15px;"><div class="jaytree force-overflow" id="tree1" style="margin-top:42px"> </div></div></div><div class="col-md-8"><div class="preview-tag" style="height:calc(100vh - 60px); border-left:1px solid #ddd;"><div class="loader"></div><div class="viewDiv"></div></div> </div>')


            //            <div class="template-form"><div class="col-md-4"><div class="form-group"><label for="buyer_name" class="form_label text-left">Po Number</label><input class="form-control input-sm " placeholder="PO Number" required="" type="text" value=""></div></div><div class="col-md-4"><div class="form-group"><label for="buyer_name" class="form_label text-left">Po Number</label><input class="form-control input-sm " placeholder="PO Number" required="" type="text" value=""></div></div><div class="col-md-4"><div class="form-group"><label for="buyer_name" class="form_label text-left">Po Number</label><input class="form-control input-sm " placeholder="PO Number" required="" type="text" value=""></div></div></div>



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

                            if (data.length > 0) {
                                $('.viewDiv').html('<object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                                $('.descriptionDiv').html('<label>Template Name : </label><p>' + data[0].template_name + '</p><label>Reference Number : </label><p>' + data[0].ref_no + '</p><label>Company Name : </label><p>' + data[0].company_name + '</p>')
                                $('.descriptionDiv').css('margin-top', '20px')
                                $('.viewDiv').css('margin-top', '0px');
                                setTimeout(function(){ showPricing(); }, 400);
                            } else {
                                $('.viewDiv').html('<img style="width:160px;height:150px;object-fit:cover;margin-left: 118px;"src="https://maxcdn.icons8.com/Share/icon/Dusk_Wired/Programming//template1600.png">')
                                $('.descriptionDiv').html('<b>Template Not Found</b>');
                                $('.descriptionDiv').css('margin-top', '0px')
                                $('.viewDiv').css('margin-top', '140px');
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
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
}


function showPricing() {
    $('.viewDiv').append('<hr><div class="template-form"><h4 style="margin-bottom:30px;">Artwork Configuration</h4><div class="col-md-4"><form><div class="form-group"><label for="custom_name" class="form_label text-left">Custom Name</label><input class="form-control input-sm " placeholder="Customers Custom Name" required type="text" ></div></div><div class="col-md-4"><div class="form-group"><label for="price" class="form_label text-left">Price</label><input class="form-control input-sm " placeholder="Price in INR" required type="text"></div></div><div class="col-md-4"><div class="form-group"><label for="taxation" class="form_label text-left">Taxation</label><input class="form-control input-sm " placeholder="Enter Tax" required type="text"></div></div><button type="submit" class="btn btn-primary pull-right">Save</button></form></div>');

    $('#priceForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           savePricing();
        }
      });
}
