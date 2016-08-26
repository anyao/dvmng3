<div class="col-md-2">
       <div class="col-md-3">
    <div class="sidebar-module">
      <h3>Functions</h3>
      <ol class="list-unstyled">
        <li><a class="badge" href="inspStd.php"><span class="glyphicon glyphicon-list-alt"></span> 巡检标准列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addStd"><span class="glyphicon glyphicon-plus"></span> 添加新的巡检标准 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findStd"><span class="glyphicon glyphicon-search"></span> 搜索巡检标准 </a></li>
        <li style="height: 10px"></li>
        <li><a class="badge" href="inspMis.php"><span class="glyphicon glyphicon-list-alt"></span> 巡检任务列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addMis"><span class="glyphicon glyphicon-plus"></span> 分配新的巡检任务 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findMis"><span class="glyphicon glyphicon-search"></span> 搜索巡检任务 </a></li>
        <li style="height: 10px"></li>
         <li><a class="badge" href="inspList.php"><span class="glyphicon glyphicon-list-alt"></span> 巡检记录列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addInfo"><span class="glyphicon glyphicon-plus"></span> 添加新的巡检记录 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findInfo"><span class="glyphicon glyphicon-search"></span> 搜索巡检记录 </a></li>
        <li><a class="badge" id="tree-open"><span class="glyphicon glyphicon-indent-left"></span> 展开时间轴 </a></li>
      </ol>
    </div>
    </div>

<div class="tree" style="display: none">
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

<!-- 时间轴导航收起按钮 -->
  <div class="close-button">
    <a class="badge" ><span class="glyphicon glyphicon-chevron-up"></span> 收起</a>
  </div>



</div>

<!-- 添加新的巡检标准 -->
<div class="modal fade" id="addStd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">新的巡检标准</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
          <label class="col-md-3 control-label">设备名称：</label>
            <div class="col-md-7">
              <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="请搜索要添加巡检标准的设备">
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
            <label class="col-md-3 control-label">点检内容：</label>
            <div class="col-md-7">
              <input type="text" class="form-control" name="iden">
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">具体要求：</label>
            <div class="col-md-7">
              <textarea class="form-control" rows="3" name="info" placeholder="请输入巡检时该设备具体需要注意事项..."></textarea>
            </div>
          </div>  
        </div> 
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addStd">
          <input type="hidden" name="devid">
          <button type="submit" class="btn btn-primary" id="addStdYes">确认添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 搜索符合条件的巡检标准 -->
<div class="modal fade" id="findStd">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">按条件搜索设备巡检标准</h4>
      </div>
      <form class="form-horizontal" method='post' action='inspStd.php'> 
       <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">设备名称：</label>
            <div class="col-sm-6">
             <div class="input-group">
              <input type="text" class="form-control" name="name">
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
            <label class="col-sm-3 control-label">设备编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name='code' placeholder="必须输入正确的设备编号">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name='depart'>
            </div>
          </div>
      </div>
      <div class="modal-footer" style="padding-right:20px;">
        <input type="hidden" name="flag" value="findStd">
        <input type="hidden" name="devid"> 
        <button class="btn btn-primary" id='yesStdFind'> 搜 索 </button>
      </div>
    </form>
  </div>
</div>
</div>

<!-- 添加任务信息-->
<div class="modal fade" id="addMis" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加点检任务</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">点检时间：</label>
            <div class="col-sm-7">
                <input type="text" class="form-control datetime">        
            </div>
            <div class="btn-set">
             <a href="javascript:void(0);" id="yesTime" class='glyphicon glyphicon-ok'></a>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3  control-label">时间列表：</label>
            <div class="col-sm-8" id="forTime">
            </div>
          </div>

          <div class='form-group' >
            <label class='col-sm-3 control-label'>点检设备：</label>
              <div class='col-sm-7'>
            <div class='input-group'>
              <input type='text' class='form-control' name="devName">
              <div class='input-group-btn'>
                <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
                  <span class='caret'></span>
                </button>
                <ul class='dropdown-menu dropdown-menu-right' role='menu'>
                </ul>
              </div>
            </div>
          </div>
            <div class="btn-set">
             <a href="javascript:void(0);" id="yesDev" class='glyphicon glyphicon-ok'></a>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">设备列表：</label>
            <div class="col-sm-8" id="forDev">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addMis">
            <button type="submit" class="btn btn-primary" id="addYes">确认添加</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
          </div>
          </div>
        </form>
    </div>
  </div>
