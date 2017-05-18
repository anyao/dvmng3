<?php 
require_once "model/cookie.php";
require_once 'model/devService.class.php';
checkValidate();
$user=$_SESSION['user'];

$id=$_GET['id'];
$devService=new devService();
$arr=$devService->getDevById($id);

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
  .last-th{
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


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="img/favicon.ico">
  <title>-设备管理系统</title>
  
</head>
<body>
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
      <h5>维修次数 / 次</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-wrench"></span></div>
      <div class="col-md-5"><?php
      $frequency=$devService->frequency($id);
      echo $frequency['count(id)'];
      ?></div>
      </div>
    </div>

    <div class="printview">
      <h5>运行状态</h5>
      <div class="row">
      <div class="col-md-4"><span class="glyphicon glyphicon-modal-window" style="cursor: pointer"></span></div>
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
      <input type="text" name="class" class="form-control" value="<?php echo $arr[0]['class'] ?>" readonly>
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
      <input type="text" name="parent" class="form-control" value="<?php echo $arr[0]['parent'] ?>" readonly>
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
              <h4 style='margin-left: 20px;'>负责人员：
              <a style="text-decoration: none;margin-right: 5px" href='javascript:manHstr(<?php echo $arr[0]['id'];?>);' class="glyphicon glyphicon-list-alt"></a>
              <a style="text-decoration: none;" href='javascript:updateLia(<?php echo $arr[0]['id'];?>);' class="glyphicon glyphicon-cog"></a>
              </h4>
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
                  <input type="hidden" name="depart" value="<?php echo $arr[0]['did'];?>">
                  <input type="hidden" name="factory" value="<?php echo $arr[0]['fid'];?>">
                  <input type="hidden" name="id" value="<?php echo $arr[0]['id'];?>">
                  <input type="hidden" name="pid" value="<?php echo $arr[0]['pid'];?>">
                  <input type="hidden" name="flag" value="updateDev">
                  
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
        <h4 style="margin-left: 20px;margin-top: 15px;margin-bottom: -5px">日常巡检\维修:  </h4>
       <div class="accordion-body">
          <div class="accordion-inner-spare">
            <div class="row">
                <div class="col-md-6 table-responsive graph">
                  <table class="table table-striped table-hover">
                      <thead><tr>
                        <th>巡检 • 编号</th><th>时间</th><th>执行人</th><th>结果</th>
                        <th class="last-th"><a style="text-decoration: none" href="javascript:inspAdd(<?php echo $id;?>)" class='glyphicon glyphicon-plus'></a></th>
                      </tr></thead>
                      <tbody>
                      <?php
                        require_once 'model/inspectService.class.php';
                        $inspectService=new inspectService();
                        $arrInsp=$inspectService->getInspByDev($id);
                        for ($i=0; $i < count($arrInsp); $i++) {                             
                          echo "<tr><td>{$arrInsp[$i]['id']}</td>
                                    <td>{$arrInsp[$i]['time']}</td>
                                    <td>{$arrInsp[$i]['liable']}</td>
                                    <td>{$arrInsp[$i]['result']}</td>
                                    <td><a href='javascript:inspUpdt({$arrInsp[$i]['id']})' class='glyphicon glyphicon-pencil'></a></td></tr>";
                        }
                      ?>
                      </tbody>

                    </table>
                  </div>
                  <div class="col-md-6 table-responsive graph">
                    <table class="table table-striped table-hover">
                        <thead><tr>
                          <th>维修 • 时间</th><th>故障现象</th><th>执行人</th>
                          <th class="last-th"><a style="text-decoration: none" href='javascript:repAdd(<?php echo $id;?>)' class='glyphicon glyphicon-plus'></a></th>
                        </tr></thead>
                        <tbody>
                        <?php
                          require_once 'model/repairService.class.php';
                          $repairService=new repairService();
                          $arrRep=$repairService->getRepByDev($id); 
                          for ($i=0; $i < count($arrRep); $i++) {                             
                            echo "<tr><td>{$arrRep[$i]['time']}</td>
                                      <td>{$arrRep[$i]['err']}</td>
                                      <td>{$arrRep[$i]['liable']}</td>
                                      <td><a href='javascript:repUpdt({$arrRep[$i]['id']})' class='glyphicon glyphicon-pencil'></a></td></tr>";
                          }
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

<!-- 修改设备管理员 -->
<div class="modal fade" id="updateLia" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">修改当前设备管理员</h4>
      </div>
      <form class="form-horizontal" action="controller/devProcess.php" method="post">
        <div class="modal-body">
          <div class='form-group' >
            <label class='col-sm-3 control-label'>新添加：</label>
              <div class='col-sm-7'>
            <div class='input-group'>
              <input type='text' class='form-control' name="nameLia">
              <div class='input-group-btn'>
                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                  <span class='caret'></span>
                </button>
                <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                </ul>
              </div>
            </div>
          </div>
            <div class="btn-set">
             <a href="javascript:void(0);" id="yesLia" class='glyphicon glyphicon-ok'></a>
            </div>
          </div>
           <div class="form-group">
            <label class="col-sm-3 control-label">当前分配：</label>
            <div class="col-sm-8" id="forLia" style="padding-top:7px">
            </div>
          </div>
          <div class="modal-footer">

            <input type="hidden" name="flag" value="updateLia">
            <input type="hidden" name="devid">
            <input type="hidden" name="oid">
            <button type="submit" class="btn btn-primary" id="updateYes">确认修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>


<!-- 添加新的巡检记录 -->
<div class="modal fade" id="inspAdd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加新的巡检记录</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">点检时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="time" readonly="readonly" placeholder="请点击选择时间">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">巡检人员：</label>
            <div class="col-sm-7">
               <div class='input-group'>
              <input type='text' class='form-control' name="liable">
              <div class='input-group-btn'>
                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                  <span class='caret'></span>
                </button>
                <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                </ul>
              </div>
            </div>
            </div>
          </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">巡检结果：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="result" value="正常"> 正常
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="保养"> 保养
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="需维修"> 需维修
              </label>
              </div>
            </div>
            <div class='form-group' id="forErr">
             <label class='col-sm-3 control-label'>基本描述：</label>
             <div class='col-sm-7'>
              <textarea class='form-control' rows='2' name='info'></textarea>
             </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInfoByDev">
              <input type="hidden" name="devid" value="<?php echo "$id";?>">
              <button type="submit" class="btn btn-primary" id="inspYes">确认添加</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 修改巡检记录 -->
<div class="modal fade" id="inspUpdt">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">修改巡检记录</h4>
    </div>
    <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">记录编号：</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="id" readonly="readonly" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">点检时间：</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" name="time" readonly="readonly">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">巡检人员：</label>
          <div class="col-sm-7">
             <div class='input-group'>
            <input type='text' class='form-control' name="liable">
            <div class='input-group-btn'>
              <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                <span class='caret'></span>
              </button>
              <ul class='dropdown-menu dropdown-menu-right' role='menu'>
              </ul>
            </div>
          </div>
          </div>
        </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">巡检结果：</label>
            <div class="col-sm-6">
             <label class="radio-inline">
              <input type="radio" name="result" value="正常"> 正常
            </label>
            <label class="radio-inline">
              <input type="radio" name="result" value="保养"> 保养
            </label>
            <label class="radio-inline">
              <input type="radio" name="result" value="需维修"> 需维修
            </label>
            </div>
          </div>
          <div class='form-group'>
           <label class='col-sm-3 control-label'>基本描述：</label>
           <div class='col-sm-7'>
            <textarea class='form-control' rows='2' name='info'></textarea>
           </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="updtInfoByDev">
            <input type="hidden" name="idLia">
            <input type="hidden" name="devid" value="<?php echo "$id";?>">
            <button type="button" class="btn btn-danger" id="inspDel">删除</button>
            <button class="btn btn-primary" id="inspUpdtYes">修改</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 添加新的维修记录 -->
<div class="modal fade" id="repUpdt" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改维修记录</h4>
        </div>
        <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
          <div class="row">
          <div class="col-md-6 add-left">
            <div class="form-group">
              <label class="col-sm-4 control-label">记录编号：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="id" readonly="readonly">        
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">维修时间：</label>
              <div class="col-sm-8">
                  <input type="text" class="form-control" name="time" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">维修人员：</label>
              <div class="col-sm-8">
                 <div class='input-group'>
                <input type='text' class='form-control' name="liable">
                <div class='input-group-btn'>
                  <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                    <span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                  </ul>
                </div>
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4 control-label">故障现象：</label>
              <div class="col-sm-8">
                <textarea class="form-control" name="err" rows="2"></textarea>
              </div>
            </div>
            </div>

            <div class="col-md-6 add-right">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障原因：</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="reason" rows="3"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">解决方案：</label>
              <div class="col-sm-9">
                <textarea class="form-control" name="solve" rows="4"></textarea>
              </div>
            </div>
            </div>
            </div>
            <div class="modal-footer">

              <input type="hidden" name="flag" value="updtRepByDev">
              <input type="hidden" name="devid" value="<?php echo "$id";?>">
              <button type="button" class="btn btn-danger" id="repDel">删除</button>
            <button class="btn btn-primary" id="updtRepYes">修改</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>

<div class="modal fade" id="repAdd" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加维修记录</h4>
      </div>
      <form class="form-horizontal" action="controller/repairProcess.php" method="post">
        <div class="modal-body">
        <div class="row">
        <div class="col-md-6 add-left">
          
           <div class="form-group">
            <label class="col-sm-4 control-label">维修人员：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="liable">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">维修时间：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control datetime" name="time">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">故障现象：</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="err" rows="2"></textarea>
            </div>
          </div>
          </div>

          <div class="col-md-6 add-right">
          <div class="form-group">
            <label class="col-sm-3 control-label">故障原因：</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="reason" rows="2"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">解决方案：</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="solve" rows="2"></textarea>
            </div>
          </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addInfo">
            <input type="hidden" name="devid">
            <button class="btn btn-primary" id="addYes">确认添加</button>
            <button class="btn btn-danger" data-dismiss="modal">关闭</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>


<!-- 负责人变更历史弹出框 -->
<div class="modal fade" id="manHstr">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">责任人变更历史</h4>
        </div>
        <div class="modal-body" style="height: 300px">
          <table class="table table-striped table-hover">
          <thead><tr><th>记录编号</th><th>开始时间</th><th>停管时间</th><th>负责人</th></tr></thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
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

<!-- 停用父设备 -->
<div class="modal fade"  id="devStop">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要停用该设备吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="stop">停用</button>
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

$("#stop").click(function(){
  var id=<?php echo $id;?>;
  location.href="controller/devProcess.php?flag=stopDev&id="+id;
});

// 父设备停用按钮
$(".glyphicon-modal-window").click(function(){
  var enter = allow_enter(1);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
   $('#devStop').modal({
        keyboard: true
    });
  }
});

