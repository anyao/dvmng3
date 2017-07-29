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
$paging->pageSize=40;

$paging->gotoUrl="usingList.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}


if (empty($_REQUEST['flag'])) {
  $devService->getPaging($paging);
}else{
    $data = $_POST['data'];

    $devService->findDev($data, $paging); 
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
<title>在用设备-设备管理系统</title>
<style type="text/css">
  .col-md-2, #addForm .ztree-row{
    overflow-y: scroll
  }

  .glyphicon-check, .glyphicon-unchecked, .glyphicon-resize-small, .glyphicon-resize-full{
    display:inline !important;
  }

  #takeAll{
    padding-right: 0px;
    width:5%;
  }
  #takeAll > span{
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

  .glyphicon-plus{
    cursor: pointer;
  }

  #addForm .row{
    padding-left: 10px;
    padding-right: 10px;
    border-bottom: 1px solid #CCCCCC;
  }

  #addForm .input-group{
    margin: 10px 0px;
  }

  div[comp=outComp]{
    display: none;
  }

  input[name=catename]{
    width: 101% !important;
    border-top-left-radius:0px !important;
    border-bottom-left-radius:0px !important;
  }

  .glyphicon-search{
    cursor:pointer;
  }

  .del-auth{
    display: <?=in_array(1, $_SESSION['funcid']) || $user == 'admin' ? 'inline-cell' : 'none' ?>;
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
        <li class="active"><a href="usingList.php">设备台账</a></li>
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
  <div class="row">
    <div class="col-md-10">
      <div class="page-header">
          <h4>　所有在用设备
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th id="takeAll">
            <span class="glyphicon glyphicon-download-alt"></span> 
          </th>
          <th>出厂编号</th><th>设备名称</th><th>规格型号</th><th>单位</th>
          <th>所在分厂部门</th><th>状态</th><th>安装地点</th>
          <th style="width:4%"><a class="glyphicon glyphicon-plus" href="javascript: addDev('root');"></a></th>
          <th style="width:4%" class="del-auth"></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          if (count($paging->res_array) == 0) {
              echo "<tr><td colspan=12>未找到相关设备。</td></tr>";
            }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i]; 
            if ($row['unit'] == "套") {
              $status = "<td></td>";
              $leaf = "<td><a href='javascript:void(0);' onclick=\"getLeaf(this, {$row['id']});\" class='glyphicon glyphicon-resize-small'></a></td>";
              $addLeaf = "<td><a class='glyphicon glyphicon-plus' href=\"javascript:addDev({$row['id']})\"></a></td>";
            }else{
              $status = "<td>{$row['status']}</td>";
              $leaf = "<td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>";
              $addLeaf = "<td></td>";
            }
            echo "<tr>
              {$leaf}
              <td>{$row['codeManu']}</td>
              <td><a href='using.php?id={$row['id']}'>{$row['name']}</a></td>
              <td>{$row['spec']}</td>
              <td>{$row['unit']}</td>
              <td>{$row['factory']}{$row['depart']}</td>
              {$status}
              <td>{$row['loc']}</td>
              {$addLeaf}
              <td><a class='glyphicon glyphicon-trash del-auth' href='javascript:delDev({$row['id']});'></a></td>
            </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
    </div>
      <div class="col-md-2">
        <div class="row ztree-row">
            <ul id="tree" class="ztree"></ul>
        </div>
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
              <label class="col-sm-3 control-label">规格型号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="data[spec]">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">出厂编号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="data[codeManu]">
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

<div class="modal fade" id="delModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">删除</h4>
      </div>
      <form class="form-horizontal" action="./controller/devProcess.php" method="post">
        <div class="modal-body">
          <br>确定要删除？<br>(若成套设备，子设备也会相应删除)<br><br>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="delDev">
          <input type="hidden" name="id">
          <button class="btn btn-primary">确认</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 设备添加 -->
<div class="modal fade" id="addForm">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="controller/devProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">新设备</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">管理类别</span>
                <select class="form-control" name="class">
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-addon">备件名称</span>
                <input class="form-control" name="name" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">规格型号</span>
                <input class="form-control" name="spec" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">精度等级</span>
                <input class="form-control" name="accuracy" type="text">
                <span class="input-group-addon">级</span>
              </div>
              <div class="input-group">
                <span class="input-group-addon">量　　程</span>
                <input class="form-control" name="scale" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="codeManu" type="text">
              </div>  
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">制造厂商</span>
                <input class="form-control" name="supplier" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">安装地点</span>
                <input class="form-control" name="loc" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">运行状态</span>
                <select class="form-control" name="status">
                  <?php  
                      $status = $devService->getStatus();
                      for ($i=0; $i < count($status); $i++) { 
                        echo "<option value='{$status[$i]['id']}'>{$status[$i]['status']}</option>";
                      }
                    ?>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-addon">启用日期</span>
                <input class="form-control datetime" name="useTime" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">新增日期</span>
                <input class="form-control datetime" name="takeTime" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">测量装置</span>
                <input class="form-control" name="equip" type="text">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">用　　途</span>
                <select class="form-control" name="usage">
                  <option value="质检">质检</option>
                  <option value="经营">经营</option>
                  <option value="控制">控制</option>
                  <option value="安全">安全</option>
                  <option value="环保">环保</option>
                  <option value="能源">能源</option>
                </select>
              </div>
              <div class="input-group">
                <span class="input-group-addon">检定周期</span>
                <input class="form-control" name="circle" value="6" readonly="readonly" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default" type="button">月</button>
                </span>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">单　　位</span>
                <input class="form-control" name="unit" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">分　　类</span>
                <input type="text" class="form-control" name="catename">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                   <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                  </ul>
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-addon">有效日期</span>
                <input class="form-control datetime" name="valid" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">检定单位</span>
                <select class="form-control" name="checkDpt" dpt="checkDpt">
                  <option value="199">计量室</option>
                  <option value="isTake">使用部门</option>
                  <option value="isOut">外检单位</option>
                </select>
              </div>
              <div class="input-group" comp="outComp">
                <span class="input-group-addon">外检公司</span>
                <input class="form-control" name="checkComp" type="text">
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
          <input type="hidden" name="takeDpt">
          <input type="hidden" name="pid">
          <input type="hidden" name="category">
          <input type="hidden" name="flag" value="addDev">
          <span style="display:none;color:red" id="failRadio">领取部门必须选择唯一。</span>
          <button class="btn btn-primary" id="yesAdd">确定</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<?php include 'devJs.php';?>
