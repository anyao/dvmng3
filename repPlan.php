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

<title>检修计划-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1), thead > tr > th:nth-last-child(2){
      width:4%;
  }

   thead > tr > th:nth-child(5),thead > tr > th:nth-child(2),thead > tr > th:nth-child(3),thead > tr > th:nth-child(4),thead > tr > th:nth-child(1){
      width:10%;
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
    $paging->gotoUrl="inspMis.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $repairService=new repairService();
    // $repairService->getPagingMis($paging);
  ?>
  <?php include "message.php" ?>
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
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">日常巡检 <span class="caret"></span></a>
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
        <h4>　检修计划</h4>
      </div>
    <table class="table table-striped table-hover">
        <thead><tr>
            <th>任务编号</th><th>点检责任人</th><th>间隔天数</th><th>点检时间</th><th>所在部门</th><th>点检路线</th><th>　</th><th>　</th>
        </tr></thead>
        <tbody class="tablebody">     
      <tr><td colspan="12">建设中，敬请期待！！</td></tr>
          </tbody></table><div class='page-count'><?php echo "$paging->navi"; ?></div>
                 
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
          <br>确定要该条巡检任务吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 搜索符合条件的供应商 -->
<div class="modal fade" id="findStd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">按条件搜索设备巡检标准</h4>
      </div>
      <div class="modal-body">
         <form class="form-horizontal"> 
            <div class="form-group">
              <label class="col-sm-3 control-label">设备名称：</label>
              <div class="col-sm-6">
               <input type="text" class="form-control datetime" readonly="readonly">
             </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">设备编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="通过设备品牌来搜索供应商">
            </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="通过设备型号来搜索供应商">
            </div>
           </div>

         </form>
      <div class="modal-footer" style="padding-right:40px;">
          <button type="button" class="btn btn-primary">搜索</button>
      </div>
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


<!-- 修改供应商信息-->
<div class="modal fade" id="updateMis"  role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改点检路线</h4>
        </div>
        <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 col-sm-offset-1 control-label">任务编号：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="misid" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-sm-offset-1 control-label">责任人：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="liable">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-sm-offset-1 control-label">间隔天数：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="interval">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-sm-offset-1 control-label">点检时间：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="time">
              </div>
            </div>
            <div class="form-group" id="forLoc">
              <label class="col-sm-2 col-sm-offset-1 control-label">点检设备：</label>
              <div class="col-sm-8" style="padding-top: 7px">
              </div>
            </div>
            <div class='form-group' id='addNew' style="display:none">
              <label class='col-sm-2 col-sm-offset-1 control-label'>新添加：</label>
                <div class='col-sm-7'>
              <div class='input-group'>
                <input type='text' class='form-control' name='devid'>
                <div class='input-group-btn'>
                  <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                    <span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                  </ul>
                </div>
              </div>
            </div>
              <div style='margin-top: 10px;font-size:15px;'>
               <a href=javascript:void(0) class='glyphicon glyphicon-ok' id='yesNew' style='text-decoration: none;margin-right: 7px'></a>
               <a href=javascript:void(0) class='glyphicon glyphicon-trash' id='noNew' style='text-decoration: none'></a>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="updateMis">
              <button type="button" class="btn btn-default" id="addDev">添加设备</button>
              <button type="submit" class="btn btn-primary" id="updateYes">确认修改</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 修改任务信息不完整弹出框 -->
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
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
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

<!-- 供应商下的设备列表 -->
 <div class="modal fade" id="devList">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">供应商所供设备</h4>
        </div>
        
          <div class="modal-body" style="height: 300px">
            <table class="table table-striped table-hover">
              <thead><tr>
                <th>设备编号</th><th>设备名称</th><th>　</th><th>设备型号</th><th>设备状态</th><th>运行天数</th>
              </tr></thead>
              <tbody>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
              </tbody>
              </table>
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">关闭</button>
            </div>

      
    </div>
  </div>

    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
   <?php include "repJs.php" ?>
  </body>
</html>