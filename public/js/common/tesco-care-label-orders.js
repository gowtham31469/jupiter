/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
$(document).ready(function() {
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
var addComponent = (obj, event) => {
    $.ajax({
        url: base_url + "/tesco/care-label-fiber-components",
        type: "get",
        dataType:"json",
        data: {_token:csrf_token},
        statusCode: {
             /** [Show response status based on HTTP status code] */
             200: function(data) {
                /** [ Get data-id of add button ] */
                var button_count = $(obj).attr("data-id");
                var component_count = parseInt(button_count) + 1;
                
                var fiber_count = 1;
                var garment_comp_div = `<div class="col-md-4 component-div-`+component_count+`">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                <i class="fa fa-trash pull-right remove-component-btn" aria-hidden="true" title="Delete" data-placement="left" onclick="removeGarmentComponent(this,event)"></i>
                                                <div class="form-group">
                                                    <label for="tesco_care_garment_component" class="component-label">Garment Component</label>
                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_garment_component" name="tesco_care_garment_component" required="">
                                                    <option value="">-- Select Garment Component --</option>`;
                                                    $.each(data.data.components, function(i, item){
                                                        garment_comp_div += `<option value=` + i + `>` + item + `</option>`;
                                                    });
                                                    garment_comp_div += `</select>
                                                    <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>`;
                                                while (fiber_count <= 6) {
                                                    garment_comp_div += `<div class="col-xs-9">
                                                    <div class="form-group">
                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber" name="tesco_care_fiber" required="">
                                                    <option value="">-- Select Fiber --</option>`;
                                                    $.each(data.data.fabriclists, function(i, item){
                                                        garment_comp_div += `<option value=` + item + `>` + item + `</option>`;
                                                    });
                                                garment_comp_div += `</select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control input-sm">
                                                            <div class="input-group-addon">%</div>
                                                        </div>
                                                        </div>
                                                    </div>`;
                                                    fiber_count++
                                                }
                    garment_comp_div += `<div class="col-xs-9">
                                                    <h5 class="text-right"><strong>Total</strong></h5>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm">
                                                        <div class="input-group-addon">%</div>
                                                    </div>
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
        },
        error: function(errorXHR, errorStatus, errorThrown) {
           
        },
        complete: function () {
                 
        }
    });
}

// Remove garment component
removeGarmentComponent = (obj, event) => {
    try {
        console.log('PKDD');
        event.preventDefault();
        /** [ Remove Row from table ] */
        $(obj).parent().parent().parent().remove();	
        
    } catch (e) {
        /** [ error statements ] */
        console.log("Error:" + e)
    }
}

// fetch tesco care size chart lists

getSizechart = (size_id) => {
    $.ajax({
        type: "get",
        url: base_url + "/tesco/care-size-lists",
        dataType:"json",
        data: {_token:csrf_token, size_id:size_id},
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
                                            console.log(`PKD:::`+valueOfElement.uk_size);
                                             quantity_html += `<tr>
                                                                    <td >`+valueOfElement.uk_size+`</td>
                                                                    <td >`+valueOfElement.ce_size+`</td><td>`;
                                                                    $.each(valueOfElement.sec_size_chart, function (index, value) { 
                                                                        quantity_html += `<dd><strong>`+value.category_name+`</strong> : `+ value.secondary_size +`</dd>` 
                                                                    });
                                                                    quantity_html += `</td><td><div class="form-group has-feedback"><input class="form-control input-sm" placeholder="UK EAN Number" required><div class="help-block with-errors"></div></div></td>
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