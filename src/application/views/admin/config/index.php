<?php $page_title = '内容管理' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="">内容管理</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-warning"></i> <?php echo $page_title; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form action="/admin/config/index?type=aboutUs" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8"  >
                                    <input type="hidden" name="type" value="<?=$type ?>" >
                                    <!--<div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">标题</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="title" value="<?/*=$data['title']*/?>" id="title" class="form-control">                                        </div>
                                    </div>-->
                                    <!--<div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">图片</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="pic" value="<?/*=$data['pic']*/?>" id="pic" class="form-control">                                        </div>
                                    </div>-->
                                   <!-- <div class="form-group">
                                        <label for="picture_url" class="col-sm-2 control-label">图片链接</label>
                                        <div class="col-sm-3">
                                            <img src = "<?/*=$data['pic'] ? qiniu_image($data['pic'],false) : '/assets/images/upload.png';*/?>" style="cursor: pointer;height:64px;" id="img_imageupload"/>
                                            <input type="hidden" name="pic" id="image" class="spec_image" value="<?/*=$data['pic'] */?>" />
                                            <input type="file"  style="display:none;" class="file-btn"  id="image_file"  name="upload_file"  />
                                        </div>
                                    </div>-->

                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">内容</label>
                                        <div class="col-sm-6">
                                            <script id="container" name="details" type="text/plain"></script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-flat"
                                                    style="margin-right: 5px;">提交
                                            </button>
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
<!-- 配置文件 -->
<script type="text/javascript" src="<?php echo base_url() ?>ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?php echo base_url() ?>ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container', {
        autoHeight: false,
    });
    ue.ready(function(){
        //设置编辑器的内容
        ue.setContent("<?=$data['details']?>");
    });
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
</script>
