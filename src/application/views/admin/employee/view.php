

<?php $page_title = '内部员工 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/employee/index">内部员工 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/employee/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="user_name" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-3">
                                <?php echo $data['user_name'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-3">
                                <?php echo $data['password'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="created_at" class="col-sm-2 control-label">创建时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['created_at'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="updated_at" class="col-sm-2 control-label">更新时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['updated_at'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">等级</label>
                            <div class="col-sm-3">
                                <select readonly name="level" id="level"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($levels != null) : ?>
                                        <?php foreach ($levels as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['level'] === (string)$key || set_value('level') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('level'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_default" class="col-sm-2 control-label">是否默认管理员</label>
                            <div class="col-sm-3">
                                <select readonly name="is_default" id="is_default"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($is_defaults != null) : ?>
                                        <?php foreach ($is_defaults as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['is_default'] === (string)$key || set_value('is_default') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('is_default'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="parent_id" class="col-sm-2 control-label">父级id</label>
                            <div class="col-sm-3">
                                <?php echo $data['parent_id'] ?>
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
