/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
$(function() {
    $(".selectpicker").selectpicker("refresh");

    // Method to render tesco care label orders page
    renderTescoCareOrders = (role, size_id, size_category, size_category_text) => {
        user_role = role;
        $('.main-content').load('/'+user_role+'/tesco/care-label-orders');
        $('li.year-nav-menu').addClass('hidden');
        $('li.buyer-nav-menu').addClass('hidden');
        
        getSizechart(size_id, size_category, size_category_text, user_role);

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

    // get all the secondary size categories based on primary size selection
    getSecondarySizeCategory = (size, user_type) => {
        $('#tesco_care_size_category_div').html('');
        $.ajax({
            type: "get",
            url: base_url + "/"+user_type+"/tesco/secondary-size-categories",
            data: {size:$(size).val()},
            dataType: "json",
            statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function(response) {
                    size_category_options = ``;
                    $.each(response.size_categories, function(i, item){
                        size_category_options += `<option value=` + item.id + `>` + item.category_name + `</option>`;
                    });

                    var size_category_html = `<label for="tesco_care_size_category">Size Category</label>
                        <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_size_category" name="tesco_care_size_category" required="" data-actions-box="true" multiple="true" multiple title="Choose one of the following..." data-width="100%">
                        `+size_category_options+`
                        </select>	
                    <div class="help-block with-errors"></div>`;

                    $('#tesco_care_size_category_div').append(size_category_html);

                    $('#tesco_care_size_category_div').removeClass('hidden');
                    $(".selectpicker").selectpicker("refresh");
                    $('#create_order_form').validator('update');
                },
                400: function() {
                    $('.snackbar_span').html('Something went wrong');
                    showSnackbar();
                }
            },
        });
    }

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
            $('#fiber_total_percentage_' + (table)).val(total_fiber_percentage).change();
            
            // toggle min & max validation for total percentage
            if (  total_fiber_percentage === 0 || total_fiber_percentage === 100 ) {
                    
                $('#fiber_total_percentage_' + (table)).removeAttr("min"); 
                $('#fiber_total_percentage_' + (table)).removeAttr("max"); 

            } else {

                $('#fiber_total_percentage_' + (table)).attr('min', 100);
                $('#fiber_total_percentage_' + (table)).attr('max', 100);

            }

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
            url: base_url + "/"+user_role+"/tesco/care-label-fiber-components",
            type: "get",
            dataType: "json",
            data: {
                _token: csrf_token
            },
            statusCode: {
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
                                                        <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber_`+component_count+`_`+fiber_count+`" name="tesco_care_fiber_`+component_count+`[]">
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
                                                        <input type="number" class="form-control input-sm fiber-individual-percentage-`+component_count+`" onkeyup="return calculateTotalFiberPercentage()" max="100"  id="fiber_individual_percentage_`+component_count+`_`+fiber_count+`" name="fiber_individual_percentage_`+component_count+`[]" data-max-error= "Value must be <= 100">
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
                                                            <input type="number" class="form-control input-sm composition-percentage total-percentage-`+component_count+`" id="fiber_total_percentage_`+component_count+`" name="fiber_total_percentage[]">
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
    getSizechart = (size_id, size_category, size_category_text, role) => {

        $.ajax({
            type: "get",
            url: base_url + "/"+role+"/tesco/care-size-lists",
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
                                                <th>EUR</th>
                                                <th>Secondary Size</th>
                                                <th>UK EAN</th>
                                                <th>CE EAN</th>
                                                <th>Quantity <small>(Units)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>`;
                
                size_array = [];
                $.each(response.data, function (indexInArray, valueOfElement) {
                    quantity_html += `<tr>
                                            <input type="hidden" id="secondary_size_id_` + valueOfElement.id + `" name="secondary_size_id[]" value="` + valueOfElement.id + `">
                                            <td >` + valueOfElement.uk_size + `</td>
                                            <td >` + valueOfElement.ce_size + `</td>  <td >`;

                                            secondary_sizes = [];

                                            for (var index = 0; index < size_category_text.length; index++) {

                                                var category_value = '-';
                                                var size_id = null;
                                                
                                                $.each(valueOfElement.sec_size_chart, function (index_1, category) {

                                                    if($.trim(size_category_text[index]) === $.trim(category['category_name'])){

                                                        category_value = category['secondary_size'];
                                                        size_id = category['id'];
                                                        return false;
                                                        
                                                    }

                                                });
                                                
                                                quantity_html += `<strong class="text-justify">`+$.trim(size_category_text[index])+`</strong> : `+category_value+`<br>`;

                                                size_value = category_value === '-'?null:category_value;

                                                secondary_sizes.push({
                                                    id : size_id,
                                                    secondary_size : $.trim(size_category_text[index]),
                                                    value : size_value
                                                });

                                            }
                                            
                                           quantity_html+= `</td> <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" id="uk_ean_` + valueOfElement.id + `" name="uk_ean[]" required><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" id="ce_ean_` + valueOfElement.id + `" name="ce_ean[]" required><div class="help-block with-errors"></div></div></td>
                                            <td><div class="form-group has-feedback label-quantity-feedback"><input type="number" class="form-control input-sm label-quantity-field" placeholder="Quantity" id="quantity_` + valueOfElement.id + `" name="quantity[]"><div class="help-block with-errors"></div></div></td>
                                            <input type='hidden' id='secondary_size_data_` + valueOfElement.id + `' name='secondary_size_data[]' value='` + JSON.stringify(secondary_sizes) + `'>
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
			       url:base_url + "/"+user_role+"/tesco/get-moq-value",
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
            // 'secondary_size': size_array
        }];
        var formData = $('#tesco_care_label_form').serialize()+ '&' + $.param(form_obj[0]);

        $.ajax({
            type: "post",
            url: base_url + "/"+user_role+"/tesco/submit-care-label-order",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $(".loader").show();
                $('.label-order-submit-btn').prop('disabled', true);
            },
            statusCode: {
                200: function(response) {
                    console.log(JSON.stringify(response))
                    $('#result').html('<div class="alert alert-success alert-dismissible"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been placed successfully</div>');
                    $('.label-order-cancel-btn').show();
                    postLabelOrderSubmit(response.data);
                    $(window).scrollTop(0);
                },
                400: function(error) {
                    $('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
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

    postLabelOrderSubmit = (data) => {
        $('.label-order-submit-btn').remove();

		/** [ store last updated id as hidden input , for cancel order functionality] */
		
		var div_html = '<input class="washcare-table-id-hidden" id="washcare_order_id" name="washcare_order_id" type="hidden" value="' + data.last_insertion + '">';

		$('.label-order-invisible-div').html(div_html);
    }

    cancelCareLabelOrder = () => {

        var array_list   = [];
        my_object    = new Object();

        my_object.table    = 'Washcare';
        my_object.table_id = $('#washcare_order_id').val();

        array_list.push(my_object);
        
        $.ajax({
            url: base_url + "/"+user_role+"/tesco/cancel-tesco-order",
            type: 'PUT',
            data: {
                _token: csrf_token,
                data_array:array_list
            },
            dataType: "json",
            beforeSend: function () {
                $(".loader").show();
            },
            statusCode: {
                200: function(response) {
                    $('#result').html('<div class="alert alert-success alert-dismissible"><strong>Success! </strong><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been cancelled successfully</div>');

                    $('#cancelCareLabelOrderModal').modal('hide');
                    $('.label-order-cancel-btn').remove();
                    $(window).scrollTop(0);
                },
                400: function(error) {
                    $('.snackbar_span').html("Something went wrong!!");
                    showSnackbar();
                }
            },
            complete: function () {
                $(".loader").hide();
            }
        })
        
    }

});

