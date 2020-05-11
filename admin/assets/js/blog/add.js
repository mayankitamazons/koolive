$(document).ready(function(){
	//console.log("Add blog");
	//$('#form_addBlog .blog_info').froalaEditor();
	tinymce.init({ selector:'.info_editor' });
	$('#form_addBlog').submit(function(e){
		e.preventDefault();
	    //var blog_info = $('#form_addBlog .blog_info').froalaEditor("html.get");
	    var blog_info = tinymce.activeEditor.getContent();
	    console.log("This is blog info: "+blog_info);
		var file_data = $('#add_blog_logo').prop('files')[0];   
	    var fdata = new FormData();
	    fdata.append('file', file_data);
		var url = '/blog/upload';
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
					url: '/blog/addBlog',
					data: $('#form_addBlog').serialize()+'&info='+blog_info+'&path='+upload_path,
					type: 'post',
					success: function(res){
						console.log(res);
						if(res['state']==true){
							
					        swal({
					            title: "Success!",
					            text: "Successfully Added!",
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
	$('#form_addBlog input[type="file"]').each(function(){
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