<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$devService = new devService($sqlHelper);
$dptService = new dptService($sqlHelper);

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;

$paging->gotoUrl="checkMis.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}


if (empty($_REQUEST['flag'])) 
  $devService->getChkPaging($paging);
else{
    // $status = $_POST['status'];
    // $name = $_POST['name'];
    // $spec = $_POST['spec'];

    // $devService->findDev($status, $name, $spec, $paging); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="普阳钢铁设备管理系统">
<meta name="author" content="安瑶">
<link rel="icon" href="img/favicon.ico">
<title>周检计划-设备管理系统</title>
<style type="text/css">
  .glyphicon-check, .glyphicon-unchecked{
    display:inline !important;
  }

  #checkPlan{
    padding-left:0px;
    padding-right: 0px;
    width:5%;
    display: none;
    cursor: pointer;
  }
  .page-header{
    margin-bottom: 0px !important
  }

  .page-header > h4 > span{
    float: right;
    padding-right: 25px
  }

  .glyphicon-search{
    cursor:pointer;
  }
</style>
<?php include 'buyVendor.php'; ?>
</head>
<body role="document">
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
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
        <li class="dropdown active">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检定记录 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="inspStd.php">周检计划</a></li>
            <li><a href="inspMis.php">检定记录</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修保养 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repPlan.php">检修计划</a></li>
            <li><a href="repMis.php">维修/保养任务</a></li>
            <li><a href="repList.php">维修记录</a></li>
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
  <div class="row">
    <div class="col-md-12">
      <div class="page-header">
          <h4>　周检计划
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
        <!-- <span class="glyphicon glyphicon-thumbs-up" style="margin-left: 5px"></span> -->
          <th><span class="glyphicon glyphicon-download-alt" id="checkPlan"></span></th>
          <th>出厂编号</th><th>设备名称</th><th>规格型号</th><th>所在分厂部门</th><th>安装地点</th><th>有效日期</th>
          <th>状态</th><th style="width:4%"></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i]; 
            echo "<tr>
	                <td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>
	                <td>{$row['codeManu']}</td>
	                <td><a href='using.php?id={$row['id']}'>{$row['name']}</a></td>
	                <td>{$row['spec']}</td>
	                <td>{$row['factory']}{$row['depart']}</td>
	                <td>{$row['loc']}</td>
	                <td>{$row['status']}</td>
	                <td>{$row['valid']}</td>
	                <td><a class='glyphicon glyphicon-list-alt' href='javascript:delDev({$row['id']});'></a></td>
	              </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
    </div>
  </div>
</div>

<div class="modal fade" id="passCheck">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">批量检定</h4>
      </div>
      <form class="form-horizontal" action="./controller/checkProcess.php" method="post">
        <div class="modal-body"> 
          <div class="form-group">
            <label class="col-sm-3 control-label">检定日期：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control datetime" name="time" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">检定类型：</label>
            <div class="col-sm-8">
              <select class="form-control" name="type">
                <?php  
                  $chkType = $checkService->getTypeAll();
                  for ($i=1; $i < count($chkType); $i++) { 
                    echo "<option value='{$chkType[$i]['id']}'>{$chkType[$i]['name']}</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">检定结果：</label>
            <div class="col-sm-8">
              <select class="form-control" name="res" readonly>
                <option value="1">合格</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="passCheck">
          <input type="hidden" name="id">
          <span style="color:red; display:none" id="failPass">日期必填。</span>
          <button class="btn btn-primary" id="yesPass">批量检定合格</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 搜索备件检定记录-->
<div class="modal fade" id="searchForm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索</h4>
      </div>
      <form class="form-horizontal" method="post">
        <div class="modal-body">
          <div class="form-group">
              <label class="col-sm-3 control-label">运行状态：</label>
              <div class="col-sm-8">
                <select class="form-control" name="status">
                  <?php  
                    $status = $devService->getStatus();
                    for ($i=0; $i < count($status); $i++) { 
                      echo "<option value='{$status[$i]['id']}'>{$status[$i]['status']}</option>";
                    }
                  ?>
                </select>
              </div>
            </div> 
            <div class="form-group">
              <label class="col-sm-3 control-label">备件名称：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">规格型号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="spec">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="findDev">
            <button class="btn btn-primary" id="yesFind">确认</button>
          </div>
        </form>
    </div>
  </div>
</div>

<?php include 'devJs.php';?>
<script type="text/javascript">
$("#takeAll .glyphicon-thumbs-up").click(function(){
  var str = takeAll();
  $("#passCheck input[name=id]").val(str);
  $('#passCheck').modal({
    keyboard: true
  });
})

$("#yesPass").click(function(){
  var allow_submit = true;
  if ($("#passCheck input[name=time]").val() == "") {
    allow_submit = false;
    $("#failPass").show();
  }
  return allow_submit;
});

$("#checkPlan").click(function(){
  var str = checkPlan();
  location.href = "./controller/checkProcess.php?flag=xlsPlan&devid="+str;
});

function checkPlan(){
  var str = "";
  $(".glyphicon-check").each(function(){
    str += $(this).attr('chosen') + ",";
  });
  return str;
}

// 搜索
$(".glyphicon-search").click(function(){
  $('#searchForm').modal({
    keyboard: true
  });
});

// 多选
$(".tablebody").on("click","span.chosen",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked chosen");
    $(this).toggleClass("glyphicon glyphicon-check chosen");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $("#checkPlan").show();
    }else{
      $("#checkPlan").hide();
    }
});

//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});


   </script>

  </body>
</html>