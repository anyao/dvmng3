<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$confirmService = new confirmService($sqlHelper);
$userService = new userService($sqlHelper);
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag == "addConfirm") {
		$goto = $_POST['goto'];
		$cfr = $_POST['cfr'];
		$confirmService->addConfirm($cfr);
		switch ($goto) {
			case 'using':
				header("location: ./../using.php?id=".$_POST['devid']); break;
			case 'checkList':
				header("location:./../checkList.php"); break;
		}
	}

	else if ($flag == "xlsConfirm") {
		$idStr = substr($_GET['chkid'], 0, -1);
		$userDpt = $userService->getDpt();
		$arr = $confirmService->getXlsCfr($idStr);
		$confirmService->listStyleConfirm($arr, $userDpt);
	}

	else if ($flag == "xlsUnqual") {
		$chkid = $_GET['chkid'];
		$userDpt = $userService->getDpt();
		$arr = $confirmService->getXlsUnqual($chkid);
		$confirmService->listStyleUnqual($arr, $userDpt);
	}
}