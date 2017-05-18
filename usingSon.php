<?php 
require_once "model/cookie.php";
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

<title>设备具体信息-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1){
      width: 3%;
  }
</style>
<link rel="stylesheet" type="text/css" href="bootstrap/css/printview.css">
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
<script src="bootstrap/js/Chart.js"></script>
<script src="bootstrap/js/chartEffects.js"></script>
<script src="bootstrap/js/chartModernizr.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
</head>
<body role="document">
<?php 
include "message.php";
require_once 'model/devService.class.php';
$id=$_GET['id'];
$devService=new devService();
$arr=$devService->getDevById($id);
// echo "<pre>";
// print_r($arr);
// echo "</pre>";
// exit();
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
        <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button">设备购置 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="buyGauge.php">仪表备件申报</a></li>
          </ul>
        </li>
        <li class="dropdown active">
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
<form action="controller/devProcess.php" method="post" id="updateForm">
<div class="row">
  <div class="col-md-2">
    <div class="printview">
      <h5>父设备维修 / 次</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-wrench"></span></div>
      <div class="col-md-5"><?php
      $path=explode("-",$arr[0]['path']);
      $idRoot=$path[1];
      $frequency=$devService->frequency($idRoot);
      echo $frequency['count(id)'];
      ?></div>
      </div>
    </div>

    <div class="printview">
      <h5>运行状态</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-modal-window"></span></div>
      <div class="state col-md-5"><?php echo $arr[0]['state']?></div>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="printview printview-second">
      <h5>小时成本 / 元</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-jpy"></span></div>
      <div class="col-md-5"><?php 
        $hourCost=$devService->hourCost($arr[0]['dateInstall'],$arr[0]['dateEnd'],$arr[0]['price']);
        echo $hourCost;
      ?></div>
      </div>
    </div>
    <div class="printview printview-second" > 
      <?php  $timediff = $devService->timediff($arr[0]['dateInstall'],$arr[0]['dateEnd']);?>
      <h5>运行时间 / <?php echo $timediff[1]; ?></h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-calendar"></span></div>
      <div class="col-md-5">
        <?php  echo "$timediff[0]";?></div>
      </div>
    </div>
  </div>

  <div class="col-md-7 detail">
  <h4>设备基本信息：</h4>
  <div class="row">
  <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-addon">设备名称</span>
      <input type="text" class="form-control" name="name" value="<?php echo $arr[0]['name']; ?>" readonly>
    </div> 
    <div class="input-group">
      <span class="input-group-addon">设备编号</span>
      <input type="text" class="form-control" name="code" value="<?php echo $arr[0]['code']; ?>" readonly>  
    </div>  
    <div class="input-group">
      <span class="input-group-addon">设备型号</span>
      <input type="text" class="form-control" name="no" value="<?php echo $arr[0]['no'] ?>" readonly>
    </div>

    <div class="input-group">
      <span class="input-group-addon">设备类别</span>
      <input type="text" class="form-control" name="class" value="<?php echo $arr[0]['class'] ?>" readonly>
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

  <div class="col-md-6">
    <div class="input-group">
      <span class="input-group-addon">当前数量</span>
      <input type="text" class="form-control" name="number" value="<?php echo $arr[0]['number'] ?>" readonly>
    </div> 

    <div class="input-group">
      <span class="input-group-addon">所属设备</span>
      <input type="text" class="form-control" name="parent" value="<?php echo $arr[0]['parent'] ?>" readonly>
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
        </ul>
      </div>
      <!-- /btn-group -->
    </div>
 
 
<div class="input-group">
  <span class="input-group-addon">所在分厂</span>
  <input type="text" name="nameFct" class="form-control notNull" value="<?php echo $arr[0]['factory'] ?>" readonly="readonly">
  <div class="input-group-btn">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
    </ul>
  </div>
  <!-- /btn-group -->
