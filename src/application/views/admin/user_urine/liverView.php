

<?php $page_title = '用户肝检记录 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/user_urine/index">用户记录记录 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/user_urine/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal detail-horizontal" id="createForm" method="post">

                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">医院</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['hospitalName']?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">检测编号</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['examinationNo']?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">弹性值</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['stiffness']?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">Iqr/med</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['iqrMed']?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">四分位差</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['iqr']?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">脂肪肝参数</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$data['flai']?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="urine_check_id" class="col-sm-2 control-label">图片</label>
                                        <div class="col-sm-3">
                                            <?php if(isset($data['images']['image'])){ ?>
                                            <?php foreach ($data['images']['image'] as $image){ ?>
                                                <img src="data:image/jpg;base64,<?=$image?>">
                                            <?php }} ?>
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
