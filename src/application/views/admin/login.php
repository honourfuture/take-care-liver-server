<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>后台管理系统 | 登陆</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/assets/plugins/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/assets/plugins/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/admin/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/assets/admin/css/admin.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>后台管理系统</b>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">请输入管理员账号</p>
	
	<div  id="login-error" class="alert alert-danger" style="display: none;">账号或者密码错误</div>

    <form action="" method="post" onsubmit="return false;">
      <div class="form-group has-feedback">
	    <label id="username_error" class="control-label" for="inputError">账号</label>
        <input id="username" name="username" type="text" class="form-control" placeholder="账 号">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
	    <label id="password_error" class="control-label" for="inputError">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密 码">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat submit_form">登陆</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script>
  $(function () {
	  
	$(".submit_form").click(function(){
		var username = $.trim($("#username").val());
		var password = $.trim($("#password").val());
		if(username.length<1){
			$("#username_error").show();
			$("#username").parent().addClass("has-error");
			$("#username").focus();
			return false;
		}
		if(password.length<1){
			$("#password_error").show();
			$("#password").parent().addClass("has-error");
			$("#password").focus();
			return false;
		}
		
		$.post("/admin/check_admin",{'username':username,'password':password},function(msg){
			if(msg == "succ"){
				location.href = "/admin/dashboard";
			}else{
				$("#login-error").show();
			}
		});
	});
  });
</script>
</body>
</html>
