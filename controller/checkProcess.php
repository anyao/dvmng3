<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$checkService = new checkService($sqlHelper);
$devService = new devService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag=="checkOne"){ 
		$arr = $_POST;
		unset($arr['flag']);
		// check记录的添加
		$res = $checkService->checkOne($arr);

		// 不合格时做相应调整
		if ($arr['res'] == 2) {
			// 检定结果为维修，① 更改状态；② 添加statusLog记录
			$statusChange = $devService->uptDev(["status" => $arr['status']], $arr['devid']);
			$statusLog = $devService->logStatus($arr['status'], $arr['devid']);
		}else if ($arr['res'] == 3) {
			$classDown = $devService->uptDev(["class" => $arr['class']], $arr['devid']);
		}

		if ($res !== false) 
			header("location: ./../using.php?id=".$arr['devid']);
	}

	elseif ($flag == "passCheck") {
		$arr = $_POST;
		$idList = explode(",", substr($arr['id'], 0, -1));
		foreach ($idList as $v) {
			$arr['devid'] = $v;
			$res[] = $checkService->checkOne($arr);
		}
		if (!in_array(false, $res)) 
			header("location: ./../usingList.php");
	}

}
?>