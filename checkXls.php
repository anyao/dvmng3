<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$dptService = new dptService($sqlHelper);
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

  #tree{
    overflow-y: scroll;
    height: 400px;
  }

  #treeBefore,
  #treePlanMonth,
  #treePlanYear{
    overflow-y: scroll;
    height: 200px;
  }

  .table-bordered{
    margin-top: 20px
  }

  .table-bordered th, .table-bordered td{
    text-align: center !important;
  }

  #checkFinishedBefore .col-md-7 .input-group,
  #planMonth .col-md-7 .input-group,
  #planYear .col-md-7 .input-group{
    margin-top: 15px;
  }

  .fail-submit{
    display: none;
    color: red;
  }
</style>
<?php 
include 'buyVendor.php';
include 'devJs.php';
?>
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
      <h4>　检定记录模板</h4>
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
  <div class="row" style="margin-top: 30px">
    <div class="page-header">
      <h4>　计量目标完成情况</h4>
    </div>
  </div>
  <div class="row">
    <div class='col-md-12'>
      <span class='glyphicon glyphicon-paperclip'></span> 
      <a href='javascript: finishBefore()'>历史</a>
    </div>
    <div class='col-md-12'>
      <span class='glyphicon glyphicon-paperclip'></span> 
      <a href='javascript: finishMonth();'>本月</a>
    </div>
  </div>
  <div class="row" style="margin-top: 30px">
    <div class="page-header">
      <h4>　周检计划下载</h4>
    </div>
  </div>
  <div class="row">
    <div class='col-md-12'>
      <span class='glyphicon glyphicon-paperclip'></span> 
      <a href='javascript: planYear()'>年周检计划</a>
    </div>
    <div class='col-md-12'>
      <span class='glyphicon glyphicon-paperclip'></span> 
      <a href='javascript: planMonth();'>月周检计划</a>
    </div>
  </div>
</div>

<div class="modal fade" id="checkFinishedMonth">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" id="formMonth">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">本月计量完成情况</h4>
        </div>
        <div class="modal-body">
          <div class="row ztree-row">
            <div class="col-md-5">
              <ul id="tree" class="ztree"></ul>
            </div>
            <div class="col-md-7">
              <table class="table table-bordered">
                <tr><th>计划送检</th></tr>
                <tr><td res="countPlan"> 0 </td></tr>
                <tr><th>实际送检</th></tr>
                <tr><td res="countChecked"> 0 </td></tr>
                <tr><th>计量确认率</th></tr>
                <tr><td res="perConfirm">0 %</td></tr>
                <tr><th>设备周检率</th></tr>
                <tr><td res="perChecked">0 %</td></tr>
                <tr><th>计量确认合格率</th></tr>
                <tr><td res="perPass">0 %</td></tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="takeDpt">
          <input type="hidden" name="flag" value="finishMonth">
          <a class="btn btn-primary" id="allDpt" onclick="clickMonth(null, null, {id:0});">全部</a>
        </div>
      </form> 
    </div>
  </div>
</div>  

<div class="modal fade" id="checkFinishedBefore">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" id="formBefore" action="./controller/checkProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title">历史计量完成情况</h4>
        </div>
        <div class="modal-body">
          <div class="row ztree-row">
            <div class="col-md-5">
              <ul id="treeBefore" class="ztree"></ul>
            </div>
            <div class="col-md-7">
              <div class="input-group" style="margin-top: 60px">
                <div class="radio">
                  <label>
                    <input type="radio" onclick="clickBefore(null,null,{id:0});">
                    全部
                  </label>
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-addon">选择年份</span>
                <input class="form-control datetime" name="before" readonly="" type="text">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="takeDpt">
          <input type="hidden" name="flag" value="finishBefore">
          <span style="color:red;display:none" id="failBefore">部门或年份没有选择。</span>
          <button class="btn btn-primary" id="yesBefore">下载表格</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<div class="modal fade" id="planMonth">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="./controller/checkProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title">月周检计划</h4>
        </div>
        <div class="modal-body">
          <div class="row ztree-row">
            <div class="col-md-5">
              <ul id="treePlanMonth" class="ztree"></ul>
            </div>
            <div class="col-md-7">
              <div class="input-group" style="margin-top: 60px">
                <div class="radio">
                  <label>
                    <input type="radio" onclick="clickBefore(null,null,{id:0});">
                    全部
                  </label>
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-addon">选择月份</span>
                <input class="form-control" name="planMonth" readonly="" type="text" value="<?=date('Y-m')?>">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="takeDpt">
          <input type="hidden" name="flag" value="planMonth">
          <span class="fail-submit failNull">信息填写不完整。</span>
          <span class="fail-submit failYear">最多只能看到下一年的计划。</span>
          <button class="btn btn-primary" id="yesPlanMonth">下载表格</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<div class="modal fade" id="planYear">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="./controller/checkProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title">年周检计划</h4>
        </div>
        <div class="modal-body">
          <div class="row ztree-row">
            <div class="col-md-5">
              <ul id="treePlanYear" class="ztree"></ul>
            </div>
            <div class="col-md-7">
              <div class="input-group" style="margin-top: 60px">
                <div class="radio">
                  <label>
                    <input type="radio" onclick="clickBefore(null,null,{id:0});">
                    全部
                  </label>
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-addon">选择年份</span>
                <input class="form-control" name="planYear" readonly="" type="text" value="<?=date('Y')?>">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="takeDpt">
          <input type="hidden" name="flag" value="planYear">
          <span class="fail-submit failNull">信息填写不完整。</span>
          <span class="fail-submit failYear">最多只能看到下一年的计划。</span>
          <button class="btn btn-primary" id="yesPlanYear" type="button">下载表格</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<!-- 删除失败弹出框 -->
