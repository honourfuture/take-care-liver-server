<div class="content-wrapper">
    <section class="content-header">
        <h1>
            实名认证
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/users">用户管理</a></li>
            <li class="active">实名认证</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> 用户详情</h3>
                        <a href="<?=$form_url?>" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div action="/admin/operator/editReal/<?=$user->id?>" class="form-horizontal detail-horizontal" id="createForm" method="post" accept-charset="utf-8">
                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">姓名</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$user->username?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">身份证</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$user->id_card?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">身份证正面</label>
                                        <div class="col-sm-3">
                                            <img height="240px" width="400px" src="<?=$user->id_front_pic?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">身份证反面</label>
                                        <div class="col-sm-3">
                                            <img height="240px" width="400px" src="<?=$user->id_back_pic?>">
                                        </div>
                                    </div>
                                </div>
                                <?php if($user->is_operator == 2){ ?>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div style="display: inline-block;">
                                        <form action="/admin/operator/editReal/<?=$user->id?>" class="form-horizontal" id="createForm" method="post" accept-charset="utf-8">
                                            <input type="hidden" name="is_operator" value="1">

                                            <button type="submit" class="btn btn-success btn-sm btn-primary btn-flat" style="	margin-right: 5px;">同意</button>
                                        </form></div>
                                        <form style="display: contents;" action="/admin/operator/editReal/<?=$user->id?>" class="form-horizontal" id="createForm1" method="post" accept-charset="utf-8">
                                            <input type="hidden" name="is_operator" value="3">
                                            <button type="submit" class="btn btn btn-danger btn-sm pull-left btn-primary" style="	margin-right: 5px;">拒绝</button>
                                        </form>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
