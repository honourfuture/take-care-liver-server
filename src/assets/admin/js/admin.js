/**
 * AdminLTE
 * ------------------
 */
(function ($, AdminLTE) {

  "use strict";

    $(function(){
	
		if ($('.animsition').length) {
			$('.animsition').animsition({
				inClass             : 'fade-in',
				outClass            : 'fade-out',
				inDuration          : 400,
				outDuration         : 200,
				linkElement         : 'a[href]:not([target="_blank"]):not([href^="mailto\\:"]):not([href^="\\#"])',
				loading             : true,
				loadingParentElement: 'body',
				loadingClass        : 'animsition-loading',
				unSupportCss        : ['animation-duration', '-webkit-animation-duration', '-o-animation-duration'],
				overlay             : false,
				overlayClass        : 'animsition-overlay-slide',
				overlayParentElement: 'body'
			});
		}

		if ($('#password').length) {
			var options = {};
			options.common = {
				debug: false,
				onLoad: function() {
					$('#messages').text('Start typing password');
				}
			};

			$('#password').pwstrength(options);
		}
	});
})(jQuery, $.AdminLTE);


function loadModal(page)
{
	$('#boxModal .modal-content').html('<div class="loader"></div>');
	$.ajax({type: 'GET',url: page}).done(
		function(response)
		{
			$('#boxModal .modal-content').html(response);
		}
	);
}

function submitAjax(doRefresh)
{
	$('#boxModal .submitButton').attr('disabled','disabled');
	$('#boxModal .boxMessage').html('<div class="loader"></div>');
	$.ajax({
		type: 'POST',
		url: $('#boxModal .ajaxForm').attr('action'),
		data: $('#boxModal .ajaxForm').serialize()
	}).done(
		function(response){

			if(doRefresh != 0 & doRefresh != null){
				location.reload();
			}
			else{
				$('#boxModal .modal-content').html(response);
				$('#boxModal').modal('hide');
				toastr.error("删除成功");
			}
		}
	);
	
	return false;		
}
