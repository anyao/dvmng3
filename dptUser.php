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

<title>部门/人员-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  /*thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }*/
  .glyphicon-check, .glyphicon-unchecked{
    cursor:pointer;
  }

  #roleInfo > .row, #departInfo > .row{
    margin:auto 5px !important;
  }

  #formUser .col-md-4{
    margin:15px auto !important;
  }
  
  #formUser .list-group-item{
    padding:5px 15px !important;
  }
  .dvd-line{
    border-bottom: 1px solid #CCCCCC;
    margin:auto 5px 10px 5px !important;
  }
</style>
<link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="bootstrap/css/choose.css" media="all" type="text/css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

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

	require_once "model/dptService.class.php";
	$dptService=new dptService();
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
		    <li>
          <a href="dptSearch.php">用户管理</a>
        </li>

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
    <div class="col-md-3">
      <ul class="nav nav-pills  nav-stacked nav-self" role="tablist">
        <li role="presentation"><a href="dptSearch.php">用户搜索</a></li>
        <li role="presentation" class="active"><a href="dptUser.php">部门 / 人员</a></li>
        <li role="presentation"><a href="dptRole.php">角色 / 功能</a></li>
      </ul>
    </div>
		<div class="col-md-9">
			<div class="accordion" id="sqebox">
	          <div class="accordion-group">
	            <div class="accordion-heading"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#sqebox" href="#py"> <h4>河北普阳钢铁有限公司</h4> </a></div>
	            <div style="height: auto;" id="py" class="accordion-body collapse in">
	              <div class="accordion-inner">  
        					<div class="row">
        						<div class="col-md-12">
        							<div id="py-tree"></div>
        						</div>
        					</div>


	              </div>
	            </div>
	          </div>

	          <div class="accordion-group">
	            <div class="accordion-heading"> <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#sqebox" href="#zp"> <h4>中普(邯郸)钢铁有限公司</h4> </a> </div>
	            <div style="height: 0px;" id="zp" class="accordion-body collapse">
	              <div class="accordion-inner"> 
          					<div class="row">
          						<div class="col-md-12">
          							<div id="zp-tree"></div>
          						</div>
          					</div>
	              </div>
	            </div>
	          </div>

	          <div class="accordion-group">
	            <div class="accordion-heading"> <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#sqebox" href="#gp"> <h4>武安广普焦化有限公司</h4> </a> </div>
	            <div style="height: 0px;" id="gp" class="accordion-body collapse">
	              <div class="accordion-inner"> 
        					<div class="row">
        						<div class="col-md-12">
        							<div id="gp-tree"></div>
        						</div>
        					</div>
	              </div>
	            </div>
	          </div>
	        </div>
				
			
                

		</div>
	</div>
</div>

<!-- 设备管理员列表 -->
<div class="modal fade" id="userMsg">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"> <span class="glyphicon glyphicon-user"></span> 用户管理</h4>
        </div>
        <div class="modal-body" style="height: 300px">
          <table class="table table-striped table-hover">
          <thead><tr><th>用户ID</th><th>用户编号</th><th>用户姓名</th><th>用户级别</th>
          <th style="width: 4%">　</th><th style="width: 4%">　</th><th style="width: 4%">　</th><th style="width: 4%">　</th></tr></thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="modal-footer">
        	<button class="btn btn-primary" id="addUser">添加新用户</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addDpt">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加下属部门</h4>
      </div>
      <form class="form-horizontal" action="
      controller/dptProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">部门名称：</label>
            <div class="col-sm-7">	
              <input type="text" class="form-control" name="name">
            </div>
          </div> 
          </div> 
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addDpt">
            <input type="hidden" name="pid">
            <button class="btn btn-primary" id="yesAddDpt">确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 部门下添加新用户 -->
