$(document).ready(function(){
	tinymce.init({ selector:'#bio_editor' });
	tinymce.init({ selector:'#full_bio_editor' });
	var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $("#form_addEscort .input_fields_wrap .fields"); //Fields wrapper
    var add_button      = $("#form_addEscort .add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="form-group rate"><input class="input-rounded form-control-sm col-md-3" type="text" name="duration[]" placeholder="Duration"><input class="input-rounded form-control-sm col-md-3" type="text" name="rate[]" placeholder="Rate"><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    });
	$('#form_addEscort input[type="file"]').each(function(){
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
	$("#form_addEscort .escort_masseuse").click(function(){
		var status = $(this).is(':checked');
		if(status) $("#form_addEscort input[name='masseuse']").val("Yes");
		else $("#form_addEscort input[name='masseuse']").val("No");
		console.log($("#form_addEscort input[name='masseuse']").val());
	});
	$("#form_addEscort .escort_active").click(function(){
		var status = $(this).is(':checked');
		if(status) $("#form_addEscort input[name='active']").val("Yes");
		else $("#form_addEscort input[name='active']").val("No");
		console.log($("#form_addEscort input[name='active']").val());
	});
	$('#form_addEscort').submit(function(e){
		e.preventDefault();
		//console.log("AAAAAAAAAAAA");
		var images = [];
		$('#dropzone_escort .dz-details .dz-filename span').each(function(){
			//console.log($(this));
			images.push($(this).html());
		});
		console.log(images);
		var bio = tinymce.get("bio_editor").getContent();
		var full_bio = tinymce.get("full_bio_editor").getContent();
	    $.ajax({
			url: '/escort/addEscort',
			data:$("#form_addEscort").serialize()+"&bio="+bio+"&full_bio="+full_bio+"&thumbnails="+JSON.stringify(images),
			type: 'post',
			success: function(res){
				console.log(res);
				if(res['state']==true){
					var form_data = $('#form_addEscort').serialize();
					var file_data = $('#add_escort_logo').prop('files')[0];   
				    var fdata = new FormData();        
				    fdata.append('file', file_data); 
				    var email = $("#form_addEscort input[name='email']").val();
				    //console.log(username);
				    var url = '/escort/add_logo?email='+email;
				    console.log("url= "+url);
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
			            text: "Successfully Added!",
			            type: "success"
			        }, function() {
			            window.location = "/escort/index";
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