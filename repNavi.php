<div class="col-md-2">
       <div class="col-md-3">
    <div class="sidebar-module">
      <h3>Functions</h3>
      <ol class="list-unstyled">
        <li><a class="badge" href="repPlan.php"><span class="glyphicon glyphicon-list-alt"></span> 检修计划列表 </a></li>
        <li><a class="badge" href="repPlan.php"><span class=" glyphicon glyphicon-sunglasses"></span> 审批检修计划 </a></li>
        <li><a class="badge" href="repPlan.php"><span class="glyphicon glyphicon-plus"></span> 添加新的检修计划 </a></li>
        <li><a class="badge" href="repPlan.php"><span class="glyphicon glyphicon-search"></span> 搜索检修计划 </a></li>
        <li style="height: 10px"></li>
         <li><a class="badge" href="repMis.php"><span class="glyphicon glyphicon-list-alt"></span> 维修任务列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addMis"><span class="glyphicon glyphicon-plus"></span> 添加新的维修任务 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findMis"><span class="glyphicon glyphicon-search"></span> 搜索维修任务 </a></li>
        <li style="height: 10px"></li>
         <li><a class="badge" href="repList.php"><span class="glyphicon glyphicon-list-alt"></span> 维修记录列表 </a></li>
        <li><a class="badge" data-toggle="modal" data-target="#addInfo"><span class="glyphicon glyphicon-plus"></span> 添加新的维修记录 </a></li>
        <li><a class="badge"  data-toggle="modal" data-target="#findInfo"><span class="glyphicon glyphicon-search"></span> 搜索维修记录 </a></li>
      </ol>
    </div>
    </div>
</div>

<!-- 添加周期巡检任务 -->
<div class="modal fade" id="addMis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加新的维修任务</h4>
      </div>
      <form class="form-horizontal" action="controller/repairProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障设备：</label>
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
              <label class="col-sm-3 control-label">故障现象：</label>
              <div class="col-sm-7">
                <textarea class="form-control" rows="2" name="err" placeholder="请简要说明该设备故障现象"></textarea>
              </div>
            </div>   
            
            <div class="form-group">
              <label class="col-sm-3 control-label">维修人员：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="liable" placeholder="请输入维修人员">
              </div>
            </div>

            <!-- <div class="form-group">
              <label class="col-sm-3 control-label">维修时间：</label>
              <div class="col-sm-7">
                <input type="text" class="form-control datetime" name="time" placeholder="请选择维修时间" readonly="readonly">
              </div>
            </div> -->
            
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addMis">
                <input type="hidden" name="devid">
                <button type="submit" class="btn btn-primary" id="addYes">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 搜索符合条件的供应商 -->
<div class="modal fade" id="findMis">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索 维修/保养 任务</h4>
      </div>
         <form class="form-horizontal" action="repMis.php" method="post" > 
      <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障设备：</label>
              <div class="col-sm-7">
                <div class="input-group">
                  <input type="text" class="form-control" name="devName">
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

           <!-- <div class="form-group">
            <label class="col-sm-3 control-label">执行时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name='time'>
            </div>
           </div> -->

           <div class="form-group">
            <label class="col-sm-3 control-label">处理结果：</label>
            <div class="col-sm-7">
              <label class="radio-inline">
                <input type="radio" name="result" value="2"> 已处理
              </label>
              <label class="radio-inline">
                <input type="radio" name="result" value="1"> 未处理
              </label>
            </div>
           </div>         
    </div>
      <div class="modal-footer" style="padding-right:20px;">
          <input type="hidden" name="flag" value="findMis">
          <input type="hidden" name="devid">
          <button id="yesFindMis" class="btn btn-primary"> 搜 索 </button>
      </div>
      </form>
  </div>
  </div>
</div>

<div class="modal fade" id="addInfo" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">添加维修记录</h4>
      </div>
      <form class="form-horizontal" action="controller/repairProcess.php" method="post">
        <div class="modal-body">
        <div class="row">
        <div class="col-md-6 add-left">
          <div class="form-group">
            <label class="col-sm-4 control-label">故障设备：</label>
            <div class="col-sm-8">
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
            <label class="col-sm-4 control-label">维修人员：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="liable">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">维修时间：</label>
            <div class="col-sm-8">
                <input type="text" class="form-control datetime" name="time">        
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">故障现象：</label>
            <div class="col-sm-8">
              <textarea class="form-control" name="err" rows="2"></textarea>
            </div>
          </div>
          </div>

          <div class="col-md-6 add-right">
          <div class="form-group">
            <label class="col-sm-3 control-label">故障原因：</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="reason" rows="3"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">解决方案：</label>
            <div class="col-sm-9">
              <textarea class="form-control" name="solve" rows="4"></textarea>
            </div>
          </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addInfo">
            <input type="hidden" name="devid">
            <button class="btn btn-primary" id="addYes">确认添加</button>
            <button class="btn btn-danger" data-dismiss="modal">关闭</button>
          </div>
          </div>
        </form>
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

<!-- 搜索记录 -->
<div class="modal fade" id="findInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索 维修/保养 记录</h4>
      </div>
         <form class="form-horizontal" action="repList.php" method="post"> 
      <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">故障设备：</label>
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
            <label class="col-sm-3 control-label">执行时间：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name='time'>
            </div>
           </div>

           <div class="form-group">
            <label class="col-sm-3 control-label">执行人员：</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" name="liable">
            </div>
           </div>

    </div>
      <div class="modal-footer" style="padding-right:40px;">
        <input type="hidden" name="devid">
        <input type="hidden" name="flag" value="findInfo">
        <button id="yesFindInfo" class="btn btn-primary">搜索</button>
      </div>
  </form>
  </div>
  </div>
</div>
