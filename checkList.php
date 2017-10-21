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
  $data = !empty($_GET['para']) ? $_GET['para']['data'] : $_POST['data'];
  $paging->para = ['para' => ['data' => $data], 'flag' => 'findCheck'];
  $checkService->findCheckPaging($paging);
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
  <title>检定记录-设备管理系统</title>
  <style type="text/css">
    .glyphicon-check, .glyphicon-unchecked,.glyphicon-thumbs-up,.glyphicon-option-horizontal, .glyphicon-thumbs-down{
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

    #searchForm .ztree-row{
      overflow-y: scroll
    }

  </style>
  <?php include 'buyVendor.php'; ?>
</head>
<body role="document">
<?php include 'message.php'; ?>
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
          <h4>　检定记录
            <span class="glyphicon glyphicon-search" ></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th><span class="glyphicon glyphicon-download-alt" id="confirmList"></span></th>
          <th>检定日期</th><th>出厂编号</th><th>设备名称</th><th>所在分厂部门</th><th>安装地点</th><th>溯源方式</th>
          <th>证书结论</th><th>状态</th><th>计量要求</th><th style="text-align:center">验证结果</th>
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
              $confirm = "{$row['chkRes']}";
            }elseif (in_array($row['res'], [1,5,6])) {
             $request = "未计量确认";
             $confirm = "<a class='glyphicon glyphicon-thumbs-up' href='javascript:addConfirm({$row['id']},\"{$row['codeManu']}\");'></a>";
            }else{
              $request = "检定不合格";
              if (empty($row['when'])) 
                $confirm = "<a class='glyphicon glyphicon-thumbs-down' href='javascript:failCheck({$row['id']},\"{$row['codeManu']}\");'></a>";
              else
                $confirm = "<a class='glyphicon glyphicon-option-horizontal' href='./controller/confirmProcess.php?flag=xlsUnqual&chkid={$row['id']}'></a>";
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
              default:
                $row['res'] = $row['conclu']; break;
            }
            echo "<tr>
	                <td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>
                  <td>{$row['time']}</td>
	                <td>{$row['codeManu']}</td>
                  <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
	                <td>{$row['takeFct']}</td>
	                <td>{$row['loc']}</td>
                  <td>{$row['track']}</td>
                  <td>{$row['res']}</td>
                  <td>{$row['status']}</td>
                  <td>$request</td>
                  <td style='text-align:center'>$confirm</td>
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
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">计量确认</h4>
      </div>
      <form class="form-horizontal" action="./controller/confirmProcess.php" method="post">
        <div class="modal-body"> 
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-4 control-label">出厂编号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="codeManu" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">检定日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="cfr[time]" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">工艺测量范围：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="cfr[techscale]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">工艺控制精度：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="cfr[techaccu]">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-4 control-label">计量测量范围：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="cfr[scale]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">计量允许误差：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <input type="text" class="form-control" name="cfr[error]">
                    <span class="input-group-addon">级</span>
                  </div> 
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">计量分度值：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="cfr[interval]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">验证结果：</label>
                <div class="col-sm-8">
                  <select class="form-control" name="cfr[chkRes]">
                    <option value="合格">合格</option>
                  </select>
                </div>
              </div>
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
$("#yesNoCheck").click(function(){
  var allow_submit = true;
  var chked = $(".checkbox").find(":checked").length == 0 ? false : true;
  var text = $("#noModal textarea").val() == "" ? false : true;
  if (!chked || !text) {
    $("#failNoCheck").show();
    allow_submit = false;
  }
  return allow_submit;
});


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