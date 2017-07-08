<?php 
require_once "model/cookie.php";
require_once 'model/devService.class.php';
require_once 'model/dptService.class.php';
require_once 'model/paging.class.php';
checkValidate();
$user = $_SESSION['user'];

$devService = new devService();
$dptService = new dptService();

$paging=new paging();
$paging->pageNow=1;
$paging->pageSize=10;

$paging->gotoUrl="usingList.php";
if (!empty($_GET['pageNow'])) {
  $paging->pageNow=$_GET['pageNow'];
}


if (empty($_REQUEST['flag']) && empty($_GET['fct']) && empty($_GET['dpt'])) {
  $devService->getPaging($paging);
}else if(!empty($_GET['fct'])){
  $idFct=$_GET['fct'];
  $devService->getDevByFct($idFct,$paging);
}else if (!empty($_GET['dpt'])) {
  $idDpt=$_GET['dpt'];
  $devService->getDevByDpt($idDpt,$paging);
}else{
    if(empty($_POST['sector'])){
      $depart='';
    }else{
      $depart=$_POST['sector'];
    }

    if(empty($_POST['office'])){
      $office='';
    }else{
      $office=$_POST['office'];
    }

    if(empty($_POST['factory'])){
      $factory='';
    }else{
      $factory=$_POST['factory'];
    }

    if(empty($_POST['keyword'])){
      $keyword='';
    }else{
      $keyword=$_POST['keyword'];
    }

    if(empty($_POST['devid'])){
      $devid='';
    }else{
      $devid=$_POST['devid'];
    }

    $devService->findDev($depart,$factory,$keyword,$devid,$office,$paging); 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="普阳钢铁设备管理系统">
<meta name="author" content="安瑶">
<link rel="icon" href="img/favicon.ico">
<title>在用设备-设备管理系统</title>
<style type="text/css">
  .col-md-2, #addForm .ztree-row{
    overflow-y: scroll
  }

  .glyphicon-check, .glyphicon-unchecked, .glyphicon-resize-small, .glyphicon-resize-full{
    display:inline !important;
  }

  #takeAll{
    padding-left:0px;
    padding-right: 0px;
    width:5%;
  }
  #takeAll > span{
    display: none;
  }

  .page-header{
    margin-bottom: 0px !important
  }

  .page-header > h4 > span{
    float: right;
    padding-right: 25px
  }

  .glyphicon-plus{
    cursor: pointer;
  }

  #addForm .row{
    padding-left: 10px;
    padding-right: 10px;
    border-bottom: 1px solid #CCCCCC;
  }

  #addForm .input-group{
    margin: 10px 0px;
  }

  div[comp=outComp]{
    display: none;
  }
</style>
<?php include 'buyVendor.php'; ?>
</head>
<body role="document">
<?php include "message.php";?>
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
        <li><a href="<?= (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin') ? "buyCheck.php" : "buyInstall.php"; ?>">备件申报</a></li>
        <li class="dropdown active">
          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">设备档案 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="usingList.php">在用设备</a></li>
            <li><a href="spareList.php">备品备件</a></li>
            <li style="display: <?= (in_array(4, $_SESSION['funcid'])  && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='devPara.php' >属性参数</a></li>
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
        <li style="display: <?=(!in_array(10, $_SESSION['funcid']) && $_SESSION['user'] != 'admin') ? "none" : "inline"?>"><a href='dptUser.php'>用户管理</a></li>
        <li class="dropdown">
        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"><?=$user?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:chgPwd();">更改密码</a></li>
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
          <h4>　所有在用设备
            <span class="glyphicon glyphicon-search"></span>
          </h4>
      </div>
      <table class="table table-striped table-hover">
        <thead><tr>
          <th id="takeAll">
            <span class="glyphicon glyphicon-download-alt"></span> 
            <span class="glyphicon glyphicon-edit" style="margin-left: 5px"></span>
          </th>
          <th>出厂编号</th><th>设备名称</th><th>规格型号</th><th>单位</th>
          <th>所在分厂部门</th><th>状态</th><th>安装地点</th>
          <th><a class="glyphicon glyphicon-plus" href="javascript: addDev('root');" ></a></th>
        </tr></thead>
        <tbody class="tablebody">  
        <?php
          for ($i=0; $i < count($paging->res_array); $i++) { 
            $row=$paging->res_array[$i]; 
            if ($row['unit'] == "套") {
              $status = "<td></td>";
              $leaf = "<td><a href='javascript:void(0);' onclick=\"getLeaf(this, {$row['id']});\" class='glyphicon glyphicon-resize-small'></a></td>";
              $addLeaf = "<td><a class='glyphicon glyphicon-plus' href=\"javascript:addDev({$row['id']})\"></a></td>";
            }else{
              $status = "<td>{$row['status']}</td>";
              $leaf = "<td><span class='glyphicon glyphicon-unchecked chosen' chosen='{$row['id']}'></span></td>";
              $addLeaf = "<td></td>";
            }
            echo "<tr>
              {$leaf}
              <td>{$row['codeManu']}</td>
              <td><a href='using.php?id={$row['id']}'>{$row['name']}</a></td>
              <td>{$row['spec']}</td>
              <td>{$row['unit']}</td>
              <td>{$row['factory']}{$row['depart']}</td>
              {$status}
              <td>{$row['loc']}</td>
              {$addLeaf}
            </tr>";
          }
        ?>  
        </tbody>
      </table>
          <div class='page-count'><?= $paging->navi?></div>                
    </div>
      <div class="col-md-2">
        <div class="row ztree-row">
            <ul id="tree" class="ztree"></ul>
        </div>
      </div> 
  </div>
