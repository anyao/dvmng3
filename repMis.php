<?php 
require_once "model/cookie.php";
checkValidate();
$user=$_SESSION['user'];
$uid=$_SESSION['uid'];
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

<title>维修任务-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-child(1),thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2), thead > tr > th:nth-last-child(3){
      width:4%;
  }
</style>
<link rel="stylesheet" href="bootstrap/css/supTips.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
</head>
<body role="document">

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

<title>维修任务-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
   
</style>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
</head>
<body role="document">

  <?php
    require_once 'model/repairService.class.php';
    require_once 'model/paging.class.php';
    $paging=new paging();
    $paging->pageNow=1;
    $paging->pageSize=18;
    $paging->gotoUrl="repMis.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $repairService=new repairService();
    if (empty($_POST['flag'])) {
      $repairService->getPagingMis($paging);
    }else{
      if (!empty($_POST['devName'])) {
        $devName=$_POST['devName']; 
      }else{
        $devName='';
      }

      if (!empty($_POST['devid'])) {
        $devid=$_POST['devid']; 
      }else{
        $devid='';
      }

      if (!empty($_POST['result'])) {
        $result=$_POST['result'];  
      }else{
        $result='';
      }

      // if (!empty($_POST['time'])) {
      //   $time=$_POST['time'];  
      // }else{
      //   $time='';
      // }

      $repairService->findMis($devName,$devid,$result,$paging);

    }

      
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
        <li><a href="javascript:void(0)">设备购置</a></li>
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
        <li class="dropdown active">
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
       <li><a href="dptUser.php">用户管理</a></li>
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
        <h4>　维修任务</h4>
      </div>
    <table class="table table-striped table-hover">
        <thead><tr>
            <th>　</th><th>　</th><th>编号</th><th>维修设备</th><th>维修人员</th><th>所在部门</th><th>结果</th>
            <th><span class="glyphicon glyphicon-edit"></span></th>
            <th><span class="glyphicon glyphicon-trash"></span></th>
            <th><span class="glyphicon glyphicon-eye-open"></span></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
            for ($i=0; $i < count($paging->res_array); $i++) { 
              $row=$paging->res_array[$i];
              // [id] => 2 [devid] => 1 [err] => test11 [liable] => myself [time] => 2016-05-28 16:00:00 [result] => 0 [infoid] => [name] => 水果 [depart] => 生产部  [seen] => 0
              if($row['seen']==0){
                $seen="<td><span class='glyphicon glyphicon-gift' style='display:inline'></span></td>";
              }else{
                $seen="<td></td>";
              }

              // 对修改按钮的控制
              if ($row['seen']==0 && $uid!=$row['liable']) {
                $uptMis="<td><a href=javascript:updateMis({$row['id']}) class='glyphicon glyphicon-edit'></a></td>";
              }else{
                $uptMis="<td></td>";
              }

              // 该任务是不是当前用户的
              if ($uid==$row['liable']) {
                $me="<td><span class='glyphicon glyphicon-hand-right' style='display:inline;cursor:default'></span></td>";
              }else{
                $me="<td></td>";
              }

              if($row['result']==0){
                $result="<td><a href=javascript:addInfo({$row['id']})>未处理</a></td>";
              }else{
                $result="<td><a href=javascript:getInfo({$row['infoid']})>已处理</td>";
              }

              echo "<tr>{$me}<td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='openInfo(this,{$row['id']})'></a></td>
                         <td>{$row['id']}</td>
                         <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
                         <td class='fxman' liable='{$row['liable']}'>{$row['fxman']}</td>
                         <td>{$row['depart']}</td>{$result}
                         {$uptMis}
                         <td><a href=javascript:delMis({$row['devid']}) class='glyphicon glyphicon-trash'></a></td>
                         {$seen}
                    </tr>
                    <tr style='display:none' id='errInfo-{$row['id']}'>
                      <td colspan='12'> 
                      <p><b>故障现象：</b>{$row['err']}</p>
                      </td>  
                    </tr>";
            }
        ?>
            </tbody>
            </table><div class='page-count'><?php echo $paging->navi; ?></div>
                 
    </div>
    <?php include "repNavi.php" ?>
    
</div>
</div>
  <!-- 删除弹出框 -->
<div class="modal fade"  id="delMis" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要该条维修任务吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="delYes">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>




<!-- 查看任务具体信息 -->
<div class="modal fade" id="getMis" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">任务具体信息</h4>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-md-3 control-label">任务编号：</label>
              <div class="col-md-7">
                <input type="text" class="form-control" name="misid" readonly="readonly">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">执行时间：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="time" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">设备名称：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="name" readonly="readonly">        
              </div>
            </div>
            
             <div class="form-group">
              <label class="col-sm-3 control-label">所在位置：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="locate" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">故障现象：</label>
              <div class="col-sm-7">
                <textarea class="form-control" name="err" readonly="readonly" rows="3"></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="noAgain">不再提醒</button>
              <button class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 修改任务具体信息 -->
