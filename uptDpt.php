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

<title>部门信息-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  .basic{
    border-bottom: 1px solid #CCCCCC;
    padding:5px 10px;
  }

  form >.basic >  .form-group{
    position: relative;
    left: -180px;
  }

  .part{
    margin: 30px 0px 40px 0px;
    border: 1px solid #A9A9A9;
    border-radius: 6px;
  }
  
  .part > h4{
    margin-left: 30px;
  } 
  .part > .col-md-4 > .input-group{
    margin:5px 5px 20px 5px;
  } 

  .sel-box{
    height:400px;overflow-y:auto;margin-top: 20px
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

	require_once "model/dptService.class.php";
	$dptService=new dptService();

  $id=$_GET['id']; 
  $info=$dptService->getDptOne($id);

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
		 <ul class="nav navbar-nav navbar-right">
        <?php if (in_array($_SESSION['permit'],array("a","b",0,2))) {
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
    <div class="col-md-12">
      <div class="page-header">
          <h4>　修改部门信息</h4>
      </div>
      <form class="form-horizontal" action="controller/dptProcess.php" method="post">
         <div class="basic">
          <!-- [id] => 61 [depart] => 锅炉 [path] => 25MW余热电厂->工艺组->60 [comp] => 1 [pid] => 60 [pdpt] => 工艺组 -->
          <div class="form-group">
            <label class="col-sm-3 control-label">部门编号：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="id" value="<?php echo $id;?>" readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">部门名称：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="name" value="<?php echo "{$info['depart']}";?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-3 control-label">所在公司：</label>
            <div class="col-sm-7">
              <?php switch ($info['comp']) {
                case 1:
                  $info['comp']="河北普阳钢铁有限公司";
                  break;
                case 2:
                  $info['comp']="中普(邯郸)钢铁有限公司";
                  break;
                default:
                  $info['comp']="武安广普焦化有限公司";
                  break;
              } ?>
              <input type="text" class="form-control" value="<?php echo "{$info['comp']}";?>" readonly>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">当前上级：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" readonly name="parent" value="<?php echo "{$info['pathName']}"?>">
            </div>
          </div>

          <ul class="nav nav-tabs">
            <li class="pull-right active"><a href="javascript:selComp(3);">武安广普焦化有限公司</a></li>
            <li class="pull-right active"><a href="javascript:selComp(2);">中普(邯郸)钢铁有限公司</a></li>
            <li class="pull-right active"><a href="javascript:selComp(1);">河北普阳钢铁有限公司</a></li>
          </ul>
          <div class="row sel-box">
            <div class="col-md-4">
              <div id="dpt1-select"></div>
            </div>
            <div class="col-md-4">
              <div id="dpt2-select"></div>
            </div>
            <div class="col-md-4">
              <div id="dpt3-select"></div>
            </div>
          </div>
        </div>
        <div style="text-align:center">
          <input type="hidden" name="pid" value="<?php echo "{$info['pid']}";?>">
          <input type="hidden" name="flag" value="uptDpt">
          <input type="hidden" name="path" value="<?php echo "{$info['path']}";?>">
          <button class="btn btn-primary" style="width:200px;margin:25px 0px 0px 0px" id="yesUpt">确 认 修 改</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade"  id="failAdd">
<div class="modal-dialog modal-sm" role="document" >
<div class="modal-content">
     <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
     </div>
     <div class="modal-body"><br/>
        <div class="loginModal">您需要添加的信息不完整，请补充。</div><br/>
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
<script src="bootstrap/js/dptUser-treeview.js"></script>
<script src="bootstrap/js/jsonToTree.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script type="text/javascript">


// 确认修改按钮判断
$("#yesUpt").click(function(){
  var allow_submit=true;
  var name=$("input[name=name]").val();
  if (name.length==0) {
    $('#failAdd').modal({
        keyboard: true
    });
    allow_submit = false;
  }
  return allow_submit;
});

// 选项卡选择公司对应分厂
function selComp(id){
  var $dpt=$("#dpt"+id+"-select").parent();
  var $active=$(".pull-right:eq("+(3-id)+")");
  $active.addClass("active")
  $active.siblings().removeClass('active');
  $dpt.removeClass('.col-md-4').addClass(".col-md-12").parents(".sel-box");
  $dpt.show();
  $dpt.siblings().hide();
}

$(function(){
  // $("#uptDpt input[name=pid]").val(dptid);
  var slPy='<?php $slPy=$dptService->getDptAllByJson(1); echo $slPy; ?>';
  var slDataTree = transData(eval(slPy),'dptid',  'pid', 'nodes'); 
  dataPy=JSON.stringify(slDataTree); 

  var slZp='<?php $slZp=$dptService->getDptAllByJson(2); echo $slZp; ?>';
  var slDataTree = transData(eval(slZp),'dptid',  'pid', 'nodes'); 
  dataZp=JSON.stringify(slDataTree); 

  var slGp='<?php $slGp=$dptService->getDptAllByJson(3); echo $slGp; ?>';
  var slDataTree = transData(eval(slGp),'dptid',  'pid', 'nodes'); 
  dataGp=JSON.stringify(slDataTree); 

  $('#dpt1-select').treeview({
    selectedBackColor:"#DBDBDB",
      showBorder: false,
      data: dataPy,
      levels: 1,
      onNodeSelected: function(event, node) {
        $('input[name=parent]').val(node.text);
        $("input[name=pid]").val(node.dptid);
      }

    });
  $('#dpt2-select').treeview({
    selectedBackColor:"#DBDBDB",
      showBorder: false,
      data: dataZp,
      levels: 1,
      onNodeSelected: function(event, node) {
        $('input[name=parent]').val(node.text);
        $("input[name=pid]").val(node.dptid);
      }
    });
  $('#dpt3-select').treeview({
    selectedBackColor:"#DBDBDB",
      showBorder: false,
      data: dataGp,
      levels: 1,
      onNodeSelected: function(event, node) {
        $('input[name=parent]').val(node.text);
        $("input[name=pid]").val(node.dptid);
      }
    });

});

//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

</script>
</body>
</html>