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
$paging->gotoUrl="buyApv.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyCheckHis($paging);



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
.open > th, .open > td{
  background-color:#F0F0F0;
}

th > .glyphicon-trash{
  display:none;
} 

tr:hover > th > .glyphicon-trash {
  display: inline;
}

</style>
<link rel="stylesheet" href="tp/datetimepicker.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
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
            <li><a href="buyApply.php">仪表备件申报</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
             <?php if ($_SESSION['permit']==2) {
                 echo "<li role='separator' class='divider'></li>";
              } ?>
            <li><a href="spareList.php">备品备件</a></li>
            
            <?php if ($_SESSION['permit']<2) {
                 echo "<li role='separator' class='divider'></li><li><a href='devPara.php'>属性参数</a></li>";
               } ?>
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
       <?php if ($_SESSION['permit']<2) {
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
            <li><a href="#">我的基本信息</a></li>
            <li><a href="#">更改密码</a></li>
            <li class="divider">&nbsp;</li>
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
        <h4>　历史入厂检定记录</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th><th>检定时间</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>申报部门</th>
            <th style="width:4%"></th><th style="width:4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前没有已经检定的备件</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            //  [id] => 1 [code] => 510740110018 [name] => 超声波流量计 [no] => TJZ-100B [num] => 3 [unit] => 个 [info] => test upt spr info [depart] => 能源部 [cljl] => CLJL-30-09 [factory] => 办公楼
            // glyphicon glyphicon-transfer
            $checkInfo = "<td><a href='javascript:void(0);' class='glyphicon glyphicon-";
            if ($row['res'] == 3) {
              $checkInfo .= "transfer";
              $fold = "<td></td>";
            }else{
              $checkInfo .= "ok";
              $fold = "<td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='ckInfo(this,{$row['id']});'></a></td>";
            }
            $checkInfo .= "' style='display:inline;cursor:default'></a></td>";


            $addHtml = 
            "<tr>
                ".$fold."
                <td>{$row['checktime']}</td>
                <td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['id']});'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['num']} {$row['unit']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td><a class='glyphicon glyphicon-shopping-cart' href='javascript:flowInfo({$row['id']});'></a></td>
                ".$checkInfo."
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



<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<?php  include "./buyJs.php";?>
<script type="text/javascript">
function ckInfo(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getCkInfo',
      sprId:id
    },function(data,success){
      var addHtml = "<tr class='open-"+id+"'>"+
                    "   <td colspan='12'>"+
                    "     <div class='row'>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>制造厂：</b>"+data.supplier+"</p>"+
                    "         <p><b>精度等级：</b>"+data.accuracy+"</p>"+
                    "         <p><b>量程：</b>"+data.scale+"</p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>出厂编号：</b>"+data.codeManu+"</p>"+
                    "         <p><b>检定周期(月)：</b>"+data.circle+"</p>"+
                    "         <p><b>检定部门：</b>"+data.depart+"</p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>检定日期：</b>"+data.checkNxt+"</p>"+
                    "         <p><b>溯源方式：</b>"+data.track+"</p>"+
                    "         <p><b>证书结论：</b>"+data.certi+"</p>"+    
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