/**
* Function for loading the template preview
*/
function loadTemplatePreview() {
    var template_id = $('#po_number').find(':selected').data('tempid');
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
            url: base_url + "/admin/tesco/view-order",
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
    var header = 'Create Order For TESCO - '+localStorage.getItem('zone')+' - '+localStorage.getItem('template_group');
    $('.page-header h4 strong').text(header);
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
    });
    if(artwork_status === 'Saved') {

    } else {
        $('#create_tesco_order_form input').attr('readonly', 'readonly');
        $('.selectpicker').prop('disabled', true);
        $('#tesco_manufacture_date').prop('disabled',true);
        $("#datePicker1").datepicker("disabled");
        $('.delete-row, .add-row-btn, .submit-btn, .save-btn').remove();
    }
    $(".selectpicker").selectpicker('refresh');
}