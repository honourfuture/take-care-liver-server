<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '管理员角色列表' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/administrators">管理员列表</a></li>
      <li class="active"><?php echo $page_title ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <form action="/admin/administrators/save_admin_role" class="form-horizontal" id="createForm"
                method="post"
                accept-charset="utf-8">
            <input type="hidden" name="user_id" value="<?= $user_id ?>" />
            <div class="box-header">
              <button type="submit" class="btn btn-primary btn-flat" style="margin-right: 5px;">提交</button>
              <a href="/admin/administrators" class="btn btn-default btn-flat">取消</a>
              <label>管理员名称: <?= $user_name ?></label>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">

              <table class="table table-condensed table-hover">
                <thead>
                <tr>
                  <th>角色编号</th>
                  <th>角色名称</th>
                  <th>是否选中</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($role_list as $role) { ?>
                  <tr>
                    <td><?= $role->id ?></td>
                    <td><?= $role->name ?></td>
                    <td>
                      <input type="checkbox" name="role_ids[]" value="<?= $role->id ?>"
                             id="<?= $role->id ?>"
                          <?php if ($role->check) { ?> checked="checked" <?php } ?>/>
                      <label for="<?= $role->id ?>"><?= $role->name ?></label>
                    </td>
                  </tr>
                <?php } ?>
                <?php if (empty($role_list)) { ?>
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
