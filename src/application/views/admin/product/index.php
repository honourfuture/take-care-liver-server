<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      商品管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/products/index">商品管理</a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">
                <?php if($type == 3){ ?>
                <a href="/admin/products/create" class="btn btn-block btn-primary btn-flat"><i class="fa fa-plus"></i> 添加</a>
                <?php } ?>
            </h3>

            <div class="box-tools">
<!--              <form action="/admin/users" method="get">-->
<!--                <div class="input-group input-group" style="width: 250px;">-->
<!--                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索" value="--><?//=$keyword?><!--">-->
<!--                  <div class="input-group-btn">-->
<!--                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </form>-->
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-condensed table-hover">
              <thead>
              <tr>
                <th>编号</th>
                <th>商品名称</th>
                <th>价格</th>
                <th>历史价格</th>
                <th width="200">操作</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($users_list as $user){?>
                <tr>
                  <td><?=$user->id?></td>
                  <td><?=$user->name?></td>
                  <td><?=$user->price?></td>
                    <td><?=$user->old_price?></td>
                  <td>
                      <?php if($type == 3){ ?>
                          <a href="/admin/products/delete/<?=$user->id?>" class="btn <?php

                          echo $user->is_delete == 0 ? 'btn-success' : 'btn-danger';
                          ?> btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-edit"></i><?php
                              echo $user->is_delete == 0 ? '上架' : '下架';
                          ?></a>
                      <?php } ?>
                    <a href="/admin/products/details/<?=$user->id?>" class="btn btn-primary btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
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
