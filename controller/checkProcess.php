<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$checkService = new checkService($sqlHelper);
$devService = new devService($sqlHelper);
$userService = new userService($sqlHelper);

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

		$chk['valid'] = $checkService->getValid($devid);

		$checkService->addCheck($chk);
		$checkService->setValid($devid, $chk['time']);

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