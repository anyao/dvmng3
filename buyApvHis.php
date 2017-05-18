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
$paging->gotoUrl="buyApvHis.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();

// 是否为搜索结果
if (empty($_POST['flag'])) {
  $gaugeService->buyApvHis($paging);
}else if ($_POST['flag'] == 'findApv') {
  $apvTime = $_POST['apvTime'];
  $depart = $_POST['dptId'];
  $code = $_POST['sprCode'];
  $name = $_POST['sprName'];
  $no = $_POST['sprNo'];

  $gaugeService->buyApvFind($apvTime,$depart,$code,$name,$no,$paging);
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
<title>备件审核历史-仪表管理</title>
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
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
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
<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件审核历史</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
           <th style="width: 8%"></th>
           <th style="width: 24%">审核时间</th>
           <th style="width: 18%">申报分厂</th>
           <th style="width: 15%">申报单位</th>
           <th style="width: 10%">申报人</th>
           <th style="width: 17%">CLJL</th>
           <th style="width: 4%"></th>
           <th style="width: 4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            if (empty($_POST['flag'])) {
              echo "<tr><td colspan=12>当前无备件审核历史/</td></tr>";
            }else{
              echo "<tr><td colspan=12>没有符合当前搜索条件的记录，请重新核实。</td></tr>";
            }
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            $apvInfo = "<td><a href='javascript:getApvInfo(\"{$row['apvtime']}\",\"{$row['apvinfo']}\");' class='glyphicon glyphicon-";
            if (!empty($row['apvinfo'])) {
              $apvInfo .= "remove";
            }else{
              $apvInfo .= "ok";
            }
            $apvInfo .= "' style='display:inline'></a></td>";
            $addHtml = 
            "<tr>
                <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0);' onclick='buyList(this,{$row['id']})'></a></td>
                <td>{$row['apvtime']}</td>
                <td>{$row['factory']}</td>
                <td>{$row['depart']}</td>
                <td>{$row['name']}</td>
                <td>{$row['cljl']}</td>".$apvInfo."
                <td><a href='./xlsx/buyApply.php?id={$row['id']}&dpt={$row['depart']}&user={$row['name']}&cljl={$row['cljl']}' class='glyphicon glyphicon-save'></a></td>
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


<!-- 审核弹出框 -->
<div class="modal fade" id="apvInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">审核记录</h4>
      </div>
      <form class="form-horizontal">
        <div class="modal-body">
        <div class="form-group">
            <label class="col-sm-3 control-label">审核时间：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="apvTime" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">审核结果：</label>
            <div class="col-sm-8">
              <label class="radio-inline">
                <input type="radio" name="apvRes" value="同意" disabled> 同意
              </label>
              <label class="radio-inline">
                <input type="radio" name="apvRes" value="需修改" disabled> 需修改
              </label>
            </div>
          </div>
        
          <div class="form-group" id="apvInfo">
            <label class="col-sm-3 control-label">修改意见：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="3" readonly></textarea>
            </div>
          </div>   
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>



<!-- 删除该条列表下所有的备件申报记录 -->
<div class="modal fade"  id="delBuy" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该列表下 <b>所有</b> 备件申报吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="delBuyYes">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<!-- 添加记录不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>



<!--修改备件申报基本信息-->
<div class="modal fade" id="getSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">备件信息</h4>
      </div>
      <form class="form-horizontal" action="controller/gaugeProcess.php" method="post">
        <div class="modal-body">
        <div class="form-group">
            <label class="col-sm-3 control-label">记录编号：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="id" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">存货编码：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="code">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">存货名称：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="name" name="name">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">规格型号：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="no">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">数量：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="num">
            </div>
          </div>
           <div class="form-group">
            <label class="col-sm-3 control-label">单位：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="unit">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">备注描述：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="3" name="info"></textarea>
            </div>
          </div>   
          <div class="modal-footer">
            <input type="hidden" name="flag" value="uptSprById">
            <button type="button" class="btn btn-danger">删除</button>
            <button type="submit" class="btn btn-primary" id="uptYes">修改</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 删除弹出框 -->
<div class="modal fade"  id="delSpr" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该备件申报吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="delSprYes">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<?php  include "./buyJs.php";?>
<script type="text/javascript">
// // 获得审核具体信息
function getApvInfo(time,info){
  $("#apvInfo input[name=apvTime]").val(time);
  if (info == "") {
    $("#apvInfo input[name=apvRes][value=同意]").attr("checked","checked");
  }else{
    $("#apvInfo input[name=apvRes][value=需修改]").attr("checked","checked");
  }
  $("#apvInfo textarea").val(info);
  $('#apvInfo').modal({
    keyboard: true
  });
}

function delSpr(id){
  $('#delSpr').modal({
    keyboard: true
  });
  $("#delSprYes").click(function() {
    location.href="controller/gaugeProcess.php?flag=delSprById&id="+id;
  });            
}


    </script>
  </body>
</html>