/**
 * Method to pull and render the template hierarchy of a buyer
 */
function price_template_list() {
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    
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
        url: base_url + "/admin/template-management/template-hierarchy",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
			buyer:buyer
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            $('.template-block').html('<div class="col-md-4"><div class="search-tree"><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class=" js-card style-4"  style="height:calc(100vh - 60px); overflow-y:scroll; position:relative;margin-bottom: 15px;"><div class="jaytree force-overflow" id="tree1" style="margin-top:42px"> </div></div></div><div class="col-md-8"><div class="preview-tag" style="height:100%; border-left:1px solid #ddd;"><div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="viewDiv"></div></div> </div>')

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
                    
                    if(buyer === 'tesco') {
                        var url = base_url + "/admin/" + buyer + "/customer-management/template-view";
                    } else if(buyer === 'orchestra') {
                        var url =  base_url + "/admin/" + buyer + "/template-management/template-view";
                    } else{
						var url =  base_url + "/admin/template-management/template-view";
					}
                     $.ajax({
                        url: url,
                        type: "Post",
                        dataType: "json",
                        data: {
                            template_id: template_id,
							buyer:buyer,
                            _token: csrf_token
                        },
                        beforeSend: function () {
                            $(".loader").css("margin-left", "22%");
                            $(".loader").show();
                            $('.viewDiv').html('');
                        },
                        success: function (data) {

                            if (data.length > 0) {
								
								if(data[0].template_file != "")
								{
									$('.viewDiv').html('<object id="svg2" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
									$('.descriptionDiv').html('<label>Template Name : </label><p>' + data[0].template_name + '</p><label>Reference Number : </label><p>' + data[0].ref_no + '</p><label>Company Name : </label><p>' + data[0].company_name + '</p>')
									$('.descriptionDiv').css('margin-top', '20px')
									$('.viewDiv').css('margin-top', '0px');
								}
								else
								{
									$('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>')
									$('.descriptionDiv').html('<b>Template Not Found</b>');
									$('.descriptionDiv').css('margin-top', '0px')
									$('.viewDiv').css('margin-top', '140px');
								}
                               
                            } else {
                                $('.viewDiv').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover;margin-top: 122px; font-size: 100px;"></i>')
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
    $('.viewDiv').append('<hr><div class="template-form"><h4 style="margin-bottom:20px;">Artwork Configuration</h4><form role="form" data-toggle="validator" id="priceForm"><div class="form-group row has-feedback"> <label for="custom_name" class="col-sm-3 col-md-offset-1 col-form-label text-left">Name in Invoice</label><div class="col-md-8"> <input class="form-control input-sm " placeholder="Template Custom Name" required type="text" id="name"> </div></div><div class="form-group row has-feedback"> <label for="moq" class="col-sm-3 col-md-offset-1 col-form-label text-left">MOQ</label> <div class="col-md-8"> <input type="number" class="form-control input-sm" id="moq" min=1  onkeypress="return isNumber(event)" oninput="baseMOQ()" placeholder="Minimum Order Quantity"> </div></div> <div class="col-md-offset-1"><button type="button" onclick="addVolume(this,event)" data-id="1" style="margin-bottom: 10px;" class="btn btn-default pull-left add-volume"><i class="fa fa-plus" aria-hidden="true"></i> Add Volume</button></div><div class="col-md-offset-1"><table class="table table-bordered text-center"> <thead> <tr> <th>Line No.</th> <th>Volume Begin</th> <th>Volume End</th> <th>Price (&#x20b9;)</th> <th>Actions</th> </tr></thead> <tbody id="table_row"> <tr> <td class="slno">1</td><td> <div class="form-group has-feedback"> <input min=1 data-min-error="Value should be > 0" type="number" class="form-control input-sm" required="" id="volume_from_1" name="volume_from_1" placeholder="Volume From"> <div class="help-block with-errors"></div></div></td><td> <div class="form-group has-feedback"> <input min=1 data-min-error="Value should be > 0" type="number" class="form-control input-sm" required="" id="volume_to_1" name="volume_to_1" placeholder="Volume To"> <div class="help-block with-errors"></div></div></td><td> <div class="form-group has-feedback"> <input type="number" class="form-control input-sm" required="" id="price_1"  min=0.01 data-min-error="Value should be > 0" name="price_1" placeholder="Price in INR" step="0.01"> <div class="help-block with-errors"></div></div></td><td></td></tr></tbody></table></div><button type="submit" style="margin-bottom:20px;" class="btn btn-primary pull-right">Save</button></form></div>');

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
 * method to get the MOQ as the initial volume from data
 * @return {[type]} [description]
 */
function baseMOQ() {
    $('#volume_from_1').val($('#moq').val());
}
/**
 *  addCESingle function for adding dynamic table row for CE single templates 
 * @author <praveen>
 * 
 */
function addVolume(rem,e) {
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
                            <input  min=1 type="number" data-min-error="Value should be > 0" class="form-control input-sm" id="volume_from_'+val+'" name="volume_from_'+val+'" required="" placeholder="Volume From">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input  min=1 type="number" data-min-error="Value should be > 0" class="form-control input-sm"  id="volume_to_'+val+'" name="volume_to_'+val+'" required="" placeholder="Volume To">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>\
                    <td>\
                        <div class="form-group has-feedback">\
                            <input min=0.01 type="number" data-min-error="Value should be > 0" class="form-control input-sm"  id="price_'+val+'" name="price_'+val+'" required="" placeholder="Price in INR" step="0.01">\
                            <div class="help-block with-errors"></div>\
                        </div>\
                    </td>';

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
     $('#priceForm').validator('update'); 

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
 * AJAX function to save the price and tax details of a template to the db
 */
function savePricing() {

    var customer_id = localStorage.getItem("customer_id");
    var template_id = localStorage.getItem("template_id");
    var buyer_id    = localStorage.getItem("buyer_id");
    var csrf_token  = $('meta[name="csrf-token"]').attr('content');
    var pricestring = $("#priceForm").serialize();
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
            moq:$('#moq').val(),
            price:pricestring

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
            $('.snackbar_span').html('Saved Successfully');
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
                var leng = Object.keys(data.price_list).length;
                $.each(data.price_list, function(i,val) {
                    $('#name').val(val.custom_name);
                    $('#moq').val(val.moq);
                    $('#volume_from_'+(i+1)).val(val.volume_from);
                    $('#volume_to_'+(i+1)).val(val.volume_to);
                    $('#price_'+(i+1)).val(val.price);
                    if(i < leng-1) {
                        $('.add-volume').trigger('click');
                    }
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
