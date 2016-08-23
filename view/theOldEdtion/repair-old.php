<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <link rel="icon" href="img/favicon.ico">

    <title>安装/更换记录-设备管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/treeview.css">
    <link rel="stylesheet" href="tp/datetimepicker.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <div class="container">
  <div class="row">
    <div class="span12">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">
              仪表盘
            </h1>
            <div class="row placeholders">
              <div class="col-xs-6 col-sm-3 placeholder">
                <img alt="200x200" class="img-responsive" src="img/u=3168308820,3625267739&fm=21&gp=0.jpg" />
                <h4>
                  标题
                </h4> <span class="text-muted">简短描述</span>
              </div>
              <div class="col-xs-6 col-sm-3 placeholder">
                <img alt="200x200" class="img-responsive" src="glyphicon glyphicon-plus" />
                <h4>
                  标题
                </h4> <span class="text-muted">简短描述</span>
              </div>
            </div>
            <h2 class="sub-header">
              标题部分
            </h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <?php
    require_once '../model/repairService.class.php';
    require_once '../model/paging.class.php';

    // $paging=new paging();
    // $paging->pageNow=1;
    // $paging->pageSize=16;
    // $paging->gotoUrl="devInspect.php";
    // if (!empty($_GET['pageNow'])) {
    //   $paging->pageNow=$_GET['pageNow'];
    // }

    // $repairService=new repairService();
    // $repairService->getPaging($paging);
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
              <li><a href="confiList.php">所有机柜</a></li>
              <li><a href="devInspect.php">日常巡检</a></li>
              <li class="active"><a href="devRepair.php">设备维修</a></li>
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
          <h3>　设备安装/更换记录
          <span class="badge-button" data-toggle="modal" data-target="#findRepair">
            <span class="glyphicon glyphicon-search"></span> 搜索安装/更换记录 
          </span>　
          </h3>
        </div>
        <table class="table table-striped table-hover">
            <thead><tr>
                <th>状态</th><th>记录号</th><th>安装/更换时间</th><th>新设备</th><th>负责人</th><th>旧设备</th><th>更换原因</th><th>　</th>
            </tr></thead>
            <tbody class="tablebody">
               <?php
               
               for ($i=0; $i < count($paging->res_array); $i++) { 
                $row=$paging->res_array[$i];
                // print_r($row);
                // exit();
                // [repairTime] => 2016-03-19 [repairMan] => me [repairInfo] => test 更换设备记录 [repairId] => 4 [old_name] => 大澳洲青苹
                // [new_name] => new_澳洲青苹 
              
                if($row['repairInfo']==""){
                  echo "<tr><td>安装</td>";
                }else{
                  echo "<tr><td>更换</td>";
                }
                echo " <td>{$row['repairId']}</td><td>{$row['repairTime']}</td><td>{$row['new_name']}</td><td>{$row['repairMan']}</td>
                       <td>{$row['old_name']}</td><td>{$row['repairInfo']}</td>
                       <td><span class='glyphicon glyphicon-trash' data-toggle='modal' 
                                 data-target='#delRepair'></span></td>
                      </tr>";
              }
              echo "</tbody></table>";
              echo "<div class='page-count'>$paging->navi</div>"
              ?>
      </div>
      <div class="col-md-3">
            <div class="tree">
              <ul>
                  <li>
                      <span><i class="icon-calendar"></i> 2013, Week 2</span>
                      <ul>
                          <li>
                            <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 7: 8.00 hours</span>
                              <ul>
                                  <li>
                                     <a href=""><span><i class="icon-time"></i> 8.00</span> – Changed CSS to accomodate...</a>
                                  </li>
                              </ul>
                          </li>
                          <li>
                            <span class="badge badge-success"><i class="icon-minus-sign"></i> Tuesday, January 8: 8.00 hours</span>
                              <ul>
                                  <li>
                                     <span><i class="icon-time"></i> 6.00</span> – <a href="">Altered code...</a>
                                  </li>
                                  <li>
                                     <span><i class="icon-time"></i> 2.00</span> – <a href="">Simplified our approach to...</a>
                                  </li>
                              </ul>
                          </li>
                          <li>
                            <span class="badge badge-warning"><i class="icon-minus-sign"></i> Wednesday, January 9: 6.00 hours</span>
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
                            <span class="badge badge-important"><i class="icon-minus-sign"></i> Wednesday, January 9: 4.00 hours</span>
                              <ul>
                                  <li>
                                     <a href=""><span><i class="icon-time"></i> 2.00</span> – Create component that...</a>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <span><i class="icon-calendar"></i> 2013, Week 3</span>
                      <ul>
                          <li>
                            <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 14: 8.00 hours</span>
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

<!-- 删除弹出框 -->
<div class="modal fade"  id="delRepair" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 150px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          确定要删除这条记录吗？
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary">关闭</button>
          <button type="button" class="btn btn-danger">删除</button>
        </div>
    </div>
  </div>
</div>

<!-- 添加新的维修记录 -->
<div class="modal fade" id="addRepair">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新的维修/更换记录</h4>
      </div>
      <div class="modal-body">

  <form class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-3 control-label">维修/更换时间：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control datetime" readonly="readonly">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">维修设备：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入所维修的设备">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label">维修人员：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="请输入本次维修人员的姓名">
        </div>
      </div>

      <div class="form-group">
      <label class="col-sm-3 control-label">基本信息：</label>
      <div class="col-sm-6">
      <textarea class="form-control" rows="3" placeholder="请输入维修的基本信息..."></textarea>
      </div>
      </div>
  </form>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary">修改</button>
        <button type="button" class="btn btn-danger">删除</button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- 搜索符合条件的维修记录 -->
<div class="modal fade" id="findRepair">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的维修/更换记录</h4>
      </div>
      <div class="modal-body">
 <form class="form-horizontal"> 
    <div class="form-group">
      <label class="col-sm-3 control-label">维修时间：</label>
      <div class="col-sm-6">
         <input type="text" class="form-control datetime" readonly="readonly">
      </div>
    </div>

   
    <div class="form-group">
      <label class="col-sm-3 control-label">维修设备：</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" placeholder="请输入要搜索的设备名称">
      </div>
    </div>

   
    <div class="form-group">
      <label class="col-sm-3 control-label">维修人员：</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" placeholder="请输入要搜索的维修人员">
      </div>
    </div>

    </form>
      <div class="modal-footer" style="padding-right:40px;">
        <button type="button" class="btn btn-primary">搜索</button>
      </div>
    </div>
  </div>


<!-- <div class="container">  
    <div class="mastafoot">
        <p>© 河北普阳钢铁有限公司　2016</p>
    </div>
</div> -->


    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>

    <script type="text/javascript">
  $(function () {
    //树形导航
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
    //时间选择器
    $(".datetime").datetimepicker({
      format: 'yyyy-mm-dd hh:ii', language: "zh-CN", autoclose: true
    });

    //巡检基本信息弹出框
     $("[data-toggle='popover']").popover();
});
    </script>
  </body>
</html>