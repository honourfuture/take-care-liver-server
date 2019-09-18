<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>
		用户管理
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
		<li><a href="/admin/users">用户管理</a></li>
		<li class="active">编辑用户信息</li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-plus"></i> 编辑用户信息</h3>
						<a href="<?=$form_url?>" class="pull-right">返回</a>
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
								<form action="/admin/users/edit/<?=$user->id?>" class="form-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<!--<div class="form-group">
										<label for="name" class="col-sm-2 control-label">姓名</label>
										<div class="col-sm-3">
											<input type="text" name="name" value="<?/*=$name*/?>" id="name" class="form-control">
										</div>
									</div>-->
									<div class="form-group">
										<label for="username" class="col-sm-2 control-label">昵称</label>
										<div class="col-sm-3">
											<input type="text" name="username" value="<?=$username?>" id="description" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="mobile" class="col-sm-2 control-label">手机号码</label>
										<div class="col-sm-3">
											<input type="text" name="mobile" value="<?=$mobile?>" class="form-control" required minlength="11"  maxlength="11" data-msg="请填写手机号码" data-msg-minlength="请至少输入11位数字">
										</div>
									</div>
									<div class="form-group">
										<label for="password" class="col-sm-2 control-label">密码</label>
										<div class="col-sm-3">
											<input type="text" name="password" value="<?=$password?>" id="password" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="birthday" class="col-sm-2 control-label">生日</label>
										<div class="col-sm-3">
											<input type="text" name="birthday" value="<?=$birthday?>" id="birthday" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label for="gender" class="col-sm-2 control-label">性别</label>
										<div class="col-sm-3">
											<select name="gender" id="gender" class="form-control">
												<option value="">请选择性别</option>
												<option value="0" <?=($gender == '0')?'selected = "selected"':''?>>女</option>
												<option value="1" <?=($gender == '1')?'selected = "selected"':''?>>男</option>
											</select>
										</div>
									</div>
									<!--<div class="form-group">
										<label for="info" class="col-sm-2 control-label">简介</label>
										<div class="col-sm-3">
											<textarea type="text" name="info" id="info" class="form-control"><?/*=$info*/?></textarea>
										</div>
									</div>-->
									<div class="form-group">
										<label for="active" class="col-sm-2 control-label">状态</label>
										<div class="col-sm-3">
											<select name="active" id="active" class="form-control">
												<option value="">请选择状态</option>
												<option value="1" <?=($active == '1')?'selected = "selected"':''?>>正常</option>
												<option value="0" <?=($active == '0')?'selected = "selected"':''?>>冻结</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="parent_id" class="col-sm-2 control-label">内部管理员</label>
										<div class="col-sm-3">
											<select name="employee_id" id="employee_id" class="form-control" >
												<option value="0">请选择</option>
												<?php foreach($employee_list as $employee){ ?>
													<option value="<?=$employee['id']?>" <?=($employee['id'] == $employee_id)?'selected = "selected"':''?>><?=$employee['user_name']?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary btn-flat" style="	margin-right: 5px;">提交</button>
											<a href="/admin/users" class="btn btn-default btn-flat">取消</a>
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
