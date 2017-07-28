<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$devService = new devService($sqlHelper);
$dptService = new dptService($sqlHelper);
$checkService = new checkService($sqlHelper);

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;

$paging->gotoUrl="checkList.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}


if (empty($_REQUEST['flag'])) 
  $checkService->getChkPaging($paging);
else{
  $arr = $_POST['data'];
  $checkService->findCheckPaging($arr, $paging);
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
  <title>检定记录-设备管理系统</title>
  <style type="text/css">
    .glyphicon-check, .glyphicon-unchecked,.glyphicon-thumbs-up,.glyphicon-option-horizontal{
      display:inline !important;
    }

    #confirmList{
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

    a.glyphicon-remove{
      display:inline !important;
    }
    
    span.glyphicon-ok{
      display:none;
      cursor: pointer;
    }

    #noModal .control-label{
      padding-left: 0px
    }

    #downClass{
      display: none;
    }

    #searchForm .ztree-row{
      overflow-y: scroll
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
            <li><a href="checkMis.php">周检计划</a></li>
            <li><a href="checkList.php">检定记录</a></li>
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
          <h4>　检定记录
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th><span class="glyphicon glyphicon-download-alt" id="confirmList"></span></th>
          <th>检定日期</th><th>出厂编号</th><th>设备名称</th><th>所在分厂部门</th><th>安装地点</th>
          <th>结果</th><th>状态</th><th>计量要求</th><th style="text-align:center">验证结果</th>
          <th style="width:3%"></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>未找到相关设备。</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i]; 
            if (!empty($row['chkRes'])) {
              $request = "{$row['scale']} / {$row['error']} / {$row['interval']}";
              $confirm = "<td style='text-align:center'>{$row['chkRes']}</td>";
            }elseif ($row['res'] == 1) {
             $request = "未计量确认";
             $confirm = "<td style='text-align:center'>
                          <a class='glyphicon glyphicon-thumbs-up' href='javascript:addConfirm({$row['id']},\"{$row['codeManu']}\");'></a>
                         </td>";
            }else{
              $request = "检定不合格";
              $confirm = "<td style='text-align:center'>
                            <a class='glyphicon glyphicon-option-horizontal' href='./controller/confirmProcess.php?flag=xlsUnqual&chkid={$row['id']}'></a>
                          </td>";
            }

            switch ($row['res']) {
              case 1:
                $row['res'] = "合格"; break;
              case 2:
                $row['res'] = "维修"; break;
              case 3:
                $row['res'] = "降级"; break;
              case 4:
                $row['res'] = "封存"; break;
            }
            echo "<tr>
	                <td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>
                  <td>{$row['time']}</td>
	                <td>{$row['codeManu']}</td>
                  <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
	                <td>{$row['takeFct']}</td>
	                <td>{$row['loc']}</td>
                  <td>{$row['res']}</td>
                  <td>{$row['status']}</td>
                  <td>$request</td>
                  $confirm
                  <td><a class='glyphicon glyphicon-download-alt' href='javascript:void(0);'></a></td>
	              </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
    </div>
  </div>
</div>

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
          <div class="row">
            <div class="form-group">
              <label class="col-sm-3 control-label">运行状态：</label>
              <div class="col-sm-8">
                <select class="form-control" name="data[status]">
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
                <input type="text" class="form-control" name="data[name]">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">出厂编号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="data[codeManu]">
              </div>
            </div>
          </div>
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
          <input type="hidden" name="data[takeDpt]" id="dpt">
          <input type="hidden" name="flag" value="findCheck">
          <span style="color: red;display:none" id="failSearch">搜索条件至少填写一个。</span>
          <button class="btn btn-primary" id="yesFind">确认</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'devJs.php';?>
<script type="text/javascript">
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

$("#yesFind").click(function(){
  var allow_submit = true;
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  var dpt = "";
  $.each(nodes, function(i, n) {
    dpt += n.id+",";
  });
  $("#dpt").val(dpt);
  if ($.inArray("",$("input").val()) != -1) {
    allow_submit = false;
    $("#failSearch").show();
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
        chkStyle:"checkbox",
        radioType:'all',
    },
    data: {
        simpleData: {
            enable: true
        }
    }
};

var zTree = <?= $dptService->getDptForRole('1,2,3') ?>,
dptTree = {
  py: <?= $dptService->getDptForRole(1) ?>, 
  zp: <?= $dptService->getDptForRole(2) ?>, 
  gp: <?= $dptService->getDptForRole(3) ?>
};

$("#confirmList").click(function(){
  var str = takeAll();
  location.href = "./controller/confirmProcess.php?flag=xlsConfirm&chkid="+str;
});

function takeAll(){
  var str = "";
  $(".glyphicon-check").each(function(){
    str += $(this).attr('chosen') + ",";
  });
  return str;
}

// 搜索
$(".glyphicon-search").click(function(){
  $.fn.zTree.init($("#tree-py"), settingModal, dptTree.py);
  $.fn.zTree.init($("#tree-zp"), settingModal, dptTree.zp);
  $.fn.zTree.init($("#tree-gp"), settingModal, dptTree.gp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");
  $("#searchForm .ztree-row").height(0.3 * $(window).height());
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
      $("#confirmList, #yesCheck").show();
    }else{
      $("#confirmList, #yesCheck").hide();
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