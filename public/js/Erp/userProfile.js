document.getElementById("showProfileModal").onclick = function () {
	$(document).find('.alertProfile').addClass('hidden');
	$(document).find('.alertPwd').addClass('hidden');
	$('.profileTemplates').add($('.profileRoles')).add($('.profileBuyers')).add($('.profileCompanies')).addClass('text-center').html(`<i class="fa fa-spinner fa-pulse"></i>`);
	
	$.ajax({
		url: "/erp/profile",
		method: 'get',
		beforeSend: function () {
			$(".loader").show();
		},
		statusCode: {
			200: function(data){
				var tHtml = '', bHtml = '', rHtml = '', cHtml = '';
				
				if(data.profile.template.length){
					$.each(data.profile.template, function(index, val){
						tHtml += `<span >`+val+`</span>`
					});
				}else{
					tHtml += `<span > No Templates Assigned </span>`
				}
				

				if(data.profile.roles.length){
					$.each(data.profile.roles, function(index, val){
						rHtml += `<span >`+val+`</span>`
					});
				}else{
					rHtml += `<span > Roles Not Assigned </span>`
				}
				
				if(data.profile.buyer.length){
					$.each(data.profile.buyer, function(index, val){
						bHtml += `<span >`+val+`</span>`
					});
				}else{
					bHtml += `<span > Buyers Not Assigned </span>`
				}
				
				if(data.profile.company.length){
					$.each(data.profile.company, function(index, val){
						cHtml += `<span >`+val+`</span>`
					});
				}else{
					cHtml += `<span > Companies Not Assigned </span>`
				}
				setTimeout(function(){
					$('.profileTemplates').removeClass('text-center').html(tHtml)
					$('.profileRoles').removeClass('text-center').html(rHtml)
					$('.profileBuyers').removeClass('text-center').html(bHtml)
					$('.profileCompanies').removeClass('text-center').html(cHtml)
				}, 500)
				
			}
		},
		complete: function () {
			$(".loader").hide();
		}
	});
	
}

document.getElementById("profileBtn").onclick = function () {
	
	var name	= $(document).find('#profile_form').find('#profile_name').val();
	var email	= $(document).find('#profile_form').find('#profile_mail').val();
	$.ajax({
		url: "/erp/profile",
		method: 'PUT',
		beforeSend: function () {
			$(".loader").show();
		},
		data:{
			name:name,
			email:email,
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		statusCode: {
			200: function(data){
				$('.alertProfile').addClass('alert-success').removeClass('alert-danger').removeClass('hidden').html(data.message) 
			},
			400: function(data){
				$('.alertProfile').addClass('alert-danger').removeClass('alert-success').removeClass('hidden').html(data.responseJSON.message)
			},
			409: function(data){
				$('.alertProfile').addClass('alert-danger').removeClass('alert-success').removeClass('hidden').html(data.responseJSON.message)
			}
			
		},
		complete: function () {
			$(".loader").hide();
		}
	});
	
}

document.getElementById("pwdBtn").onclick = function () {
	
	$.ajax({
		url: "/erp/password",
		method: 'PUT',
		beforeSend: function () {
			$(".loader").show();
		},
		data:{
			old_pwd:$(document).find('#profile_form').find('#old_pwd').val(),
			new_pwd:$(document).find('#profile_form').find('#new_pwd').val(),
			new_pwd_confirmation:$(document).find('#profile_form').find('#retype_pwd').val(),
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		statusCode: {
			200: function(data){
				$('.alertPwd').addClass('alert-success').removeClass('hidden').removeClass('alert-danger').html(data.message) 
			},
			400: function(data){
				var vErrors = `<ul>`;
				$.each(data.responseJSON.message, function(index, value){
					vErrors += `<li>`+value[0]+`</li>`
				});
				vErrors += `</ul>`;
				$('.alertPwd').addClass('alert-danger').removeClass('alert-success').removeClass('hidden').html(vErrors)
			},
			409: function(data){
				$('.alertPwd').addClass('alert-danger').removeClass('alert-success').removeClass('hidden').html(data.responseJSON.message)
			}
			
		},
		complete: function () {
			$(".loader").hide();
		}
	});
}