<div class="modal fade" id="addUser-modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新用户</h4>
      </div>
      <form class="form-horizontal" id="formUser">
        <div class="modal-body">
          <div class="row dvd-line">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">用户编号</span>
                <input type="text" class="form-control"  name="code">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">用户姓名</span>
                <input type="text" class="form-control"  name="name">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">登录密码</span>
                <input type="text" class="form-control"  name="psw">
              </div>
            </div>
          </div>

          <div class="row dvd-line">
            <?php  
              // 如果所登录的用户有角色管理这一权限，则显示。没有则直接默认普通用户
              $role = $dptService->getRoleAll();
              $addHtml = "";
              for ($i=1; $i < count($role); $i++) { 
                $addHtml .= "<div class='col-md-3'>
                                <p>
                                  <span class='glyphicon glyphicon-unchecked' role='{$role[$i]['id']}'></span> {$role[$i]['title']}
                                </p>
                             </div>";
              }
              echo "$addHtml";
            ?>
          </div>

          <div class="row dvd-line" >
            <div class="col-md-4">
              <div id='py-dpt'></div>
            </div>
            <div class="col-md-4">
              <div id="zp-dpt"></div>
            </div>
            <div class="col-md-4">
              <div id="gp-dpt"></div>
            </div>
          </div>
          
          <div id="dptList">
            
          </div>

          </div> 
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addUser">
            <input type="hidden" name="dptid">
            <button type="button" class="btn btn-primary" id="yesAddUser">确认</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 搜索用户 -->
<div class="modal fade" id="findUser" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="glyphicon glyphicon-map-marker"></span> 用户搜索</h4>
      </div>
      <form class="form-horizontal" id="formFind">
        <div class="modal-body" style="margin:0 10px">
          <div class="row" style="border-bottom: 1px solid #CCCCCC;">
            <div class="col-md-8">
              <div class="form-group">
                <label class="col-sm-3 control-label" style="text-align:left;padding-left:0px;padding-right:0px">用户名称 / 编号：</label>
                <div class="col-sm-7">
                  <div class="input-group"  style="position: relative;left:-20px">
                    <input type="text" class="form-control" name="find">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" id="yesFind"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                  </div>  
                </div>
              </div>
              
            </div>
          </div>
          <div class="row" style="height: 500px">
            <table class="table table-striped table-hover">
            <thead><tr><th>用户ID</th><th>用户编号</th><th>用户姓名</th><th>用户级别</th>
            <th style="width: 4%">　</th><th style="width: 4%">　</th><th style="width: 4%">　</th><th style="width: 4%">　</th></tr></thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>    
        </form>
    </div>
  </div>
</div>


<!-- 修改用户信息 -->
<div class="modal fade" id="uptUser">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">修改用户信息</h4>
      </div>
      <form class="form-horizontal" id="formUptUser">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">用户编号：</label>
            <div class="col-sm-7">  
              <input type="text" class="form-control" name="code" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">用户名称：</label>
            <div class="col-sm-7">  
              <input type="text" class="form-control" name="name">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-7">
              <div class="input-group">
              <input type="text" name="dptName" class="form-control">
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
            <label class="col-sm-3 control-label">登录密码：</label>
            <div class="col-sm-7">  
              <input type="password" class="form-control" name="psw">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">管理权限：</label>
            <div class="col-sm-7">  
              <label class="radio-inline">
                <input type="radio" name="permit" value="0"> 高级用户
              </label>
              <label class="radio-inline">
                <input type="radio" name="permit" value="1"> 普通用户
              </label>
            </div>
          </div>
          </div> 
          <div class="modal-footer">
            <input type="hidden" name="id">
            <input type="hidden" name="flag" value="uptUser">
            <input type="hidden" name="dptid">
            <button type="button" class="btn btn-primary" id="yesUptUser">确认修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 用户对应设备 -->
