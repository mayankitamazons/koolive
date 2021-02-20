$(document).ready(function(){
	var category_id = $("#form_editCategory input[name='id']").val();
	if(category_id>0){
		$.ajax({
			url: '/category/getCategoryById/'+category_id,
			type: 'post',
			dataType: 'json',
			success: function(res){
				console.log(res);
				var category = res['category'];
				var url = res['url'];
				$("#form_editCategory input[name='category']").val(category);
				$("#form_editCategory input[name='url']").val(url);
			}
		});
	}
	$('#form_editCategory').submit(function(e){
		e.preventDefault();
		//console.log($("#form_editCategory").serialize());
		$.ajax({
			url: '/category/editCategory/',
			data: $("#form_editCategory").serialize(),
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
			        swal({
			            title: "Success!",
			            text: "Successfully Updated!",
			            type: "success"
			        }, function() {
			            window.location = "/category/index";
			        });
				}
				else if(res['state']==false){
					swal({
			            title: "Fail!",
			            text: res['msg'],
			            type: "error"
			        });
				}
			}
		});
	});
});