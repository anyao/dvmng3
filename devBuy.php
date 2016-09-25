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
<title>备件申报-仪表管理</title>
<style type="text/css">
#apvSpr li{
    list-style: none;
    margin:10px 0px;
    /*font-size: 18px */
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
    require_once 'model/inspectService.class.php';
    require_once 'model/paging.class.php';

    // $paging=new paging();
    // $paging->pageNow=1;
    // $paging->pageSize=18;
    // $paging->gotoUrl="devInspect.php";
    // if (!empty($_GET['pageNow'])) {
    //   $paging->pageNow=$_GET['pageNow'];
    // }

    // $inspectService=new inspectService();
    // $inspectService->getPaging($paging);
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
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件申报</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
           <th style="width: 8%"></th>
           <th style="width: 24%">申报时间</th>
           <th style="width: 18%">申报分厂</th>
           <th style="width: 15%">申报单位</th>
           <th style="width: 10%">申报人</th>
           <th style="width: 17%">CLJL</th>
           <th style="width: 4%"></th>
           <th style="width: 4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
          <tr>
            <td><a class="glyphicon glyphicon-resize-small" href="javascript:void(0);" onclick="buyList(this,201609242100)"></a></td>
            <td>2016-09-24 21:00</td>
            <td>焦化厂</td>
            <td>电工段</td>
            <td>张军兵</td>
            <td>CLJL-XXXX-09</td>
            <td><span class='glyphicon glyphicon-gift' style="display: inline;cursor: default;"></span></td>
            <td><a href="javascript:apvSpr(123);" class='glyphicon glyphicon-envelope' style="display: inline"></a></td>
         </tr>

        </tbody>
        </table>
                
                 
    </div>
    <div class="col-md-2">
       <div class="col-md-3">
    <div class="sidebar-module">
      <h3>Functions</h3>
      <ol class="list-unstyled">
        <li><a class="badge" href="buyApply.php"><span class="glyphicon glyphicon-list-alt"></span> 备件申报列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addInspect"><span class="glyphicon glyphicon-plus"></span> 添加新的备件申报 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findInspect"><span class="glyphicon glyphicon-search"></span> 搜索备件申报记录 </a></li>
        <li style="height: 10px"></li>
        <li><a class="badge" href="##"><span class="glyphicon glyphicon-sunglasses"></span> 备件审核列表 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索备件审核记录 </a></li>
        <li style="height: 10px"></li>
        <li><a class="badge" href="##"><span class="glyphicon glyphicon-glass"></span> 备件入厂检定登记列表 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索入厂登记记录 </a></li>

        <li style="height: 10px"></li>
        <li><a class="badge" href="##"><span class="glyphicon glyphicon-briefcase"></span> 备件入账存库列表 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索入账存库记录 </a></li>

        <li style="height: 10px"></li>
        <li><a class="badge" href="##"><span class="glyphicon glyphicon-cog"></span> 备件安装验收列表 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索安装验收记录 </a></li>
      </ol>
    </div>
    </div>


</div>
</div>
</div>
<!-- 审批状态 -->
<div class="modal fade" id="apvSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">当前状态</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div>
            <ul style="padding-left: 8%;margin: 20px;border-bottom: 1px solid #c0c0c0">
              <li><span class="glyphicon glyphicon-map-marker"></span> 2016-9-25 21:00： XXX 创建</li>
              <li><span class="glyphicon glyphicon-ok"></span> 2016-9-27 21:00： XXX 同意</li>
              <li><span class="glyphicon glyphicon-sort-by-attributes-alt"></span> XXX 审批中...</li>
              <li><span class="glyphicon glyphicon-shopping-cart"></span> 2016-9-27 21:00： XXX 入库。</li>
              <li><span class=" glyphicon glyphicon-cog"></span> 2016-9-27 21:00： XXX 安装。<a href="javascript:void(0);">查看设备详细信息</a></li>
            </ul>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">审批意见：</label>
            <div class="col-sm-8">
              <label class="radio-inline">
                <input type="radio" name="result" value="正常" checked> 同意
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="正常"> 需修改
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="正常"> 不合格·返厂
              </label><label class="radio-inline">
                <input type="radio" name="result" value="正常"> 合格
              </label>
            </div>
          </div>
        
          <div class="form-group">
            <label class="col-sm-3 control-label">修改意见：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="2" name="inspectInfo"></textarea>
            </div>
          </div>   
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addInspectByName">
            <button type="submit" class="btn btn-danger" id="add">确认</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 删除弹出框 -->
