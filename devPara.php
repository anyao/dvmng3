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

<title>在用设备-设备管理系统</title>

<!-- Bootstrap core CSS -->
  <style type="text/css">
    .type-header{
      border-bottom: 1px solid rgba(8,31,52,0.1);
      margin-top: 15px;
    }

    .type-header .glyphicon-plus,.type-header .glyphicon-trash{
      text-decoration: none;
      float: right;
      display: none;
      margin-right: 15px
    }

    .type-header .glyphicon-trash{
      padding-top: 2px;
      margin-right: 30px;
    }

    .type-header:hover .glyphicon-plus,.type-header:hover .glyphicon-trash{
      display: inline !important;
    }

    .btn-link{
      margin:5px 5px 0 5px;
    }

  </style>
  <link rel="stylesheet" type="text/css" href="tp/datetimepicker.css">
  <link rel="stylesheet" href="bootstrap/css/treeview.css">
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
  require_once 'model/devService.class.php';
  $devService=new devService();
  $res=$devService->getType();
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
      <li><a href="dptUser.php">用户管理</a></li>
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
    <div class="page-header">
      <h4>　设备参数设置
        <span class="badge-button" data-toggle="modal" data-target="#devFind"><span class="glyphicon glyphicon-paperclip"></span> 按条件筛选查找</span>　
        <span class="badge-button" id="modalAddType"><span class="glyphicon glyphicon-plus"></span> 添加新的类别</span>
      </h4>
    </div>
  </div>
  <?php
    for ($i=0; $i < count($res); $i++) { 
      $flag=0;
      if (!empty($res[$i]['childrens'])) {
        $flag=1;
      }
      echo "<div class='row'>
            <div class='type-header'>
              <h4>　<span class='glyphicon glyphicon-briefcase'></span> {$res[$i]['name']}
                    <a href=javascript:delPa({$res[$i]['id']},{$flag}) class='glyphicon glyphicon-trash'></a>
                    <a href=javascript:addSon({$res[$i]['id']}) class='glyphicon glyphicon-plus'></a>
              </h4>
            </div>";
      for ($k=0; $k < count($res[$i]['childrens']); $k++) { 
        echo "<div class='col-md-2'>
                <button class='btn btn-link' value='{$res[$i]['childrens'][$k]['id']}' name='{$res[$i]['childrens'][$k]['name']}'>
                  <span class=' glyphicon glyphicon-paperclip'></span> {$res[$i]['childrens'][$k]['name']}
                </button>
              </div>";
      }
      echo "</div>";
    }
  ?>

</div>

<!-- 类别、参数具体信息弹出框  修改设备类别和参数 -->
<div class="modal fade" id="paraInfo" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">设备具体属性参数</h4>
        </div>
        <form class="form-horizontal" action="controller/devProcess.php" method="post" id="updateTypePara">
          <div class="modal-body">
          <div class='form-group' id='updateNew'>
              <label class='col-sm-3 control-label'>新添加：</label>
                <div class='col-sm-7'>
                <div class="input-group">
                  <input type="text" class="form-control" name="devType">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="yesPara"><span class="glyphicon glyphicon-ok"></span></button>
                  </span>
                </div> 
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">类别名称：</label>
              <div class="col-sm-7">
              <input type="text" class="form-control" name="typeName">
              </div>
            </div>
            
            <div class="form-group" id="havePara">
              <label class="col-sm-3 control-label">属性名称：</label>
              <div class="col-sm-7" style="padding-top: 7px;">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="updateTypePara">
            <input type="hidden" name="id">
            <button type="button" class="btn btn-default" id='delType'>删除</button>
            <button type="submit" class="btn btn-primary" id='updateYes'>修改</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
    </div>
  </div>

<!-- 在添加新类别和属性弹出框 -->
<div class="modal fade" id="typeAdd" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">添加新的类别</h4>
        </div>
        <form class="form-horizontal" action="controller/devProcess.php" method="post">
          <div class="modal-body">

            <div class="form-group">
              <label class="col-sm-3 control-label">类别名称：</label>
              <div class="col-sm-7">
              <input type="text" class="form-control" name="typeName">
                <!-- <input type="text" class="form-control" name="name" placeholder="请输入新添加的类别名称"> -->
              </div>
            </div>
            <div class='form-group' id='addNew'>
              <label class='col-sm-3 control-label'>新添加：</label>
                <div class='col-sm-7'>
                <div class="input-group">
                  <input type="text" class="form-control" name="devType">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="yesNew"><span class="glyphicon glyphicon-ok"></span></button>
                  </span>
                </div> 
              </div>
            </div>
            <div class="form-group" id="haveAdd">
              <label class="col-sm-3 control-label">属性名称：</label>
              <div class="col-sm-8" style="padding-top: 7px;">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addType">
            <input type="hidden" name="pid">
            <button type="submit" class="btn btn-primary" id='typeYes'>确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
    </div>
  </div>

