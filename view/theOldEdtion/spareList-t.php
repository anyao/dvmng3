<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <link rel="icon" href="img/favicon.ico">

    <title>备品备件-设备管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/treeview.css">
    <link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/treetable.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <?php
  require_once '../model/devService.class.php';
  require_once '../model/paging.class.php';
  require_once '../controller/devProcess.php';

  // $parentId=$_GET['parentId'];
  // echo "$parentId";
  // exit();
  // $paging=new paging();
  // $paging->pageNow=1;
  // $paging->pageSize=17;
  // $paging->gotoUrl="devList.php";
  // if (!empty($_GET['pageNow'])) {
  //   $paging->pageNow=$_GET['pageNow'];
  // }

  // // 得到当前页数
  // $devService=new devService();
  // $parentName=$devService->getParentName($parentId);
  // $devService->getPaging($paging,$parentId);
  // print_r($parentName);
  // exit();
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
            <!-- <div class="lead"> -->
              <a href="#" class="navbar-brand"><p>设备管理系统</p></a>
            <!-- </div> -->
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="homePage.php">首页</a></li>
              <li class="active"><a class="dropdown-toggle" data-toggle="dropdown" href="">设备档案 <span class="caret"></a>
                <ul class="dropdown-menu">
                   <li><a href="usingList.php">在用设备</a></li>
                    <li class="divider">&nbsp;</li>
                   <li><a href="spareList.php">备品备件</a></li>
                  </ul>
              </li>
              <li><a href="inspect.php">日常巡检</a></li>
              <li><a class="dropdown-toggle" data-toggle="dropdown" href="">设备维修 <span class="caret"></a>
                <ul class="dropdown-menu">
                    <li><a href="resMis.php">维修/保养任务</a></li>
                    <li class="divider">&nbsp;</li>
                    <li><a href="resTb.php">维修记录</a></li>
                  </ul>
              </li>
              <li><a href="supplier.php">供应商档案</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                  <form class="navbar-form">
                      <div class="form-group">
                          <input type="text" class="form-control input-md" placeholder="名称/关键字">      
                      </div>        
                      <button type="submit" class="btn btn-default btn-md">搜索</button>
                  </form>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">用户信息 <span class="caret"></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">我的基本信息</a></li>
                    <li><a href="#">更改密码</a></li>
                    <li class="divider">&nbsp;</li>
                    <li><a href="login.php">注销</a></li>
                  </ul>
                </li>
              </ul>          
          </div>
    </div>
  </nav>
<div class="container">
  <div class="row">
    <div class="col-md-3 nav-self">
    <ul class="nav nav-stacked nav-pills">
        <li class="nav-header">设备分类：</li>
        <li class="active"><a href="#">全部</a></li>
        <li><a href="#">电源</a></li>
        <li><a href="#">apple</a></li>
        <li><a href="#">继电器</a></li>
        <li><a href="#">开关</a></li>
        <li><a href="#">电缆</a></li>
        <li><a href="#">电源</a></li>
        <li><a href="#">apple</a></li>
        <li><a href="#">banana</a></li>
        <li><a href="#">grape</a></li>
        <li><a href="#">peach</a></li>
        <li><a href="#">电源</a></li>
        <li><a href="#">apple</a></li>
        <li><a href="#">banana</a></li>
        <li><a href="#">grape</a></li>
        <li><a href="#">peach</a></li><li><a href="#">电源</a></li>
      </ul>
    </div>
      <div class="col-md-9">
          <div class="page-header">
              <h3>&nbsp;&nbsp;<?php echo "$parentName[0]";?>&nbsp;下的设备
              <span class="badge-button" data-toggle="modal" data-target="#devFind"><span class="glyphicon glyphicon-paperclip"></span> 按条件查找</span>　
              <span class="badge-button" data-toggle="modal" data-target="#devAdd"><span class="glyphicon glyphicon-plus"></span> 添加设备</span>
              <span class="badge-button" data-toggle="modal" data-target="#devRepair"><span class="glyphicon glyphicon-transfer"></span> 更换设备</span>
              </h3>
          </div>
          <table class="table table-striped table-hover">
            <thead><tr>
                <th>设备编号</th><th>设备名称</th><th>　</th><th>型号</th><th>品牌</th><th>运行状态</th><th>负责人</th><th>安装时间</th><th>　</th>
              </tr></thead>
            <tbody class="tablebody">  
               <tr>
                    <td><a href=javascript:openChild()>0</a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr>

                <tr>
                    <td><a href=javascript:openChild()>0</a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr>
                <!-- 测试的树形列表根节点 -->
                <tr class="treetable-0">
                    <td><a href=javascript:void(0) class="glyphicon glyphicon-triangle-right" value="123" name="open-child"></a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr>
                <!-- 测试节点的下一个节点 -->
                <tr class="treetable-0">
                    <td><a href=javascript:void(0) class="glyphicon glyphicon-triangle-right" value="123" name="open-child"></a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr>
               
 
                <!-- <tr class="treetable-1">
                    <td><a href=javascript:openChild()>0</a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr>
                <tr class="treetable-1">
                    <td><a href=javascript:openChild()>0</a></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
                               data-toggle='popover' data-placement='top' 
                               data-content='{$row['inspectInfo']}'
                               data-trigger='hover focus'></span></td>
                    <td>5</td>
                    <td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>
                    <td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>
               </tr> -->
    
              <?php
              for ($i=0; $i < count($paging->res_array); $i++) { 
                $row=$paging->res_array[$i];
                // print_r($row);
                // exit();
                // [devId] => 5 [devCode] => 20160312 [devName] => 红富士 [devNo] => red123 [devType] => 水果 [devBrand] => only [parentId] => 1 
                // [depart] => 生产部 [devChar] => you [devTime] => 2010-12-29 [endTime] => [devPrice] => 99800 [supplierName] => 山东 [parentList] => 1-
                // [state] => 正常 
                // echo "<tr><td>{$row['devCode']}</td><td><a href='devList.php?parentId={$row['devId']}'>{$row['devName']}</td>
                //           <td><a class='glyphicon glyphicon-eye-open' data-toggle='modal' href='devDetail.php?devId={$row['devId']}'></span></td>
                //           <td>{$row['devNo']}</td><td>{$row['devBrand']}</td><td>{$row['state']}</td><td>{$row['devChar']}</td><td>{$row['devTime']}</td>
                //           <td><a href=javascript:devDel({$row['devId']}) class='glyphicon glyphicon-trash'></a></td></tr>";
              }
              ?>
            </tbody>
          </table>
          <div id="null_info">
           <div class="null_info_suggest">
              <span class="null_info_add">该设备下暂时没有子设备，可点击添加</span>  
              <span class="badge-button" data-toggle="modal" data-target="#devAdd">
                <span class="glyphicon glyphicon-plus"></span> 添加设备
              </span>
          </div>
          </div>   
          <?php
          echo "<div class='page-count'>$paging->navi</div>";
          ?>
      </div>

