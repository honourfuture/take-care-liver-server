<?php $page_title = '代码生成' ?>
<?php
$canPost = TRUE; //能够提交
$index = 1;
?>
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
                        <h3 class="box-title">
                            <i class="fa fa-plus"></i>
                            <?php echo $page_title; ?>
                        </h3>
                        <div class="box-tools">
                            <!--<a href="/admin/generator/index" class="btn btn-block btn-primary btn-flat"><i
                                    class="fa fa-arrow-left"></i> 上一步</a>-->
                        </div>
                    </div>
                    <div class="box-body">

                        <?php if (!empty($message)) { ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                <?php echo $message; ?>
                            </div>
                        <?php } ?>

                        <form action="" class="form-horizontal" id="createForm" method="post"
                              accept-charset="utf-8">
                            <div class="panel box <?=$canPost? 'box-success':'box-danger'?>">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" href="#collapseZero">
                                            基础信息
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseZero" class="panel-collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <div class="form-group"  style="margin-top:10px;">
                                                    <label for="table" class="col-sm-5 control-label">表名称</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" name="table" value="<?= $tableName; ?>"
                                                               required minlength="2" data-msg="请填写表名称" placeholder="测试请输入: example"
                                                               data-msg-minlength="请至少输入2个以上的字符"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="funName" class="col-sm-5 control-label">功能名称</label>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" name="funName" value="<?= $funName; ?>"
                                                               required minlength="2" data-msg="请填写功能名称" placeholder="请输入功能名称"
                                                               data-msg-minlength="请至少输入2个以上的字符"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="funName" class="col-sm-5 control-label">生成权限</label>
                                                    <div class="col-sm-7">
                                                        <div class="radio" style="display: inline-block;">
                                                            <label>
                                                                <input type="radio" name="is_menu" id="is_menu1" value="1" <?php if ($is_menu==1) { echo 'checked'; } ?> />
                                                                是
                                                            </label>
                                                        </div>&nbsp;&nbsp;
                                                        <div class="radio" style="display: inline-block;">
                                                            <label>
                                                                <input type="radio" name="is_menu" id="is_menu2" value="0"  <?php if ($is_menu==0 || empty($is_menu) ) { echo 'checked'; } ?> />
                                                                否
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" style="margin-top:40px;">
                                                    <div class="col-sm-offset-5 col-sm-12">
                                                        <button type="button" class="btn btn-success btn-flat"  style="margin-right: 5px;" onclick="submit1('reload')" >加载字段</button>
                                                       <!-- <a href="/admin/generator/index" class="btn btn-default btn-flat">取消</a>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-8">
                                                <div class="form-group">
                                                    <label for="last_name" class="col-sm-3 control-label"></label>
                                                    <div class="col-sm-9">
                                                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                                            需要先创建对应数据表，可自动生成表的模型,后管控制器,接口控制器,视图等代码<br/>
                                                            <span style="color: red;">警告：若该表已存在对应的model，controller，view的文件，所有文件会被自动覆盖 </span>
                                                        </p>
                                                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                                            魔法操作符，填入魔法字符则会生成对应的特殊功能，例如:<br/>
                                                            $array$0:停用|1:启用 &nbsp;(说明: $array$键值:键名|键值:键名 如status需要有两个状态组成的数组)<br/>
                                                            $id$article &nbsp;(说明: $id$表名 例如字段article_id为关联article表的id)<br/>
                                                            $max$ &nbsp;(说明: 常用于price价格等需要最大值和最小值的字段)<br/>
                                                        </p>
                                                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;color:red;">
                                                            默认保留字段: id, update_time, create_time, 在创建表时必须要有, 具体参考example表
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!--<input name="post" type="hidden" value="1" />-->
                            <!--<input name="table" type="hidden" value="<?/*=$tableName */?>" />-->
                            <div class="panel box <?=$canPost? 'box-success':'box-danger'?>">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" href="#collapseOne">
                                            <?php echo $tableName; ?> 字段列表
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse">
                                    <div class="box-body">
                                        <table  class="table table-condensed table-hover">
                                            <thead>
                                            <tr>
                                                <th>字段名称</th>
                                                <th>字段类型</th>
                                                <th>字段备注</th>
                                                <th width="250">魔法操作符</th>
                                            </tr>
                                            </thead>
                                            <?php if ($result) : ?>
                                                <?php foreach ($result as $key => $value) : ?>
                                                    <tr>
                                                        <th><?php echo $value['COLUMN_NAME']; ?></th>
                                                        <td><?php echo $value['COLUMN_TYPE']; ?></td>
                                                        <td><input type="text" required  id="comment_<?php echo $value['COLUMN_NAME']; ?>" name="column_desc[]" value="<?php echo $value['COLUMN_COMMENT']; ?>" /></td>
                                                        <td><input type="text"  id="magic_<?php echo $value['COLUMN_NAME']; ?>" name="magic[]" value="<?php
                                                                    if($value['COLUMN_NAME'] =='price' && $tableName=='example'){
                                                                        echo '$max$';
                                                                    }else if($value['COLUMN_NAME'] =='article_category' && $tableName=='example'){
                                                                        echo '$id$article';
                                                                    }else if($value['COLUMN_NAME'] =='status'){
                                                                        echo '$array$0:禁用|1:启用';
                                                                    }
                                                                ?>" /></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <!--<tr>
                                                <th>&nbsp;</th>
                                                <td></td>
                                                <td></td>
                                            </tr>-->
                                        </table>

                                    </div>
                                </div>
                            </div>


                            <div class="panel box <?=$canPost? 'box-success':'box-danger'?>">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                            待生成文件列表
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse">
                                    <div class="box-body">
                                        <dl>
                                            <?php if ($files) : ?>
                                                <?php foreach ($files as $key => $value): ?>
                                                    <dt>
                                                        <input type="checkbox" name="file_paths[]" class="file_paths" value="<?=$value ?>" checked />
                                                        <?php echo $index;$index++; ?>. <?php echo $value; ?>
                                                    </dt>
                                                    <?php if(file_exists($value)) :?>
                                                        <?php $canPost = FALSE; ?>
                                                        <dd style="color:red;padding: 5px 0;">
                                                            &nbsp;&nbsp; &nbsp;&nbsp;错误：文件已存在，需要手动删除
                                                        </dd>
                                                    <?php else : ?>
                                                        <dd style="color:green;padding: 5px 0;">
                                                            &nbsp;&nbsp;准备就绪
                                                        </dd>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </dl>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-5 col-sm-12">
                                    <button type="button" class="btn btn-primary btn-flat" style="margin-right: 5px;" onclick="submit1('submit')" >提交</button>
                                    <a href="/admin/generator/index" class="btn btn-default btn-flat">取消</a>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="/assets/plugins/pwstrength/pwstrength.min.js"></script>
<script src="/assets/plugins/validate/jquery.validate.min.js"></script>

<script>
    $(function () {
        $("#createForm").validate();


    });

    //提交
    function submit1(type){
        var reload = "/admin/generator/index";
        var submit = "/admin/generator/next";
        debugger;
        if(type == 'reload'){
            $("#createForm").attr("action",reload);
            $("#createForm").submit();
        }else if(type == 'submit'){
            $("#createForm").attr("action",submit);
            $("#createForm").submit();
        }else{
            return false;
        }
    }
</script>