<div class="modal fade"  id="delSpr" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该备件申报吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 删除该条列表下所有的备件申报记录 -->
<div class="modal fade"  id="delAll" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该备件申报表吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 添加新的供应商 -->
<div class="modal fade" id="addSupplier" style="top: 80px">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新的供应商</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">供应商名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="inspectTime" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">联系方式：</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" class="form-control" id="findName" name="devCode">
              </div>
            </div>
          </div>
           
            <div class="form-group">
              <label class="col-sm-3 control-label">供货渠道：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">售后服务情况：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入售后服务基本情况..."></textarea>
              </div>
            </div>   
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInspect">
              <input type="hidden" name="return" value="list"></input>
              <button type="submit" class="btn btn-primary" id="add">确认添加</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- 添加记录不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的巡检记录不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>
<!-- 搜索符合条件的供应商 -->
<div class="modal fade" id="findSupplier">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的供应商</h4>
      </div>
      <div class="modal-body">
         <form class="form-horizontal"> 
            <div class="form-group">
              <label class="col-sm-3 control-label">供应商名称：</label>
              <div class="col-sm-6">
               <input type="text" class="form-control datetime" readonly="readonly">
             </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">其下品牌：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="通过设备品牌来搜索供应商">
            </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">设备型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="通过设备型号来搜索供应商">
            </div>
           </div>

         </form>
      <div class="modal-footer" style="padding-right:40px;">
          <button type="button" class="btn btn-primary">搜索</button>
      </div>
    </div>
  </div>
  </div>
</div>


<!--修改备件申报基本信息-->
<div class="modal fade" id="getSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">备件信息</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">存货编码：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="inspectTime" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">存货名称：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="findName" name="devName">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">规格型号：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="inspecter">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">数量：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="inspecter">
            </div>
          </div>
           <div class="form-group">
            <label class="col-sm-3 control-label">单位：</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="inspecter">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">备注描述：</label>
            <div class="col-sm-8">
              <textarea class="form-control" rows="3" name="inspectInfo"></textarea>
            </div>
          </div>   
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addInspectByName">
            <button type="submit" class="btn btn-primary" id="add">确认修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>

<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script type="text/javascript">
// 审批
function apvSpr(id){
  $("#apvSpr").modal({
    keyboard:true
  });
}

// 删除所有所有申报记录
function delAll(id){
 $('#delAll').modal({
    keyboard: true
 });
}

function buyList(obj,info){
  // $(obj).toggleClass("glyphicon glyphicon-resize-small");
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    var addHtml="<tr class='"+info+"'>"+
                "<th>编号</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>备注描述</th><th></th>"+
                "<th><a class='glyphicon glyphicon-trash' href='javascript:delAll(123);'></a></th> "+
                "</tr>"+
                "<tr class='"+info+"'>"+
                "<td>1</td><td>510740110018</td><td>超声波流量计</td><td>TJZ-100B</td><td>3个</td><td>无</td>"+
                "<td><a href=javascript:getSpr() class='glyphicon glyphicon-edit'></a></td>"+
                "<td><a href=javascript:delSpr() class='glyphicon glyphicon-trash'></a></td>"+
                "<tr/>"+
              "</tr>";
    $rootTr.after(addHtml);
  }else{
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
    $("."+info).detach();
  }
}

  

    $(".close-button").click(function(){
      $(".tree").slideUp();
      $(".sidebar-module").slideDown();
      $(this).slideUp();
    })

   $("#add").click(function(){
     var allow_submit = true;
     $("#addInspect  .form-control").each(function(){
        if($(this).val()==""){
          // alert("hello");
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     });
     return allow_submit;
   });

   function delSpr(id){
      // alert("hello world");
      var $id =id;
      $('#delSpr').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="controller/inspectProcess.php?flag=delInspect&inspectId="+$id;
      });            
    }

    function getSpr(id){ 
      // [inspectId] => 65 [devState] => 备用 [inspecter] => he [inspectInfo] => 测试添加巡检记录 [inspectTime] => 2016-03-21 13:25:00 
      // [devCode] => 201603122 [devName] => 橘子
      var id=id;
      $.get("controller/inspectProcess.php",{
        inspectId:id,
        flag:"getInspect"
      },function(data,success){
         // [{"inspectId":"65","devState":"\u5907\u7528","inspecter":"he","inspectInfo":"\u6d4b\u8bd5\u6dfb\u52a0\u5de1\u68c0\u8bb0\u5f55\r\n","inspectTime":"2016-03-21 13:25:00","devCode":"201603122","devName":"\u6a58\u5b50"}]
         alert(data.inspectId);
        var inspectId=data.inspectId;
        var devState=data.devState;
        var inspecter=data.inspecter;
        var inspectInfo=data.inspectInfo;
        var inspectTime=data.inspectTime;
        var devName=data.devName;
        // alert(inspectTime);
        $('#revInspect input[name="inspectTime"]').val("inspectTime");
        // alert(data)
      },"json");
       $('#getSpr').modal({
        keyboard: true
      });
      
    }

    function devList(){
      $('#devList').modal({
        keyboard: true
      });
    }

   
 



    </script>
  </body>
</html>