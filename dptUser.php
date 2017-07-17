<?php 
require_once "model/cookie.php";
require_once "model/sqlHelper.class.php";
require_once "model/dptService.class.php";
checkValidate();
$user=$_SESSION['user'];

$sqlHelper = new sqlHelper;
$dptService=new dptService($sqlHelper);
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

  .ztree-row{
    margin-left: 0px !important; 
    margin-bottom:0px !important;
    height: 400px;
    overflow-y:auto
  }

  .ztree-row > .col-md-4{
    margin:0px !important;
  }

  .accordion-inner{
    padding-top: 0px !important;
    padding-bottom: 0px !important;
  }


</style>
<link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="bootstrap/css/metroStyle/metroStyle.css">
<link rel="stylesheet" href="bootstrap/css/choose.css" media="all" type="text/css">
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
<script src="bootstrap/js/dptUser-treeview.js"></script>
<script src="bootstrap/js/jsonToTree.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/jquery.ztree.core.js"></script>
<script src="bootstrap/js/jquery.ztree.excheck.min.js"></script>
</head>
<body role="document">
<?php include "message.php";?>
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
                      echo "<li class='active'><a href='dptUser.php'>用户管理</a></li>";
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
    <div class="col-md-3">
      <ul class="nav nav-pills  nav-stacked nav-self" role="tablist">
        <li role="presentation"><a href="dptSearch.php">用户搜索</a></li>
        <li role="presentation" class="active"><a href="dptUser.php">部门 / 人员</a></li>
        <li role="presentation"><a href="javascript:goto(12,'dptRole');">角色 / 功能</a></li>
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
        <div class="modal-body">
        <div class="row" style="height: 400px;overflow-y:scroll;margin-left: 0px">
          <table class="table table-striped table-hover">
          <thead><tr><th>用户ID</th><th>用户编号</th><th>用户姓名</th><th>所属角色组</th>
          <th style="width: 4%">　</th><th style="width: 4%">　</th>
          <th style="width: 4%">　</th><th style="width: 4%">　</th></tr></thead>
            <tbody></tbody>
          </table>
          
        </div>
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
<div class="modal fade" id="addUserModal">
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
                <input type="password" class="form-control"  name="psw">
              </div>
            </div>
          </div>

          <div class='row dvd-line'>
            <?php  
              // 如果所登录的用户有角色管理这一权限，则显示。没有则直接默认普通用户
              if(in_array(13,$_SESSION['funcid']) || $_SESSION['user'] =='admin'){
                $role = $dptService->getRoleAll();
                $roleList = "";
                for ($i=1; $i < count($role); $i++) { 
                  $roleList .= "
                                 <div class='col-md-3'>
                                    <p>
                                      <span class='glyphicon glyphicon-unchecked' role='{$role[$i]['id']}'></span> {$role[$i]['title']}
                                    </p>
                                 </div>
                              ";
                }
                echo "$roleList";

                
              }
            ?>
           </div>

            <div class="row ztree-row">
              <div class="col-md-4">
                <ul id="tree-py" class="ztree"></ul>
              </div>
              <div class="col-md-4">
                <ul id="tree-zp" class="ztree"></ul>
              </div>
              <div class="col-md-4">
                <ul id="tree-gp" class="ztree"></ul>
              </div>
            </div>
          </div> 
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addUser">
            <input type="hidden" name="dptid">
            <input type="hidden" name="node">
            <input type="hidden" name="role">
            <button type="button" class="btn btn-primary" id="yesAddUser">确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade"  id="delDpt">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">确定要删除该部门吗？</div><br/>
         </div>
         <div class="modal-footer">  
            <button type="button" class="btn btn-primary" id="yesDel">删除</button>
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

<?php include "./dptNavi.php"; ?>
<?php include "./dptJs.php"; ?>
<script type="text/javascript">
var zTreePy = <?php $data = $dptService->getDptForRole(1); echo $data; ?>;
var zTreeZp = <?php $data = $dptService->getDptForRole(2); echo $data; ?>;
var zTreeGp = <?php $data = $dptService->getDptForRole(3); echo $data; ?>;

