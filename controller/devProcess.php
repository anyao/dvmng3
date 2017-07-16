<?php
require_once '../model/devService.class.php';
require_once '../model/checkService.class.php';
require_once '../model/userService.class.php';
header("content-type:text/html;charset=utf-8");
$devService=new devService;
$chkService = new checkService;
$userService = new userService;
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag=="addDev"){ 
		$name = $_POST['name'];
		$spec = $_POST['spec'];
		$codeManu = $_POST['codeManu'];
		$accuracy = $_POST['accuracy'];
		$scale = $_POST['scale'];
		$certi = $_POST['certi'];
		$unit = $_POST['unit'];
		$checkNxt = $_POST['checkNxt'];
		$valid = $_POST['valid'];
		$circle = $_POST['circle'];
		$track = $_POST['track'];
		$takeDpt = $_POST['depart'];
		$class = $_POST['class'];
		
		$category = $_POST['cateid'];

		$pid = $_POST['pid'];

		$checkDpt = $_POST['checkDpt'];
		$outComp = $_POST['outComp'];
		
		$status = $_POST['status'];
		

		$res = $devService->addDev($name, $spec, $codeManu, $accuracy, $status, $scale, $certi, $unit, $checkDpt, $outComp, $checkNxt, $valid, $circle, $track, $takeDpt, $pid, $useTime, $storeTime,$category,$class);

		if ($res !== false) 
			header("location: ./../usingList.php");
	}

	// 修改设备信息 made it
	else if($flag=="uptDev"){
		$arr = $_POST;
		$id = $arr['id'];
		unset($arr['flag'], $arr['take'], $arr['id'], $arr['ostatus']);
		// 更新信息
		$res = $devService->uptDev($arr, $id);

		// 修改时状态改变
		if ($arr['status'] != $arr['ostatus']) 
		 $devService->logStatus($arr['status'], $id);

		if ($res !== false) 
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
		$check = $chkService->trimXls($check);
		$userDpt = $userService->getDpt();
		$devService->listStyle($bas, $check, $userDpt);
	}


}
?>