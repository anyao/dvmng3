<?php 
require_once "model/cookie.php";
require_once 'model/paging.class.php';
require_once 'model/gaugeService.class.php';
require_once "./model/devService.class.php";
checkValidate();
$user=$_SESSION['user'];

$devService = new devService();

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=18;
$paging->gotoUrl="buyInstall.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}

$gaugeService = new gaugeService();
$gaugeService->buyInstall($paging);
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
<title>安装验收-仪表管理</title>
<style type="text/css">
#ifStore .form-group{
  margin-bottom: 20px !important;
}

#ifStore .input-group{
  position: relative;
  left: -25px
}

.ifStore{
  position: relative; 
  left: 19%;
  margin: 20px auto; 
}

.open > th, .open > td{
  background-color:#F0F0F0;
}

th > .glyphicon-trash{
  display:none;
} 

tr:hover > th > .glyphicon-trash {
  display: inline;
}

/*.form-group {
    margin-bottom: 7px !important;
    margin-top: 7px !important;
}*/

#useSpr .modal-body{
  padding-top: 8px !important;
  padding-bottom: 8px !important;
}

#asetPara >.col-md-6 > .form-group{
  margin-top: 5px !important;
  margin-bottom: 5px !important;
}

</style>
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/metroStyle/metroStyle.css">
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->
<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/jquery.ztree.core.js"></script>
<script src="bootstrap/js/jquery.ztree.excheck.min.js"></script>
<script src="bootstrap/js/jquery.ztree.exedit.js"></script>
</head>
<body role="document">
<?php   include "message.php";?>
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
            <li><a href="javascript:chgPwd();">更改密码</a></li>
            <li class="divider">&nbsp;</li>
            <li><a href="login.php">注销</a></li>
          </ul>
          </li>
      </ul>

    </div><!--/.nav-collapse -->
  </div>
</nav>

<!-- 备件是否存入小仓库 -->
<div class="modal fade" id="spareSpr">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">备件备用</h4>
      </div>
      <form class="form-horizontal" id="spareForm">
        <div class="modal-body">
          <div class="row">
            <div class="ifStore">
              <b>是否需要存入备用仓库？</b>
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="spareSpr">
            <input type="hidden" name="id">
            <button class="btn btn-primary" id="yesSpare" type="button">备用</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 添加新设备弹出框 -->
<form class="form-horizontal" method="post" id="useForm">
  <div class="modal fade" id="useSpr" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">安装验收新仪表</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="para[94]">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-3 control-label">确认日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="para[92]" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">新增时间：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="para[89]" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">测量装置：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="para[90]">
                </div>
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-3 control-label">管理类别：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="A" checked> A
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="B"> B
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="C"> C
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">使用用途：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="质检" checked> 质检
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="经营"> 经营
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="控制"> 控制
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="安全"> 安全
                    </label><br/>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="环保"> 环保
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="能源"> 能源
                    </label>
                  </div>
                </div>

               <div class="form-group">
                  <label class="col-sm-3 control-label">使用数量：</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="number" value="1" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">确认结论：</label>
                  <div class="col-sm-8">
                    <textarea type="text" class="form-control" name="para[93]"></textarea>
                  </div>
                </div>
            </div>
            </div>

        </div>
        <div class="modal-footer">
          <input type="hidden" name="id">
          <input type="hidden" name="flag" value="useSpr">
          <button type="button" class="btn btn-primary yesUse">确定添加</button>
          <button class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 

<!-- 添加新设备(成套)弹出框 -->
<form class="form-horizontal" method="post" id="asetForm">
  <div class="modal fade" id="useAset" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">安装验收新仪表</h4>
        </div>
        <div class="modal-body">
        <div class="row" >
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="para[94]">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>
              <div class="form-group">
               <label class="col-sm-3 control-label">确认日期：</label>
               <div class="col-sm-8">
                 <input type="text" class="form-control datetime" name="para[92]" readonly>
               </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">新增时间：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="para[89]" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">测量装置：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="para[90]">
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-3 control-label">管理类别：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="A" checked> A
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="B"> B
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[88]" value="C"> C
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">使用用途：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="质检" checked> 质检
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="经营"> 经营
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="控制"> 控制
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="安全"> 安全
                    </label><br/>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="环保"> 环保
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="para[91]" value="能源"> 能源
                    </label>
                  </div>
                </div>

               <div class="form-group">
                  <label class="col-sm-3 control-label">使用数量：</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="number" value="1" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">确认结论：</label>
                  <div class="col-sm-8">
                    <textarea type="text" class="form-control" name="para[93]"></textarea>
                  </div>
                </div>
            </div>
          </div>
            <div class="row">
              <div class="col-md-6">
              <span class="glyphicon glyphicon-plus" id="addLeaf" style='margin-left: 20px;cursor: pointer'></span>
                <ul id="tree" class="ztree" style='margin-left: 10px;'></ul>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-3 control-label">基本描述：</label>
                  <div class="col-sm-8">
                    <textarea class="form-control" rows="8" id="asetInfo" readonly></textarea>
                  </div>
                </div> 
              </div>
            </div>
        </div>
        <div id="asetPara"></div>
        <div class="modal-footer">
          <input type="hidden" name="id">
          <input type="hidden" name="flag" value="useAset">
          <button type="button" class="btn btn-primary yesUse" >确定添加</button>
          <button class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 

