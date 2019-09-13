<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '角色权限列表' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/roles">角色列表</a></li>
      <li class="active"><?php echo $page_title ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <form action="/admin/roles/save_role_permission" class="form-horizontal" id="createForm"
                method="post"
                accept-charset="utf-8">
            <input type="hidden" name="role_id" value="<?= $role_id ?>" />
            <div class="box-header">
              <button type="submit" class="btn btn-primary btn-flat" style="margin-right: 5px;">提交</button>
              <a href="/admin/roles" class="btn btn-default btn-flat">取消</a>
              <label>角色名称: <?= $role_name ?></label>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">

              <table class="table table-condensed table-hover">
                <thead>
                <tr>
                  <th>菜单编号</th>
                  <th>菜单名称</th>
                  <th>权限</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($menu_list as $menu) { ?>
                  <tr>
                    <td><?= $menu->id ?></td>
                    <td><?= $menu->name ?></td>
                    <td>
                        <?php foreach ($menu->permissions as $permission) { ?>
                          <input type="checkbox" name="permission_ids[]" value="<?= $permission->id ?>"
                                 id="<?= $permission->id ?>"
                              <?php if ($permission->check) { ?> checked="checked" <?php } ?>/>
                          <label for="<?= $permission->id ?>"><?= $permission->name ?></label>
                        <?php } ?>
                    </td>
                  </tr>
                <?php } ?>
                <?php if (empty($menu_list)) { ?>
                  <tr>
                    <td colspan="6" class="no-data">没有数据</td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
