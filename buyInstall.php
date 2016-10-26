<?php 
require_once "model/cookie.php";
require_once "model/repairService.class.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
require_once "./model/devService.class.php";
checkValidate();
$user=$_SESSION['user'];

$devService = new devService();

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyApv.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyInstall($paging);
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
<title>安装验收-仪表管理</title>
<style type="text/css">
#ifStore .form-group{
  margin-bottom: 20px !important;
}

#ifStore .input-group{
  position: relative;
  left: -25px
}

.ifStore{
  position: relative; 
  left: 19%;
  margin: 20px auto; 
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

/*.form-group {
    margin-bottom: 7px !important;
    margin-top: 7px !important;
}*/

#installSpr .modal-body{
  padding-top: 8px !important;
  padding-bottom: 8px !important;
}

</style>
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
  $repairService=new repairService();
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
      <a class="navbar-brand" href="homePage.php">设备管理系统</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="homePage.php">首页</a></li>
        <li class="active dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button">设备购置 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="buyApply.php">仪表备件申报</a></li>
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

<!-- 备件是否存入小仓库 -->
<div class="modal fade" id="ifStore">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件安装验收</h4>
      </div>
      <form class="form-horizontal" id="spareForm">
        <div class="modal-body">
          <div class="row">
            <div class="ifStore">
              <b>是否需要存入备用仓库？</b>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">存库数量：</label>
              <div class="col-sm-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" id="minus"><span class="glyphicon glyphicon-minus"></span></button>
                    </span>
                    <input type="text" class="form-control" name='num' readonly="readonly" >
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" id="plus"><span class="glyphicon glyphicon-plus"></span></button>
                    </span>
                  </div>
              </div>
            </div>
            
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="spareSpr">
            <input type="hidden" name="id">
            <button class="btn btn-primary" id="yesSpareSpr" type="button">备用</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 添加新设备弹出框 -->
<form class="form-horizontal" method="post" id="formSpr">
  <div class="modal fade" id="installSpr" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">安装验收新仪表</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <input type="text" name="pname" class="form-control">
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
                <label class="col-sm-3 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">使用数量：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="number" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">新增时间：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="para[89]" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">测量装置：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="para[90]">
                </div>
              </div>
              </div>
              <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">管理类别：</label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <input type="radio" name="para[88]" value="A" checked> A
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[88]" value="B"> B
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[88]" value="C"> C
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">使用用途：</label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="质检" checked> 质检
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="经营"> 经营
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="控制"> 控制
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="安全"> 安全
                  </label><br/>
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="环保"> 环保
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="para[91]" value="能源"> 能源
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">确认日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="para[92]" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">确认结论：</label>
                <div class="col-sm-8">
                  <textarea type="text" class="form-control" name="para[93]"></textarea>
                </div>
              </div>
            </div>
            </div>

        </div>
        <div class="modal-footer">
          <input type="hidden" name="pid">
          <input type="hidden" name="sprId">
          <input type="hidden" name="flag" value="useSpr">
          <button type="button" class="btn btn-primary" id="yesInstall">确定添加</button>
          <button class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 

<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件安装验收</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th>
            <th>领取时间</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th style="width:4%"></th>
          </tr>

        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无新备件需要安装验收</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
             // [id] => 1 [code] => 510740110018 [name] => 超声波流量计 [no] => TJZ-100B [resnum] => 1 
             // [unit] => 个 [storetime] => 2016-10-23 14:43:32 
            $addHtml = 
            "<tr>
                <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='ckInfo(this,{$row['id']});'></a></td>
                <td>{$row['storetime']}</td>
                <td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['resnum']} {$row['unit']}</td>
                <td><a class='glyphicon glyphicon-cog' href='javascript:ifStore({$row['id']},{$row['resnum']});'></a></td>
             </tr>";
             echo "$addHtml";

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





<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<?php  include "./buyJs.php";?>
<script type="text/javascript">
$("#yesSpareSpr").click(function(){
  var total = $("#ifStore #plus").attr("max");
  var num = $("#ifStore input[name=num]").val();
  var sprId = $("#ifStore input[name=id]").val();
  if (num != 0) {
    $.get("./controller/gaugeProcess.php",$("#spareForm").serialize(),function(data,success){
      // 还有备件需要使用  
      if (total != num) {
        var dif = total - num;
        $("#installSpr input[name=number]").val(dif);
        $("#installSpr input[name=sprId]").val(sprId);
        $('#ifStore').modal('hide');
        $("#installSpr").modal({
          keyboard:true
        });
      }

        // else{
        //   location.href="./buyI";
        // }
    },"text");
    
  }

});

// 入账的备件数目加
$("#ifStore #plus").click(function(){
  var num = parseInt($("#ifStore input[name=num]").val());
  if (num != $(this).attr("max")) {
    num++;
    $("#ifStore input[name=num]").val(num);
  }
});

// 入账的备件数目减
$("#ifStore #minus").click(function(){
  var num = parseInt($("#ifStore input[name=num]").val());
  if (num != 0) {
    num--;
    $("#ifStore input[name=num]").val(num);
  }
});

// 展开备件的检定信息 
function ckInfo(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getCkInfo',
      sprId:id
    },function(data,success){
      var addHtml = "<tr class='open-"+id+"'>"+
                    "   <td colspan='12'>"+
                    "     <div class='row'>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>制造厂：</b>"+data.supplier+"</p>"+
                    "         <p><b>精度等级：</b>"+data.accuracy+"</p>"+
                    "         <p><b>量程：</b>"+data.scale+"</p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>出厂编号：</b>"+data.codeManu+"</p>"+
                    "         <p><b>检定周期(月)：</b>"+data.circle+"</p>"+
                    "         <p><b>检定部门：</b>"+data.depart+"</p>"+
                    "       </div>"+
                    "       <div class='col-md-4'>"+
                    "         <p><b>检定日期：</b>"+data.checkNxt+"</p>"+
                    "         <p><b>溯源方式：</b>"+data.track+"</p>"+
                    "         <p><b>证书结论：</b>"+data.certi+"</p>"+    
                    "       </div>"+
                    "     </div>"+
                    "   </td>"+
                    " </tr>";
      $rootTr.after(addHtml);
    },'json');
  }else{
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
    $(".open-"+id).detach();
  }
}

// 父设备搜索建议
$("#installSpr input[name=pname]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:3,
    // indexKey: 1,
    data: {
         'value':
         <?php 
          $allDev=$devService->getDevAll();
          echo "$allDev";
         ?>
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
     var pid=$(this).attr("data-id");
    if (pid!="" && typeof(pid)!="undefined") {
      $("#installSpr input[name=pid]").val(pid);
    }
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});



// 添加子设备确认添加按钮
$("#yesInstall").click(function(){
  // 添加新设备信息不完整时，弹出提示框
  var allow_submit = true;
  $("#installSpr .form-control").each(function(){
    if ($(this).val()=="") {
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
      return allow_submit;
    }
  }); 
  if (allow_submit == true) {
    $.post("./controller/gaugeProcess.php",$("#formSpr").serialize(),function(data,success){
      // 在用设备添加成功，返回新添加的在用设备的id
      // var devId = data;
      alert(data);
      // location.href="./controller/gaugeProcess.php?flag=installSpr&sprId="+sprId+"&devId="+devId;
    },'text');
  }

});

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

// 新设备是否备用
function ifStore(id,resnum){
  $("#ifStore input[name=id]").val(id);
  $("#ifStore input[name=num]").val(resnum);
  $("#ifStore #plus").attr("max",resnum);
  $("#ifStore").modal({
    keyboard:true
  });
}



    </script>
  </body>
</html>