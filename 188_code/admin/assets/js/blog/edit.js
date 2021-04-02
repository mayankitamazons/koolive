$(document).ready(function(){
	//$('#form_editBlog .blog_info').froalaEditor();
	tinymce.init({ selector:'.info_editor' });
	var blog_id = $("#form_editBlog input[name='id']").val();
	var oldtitle = $("#form_editBlog input[name='title']").val();
	/*if(blog_id>0){
		$.ajax({
			url: '/blog/getBlogById/'+blog_id,
			type: 'post',
			dataType: 'json',
			success: function(res){
				console.log(res);
				var title = res['title'];
				var url = res['url'];
				var info = res['content'];
				var path = res['blog_image'];
				$("#form_editBlog input[name='title']").val(title);
				$("#form_editBlog input[name='url']").val(url);
				$(".blog_image_preview").attr("src", window.location.protocol + "//" + window.location.host + "/" +path);
				//$('#form_editBlog .blog_info').froalaEditor('html.set', info);
				tinymce.activeEditor.setContent(info);

			}
		});
	}*/
	
	$('#form_editBlog').submit(function(e){
		e.preventDefault();
		//console.log($("#form_editBlog").serialize());
	    var blog_info = tinymce.activeEditor.getContent();
		var file_data = $('#edit_blog_logo').prop('files')[0];   
	    var fdata = new FormData();        
	    fdata.append('file', file_data); 
	    var url = '/blog/upload?id='+blog_id;
	    $.ajax({
	        url: url, // point to server-side PHP script 
	        dataType: 'text',  // what to expect back from the PHP script, if anything
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: fdata,                         
	        type: 'post',
	        success: function(upload_path){
	            console.log(upload_path); // display response from the PHP script, if any
	            $.ajax({
					url: '/blog/editBlog/',
					data: $("#form_editBlog").serialize()+'&info='+blog_info+'&path='+upload_path,
					type: 'post',
					success: function(res){
						console.log(res);
						if(res['state']==true){
					        swal({
					            title: "Success!",
					            text: "Successfully Updated!",
					            type: "success"
					        }, function() {
					            window.location = "/blog/index";
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
	        }	
	    });
		
	});
	$('#form_editBlog input[type="file"]').each(function(){
	  // Refs
	  var $file = $(this),
	      $label = $file.next('label'),
	      $labelText = $label.find('span'),
	      labelDefault = $labelText.text();

	  // When a new file is selected
	  $file.on('change', function(event){
	    var fileName = $file.val().split( '\\' ).pop(),
	        tmppath = URL.createObjectURL(event.target.files[0]);
	    if( fileName ){
	      $label
	        .addClass('file-ok')
	        .css('background-image', 'url(' + tmppath + ')');
	      $labelText.text("");
	    }else{
	      $label.removeClass('file-ok');
	      $labelText.text(labelDefault);
	    }
	  });
	// End loop of file input elements
	});
});