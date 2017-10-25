<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$safeCheckService=new safeCheckService($sqlHelper);

if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag == "addCheck"){
		$data = $_POST['chk'];
		$safeCheckService->addCheck($data);
		$safeCheckService->setValid($data['devid'], $data['time']);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
}