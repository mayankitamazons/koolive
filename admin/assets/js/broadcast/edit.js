$(document).ready(function(){
	//$('#form_editBlog .blog_info').froalaEditor();
	tinymce.init({ selector:'.text_editor' });
	$('#form_editBroadcast').submit(function(e){
		e.preventDefault();
		//console.log($("#form_editBlog").serialize());
	    var text = tinymce.activeEditor.getContent();
		$.ajax({
			url: '/broadcast/editBroadcast/',
			data: $("#form_editBroadcast").serialize()+'&text='+text,
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
			        swal({
			            title: "Success!",
			            text: "Successfully Updated!",
			            type: "success"
			        }, function() {
			            window.location = "/broadcast/index";
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