<!-- 成套设备下添加子设备 -->
<form class="form-horizontal">
  <div class="modal fade" id="asetSon" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">安装验收新仪表</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-sm-3 control-label">安装地点：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="94">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">规格型号：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="no">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">安装日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="dateInstall" placeholder="请选择安装日期(不可为空)" readonly>
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-3 control-label">确认日期：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="92" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">新增时间：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control datetime" name="89" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">测量装置：</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="90">
                </div>
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-sm-3 control-label">管理类别：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="88" value="A" checked> A
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="88" value="B"> B
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="88" value="C"> C
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">使用用途：</label>
                  <div class="col-sm-8">
                    <label class="radio-inline">
                      <input type="radio" name="91" value="质检" checked> 质检
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="91" value="经营"> 经营
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="91" value="控制"> 控制
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="91" value="安全"> 安全
                    </label><br/>
                    <label class="radio-inline">
                      <input type="radio" name="91" value="环保"> 环保
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="91" value="能源"> 能源
                    </label>
                  </div>
                </div>

               <div class="form-group">
                  <label class="col-sm-3 control-label">使用数量：</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="number" value="1" readonly>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">确认结论：</label>
                  <div class="col-sm-8">
                    <textarea type="text" class="form-control" name="93"></textarea>
                  </div>
                </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">所属类别：</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                        <input type="text" name="class" class="form-control notNull" placeholder="请搜索要设备类别">
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
                </div>
                <div class="row">
                  <div id="sonPara"></div>
                  
                </div>
            
            </div>

        </div>
        <div class="modal-footer">
          <input type="hidden" name="tid">
          <input type="hidden" name="name">
          <button type="button" class="btn btn-primary yesAsetSon">确定添加</button>
          <button class="btn btn-default" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>  
</form> 

<div class="container">
  <div class="row">
  <div class="col-md-10">
    <div class="page-header">
        <h4>　仪表备件安装验收</h4>
    </div>
    <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th style="width:4%"></th>
            <th>领取时间</th><th>出厂编号</th><th>存货名称</th><th>存货编码</th><th>规格型号</th>
            <th>领取人</th><th>领取部门</th><th style="width:4%"></th><th style="width:4%"></th>
          </tr>
        </thead>
        <tbody class="tablebody">
        <?php 
          if (count($paging->res_array) == 0) {
            echo "<tr><td colspan=12>当前无新备件需要安装验收</td></tr>";
          }
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row = $paging->res_array[$i];
            $addHtml = 
            "<tr>
                <td><a class='glyphicon glyphicon-resize-small' href='javascript:void(0)' onclick='storeInfo(this,{$row['id']});'></a></td>
                <td>{$row['takeTime']}</td>
                <td>{$row['codeManu']}</td>
                <td><a href='javascript:flowInfo({$row['sprid']})'>{$row['name']}</td>
                <td>{$row['code']}</td>
                <td>{$row['no']}</td>
                <td>{$row['takeUser']}</td>
                <td>{$row['factory']}{$row['depart']}</td>
                <td><a class='glyphicon glyphicon-briefcase' href='javascript:spareSpr({$row['id']});'></a></td>
                <td><a class='glyphicon glyphicon-cog' href='javascript:useSpr({$row['id']},\"{$row['name']}\");'></a></td>
             </tr>";
             echo "$addHtml";

          }
        ?>
        </tbody>
        </table>
        <div class='page-count'><?php echo $paging->navi?></div>                    
    </div>
    <div class="col-md-2">
    <div class="col-md-3">
    <?php  include "buyNavi.php";?>
    </div>
    </div>
</div>
</div>

<?php  include "./buyJs.php";?>
<script type="text/javascript">
var setting = {
  edit: {
    enable: true,
    showRemoveBtn: setRemoveBtn,
    showRenameBtn: false
  },
  data: {
    keep: {
      parent:true,
      leaf:true
    },
    simpleData: {
      enable: true
    }
  },
  callback: {
    onClick: zTreeOnClick,
    beforeRemove: zTreeBeforeRemove
  },
  view: {
    showIcon: false
  } 
};

