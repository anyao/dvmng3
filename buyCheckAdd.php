<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];
$uid=$_SESSION['uid'];

$sqlHelper = new sqlHelper;
$gaugeService=new gaugeService($sqlHelper);

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
<link rel="icon" href="bootstrap/img/favicon.ico">
<title>备件导入-仪表管理</title>
<style type="text/css">
  .basic{
    border-bottom: 1px solid #CCCCCC;
    padding:5px 10px 0px 10px;
  }
  .col-md-8{
    margin-bottom: 15px;
  }
  
  #yesAdd{
    width:200px;
    margin-bottom: 20px;
    display: none;
  }

</style>
<?php include "buyVendor.php"; ?>
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
        <li class="active"><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检定记录 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="checkMis.php">周检计划</a></li>
            <li><a href="checkList.php">巡检计划</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修调整 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repairMis.php">维修任务</a></li>
            <li><a href="repairList.php">维修记录</a></li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
        <li style="display: <?=(!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?=$user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="login.php">注销</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="container">
  <div class="page-header">
    <h4>　备件信息导入</h4>
  </div>
  <div class="basic">
    <div class="row">
      <div class="col-md-8">
        <input type="file" name="file" class="file-loading" id="fileInput" data-show-preview="false" multiple>
      </div>
    </div>
  </div>
  <table class="table table-bordered table-hover table-striped">
    <thead><tr>
        <th>入库日期</th><th>存货编码</th><th>名称</th><th>规格</th><th>数量</th>
        <th>单位</th><th>存货分类</th><th>供应商</th><th>备注</th>
    </tr></thead>
    <tbody>
      
    </tbody>
  </table>
  <div style="text-align: center">
    <form action="./controller/gaugeProcess.php" method="post">
      <input type="hidden" name="flag" value="addInfo">
      <input type="hidden" name="data">
      <button class="btn btn-primary" id="yesAdd">确认导入</button>       
    </form>
  </div>
</div>


<script type="text/javascript">
$(function(){
  $("input[type=file]").fileinput({
      showUpload: true,
      maxFileCount: 10,
      allowedFileExtensions : ['xls', 'xlsx'],
      mainClass: "input-group",
      language: 'zh',
      uploadIcon: '<i class="glyphicon glyphicon-play-circle text-info"></i>',
      uploadUrl: './controller/gaugeProcess.php',
      uploadExtraData: {flag: 'file2Arr', value: $(this).val()},
      uploadAsync: true,

  });

  $('#fileInput').on('fileuploaded', function(event, data, previewId, index) {
    var $addHtml = "";
    $("form input[name=data]").val(data.jqXHR.responseText);
    for (var i = 0; i < data.response.length; i++) {
      $addHtml += "<tr>";
      for(var key in data.response[i]){
        if ($.inArray(key, ['AP', 'P', 'S']) == -1) 
          $addHtml += "<td>"+data.response[i][key]+"</td>" ;
      }
      if (data.response[i]['R'] == undefined)
         $addHtml += "<td></td>";
      $addHtml += "</tr>";
    }
    $("tbody").append($addHtml);
    $("#yesAdd").show();
  });

  $('#fileInput').on('fileclear', function(event) {
    $("#yesAdd").hide();
    $("tbody").empty();
    $("form input[name=data]").val("");
  });
});
</script>
</body>
</html>