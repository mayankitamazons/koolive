$(document).ready(function(){
	$('#form_addCategory').submit(function(e){
		e.preventDefault();
		
        $.ajax({
			url: '/category/addCategory',
			data: $(this).serialize(),
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
					
			        swal({
			            title: "Success!",
			            text: "Successfully Added!",
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
	/*$('#form_addCategory input[type="file"]').each(function(){
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
	});*/
});