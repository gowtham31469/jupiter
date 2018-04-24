/**
 * Method to pull and render the template hierarchy of a buyer
 */
function price_template_list() {
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
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
                $('.customer-select li a').first().addClass('active');
                localStorage.setItem("customer_id", $('.customer-select li').first().attr('id').toLowerCase());
            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
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
        },
        success: function (data) {
            $('.template-block').html('<div class="col-md-4"><div class="search-tree"><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class=" js-card style-4"  style="height:calc(100vh - 60px); overflow-y:scroll; position:relative;margin-bottom: 15px;"><div class="jaytree force-overflow" id="tree1" style="margin-top:42px"> </div></div></div><div class="col-md-8"><div class="preview-tag" style="height:calc(100vh - 60px); border-left:1px solid #ddd;"><div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="viewDiv"></div></div> </div>')

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
                { $(this).jstree("open_all");});


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
                    localStorage.setItem("template_id", data.node.id);
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    if(buyer === 'tesco') {
                        var url = base_url + "/admin/" + buyer + "/customer-management/template-view";
                    } else {
                        var url =  base_url + "/admin/" + buyer + "/template-management/template-view";
                    }
                                        $.ajax({
                        url: url,
                        type: "Post",
                        dataType: "json",
                        data: {
                            template_id: template_id,
                            _token: csrf_token
                        },
                        beforeSend: function () {
                            $(".loader").css("margin-left", "22%");
                            $(".loader").show();
                            $('.viewDiv').html('');
                        },
                        success: function (data) {

                            if (data.length > 0) {
                                $('.viewDiv').html('<object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
                                $('.descriptionDiv').html('<label>Template Name : </label><p>' + data[0].template_name + '</p><label>Reference Number : </label><p>' + data[0].ref_no + '</p><label>Company Name : </label><p>' + data[0].company_name + '</p>')
                                $('.descriptionDiv').css('margin-top', '20px')
                                $('.viewDiv').css('margin-top', '0px');
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
                            setTimeout(function(){ showPricing(); }, 400);
                            $(".loader").hide();
                        }
                    })
                }
            });
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * Method to show the pricing details on a template click
 */
function showPricing() {
    $('.viewDiv').append('<hr><div class="template-form"><h4 style="margin-bottom:20px;">Artwork Configuration</h4><form role="form" data-toggle="validator" id="priceForm"><div class="col-md-4"><div class="form-group has-feedback"><label for="custom_name" class="form_label text-left">Custom Name</label><input class="form-control input-sm " placeholder="Template Custom Name" required type="text" id="name" ></div></div><div class="col-md-4"><div class="form-group has-feedback"><label for="price" class="form_label text-left">Price (in ₹ per unit)</label><input class="form-control input-sm " min="0" placeholder="Price in INR/ Unit" required type="number" id="price" step="0.01"></div></div><div class="col-md-4"><div class="form-group has-feedback"><label for="taxation" class="form_label text-left">Tax</label><div class="input-group"><input class="form-control input-sm " placeholder="Enter Tax" required type="number" id="tax" min="0" step="0.01"><div class="input-group-addon">%</div></div></div></div><button type="submit" class="btn btn-primary pull-right">Save</button></form></div>');

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

    getSavedPriceTax();
}

/**
 * AJAX function to save the price and tax details of a template to the db
 */
function savePricing() {

    var customer_id = localStorage.getItem("customer_id");
    var template_id = localStorage.getItem("template_id");
    var buyer_id    = localStorage.getItem("buyer_id");
    var csrf_token  = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:base_url + "/admin/customer-management/save-price-tax",
        type:"POST",
        dataType:"json",
        data :{
            _token: csrf_token,
            customer_id:customer_id,
            buyer_id:buyer_id,
            template_id:template_id,
            custom_name:$('#name').val(),
            price:$('#price').val(),
            tax:$('#tax').val()

        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            //incluude toast
        },
        error: function (data) {
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
            showSnackbar();
        }
    });
}
/**
 * Method to pull the saved tax details of a customer from the db
 */
function getSavedPriceTax() {
    var customer_id = localStorage.getItem("customer_id");
    var template_id = localStorage.getItem("template_id");
    var buyer_id    = localStorage.getItem("buyer_id");
    var csrf_token  = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:base_url + "/admin/customer-management/get-price-tax",
        type:"GET",
        dataType:"json",
        data :{
            _token: csrf_token,
            customer_id:customer_id,
            buyer_id:buyer_id,
            template_id:template_id
        },
        statusCode: {
            204: function () {
                console.log('No Price details found for this combination');
            },
            500: function () {
                console.log('Internal server error in /admin/customer-management/get-price-tax API');
            }, 
            200: function(data) {
                $.each(data.price_list, function(i,val) {
                    $('#name').val(val.custom_name);
                    $('#price').val(val.price);
                    $('#tax').val(val.tax);
                }) 
            }
        },
        complete: function () {
        }
    });
}

/**
 * To show the snackbar
 */
function showSnackbar() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar")
    // Add the "show" class to DIV
    x.className = "show";
    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

/**
 * JS Tree function is refreshed when a new customer name is clicked
 */
$('.customer-select').on('click', 'li', function() {
    $('.customer-select li a').removeClass('active');
    localStorage.setItem("customer_id", this.id);
    $(this).find('a').addClass('active');
    $('#tree1').jstree('refresh');
    $('.viewDiv').empty();
})