</div>

    <div class="input-group">
      <span class="input-group-addon">所在部门</span>
      <input type="text" name="nameDepart" class="form-control notNull" value="<?php echo $arr[0]['depart'] ?>" readonly="readonly">
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
</div>
<!-- 更多具体信息 -->
<div class="row">
  <div class="col-md-12">
    <div class="accordion">
      <div class="accordion-group">
        <div class="accordion-body">
          <div class="accordion-inner-spare">
          
            <div class="row detail-info">
              <h4 style='margin-left: 20px;'>负责人员：</h4>
              <?php
                $addHtml="";
                for ($i=0; $i < count($arr[1]); $i++) { 
                  $addHtml.="<div class='col-md-4'>
                                  <div class='input-group'>
                                    <span class='input-group-addon'>负责人员</span>
                                    <input type='text' class='form-control' name='paraid[{$arr[1][$i]['uid']}]' value='{$arr[1][$i]['name']}' disabled>
                                  </div> 
                                </div>";
                }
                  echo "$addHtml";
              ?>
            </div>
            <div class="row detail-info">
              
                <?php 
                  $detail=$devService->getDetail($id);
                  if (count($detail)!=0) {
                    echo "<h4 style='margin-left: 20px;'>属性参数：</h4>";
                  }
                  $addHtml="";
                  for ($i=0; $i < count($detail); $i++) { 
                      $addHtml.="<div class='col-md-4'>
                                  <div class='input-group'>
                                    <span class='input-group-addon'>{$detail[$i]['name']}</span>
                                    <input type='text' class='form-control' name='paraid[{$detail[$i]['paraid']}]' value='{$detail[$i]['paraval']}' readonly>
                                  </div> 
                                </div>";

                    }
                    echo "$addHtml";
                ?>
                    
            </div>
            <div class="row detail-info">
              <h4 style="margin-left: 20px;">具体信息：</h4> 
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">安装日期</span>
                  <input type="text" class="form-control datetime" name="dateInstall" value="<?php echo $arr[0]['dateInstall']; ?>" readonly>
                </div> 
                <div class="input-group">
                  <span class="input-group-addon">出厂日期</span>
                  <input type="text" class="form-control datetime" name="dateManu" value="<?php echo $arr[0]['dateManu']; ?>" readonly>
                </div> 
                <div class="input-group">
                  <span class="input-group-addon">报废日期</span>
                  <input type="text" class="form-control datetime" name="dateEnd" value="<?php echo $arr[0]['dateEnd']; ?>" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">有效期止</span>
                  <input type="text" class="form-control datetime" name="periodVali" value="<?php echo $arr[0]['periodVali']; ?>" readonly>
                </div>
                <div class="input-group">
                  <span class="input-group-addon">供应厂商</span>
                  <input type="text" class="form-control" name="supplier" value="<?php echo $arr[0]['supplier']; ?>" readonly>
                </div>
                <center>
                 <div class="input-group">
                  <input type="hidden" name="id" value="<?php echo $arr[0]['id'];?>">
                  <input type="hidden" name="pid" value="<?php echo $arr[0]['pid'];?>">
                  <input type="hidden" name="flag" value="updateDev">
                   <input type="hidden" name="depart" value="<?php echo $arr[0]['did'];?>">
                  <input type="hidden" name="factory" value="<?php echo $arr[0]['fid'];?>">
                  <a class="btn btn-default using-btn"><span class="glyphicon glyphicon-link"></span> 设备说明书 / 图纸</a>
                  <button class="btn btn-primary using-btn" id="updateInfo">修改设备信息</button>
                 </div>
                 </center>            
              </div> 
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon">购入价格</span>
                  <input type="text" class="form-control" name="price" value="<?php echo $arr[0]['price'] ?>" readonly>
                </div>  
                  <div class="input-group">
                  <span class="input-group-addon">所属品牌</span>
                  <input type="text" class="form-control" name="brand" value="<?php echo $arr[0]['brand'] ?>" readonly>
                </div> 

              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</form>
