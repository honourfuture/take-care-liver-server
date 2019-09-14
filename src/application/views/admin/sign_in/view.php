

<?php $page_title = '签到记录 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/sign_in/index">签到记录 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/sign_in/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="date" class="col-sm-2 control-label">签到时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['date'] ?>
                            </div>
                        </div>
                     <div class="form-group">
                        <label for="user_id" class="col-sm-2 control-label">用户id</label>
                        <div class="col-sm-3">
                            <select readonly name="user_id" id="user_id"  class="form-control">
                                <option value="">请选择</option>
                                <?php if ($userss != null) : ?>
                                    <?php foreach ($userss as $value) : ?>
                                        <option  value="<?php echo $value['id']; ?>" <?php if ($data['user_id'] === (string)$value['id'] || set_value('user_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('user_id'); ?>
                        </div>
                     </div>
                        <div class="form-group">
                            <label for="continue" class="col-sm-2 control-label">连续签到天数</label>
                            <div class="col-sm-3">
                                <?php echo $data['continue'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_send" class="col-sm-2 control-label">是否已邮寄礼物</label>
                            <div class="col-sm-3">
                                <select readonly name="is_send" id="is_send"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($is_sends != null) : ?>
                                        <?php foreach ($is_sends as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['is_send'] === (string)$key || set_value('is_send') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('is_send'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address_id" class="col-sm-2 control-label">邮寄地址</label>
                            <div class="col-sm-3">
                                <?php echo $data['address_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="express_no" class="col-sm-2 control-label">快递单号</label>
                            <div class="col-sm-3">
                                <?php echo $data['express_no'] ?>
                            </div>
                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(function () {

    });
</script>
