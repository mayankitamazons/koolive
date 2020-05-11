$(document).ready(function(){
	//console.log("Add agency");
	//$('#form_addAgency .agency_info').froalaEditor();
	tinymce.init({ selector:'.info_editor' });
	console.log("ADD AGENCY");
	$('#form_addAgency').submit(function(e){
		e.preventDefault();
	    //var agency_info = $('#form_addAgency .agency_info').froalaEditor("html.get");
	    var agency_info = tinymce.activeEditor.getContent();
	    var pwd = $("#form_addAgency .pwd").val();
	    var conpwd = $("#form_addAgency .conpwd").val();
	    if(pwd!=conpwd){
		    swal({
	            title: "Confirm Password",
	            text: "Password is incorrect!",
	            type: "error"
	        }, function() {
	            $("#form_addAgency .pwd").val("");
	            $("#form_addAgency .conpwd").val("");
	        });
		}else{
		    $.ajax({
				url: '/dashboard/addAgency',
				data: $(this).serialize()+'&info='+agency_info,
				type: 'post',
				success: function(res){
					console.log(res);
					if(res['state']==true){
						var form_data = $('#form_addAgency').serialize();
						var file_data = $('#add_agency_logo').prop('files')[0];   
					    var fdata = new FormData();        
					    fdata.append('file', file_data); 
					    var email = $("#form_addAgency input[name='email']").val();
					    var url = '/dashboard/add_logo?email='+email;
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
					    url = '/dashboard/add_banner?email='+email;
					    fdata = new FormData();
					    file_data = $('#add_agency_banner').prop('files')[0];
					    fdata.append('file', file_data);
					    $.ajax({
					    	url: url,
					    	dataType: 'text',
					    	cache: false,
					    	contentType: false,
					    	processData: false,
					    	data: fdata,
					    	type: 'post',
					    	success: function(res){
					    		
					    	}
					    });
				        swal({
				            title: "Success!",
				            text: "Successfully Added!",
				            type: "success"
				        }, function() {
				            window.location = "/dashboard/index";
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
	$('#form_addAgency input[type="file"]').each(function(){
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