<div class="row">
  <div class="col-md-12">
    <div class="accordion" id="Info">   
      <div class="accordion-group"> 
        <h4 style="margin-left: 20px;margin-top: 15px;margin-bottom: -5px">日常更换 \ 性能对比:</h4>
       <div class="accordion-body">
          <div class="accordion-inner-spare">
            <div class="row">
              <!-- 设备更换记录表 -->
              <div class="col-md-6 table-responsive graph" id="install_table">
                <table class="table table-striped table-hover">
                    <thead><tr>
                      <th>编号</th><th>名称</th><th>更换时间</th><th>价格</th>
                      <th><a href="javascript:chgeDev(<?php echo $id;?>)" class="glyphicon glyphicon-transfer"></a></th>
                    </tr></thead>
                    <tbody>
                      <?php
                        $chgInfo=$devService->getChgInfo($id);
                        // [info] => test reason [nid] => 189 [name] => 电流表 [dateInstall] => 2016-07-16 [supplier] => testChge2 
                        for ($i=0; $i < count($chgInfo); $i++) {
                          echo "<tr><td>{$chgInfo[$i]['id']}</td>
                                    <td><a href='usingSon.php?id={$chgInfo[$i]['id']}'>{$chgInfo[$i]['name']}</a></td>
                                    <td>{$chgInfo[$i]['dateInstall']}</td><td>{$chgInfo[$i]['price']}</td>
                                    <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='openInfo(this,{$chgInfo[$i]['id']})'></a></td>
                                <tr style='display:none' id='change-{$chgInfo[$i]['id']}'>
                                  <td colspan='12'> 
                                  <p><b>更换原因：</b>{$chgInfo[$i]['info']}</p>
                                  <p><b>供应商：</b>{$chgInfo[$i]['supplier']}</p>
                                  </td>  
                                </tr>";
                        }
                          // print_r(array_column($chgInfo,'price'));
                          // exit();
                      ?>
                    </tbody>
                  </table>
                </div>
                    <div class="col-md-5 chart" style="width:45% !important">
                      <caption>不同型号的对比　<span class="badge_a">购入价格</span> / <span class="badge_b">小时成本</span></caption>
                      <div id="barChart" class="canvasWrapper">
                        <canvas id="barChartCanvas" width="475px" height="300px"></canvas> 
                      </div>
                      <div id="lineChart" style="display:none" class="canvasWrapper">
                        <canvas id="lineChartCanvas" width="475px" height="300px"></canvas>    
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
</div>
</div>

<!-- 确认修改弹出框 -->
<div class="modal fade"  id="confirm">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <br/>确定要提交修改吗？<br/><br/>
      </div>
      <div class="modal-footer">  
        <button class="btn btn-primary" id="confirmYes">确定</button>
        <button class="btn btn-danger" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>   


<!-- 确认更换提示框 -->
<div class='modal fade'  id='failChg'>
  <div class='modal-dialog modal-sm' role='document' style='margin-top: 150px'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>该设备已被更换。<br/><br/>
      </div>
      <div class='modal-footer'>  
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

 <!-- 添加新的巡检记录 -->
  <div class="modal fade" id="addInspect">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新的巡检记录</h4>
        </div>
        <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control datetime" name="inspectTime" readonly="readonly">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检状态：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="devState" value="正常"> 正常
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState" value="需维修"> 需维修
              </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">巡检结果：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspectTime" readonly="readonly">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入巡检基本分析..."></textarea>
              </div>
            </div>   
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInspect">
              <input type="hidden" name="return" value="list">
              <button type="submit" class="btn btn-primary" id="add">确认添加</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>


<!-- 添加更换记录 -->
<div class="modal fade" id="chgeDev">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">更换该设备</h4>
      </div>
  <form class="form-horizontal" action="controller/devProcess.php" method="post">
      <div class="modal-body">
      <div class="form-group">
        <label class="col-sm-3 control-label">供应厂商：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name='n_supplier'>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">所属品牌：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name='n_brand'>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">购入价格：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name='n_price'>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">安装日期：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control datetime" readonly="readonly" name='n_dateInstall' placeholder="不可为空">
        </div>
      </div>

       <div class="form-group">
        <label class="col-sm-3 control-label">出厂日期：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control datetime" readonly="readonly" name="n_dateManu">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">有效期止：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control datetime" readonly="readonly" name='n_periodVali'>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">更换原因：</label>
        <div class="col-sm-7">
          <textarea class="form-control" rows="2" name="info"></textarea>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="flag" value="chgeDev">
        <input type="hidden" name="oid" value="<?php echo $id?>">
        <button class="btn btn-primary" id="yesChge">更换</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
      </div>
  </form>
    </div>
  </div>
