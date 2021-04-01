/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
$(document).ready(function () {
    $(".selectpicker").selectpicker("refresh");
});

// Menthod to render tesco care label orders page

renderTescoCareOrders = (size_chart) => {
    $('.main-content').load('/admin/tesco/care-label-orders');
    $('li.year-nav-menu').addClass('hidden');
    $('li.buyer-nav-menu').addClass('hidden');
    getSizechart(size_chart);
}

// Menthod to validate and submit tesco care label orders
submitTescoCareOrders = () => {
    $('#create_tesco_care_order_form').validator('validate');

    /** [ bootstrap validator for create tesco order form ] **/
    $('#create_tesco_care_order_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            alert();
        }
    });
}

// Method to add dynamic garment component
var addComponent = (obj) => {
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
                                                <div class="col-xs-12">

                                                <i class="fa fa-trash pull-right remove-component-btn" aria-hidden="true" title="Delete" data-placement="left" ></i>
                                                <div class="form-group has-feedback">
                                                    <label for="tesco_care_garment_component_`+component_count+`" class="component-label">Garment Component</label>
                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_garment_component_`+component_count+`" name="tesco_care_garment_component_`+component_count+`">
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
                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber_`+component_count+`_`+fiber_count+`" name="tesco_care_fiber_`+component_count+`_`+fiber_count+`">
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
                                                    <input type="number" class="form-control input-sm fiber-individual-percentage-`+component_count+`" onkeyup="return calculateTotalFiberPercentage()" max="100"  id="fiber_individual_percentage_`+component_count+`_`+fiber_count+`" data-max-error= "Value must be <= 100">
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
                                                        <input type="number" class="form-control input-sm composition-percentage total-percentage-`+component_count+`" id="fiber_total_percentage_`+component_count+`"  min="100" max="100">
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
                $('#create_tesco_care_order_form').validator('update');
            }
        }
    });
}


// Remove garment component
$(document).on('click', '.remove-component-btn', function(event){
    $(this).closest('.component-div').addClass('hidden');
    $(this).parent().parent().remove();
    event.preventDefault();
    // calculateTotalFiberPercentage()
});

// toggle readonly prop of total percentage field
$(document).on("focusin", ".composition-percentage", function() {
    $(this).prop('readonly', true);  
 });
 
 $(document).on("focusout", ".composition-percentage", function() {
    $(this).prop('readonly', false); 
 });


// fetch tesco care size chart lists
getSizechart = (size_id) => {
    $.ajax({
        type: "get",
        url: base_url + "/admin/tesco/care-size-lists",
        dataType: "json",
        data: {
            _token: csrf_token,
            size_id: size_id
        },
        success: function (response) {
            var quantity_html = `<div class="table-responsive">
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
            $.each(response.data, function (indexInArray, valueOfElement) {
                quantity_html += `<tr>
                                        <td >` + valueOfElement.uk_size + `</td>
                                        <td >` + valueOfElement.ce_size + `</td>
                                        <td>42 cm</td>
                                        <td>42 cm</td>
                                        <td>42 cm</td>
                                        <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" required><div class="help-block with-errors"></div></div></td>
                                        <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="CE EAN Number" required><div class="help-block with-errors"></div></div></td>
                                        <td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="Quantity" required><div class="help-block with-errors"></div></div></td>
                                    </tr>`;
            });
            quantity_html += `</tbody>
                                    </table>
                                    </div>`;
            $('.teasco-care-quantity-div').html(quantity_html);
        }
    });
}

// Calculate total fiber percentage
calculateTotalFiberPercentage = () => {
    
    var table = 1;
    $('.component-div').each(function () {
        var total_fiber_percentage = 0;
        $(this).find('.fiber-individual-percentage-' + table).each(function () {
            var individual_percentage = $(this).val();

            if (!isNaN(individual_percentage) && individual_percentage.length !== 0) {
                total_fiber_percentage += parseInt(individual_percentage);
            }
            console.log("total_fiber_percentage :"+total_fiber_percentage);
        });
        console.log(`table :`+table);
        $('#fiber_total_percentage_' + (table)).val(total_fiber_percentage);
        table++;
    });

    $(".selectpicker").selectpicker("refresh");
    $('#create_tesco_care_order_form').validator('update');
}