</div>


<!-- 搜索点检任务 -->
<div class="modal fade" id="findMis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">按条件搜索设备巡检任务</h4>
      </div>
      <form class="form-horizontal" method='post' action='inspMis.php'> 
       <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">设备名称：</label>
            <div class="col-sm-7">
             <div class="input-group">
              <input type="text" class="form-control" name="name">
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
            <label class="col-sm-3 control-label">点检时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control datetime" name='time'>
            </div>
          </div>
      </div>
      <div class="modal-footer" style="padding-right:20px;">
        <input type="hidden" name="flag" value="findMis">
        <input type="hidden" name="devid"> 
        <button class="btn btn-primary" id='yesMisFind'> 搜 索 </button>
      </div>
    </form>
  </div>
</div>
</div>

<!-- 添加新的巡检记录 -->
<div class="modal fade" id="addInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加新的巡检记录</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">点检时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control datetime" name="time" readonly="readonly" placeholder="请点击选择时间">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">点检人员：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="liable" placeholder="请搜索点检人员">
            </div>
          </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">点检路线：</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <input type="text" class="form-control" name="inspMis" placeholder="请输入时间、人员或设备等搜索路线">
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
              <label class="col-sm-3 control-label">巡检结果：</label>
              <div class="col-sm-6">
               <label class="radio-inline">
                <input type="radio" name="result" value="正常"> 正常
              </label>
<!--               <label class="radio-inline">
                <input type="radio" name="result" value="保养"> 个别保养
              </label> -->
              <label class="radio-inline">
                <input type="radio" name="result" value="需维修"> 个别需维修
              </label>
              </div>
            </div>
             <div class="form-group" id="haveErr">
               <label class="col-sm-3 control-label">设备列表：</label>
               <div class="col-sm-7" style="padding-top: 7px;">
               </div>
             </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInfo">
              <input type="hidden" name="idList">
              <button type="submit" class="btn btn-primary" id="addYes">确认添加</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

  <!-- 添加个别需维修设备的备注 并添加维修任务 -->
<div class="modal fade" id="repairInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">维修任务添加</h4>
      </div>
      <form class="form-horizontal" id="repairForm">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">故障现象：</label>
            <div class="col-sm-7">
              <textarea class="form-control" rows="2" name="info"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">交付任务给：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="repairMan">
            </div>
          </div> 
          <!-- <div class="form-group">
            <label class="col-sm-3 control-label">实施时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control datetime" name="repairTime">
            </div>
          </div>  -->  
        </div>
        <div class="modal-footer">
          <input type="hidden" name="flag" value="addInfo">
          <button type="button" class="btn btn-primary" id="addRepair">确认添加</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
        </div>
      </form>
      </div>
    </div>
  </div>

  <!-- 搜索符合条件的巡检记录 -->
<div class="modal fade" id="findInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索符合条件的巡检记录</h4>
      </div>
         <form class="form-horizontal" method='post' action='inspList.php'> 
      <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">时间段起始：</label>
              <div class="col-sm-6">
               <input type="text" class="form-control datetime" readonly="readonly" name='begin'>
             </div>
           </div>
           <div class="form-group">
              <label class="col-sm-3 control-label">时间段截止：</label>
              <div class="col-sm-6">
               <input type="text" class="form-control datetime" readonly="readonly" name='end'>
             </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="请输入要搜索的设备所在部门" name='depart'>
            </div>
           </div>  
      </div>
      <div class="modal-footer" style="padding-right:40px;">
        <input type="hidden" name="flag" value="findInfo">
        <button class="btn btn-primary" id='yesInfoFind'>搜索</button>
      </div>
      </form>
  </div>
  </div>
</div>



