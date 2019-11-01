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


/*列表中单个选择和取消*/
function checkThis(obj) {
    var id = $(obj).attr('value');
    if ($(obj).is(':checked')) {
        if ($.inArray(id, AdminCommon.dataSelectIds) < 0) {
            AdminCommon.dataSelectIds.push(id);
        }
    } else {
        if ($.inArray(id, AdminCommon.dataSelectIds) > -1) {
            AdminCommon.dataSelectIds.splice($.inArray(id, AdminCommon.dataSelectIds), 1);
        }
    }

    var all_length = $("input[name='data-checkbox']").length;
    var checked_length = $("input[name='data-checkbox']:checked").length;
    if (all_length === checked_length) {
        $("#dataCheckAll").prop("checked", true);
    } else {
        $("#dataCheckAll").prop("checked", false);
    }
    console.log(AdminCommon.dataSelectIds);
}


/*全部选择/取消*/
function checkAll(obj) {
    AdminCommon.dataSelectIds = [];
    var all_check = $("input[name='data-checkbox']");
    if ($(obj).is(':checked')) {
        all_check.prop("checked", true);
        $(all_check).each(function () {
            AdminCommon.dataSelectIds.push(this.value);
        });
    } else {
        all_check.prop("checked", false);
    }
}

//跳转到
function gotoPage(url,totalPage){
    var page = $("#gotoPage").val();
    if(page > totalPage){
        page = totalPage;
        $("#gotoPage").val(page);
    }
    if(page == 0){
        page = 1;
        $("#gotoPage").val(page);
    }
    url = url +"?per_page="+page;
    window.location.href = url;
}

//模态框定义
var AdminCommon = {
    confirm:function(params){
        var model = $("#common_confirm_model");
        model.find(".title").html(params.title)
        model.find(".message").html(params.message)

        $("#common_confirm_btn").click()
        //每次都将监听先关闭，防止多次监听发生，确保只有一次监听
        model.find(".cancel").off("click")
        model.find(".ok").off("click")

        model.find(".ok").on("click",function(){
            params.operate(true)
        })

        model.find(".cancel").on("click",function(){
            params.operate(false)
        })
    },
    dataSelectIds : []
}