<div class="modal fade" id="getDev" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">用户当前管理设备</h4>
      </div>
      <form class="form-horizontal" id="formDev">
        <div class="modal-body">
          <div class='form-group' >
            <label class='col-sm-3 control-label'>新添加：</label>
              <div class='col-sm-7'>
            <div class='input-group'>
              <input type='text' class='form-control' name="devName">
              <div class='input-group-btn'>
                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                  <span class='caret'></span>
                </button>
                <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                </ul>
              </div>
            </div>
          </div>
            <div class="btn-set">
             <a href="javascript:void(0);" id="yesDev" class='glyphicon glyphicon-ok'></a>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">设备列表：</label>
            <div class="col-sm-8" id="forDev" style="padding-top: 5px">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="setCon">
            <input type="hidden" name="uid">
            <input type="hidden" name="oCon">
            <button type="button" class="btn btn-primary" id="addConYes">确认修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 删除配置柜提示框 -->
<div class="modal fade"  id="delDpt">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该部门吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="yesDel">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade"  id="failAdd">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您需要添加的信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<!-- 删除失败弹出框 -->
<div class="modal fade"  id="failDel">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">删除失败，其下存在子部门/用户/设备。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<div class="modal fade"  id="userErr">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">添加失败，您需要添加的用户名或用户编号已存在。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<div class="modal fade"  id="delUser">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该用户记录吗？<br/>删除后对应与设备管理关系也将删除。<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="yesDelUser">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade"  id="userFail">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">操作失败，请联系管理员。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<div class="modal fade"  id="getAuth">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">建设中，敬请期待。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

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


<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/dptUser-treeview.js"></script>
<script src="bootstrap/js/jsonToTree.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script type="text/javascript">
function checkDpt(id,flag){
  // 只有点击是部门时才会进行操作
  if (flag == 1) {
    $.get("./controller/dptProcess.php",{
      flag:'getFct',
      id:id
    },function(data,success){
      // {"depart":"竖炉车间","id":"1","factory":"新区竖炉"}
      var addHtml="<span class='badge'>"+data.factory+"-"+data.depart+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='chkDpt[]' value="+data.id+"></span> ";
      $("#dptList").append(addHtml);

      
    },'json');
  }
}

// 选中按钮
$(".col-md-3 > p > .glyphicon").click(function(){
  $(this).toggleClass("glyphicon glyphicon-check");
  $(this).toggleClass("glyphicon glyphicon-unchecked");
}); 

function addUserStep(step){
  if (step == "basic") {
    $("#roleInfo, #departInfo").hide();
    $("#basicInfo").show();
  }else if (step == "role") {
    $("#basicInfo, #departInfo").hide();
    $("#roleInfo").show();
  }else if (step == "depart") {
    $("#basicInfo,#roleInfo").hide();
    $("#departInfo").show();
  }

}


$("#yesFind").click(function(){
  var find=$("#findUser input[name=find]").val();
  if (find.length==0) {
    $('#failAdd').modal({
        keyboard: true
    });
  }else{
    $.get("controller/dptProcess.php",{
      flag:'findUser',
      kword:find
    },function(data,success){
      if (data.length==0) {
        $addHtml="<tr><td colspan='12'>未找到相关用户，请核实关键字。</td></tr>"
      }else{  
        var $addHtml="";
        for (var i = data.length - 1; i >= 0; i--) {
          if (data[i].permit==1) {
            data[i].permit="普通管理员";
          }else{
            data[i].permit="高级管理员";
          }
          $addHtml+="<tr><td>"+data[i].id+"</td><td>"+data[i].code+"</td><td>"+data[i].name+"</td><td>"+data[i].permit+"</td>"+
                "<td><a href=\"javascript:setAuth("+data[i].id+")\" class='glyphicon glyphicon-thumbs-up'></a></td>"+
                "<td><a href=\"javascript:uptUser("+data[i].id+")\" class='glyphicon  glyphicon glyphicon-scissors'></a></td>"+
                "<td><a href=\"javascript:getDev("+data[i].id+")\" class='glyphicon glyphicon-list'></a></td>"+
                "<td><a href=\"javascript:delUser("+data[i].id+","+data[i].departid+")\" class='glyphicon glyphicon-remove'></a></td></tr>";
        }
      }
      $("#findUser tbody").empty().append($addHtml);
      $('#findUser').modal({
          keyboard: true
      });
    },"json");
  }
});

