/* Generic Function to Store values using AJAX */ 
function store(url, data) {
	try {
console.log(base_url + url)
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$.ajax({
			url: base_url + url,
			method: 'POST',
			data: data,
			tryCount: 0,
			retryLimit: 2,
			beforeSend: function () {
				$(".loader").show();
			},
			statusCode: {

				/** [ Show response status based on HTTP status code ] */
				200: function (data) {
					$('.snackbar_span').html(data.message);
					showSnackbar();
//					setTimeout(function(){
//						location.reload();
//					}, 500);
				},
				400: function () {
					$('.snackbar_span').html(data.message);
					showSnackbar();
				}
			},
			error: function (errorXHR, errorStatus, errorThrown) {
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
	} catch (e) {
		console.log("Error:" + e)
	}
}