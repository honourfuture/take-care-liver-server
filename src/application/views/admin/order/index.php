<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      订单管理
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/order">订单管理</a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
              <form action="/admin/order/<?=$page?>" method="get">

            <h3 class="box-title"></h3>
              <div class="form-group">
                  <div class="col-sm-2">
                      <div class="input-group date">
                          <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text"  name="start_date" class="form-control pull-right" id="start_date" value="<?php echo $start_date ?>" />
                      </div>

                  </div>
                  <div class="col-sm-2">
                      <div class="input-group date">
                          <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text"  name="end_date" class="form-control pull-right" id="end_date" value="<?php echo $end_date ?>" />
                      </div>
                  </div>
            <div class="box-tools">
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
                <th>姓名</th>
                <th>手机号</th>
                <th>订单编号</th>
                <th>下单时间</th>
                <th>商品名称</th>
                <th>商品价格</th>
                <th>订单状态</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach($order_list as $user){?>
                <tr>
                  <td><?=$user->id?></td>
                  <td><?=$user->username?></td>
                  <td><?=$user->mobile?></td>
                    <td><?=$user->order_no?></td>
                    <td><?=$user->create_time?></td>
                    <td><?=$user->products_title?></td>
                    <td><?=$user->price?></td>
                <td>
                    <?php
//                    已取消，5购物车，10待支付，20已支付，30已使用,40已过期，50已申请退款，60退款中，70已退款]
                    if($user->status == 0){
                        echo '已取消';
                    }else if($user->status == 10){
                        echo '待支付';
                    }else if($user->status == 20){
                        echo '已支付';
                    }else if($user->status == 40){
                        echo '已过期';
                    }else if($user->status == 40){
                        echo '退款中';
                    }else if($user->status == 70){
                        echo '已退款';
                    }
                    ?>
                </td>
                </tr>
              <?php } ?>
              <?php if(empty($order_list)){?>
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
<script>
    $(function () {

        $('#start_date').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#end_date').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

    });
</script>
