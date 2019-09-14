

<?php $page_title = '用户尿检记录 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/user_urine/index">用户尿检记录 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/user_urine/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="user_id" class="col-sm-2 control-label">用户id</label>
                            <div class="col-sm-3">
                                <?php echo $data['user_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="urine_check_id" class="col-sm-2 control-label">尿检id</label>
                            <div class="col-sm-3">
                                <?php echo $data['urine_check_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-3">
                                <select readonly name="type" id="type"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($types != null) : ?>
                                        <?php foreach ($types as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['type'] === (string)$key || set_value('type') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('type'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date" class="col-sm-2 control-label">尿检时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['date'] ?>
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
