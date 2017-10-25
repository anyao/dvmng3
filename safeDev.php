<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user = $_SESSION['user'];

$sqlHelper = new sqlHelper;
$safeService = new safeService($sqlHelper);
$dptService = new dptService($sqlHelper);

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=40;

$paging->gotoUrl="safedev.php";
if (!empty($_GET['pageNow'])) 
  $paging->pageNow=$_GET['pageNow'];


if (empty($_REQUEST['flag'])) {
  $safeService->getPaging($paging);
}else{ 
    if (empty($_GET['para'])) {
      $data = isset($_POST['data']) ? $_POST['data'] : null;
      $dptid = isset($_POST['dptid']) ? $_POST['dptid'] : null;
    }else{
      $data = $_GET['para']['data'];
      $dptid = $_GET['para']['dptid'];
    }
    $paging->para = ['para' => ['data' => $data, 'dptid' => $dptid], 'flag' => 'findDev'];
    $safeService->findSafe($paging); 
}
$where = !empty($paging->para) ? http_build_query($paging->para['para']) : "";
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
  .col-md-2, #addForm .ztree-row,
  .col-md-2, #uptForm .ztree-row{
    overflow-y: scroll
  }

  .glyphicon-check, 
  .glyphicon-unchecked, 
  .glyphicon-resize-small, 
  .glyphicon-resize-full{
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

  #addForm .row, #uptForm .row{
    padding-left: 10px;
    padding-right: 10px;
    border-bottom: 1px solid #CCCCCC;
  }

  #addForm .input-group,#uptForm .input-group{
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
    padding-right: 15px !important
  }

  .del-auth{
    display: <?=in_array(1, $_SESSION['funcid']) || $user == 'admin' ? 'inline-cell' : 'none' ?>;
  }
</style>
<?php include 'buyVendor.php'; ?>
</head>
<body role="document">
<?php include "messageSafe.php";?>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="safedev.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="safeDev.php">设备台账</a></li>
        <li class="dropdown">
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
    <div class="col-md-10">
      <div class="page-header">
          <h4>　所有在用设备
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th>出厂编号</th><th>设备名称</th><th>所在分厂部门</th>
          <th>安装地点</th><th>有效日期</th><th>检修周期</th>
          <th style="width: 4%"></th>
          <th style="width:4%" class="del-auth"><a class="glyphicon glyphicon-plus" href="javascript: addDev('root');"></a></th>
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
              <td>{$row['circle']} 个月</td>
              <td><a class='glyphicon glyphicon-pencil del-auth' href='javascript:uptDev(".json_encode($row, JSON_UNESCAPED_UNICODE).");'></a></td>
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
          <div class="modal-footer">
            <input type="hidden" name="flag" value="findSafe">
            <input type="hidden" name="dptid" value="<?= isset($dptid) ? $dptid : "" ?>">
            <span style="display:none;color:red" class="failAdd">信息填写不完整。</span>
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
      <form class="form-horizontal" action="./controller/safeProcess.php" method="post">
        <div class="modal-body">
          <br>确定要删除？<br>(若成套设备，子设备也会相应删除)<br><br>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="delSafe">
          <input type="hidden" name="id">
          <button class="btn btn-primary">确认</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 设备添加 -->
<div class="modal fade" id="addForm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="controller/safeProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title">新设备</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">备件名称</span>
                <input class="form-control" name="data[name]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="data[codeManu]" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">安装地点</span>
                <input class="form-control" name="data[loc]" type="text">
              </div>  
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">启用日期</span>
                <input class="form-control datetime" name="data[takeTime]" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">有效日期</span>
                <input class="form-control datetime" name="data[valid]" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">检定周期</span>
                <input class="form-control" name="data[circle]" value="6" readonly="readonly" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default" type="button">月</button>
                </span>
              </div> 
            </div>
          </div>
          <div class="row ztree-row">
            <div class="col-md-4">
              <ul class="ztree" id="add-tree-py"></ul>
            </div>
            <div class="col-md-4">
              <ul class="ztree" id="add-tree-zp"></ul>
            </div>
            <div class="col-md-4">
              <ul class="ztree" id="add-tree-gp"></ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="data[takeDpt]" id="addTakeDpt">
          <input type="hidden" name="flag" value="addSafe">
          <span style="display:none;color:red" class="failAdd">信息填写不完整。</span>
          <span style="display:none;color:red" class="failRadio">领取部门必须选择唯一。</span>
          <button class="btn btn-primary" id="yesAdd">确定</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<div class="modal fade" id="uptForm">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="controller/safeProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title">修改</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">备件名称</span>
                <input class="form-control" name="name" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="codeManu" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">安装地点</span>
                <input class="form-control" name="loc" type="text">
              </div>  
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">启用日期</span>
                <input class="form-control datetime" name="takeTime" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">有效日期</span>
                <input class="form-control datetime" name="valid" readonly="" type="text">
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
            </div>
          </div>
          <div class="row ztree-row">
            <div class="col-md-4">
              <ul class="ztree" id="upt-tree-py"></ul>
            </div>
            <div class="col-md-4">
              <ul class="ztree" id="upt-tree-zp"></ul>
            </div>
            <div class="col-md-4">
              <ul class="ztree" id="upt-tree-gp"></ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="takeDpt" id="uptTakeDpt">
          <input type="hidden" name="flag" value="uptSafe">
          <input type="hidden" name="id">
          <span style="display:none;color:red" class="failAdd">信息填写不完整。</span>
          <span style="display:none;color:red" class="failRadio">领取部门必须选择唯一。</span>
          <button class="btn btn-primary" id="yesUpt">确定</button>
        </div>
      </form> 
    </div>
  </div>
</div>  

