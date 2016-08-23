<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <link rel="icon" href="img/favicon.ico">

    <title>配置柜列表-设备管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/treeview.css">
    <link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<?php
  require_once '../model/confiService.class.php';
  require_once '../model/paging.class.php';

  $paging=new paging();
  $paging->pageNow=1;
  $paging->pageSize=18;
  $paging->gotoUrl="confiList.php";
  if (!empty($_GET['pageNow'])) {
    $paging->pageNow=$_GET['pageNow'];
  }

  $confiService=new confiService();
  $confiService->getPaging($paging);
 
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
              <li><a href="confiList.php">设备档案</a></li>
              <li><a href="devInspect-renew.php">日常巡检</a></li>
              <li><a href="devRepair - renew.php">设备维修</a></li>
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
      <div class="col-md-9">
            <div class="page-header">
                <h3>　所有的设备配置柜
                <span class="badge-button" data-toggle="modal" data-target="#devFind"><span class="glyphicon glyphicon-paperclip"></span> 按条件筛选查找</span>　
                <span class="badge-button" data-toggle="modal" data-target="#confiAdd"><span class="glyphicon glyphicon-plus"></span> 添加新的机柜</span></h3>
            </div>
            <table class="table table-striped table-hover">
                    <thead><tr>
                        <th>机柜编号</th><th>配置柜名称</th><th>　</th><th>机柜型号</th><th>机柜负责人</th><th>所属部门</th><th>安装时间</th><th>　</th>
                      </tr></thead>
                    <tbody class="tablebody">       
                        <?php
                        for ($i=0; $i < count($paging->res_array); $i++) { 
                          $row=$paging->res_array[$i];
                          //[devId] => 1            [devCode] => 20160312     [devName] => 苹果   [devNo] => red      [devType] => 水果类别 
                          //[devBrand] => only      [parentId] => 0           [depart] => 生产部  [devChar] => admin  [devTime] => 2015-02-03 
                          //[devPrice] => 5000      [supplierName] =>         [parentList] => 0 
                          // print_r($row);
                          // exit();
                          // devCode,devName,devNo,devChar,depart,devTime
                          echo " <tr><td>{$row['devCode']}</td>
                                <td><a href='devList - treetable.php'>{$row['devName']}</a>
                                <td><a class='glyphicon glyphicon-eye-open' data-toggle='modal' href='devDetail.php?devId={$row['devId']}'></span></td>
                                <td>{$row['devNo']}</td><td>{$row['devChar']}</td><td>{$row['depart']}</td><td>{$row['devTime']}</td>
                                <td><a href=javascript:confiDel({$row['devId']}) class='glyphicon glyphicon-trash'></span></td></tr>";
                        }
                        echo "</tbody></table>";
                        echo "<div class='page-count'>$paging->navi</div>"
                        ?>                  
      </div>
      <div class="col-md-3">
  <div class="tree">
      <ul>
          <li>
              <span> 普阳钢铁有限公司 </span>
              <ul>
                  <li>
                    <span class="badge">机关-办公楼</span>
                      <ul>
                          <li>
                             <a href=""><span>生产部</span></a>
                          </li>
                          <li>
                             <a href=""><span>运输部</span></a>
                          </li>
                      </ul>
                  </li>
                  <li>
                    <span class="badge">制氧厂</span>
                      <ul>
                          <li>
                             <span>一期制氧</span>—<a href="">设备</a> <a href="">电气</a> <a href="">仪器</a>
                          </li>
                          <li>
                             <a href=""><span>二期制氧</span></a>
                          </li>
                          <li>
                             <a href=""><span>三期制氧</span></a>
                          </li>
                          <li>
                             <a href=""><span>四期制氧</span></a>
                          </li>
                          <li>
                             <a href=""><span>五期制氧</span></a>
                          </li>
                      </ul>
                  </li>
                  <li>
                    <span class="badge">25MW余热电厂</span>
                      <ul>
                          <li>
                             <a href=""><span><i class="icon-time"></i> 3.00</span> – Fixed bug caused by...</a>
                          </li>
                          <li>
                             <a href=""><span><i class="icon-time"></i> 3.00</span> – Comitting latest code to Git...</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                    <span class="badge">新区65MW电厂</span>
                      <ul>
                          <li>
                             <a href=""><span><i class="icon-time"></i> 2.00</span> – Create component that...</a>
                          </li>
                      </ul>
                  </li>
              </ul>
          </li>
          <li>
              <span>河北中普(邯郸)有限公司</span>
              <ul>
                  <li>
                    <span class="badge">中普炼钢</span>
                      <ul>
                          <li>
                             <span><i class="icon-time"></i> 7.75</span> – <a href="">Writing documentation...</a>
                          </li>
                          <li>
                             <span><i class="icon-time"></i> 0.25</span> – <a href="">Reverting code back to...</a>
                          </li>
                      </ul>
                  </li>
            </ul>
          </li>
      </ul>
  </div>
      </div> 
  </div>
</div>

<!-- 添加新的机柜 -->
<form class="form-horizontal" action="../controller/devProcess.php?flag=addConfi" method="post">
  <div class="modal fade" id="confiAdd" role="dialog" aria-labelledby="myModalLabel" style="margin-top: -70px">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新的配置柜</h4>
        </div>
        <div class="modal-body" id="addInfo">
          <div class="form-group">
            <label class="col-sm-3 control-label">机柜名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devName" placeholder="请输入机柜名称">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">机柜编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devCode" placeholder="请输入机柜编号">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">机柜型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devNo" placeholder="请输入机柜型号">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">机柜品牌：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devBrand" placeholder="请输入机柜品牌">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">安装时间：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control datetime" name="devTime" placeholder="请点击选择安装时间" readonly="readonly">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">机柜负责人：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devChar" placeholder="请输入机柜负责人">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="depart" placeholder="请输入机柜所属部门">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">设备价格：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="devPrice" placeholder="请输入设备购入价格">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">供应商：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="supplierName" placeholder="请输入设备所购来源">
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="add">确定添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 


<!-- 添加巡检记录不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您需要添加的机柜信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
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

<!-- 删除配置柜提示框 -->
<div class="modal fade"  id="confiDel">
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 150px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要这条机柜记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 删除失败其下有子元素提示框 -->
<div class="modal fade"  id="failDel" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 105px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">删除失败，请删除其下子设备后再操作。</div><br/>
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
    <script>
    //弹出框
    $(function () 
      { $("[data-toggle='popover']").popover();
      });

    //时间选择器
      $(".datetime").datetimepicker({
        format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true
      });

    //树
    $(function () {
      $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
      $('.tree li.parent_li > span').on('click', function (e) {
          var children = $(this).parent('li.parent_li').find(' > ul > li');
          if (children.is(":visible")) {
              children.hide('fast');
              $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
          } else {
              children.show('fast');
              $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
          }
          e.stopPropagation();
      });
    });

    // 添加机柜信息不完整时，弹出提示框
    $("#add").click(function(){
     var allow_submit = true;
     $("#confiAdd  .form-control").each(function(){
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

    // 删除机柜信息弹出框
    function confiDel(id){
      // alert("hello world");
      var $id =id;
      // alert($id);
      $('#confiDel').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/devProcess.php?flag=delConfi&devId="+$id;
      });            
    }

    //要删除的设备下存在子元素提示框
    <?php $wrong=$_GET['wrong'];?>
    var wrong='<?php echo $wrong;?>';
    $(function(){
      if (wrong=="failDel") {
        $('#failDel').modal({
        keyboard: true
      });
      }
    })
    


   </script>

  </body>
</html>