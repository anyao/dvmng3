<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico">

    <title>注册-设备管理系统</title>

    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/cover.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="bootstrap/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="site-wrapper">
    
    <div class="site-wrapper-inner">
      
      <div class="cover-container">
        
        <div class="masthead clearfix">
          <div class="inner">
            <h2 class="masthead-brand"> <p>设备管理系统</p></h2>
            <nav>
              <ul class="nav masthead-nav">
                <li ><a href="homePage.php">首页</a></li>
                <li><a href="login.php">登录</a></li>
                <li class="active"><a href="register.php">注册</a></li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- 注册表单开始 -->
        <div class="inner cover">      
        <form action="#" method="post">
            <div class="form-group">
              <div class="lead">
                <label for="firstname" class="col-md-4 control-label"><span class="glyphicon glyphicon-user"></span>　用户名称：</label>
                <div class="col-md-8 col-ms-8 col-xs-8">
                  <input type="text" class="form-control" id="firstname" placeholder="请输入用户名/账号"><br/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="lead">                 
                <label for="password" class="col-md-4 control-label"><span class="glyphicon glyphicon-lock"></span>　密　　码：</label>
                <div class="col-md-8 col-ms-8 col-xs-8">
                  <input type="password" class="form-control" id="password" placeholder="请输入密码（六位以上）"><br/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="lead">          
                <label for="password" class="col-md-4 control-label"><span class="glyphicon glyphicon-exclamation-sign"></span>　确认密码：</label>
                <div class="col-md-8 col-ms-8 col-xs-8">
                  <input type="text" class="form-control" id="password" placeholder="请确认密码"><br/>
                </div>
              </div>
            </div>
          
            <div class="form-group">
              <div class="lead">
                <label for="dept" class="col-md-4 control-label"><span class="glyphicon glyphicon-briefcase"></span>　所在部门：</label>
                <div class="col-md-8 col-ms-8 col-xs-8">
                  <input type="text" class="form-control" id="dept" placeholder="请输入用户所在部门"><br/>
                </div>
              </div>
            </div>
 
          <div class="row">
              <div class="col-md-3 col-md-offset-3 col-xs-12">
                  <button type="reset" class="btn btn-default btn-block"><b>&nbsp;&nbsp;清空&nbsp;&nbsp;</b>
                    <span class="glyphicon glyphicon-remove"></span></button>
              </div>
              <div class="col-md-3 col-xs-12">
                  <button type="submit" class="btn btn-default btn-block"><b>&nbsp;&nbsp;提交&nbsp;&nbsp;</b>
                  <span class="glyphicon glyphicon-arrow-right"></span></button>
              </div>
              <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
          </form><!-- 注册表单结束 -->
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>© 河北普阳钢铁有限公司　2016</p>
            </div>
          </div>



      </div>
    </div>
  </div>

   <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>