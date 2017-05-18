<?php 
require_once "model/cookie.php";
require_once "model/repairService.class.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
checkValidate();
$user=$_SESSION['user'];

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyStoreHouse.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
// 是否为搜索结果
if (empty($_POST['flag'])) {
  $gaugeService->buyStoreHouse($paging);
}else if ($_POST['flag'] == 'findStore') {
  $storeTime = $_POST['storeTime'];
  $depart = $_POST['dptId'];
  $code = $_POST['sprCode'];
  $name = $_POST['sprName'];
  $no = $_POST['sprNo'];

  $gaugeService->buyStoreFind($storeTime,$depart,$code,$name,$no,$paging);
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
<title>备件入账存库-仪表管理</title>
<style type="text/css">
.tablebody > tr > td:nth-child(2) > a {
    display: inline !important;
}

.open > th, .open > td{
  background-color:#F0F0F0;
}

#apvSpr li{
    list-style: none;
    margin:10px 0px;
}

.open > th, .open > td{
  background-color:#F0F0F0;
}

th > .glyphicon-trash{
  display:none;
} 

tr:hover > th > .glyphicon-trash {
  display: inline;
}

.ztree-row{
    margin-left: 0px !important; 
    margin-bottom:0px !important;
    height: 400px;
    overflow-y:auto
  }

  .ztree-row > .col-md-4{
    margin:0px !important;
  }

</style>
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/metroStyle/metroStyle.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/jquery.ztree.core.js"></script>
<script src="bootstrap/js/jquery.ztree.excheck.min.js"></script>
</head>
<body role="document">
<?php 
  $repairService=new repairService();
  include "message.php";
?>
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
        <li><a href="homePage.php">首页</a></li>
        <li class="active dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button">设备购置 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="buyGauge.php">仪表备件申报</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
            <?php if (!in_array(4,$_SESSION['funcid'])  && $_SESSION['user'] != 'admin') {
                        echo "<li role='separator' class='divider'></li><li>";
                      } 
                ?>
                <li><a href="spareList.php">备品备件</a></li>
                
                <?php if (in_array(4,$_SESSION['funcid']) || $_SESSION['user'] == 'admin') {
                        echo "<li role='separator' class='divider'></li><li><a href='devPara.php'>属性参数</a></li>";
                      } 
                ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">日常巡检 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="inspStd.php">巡检标准</a></li>
            <li><a href="inspMis.php">巡检计划</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="inspList.php">巡检记录</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修保养 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repPlan.php">检修计划</a></li>
            <li><a href="repMis.php">维修/保养任务</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="repList.php">维修记录</a></li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
       <?php if (in_array(10,$_SESSION['funcid']) || $_SESSION['user'] == 'admin') {
                      echo "<li><a href='dptUser.php'>用户管理</a></li>";
                    } 
             ?>
       
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php 
              if (empty($user)) {
                echo "用户信息";
              }else{
                echo "$user";
              } 
            ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="login.php">注销</a></li>
          </ul>
          </li>
      </ul>

    </div><!--/.nav-collapse -->
  </div>
</nav>
<!-- 领取安装弹出框 -->
<div class="modal fade" id="takeSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件出库·领取</h4>
      </div>
      <form class="form-horizontal" id="formTake">
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


          <div class="form-group">
            <label class="col-sm-2 control-label">领取人：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name='takeUser'>
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="takeSpr">
            <input type="hidden" name="id">
            <input type="hidden" name="dptId">
            <button class="btn btn-primary" id="yesTake" type="button">确认</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
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

