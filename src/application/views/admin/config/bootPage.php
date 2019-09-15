<?php $page_title = '引导页' ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="">App管理</a></li>
            <li class="active"><?php echo $page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-warning"></i> 引导页</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <form action="/admin/config/bootPage" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8" enctype="multipart/form-data" >
                                    <?php echo form_open_multipart('upload/do_upload');?>
                                    <div class="form-group">
                                        <label for="indate" class="col-sm-2 control-label">图片list</label>
                                        <div class="col-sm-3">
                                            <input type="file" name="userfile" size="20" />
                                            <?php if(isset($images[0]) && $images[0]){?>
                                           <img src=<?=$images[0]?> />
                                            <?php
                                             }
                                            ?>
                                        </div>
                                    </div>
                                   <div class="form-group">
                                        <label for="radius" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-3">
                                            <input type="file" name="userfile1" size="20" />
                                            <?php if(isset($images[1]) && $images[1]){?>

                                            <img src=<?=$images[1]?> />
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="radius" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-3">
                                            <input type="file" name="userfile2" size="20" />
                                            <?php if(isset($images[2]) && $images[2]){?>
                                            <img src=<?=$images[2]?> />
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-flat"
                                                    style="margin-right: 5px;">提交
                                            </button>
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
    })
</script>
