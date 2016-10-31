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
$paging->gotoUrl="buyInstallHis.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyInstallHis($paging);
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


<div class="modal fade"  id="noInfo" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您尚未完善安装验收单，填写后可下载。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" id="addInfo">添加</button>
        </div>
    </div>
  </div>
</div>

<!-- 添加新设备弹出框 -->
<form class="form-horizontal" method="post" id="formInfo" action="./controller/gaugeProcess.php">
  <div class="modal fade" id="installInfo" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">安装验收新仪表</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="location">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">技术参数：</label>
                <div class="col-sm-9">
                  <textarea type="text" class="form-control" name="paraInfo"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">运行情况：</label>
                <div class="col-sm-9">
                  <textarea type="text" class="form-control" name="runInfo"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="col-sm-3 control-label">安装情况：</label>
                  <div class="col-sm-9">
                    <textarea type="text" class="form-control" name="installInfo"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">结论：</label>
                  <div class="col-sm-9">
                    <textarea type="text" class="form-control" name="conclude"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="modal-footer">
          <input type="hidden" name="devId">
          <input type="hidden" name="flag" value="installInfo">
          <button class="btn btn-primary" id="yesInstall">确定添加</button>
          <button class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form>

<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件验收记录</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>验收时间</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>申报部门</th>
            <th style="width:4%"></th><th style="width:4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无历史安装验收记录</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
             // [sprid] => 2 [devid] => 198 [trsftime] => [name] => 超声波流量计 [code] => 510740110011 [number] => 2 
             // [state] => 正常 [depart] => 竖炉车间 [factory] => 新区竖炉
            if ($row['state'] == "正常") {
              $url = "usingSon";
              $icon = "glyphicon glyphicon-play-circle";
            }else{
              $url = "spare";
              $icon = "glyphicon glyphicon-briefcase";
            }
            $addHtml = 
            "<tr>
                <td>{$row['trsftime']}</td>
                <td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['sprid']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['number']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td><a class='{$icon}' href='./{$url}.php?id={$row['devid']}' style='display:inline;'></a></td>
                <td><a class='glyphicon glyphicon-save' href='javascript:downXls({$row['devid']})' style='display:inline;'></a></td>
             </tr>";
             echo "$addHtml";
            // <td><a class='glyphicon glyphicon-save' href='./xlsx/sprInstall.php?id={$row['id']}' style='display:inline;'></a></td>
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
$("#yesInstall").click(function(){
  var allow_submit = true;
  $("#installInfo input,#installInfo textarea").each(function() {
    // alert($(this))
    if ($(this).val() == "") {
      allow_submit = false;
      $("#failAdd").modal({
        keyboard:true
      });
    }
  });
  return allow_submit;
});

$("#addInfo").click(function(){
  $("#noInfo").modal('hide');
  $("#installInfo").modal({
    keyboard:true
  });
});

function downXls(id){
  $.get("./controller/gaugeProcess.php",{
    flag:'installXls',
    devid:id
  },function(data,success){
    if (data == 0) {
      $("#installInfo input[name=sprId]").val(id);
      $("#noInfo").modal({
        keyboard:true
      });
    }else{
      // 已经有安装验收单啦
      location.href="./xlsx/buyInstall.php?devid="+id;
    }
  },"text");
}
    </script>
  </body>
</html>