<div class="modal fade" id="updateMis" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改任务信息</h4>
        </div>
        <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-md-3 control-label">任务编号：</label>
              <div class="col-md-7">
                <input type="text" class="form-control" name="misid" readonly="readonly">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">故障设备：</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <input type="text" class="form-control" name="name">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    </ul>
                  </div>
                  <!-- /btn-group -->
                </div>
              </div>
            </div>

             <div class="form-group">
              <label class="col-sm-3 control-label">维修人员：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control notNull" name="liable">        
              </div>
            </div>

            <!-- <div class="form-group">
              <label class="col-sm-3 control-label">执行时间：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control notNull datetime" name="time">        
              </div>
            </div> -->

            <div class="form-group">
              <label class="col-sm-3 control-label">故障现象：</label>
              <div class="col-sm-7">
                <textarea class="form-control notNull" name="err" rows="3"></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <input type="hidden" name="flag" value="updateMis">
              <input type="hidden" name="devid">
              <button class="btn btn-primary" id="updateYes">确认修改</button>
              <button class="btn btn-danger" data-dismiss="modal">关闭</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

<div class="modal fade" id="addInfo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">添加维修记录</h4>
        </div>
        <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
          <div class="row">
          <div class="col-md-6 add-left">
            <div class="form-group">
              <label class="col-md-4 control-label">故障设备：</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="name" readonly="readonly">
              </div>
            </div>

             <div class="form-group">
              <label class="col-sm-4 control-label">维修人员：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="liable" readonly>        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">维修时间：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="time" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">故障现象：</label>
              <div class="col-sm-8">
                <textarea class="form-control notNull" name="err" rows="2"  readonly="readonly"></textarea>
              </div>
            </div>
            </div>

            <div class="col-md-6 add-right">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障原因：</label>
              <div class="col-sm-9">
                <textarea class="form-control notNull" name="reason" rows="3"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">解决方案：</label>
              <div class="col-sm-9">
                <textarea class="form-control notNull" name="solve" rows="4"></textarea>
              </div>
            </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInfoByMis">
              <input type="hidden" name="devid">
              <input type="hidden" name="misid">
              <button class="btn btn-primary" id="addInfoYes">确认添加</button>
              <button class="btn btn-danger" data-dismiss="modal">关闭</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="getInfo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">维修记录</h4>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
          <div class="row">
          <div class="col-md-6 add-left">
            <div class="form-group">
              <label class="col-md-4 control-label">故障设备：</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="name" readonly="readonly">
              </div>
            </div>

             <div class="form-group">
              <label class="col-sm-4 control-label">维修人员：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="liable" readonly>        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">维修时间：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="time" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">故障现象：</label>
              <div class="col-sm-8">
                <textarea class="form-control notNull" name="err" rows="2"  readonly="readonly"></textarea>
              </div>
            </div>
            </div>

            <div class="col-md-6 add-right">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障原因：</label>
              <div class="col-sm-9">
                <textarea class="form-control notNull" name="reason" rows="3" readonly="readonly"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">解决方案：</label>
              <div class="col-sm-9">
                <textarea class="form-control notNull" name="solve" rows="4" readonly="readonly"></textarea>
              </div>
            </div>
            </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 信息不完整弹出框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">您所填信息不完整，请补充。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 修改任务弹出框 添加新的设备未完成-->
<div class="modal fade"  id="noAddNew" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前设备添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>
<?php include "message.php" ?>
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="tp/jquery.timers-1.2.js"></script>
<script src="tp/format.js"></script>
<?php include "repJs.php" ?>
<script type="text/javascript">
// window.console = window.console || (function(){ 
//   var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile 
//   = c.clear = c.exception = c.trace = c.assert = function(){}; 
//   return c; 
// })();
var auth='<?php echo "{$_SESSION['permit']}"; ?>';
// 查看任务对应的记录
function getInfo(id){
  $.get("controller/repairProcess.php",{
    flag:'getInfo',
    id:id
  },function(data,success){
    // {"id":"5","devid":"144","liable":"test","err":"test update","reason":"test addinfo","solve":"test addinfo","time":"2016-06-10 15:30:00","name":"2#鼓风机","depart":"风机房","factory":"办公楼"}
    $("#getInfo textarea[name=err]").val(data.err);
    $("#getInfo input[name=time]").val(data.time);
    $("#getInfo input[name=name]").val(data.name+" - "+data.depart+" - "+data.factory);
    $("#getInfo input[name=liable]").val(data.liable);
     $("#getInfo textarea[name=reason]").val(data.reason);
      $("#getInfo textarea[name=solve]").val(data.solve);
    $('#getInfo').modal({
        keyboard: true
    });
  },'json');
}