</div>



<!-- 添加记录不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 权限警告 -->
<div class="modal fade"  id="failAuth">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您无权限进行此操作。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<script type="text/javascript">
var session = <?php echo json_encode($_SESSION,JSON_UNESCAPED_UNICODE); ?>;
var user = session.user;
function allow_enter(funcid){
  var allow = $.inArray(funcid.toString(),session.funcid);
  if (user == "admin") {
    allow = 0;
  }
  return allow;
}

function openInfo(obj,id){
  $("#change-"+id).toggle();
  $(obj).toggleClass("glyphicon glyphicon-resize-small");
  $(obj).toggleClass("glyphicon glyphicon-resize-full");     
}


// 更换设备确认按钮
$("#yesChge").click(function(){
     var allow_submit = true;
     var $dateInstall=$("#chgeDev input[name=n_dateInstall]").val();
     if ($dateInstall=="") {
       $('#failAdd').modal({
              keyboard: true
        });
        allow_submit = false;
     }
     return allow_submit;
});

// 更换记录时的时间选择
$("#chgeDev .datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2
});

// 更换设备
function chgeDev(id){
  var enter = allow_enter(1);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
    if ($(".state").text()=="更换") {
      $('#failChg').modal({
          keyboard: true
     });
    }else{
     $('#chgeDev').modal({
          keyboard: true
     });
    }
  }
}

// 已确定添加的设备删除
$(document).on("click",".glyphicon-remove",delDeved)
function delDeved(){
  $(this).parents("span").detach();
}

// 修改当前设备管理员
function updateLia(id){
  $("#updateLia #forLia").empty();
  $("#updateLia input[name=devid]").val(id);
      $.get("controller/devProcess.php",{
        id:id,
        flag:"getLia"
      },function(data,success){
        var idArr=new Array();
         for(var i=0;i<data.length;i++){
            idArr[i]=data[i].id
            var addHtml="<span class='badge'>"+data[i].name+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='rem[]' value="+data[i].id+"></span> "
            $("#updateLia #forLia").append(addHtml);
         }
         $("#updateLia input[name=oid]").val(idArr);
         $('#updateLia').modal({
              keyboard: true
         });
      },"json");
}

// 查看设备负责人变更记录
function manHstr(id){
 $.get("controller/devProcess.php",{
  flag:'getCon',
  id:id
 },function(data,success){
  // {"id":"1","uid":"2","devid":"45","time":"2016-06-20","info":"开始管理","name":"test1"},
  var addHtml="";
  for(var i=0;i<data.length;i++){
    addHtml+="<tr><td>"+data[i].id+"</td><td>"+data[i].time+"</td><td>"+data[i].end+"</td><td title='用户id："+data[i].uid+"'>"+data[i].name+"</td></tr>";
  }
  $("#manHstr tbody").empty();
  $("#manHstr tbody").append(addHtml);
 $('#manHstr').modal({
      keyboard: true
  });
 },'json');

}

