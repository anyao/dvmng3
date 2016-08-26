<?php 
require_once "../model/cookie.php";
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

<title>日常巡检-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
   .last-th{
      width: 4%;
   }
</style>
<link rel="stylesheet" href="bootstrap/css/treeview.css">
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
require_once "../model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
  <?php
    require_once '../model/inspectService.class.php';
    require_once '../model/paging.class.php';

    $paging=new paging();
    $paging->pageNow=1;
    $paging->pageSize=19;
    $paging->gotoUrl="inspList.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }
    $inspectService=new inspectService();
    if (empty($_POST['flag'])) {
      $inspectService->getPagingInfo($paging);
    }else{
      $begin=$_POST['begin'];
      $depart=$_POST['depart']; 
      $end=$_POST['end'];
      $inspectService->findInfo($begin,$depart,$end,$paging);
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
            <li><a href="spareList.php">备品备件</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="devPara.php">属性参数</a></li>
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
            <h4>　日常巡检记录</h4>
        </div>
    <table class="table table-striped table-hover">
            <thead><tr>
                <th>记录编号</th><th>点检时间</th><th>巡检设备</th><th>巡检结果</th><th>巡检人</th>
                <th class="last-th"></th><th class="last-th"></th><th class="last-th"></th>
              </tr></thead>
            <tbody class="tablebody">           
             <?php
                for ($i=0; $i < count($paging->res_array); $i++) { 
                  $row=$paging->res_array[$i];
                    echo "<tr><td>{$row['id']}</td>
                               <td>{$row['time']}</td>
                               <td><a href='using.php?id={$row['devid']}'>{$row['name']}</td>
                               <td>{$row['result']}</td>
                               <td>{$row['liable']}</td>
                               <td><span class='glyphicon glyphicon-triangle-top' data-toggle='tooltip' data-placement='left' title='{$row['info']}'></span></td>
                               <td><a href=\"javascript:updateInfo({$row['id']});\" class='glyphicon glyphicon-edit'></a></td>
                               <td><a href=\"javascript:delInfo({$row['id']});\" class='glyphicon glyphicon-trash'></a></td>
                          </tr>";
                }
                echo "</tbody></table><div class='page-count'>$paging->navi</div>";
             ?>
                 
    </div>
    <?php include "inspNavi.php" ?>
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
          <br>确定要这条记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<!-- 添加巡检记录不完整提示框 -->
<div class="modal fade"  id="failErr" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
       </div>
       <div class="modal-body"><br/>
          <div class="loginModal">请先完成当前异常设备添加。</div><br/>
       </div>
       <div class="modal-footer">  
        <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 添加个别保养设备的记录 -->
<!-- <div class="modal fade" id="mainInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">保养备注</h4>
      </div>
      <form class="form-horizontal" id="mainForm">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">基本描述：</label>
            <div class="col-sm-7">
              <textarea class="form-control" rows="2" name="info"></textarea>
            </div>
          </div>  
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addInfo">
          <button type="button" class="btn btn-primary" id="addMain">确认添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </form>
      </div>
    </div>
  </div> -->

<!-- 修改巡检记录 -->
<div class="modal fade" id="updateInfo">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">修改记录信息</h4>
        </div>
        <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">点检设备：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="name" disabled>
                <input type="hidden" name="devid">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">点检时间：</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control datetime" name="time" readonly="readonly">        
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">点检结果：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="result" value="正常"> 正常
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="需维修"> 需维修
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="保养" > 保养
              </label>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">点检人员：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="liable">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="2" name="info"></textarea>
              </div>
            </div>  
            </div> 
            <div class="modal-footer">
              <input type="hidden" name="flag" value="updateInfo">
              <input type="hidden" name="id">
              <button class="btn btn-primary" id="yesUpdate">确认修改</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 添加异常设备弹出框 添加新的异常设备未完成-->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前信息添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>



    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    
    <script type="text/javascript">
    window.console = window.console || (function(){ 
      var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile 
      = c.clear = c.exception = c.trace = c.assert = function(){}; 
      return c; 
    })();

    // 个别设备保养备注确认提交按钮
    // $("#addMain").click(function(){
    //   var id=$("#mainInfo input[name=devid]").val();
    //   var allow_submit = true;
    //  $("#mainInfo .modal-body textarea").each(function(){
    //     if($(this).val()==""){
    //       $('#failAdd').modal({
    //           keyboard: true
    //       });
    //       allow_submit = false;
    //     }else{
    //       $.post("../controller/inspectProcess.php",$("#mainForm").serialize(),function(data,success){
    //         if (data!="fail") {
    //           $("#mainInfo").modal('hide');
    //           $("#haveErr span input[value="+id+"]").parents("span.badge").detach();
    //         }else{
    //           alert("添加保养备注失败，请联系管理员。0310-5178939。");
    //         }
    //       });
    //     }
    //  });
    //  return allow_submit;
    // });

  
      //时间选择器
      $(".datetime").datetimepicker({
        format: 'yyyy-mm-dd hh:ii', language: "zh-CN", autoclose: true
      });

   function delInfo(id){
      $('#delInfo').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/inspectProcess.php?flag=delInfo&id="+id;
      });            
    }
    
    // 更新巡检记录函数
    function updateInfo(id){ 
      $.get("../controller/inspectProcess.php",{
        id:id,
        flag:"getInfo"
      },function(data,success){
        var time=data.time;
        var id=data.id;
        var result=data.result;
        var liable=data.liable;
        var info=data.info;
        var devid=data.devid;
        var name=data.name;

        $("#updateInfo input[name=time]").val(time);
        $("#updateInfo input[name=id]").val(id);
        $("#updateInfo input[name=result][value="+result+"]").attr("checked",true);
        $("#updateInfo input[name=liable]").val(liable);
        $("#updateInfo textarea[name=info]").val(info);
        $("#updateInfo input[name=devid]").val(devid);
        $("#updateInfo input[name=name]").val(name);
        
       $('#updateInfo').modal({
          keyboard: true
        });
      },"json");
    }

    // 修改信息提交按钮
    $("#yesUpdate").click(function(){
      var allow_submit = true;
     $("#updateInfo input,#updateInfo textarea").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     });
     return allow_submit;
    });


    

    </script>
    <?php 
    include "inspJs.php" 
    ?>

  </body>
</html>