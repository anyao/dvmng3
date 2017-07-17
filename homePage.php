<?php 
require_once "model/cookie.php";
require_once "model/sqlHelper.class.php";
require_once "model/devService.class.php";
require_once "model/dptService.class.php";
checkValidate();
$user=$_SESSION['user'];
$sqlHelper = new sqlHelper;
$devService=new devService($sqlHelper);
$dptService=new dptService($sqlHelper);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="普阳钢铁设备管理系统">
<meta name="author" content="安瑶">
<title>首页-设备管理系统</title>
<link rel="icon" href="img/favicon.ico">
<link rel="stylesheet" href="bootstrap/css/choose.css" media="all" type="text/css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->

<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
</head>
<body role="document">
<?php  include "message.php";?>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
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
            <li><a href="<?php echo (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="usingList.php">在用设备</a></li>
                <li><a href="spareList.php">备品备件</a></li>
                <li style="display: <?php echo (in_array(4, $_SESSION['funcid'])  && $_SESSION['user'] != 'admin') ? "inline" : "none";?>"><a href='devPara.php' >属性参数</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">日常巡检 <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="inspStd.php">巡检标准</a></li>
                <li><a href="inspMis.php">巡检计划</a></li>
                <li><a href="inspList.php">巡检记录</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">维修保养 <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="repPlan.php">检修计划</a></li>
                <li><a href="repMis.php">维修/保养任务</a></li>
                <li><a href="repList.php">维修记录</a></li>
              </ul>
            </li>
          </ul>
           <ul class="nav navbar-nav navbar-right">
            <li style="display: <?php echo (in_array(10, $_SESSION['funcid'])  && $_SESSION['user'] != 'admin') ? "inline" : "none";?>"><a href='dptUser.php'>用户管理</a></li>
            <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php echo $user;?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="javascript:chgPwd();">更改密码</a></li>
                <li><a href="login.php">注销</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<img src="img/jum.jpg" style="width:100%;z-index:-999;position:absolute;top:0;left:0;height:50%">

<form action="usingList.php" method="post">
<div class="jumbotron">
  <div class="container">
  <div class="jum-caption">
      <h2>Search..</h2>
        <div class="form-group">
          <div class="input-group">
          <input type="text" class="form-control input-lg" placeholder="请输入要搜索的名称/关键字" name='keyword'>
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle btn-lg" data-toggle="dropdown">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu" style="color: #333">
              </ul>
            </div>
            <!-- /btn-group -->
          </div>
        </div>
        <div class="form-group">
          <input type="hidden" name="flag" value="findDev">
          <button class="btn btn-lg btn-primary">&nbsp;&nbsp;搜&nbsp;&nbsp;&nbsp;索&nbsp;&nbsp;</button>
        </div>

    </div>
  </div>
</div>
<div class="container theme-showcase" role="main">
<div class="row" >
  <ul class="nav nav-pills navbar-right" >
    <li class="active"><a href="javascript:toggleChos('py')">河北普阳钢铁有限公司</a></li>
    <li><a href="javascript:toggleChos('zp')">河北中普(邯郸)钢铁有限公司</a></li>
    <li><a href="javascript:toggleChos('jh')">武安广普焦化有限公司</a></li>    
  </ul>
</div>
<!-- 普阳钢铁 -->
<div class="choose-which" id="py">
    <div class="row">
      <div class="col-md-1">
        <label>所选：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose-result">
          <li class="choose-result1"></li>
          <li class="choose-result2"></li>
          <li class="choose-result3"></li>
        </ul><br/>
      </div> 
    </div>  

    <div class="row">
      <div class="col-md-1">
        <label>分厂：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="plant1">
          
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $fct=$dptService->getFctAll(1);
            for ($i=0; $i < count($fct); $i++) { 
              echo "<li value='{$fct[$i]['id']}'><a href=\"javascript:void(0)\">{$fct[$i]['depart']}</a></li>";
            }
          ?>
        </ul><br/>
      </div>
    </div>

    <div class="row">
      <div class="col-md-1">
        <label>部门：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="sector1">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $sec=$dptService->getSecSome(1,"");
            for ($i=0; $i < count($sec); $i++) { 
              echo "<li value='{$sec[$i]['id']}'><a href=\"javascript:void(0)\">{$sec[$i]['factory']}-{$sec[$i]['depart']}</a></li>";
            }
           ?>
           <li>••••</li>
        </ul><br/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-1">
        <label>科室：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose" id="office1">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $ofc=$dptService->getOfcSome(1,"");
            for ($i=0; $i < count($ofc); $i++) { 
              echo "<li value='{$ofc[$i]['id']}'><a href=\"javascript:void(0)\">{$ofc[$i]['factory']}-{$ofc[$i]['depart']}-{$ofc[$i]['sector']}</a></li>";
            }
           ?>
          <li>••••</li>
        </ul><br/>
        </div>
    </div>
