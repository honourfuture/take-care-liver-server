<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '角色列表' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/roles"><?php echo $page_title ?></a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"><a href="/admin/roles/edit" class="btn btn-block btn-primary btn-flat"><i
                  class="fa fa-plus"></i> 添加</a></h3>

            <div class="box-tools">
              <form action="/admin/roles" method="get">
                <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索"
                         value="<?= $keyword ?>">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-condensed table-hover">
              <thead>
              <tr>
                <th>编号</th>
                <th>名称</th>
                <th>描述</th>
                <th>添加时间</th>
                <th>更新时间</th>
                <th width="250">操作</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($roles_list as $role) { ?>
                <tr>
                  <td><?= $role->id ?></td>
                  <td><?= $role->name ?></td>
                  <td><?= $role->description ?></td>
                  <td><?= $role->created_at ?></td>
                  <td><?= $role->updated_at ?></td>
                  <td>
                    <button data-toggle="modal" data-target="#boxModal"
                            onclick="loadModal('/admin/roles/del/<?= $role->id ?>')"
                            class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> 删除
                    </button>
                    <a href="/admin/roles/role_permissions/<?= $role->id ?>" class="btn btn-success btn-sm pull-right"
                       style="margin-right: 5px;"><i class="fa fa-edit"></i> 分配权限</a>
                    <a href="/admin/roles/edit/<?= $role->id ?>" class="btn btn-primary btn-sm pull-right"
                       style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
                  </td>
                </tr>
              <?php } ?>
              <?php if (empty($roles_list)) { ?>
                <tr>
                  <td colspan="6" class="no-data">没有数据</td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">

            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                  显示<?= $roles_show_begin ?>-<?= $roles_show_end ?>条，共<?= $roles_total_rows ?>条
                </div>
              </div>
              <div class="col-sm-7">
                <ul class="pagination pagination no-margin pull-right">
                    <?php echo $this->pagination->create_links(); ?>
                </ul>
              </div>
            </div><!-- /.row -->

          </div>
        </div>
        <!-- /.box -->
      </div>
    </div>


  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
