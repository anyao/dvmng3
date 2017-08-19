<?php 
include_once "./model/commonService.class.php";
CommonService::autoload();
CommonService::checkValidate();
$sqlHelper = new sqlHelper;
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
<link rel="icon" href="bootstrap/img/favicon.ico">
<title>备件导入-仪表管理</title>
<style type="text/css">
  .basic{
    border-bottom: 1px solid #CCCCCC;
    padding:0px 10px;
  }
  .col-md-8{
    margin-bottom: 15px;
  }
  
  #yesAdd{
    width:200px;
    margin-bottom: 20px;
  }
  
  .input-group{
    margin: 8px 0px
  }
  
  #addBtn{
    font-size:25px; 
    text-align: center;
    margin-top: 10px;
    cursor: pointer;
  }

  .bas-remove{
    text-align: right;
    margin: 5px 8px -2px 0px !important;
  }

  .bas-remove span{
    cursor: pointer;  
  }
  
  div[comp=outComp]{
    display: none;
  }
</style>
<?php include "buyVendor.php"; ?>
</head>
<body role="document">
<?php 
include "message.php";
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
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
        <li class="active"><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li><a href="usingList.php">设备台账</a></li>
        <li class="dropdown">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">检定记录 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="checkMis.php">检定任务</a></li>
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
            <li><a href="repairList.php">维修历史</a></li>
          </ul>
        </li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
        <li style="display: <?=(in_array(10, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "inline" : "none";?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?= $user;?> <span class="caret"></span></a>
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
  <div class="page-header">
    <h4>　成套备件入厂检定</h4>
  </div>
  <form action="./controller/gaugeProcess.php" method="post">
    <?php for ($i=0; $i < 2; $i++) { ?>
      <div class="row basic">
        <div class="row bas-remove">
          <span class="glyphicon glyphicon-remove-circle"></span>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">管理类别</span>
                <select class="form-control" name="spr[<?=$i?>][info][class]">
                  <option value="A">A</option>
                  <option value="B">B</option>
                </select>
              </div>  
              <div class="input-group">
                <span class="input-group-addon">设备名称</span>
                <input class="form-control" name="spr[<?=$i?>][info][name]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="spr[<?=$i?>][info][codeManu]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">精度等级</span>
                <input class="form-control" name="spr[<?=$i?>][info][accuracy]" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default">级</button>
                </span>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">量　　程</span>
                <input class="form-control" name="spr[<?=$i?>][info][scale]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">测量装置</span>
                <input class="form-control" name="spr[<?=$i?>][info][equip]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">用　　途</span>
                <select class="form-control" name="spr[<?=$i?>][info][usage]">
                  <option value="质检">质检</option>
                  <option value="经营">经营</option>
                  <option value="控制">控制</option>
                  <option value="安全">安全</option>
                  <option value="环保">环保</option>
                  <option value="能源">能源</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">规格型号</span>
                <input class="form-control" name="spr[<?=$i?>][info][spec]" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">单　　位</span>
                <input class="form-control" name="spr[<?=$i?>][info][unit]" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">检定周期</span>
                <input class="form-control" name="spr[<?=$i?>][info][circle]" value="6" readonly="readonly" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default">月</button>
                </span>
              </div>  
              <div class="input-group">
                <span class="input-group-addon">检定单位</span>
                <select class="form-control" name="spr[<?=$i?>][info][checkDpt]" dpt="checkDpt">
                  <option value="199">计量室</option>
                  <option value="isTake">使用部门</option>
                  <option value="isOut">外检单位</option>
                </select>
              </div>
              <div class="input-group" comp="outComp">
                <span class="input-group-addon">外检公司</span>
                <input class="form-control" name="spr[<?=$i?>][info][checkComp]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">检定日期</span>
                <input class="form-control datetime" name="spr[<?=$i?>][chk][time]" readonly="" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">溯源方式</span>
                <select class="form-control" name="spr[<?=$i?>][chk][track]">
                  <option value="检定">检定</option>
                  <option value="校准">校准</option>
                </select>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">证书结论</span>
                <input class="form-control" name="spr[<?=$i?>][chk][res]" value="合格" type="text" readonly>
              </div> 
            </div>
          </div>
      </div>
      
    <?php }?>
    <div id="addArea">
      
    </div>
    <div id="addBtn">
      <span class="glyphicon glyphicon-option-horizontal" ></span>
    </div>
    <div style="text-align: center">
        <input type="hidden" name="flag" value="addCheckAset">
        <input type="hidden" name="pid" value="<?= $_GET['id']?>">
        <button class="btn btn-primary" id="yesAdd">确认导入</button>       
    </div>
  </form>
