

<?php $page_title = '尿检结果 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/urine_check/index">尿检结果 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/urine_check/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="color" class="col-sm-2 control-label">试纸颜色</label>
                            <div class="col-sm-3" style="background: <?php echo $data['color'] ?>">
                                <?php echo $data['color'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="summary" class="col-sm-2 control-label">尿检结果</label>
                            <div class="col-sm-3">
                                <?php echo $data['summary'] ?>
                               <!-- <select readonly name="summary" id="summary"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php /*if ($summarys != null) : */?>
                                        <?php /*foreach ($summarys as $key=>$value) : */?>
                                            <option value="<?php /*echo $key; */?>" <?php /*if ($data['summary'] === (string)$key || set_value('summary') === (string)$key) : */?>selected="selected"<?php /*endif; */?>><?php /*echo $value; */?></option>
                                        <?php /*endforeach; */?>
                                    <?php /*endif; */?>
                                </select>
                                --><?php /*echo form_error('summary'); */?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="details" class="col-sm-2 control-label">尿检详情</label>
                            <div class="col-sm-3">
                                <?php echo $data['details'] ?>
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
