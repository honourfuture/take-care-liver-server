<?php $page_title = '回复反馈' ?>
<link href="/assets/plugins/chosen/chosen.min.css" rel="stylesheet">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/feedback">反馈列表</a></li>
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

                                <form action="/admin/feedback/edit" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">问题</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="q" value="<?= $data->q; ?>"
                                                   required minlength="2" data-msg="请填写问题"
                                                   data-msg-minlength="请至少输入2个以上的字符"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">答复</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" name="a" value="<?= $data->a; ?>"
                                                   required minlength="2" data-msg="请填写答案"
                                                   data-msg-minlength="请至少输入2个以上的字符"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-flat"
                                                    style="margin-right: 5px;">提交
                                            </button>
                                            <a href="/admin/feedback" class="btn btn-default btn-flat">取消</a>
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
        $(".city").chosen();
        $(".area").chosen();
        $(".prov").chosen();
        $(".prov").chosen().change(function () {
            id = $(".prov").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
                $(".city").empty();
                $(".city").append(data.data);
                $(".city").trigger('chosen:updated');
                id = $(".city").val();
                $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
                    $(".area").empty();
                    $(".area").append(data.data);
                    $(".area").trigger('chosen:updated');
                })
            })

        })

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
