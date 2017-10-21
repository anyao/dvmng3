<div class="sidebar-module">
  <ol class="list-unstyled" style="margin-top: 30px">
    <?php if (in_array(7, $_SESSION['funcid']) || $_SESSION['user'] == 'admin'): ?>
      <li><a class="badge" href="buyCheck.php"><span class="glyphicon glyphicon-glass"></span> 备件入厂检定登记 </a></li>
      <li><a class="badge" href="buyCheckAdd.php"><span class="glyphicon glyphicon-log-in"></span> 添加入厂检定记录 </a></li>
    <?php endif ?>
    <li style="height: 10px"></li>
    <li><a class="badge" href="buyCheckHis.php"><span class="glyphicon glyphicon-ok"></span> 已登记备件 </a></li>
    <li><a class="badge" href="javascript:void(0);" data-toggle="modal" data-target="#findCheck"><span class="glyphicon glyphicon-search"></span> 搜索已入厂登记记录 </a></li>
    <li style="height: 10px"></li>
    <li><a class="badge install" href="buyInstall.php"><span class="glyphicon glyphicon-cog"></span> 备件安装验收 </a></li>
    <li><a class="badge" href="buyInstallHis.php"><span class="glyphicon glyphicon-ok"></span> 历史安装记录 </a></li>
    <li><a class="badge" href="javascript:void(0);" data-toggle="modal" data-target="#findInstall"><span class="glyphicon glyphicon-search"></span> 搜索已领取记录 </a></li>
  </ol>
</div>


<!-- 搜索备件检定记录-->
<div class="modal fade" id="findCheck">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索检定记录</h4>
      </div>
      <form class="form-horizontal" action="buyCheckHis.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">检定时间起：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="check_from" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">检定时间止：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="check_to" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">出厂编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="codeManu">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">备件名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">规格型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="spec">
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="findCheck">
            <button class="btn btn-primary yesFind">搜索</button>
          </div>
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="findInstall">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索领取记录</h4>
      </div>
      <form class="form-horizontal" action="buyInstallHis.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-4 control-label">领取时间起：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="data[install_from]" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">领取时间止：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control date" name="data[install_to]" readonly>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">出厂编号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="data[codeManu]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">备件名称：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="data[name]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">规格型号：</label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="data[spec]">
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="flag" value="findInstall">
            <button class="btn btn-primary yesFind">搜索</button>
          </div>
        </form>
    </div>
  </div>
</div>
