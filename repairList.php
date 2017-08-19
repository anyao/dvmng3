<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$dptService = new dptService($sqlHelper);
$repairService = new repairService($sqlHelper);

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;

$paging->gotoUrl="repairList.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

if (empty($_REQUEST['flag'])) 
  $repairService->getRepPaging($paging);
else{
  $arr = $_POST['data'];
  $repairService->findRepPaging($arr, $paging);
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
  <link rel="icon" href="bootstrap/img/favicon.ico">
  <title>维修记录-设备管理系统</title>
  <style type="text/css">
    .glyphicon-check, .glyphicon-unchecked,.glyphicon-thumbs-up,.glyphicon-option-horizontal{
      display:inline !important;
    }

    #repList{
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
      <a class="navbar-brand" href="usingList.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
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
        <li class="dropdown active">
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
  <div class="row">
    <div class="col-md-12">
      <div class="page-header">
          <h4>　维修记录
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th><span class="glyphicon glyphicon-download-alt" id="repList"></span></th>
          <th>设备名称</th><th>出厂编号</th><th>安装地点</th><th>维护日期</th>
          <th>设备情况</th><th>维护调整情况</th><th>外观腐蚀情况</th>
          <th style="width:3%"></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>未找到相关记录。</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i]; 
            echo "<tr>
                  <td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>
                  <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
                  <td>{$row['codeManu']}</td>
                  <td>{$row['factory']}{$row['loc']}</td>
                  <td>{$row['time']}</td>
                  <td>{$row['device']}</td>
                  <td>{$row['repair']}</td>
                  <td>{$row['surface']}</td>
                  <td><a class='glyphicon glyphicon-trash' href='javascript:del({$row['id']}, {$row['devid']});'></a></td>
                </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
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
          <input type="hidden" name="flag" value="findRepair">
          <span style="color: red;display:none" id="failSearch">搜索条件至少填写一个。</span>
          <button class="btn btn-primary" id="yesFind">确认</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="delModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">删除</h4>
      </div>
      <form class="form-horizontal" action="./controller/repairProcess.php" method="post">
        <div class="modal-body">
          <br>确定要删除该条记录？<br><br>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="delRepair">
          <input type="hidden" name="id">
          <input type="hidden" name="devid">
          <button class="btn btn-primary">确定</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'devJs.php';?>
<script type="text/javascript">
function del(id, devid){
  $("#delModal input[name=id]").val(id);
  $("#delModal input[name=devid]").val(devid);
  $("#delModal").modal({
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

$("#repList").click(function(){
  var str = takeAll();
  location.href = "./controller/repairProcess.php?flag=xlsRep&id="+str;
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
      $("#repList").show();
    }else{
      $("#repList").hide();
    }
});
   </script>
  </body>
</html>