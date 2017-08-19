<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
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
<title>记录模板-设备管理系统</title>
<style type="text/css">
  .type-header{
    border-bottom: 1px solid rgba(8,31,52,0.1);
    margin-top: 15px;
  }

  .col-md-12{
    margin:10px 5px;
  }
</style>
<?php include 'buyVendor.php'; ?>
</head>
<body role="document">
<?php include "message.php";?>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="usingList.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
        <li class="dropdown active">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检定记录 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="checkMis.php">周检计划</a></li>
            <li><a href="checkList.php">检定历史</a></li>
            <li  style="display: <?=(!in_array(7, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>">
              <a href="checkXls.php">表格模板</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修调整 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repairMis.php">维修任务</a></li>
            <li style="display: <?=(!in_array(7, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>">
              <a href="repairList.php">维修记录</a>
            </li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
        <li style="display: <?=(!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?=$user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="./controller/userProcess.php?flag=downInstr">说明书</a></li>
            <li><a href="login.php">注销</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav> 

<div class="container">
  <div class="row">
    <div class="page-header">
      <h4>　检定记录表格</h4>
    </div>
  </div>
    <div class='row'>
     <div class='col-md-12'>
         <span class='glyphicon glyphicon-paperclip'></span> 
         <a href='javascript: downXls(1)'>弹性元件式一般压力表检定记录</a>
     </div>
     <div class='col-md-12'>
         <span class='glyphicon glyphicon-paperclip'></span> 
         <a href='javascript: downXls(2)'>电流电压表检定记录</a>
     </div>
     <div class='col-md-12'>
         <span class='glyphicon glyphicon-paperclip'></span> 
         <a href='javascript: downXls(3)'>流量积算仪检定记录</a>
     </div>
     <div class='col-md-12'>
         <span class='glyphicon glyphicon-paperclip'></span> 
         <a href='javascript: downXls(4)'>数字指示仪检定记录</a>
     </div>
     <div class='col-md-12'>
         <span class='glyphicon glyphicon-paperclip'></span> 
         <a href='javascript: downXls(5)'>压力（差压）变送器检定记录</a>
     </div>
    </div>
</div>
<script type="text/javascript">
  function downXls(filename){
    location.href = "./controller/checkProcess.php?flag=downXls&filename="+filename;
  }
</script>
</html>