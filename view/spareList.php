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

<title>备用设备-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
/*  thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }*/
</style>
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
 ?>
  <?php
  require_once '../model/spareService.class.php';
  require_once '../model/paging.class.php';
  // require_once '../controller/spareProcess.php';


  $paging=new paging();
  $paging->pageNow=1;
  $paging->pageSize=17;
  $paging->gotoUrl="spareList.php";
  if (!empty($_GET['pageNow'])) {
    $paging->pageNow=$_GET['pageNow'];
  }
  // 得到当前页数
  $spareService=new spareService();

  if (empty($_REQUEST['flag']) || $_GET['classId']=="0") {
    $spareService->getPaging($paging);
  }else if ($_REQUEST['flag']=="findSpare") {
    if(!empty($_['brand'])){  
      $brand=$_POST['brand'];
    }else{
      $brand="";
    }

    if(!empty($_POST['devid'])){  
      $devid=$_POST['devid'];
    }else{
      $devid="";
    }

    if(!empty($_POST['name'])){  
      $name=$_POST['name'];
    }else{
      $name="";
    }

    if(!empty($_POST['no'])){  
      $no=$_POST['no'];
    }else{
      $no="";
    }
    $spareService->findSpare($brand,$devid,$name,$no,$paging);
  }else if ($_REQUEST['flag']=="getByClass") {
    $typeName=$_GET['class'];
    $typeId=$_GET['classId'];

    $spareService->getByClass($typeId,$typeName,$paging);

  }
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
        <li class="dropdown active">
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
       <li><a href="dptUser.php">用户管理</a></li>
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
    <div class="col-md-3">
    <ul class="nav nav-stacked nav-pills nav-self">
        <li class="nav-header" style="cursor: pointer;" id="getByAll">全部：
          <span class="glyphicon glyphicon-cog" style="float: right;margin-right: 10px;margin-top:3px" id="setType"></span>
          <span id="setOp" style="width: 50px;display: none;font-size: 15px;float: right;" >
            <a class="glyphicon glyphicon-plus-sign" style="margin-right:5px;cursor: pointer;text-decoration: none" data-toggle="modal" data-target="#typeAdd"></a>
            <a class="glyphicon glyphicon-trash" style="cursor: pointer;text-decoration: none" data-toggle="modal" data-target="#typeDel"></a>
          </span>
        </li>
        <?php 
          if (empty($typeId)||$typeId==0) {
            $type=$spareService->getTypeDad();
            for ($i=0; $i < count($type); $i++) { 
              echo "<li id='{$type[$i]['id']}'><a href=\"javascript:getByClass('{$type[$i]['name']}','{$type[$i]['id']}');\">{$type[$i]['name']}</a></li>";
            }
          }else{
            $rootPa=$spareService->getTypePa($typeId);
            // print_R($rootPa);
            for ($i=0; $i < count($rootPa); $i++) { 
              if ($i!=count($rootPa)-1) {
                echo "<li id='{$rootPa[$i]['id']}'>
                        <a id='{$rootPa[$i]['id']}' href=\"javascript:getByClass('{$rootPa[$i]['name']}',$rootPa[$i]['id']);\">{$rootPa[$i]['name']}</a>
                      </li>";
              }else{
                // echo "{$rootPa[$i]['id']}";
                echo "<li id='{$rootPa[$i]['id']}'>
                        <a id='{$rootPa[$i]['id']}' href=\"javascript:getByClass('{$rootPa[$i]['name']}',{$rootPa[$i]['id']});\">{$rootPa[$i]['name']}</a>
                      </li>";
              }
            }
            echo "<li class='active' id='{$typeId}'>
                    <a id='{$typeId}' href=\"javascript:getByClass('{$typeName}',$typeId);\">$typeName</a>
                  </li>";
          }
        ?>
        
      </ul>
    </div>
      <div class="col-md-9">
          <div class="page-header">
              <h4>&nbsp;&nbsp;备品备件
              <span class="badge-button" data-toggle="modal" data-target="#spareFind"><span class="glyphicon glyphicon-paperclip"></span> 按条件查找</span>　
              <span class="badge-button" data-toggle="modal" data-target="#addSpare"><span class="glyphicon glyphicon-plus"></span> 添加备用设备</span>
              </h4>
          </div>
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>设备编号</th><th>设备名称</th><th>设备型号</th><th>设备品牌</th><th>部门</th><th>分厂</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
              </tr></thead>
            <tbody class="tablebody">  
              <?php
              // [id] => 141 [code] => [name] => 指示灯(绿) [no] => AD16-22D/-S [brand] => [depart] => 风机房 [factory] => 办公楼
              for ($i=0; $i < count($paging->res_array); $i++) { 
                $row=$paging->res_array[$i];
                echo "<tr><td>{$row['code']}</td><td><a href='spare.php?id={$row['id']}'>{$row['name']}</td>
                          <td>{$row['no']}</td><td>{$row['brand']}</td>
                          <td>{$row['depart']}</td><td>{$row['factory']}</td>
                          <td><a href=javascript:delSpare({$row['id']}) class='glyphicon glyphicon-trash'></a></td></tr>";
              }
              ?>
            </tbody>
          </table>
          <div id="null_info">
           <div class="null_info_suggest">
              <span class="null_info_add">该设备下暂时没有子设备，可点击添加</span>  
              <span class="badge-button" data-toggle="modal" data-target="#typeAdd">
                <span class="glyphicon glyphicon-plus"></span> 添加设备
              </span>
          </div>
          </div>   
          <?php
          echo "<div class='page-count'>$paging->navi</div>";
          ?>
      </div>

