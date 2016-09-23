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

<title>设备拆分-设备管理系统</title>

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
require_once "model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
<?php
  $id=$_GET['id'];
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
  <div class="page-header" style="margin-top: 20px">
    <h3>　选择设备拆分要求</h3>
  </div>
<form class="form-horizontal" action="controller/spareProcess.php" method="post">  
  <div class="basic">
      <div class="form-group">
        <label class="col-sm-3 control-label">拆解人员：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name="dvd[0][0]" placeholder="请输入拆解人员(不可为空)">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拆解时间：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control datetime" name="dvd[0][1]" placeholder="请选择拆解时间" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拆解描述：</label>
        <div class="col-sm-7">
          <textarea class="form-control" rows="4" name="dvd[0][2]" placeholder="请简要描述更换原因和情况(必填)..."></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拆分件数：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="dvdNum" placeholder="请输入准备要将设备拆为几部分，只可输入数字 ( 默认为 2 )">
        </div>
      </div>
    </div>
    <!-- 只要拆分最少是2部分 -->
    <!-- 拆分第一部分 -->
    <!-- sample of the divide informaition ____the first -->
    <div class="part row" id="part-1">
      <h4>拆分第 1 部分</h4>
      <div class="col-md-4"><!-- col1 start -->
        <div class="input-group">
          <span class="input-group-addon">设备名称</span>
          <input type="text" class="form-control" name="dvd[1][0]">
        </div> 
      </div><!-- col1 end -->
      <div class="col-md-4"><!-- col2 start -->   
        <div class="input-group">
          <span class="input-group-addon">设备型号</span>
          <input type="text" class="form-control" name="dvd[1][1]">
        </div> 
      </div><!-- clo2 end -->
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">所属类别</span>
          <input type="text" class="form-control" name="dvd[1][2]">
        </div>  
      </div>
    </div><!-- sample end -->
    
    <!-- 拆分第2部分 -->
    <!-- sample of the divide informaition ____the first -->
    <div class="part row" id="part-2">
      <h4>拆分第 2 部分</h4>
      <div class="col-md-4"><!-- col1 start -->
        <div class="input-group">
          <span class="input-group-addon">设备名称</span>
          <input type="text" class="form-control" name="dvd[2][0]">
        </div> 
      </div><!-- col1 end -->
      <div class="col-md-4"><!-- col2 start -->   
        <div class="input-group">
          <span class="input-group-addon">设备型号</span>
          <input type="text" class="form-control" name="dvd[2][1]">
        </div> 
      </div><!-- clo2 end -->
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">所属类别</span>
          <input type="text" class="form-control" name="dvd[2][2]">
        </div>  
      </div>
    </div><!-- sample end -->
    
    <div id="addPart"></div>

    <div style="text-align: center">
      <input type="hidden" name="id" value="<?php echo $id;?>">
      <input type="hidden" name="flag" value="dvdSpare">
      <button type="submit" class="btn btn-primary" style="width:200px;margin-bottom: 30px" id="dvd">确 认 拆 分</button>       
    </div>
</form>
  <div class="modal fade"  id="failPart" >
      <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
        <div class="modal-content">
             <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body"><br/>
                <div class="loginModal">拆分总件数应大于2，请重新填写。</div><br/>
             </div>
             <div class="modal-footer">  
              <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
        </div>
      </div>
    </div>
    <div class="modal fade"  id="notNum" >
      <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
        <div class="modal-content">
             <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body"><br/>
                <div class="loginModal">所填内容应为数字，请重新填写。</div><br/>
             </div>
             <div class="modal-footer">  
              <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
        </div>
      </div>
    </div>

</div>
</body>
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
 //时间选择器
  $(".datetime").datetimepicker({
    format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2
  });

$(document).on("keyup","#dvdNum",addPartByNum);
  function addPartByNum(){
    if(isNaN($(this).val())){
      $('#notNum').modal({
                keyboard: true
            });
    }
    var $dvdNum=$(this).val()-2;
    // 所填数字需大于2
    if($dvdNum<0){
      // 当删去重新填写时，删掉相应添加的input
      if($(this).val()==""){
       $("#addPart").empty(); 
       return;
    }
      $('#failPart').modal({
                keyboard: true
            });
    }
    for (var i = 0; i < $dvdNum; i++) {
      $k=i+3;
    var $addHtml=
    "<div class='col-md-4'>"+
    "  <div class='input-group'>"+
    "    <span class='input-group-addon'>设备名称</span>"+
    "    <input type='text' class='form-control' name='dvd["+$k+"][0]'>"+
    "  </div> "+
    "</div>"+
    "<div class='col-md-4'>"+
    "  <div class='input-group'>"+
    "    <span class='input-group-addon'>设备型号</span>"+
    "    <input type='text' class='form-control' name='dvd["+$k+"][1]'>"+
    "  </div> "+
    "</div>"+
    "<div class='col-md-4'>"+
    "  <div class='input-group'>"+
    "    <span class='input-group-addon'>所属类别</span>"+
    "    <input type='text' class='form-control' name='dvd["+$k+"][2]'>"+
    "  </div>  "+
    "</div>";
    
      $("#addPart").append("<div class='part row'><h4>拆分第 "+$k+" 部分</h4>"+$addHtml);
    }
  };

</script>
</html>