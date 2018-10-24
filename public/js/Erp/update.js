/* Generic Function to Store values using AJAX */ 
function update(url, data) {
	try {
		$.ajax({
			url: base_url + url,
			method: 'PUT',
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
					setTimeout(function(){
						location.reload();
					}, 1000);
				},
				400: function (data) {
					$('.snackbar_span').html(data.message);
				},
				409: function (data) {
					$('.snackbar_span').html(data.responseJSON.message);
				},
				500: function () {
					$('.snackbar_span').html("Something Went Wrong");
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
				showSnackbar();
			}
		})
	} catch (e) {
		console.log("Error:" + e)
	}
}