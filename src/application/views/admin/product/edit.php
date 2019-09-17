<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>
		商品管理
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="/admin/"><i class="fa fa-dashboard"></i>首页</a></li>
		<li><a href="/admin/products/index">商品管理</a></li>
		<li class="active">编辑商品信息</li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-plus"></i> 编辑商品信息</h3>
						<a href="<?=$form_url?>" class="pull-right">返回</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">

							    <?php if(!empty($message)){?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $message;?>
								</div>
								<?php }?>
								<form action="/admin/products/details/<?=$product->id?>" class="form-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<div class="form-group">
										<label for="name" class="col-sm-2 control-label">商品名称</label>
										<div class="col-sm-3">
											<input type="text" name="name" value="<?=$product->name?>" id="name" class="form-control">
										</div>
									</div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">价格</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="price" value="<?=$product->price?>" id="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">历史价格</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="old_price" value="<?=$product->old_price?>" id="old_price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">商品描述</label>
                                        <div class="col-sm-3">
                                            <input type="text" name="describe" value="<?=$product->describe?>" id="describe" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="picture_url" class="col-sm-2 control-label">商品图片</label>
                                        <div class="col-sm-3">
                                            <img src = "<?=$product->pic ? qiniu_image($product->pic,false) : '/assets/images/upload.png';?>" style="cursor: pointer;height:64px;" id="img_imageupload"/>
                                            <input type="hidden" name="pic" id="image" class="spec_image" value="<?=$product->pic?>" />
                                            <input type="file"  style="display:none;" class="file-btn"  id="image_file"  name="upload_file"  />
                                        </div>
                                    </div>
                                    <?php for($i = 1;$i <= 3; $i++){ ?>
                                    <div class="form-group">
                                        <label for="picture_url" class="col-sm-2 control-label">banner图片<?=$i?></label>
                                        <div class="col-sm-3">
                                            <img src = "<?=$product->banner_pic[$i-1] ? qiniu_image($product->banner_pic[$i-1] ,false) : '/assets/images/upload.png';?>" style="cursor: pointer;height:64px;" id="img_imageupload<?=$i?>"/>
                                            <input type="hidden" name="banner[]" id="image<?=$i?>" class="spec_image" value="<?=$product->banner_pic[$i-1]?>" />
                                            <input type="file"  style="display:none;" class="file-btn"  id="image_file<?=$i?>"  name="upload_file"  />
                                        </div>
                                    </div>
                                    <?php }?>


                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">内容</label>
                                        <div class="col-sm-6">
                                            <!--<script id="container" name="details" type="text/plain"></script>-->
                                            <!-- 加载编辑器的容器 -->
                                            <textarea type="text"   name="details" id="details" class="form-control"><?php echo $product->details ;?></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="type" id="type" value="<?=$product->type?>" />
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary btn-flat" style="	margin-right: 5px;">提交</button>
											<a href="/admin/products" class="btn btn-default btn-flat">取消</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script src="/assets/plugins/pwstrength/pwstrength.min.js"></script>
<script src="/assets/plugins/validate/jquery.validate.min.js"></script>
<script src="/assets/plugins/chosen/chosen.jquery.min.js"></script>
<script src="/assets/js/ajaxfileupload.js"></script>
<script src="/assets/plugins/ckeditor/ckeditor.js"></script>

<!-- 实例化编辑器 -->
<script type="text/javascript">
    CKEDITOR.replace('details');
</script>
<script>
    $(function () {
        $("#createForm").validate();
    });

    //上传图片
    $("#image_file").on("change",function(){
        $.ajaxFileUpload({
            type: "post",
            url: '/admin/upload/upload_image',
            secureuri: false,
            fileElementId: 'image_file',
            dataType: 'json',
            success: function(res) {
                debugger;
                if(res.status == 1){

                    $('#img_imageupload').attr('src',res.data.url);
                    $('#image').val(res.data.url);//res.data.file_name
                }else{
                    if(res == null || res==false)
                    {
                        alert("上传失败！");
                        return;
                    }
                    alert(res.message);
                }
            },
            error:function(data, error){
                debugger;
                alert("上传失败");
            }
        });
    });
    $("#img_imageupload").click(function(){
        $("#image_file").click();
    });

    <?php for($i = 1;$i <= 3; $i++){ ?>
    //上传图片
        $("#image_file<?=$i?>").on("change",function(){
        $.ajaxFileUpload({
            type: "post",
            url: '/admin/upload/upload_image',
            secureuri: false,
            fileElementId: 'image_file<?=$i?>',
            dataType: 'json',
            success: function(res) {
                debugger;
                if(res.status == 1){

                    $('#img_imageupload<?=$i?>').attr('src',res.data.url);
                    $('#image<?=$i?>').val(res.data.url);//res.data.file_name
                }else{
                    if(res == null || res==false)
                    {
                        alert("上传失败！");
                        return;
                    }
                    alert(res.message);
                }
            },
            error:function(data, error){
                debugger;
                alert("上传失败");
            }
        });
    });
        $("#img_imageupload<?=$i?>").click(function(){
        $("#image_file<?=$i?>").click();
    });

    <?php }?>
</script>

<script>
	$(function(){
		$("#createForm").validate();
	});
</script>
