

<?php $page_title = 'Banner图 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/banner/index">Banner图 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/banner/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-3">
                                <?php echo $data['name'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sort" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-3">
                                <?php echo $data['sort'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="picture_url" class="col-sm-2 control-label">图片链接</label>
                            <div class="col-sm-3">
                                <?php echo $data['picture_url'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="url" class="col-sm-2 control-label">跳转URL</label>
                            <div class="col-sm-3">
                                <?php echo $data['url'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="overdue_time" class="col-sm-2 control-label">过期时间</label>
                            <div class="col-sm-3">
                                <?php echo $data['overdue_time'] ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-3">
                                <select readonly name="status" id="status"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if ($statuss != null) : ?>
                                        <?php foreach ($statuss as $key=>$value) : ?>
                                            <option value="<?php echo $key; ?>" <?php if ($data['status'] === (string)$key || set_value('status') === (string)$key) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('status'); ?>
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
