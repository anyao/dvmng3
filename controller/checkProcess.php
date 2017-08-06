<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$checkService = new checkService($sqlHelper);
$devService = new devService($sqlHelper);
$userService = new userService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	// if($flag=="checkOne"){ 
	// 	$arr = $_POST;
	// 	unset($arr['flag']);
	// 	// check记录的添加
	// 	$res = $checkService->checkOne($arr);

	// 	// 不合格时做相应调整
	// 	if ($arr['res'] == 2) {
	// 		// 检定结果为维修，① 更改状态；② 添加statusLog记录
	// 		$statusChange = $devService->uptDev(["status" => $arr['status']], $arr['devid']);
	// 		$statusLog = $devService->logStatus($arr['status'], $arr['devid']);
	// 	}else if ($arr['res'] == 3) {
	// 		$classDown = $devService->uptDev(["class" => $arr['class']], $arr['devid']);
	// 	}

	// 	if ($res !== false) 
	// 		header("location: ./../using.php?id=".$arr['devid']);
	// }

	// else
	if ($flag == "noCheck") {
		$chk = $_POST['chk'];
		$devid = $_POST['id'];
		switch ($chk['res']) {
			case 3:
				// 降级
				$devService->uptDev(['class'=>'downClass'], $devid);
				break;
			default:
				// 封存 | 维修
				$chk['downClass'] = "";
				$chk['chgStatus'] = $chk['res'] == 2 ? 8 : 13;
				$chk['devid'] = $devid;
				$devService->uptDev(['status' => $chk['chgStatus']],$devid);
				$devService->logStatus($chk['chgStatus'], $devid);
				break;
		}
		$checkService->addCheck($chk);
		$checkService->setValid($devid, $chk['time']);
		header("location:".$_SERVER['HTTP_REFERER']);
	}

	elseif ($flag == "yesCheck") {
		$id = $_POST['id'];
		$chk = $_POST['chk'];

		$idList = explode(",", substr($id, 0, -1));
		foreach ($idList as $v) {
			$chk['chgStatus'] = 4;
			$chk['devid'] = $v;
			$chk['valid'] = $checkService->getValid($chk['devid']);
			$checkService->addCheck($chk);

			$devService->uptDev(['status' => 4], $chk['devid']);
			$devService->logStatus(4, $chk['devid']);

			$checkService->setValid($v, $chk['time']);
		}
		header("location:".$_SERVER['HTTP_REFERER']);
	}

	else if ($flag == "xlsPlan") {
		$idStr = substr($_GET['devid'], 0, -1);
		$userDpt = $userService->getDpt();
		$arr = $checkService->getXlsPlan($idStr);
		$res = $checkService->listStylePlan($arr, $userDpt);
	}

	else if ($flag == "downXls") {
		$filename = $_GET['filename'];
		$checkService->downXls($filename);
	}

	

	

}
?>