<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件当前存库</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th><th style="width:4%"></th>
            <th>存货编码</th><th>出厂编号</th><th>存货名称</th><th>规格型号</th><th>入库时间</th>
            <th style="width:4%"><span class="glyphicon glyphicon-th-list" style='cursor:pointer;display:none'></span></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            if (empty($_POST['flag'])) {
              echo "<tr><td colspan=12>当前无仪表备件库存</td></tr>";
            }else{
              echo "<tr><td colspan=12>没有符合当前搜索条件的记录，请重新核实。</td></tr>";
            }
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            $addHtml = 
            "<tr>
              <td><a class='glyphicon glyphicon-unchecked' href='javascript:void(0);' chosen='{$row['id']}'></a></td>
              <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='storeInfo(this,{$row['id']});'></a></td>
              <td>{$row['code']}</td><td>{$row['codeManu']}</td>
              <td><a href='javascript:flowInfo({$row['sprid']})'>{$row['name']}</td>
              <td>{$row['no']}</td>
              <td>{$row['storeTime']}</td>
              <td><a class='glyphicon glyphicon-log-out' href='javascript:takeSpr({$row['id']});'></a></td>
             </tr>";
             echo "$addHtml";

          }
        ?>
        </tbody>
        </table>
        <div class='page-count'><?php echo $paging->navi?></div>                    
    </div>
    <div class="col-md-2">
    <div class="col-md-3">
    <?php  include "buyNavi.php";?>
    </div>
    </div>
</div>
</div>

<?php  include "./buyJs.php";?>
<script type="text/javascript">
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

var zTreePy = <?php $data = $dptService->getDptForRole(1); echo $data; ?>;
var zTreeZp = <?php $data = $dptService->getDptForRole(2); echo $data; ?>;
var zTreeGp = <?php $data = $dptService->getDptForRole(3); echo $data; ?>;


function takeSpr(str){
  $.fn.zTree.init($("#tree-py"), setting, zTreePy);
  $.fn.zTree.init($("#tree-zp"), setting, zTreeZp);
  $.fn.zTree.init($("#tree-gp"), setting, zTreeGp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");

  if (!isNaN(str)) {
    str = '["'+str+'"]';
  }
  $("#takeSpr input[name=id]").val(str);
  $("#takeSpr").modal({
    keyboard:true
  });
}



// 确认领取按钮
$("#yesTake").click(function(){
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) 
     $("#failRadio").modal({
      keyboard:true
     });
   else
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  // 领取部门编号
  $("#takeSpr input[name=dptId]").val(nodes[0].id);
  var takeUser =$("#takeSpr input[name=takeUser]").val();

  if (takeUser.length == 0) {
    $("#failAdd").modal({
      keyboard:true
     });
     return false;
  }
   $.post("./controller/gaugeProcess.php",$("#formTake").serialize(),function(data){
    if (data == 'success') {
      location.href="./buyStoreHouse.php";
    }
  },'text');
});




// 多选出库
$(".glyphicon-th-list").click(function(){
  var arr = new Array();
  var i = 0;
  $(".glyphicon-check").each(function(){
    arr[i] = $(this).attr('chosen');
    i++;
  });
  ajaxArr = JSON.stringify(arr);
  takeSpr(ajaxArr);
});


// 多选按钮
$(".tablebody").on("click","tr>td:first-child>a",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked");
    $(this).toggleClass("glyphicon glyphicon-check");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $(".glyphicon-th-list").show();
    }else{
      $(".glyphicon-th-list").hide();
    }
});

// 入账的备件数目加
$("#takeSpr #plus").click(function(){
  var num = parseInt($("#takeSpr input[name=num]").val());
  if (num != $(this).attr("max")) {
    num++;
    $("#takeSpr input[name=num]").val(num);
  }
});

// 入账的备件数目减
$("#takeSpr #minus").click(function(){
  var num = parseInt($("#takeSpr input[name=num]").val());
  if (num != 1) {
    num--;
    $("#takeSpr input[name=num]").val(num);
  }
});

function storeInfo(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getStoreInfo',
      id:id
    },function(data,success){
      var addHtml = "<tr class='open-"+id+"'>"+
                    "   <td colspan='12'>"+
                    "     <div class='row'>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>制造厂：</b> "+data.supplier+" </p>"+
                    "         <p><b>精度等级：</b> "+data.accuracy+" </p>"+
                    "         <p><b>量程：</b> "+data.scale+" </p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>检定周期：</b> "+data.circle+" </p>"+
                    "         <p><b>溯源方式：</b> "+data.track+" </p>"+
                    "         <p><b>证书结论：</b> "+data.certi+" </p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>检定部门：</b> "+data.factory+data.depart+" </p>"+
                    "         <p><b>入库人：</b> "+data.storeUser+" </p>"+
                    "       </div>"+
                    "     </div>"+
                    "   </td>"+
                    " </tr>";
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