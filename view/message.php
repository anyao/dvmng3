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