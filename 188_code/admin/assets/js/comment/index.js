$(document).ready(function(){
	$(".delete_agency").click(function(e){
		var id = $(this).attr('id');
		$(".confirm_btn").attr({'id': id});
	});
	$(".confirm_btn").click(function(e){
		var id = $(this).attr('id');
		var data = {id: id};
		$.ajax({
			url: '/comments/delete',
			data: data,
			type: 'post',
			success: function(res){
				toastr.info(
 					"Delete Successfully!",
 					{
 						positionClass:"toast-bottom-right",
 						timeOut:5e3,
 						closeButton:!0,
 						debug:!1,
 						newestOnTop:!0,
 						progressBar:!0,
 						preventDuplicates:!0,
 						onclick:null,
 						showDuration:"300",
 						hideDuration:"1000",
 						extendedTimeOut:"1000",
 						showEasing:"swing",
 						hideEasing:"linear",
 						showMethod:"fadeIn",
 						hideMethod:"fadeOut",
 						tapToDismiss:!1
 					}
 				);
				window.location.href="/comments";
			}
		});
	});
});