</div> 
</div>
<!-- 添加新的设备弹出框 -->
<form class="form-horizontal" action="../controller/spareProcess.php" method="post">
  <div class="modal fade" id="addSpare" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">添加新的备用设备</h4>
        </div>
        <div class="modal-body" id="addSpare">
        <div class="row">
          <div class="col-md-6 add-left">
              <div class="form-group">
                <label class="col-sm-4 control-label">设备编号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="code" placeholder="请输入新设备编号">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">设备名称：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="name" placeholder="请输入新设备名称(不可为空)">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">设备型号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="no" placeholder="请输入新设备型号">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">当前数量：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="number" placeholder="请输入当前设备总数量">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">购入价格：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="price" placeholder="请输入购入价格">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">所属品牌：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="brand" placeholder="请输入新设备品牌">
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-4 control-label">所属类别：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                  <input type="text" name="class" class="form-control notNull" placeholder="请搜索要设备类别(不可为空)">
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
            <div class="col-md-6 add-right">
              <div class="form-group">
                <label class="col-sm-4 control-label">出厂日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateManu" readonly placeholder="请选择新设备的出厂日期">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">报废日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateEnd" readonly placeholder="请选择新设备的出厂日期">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">有效期止：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="periodVali" readonly placeholder="请选择新设备的有效期止">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">供应商：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="supplier" placeholder="请输入新设备的供应源">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">所属分厂：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="factory" placeholder="请输入新设备所在分厂(不可为空)">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">所在部门：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="depart" placeholder="请输入新设备所在部门(不可为空)">
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addSpare">
          <button type="submit" class="btn btn-primary" id="addSpareYes">确认添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 
<!-- 确认更换提示框 -->
<div class='modal fade'  id='sureRepair'>
  <div class='modal-dialog modal-sm' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>确定要添加设备吗？<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="sure">确定</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 在该配置柜下添加新设备弹出框 -->
<form class="form-horizontal" action="../controller/spareProcess.php" method="post">
  <div class="modal fade" id="typeAdd" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新的类别</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">所属父类型：</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" name="nameDad" class="form-control" placeholder="请输入设备所属父类型">
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
            <label class="col-sm-4 control-label">新增类别名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="typeNew" placeholder="请先选择父类型" readonly>
            </div>
          </div>
        </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addType">
            <button type="submit" class="btn btn-primary" id='typeYes'>确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
      </div>
    </div>
  </div>  
