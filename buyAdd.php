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

<title>备件申报明细-仪表管理</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
    .basic{
      border-bottom: 1px solid #CCCCCC;
      padding:5px 10px 0px 10px;
    }

    form >.basic > .row > .col-md-4 > .input-group{
      margin-bottom: 15px;
    }

    .part{
      padding: 10px 0px 10px 0px;
      border-bottom: 1px solid #CCCCCC;
    }
    
    .part > .col-md-4 > .input-group{
      margin:5px;
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
  // $id=$_GET['id'];
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
            <li><a href="devBuy.php">仪表备件申报</a></li>
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
<div class="container">
  <div class="page-header">
    <h4>　仪表备件申报明细</h4>
  </div>
<form class="form-horizontal" action="controller/spareProcess.php" method="post">  
  <div class="basic">
    <div class="row">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">申报时间</span>
          <input type="text" class="form-control" name="applytime" value='<?php echo date("Y-m-d H:i");?>' readonly>
        </div> 
        <div class="input-group">
          <span class="input-group-addon">申报分厂</span>
          <input type="text" class="form-control" name='factory' readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">C L J 
          L</span>
          <input type="text" class="form-control" name="depart" readonly>
        </div> 
        <div class="input-group">
          <span class="input-group-addon">申报人</span>
          <input type="text" class="form-control" name='factory' readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-addon">申报单位</span>
          <input type="text" class="form-control" name="depart" readonly>
        </div> 
        <div class="input-group">
          <span class="input-group-addon">备件总数</span>
          <input type="text" class="form-control" name="sprNum">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" id="sprNum">确定</button>
          </span>
        </div>
      </div>
    </div>
  </div>
  <?php 
    $addHtml="";
    for ($i=0; $i < 6; $i++) { 
      $addHtml.="<div class='part row' id='part-".($i+1)."'>".
               " <div class='col-md-4'>".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>".($i+1).": 存货编码</span>".
               "     <input type='text' class='form-control' name='dvd[1][0]'>".
               "   </div> ".
               " </div>".
               " <div class='col-md-4'> ".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>存货名称</span>".
               "     <input type='text' class='form-control' name='dvd[1][1]'>".
               "   </div> ".
               " </div>".
               " <div class='col-md-4'> ".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>规格型号</span>".
               "     <input type='text' class='form-control' name='dvd[1][1]'>".
               "   </div> ".
               " </div>".
               " <div class='col-md-4'>".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>　单　　位</span>".
               "     <input type='text' class='form-control' name='dvd[1][2]'>".
               "   </div>  ".
               " </div>".
               " <div class='col-md-4'>".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>数　　量</span>".
               "     <input type='text' class='form-control' name='dvd[1][2]'>".
               "   </div>  ".
               " </div>".
               " <div class='col-md-4'>".
               "   <div class='input-group'>".
               "     <span class='input-group-addon'>备注描述</span>".
               "     <input type='text' class='form-control' name='dvd[1][2]'>".
               "   </div>  ".
               " </div>".
               "</div>";
    }
    echo "$addHtml";
  ?>
    <div id="addPart"></div>

    <div style="text-align: center">
      <input type="hidden" name="id" value="<?php echo $id;?>">
      <input type="hidden" name="flag" value="dvdSpare">
      <button type="submit" class="btn btn-primary" style="width:200px;margin: 20px 0px" id="dvd">确 认 申 报</button>       
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

$(document).on("click","#sprNum",addPartByNum);
  function addPartByNum(){
    var num=$("input[name=sprNum]").val();
    if(isNaN(num)){
      $('#notNum').modal({
                keyboard: true
            });
    }
    var $sprNum=num-2;
    // 所填数字需大于2
    if($sprNum<0){
      // 当删去重新填写时，删掉相应添加的input
      if(num==""){
       $("#addPart").empty(); 
       return;
    }
      $('#failPart').modal({
                keyboard: true
            });
    }
    for (var i = 0; i < $sprNum; i++) {
      $k=i+6;
    var $addHtml=
    "<div class='part row' id='part-1'>"+
    "  <div class='col-md-4'>"+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>"+$k+": 存货编码</span>"+
    "      <input type='text' class='form-control' name='dvd[1][0]'>"+
    "    </div> "+
    "  </div>"+
    "  <div class='col-md-4'> "+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>存货名称</span>"+
    "      <input type='text' class='form-control' name='dvd[1][1]'>"+
    "    </div> "+
    "  </div>"+
    "  <div class='col-md-4'> "+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>规格型号</span>"+
    "      <input type='text' class='form-control' name='dvd[1][1]'>"+
    "    </div> "+
    "  </div>"+
    "  <div class='col-md-4'>"+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>　单　　位</span>"+
    "      <input type='text' class='form-control' name='dvd[1][2]'>"+
    "    </div>  "+
    "  </div>"+
    "  <div class='col-md-4'>"+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>数　　量</span>"+
    "      <input type='text' class='form-control' name='dvd[1][2]'>"+
    "    </div>  "+
    "  </div>"+
    "  <div class='col-md-4'>"+
    "    <div class='input-group'>"+
    "      <span class='input-group-addon'>备注描述</span>"+
    "      <input type='text' class='form-control' name='dvd[1][2]'>"+
    "    </div>  "+
    "  </div>"+
    "</div>";
    
      $("#addPart").append($addHtml);
    }
  };

</script>
</html>