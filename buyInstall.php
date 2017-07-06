<?php 
require_once "model/cookie.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
require_once "./model/devService.class.php";
checkValidate();
$user=$_SESSION['user'];

$devService = new devService();

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyInstall.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyInstall($paging);
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
<title>安装验收-仪表管理</title>
<style type="text/css">
tr:hover > th > .glyphicon-trash {
  display: inline;
}

tbody .glyphicon{
  display: inline !important;
}
</style>
<?php include "buyVendor.php"; ?>
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
      <a class="navbar-brand" href="homePage.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?=(in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
            <li><a href="spareList.php">备品备件</a></li>
            <li style="display: <?= (in_array(4, $_SESSION['funcid'])  && $_SESSION['user'] != 'admin') ? "inline" : "none";?>"><a href='devPara.php' >属性参数</a></li>
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
        <li style="display: <?= (!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline";?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?= $user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="login.php">注销</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

<!-- 备件是否存入小仓库 -->
<div class="modal fade" id="storeModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备用</h4>
      </div>
      <form class="form-horizontal" action="./controller/gaugeProcess.php" method="post">
        <div class="modal-body">
          <br>是否需要存入备用仓库？<br><br>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="storeSpr">
          <input type="hidden" name="id">
          <button class="btn btn-primary">确定</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="useModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">安装地点</h4>
      </div>
      <form class="form-horizontal" method="post" action="./controller/gaugeProcess.php">
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" name="loc">
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="useSpr">
          <input type="hidden" name="id">
          <button class="btn btn-primary" id="yesUse">确定</button>
        </div>
        </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-10">
      <div class="page-header">
        <h4>　仪表备件安装验收</h4>
      </div>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width: 4%"></th>
            <th>检定日期</th><th>出厂编号</th><th>名称</th><th>规格</th>
            <th>单位</th><th>存货分类</th><th>存货编码</th>
            <th style="width:4%"></th><th style="width:4%"></th>
          </tr>
        </thead>
      <tbody class="tablebody">
      <?php if (count($paging->res_array) == 0): ?>
        <tr><td colspan=12>当前无新备件需要安装验收</td></tr>
      <?php else: ?>
        <?php for ($i=0; $i < count($paging->res_array); $i++) { 
          $row = $paging->res_array[$i]; 
          if ($row['unit'] == "套") {
            $leaf = "<td><a href='javascript:void(0);' onclick=\"getLeaf(this, {$row['id']});\" class='glyphicon glyphicon-resize-small' id='test'></a></td>";
            $use = "<td></td>";
            $store = "<td></td>";
          }else{
            $leaf = "<td></td>";
            $use = "<td><a href='javascript:use({$row['id']});' class='glyphicon glyphicon-play-circle'></a></td>";
            $store = "<td><a href='javascript:store({$row['id']});' class='glyphicon glyphicon-briefcase'></a></td>";
          }
          echo 
          "<tr>
          {$leaf}
          <td>{$row['checkTime']}</td>
          <td>{$row['codeManu']}</td>
          <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
          <td>{$row['spec']}</td>
          <td>{$row['unit']}</td>
          <td>{$row['category']}</td>
          <td>{$row['codeWare']}</td>
          {$use}{$store}
          </tr>";
        } ?>
      <?php endif ?> 
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

<?php include "./buyJs.php";?>
<script type="text/javascript">
function getLeaf(obj,id){
    var flagIcon=$(obj).attr("class");
    var $rootTr=$(obj).parents("tr");
    // 列表是否未展开
    if (flagIcon=="glyphicon glyphicon-resize-small") {
      // 展开
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
      $.post("controller/gaugeProcess.php",{
        flag:'getLeaf',
        id: id,
        status: 3
      },function(data){
        var addHtml = "";
        for (var i = 0; i < data.length; i++){
          addHtml += 
          "<tr class='open "+data[i].id+" open-"+id+"' style='border: 1px solid #ddd !important;'>"+
              "<td></td>"+
              "<td>" + data[i].checkTime + "</td>" +
              "<td>" + data[i].codeManu + "</td>" +
              "<td>" + data[i].name + "</td>" +
              "<td>" + data[i].spec + "</td>" +
              "<td>" + data[i].unit + "</td>" +
              "<td>" + data[i].category + "</td>" +
              "<td>" + data[i].codeWare + "</td>" +
              "<td><a href='javascript:use(" + data[i].id + ");' class='glyphicon glyphicon-play-circle'></a></td>"+
              "<td><a href='javascript:store(" + data[i].id + ");' class='glyphicon glyphicon-briefcase'></a></td>"+
           "</tr>";
        }
        $rootTr.after(addHtml);
      },'json');
    }else{
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
      $(".open-"+id).detach();
    }
}

function use(id){
  $("#useModal input[name=id]").val(id);
  $("#useModal").modal({
    keyboard:true
  });
}

$("#yesUse").click(function(){
  var allow_submit = true, isNull = $("#useModal input[name=loc]").val();
  if (isNull == "") 
    allow_submit =false;
  return allow_submit;
});

function store(id){
  $("#storeModal input[name=id]").val(id);
  $("#storeModal").modal({
    keyboard:true
  });
}

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

    </script>
  </body>
</html>