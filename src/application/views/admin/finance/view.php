<div class="content-wrapper">
	<section class="content-header">
		<h1>
			举报管理
		</h1>
		<ol class="breadcrumb">
			<li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
			<li><a href="/admin/report">举报管理</a></li>
			<li class="active">信息详情</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-info"></i> 信息详情</h3>
						<a href="<?=$form_url?>" class="pull-right">返回</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<div class="form-horizontal detail-horizontal" id="createForm">

									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">ID</label>
										<div class="col-sm-6">
											<span class="form-control"><?=$report->id?></span>
										</div>
									</div>

                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">举报人</label>
                                        <div class="col-sm-3">
                                            <img src="<?=$user->head_pic?>" style="width:50px;height: 50px" />
                                            <span class="form-control"><a href="/admin/users/view/<?=$user->id?>"><?=$user->username?></a> [用户ID：<?=$user->id?>,手机号码：<?=$user->mobile?>]</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">信息</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><a href="/admin/information/view/<?=$report->info_id?>"><?=$info->title?></a> [信息ID:<?=$report->info_id?>]</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">被举报人</label>
                                        <div class="col-sm-3">
                                            <img src="<?=$reported_user->head_pic?>" style="width:50px;height: 50px" />
                                            <span class="form-control"><a href="/admin/users/view/<?=$reported_user->id?>"><?=$reported_user->username?></a> [用户ID：<?=$reported_user->id?>,手机号码：<?=$reported_user->mobile?>]</span>
                                        </div>
                                    </div>

									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">举报内容</label>
										<div class="col-sm-3">
											<span class="form-control"><?=$report->content?></span>
										</div>
									</div>


                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">图片</label>
                                        <div class="col-sm-10">
                                            <?php foreach (json_decode($report->images) as $item) { ?>
                                                <img class="form-control" src="<?=$item?>" style="width:200px;height:200px;float:left;margin-right:10px;"/>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">状态</label>
                                        <div class="col-sm-3">
                                            <span class="form-control" style=" width:50%;display: inline-block !important;"><?=($report->status == 1 ? '待审核': '已处理')?></span>
                                            <?php if ($report->status == 1) { ?>
                                            <a href="/admin/report/edit/<?= $report->id ?>"
                                               class="btn btn-primary btn-sm" style="display: inline-block !important;"><i
                                                        class="fa fa-edit"></i> 同意</a>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">创建时间</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$report->create_at?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">更新时间</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$report->update_at?></span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">处理人</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$report->deal_user_id?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-sm-2 control-label">处理意见</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$report->deal_remark?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="first_name" class="col-sm-2 control-label">处理时间</label>
                                        <div class="col-sm-3">
                                            <span class="form-control"><?=$report->deal_at?></span>
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
