<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];
$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;
$paging->gotoUrl="buyCheck.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}
$sqlHelper = new sqlHelper;
$gaugeService = new gaugeService($sqlHelper);
$gaugeService->buyCheck($paging);
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
<title>备件入厂检定-仪表管理</title>
<style type="text/css">
  #apvSpr li{
      list-style: none;
      margin:10px 0px;
  }

  .open > th, .open > td{
    background-color:#F0F0F0;
  }

  th > .glyphicon-trash{
    display:none;
  } 

  tr:hover > th > .glyphicon-trash {
    display: inline;
  }

  #addInfo .input-group{
    margin: 12px 0;
  }

  .modal-body{
    padding: 0 25px !important;
  }

  #outComp{
    display: none;
  }
</style>
<?php include "buyVendor.php"; ?>
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
      <a class="navbar-brand" href="usingList.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
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
        <li style="display: <?=(!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?=$user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li><a href="login.php">注销</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>

<div class="modal fade" role="dialog" id="addInfo">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">入厂检定</h4>
      </div>
      <form action="./controller/gaugeProcess.php" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">管理类别</span>
                <select class="form-control" name="info[class]">
                  <option value="A">A</option>
                  <option value="B">B</option>
                </select>
              </div>  
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="info[codeManu]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">精度等级</span>
                <input class="form-control" name="info[accuracy]" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default">级</button>
                </span>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">量　　程</span>
                <input class="form-control" name="info[scale]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">测量装置</span>
                <input class="form-control" name="info[equip]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">用　　途</span>
                <select class="form-control" name="info[usage]">
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
                <span class="input-group-addon">检定周期</span>
                <input class="form-control" name="info[circle]" value="6" readonly="readonly" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default">月</button>
                </span>
              </div>  
              <div class="input-group">
                <span class="input-group-addon">检定单位</span>
                <select class="form-control" name="info[checkDpt]" id="checkDpt">
                  <option value="199">计量室</option>
                  <option value="isTake">使用部门</option>
                  <option value="isOut">外检单位</option>
                </select>
              </div>
              <div class="input-group" id="outComp">
                <span class="input-group-addon">外检公司</span>
                <input class="form-control" name="info[checkComp]" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">检定日期</span>
                <input class="form-control datetime" name="chk[time]" readonly="" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">溯源方式</span>
                <select class="form-control" name="chk[track]"  id="track">
                  <option value="检定">检定</option>
                  <option value="校准">校准</option>
                </select>
              </div> 
              
              <div id="checkRes">
                <div class="input-group">
                  <span class="input-group-addon">检定结果</span>
                  <input class="form-control" name="chk[check][res]" value="合格" type="text" readonly>
                </div> 
              </div>

              <div id="correctRes">
                <div class="input-group">
                  <span class="input-group-addon">校准结果</span>
                  <select class="form-control" name="chk[correct][res]">
                    <option value="5">合格</option>
                    <option value="6">不合格</option>
                  </select>
                </div>
                <div class="input-group">
                  <span class="input-group-addon">证书结论</span>
                  <input type="text" class="form-control" name="chk[correct][conclu]" id="conclu">
                </div>
              </div> 
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addCheck">
          <input type="hidden" name="id">
          <span id="failAdd" style="color: red;display:none">信息填写不完整。</span>
          <button class="btn btn-primary" id='yesAddInfo'>录入</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" role="dialog" id="delInfo" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">确定要删除该备件吗？</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" id="yesDelInfo">确定</button>
        </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-10">
      <div class="page-header">
          <h4>　仪表备件入厂检定</h4>
      </div>
      <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>入库日期</th><th>名称</th><th>规格</th>
              <th>存货编码</th><th>单位</th><th>存货分类</th>
              <th style="width:4%"></th><th style="width:4%"></th>
            </tr>
          </thead>
          <tbody class="tablebody">
          <?php 
            if (count($paging->res_array) == 0) {
              echo "<tr><td colspan=12>当前无新的入厂检定任务</td></tr>";
            }
            for ($i=0; $i < count($paging->res_array); $i++) { 
              $row = $paging->res_array[$i];
              echo
              "<tr>
                  <td>{$row['wareTime']}</td>
                  <td>{$row['name']}</td>
                  <td>{$row['spec']}</td>
                  <td>{$row['codeWare']}</td>
                  <td>{$row['unit']}</td>
                  <td>{$row['category']}</td>
                  <td><a href='javascript:sprCheck({$row['id']},\"{$row['unit']}\");' class='glyphicon glyphicon-pencil'></a></td>
                  <td><a href='javascript:sprDel({$row['id']});' class='glyphicon glyphicon-trash'></a></td>
               </tr>";
            }
          ?>
          </tbody>
          </table>
          <div class='page-count'><?php echo $paging->navi?></div>                    
      </div>
      <div class="col-md-2">
      <div class="col-md-3">
      <?php  include "buyNavi.php";?>
      </div>
      </div>
  </div>
</div>
<?php  include "./buyJs.php";?>
<script type="text/javascript">
// 溯源方式
$("#addInfo").on('click', '#track', checkOrCorrect);
function checkOrCorrect(){
  $("#conclu").val("");
  if ($("#track").val() == "检定") {
    $("#checkRes").show();
    $("#correctRes").hide();
  }else{
    $("#checkRes").hide();
    $("#correctRes").show();
  }
}

// 确定添加检定信息到mysql中
$("#yesAddInfo").click(function(){
  var allow_submit = true;

  $("#addInfo .form-control").not("#outComp input, #conclu").each(function(){
     if ($(this).val() == "" 
      || ($(this).val() == 'isOut' && $("#outComp input").val() == "")
      || ($("#track").val() == '校准' && $("#conclu").val() == "")
      ) {
        $("#failAdd").show();
        allow_submit = false;
      }
  });
  return allow_submit;
});

$("#yesCheckSpr").click(function(){
  var max = $("#numPlus").attr('max');
  var num = $("#checkSpr input[name=num]").val();
  var sprId = $("#checkSpr input[name=id]").val();
  var allow_submit = false;
  if(num == 0){
    // 全都不合格
    allow_submit = true;
  }else{
    location.href="./buyCheckAdd.php?id="+sprId+"&num="+num;
  }
  return allow_submit;
}); 

// 检定弹出框
function sprCheck(id, unit){
  $("#addInfo input[name=id]").val(id);
  if (unit == '套') 
    location.href = "./buyASet.php?id="+id;
  else{
    $("#failAdd").hide();
    checkOrCorrect();
    $("#addInfo").modal({
      keyboard:true
    });
  }
}

$("#yesDelInfo").click(function(){
  var id = $(this).attr("delid");
  $.post("./controller/gaugeProcess.php",{flag: 'delInfo', id: id},function(data){
    if (data == true) 
      location.href = "./buyCheck.php";
  }, 'text');
})

function sprDel(id){
  $("#yesDelInfo").attr("delid", id);
  $("#delInfo").modal({
    keyboard:true
  });
}
    </script>
  </body>
</html>