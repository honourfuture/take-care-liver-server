<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="/assets/plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2/select2.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/plugins/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/assets/plugins/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/admin/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/assets/admin/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/assets/admin/css/admin.css">

    <link rel="stylesheet" href="/assets/plugins/toastr/toastr.min.css">

    <!-- jQuery 2.2.3 -->
    <script src="/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/assets/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/admin/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script src="/assets/admin/js/admin.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="/admin" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>后台</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>管理后台</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">切换</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">您有1条通知</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 新的订单
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">查看全部</a></li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/assets/admin/img/user2-160x160.jpg" class="user-image" alt="头像">
                            <span class="hidden-xs">管理员</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/assets/admin/img/user2-160x160.jpg" class="img-circle" alt="头像">
                                <p>
                                    超级管理员
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="/admin/administrators/edit/<?= $admin_id; ?>"
                                       class="btn btn-default btn-flat">个人资料</a>
                                </div>
                                <div class="pull-right">
                                    <a href="/admin/logout" class="btn btn-default btn-flat">登出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/assets/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>管理员</p>
                </div>
            </div>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="<?= active_link_controller('dashboard') ?>"><a href="/admin/dashboard"><i
                            class="fa fa-dashboard"></i> <span>控制台</span></a></li>

				<!-- 业务菜单-BEGIN -->
				<li class="header">业务管理</li>
				<li class="treeview">
				  <a href="#">
					<i class="fa fa-pie-chart"></i>
					<span>业务管理-1</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li><a href="#"><i class="fa fa-circle-o"></i> 业务管理-1</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> 业务管理-1</a></li>
				  </ul>
				</li>
				<li class="treeview">
				  <a href="#">
					<i class="fa fa-pie-chart"></i>
					<span>业务管理-2</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li><a href="#"><i class="fa fa-circle-o"></i> 业务管理-2</a></li>
					<li><a href="#"><i class="fa fa-circle-o"></i> 业务管理-2</a></li>
				  </ul>
				</li>
				<li><a href="#"><i class="fa fa-pie-chart"></i> <span>业务管理-3</span></a></li>

                <li class="treeview <?= active_link_controller('hospitals') ?>">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i>
                        <span>医院管理</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= active_link_method('hospitals', 'index') ?>"><a href="/admin/hospitals/index"><i class="fa fa-circle-o"></i> 医院列表</a></li>
                        <li class="<?= active_link_method('hospitals', 'save') ?>"><a href="/admin/hospitals/save"><i class="fa fa-circle-o"></i> 新建医院</a></li>
                    </ul>
                </li>
				<!-- 业务菜单-END -->

                <li class="header">人员管理</li>
				<li class="<?= active_link_controller('administrators') ?>"><a href="/admin/administrators"><i class="fa fa-circle-o"></i> <span>管理员</span></a></li>
                <li class="<?= active_link_controller('users') ?>"><a href="/admin/users"><i class="fa fa-user"></i> <span>用户管理</span></a></li>
    
 
                <li class="header">系统设置</li>
                <li class="treeview <?= active_link_controller('settings') ?>  <?= active_link_controller('smslogs') ?>  <?= active_link_controller('pages') ?> <?= active_link_controller('help') ?> <?= active_link_controller('feedback') ?> <?= active_link_controller('notice') ?>">
                    <a href="#">
                        <i class="fa  fa-cog"></i> <span>系统管理</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
                    </a>
                    <ul class="treeview-menu">
                        <!--<li class="<?= active_link_method('settings', 'index') ?>"><a href="/admin/settings"><i class="fa fa-circle-o"></i> 系统参数</a></li>
                        <li class="<?= active_link_method('smslogs', 'index') ?>"><a href="/admin/smslogs"><i class="fa fa-circle-o"></i> 短信记录</a></li>
                        <li class="<?= active_link_method('pages', 'index') ?>"><a href="/admin/pages"><i class="fa fa-circle-o"></i> 静态页面</a></li>-->
                        <li class="<?= active_link_method('help', 'index') ?>"><a href="/admin/help"><i class="fa fa-circle-o"></i> 帮助管理</a></li>
                        <li class="<?= active_link_method('feedback', 'index') ?>"><a href="/admin/feedback"><i class="fa fa-circle-o"></i> 意见反馈</a></li>
                        <li class="<?= active_link_method('notice', 'index') ?>"><a href="/admin/notice"><i class="fa fa-circle-o"></i> 系统通知</a></li>
                    </ul>
                </li>
                <!--<li class="treeview <?= active_link_controller('appsplashes') ?>  <?= active_link_controller('appbanners') ?>  <?= active_link_controller('appsettings') ?>">
                    <a href="#">
                        <i class="fa  fa-mobile"></i> <span>APP管理</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= active_link_method('appsplashes', 'index') ?>"><a href="/admin/appsplashes"><i class="fa fa-circle-o"></i>启动页面</a></li>
                        <li class="<?= active_link_method('appbanners', 'index') ?>"><a href="/admin/appbanners"><i class="fa fa-circle-o"></i> 广告图</a></li>
                        <li class="<?= active_link_method('appsettings', 'index') ?>"><a href="/admin/appsettings"><i class="fa fa-circle-o"></i> APP参数设置</a></li>
                    </ul>
                </li>-->
                <li class="treeview <?= active_link_controller('generator') ?>
           <?= active_link_controller('example') ?>
           ?>">
                    <a href="#">
                        <i class="fa  fa-mobile"></i> <span>开发中心</span>
						<span class="pull-right-container">
						  <i class="fa fa-angle-left pull-right"></i>
						</span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?= active_link_method('generator', 'index') ?>"><a href="/admin/generator/index"><i class="fa fa-circle-o"></i> 代码生成</a></li>
                        <li class="<?= active_link_method('example', 'index') ?>"><a href="/admin/example/index"><i class="fa fa-circle-o"></i> Example</a></li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
