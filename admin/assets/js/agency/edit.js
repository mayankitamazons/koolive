$(document).ready(function(){
	//$('#form_editAgency .agency_info').froalaEditor();
	tinymce.init({ selector:'#agency_info_editor' });
	var agency_id = $("#form_editAgency input[name='id']").val();
	var oldEmail = $("#form_editAgency input[name='email']").val();
	//console.log(agency_id);
	$("#form_editAgency input[name='id']").val(agency_id);
	$('#form_editAgency').submit(function(e){
		e.preventDefault();
		//console.log($("#form_editAgency").serialize());
		var agency_info = tinymce.get("agency_info_editor").getContent();
		console.log(agency_info);
		var pwd = $("#form_editAgency .pwd").val();
	    var conpwd = $("#form_editAgency .conpwd").val();
		if(pwd!=conpwd){
		    swal({
	            title: "Confirm Password",
	            text: "Password is incorrect!",
	            type: "error"
	        }, function() {
	            $("#form_editAgency .pwd").val("");
	            $("#form_editAgency .conpwd").val("");
	        });
		}else{
		    $.ajax({
				url: '/dashboard/editAgency/',
				data: $("#form_editAgency").serialize()+'&info='+agency_info,
				type: 'post',
				success: function(res){
					console.log(res);
					if(res['state']==true){
						var form_data = $('#form_editAgency').serialize();
						var file_data = $('#edit_agency_logo').prop('files')[0];   
					    var fdata = new FormData();        
					    fdata.append('file', file_data); 
					    var email = $("#form_editAgency input[name='email']").val();
					    var url = '/dashboard/edit_logo?id='+agency_id+'&oldemail='+oldEmail+'&email='+email;
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
					    file_data = $('#edit_agency_banner').prop('files')[0];   
					    fdata = new FormData();
					    fdata.append('file', file_data); 
					    url = '/dashboard/edit_banner?id='+agency_id+'&oldemail='+oldEmail+'&email='+email;
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
	$('#form_editAgency input[type="file"]').each(function(){
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