// 删除维修记录按钮
$("#repDel").click(function(){
  var enter = allow_enter(2);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
    var id=$("#repUpdt input[name=id]").val();
    var devid=<?php echo "$id";?>;
    location.href="controller/repairProcess.php?flag=delrepByDev&devid="+devid+"&id="+id;
  }
})

// 修改维修记录确认按钮
$("#updtRepYes").click(function(){
  var enter = allow_enter(1);
  if (enter == -1) {
    $('#failAuth').modal({
      keyboard: true
    });
    return false
  }else{
     var allow_submit = true;
     $("#repUpdt .form-control").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     });
     return allow_submit;
  }
});

// 修改维修记录
function repUpdt(id){
  $.get("controller/repairProcess.php",{
    flag:'getRep',
    id:id
  },function(data,success){
     $("#repUpdt input[name=id]").val(data.id);
     $("#repUpdt input[name=time]").val(data.time);
     $("#repUpdt input[name=liable]").val(data.liable);
     $("#repUpdt textarea[name=err]").val(data.err);
     $("#repUpdt textarea[name=reason]").val(data.reason);
     $("#repUpdt textarea[name=solve]").val(data.solve);
    $('#repUpdt').modal({
        keyboard: true
    });
  },"json");
}
// 添加维修记录确认按钮
$("#addRepYes").click(function(){
 var allow_submit = true;
 $("#repAdd .form-control").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
})

