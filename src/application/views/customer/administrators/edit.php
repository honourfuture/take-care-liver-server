<?php $page_title = '修改密码' ?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1>
		  <?php echo $page_title; ?>
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="/customer/"><i class="fa fa-dashboard"></i> 首页</a></li>
		<li><a href="/customer/users">会员列表</a></li>
		<li class="active"><?php echo $page_title; ?></li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $page_title; ?></h3>
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

								<form action="/customer/administrators/edit/<?= $data->id;?>" class="form-horizontal" id="createForm" method="post" accept-charset="utf-8">
									<input type="hidden" name="id" value="<?= $data->id;?>" />
									<div class="form-group">
										<label for="first_name" class="col-sm-2 control-label">用户名</label>
										<div class="col-sm-3">
											<input type="text" name="user_name" value="<?=$data->user_name?>" class="form-control" required minlength="2" data-msg="请填写用户名" data-msg-minlength="请至少输入2个以上的字符">
										</div>
									</div>
									<div class="form-group">
										<label for="last_name" class="col-sm-2 control-label">密码</label>
										<div class="col-sm-3">
											<input type="text" name="password" value="" class="form-control" required minlength="6" data-msg="请填写密码" data-msg-minlength="请至少输入6个以上的字符">
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-primary btn-flat" style="margin-right: 5px;">提交</button>
											<a href="/customer/administrators" class="btn btn-default btn-flat">取消</a>
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
