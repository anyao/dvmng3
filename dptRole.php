<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];

$sqlHelper = new sqlHelper;
$dptService=new dptService($sqlHelper);
$res = $dptService->getRoleFunc();
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
<link rel="icon" href="bootstrap/img/favicon.ico">

<title>部门/人员-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
 /* thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }*/
  .accordion-heading > h4{
    padding-left: 20px
  }

  .accordion-heading > h4 > a{
    float:right;
    margin-right: 20px
  }

  .roleName{
    text-align:left !important;
    width:80% !important;
  }

  .col-md-7 > .col-md-3{
    padding-left: 0px !important;
  }
  .col-md-7 > .col-md-7{
    position: relative !important;
    left: -50px !important
  }

  .first-row{
    border-bottom: 1px solid #CCCCCC !important;
  }

  .glyphicon-unchecked,.glyphicon-check{
    cursor:pointer;
  }

  .second-row{
    /*display: inline;*/
    
  }
</style>
<?php include "buyVendor.php" ?>
</head>
<body role="document">
<?php 
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
      <a class="navbar-brand" href="usingList.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检定记录 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="checkMis.php">周检计划</a></li>
            <li><a href="checkList.php">检定历史</a></li>
            <li  style="display: <?=(!in_array(7, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>">
              <a href="checkXls.php">表格模板</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修调整 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="repairMis.php">维修任务</a></li>
            <li><a href="repairList.php">维修记录</a></li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
        <li class="active" style="display: <?=(!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?=$user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="./controller/userProcess.php?flag=downInstr">说明书</a></li>
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
        <li role="presentation"><a href="dptUser.php">部门 / 人员</a></li>
        <li role="presentation" class="active"><a href="javascript:goto(12,'dptRole');">角色 / 功能</a></li>
      </ul>
    </div>
    <div class="col-md-9">
      <div class="accordion">
        <div class="accordion-group">
          <div class="accordion-heading">
             <h4>角色和子功能 <a class='glyphicon glyphicon-paperclip' href='javascript:addRole();'></a>
             </h4></div>
            <div class="accordion-inner" style="min-height: 800px">  
              <div class="row">
                <div class="col-md-12">
                  <table class="table table-striped table-hover" >
                    <tbody class="tablebody" style='font-size: 14px'>  
                        <?php
                          foreach ($res as $k => $v) {
                            $arr[$v['rid']]['fid'][] = $v['fid'];
                            $arr[$v['rid']]['func'][] = $v['func'];
                            $arr[$v['rid']]['role'] = $v['role'];
                            $arr[$v['rid']]['rid'] = $v['rid'];
                          }
                          $arr = array_values($arr);
                          for ($i=0; $i < count($arr); $i++) { 
                            $addHtml .= "<tr><td style='width:15%'>{$arr[$i]['role']}</td><td>".implode(" | ",$arr[$i]['func'])."</td>
                                             <td style='width:4%'><a href='javascript:uptRole({$arr[$i]['rid']},\"".implode(",",$arr[$i]['fid'])."\",\"{$arr[$i]['role']}\");' class='glyphicon glyphicon-edit'></a></td>
                                             <td style='width:4%'><a href='javascript:delRole({$arr[$i]['rid']});' class='glyphicon glyphicon-trash'></a></td></tr>";
                          }
                          echo "$addHtml";
                          
                        ?>  
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- 添加新的角色及其子功能 -->
<div class="modal fade" id="addRole" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-link"></span> 配置新的功能组</h4>
      </div>
      <form class="form-horizontal" action='./controller/dptProcess.php'>
        <div class="modal-body" style="margin:0 10px;height: 500px">
          <div class="row first-row">
            <div class="form-group col-md-7">
                <div class="col-md-3" ><label class="control-label roleName" >角色名称：</label></div>
                <div class="col-md-7">
                  <input type="text" class="form-control" name="roleName">
                </div>
              </div>
            </div> 
          <div class="row">
              <h4>设备信息</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='1'></span> 信息修改</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='2'></span> 信息删除</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='4'></span> 属性浏览</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='5'></span> 属性增删改</p></div>
          </div>
          <div class="row">
              <h4>仪表申报</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='14'></span> 仪表申报浏览</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='6'></span> 备件审核</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='7'></span> 入厂检定</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='8'></span> 入账存库</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='9'></span> 查询库存</p></div>
          </div>
          <div class="row">
              <h4>安检设备</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='15'></span> 安检设备浏览</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='16'></span> 台账管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='17'></span> 设备检修</p></div>
          </div>
          <div class="row">
              <h4>用户管理</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='10'></span> 用户管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='11'></span> 部门管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='12'></span> 角色组管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='13'></span> 用户角色归类</p></div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addRole">
          <div id="funcId"></div>
          <button class="btn btn-primary" id='yesAddRole'>确认添加</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>    
      </form>
    </div>
  </div>