// 添加维修记录
function repAdd(devid){
  $("#repAdd input[name=devid]").val(devid);
  $('#repAdd').modal({
    keyboard: true
  });
}

// 删除巡检记录按钮
$("#inspDel").click(function(){
  var enter = allow_enter(2);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
    var id =$("#inspUpdt input[name=id]").val();
    var devid=<?php echo "$id";?>;
    location.href="controller/inspectProcess.php?flag=delInfoByDev&devid="+devid+"&id="+id;
  }
});
// 修改巡检记录
function inspUpdt(id){
    $.get("controller/inspectProcess.php",{
      flag:'getInfoByDev',
      id:id
    },function(data,success){
       // {"id":"37","time":"2016-06-25 05:59:00","result":"正常","liable":"admin","info":"无","devid":"45"}
       $("#inspUpdt input[name=id]").val(data.id);
       $("#inspUpdt input[name=time]").val(data.time);
       $("#inspUpdt input[name=result][value="+data.result+"]").attr("checked",true);
       $("#inspUpdt input[name=liable]").val(data.liable);
       $("#inspUpdt textarea[name=info]").val(data.info);
        $('#inspUpdt').modal({
            keyboard: true
        });
    },'json');
}

// 修改巡检记录确认按钮
$("#inspUpdtYes").click(function(){
  var enter = allow_enter(1);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
      return false;
  }else{
    var idLia=$("#inspUpdt input[name=liable]").attr("data-id");
    $("#inspUpdt input[name=idLia]").val(idLia);
    var allow_submit = true;
    $("#inspUpdt input[type!=hidden] , #inspUpdt textarea").each(function(){
      if($(this).val()==""){
         $('#failAdd').modal({
            keyboard: true
        });
        allow_submit = false;
      }
    })
    return allow_submit;
  }
});

