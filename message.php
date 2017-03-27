<div class="row" id="message">
<?php
 $countSee=$repairService->getMisCount();
 // $today=time();
 // $arrNow=$repairService->getMisNow($today);
 // $countNow=count($arrNow);
 if ($countSee!=0) {
   echo "<div class='col-md-12' >
          <div class='alert alert-warning' id='mesSee'>
             <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>
             <strong>您有 <span>$countSee</span> 项新的维修任务！</strong><a href='repMis.php'>点击查看</a>。
          </div>
        </div>";
 }
 // if ($countNow!=0) {
 //   $jsonNow=json_encode($arrNow,JSON_UNESCAPED_UNICODE);
 //   for ($i=0; $i < $countNow; $i++) { 
 //    if($arrNow[$i]['today']!=1){
 //      $time=date("H:i",strtotime($arrNow[$i]['time']));
 //      echo "<div class='col-md-12'>
 //            <div class='alert alert-warning' id='mesToday-{$arrNow[$i]['id']}'>
 //               <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>
 //               <strong>您今天 <span>$time</span> 有维修任务！</strong><a href=javascript:getMis({$arrNow[$i]['id']},'today')>点击查看</a>。
 //            </div>
 //          </div>";
 //    }
 //   }
 // }

?>
</div>

<!-- 查看当前用户具体信息 -->
<div class="modal fade" id="getUserInfo" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">我的基本信息</h4>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-md-3 control-label">用户ID：</label>
              <div class="col-md-7">
                <input type="text" class="form-control" name="id" readonly="readonly">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">用户编号：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="code" readonly="readonly">        
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">用户姓名：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="name">        
              </div>
            </div>
            
             <div class="form-group">
              <label class="col-sm-3 control-label">所在部门：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="depart" readonly="readonly">        
              </div>
            </div>
			
			<div class="form-group">
              <label class="col-sm-3 control-label">当前权限：</label>
              <div class="col-sm-7">
                  <input type="text" class="form-control" name="depart" readonly="readonly">        
              </div>
            </div>
            <div class="form-group">
	           <label class="col-sm-3 control-label">管理权限：</label>
	           <div class="col-sm-7">  
	             <label class="radio-inline">
	               <input type="radio" name="permit" value="0"> 高级用户
	             </label>
	             <label class="radio-inline">
	               <input type="radio" name="permit" value="1"> 普通用户
	             </label>
	           </div>
	         </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" id="yesUptMy">确认修改</button>
              <button class="btn btn-primary" data-dismiss="modal">关闭</button>
            </div>
            </div>
          </form>
      </div>
    </div>
  </div>