// 搜索部门或者用户
function findUser(){
  $('#findUser').modal({
      keyboard: true
  });
}

// 确认添加用户和设备关系按钮
$("#addConYes").click(function(){
  var allow_submit = true;
  var forDev=$("#getDev #forDev input").length;
  if (forDev==0) {
    $('#failAdd').modal({
        keyboard: true
    });
    allow_submit = false;
  }
  
  if (allow_submit==true) {
    $.post("controller/dptProcess.php",$("#formDev").serialize(),function(data,success){
      if (data=="success") {
        $("#getDev").modal('toggle');
        getUser(dptid);
      }else if (data=="failDel") {
        alert("停止管理设备失败，请联系管理员。");
      }else{
        alert("新增加管理关系失败，请联系管理员。");
      }
    },"text");
  }
});

$("#getDev #yesDev").click(function(){
  if($("#getDev input[name=devName]").val().length>0){
    var nameDev=$("#getDev input[name=devName]").val();
    var idDev=$("#getDev input[name=devName]").attr("data-id");
    var addHtml="<span class='badge'>"+nameDev+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='dev[]' value="+idDev+"></span> "
    $("#getDev #forDev").append(addHtml);
    $("#getDev input[name=devName]").val("");
  }else{
    $('#noDev').modal({
      keyboard: true
    });
  }  
});

