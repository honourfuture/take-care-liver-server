<div class="content-wrapper">
	<section class="content-header">
		<h1>
			用户管理
		</h1>
		<ol class="breadcrumb">
			<li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
			<li><a href="/admin/users">用户管理</a></li>
			<li class="active">用户详情</li>
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

								<div action="/admin/users/edit/<?=$user->id?>" class="form-horizontal detail-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">姓名</label>
										<div class="col-sm-3">
											<span class="form-control"><?=$user->name?></span>
										</div>
									</div>
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">昵称</label>
										<div class="col-sm-3">
											<span class="form-control"><?=$user->username?></span>
										</div>
									</div>
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">手机号</label>
										<div class="col-sm-3">
											<span class="form-control"><?=$user->mobile?></span>
										</div>
									</div>

									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">性别</label>
										<div class="col-sm-3">
											<span class="form-control"><?=($user->gender>0?'女':'男')?></span>
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