<!-- 添加设备信息不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
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

<!-- 在添加新的根设备类型弹出框 -->
<div class="modal fade" id="typePaAdd" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新的根类别</h4>
        </div>
        <form class="form-horizontal" action="controller/devProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">类别名称：</label>
              <div class="col-sm-7">
              <input type="text" class="form-control" name="typeName">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addTypePa">
            <button type="submit" class="btn btn-primary" id='typeYes'>确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
    </div>
  </div>

<!-- 删除设备信息提示框 -->
<div class='modal fade'  id='delConfir'>
  <div class='modal-dialog modal-sm' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>确定要删除该设备类别和属性参数吗？<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="del">删除</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 删除设备信息提示框 -->
<div class='modal fade'  id='delParaCfr'>
  <div class='modal-dialog modal-sm' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close' style='margin-top:-10px;'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        <br/>确定要删除该属性吗？<br/>
             若删除，所有设备的相关内容都将删除！<br/><br/>
      </div>
      <div class='modal-footer'>  
          <button class='btn btn-danger' id="yesDelPara">删除</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 删除父类别失败提示框 -->
<div class="modal fade"  id="failDel" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">删除失败，该类别下存在子类别。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>



<!-- 添加属性弹出框 添加新的属性未完成-->
<div class="modal fade"  id="noAddNew" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
                <div class="loginModal">请先完成当前属性添加。</div><br/>
             </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 搜索 -->
<div class="modal fade" id="devFind" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的备用设备</h4>
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
        <label class="col-sm-3 control-label">所属品牌：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="">
        </div>
      </div>
      
      <div class="form-group">
        <label class="col-sm-3 control-label">设备编号：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="">
        </div>
      </div>
      
    <div class="form-group">
        <label class="col-sm-3 control-label">所在部门：</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" placeholder="">
        </div>
      </div>
      </form>

      </div>
      <div class="modal-footer" style="padding-right:40px;">
        <button type="button" class="btn btn-primary">搜索</button>
        <button  class='btn btn-primary' data-dismiss='modal'>关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 确认修改弹出框 -->
<div class="modal fade"  id="updateCfr">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <br/>确定要修改吗？<br/><br/>
      </div>
      <div class="modal-footer">  
        <button class="btn btn-primary" id="cfrYes">确定</button>
        <button class="btn btn-danger" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>  