</div>

<!-- 中普 -->
<div class="choose-which" id="zp" style="display:none">
    <div class="row">
      <div class="col-md-1">
        <label>所选：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose-result">
          <li class="choose-result1"></li>
          <li class="choose-result2"></li>
          <li class="choose-result3"></li>
        </ul><br/>
      </div> 
    </div>  

    <div class="row">
      <div class="col-md-1">
        <label>分厂：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="plant2">
          
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $fct=$dptService->getFctAll(2);
            for ($i=0; $i < count($fct); $i++) { 
              echo "<li value='{$fct[$i]['id']}'><a href=\"javascript:void(0)\">{$fct[$i]['depart']}</a></li>";
            }
          ?>
        </ul><br/>
      </div>
    </div>

    <div class="row">
      <div class="col-md-1">
        <label>部门：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="sector2">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $sec=$dptService->getSecSome(2,"");
            for ($i=0; $i < count($sec); $i++) { 
              echo "<li value='{$sec[$i]['id']}'><a href=\"javascript:void(0)\">{$sec[$i]['factory']}-{$sec[$i]['depart']}</a></li>";
            }
           ?>
           <li>••••</li>
        </ul><br/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-1">
        <label>科室：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose" id="office2">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $ofc=$dptService->getOfcSome(2,"");
            for ($i=0; $i < count($ofc); $i++) { 
              echo "<li value='{$ofc[$i]['id']}'><a href=\"javascript:void(0)\">{$ofc[$i]['factory']}-{$ofc[$i]['depart']}-{$ofc[$i]['sector']}</a></li>";
            }
           ?>
          <li>••••</li>
        </ul><br/>
        </div>
    </div>
</div>

<!-- 焦化 -->
<div class="choose-which" style="display:none" id="jh">
    <div class="row">
      <div class="col-md-1">
        <label>所选：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose-result">
          <li class="choose-result1"></li>
          <li class="choose-result2"></li>
          <li class="choose-result3"></li>
        </ul><br/>
      </div> 
    </div>  

    <div class="row">
      <div class="col-md-1">
        <label>分厂：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="plant3">
          
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $fct=$dptService->getFctAll(3);
            for ($i=0; $i < count($fct); $i++) { 
              echo "<li value='{$fct[$i]['id']}'><a href=\"javascript:void(0)\">{$fct[$i]['depart']}</a></li>";
            }
          ?>
        </ul><br/>
      </div>
    </div>

    <div class="row">
      <div class="col-md-1">
        <label>部门：</label>
      </div>
      <div class="col-md-11">
        <ul class="choose" id="sector3">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $sec=$dptService->getSecSome(3,"");
            for ($i=0; $i < count($sec); $i++) { 
              echo "<li value='{$sec[$i]['id']}'><a href=\"javascript:void(0)\">{$sec[$i]['factory']}-{$sec[$i]['depart']}</a></li>";
            }
           ?>
           <li>••••</li>
        </ul><br/>
      </div>
    </div>
    <div class="row">
      <div class="col-md-1">
        <label>科室：</label>
      </div> 
      <div class="col-md-11">
        <ul class="choose" id="office3">
          <li><a href="javascript:void(0);" class="choose-all badge">全部</a></li>
          <?php 
            $ofc=$dptService->getOfcSome(3,"");
            for ($i=0; $i < count($ofc); $i++) { 
              echo "<li value='{$ofc[$i]['id']}'><a href=\"javascript:void(0)\">{$ofc[$i]['factory']}-{$ofc[$i]['depart']}-{$ofc[$i]['sector']}</a></li>";
            }
           ?>
          <li>••••</li>
        </ul><br/>
        </div>
    </div>
