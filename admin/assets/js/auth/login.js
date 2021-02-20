jQuery(document).ready(function() {
    var domain = "http://koofamilies.com/admin";
	$('.form_login').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		//console.log(data);
		$.ajax({
			url: domain + '/auth/login_check',
			data: data,
			type: 'post',
			success: function(res){
				console.log(res);
				if(res=="success"){
					window.location.href= domain + "/comments";
				}
			}
		});
		//console.log(data);
	});
});