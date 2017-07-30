<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$devService = new devService($sqlHelper);
$userService = new userService($sqlHelper);
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

	else if ($flag == "xlsRep") {
		$idStr = substr($_GET['id'], 0, -1);
		$userDpt = $userService->getDpt();
		$arr = $repairService->getXlsRep($idStr);
		$res = $repairService->listStyle($arr, $userDpt);
	}

	else if ($flag == "delRepair") {
		$id = $_POST['id'];
		$devid = $_POST['devid'];
		// 删除维修记录
		$repairService->delRepair($id);
		// 修改设备状态
		$devService->uptDev(['status'=>8], $devid);
		// 增加status_log记录
		$devService->logStatus(8, $devid);
	}

	header("location:".$_SERVER['HTTP_REFERER']);

}
?>