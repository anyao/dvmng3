<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];
$sqlHelper = new sqlHelper;
$checkService = new checkService($sqlHelper);
$dptService = new dptService($sqlHelper);
$devService = new devService($sqlHelper);

$id = $_GET['id'];
$res = $devService->getDevById($id);
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
<title>设备具体信息-设备管理系统</title>
<style type="text/css">
  #uptInfo{
    width:100%;
    display: <?=in_array(1, $_SESSION['funcid']) || $user == 'admin' ? 'inline' : 'none' ?>;
  }

  #dptModal .ztree-row,#statusLogModal tbody{
    overflow-y: scroll;
  }

  #chgStatus,#downClass{
    display: none;
  }

</style>
<?php include "./buyVendor.php"; ?>
</head>
<body role="document">
<?php include "message.php"; ?>
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
        <li class="active"><a href="usingList.php">设备台账</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">日常巡检 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="inspStd.php">巡检标准</a></li>
            <li><a href="inspMis.php">巡检计划</a></li>
            <li><a href="inspList.php">巡检记录</a></li>
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
<form action="controller/devProcess.php" method="post" id="infoForm">
<div class="row">
  <div class="col-md-2">
    <div class="printview">
      <h5>运行状态</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-modal-window"></span></div>
      <div class="state col-md-6"><?= $res['status'] ?></div>
      </div>
    </div>
    <div class="printview" style="margin-left: 20px">
      <?php $dur = $devService->getDuration($res['valid']); ?>
      <h5>下次检修 / <?=$dur[1]?></h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-wrench"></span></div>
      <div class="state col-md-6"><?=$dur[0]?></div>
      </div>
    </div>
  </div>
  <div class="col-md-9 detail">
    <div class="row">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-addon">设备名称</span>
          <input type="text" class="form-control" name="name" value="<?=$res['name']?>" readonly>
        </div> 
        <div class="input-group">
          <span class="input-group-addon">规格型号</span>
          <input type="text" class="form-control" name="spec" value="<?=$res['spec']?>" readonly>  
        </div> 
        <div class="input-group">
          <span class="input-group-addon">精度等级</span>
          <input type="text" class="form-control" name="accuracy" value="<?=$res['accuracy']?>" readonly>
        </div>
        <div class="input-group">
          <span class="input-group-addon">量　　程</span>
          <input type="text" class="form-control" name="scale" value="<?=$res['scale']?>" readonly>
        </div>
        <div class="input-group">
          <span class="input-group-addon">出厂编号</span>
          <input type="text" class="form-control" name="codeManu" value="<?=$res['codeManu']?>" readonly>
        </div> 
      </div>
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-addon">制造厂商</span>
          <input type="text" class="form-control" name="supplier" value="<?=$res['supplier']?>" readonly>
        </div>
        <div class="input-group">
          <span class="input-group-addon">安装地点</span>
          <input type="text" class="form-control" name="loc" value="<?=$res['loc']?>" readonly>
        </div>
        <div class="input-group">
          <span class="input-group-addon">检定周期</span>
                <input type="text" class="form-control" name="circle" value="<?=$res['circle']?>" readonly>
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default" type="button">月</button>
                </span>
        </div>
        <div class="input-group">
          <span class="input-group-addon">有效日期</span>
          <input type="text" class="form-control" name="valid" value="<?=$res['valid']?>" readonly>
        </div> 
        <div class="input-group">
          <span class="input-group-addon">使用单位</span>
          <input type="text" class="form-control" name="take" value="<?=$res['take']?>" readonly>
          <input type="hidden" name="takeDpt" value="<?=$res['takeDpt']?>">
        </div> 
      </div>
    </div>    
  </div>
