	function dynamicTable() {
	    $('.dynamic-table').html('<table class="table "><thead><tr><th></th><th class="text-center">Order ID</th><th class="text-center">PO Number</th><th class="text-center">Style No</th></tr></thead><tbody><tr><td><input name="select_all" class="po-select" value="1" data-id="vals1" id="example-select-all" type="checkbox" /></td><td>Tiger Nixon</td><td>System Architect</td><td>Edinburgh</td></tr><tr><td><input name="select_all" value="2" data-id="vals2" id="example-select-all" class="po-select" type="checkbox" /></td><td>Tiger Nixon</td><td>System Architect</td><td>Edinburgh</td></tr></tbody></table>')
	    $('.po-list').removeClass('hidden')

	    $('.dynamic-table tbody').on('change', 'input[type="checkbox"]', function(){
	      $('.po-select').not(this).prop('checked', false);  
	      showOrderList(this)                           
	    });
	}
    function showOrderList(obj) {
        var row_id = $(obj).val()
        var table  = $(obj).attr('data-id')
        
        var checkboxes = $('.dynamic-table td input[type="checkbox"]');
        if(checkboxes.filter(':checked').length >0){
            $('.order-details').html('<table class="table text-center"><thead><tr><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Style No</th><th class="text-center">Reference No</th><th class="text-center">Zone</th><th class="text-center">Style No</th><th class="text-center">Reference No</th></tr></thead><tbody><tr><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td><input type="text" value="10" class="form-control" onkeyup="updateAmount(this)"></td><td>1</td><td>1</td></tr><tr><td colspan="11">Total</td><td>1</td><td>1</td><td>1</td></tr></tbody></table>')
            $('.order-details').removeClass('hidden')
            // var editable = document.getElementById('qnty1');
            // alert(editable)
            // editable.addEventListener('input', function() {
            //     alert('Hey, somebody changed something in my text!');
            // });
        }
        else{
            $('.order-details').addClass('hidden')
        }

    }

    function updateAmount(obj) {
        alert($(obj).val())
    }