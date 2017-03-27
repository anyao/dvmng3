<div class="sidebar-module">
  <!-- <h3>导航</h3> -->

  <ol class="list-unstyled" style="margin-top: 30px">
    <li><a class="badge" href="javascript:gotoBuy(14,'buyApply');"><span class="glyphicon glyphicon-list-alt"></span> 备件申报列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(14,'buyAdd');"><span class="glyphicon glyphicon-plus"></span> 添加新的备件申报 </a></li>
    <li><a class="badge" href="javascript:find(14,'Apply')"><span class="glyphicon glyphicon-search"></span> 搜索备件申报记录 </a></li>
    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy(6,'buyApv');"><span class="glyphicon glyphicon-sunglasses"></span> 备件审核列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(6,'buyApvHis');"><span class="glyphicon glyphicon-ok"></span> 历史审核 </a></li>
    <li><a class="badge" href="javascript:find(6,'Apv');"><span class="glyphicon glyphicon-search"></span> 搜索已审核备件记录 </a></li>
    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy(7,'buyCheck');"><span class="glyphicon glyphicon-glass"></span> 备件入厂检定登记列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(7,'buyCheckHis');"><span class="glyphicon glyphicon-ok"></span> 历史入厂检定 </a></li>
    <li><a class="badge" href="javascript:find(7,'Check');"><span class="glyphicon glyphicon-search"></span> 搜索已入厂登记记录 </a></li>

    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy(8,'buyStore');"><span class="glyphicon glyphicon-tags"></span> 备件入账列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(8,'buyStoreHis');"><span class="glyphicon glyphicon-ok"></span> 历史入账 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(14,'buyStoreHouse');"><span class="glyphicon glyphicon-briefcase"></span> 备件库存列表 </a></li>
    <li><a class="badge" href="javascript:find(8,'Store');"><span class="glyphicon glyphicon-search"></span> 搜索存库记录 </a></li>

    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy(14,'buyInstall');"><span class="glyphicon glyphicon-cog"></span> 备件安装验收列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy(14,'buyInstallHis');"><span class="glyphicon glyphicon-ok"></span> 历史安装记录 </a></li>
    <li><a class="badge" href="javascript:find(14,'Install')"><span class="glyphicon glyphicon-search"></span> 搜索已验收备件记录 </a></li>
  </ol>
</div>

<!-- 搜索备件申报记录-->
<div class="modal fade" id="findApply">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索备件申报记录</h4>
      </div>
      <form class="form-horizontal" action="buyApply.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">申报时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control date" name="applyTime" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">申请部门：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" name="nDpt" class="form-control">
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
              <label class="col-sm-4 control-label">备件编号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprCode">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprName">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件型号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprNo">
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="findApply">
              <input type="hidden" name="dptId">
              <button type="submit" class="btn btn-primary yesFind">搜索</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 搜索备件审核记录-->
<div class="modal fade" id="findApv">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索已审核备件记录</h4>
      </div>
      <form class="form-horizontal" action="buyApvHis.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">审核时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control date" name="apvTime" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">申请部门：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" name="nDpt" class="form-control">
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
              <label class="col-sm-4 control-label">备件编号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprCode">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprName">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件型号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprNo">
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="findApv">
              <input type="hidden" name="dptId">
              <button type="submit" class="btn btn-primary yesFind">搜索</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 搜索备件检定记录-->
<div class="modal fade" id="findCheck">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索已检定备件记录</h4>
      </div>
      <form class="form-horizontal" action="buyCheckHis.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">检定时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control date" name="checkTime" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">申请部门：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" name="nDpt" class="form-control">
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
              <label class="col-sm-4 control-label">存货编号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprCode">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprName">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件型号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprNo">
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="findCheck">
              <input type="hidden" name="dptId">
              <button type="submit" class="btn btn-primary yesFind">搜索</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 搜索备件库存记录-->
<div class="modal fade" id="findStore">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索备件库存记录</h4>
      </div>
      <form class="form-horizontal" action="buyStoreHouse.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">入库时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control date" name="storeTime" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">申请部门：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" name="nDpt" class="form-control">
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
              <label class="col-sm-4 control-label">备件编号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprCode">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprName">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件型号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprNo">
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="findStore">
              <input type="hidden" name="dptId">
              <button type="submit" class="btn btn-primary yesFind">搜索</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 搜索备件库存记录-->
<div class="modal fade" id="findInstall">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索备件已验收记录</h4>
      </div>
      <form class="form-horizontal" action="buyInstallHis.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-4 control-label">时间：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control date" name="installTime" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">申请部门：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" name="nDpt" class="form-control">
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
              <label class="col-sm-4 control-label">备件编号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprCode">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件名称：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprName">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">备件型号：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="sprNo">
              </div>
            </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="findInstall">
              <input type="hidden" name="dptId">
              <button type="submit" class="btn btn-primary yesFind">搜索</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
          </form>
      </div>
    </div>
  </div>

<!-- 无权进入 -->
<div class="modal fade"  id="failCheck" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>您无权进入该界面！<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 当前审核转台状态 -->
<div class="modal fade" id="flowInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">当前状态</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
        <div class="modal-body">
          <div>
            <ul style="padding-left: 8%;margin: 20px;">
              
            </ul>
          </div>
        </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="addInspectByName">
            <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 添加记录不完整提示框 -->
<div class="modal fade"  id="failAdd" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您所填的信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 重新选择部门id -->
<div class="modal fade"  id="failDpt" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">请重新搜索并选择部门信息。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>