// 添加设备负责人
function addLiable(id){
 $('#addLiable').modal({
      keyboard: true
  });
}

      //弹出框
      $(function(){
       $("[data-toggle='popover']").popover(); 
     });

      $(window).load(function() {   
        var globalGraphSettings = {animation : Modernizr.canvas};
        var graphInitDelay = 300; 

      var barChartData = {
      labels:<?php 
                if (!empty($chgInfo)) {
                  $chgName=array_column($chgInfo,'id');
                  $chgName=json_encode($chgName,JSON_UNESCAPED_UNICODE);
                }else{
                  $chgName='[\'当\',\'前\',\'无\',\'数\',\'据\',\'对\',\'比\']';
                }
                echo "$chgName";
              ?>,
      datasets : [{
        fillColor : 'rgba(8,31,52,0.9)',
        strokeColor : 'rgba(220,220,220,1)',
        data:<?php 
                if (!empty($chgInfo)) {
                  $price=array_column($chgInfo,'price');
                  $chgPrc=json_encode($price,JSON_UNESCAPED_UNICODE);
                }else{
                  $chgPrc='[7,6,0,2,3,4,5]';
                }
                echo "$chgPrc";
              ?>
      }]};
      
      var lineChartData = {
        labels :<?php 
                if (!empty($chgInfo)) {
                  $chgName=array_column($chgInfo,'id');
                  $chgName=json_encode($chgName,JSON_UNESCAPED_UNICODE);
                }else{
                  $chgName='[\'当\',\'前\',\'无\',\'数\',\'据\',\'对\',\'比\']';
                }
                echo "$chgName";
              ?>,
        datasets : [{
            fillColor : 'rgba(151,187,205,1)',
            strokeColor : 'rgba(151,187,205,1)',
            data:<?php 
                    $hrCst="";
                    if(!empty($chgInfo)){
                      $hrCstArr=array();
                      $start=array_column($chgInfo,'dateInstall');
                      $end=array_column($chgInfo,'dateEnd'); 
                      for ($i=0; $i < count($chgInfo); $i++) {
                          $hrCstArr[$i]=$devService->hourCost($start[$i],$end[$i],$price[$i]);
                      }
                      $hrCst=json_encode($hrCstArr,JSON_UNESCAPED_UNICODE);
                    }else{
                      $hrCst='[7,6,0,2,3,4,5]';
                    }
                    echo "$hrCst";
                   ?>
      }]};

        // 小时成本显示图表--虽然名字是LineChart,但显示的是目的是显示的柱状图！！！！！！
        function showLineChart(){
          var ctx = document.getElementById("lineChartCanvas").getContext("2d");
          new Chart(ctx).Bar(lineChartData,globalGraphSettings);
        };

        $("#lineChart").on("inview",function(){
          var $this = $(this);
          $this.removeClass("hidden").off("inview");
          setTimeout(showLineChart,graphInitDelay);
        });

        // 购入价格显示图表--柱状图
        function showBarChart(){
          var ctx = document.getElementById("barChartCanvas").getContext("2d");
          new Chart(ctx).Bar(barChartData,globalGraphSettings);
        };   
          
        //Set up each of the inview events here.
        $("#barChart").on("inview",function(){
          var $this = $(this);
          $this.removeClass("hidden").off("inview");
          setTimeout(showBarChart,graphInitDelay);
        });

        $(".badge_a").click(function(){
          $("#barChart").show();
          $("#lineChart").hide();
        });
        $(".badge_b").click(function(){
          $("#lineChart").show();
          $("#barChart").hide();
        });

        $(".badge_c").click(function(){
          $("#inspect_table").show();
          $("#install_table").hide();
          $("#repair_table").hide();
        });
        $(".badge_d").click(function(){
          $("#install_table").show();
          $("#inspect_table").hide();
          $("#repair_table").hide();
        });
        $(".badge_e").click(function(){
          $("#install_table").hide();
          $("#inspect_table").hide();
          $("#repair_table").show();
        });
      });

      $("#updateInfo").click(function(){
        var enter = allow_enter(1);
        if (enter == -1) {
            $('#failAuth').modal({
              keyboard: true
            });
        }else{
          if($(".form-control").prop("readonly")){
            $("#updateForm .form-control").not("input[name=nameDepart]").removeAttr("readonly");
            $(this).text("提交修改");
            // 时间选择器
            $(".datetime").datetimepicker({
              format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2
            });

            // 分厂搜索提示，并根据所选调用部门搜索函数
            $("input[name=nameFct]").bsSuggest({
                allowNoKeyword: false,
                // showBtn: false,
                indexId:1,
                // indexKey: 1,
                data: {
                     'value':<?php 
                      $allFct=$devService->getFctAll();
                      echo "$allFct";
                      ?>,
                }
            }).on('onDataRequestSuccess', function (e, result) {
                console.log('onDataRequestSuccess: ', result);
            }).on('onSetSelectValue', function (e, keyword, data) {
               console.log('onSetSelectValue: ', keyword, data);
               var idFct=$(this).attr("data-id");
               $(this).parents("form").find("input[name=factory]").val(idFct);
               var $depart=$(this).parents("form").find("input[name=nameDepart]"); 
               $.get("controller/devProcess.php",{
                flag:'getDptAll',
                idFct:idFct
               },function(data,success){
                var departAll=data;

                $depart.removeAttr("readonly");
                 // 部门搜索提示
                $depart.bsSuggest({
                    allowNoKeyword: false,
                    // showBtn: false,
                    indexId:1,
                    // indexKey: 1,
                    data: {
                         'value':departAll,
                    }
                }).on('onDataRequestSuccess', function (e, result) {
                    console.log('onDataRequestSuccess: ', result);
                }).on('onSetSelectValue', function (e, keyword, data) {
                   console.log('onSetSelectValue: ', keyword, data);
                   var idDepart=$(this).attr("data-id");
                   $(this).parents("form").find("input[name=depart]").val(idDepart);
                }).on('onUnsetSelectValue', function (e) {
                    console.log("onUnsetSelectValue");
                });
               },"json")
            }).on('onUnsetSelectValue', function (e) {
                console.log("onUnsetSelectValue");
            });

            // 修改父设备，设备类别搜索建议
            $(".detail input[name=parent]").bsSuggest({
                allowNoKeyword: false,
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
                  $("input[name=pid]").val(pid);
                }
            }).on('onUnsetSelectValue', function (e) {
                console.log("onUnsetSelectValue");
            });

            $(".detail input[name=class]").bsSuggest({
                allowNoKeyword: false,
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
                    addHtml+="<div class='col-md-4'>"+
                             "  <div class='input-group'>"+
                             "    <span class='input-group-addon'>"+data[i].name+"</span>"+
                             "    <input type='text' class='form-control' name='paraid["+data[i].id+"]'>"+
                             "  </div> "+
                             "</div>";
                 }
                 $(".detail-info:last .col-md-4:not(:last)").detach();
                 $(".detail-info:last h4").after(addHtml);
                },'json');
                
            }).on('onUnsetSelectValue', function (e) {
                console.log("onUnsetSelectValue");
            });

          }else{
            $(this).text("修改设备信息");
             $('#confirm').modal({
                  keyboard: true
              });
             $("#confirmYes").click(function(){
              $("#updateForm").submit();
             });
          }
        }
        return false;

    });

  
