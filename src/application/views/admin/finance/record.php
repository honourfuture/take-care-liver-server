<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '举报处理记录列表' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/report/record"><?php echo $page_title ?></a></li>
            <li class="active">列表</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                       <h3 class="box-title"></h3>
                        <div class="box-tools">
                            <form action="/admin/report/record" method="get">
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
                                <th>处理类型</th>
                                <th>处理规则</th>
                                <th>被处理人</th>
                                <th>备注</th>
                                <th>处理人</th>
                                <th>添加时间</th>
                                <th>更新时间</th>
                                <th width="250">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($report_list as $role) { ?>
                                <tr>
                                    <td><?= $role->id ?></td>
                                    <td><?= $role->type == 1?'管理员':'系统' ?></td>
                                    <td><?= empty($role->rule)?'':($role->rule<5?'规则'.$role->rule:($role->rule==11?'举报清零':'禁用账号')) ?></td>
                                    <td><a href="/admin/users/view/<?= $role->user_id ?>"><?= $role->username ?></a></td>
                                  <!--  <td><?/*= $role->user_ided */?></td>
                                    <td><?/*= $role->user_id */?></td>-->
                                    <td><?= $role->remark ?></td>
                                    <td><?= $role->create_by === '0'?'系统':$role->create_by ?></td>
                                    <td><?= $role->create_at ?></td>
                                    <td><?= $role->update_at ?></td>
                                    <td>
                                        <button data-toggle="modal" data-target="#boxModal"
                                                onclick="loadModal('/admin/report/record_del/<?= $role->id ?>')"
                                                class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> 删除
                                        </button>

                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($report_list)) { ?>
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
                                    共<?= $total_rows ?>条
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