// 添加子设备时，设备类别搜索建议
$("#asetSon input[name=class]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:1,
    // indexKey: 1,
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
    var idType=$(this).attr("data-id");
    $.get("controller/devProcess.php",{
      flag:'getPara',
      id:idType
    },function(data,success){
     var addHtml="";
     for (var i = 0; i < data.length; i++) {
        addHtml+="<div class='col-md-6'>"+
                "  <div class='form-group'>"+
                "    <label class='col-sm-3 control-label'>"+data[i].name+"：</label>"+
                "    <div class='col-sm-8'>"+
                "      <input type='text' class='form-control' name='"+data[i].id+"'>"+
                "    </div>"+
                "  </div>"+
                "</div>";
     }
     $("#sonPara").empty();
     $("#sonPara").append(addHtml);
    },'json');
     
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

// 添加父设备时，设备类别搜索建议
$("#prtAdd input[name=class]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:1,
    // indexKey: 1,
    data: {
         'value':<?php 
          $allType=$devService->getTypePrt();
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


function spareSpr(id){
  $("#spareSpr input[name=id]").val(id);
  $("#spareSpr").modal({
    keyboard:true
  });
}

$("#yesSpare").click(function(){
   $.post("./controller/gaugeProcess.php",$("#spareForm").serialize(),function(data,success){
      location.href="./spare.php?id="+data;
   },"text");     
});
    

// 展开备件的检定信息 
function storeInfo(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getStoreInfo',
      id:id
    },function(data,success){
      if(data.info){
        var info = '<div class="row">'+
                   '  <div class="col-md-12"><p><b>备注：</b>'+data.info+'</p></div>'+
                   '</div>';
      }
      var addHtml = "<tr class='open-"+id+"'>"+
                    "   <td colspan='12'>"+
                    "     <div class='row'>"+
                    "       <div class='col-md-3'>"+
                    "         <p><b>制造厂：</b> "+data.supplier+" </p>"+
                    "         <p><b>精度等级：</b> "+data.accuracy+" </p>"+
                    "       </div>"+
                    "       <div class='col-md-3'>"+
                    "         <p><b>检定周期：</b> "+data.circle+" </p>"+
                    "         <p><b>溯源方式：</b> "+data.track+" </p>"+
                    "       </div>"+
                    "       <div class='col-md-3'>"+
                    "         <p><b>检定部门：</b> "+data.factory+data.depart+" </p>"+
                    "         <p><b>入库人：</b> "+data.storeUser+" </p>"+
                    "       </div>"+
                    "       <div class='col-md-3'>"+
                    "         <p><b>量程：</b> "+data.scale+" </p>"+
                    "         <p><b>证书结论：</b> "+data.certi+" </p>"+
                    "       </div>"+
                    "     </div>"+info+
                    "   </td>"+
                    " </tr>";
      $rootTr.after(addHtml);
    },'json');
  }else{
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
    $(".open-"+id).detach();
  }
}


// 添加子设备确认添加按钮
$(".yesUse").click(function(){
  // 添加新设备信息不完整时，弹出提示框
  var allow_submit = true;
  var $form = $(this).parents("form")
  $form.find(".form-control").each(function(){
    if ($(this).val()=="") {
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
      return false;
    }
  });
  if (allow_submit == true) 
    $.post("./controller/gaugeProcess.php",$form.serialize(),function(data){
        location.href="./"+data.url+".php?id="+data.devid;
    },'json');
  else
    return allow_submit;
});


//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

function setRemoveBtn(treeId, treeNode) {
  return !treeNode.isParent;
}



// 新设备是否备用
function useSpr(id,name){
  $("#useSpr input[name=id], #useAset input[name=id]").val(id);
  $.get("./controller/gaugeProcess.php",{
    flag:'getChkInfo',
    id:id
  },function(data){
    if (data.unit == "套") {
      newCount = 1;
      $("#asetInfo").empty().append(data.info);
      var zNodes = [{id:id, pId:0, name:name, open:true,isParent:true, }];
      $.fn.zTree.init($("#tree"), setting, zNodes);
      $("#addLeaf").one("click", {isParent:false,id:id}, add);
      $("#asetPara").empty();
      $("#useAset").modal({
        keyboard:true
      });
    }else{
      $("#useSpr").modal({
        keyboard:true
      });
    }
  },'json');
}

var newCount = 1;
function add(e) {
  var zTree = $.fn.zTree.getZTreeObj("tree"),
  isParent = e.data.isParent,
  nodes = zTree.getNodesByParam("id", e.data.id, null),
  treeNode = nodes[0];
  if (treeNode) {
    treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:isParent, name:"子设备" + (newCount++)});
    zTree.editName(treeNode[0]); 
  }
};

function zTreeOnClick(event, treeId, treeNode) {
  if (treeNode.isParent == false) {
    $("#asetSon input[name=tid]").val(treeNode.tId);
    $("#asetSon input[name=name]").val(treeNode.name);
    $("#asetSon input[type=text][name!=number]").val("");
    $("#asetSon").modal({
      keyboard:true
    });
  }
};

$(".yesAsetSon").click(function(){
  var tid = $(this).parents("form").find("input[name=tid]").val();
  var addHtml = "";
  var $input = escape(JSON.stringify($(this).parents("form").find("input[type=text],input[type=radio][checked],input[name=name]").serializeArray()));
  addHtml += '<input type="hidden" tid='+tid+' name="aSet['+tid+']" value="'+$input+'">';
  $("#asetPara").append(addHtml);
  $("#asetSon").modal('hide');
});

function zTreeBeforeRemove(treeId, treeNode){
  var $del = $("#asetPara").find("input[tid="+treeNode.tId+"]");
  if ($del.length != 0) {
    $del.detach();
  }
}

// Nowadays, some organizations and charities publicize their activities by introducing special days every year like National Children's Day and Non-smoking Day. Why do they introduce special days and what effects does this have? 
    </script>
  </body>
</html>