</form> 
<!-- 删除指定备用设备类型 -->
<form class="form-horizontal" action="../controller/spareProcess.php" method="post">
  <div class="modal fade" id="typeDel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">删除指定类型</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">设备类型：</label>
            <div class="col-sm-7">
              <div class="input-group">
                <input type="text" name="nameDel" class="form-control" placeholder="请输入要删除的备用设备类型">
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
          <div class="modal-footer">
            <input type="hidden" name="flag" value="delType">
            <button type="submit" class="btn btn-primary" id='delYes'>确认删除</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
      </div>
    </div>
  </div>  
</form> 



<!-- 删除设备信息提示框 -->
<div class='modal fade'  id='delSpare'>
  <div class='modal-dialog modal-sm' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>确定要删除该设备信息吗？<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="del">删除</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="spareFind" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索备件</h4>
      </div>
      <form class="form-horizontal" method="post" action="spareList.php">
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">设备名称：</label>
          <div class="col-sm-7">
            <div class="input-group">
            <input type="text" class="form-control" name="name" placeholder="请输入要搜索的设备名称">
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
        <label class="col-sm-3 control-label">设备型号：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备型号" name="no">
        </div>
      </div>
     
      <div class="form-group">
        <label class="col-sm-3 control-label">所属品牌：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备品牌" name="brand">
        </div>
      </div>
           

      </div>
      <div class="modal-footer" style="padding-right:20px;">
        <input type="hidden" name="devid">
        <input type="hidden" name="flag" value="spareFind">
        <button class="btn btn-primary" id="yesFind">搜索</button>
        <button  class='btn btn-default' data-dismiss='modal'>关闭</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- 添加设备信息不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的设备信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

  </body>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    <script type="text/javascript">
    $("#getByAll").click(function(){
      getByClass("全部",0);
    }
    );

    // 点击左侧类别搜索备件
    function getByClass(typeName,typeId){
     location.href="spareList.php?flag=getByClass&class="+typeName+"&classId="+typeId;
    }

    // 搜索框中数据不可全为空
    $("#yesFind").click(function(){
      var flag=false;
      $("#spareFind .form-control").each(function(){
        if ($(this).val().length>0){
          flag=true;
        }
      });
      if (flag==false) {
         $('#failAdd').modal({
            keyboard: true
         });
      }
      return flag;
    });

    $("#spareFind input[name=name]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        inputWarnColor:"#f5f5f5",
        indexId:1,
        // indexKey: 1,
        data: {
             'value':<?php 
              $allName=$spareService->getNameAll();
              echo "$allName";
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
       var devid=$(this).attr("data-id");
       $("#spareFind input[name=devid]").val(devid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    // 添加备件时类别搜索提示
    $("#addSpare input[name=class]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        // indexId:1,
        // indexKey: 1,
        data: {
             'value':<?php 
              $allType=$spareService->getTypeAll();
              echo "$allType";
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    // 类别设置按钮
    $("#setType").click(function(){
      $("#setOp").toggle();
    });

    // 添加类别为空时，弹出提示框
    $("#typeYes").click(function(){
       var allow_submit = true;
       $("#typeAdd  .form-control").each(function(){
          if($(this).val()==""){
            $('#failAdd').modal({
                keyboard: true
            });
            allow_submit = false;
          }
       });
       return allow_submit;
    })

    // 父设备搜索提示
    var testdataBsSuggest = $("input[name=nameDad]").bsSuggest({
        showBtn: false,
        allowNoKeyword: false,
        indexKey: 0,
        data: {
          'value':<?php 
            $info=$spareService->getTypeAll();
            echo $info;
          ?>,
          'defaults':'没有相关设备请另查询或添加新的设备'
          }
        }).on('onDataRequestSuccess', function (e, result) {
          console.log('onDataRequestSuccess: ', result);
        }).on('onSetSelectValue', function (e, keyword, data) {
          console.log('onSetSelectValue: ', keyword, data);
          $("input[name=typeNew]").removeAttr("readonly").attr("placeholder","请输入新的类别");
      }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
      });


      // 删除设备信息填写不完整，弹出提示框
    $("#delYes").click(function(){
       var allow_submit = true;
       $("#typeDel  .form-control").each(function(){
          if($(this).val()==""){
            $('#failAdd').modal({
                keyboard: true
            });
            allow_submit = false;
          }
       });
       return allow_submit;
    })

    // 指定备用设备类型删除
    var testdataBsSuggest = $("input[name=nameDel]").bsSuggest({
        showBtn: false,
        allowNoKeyword: false,
        indexKey: 0,
        data: {
          'value':<?php 
            $info=$spareService->getTypeAll();
            echo $info;
          ?>,
          'defaults':'没有相关设备请另查询或添加新的设备'
          }
        }).on('onDataRequestSuccess', function (e, result) {
          console.log('onDataRequestSuccess: ', result);
        }).on('onSetSelectValue', function (e, keyword, data) {
          console.log('onSetSelectValue: ', keyword, data);
      }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
      });

    // 点击导航头，显示全部类别
    $("li.nav-header").click(function(){
        var $addHtml="";
        $.get("../controller/spareProcess.php",{
          flag:"getTypeDad"
        },function(data,success){
          $("li.nav-header").siblings().detach();
          for (var i = 0; i < data.length; i++) {
            $addHtml+="<li id='"+data[i].id+"'><a href=\"javascript:getByClass('"+data[i].name+"');\">"+data[i].name+"</a></li>";
          } 
          $("li.nav-header").after($addHtml);
        },"json");   
    })

    $(function(){
      var typeId="<?php
        if (empty($_GET['classId']) || $_GET['classId']==0) {
          echo '';
        }else{
          echo $_GET['classId'];
        }
      ?>"
      if (typeId=="") {
        return false;
      }
      var $thisvar=$("#"+typeId);
      // alert($thisvar.html());
      var $addHtml="<ul class='nav nav-stacked nav-pills nav-self'>";
      var id=typeId;    
      $.get("../controller/spareProcess.php",{
        flag:"getType",
        pid:id
      },function(data,success){
        for (var i = 0; i < data.length; i++) {
           $addHtml+="<li id='"+data[i].id+"'><a href=\"javascript:getByClass('"+data[i].name+"',"+data[i].id+");\">"+data[i].name+"</a></li>";
        }
        $addHtml+="</ul>";
        // $thisvar.parent().prev().removeClass('active');
        // $thisvar.addClass('active').siblings().not(".nav-header").detach();
        $thisvar.after($addHtml);
      },"json"); 
    })


    // 点击显示相应子类别
    // $("ul.nav-pills").on("click","li:not(.nav-header)",pillTree);
    // function pillTree(){
    //   var $thisvar=$(this);
    //   var $addHtml="<ul class='nav nav-stacked nav-pills nav-self'>";
    //   var id=$(this).attr("id");    
    //   $.get("../controller/spareProcess.php",{
    //     flag:"getType",
    //     pid:id
    //   },function(data,success){
    //     for (var i = 0; i < data.length; i++) {
    //        $addHtml+="<li id='"+data[i].id+"'><a href=\"javascript:getByClass('"+data[i].name+"');\">"+data[i].name+"</a></li>";
    //     }
    //     $addHtml+="</ul>";
    //     $thisvar.parent().prev().removeClass('active');
    //     $thisvar.addClass('active').siblings().not(".nav-header").detach();
    //     $thisvar.after($addHtml);
    //   },"json"); 
    // }

    <?php 
      // $page_count=count($paging->res_array);
    ?>
    $(function(){
      var count_page=1;
      // <?php echo $page_count;?>;
      // alert("hello world");
      if (count_page==0) {
        $("#null_info").show();
      }
    });
    //弹出框
    $(function() 
      { $("[data-toggle='popover']").popover();
    });
     //时间选择器
     $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2
    });
     
     function delSpare(id){
      var $id =id;
      $('#delSpare').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/spareProcess.php?flag=delSpare&id="+$id;
      });            
    }

    $("#addSpareYes").click(function(){
     var allow_submit = true;
     $("#addSpare .notNull").each(function(){
      if($(this).val()==""){
            $('#failAdd').modal({
              keyboard: true
            });
            allow_submit = false;
          }
        });
     return allow_submit;
   });



    
    </script>
</html>