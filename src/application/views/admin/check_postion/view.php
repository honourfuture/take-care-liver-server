

<?php $page_title = '检测点 详情' ?>
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
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/check_postion/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="check_postion" class="col-sm-2 control-label">检测点</label>
                            <div class="col-sm-3">
                                <?php echo $data['check_postion'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hospital_id" class="col-sm-2 control-label">医院id</label>
                            <div class="col-sm-3">
                                <?php echo $data['hospital_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="money" class="col-sm-2 control-label">总金额</label>
                            <div class="col-sm-3">
                                <?php echo $data['money'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-3">
                                <?php echo $data['remark'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-3">
                                <select readonly name="status" id="status"  class="form-control">
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