</div> 
</div>

<!-- 在该配置柜下添加新设备弹出框 -->
<form class="form-horizontal" action="../controller/devProcess.php" method="post">
  <div class="modal fade" id="devAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: -100px">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">在&nbsp;<?php echo "$parentName[0]";?>&nbsp;下添加新的设备</h4>
        </div>
        <div class="modal-body" id="addInfo">
          <div class="form-group">
            <label class="col-sm-3 control-label">设备编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devCode" placeholder="请输入要添加的设备编号">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">设备名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devName" placeholder="请输入要添加的设备名称">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">安装时间：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control datetime" name="devTime" placeholder="请点击选择安装时间" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">设备型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devNo" placeholder="请输入要添加的设备型号">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">所属类型：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devType" placeholder="请输入设备所属类型">
            </div>
          </div>
      
          <div class="form-group">
            <label class="col-sm-3 control-label">负责人员：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devChar" placeholder="请输入设备的负责人员">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">购入价格：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devPrice" placeholder="请输入设备的购入价格">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">&nbsp;&nbsp;供应商：&nbsp;&nbsp;</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="supplierName" placeholder="请输入设备的供应源">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">设备品牌：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devBrand" placeholder="请输入设备所属品牌">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="depart" placeholder="请输入设备所在部门">
            </div>
          </div>
          
          
        </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addDev"></input>
            <input type="hidden" name="parentId" value="<?php echo $parentId; ?>">
            <button type="submit" class="btn btn-primary" id="add">添加设备</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
      </div>
    </div>
  </div>  
</form> 


<!-- 添加设备信息不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的设备信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 删除设备信息提示框 -->
<div class='modal fade'  id='devDel'>
  <div class='modal-dialog modal-sm' role='document' style='margin-top: 150px'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>确定要删除该设备信息吗？<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="del">删除</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="devFind" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的设备</h4>
      </div>
      <div class="modal-body">

  <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-3 control-label">设备名称：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备名称">
        </div>
      </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">设备型号：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备型号">
        </div>
      </div>
     
      <div class="form-group">
        <label class="col-sm-3 control-label">设备负责人：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备负责人">
        </div>
      </div>
      
      <div class="form-group">
        <label class="col-sm-3 control-label">所在部门：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备所在部门">
        </div>
      </div>
      
    <div class="form-group">
        <label class="col-sm-3 control-label">安装时间：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入要搜索的设备安装时间">
        </div>
      </div>
      </form>

      </div>
      <div class="modal-footer" style="padding-right:40px;">
        <button type="button" class="btn btn-primary">搜索</button>
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
    <script src="bootstrap/js/treetable.js"></script>
    <script type="text/javascript">
      <?php 
      // $page_count=count($paging->res_array);
      ?>
      $(function(){
      var count_page=<?php echo $page_count;?>;
      // alert("hello world");
      if (count_page==0) {
        $("#null_info").show();
      }
    });
    //弹出框
    $(function() 
      { $("[data-toggle='popover']").popover();
    });
     //时间选择器
     $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true
    });
     
     function devDel(id){
      // alert("hello world");
      var $id =id;
      var $parentId=<?php echo $parentId;?>;
      $('#devDel').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/devProcess.php?flag=delDev&devId="+$id+"&parentId="+$parentId;
      });            
    }

    $("#add").click(function(){
     var allow_submit = true;
     $("#addInfo  .form-control").each(function(){
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

    $("#repair").click(function(){
     var allow_submit = true;
     $("#repairInfo  .form-control").each(function(){
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

    <?php
    // $find_name=$_GET['find_name'];
    $info=$devService->findOldCode($parentId);
    ?>

    var testdataBsSuggest = $("#oldDev").bsSuggest({
      allowNoKeyword: false,
        indexKey: 1,
        data: {
          'value':<?php echo $info;?>,
            // 'defaults':'没有相关设备请另查询或添加新的设备'
          }
        }).on('onDataRequestSuccess', function (e, result) {
          console.log('onDataRequestSuccess: ', result);
        }).on('onSetSelectValue', function (e, keyword, data) {
          console.log('onSetSelectValue: ', keyword, data);
        // alert("hello");
        $.get("../controller/devProcess.php",{
          oCode:$("#oldDev").val(),
          flag:"findSon"
        },function(data,success){
          var count=data;
          // alert(count);
          if (count!=0) {
            $(".son-suggest").text("其下有"+count+"个子设备，若更换，则子设备都将停用。");
          }else{
            $(".son-suggest").empty();
          }
          ; 
        },"text");
      }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
      });
    </script>
</html>