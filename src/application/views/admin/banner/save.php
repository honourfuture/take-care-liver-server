<?php $page_title = ($data['id']?'编辑':'新增').' Banner图' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/banner/index">Banner图 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/banner/index" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?php if (!empty($message)) { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <?php echo$message; ?>
                                    </div>
                                <?php } ?>

                                <form action="/admin/banner/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="name" name="name" value="<?php echo $data['name'] ?>"
                                       data-msg="请填写name"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="sort" name="sort" value="<?php echo $data['sort'] ?>"
                                       data-msg="请填写sort"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="picture_url" class="col-sm-2 control-label">图片链接</label>
                            <div class="col-sm-3">
                                <img src = "<?=$data['picture_url'] ? qiniu_image($data['picture_url'],false) : '/assets/images/upload.png';?>" style="cursor: pointer;height:64px;" id="img_imageupload"/>
                                <input type="hidden" name="picture_url" id="image" class="spec_image" value="<?=$data['picture_url'] ?>" />
                                <input type="file"  style="display:none;" class="file-btn"  id="image_file"  name="upload_file"  />
                            </div>
                        </div>
                      <!--  <div class="form-group">
                            <label for="picture_url" class="col-sm-2 control-label">图片链接</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="picture_url" name="picture_url" value="<?php /*echo $data['picture_url'] */?>"
                                       data-msg="请填写picture_url"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>-->
                        <!--<div class="form-group">
                            <label for="url" class="col-sm-2 control-label">跳转URL</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="url" name="url" value="<?php /*echo $data['url'] */?>"
                                       data-msg="请填写url"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="indate" class="col-sm-2 control-label">跳转URL</label>
                            <div class="col-sm-6">
                                <script id="container" name="url" type="text/plain"></script>
                                <!-- 加载编辑器的容器 -->

                               <!-- <textarea type="text"   name="url" id="url" class="form-control"><?php /*echo $data['url'] ;*/?></textarea>-->
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="create_time" class="col-sm-2 control-label">创建时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写create_time" name="create_time" class="form-control pull-right" id="create_time" value="<?php /*echo $data['create_time'] */?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_time" class="col-sm-2 control-label">更新时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写update_time" name="update_time" class="form-control pull-right" id="update_time" value="<?php /*echo $data['update_time'] */?>" />
                                </div>
                            </div>
                        </div>-->
                       <!-- <div class="form-group">
                            <label for="overdue_time" class="col-sm-2 control-label">过期时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写overdue_time" name="overdue_time" class="form-control pull-right" id="overdue_time" value="<?php /*echo $data['overdue_time'] */?>" />
                                </div>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-3">
                                <select name="status" id="status"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($statuss != null) : ?>
                                        <?php foreach ($statuss as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['status'] === (string)$key || set_value('status') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('status'); ?>
                            </div>
                        </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-flat"
                                            style="margin-right: 5px;">提交
                                    </button>
                                    <a href="/admin/banner/index" class="btn btn-default btn-flat">取消</a>
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
<script src="/assets/js/ajaxfileupload.js"></script>
<!--<script src="/assets/plugins/ckeditor/ckeditor.js"></script>-->
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
        ue.setContent('<?=$data['url']?>');
    });
</script>

<!-- 实例化编辑器 -->
<script type="text/javascript">
    //CKEDITOR.replace('url');
    $(function () {
        $("#createForm").validate();
        $('#create_time').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#update_time').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#overdue_time').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        /*$(".prov").change(function () {
            id = $(".prov").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
            });
        });*/

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
    });
</script>
