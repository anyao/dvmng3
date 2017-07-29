<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
// $checkService = new checkService($sqlHelper);
$devService = new devService($sqlHelper);
// $userService = new userService($sqlHelper);
$repairService = new repairService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag == "addRepair") {
 		// [time] => 2017-07-28 [device] => 设备状况 [repair] => 维护调整情况 [surface] => 维护调整情况 [devid] => 591
		$repair = $_POST['repair'];
		$repairService->addRepair($repair);

		// 转为状态 检定 status=11
		$devService->uptDev(['status' => 11], $repair['devid']);
		$devService->logStatus(11, $repair['devid']);
	}
	header("location:".$_SERVER['HTTP_REFERER']);

}
?>