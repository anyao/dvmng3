<?php 
require_once "model/repairService.class.php";
require_once "model/cookie.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
checkValidate();
$user=$_SESSION['user'];


$repairService=new repairService();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="普阳钢铁设备管理系统">
<meta name="author" content="安瑶">
<link rel="icon" href="img/favicon.ico">
<title>备件申报-仪表管理</title>
<style type="text/css">

.open > th, .open > td{
  background-color:#F0F0F0;
}

th > .glyphicon-trash{
  display:none;
} 


tr:hover > th > .glyphicon-trash {
  display: inline;
}

#navi{
  padding-left:40px;
  background: rgba(0, 0, 0, 0.6) none repeat scroll 0 0 !important;
  bottom: 0px;
  filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#7F000000,endcolorstr=#7F000000);
}

</style>
<link rel="stylesheet" href="bootstrap/css/jquery.enjoyhint.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/kinetic.min.js"></script>
<script src="bootstrap/js/enjoyhint.js"></script>
</head>
<body role="document">
<?php include "message.php";?>
<nav class="navbar navbar-inverse" style="margin-bottom: 0px">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="homePage.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="homePage.php">首页</a></li>
        <li class="active dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button">设备购置 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="buyGauge.php">仪表备件申报</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
             <?php if (!in_array(4,$_SESSION['funcid'])  && $_SESSION['user'] != 'admin') {
                        echo "<li role='separator' class='divider'></li><li>";
                      } 
                ?>
                <li><a href="spareList.php">备品备件</a></li>
                
                <?php if (in_array(4,$_SESSION['funcid']) || $_SESSION['user'] == 'admin') {
                        echo "<li role='separator' class='divider'></li><li><a href='devPara.php'>属性参数</a></li>";
                      } 
                ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">日常巡检 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="inspStd.php">巡检标准</a></li>
            <li><a href="inspMis.php">巡检计划</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="inspList.php">巡检记录</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修保养 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repPlan.php">检修计划</a></li>
            <li><a href="repMis.php">维修/保养任务</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="repList.php">维修记录</a></li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
       <?php if (in_array(10,$_SESSION['funcid']) || $_SESSION['user'] == 'admin') {
                      echo "<li><a href='dptUser.php'>用户管理</a></li>";
                    } 
             ?>
       
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php 
              if (empty($user)) {
                echo "用户信息";
              }else{
                echo "$user";
              } 
            ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="javascript:intro();">首次使用</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="login.php">注销</a></li>
          </ul>
          </li>
      </ul>

    </div><!--/.nav-collapse -->
  </div>
</nav>
<img src="./img/login.JPG" style="width:100%;position: absolute;top:0px;z-index: -999;height: 100%">
<div class="container">
  <div class="col-md-3 col-md-offset-9" id="navi">
  <?php  include "buyNavi.php";?>
  </div>
</div>
<div class="foothome" style="color: #f0f0f0">© 河北普阳钢铁有限公司　2015-<?php echo date("Y")?>
</div>


<?php  include "./buyJs.php";?>
<script type="text/javascript">
function intro(){
  var enjoyhint_instance = new EnjoyHint({
    onSkip:function(){
      $('#findInstall').modal('hide');
    }
  });

  var enjoyhint_script_steps = [
    {
      selector: $(".glyphicon-list-alt").parent(),
      description: '部门备件申报列表，使用部门在这里管理申报的备件。',
      showNext: true,
      showSkip: false,
    }, 
    {
      selector: $(".glyphicon-plus").parent(),
      description: '在这里添加新的备件申报。',
      showNext: true,
      showSkip: false,
    },
    { 
      selector: ".user",
      event: 'click',
      description: '点击尝试搜索申报的备件。',
      showSkip: false,
    },
    {
      selector: "#findApply .modal-body",
      description: '在这里搜索想找的备件。条件可为空，但不可全为空。',
      top: 50,
      bottom: -70,
      showNext: true,
      showSkip: false,
    }, 
    { 
      onBeforeStart: function(){
        $('#findApply').modal('hide');
      },
      selector: ".warehouse",
      event: 'click',
      description: '点击搜索备件库存。',
      showSkip: false,
    }, 
    {
      selector: "#findStore .modal-body",
      description: '在这里搜索想找的库存备件。条件可为空，但不可全为空。',
      top: 50,
      bottom: -70,
      showNext: true,
      showSkip: false,
    },
    { 
      onBeforeStart: function(){
        $('#findStore').modal('hide');
      },
      selector: ".install",
      description: '从仓库领取的备件会在这里显示。',
      showNext: true,
      showSkip: false,
    },  
    {
      selector: ".installHis",
      description: '想要查看历史备件安装验收信息可以点击这里。',
      showNext: true,
      showSkip: false,
    },
    { 
      selector: ".installSear",
      event: 'click',
      description: '点击尝试搜索已经安装验收的备件。',
      showSkip: false,
    },
    {
      selector: "#findInstall .modal-body",
      description: '在这里搜索想找已经安装验收的备件。条件可为空，但不可全为空。',
      top: 50,
      bottom: -70,
    }
  ];

  enjoyhint_instance.set(enjoyhint_script_steps);
  enjoyhint_instance.run();
}

$(function(){
  // 获取浏览器可视区域高度
  var he=document.documentElement.clientHeight;
  he = eval(he-52);
  $("#navi").css("height",he);
})
</script>
  </body>
</html>