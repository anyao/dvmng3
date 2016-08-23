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

<title>部门/人员-设备管理系统</title>

<!-- Bootstrap core CSS -->
<!-- <style type="text/css">
  thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }
</style> -->
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
	require_once "../model/repairService.class.php";
	$repairService=new repairService();
	include "message.php";

	require_once "../model/dptService.class.php";
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
		<li class="dropdown active"  >
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">用户管理</span></a>
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
		<div class="col-md-12">
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
<div class="modal fade" id="userMng">
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
        <form class="form-horizontal" action="../controller/dptProcess.php" method="post">
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
        <div class="loginModal">删除失败，其下存在子部门。</div><br/>
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
// 删除部门按钮
$(document).on("click",".glyphicon-trash",function delDpt(){
  var id=$(this).attr("dpt");
  $('#delDpt').modal({
      keyboard: true
  });
  // 确认删除部门
  $("#yesDel").click(function(){
    $.get("../controller/dptProcess.php",{
      flag:"findSon",
      id:id
    },function(data,success){
      if (data==0) {
        location.href="../controller/dptProcess.php?flag=delDpt&id="+id;  
      }else{
        $('#failDel').modal({
            keyboard: true
        });
      }
    },"text");
  });
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
$(function(){
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


});

function getUser(id){
	$.get("../controller/dptProcess.php",{
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
				$addHtml+="<tr><td>"+data[i].id+"</td><td>"+data[i].code+"</td><td>"+data[i].name+"</td><td>"+data[i].permit+"</td>"+
						  "<td><a href=\"javascript:setAuth("+data[i].id+")\" class='glyphicon glyphicon-thumbs-up'></a></td>"+
						  "<td><a href=\"javascript:updtUser("+data[i].id+")\" class='glyphicon glyphicon-edit'></a></td>"+
						  "<td><a href=\"javascript:getDev("+data[i].id+")\" class='glyphicon glyphicon-list'></a></td>"+
						  "<td><a href=\"javascript:delUser("+data[i].id+")\" class='glyphicon glyphicon-trash'></a></td></tr>";
			}
		}
		$("#userMng tbody").empty().append($addHtml);
		$('#userMng').modal({
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