<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$safeService=new safeService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag == "addSafe"){
		$data = $_POST['data'];
		$safeService->addSafe($data);
		header("location:".$_SERVER['HTTP_REFERER']);
	}

	else if ($flag == "delSafe"){
		$id = $_POST['id'];
		$res = $safeService->delSafe($id);
		if ($res !== false) 
			header("location:".$_SERVER['HTTP_REFERER']);
	}

	else if ($flag == "uptSafe"){
		$data = $_POST;
		$id = $data['id'];
		unset($data['flag'], $data['id']);
		$safeService->uptSafe($data, $id);
		if ($res !== false) 
			header("location:".$_SERVER['HTTP_REFERER']);
	}
}
?>