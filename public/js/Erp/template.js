function getTemplate(buyer, sVal){
	
	$.ajax({
		url: base_url + "/erp/get-template",
		type: "post",
		dataType: "json",
		data: {
			_token: $('meta[name="csrf-token"]').attr('content'),
			buyer: buyer
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#templateDiv').removeClass('hidden')
			$('#templateHiecs').append(`<div class="col-md-4" id=`+sVal+` style="box-shadow: 2px 2px 10px 2px #eee;"><p style="font-weight: bold;margin-top: 10px;" class="text-center">`+buyer.toUpperCase()+`</p><div id="`+buyer+`_tree"> </div></div>`);
				
			$('#'+buyer+'_tree').jstree({
				'core': {
					'data': data
				},
				"types": {
					"default": {
						"icon": "fa fa-folder"
					}
				},
				"plugins": ["checkbox"]
			});
		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
		}
	})
}