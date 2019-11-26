<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      经营者管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/users">经营者管理</a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"><a href="/admin/users/create" class="btn btn-block btn-primary btn-flat"><i class="fa fa-plus"></i> 添加</a></h3>

            <div class="box-tools">
              <form action="/admin/users" method="get">
                <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索" value="<?=$keyword?>">
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
                <th>昵称</th>
                <th>姓名</th>
                <th>手机号</th>
                <th>余额</th>
                <th>最后审核时间</th>
                <th width="410">操作</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($users_list as $user){?>
                <tr>
                  <td><?=$user->id?></td>
                  <td><?=$user->username?></td>
                  <td><?=$user->real_name?></td>
                  <td><?=$user->mobile?></td>
                  <td><?=$user->balance?></td>
                    <td><?=$user->operator_time?></td>

                  <td>
                    <a href="/admin/users/edit/<?=$user->id?>" class="btn btn-primary btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
                    <a href="/admin/users/view/<?=$user->id?>" class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 查看</a>
                    <a href="/admin/operator/member?user_id=<?=$user->id?>" class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 查看会员</a>
                    <a href="/admin/operator/balance?user_id=<?=$user->id?>" class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 收益明细</a>
                    <a href="/admin/operator/card?user_id=<?=$user->id?>" class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 银行卡</a>
                  </td>
                </tr>
              <?php } ?>
              <?php if(empty($users_list)){?>
                <tr>
                  <td colspan="6" class="no-data">没有数据</td>
                </tr>
              <?php } ?>
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">

            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">显示<?=$users_show_begin?>-<?=$users_show_end?>条，共<?=$users_total_rows?>条</div>
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
