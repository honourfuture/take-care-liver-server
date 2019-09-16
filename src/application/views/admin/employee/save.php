<?php $page_title = ($data['id']?'编辑':'新增').' 内部员工' ?>
<link href="/assets/plugins/chosen/chosen.min.css" rel="stylesheet">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/employee/index">内部员工 列表</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
                        <a href="/admin/employee/index" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?php if (!empty($message)) { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <?php echo$message; ?>
                                    </div>
                                <?php } ?>

                                <form action="/admin/employee/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>"/>


                                    <div class="form-group">
                                        <label for="level" class="col-sm-2 control-label">等级</label>
                                        <div class="col-sm-3">
                                            <select name="level" id="level"  class="form-control">
                                                <option value="">请选择</option>
                                                <?php if ($levels != null) : ?>
                                                    <?php foreach ($levels as $key=>$value) : ?>
                                                        <option value="<?php echo $key; ?>" <?php if ($data['level'] === (string)$key ) : ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <?php echo form_error('level'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="parent_id" class="col-sm-2 control-label">父级</label>
                                        <div class="col-sm-3">
                                            <select name="parent_id" id="parent_id" class="form-control" >
                                                <option value="0">暂无父级</option>
                                                <?php foreach($category_list as $category){ ?>
                                                    <option value="<?=$ct?>" <?=($category == $data['parent_id'])?'selected = "selected"':''?>><?=$category?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="user_name" class="col-sm-2 control-label">用户名</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" id="user_name" name="user_name" value="<?php echo $data['user_name'] ?>"
                                                   data-msg="请填写user_name"
                                                   required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                             />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">密码</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" id="password" name="password" value=""
                                             />
                                        </div>
                                    </div>
                       <!-- <div class="form-group">
                            <label for="created_at" class="col-sm-2 control-label">创建时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写created_at" name="created_at" class="form-control pull-right" id="created_at" value="<?php /*echo $data['created_at'] */?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="updated_at" class="col-sm-2 control-label">更新时间</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写updated_at" name="updated_at" class="form-control pull-right" id="updated_at" value="<?php /*echo $data['updated_at'] */?>" />
                                </div>
                            </div>
                        </div>-->

                       <!-- <div class="form-group">
                            <label for="is_default" class="col-sm-2 control-label">是否默认管理员</label>
                            <div class="col-sm-3">
                                <select name="is_default" id="is_default"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php /*if ($is_defaults != null) : */?>
                                        <?php /*foreach ($is_defaults as $key=>$value) : */?>
                                            <option value="<?php /*echo $key; */?>" <?php /*if ($row['is_default'] === (string)$key || set_value('is_default') === (string)$key) : */?>selected="selected"<?php /*endif; */?>><?php /*echo $value; */?></option>
                                        <?php /*endforeach; */?>
                                    <?php /*endif; */?>
                                </select>
                                <?php /*echo form_error('is_default'); */?>
                            </div>
                        </div>-->


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-flat"
                                            style="margin-right: 5px;">提交
                                    </button>
                                    <a href="/admin/employee/index" class="btn btn-default btn-flat">取消</a>
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
        $('#created_at').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        $('#updated_at').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
        /*$(".prov").change(function () {
            id = $(".prov").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
            });
        });*/

        $("#level").chosen();
        $("#parent_id").chosen();
        $("#level").chosen().change(function () {
            getParent();
        });

        function getParent(){
            var level = $("#level").val();
            if(level == "A"){
                $("#parent_id").empty();
            }else{
                level = parseInt(level.charCodeAt(0))-1;
                level = String.fromCharCode(level);
                console.log(level);
                $.post("/admin/employee/parent" , {"level": level}, function (data) {
                    $("#parent_id").empty();
                    var optHtml="";
                    data.data.forEach(function(item){
                        optHtml += '<option value="' + item.id + '">' + item.user_name + '</option>';
                    });
                    $("#parent_id").append(optHtml);
                    $("#parent_id").trigger('chosen:updated');
                });
            }
        }

        getParent();
    });
</script>
