<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <link rel="icon" href="img/favicon.ico">

    <title>日常巡检-设备管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/treeview.css">
    <link rel="stylesheet" href="tp/datetimepicker.css">
    <style type="text/css">
      /*.child{
        display: none;
      }*/



    </style>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <?php
    require_once '../model/inspectService.class.php';
    require_once '../model/paging.class.php';

    $paging=new paging();
    $paging->pageNow=1;
    $paging->pageSize=18;
    $paging->gotoUrl="devInspect.php";
    if (!empty($_GET['pageNow'])) {
      $paging->pageNow=$_GET['pageNow'];
    }

    $inspectService=new inspectService();
    $inspectService->getPaging($paging);
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
              <li class="active"><a href="devInspect.php">日常巡检</a></li>
              <li><a href="devRepair.php">安装更换</a></li>
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
            <h3>　日常巡检记录
            <span class="badge-button" data-toggle="modal" data-target="#findInspect"><span class=" glyphicon glyphicon-search"></span> 搜索巡检记录 </span>　
            <span class="badge-button" data-toggle="modal" data-target="#addInspect"><span class="glyphicon glyphicon-plus"></span> 添加巡检记录 </span></h3>
        </div>

    <table class="table table-striped table-hover">
            <thead><tr>
                <th>记录编号</th><th>巡检时间</th><th>设备名称</th><th>设备状态</th><th>　</th><th>巡检人</th><th>　</th><th>　</th>
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

                <tr>
                    <td><a href=javascript:openChild() style="text-decoration: none"><span class="glyphicon glyphicon-triangle-right" id="change_icon"></span></a></td>
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
        //                    <td><a href=javascript:inspectDel({$row['inspectId']}) class='glyphicon glyphicon-trash'></a></td>
        //           </tr>";
        //       }
        //       echo "</tbody></table>";
              
        //       echo "<div class='page-count'>$paging->navi</div>"
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
<div class="modal fade"  id="inspectDel" >
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 150px">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要这条记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="del">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 添加新的巡检记录 -->
  <div class="modal fade" id="addInspect">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">添加新的巡检记录</h4>
        </div>
        <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control datetime" name="inspectTime" readonly="readonly">
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
              <label class="col-sm-3 control-label">巡检状态：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="devState" value="正常"> 正常
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="需维修"> 需维修
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="停用" > 停用
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="备用" > 备用
              </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">巡检人员：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter" placeholder="请输入巡检负责人" >
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入巡检基本信息..."></textarea>
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
<!-- 添加巡检记录不完整提示框 -->
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
<!-- 搜索符合条件的巡检记录 -->
<div class="modal fade" id="findInspect">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的巡检记录</h4>
      </div>
      <div class="modal-body">
         <form class="form-horizontal"> 
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检时间：</label>
              <div class="col-sm-6">
               <input type="text" class="form-control datetime" readonly="readonly">
             </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">巡检设备：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="请输入要搜索的设备名称">
            </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">巡检人员：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="请输入要搜索的巡检人员">
            </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="请输入要搜索的设备所在部门">
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
<!-- 修改巡检记录 -->
<div class="modal fade" id="revInspect">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">修改巡检信息</h4>
        </div>
        <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control datetime" name="inspectTime" readonly="readonly">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检设备：</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" id="findName" name="devName">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">巡检状态：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="devState" value="正常"> 正常
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="需维修"> 需维修
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="停用" > 停用
              </label>
              <label class="radio-inline">
                <input type="radio" name="devState"value="备用" > 备用
              </label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">巡检人员：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo"></textarea>
              </div>
            </div>   
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInspectByName">
              <button type="submit" class="btn btn-primary" id="add">确认修改</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
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

   function inspectDel(id){
      // alert("hello world");
      var $id =id;
      $('#inspectDel').modal({
        keyboard: true
      });
      $("#del").click(function() {
        location.href="../controller/inspectProcess.php?flag=delInspect&inspectId="+$id;
      });            
    }

    function inspectRev(id){ 
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
       $('#revInspect').modal({
        keyboard: true
      });
      $

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