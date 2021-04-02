$(document).ready(function(){
	console.log("Add banner");
	//$('#form_addBlog .blog_info').froalaEditor();
	tinymce.init({ selector:'.text_editor' });
	$("#form_addBanner .banner_active").click(function(){
		var status = $(this).is(':checked');
		if(status) $("#form_addBanner input[name='active']").val("Yes");
		else $("#form_addBanner input[name='active']").val("No");
		console.log($("#form_addBanner input[name='active']").val());
	});
	$('#form_addBanner').submit(function(e){
		e.preventDefault();
	    //var blog_info = $('#form_addBlog .blog_info').froalaEditor("html.get");
	    var banner_info = tinymce.activeEditor.getContent();
	    console.log("This is banner info: "+banner_info);
		var file_data = $('#add_banner_logo').prop('files')[0];
	    var fdata = new FormData();
	    fdata.append('file', file_data);
		var url = '/adminbanner/upload';
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
					url: '/adminbanner/addBanner',
					data: $('#form_addBanner').serialize()+'&text='+banner_info+'&path='+upload_path,
					type: 'post',
					success: function(res){
						console.log(res);
						if(res['state']==true){
							
					        swal({
					            title: "Success!",
					            text: "Successfully Added!",
					            type: "success"
					        }, function() {
					            window.location = "/adminbanner/index";
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
	$('#form_addBanner input[type="file"]').each(function(){
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