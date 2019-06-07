/**
* Function for loading the template preview
*/
function loadTemplatePreview() {
    var template_id = $('#artwork_list').find(':selected').data('tempid');
    var csrf_token  = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/template-management/template-view",
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
                        $('.templateDiv').append('<div class="col-md-4"><object style="width:50%;border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">'+value[0].template_name+'</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">'+value[0].ref_no+'</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">'+value[0].company_name+'</td></tr></tbody></table></div></div>')

                    } else {
                        $('.templateDiv').append('<div class="col-md-4"><object style="border: 2px #333 solid;height: 153px;width: 235px;" id="svg2" class="center-block svg2" data="' + value[0].template_file + '" type="image/svg+xml"></object><div class=" col-md-12 col-lg-12 " style="margin-top: 10px;padding: 10px;border: 1px dashed #333;text-align: center;"><table class="table table-user-information"><tbody><tr><td class="pull-left">Template Name :</td><td class="pull-left">'+value[0].template_name+'</td></tr><tr><td class="pull-left">Reference Number :</td><td class="pull-left">'+value[0].ref_no+'</td></tr><tr><td class="pull-left">Company Name : </td><td class="pull-left">'+value[0].company_name+'</td></tr></tbody></table></div></div>')

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
function viewOrder(pNumber,aWork) {
    try {
        $.ajax({
            url: base_url + "/admin/tesco/view-order",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $(".loader").show();
            },
            data: {'po_number':pNumber, 'artwork':aWork},
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
            complete: function() {
                $(".loader").hide();
            }
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
    var order_status = '';
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
            $('#tesco_supplier').val(ele.tesco_metadata_supplier_id);
            $('#tesco_rfid').val(ele.rfid_compliant === 1  ? 'yes' : 'no');
            $('#tesco_tag_at_source').val(ele.tag_at_source === 1  ? 'yes' : 'no');
            $('#tesco_ocn').val(ele.ocn_number);

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
                        $('#'+zone+'_ratio_orms_tpn_'+index).val(j.orms_tpn);
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
        order_status = ele.order_status;
        last_insertions[ele.TemplateName.replace(/ /g,"")+'_id'] = ele.id;
        /** [ Remove print and proof genration button if quantity is 0 ] */
        if(ele.is_order_count_empty === 1)
        {
           $('#print_' + ele.TemplateName.replace(' ', '') + '_btn').hide();
           $('#proof_' + ele.TemplateName.replace(' ', '') + '_btn').hide();
        }
        else
        {
            $('#print_' + ele.TemplateName.replace(' ', '') + '_btn').show();
            $('#proof_' + ele.TemplateName.replace(' ', '') + '_btn').show();
        }
    });
    
        var div_html='';
        /** [ store last updated id as hidden input , for updating] */
        for (var k in last_insertions){
            if (last_insertions.hasOwnProperty(k)) {
                 div_html += '<input class="table-id-hidden" id="'+k+'" name="'+k+'" type="hidden" value="'+last_insertions[k]+'">';
            }
        }
        $('.invisible-div').html(div_html);
        
    if(order_status != 'Saved') {
        $('#create_tesco_order_form input').attr('readonly', 'readonly');
        $('.selectpicker').prop('disabled', true);
        $('#tesco_manufacture_date').prop('disabled',true);
        $("#datePicker1").datepicker("disabled");
        $('.delete-row, .add-row-btn, .submit-btn, .save-btn').remove();
        if(order_status === 'Order Placed')
        {
            $('.approve-btn').show();
            $('.cancel-btn').show();
        }
        else 
        {
            $('.approve-btn').hide();
            $('.cancel-btn').hide();
        }
    }

    /** [For MOQ validation after page load] */
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
	/** [ Refresh boostrap form-validator ]  */
	$('#create_tesco_order_form').validator('validate');
    $(".selectpicker").selectpicker('refresh');
}
/**
 * Function to load the artworks for correspinding style number
 * @param  {string} style     Style Number of the order
 * @param  {object} artwork_collection Collection of all orders
 */
function loadArtworkNames(artwork_collection_style) {
    if(Object.keys(artwork_collection_style).length !== 0 && artwork_collection_style.constructor === Object) {
        $('.artwork_list').removeClass('hidden');
        var options="<option value=''>Search Artwork</option>";
        $.each(artwork_collection_style, function(po,templs) {
            var awArray = templs.split(",");
            var ratioTCL  = [];
            var ratioTCLArtworks  = [];
            var singleTCL = [];
            var singleTCLArtworks = [];
            var ratioTHL = [];
            var ratioTHLArtworks = [];
            var singleTHL = [];
            var singleTHLArtworks = [];
            var ratioTCLStatus, singleTCLStatus, ratioTHLStatus, singleTHLStatus;
            for (i = 0; i < awArray.length; i++) { 
                var templateInfo = awArray[i].split("__");
                switch (templateInfo[1]) {
                    case 'TCL 1':
                        ratioTCL.push(templateInfo[1]);
                        ratioTCLArtworks.push(templateInfo[0]);
                        ratioTCLStatus = templateInfo[2];
                        break;
                    case 'TCL 2':
                        ratioTCL.push(templateInfo[1]);
                        ratioTCLArtworks.push(templateInfo[0]);
                        ratioTCLStatus = templateInfo[2];
                        break;
                    case 'TCL 4':
                        singleTCL.push(templateInfo[1]);
                        singleTCLArtworks.push(templateInfo[0]);
                        singleTCLStatus = templateInfo[2];
                        break;
                    case 'TSL 1':
                        ratioTCL.push(templateInfo[1]);
                        ratioTCLArtworks.push(templateInfo[0]);
                        ratioTCLStatus = templateInfo[2];
                        break;
                    case 'TSL 3':
                        singleTCL.push(templateInfo[1]);
                        singleTCLArtworks.push(templateInfo[0]);
                        singleTCLStatus = templateInfo[2];
                        break;
                    case 'THL 1':
                        ratioTHL.push(templateInfo[1]);
                        ratioTHLArtworks.push(templateInfo[0]);
                        ratioTHLStatus = templateInfo[2];
                        break;
                    case 'THL 3':
                        singleTHL.push(templateInfo[1]);
                        singleTHLArtworks.push(templateInfo[0]);
                        singleTHLStatus = templateInfo[2];
                }
            }
            if (typeof ratioTCL !== 'undefined' && ratioTCL.length > 0 && typeof ratioTCLArtworks !== 'undefined' && ratioTCLArtworks.length > 0) {
                options+="<option data-icon='glyphicon glyphicon-stop "+ratioTCLStatus+"' value='"+po+"' data-subtext='"+po+"' data-tempid='"+ratioTCLArtworks.join()+"'>"+ratioTCL.join()+"</option>";
                
            }

            if (typeof singleTCL !== 'undefined' && singleTCL.length > 0 && typeof singleTCLArtworks !== 'undefined' && singleTCLArtworks.length > 0) {
                options+="<option data-icon='glyphicon glyphicon-stop "+singleTCLStatus+"'  value='"+po+"' data-subtext='"+po+"' data-tempid='"+singleTCLArtworks.join()+"'>"+singleTCL.join()+"</option>";
                
            }

            if (typeof ratioTHL !== 'undefined' && ratioTHL.length > 0 && typeof ratioTHLArtworks !== 'undefined' && ratioTHLArtworks.length > 0) {
                options+="<option data-icon='glyphicon glyphicon-stop "+ratioTHLStatus+"' value='"+po+"' data-subtext='"+po+"' data-tempid='"+ratioTHLArtworks.join()+"'>"+ratioTHL.join()+"</option>";
                
            }

            if (typeof singleTHL !== 'undefined' && singleTHL.length > 0 && typeof singleTHLArtworks !== 'undefined' && singleTHLArtworks.length > 0) {
                options+="<option data-icon='glyphicon glyphicon-stop "+singleTHLStatus+"'  value='"+po+"' data-subtext='"+po+"' data-tempid='"+singleTHLArtworks.join()+"'>"+singleTHL.join()+"</option>";
                
            }
        });
        $('#artwork_list').html(options);
        $('#artwork_list').selectpicker('refresh');
    } else {
        throw "artwork_collection_empty";
    }
}