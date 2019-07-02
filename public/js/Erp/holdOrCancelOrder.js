$(document).ready(function () {

	$('#edit_user_roles').val('0').selectpicker('refresh');
	$('#admin-orders-table').DataTable();
})
/** submit add customer form and check validation **/
$('#add_user_form').validator().on('submit', function (e) {
	if (!e.isDefaultPrevented()) {
		e.preventDefault();
		/* Form Data Manipulation */
		var templates = [];
		/* Get value of templates from tree */
		$('#user_buyer').find('option:selected').each(function () {
			$this = $(this);
			var treeTxt = $this.text();
			var treeId = treeTxt + "_tree";

			$.each($('#' + treeId).jstree().get_selected(true), function () {
				templates.push($(this)[0].id + "_" + treeTxt);
			});
		});
		/* Formatted Form Data */
		var data = {
			name: $('#user_name').val(),
			email: $('#user_email').val(),
			company: $('#user_company').val(),
			roles: $('#user_roles').val(),
			buyer: $('#user_buyer').val(),
			templates: templates,
			_token: $('meta[name="csrf-token"]').attr('content')
		};

		if (templates.length) {
			/* Pass to AJAX request */
			store('/erp/users', data);
		} else {
			$('.error_label').removeClass('hidden')
		}
	}
});
/** submit add customer form and check validation **/
$('#edit_user_form').validator().on('submit', function (e) {
	if (!e.isDefaultPrevented()) {
		e.preventDefault();
		/* Form Data Manipulation */
		var templates = [];
		/* Get value of templates from tree */
		$('#edit_user_buyer').find('option:selected').each(function () {
			$this = $(this);
			var treeTxt = $this.text();
			var treeId = treeTxt + "_tree";

			$.each($('#' + treeId).jstree().get_selected(true), function () {
				templates.push($(this)[0].id + "_" + treeTxt);
			});
		});
		/* Formatted Form Data */
		var data = {
			id: $('#hiddenId').val(),
			name: $('#edit_user_name').val(),
			email: $('#edit_user_email').val(),
			company: $('#edit_user_company').val(),
			roles: $('#edit_user_roles').val(),
			buyer: $('#edit_user_buyer').val(),
			templates: templates,
			_token: $('meta[name="csrf-token"]').attr('content')
		};

		if (templates.length) {
			/* Pass to AJAX request */
			update('/erp/users', data);
		} else {
			$('.edit_error_label').removeClass('hidden')
		}
	}
});
/* Delete User */
$(document).on('click', '.holdOrder', function () {
	$('#holdModal').modal('show')
	$(document).find('#hold_id').val($(this).closest('tr').attr('id'));
});
/* Get Template Tree */
$(function () {
	$("#user_buyer").on("changed.bs.select", function (e, clickedIndex, newValue) {
		var sVal = $(this).find('option').eq(clickedIndex).val();
		var selectedTxt = $(this).find('option').eq(clickedIndex).text();
		if (newValue) {
			getTemplate(selectedTxt, sVal);
		} else {
			$('#templateHiecs').find('div#' + sVal).remove();
			if (!$("#user_buyer").val().length) {
				$('#templateDiv').addClass('hidden')
			}
		}
	});
});
/* Get User Details */
$(function () {
	$(document).on("click", ".edit-user", function () {

		$('#editTemplateDiv').addClass('hidden');
		$('#editTH').html('')
		var id = $(this).closest('tr').attr('id')
		$('#hiddenId').val(id)
		$.ajax({
			url: base_url + "/erp/get-user",
			type: "get",
			data: {
				id: id
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				var data = data.data[0];
				var roles = data.roles.map(a => a.id);
				var buyer = data.buyer.map(a => a.id);
				var company = data.company.map(a => a.id);
				$('#edit_user_roles').selectpicker('val', roles);
				$('#edit_user_name').val(data.name);
				$('#edit_user_email').val(data.email);
				$('#edit_user_company').selectpicker('val', company);
				$('#edit_user_buyer').selectpicker('val', buyer);
				$('#edit_user_form').validator('validate');
				/* Rendering Templates */
				if (Object.values(data.templates).length) {
					/*	Append Jstree Arrays  */
					$.each(Object.values(data.templates), function (index, value) {
						var head = Object.keys(data.templates)[index].toUpperCase();
						$('#editTH').append(`<div class="col-md-6 treeDiv" id="` + head + `" style="box-shadow: 0px 1px 5px 1px #eee;padding:10px 0px;">
								<p style="font-weight: bold;" class="text-center">` + head + `</p>
	
								<div id="` + Object.keys(data.templates)[index] + `_tree"> </div>
							</div>`);

						$('#' + Object.keys(data.templates)[index] + '_tree').jstree({
							'core': {
								'data': JSON.parse(value),
								"themes": {
									"icons": false
								}
							},
							"plugins": ["checkbox"]
						});
					});
					/*	User specific Jstree Data  */
					setTimeout(function () {
						$.each(data.user_template, function (index, value) {
							var $tree = $(document).find('#editTH').find('#' + index + '_tree');
							for (var i = 0; i < value.length; i++) {
								$tree.jstree(true).select_node(value[i])
							}
						});
					}, 100);
					$('#editTemplateDiv').removeClass('hidden');
				}

			},
			complete: function () {
				$(".loader").hide();
				$('#editModal').modal('show');
			}
		});
	});
});

