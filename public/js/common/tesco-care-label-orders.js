/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
$(function() {
    $(".selectpicker").selectpicker("refresh");

    // Method to render tesco care label orders page
    renderTescoCareOrders = (size_id, size_category, size_category_text) => {
        $('.main-content').load('/admin/tesco/care-label-orders');
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');
        
        getSizechart(size_id, size_category, size_category_text);

    }

    // Remove garment component
    $(document).on('click', '#remove_component_btn', function(){
        $(this).closest('.component-div').addClass('hidden');
        $(this).parent().remove();
    });

    // toggle readonly prop of total percentage field
    $(document).on("focusin", ".composition-percentage", function() {
        $(this).prop('readonly', true);  
    });
    
    $(document).on("focusout", ".composition-percentage", function() {
        $(this).prop('readonly', false); 
    });

    // Method to Calculate total fiber percentage
    calculateTotalFiberPercentage = () => {
    
        var table = 1;
        $('.component-div').each(function () {
            var total_fiber_percentage = 0;
            $(this).find('.fiber-individual-percentage-' + table).each(function () {
                var individual_percentage = $(this).val();
                if (!isNaN(individual_percentage) && individual_percentage.length !== 0) {
                    total_fiber_percentage += parseInt(individual_percentage);
                }
            });
            $('#fiber_total_percentage_' + (table)).val(total_fiber_percentage);
            table++;
        });

        $(".selectpicker").selectpicker("refresh");
        $('#tesco_care_label_form').validator('update');
    }
    
    $('#add_garment_component_btn').click( function(){
        addComponent(this);
    });

    // Method to add dynamic garment component
    addComponent = (obj) => {
        $.ajax({
            url: base_url + "/admin/tesco/care-label-fiber-components",
            type: "get",
            dataType: "json",
            data: {
                _token: csrf_token
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function (data) {
                    /** [ Get data-id of add button ] */
                    var button_count = $(obj).attr("data-id");
                    var component_count = parseInt(button_count) + 1;

                    var fiber_count = 1;
                    var garment_comp_div = `<div class="col-md-4 component-div">
                                                <div class="row">
                                                   <i class="fa fa-trash pull-right remove-component-btn" aria-hidden="true" title="Delete" data-placement="left" id="remove_component_btn"></i>
                                                    <div class="col-xs-12">
                                                    <div class="form-group has-feedback">
                                                        <label for="tesco_care_garment_component_`+component_count+`" class="component-label">Garment Component </label>
                                                        <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_garment_component_`+component_count+`" name="tesco_care_garment_component[]">
                                                        <option value="">-- Select Garment Component --</option>`;
                    $.each(data.data.components, function (i, item) {
                        garment_comp_div += `<option value=` + i + `>` + item + `</option>`;
                    });
                    garment_comp_div += `</select>
                                                <div class="help-block with-errors"></div>
                                                </div>
                                            </div>`;
                    while (fiber_count <= 6) {
                        garment_comp_div += `<div class="col-xs-9">
                                                        <div class="form-group has-feedback">
                                                        <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber_`+component_count+`_`+fiber_count+`" name="tesco_care_fiber[]">
                                                        <option value="">-- Select Fiber --</option>`;
                        $.each(data.data.fabriclists, function (i, item) {
                            garment_comp_div += `<option value=` + item + `>` + item + `</option>`;
                        });
                        garment_comp_div += `</select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group has-feedback">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control input-sm fiber-individual-percentage-`+component_count+`" onkeyup="return calculateTotalFiberPercentage()" max="100"  id="fiber_individual_percentage_`+component_count+`_`+fiber_count+`" name="fiber_individual_percentage[]" data-max-error= "Value must be <= 100">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
                                                    <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>`;
                        fiber_count++
                    }
                    garment_comp_div += `<div class="col-xs-9">
                                                        <h5 class="text-right"><strong>Total</strong></h5>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group has-feedback">
                                                        <div class="input-group">
                                                            <input type="number" class="form-control input-sm composition-percentage total-percentage-`+component_count+`" id="fiber_total_percentage_`+component_count+`" name="fiber_total_percentage[]" min="100" max="100">
                                                            <div class="input-group-addon">%</div>
                                                        </div>
                                                        <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                    $('.garment-component-div').append(garment_comp_div);

                    /** [ Update data-id of add button ] */
                    $(obj).attr("data-id", component_count);

                    $(".selectpicker").selectpicker("refresh");
                    $('#tesco_care_label_form').validator('update');
                }
            }
        });
    }


    // Method to fetch tesco care size chart lists
    getSizechart = (size_id, size_category, size_category_text) => {

        $.ajax({
            type: "get",
            url: base_url + "/admin/tesco/care-size-lists",
            dataType: "json",
            data: {
                _token: csrf_token,
                size_id: size_id,
                size_category:size_category
            },
            success: function (response) {
                var quantity_html = `<input type="hidden" id="primary_size_id" name="primary_size_id" value="`+size_id+`">
                                        <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>UK</th>
                                                <th>EUR</th>`;
                                                for (let index = 0; index < size_category_text.length; index++) {
                                                    quantity_html+=  `<th>`+size_category_text[index]+`</th>`;
                                                }
                                              quantity_html+=  `
                                                <th>UK EAN</th>
                                                <th>CE EAN</th>
                                                <th>Quantity <small>(Units)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>`;
                $.each(response.data, function (indexInArray, valueOfElement) {
                    quantity_html += `<tr>
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.uk_size + `</td>
                                            <td >` + valueOfElement.ce_size + `</td>`;
                                            
                                            for (let index = 0; index < size_category_text.length; index++) {
                                                let category_value = valueOfElement.secondary_sizes[size_category_text[index]] != undefined?valueOfElement.secondary_sizes[size_category_text[index]]:'-';
                                                quantity_html+=  `<td>`+category_value+`</td>`;
                                            }
                                           quantity_html+= `<td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" id="uk_ean_`+indexInArray+`" name="uk_ean[]" required><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" id="ce_ean_`+indexInArray+`" name="ce_ean[]" required><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control input-sm label-quantity-field" placeholder="Quantity" id="quantity_`+indexInArray+`" name="quantity[]"><div class="help-block with-errors"></div></div></td>
                                        </tr>`;
                });
                quantity_html += `</tbody>
                                        </table>
                                        </div>`;
                $('.teasco-care-quantity-div').html(quantity_html);

                // show only for "Baby Sleeping Bags"
                if(size_id === '4'){
                    $('#baby_sleeping_bag_div').removeClass("hidden");
                }
            }
        });
    }

    // Get MOQ value based on supplier value.
    $('#tesco_care_supplier').on('change',function(){ 
        var supplier_id = $('#tesco_care_supplier option:selected').val();
        var buyer_id  = localStorage.getItem("buyer_id");
        var templates = localStorage.getItem("templates");
        if (supplier_id != '') {
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
		                 /** hide quantity missing error msgs */
						 $('.label-quantity-feedback').removeClass('has-error has-danger');
						 $('.label-quantity-feedback-err-msg').css('display', 'none');
						 $('.label-quantity-field').prop('required', false);
						 $('.label-quantity-field').parent().removeClass('has-error')
						 $('.help-block').html('');
		                 /** [ Dynamic moq value assignment on supplier change ] */
						 $('.label-quantity-field').each(function () {
                                $(this).attr("min", data.moq[0][localStorage.getItem("template_group")]); 
                                $(this).data("min-error","Qty should be >= "+data.moq[0][localStorage.getItem("template_group")]); 
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
				$('.label-quantity-feedback').removeClass('has-error has-danger');
				$('.label-quantity-feedback-err-msg').css('display', 'none');
				$('.label-quantity-field').prop('required', false);
				$('.label-quantity-field').parent().removeClass('has-error')
				$('.help-block').html('');
				/** [ Remove MOQ values] */
                $('.label-quantity-field').each(function () {
                        $(this).removeAttr("min"); 
                        $(this).data("min-error",""); 
                });
		}
    });


    // Method to validate and submit tesco care label orders
    submitTescoCareLabelOrder = () => {
        $('#tesco_care_label_form').validator('validate');

        /** [ bootstrap validator for create tesco order form ] **/
        $('#tesco_care_label_form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {

            } else {
                e.preventDefault();
                submitCarelabelOrder();
            } 
        });
    }

    // Method to Save tesco care label orders
    submitCarelabelOrder = () => {
        var form_obj = [{
            "template_id": localStorage.getItem("templates"),
            "include_btn_sew_space": $("#tesco_care_button_space").prop(":checked")?1:0,
            "exclusive_trims": $("#tesco_care_exclusive_trim").prop(":checked")?1:0,
            "contains_non_textile_part": $("#tesco_care_non_textile_part").prop(":checked")?1:0,
            "part_of_set": $("#tesco_care_part_of_set").prop(":checked")?1:0,
            "bs_en_declaration": $("#tesco_care_bs_en").prop(":checked")?1:0,
            "baby_sleeping_bag_symbol": $("#tesco_care_sleep_bag_symbol").prop(":checked")?1:0
        }];
        var formData = $('#tesco_care_label_form').serialize()+ '&' + $.param(form_obj[0]);

        $.ajax({
            type: "post",
            url: base_url + "/admin/tesco/submit-care-label-order",
            data: formData,
            dataType: "json",
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function(response) {
                    console.log(JSON.stringify(response))
                    $('#result').html('<div class="alert alert-success">Your Order Has Been Submitted Successfully</div>');
                },
                400: function(error) {
                    $('.snackbar_span').html("Something went wrong!!");
                    showSnackbar();
                },
                422: function(errorXHR) {
                    var error_msg = ``;
                    $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) { 
                            error_msg += `<li>`+valueOfElement+`</li>`;
                    });
                    $('#result').html('<div class="alert alert-danger">'+error_msg+'</div>');
                    $(window).scrollTop(0);
                }
            }
        });
    }
});

