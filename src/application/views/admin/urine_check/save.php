<?php $page_title = ($data['id']?'编辑':'新增').' 尿检结果' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/urine_check/index">尿检结果 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/urine_check/index" class="pull-right">返回</a>
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

                                <form action="/admin/urine_check/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>


                                    <div class="form-group">
                                        <label for="summary" class="col-sm-2 control-label">尿检结果</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" id="summary" name="summary" value="<?php echo $data['summary']; ?>"
                                                   data-msg="请填写尿检结果"
                                                   required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="color" class="col-sm-2 control-label">试纸颜色</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" id="color" name="color" value="<?php echo $data['color']; ?>"
                                                   data-msg="请填写color"  style="background:<?php echo $data['color']; ?> "
                                                   required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                             />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="details" class="col-sm-2 control-label">尿检详情</label>
                                        <div class="col-sm-3">
                                            <textarea type="text" name="details" id="details" rows="15" class="form-control"><?php echo $data['details'] ?></textarea>
                                        </div>
                                    </div>
                                   <!-- <div class="form-group">
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

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-flat"
                                                    style="margin-right: 5px;">提交
                                            </button>
                                            <a href="/admin/urine_check/index" class="btn btn-default btn-flat">取消</a>
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

<script>
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
        $("#summary").change(function () {
            var id = $("#summary option:selected").data("values");
            $("#color").val(id);
            $("#color").css("background",id);
        });
        $("#waring_type").change(function () {
            var text = $("#waring_type option:selected").text();
            $("#background").css("background",text);
        });
    });
</script>
