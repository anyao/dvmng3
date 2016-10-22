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

.form-group {
    margin-bottom: 7px !important;
    margin-top: 7px !important;
}

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
      <form class="form-horizontal">
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
            <input type="hidden" name="flag" value="takeSpr">
            <input type="hidden" name="code">
            <input type="hidden" name="dptTk">
            <button class="btn btn-default" id="yesTakeSpr">备用</button>
            <button type="button" class="btn btn-primary">不需要，全部使用</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 添加新设备弹出框 -->
<form class="form-horizontal" method="post" id="formCld">
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
                <label class="col-sm-3 control-label">使用状态：</label>
                <div class="col-sm-8">
                  <label class="radio-inline">
                    <input type="radio" name='state' value='正常' checked="checked"> 投入使用
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name='state' value='备用'> 备用
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <input type="text" name="pname" class="form-control  notNull">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label ">设备名称：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="name" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">设备型号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="no" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">所属品牌：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="brand" placeholder="请输入新设备品牌">
                </div>
              </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                <label class="col-sm-3 control-label">所属类别：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                  <input type="text" name="class" class="form-control notNull" placeholder="请搜索要设备类别">
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
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">购入价格：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="price" placeholder="请输入新设备所购价格">
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">出厂日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateManu" placeholder="请选择出厂日期" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime notNull" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">有效期止：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="periodVali" placeholder="请选择有效期止" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">供应商：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="supplier" placeholder="请输入新设备的供应源">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">数量：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="number" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">所在部门：</label>
                <div class="col-sm-8">
                  <input type="text" name="nameDepart" class="form-control" readonly="readonly">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">所在分厂：</label>
                <div class="col-sm-8">
                  <input type="text" name="nameFct" class="form-control" readonly="readonly">
                </div>
              </div>
            </div>
            </div>
            <div class="row" id="cldPara">
            </div> 
        </div>
        <div class="modal-footer">
        <input type="hidden" name="depart">
        <input type="hidden" name="factory">
          <input type="hidden" name="pid">
          <input type="hidden" name="ext">
          <input type="hidden" name="flag" value="addCld">
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
            <th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>申报部门</th><th>申报人</th><th>备注描述</th><th style="width:4%"></th>
          </tr>

        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无新备件需要安装验收</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            $addHtml = 
            "<tr>
                <td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['num']} {$row['unit']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td>{$row['user']}</td>
                <td>{$row['info']}</td>
                <td><a class='glyphicon glyphicon-cog' href='javascript:sprIntall({$row['id']});'></a></td>
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

// 添加子设备时，设备类别搜索建议
$("#installSpr input[name=class]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:1,
    // indexKey: 1,
    data: {
         'value':<?php 
          $allType=$devService->getTypeSon();
          echo "$allType";
          ?>,
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
    var idType=$(this).attr("data-id");
    $.get("controller/devProcess.php",{
      flag:'getPara',
      id:idType
    },function(data,success){
     var addHtml="";
     for (var i = 0; i < data.length; i++) {
        addHtml+="<div class='col-md-6'>"+
                "  <div class='form-group'>"+
                "    <label class='col-sm-3 control-label'>"+data[i].name+"：</label>"+
                "    <div class='col-sm-8'>"+
                "      <input type='text' class='form-control' name='paraId["+data[i].id+"]'>"+
                "    </div>"+
                "  </div>"+
                "</div>";
     }
     $("#cldPara").empty();
     $("#cldPara").append(addHtml);
    },'json');
     
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});


// 添加子设备确认添加按钮
$("#yesInstall").click(function(){
  // 添加新设备信息不完整时，弹出提示框
  var allow_submit = true;
  $("#installSpr .notNull").each(function(){
    if ($(this).val()=="") {
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
      return allow_submit;
    }
  }); 
  var idType=$("#installSpr input[name=class]").attr("data-id");
  if(typeof(idType)=="undefined"||idType==""){
      $('#failParaInfo').modal({
            keyboard: true
      });
      allow_submit=false;
      return allow_submit;
  }
  
  if (allow_submit == true) {
    $.post("./controller/devProcess.php",$("#formCld").serialize(),function(data,success){
      // 在用设备添加成功，返回新添加的在用设备的id
      var sprId = $("#installSpr input[name=ext]").val();
      var devId = data;
      location.href="./controller/gaugeProcess.php?flag=installSpr&sprId="+sprId+"&devId="+devId;
    },'text');
  }

});

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

// 安装验收新设备
function sprIntall(id){
  var sprId = id;
    $("#ifStore").modal({
      keyboard:true
    });
  // $.get("./controller/gaugeProcess.php",{
  //   flag:"getSprDtlForInstal",
  //   id:sprId
  // },function(data,success){
  //   // {"id":"2","code":"510740110018","name":"超声波流量计","no":"TJZ-100B","unit":"个","num":"3","info":"无","basic":"2","checktime":"2016-10-13 16:26:16","storetime":"2016-10-13 16:34:33","installtime":null,"devid":null,"res":"5","see":"1","fid":"1","factory":"新区竖炉","did":"2","depart":"竖炉车间"}
  //   $("#installSpr input[name=code]").val(data.code);
  //   $("#installSpr input[name=name]").val(data.name);
  //   $("#installSpr input[name=no]").val(data.no);
  //   $("#installSpr input[name=number]").val(data.num);
  //   $("#installSpr input[name=nameFct]").val(data.factory);
  //   $("#installSpr input[name=nameDepart]").val(data.depart);
  //   $("#installSpr input[name=depart]").val(data.did);
  //   $("#installSpr input[name=factory]").val(data.fid);
  //   $("#installSpr input[name=ext]").val(sprId);
  //   $("#installSpr").modal({
  //     keyboard:true
  //   });
  // },"json");
}



    </script>
  </body>
</html>