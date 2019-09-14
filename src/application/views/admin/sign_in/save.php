<?php $page_title = ' 邮寄礼品' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/sign_in/index_express">签到达标用户 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/sign_in/index_express" class="pull-right">返回</a>
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

                                <form action="/admin/sign_in/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>
                                    <!--<div class="form-group">
                                        <label for="date" class="col-sm-2 control-label">签到时间</label>
                                        <div class="col-sm-3">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" data-msg="请填写date" name="date" class="form-control pull-right" id="date" value="<?php /*echo $data['date'] */?>" />
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label for="user_id" class="col-sm-2 control-label">用户id</label>
                                        <div class="col-sm-3">
                                            <select name="user_id" id="user_id"  class="form-control">
                                                <option value="">请选择</option>
                                                <?php /*if ($userss != null) : */?>
                                                    <?php /*foreach ($userss as $value) : */?>
                                                        <option value="<?php /*echo $value['id']; */?>" <?php /*if ($row['user_id'] === (string)$value['id'] || set_value('user_id') === (string)$value['id']) : */?>selected="selected"<?php /*endif; */?>><?php /*echo $value['id']; */?></option>
                                                    <?php /*endforeach; */?>
                                                <?php /*endif; */?>
                                            </select>
                                            <?php /*echo form_error('user_id'); */?>
                                        </div>
                                     </div>-->
                                       <!-- <div class="form-group">
                                            <label for="continue" class="col-sm-2 control-label">连续签到天数</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" id="continue" name="continue" value="<?php /*echo $data['continue'] */?>"
                                                       data-msg="请填写continue"
                                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                                 />
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label for="continue" class="col-sm-2 control-label">连续签到天数</label>
                                            <div class="col-sm-3">
                                                <span class="label label-success"><?php echo $data['continue']; ?> 天
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
                                        <div class="form-group">
                                            <label for="is_send" class="col-sm-2 control-label">是否已邮寄礼物</label>
                                            <div class="col-sm-3">
                                                <select name="is_send" id="is_send"  class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php if ($is_sends != null) : ?>
                                                        <?php foreach ($is_sends as $key=>$value) : ?>
                                                            <option value="<?php echo $key; ?>" <?php if ($row['is_send'] === (string)$key || set_value('is_send') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <?php echo form_error('is_send'); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="address_id" class="col-sm-2 control-label">邮寄地址</label>
                                            <div class="col-sm-3">
                                                <!--<input class="form-control" id="address_id" name="address_id" value="<?php /*echo $data['address_id'] */?>"
                                                       data-msg="请填写address_id"
                                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                                 />-->
                                                <select name="address_id" id="address_id"  class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php if ($addresss != null) : ?>
                                                    <?php foreach ($addresss as $value) : ?>
                                                        <option value="<?php echo $value->id; ?>" <?php if ($data['address_id'] === (string)$value->id
                                                            ) : ?>selected="selected"<?php endif; ?>>
                                                            <?php echo $value->address; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="express_no" class="col-sm-2 control-label">快递单号</label>
                                            <div class="col-sm-3">
                                                <input class="form-control" id="express_no" name="express_no" value="<?php echo $data['express_no'] ?>"
                                                       data-msg="请填写express_no"
                                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                                 />
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary btn-flat"
                                                            style="margin-right: 5px;">提交
                                                    </button>
                                                    <a href="/admin/sign_in/index" class="btn btn-default btn-flat">取消</a>
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
        $('#date').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
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
