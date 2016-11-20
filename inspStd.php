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

<title>点检标准-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1), thead > tr > th:nth-last-child(2){
      width:4%;
  }

  thead > tr > th:nth-child(2),thead > tr > th:nth-child(3),thead > tr > th:nth-child(4),thead > tr > th:nth-child(5){
      width:15%;
  }

  thead > tr > th:nth-child(1){
    width: 5%;
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
<?php 
require_once "model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
  <?php
    require_once 'model/inspectService.class.php';
    require_once 'model/paging.class.php';
    $paging=new paging();
    $paging->pageNow=1;
    $paging->pageSize=18;
    $paging->gotoUrl="inspStd.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $inspectService=new inspectService();
    if (empty($_POST['flag'])) {   
      $inspectService->getPagingStd($paging);
    }else{
      if(!empty($_POST['code'])){
        $code=$_POST['code'];
      }else{
        $code='';
      }
      if(!empty($_POST['depart'])){
        $depart=$_POST['depart'];
      }else{
        $depart='';
      }
      if(!empty($_POST['devid'])){
        $devid=$_POST['devid'];
      }else{
        $devid='';
      }
      if(!empty($_POST['name'])){
        $name=$_POST['name'];
      }else{
        $name='';
      }
      $inspectService->findStd($code,$depart,$devid,$name,$paging);
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
        <h4>　点检标准</h4>
      </div>
    <table class="table table-striped table-hover">
            <thead><tr>
                <th>　</th><th>记录编号</th><th>设备编号</th><th>巡检设备</th><th>所在部门</th><th>点检内容</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
              </tr></thead>
            <tbody class="tablebody">                
           <?php
              for ($i=0; $i < count($paging->res_array); $i++) { 
                      $row=$paging->res_array[$i];

                      echo "<tr><td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='openInfo(this,{$row['id']})'></a></td>
                                 <td>{$row['id']}</td>
                                 <td>{$row['code']}</td>
                                 <td>{$row['name']}</td>
                                 <td>{$row['depart']}</td>
                                 <td>{$row['iden']}</td>
                                 <td><a href=javascript:updateStd({$row['id']}) class='glyphicon glyphicon-edit'></a></td>
                                 <td><a href=javascript:delStd({$row['id']}) class='glyphicon glyphicon-trash'></a></td>
                            </tr>
                            <tr style='display:none' id='stdInfo-{$row['id']}'>
                              <td colspan='12'> 
                              <p><b>具体标准：</b>{$row['info']}</p>
                              </td>  
                            </tr>";
                    }
                    echo "</tbody></table>";
                    echo "<div class='page-count'>$paging->navi</div>"
           ?>               
    </div>
    <?php include "inspNavi.php" ?>
  </div>
</div>
<!-- 删除弹出框 -->
<div class="modal fade"  id="delStd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该条巡检标准记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="stdDelYes">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 添加周期巡检任务 -->
<div class="modal fade" id="addMission">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加周期巡检任务</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">周期巡检时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="" placeholder="请设置周期天数间隔">
                <!-- <span class="input-group-addon">天</span> -->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检设备：</label>
              <div class="col-sm-6">
                <div class="input-group">
                  <input type="text" class="form-control" id="findName" name="devCode">
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
              <label class="col-sm-3 control-label">任务描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请简要说明该周期巡检任务内容"></textarea>
              </div>
            </div>   
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 添加巡检类型 -->
<div class="modal fade" id="addType">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新的巡检类型</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检类型：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="" placeholder="请添加新的巡检类型名称">
                <!-- <span class="input-group-addon">天</span> -->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">类型描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请简要说明该巡检类型信息..."></textarea>
              </div>
            </div>   
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 在巡检类型下添加新的巡检内容 -->
<div class="modal fade" id="addTypeInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">在巡检类型下添加具体内容项目</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检类型：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" class="form-control" id="findName" name="devCode">
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
              <label class="col-sm-3 control-label">新的巡检内容：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter" placeholder="请输入新的巡检内容" >
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入该巡检内容所要巡检的基本信息..."></textarea>
              </div>
            </div>     
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>


<!-- 修改巡检标准-->
<div class="modal fade" id="stdUpdt">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改巡检标准</h4>
        </div>
        <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-md-3 control-label">设备编号：</label>
              <div class="col-md-7">
                  <input type="text" class="form-control" name="code" readonly>        
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-3 control-label">巡检设备：</label>
              <div class="col-md-7">
                  <input type="text" class="form-control" name="name" readonly>        
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">所在部门：</label>
              <div class="col-md-7">
                <input type="text" class="form-control" name="depart" readonly> 
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">点检内容：</label>
              <div class="col-md-7">
                <input type="text" class="form-control" name="iden">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">具体标准：</label>
              <div class="col-md-7">
                <textarea class="form-control" rows="4" name="info"></textarea>
              </div>
            </div>   
            <div class="modal-footer">
              <input type="hidden" name="flag" value="updateStd">
              <input type="hidden" name="id">
              <button type="submit" class="btn btn-primary" id="stdYes">确认修改</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
            </div>
          </form>
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

<!-- 权限警告 -->
<div class="modal fade"  id="failAuth">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您无权限进行此操作。</div><br/>
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
    <?php include "inspJs.php" ?>
    <script type="text/javascript">

   // 删除巡检标准记录
   function delStd(id){
    if (auth==2) {
          $('#failAuth').modal({
            keyboard: true
          });
      }else{
        var id =id;
        $('#delStd').modal({
          keyboard: true
        });
        $("#stdDelYes").click(function() {
          location.href="controller/inspectProcess.php?flag=delStd&id="+id;
        });            
      }
    }

    function updateStd(id){ 
      $.get("controller/inspectProcess.php",{
        id:id,
        flag:"getStd"
      },function(data,success){
        var id=data.id;
        var iden=data.iden;
        var info=data.info;
        var name=data.name;
        var code=data.code;
        var depart=data.depart;
        $('#stdUpdt input[name="iden"]').val(iden);
        $('#stdUpdt textarea').text(info);
        $('#stdUpdt input[name="name"]').val(name);
        $('#stdUpdt input[name="id"]').val(id);
        $('#stdUpdt input[name="code"]').val(code);
        $('#stdUpdt input[name="depart"]').val(depart);
      },"json");
       $('#stdUpdt').modal({
        keyboard: true
      });
    }

   


    </script>
  </body>
</html>