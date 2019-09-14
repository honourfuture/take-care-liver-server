<?php $page_title = '举报处理规则' ?>
<style>
    .alert{ padding:10px;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/help">举报列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> 举报处理规则</h3>
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

                                <form action="/admin/report/edit" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data->id ?>"/>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">规则一</label>
                                        <div class="col-sm-6">
                                            <div class="alert alert-info">1天内被不同用户举报达2次，发布的权限中断，发布者可修改并重新发布。</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">规则二</label>
                                        <div class="col-sm-6">
                                            <div class="alert alert-info">1天内被不同用户举报达3次，发布的权限中断，限制发布时间30分钟。</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">规则三</label>
                                        <div class="col-sm-6">
                                            <div class="alert alert-warning">1天内被不同用户举报达4次及以上时，发布的权限中断，限制发布时间1小时，4次之后每次限制时间1小时。</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">规则四</label>
                                        <div class="col-sm-6">
                                            <div class="alert alert-danger">1周内被举报累计达10次，本周限制发布。</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">规则五</label>
                                        <div class="col-sm-6">
                                            <div class="alert alert-danger">1周内被举报累计达20次，本月限制发布。</div>
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
