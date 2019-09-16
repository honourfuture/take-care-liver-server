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
                <th width="200">金额</th>
                <th width="200">来源</th>
                <th width="200">余额</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($users_list as $user){?>
                <tr>
                    <td><span style="color:<?php echo $user['status'] == 1 ? 'green' : 'red'; ?>"><?php echo $user['status'] == 1 ? '+' : '-'; ?> <?=$user['money']?></span></td>
                    <td><?php
                        if($user['type'] == 1){
                            echo '提现';
                        }else if($user['type'] == 2){
                            echo '用户购买年卡';
                        }else if($user['type'] == 3){
                            echo '购买商品';
                        }else if($user['type'] == 4){
                            echo '购买年卡';
                        }
                        ?></td>
                    <td><?=$user['create_time']?></td>
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
