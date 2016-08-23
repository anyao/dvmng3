<?php 
require_once "../model/cookie.php";
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

<title>备用设备具体信息-设备管理系统</title>

<!-- Bootstrap core CSS -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="bootstrap/css/printview.css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
</head>
<body role="document">
<?php 
require_once "../model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
<?php
require_once '../model/spareService.class.php';
require_once "../model/devService.class.php";
$devService=new devService();
$id=$_GET['id'];

$spareService=new spareService();
$arr=$spareService->getSprById($id);

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
   <ol class="breadcrumb" style="margin:10px 0px">
      <li><a href="javascript:void(0);">所属父类别</a></li>
      <li class="active">设备具体信息</li>
    </ol>

    <form class="form-horizontal" action="../controller/spareProcess.php" method="post" id="updateForm">
      <div class="row">
        <div class="col-md-2">
          <div class="printview">
            <h5>运行状态</h5>
            <div class="row">
              <div class="col-md-4">
                <span class="glyphicon glyphicon-modal-window" style="cursor:pointer" href="javascript:void(0)"  data-toggle="modal" data-target="#spareUse"></span>
              </div>
              <div class="state col-md-5"><?php echo $arr[0]['state'] ?></div>
            </div>
          </div>

          <div class="printview">
            <h5>原装/拆分/拼装</h5>
            <div class="row">
              <div class="col-md-4"><span class="glyphicon glyphicon-cog"></span></div>
              <div class="col-md-5 state"><?php
                if(!empty($arr[0]['divide'])){
                  echo "拆分";
                }else if(!empty($arr[0]['tgther'])){
                  echo "拼装";
                }else{
                  echo "原装";
                }
              ?></div>
            </div>
          </div>  
        </div>

        <div class="col-md-2">
          <div class="printview printview-second">
            <h5>单价  / 元</h5>
            <div class="row">
              <div class="col-md-4"><span class="glyphicon glyphicon-jpy"></span></div>
              <div class="col-md-5"><?php 
                if (empty( $arr[0]['price'])) {
                  echo "∞";
                }else{
                  echo $arr[0]['price'];
                }
              ?></div>
            </div>
          </div>

          <div class="printview printview-second" > 
            <?php  
              if ($arr[0]['periodVali']=="") {
                $timediff[1]="天";
                $timediff[0]="∞";
              }else{
                $timediff = $spareService->timediff($arr[0]['periodVali']);
              }
            ?>
            <h5>有效期止 / <?php echo $timediff[1]; ?></h5>
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
                <input type="text" class="form-control notNull" name="name" value="<?php echo $arr[0]['name']; ?>" readonly>
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
                <input type="text" name="class" class="form-control notNull" value="<?php echo $arr[0]['class'] ?>" readonly>
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
                            


               <div class="input-group">
                <span class="input-group-addon">所属品牌</span>
                <input type="text" class="form-control" name="brand" value="<?php echo $arr[0]['brand'] ?>" readonly>
              </div>  

              </div>

            </div>         

      </div>
      </div>


<div class="row">
  <div class="col-md-12">
    <div class="accordion">
      <div class="accordion-group">
        <h4 style="margin-left: 20px;margin-top: 20px;margin-bottom: -5px">
        <?php 
          if(count($arr[1])!=0){
            echo "属性参数：";
          } 
        ?>
        </h4> 
        <div class="accordion-body">
          <div class="accordion-inner-spare">
            <div class="row detail-info">
              <?php 
              $addHtml="";
              for ($i=0; $i < count($arr[1]); $i++) { 
                $addHtml.="<div class='col-md-4'>
                          <div class='input-group'>
                            <span class='input-group-addon'>{$arr[1][$i]['name']}</span>
                            <input type='text' class='form-control' name='paraid[{$arr[1][$i]['paraid']}]' value='{$arr[1][$i]['paraval']}' readonly>
                          </div> 
                        </div>";
              }
              echo "$addHtml";
              ?>       
            </div>
          </div>
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
          <h4 style="margin-left: 20px;margin-top: 20px;margin-bottom: -5px">设备具体信息：
          </h4> 
          <div class="accordion-body">
            <div class="accordion-inner-spare">
               <div class="row detail-info">
           <div class="col-md-4">
             <div class="input-group">
                <span class="input-group-addon">安装日期</span>
                <input type="text" class="form-control datetime" name="dateInstall" value="<?php echo $arr[0]['dateInstall']; ?>" readonly>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂日期</span>
                <input type="text" class="form-control datetime" name="dateManu" value="<?php echo $arr[0]['dateManu']; ?>" readonly>
              </div> 

              <div class="form-group">
              <label class="control-label">拆解备注：</label>
                <textarea class="form-control" rows="3" readonly><?php 
                  if(empty($arr[0]['divide'])){
                    echo "未拆解过";
                  }else{
                    $dvd=explode(",",$arr[0]['divide']);
                    for ($i=0; $i < count($dvd)-1; $i++) { 
                      $k=$i+1;
                      echo "第".$k."部分:".$dvd[$i]."\r\n";
                    }
                  }
                ?></textarea>
              </div>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">报废日期</span>
              <input type="text" class="form-control datetime" name="dateEnd" value="<?php echo $arr[0]['dateEnd']; ?>" readonly>
            </div>

            <div class="input-group">
              <span class="input-group-addon">有效期止</span>
              <input type="text" class="form-control datetime" name="periodVali" value="<?php echo $arr[0]['periodVali']; ?>" readonly>
            </div>

            <div class="form-group">
                <label class="control-label">拼装备注：</label>
                  <textarea class="form-control" rows="3" readonly><?php 
                    if(empty($arr[0]['tgther'])){
                      echo "不通过拼装得到";
                    }else{
                      // $liable."-".$time."-".$info."-".$n_id
                      // tgtherliable-2016-07-21-test tgther-194
                      $tgther=explode(",",$arr[0]['tgther']);
                      if (count($tgther)==4) {
                        echo "拼装人：$tgther[0]&nbsp;&nbsp;&nbsp;&nbsp;拼装成品ID：$tgther[3]\r\n时间：$tgther[1]\r\n基本备注：$tgther[2]";
                      }else{
                        echo "拼装人：$tgther[0]&nbsp;&nbsp;&nbsp;&nbsp;时间：$tgther[1]\r\n基本备注：$tgther[2]\r\n";
                        for ($i=3; $i < count($tgther)-1; $i++) { 
                          echo "第{$i}部分：$tgther[$i]\r\n";
                        }
                      }
                      
                    }
                  ?></textarea>
            </div> 
          </div> 
          <div class="col-md-4">
             <div class="input-group">
                <span class="input-group-addon">供应厂商</span>
                <input type="text" class="form-control" name="supplier" value="<?php echo $arr[0]['supplier']; ?>" readonly>
              </div>

              <div class="input-group">
                <span class="input-group-addon">购入价格</span>
                <input type="text" class="form-control" name="price" value="<?php echo $arr[0]['price'] ?>" readonly>
              </div>  
            <div class="row detail-btn">
              <div class="col-md-6" >
                <div class="input-group">
                   <button class="btn btn-default" type="button" id="devDvd"><span class="glyphicon glyphicon-scissors"></span>  拆分该设备</button>
                </div>
                <div class="input-group">
                  <button class="btn btn-default" type="button" id="devTgther"><span class="glyphicon glyphicon-compressed"></span>  拼装该设备</button>
                </div> 
              </div>
              <div class="col-md-6">
                <div class="input-group">
                 <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-link"></span> 说明书 / 图纸</button>
                 </div>
                  <div class="input-group">
                  <input type="hidden" name="depart">
                  <input type="hidden" name="factory">
                   <input type="hidden" name="id" value="<?php echo $arr[0]['id'];?>"/>
                   <input type="hidden" name="flag" value="updateSpare">
                   <button type="button" class="btn btn-primary"  data-toggle='modal' id="reviseInfo">修改设备信息</button>
                </div> 
              </div>
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


