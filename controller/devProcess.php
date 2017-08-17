<?php
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$devService=new devService($sqlHelper);
$chkService = new checkService($sqlHelper);
$userService = new userService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag=="addDev"){ 
		$arr = $_POST;
		// commonService::dump($arr);
		unset($arr['flag'], $arr['catename']);
		$arr['useTime'] = $arr['status'] == 4 ? "" : $arr['useTime'];
		$arr['path'] = $arr['pid'] != "" ? "-".$arr['pid'] : "";
		$devService->addDev($arr);
		header("location: ./../usingList.php");
	}

	// 修改设备信息 made it
	else if($flag=="uptDev"){
		$arr = $_POST;
		$id = $arr['id'];
		$ostatus = $arr['ostatus'];
		unset($arr['flag'], $arr['take'], $arr['id'], $arr['ostatus']);
		// 更新信息
		$res = $devService->uptDev($arr, $id);

		// 修改时状态改变
		if ($arr['status'] != $ostatus) 
		$devService->logStatus($arr['status'], $id);

		header("location: ./../using.php?id=".$id);
	}

	else if ($flag == "getStatusLog") {
		$id = $_POST['id'];
		$res = $devService->getStatusLogById($id);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

	else if ($flag == 'getLeaf') {
		$pid = $_POST['id'];
		$res = $devService->getLeaf($pid);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

	// 删除设备节点 made it
	else if($flag=="delDev"){
		$id = $_POST['id'];
		$res = $devService->delDevById($id);
		if ($res !== false) 
			header("location:./../usingList.php");
	}

	else if ($flag == "xlsDev") {
		$idStr = substr($_GET['id'], 0, -1);
		$bas = $devService->getXlsDev($idStr); 
		$check = $chkService->getXlsChk($idStr);
		$userDpt = $userService->getDpt();
		$devService->listStyle($bas, $check, $userDpt);
	}


}
?>