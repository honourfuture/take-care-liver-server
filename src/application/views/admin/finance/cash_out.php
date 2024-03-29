<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php $page_title = '提现列表' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/report"><?php echo $page_title ?></a></li>
            <li class="active">列表</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <form action="/admin/finance/cash_out" method="get">
                       <h3 class="box-title">
                       </h3>
                        <div class="box-tools">

                            <div class="input-group-btn" style="width: 250px;">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input placeholder="开始时间" type="text" name="start_date" class="form-control pull-right" id="start_date" value="<?php echo $start_date ?>" />
                                </div>
                            </div>
                            <div class="input-group-btn" style="width: 250px;">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" placeholder="结束时间" name="end_date" class="form-control pull-right" id="end_date" value="<?php echo $end_date ?>" />
                                </div>
                            </div>
                                <div class="input-group-btn" style="width: 250px;">
                                    <select name="status"  class="form-control pull-right">
                                        <option value="">提现状态</option>
                                        <option value="0" <?php echo strlen($status) !=0 && $status==0?"selected":"" ?>>待审核</option>
                                        <option value="1" <?php echo $status==1?"selected":"" ?>>已通过</option>
                                        <option value="2" <?php echo $status==2?"selected":"" ?>>已拒绝</option>
                                    </select>
                                </div>

                                <div class="input-group-btn" style="width: 250px;">
                                    <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索"
                                           value="<?= $keyword ?>">
                                </div>
                                <div class="input-group-btn" style="width: 250px;">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    <a href="/admin/finance/export?status=<?= $status ?>&keyword=<?= $keyword ?>" class="btn btn-primary btn-flat"><i
                                            class="fa fa-plus"></i> 导出</a>

                                </div>

                        </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>真实姓名</th>
                                <th>昵称</th>
                                <th>开户行</th>
                                <th>银行卡号</th>
                                <th>预留电话</th>
                                <th>提现金额</th>
                                <th>提现状态</th>
                                <th>申请时间</th>
                                <th width="150">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($report_list as $key=>$role) { ?>
                                <tr>
                                    <td><?= $role->real_name ?></a></td>
                                    <td><?= $role->username ?></a></td>
                                    <td><?= $role->bank_name ?></td>
                                    <td><?= $role->card_number ?></td>
                                    <td><?= $role->phone ?></td>
                                    <td><?= $role->cash_out_money ?></td>
                                    <td>
                                        <?php
                                        if($role->status == 0){
                                            echo '待审核';
                                        }else if($role->status == 1){
                                            echo '已通过';
                                        }else if($role->status == 2){
                                            echo '已拒绝';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $role->apply_time ?></td>
                                    <td>
                                        <?php
                                             if($role->status == 0) {
                                         ?>
                                            <a href="/admin/finance/edit/<?= $role->id ?>/2"
                                               class="btn btn-danger btn-sm pull-right" style="margin-right: 5px;"><i
                                                        class="fa fa-edit"></i> 拒绝</a>
                                                 <a href="/admin/finance/edit/<?= $role->id ?>/1"
                                                    class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i
                                                             class="fa fa-edit"></i> 通过</a>

                                         <?php
                                             }else{
                                         ?>
                                             <a href="javascript:void(0)"
                                                class="btn btn-<?php
                                                if($role->status == 1){
                                                    echo 'success';
                                                }else if($role->status == 2){
                                                    echo 'danger';
                                                }
                                                ?> btn-sm pull-right" style="margin-right: 5px;"><i
                                                         class="fa fa-edit"></i>
                                        <?php
                                           if($role->status == 1){
                                                echo '已通过';
                                            }else if($role->status == 2){
                                                echo '已拒绝';
                                            }
                                        ?></a>
                                        <?php
                                             }
                                         ?>
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
