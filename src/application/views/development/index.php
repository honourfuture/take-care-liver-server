
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          CI开发框架
          <small>1.0.0</small>
        </h1>
      </section>



      <!-- Main content -->
<div class="content body">

<section id="introduction">
  <h2 class="page-header"><a href="#introduction">介绍</a></h2>
  <p class="lead">
    CodeIgniter 是一个小巧但功能强大的 PHP 框架，作为一个简单而“优雅”的工具包，它可以为开发者们建立功能完善的 Web 应用程序。</p>
	
  <p class="lead">快码CI开发框架基于CodeIgniter 进行开发，集成基础的用户账号体系、管理后台系统等功能，同时适配web版网站、手机版网站、API接口及文档、微信公众号、微信小程序等平台，可以快速启动项目，节省项目的开发时间。
  </p>
</section><!-- /#introduction -->


<!-- ============================================================= -->
<p><br/><br/></p>

<section id="download">
  <h2 class="page-header"><a href="#download">文件结构</a></h2>
  <pre class="hierarchy bring-up" style="background:#333;color:#fff;"><code class="language-bash" data-lang="bash">
src
│  
│  
├─application
│  │      
│  ├─config
│  │      database.php  数据库参数配置
│  │      rest.php    API参数配置
│  │      routes.php  路由配置
│  │      
│  ├─controllers
│  │  │  Home.php  首页入口Controller，可以跳转到默认的站点
│  │  │  
│  │  ├─admin  管理后台Controller 
│  │  │      
│  │  ├─api  API接口Controller 
│  │  │      
│  │  ├─mobile 手机网站Controller
│  │  │      
│  │  ├─web web网站Controller
│  │  │      
│  │  └─wechat 微信公众号Controller
│  │          
│  ├─core
│  │      MY_Controller.php  Controller基类
│  │          
│  ├─libraries
│  │  │  Format.php  API控制器核心文件
│  │  │  MsgSend.php  Testin短信
│  │  │  Myupload.php  文件上传类
│  │  │  REST_Controller.php  API控制器核心文件
│  │  │  Template.php   模板核心文件
│  │  │  
│  │  ├─IGt 个推推送
│  │  │              
│  │  └─oss 阿里云OSS云存储
│  │      
│  ├─models 数据模型Model
│  │      
│  └─views
│      │  
│      ├─admin 管理后台Views
│      │          
│      ├─mobile 手机版网站Views
│      │          
│      ├─web web版网站Views
│      │          
│      └─wechat 微信公众号Views
│                  
│                  
├─assets 图片/js/css等静态文件
│              
├─docs API文档
│                              
├─session Session的文件缓存
│      
├─system  CI框架核心文件
│                  
├─tests 测试文件夹
│              
└─vendor  Composer第三方库文件夹
</code></pre>
</section>


<!-- ============================================================= -->
<p><br/><br/></p>
<section id="admin">
  <h2 class="page-header"><a href="#admin">管理后台</a></h2>
  <p class="lead">管理后台网址：<a href="/admin" target="_blank">/admin</a></p>
  <p class="lead">用户名：admin</p>
  <p class="lead">密码：111111</p>
</section>


<!-- ============================================================= -->
<p><br/><br/></p>
<section id="api">
  <h2 class="page-header"><a href="#api">API接口及文档</a></h2>
  <p class="lead">API接口网址：<a href="/docs" target="_blank">/docs</a></p>
  <p class="lead">接口文档使用swagger-php进行处理，请遵守开发规范，如果不熟悉调用方式，请参考<a href="https://github.com/zircote/swagger-php/blob/master/Examples/petstore.swagger.io/controllers/UserController.php" target="_blank">swagger-php示例</a>，或者参考api中的Controller文件</p>
</section>



<!-- ============================================================= -->
<p><br/><br/></p>
<section id="environment">
  <h2 class="page-header"><a href="#environment">运行环境</a></h2>
  <p class="lead">PHP:5.6+</p>
  <p class="lead">MySQL:5.6+</p>
</section>

<!-- ============================================================= -->
<p><br/><br/></p>
<section id="dependencies">
  <h2 class="page-header"><a href="#dependencies">第三方开发框架</a></h2>
  <p class="lead">本项目使用了以下第三方开发框架</p>
  <ul class="bring-up">
    <li><a href="http://codeigniter.org.cn/user_guide/" target="_blank">Codeigniter</a></li>
    <li><a href="https://github.com/almasaeed2010/AdminLTE" target="_blank">Admin LTE</a></li>
    <li><a href="https://github.com/chriskacerguis/codeigniter-restserver" target="_blank">CodeIgniter Rest Server</a></li>
    <li><a href="https://github.com/zircote/swagger-php" target="_blank">swagger-php</a></li>
  </ul>
</section>


	  <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
