<?php 
require_once "model/cookie.php";
require_once "model/repairService.class.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
// require_once 'model/dptService.class.php';
checkValidate();
$user=$_SESSION['user'];

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyStoreHouse.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
// 是否为搜索结果
if (empty($_POST['flag'])) {
  $gaugeService->buyStoreHouse($paging);
}else if ($_POST['flag'] == 'findStore') {
  $storeTime = $_POST['storeTime'];
  $depart = $_POST['dptId'];
  $code = $_POST['sprCode'];
  $name = $_POST['sprName'];
  $no = $_POST['sprNo'];

  $gaugeService->buyStoreFind($storeTime,$depart,$code,$name,$no,$paging);
}
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
<title>备件入账存库-仪表管理</title>
<style type="text/css">
.open > th, .open > td{
  background-color:#F0F0F0;
}

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
<!-- 领取安装弹出框 -->
<div class="modal fade" id="takeSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件领取安装</h4>
      </div>
      <form class="form-horizontal" action="controller/gaugeProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">申领部门：</label>
            <div class="col-sm-6">
              <div class="input-group">
              <input type="text" name="nDpt" class="form-control">
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
            <label class="col-sm-4 control-label">领取数量：</label>
            <div class="col-sm-6">
              <div class="input-group">
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
          <div class="modal-footer">
            <input type="hidden" name="flag" value="takeSpr">
            <input type="hidden" name="id">
            <input type="hidden" name="dptTk">
            <button class="btn btn-primary" id="yesTakeSpr">确认</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件当前存库</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th>
            <th>入库时间</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>申报总数</th><th>领取数量</th><th>库存数量</th>
            <th style="width:4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            if (empty($_POST['flag'])) {
              echo "<tr><td colspan=12>当前无仪表备件库存</td></tr>";
            }else{
              echo "<tr><td colspan=12>没有符合当前搜索条件的记录，请重新核实。</td></tr>";
            }
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
             // [id] => 1 [code] => 510740110018 [name] => 超声波流量计 [no] => TJZ-100B [num] => 3 [unit] => 个 
             // [storetime] => 2016-10-23 14:43:32 [resnum] => 1 [takenum] => 1
            $storeNum = $row['num'] - $row['resnum'] - $row['takenum'];
            $takeNum = $row['resnum'] + $row['takenum'];
            $remain = "<td><a class='glyphicon glyphicon-log-out' href='javascript:takeSpr({$row['id']},$storeNum);'></a></td>";

            $addHtml = 
            "<tr>
                <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='storeInfo(this,{$row['id']});'></a></td>
                <td>{$row['storetime']}</td><td>{$row['code']}</td>
                <td><a href='javascript:flowInfo({$row['id']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['num']} {$row['unit']}</td>
                <td>$takeNum {$row['unit']}</td>
                <td>$storeNum {$row['unit']}</td>
                ".$remain."
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
// 入账的备件数目加
$("#takeSpr #plus").click(function(){
  var num = parseInt($("#takeSpr input[name=num]").val());
  if (num != $(this).attr("max")) {
    num++;
    $("#takeSpr input[name=num]").val(num);
  }
});

// 入账的备件数目减
$("#takeSpr #minus").click(function(){
  var num = parseInt($("#takeSpr input[name=num]").val());
  if (num != 1) {
    num--;
    $("#takeSpr input[name=num]").val(num);
  }
});

// 确认领取备件按钮
$("#yesTakeSpr").click(function(){
  var notNull = $("#takeSpr input[name=nDpt]").val();
  if (notNull.length == 0) {
    $("#failAdd").modal({
      keyboard:true
    });
    return false;
  }
});

// // 部门搜索提示
//  $("#takeSpr input[name=nDpt]").bsSuggest({
//     allowNoKeyword: false,
//     showBtn: false,
//     indexId:2,
//     // indexKey: 1,
//     data: {
//          'value':<?php  ; ?>,
//          echo "$dptAll"
//     }
// }).on('onDataRequestSuccess', function (e, result) {
//     console.log('onDataRequestSuccess: ', result);
// }).on('onSetSelectValue', function (e, keyword, data) {
//    console.log('onSetSelectValue: ', keyword, data);
//    var idDepart=$(this).attr("data-id");
//    $(this).parents("form").find("input[name=dptTk]").val(idDepart);
// }).on('onUnsetSelectValue', function (e) {
//     console.log("onUnsetSelectValue");
// });

function takeSpr(id,storeNum){
  $("#takeSpr input[name=id]").val(id);
  $("#takeSpr input[name=num]").val(storeNum);
  $("#takeSpr #plus").attr("max",storeNum);
  $("#takeSpr").modal({
    keyboard:true
  });
}

function storeInfo(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getStoreInfo',
      sprId:id
    },function(data,success){
      // {"storetime":"2016-10-23 14:43:32","num":"3","resnum":"1",
      // "0":[{"taketime":"2016-10-23 15:13:33","takenum":"1","depart":"能源部","factory":"办公楼"}]}
      var addHtml = "<tr class='open-"+id+"'>"+
                    "   <td colspan='12'>"+
                    "     <div class='row'>"+
                    "       <div class='col-md-12'>"+
                    "         <p><b>"+data.storetime+"：</b> 入账总数 "+data.num+data.unit+" ，由原申报部门领取 "+data.resnum+data.unit+"</p>";
      for (var i = 0; i < data.take.length; i++) {
        // data.take[i]
        addHtml += "<p><b>"+data.take[i].taketime+"：</b>"+data.take[i].factory+data.take[i].depart+" 领取 "+data.take[i].takenum+data.unit+"</p>";
      }
      addHtml +=   "       </div>"+
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

    </script>
  </body>
</html>