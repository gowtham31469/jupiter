/** 
 * Tesco care labels JS 
 * @author Praveen
 * 
 */
$(document).ready(function() {
    $(".selectpicker").selectpicker("refresh");
});

// Menthod to render tesco care label orders page
renderTescoCareOrders = () => {
    $('.main-content').load('/admin/tesco/care-label-orders');
    $('li.year-nav-menu').addClass('hidden');
    $('li.buyer-nav-menu').addClass('hidden');
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
                 console.log(data.data.components);
                 console.log(data);
                /** [ Get data-id of add button ] */
                var button_count = $(obj).attr("data-id");
                var component_count = parseInt(button_count) + 1;
                
                var fiber_count = 1;
                var garment_comp_div = `<div class="col-md-2 component-div-`+component_count+`">
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
                                                    garment_comp_div += `<div class="col-xs-6">
                                                    <div class="form-group">
                                                    <select class="selectpicker form-control input-sm" data-live-search="true" id="tesco_care_fiber" name="tesco_care_fiber" required="">
                                                    <option value="">-- Select Fiber --</option>`;
                                                    $.each(data.data.fabriclists, function(i, item){
                                                        garment_comp_div += `<option value=` + item + `>` + item + `</option>`;
                                                    });
                                                garment_comp_div += `</select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control input-sm">
                                                            <div class="input-group-addon">%</div>
                                                        </div>
                                                        </div>
                                                    </div>`;
                                                    fiber_count++
                                                }
                    garment_comp_div += `<div class="col-xs-6">
                                                    <h5 class="text-right"><strong>Total</strong></h5>
                                                </div>
                                                <div class="col-xs-6">
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
function removeGarmentComponent(obj, event) {
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