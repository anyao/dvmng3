<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$checkService = new checkService($sqlHelper);
$devService = new devService($sqlHelper);
$userService = new userService($sqlHelper);
$confirmService = new confirmService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag=="checkOne"){ 
		$devid = $_POST['devid'];
		$chk = $_POST['chk'];
		$chk['devid'] = $devid;

		if ($chk['track'] == '检定') {
			$chk['res'] = $chk['check']['res'];
			switch ($chk['res']) {
				case 3: //检定 降级
					$chk['downAccu'] = $chk['check']['downAccu'];
					$devService->uptDev(['accuracy' => $chk['downAccu']], $devid);
					break;
				default: // 检定 合格、维修、封存
					$chk['chgStatus'] = $checkService->getChgStatus($chk['res']);
					$devService->uptDev(['status' => $chk['chgStatus']], $devid);
					$devService->logStatus($chk['chgStatus'], $devid);	
					break;
			}
		}else{
			$chk['res'] = $chk['correct']['res'];
			$chk['conclu'] = $chk['conclu'];

			$chk['chgStatus'] = 4;
			$devService->uptDev(['status' => $chk['chgStatus']], $devid);
			$devService->logStatus($chk['chgStatus'], $devid);	
		}
		unset($chk['correct'], $chk['check']);


		$checkService->setValid($devid, $chk['time']);
		$chk['valid'] = $checkService->getValid($devid);
		$checkService->addCheck($chk);

		header("location:".$_SERVER['HTTP_REFERER']);
	}

	else if ($flag == "noCheck") {
		$chk = $_POST['chk'];
		$chkid = $_POST['chkid'];

		$checkService->uptCheck($chk, $chkid);
		header("location:".$_SERVER['HTTP_REFERER']);
	}

	elseif ($flag == "yesCheck") {
		$id = $_POST['id'];
		$chk = $_POST['chk'];

		$idList = explode(",", substr($id, 0, -1));
		foreach ($idList as $v) {
			$chk['chgStatus'] = 4;
			$chk['devid'] = $v;
			$checkService->setValid($v, $chk['time']);
			$chk['valid'] = $checkService->getValid($chk['devid']);
			$checkService->addCheck($chk);

			$devService->uptDev(['status' => 4], $chk['devid']);
			$devService->logStatus(4, $chk['devid']);

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

	else if ($flag == "finishMonth") {
		$takeDpt = $_POST['takeDpt'];
		$month = date('Y-m');
		$countPlan = $checkService->getCountPlan($takeDpt, ['first' => $month, 'last' => $month]);

		$day = CommonService::getTheMonth(date("Y-m-d"));
		$countChecked = $checkService->getCountChecked($takeDpt, $day);
		$countConfirm = $confirmService->getCountConfirmed($takeDpt, $day);
		$countPass = $countConfirm;

		$countPlan = $countPlan[$month]?: 0;
		$countChecked = $countChecked[$month] ?: 0;
		$countConfirm = $countConfirm[$month] ?: 0;
		// 计量确认率
		$perConfirm = $checkService->percentAndRound($countConfirm, $countPlan);
		// 设备周检率
		$perChecked = $checkService->percentAndRound($countChecked, $countPlan);
		// 计量确认合格率
		$perPass = $perConfirm;

		$res = [
			'countPlan' => $countPlan,
			'countChecked' => $countChecked,
			'perConfirm' => $perConfirm,
			'perChecked' => $perChecked,
			'perPass' => $perPass
		];
		echo json_encode($res, JSON_UNESCAPED_UNICODE);  
		exit();
	}

	else if ($flag == "finishBefore") {
		$takeDpt = $_POST['takeDpt'];
		$before = $_POST['before'];
		$countPlan = $checkService->getCountPlan($takeDpt, ['first' => $before.'-01', 'last' => $before.'-12']);

		$day = ['first' => $before.'-01-01', 'last' => $before.'-12-31'];
		$countChecked = $checkService->getCountChecked($takeDpt, $day);
		$countConfirm = $confirmService->getCountConfirmed($takeDpt, $day);
		$countPass = $countConfirm;

		$res = $checkService->mergeCount($countPlan, $countChecked, $countConfirm);
		$userDpt = $userService->getDpt();
		$checkService->listStyleFinish($before, $userDpt, $res);
	}

	else if ($flag == "planMonth") {
		$month = $_POST['planMonth'];
		$dpt = $_POST['takeDpt'];
		$data = $checkService->getPlanComming($dpt);
		for ($j=0; $j < count($data); $j++) { 
			$id = $checkService->getValidComming($data[$j], $month);
			if ($id)
				$idArr[] = $id;
		}
		if (count($idArr) == 0) 
			header("location:".$_SERVER['HTTP_REFERER'].'?err=0plan');
		$idStr = implode(",", $idArr);
		$userDpt = $userService->getDpt();
		$arr = $checkService->getXlsPlan($idStr);
		$res = $checkService->listStylePlan($arr, $userDpt);
	}

	else if ($flag == "planYear") {
		// CommonService::dump($_POST);
		$year = $_POST['planYear'];
		$dpt = $_POST['takeDpt'];
		$data = $checkService->getPlanComming($dpt);
		for ($j=0; $j < count($data); $j++) { 
			$id = $checkService->getValidYear($data[$j], $year);
			if ($id)
				$idArr[] = $id;
		}
		if (count($idArr) == 0) 
			header("location:".$_SERVER['HTTP_REFERER'].'?err=0plan');
		$idStr = implode(",", $idArr);
		$userDpt = $userService->getDpt();
		$arr = $checkService->getXlsPlan($idStr);
		$res = $checkService->listStylePlan($arr, $userDpt);
	}

}
?>