$("input[name=devName]").bsSuggest({
    allowNoKeyword: false,
     showBtn: false,
    indexKey: 0,
    indexId:1,
    inputWarnColor: '#f5f5f5',
    data: {
       'value':<?php
                $devAll=$dptService->getUsingAll();
                echo "$devAll";
               ?>,
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
    var devid=$(this).attr("data-id");
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

// 已确定添加的设备删除
$(document).on("click",".glyphicon-remove",delDeved);
function delDeved(){
  $(this).parents("span").detach();
}

// 获取用户管理的相应设备
function getDev(id){
  $.get("controller/dptProcess.php",{
    flag:'getCon',
    uid:id
  },function(data,success){
    // [{"id":"5","devid":"85","name":"炉区控制器A柜"},{"id":"17","devid":"186","name":"粗轧顺控控制器柜"}]
    // if (data.length==0) {
    //   $addHtml="该用户未管理设备，可在文本框中搜索添加";
    // }else{
    var $addHtml="";
    var oCon=new Array();
    for (var i = 0; i < data.length; i++) {
      oCon[i]=data[i].id;
      $addHtml+="<span class='badge'>"+data[i].name+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='con[]' value="+data[i].id+"></span> ";
      // }
    }
    $("#getDev input[name=uid]").val(id);
    $("#getDev input[name=oCon]").val(oCon);
    $("#forDev").empty().append($addHtml);
    $("#getDev").modal({ 
      keyboard: true
    });
  },"json");
}

function setAuth(id){
  $("#getAuth").modal({ 
    keyboard: true
  });
}

function delUser(id,dptid){
  $("#yesDelUser").val(id);
  $("#yesDelUser").attr("dptid",dptid);
  $("#delUser").modal({ 
    keyboard: true
  });
}

$("#yesDelUser").click(function(){
  var id=$(this).val();
  var dptid=$(this).attr('dptid');
  // location.href="controller/dptProcess.php?flag=delUser&id="+id;
  $.get("controller/dptProcess.php",{
    flag:'delUser',
    id:id
  },function(data,success){
    if (data=="fail") {
      $("#userFail").modal({ 
        keyboard: true
      });
    }else{
      $("#delUser").modal('toggle');
      getUser(dptid);
    }
  },"text");
});

// 修改用户信息
function uptUser(id){
  $.get("controller/dptProcess.php",{
    flag:'getUserForUpt',
    id:id
  },function(data,success){
    $("#uptUser input[name=id]").val(data.id);
    $("#uptUser input[name=name]").val(data.name);
    $("#uptUser input[name=code]").val(data.code);
    $("#uptUser input[name=psw]").val(data.psw);
    $("#uptUser input[name=dptid]").val(data.departid);
    $("#uptUser input[name=permit][value="+data.permit+"]").attr("checked","checked");
    $("#uptUser input[name=dptName]").val(data.depart);
    // 修改用户信息弹出框下的部门提示
    $("#uptUser input[name=dptName]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        indexId:1,
        data: {
             'value':<?php $dptForUser=$dptService->getDptForUser();echo "$dptForUser"; ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
       var idDepart=$(this).attr("data-id");
       $(this).parents("form").find("input[name=dptid]").val(idDepart);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });
    $("#uptUser").modal({ 
        keyboard: true
      });
  },'json');
}

// 修改用户信息确认按钮
$("#yesUptUser").click(function(){

  var allow_submit=true;
  $("#uptUser input[type!=hidden]").each(function(){
    if ($(this).val()=="") {
      $("#failAdd").modal({ 
        keyboard: true
      });
      allow_submit=false;
    }
  });
  if (allow_submit==true) {
    var dptid=$("#formUptUser input[name=dptid]").val();
    $.get("controller/dptProcess.php",$("#formUptUser").serialize(),function(data,success){
      if (data=="fail") {
        $("#userFail").modal({ 
          keyboard: true
        });
      }else{
        $("#uptUser").modal('toggle');
        getUser(dptid);
      }
    },'text');
  }
});


$("#yesAddUser").click(function(){
  var allow_submit=true;
  $("#addUser-modal input[type!=hidden]").each(function(){ 
    if ($(this).val()=="") {
      $("#failAdd").modal({ 
        keyboard: true
      });
      allow_submit=false;
    }
  });
  if (allow_submit==true) {
    var dptid=$("#formUser input[name=dptid]").val();
    $.get("controller/dptProcess.php",$("#formUser").serialize(),function(data,success){
      if (data=="error") {
        $("#userErr").modal({ 
          keyboard: true
        });
      }else if (data=="fail") {
        $("#userFail").modal({ 
          keyboard: true
        });
      }else{
        $("#addUser-modal").modal('toggle');
        getUser(dptid);
      }
    },'text');
  }
});


// 部门添加新用户
$("#addUser").click(function(){
  var dptid=$(this).val();
  $("#addUser-modal").modal({ 
    keyboard: true
  });
});

// 删除部门按钮
$(document).on("click",".glyphicon-trash",function delDpt(){
  var id=$(this).attr("dpt");
  $.get("controller/dptProcess.php",{
    flag:'findSon',
    id:id
  },function(data,success){
    // [{"num":"3"},{"num":"0"},{"num":"110"}]
    var all_null=true;
    $.each(data,function(index, el) {
      if (el.num!=0) {
        all_null=false;
        return false;
      }   
    });
    if (all_null==true) {
     $('#delDpt').modal({
        keyboard: true
     });
     $("#yesDel").val(id);
    }else{
     $('#failDel').modal({
        keyboard: true
     });
    }
  },'json');
});

// 确认删除部门
$("#yesDel").click(function(){
  var id=$(this).val();
  location.href="controller/dptProcess.php?flag=delDpt&id="+id;  
});


// 修改部门信息弹出窗口
$(document).on('click','.glyphicon-edit',function uptDpt(){
	var dptid=$(this).attr("dpt");
	location.href="uptDpt.php?id="+dptid;
})

// 部门添加的确认按钮
$("#yesAddDpt").click(function(){
	var allow_submit=true;
	var ifEmp=$("#addDpt input").val();
	if (ifEmp=="") {
		 $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
	}
	return allow_submit;
});

// 部门的增加
$(document).on("click",".glyphicon-import",function addDpt(){
	var dptid=$(this).attr("dpt");
	$("#addDpt input[name=pid]").val(dptid);
	$('#addDpt').modal({
	    keyboard: true
	});
});

// 从数据库中所取出的数据
var dataPy='<?php $dataPy=$dptService->getDptForRole(1); echo $dataPy; ?>';
var pyDataTree = transData(eval(dataPy), 'tags','pid', 'nodes'); 
dataPy=JSON.stringify(pyDataTree); 

var dataZp='<?php $dataZp=$dptService->getDptForRole(2); echo $dataZp; ?>';
var zpDataTree = transData(eval(dataZp), 'tags','pid', 'nodes'); 
dataZp=JSON.stringify(zpDataTree);

var dataGp='<?php $dataGp=$dptService->getDptForRole(3); echo $dataGp; ?>';
var gpDataTree = transData(eval(dataGp), 'tags','pid', 'nodes'); 
dataGp=JSON.stringify(gpDataTree);

// 添加新用户时权限范围
$("#py-dpt").treeview({
  showBorder: false,
  data: dataPy,
  enableLinks: true,
  levels: 1
});

$('#zp-dpt').treeview({
  showBorder: false,
  enableLinks: true,
  data: dataZp,
  levels: 1
});

$('#gp-dpt').treeview({
  showBorder: false,
  enableLinks: true,
  data: dataGp,
  levels: 1
});

// 从数据库中所取出的数据
var dataPy='<?php $dataPy=$dptService->getFctByJson(1); echo $dataPy; ?>';
var pyDataTree = transData(eval(dataPy), 'tags', 'pid', 'nodes'); 
dataPy=JSON.stringify(pyDataTree); 

var dataZp='<?php $dataZp=$dptService->getFctByJson(2); echo $dataZp; ?>';
var zpDataTree = transData(eval(dataZp), 'tags', 'pid', 'nodes'); 
dataZp=JSON.stringify(zpDataTree); 

var dataGp='<?php $dataGp=$dptService->getFctByJson(3); echo $dataGp; ?>';
var gpDataTree = transData(eval(dataGp), 'tags', 'pid', 'nodes'); 
dataGp=JSON.stringify(gpDataTree); 

// 调用树形结构
$('#py-tree').treeview({
  // color: "#428bca",
  showBorder: false,
  data: dataPy,
  levels: 1,
  enableLinks: true,
  showTags: true
});

$('#zp-tree').treeview({
  // color: "#428bca",
  showBorder: false,
  data: dataZp,
  levels: 1,
  enableLinks: true,
  showTags: true
});

$('#gp-tree').treeview({
  // color: "#428bca",
  showBorder: false,
  data: dataGp,
  levels: 1,
  enableLinks: true,
  showTags: true
});

function getUser(id){
	$.get("controller/dptProcess.php",{
		flag:"getUser",
		dptid:id
	},function(data,success){
		// [{"id":"1","name":"admin","code":"admin","psw":"123456","departid":"2","permit":"1"}]
		// <th>用户ID</th><th>用户编号</th><th>用户姓名</th><th>用户级别</th>
		if (data.length==0) {
			$addHtml="<tr><td colspan='12'>该 部门 / 分厂 暂时没有设备管理员，请添加。</td></tr>"
		}else{	
			var $addHtml="";
			for (var i = data.length - 1; i >= 0; i--) {
        if (data[i].permit==1) {
            data[i].permit="普通管理员";
          }else{
            data[i].permit="高级管理员";
          }
				$addHtml+="<tr><td>"+data[i].id+"</td><td>"+data[i].code+"</td><td>"+data[i].name+"</td><td>"+data[i].permit+"</td>"+
						  "<td><a href=\"javascript:setAuth("+data[i].id+")\" class='glyphicon glyphicon-thumbs-up'></a></td>"+
						  "<td><a href=\"javascript:uptUser("+data[i].id+")\" class='glyphicon  glyphicon glyphicon-scissors'></a></td>"+
						  "<td><a href=\"javascript:getDev("+data[i].id+")\" class='glyphicon glyphicon-list'></a></td>"+
						  "<td><a href=\"javascript:delUser("+data[i].id+","+id+")\" class='glyphicon glyphicon-remove'></a></td></tr>";
			}
		}
		$("#userMsg tbody").empty().append($addHtml);
    $("#addUser-modal input[name=dptid]").val(id);
		$('#userMsg').modal({
		    keyboard: true
		});
	},"json");
}

//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

</script>
</body>
</html>