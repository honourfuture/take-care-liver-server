<div class="content-wrapper">
	<section class="content-header">
	  <h1>
		角色管理
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
		<li><a href="/admin/roles">角色管理</a></li>
		<li class="active">角色详情</li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-info"></i> 角色详情</h3>
						<a href="<?=$form_url?>" class="pull-right">返回</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">

								<div action="/admin/roles/edit/<?=$role->id?>" class="form-horizontal detail-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">角色名称</label> 
										<div class="col-sm-3">
											<span class="form-control"><?=$role->name?></span>
										</div>
									</div>
									<div class="form-group">
										<label for="last_name" class="col-sm-2 control-label">备注</label>
										<div class="col-sm-3">
											<span class="form-control"><?=$role->description?></span>
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
