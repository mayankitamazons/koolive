$(document).ready(function(){
	tinymce.init({ selector:'.text_editor' });
	$('#form_addBroadcast').submit(function(e){
		e.preventDefault();
	    var text = tinymce.activeEditor.getContent();
	    $.ajax({
			url: '/broadcast/addBroadcast',
			data: $('#form_addBroadcast').serialize()+'&text='+text,
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
					
			        swal({
			            title: "Success!",
			            text: "Successfully Added!",
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