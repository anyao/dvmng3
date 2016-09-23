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

<title>在用设备-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1),thead > tr > th:nth-last-child(2){
      width: 3%;
  }
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
require_once "model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
<?php
  require_once 'model/devService.class.php';
  require_once 'model/paging.class.php';

  
  $paging=new paging();
  $paging->pageNow=1;
  $paging->pageSize=18;
  $paging->gotoUrl="usingList.php";
  if (!empty($_GET['pageNow'])) {
    $paging->pageNow=$_GET['pageNow'];
  }

  $devService=new devService();



if (empty($_REQUEST['flag']) && empty($_GET['fct']) && empty($_GET['dpt'])) {
  $devService->getPaging($paging);
}else if(!empty($_GET['fct'])){
  $idFct=$_GET['fct'];
  $devService->getDevByFct($idFct,$paging);
}else if (!empty($_GET['dpt'])) {
  $idDpt=$_GET['dpt'];
  $devService->getDevByDpt($idDpt,$paging);
}else{
    if(empty($_POST['sector'])){
      $depart='';
    }else{
      $depart=$_POST['sector'];
    }

    if(empty($_POST['office'])){
      $office='';
    }else{
      $office=$_POST['office'];
    }

    if(empty($_POST['factory'])){
      $factory='';
    }else{
      $factory=$_POST['factory'];
    }

    if(empty($_POST['keyword'])){
      $keyword='';
    }else{
      $keyword=$_POST['keyword'];
    }

    if(empty($_POST['devid'])){
      $devid='';
    }else{
      $devid=$_POST['devid'];
    }

    $devService->findDev($depart,$factory,$keyword,$devid,$office,$paging);
  
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
<div class="container">
  <div class="row">
      <div class="col-md-10">
            <div class="page-header">
                <h4>　所有在用设备</h4>
            </div>
    <table class="table table-striped table-hover">
        <thead><tr>
            <th>　</th><th>编号</th><th>设备名称</th><th>运行状态</th><th>运行天数</th><th>上次巡检</th><th>上次维修</th>
            <th><span style='cursor: pointer;' class="glyphicon glyphicon-import"></span></th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
          </tr></thead>
        <tbody class="tablebody">  
            <?php
              for ($i=0; $i < count($paging->res_array); $i++) { 
                $row=$paging->res_array[$i];
                $son=$devService->IfHasSon($row['id']);
                $timediff = $devService->timediff($row['dateInstall'],$row['dateEnd']);
                if($son['count(id)']==0){
                  $info="<tr>
                          <td></td>
                          <td>{$row['code']}</td>
                          <td><a href='using.php?id={$row['id']}'>{$row['name']}</a></td>
                          <td>{$row['state']}</td><td>{$timediff[0]}{$timediff[1]}</td>
                          <td>{$row['insp']}</td><td>{$row['rep']}</td>
                          <td><span class='glyphicon glyphicon-import' id='{$row['id']}'></span></td>
                          <td><span class='glyphicon glyphicon-trash' id='{$row['id']}'></span></td>
                        </tr>";
                }else{
                  $info="<tr>
                          <td><a name='openChild' class='glyphicon glyphicon-plus' value='{$row['id']}'></a></td>
                          <td>{$row['code']}</td>
                          <td><a href='using.php?id={$row['id']}'>{$row['name']}</a></td>
                          <td>{$row['state']}</td><td>{$timediff[0]}{$timediff[1]}</td>
                          <td>{$row['insp']}</td><td>{$row['rep']}</td>
                          <td><span class='glyphicon glyphicon-import' id='{$row['id']}'></span></td>
                          <td><span class='glyphicon glyphicon-trash' id='{$row['id']}'></span></td>
                        </tr>";
                  }
                echo $info;
              }
            ?>  
            </tbody></table>
            <div class='page-count'><?php echo $paging->navi?></div>                
      </div>
      <div class="col-md-2">
      <div class="tree">
      <!-- <li><span>一期制氧</span>—<a href="">设备</a> <a href="">电气</a> <a href="">仪器</a></li> -->
      <?php 
        $dptNavi=$devService->departNavi();
        $addHtml="<ul>";
        for ($k=1; $k <= count($dptNavi); $k++) {
          if ($k==1) {
            $addHtml.="<li class='comp'><span> 普阳钢铁有限公司 </span><ul>";
          }else if($k==2){
            $addHtml.="<li class='comp'><span> 中普(邯郸)钢铁有限公司 </span><ul>";
          }else if ($k==3) {
            $addHtml.="<li class='comp'><span> 武安广普焦化有限公司 </span><ul>";
          }

          for ($i=0; $i < count($dptNavi[$k]); $i++) { 
            $addHtml.="<li><span class='badge' fct='{$dptNavi[$k][$i]['id']}'>{$dptNavi[$k][$i]['name']}
                           <a href=\"javascript:void(0);\" class='glyphicon glyphicon-map-marker'></a></span><ul>";
            if (!empty($dptNavi[$k][$i]['childrens'])) {  
              for ($j=0; $j < count($dptNavi[$k][$i]['childrens']); $j++) { 
                $addHtml.="<li dpt='{$dptNavi[$k][$i]['childrens'][$j]['id']}'><a href=\"javascript:getDevByDpt({$dptNavi[$k][$i]['childrens'][$j]['id']});\"><span>{$dptNavi[$k][$i]['childrens'][$j]['name']}</span></a></li>";
              }
            }
            $addHtml.="</ul></li>";
          }
          
          $addHtml.="</ul></li>";
        }
        $addHtml.="</ul>";
        echo $addHtml;
       ?>
         
       </div>

      </div> 
  </div>
</div>

<!-- 添加父设备 -->
<form class="form-horizontal" action="controller/devProcess.php?flag=addPrt" method="post">
  <div class="modal fade" id="prtAdd" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加父设备</h4>
        </div>
        <div class="modal-body">
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
                <label class="col-sm-4 control-label">所属品牌：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="brand" placeholder="请输入新设备品牌">
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-4 control-label">所属类别：</label>
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
              <div class="form-group">
                <label class="col-sm-4 control-label">购入价格：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="price" placeholder="请输入新设备所购价格">
                </div>
              </div>
              <!-- <div class="form-group">
                <label class="col-sm-4 control-label">所在分厂：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="factory" placeholder="请输入新设备所在分厂(不可为空)">
                </div>
              </div> -->

              <div class="form-group">
                <label class="col-sm-4 control-label">所在分厂：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                  <input type="text" name="nameFct" class="form-control notNull" placeholder="请搜索所在分厂(不可为空)">
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
                  <input type="text" class="form-control datetime" name="dateManu" placeholder="请选择出厂日期" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime notNull" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">有效期止：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="periodVali" placeholder="请选择有效期止" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">供应商：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="supplier" placeholder="请输入新设备的供应源">
                </div>
              </div>
            
               <div class="form-group">
                <label class="col-sm-4 control-label">所在部门：</label>
                <div class="col-sm-8">
                  <div class="input-group">
                  <input type="text" name="nameDepart" class="form-control notNull" placeholder="请选择所在部门" readonly="readonly">
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
              

              <div class='form-group' >
                <label class='col-sm-4 control-label'>责任人员：</label>
                  <div class='col-sm-7'>
                <div class='input-group'>
                  <input type="text" class="form-control notNul" name="theLiable" placeholder="负责人员(不可为空)">
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
                 <a href="javascript:void(0);" id="yesLiable" class='glyphicon glyphicon-ok'></a>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">责任人列表：</label>
                <div class="col-sm-8" id="forLiable" style="padding-top:7px">
                </div>
              </div>


            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <input type="hidden" name="depart">
          <input type="hidden" name="factory">
          <button type="submit" class="btn btn-primary" id="addPrt">确定添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 

<!-- 添加新设备弹出框 -->
<form class="form-horizontal" action="controller/devProcess.php" method="post" id="formCld">
  <div class="modal fade" id="cldAdd" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新设备</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">设备编号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="code" placeholder="请输入新设备编号">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label ">设备名称：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="name" placeholder="请输入新设备名称(不可为空)">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">设备型号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control notNull" name="no" placeholder="请输入新设备型号(不可为空)">
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
                  <input type="text" class="form-control" name="number" placeholder="请输入该设备具体数量">
                </div>
              </div>
            </div>
            <div class="col-md-6">
         <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-8">
              <div class="input-group">
              <input type="text" name="nameDepart" class="form-control notNull" placeholder="请选择所在部门" readonly="readonly">
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
            <label class="col-sm-3 control-label">所在分厂：</label>
            <div class="col-sm-8">
              <div class="input-group">
              <input type="text" name="nameFct" class="form-control notNull" placeholder="请搜索所在分厂(不可为空)">
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
          <div class="row" id="cldPara">
            
          </div> 
        </div>
        <div class="modal-footer">
        <input type="hidden" name="depart">
        <input type="hidden" name="factory">
          <input type="hidden" name="pid">
          <input type="hidden" name="flag" value="addCld">
          <button class="btn btn-primary" id="addCld">确定添加</button>
          <button class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 




<!-- 删除配置柜提示框 -->
<div class="modal fade"  id="devDel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该设备记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 删除失败其下有新元素提示框 -->
<div class="modal fade"  id="failDel" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">删除失败，请删除其下子设备后再操作。</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<!-- 设备类别重新选择提示框 -->
<div class="modal fade"  id="failParaInfo" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">请重新选择设备类别(需从下拉菜单中选择)。</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 


<!-- 确认更换提示框 -->
<div class='modal fade' id="sonCnfr">
  <div class='modal-dialog modal-sm' role='document' style='margin-top: 70px'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>若更换,其下新设备都将停用。<br/>确定要更换设备吗？<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="sure">确定</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 添加不完整提示框 -->
<div class="modal fade"  id="failAdd">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您需要添加的设备信息不完整，请补充。</div><br/>
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


<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/table-treeview.js"></script>
<script src="bootstrap/js/jsonToTree.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script type="text/javascript">
var auth='<?php echo "{$_SESSION['permit']}"; ?>';
// 插入根设备弹出框
$("th > .glyphicon-import").click(function(){
  if (auth==2) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
      $('#prtAdd').modal({
        keyboard: true
      });
  }
})

    function getDevByDpt(id){
      location.href="usingList.php?dpt="+id;
    }

      //所有弹出框
    $(function () 
      { $("[data-toggle='popover']").popover();
      });

    //时间选择器
      $(".datetime").datetimepicker({
        format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
      });


    //树形导航
    $(function () {
      $('.tree li:has(ul)').addClass('parent_li');
      // 设置部门初始状态为折叠不显示
      $('.tree .badge').parent().find(' > ul > li').hide();
      $(".comp:eq(1)").find(' > ul > li').hide();

      // 如果根据分厂选择设备列表
      var idFct="<?php if (!empty($idFct)) {echo "$idFct";}else{echo " ";}?>";
      if (idFct!=" ") {
        var $fctChos=$(".tree li.parent_li > span[fct="+idFct+"]");
        var $kids=$fctChos.parent('li.parent_li').find(' > ul > li');
        var $bros=$fctChos.parent().siblings();
        var $comp=$fctChos.parents(".comp");
        // $comp.css("background","pink");
        $comp.siblings().find(' > ul > li').hide();
        $comp.find(' > ul > li').show();
        $kids.show();
        $bros.hide();
      }

      // 根据部门选择设备列表
      var idDpt="<?php if (!empty($idDpt)) {echo "$idDpt";}else{echo "n";}?>";
      if (idDpt!="n") {
        var $dptChos=$("li[dpt="+idDpt+"]");
        var $comp=$dptChos.parents(".comp");

        var $fctBros=$dptChos.parents("li.parent_li").siblings();
        var $bros=$dptChos.parents("li.parent_li").find(' > ul > li');
        $comp.siblings().find(' > ul > li').hide();
        $comp.find(' > ul > li').show();
        $fctBros.hide();
        $bros.show();
      }

      $('.tree li.parent_li > span').on('click', function (e) {
          var children = $(this).parent('li.parent_li').find(' > ul > li');
          if (children.is(":visible")) {
               $(this).parent().siblings().show();
              children.hide('fast');
              $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
          } else {
              $(this).parent().siblings().hide();
              children.show('fast');
              $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
          }
          e.stopPropagation();
      });
    });

    // 分厂设备列表
    $(".glyphicon-map-marker").click(function(){
      var $markerPa=$(this).parent();
      var fctid=$markerPa.attr("fct");
      // alert(fctid);
      location.href="usingList.php?fct="+fctid;
    });

    //删除提示框 made it
    $(document).on("click","span.glyphicon-trash",trash);
    function trash(){
      var id=$(this).attr("id");
      if (auth==2) {
            $('#failAuth').modal({
              keyboard: true
            });
        }else{
           $('#devDel').modal({
              keyboard: true
            });
            $("#del").click(function() {
            $.get("controller/devProcess.php",{
              pid:id,
              flag:"findSon"
            },function(data,success){
              var count=data;
              if (count!=0) {
                $('#devDel').modal('hide');
                $('#failDel').modal({
                  keyboard: true
                });
              }else{
                // alert("failure");
                location.href="controller/devProcess.php?flag=delDev&id="+id;
              }
            },"text");   
          });
        }
    }


    // 添加新设备信息弹出框
    $(document).on("click",".tablebody .glyphicon-import",addSon);
    function addSon(){
       var $id=$(this).attr("id");
       if (auth==2) {
            $('#failAuth').modal({
              keyboard: true
            });
        }else{
           $("#cldAdd input[name=pid]").val($id);
           $('#cldAdd').modal({
              keyboard: true
           });
        }
    }


    // 添加父设备信息不完整时，弹出提示框
    $("#addPrt").click(function(){
     var allow_submit = true;
     $("#prtAdd .notNull").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     });

     // 负责人列表为空时，也不可提交
     var forLiable=$("#prtAdd #forLiable input").length;
     if (forLiable==0) {
       $('#failAdd').modal({
              keyboard: true
        });
        allow_submit = false;
     }

     // 重新选择设备类别
     var idType=$("#prtAdd input[name=class]").attr("data-id");
      if(typeof(idType)=="undefined"||idType==""){
          $('#failParaInfo').modal({
                keyboard: true
          });
          allow_submit=false;
      }

     return allow_submit;
    });
    
    // 添加子设备确认添加按钮
    $("#addCld").click(function(){
      // 添加新设备信息不完整时，弹出提示框
      var allow_submit = true;
      $("#cldAdd .notNull").each(function(){
        if ($(this).val()=="") {
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
      }); 
      var idType=$("#cldAdd input[name=class]").attr("data-id");
      if(typeof(idType)=="undefined"||idType==""){
          $('#failParaInfo').modal({
                keyboard: true
          });
          allow_submit=false;
      }
      return allow_submit;
   });


    // 设备根节点打开其下新节点
    $(document).on("click","a[name=openChild]",child_click);
    function child_click(){
      // 获取该设备的id值
      var $id=$(this).attr("value");
      // 获取该设备的tr节点
      var $parent=$(this).parents("tr");
      // 获取下一个设备tr节点的class
      var $nextTr=$parent.next();
      var $parentNext=$nextTr.attr("class");
      var $addHtml="<tr class='child-list'>"+
                      "<td colspan='12' style='padding:0'>"+
                        "<div id='Prt-"+$id+"'></div>"+
                      "</td>"+
                    "</tr>";
      if ($parentNext=="child-list") {
        // 新设备列表显示状态，触发应让其消失
        $(this).removeClass('glyphicon glyphicon-minus')
             .addClass('glyphicon glyphicon-plus');
        $($nextTr).detach();
      }else{
        // 新设备列表未加载状态，触发应让其显示
        $(this).removeClass('glyphicon glyphicon-plus')
             .addClass('glyphicon glyphicon-minus');
        $parent.after($addHtml);
        $.get("controller/devProcess.php",{
          flag:"addSon",
          pid:$id
        },function(data,success){
          var jsonDataTree = transData(eval(data), 'tags', 'pid', 'nodes'); 
          var data=JSON.stringify(jsonDataTree); 
          $('#Prt-'+$id+'').treeview({
            enableLinks: true,
            showBorder: false,
            levels: 1,
            showTags: true,
            data: data
         });
        },"text");    
      }
    }
    


    // 添加子设备时，设备类别搜索建议
    $("#cldAdd input[name=class]").bsSuggest({
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

    // 添加父设备时，设备类别搜索建议
    $("#prtAdd input[name=class]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        indexId:1,
        // indexKey: 1,
        data: {
             'value':<?php 
              $allType=$devService->getTypePrt();
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

    

    // 分厂搜索提示，并根据所选调用部门搜索函数
    $("input[name=nameFct]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
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
       var fct=$(this).attr("data-id");
       $(this).parents("form").find("input[name=factory]").val(fct);
       var $depart=$(this).parents("form").find("input[name=nameDepart]"); 
       $.get("controller/devProcess.php",{
        flag:'getDptAll',
        idFct:fct
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


// 添加父设备时，负责人搜索建议
    $("#prtAdd input[name=theLiable]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        indexId:1,
        // indexKey: 1,
        data: {
             'value':<?php 
              $allLiable=$devService->getLiable();
              echo "$allLiable";
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    $("#prtAdd #yesLiable").click(function(){
      if($("#prtAdd input[name=theLiable]").val().length>0){
        var nameLiable=$("#prtAdd input[name=theLiable]").val();
        var idLiable=$("#prtAdd input[name=theLiable]").attr("data-id");
        var addHtml="<span class='badge'>"+nameLiable+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='liable[]' value="+idLiable+"></span> "
        $("#prtAdd #forLiable").append(addHtml);
        $("#prtAdd input[name=theLiable]").val("");
      }else{
        $('#failAdd').modal({
          keyboard: true
        });
      }  
    });

     $(document).on("click",".glyphicon-remove",delDeved)
      function delDeved(){
        $(this).parents("span").detach();
      }
   </script>

  </body>
</html>