// 确认添加巡检记录按钮
$("#inspAdd").on("click","#inspYes",inspYes)
function inspYes(){
  var result=$("#inspAdd input[name=result]:checked").val();
  if (result=="正常") {
    var $notNull=$("#inspAdd input[type!=hidden]");
  }else{  
    var $notNull=$("#inspAdd input[type!=hidden] , #inspAdd textarea");
  }
  var allow_submit = true;
  $notNull.each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
  });
  return allow_submit;
}

// 添加巡检记录弹出框中的设备列表显示隐藏设置
$(function () {
  devErr();
});

// 若点击巡检结果，设备列表的显示隐藏
$("#inspAdd").on("click","input[name=result]",devErr);
function devErr(){
  var result=$("#inspAdd input[name=result]:checked").val();
  if (result=="正常") {
    $("#forErr").hide();
  }else{
    $("#forErr").show();
  }
}

// 添加巡检记录弹出框中时间选择
$("#inspAdd input[name=time] , #repAdd input[name=time] , #inspUpdt input[name=time], #repUpdt input[name=time]").datetimepicker({
  format: 'yyyy-mm-dd HH:ss', language: "zh-CN", autoclose: true,
});

// 添加新的巡检记录
function inspAdd($devid){
    $('#inspAdd').modal({
        keyboard: true
    });
}

// 已确定添加的设备删除
$(document).on("click",".glyphicon-remove",delDeved)
function delDeved(){
  $(this).parents("span").detach();
}

// 修改当前设备管理员
function updateLia(id){
  var enter = allow_enter(1);
  if (enter == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
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
$("#updateLia input[name=nameLia] , #inspAdd input[name=liable] , #repAdd input[name=liable] , #inspUpdt input[name=liable],#repUpdt input[name=liable]").bsSuggest({
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