$(document).ready(function(){
	tinymce.init({ selector:'#bio_editor' });
	tinymce.init({ selector:'#full_bio_editor' });

	var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $("#form_editEscortProfile .input_fields_wrap .fields"); //Fields wrapper
    var add_button      = $("#form_editEscortProfile .add_field_button"); //Add button ID

    var x = wrapper.find('.rate').length; //initlal text box count
    console.log(x);
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group rate"><input class="input-rounded form-control-sm col-md-3" type="text" name="duration[]" placeholder="Duration"><input class="input-rounded form-control-sm col-md-3" type="text" name="rate[]" placeholder="Rate"><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    	console.log("clicked");
        e.preventDefault(); $(this).parent('div').remove(); x--;
    });

	var escort_id = $("#form_editEscortProfile input[name='id']").val();
	var oldEmail = $("#form_editEscortProfile input[name='email']").val();
	$("#form_editEscortProfile .escort_masseuse").click(function(){
		var status = $(this).is(':checked');
		if(status) $("#form_editEscortProfile input[name='masseuse']").val("Yes");
		else $("#form_editEscortProfile input[name='masseuse']").val("No");
		console.log($("#form_editEscortProfile input[name='masseuse']").val());
	});
	$("#form_editEscortProfile .escort_active").click(function(){
		var status = $(this).is(':checked');
		if(status) $("#form_editEscortProfile input[name='active']").val("Yes");
		else $("#form_editEscortProfile input[name='active']").val("No");
		console.log($("#form_editEscortProfile input[name='active']").val());
	});
	$("#form_editEscortProfile a.dz-remove").click(function(){
		var filename = $(this).parent().find(".dz-filename span").html();
		console.log(filename);
		$.ajax({
			url: '/escort/delete_thumbnail',
			data: {name: filename},
			type: 'post',
			success: function(res){
				if(res=="success"){
					console.log("Successfully removed");
					$.ajax({
						url: '/escort/'
					});
				}
			}
		})
		$(this).parent().remove();
	})
	$('#form_editEscortProfile').submit(function(e){
		e.preventDefault();
		//console.log($("#form_editescortProfile").serialize());
		var images = [];
		$('#dropzone_escort .dz-details .dz-filename span').each(function(){
			//console.log($(this));
			images.push($(this).html());
		});
		var bio = tinymce.get("bio_editor").getContent();
		var full_bio = tinymce.get("full_bio_editor").getContent();
		console.log($(this).serialize());
	    $.ajax({
			url: '/escort/editEscort/',
			data: $("#form_editEscortProfile").serialize()+"&bio="+bio+"&full_bio="+full_bio+"&thumbnails="+JSON.stringify(images),
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
					var file_data = $('#edit_escort_logo').prop('files')[0];   
				    var fdata = new FormData();
				    fdata.append('file', file_data); 
				    var email = $("#form_editEscortProfile input[name='email']").val();
				    var url = '/escort/edit_logo?id='+escort_id+'&oldemail='+oldEmail+'&email='+email;
				    console.log(url);
				    $.ajax({
				        url: url, // point to server-side PHP script 
				        dataType: 'text',  // what to expect back from the PHP script, if anything
				        cache: false,
				        contentType: false,
				        processData: false,
				        data: fdata,
				        type: 'post',
				        success: function(res){
				            console.log(res); // display response from the PHP script, if any
				        }	
				    });
			        swal({
			            title: "Success!",
			            text: "Successfully Updated!",
			            type: "success"
			        }, function() {
			            window.location = "/escort/viewProfile";
			        });
				}
				else{
					swal({
			            title: "Fail!",
			            text: res['msg'],
			            type: "error"
			        });
				}
			}
		});
	});
	$('#form_editEscortProfile input[type="file"]').each(function(){
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