<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <link rel="icon" href="img/favicon.ico">

    <title>购置申请-设备管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/treeview.css">
    <link rel="stylesheet" href="tp/datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/supTips.css">
    <style type="text/css">
      thead > tr > th:nth-last-child(1), thead > tr > th:nth-last-child(2){
          width:4%;
      }


    </style>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <?php 
require_once "../model/repairService.class.php";
$repairService=new repairService();
include "message.php";
 ?>
  <?php
    require_once '../model/inspectService.class.php';
    require_once '../model/paging.class.php';

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
            <!-- <div class="lead"> -->
              <a href="#" class="navbar-brand"><p>设备管理系统</p></a>
            <!-- </div> -->
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="homePage.php">首页</a></li>
               <li><a class="dropdown-toggle" data-toggle="dropdown" href="">设备购置 <span class="caret"></a>
                <ul class="dropdown-menu">
                  <li><a href="buyApply.php">购置申请</a></li>
                  <li><a href="spareList.php">购置审批</a></li>
                  <li class="divider">&nbsp;</li>
                  <li><a href="supplier.phpp">供应商档案</a></li>
                </ul>
              </li>
              <li><a class="dropdown-toggle" data-toggle="dropdown" href="">设备档案 <span class="caret"></a>
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
            </ul>

            <ul class="nav navbar-nav navbar-right">
                
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
    <div class="col-md-10">
      <div class="page-header">
        <h3>　我的购置申请列表</h3>
      </div>
    <table class="table table-striped table-hover">
            <thead><tr>
                <th>　</th><th>记录编号</th><th>申请状态</th><th>设备名称</th><th>申请用途原因</th><th>申请日期</th><th>　</th><th>　</th>
              </tr></thead>
            <tbody class="tablebody">
              <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>
             <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>
             <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>
             <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>
             <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>
             <tr>
                <td><a class="glyphicon glyphicon-resize-small" name="listInfo"></a></td>
                <td>abc123</td>
                <td>审批中</td>
                <td>apple</td>
                <td>故宫被誉为世界五大宫之首</td>
                <td>2016-4-12</td>
                <td><a href=javascript:supRev() class='glyphicon glyphicon-edit'></a></td>
                <td><a href=javascript:supDel() class='glyphicon glyphicon-trash'></a></td>
             </tr>

            

            </tbody></table>
                
     <?php
        // for ($i=0; $i < count($paging->res_array); $i++) { 
        //         $row=$paging->res_array[$i];
        //         // print_r($row);
        //         // exit();
        //         $row_json=json_encode($row);
        //         // echo "$row_json";
        //         // exit();
        //         echo " <tr><td>{$row['inspectId']}</td>
        //                    <td>{$row['inspectTime']}</td>
        //                    <td>{$row['devName']}</td>
        //                    <td>{$row['devState']}</td>
        //                    <td><span class='glyphicon glyphicon-triangle-bottom' title='基本描述' 
        //                               data-toggle='popover' data-placement='top' 
        //                               data-content='{$row['inspectInfo']}'
        //                               data-trigger='hover focus'></span></td>
        //                    <td>{$row['inspecter']}</td>
        //                    <td><a href=javascript:inspectRev({$row['inspectId']}) class='glyphicon glyphicon-edit'></a></td>
        //                    <td><a href=javascript:supDel({$row['inspectId']}) class='glyphicon glyphicon-trash'></a></td>
        //           </tr>";
        //       }
        //       echo "</tbody></table>";
              
              echo "<div class='page-count'>$paging->navi</div>"
     ?>
                 
    </div>
    <div class="col-md-2">
       <div class="col-md-3">
    <div class="sidebar-module">
      <h3>Functions</h3>
      <ol class="list-unstyled">
        <li><a class="badge" href="buyApply.php"><span class="glyphicon glyphicon-pencil"></span> 购置申请列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addInspect"><span class="glyphicon glyphicon-plus"></span> 添加新的购置申请 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findInspect"><span class="glyphicon glyphicon-search"></span> 搜索购置申请 </a></li><li style="height: 10px"></li>
        <li><a class="badge" href="##"><span class=" glyphicon glyphicon-sunglasses"></span> 购置审批列表 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-tags"></span> 搜索要审批的申请 </a></li>
      </ol>
    </div>
    </div>

