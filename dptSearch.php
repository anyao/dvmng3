<?php 
require_once "model/cookie.php";
checkValidate();
$user=$_SESSION['user'];
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

<title>用户搜索-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  /*thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }*/
  .accordion-inner > .row {
    margin:auto 0px;
  }
  
  .accordion-inner > .row > .col-md-7{
    padding: 0px 0px 8px 0px  !important;
  }
</style>
<link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="bootstrap/css/metroStyle/metroStyle.css">
<link rel="stylesheet" href="bootstrap/css/choose.css" media="all" type="text/css">
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
<script src="bootstrap/js/dptUser-treeview.js"></script>
<script src="bootstrap/js/jsonToTree.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/jquery.ztree.core.js"></script>
<script src="bootstrap/js/jquery.ztree.excheck.min.js"></script>
</head>
<body role="document">
<?php 
	require_once "model/repairService.class.php";
	$repairService=new repairService();
	include "message.php";

	require_once "model/dptService.class.php";
	$dptService=new dptService();
?>

<nav class="navbar navbar-inverse">
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
        <li class="dropdown">
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
                      echo "<li class='active'><a href='dptUser.php'>用户管理</a></li>";
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
            <li class="divider">&nbsp;</li>
            <li><a href="login.php">注销</a></li>
          </ul>
         </li>
      </ul>

    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="container">
<div class="row">
<div class="col-md-3">
  <ul class="nav nav-pills  nav-stacked nav-self" role="tablist">
    <li role="presentation" class="active"><a href="dptSearch.php">用户搜索</a></li>
    <li role="presentation"><a href="dptUser.php">部门 / 人员</a></li>
    <li role="presentation"><a href="javascript:goto(12,'dptRole');">角色 / 功能</a></li>
  </ul>

</div>
<div class="col-md-9">
  <div class="accordion">
    <div class="accordion-group">
        <div class="accordion-heading">
           <h4>　用户搜索</h4>
        </div>
        <div class="accordion-inner" style="min-height: 800px;">  
          <div class="row">
            <div class="col-md-7">
                <div class="input-group">
                  <span class="input-group-addon">账户 / 名称</span>
                  <input type="text" class="form-control" name="find">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="yesFind"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>  
            </div>
          </div>
          <div class="row">
            <table class="table table-striped table-hover">
              <thead><tr><th>用户账号</th><th>用户姓名</th><th>所在部门</th><th>所在分厂</th>
                         <th style="width: 4%">　</th><th style="width: 4%">　</th>
                         <th style="width: 4%">　</th><th style="width: 4%">　</th></tr></thead>
              <tbody id="searchRes"></tbody>
            </table>
          </div>
            </div>
          </div>
        </div>
    </div>
</div>
</div>

<?php include "./dptNavi.php"; ?>
<?php include "./dptJs.php"; ?>
<script type="text/javascript">
$(document).on("click","#yesFind",yesFind);
function yesFind(){
  var find=$("input[name=find]").val();
  if (find.length==0) {
    $('#failAdd').modal({
        keyboard: true
    });
  }else{
    $.get("./controller/dptProcess.php",{
      flag:'findUser',
      kword:find
    },function(data){
      var $addHtml="";
      if (data.length==0) {
        $addHtml="<tr><td colspan='12'>未找到相关用户，请核实关键字。</td></tr>"
      }else{  
        for (var i = 0; i < data.length; i++) {
          if (data[i].factory == null) {
            data[i].factory = data[i].depart;
            data[i].depart = "";
          }
          $addHtml += "<tr><td>"+data[i].code+"</td><td>"+data[i].name+"</td>"+
                          "<td>"+data[i].depart+"</td><td>"+data[i].factory+"</td>"+
                      "    <td><a href=\"javascript:getBsc("+data[i].id+")\" class='glyphicon glyphicon-credit-card'></a></td>"+
                      "    <td><a href=\"javascript:getRole("+data[i].id+")\" class='glyphicon glyphicon-tower'></a></td>"+
                      "    <td><a href=\"javascript:getScale("+data[i].id+")\" class='glyphicon glyphicon-list'></a></td>"+
                      "    <td><a href=\"javascript:delUser("+data[i].id+")\" class='glyphicon glyphicon-remove'></a></td></tr>";
        }
      }
      $("#searchRes").empty().append($addHtml);
    },"json");
  }
}



//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

</script>
</body>
</html>