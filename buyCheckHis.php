<?php 
require_once "model/cookie.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
checkValidate();
$user=$_SESSION['user'];

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyCheckHis.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
// 是否为搜索结果
if (empty($_POST['flag'])) {
  $gaugeService->buyCheckHis($paging);
}else if ($_POST['flag'] == 'findCheck') {
  $check_from = $_POST['check_from'];
  $check_to = $_POST['check_to'];
  $codeWare = $_POST['codeWare'];
  $name = $_POST['name'];
  $spec = $_POST['spec'];

  $gaugeService->buyInstallFind($check_from, $check_to, $codeWare, $name, $spec, $paging);
}
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
<title>入厂检定历史记录-仪表管理</title>
<style type="text/css">
.glyphicon-unchecked, .glyphicon-check{
  display: inline !important;
}

#takeAll{
  display:none;
  cursor: pointer;
}

.glyphicon-search{
  float: right;
  margin-right: 15px;
}

#uptCheck .input-group{
  margin: 12px 0;
}

#uptCheck .modal-body{
  padding: 0 25px !important;
}

#outComp{
  display: none;
}

.glyphicon-resize-small, .glyphicon-resize-full{
  display: inline !important;
}
</style>
<?php include "./buyVendor.php"; ?>
</head>
<body role="document">
<?php   include "message.php";?>
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
        <li class="active"><a href="<?php echo (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
            <li><a href="spareList.php">备品备件</a></li>
            <li style="display: <?php echo (in_array(4, $_SESSION['funcid'])  && $_SESSION['user'] != 'admin') ? "inline" : "none";?>"><a href='devPara.php' >属性参数</a></li>
          </ul>
        </li>
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
        <li style="display: <?php echo (!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline";?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php echo $user;?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="login.php">注销</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

<!-- 搜索备件检定记录-->
<div class="modal fade" id="findCheck">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索检定记录</h4>
      </div>
      <form class="form-horizontal" action="buyCheckHis.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">检定时间起：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="check_from" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">检定时间止：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="check_to" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">存货编码：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="codeWare">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">备件名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">规格型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="spec">
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="findCheck">
            <button class="btn btn-primary yesFind">搜索</button>
          </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="uptCheck">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">修改入厂检定信息</h4>
      </div>
      <form action="./controller/gaugeProcess.php" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="codeManu" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">精度等级</span>
                <input class="form-control" name="accuracy" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">量　　程</span>
                <input class="form-control" name="scale" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">证书结论</span>
                <input class="form-control" name="certi" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">溯源方式</span>
                <select class="form-control" name="track">
                  <option value="检定">检定</option>
                  <option value="校准">校准</option>
                  <option value="测试">测试</option>
                </select>
              </div> 
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">检定日期</span>
                <input class="form-control datetime" name="checkNxt" readonly="" type="text">
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
                  <button class="btn btn-default">月</button>
                </span>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">检定单位</span>
                <select class="form-control" name="checkDpt">
                  <option value="199">计量室</option>
                  <option value="<?php echo $_SESSION['udptid']?>">使用部门</option>
                  <option value="isOut">外检单位</option>
                </select>
              </div>
              <div class="input-group" id="outComp">
                <span class="input-group-addon">外检公司</span>
                <input class="form-control" name="outComp" type="text">
              </div>  
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addCheck">
          <input type="hidden" name="id">
          <button class="btn btn-default" type="button" id='delCheck'>删除</button>
          <button class="btn btn-primary" id='yesUpt'>修改</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="delInfo" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">确定要删除该备件吗？</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" id="yesDelInfo">确定</button>
        </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="page-header">
        <h4>　已登记备件 <a href="javascript:void(0);" class="glyphicon glyphicon-search"></a></h4>
    </div>
    <table class="table table-striped table-hover">
      <thead>
        <tr><th><span class='glyphicon glyphicon-log-out' id="takeAll" ></span></th>
            <th style="width: 8%">检定日期</th><th style="width: 8%">出厂编号</th><th>名称</th><th>规格</th><th>入库日期</th><th>单位</th>
            <th>存货分类</th><th>供应商</th><th>存货编码</th>
            <th style="width:4%"></th><th style="width:4%"></th><th style="width:4%"></th>
        </tr>
      </thead>
      <tbody class="tablebody">
      <?php 
        if (count($paging->res_array) == 0) {
          echo "<tr><td colspan=12>当前无新的已入库的备件</td></tr>";
        }
        for ($i=0; $i < count($paging->res_array); $i++) { 
          $row = $paging->res_array[$i];
          if (in_array(7, $_SESSION['funcid'])  || $_SESSION['user'] == 'admin') 
            $uptCheck = "<td><a href='javascript:uptCheck({$row['id']});' class='glyphicon glyphicon-pencil'></a></td>";
          else
            $uptCheck ="<td></td>";

          if ($row['unit'] == "套") 
            $leaf = "<td><a href='javascript:void(0);' onclick=\"getLeaf(this, {$row['id']});\" class='glyphicon glyphicon-resize-small' id='test'></a></td>";
          else
            $leaf = "<td></td>";
          echo
          "<tr>
              <td><span class='glyphicon glyphicon-unchecked' chosen='{$row['id']}'></span></td>
              <td>{$row['checkTime']}</td>
              <td>{$row['codeManu']}</td>
              <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
              <td>{$row['spec']}</td>
              <td>{$row['wareTime']}</td>
              <td>{$row['unit']}</td>
              <td>{$row['category']}</td>
              <td>{$row['supplier']}</td>
              <td>{$row['codeWare']}</td>
              {$uptCheck}
              {$leaf}
              <td><a href='javascript:takeOne({$row['id']});' class='glyphicon glyphicon-log-out'></a></td>
           </tr>";
        }
      ?>
      </tbody>
    </table>
  </div>
