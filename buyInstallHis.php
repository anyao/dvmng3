<?php 
include_once "./model/commonService.class.php";
CommonService::checkValidate();
CommonService::autoload();
$user=$_SESSION['user'];

$paging=new paging;
$paging->pageNow=1;
$paging->pageSize=50;
$paging->gotoUrl="buyInstallHis.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}
$sqlHelper = new sqlHelper;
$gaugeService = new gaugeService($sqlHelper);
// 是否为搜索结果
if (empty($_POST['flag'])) {
  $gaugeService->buyInstallHis($paging);
}else if ($_POST['flag'] == 'findInstall') {
  $install_from = $_POST['take_from'];
  $install_to = $_POST['take_to'];
  $codeManu = $_POST['codeManu'];
  $name = $_POST['name'];
  $spec = $_POST['spec'];

  $gaugeService->buyInstallFind($take_from, $take_to, $codeManu, $name, $spec, $paging);
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
<link rel="icon" href="bootstrap/img/favicon.ico">
<title>备件入账存库-仪表管理</title>
<style type="text/css">
.glyphicon-briefcase, .glyphicon-play-circle{
  display: inline !important;
  cursor: default !important;
}

#uptModal .input-group{
  margin: 12px 0px;
}

</style>
<?php include "./buyVendor.php" ?>
</head>
<body role="document">
<?php  include "message.php";?>
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

<div class="modal fade" id="uptModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">安装验收</h4>
      </div>
      <form class="form-horizontal" method="post" action="./controller/gaugeProcess.php">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">安装地点：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="bas[loc]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">安装情况：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="2" name="ins[info]"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">运行情况：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="2" name="ins[runinfo]"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">技术参数：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="3" name="ins[tech]"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">结论：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="2" name="ins[res]"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="uptInstall">
          <input type="hidden" name="id">
          <span style="display: none;color: red" class="failAdd">信息不完整。</span>
          <button class="btn btn-primary" id="yesUpt">确定</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-10">
      <div class="page-header">
        <h4>　仪表备件验收记录</h4>
      </div>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th></th>
            <th>领取时间</th><th>设备名称</th><th>规格型号</th><th>出厂编号</th><th>使用部门</th><th>安装地点</th>
            <th style="width:4%">
              <span class='glyphicon glyphicon-save' id='downXls' style='cursor:pointer;display:none'></span>
            </th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无历史安装验收记录</td></tr>";
          }else{
            for ($i=0; $i < count($paging->res_array); $i++) { 
              $row = $paging->res_array[$i];
            if ($row['status'] == 4) {
              $icon = "<td><span class='glyphicon glyphicon-play-circle'></span></td>";
              $down = "<td><a href='./controller/gaugeProcess.php?flag=getXls&id={$row['id']}' class='glyphicon glyphicon-save'></a></td>";
            }else{
              $icon = "<td><span class='glyphicon glyphicon-briefcase'></span></td>";
              $down = "<td><a href='javascript:uptInstall({$row['id']});' class='glyphicon glyphicon-pencil'></a></td>";
            }
            echo
              "<tr>{$icon}
              <td>{$row['takeTime']}</td>
              <td>{$row['name']}</td>
              <td>{$row['spec']}</td>
              <td>{$row['codeManu']}</td>
              <td>{$row['factory']}{$row['depart']}</td><td>{$row['loc']}</td>
              $down
              </tr>";
            }
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
function uptInstall(id){
  $("#uptModal input[name=id").val(id);
  $("#uptModal").modal({
    keyboard:true
  });
}

$("#yesUpt").click(function(){
  var allow_submit = true;
  $("#uptModal input[type=text], #uptModal textarea").each(function(){
    if($(this).val()==""){
      allow_submit =false;
      $("#uptModal .failAdd").show();
      return false;
    }
  });
  return allow_submit;
});

    </script>
  </body>
</html>