<?php include 'devJs.php';?>
<script type="text/javascript">
$("yesFind").click(function(){
  $("#searchForm input[type=text]").each(function(){
    var allow_submit = true;
    if($(this).val()==""){
      allow_submit = false;
      $("$searchForm .failAdd").show();
    }
    return allow_submit;
  })

});

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
    },
    callback: {
      onClick: zTreeOnClick
    }
};

$.extend({
    StandardPost:function(url,args,data){
        var body = $(document.body),
            form = $("<form method='post'></form>"),
            input;
        form.attr({"action":url});
        $.each(args,function(key,value){
            input = $("<input type='hidden'>");
            input.attr({"name":key});
            input.val(value);
            form.append(input);
        });

        if (data != 'all') 
          $.each(data, function(key,value){
            input = $("<input type='hidden'>");
            input.attr({"name":"data["+key+"]"});
            input.val(value);
            form.append(input);
          });

        form.appendTo(document.body);
        form.submit();
        document.body.removeChild(form[0]);
    }
});

function zTreeOnClick(event, treeId, treeNode) {
  var data = <?= isset($_POST['data']) ? json_encode($_POST['data'], JSON_UNESCAPED_UNICODE) : "'all'";?>;
  $.StandardPost('./safeDev.php', {flag: 'findSafe', dptid: treeNode.id}, data);  
};

var zTree = <?= $dptService->getDptForRole("1,2,3") ?>,
dptTree = {
  py: <?= $dptService->getDptForRole(1) ?>, 
  zp: <?= $dptService->getDptForRole(2) ?>, 
  gp: <?= $dptService->getDptForRole(3) ?>
};

// 检定周期加
$("#addForm .glyphicon-plus,#uptForm .glyphicon-plus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  num++;
  $circle.val(num);
});

// 检定周期减
$("#addForm .glyphicon-minus,#uptForm .glyphicon-minus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  if (num != 1) {
    num--;
    $circle.val(num);
  }
});

// 确认添加
$("#yesAdd").click(function(){
  $("#addForm .failAdd,#addForm .failRadio").hide();
  var allow_submit = true;
  $("#addForm input[name^='data'][type!=hidden]").each(function(i, el){
    if(el.value == ""){
      allow_submit = false;
      $("#addForm .failAdd").show();
    }
  });
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) {
    allow_submit = false;
    $("#addForm .failRadio").show();
  }else{
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
    $("#addForm #addTakeDpt").val(nodes[0].id);
  }
  return allow_submit;
});

// 添加设备
function addDev(path){
  $.fn.zTree.init($("#add-tree-py"), settingModal, dptTree.py);
  $.fn.zTree.init($("#add-tree-zp"), settingModal, dptTree.zp);
  $.fn.zTree.init($("#add-tree-gp"), settingModal, dptTree.gp);
  treePy = $.fn.zTree.getZTreeObj("add-tree-py");
  treeZp = $.fn.zTree.getZTreeObj("add-tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("add-tree-gp");
  $("#addForm .ztree-row").height(0.3 * $(window).height());

  $("#addForm #addTakeDpt").val();
  $('#addForm').modal({
    keyboard: true
  });  
}

function uptDev(data){
  $.fn.zTree.init($("#upt-tree-py"), settingModal, dptTree.py);
  $.fn.zTree.init($("#upt-tree-zp"), settingModal, dptTree.zp);
  $.fn.zTree.init($("#upt-tree-gp"), settingModal, dptTree.gp);
  uptTreePy = $.fn.zTree.getZTreeObj("upt-tree-py");
  uptTreeZp = $.fn.zTree.getZTreeObj("upt-tree-zp");
  uptTreeGp = $.fn.zTree.getZTreeObj("upt-tree-gp");
  $("#uptForm .ztree-row").height(0.3 * $(window).height());

  $.each(data,function(key,val){
    $("input[name="+key+"]").val(val);
  })
  switch (data.comp){
    case "1":
      var node = uptTreePy.getNodesByParam("id", data.takeDpt, null);
      uptTreePy.checkNode(node[0], true, true);
      break;
    case "2":
      var node = uptTreeZp.getNodesByParam("id", data.takeDpt, null);
      uptTreeZp.checkNode(node[0], true, true);
      break; 
    case "3":
      var node = uptTreeGp.getNodesByParam("id", data.takeDpt, null);
      uptTreeGp.checkNode(node[0], true, true);
      break;
  }

  $('#uptForm').modal({
    keyboard: true
  }); 
}

$("#yesUpt").click(function(){
  $("#uptForm .failAdd,#uptForm .failRadio").hide();
  var allow_submit = true;
  $("#uptForm input[type=text]").each(function(i, el){
    if(el.value == ""){
      allow_submit = false;
      $("#uptForm .failAdd").show();
    }
  });
  var nodesPy = uptTreePy.getCheckedNodes(true);
  var nodesZp = uptTreeZp.getCheckedNodes(true);
  var nodesGp = uptTreeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) {
    allow_submit = false;
    $("#uptForm .failRadio").show();
  }else{
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
    $("#uptForm #uptTakeDpt").val(nodes[0].id);
  }
  return allow_submit;
});

$(function(){
  $(".col-md-2").height(0.9 * $(window).height());
  $.fn.zTree.init($("#tree"), setting, zTree);
  tree = $.fn.zTree.getZTreeObj("tree");
  var dptid = '<?= isset($dptid) ? $dptid : ""?>';
  if (dptid.length > 0) {
    var node = tree.getNodeByParam("id", dptid, null);
    for (var i = 0; i < node.getPath().length; i++) {
      tree.expandNode(node.getPath()[i], true, false, true);
    }
    tree.selectNode(node);
  }
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


   </script>

  </body>
</html>