</div>

<div class='foothome'><?php echo $paging->navi?></div>  

<!-- 领取安装弹出框 -->
<div class="modal fade" id="takeSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件领取</h4>
      </div>
      <form class="form-horizontal" id="formTake" action="./controller/gaugeProcess.php" method="post">
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
          <input type="hidden" name="flag" value="takeSpr">
          <input type="hidden" name="dptId">
          <input type="hidden" name="arrId">
          <button class="btn btn-primary" id="yesTake">确认</button>
        </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade"  id="failRadio" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">领取部门必须选择唯一。</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<?php  include "./buyJs.php";?>
<script type="text/javascript">
function getLeaf(obj,id){
    var flagIcon=$(obj).attr("class");
    var $rootTr=$(obj).parents("tr");
    // 列表是否未展开
    if (flagIcon=="glyphicon glyphicon-resize-small") {
      // 展开
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
      $.post("controller/gaugeProcess.php",{
        flag: 'getLeaf',
        id: id,
        status: 2
      },function(data){
        var user = session.user;
        var allow_enter = $.inArray(7,session.funcid);
        if (user == "admin") {
          allow_enter = 0;
        }

        var addHtml = "";
        for (var i = 0; i < data.length; i++){
          if(allow_enter != -1)
            $uptCheck = "<td><a href='javascript:uptCheck(" + data[i].id + ");' class='glyphicon glyphicon-pencil'></a></td>";
          else
            $uptCheck = "<td></td>";
          addHtml += 
          "<tr class='open "+data[i].id+" open-"+id+"' style='border: 1px solid #ddd !important;'>"+
              "<td></td>"+
              "<td>" + data[i].checkTime + "</td>" +
              "<td>" + data[i].codeManu + "</td>" +
              "<td>" + data[i].name + "</td>" +
              "<td>" + data[i].spec + "</td>" +
              "<td>" + data[i].wareTime + "</td>" +
              "<td>" + data[i].unit + "</td>" +
              "<td>" + data[i].category + "</td>" +
              "<td>" + data[i].supplier + "</td>" +
              "<td>" + data[i].codeWare + "</td>" +
              $uptCheck+
              "<td></td>"+
              "<td><a href='javascript:takeOne(" + data[i].id + ");' class='glyphicon glyphicon-log-out'></a></td>"+
           "</tr>";
        }
        $rootTr.after(addHtml);
      },'json');
    }else{
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
      $(".open-"+id).detach();
    }
}

