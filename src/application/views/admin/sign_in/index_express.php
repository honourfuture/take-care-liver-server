<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '达标签到用户' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/sign_in/index_express"><?php echo $page_title ?></a></li>
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
                <!--<a href="/admin/sign_in/save" class="btn btn-block btn-primary btn-flat"><i
                  class="fa fa-plus"></i> 添加</a>-->
            </h3>

            <div class="box-tools">
              <form action="/admin/sign_in/index_express" method="get">
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
                            <th><b>ID</b></th>
                            <th>签到时间</th>
                            <th>用户ID</th>
                            <th>用户昵称</th>
                            <th>用户手机号</th>
                            <th>连续签到天数</th>
                          <!--  <th>创建时间</th>
                            <th>更新时间</th>-->
                            <th>是否已邮寄礼物</th>
                            <th>是否申请领取</th>
                            <th>邮寄地址ID</th>
                            <th>邮寄地址</th>
                            <th>快递单号</th>
                            <th width="250">操作</th>
              </tr>
              </thead>
              <tbody>
                        <?php if ($result != null) : ?>
                            <?php foreach ($result as $value) : ?>
                                <tr>
                                    <td><!--<input type="checkbox" name="ids[]" class="ids" value="<?php /*echo $value['id']; */?>" />--> <?php echo $value['id']; ?></td>
                                    <td><?php echo $value['date']; ?></td>
                                    <td><?php echo $value['user_id']; ?></td>
                                    <td><?php echo $value['username']; ?></td>
                                    <td><?php echo $value['mobile']; ?></td>
                                    <td><span class="label label-success"><?php echo $value['continue']; ?> 天</span></td>
                                   <!-- <td><?php /*echo $value['create_time']; */?></td>
                                    <td><?php /*echo $value['update_time']; */?></td>-->
                                    <td><span class="label label-success"><?php echo $is_sends[$value['is_send']]; ?></span></td>
                                    <td><span class="label label-success"><?php echo $is_sends[$value['is_apply']]; ?></span></td>
                                    <td><?php echo $value['address_id']; ?></td>
                                    <td><?php echo $value['address']; ?></td>
                                    <td><?php echo $value['express_no']; ?></td>
                                    <td>
                                        <!--<button data-toggle="modal" data-target="#boxModal"
                                                onclick="loadModal('/admin/sign_in/del/<?php /*echo $value['id']; */?>')"
                                                class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> 删除
                                        </button>-->
                                        <!--<a href="/admin/sign_in/save/<?php /*echo $value['id']; */?>" class="btn btn-primary btn-sm pull-right"
                                           style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
                                         <a href="/admin/sign_in/view/<?php /*echo $value['id']; */?>"
                                           class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 详情</a>-->
                                        <?php if($value['is_send'] != 1){ ?>
                                            <a href="/admin/sign_in/save/<?php echo $value['id']; ?>" class="btn btn-primary btn-sm pull-right"
                                                style="margin-right: 5px;"><i class="fa fa-edit"></i> 邮寄礼品</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                     <?php if (empty($result)) { ?>
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
                                    <!-- 显示<?/*= $administrators_show_begin */?>---><?/*= $administrators_show_end */?>
                                    <!-- 条，-->共<?= $total_rows ?>条
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