<!-- 时间轴导航收起按钮 -->
  <div class="close-button">
    <a class="badge" ><span class="glyphicon glyphicon-chevron-up"></span> 收起</a>
  </div>
</div>
</div>
</div>
<!-- 删除弹出框 -->
<div class="modal fade"  id="supDel" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 120px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要该条购置申请吗？<br/><br/>
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
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
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

<!-- 添加周期巡检任务 -->
<div class="modal fade" id="addMission">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加周期巡检任务</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">周期巡检时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="" placeholder="请设置周期天数间隔">
                <!-- <span class="input-group-addon">天</span> -->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检设备：</label>
              <div class="col-sm-6">
                <div class="input-group">
                  <input type="text" class="form-control" id="findName" name="devCode">
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
            <div class="form-group">
              <label class="col-sm-3 control-label">任务描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请简要说明该周期巡检任务内容"></textarea>
              </div>
            </div>   
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 添加巡检类型 -->
<div class="modal fade" id="addType">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">添加新的巡检类型</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检类型：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="" placeholder="请添加新的巡检类型名称">
                <!-- <span class="input-group-addon">天</span> -->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">类型描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请简要说明该巡检类型信息..."></textarea>
              </div>
            </div>   
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 在巡检类型下添加新的巡检内容 -->
<div class="modal fade" id="addTypeInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">在巡检类型下添加具体内容项目</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检类型：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" class="form-control" id="findName" name="devCode">
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
            <div class="form-group">
              <label class="col-sm-3 control-label">新的巡检内容：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter" placeholder="请输入新的巡检内容" >
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入该巡检内容所要巡检的基本信息..."></textarea>
              </div>
            </div>     
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>


<!-- 修改供应商信息-->
<div class="modal fade" id="revSup" style="top:120px">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">修改供应商信息</h4>
        </div>
        <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 col-sm-offset-1 control-label">供应商名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspectTime" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 col-sm-offset-1 control-label">联系方式：</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" id="findName" name="devName">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 col-sm-offset-1 control-label">其下品牌：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 col-sm-offset-1 control-label">供货渠道：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 col-sm-offset-1 control-label">售后服务情况：</label>
              <div class="col-sm-6">
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

<!-- 供应商下的设备列表 -->
 <div class="modal fade" id="devList" style="top:120px">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">供应商所供设备</h4>
        </div>
        
          <div class="modal-body" style="height: 300px">
            <table class="table table-striped table-hover">
              <thead><tr>
                <th>设备编号</th><th>设备名称</th><th>　</th><th>设备型号</th><th>设备状态</th><th>运行天数</th>
              </tr></thead>
              <tbody>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
                <tr>
                  <td>123</td>
                  <td>123</td>
                  <td><a href="" class="glyphicon glyphicon-eye-open"></a></td>
                  <td>123</td>
                  <td>123</td>
                  <td>123</td>
                </tr>
              </tbody>
              </table>
              </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">关闭</button>
            </div>

      
    </div>
  </div>
</div>
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="tp/bootstrap-datetimepicker.js"></script>
    <script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    <script src="bootstrap/js/supTips.js"></script>
    <script type="text/javascript">
   

    $("#tree-open").click(
      function () {
        $(".tree").slideDown();
        $(".close-button").slideDown();
        $(".sidebar-module").slideUp();
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

   function supDel(id){
      // alert("hello world");
      var $id =id;
      $('#supDel').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/inspectProcess.php?flag=delInspect&inspectId="+$id;
      });            
    }

    function supRev(id){ 
      // [inspectId] => 65 [devState] => 备用 [inspecter] => he [inspectInfo] => 测试添加巡检记录 [inspectTime] => 2016-03-21 13:25:00 
      // [devCode] => 201603122 [devName] => 橘子
      var id=id;
      $.get("../controller/inspectProcess.php",{
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
       $('#revSup').modal({
        keyboard: true
      });
      
    }

    function devList(){
      $('#devList').modal({
        keyboard: true
      });
    }

   // $("#findName").keyup(function(){
   //    $.get("..",{
   //      find_name: $("#findName").val()
   //    });
   // }); 
 
<?php
// $find_name=$_GET['find_name'];
$inspectService=new inspectService();
$info=$inspectService->findDevName();
?>

var testdataBsSuggest = $("#findName").bsSuggest({
        allowNoKeyword: false,
        // indexKey: 0,
        data: {
            'value':<?php echo $info;?>,
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
  </body>
</html>