</div>
<!-- 启用设备弹出框 -->
<div class="modal fade" id="spareUse">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">启用设备</h4>
      </div>
      <form class="form-horizontal" action="../controller/spareProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">所属父设备：</label>
            <div class="col-sm-7">
              <div class="input-group">
                <input type="text" name="pname" class="form-control">
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
        <div class="modal-footer">
          <input type="hidden" name="flag" value="toUsing">
          <input type="hidden" name="pid">
          <input type="hidden" name="id" value="<?php echo $id?>">
          <button type="submit" class="btn btn-primary" id="add">确认启用</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </form>
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
            <div class="loginModal">您所填的内容不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>


<!-- 确认修改弹出框 -->
<div class="modal fade"  id="confirm">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br/>确定要提交修改吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" id="updateYes">确定</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
    </div>
  </div>
</div> 


</body>
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script type="text/javascript">
// 若拆分拼装过，则不可再拆分拼装
$(function(){
  var dvd="<?php echo $arr[0]['divide'];?>";
  var tgther="<?php echo $arr[0]['tgther']?>";
  if (dvd!="") {
    $("#devDvd").attr("disabled","disabled");
  }

  if (tgther!="") {
    $("#devTgther").attr("disabled","disabled");
  }
});
   //弹出框
   $(function(){
     $("[data-toggle='popover']").popover(); 
   });

  // 修改信息按钮动画效果设置
  $("#reviseInfo").click(function(){
    var $en_input=$("input:not(.datetime, input[name=nameDepart])");
    if($en_input.prop("readonly")){

     $en_input.removeAttr("readonly");
      $(this).attr("value"," 提交修改 ");
      //时间选择器
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
       $.get("../controller/devProcess.php",{
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



      $("input[name=class]").bsSuggest({
          allowNoKeyword: false,
          indexId:1,
          // indexKey: 1,
          // showBtn:false,
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
      }).on('onUnsetSelectValue', function (e) {
          console.log("onUnsetSelectValue");
      });


    }
    else{
      var flag=true;
      $en_input.attr("readonly","");
      $(this).attr("value","修改设备信息");
       $(".notNull").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true
          });
        }
      });
      if (flag) {
       $('#confirm').modal({
         keyboard: true
       });  
      }else{
        return false;
      }
  }
  });
  
  // 确认修改
  $("#updateYes").click(function(){
      $("#updateForm").submit();
  });


  // 拆分设备跳转
  $("#devDvd").click(function(){
    var $id="<?php echo $id;?>";
    location.href="devDvd.php?id="+$id;
  });

  $("#devTgther").click(function(){
    var $id="<?php echo $id;?>";
    var devName="<?php echo "{$arr[0]['name']}";?>"
    location.href="devTgther.php?id="+$id+"&devName="+devName;
  });

  // 启用设备搜索父设备提示
  $("input[name=pname]").bsSuggest({
        allowNoKeyword: false,
        indexId:1,
        indexKey: 0,
        showBtn:false,
        data: {
             'value':
             <?php 
              $allDev=$spareService->getDev();
              echo "$allDev";
             ?>
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
         var pid=$(this).attr("data-id");
        if (typeof(pid)!="undefined") {
          $("input[name=pid]").val(pid);
        }else{
          alert("wrong,please contact the administrator")
        }
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

  
</script>
</html>