</div>


</div>
</form>



  <!-- 页脚 -->
  <div class="foothome">
    &copy; 河北普阳钢铁有限公司　2016
  </div>

<script type="text/javascript">
function toggleChos(id){
  $("#"+id).show();
  $(".choose-which").not("#"+id).hide();
}

$("#py").on("click","#plant1 li",function chooseA(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseA").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseA").length > 0) {
          $("#chooseA").html($(this).text());
        }else{
          $("#py .choose-result1").append(copy1.attr("id","chooseA"));  
        }
        $("#chooseB").remove();
        $("#chooseC").remove();
        $("#sector1 .choose-all").addClass('badge');

        $("#chooseA").append("<input type='hidden' name='factory' value='"+$(this).val()+"'>");
        
        var fid=$("#chooseA input").val();
        $.get("controller/dptProcess.php",{
          flag:'getSector',
          fid:fid
        },function(data,success){
          var $sector=$("#sector1 li:not(:first)");
          $sector.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#sector1").append(addHtml);
          }
        },"json");
      }
});

$("#py").on("click","#sector1 li",function chooseB(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseB").remove();
      }else{
        var copy1 = $(this).children().clone();
        copy1.attr("name","depart");
        copy1.attr("value",copy1.text());
        if ($("#chooseB").length > 0) {
          $("#chooseB").html($(this).text());
        }else{
          $("#py .choose-result2").append(copy1.attr("id","chooseB"));
        }
        $("#chooseB").append("<input type='hidden' name='sector' value='"+$(this).val()+"'>");
        
        $("#chooseC").remove();
        $("#office1 .choose-all").addClass('badge');

        var sid=$("#chooseB input").val();
        $.get("controller/dptProcess.php",{
          flag:'getOffice',
          sid:sid
        },function(data,success){
          // [{"id":"55","depart":"设备","path":"-52-53","comp":"1"},
          // {"id":"56","depart":"电气","path":"-52-53","comp":"1"}]
          var $office=$("#office1 li:not(:first)");
          $office.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#office1").append(addHtml);
          }
        },"json");
      }
});

$("#py").on("click","#office1 li",function chooseC(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseC").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseC").length > 0) {
          $("#chooseC").html($(this).text());
        }else{
          $("#py .choose-result3").append(copy1.attr("id","chooseC"));
        }
         $("#chooseC").append("<input type='hidden' name='office' value='"+$(this).val()+"'>");
      }
});
 
$("#zp").on("click","#plant2 li",function chooseD(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseA").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseA").length > 0) {
          $("#chooseA").html($(this).text());
          
        }else{
          $("#zp .choose-result1").append(copy1.attr("id","chooseA"));  
        }
        $("#chooseB").remove();
        $("#chooseC").remove();
        $("#sector2 .choose-all").addClass('badge');

        $("#chooseA").append("<input type='hidden' name='factory' value='"+$(this).val()+"'>");
        
        var fid=$("#chooseA input").val();
        $.get("controller/dptProcess.php",{
          flag:'getSector',
          fid:fid
        },function(data,success){
          var $sector=$("#sector2 li:not(:first)");
          $sector.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#sector2").append(addHtml);
          }
        },"json");
      }
});

$("#zp").on("click","#sector2 li",function chooseE(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseB").remove();
      }else{
        var copy1 = $(this).children().clone();
        copy1.attr("name","depart");
        copy1.attr("value",copy1.text());
        if ($("#chooseB").length > 0) {
          $("#chooseB").html($(this).text());
        }else{
          $("#zp .choose-result2").append(copy1.attr("id","chooseB"));
        }
        $("#chooseB").append("<input type='hidden' name='sector' value='"+$(this).val()+"'>");
        
        $("#chooseC").remove();
        $("#office2 .choose-all").addClass('badge');

        var sid=$("#chooseB input").val();
        $.get("controller/dptProcess.php",{
          flag:'getOffice',
          sid:sid
        },function(data,success){
          // [{"id":"55","depart":"设备","path":"-52-53","comp":"1"},
          // {"id":"56","depart":"电气","path":"-52-53","comp":"1"}]
          var $office=$("#office2 li:not(:first)");
          $office.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#office2").append(addHtml);
          }
        },"json");
      }
});

