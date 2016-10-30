<div class="sidebar-module">
  <!-- <h3>导航</h3> -->

  <ol class="list-unstyled" style="margin-top: 30px">
    <li><a class="badge" href="buyApply.php"><span class="glyphicon glyphicon-list-alt"></span> 备件申报列表 </a></li>
    <li><a class="badge" href="buyAdd.php"><span class="glyphicon glyphicon-plus"></span> 添加新的备件申报 </a></li>
    <li><a class="badge"  data-toggle="modal" data-target="#findSpr"><span class="glyphicon glyphicon-search"></span> 搜索备件申报记录 </a></li>
    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','2','Apv')"><span class="glyphicon glyphicon-sunglasses"></span> 备件审核列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','2','ApvHis')"><span class="glyphicon glyphicon-ok"></span> 历史审核 </a></li>
    <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索备件审核记录 </a></li>
    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','3','Check')"><span class="glyphicon glyphicon-glass"></span> 备件入厂检定登记列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','3','CheckHis')"><span class="glyphicon glyphicon-ok"></span> 历史入厂检定 </a></li>
    <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索入厂登记记录 </a></li>

    <li style="height: 10px"></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','4','Store')"><span class="glyphicon glyphicon-tags"></span> 备件入账列表 </a></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','4','StoreHis')"><span class="glyphicon glyphicon-ok"></span> 历史入账 </a></li>
    <li><a class="badge" href="javascript:gotoBuy('gaugeBuy','4','StoreHouse')"><span class="glyphicon glyphicon-briefcase"></span> 备件库存列表 </a></li>
    <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索入账存库记录 </a></li>

    <li style="height: 10px"></li>
    <li><a class="badge" href="buyInstall.php"><span class="glyphicon glyphicon-cog"></span> 备件安装验收列表 </a></li>
    <li><a class="badge" href="buyInstallHis.php"><span class="glyphicon glyphicon-ok"></span> 历史安装记录 </a></li>
    <li><a class="badge"  data-toggle="modal" data-target="#addTypeInfo"><span class="glyphicon glyphicon-search"></span> 搜索安装验收记录 </a></li>
  </ol>
</div>

<!-- 搜索备件申报记录-->
<div class="modal fade" id="findSpr">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">搜索备件申报记录</h4>
      </div>
      <form class="form-horizontal" action="controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">存货编号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="code">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">设备名称：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">规格型号：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="no">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">申报时间：</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="createtime">
              </div>
            </div>
            <?php  
              if ($_SESSION['permit'] < 2) {
                $addHtml = "<div class='form-group'>
                              <label class='col-sm-3 control-label'>申报单位：</label>
                              <div class='col-sm-8'>
                                <input type='text' class='form-control' name='factory'>
                              </div>
                            </div>";
                echo "$addHtml";
              }
            ?>
            
            </div>
            <div class="modal-footer">
              <input type="hidden" name="flag" value="addInspect">
              <input type="hidden" name="return" value="list">
              <button type="submit" class="btn btn-primary" id="add">搜索</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
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