</div>

<!-- 修改角色及其子功能 -->
<div class="modal fade" id="uptRole" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-link"></span> 修改角色及其子功能</h4>
      </div>
      <form class="form-horizontal" action='./controller/dptProcess.php'>
        <div class="modal-body" style="margin:0 10px;height: 500px">
          <div class="row first-row">
            <div class="form-group col-md-7">
                <div class="col-md-3" ><label class="control-label roleName" >角色名称：</label></div>
                <div class="col-md-7">
                  <input type="text" class="form-control" name="roleName">
                </div>
              </div>
            </div> 
          <div class="row">
              <h4>设备信息</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='1'></span> 信息修改</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='2'></span> 信息删除</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='4'></span> 属性增加</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='5'></span> 属性修改、删除</p></div>
          </div>
          <div class="row">
              <h4>仪表申报</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='6'></span> 备件审核</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='7'></span> 入厂检定</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='8'></span> 入账存库</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='9'></span> 查询库存</p></div>
          </div>
          <div class="row">
              <h4>用户管理</h4>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='10'></span> 用户管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='11'></span> 部门管理</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='12'></span> 角色功能组</p></div>
              <div class="col-md-2"><p><span class='glyphicon glyphicon-unchecked' func='13'></span> 用户角色归类</p></div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="uptRole">
          <input type="hidden" name="rid">
          <div id="funcForUpt"></div>
          <button class="btn btn-primary" id='yesUptRole'>确认修改</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>    
      </form>
    </div>
  </div>
</div>

<div class="modal fade"  id="delRole">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除角色吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="yesDelRole">删除</button>
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

<script type="text/javascript">
// 确认添加role和function的关系
$("#uptRole").on("click","#yesUptRole",function(){
  var allow_submit = true;
  // 用户组名为空不可提交
  var roleName = $("#uptRole input[name=roleName]").val();
  // 没有选中的功能也不可提交
  var funcChk = $("#uptRole .glyphicon-check").length;
  if (roleName.length == "" || funcChk == 0) {
    $("#failAdd").modal({
      keyboard:true
    });
    allow_submit = false;
  }else{
    var $addHtml = "";
     $("#uptRole .glyphicon-check").each(function(){
       $addHtml += "<input type='hidden' name='func[]' value='"+$(this).attr("func")+"'>";
     });
     $("#funcForUpt").empty().append($addHtml);
  }
  return allow_submit;
});


// 修改func和role的关系
function uptRole(rid,fid,role){
  $("#uptRole input[name=roleName]").val(role);
  $("#uptRole input[name=rid]").val(rid);
  fid = fid.split(",");
   $("#uptRole p .glyphicon").attr("class","glyphicon glyphicon-unchecked");
  for (var i = 0; i < fid.length; i++) {
    $("#uptRole p .glyphicon[func="+fid[i]+"]").removeClass('glyphicon-unchecked').addClass("glyphicon-check");
  }
  $("#uptRole").modal({
    keyboard:true
  });
}

// 删除func和role的关系
function delRole(rid){
  $("#yesDelRole").attr("rid",rid);
  $("#delRole").modal({
    keyboard:true
  });
}

$("#yesDelRole").click(function(){
  var rid = $(this).attr("rid");
  $.get("./controller/dptProcess.php",{
    flag:'delRole',
    rid:rid
  },function(data,success){
    if (data != 0) {
      location.href = "./dptRole.php";
    }
  },'text');
})

// 确认添加role和function的关系
$("#addRole").on("click","#yesAddRole",function(){
  var allow_submit = true;
  // 用户组名为空不可提交
  var roleName = $("#addRole input[name=roleName]").val();
  // 没有选中的功能也不可提交
  var funcChk = $("#addRole .glyphicon-check").length;
  if (roleName.length == "" || funcChk == 0) {
    $("#failAdd").modal({
      keyboard:true
    });
    allow_submit = false;
  }else{
    var $addHtml = "";
     $("#addRole .glyphicon-check").each(function(){
       $addHtml += "<input type='hidden' name='func[]' value='"+$(this).attr("func")+"'>";
     });
     $("#funcId").empty().append($addHtml);
  }
  return allow_submit;
});

// 选中按钮
$(".col-md-2 > p > .glyphicon").click(function(){
  $(this).toggleClass("glyphicon glyphicon-check");
  $(this).toggleClass("glyphicon glyphicon-unchecked");
}); 

// 添加新的角色及其子功能
function addRole(){
  $("#addRole").modal({
    keyboard:true
  });
}


//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

</script>
</body>
</html>