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
<link rel="icon" href="bootstrap/img/favicon.ico">
<title>设备具体信息-设备管理系统</title>
<style type="text/css">
  #uptInfo{
    width:100%;
    display: <?=in_array(1, $_SESSION['funcid']) || $user == 'admin' ? 'inline' : 'none' ?>;
  }

  #dptModal .ztree-row,#statusLogModal tbody{
    overflow-y: scroll;
  }

  #chgStatus,#downAccu{
    display: none;
  }
  
  .glyphicon-thumbs-up,.glyphicon-option-horizontal, .glyphicon-thumbs-down{
      display:inline !important;
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
      <a class="navbar-brand" href="usingList.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li class="active"><a href="usingList.php">设备台账</a></li>
        <li class="dropdown">
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
            <li><a href="./controller/userProcess.php?flag=downInstr">说明书</a></li>
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
        <th>证书结论</th><th>计量要求</th><th style="text-align:center">验证结果</th>
      </tr></thead>
      <tbody style="overflow-y: scroll">
      <?php
        $check = $checkService->getCheckByDev($_GET['id']); 
        for ($i=0; $i < count($check); $i++) {
          if (!empty($check[$i]['chkRes'])) {
            $request = "{$check[$i]['scale']} / {$check[$i]['error']} / {$check[$i]['interval']}";
            $confirm = "{$check[$i]['chkRes']}";
          }elseif (in_array($check[$i]['res'], [1,5,6])) {
           $request = "未计量确认";
           $confirm = "<a class='glyphicon glyphicon-thumbs-up' href='javascript:addConfirm({$check[$i]['id']},\"{$res['codeManu']}\");'></a>";
          }else{
            $request = "检定不合格";
            if (empty($check[$i]['when'])) 
              $confirm = "<a class='glyphicon glyphicon-thumbs-down' href='javascript:failCheck({$check[$i]['id']},\"{$res['codeManu']}\");'></a>";
            else
              $confirm = "<a class='glyphicon glyphicon-option-horizontal' href='./controller/confirmProcess.php?flag=xlsUnqual&chkid={$check[$i]['id']}'></a>";
          }  
          switch ($check[$i]['res']) {
            case 1:
              $check[$i]['res'] = '合格'; break;
            case 2:
              $check[$i]['res'] = '维修'; break;
            case 3:
              $check[$i]['res'] = '降级'; break;
            case 4:
              $check[$i]['res'] = '封存'; break;
            default:
              $check[$i]['res'] = $res['conclu']; break;        
          }
          
          if ($check[$i]['info'] == "") 
            $check[$i]['info'] = '无'; 
              
          echo 
          "<tr><td>{$check[$i]['type']}</td>
              <td>{$check[$i]['valid']}</td>
              <td>{$check[$i]['checkTime']}</td>
              <td>{$check[$i]['track']}</td>
              <td>{$check[$i]['res']}</td>
              <td>{$request}</td>
              <td style='text-align:center'>{$confirm}</td>
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

<div class="modal fade" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">计量确认</h4>
      </div>
      <form class="form-horizontal" action="./controller/confirmProcess.php" method="post">
        <div class="modal-body"> 
          <div class="form-group">
            <label class="col-sm-3 control-label">出厂编号：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="codeManu" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">检定日期：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control datetime" name="cfr[time]" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">测量范围：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="cfr[scale]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">允许误差：</label>
            <div class="col-sm-8">
              <div class="input-group">
                <input type="text" class="form-control" name="cfr[error]">
                <span class="input-group-addon">级</span>
              </div> 
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">分度值：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="cfr[interval]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">验证结果：</label>
            <div class="col-sm-8">
              <select class="form-control" name="cfr[chkRes]">
                <option value="合格">合格</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addConfirm">
          <input type="hidden" name="cfr[chkid]" class="chkid">
          <input type="hidden" name="goto" value="checkList">
          <span style="color:red; display:none" id="failAdd">信息不完整。</span>
          <button class="btn btn-primary" id="yesAdd">确定</button>
        </div>
      </form>
    </div>
  </div>
</div>

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

<!-- 不合格设备处置记录填写 -->
<div class="modal fade" id="noModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">不合格记录</h4>
      </div>
      <form class="form-horizontal" action="./controller/checkProcess.php" method="post">
        <div class="modal-body"> 
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label">出厂编号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="codeManu" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">发现场所：</label>
              <div class="col-sm-8">
                 <select class="form-control" name="chk[when]">
                  <option value="检定校准">检定校准</option>
                  <option value="使用中">使用中</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">不合格原因：</label>
              <div class="col-sm-8">
                <div class="checkbox">
                  <label class="checkbox-inline">
                    <input type="checkbox" name="chk[reason1]" value="1">
                    损坏
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" name="chk[reason2]" value="1">
                      过载
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" name="chk[reason6]" value="1">
                      误操作
                  </label>
                  <label class="checkbox-inline">
                    <input type="checkbox" name="chk[reason9]" value="1">
                      其它
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="chk[reason3]" value="1">
                      可能使其预期用途无效的故障
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="chk[reason4]" value="1">
                      产生不正确的测量结果
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="chk[reason5]" value="1">
                      超过规定的计量确认间隔
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="chk[reason7]" value="1">
                      封印或保护装置损坏或破裂
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="chk[reason8]" value="1">
                      暴露在已有可能影响其预期用途的影响量中(如电磁场、灰尘)
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">处理结果：</label>
              <div class="col-sm-8">
                <textarea class="form-control" rows="4" name="chk[info]"></textarea>
              </div>
            </div> 
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="noCheck">
          <input type="hidden" name="chkid">
          <span style="color:red; display:none" id="failNoCheck">信息不完整。</span>
          <button class="btn btn-primary" id="yesNoCheck">确定</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
function failCheck(id, code){
  $("#noModal input[name=chkid]").val(id);
  $("#noModal input[name=codeManu]").val(code);
  $("#failNoCheck").hide();
  $('#noModal').modal({
    keyboard: true
  });
}

$("#yesAdd").click(function(){
  var allow_submit = true;
  $("#addModal input[type=text]").each(function(){
    if ($(this).val() == "") {
      allow_submit = false;
      $("#failAdd").show();
    }
  })
  return allow_submit;
});

function addConfirm(id, code){
  $("#addModal .chkid").val(id);
  $("#addModal input[name=codeManu]").val(code);
  $('#addModal').modal({
    keyboard: true
  });
}

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