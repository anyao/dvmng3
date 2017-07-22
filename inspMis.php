<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];

require_once "./model/dptService.class.php";
$dptService = new dptService();

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

<title>点检任务-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1), thead > tr > th:nth-last-child(2){
      width:4%;
  }

  thead > tr > td:first-child{
    display:none;
  }
</style>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/supTips.css">
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
  include "message.php";

  require_once 'model/inspectService.class.php';
  require_once 'model/paging.class.php';
  $paging=new paging();
  $paging->pageNow=1;
  $paging->pageSize=18;
  $paging->gotoUrl="inspMis.php";
  if (!empty($_GET['pageNow'])) {
    $paging->pageNow=$_GET['pageNow'];
  }

  $inspectService=new inspectService();
  if (empty($_POST['flag'])) {
    $inspectService->getPagingMis($paging);
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
    $inspectService->findMis($devid,$name,$time,$paging);
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
        <li class="dropdown  active">
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
        <h4>　设备巡检计划</h4>
      </div>
    <table class="table table-striped table-hover">
        <thead><tr>
            <th style='width:4%'></th>
            <th>下一次巡检时间</th><th>检查类型</th><th>设备名称</th><th>巡检周期</th><th>巡检部门</th><th>设备使用部门</th>
            <th style='width:4%'></th><th><span class='glyphicon glyphicon-thumbs-up' id='runWellAll' style='display:none'></span></th>
        </tr></thead>
        <tbody class="tablebody">     
     <?php
        $addHtml = "";
        for ($i=0; $i < count($paging->res_array); $i++) { 
          $row = $paging->res_array[$i];
           // [id] => 35 [devid] => 45 [cyc] => 480 [nxt] => 2016-12-03 16:00:00 [type] => 1 [inspDpt] => 2 [factory] => 新区竖炉 [depart] => 竖炉车间 [inspdpt] => 竖炉车间 [inspfct] => 新区竖炉
          $cyc = $inspectService->transTime($row['cyc']);
          if ($row['type'] == 1) {
            $type = "定期周检";
          }else if ($row['type'] == 2) {
            $type = "临时抽检";
          }
          $addHtml .= "<tr><td><span class='glyphicon glyphicon-unchecked' style='display:inline'><span></td>
                          <td>{$row['nxt']}</td><td>$type</td>
                          <td><a href='./using.php?id={$row['devid']}'>{$row['name']}</a></td>
                          <td>$cyc[0]$cyc[1]</td><td>{$row['inspfct']}{$row['inspdpt']}</td>
                          <td>{$row['factory']}{$row['depart']}</td>";
          if (in_array(1,$_SESSION['funcid']) || $_SESSION['user'] == "admin") {
            $addHtml .= "<td><a href=\"javascript:getMis('{$row['id']}');\" class='glyphicon glyphicon-edit'></a></td>";
          }
          $addHtml .= "<td><a href=\"javascript:addRes('{$row['devid']});\" class='glyphicon glyphicon-thumbs-up')></a></td></tr>";
        }
        echo "$addHtml";
        
     ?>
        </tbody></table><div class='page-count'><?php echo $paging->navi;?></div>         
    </div>
    <?php include "inspNavi.php" ?>
</div>
</div>

<div class="modal fade" id="getMis" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">设备巡检基本信息</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">设备名称：</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name='name' readonly>     
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">下一次巡检：</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="nxt" readonly>     
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">巡检类型：</label>
            <div class="col-sm-7">
              <label class="radio-inline">
                <input type="radio" name="type" value="1"> 定期周检
              </label>
              <label class="radio-inline">
                <input type="radio" name="type" value="2"> 临时抽检
              </label>
            </div>
          </div>
          
          

          <div class="form-group">
            <label class="col-sm-3 control-label">巡检周期：</label>
            <div class="col-sm-7">
              <div class="input-group">
                <input type="text" class="form-control" name='cyc'>
                <div class="input-group-btn">
                  <button class="btn btn-default cycUnit" type="button" ></button>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span></button>
                 <ul class="dropdown-menu dropdown-menu-right" role="menu">
                   <li><a href="javascript:cycUnit('分钟');">分钟</a></li>
                   <li><a href="javascript:cycUnit('小时');">小时</a></li>
                   <li><a href="javascript:cycUnit('天');">天</a></li>
                   <li><a href="javascript:cycUnit('月');">月</a></li>
                   <li><a href="javascript:cycUnit('年');">年</a></li>
                 </ul>
                </div>
              </div> 
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">巡检部门：</label>
            <div class="col-sm-7">
              <div class="input-group">
              <input type="text" name="dptName" class="form-control" style='width: 101%'>
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
          
          <div class="modal-footer">
            <input type="hidden" name="flag" value="uptMis">
            <input type="hidden" name="inspDpt">
            <input type="hidden" name="id">
            <input type="hidden" name="unit">
            <button type="button" class="btn btn-danger" id='getDel'>删除</button>
            <button type="submit" class="btn btn-primary" id="uptMisYes">修改</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>


<!-- 删除弹出框 -->
<div class="modal fade"  id="delMis" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要该条巡检任务吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" id="del">删除</button>
        </div>
    </div>
  </div>
</div>

  <!-- 时间添加失败弹出框 -->
<div class="modal fade"  id="noTime" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前时间添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 修改任务弹出框 添加新的设备未完成-->
<div class="modal fade"  id="noDev" >
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

<?php include "inspJs.php";?>
<script type="text/javascript">
var session = <?php echo json_encode($_SESSION,JSON_UNESCAPED_UNICODE); ?>;
var user = session.user;
function allow_enter(funcid){
  var allow = $.inArray(funcid.toString(),session.funcid);
  if (user == "admin") {
    allow = 0;
  }
  return allow;
}

// 多选按钮
$(".tablebody").on("click","tr>td:first-child>span",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked");
    $(this).toggleClass("glyphicon glyphicon-check");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $("#runWellAll").show();
    }else{
      $("#runWellAll").hide();
    }
});

    // 修改任务弹出框中确认添加设备按钮
    $("#getMis #yesDev").click(function(){
      if($("#getMis input[name=devName]").val().length>0){
        var nameDev=$("#getMis input[name=devName]").val();
        var idDev=$("#getMis input[name=devName]").attr("data-id");
        var addHtml="<span class='badge'>"+nameDev+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='dev[]' value="+idDev+"></span> "
        $("#getMis #forDev").append(addHtml);
        $("#getMis input[name=devName]").val("");
      }else{
        $('#noDev').modal({
          keyboard: true
        });
      }  
    });

    

     //时间选择器
      $(".datetime").datetimepicker({
        format: 'hh:ii', language: "zh-CN", autoclose: true,startView:1,minView:0
      });
    // 已确定添加的设备删除
    $(document).on("click",".glyphicon-remove",delDeved);
    function delDeved(){
      $(this).parents("span").detach();
    }
   
   // 确认修改按钮
   $("#uptMisYes").click(function(){
     var allow_submit = true;
     var forDev=$("#getMis #forDev input").length;
     var forTime=$("#getMis input[name=start]").val().length;
    if (forDev==0 || forTime==0) {
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     return allow_submit;
   });
  

// 删除巡检任务
$("#getDel").click(function(){
  var misid = $(this).attr('misid');
  $('#delMis').modal({
    keyboard: true
  });
});

// function delMis(arr){
//   $("#del").click(function() {
//     location.href="controller/inspectProcess.php?flag=delMis&misid="+arr;
//   });            
// }

// 巡检基本信息模态框
function getMis(misid){
  $.get("controller/inspectProcess.php",{
    misid:misid,
    flag:"getMis"
  },function(data){
    $("#getMis input[name=nxt]").attr("diff",data.dateInstall);
     $("#getMis input[name=name]").val(data.name);
     $("#getMis input[name=id]").val(data.id);
     $("#getMis input[name=nxt]").val(data.nxt);
     $("#getMis input[name=cyc]").val(data.cyc[0]);
     $("#getMis input[name=dptName]").val(data.dpt);
     $("#getMis input[name=inspDpt]").val(data.inspDpt);
     $("#getMis input[name=type][value="+data.type+"]").attr("checked","checked");
     $("#delMis").attr("misid",misid);
     cycUnit(data.cyc[1]);
     $("#getMis input[name=type]").val(data.type);
     $('#getMis').modal({
          keyboard: true
     });
  },"json");
}

function   addMinutes(date,minutes)  
{   

  minutes=parseInt(minutes)*60*1000;  

  var  interTimes=minutes*60*1000;

  interTimes=parseInt(interTimes);  

  return   new   Date(Date.parse(date)+interTimes);  

}

// 计算下一次巡检时间
$("#getMis input[name=cyc]").keyup(function(){
  if ($(this).val().length != 0) {
    // var cyc = parseInt($(this).val());
    // alert($.type(cyc));
    var cyc = eval($(this).val() * $("#getMis input[name=unit]").val()*60);
    var now = new Date();
    var test = new Date();
    test.setTime(now.getTime() + cyc);

    alert(test);
  }
  // var cyc = parseInt($(this).val()) * parseInt();
  // // 安装日期距今的时间差
  // var diff = parseInt($("#getMis input[name=nxt]").attr("diff"));

  // var remain = diff % cyc;
  // alert(remain);
  // remain = cyc - remain;
  // var nxt = 


});

// 巡检周期单位修改
function cycUnit(unit){
  var times = "";
  switch (unit){
    case '分钟':
      times = 1;
      break;
    case '小时':
      times = 60;
      break;
    case '天':
      times = 1440;
      break;
    case '月':
      times = 43200;
      break;
    case '年':
      times = 525600;
      break;
  }
  $("#getMis button.cycUnit").text(unit);
  $("#getMis input[name=unit]").val(times);
}

$("#getMis input[name=dptName]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:2,
    data: {
         'value':<?php $dptAll = $dptService->getDpt();
                       echo "$dptAll"; ?>
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
   console.log('onSetSelectValue: ', keyword, data);
   var dptid = $(this).attr("data-id");
   $(this).parents("form").find("input[name=inspDpt]").val(dptid);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});



$("input[name=devName]").bsSuggest({
    allowNoKeyword: false,
     showBtn: false,
    indexKey: 0,
    indexId:1,
    inputWarnColor: '#f5f5f5',
    data: {
       'value':<?php
                $devAll=$inspectService->getUsingAll();
                echo "$devAll";
               ?>,
        // 'defaults':'没有相关设备请另查询或添加新的设备'
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

    
    </script>
  </body>
</html>