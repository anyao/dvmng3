<?php 
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="zh-CN"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="安瑶">
    <link rel="icon" href="img/favicon.ico">

    <title>登录-设备管理系统</title>
    
    <style type="text/css">
      .form-group{
        margin:25px auto !important;
      }
    </style>

    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/cover.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/js/html5shiv.js"></script>
      <script src="bootstrap/js/respond.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php
    // require_once 'model/cookie.php';
    
  ?>

  <div class="site-wrapper">
    <div class="site-wrapper-inner">
      <div class="cover-container">
        <div class="masthead clearfix">
          <div class="inner">
            <h2 class="masthead-brand"> <p>设备管理系统</p></h2>
            <nav>
              <ul class="nav masthead-nav">
                <li class="active"><a href="login.php">登录</a></li>
              </ul>
            </nav>
          </div>
        </div>

        <div class="inner cover" style="position:relative;top: -30px">
          <form action="controller/userProcess.php" method="post" class="form-horizontal">
            <div class="form-group">
              <div class="lead">
                <label class="col-md-4 control-label"><span class="glyphicon glyphicon-user"></span>　用户账号：</label>
                <div class="col-md-8">
                  <input type="text" class="form-control input-lg" name="code" placeholder="请输入账号/ID"><br/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="lead">
                <label class="col-md-4 control-label"><span class="glyphicon glyphicon-lock"></span>　密　　码：</label>
                <div class="col-md-8">
                  <input type="password" class="form-control input-lg" name="psw" placeholder="请输入密码"><br/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input checked="checked" name="keep" type="checkbox"> 记住我？
                </label>
              </div>
            </div>
            
            <div class="form-group">
              <div class="lead">
                <button class="btn btn-lg btn-default" style="width: 45%" id="yesLogin">登　　　　　录</button>
              </div>
            </div>
          </form>
        </div>


        <div class="mastfoot">
          <div class="inner">
            <p>© 河北普阳钢铁有限公司　2015-<?php echo date("Y")?></p>
          </div>
        </div>

      </div>
    </div>
  </div>


<!-- id或者密码输入错误弹出框 -->
<div class="modal fade"  id="failLogin" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div id="failInfo" class="loginModal">
            </div>
         </div><br/>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade"  id="failNull" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的信息不完整，请重新填写。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
$(function(){
  var err="<?php 
              if (!empty($_GET['err'])) {
              echo $_GET['err'];      
              }
            ?>";
  // 用户名输入错误或不存在
  if (err==1) {
    $("#failInfo").text("用户名输入错误或该用户不存在。");
    $('#failLogin').modal({
        keyboard: true
     });
  }else if(err==2){
    $("#failInfo").text("您输入的密码不正确，请重新输入!");
    $('#failLogin').modal({
        keyboard: true
     });
  }
})

// 确认登录按钮检查是否账户和密码为空
$("#yesLogin").click(function(){
  var allow_submit=true;
  $("input[name=code],input[name=psw]").each(function(){
    if($(this).val().length==0){
      allow_submit=false;
    }
  });
  if (allow_submit==false) {
     $('#failNull').modal({
         keyboard: true
      });
  }
  return allow_submit;
});
</script>
  </body>
</html>
