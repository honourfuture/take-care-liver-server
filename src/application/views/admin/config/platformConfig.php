<?php $page_title = '平台设置' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="">App管理</a></li>
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
                                <form action="/admin/config/platformConfig" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8"  enctype="multipart/form-data">
                                    <?php echo form_open_multipart('upload/do_upload');?>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">平台客服电话</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="customer_service_phone" value="<?=$data['customer_service_phone']?>" id="description" class="form-control">                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">提现费率</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cash_out" value="<?=$data['cash_out']?>" id="description" class="form-control">                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">分享链接</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="share_url" value="<?=$data['share_url']?>" id="description" class="form-control">                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">安卓下载</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="download_android_url" value="<?=$data['download_android_url']?>" id="description" class="form-control">                                        </div>
                                            <div class="col-sm-2">
                                                <input type="file" name="download_android" size="20" />
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">注册及用户协议</label>
                                        <div class="col-sm-6">
                                            <script id="container" name="user_rule" type="text/plain"></script>
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
        ue.setContent('<?=$data["user_rule"]?>');
    });
</script>
<script>
    $(function () {
        $("#createForm").validate();
    })
</script>