// 部门添加新用户
$("#addUser").click(function(){
  $.fn.zTree.init($("#tree-py"), setting, zTreePy);
  $.fn.zTree.init($("#tree-zp"), setting, zTreeZp);
  $.fn.zTree.init($("#tree-gp"), setting, zTreeGp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");
  $("#addUserModal").modal({ 
    keyboard: true
  });
});

// 提交添加新用户
$("#yesAddUser").click(function(){
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  var allow_submit=true;
  // 用户基本信息
  $("#addUserModal input[type=text]").each(function(){ 
    if ($(this).val()=="") {
      allow_submit=false;
      return false;
    }
  });

  // 管理部门范围
  var nodeDpt = "";
  for (var i = 0; i < nodes.length; i++) {
      nodeDpt += nodes[i].id+",";
  }
  if (nodes.length == 0) {
    allow_submit = false;
  }else{
    $("#addUserModal input[name=node]").val(nodeDpt);
  }

  // 角色管理范围
  var role = "";
  $("#addUserModal .glyphicon-check").each(function(){
    role += $(this).attr("role")+",";
  });
  $("#addUserModal input[name=role]").val(role);
  

  if (allow_submit==true) {
    var dptid=$("#formUser input[name=dptid]").val();
    $.get("controller/dptProcess.php",$("#formUser").serialize(),function(data,success){
      if (data == "error") {
        // 用户编号已存在
        $("#userErr").modal({ 
         keyboard: true
        });
      }else if(data == "success"){
        $("#addUserModal").modal('hide');
        getUser(dptid); 
      }else if (data == "failure") {
        alert("failure!!");
      }
    },'text');
  }else{
     $("#failAdd").modal({ 
        keyboard: true
     });
  }
});

// 删除部门按钮
$(document).on("click",".glyphicon-trash",function delDpt(){
  var id=$(this).attr("dpt");
  var user = session.user;
  var ifDpt = $.inArray(id.toString(),session.dptid);
  var ifFunc = $.inArray('11',session.funcid);
  if (user == "admin") {
    ifDpt = 0;
    ifFunc = 0;
  }

  if(ifDpt != -1 && ifFunc != -1 ){
    $.get("controller/dptProcess.php",{
      flag:'findSon',
      id:id
    },function(data){
      data = parseInt(data);
      if (data == 0) {
       $('#delDpt').modal({
          keyboard: true
       });
       $("#yesDel").val(id);
      }else{
       $('#failDel').modal({
          keyboard: true
       });
      }
    },'text');
  }
  else{
    $('#failCheck').modal({
        keyboard: true
    });
  }
});

// 确认删除部门
$("#yesDel").click(function(){
  var id=$(this).val();
  location.href="controller/dptProcess.php?flag=delDpt&id="+id;  
});


// 修改部门信息弹出窗口
$(document).on('click','.glyphicon-edit',function uptDpt(){
  var dptid=$(this).attr("dpt");
  // 部门对应和功能必须都要对应
  var user = session.user;
  var ifDpt = $.inArray(dptid.toString(),session.dptid);
  var ifFunc = $.inArray('10',session.funcid);
  if (user == "admin") {
    ifDpt = 0;
    ifFunc = 0;
  }
  if(ifDpt != -1 && ifFunc != -1 ){
   location.href="uptDpt.php?id="+dptid; 
  }else{
    $('#failCheck').modal({
        keyboard: true
    });
  }
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

  var user = session.user;
  var ifDpt = $.inArray(dptid.toString(),session.dptid);
  var ifFunc = $.inArray('10',session.funcid);
  if (user == "admin") {
    ifDpt = 0;
    ifFunc = 0;
  }
  if(ifDpt != -1 && ifFunc != -1 ){
    $("#addDpt input[name=pid]").val(dptid);
    $('#addDpt').modal({
        keyboard: true
    });
  }else{
    $('#failCheck').modal({
        keyboard: true
    });
  }
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
  var user = session.user;
  var allow_enter = $.inArray(id.toString(),session.dptid);
  if (user == "admin") {
    allow_enter = 0;
  }
  if(allow_enter != -1){
    $.get("controller/dptProcess.php",{
      flag:"getUser",
      dptid:id
    },function(data,success){
      if (jQuery.isEmptyObject(data)) {
        $addHtml="<tr><td colspan='12'>该 部门 / 分厂 暂时没有设备管理员，请添加。</td></tr>"
      }else{  
        var $addHtml="";
        $.each(data,function(i,val){
          $addHtml += "<tr><td>"+i+"</td><td>"+val.code+"</td><td>"+val.name+"</td><td>"+val.role+"</td>"+
                      "    <td><a href=\"javascript:getBsc("+i+")\" class='glyphicon glyphicon-credit-card'></a></td>"+
                      "    <td><a href=\"javascript:getRole("+i+")\" class='glyphicon glyphicon-tower'></a></td>"+
                      "    <td><a href=\"javascript:getScale("+i+")\" class='glyphicon glyphicon-list'></a></td>"+
                      "    <td><a href=\"javascript:delUser("+i+")\" class='glyphicon glyphicon-remove'></a></td></tr>";
        });
      }
      $("#userMsg tbody").empty().append($addHtml);
      $("#userMsg").attr("dptid",id);
      $("#addUserModal input[name=dptid]").val(id);
      $('#userMsg').modal({
          keyboard: true
      });
    },"json");
  }else{
    $("#failCheck").modal({
            keyboard: true
        });
  }

  
}

//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

</script>
</body>
</html>