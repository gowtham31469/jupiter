function getTemplate(buyer, sVal) {

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
			console.log(typeof data)
			$('#templateDiv').removeClass('hidden')
			$('#templateHiecs').append(`<div class="col-md-6" id=` + sVal + ` style="border: 1px solid #eee;border-radius: 4px;box-shadow: 1px 1px 1px #eee;padding:10px 0px;"><p style="font-weight: bold;" class="text-center">` + buyer.toUpperCase() + `</p><div id="` + buyer + `_tree"> </div></div>`);

			$('#' + buyer + '_tree').jstree({
				'core': {
					'data': data,
					"themes":{
						"icons":false
					}
				},
				
				"plugins": ["checkbox"]
			})
		},
		error: function (data) {

		},
		complete: function () {
			$(".loader").hide();
		}
	})
}