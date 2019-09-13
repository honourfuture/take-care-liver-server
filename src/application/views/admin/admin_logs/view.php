

<?php $page_title = '管理员操作记录 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/admin_logs/index">管理员操作记录 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/admin_logs/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                     <div class="form-group">
                        <label for="user_id" class="col-sm-2 control-label">用户id</label>
                        <div class="col-sm-3">
                            <select readonly name="user_id" id="user_id"  class="form-control">
                                <option value="">请选择</option>
                                <?php if ($admin_userss != null) : ?>
                                    <?php foreach ($admin_userss as $value) : ?>
                                        <option  value="<?php echo $value['id']; ?>" <?php if ($data['user_id'] === (string)$value['id'] || set_value('user_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('user_id'); ?>
                        </div>
                     </div>
                        <div class="form-group">
                            <label for="user_name" class="col-sm-2 control-label">用户名称</label>
                            <div class="col-sm-3">
                                <?php echo $data['user_name'] ?>
                            </div>
                        </div>
                     <div class="form-group">
                        <label for="menu_id" class="col-sm-2 control-label">菜单id</label>
                        <div class="col-sm-3">
                            <select readonly name="menu_id" id="menu_id"  class="form-control">
                                <option value="">请选择</option>
                                <?php if ($admin_menus != null) : ?>
                                    <?php foreach ($admin_menus as $value) : ?>
                                        <option  value="<?php echo $value['id']; ?>" <?php if ($data['menu_id'] === (string)$value['id'] || set_value('menu_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('menu_id'); ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="permission_id" class="col-sm-2 control-label">权限id</label>
                        <div class="col-sm-3">
                            <select readonly name="permission_id" id="permission_id"  class="form-control">
                                <option value="">请选择</option>
                                <?php if ($admin_permissions != null) : ?>
                                    <?php foreach ($admin_permissions as $value) : ?>
                                        <option  value="<?php echo $value['id']; ?>" <?php if ($data['permission_id'] === (string)$value['id'] || set_value('permission_id') === (string)$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo $value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('permission_id'); ?>
                        </div>
                     </div>
                        <div class="form-group">
                            <label for="data_id" class="col-sm-2 control-label">数据id</label>
                            <div class="col-sm-3">
                                <?php echo $data['data_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remark" class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-3">
                                <?php echo $data['remark'] ?>
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