</div>

<!-- 根设备 -->
<div class="modal fade" id="addForm">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form class="form-horizontal" action="controller/devProcess.php" method="post">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">新设备</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">备件名称</span>
                <input class="form-control" name="name" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">规格型号</span>
                <input class="form-control" name="spec" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">出厂编号</span>
                <input class="form-control" name="codeManu" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">精度等级</span>
                <input class="form-control" name="accuracy" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">运行状态</span>
                <select class="form-control" name="status">
                  <option value="4">在用</option>
                  <option value="5">封存</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">量　　程</span>
                <input class="form-control" name="scale" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">证书结论</span>
                <input class="form-control" name="certi" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位</span>
                <input class="form-control" name="unit" type="text">
              </div> 
              <div class="input-group">
                <span class="input-group-addon">检定单位</span>
                <select class="form-control" name="checkDpt" dpt="checkDpt">
                  <option value="199">计量室</option>
                  <option value="<?= $_SESSION['udptid']?>">使用部门</option>
                  <option value="isOut">外检单位</option>
                </select>
              </div>
              <div class="input-group" comp="outComp">
                <span class="input-group-addon">外检公司</span>
                <input class="form-control" name="outComp" type="text">
              </div> 
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <span class="input-group-addon">检定日期</span>
                <input class="form-control datetime" name="checkNxt" readonly="" type="text">
              </div>  
              <div class="input-group">
                <span class="input-group-addon">有效日期</span>
                <input class="form-control datetime" name="valid" readonly="" type="text">
              </div>
              <div class="input-group">
                <span class="input-group-addon">检定周期</span>
                <input class="form-control" name="circle" value="6" readonly="readonly" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-minus"></span></button>
                  <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>
                  <button class="btn btn-default" type="button">月</button>
                </span>
              </div> 
              <div class="input-group">
                <span class="input-group-addon">溯源方式</span>
                <select class="form-control" name="track">
                  <option value="检定">检定</option>
                  <option value="校准">校准</option>
                  <option value="测试">测试</option>
                </select>
              </div>
            </div>    
          </div>
          <div class="row ztree-row">
            <div class="col-md-4">
              <ul id="tree-py" class="ztree"></ul>
            </div>
            <div class="col-md-4">
              <ul id="tree-zp" class="ztree"></ul>
            </div>
            <div class="col-md-4">
              <ul id="tree-gp" class="ztree"></ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="depart">
          <input type="hidden" name="pid">
          <input type="hidden" name="flag" value="addDev">
          <button class="btn btn-primary" id="yesAdd">确定</button>
      </form> 
    </div>
  </div>
</div>  

<div class="modal fade"  id="failRadio" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">领取部门必须选择唯一。</div><br/>
         </div>
         <div class="modal-footer">  
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<!-- 添加不完整提示框 -->
<div class="modal fade"  id="failAdd">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您需要添加的设备信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<?php include 'devJs.php';?>
<script type="text/javascript">
// 树形部门结构基础配置
var setting = {
    view: {
        selectedMulti: false,
        showIcon: false
    },
    check: {
        enable: true,
        chkStyle:"radio",
        radioType:'all',
    },
    data: {
        simpleData: {
            enable: true
        }
    }
};

var zTree = <?= $dptService->getDptForRole('1,2,3') ?>,
dptTree = {
  py: <?= $dptService->getDptForRole(1) ?>, 
  zp: <?= $dptService->getDptForRole(2) ?>, 
  gp: <?= $dptService->getDptForRole(3) ?>
};

// 外检input框显示
$("#addForm").on('click', 'select[dpt=checkDpt]', function() {
  if ($(this).val() == "isOut") 
    $(this).parents(".input-group").next().css("display", "table");
  else
    $(this).parents(".input-group").next().hide();
    $("div[comp=outComp]");
});

// 检定周期加
$("#addForm .glyphicon-plus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  num++;
  $circle.val(num);
});

// 检定周期减
$("#addForm .glyphicon-minus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  if (num != 1) {
    num--;
    $circle.val(num);
  }
});