<script type="text/javascript">
$("#yesPass").click(function(){
  var allow_submit = true;
  if ($("#passCheck input[name=time]").val() == "") {
    allow_submit = false;
    $("#failPass").show();
  }
  return allow_submit;
});

$("#takeAll .glyphicon-download-alt").click(function(){
  var str = takeAll();
  location.href = "./controller/devProcess.php?flag=xlsDev&id="+str;
});

function takeAll(){
  var str = "";
  $(".glyphicon-check").each(function(){
    str += $(this).attr('chosen') + ",";
  });
  return str;
}

function delDev(id){
  $("#delModal input[name=id]").val(id);
  $("#delModal").modal({
    keyboard: true
  });
}

// 搜索
$(".glyphicon-search").click(function(){
  $('#searchForm').modal({
    keyboard: true
  });
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

var setting = {
    view: {
        selectedMulti: false,
        showIcon: false
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

// 外检input框显示
$("#addForm").on('click', 'select[dpt=checkDpt]', function() {
  if ($(this).val() == "isOut") 
    $(this).parents(".input-group").next().css("display", "table");
  else
    $(this).parents(".input-group").next().hide();
});

// 检定周期加
$("#addForm .glyphicon-plus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  num++;
  $circle.val(num);
});

// 检定周期减
$("#addForm .glyphicon-minus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  if (num != 1) {
    num--;
    $circle.val(num);
  }
});

$("#addForm input[name=catename]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:1,
    data: {
         'value':<?= $devService->getCategory() ?>
    }
}).on('onSetSelectValue', function (e, keyword, data) {
   $("#addForm input[name=category]").val($(this).attr("data-id"));
   $(this).parents("form").find("input[name=inspDpt]").val(dptid);
});

// 确认添加
$("#yesAdd").click(function(){
  var allow_submit = true;
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) {
    allow_submit = false;
    $("#failRadio").show();
  }else{
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
    $("#addForm input[name=takeDpt]").val(nodes[0].id);
  }
  return allow_submit;
});

// 添加设备
function addDev(path){
  $.fn.zTree.init($("#tree-py"), settingModal, dptTree.py);
  $.fn.zTree.init($("#tree-zp"), settingModal, dptTree.zp);
  $.fn.zTree.init($("#tree-gp"), settingModal, dptTree.gp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");
  $("#addForm .ztree-row").height(0.4 * $(window).height());

  if (path == 'root') 
    $("#addForm input[name=pid]").val(null);
  else
    $("#addForm input[name=pid]").val(path);

  $('#addForm').modal({
    keyboard: true
  });  
}

$(function(){
  // alert($(document).height());
  $(".col-md-2").height(0.9 * $(window).height());
  $.fn.zTree.init($("#tree"), setting, zTree);
  tree = $.fn.zTree.getZTreeObj("tree");
});

// 多选
$(".tablebody").on("click","span.chosen",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked chosen");
    $(this).toggleClass("glyphicon glyphicon-check chosen");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $("#takeAll span").show();
    }else{
      $("#takeAll span").hide();
    }
});

// 成套设备展开
function getLeaf(obj,id){
    var flagIcon=$(obj).attr("class");
    var $rootTr=$(obj).parents("tr");
    // 列表是否未展开
    if (flagIcon=="glyphicon glyphicon-resize-small") {
      // 展开
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
      $.post("controller/devProcess.php",{
        flag: 'getLeaf',
        id: id
      },function(data){
        var addHtml = "";
        for (var i = 0; i < data.length; i++){
          addHtml += 
            "<tr class='open "+data[i].id+" open-"+id+"' style='border: 1px solid #ddd'>"+
            "  <td><span class='glyphicon glyphicon-unchecked chosen' chosen='"+data[i].id+"'></span></td>"+
            "  <td>"+data[i].codeManu+"</td>"+
            "  <td><a href='using.php?id="+data[i].id+"'>"+data[i].name+"</a></td>"+
            "  <td>"+data[i].spec+"</td>"+
            "  <td>"+data[i].unit+"</td>"+
            "  <td>"+data[i].factory+data[i].depart+"</td>"+
            "  <td>"+data[i].status+"</td>"+
            "  <td>"+data[i].loc+"</td><td></td>"+
            "  <td><a class='glyphicon glyphicon-trash del-auth' href='javascript:delDev("+id+")'></a></td>"+
            "</tr>";
        }
        $rootTr.after(addHtml);
      },'json');
    }else{
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
      $(".open-"+id).detach();
    }
}

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