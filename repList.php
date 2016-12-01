<?php 
require_once "model/cookie.php";
checkValidate();
$user=$_SESSION['user'];
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

<title>维修记录-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-child(1),thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
    width:4%;
  }
</style>
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/supTips.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
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
    $paging->gotoUrl="repList.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $repairService=new repairService();
    if (empty($_POST['flag'])) {
      $repairService->getPagingInfo($paging);
    }else{
      if (!empty($_POST['devid'])) {
        $devid=$_POST['devid'];  
      }else{
        $devid='';
      }

      if (!empty($_POST['name'])) {
        $name=$_POST['name'];  
      }else{
        $name='';
      }
       
      if (!empty($_POST['time'])) {
        $time=$_POST['time'];  
      }else{
        $time='';
      }
       
      if (!empty($_POST['liable'])) {
        $liable=$_POST['liable'];  
      }else{
        $liable='';
      }
      $repairService->findInfo($devid,$name,$time,$liable,$paging);

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
        <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button">设备购置 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="buyGauge.php">仪表备件申报</a></li>
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
        <?php if (in_array($_SESSION['permit'],array("a","b",0,2))) {
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
        <h4>　维修记录</h4>
      </div>
     <table class="table table-striped table-hover">
        <thead><tr>
            <th>　</th><th>编号</th><th>维修设备</th><th>维修时间</th><th>维修人员</th><th>所在部门</th><th>所在分厂</th>
            <th><span class="glyphicon glyphicon-edit"></span></th>
            <th><span class="glyphicon glyphicon-trash"></span></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
        // [id] => 6 [devid] => 138 [liable] => me [err] => test update [reason] => test addinfo [solve] => test addinfo 
        // [time] => 2016-06-07 16:38:09 [name] => 1鼓风机 [depart] => 风机房 [factory] => 办公楼
            for ($i=0; $i < count($paging->res_array); $i++) { 
              $row=$paging->res_array[$i];
              echo "<tr>
                   <td>
                   <a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='openInfo(this,{$row['id']})'></a>
                   </td>
                   <td>{$row['id']}</td>
                   <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
                   <td>{$row['time']}</td>
                   <td>{$row['liable']}</td>
                   <td>{$row['depart']}</td><td>{$row['factory']}</td>
                   <td><a href=javascript:updateInfo({$row['id']},{$row['devid']}) class='glyphicon glyphicon-edit'></a></td>
                   <td><a href=javascript:delInfo({$row['id']}) class='glyphicon glyphicon-trash'></a></td>
                    </tr>
                    <tr style='display:none' id='errInfo-{$row['id']}'>
                      <td colspan='12'> 
                      <p><b>故障现象：</b>{$row['err']}</p>
                      <p><b>故障原因：</b>{$row['reason']}</p>
                      <p><b>解决方案：</b>{$row['solve']}</p>
                      </td>  
                    </tr>";
            }
            echo "</tbody></table><div class='page-count'>$paging->navi</div>"
        ?>
                 
    </div>
<?php include "repNavi.php" ?>

</div>
</div>
  <!-- 删除弹出框 -->
<div class="modal fade"  id="delInfo" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要该条维修记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="delYes">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<!-- 添加周期巡检任务 -->
<div class="modal fade" id="addMis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新的维修记录</h4>
      </div>
      <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
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
              <label class="col-sm-3 control-label">故障现象：</label>
              <div class="col-sm-7">
                <textarea class="form-control" rows="2" name="err" placeholder="请简要说明该设备故障现象"></textarea>
              </div>
            </div>   
            
            <div class="form-group">
              <label class="col-sm-3 control-label">维修人员：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="liable" placeholder="请输入维修人员">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">维修时间：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control datetime" name="time" placeholder="请选择维修时间" readonly="readonly">
              </div>
            </div>
            
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addMis">
                <input type="hidden" name="devid">
                <button type="submit" class="btn btn-primary" id="addYes">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
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
<div class="modal fade" id="updateInfo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改维修记录</h4>
        </div>
        <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
          <div class="row">
          <div class="col-md-6 add-left">
            <div class="form-group">
              <label class="col-sm-4 control-label">记录编号：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="id" readonly="readonly">        
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">故障设备：</label>
              <div class="col-sm-8">
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
              <label class="col-sm-4 control-label">维修人员：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="liable">        
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-4 control-label">故障现象：</label>
              <div class="col-sm-8">
                <textarea class="form-control" name="err" rows="2"></textarea>
              </div>
            </div>
            </div>

            <div class="col-md-6 add-right">

            <div class="form-group">
              <label class="col-sm-3 control-label">维修时间：</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control datetime" name="time">        
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">故障原因：</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="reason" rows="2"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">解决方案：</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="solve" rows="3"></textarea>
              </div>
            </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="updateInfo">
              <input type="hidden" name="devid">
              <input type="hidden" name="o_devid">
              <button class="btn btn-primary" id="updateYes">确认修改</button>
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
              <label class="col-sd-4 control-label">故障设备：</label>
              <div class="col-sm-8">
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

<div class="modal fade"  id="failDev" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">请重新选择设备(需从下拉菜单中选择)。</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
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

<!-- <div class="row" id="message"> -->
<?php
 // $countSee=$repairService->getMisCount();

 // $today=time();
 // $arrNow=$repairService->getMisNow($today);
 // $countNow=count($arrNow);


 // if ($countSee!=0) {
 //   echo "<div class='col-md-12' >
 //          <div class='alert alert-warning' id='mesSee'>
 //             <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>
 //             <strong>您有 <span>$countSee</span> 项新的维修记录！</strong><a href='repMis.php'>点击查看</a>。
 //          </div>
 //        </div>";
 // }
 // if ($countNow!=0) {
 //   $jsonNow=json_encode($arrNow,JSON_UNESCAPED_UNICODE);
 //   for ($i=0; $i < $countNow; $i++) { 
 //    if($arrNow[$i]['today']!=1){
 //      $time=date("H:i",strtotime($arrNow[$i]['time']));
 //      echo "<div class='col-md-12'>
 //            <div class='alert alert-warning' id='mesToday-{$arrNow[$i]['id']}'>
 //               <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>
 //               <strong>您今天 <span>$time</span> 有维修记录！</strong><a href=javascript:getMis({$arrNow[$i]['id']},'today')>点击查看</a>。
 //            </div>
 //          </div>";
 //    }
 //   }
 // }

?>

<?php 
include "message.php";
 ?>
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="tp/jquery.timers-1.2.js"></script>
<script src="tp/format.js"></script>
<?php include "repJs.php" ?>
<script type="text/javascript">
// 右侧树形导航
$("#tree-open").click(
function () {
    $(".tree").slideDown();
    $(".close-button").slideDown();
    $(".sidebar-module").slideUp();
    //树形导航
    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
    var children = $(this).parent('li.parent_li').find(' > ul > li');
    if (children.is(":visible")) {
      children.hide('fast');
      $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
    } else {
      children.show('fast');
      $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
    }
    e.stopPropagation();
    });
});

