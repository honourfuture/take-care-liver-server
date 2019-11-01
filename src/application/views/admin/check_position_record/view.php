

<?php $page_title = '检测点检测记录 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/check_position_record/index">检测点检测记录 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/check_position_record/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="user_id" class="col-sm-2 control-label">用户ID</label>
                            <div class="col-sm-3">
                                <?php echo $data['user_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="check_position_id" class="col-sm-2 control-label">检测点ID</label>
                            <div class="col-sm-3">
                                <?php echo $data['check_position_id'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date" class="col-sm-2 control-label">检测时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['date'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="money" class="col-sm-2 control-label">金额</label>
                            <div class="col-sm-3">
                                <?php echo $data['money'] ?>
                            </div>
                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(function () {

    });
</script>
