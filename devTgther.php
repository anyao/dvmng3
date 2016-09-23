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
    .basic{
      border-bottom: 1px solid #CCCCCC;
      padding:5px 0px;
    }

    form >.basic >  .form-group{
      position: relative;
      left: -180px;
    }
    
    form >.basic > #addPart > .form-group{
      position: relative;
      left: -180px;
      border-bottom: none
    }
    .part{
      margin: 20px 0px 30px 0px;
      border: 1px solid #A9A9A9;
      border-radius: 6px;
    }
    
    .part > h4{
      margin-left: 30px;
    } 
    .part > .col-md-6 > .input-group{
      margin:5px 30px 20px 30px !important;
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
require_once "model/spareService.class.php";
require_once "model/repairService.class.php"; 
$spareService=new spareService();
$repairService=new repairService();
include "message.php";
$id=$_GET['id'];
$devName=$_GET['devName'];
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
      </ul>

    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="container">
  <div class="page-header">
    <h3>　选择设备拼装要求</h3>
  </div>
    <form class="form-horizontal" action="controller/spareProcess.php" method="post">  
  <div class="basic">
      <div class="form-group">
        <label class="col-sm-3 control-label">拼装人员：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name="liable">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拼装时间：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control datetime" name="time" readonly>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拼装描述：</label>
        <div class="col-sm-7">
          <textarea class="form-control" rows="3" name="info" placeholder="请简要描述拼装原因和情况(必填)..."></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">拼装件数：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name="tgtherNum" placeholder="请输入准备要拼装几部分，只可输入数字 ( 默认为 2 )">
        </div>
      </div>
  </div>
 
  <div class="basic">

      <div class="form-group">
        <label class="col-sm-3 control-label">第 1 部分：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name="tgther[]" readonly="readonly" value="<?php echo $devName."-".$id;?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">第 2 部分：</label>
        <div class="col-sm-7">
          <input type="text" class="form-control" name="tgther[]">
        </div>
      </div>
      <div id="addPart"></div>

  </div>

  <div class="part row" id="part-1">
    <h4>拼装后的设备信息</h4>
    <div class="col-md-6"><!-- col1 start -->
      <div class="input-group">
        <span class="input-group-addon">设备名称</span>
        <input type="text" class="form-control  notNull" name="name">
      </div> 
     

      <div class="input-group">
        <span class="input-group-addon">设备编号</span>
        <input type="text" class="form-control" name="code">  
      </div>  

      <div class="input-group">
        <span class="input-group-addon">设备型号</span>
        <input type="text" class="form-control" name="no">
      </div>

        
    </div><!-- col1 end -->
    <div class="col-md-6"><!-- col2 start -->
    <div class="input-group">
        <span class="input-group-addon">所在部门</span>
        <input type="text" class="form-control  notNull" name="depart">
      </div>

    <div class="input-group">
      <span class="input-group-addon">所在分厂</span>
      <input type="text" class="form-control notNull" name="factory">
    </div> 
 <div class="input-group">
        <span class="input-group-addon">设备类别</span>
        <input type="text" name="class" class="form-control notNull">
        <div class="input-group-btn">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right" role="menu">
          </ul>
        </div>
        <!-- /btn-group -->
      </div>



    </div><!-- clo2 end -->
  </div><!-- sample end -->

  <div style="text-align: center">
    <input type="hidden" name="flag" value="tgther">
    <input type="hidden" name="devid" value="<?php echo $id;?>">
    <button class="btn btn-primary" style="width:200px;margin:-8px 0px" id="tgther">确 认 拼 装</button>       
  </div>

  </form>
  <div class="modal fade"  id="failPart" >
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
             <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body"><br/>
                <div class="loginModal">拼装总件数应大于2，请重新填写。</div><br/>
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

</div>
</body>
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript">
$("#tgther").click(function(){
 var allow_submit = true;
 $(".notNull").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
});

 //时间选择器
  $(".datetime").datetimepicker({
    format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2
  });

  // $("input[name=tgtherNum]").click(function(){
  //   if($(this).val()==""){
  //   //   $("#addPart").detach();
  //   alert($(this).val());
  //   }
  // })
$(document).on("keyup","input[name=tgtherNum]",TgtherByNum)
  function TgtherByNum(){
    if(isNaN($(this).val())){
      $('#notNum').modal({
                keyboard: true
            });
    }
    var $tgtherNum=$(this).val()-2;
    // 所填数字需大于2
    // if($tgtherNum<0){
      // 当删去重新填写时，删掉相应添加的input
      if($(this).val()==""){
       $("#addPart").empty(); 
       // return;
    }
    //   $('#failPart').modal({
    //             keyboard: true
    //         });
    // }
    
    var $addHtml=
    " 部分：</label>"+
    "<div class='col-sm-7'>"+
    "<input type='text' class='form-control' name='tgther[]'>"+
    "</div>"+
    "</div>";
    // <div class="form-group">
        // <label class="col-sm-3 control-label">第 2
    for (var i = 0; i < $tgtherNum; i++) {
      $k=i+3
      $("#addPart").append("<div class='form-group'><label class='col-sm-3 control-label'>第 "+$k+$addHtml);
    }
  };


   $("input[name=class]").bsSuggest({
          allowNoKeyword: false,
          // indexId:1,
          // indexKey: 1,
          // showBtn:false,
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

</script>
</html>