</div>
<div class="row">
  <div class="col-md-7 detail" style="margin-left: 20px;height:500px;width:64%">
    <table class="table table-striped table-hover">
      <thead><tr>
        <th>检定类型</th><th>有效日期</th><th>实际完成</th><th>溯源方式</th>
        <th>结果</th><th>备注</th><th style="width:5%"></th>
      </tr></thead>
      <tbody style="overflow-y: scroll">
      <?php
        $check = $checkService->getCheckByDev($_GET['id']); 
        for ($i=0; $i < count($check); $i++) {  
          switch ($check[$i]['res']) {
            case 1:
              $check[$i]['res'] = '合格';
              break;
            case 2:
              $check[$i]['res'] = '维修';
              $check[$i]['info'] = "状态:".$check[$i]['status']." 注:".$check[$i]['info'];
              break;
            case 3:
              $check[$i]['res'] = '调整';
              $check[$i]['info'] = "等级:".$check[$i]['downClass']." 注:".$check[$i]['info'];
              break;
          } 
          
          if ($check[$i]['info'] == "") 
            $check[$i]['info'] = '无'; 
          $confirm = $check[$i]['count']==0 ? "<td><span class='glyphicon glyphicon-thumbs-up'></span></td>" : "<td></td>";
              
          echo 
          "<tr><td>{$check[$i]['type']}</td>
              <td>{$check[$i]['valid']}</td>
              <td>{$check[$i]['checkTime']}</td>
              <td>{$check[$i]['track']}</td>
              <td>{$check[$i]['res']}</td>
              <td>{$check[$i]['info']}</td>
              {$confirm}
          </tr>";
        }
        ?>
      </tbody>
    </table> 
  </div>
  <div class="col-md-4 detail" style="margin-left:25px;height:500px;width:28.9%">
    <div class="row">
      <div class="input-group">
        <span class="input-group-addon">使用时间</span>
        <input type="text" class="form-control datetime" name="useTime" value="<?=$res['useTime']?>" readonly>  
      </div>  
      <div class="input-group">
        <span class="input-group-addon">停用时间</span>
        <input type="text" class="form-control datetime" name="stopTime" value="<?=$res['stopTime']?>" readonly>
      </div>
      <div class="input-group">
        <span class="input-group-addon">检定单位</span>
        <select class="form-control" name="checkDpt" disabled>
          <option value="199" <?=$res['checkDpt']==199 ? 'selected' : ''?>>计量室</option>
          <option value="isTake" <?=$res['checkDpt']=='isTake' ? 'selected' : null?>>使用部门</option>
          <option value="isOut" <?=$res['checkDpt']=='isOut' ? 'selected' : null?>>外检单位</option>
        </select>
      </div>
      <div class="input-group">
        <span class="input-group-addon">外检公司</span>
        <input type="text" class="form-control" name="checkComp" value="<?=$res['checkComp'] ?>" readonly>
      </div>
      <div class="input-group">
        <span class="input-group-addon">单　　位</span>
        <input type="text" class="form-control" name="unit" value="<?=$res['unit'] ?>" readonly>
      </div>
      <div class="input-group">
        <span class="input-group-addon">测量装置</span>
        <input type="text" class="form-control" name="equip" value="<?=$res['equip'] ?>" readonly>
      </div>
      <div class="input-group">
        <span class="input-group-addon">用　　途</span>
        <select class="form-control" name="usage" disabled>
          <option value="质检" <?=$res['usage']=='质检' ? 'selected' : null?>>质检</option>
          <option value="经营" <?=$res['usage']=='经营' ? 'selected' : null?>>经营</option>
          <option value="控制" <?=$res['usage']=='控制' ? 'selected' : null?>>控制</option>
          <option value="安全" <?=$res['usage']=='安全' ? 'selected' : null?>>安全</option>
          <option value="环保" <?=$res['usage']=='环保' ? 'selected' : null?>>环保</option>
          <option value="能源" <?=$res['usage']=='能源' ? 'selected' : null?>>能源</option>
        </select>
      </div>
      <div class="input-group">
        <span class="input-group-addon">管理类别</span>
        <select class="form-control" name="class" disabled>
          <option value="A" <?=$res['class']=='A'? 'selected' : ''?>>A</option>
          <option value="B" <?=$res['class']=='B' ? 'selected' : ''?>>B</option>
          <option value="C" <?=$res['class']=='C' ? 'selected' : ''?>>C</option>
        </select>
      </div>
      <div class="input-group">
        <a class="input-group-addon" href="javascript:getStatusLog()" style="text-decoration: none">运行现状</a>
        <select class="form-control" name="status" disabled>
          <?php  
            $status = $devService->getStatus();
            for ($i=0; $i < count($status); $i++) { 
              $selected = $res['statusid'] == $status[$i]['id'] ? 'selected' : null;
              echo "<option value='{$status[$i]['id']}' $selected >{$status[$i]['status']}</option>";
            }
          ?>
        </select>
        <input type="hidden" name="ostatus" value="<?=$res['statusid']?>">
      </div>
      <input type="hidden" name="flag" value="uptDev">
      <input type="hidden" name="id" value="<?=$_GET['id']?>">
      <button class="btn btn-default" id="uptInfo" info="read">
        <span class="glyphicon glyphicon-pencil"></span>　修改设备信息
      </button>
     <!--  <button class="btn btn-default" id="addCheck" style="width:100%;margin-top: 10px" type="button">
        <span class="glyphicon glyphicon-plus" id="addCheck"></span>　添加检定记录
      </button> -->
    </div>
  </div>
</div>
</form>

