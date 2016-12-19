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

<title>点检任务-设备管理系统</title>

<!-- Bootstrap core CSS -->
<style type="text/css">
  thead > tr > th:nth-last-child(1), thead > tr > th:nth-last-child(2){
      width:4%;
  }

  thead > tr > td:first-child{
    display:none;
  }
</style>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/css/treeview.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/supTips.css">
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
    require_once 'model/inspectService.class.php';
    require_once 'model/paging.class.php';
    $paging=new paging();
    $paging->pageNow=1;
    $paging->pageSize=18;
    $paging->gotoUrl="inspMis.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $inspectService=new inspectService();
    if (empty($_POST['flag'])) {
      $inspectService->getPagingMis($paging);
    }else{
      if (!empty($_POST['devid'])) {
        $devid=$_POST['devid'];
      }else{
        $devid='';
      }
      if (!empty($_POST['name'])) {
        $name=$_POST['name']; 
      }else{
        $name='';
      }
      if (!empty($_POST['time'])) {
        $time=$_POST['time']; 
      }else{
        $time='';
      }
      $inspectService->findMis($devid,$name,$time,$paging);
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
        <li class="dropdown  active">
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
        <h4>　巡检任务</h4>
      </div>
    <table class="table table-striped table-hover">
        <thead><tr>
            <th style='width:4%'></th>
            <th>下一次巡检时间</th><th>检查类型</th><th>设备名称</th><th>巡检周期</th><th>执行部门</th><th>使用部门</th>
            <th style='width:4%'></th><th style='width:4%'></th>
        </tr></thead>
        <tbody class="tablebody">     
     <?php
        for ($i=0; $i < count($paging->res_array); $i++) { 
          $row = $paging->res_array[$i];
          $cyc = $inspectService->transTime($row['cyc']);
          // [id] => 35 [devid] => 45 [cyc] => 480 [nxt] => 2016-12-03 16:00:00 [type] => 1 [inspDpt] => 2 
          // [factory] => 新区竖炉 [depart] => 竖炉车间 [inspdpt] => 竖炉车间 [inspfct] => 新区竖炉
          $addHtml = 
          "<tr>
            <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0);' onclick='buyList(this,{$row['id']})'></a></td>
            <td>{$row['nxt']}<td>
            <td>{$row['type']}</td>
            <td><a href='using.php?id={$row['devid']}'>{$row['name']}</a></td>
            <td></td>
          </tr>";
        }
        //  [2016-12-03 16:00:00] => Array
        // (
        //     [0] => Array
        //         (
        //             [id] => 35
        //             [devid] => 45
        //             [cyc] => 480
        //             [nxt] => 2016-12-03 16:00:00
        //             [type] => 1
        //             [inspDpt] => 2
        //             [name] => 加热炉电源柜
        //             [factory] => 新区竖炉
        //             [depart] => 竖炉车间
        //             [inspfct] => 新区竖炉
        //         )
        // foreach ($result as $k => $v) {
        //   $addHtml="<tr><td>$k</td><td>{$v[0]['depart']}</td><td>{$v[0]['factory']}</td><td>";
        //   for ($i=0; $i < count($v); $i++) { 
        //     $misid[]=$v[$i]['id'];
        //     if ($i==count($v)-1) {
        //       $addHtml.="<a href='using.php?id={$v[$i]['devid']}'>{$v[$i]['name']}</a>";
        //     }else{
        //       $addHtml.="<a href='using.php?id={$v[$i]['devid']}'>{$v[$i]['name']}</a>、";
        //     }
        //   }
        //   $misid=implode(",",$misid);
        //   $addHtml.="</td><td><a href=\"javascript:uptMis('{$misid}');\" class='glyphicon glyphicon-edit'></a></td>
        //   <td><a href=\"javascript:delMis('{$misid}');\" class='glyphicon glyphicon-trash'></a></td></tr>";
        //   echo "$addHtml";
        // }
        
     ?>
        </tbody></table><div class='page-count'><?php echo $paging->navi;?></div>         
    </div>
    <?php include "inspNavi.php" ?>
</div>
</div>
  <!-- 删除弹出框 -->