// 修改当前设备管理员搜索提示
$("#updateLia input[name=nameLia]").bsSuggest({
    allowNoKeyword: false,
     showBtn: false,
    // indexKey: 0,
    indexId:1,
    data: {
       'value':<?php 
            $allLiable=$devService->getLiable();
            echo "$allLiable";
          ?>,
        // 'defaults':'没有相关设备请另查询或添加新的设备'
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

$("#updateLia #yesLia").click(function(){
  if($("#updateLia input[name=nameLia]").val().length>0){
    var nameLiable=$("#updateLia input[name=nameLia]").val();
    var idLiable=$("#updateLia input[name=nameLia]").attr("data-id");
    var addHtml="<span class='badge'>"+nameLiable+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='lia[]' value="+idLiable+"></span> "
    $("#updateLia #forLia").append(addHtml);
    $("#updateLia input[name=nameLia]").val("");
  }else{
    $('#failAdd').modal({
      keyboard: true
    });
  }  
});

// 修改当前设备管理员确认按钮
    $("#updateLia #updateYes").click(function(){
     var allow_submit = true;
     // 负责人列表为空时，也不可提交
     var forLiable=$("#updateLia #forLia input").length;
     if (forLiable==0) {
       $('#failAdd').modal({
              keyboard: true
        });
        allow_submit = false;
     }
     return allow_submit;
    });




</script>
</body>
</html>