// 添加维修记录
function addInfo(id){
  $.get("controller/repairProcess.php",{
    flag:'getMis',
    id:id
  },function(data,success){
    $("#addInfo textarea[name=err]").val(data.err);
    $("#addInfo input[name=time]").val(data.time);
    $("#addInfo input[name=name]").val(data.name);
    $("#addInfo input[name=liable]").val(data.liable);
    $("#addInfo input[name=devid]").val(data.devid);
    $("#addInfo input[name=misid]").val(data.id);
  },"json")
 $('#addInfo').modal({
      keyboard: true
  });

}

// 删除维修任务
function delMis(id){
  if (auth==2) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
    $('#delMis').modal({
        keyboard: true
    });
    $("#delYes").click(function(){
      location.href="controller/repairProcess.php?flag=delMis&id="+id;
    });
  }
}

// 修改维修任务确认按钮
$("#updateYes").click(function(){
 var allow_submit = true;
 $("#updateMis .notNull").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
});


// 修改维修任务信息
function updateMis(id){
  $.get("controller/repairProcess.php",{
    flag:'getMis',
    id:id
  },function(data,success){
    $("#updateMis textarea[name=err]").val(data.err);
    $("#updateMis input[name=time]").val(data.time);
    $("#updateMis input[name=name]").val(data.name);
    $("#updateMis input[name=misid]").val(data.id);
    $("#updateMis input[name=liable]").val(data.liable);
    $('#updateMis').modal({
      keyboard: true
    });
  },'json');
}
    
    //时间选择器
    $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd hh:ii', language: "zh-CN", autoclose: true
    });

   
    // // 维修任务实时提醒
    // $(function(){
    //   var countNow="";
    //   if (countNow!=0) {
    //     $('body').everyTime('60s', function(){
    //       var now=new Date().Format("hh:mm");     
    //       var json='';
    //       json=eval("("+json+")");
    //       for (var i = 0; i < json.length; i++) {
    //         var aimed=json[i].time.substr(11,5);
    //         if(now==aimed){
    //           var addHtml="<div class='col-md-12' >"+
    //                       "  <div class='alert alert-warning' id='mesNow'>"+
    //                       "     <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>"+
    //                       "     <strong>您此刻需要开始一项维修任务！</strong><a href=javascript:getMis("+json[i].id+",'now')>点击查看</a>。"+
    //                       "  </div>"+
    //                       "</div>";
    //           $("#message").append(addHtml);
    //           return false;
    //         }
    //       }
    //     });  
    //   }
    // });

    function closeNow(){
      $('body').oneTime('5s',function(){
        $("#misNow").alert('close');
      })
    }

    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    // 任务不再提醒
    $("#noAgain").click(function(){
      var flag=$(this).val();

      if (flag=="noToday") {
        var misid=$(this).parents("div[role=dialog]").find("input[name=misid]").val();
        $.get("controller/repairProcess.php",{
          flag:'noToday',
          id:misid
        },function(data,success){
          if (data=="success") {
            $("#getMis").modal('hide');
            $("#mesToday-"+misid).alert('close');
          }else{
            alert("失败请联系管理员");
          }
        },"text");
      }
    })

    // 获取具体时间的维修任务
    function getMis(id,flag){
        $.get("controller/repairProcess.php",{
          flag:'getMis',
          id:id
        },function(data,success){
          $("#getMis textarea[name=err]").val(data.err);
          $("#getMis input[name=time]").val(data.time);
          $("#getMis input[name=name]").val(data.name);
          $("#getMis input[name=locate]").val(data.depart+" — "+data.factory);
          $("#getMis input[name=misid]").val(data.id);
          $('#getMis').modal({
            keyboard: true
          });
        },"json");
      if (flag=="today") { 
        $("#noAgain").val("noToday");
      }else if(flag=="now"){
        $("#noAgain").detach();
      }
    }

  // 展开任务具体内容
    function openInfo(obj,id){
      $("#errInfo-"+id).toggle();
      $(obj).toggleClass("glyphicon glyphicon-resize-small");
      $(obj).toggleClass("glyphicon glyphicon-resize-full");
      var $ifSee=$(obj).parents("tr").find(".glyphicon-gift");
      var liable=$(obj).parents("tr").find(".fxman").attr("liable");
      var $tdSee=$ifSee.parents("td");
      var uid=<?php echo $uid; ?>;
      if ($ifSee.length>0 && uid==liable) {
        $.get("controller/repairProcess.php",{
          flag:'seen',
          id:id
        },function(data,success){
          if (data=="success") {
            $tdSee.empty();
            var countSee=$("#mesSee strong span").text();
            if (countSee==1) {
              $("#mesSee").alert('close');
            }else{  
              countSee--;
              $("#mesSee strong span").text(countSee);
            }

          }else{
            alert("标志位取消失败，请联系管理员");
          }
        },"text");
        }
    }
    </script>
  </body>
</html>