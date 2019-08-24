<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>
		角色管理
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
		<li><a href="/admin/roles">角色管理</a></li>
		<li class="active">添加角色</li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-plus"></i> 添加角色</h3>
						<a href="/admin/roles" class="pull-right">返回</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">

							    <?php if(!empty($message)){?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $message;?>
								</div>
								<?php }?>

								<form action="/admin/roles/create" class="form-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">角色名称</label> 
										<div class="col-sm-3">
											<input type="text" name="name" value="<?=$name?>" class="form-control" required minlength="2" data-msg="请填写角色名称" data-msg-minlength="请至少输入2个以上的字符">
										</div>
									</div>
									<div class="form-group">
										<label for="last_name" class="col-sm-2 control-label">备注</label>
										<div class="col-sm-3">
											<textarea type="text" name="description" value="" id="description" class="form-control"><?=$description?></textarea>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary btn-flat" style="margin-right: 5px;">提交</button>
											<a href="/admin/roles" class="btn btn-default btn-flat">取消</a>
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

<script>
	$(function(){
		$("#createForm").validate();
	});
</script>