<!-- 使用部门选择 -->
<div class="modal fade" id="dptModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">部门选择</h4>
      </div>
      <div class="modal-body">
        <div class="row ztree-row">
          <div class="col-md-4">
            <ul id="tree-py" class="ztree"></ul>
          </div>
          <div class="col-md-4">
            <ul id="tree-zp" class="ztree"></ul>
          </div>
          <div class="col-md-4">
            <ul id="tree-gp" class="ztree"></ul>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <span style="color: red;display:none" id="failRadio">部门选择必须唯一。</span>
        <button type="button" class="btn btn-primary" id="yesDpt">确定</button>
      </div>
    </div>
  </div>
</div>
<!-- 状态改变日志 -->
<div class="modal fade" id="statusLogModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">状态历史</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-hover">
          <thead><tr><th>修改时间</th><th>运行状态</th><th>修改人</th></tr></thead>
          <tbody class="tablebody">  
          </tbody>
        </table>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function getStatusLog(){
  $.post('./controller/devProcess.php', {
    flag: 'getStatusLog', 
    id: <?= $_GET['id']?>
  }, function(data) {
    var $addHtml = "";
    if (data.length == 0) 
      $addHtml += "<tr><td colspan=12>暂无记录。</td></tr>";
    else
      for (var i = 0; i < data.length; i++) 
        $addHtml += "<tr>"+
                    "  <td>" + data[i].created_at + "</td>"+
                    "  <td>" + data[i].status + "</td>"+
                    "  <td>" + data[i].user + "</td>"+
                    "</tr>";
    $("#statusLogModal tbody").empty().append($addHtml);
    $("#statusLogModal .modal-body").height(0.4 * $(window).height());
    $('#statusLogModal').modal({
      keyboard: true
    });
  },'json');
}

// 可修改时需要加载的附件
function vendorUpt(){
  $("#infoForm input[name!=hidden], #infoForm select[name=checkDpt]").not(".datetime,input[name=take],input[name=checkComp],input[name=valid]").removeAttr('readonly');
  $("#infoForm select").removeAttr('disabled');
  if ($("#infoForm select[name=checkDpt]").val() == 'isOut') 
    $("#infoForm input[name=checkComp]").removeAttr('readonly');

  // 检定周期加
  $("#infoForm .glyphicon-plus").parents("button").click(function(){
    var $circle = $(this).parents(".input-group").find("input[type=text]");
    var num = parseInt($circle.val());
    num++;
    $circle.val(num);
  });

  // 检定周期减
  $("#infoForm .glyphicon-minus").parents("button").click(function(){
    var $circle = $(this).parents(".input-group").find("input[type=text]");
    var num = parseInt($circle.val());
    if (num != 1) {
      num--;
      $circle.val(num);
    }
  });

  //时间选择器
  $("#infoForm .datetime").datetimepicker({
    format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
  });

  // 使用单位
  $("#infoForm input[name=take]").click(function(){
    readyTree();
  });

  // 外检input框显示
  $("#infoForm select[name=checkDpt]").click(function(){
    if ($(this).val() == "isOut") 
      $("#infoForm input[name=checkComp]").removeAttr('readonly');
    else{
      $("#infoForm input[name=checkComp]").attr("readonly","readonly");
      $("#infoForm input[name=checkComp]").val("");
    }
  });
}

$("#uptInfo").click(function(){
  var allow_submit = true;
  switch ($(this).attr("info")){
    case 'read':
      $(this).attr("info", 'upt');
      $(this).html('<span class="glyphicon glyphicon-ok"></span>　确定修改');
      vendorUpt();
      allow_submit = false;
      break;
    case 'upt':
      allow_submit = true;
      break;
  }
  return allow_submit;
});

// 树形部门结构基础配置
var settingModal = {
    view: {
        selectedMulti: false,
        showIcon: false
    },
    check: {
        enable: true,
        chkStyle:"radio",
        radioType:'all',
    },
    data: {
        simpleData: {
            enable: true
        }
    }
};

dptTree = {
  py: <?= $dptService->getDptForRole(1) ?>, 
  zp: <?= $dptService->getDptForRole(2) ?>, 
  gp: <?= $dptService->getDptForRole(3) ?>
};

function readyTree(){
  $.fn.zTree.init($("#tree-py"), settingModal, dptTree.py);
  $.fn.zTree.init($("#tree-zp"), settingModal, dptTree.zp);
  $.fn.zTree.init($("#tree-gp"), settingModal, dptTree.gp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");
  $("#dptModal .ztree-row").height(0.4 * $(window).height());

  $('#dptModal').modal({
    keyboard: true
  });  
}

$("#yesDpt").click(function(){
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) 
    $("#failRadio").show();
  else{
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
    var pnode = nodes[0].getParentNode();
    $("input[name=takeDpt]").val(nodes[0].id);
    $("input[name=take]").val(pnode.name+nodes[0].name);
    $("#failRadio").hide();
    $('#dptModal').modal('hide');  
  }
});

//弹出框
$(function(){
 $("[data-toggle='popover']").popover(); 
});
</script>
</body>
</html>