<div class="modal fade"  id="0plan">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">所选部门所选日期没有仪表需要检定。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<script type="text/javascript">
  $(function(){
    var err = <?= !empty($_GET['err']) ? $_GET['err'] : "\"suc\""?>;
    if (err == '0plan') 
      $("#0plan").modal({ keyboard: true });
  })

  $("#yesPlanMonth").click(function(){
    var allow_submit =true,
    planMonth = $("#planMonth input[name=planMonth]").val(),
    takeDpt = $("#planMonth input[name=takeDpt]").val();
    if (!planMonth || !takeDpt) {
      $("#planMonth .fail-submit").show();
      allow_submit = false;
    }


    var planYear = planMonth.substr(0, 4),
    nxtYear = new Date().getFullYear() + 1; 
    if (planYear > nxtYear) {
      $("#planMonth .failYear").show();
      allow_submit = false;
    }else{
      $("#planMonth .failYear").hide();
      allow_submit = true;
    }

    // return false;
    return allow_submit;
  });

    $("#yesPlanYear").click(function(){
      var allow_submit =true,
      planYear = $("#planYear input[name=planYear]").val(),
      takeDpt = $("#planYear input[name=takeDpt]").val();
      if (!planYear || !takeDpt) {
        $("#planYear .failNull").show();
        allow_submit = false;
      }else{
        $("#planYear .failNull").hide();
        allow_submit = true;
      }


      var nxtYear = new Date().getFullYear() + 1; 
      if (planYear > nxtYear) {
        $("#planYear .failYear").show();
        allow_submit = false;
      }else{
        $("#planYear .failYear").hide();
        allow_submit = true;
      }

      // return false;
      return allow_submit;
    });

  var zTree = <?= $dptService->getDptForRole("1,2,3") ?>;
  function planMonth(){
    setting.callback.onClick = clickPlanMonth;
    $.fn.zTree.init($("#treePlanMonth"), setting, zTree);
    $("#planMonth input[type=radio]").attr('checked', 'checked');
    $("#planMonth input[name=takeDpt]").val(0);
    $("#planMonth").modal({ keyboard: true });
  }

  function clickPlanMonth(event, treeId, treeNode){
    if (treeNode.id != 0) 
      $("#planMonth input[type=radio]").removeAttr('checked');
    $("#planMonth input[name=takeDpt]").val(treeNode.id);
  }

  function planYear(){
    setting.callback.onClick = clickPlanYear;
    $.fn.zTree.init($("#treePlanYear"), setting, zTree);
    $("#planYear input[type=radio]").attr('checked', 'checked');
    $("#planYear input[name=takeDpt]").val(0);
    $("#planYear").modal({ keyboard: true });
  }

  function clickPlanYear(event, treeId, treeNode){
    if (treeNode.id != 0) 
      $("#planYear input[type=radio]").removeAttr('checked');
    $("#planYear input[name=takeDpt]").val(treeNode.id);
  }

  function downXls(filename){
    location.href = "./controller/checkProcess.php?flag=downXls&filename="+filename;
  }

  var setting = {
      view: {
          selectedMulti: false,
          showIcon: false
      },
      data: {
          simpleData: {
              enable: true
          }
      },
      callback: {
        onClick: null
      }
  };

  function finishMonth(){
    setting.callback.onClick = clickMonth;
    $.fn.zTree.init($("#tree"), setting, zTree);
    $("#checkFinishedMonth input[name=takeDpt]").val();
    $("#checkFinishedMonth").modal({ keyboard: true });
  }

  function clickMonth(event, treeId, treeNode){
    $("#checkFinishedMonth input[name=takeDpt]").val(treeNode.id);
    $.post("./controller/checkProcess.php", $("#formMonth").serialize(),function(data){
      $.each(data, function(k, val){
        $("#checkFinishedMonth td[res="+k+"]").text(val);
      })
    },'json');
  }

  function finishBefore(){
    setting.callback.onClick = clickBefore;
    $.fn.zTree.init($("#treeBefore"), setting, zTree);
    $("#checkFinishedBefore input[type=radio]").attr('checked', 'checked');
    $("#checkFinishedBefore input[name=takeDpt]").val(0);
    $("#checkFinishedBefore").modal({ keyboard: true });
  }

  function clickBefore(event, treeId, treeNode){
    if (treeNode.id != 0) 
      $("#checkFinishedBefore input[type=radio]").removeAttr('checked');
    $("#checkFinishedBefore input[name=takeDpt]").val(treeNode.id);
  }

  $("#yesBefore").click(function(){
    var allow_submit = true,
    before = $("#checkFinishedBefore input[name=before]").val(),
    takeDpt = $("#checkFinishedBefore input[name=takeDpt]").val();

    if (before == "" || takeDpt =="") {
      $("#failBefore").show();
      allow_submit = false;
    }
    return allow_submit;
  });

  $("#checkFinishedBefore .datetime, #planYear input[name=planYear]").datetimepicker({
    format: 'yyyy', language: "zh-CN", autoclose: true, minView: 4, startView: 4
  });

  $("#planMonth input[name=planMonth]").datetimepicker({
    format: 'yyyy-mm', language: "zh-CN", autoclose: true, minView: 3, startView: 3
  });

</script>
</html>