// 确认添加
$("#yesAdd").click(function(){
  var allow_submit = true;
  var nodesPy = treePy.getCheckedNodes(true);
  var nodesZp = treeZp.getCheckedNodes(true);
  var nodesGp = treeGp.getCheckedNodes(true);
  var len = nodesPy.length + nodesZp.length + nodesGp.length;
  if (len > 1 || len == 0) {
    allow_submit = false;
    $("#failRadio").modal({
     keyboard:true
    });
  }else
    var nodes = $.extend(nodesPy,nodesZp,nodesGp);
  // 领取部门编号
  $("#addForm input[name=depart]").val(nodes[0].id);
  return allow_submit;
});

// 添加设备
function addDev(path){
  $.fn.zTree.init($("#tree-py"), setting, dptTree.py);
  $.fn.zTree.init($("#tree-zp"), setting, dptTree.zp);
  $.fn.zTree.init($("#tree-gp"), setting, dptTree.gp);
  treePy = $.fn.zTree.getZTreeObj("tree-py");
  treeZp = $.fn.zTree.getZTreeObj("tree-zp");
  treeGp = $.fn.zTree.getZTreeObj("tree-gp");
  $("#addForm .ztree-row").height(0.4 * $(window).height());

  if (path == 'root') 
    $("#addForm input[name=pid]").val(null);
  else
    $("#addForm input[name=pid]").val(path);

  $('#addForm').modal({
    keyboard: true
  });  
}

$(function(){
  $(".col-md-2").height(0.9 * $(window).height());
  $.fn.zTree.init($("#tree"), setting, zTree);
  tree = $.fn.zTree.getZTreeObj("tree");
});

// 多选
$(".tablebody").on("click","span.chosen",function checked(){
    $(this).toggleClass("glyphicon glyphicon-unchecked chosen");
    $(this).toggleClass("glyphicon glyphicon-check chosen");
    var isChosen = $(".glyphicon-check").length;
    if (isChosen != 0) {
      $("#takeAll span").show();
    }else{
      $("#takeAll span").hide();
    }
});

// 成套设备展开
function getLeaf(obj,id){
    var flagIcon=$(obj).attr("class");
    var $rootTr=$(obj).parents("tr");
    // 列表是否未展开
    if (flagIcon=="glyphicon glyphicon-resize-small") {
      // 展开
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
      $.post("controller/devProcess.php",{
        flag: 'getLeaf',
        id: id
      },function(data){
        var addHtml = "";
        for (var i = 0; i < data.length; i++){
          addHtml += 
            "<tr class='open "+data[i].id+" open-"+id+"' style='border: 1px solid #ddd'>"+
            "  <td><span class='glyphicon glyphicon-unchecked chosen' chosen='"+data[i].id+"'></span></td>"+
            "  <td>"+data[i].codeManu+"</td>"+
            "  <td><a href='using.php?id="+data[i].id+"'>"+data[i].name+"</a></td>"+
            "  <td>"+data[i].spec+"</td>"+
            "  <td>"+data[i].unit+"</td>"+
            "  <td>"+data[i].factory+data[i].depart+"</td>"+
            "  <td>"+data[i].status+"</td>"+
            "  <td>"+data[i].loc+"</td><td></td>"+
            "</tr>";
        }
        $rootTr.after(addHtml);
      },'json');
    }else{
      $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
      $(".open-"+id).detach();
    }
}

// 插入根设备弹出框
$("th > .glyphicon-import").click(function(){
  var allow = $.inArray('1',session.funcid);
  if (user == "admin") {
    allow = 0;
  }
  if (allow == -1) {
      $('#failAuth').modal({
        keyboard: true
      });
  }else{
      $('#prtAdd').modal({
        keyboard: true
      });
  }
})

