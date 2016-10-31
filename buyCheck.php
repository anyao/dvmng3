<?php 
require_once "model/cookie.php";
require_once "model/repairService.class.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
// require_once './model/dptService.class.php';
checkValidate();
$user=$_SESSION['user'];

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyCheck.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyCheck($paging);

// $dptService = new dptService();
// $dptAll = $dptService->getDpt();


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

<!-- 审核弹出框 -->
<div class="modal fade" id="checkSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件入厂检定</h4>
      </div>
      <form class="form-horizontal" action="controller/gaugeProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">检定结果：</label>
            <div class="col-sm-8">
              <label class="radio-inline">
                <input type="radio" name="checkRes" value="2" checked> 合格
              </label>
              <label class="radio-inline">
                <input type="radio" name="checkRes" value="3"> 不合格 · 返厂
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="checkSpr">
            <input type="hidden" name="id">
            <button class="btn btn-primary" id="yesCheckSpr">确认</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 若入厂检定结果为合格则添加部分台账内容 -->
<div class="modal fade" id="addSprInfo">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">备件入厂检定信息</h4>
        </div>
        <form class="form-horizontal" id="addSprForm">
          <div class="modal-body">
          <div class="row">
            <div class="col-md-6" style="padding-right: 0px;">
              <div class="form-group">
                <label class="col-sm-3 control-label">名称型号：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="name" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">制造厂：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="supplier">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">精度等级：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="accuracy">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">量程：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="scale">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">出厂编号：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="codeManu">
                </div>
              </div>
              
            </div>
            <div class="col-md-6" style="padding-left: 0px">
              <div class="form-group">
                <label class="col-sm-3 control-label">检定部门：</label>
                <div class="col-sm-9">
                  <div class="input-group">
                  <input type="text" name="nDptCk" class="form-control">
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
                <label class="col-sm-3 control-label">证书结论：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="certi">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">检定日期：</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control datetime" name="checkNxt" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">检定周期：</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" id="minus"><span class="glyphicon glyphicon-minus"></span></button>
                      <button class="btn btn-default" type="button" id="plus"><span class="glyphicon glyphicon-plus"></span></button>
                    </span>
                    <input type="text" class="form-control" name='circle' value="6" readonly="readonly" style="text-align: right">
                    <span class="input-group-addon">月</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">溯源方式：</label>
                <div class="col-sm-9">
                  <label class="radio-inline">
                    <input type="radio" name="track" value="检定" checked> 检定
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="track" value="校准"> 校准
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="track" value="测试"> 测试
                  </label>
                </div>
              </div>
            </div>
          </div>
            </div> 
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addSprInCk">
              <input type="hidden" name="sprId">
              <input type="hidden" name="dptCk">
              <button type='button' class="btn btn-primary" id="yesAddInfo">确认</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
</div>

<!-- 精度必须是数字 -->
<div class="modal fade"  id="failAccuracy" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">精度等级必须数字。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
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
            <th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>申报部门</th><th>CLJL</th><th>备注描述</th><th style="width:4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无新的入厂检定任务</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            //  [id] => 1 [code] => 510740110018 [name] => 超声波流量计 [no] => TJZ-100B [num] => 3 [unit] => 个 [info] => test upt spr info [depart] => 能源部 [cljl] => CLJL-30-09 [factory] => 办公楼
            $addHtml = 
            "<tr>
                <td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['num']} {$row['unit']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td>{$row['cljl']}</td>
                <td>{$row['info']}</td>
                <td><a class='glyphicon glyphicon-check' href='javascript:sprCheck({$row['id']},\"{$row['name']}\",\"{$row['no']}\");'></a></td>
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
// 部门搜索提示
 $("#addSprInfo input[name=nDptCk]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:2,
    // indexKey: 1,
    data: {
         'value':<?php  echo "$dptAll"; ?>,
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
   console.log('onSetSelectValue: ', keyword, data);
   var idDepart=$(this).attr("data-id");
   $(this).parents("form").find("input[name=dptCk]").val(idDepart);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});


// 确定添加检定信息到mysql中
$("#yesAddInfo").click(function(){
  var allow_submit = true;

  // 精度必须是个数字
  var accuracy = $("#addSprInfo input[name=accuracy]").val();
  if(isNaN(parseFloat(accuracy)) && accuracy.length !=0){
    $("#failAccuracy").modal({
      keyboard:true
    });
    return false;
  }

  // 所有input不得为空
  $("#addSprInfo input[type=text]").each(function(){
    if ($(this).val() == "") {
      $("#failAdd").modal({
        keyboard:true
      });
      var allow_submit = false;
    }
  });

  if (allow_submit == true) {
    $.get("./controller/gaugeProcess.php",$("#addSprForm").serialize(),function(data,success){
      // 添加成功，关闭两个框
      if (data !=0 ) {
        // $('#addSprInfo, #checkSpr').modal('hide');
        location.href="./buyCheck.php";
      }
    },'text');
  }

  
});
// 检定周期加
$("#addSprInfo #plus").click(function(){
  var num = parseInt($("#addSprInfo input[name=circle]").val());
  if (num != 12) {
    num++;
    $("#addSprInfo input[name=circle]").val(num);
  }
});

// 检定周期减
$("#addSprInfo #minus").click(function(){
  var num = parseInt($("#addSprInfo input[name=circle]").val());
  if (num != 1) {
    num--;
    $("#addSprInfo input[name=circle]").val(num);
  }
});


//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

$("#yesCheckSpr").click(function(){
  var allow_submit = true;
  var checkRes = $("#checkSpr input[name=checkRes]:checked").val();
  if (checkRes == 2) {
    $("#addSprInfo").modal({
      keyboard:true
    });
    allow_submit = false;
  }
  return allow_submit;
});

// 检定弹出框
function sprCheck(id,name,no){
  $("#addSprInfo input[name=name]").val(name+" "+no);
  $("#addSprInfo input[name=sprId]").val(id);
  $("#checkSpr input[name=id]").val(id);
  $("#checkSpr").modal({
    keyboard:true
  });
}


    </script>
  </body>
</html>