// 树形导航收起按钮
$(".close-button").click(function(){
  $(".tree").slideUp();
  $(".sidebar-module").slideDown();
  $(this).slideUp();
});

// 查看任务对应的记录
function updateInfo(id,devid){
  $.get("controller/repairProcess.php",{
    flag:'getInfo',
    id:id
  },function(data,success){
    $("#updateInfo input[name=id]").val(data.id);
    $("#updateInfo textarea[name=err]").val(data.err);
    $("#updateInfo input[name=time]").val(data.time);
    $("#updateInfo input[name=name]").val(data.name);
    $("#updateInfo input[name=liable]").val(data.liable);
    $("#updateInfo textarea[name=reason]").val(data.reason);
    $("#updateInfo textarea[name=solve]").val(data.solve);
     $("#updateInfo input[name=o_devid]").val(devid);
    $('#updateInfo').modal({
        keyboard: true
    });
  },'json');
}



// 删除维修记录
function delInfo(id){
  $('#delInfo').modal({
      keyboard: true
  });
  $("#delYes").click(function(){
    location.href="controller/repairProcess.php?flag=delInfo&id="+id;
  });
}

// 修改维修记录确认按钮
$("#updateYes").click(function(){
 var allow_submit = true;
 $("#updateInfo .form-control").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
});







  

    //时间选择器
    $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd hh:ii', language: "zh-CN", autoclose: true
    });

    
    // 维修记录实时提醒
    // $(function(){
    //   var countNow="<?php;?>";
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
    //                       "     <strong>您此刻需要开始一项维修记录！</strong><a href=javascript:getMis("+json[i].id+",'now')>点击查看</a>。"+
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

    // 获取具体时间的维修记录
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

    // 展开设备巡检的具体标准
    function openInfo(obj,id){
      $("#errInfo-"+id).toggle();
      $(obj).toggleClass("glyphicon glyphicon-resize-small");
      $(obj).toggleClass("glyphicon glyphicon-resize-full");
      var $ifSee=$(obj).parents("tr").find(".glyphicon-gift");
      var $tdSee=$ifSee.parents("td");
      if ($ifSee.length>0) {
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