//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});




    // 分厂设备列表
    $(".glyphicon-map-marker").click(function(){
      var $markerPa=$(this).parent();
      var fctid=$markerPa.attr("fct");
      // alert(fctid);
      location.href="usingList.php?fct="+fctid;
    });

    //删除提示框 made it
    $(document).on("click","span.glyphicon-trash",trash);
    function trash(){
      var allow = $.inArray('2',session.funcid);
      if (user == "admin") {
        allow =0;
      }
      var id=$(this).attr("id");
      if (allow == -1) {
            $('#failAuth').modal({
              keyboard: true
            });
        }else{
           $('#devDel').modal({
              keyboard: true
            });
            $("#del").click(function() {
            $.get("controller/devProcess.php",{
              pid:id,
              flag:"findSon"
            },function(data,success){
              var count=data;
              if (count!=0) {
                $('#devDel').modal('hide');
                $('#failDel').modal({
                  keyboard: true
                });
              }else{
                // alert("failure");
                location.href="controller/devProcess.php?flag=delDev&id="+id;
              }
            },"text");   
          });
        }
    }

    // 添加新设备信息弹出框
    $(document).on("click",".tablebody .glyphicon-import,.list-group-item .glyphicon-import",addSon);
    function addSon(){
      var allow = $.inArray('1',session.funcid);
      if (user == "admin") {
        allow =0;
      }
       var $id=$(this).attr("id");
       if (allow == -1) {
            $('#failAuth').modal({
              keyboard: true
            });
        }else{
           $("#cldAdd input[name=pid]").val($id);
           $('#cldAdd').modal({
              keyboard: true
           });
        }
    }


    // 添加父设备信息不完整时，弹出提示框
    $("#addPrt").click(function(){
     var allow_submit = true;
     $("#prtAdd .notNull").each(function(){
        if($(this).val()==""){
          allow_submit = false;
        }
     });

     // 负责人列表为空时，也不可提交
     var forLiable = $("#prtAdd #forLiable input").length;
     if (forLiable == 0) {
        allow_submit = false;
     }

     // 重新选择设备类别
     var idType=$("#prtAdd input[name=class]").attr("data-id");
      if(typeof(idType)=="undefined"||idType==""){
          allow_submit=false;
      }

      if (allow_submit == false) {
        $('#failAdd').modal({
              keyboard: true
          });
      }
      return allow_submit;
    });
    
    // 添加子设备确认添加按钮
    $("#addCld").click(function(){
      // 添加新设备信息不完整时，弹出提示框
      var allow_submit = true;
      $("#cldAdd .notNull").each(function(){
        if ($(this).val()=="") {
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
      }); 
      var idType=$("#cldAdd input[name=class]").attr("data-id");
      if(typeof(idType)=="undefined"||idType==""){
          $('#failParaInfo').modal({
                keyboard: true
          });
          allow_submit=false;
      }
      return allow_submit;
   });


    // 设备根节点打开其下新节点
    $(document).on("click","a[name=openChild]",child_click);
    function child_click(){
      // 获取该设备的id值
      var $id=$(this).attr("value");
      // 获取该设备的tr节点
      var $parent=$(this).parents("tr");
      // 获取下一个设备tr节点的class
      var $nextTr=$parent.next();
      var $parentNext=$nextTr.attr("class");
      var $addHtml="<tr class='child-list'>"+
                      "<td colspan='12' style='padding:0'>"+
                        "<div id='Prt-"+$id+"'></div>"+
                      "</td>"+
                    "</tr>";
      if ($parentNext=="child-list") {
        // 新设备列表显示状态，触发应让其消失
        $(this).removeClass('glyphicon glyphicon-minus')
             .addClass('glyphicon glyphicon-plus');
        $($nextTr).detach();
      }else{
        // 新设备列表未加载状态，触发应让其显示
        $(this).removeClass('glyphicon glyphicon-plus')
             .addClass('glyphicon glyphicon-minus');
        $parent.after($addHtml);
        $.get("controller/devProcess.php",{
          flag:"addSon",
          pid:$id
        },function(data,success){
          var jsonDataTree = transData(eval(data), 'tags', 'pid', 'nodes'); 
          var data=JSON.stringify(jsonDataTree); 
          $('#Prt-'+$id+'').treeview({
            enableLinks: true,
            showBorder: false,
            levels: 1,
            showTags: true,
            data: data
         });
        },"text");    
      }
    }
    


    // 添加子设备时，设备类别搜索建议
    $("#cldAdd input[name=class]").bsSuggest({
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
                    "      <input type='text' class='form-control' name='paraId["+data[i].id+"]'>"+
                    "    </div>"+
                    "  </div>"+
                    "</div>";
         }
         $("#cldPara").empty();
         $("#cldPara").append(addHtml);
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

    

    // 分厂搜索提示，并根据所选调用部门搜索函数
    $("input[name=nameFct]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
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
       var fct=$(this).attr("data-id");
       $(this).parents("form").find("input[name=factory]").val(fct);
       var $depart=$(this).parents("form").find("input[name=nameDepart]"); 
       $.get("controller/devProcess.php",{
        flag:'getDptAll',
        idFct:fct
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


// 添加父设备时，负责人搜索建议
    $("#prtAdd input[name=theLiable]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        indexId:1,
        // indexKey: 1,
        data: {
             'value':<?php 
              $allLiable=$devService->getLiable();
              echo "$allLiable";
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
       var nameLiable = $(this).val();
       var idLiable = $(this).attr("data-id");
       var addHtml="<span class='badge'>"+nameLiable+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='liable[]' value="+idLiable+"></span> "
        $("#prtAdd #forLiable").append(addHtml);
        $(this).val("");
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

     $(document).on("click",".glyphicon-remove",delDeved)
      function delDeved(){
        $(this).parents("span").detach();
      }
   </script>

  </body>
</html>