$("#zp").on("click","#office2 li",function chooseF(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseC").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseC").length > 0) {
          $("#chooseC").html($(this).text());
        }else{
          $("#zp .choose-result3").append(copy1.attr("id","chooseC"));
        }
         $("#chooseC").append("<input type='hidden' name='office' value='"+$(this).val()+"'>");
      }
});

$("#jh").on("click","#plant3 li",function chooseG(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseA").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseA").length > 0) {
          $("#chooseA").html($(this).text());
          
        }else{
          $("#jh .choose-result1").append(copy1.attr("id","chooseA"));  
        }
        $("#chooseB").remove();
        $("#chooseC").remove();
        $("#sector3 .choose-all").addClass('badge');

        $("#chooseA").append("<input type='hidden' name='factory' value='"+$(this).val()+"'>");
        
        var fid=$("#chooseA input").val();
        $.get("controller/dptProcess.php",{
          flag:'getSector',
          fid:fid
        },function(data,success){
          var $sector=$("#sector3 li:not(:first)");
          $sector.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#sector3").append(addHtml);
          }
        },"json");
      }
});

$("#jh").on("click","#sector3 li",function chooseH(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseB").remove();
      }else{
        var copy1 = $(this).children().clone();
        copy1.attr("name","depart");
        copy1.attr("value",copy1.text());
        if ($("#chooseB").length > 0) {
          $("#chooseB").html($(this).text());
        }else{
          $("#jh .choose-result2").append(copy1.attr("id","chooseB"));
        }
        $("#chooseB").append("<input type='hidden' name='sector' value='"+$(this).val()+"'>");
        
        $("#chooseC").remove();
        $("#office3 .choose-all").addClass('badge');

        var sid=$("#chooseB input").val();
        $.get("controller/dptProcess.php",{
          flag:'getOffice',
          sid:sid
        },function(data,success){
          // [{"id":"55","depart":"设备","path":"-52-53","comp":"1"},
          // {"id":"56","depart":"电气","path":"-52-53","comp":"1"}]
          var $office=$("#office3 li:not(:first)");
          $office.detach();
          for (var i = 0; i < data.length; i++) {
            var addHtml="<li value='"+data[i]['id']+"'><a href=\"javascript:void(0)\">"+data[i]['depart']+"</a></li>"
            $("#office3").append(addHtml);
          }
        },"json");
      }
});

$("#jh").on("click","#office3 li",function chooseI(){
  // 点击选中条件，并取消其他条件的高亮
    $(this).children().addClass('badge');
      $(this).siblings().children().removeClass("badge");
      if($(this).children().hasClass("choose-all")){
        $("#chooseC").remove();
      }else{
        var copy1 = $(this).children().clone();
        if ($("#chooseC").length > 0) {
          $("#chooseC").html($(this).text());
        }else{
          $("#jh .choose-result3").append(copy1.attr("id","chooseC"));
        }
         $("#chooseC").append("<input type='hidden' name='office' value='"+$(this).val()+"'>");
      }
});

// 搜索提示
$(".jum-caption input[name=keyword]").bsSuggest({
        allowNoKeyword: false,
        inputWarnColor:"#f5f5f5",
        showBtn: false,
        indexId: 0,  //data.value 的第几个数据，作为input输入框的内容
        indexKey: 1, //data.value 的第几个数据，作为input输入框的内容
        data: {
            'value':<?php 
              // $allRoot=$devService->getRootAll();
              // echo "$allRoot";
            ?>,
            'defaults':'未找到相关内容'
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var devid=$(this).attr("data-id");
        var addHtml="<input type='hidden' name='devid' value='"+devid+"'>"
        $("input[value=findDev]").after(addHtml);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

  </script>
</body>
</html>