<?php $page_title = ($data['id']?'编辑':'新增').' 检测点' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/check_postion/index">检测点 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/check_postion/index" class="pull-right">返回</a>
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

                                <form action="/admin/check_postion/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>
                        <div class="form-group">
                            <label for="check_postion" class="col-sm-2 control-label">检测点</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="check_postion" name="check_postion" value="<?php echo $data['check_postion'] ?>"
                                       data-msg="请填写check_postion"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">医院</label>
                            <div class="col-sm-3">
                                <select name="hospital_id" id="hospital_id"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($hospitals != null) : ?>
                                        <?php foreach ($hospitals as $key=>$value) : ?>
                                            <option value="<?php echo $value->id; ?>" <?php if ($data['hospital_id'] === $value->id ) : ?>selected="selected"<?php endif; ?>><?php echo $value->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('level'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="money" class="col-sm-2 control-label">总金额</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="money" name="money" value="<?php echo $data['money'] ?>"
                                       data-msg="请填写money"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="remark" name="remark" value="<?php echo $data['remark'] ?>"
                                       data-msg="请填写remark"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
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
                            <label for="status" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-3">
                                <select name="status" id="status"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($statuss != null) : ?>
                                        <?php foreach ($statuss as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['status'] === (string)$key ) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
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
                                    <a href="/admin/check_postion/index" class="btn btn-default btn-flat">取消</a>
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
        /*$(".prov").change(function () {
            id = $(".prov").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
            });
        });*/
    });
</script>