/* 	Hold Order 	*/
$(document).on("click", ".cOrder, .hOrder, .rOrder", function () {
	var id = $(this).data('id');
	var status = $(this).data('status');
	$.ajax({
		url: "erp-order",
		type: "delete",
		data: {
			id: id,
			status: status,
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			location.reload()
		},
		complete: function () {
			$(".loader").hide();
		}
	});
});
/* Get Order Details */
$(document).on("click", ".getODetails", function () {
	var id = $(this).closest('tr').attr('id')

	$.ajax({
		url: "erp-order",
		type: "get",
		data: {
			id: id
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			/* Load Partial View */
			$('#appendModal').html(data);
			$('#detailsModal').modal('show');
			/* Get Order Details */
			$('.activeItems').confirmation({
				rootSelector: '[data-toggle=confirmation]',
				onConfirm: function (value) {
					updateOrderItem(value, this);
				},
				buttons: [{
						class: 'btn btn-warning btn-xs',
						label: 'Hold Order',
						value: 1
						},
					{
						class: 'btn btn-danger btn-xs',
						label: 'Cancel Order',
						value: 2
						},

					{
						class: 'btn btn-primary btn-xs',
						label: 'Back',
						cancel: true
						}
					]
			});

			$('.holdItems').confirmation({
				rootSelector: '[data-toggle=confirmation]',
				onConfirm: function (value) {
					updateOrderItem(value, this);
				},
				buttons: [{
						class: 'btn btn-warning btn-xs',
						label: 'Restore Order',
						value: 3
						},
					{
						class: 'btn btn-danger btn-xs',
						label: 'Cancel Order',
						value: 2
						},

					{
						class: 'btn btn-primary btn-xs',
						label: 'Back',
						cancel: true
						}
					]
			});

		},
		complete: function () {
			$(".loader").hide();
		}
	});
});

var updateOrderItem = function (value, obj) {
	var holdordername = $(obj).data('holdordername');
	if(value == 1)
	{
		$(obj).parent().parent().find('.label').text('HOLD')
		$(obj).parent().parent().find('.label').removeClass('label-danger').removeClass('label-success')
		$(obj).parent().parent().find('.label').addClass('label-warning')
		$(obj).parent().html('<i id="'+$(obj).attr('id')+'" data-toggle="confirmation" data-placement="left" data-popout="true" data-holdordername="'+holdordername+'" data-singleton="true" class="fa fa-pencil filterbtn options updateOItem fa-button holdItems"></i>')
	}
	else if(value == 2)
	{
		$(obj).parent().parent().find('.label').text('Cancelled')
		$(obj).parent().parent().find('.label').removeClass('label-warning').removeClass('label-success')
		$(obj).parent().parent().find('.label').addClass('label-danger')
		
		$(obj).parent().html('')
	}
	else
	{
		
		$(obj).parent().parent().find('.label').text(holdordername)
		$(obj).parent().parent().find('.label').removeClass('label-danger').removeClass('label-warning')
		$(obj).parent().parent().find('.label').addClass('label-success')
		$(obj).parent().html('<i id="'+$(obj).attr('id')+'" data-toggle="confirmation" data-placement="left" data-popout="true" data-holdordername="'+holdordername+'" data-singleton="true" class="fa fa-pencil filterbtn options updateOItem fa-button activeItems"></i>')
	}
	$('.activeItems').confirmation({
				rootSelector: '[data-toggle=confirmation]',
				onConfirm: function (value) {
					updateOrderItem(value, this);
				},
				buttons: [{
						class: 'btn btn-warning btn-xs',
						label: 'Hold Order',
						value: 1
						},
					{
						class: 'btn btn-danger btn-xs',
						label: 'Cancel Order',
						value: 2
						},

					{
						class: 'btn btn-primary btn-xs',
						label: 'Back',
						cancel: true
						}
					]
			});

			$('.holdItems').confirmation({
				rootSelector: '[data-toggle=confirmation]',
				onConfirm: function (value) {
					updateOrderItem(value, this);
				},
				buttons: [{
						class: 'btn btn-warning btn-xs',
						label: 'Restore Order',
						value: 3
						},
					{
						class: 'btn btn-danger btn-xs',
						label: 'Cancel Order',
						value: 2
						},

					{
						class: 'btn btn-primary btn-xs',
						label: 'Back',
						cancel: true
						}
					]
			});
	$.ajax({
		url: "erp-order-item",
		type: "put",
		data: {
			val: value,
			id: $(obj).attr('id'),
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		beforeSend: function () {
			$(".loader").show();
		},
		statusCode: {
			/** [ Show response status based on HTTP status code ] */
			200: function (data) {
				$('.snackbar_span').html(data.message);
			},
			500: function () {
				$('.snackbar_span').html("Something Went Wrong");
			}
		},
		complete: function () {
			$(".loader").hide();
			showSnackbar();
		}
	});
}