<?php 
require_once "model/cookie.php";
require_once "model/repairService.class.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
checkValidate();
$user=$_SESSION['user'];

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyApv.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyStore($paging);
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
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
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
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件入账存库</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th>
            <th>存货编码</th><th>出厂编号</th><th>存货名称</th><th>规格型号</th><th>申报部门</th><th>入厂时间</th>
            <th style="width:4%"><span class="glyphicon glyphicon-tags" style='cursor:pointer;display:none'></span></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无新的入账存库的备件</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            // [id] => 11 [codeManu] => 123456 [code] => 78911 [name] => test2 [no] => 451kkk [depart] => 竖炉车间 [factory] => 新区竖炉 
            $addHtml = 
            "<tr>
                <td><a class='glyphicon glyphicon-unchecked' href='javascript:void(0);' chosen='{$row['id']}'></a></td>
                <td>{$row['code']}</td>
                <td>{$row['codeManu']}</td>
                <td><a href='javascript:flowInfo({$row['sprid']})'>{$row['name']}</td>
                <td>{$row['no']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td>{$row['checkTime']}</td>
                <td><a class='glyphicon glyphicon-tag' href='javascript:sprStore({$row['id']})'></a></td>
             </tr>";
             echo "$addHtml";

          }
        ?>
        </tbody>
        </table>
        <div class='page-count'><?php echo $paging->navi;?></div>                    
    </div>
    <div class="col-md-2">
    <div class="col-md-3">
    <?php  include "buyNavi.php";?>
    </div>
    </div>
</div>
</div>


<!-- 审核弹出框 -->
<!-- <div class="modal fade" id="storeSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件入账·申领</h4>
      </div>
      <form class="form-horizontal" action="controller/gaugeProcess.php" method="post">
        <div class="modal-body">
           <div class="form-group">
            <label class="col-sm-5 control-label">申报部门领取数量：</label>
            <div class="col-sm-7">
              <div class="col-sm-5" style="padding-left: 0px;">
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
            <input type="hidden" name="flag" value="storeSpr">
            <input type="hidden" name="id">
            <button type="submit" class="btn btn-primary" id="yesStoreSpr">确认</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </form>
    </div>
  </div>
</div> -->

<!-- 确认存库警告框 多个-->
<div class="modal fade"  id="ifStoreMulti" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">确定要入账存库您所选中的备件吗？</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" id='yesStoreMulti'>确定</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 确认存库警告框  单个-->
<div class="modal fade"  id="ifStoreOne" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">确定要入账存库该备件吗？</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" id='yesStoreOne'>确定</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<?php  include "./buyJs.php";?>
<script type="text/javascript">
$("#yesStoreOne").click(function(){
  var id = '["'+$(this).attr('store')+'"]';
  AjaxStore(id);
});

function AjaxStore(str){
   $.get("./controller/gaugeProcess.php",{
    flag:'storeSpr',
    idArr:str
  },function(data,success){
    if (data == 'success') {
      location.href="./buyStoreHouse.php";
    }
  },'text');
}

// 确认存库按钮
$("#yesStoreMulti").click(function(){
  var arr = new Array();
  var i = 0;
  $(".glyphicon-check").each(function(){
    arr[i] = $(this).attr('chosen');
    i++;
  });
  AjaxStore(JSON.stringify(arr));
});

// 多个备件同时入库
$(".glyphicon-tags").click(function(){
  $("#ifStoreMulti").modal({
    keyboard:true
  });
});

// 多选按钮
$(".tablebody").on("click","tr>td:first-child>a",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked");
    $(this).toggleClass("glyphicon glyphicon-check");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $(".glyphicon-tags").show();
    }else{
      $(".glyphicon-tags").hide();
    }
});

// 入账的备件数目加
$("#storeSpr #plus").click(function(){
  var num = parseInt($("#storeSpr input[name=num]").val());
  if (num != $(this).attr("max")) {
    num++;
    $("#storeSpr input[name=num]").val(num);
  }
});

// 入账的备件数目减
$("#storeSpr #minus").click(function(){
  var num = parseInt($("#storeSpr input[name=num]").val());
  if (num != 1) {
    num--;
    $("#storeSpr input[name=num]").val(num);
  }
});

function sprStore(id){
  $("#yesStoreOne").attr('store',id);
  $("#ifStoreOne").modal({
    keyboard:true
  });
}

    </script>
  </body>
</html>