</div>

<script type="text/javascript">
var i = 2;
$("#addBtn").click(function(){
  var $addHtml = 
  '<div class="row basic">'+
  '    <div class="row bas-remove">'+
  '      <span class="glyphicon glyphicon-remove-circle"></span>'+
  '      </div>'+
  '       <div class="row">'+
  '         <div class="col-md-6">'+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">管理类别</span>'+
  '             <select class="form-control" name="spr['+i+'][info][class]">'+
  '               <option value="A">A</option>'+
  '               <option value="B">B</option>'+
  '             </select>'+
  '           </div>  '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">设备名称</span>'+
  '             <input class="form-control" name="spr['+i+'][info][name]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">出厂编号</span>'+
  '             <input class="form-control" name="spr['+i+'][info][codeManu]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">精度等级</span>'+
  '             <input class="form-control" name="spr['+i+'][info][accuracy]" type="text">'+
  '             <span class="input-group-btn">'+
  '               <button class="btn btn-default">级</button>'+
  '             </span>'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">量　　程</span>'+
  '             <input class="form-control" name="spr['+i+'][info][scale]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">测量装置</span>'+
  '             <input class="form-control" name="spr['+i+'][info][equip]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">用　　途</span>'+
  '             <select class="form-control" name="spr['+i+'][info][usage]">'+
  '               <option value="质检">质检</option>'+
  '               <option value="经营">经营</option>'+
  '               <option value="控制">控制</option>'+
  '               <option value="安全">安全</option>'+
  '               <option value="环保">环保</option>'+
  '               <option value="能源">能源</option>'+
  '             </select>'+
  '           </div>'+
  '         </div>'+
  '         <div class="col-md-6">'+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">规格型号</span>'+
  '             <input class="form-control" name="spr['+i+'][info][spec]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">单　　位</span>'+
  '             <input class="form-control" name="spr['+i+'][info][unit]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">检定周期</span>'+
  '             <input class="form-control" name="spr['+i+'][info][circle]" value="6" readonly="readonly" type="text">'+
  '             <span class="input-group-btn">'+
  '               <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>'+
  '               <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>'+
  '               <button class="btn btn-default">月</button>'+
  '             </span>'+
  '           </div>  '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">检定单位</span>'+
  '             <select class="form-control" name="spr['+i+'][info][checkDpt]" dpt="checkDpt">'+
  '               <option value="199">计量室</option>'+
  '               <option value="isTake">使用部门</option>'+
  '               <option value="isOut">外检单位</option>'+
  '             </select>'+
  '           </div>'+
  '           <div class="input-group" comp="outComp">'+
  '             <span class="input-group-addon">外检公司</span>'+
  '             <input class="form-control" name="spr['+i+'][info][checkComp]" type="text">'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">检定日期</span>'+
  '             <input class="form-control datetime" name="spr['+i+'][chk][time]" readonly="" type="text">'+
  '           </div>  '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">溯源方式</span>'+
  '             <select class="form-control" name="spr['+i+'][chk][track]">'+
  '               <option value="检定">检定</option>'+
  '               <option value="校准">校准</option>'+
  '             </select>'+
  '           </div> '+
  '           <div class="input-group">'+
  '             <span class="input-group-addon">证书结论</span>'+
  '             <input class="form-control" name="spr['+i+'][chk][res]" value="合格" type="text" readonly>'+
  '           </div> '+
  '         </div>'+
  '       </div>'+
  '</div>';
  ++i;
  $("#addArea").append($addHtml);
});

$("#addArea").on("click", ".bas-remove span", function(){
  $(this).parents(".basic").remove();
});

// 外检input框显示
$(document).on('click', 'select[dpt=checkDpt]', function() {
  if ($(this).val() == "isOut") 
    $(this).parents(".input-group").next().css("display", "table");
  else
    $(this).parents(".input-group").next().hide();
});

// 检定周期加
$(".glyphicon-plus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  num++;
  $circle.val(num);
});

// 检定周期减
$(".glyphicon-minus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  if (num != 1) {
    num--;
    $circle.val(num);
  }
});

</script>
</body>
</html>