$("#yesFind").click(function(){
  var allow_submit = true;
  $("#findCheck input").each(function(){
    if($(this).val() == ""){
      allow_submit = false;
      $('#failAdd').modal({
        keyboard: true
      });
      return false;
    }
  });
  return allow_submit;
});

// 领取部门树形结构
var setting = {
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

var zTree = {
  py: <?= $dptService->getDptForRole(1) ?>, 
  zp: <?= $dptService->getDptForRole(2) ?>, 
  gp: <?= $dptService->getDptForRole(3) ?>
};


function takeSpr(str){
  $.fn.zTree.init($("#tree-py"), setting, zTree.py);
  $.fn.zTree.init($("#tree-zp"), setting, zTree.zp);
  $.fn.zTree.init($("#tree-gp"), setting, zTree.gp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");

  $("#takeSpr").modal({
    keyboard:true
  });
}

// 确认领取按钮
$("#yesTake").click(function(){
  var allow_submit = true;
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) {
    allow_submit = false;
    $("#failRadio").modal({
     keyboard:true
    });
  }else
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  // 领取部门编号
  $("#takeSpr input[name=dptId]").val(nodes[0].id);
  return allow_submit;
});

// 外检input框显示
$("select[name=checkDpt]").click(function(){
  if ($(this).val() == "isOut") 
    $("#outComp").show().css("display", "table");
  else
    $("#outComp").hide();
});

$("#delCheck").click(function(){
  $('#delInfo').modal({
    keyboard: false
  });
});

$("#yesDelInfo").click(function(){
  var id = $(this).attr("delid");
  $.post("./controller/gaugeProcess.php",{flag: 'delInfo', id: id},function(data){
    if (data == true) 
      location.href = "./buyCheck.php";
  }, 'text');
})

function uptCheck(id){
  $.post("./controller/gaugeProcess.php",{flag: "getChk", id: id}, function(data){
    if (data.checkComp != null) {
      $("#outComp").show().css("display", "table").find("input").val(data.checkComp);
      $("select[name=checkDpt]").val("isOut");
    }else if (data.checkDpt == 199) {
      $("#outComp").hide();
      $("select[name=checkDpt]").val(199);
    }else{
      $("#outComp").hide();
      $("select[name=checkDpt]").val(session.udptid);
    }

    $("#uptCheck input[name=id]").val(id);
    $("#yesDelInfo").attr("delid", id);
    for(var key in data){
      $("#uptCheck input[name="+key+"]").val(data[key]);
    }

    $('#uptCheck').modal({
      keyboard: false
    });
  }, 'json');
}

// 确定添加检定信息到mysql中
$("#yesUpt").click(function(){
  var allow_submit = true;

  $("#uptCheck .form-control[name!=outComp]").each(function(){
    if ($(this).val() == "" || ($(this).val() == 'isOut' && $("input[name=out Comp]").val() == "")) {
      $("#failAdd").modal({
        keyboard:true
      });
      allow_submit = false;
    }
  });

  return allow_submit;
});

$(".glyphicon-search").click(function(){
  $('#findCheck').modal({
    keyboard: false
  })
});

// 多选
$(".tablebody").on("click","tr>td:first-child>span",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked");
    $(this).toggleClass("glyphicon glyphicon-check");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $("#takeAll").show();
    }else{
      $("#takeAll").hide();
    }
});

$("#takeAll").click(function(){
  var str = "";
  $(".glyphicon-check").each(function(){
    str += $(this).attr('chosen') + ",";
  });
  $("#takeSpr input[name=arrId]").val(str);
  takeSpr();
});

function takeOne(id){
  $("#takeSpr input[name=arrId]").val(id+",");
  takeSpr();
}

</script>
</body>
</html>