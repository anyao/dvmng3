<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$safeCheckService = new safeCheckService($sqlHelper);
$dptService = new dptService($sqlHelper);
$checkService = new checkService($sqlHelper);

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;

$paging->gotoUrl="safeCheck.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}


if (empty($_REQUEST['flag'])) {
  $safeCheckService->getMisPaging($paging);
}else{
  $data = !empty($_GET['para']) ? $_GET['para']['data'] : $_POST['data'];
  $paging->para = ['para' => ['data' => $data], 'flag' => 'findPlan'];
  $safeCheckService->findMisPaging($paging);
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
<title>周检计划-设备管理系统</title>
<style type="text/css">
  .glyphicon-check, 
  .glyphicon-unchecked,
  .glyphicon-time,
  .glyphicon-home,
  .glyphicon-send
  {
    display:inline !important;
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

  a.glyphicon-eye-open{
    display:inline !important;
  }
  
  span.glyphicon-ok{
    display:none;
    cursor: pointer;
  }

  #noModal .control-label{
    padding-left: 0px
  }

  #searchForm .ztree-row{
    overflow-y: scroll
  }

  .authCheck{
    width:4%;
    display: <?=(!in_array(7, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "table-cell"?>
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
        <li><a href="safeDev.php">设备台账</a></li>
        <li class="dropdown active">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检修计划 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="safeCheck.php">检修计划</a></li>
            <li><a href="safeCheckList.php">检修记录</a></li>
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
          <h4>　检修计划
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th>出厂编号</th><th>设备名称</th><th>所在分厂部门</th>
          <th>安装地点</th><th>有效日期</th><th>检修周期</th>
          <th class="authCheck"></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>未找到相关设备。</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i];
            echo "<tr>
	                <td>{$row['codeManu']}</td>
	                <td>{$row['name']}</td>
	                <td>{$row['factory']}{$row['depart']}</td>
	                <td>{$row['loc']}</td>
                  <td>{$row['valid']}</td>
                  <td>{$row['circle']}个月</td>
                  <td class='authCheck'><a class='glyphicon glyphicon-eye-open' href='javascript:checkOne({$row['id']},\"{$row['codeManu']}\");'></a></td>
	              </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
    </div>
  </div>
</div>

<div class="modal fade" id="checkOneModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">检定</h4>
      </div>
      <form class="form-horizontal" action="./controller/safeCheckProcess.php" method="post">
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
              <input type="text" class="form-control datetime chktime" name="chk[time]" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">检定类型：</label>
            <div class="col-sm-8">
              <select class="form-control" name="chk[type]" id="chkType">
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
              <select class="form-control" name="chk[res]" id="chkres">
                <option value="1">合格</option>
          <!--  <option value="2">维修</option>
                <option value="3">降级</option>
                <option value="4">封存</option> -->
              </select>
            </div>
          </div>
           <div class="form-group">
            <label class="col-sm-3 control-label">检修备注：</label>
            <div class="col-sm-8">
              <textarea class="form-control chkinfo" rows="4" name="chk[info]"></textarea>
            </div>
          </div> 
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addCheck">
          <input type="hidden" class="devid" name="chk[devid]">
          <span style="color:red; display:none" id="failCheckOne">信息填写不完整。</span>
          <button class="btn btn-primary" id="yesCheckOne">确定</button>
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
              <label class="col-sm-3 control-label">备件名称：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control name" name="data[name]">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">出厂编号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control codeManu" name="data[codeManu]">
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
          <input type="hidden" name="flag" value="findPlan">
          <span style="color: red;display:none" id="failSearch">搜索条件至少填写一个。</span>
          <button class="btn btn-primary" id="yesFind">确认</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'devJs.php';?>
<script type="text/javascript">
$("#yesFind").click(function(){
  var allow_submit = true,
  allow_dpt = true,
  allow_add = true;
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  var dpt = "";
  $.each(nodes, function(i, n) {
    dpt += n.id+",";
  });

  if(dpt == ""){
    allow_dpt = false;
  }

  var name = $("#searchForm input.name").val();
  var codeManu = $("#searchForm input.codeManu").val();
  if (!name && !codeManu) {
    allow_add = false;
  }

  allow_submit = allow_add || allow_dpt;
  if(!allow_submit){
    $("#failSearch").show();
  }

  $("#dpt").val(dpt);
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

function checkOne(devid,code){
  $("#checkOneModal .devid").val(devid);
  $("#checkOneModal input[name=codeManu]").val(code);

  $('#checkOneModal').modal({
    keyboard: true
  });
}

$("#yesCheckOne").click(function(){
  var allow_submit = true;
  var time = $("#checkOneModal .chktime").val();
  var info = $("#checkOneModal .chkinfo").val();
  if (!time && !info) {
    $("#failCheckOne").show();
    allow_submit = false;
  }
  return allow_submit;
});


// 搜索
$(".glyphicon-search").click(function(){
  $("#failAdd, #failSearch").hide();
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
      $("#yesCheck").show();
    }else{
      $("#yesCheck").hide();
    }
});
   </script>
  </body>
</html>