<!-- 权限警告 -->
<div class="modal fade"  id="failAuth">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您无权限进行此操作。</div><br/>
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
    <script src="bootstrap/js/bootstrap-suggest.js"></script>
    <script type="text/javascript">

    // 具体设备类别和属性参数修改按钮
    $("#updateYes").click(function(){
      var allow_submit = true;
       if($($("#updateNew input[name=devType]").val().length>0)){
            $('#updateCfr').modal({
                keyboard: true
            });
            allow_submit = false;
            $("#cfrYes").click(function(){
                $("#updateTypePara").submit();
            });
       }else{
            $('#failAdd').modal({
                keyboard: true
            });
            allow_submit=false;
       }

       return allow_submit;
    })

    
    // 删除类别以及属性参数
    $("#delType").click(function(){
     var id=$("#paraInfo input[name=id]").val();
     $("#del").val(id);
     $("#delConfir").modal({
          keyboard: true
     });

      $("#del").click(function(){
        var id=$(this).val();
        $.get("controller/devProcess.php",{
          flag:'getTypeSon',
          id:id
        },function(data,success){
          if (data!=0) {
            $("#failDel").modal({
                keyboard: true
            });
          }else{
              location.href="controller/devProcess.php?flag=delType&id="+id;
          }
        },'text');
    })

    });

  
    // 新类别添加按钮
    $("#paraInfo").on("click","#yesPara",yesPara);
    function yesPara(){
      if($("#updateNew input[name=devType]").val().length>0){
        var addType=$("#updateNew input[name=devType]").val();
        var addHtml="<span class='badge'>"+addType+
                    "  <a href=javascript:void(0) class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a>"+
                    "  <input type='hidden' name='paraNew[]' value="+addType+">"+
                    "</span> ";
        $("#havePara .col-sm-7").append(addHtml);
        $("#updateNew input[name=devType]").val("");
      }else{
        $('#noAddNew').modal({
          keyboard: true
        });
      }  
    }

    // 已确定添加的类别删除
    $("#paraInfo").on("click","#delPara",delDeved)
    function delDeved(){
      $("#yesDelPara").val($(this).val());
       // $(this).parents(".input-group").detach();
       $('#delParaCfr').modal({
          keyboard: true
        });
    }

    $("#yesDelPara").click(function(){
      var id=$(this).val();
      $.get("controller/devProcess.php",{
        flag:"delPara",
        id:id
      },function(data,success){
        if (data=="success") {
           location.href="devPara.php";
        }else{
          alert(data);
        }
      },'text')
    })

    // 修改类型参数和属性
    $(".btn-link").click(function(){
      var typeid=$(this).val();
      var typename=$(this).attr("name");
      $('#paraInfo').modal({
              keyboard: true
          });
      $("#paraInfo .modal-body").attr("id","para"+typeid);
      $("#paraInfo input[name=id]").val(typeid);
      $.get("controller/devProcess.php",{
        flag:'getPara',
        id:typeid
      },function(data,success){
        var $paraInfo=$("#para"+typeid+" #havePara .col-sm-7");
        if($paraInfo.children().length>0){
            $paraInfo.children().remove();
        }
        $("#paraInfo input[name=typeName]").val(typename);
        for (var i = 0; i < data.length; i++) {
          var addHtml="<div class='input-group' style='margin-bottom:15px' >"+
                      "  <input type='text' class='form-control' name='para["+data[i].id  +"]' value="+data[i].name+">"+
                      "  <span class='input-group-btn'>"+
                      "    <button class='btn btn-default' type='button' id='delPara' value='"+data[i].id+"'><span class='glyphicon glyphicon-trash'></span></button>"+
                      "  </span>"+
                      "</div>";
          // var addHtml="<input type="text" class="form-control" name="typeName">"
          // var addHtml="<span class='badge'>"+data[i].name+
          //             "  <a href=javascript:void(0) class='glyphicon glyphicon-pencil' style='color: #f5f5f5;text-decoration: none'></a>"+
          //             "  <a href=javascript:void(0) class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a>"+
          //             "  <input type='hidden' name='para["+data[i].id  +"]' value="+data[i].name+">"+
          //             "</span> ";
          $paraInfo.append(addHtml);
        }
      },'json');
    })

    // 添加新的子类别
    function addSon(id){
      $("#typeAdd input[name=pid]").val(id);
       $('#typeAdd').modal({
              keyboard: true
        });

    }

    // 已确定添加的类别删除
    $("#typeAdd").on("click","#haveAdd .glyphicon-remove",delDevedInNew)
    function delDevedInNew(){
      $(this).parents("span").detach();
    }

    // 新类别添加按钮
    $("#typeAdd").on("click","#yesNew",yesNew);
    function yesNew(){
      if($("#addNew input[name=devType]").val().length>0){
        var addType=$("#addNew input[name=devType]").val();
        var addHtml="<span class='badge'>"+addType+
                    "  <a href=javascript:void(0) class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a>"+
                    "  <input type='hidden' name='para[]' value="+addType+">"+
                    "</span> ";
        $("#haveAdd .col-sm-8").append(addHtml);
        $("#addNew input[name=devType]").val("");
      }else{
        $('#noAddNew').modal({
          keyboard: true
        });
      }  
    }


    // 删除父类别
    function delPa(id,flag){
      if(flag==0){
        location.href="controller/devProcess.php?flag=delTypePa&id="+id;
      }else{
         $('#failDel').modal({
              keyboard: true
          });
      }
    }

    // 添加类别为空时，弹出提示框
    $("#typeYes").click(function(){
       var allow_submit = true;
       if($("#haveAdd input[type=hidden]").val().length=0){
            $('#noAddNew').modal({
                keyboard: true
            });
            allow_submit = false;
       };
       return allow_submit;
    })
    


    
    </script>
    </body>
</html>