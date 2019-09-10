

<?php $page_title = '医院 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/hospitals/index">医院 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/hospitals/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-3">
                                <?php echo $data['name'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telphone" class="col-sm-2 control-label">电话</label>
                            <div class="col-sm-3">
                                <?php echo $data['telphone'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="position" class="col-sm-2 control-label">位置</label>
                            <div class="col-sm-3">
                                <?php echo $data['position'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail" class="col-sm-2 control-label">简介</label>
                            <div class="col-sm-3">
                                <?php echo $data['detail'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic" class="col-sm-2 control-label">图片</label>
                            <div class="col-sm-3">
                                <img src="<?php echo $data['pic'] ?>" style="width:150px;" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="business_type" class="col-sm-2 control-label">营业时间</label>
                            <div class="col-sm-3">
                                <select readonly name="business_type" id="business_type"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($business_types != null) : ?>
                                        <?php foreach ($business_types as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['business_type'] === (string)$key || set_value('business_type') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('business_type'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="distance" class="col-sm-2 control-label">距离</label>
                            <div class="col-sm-3">
                                <?php echo $data['distance'] ?>
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