<div class="modal fade"  id="delMis" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要该条巡检任务吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<!-- 修改任务信息-->
<div class="modal fade" id="uptMis" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">修改点检任务</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">点检时间：</label>
            <div class="col-sm-7">
                <input type="text" class="form-control datetime" name="start">     
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">设备列表：</label>
            <div class="col-sm-8" id="forDev">
            </div>
          </div>

          <div class='form-group' >
            <label class='col-sm-3 control-label'>点检设备：</label>
              <div class='col-sm-7'>
            <div class='input-group'>
              <input type='text' class='form-control' name="devName">
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
             <a href="javascript:void(0);" id="yesDev" class='glyphicon glyphicon-ok'></a>
            </div>
          </div>

          
          <div class="modal-footer">
            <input type="hidden" name="flag" value="uptMis">
            <input type="hidden" name="oid">
            <button type="submit" class="btn btn-primary" id="updateYes">确认修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>



  <!-- 时间添加失败弹出框 -->
<div class="modal fade"  id="noTime" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前时间添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 修改任务弹出框 添加新的设备未完成-->
<div class="modal fade"  id="noDev" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前设备添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>



<!-- 信息不完整弹出框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">您所填信息不完整，请补充。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 点检任务提醒 -->
<!-- <div class="row" id="message">
   <div class='col-md-12' >
    <div class='alert alert-warning' id='mesRepMis'>
       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       <strong>您今天有 <span>1</span> 项点检任务！</strong><a href='repMis.php'>点击查看</a> 或 <a href='repMis.php'>不再提醒</a>。
    </div>
  </div>
</div> -->




    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    <script type="text/javascript">
    // var auth = <?php echo $_SESSION['permit']; ?>;
    // 修改任务弹出框中确认添加设备按钮
    $("#uptMis #yesDev").click(function(){
      if($("#uptMis input[name=devName]").val().length>0){
        var nameDev=$("#uptMis input[name=devName]").val();
        var idDev=$("#uptMis input[name=devName]").attr("data-id");
        var addHtml="<span class='badge'>"+nameDev+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='dev[]' value="+idDev+"></span> "
        $("#uptMis #forDev").append(addHtml);
        $("#uptMis input[name=devName]").val("");
      }else{
        $('#noDev').modal({
          keyboard: true
        });
      }  
    });

    

     //时间选择器
      $(".datetime").datetimepicker({
        format: 'hh:ii', language: "zh-CN", autoclose: true,startView:1,minView:0
      });
    // 已确定添加的设备删除
    $(document).on("click",".glyphicon-remove",delDeved);
    function delDeved(){
      $(this).parents("span").detach();
    }
   
   // 确认修改按钮
   $("#updateYes").click(function(){
     var allow_submit = true;
     var forDev=$("#uptMis #forDev input").length;
     var forTime=$("#uptMis input[name=start]").val().length;
    if (forDev==0 || forTime==0) {
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     return allow_submit;
   });
  
   // 删除巡检任务
   function delMis(arr){
    
      $('#delMis').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="controller/inspectProcess.php?flag=delMis&misid="+arr;
      });            
  }

    function uptMis(idArr){
      $.get("controller/inspectProcess.php",{
        idArr:idArr,
        flag:"getMis"
      },function(data,success){
        // {"id":"35","devid":"45","cyc":"480","nxt":"2016-12-03 16:00:00","name":"加热炉电源柜"}
         // var arr=new Array();
         // $("#uptMis input[name=start]").val(time);
         // $("#uptMis input[name=mid]").val(idMis);
         // for(var i=0;i<data.length;i++){
         //    var nameDev=data[i].name;
         //    var idDev=data[i].devid;
         //    var idMis=data[i].id;
         //    arr[i]=idMis;
         //    var addHtml="<span class='badge'>"+nameDev+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='dev[]' value="+idDev+"></span> "
         //    $("#uptMis #forDev").append(addHtml);
         // }
         // $("#uptMis input[name=oid]").val(arr);
         // $("#uptMis #forDev").empty();
         $('#uptMis').modal({
              keyboard: true
         });
      },"json");
    }

    $("input[name=devName]").bsSuggest({
        allowNoKeyword: false,
         showBtn: false,
        indexKey: 0,
        indexId:1,
        inputWarnColor: '#f5f5f5',
        data: {
           'value':<?php
                    $devAll=$inspectService->getUsingAll();
                    echo "$devAll";
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

    
    </script>
        <?php 
        include "inspJs.php";
        ?>
  </body>
</html>