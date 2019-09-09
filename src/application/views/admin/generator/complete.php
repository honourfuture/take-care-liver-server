<?php $page_title = '代码生成' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/generator/index">代码生成</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?php if (!empty($message)) { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <?php echo $message; ?>
                                    </div>
                                <?php } ?>
                                <form action="" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-3 control-label">代码生成完成</label>
                                        <div class="col-sm-6">
                                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                                <?php echo $tableName; ?> 表相关文件已创建完成! <br/>
                                                <span style="color: red;">您需要手动添加菜单, 需要修改的文件：views/admin/_templates/header.php </span> <br/>
                                                <a href="/admin/<?php echo $tableName; ?>/index" >测试一下</a>
                                            </p>
                                            <div class="panel box box-success">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                            生成文件列表
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse">
                                                    <div class="box-body">
                                                        <ul>
                                                            <?php
                                                            ?>
                                                            <?php if ($files) : ?>
                                                                <?php foreach ($files as $key => $value): ?>
                                                                    <li>
                                                                        <?php echo $value; ?>
                                                                    </li>
                                                                    <!-- <dd style="color:green;padding: 5px 0;">
                                                                         &nbsp;&nbsp;已生成
                                                                     </dd>-->
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-10">
                                            <a href="/admin/generator/index" class="btn btn-primary btn-flat" style="margin-right: 5px;">继续生成</a>
                                            <a href="/admin/dashboard" class="btn btn-default btn-flat">返回控制台</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="/assets/plugins/pwstrength/pwstrength.min.js"></script>
<script src="/assets/plugins/validate/jquery.validate.min.js"></script>
<script src="/assets/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    $(function () {
        $("#createForm").validate();

        $(".city").chosen().change(function () {
            id = $(".city").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
                $(".area").empty();
                $(".area").append(data.data);
                $(".area").trigger('chosen:updated');
            })
        })
    })
</script>
